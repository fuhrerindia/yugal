let page = {};
const universal = {};
const yugal = {
  err404page: {},
  production: () => {
    console.log(
      "%cThis is a browser feature intended for developers. Do not enter anything here.",
      "background:black ;color: white; font-size: x-large"
    );
    console = 0;
  },
  globalComponents: {},
  afterDestroyed: () => {},
  title: (title) => {
    if (document.getElementsByTagName("title")[0] === undefined) {
      yugal.header("");
    }
    if (title !== undefined) {
      document.getElementsByTagName("title")[0].innerHTML = title;
      return true;
    } else {
      return document.getElementsByTagName("title")[0].innerHTML;
    }
  },
  error404: (props) => {
    yugal.err404page = props;
  },
  header: (code) => {
    document.querySelectorAll("[data-yugal]").forEach((prev_tag) => {
      prev_tag.remove();
    });
    _temp = document.createElement("div");
    _temp.innerHTML = code;
    __headtags = _temp.querySelectorAll("*");
    __headtags.forEach((tag) => {
      document.getElementsByTagName("head")[0].appendChild(tag);
      tag.setAttribute("data-yugal", "");
    });
  },
  allPages: {},
  page: ({
    render,
    willMount,
    willUnMount,
    didMount,
    didUnMount,
    uri,
    css,
    header,
    style,
  }) => {
    yugal.allPages[uri] = {
      render,
      willMount,
      willUnMount,
      didMount,
      didUnMount,
      css,
      header,
      style,
    };
  },
  projectRoot: "",
  loadAnchors: () => {
    const all_anchors = document.querySelectorAll("a");
    if (all_anchors.length > 0) {
      all_anchors.forEach((tag) => {
        href = tag.getAttribute("href");
        if (href[0] === "." && href[1] === "/" && href[2] !== ".") {
          const routename = href.replace("./", "/");
          if (yugal.allPages[routename] !== undefined) {
            tag.addEventListener("click", (event) => {
              event.preventDefault();
              if (event.target.getAttribute("target") === "_blank"){
                window.open(href);
              }else{
                yugal.link(routename);
              }
            });
          }
        }
      });
    }
  },
  currentDestroy: () => {},
  updatePageFromUrl: () => {
    yugal.loadAnchors();
    const uri = window.location.href;
    let req = uri.split("/");
    req = req[req.length - 1];
    yugal.link(`/${req}`);
  },
  _willUnMount: () => {},
  _didUnMount: () => {},
  _willMount: () => {},
  _didMount: () => {},
  runLifeCycleMethods: () => {
    const uri = window.location.href;
    let req = uri.split("/");
    req = req[req.length - 1];
    let screen = {};
    if (yugal.allPages[`/${req}`] === undefined) {
      screen = yugal.err404page;
    } else {
      screen = yugal.allPages[`/${req}`];
    }
    yugal._willMount =
      screen.willMount === undefined ? () => {} : screen.willMount;
    yugal._didMount =
      screen.didMount === undefined ? () => {} : screen.didMount;
    yugal._willUnMount =
      screen.willUnMount === undefined ? () => {} : screen.willUnMount;
    yugal._didUnMount =
      screen._didUnMount === undefined ? () => {} : screen.didUnMount;
    page = {};
    yugal.loadAnchors();
    yugal._willMount();
    yugal._didMount();
  },
  link: (uri, event) => {
    page = {};
    if (event !== undefined) {
      event.preventDefault();
    }
    yugal._willUnMount();
    yugal._willUnMount = () => {};
    let __tempdid = () => {};
    function navigationLocale(screen) {
      yugal._willMount =
        screen.willMount === undefined ? () => {} : screen.willMount;
      yugal._didMount =
        screen.didMount === undefined ? () => {} : screen.didMount;
      yugal._willUnMount =
        screen.willUnMount === undefined ? () => {} : screen.willUnMount;
      __tempdid =
        screen._didUnMount === undefined ? () => {} : screen.didUnMount;
      window.history.pushState(null, null, `.${uri}`);
      page = {};
      yugal._willMount();
      yugal.loadAnchors();
      if (screen.header !== undefined) {
        yugal.header(screen.header);
      }
      document.getElementById("yugal-root").innerHTML = screen.render;
      document.querySelector("[data-yugal-style]").innerHTML =
        screen.style !== undefined ? screen.style : "";
      const elements = document.querySelectorAll("[to]");
      elements.forEach((element) => {
        let toValue = element.getAttribute("to");
        if (element.getAttribute("onclick") !== null) {
          toValue_past = element.getAttribute("onclick");
        } else {
          toValue_past = "";
        }
        if (toValue_past.replaceAll(" ") === "") {
          toValue = `yugal.link("${toValue}");`;
        } else {
          toValue = `${toValue_past};yugal.link("${toValue}");`;
        }
        toValue = toValue.replaceAll(";;", ";");
        element.setAttribute("onclick", toValue);
        element.removeAttribute("to");
      });
      if (screen.css !== undefined && screen.css.replaceAll(" ") !== "") {
        csstoadd = document.createElement("link");
        csstoadd.setAttribute("rel", "stylesheet");
        csstoadd.setAttribute("type", "text/css");
        csstoadd.setAttribute("href", `modules/${screen.css}`);
        csstoadd.setAttribute("data-yugal", "");
        document.getElementsByTagName("head")[0].append(csstoadd);
      }
      yugal._didMount();
    }
    if (yugal.allPages[uri] == undefined) {
      if (Object.keys(yugal.err404page).length === 0) {
        console.error("ERROR 404: PAGE NOT FOUND");
      } else {
        navigationLocale(yugal.err404page);
      }
    } else {
      navigationLocale(yugal.allPages[uri]);
    }
    yugal._didUnMount();
    yugal._didUnMount = () => {};
    yugal.didUnMount = __tempdid;
    yugal.loadAnchors();
  },
  include: (file) => {
    var script = document.createElement("script");
    script.src = file;
    script.type = "text/javascript";
    document.getElementsByTagName("body").item(0).appendChild(script);
  },
  files: (array) => {
    array.map((item) => {
      yugal.include(item);
    });
  },
  kebabize: (str) => {
    return str
      .split("")
      .map((letter, idx) => {
        return letter.toUpperCase() === letter
          ? `${idx !== 0 ? "-" : ""}${letter.toLowerCase()}`
          : letter;
      })
      .join("");
  },
  style: (obj) => {
    des = "";
    Object.keys(obj).forEach(function (nkey) {
      end = "";
      if (typeof obj[nkey] === "number") {
        end = `${obj[nkey]}px;`;
      } else {
        end = `${obj[nkey]};`;
      }
      des = des + "" + yugal.kebabize(nkey) + ":" + end;
    });
    return des;
  },
  css: (props, name) => {
    if (document.getElementById("yugal-style") === null) {
      const style_element = document.createElement("style");
      style_element.setAttribute("id", "yugal-style");
      document.getElementsByTagName("body")[0].appendChild(style_element);
    }
    if (typeof props !== "string") {
      props = yugal.style(props);
    }
    yugal_style = document.getElementById("yugal-style");
    yugal_style.innerHTML = `${yugal_style.innerHTML}${name}{${props}}`;
  },
  $: (key) => document.querySelector(key),
  StyleSheet: {
    create: (css, beg) => {
      beg = !beg ? "" : beg;
      final_css = "";
      Object.keys(css).map((key) => {
        this_props = `${beg}${key}{`;
        Object.keys(css[key]).map((prop) => {
          prop_val =
            typeof css[key][prop] === "number"
              ? `${String(css[key][prop])}px`
              : css[key][prop];
          this_props = `${this_props}${yugal.kebabize(prop)}:${prop_val};`;
        });
        this_props = this_props + `} `;
        final_css = final_css + this_props;
      });
      return final_css;
    },
    inject: (css_string) => {
      if (document.getElementById("yugal-style") === null) {
        const style_element = document.createElement("style");
        style_element.setAttribute("id", "yugal-style");
        document.getElementsByTagName("body")[0].appendChild(style_element);
      }
      document.getElementById("yugal-style").innerHTML = `${
        document.getElementById("yugal-style").innerHTML
      }${css_string}`;
    },
    import: (url, id) => {
      new_tag = document.createElement("link");
      new_tag.setAttribute("rel", "stylesheet");
      new_tag.setAttribute("type", "text/css");
      if (id !== undefined) {
        new_tag.setAttribute("id", id);
      }
      new_tag.setAttribute("data-yugal", "");
      new_tag.setAttribute("href", url);
      document.getElementsByTagName("head")[0].append(new_tag);
      return new_tag;
    },
  },
};
window.addEventListener("load", function () {
  if (yugal.backend === undefined || yugal.backend !== true) {
    yugal.updatePageFromUrl();
  } else {
    yugal.runLifeCycleMethods();
  }
});
window.onpopstate = function () {
  yugal.updatePageFromUrl();
  yugal.loadAnchors();
};