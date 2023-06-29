<?php
include_once './string.php';
if (DEV_MODE == true) {
    error_reporting(E_ALL);
    ini_set("display_errors", "1");
    $moddir = "./modules";
    $files = scandir($moddir);
    foreach ($files as $file) {
        if (strpos($file, ".yugal.css")) {
            unlink("$moddir/$file");
        }
    }
}
class YugalSPA
{
    private $screens = [];
    private $global_head = "";
    private $js_code_list = [];
    private $external_js = "";
    private $noscript_ = "";
    private $libs = [];
    private $fav = "";
    public $_project_root = "";
    private $thm = "";
    private $global_css_snippet = "";
    private $global_css_code = "";

    private $component_list = [];


    public function removeJsComments($jsCode) {
        $pattern = '/\/\*[\s\S]*?\*\/|([^:]|^)\/\/.*$/m';      
        $cleanCode = preg_replace($pattern, '', $jsCode);
        return $cleanCode;
    }
    private function removeHtmlComments_($html) {
        $pattern = '/<!--(.*?)-->/s';
        $cleanHtml = preg_replace($pattern, '', $html);
        return $cleanHtml;
    }
    public function makeComponent($component, $file){
        $title = str_replace('.php', '', $file);
        // ${yugal.globalComponents[\''.$comp_name.'\']}
        $this->script('yugal.globalComponents["'.$title.'"]=`'.$component.'`;');
        $this->component_list[$title] = $component; 
    }
    private function replacePathWihKeyword($code){
        $root = $this->_project_root;
        return str_replace("{@}", "$root/src", $code);
    }
    public function splitCodeIntoArray($code){
        $code = str_replace(['[{', '}]'], '<comp>', $code);
        $array = explode('<comp>', $code);
        return $array;
    }
    private function isComponent($element){
        $array = str_split($element);
        if (sizeof($array) > 0 && $array[0] === '{' && $array[sizeof($array) - 1] === '}'){
            return true;
        }else{
            return false;
        }
    }
    public function mergeComponentCode($code){
        $list = $this->splitCodeIntoArray($code);
        $new_array = [];
        foreach ($list as $part) {
            if ($this->isComponent($part)){
                $comp_name = str_ireplace(['{', '}'], '', $part);
                $element_to_add = $this->component_list[$comp_name];
                array_push($new_array, $element_to_add);
            }else{
                array_push($new_array, $part);
            }
        }
        return implode('', $new_array);
    }
    private function mergeComponentVariable($code){
        $list = $this->splitCodeIntoArray($code);
        $new_array = [];
        foreach ($list as $part) {
            if ($this->isComponent($part)){
                $comp_name = str_ireplace(['{', '}'], '', $part);
                $element_to_add = '${yugal.globalComponents[\''.$comp_name.'\']}';
                array_push($new_array, $element_to_add);
            }else{
                array_push($new_array, $part);
            }
        }
        return implode('', $new_array);
    }
    public function style($code)
    {
        $code = $this->compress_code($code);
        if ($code != "") {
            $this->global_css_snippet = "<link rel=\"stylesheet\" type=\"text/css\" href=\"{$this->_project_root}/modules/chunk.css\">";
            $this->global_css_code = $this->global_css_code . $code;
        }
    }
    public function add_js($path)
    {
        $this->external_js = $this->external_js . "<script src=\"$path\"></script>";
    }
    public function theme($clr)
    {
        $this->thm = <<<HTML
            <meta name="theme-color" content="{$clr}" >
        HTML;
    }
    public function favicon($path)
    {
        $pth = explode(".", $path);
        $mimetype = $pth[sizeof($pth) - 1];
        $this->fav = "<link rel=\"icon\" type=\"image/$mimetype\" href=\"$path\">";
    }

    private function makeyugalvalidheader($html)
    {
        $pattern = '/<([a-zA-Z]+)([^>]*)>/';
        $html = preg_replace_callback($pattern, function ($matches) {
            $tag = $matches[1];
            $attributes = $matches[2];
            $newAttributes = ' data-yugal' . $attributes;
            return "<$tag$newAttributes>";
        }, $html);
        return $html;
    }
    public function script($code)
    {
        $_temp = $this->compress_code($code);
        array_push($this->js_code_list, $_temp);
    }

    public function libary($lib_name)
    {
        array_push($this->libs, $lib_name);
    }

    public function header($code)
    {
        $this->global_head = $code;
    }
    private function project_root()
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $domain = $_SERVER['HTTP_HOST'];
        $current_url = $protocol . '://' . $domain;
        $path = $current_url . dirname($_SERVER['PHP_SELF']);
        return $path;
    }
    public $pathname = "";
    private function updateRouteName()
    {
        $local = $_SERVER['REQUEST_URI'];
        $local = explode('/', $local);
        $local = $local[sizeof($local) - 1];
        $this->pathname = empty($local) ? "/" : "/$local";

    }
    public function __construct()
    {
        $this->updateRouteName();
        $prt = $this->project_root();
        $this->_project_root = $this->project_root();
        $this->script(<<<JS
        yugal.backend=true;
        yugal.projectRoot=`{$prt}`;
    JS);
    }
    public $slugs = null;
    // ([^\/]+)
    private function endsWith($string, $substring)
    {
        return substr($string, -strlen($substring)) === $substring;
    }
    private function startsWith($string, $substring)
    {
        return substr($string, 0, strlen($substring)) === $substring;
    }
    private function match_url_pattern()
    {
        $index = 0;
        $matched_index = null;
        $final = [];
        $url_slug = [];
        foreach ($this->screens as $url => $screen) {
            $templates = explode('/', $url);
            $pattern = '/';
            foreach ($templates as $string) {
                if ($this->startsWith($string, '[') && $this->endsWith($string, ']')) {
                    $new_slug = str_replace(["[", "]"], "", $string);
                    array_push($url_slug, $new_slug);
                    $pattern = $pattern . "\/([^\/]+)";
                } elseif (str_replace(" ", "", $string) === '') {
                    continue;
                } else {
                    $pattern = $pattern . "\/" . $string;
                }
            }
            $pattern = $pattern . "/";
            if (preg_match($pattern, $this->pathname, $matches)) {
                $matched_index = $url;
            }
            $index = $index + 1;
        }
        return [$matched_index, $final];
    }

    private function checkExistence($val)
    {
        return isset($val) ? $val : "";
    }
    private function compress_code($code)
    {
        $output = preg_replace('/\/\*[\s\S]*?\*\//', '', $code);
        $output = str_ireplace(["\n", "\t", "    ", "  "], "", $output);
        return $output;
    }
    private function replace_backticks($text)
    {
        return str_replace("`", "\`", $text);
    }
    public function page($arr)
    {
        // STORING IN VARIABLES AND IN ORDER TO AVOID ANY EXCEPTIONS LATER.
        $body = isset($arr['body']) ? $this->removeHtmlComments_($this->replacePathWihKeyword($arr['body'])) : "";
        $head = isset($arr['head']) ? $this->removeHtmlComments_($this->replacePathWihKeyword($arr['head'])) : "";
        $url = isset($arr['url']) ? $arr['url'] : "";
        $didmount = isset($arr['didmount']) ? $this->removeJsComments($arr['didmount']) : "";
        $willmount = isset($arr['willmount']) ? $this->removeJsComments($arr['willmount']) : "";
        $didunmount = isset($arr['didunmount']) ? $this->removeJsComments($arr['didunmount']) : "";
        $willunmount = isset($arr['willunmount']) ? $this->removeJsComments($arr['willunmount']) : "";
        $css = isset($arr['css']) ? $this->replace_backticks($arr['css']) : "";
        $style = isset($arr['style']) ? $this->replace_backticks($arr['style']) : "";
        //CODE COMPRESSION
        $body = $this->compress_code($body);
        $head = $this->compress_code($head);
        $didmount = $this->compress_code($didmount);
        $willmount = $this->compress_code($willmount);
        $didmount = $this->compress_code($didmount);
        $didunmount = $this->compress_code($didunmount);
        $willunmount = $this->compress_code($willunmount);
        $style = $this->compress_code($style);


        // WRITING NEW CSS FILE FOR PAGE
        $uri = str_replace("/", "", $url);
        if ($uri === "") {
            $uri = "index";
        }
        $file_name = "$uri.yugal.css";
        if (DEV_MODE == true) {
            if ($css != "") {
                $css = $this->compress_code($css);
                $css = str_ireplace(["    ", ": "], ["", ":"], $css);
                $file = fopen("./modules/$file_name", "w");
                fwrite($file, $css);
                fclose($file);
            }
        }
        if ($css != "") {
            $css = $file_name;
        }

        //MAKING ARRAY TO BE PUSHED INTO $screens
        $this->screens[$url] = [
            "render" => $this->mergeComponentCode($body),
            "header" => $this->mergeComponentCode($head),
            "willMount" => $willmount,
            "didMount" => $didmount,
            "didUnMount" => $didunmount,
            "willUnMount" => $willunmount,
            "css" => $css,
            "style" => $style
        ];

        // BUILDING PAGE IN YUGAL JS
        $js_head = $this->mergeComponentVariable($head);
        $js_body = $this->mergeComponentVariable($body);
        $js_willm = $this->replace_backticks($willmount);
        $jsdidm = $this->replace_backticks($didmount);
        $jsdidu = $this->replace_backticks($didunmount);
        $jswillu = $this->replace_backticks($willunmount);
        $this->script(<<<JS
yugal.page({
    render:`{$js_body}`,
    header:`{$js_head}`,
    uri:`{$url}`,
    willMount:()=>{{$js_willm}},
    didMount:()=>{{$jsdidm}},
    didUnMount:()=>{{$jsdidu}},
    willUnMount:()=>{{$jswillu}},
    css:`{$css}`,
    style:`{$style}`
});    
JS);
        // PAGE IS DEFINED IN YUGAL
    }

    public function noscript($noscript)
    {
        $this->noscript_ = $noscript;
    }
    // FUNCTION TO MAKE RESPECTIVE HTML
    public function build()
    {
        if (DEV_MODE == true) {
            if ($this->global_css_snippet != "") {
                $css_file = fopen("./modules/chunk.css", "w");
                fwrite($css_file, $this->global_css_code);
                fclose($css_file);
            }
        }
        $current_route_name = $this->pathname;

        // SEARCHING FOR RELATED ROUTE NAME

        if (isset($this->screens[$current_route_name])) {
            $page = $this->screens[$current_route_name];
        } else {
            http_response_code(404);
            if (isset($this->screens['/404'])){
                $page = $this->screens['/404'];
            }else{
                if (isset($this->screens[""])) {
                    $page = $this->screens[""];
                } else {
                    $page = [
                        "render" => "
                    <h1>404</h1>
	<p>Page not found.</p>
	<p>Sorry, the page you are looking for cannot be found. Please check the URL or use the search bar above to find what you are looking for.</p>
	<a href='./'>Go to site</a>
                    ",
                        "header" => "<title>Page Not Found</title>",
                        "willMount" => "",
                        "didMount" => "",
                        "didUnMount" => "",
                        "willUnMount" => "",
                        "css" => "",
                        "style" => "#yugal-root {
                        font-family: Arial, sans-serif;
                        background-color: #f5f5f5;
                        color: #5e5e5e;
                        text-align: center;
                        padding-top: 100px;
                        position:fixed;
                    }
                    h1 {
                        font-size: 60px;
                        color: #d3d3d3;
                        margin-bottom: 0;
                    }
                    p {
                        font-size: 18px;
                        margin-top: 20px;
                        margin-bottom: 30px;
                    }
                    a {
                        color: #4285f4;
                        text-decoration: none;
                    }"
                    ];
                }
            }
        }
        if ($page['css'] != "") {
            $css_path = $page['css'];
            $external_css = <<<HTML
            <link rel="stylesheet" data-yugal type="text/css" href="{$this->_project_root}/modules/{$css_path}" >
HTML;
        } else {
            $external_css = "";
        }
        $body = $page['render'];
        $head = $this->makeyugalvalidheader($page['header']);
        $merged_js = "";
        $js_code = $this->js_code_list;
        foreach ($js_code as $each_code) {
            $merged_js = $merged_js . $each_code;
        }
        $merged_js = $this->compress_code($merged_js);
        if (DEV_MODE == true) {
            $file = fopen("./modules/chunk.js", "w") or die("Error");
            fwrite($file, $merged_js);
            fclose($file);
        }
        $js_snippet = (sizeof($js_code) > 0) ? (<<<HTML
        <script src="{$this->_project_root}/modules/chunk.js"></script>        
HTML) : "";
        $page_specific_style = $this->compress_code($page['style']);
        $page_specific_style = str_ireplace(["    ", ": "], ["", ":"], $page_specific_style);
        $nstg = ($this->noscript_ != "") ? "<noscript>{$this->noscript_}</noscript>" : "";
        $outputhtml = $this->compress_code(<<<HTML
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {$external_css}
    <link rel="stylesheet" type="text/css" href="{$this->_project_root}/modules/yugal.css">
    {$this->global_css_snippet}
    {$head}
    {$this->fav}
    {$this->thm}
    {$this->global_head}
HTML);
        echo $outputhtml;
        //IMPORT HEAD OF LIB HERE

        foreach ($this->libs as $lib) {
            if (file_exists("./lib/$lib/header.php")) {
                include_once("./lib/$lib/header.php");
            }
        }

        $output_body = $this->compress_code(<<<HTML
</head>
<body>
    {$nstg}
    <div id="yugal-root">
        {$body}
    </div>
    <style data-yugal-style="">{$page_specific_style}</style>
    <script src="{$this->_project_root}/modules/yugal.js"></script>
    {$js_snippet}
HTML);
        echo $output_body;
        // LIBRARY BODY HERE
        foreach ($this->libs as $lib) {
            if (file_exists("./lib/$lib/index.php")) {
                include_once("./lib/$lib/index.php");
            }
        }

        echo $this->compress_code(<<<HTML
{$this->external_js}
</body>
</html>
HTML);
    }

}
?>