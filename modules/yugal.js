let page={};const universal={},yugal={sessionStorage:{},err404page:{},production:()=>{console=0},globalComponents:{},title:e=>(void 0===document.getElementsByTagName("title")[0]&&yugal.header(""),void 0!==e?(document.getElementsByTagName("title")[0].innerHTML=e,!0):document.getElementsByTagName("title")[0].innerHTML),error404:e=>{yugal.err404page=e},header:e=>{document.querySelectorAll("[data-yugal]").forEach((e=>{e.remove()})),_temp=document.createElement("div"),_temp.innerHTML=e,__headtags=_temp.querySelectorAll("*"),__headtags.forEach((e=>{document.getElementsByTagName("head")[0].appendChild(e),e.setAttribute("data-yugal","")}))},allPages:{},page:e=>yugal.allPages[e.uri]={...e},loadAnchors:()=>{const e=document.querySelectorAll("a");e.length>0&&e.forEach((e=>{if(href=e.getAttribute("href"),"."===href[0]&&"/"===href[1]&&"."!==href[2]){const t=href.replace("./","/");void 0!==yugal.allPages[t]&&e.addEventListener("click",(e=>{e.preventDefault(),"_blank"===e.target.getAttribute("target")?window.open(href):yugal.link(t)}))}}))},updatePageFromUrl:()=>{yugal.loadAnchors();let e=window.location.href.split("/");e=e[e.length-1],yugal.link(`/${e}`)},runLifeCycleMethods:()=>{let e=window.location.href.split("/");e=e[e.length-1],void 0===yugal.allPages[`/${e}`]?screen=yugal.err404page:(screen=yugal.allPages[`/${e}`],yugal.allPages[`/${e}`].willMount(),yugal.allPages[`/${e}`].didMount()),page={},yugal.loadAnchors()},link:(e,t)=>{function l(t){window.history.pushState(null,null,`.${e}`),page={},void 0!==t.header&&yugal.header(t.header),document.getElementById("yugal-root").innerHTML=t.render,document.querySelector("[data-yugal-style]").innerHTML=void 0!==t.style?t.style:"";document.querySelectorAll("[to]").forEach((e=>{let t=e.getAttribute("to");null!==e.getAttribute("onclick")?toValue_past=e.getAttribute("onclick"):toValue_past="",t=""===toValue_past.replaceAll(" ")?`yugal.link("${t}");`:`${toValue_past};yugal.link("${t}");`,t=t.replaceAll(";;",";"),e.setAttribute("onclick",t),e.removeAttribute("to")})),void 0!==t.css&&""!==t.css.replaceAll(" ")&&(csstoadd=document.createElement("link"),csstoadd.setAttribute("rel","stylesheet"),csstoadd.setAttribute("type","text/css"),csstoadd.setAttribute("href",`modules/${t.css}`),csstoadd.setAttribute("data-yugal",""),document.getElementsByTagName("head")[0].append(csstoadd))}page={},void 0!==t&&t.preventDefault(),yugal.allPages[e].willMount(),null==yugal.allPages[e]?0===Object.keys(yugal.err404page).length||l(yugal.err404page):l(yugal.allPages[e]),yugal.loadAnchors(),!0===yugal.allPages[e].fallback?void 0!==yugal.sessionStorage[e]?(document.getElementById("yugal-root").innerHTML=yugal.sessionStorage[e].render,yugal.header(yugal.sessionStorage[e].head),document.querySelector("[data-yugal-style]").innerHTML=yugal.sessionStorage[e].style,yugal.loadAnchors(),yugal.allPages[e].didMount()):fetch(location.href).then((t=>t.text().then((t=>{const l=document.createElement("html");l.innerHTML=t;const a=l.querySelector("head");let n=document.createElement("head");a.querySelectorAll("[data-yugal]").forEach((e=>{n.appendChild(e)})),yugal.header(n.innerHTML);const o=l.querySelector("#yugal-root");document.getElementById("yugal-root").innerHTML=o.innerHTML,document.querySelector("[data-yugal-style]").innerHTML=l.querySelector("[data-yugal-style]").innerHTML,yugal.sessionStorage[e]={render:o.innerHTML,head:n.innerHTML,style:l.querySelector("[data-yugal-style]").innerHTML},yugal.loadAnchors(),yugal.allPages[e].didMount()})))).catch((()=>{})):yugal.allPages[e].didMount()},$:e=>document.querySelector(e)};window.addEventListener("load",(function(){yugal.runLifeCycleMethods();const e=window.location.href.replace(yugal.projectRoot,"");let t=document.createElement("head");t.innerHTML=document.getElementsByTagName("head")[0].innerHTML;let l=document.createElement("head");t.querySelectorAll("[data-yugal]").forEach((e=>{l.appendChild(e)})),yugal.sessionStorage[e]={render:document.getElementById("yugal-root").innerHTML,style:document.querySelector("[data-yugal-style]").innerHTML,head:l.innerHTML}})),window.onpopstate=function(){yugal.updatePageFromUrl(),yugal.loadAnchors()};