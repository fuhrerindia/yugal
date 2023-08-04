<?php
require_once './modules/spa.php';
$yugal = new YugalSPA();
function extract_tag($tag, $html)
{
    preg_match("/<$tag>(.*?)<\/$tag>/is", $html, $matches);

    if (isset($matches[1])) {
        $headInnerHTML = $matches[1];
        return $headInnerHTML;
    } else {
        return "";
    }

}
function extract_tag_with_attribute($tag, $value, $html)
{
    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    
    $xpath = new DOMXPath($dom);
    $styleNodes = $xpath->query('//'.$tag.'[@use="'.$value.'"]');
    $innerHtml = '';
    
    foreach ($styleNodes as $styleNode) {
        $innerHtml .= $styleNode->nodeValue;
    }
    
    return $innerHtml;
}
function include_dir_files($dirname, $deepness)
{
    global $yugal;
    $files = scandir($dirname);
    foreach ($files as $file) {
        if (stripos($file, '.php')) {
            $html_code = include_once($dirname . '/' . $file);
            $page_array = [];
            $page_array['head'] = extract_tag('head', $html_code);
            $page_array['body'] = extract_tag('body', $html_code);
            $page_array['fallback'] = extract_tag('fallback', $html_code);
            $page_array['css'] = extract_tag_with_attribute('style', 'external', $html_code);
            $page_array['style'] = extract_tag_with_attribute('style', 'onpage', $html_code);
            $page_array['didmount'] = extract_tag_with_attribute('script', 'didmount', $html_code);
            $page_array['willmount'] = extract_tag_with_attribute('script', 'willmount', $html_code);
            if ($deepness == 0) {
                $page_array['url'] = $file === 'index.php' ? '/' : "/" . str_replace(".php", "", $file);
            } else {
                $page_array['url'] = $file === 'index.php' ? $dirname . '/' : $dirname . "/" . str_replace(".php", "", $file);
            }
            $page_array['url'] = str_replace("./src", "", $page_array['url']);
            $yugal->page($page_array);
        } elseif (is_dir($dirname . "/" . $file) && str_split("@comp")[0] !== "@" && $file !== '.' && $file !== '..') {
            include_dir_files($dirname . "/" . $file, $deepness + 1);
        }
    }
}
function include_component_files($dirname)
{
    global $yugal;
    $files = scandir($dirname);
    foreach ($files as $file) {
        if (strpos($file, '.php')) {
            $component = include_once($dirname . '/' . $file);
            $yugal->makeComponent($component, $file);
        }
    }
}
include_component_files('./src/@comp');
include_dir_files('./src', 0);
$yugal->build();
?>