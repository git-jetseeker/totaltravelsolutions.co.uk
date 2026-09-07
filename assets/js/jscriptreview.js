
var useNewRelic = Math.ceil(Math.random() * 200) === 1;
    if(useNewRelic)  {
    window.NREUM||(NREUM= {
}
), __nr_require=function(t, n, e) {
    function r(e) {
    if(!n[e]) {
    var o=n[e]= {
    exports:  {
}
}
;
    t[e][0].call(o.exports, function(n) {
    var o=t[e][1][n];
    return r(o||n);
}
, o, o.exports);
}return n[e].exports;
}if("function"==typeof __nr_require)return __nr_require;
    for(var o=0;
    o<e.length;
    o++)r(e[o]);
    return r;
}
( {
    1: [function(t, n, e) {
    function r(t) {
    try {
    s.console&&console.log(t);
}
catch(n) {
}
}
var o, i=t("ee"), a=t(15), s= {
}
;
    try {
    o=localStorage.getItem("__nr_flags").split(", "), console&&"function"==typeof console.log&&(s.console=!0, o.indexOf("dev")!==-1&&(s.dev=!0), o.indexOf("nr_dev")!==-1&&(s.nrDev=!0));
}
catch(c) {
}
s.nrDev&&i.on("internal-error", function(t) {
    r(t.stack);
}
), s.dev&&i.on("fn-err", function(t, n, e) {
    r(e.stack);
}
), s.dev&&(r("NR AGENT IN DEVELOPMENT MODE"), r("flags:  "+a(s, function(t, n) {
    return t;
}
).join(",  ")));
},  {
}
], 2: [function(t, n, e) {
    function r(t, n, e, r, o) {
    try {
    d?d-=1: i("err", [o||new UncaughtException(t, n, e)]);
}
catch(s) {
    try {
    i("ierr", [s, c.now(), !0]);
}
catch(u) {
}
}
return"function"==typeof f&&f.apply(this, a(arguments));
}function UncaughtException(t, n, e) {
    this.message=t||"Uncaught error with no additional information", this.sourceURL=n, this.line=e;
}
function o(t) {
    i("err", [t, c.now()]);
}
var i=t("handle"), a=t(16), s=t("ee"), c=t("loader"), f=window.onerror, u=!1, d=0;
    c.features.err=!0, t(1), window.onerror=r;
    try {
    throw new Error;
}
catch(l) {
    "stack"in l&&(t(8), t(7), "addEventListener"in window&&t(5), c.xhrWrappable&&t(9), u=!0);
}
s.on("fn-start", function(t, n, e) {
    u&&(d+=1);
}
), s.on("fn-err", function(t, n, e) {
    u&&(this.thrown=!0, o(e));
}
), s.on("fn-end", function() {
    u&&!this.thrown&&d>0&&(d-=1);
}
), s.on("internal-error", function(t) {
    i("ierr", [t, c.now(), !0]);
}
);
},  {
}
], 3: [function(t, n, e) {
    t("loader").features.ins=!0;
}
,  {
}
], 4: [function(t, n, e) {
    function r(t) {
}
if(window.performance&&window.performance.timing&&window.performance.getEntriesByType) {
    var o=t("ee"), i=t("handle"), a=t(8), s=t(7), c="learResourceTimings", f="addEventListener", u="resourcetimingbufferfull", d="bstResource", l="resource", p="-start", h="-end", m="fn"+p, w="fn"+h, v="bstTimer", y="pushState", g=t("loader");
    g.features.stn=!0, t(6);
    var b=NREUM.o.EV;
    o.on(m, function(t, n) {
    var e=t[0];
    e instanceof b&&(this.bstStart=g.now());
}
), o.on(w, function(t, n) {
    var e=t[0];
    e instanceof b&&i("bst", [e, n, this.bstStart, g.now()]);
}
), a.on(m, function(t, n, e) {
    this.bstStart=g.now(), this.bstType=e;
}
), a.on(w, function(t, n) {
    i(v, [n, this.bstStart, g.now(), this.bstType]);
}
), s.on(m, function() {
    this.bstStart=g.now();
}
), s.on(w, function(t, n) {
    i(v, [n, this.bstStart, g.now(), "requestAnimationFrame"]);
}
), o.on(y+p, function(t) {
    this.time=g.now(), this.startPath=location.pathname+location.hash;
}
), o.on(y+h, function(t) {
    i("bstHist", [location.pathname+location.hash, this.startPath, this.time]);
}
), f in window.performance&&(window.performance["c"+c]?window.performance[f](u, function(t) {
    i(d, [window.performance.getEntriesByType(l)]), window.performance["c"+c]();
}
, !1): window.performance[f]("webkit"+u, function(t) {
    i(d, [window.performance.getEntriesByType(l)]), window.performance["webkitC"+c]();
}
, !1)), document[f]("scroll", r,  {
    passive: !0;
}
), document[f]("keypress", r, !1), document[f]("click", r, !1);
}},  {
}
], 5:[function(t, n, e) {
    function r(t) {
    for(var n=t;
    n&&!n.hasOwnProperty(u);
    )n=Object.getPrototypeOf(n);
    n&&o(n);
}
function o(t) {
    s.inPlace(t, [u, d], "-", i);
}
function i(t, n) {
    return t[1];
}
var a=t("ee").get("events"), s=t(18)(a, !0), c=t("gos"), f=XMLHttpRequest, u="addEventListener", d="removeEventListener";
    n.exports=a, "getPrototypeOf"in Object?(r(document), r(window), r(f.prototype)): f.prototype.hasOwnProperty(u)&&(o(window), o(f.prototype)), a.on(u+"-start", function(t, n) {
    var e=t[1], r=c(e, "nr@wrapped", function() {
    function t() {
    if("function"==typeof e.handleEvent)return e.handleEvent.apply(e, arguments);
}
var n= {
    object: t, "function":e;
}
[typeof e];
    return n?s(n, "fn-", null, n.name||"anonymous"): e;
}
);
    this.wrapped=t[1]=r;
}
), a.on(d+"-start", function(t) {
    t[1]=this.wrapped||t[1];
}
);
},  {
}
], 6: [function(t, n, e) {
    var r=t("ee").get("history"), o=t(18)(r);
    n.exports=r, o.inPlace(window.history, ["pushState", "replaceState"], "-");
}
,  {
}
], 7: [function(t, n, e) {
    var r=t("ee").get("raf"), o=t(18)(r), i="equestAnimationFrame";
    n.exports=r, o.inPlace(window, ["r"+i, "mozR"+i, "webkitR"+i, "msR"+i], "raf-"), r.on("raf-start", function(t) {
    t[0]=o(t[0], "fn-");
}
);
},  {
}
], 8: [function(t, n, e) {
    function r(t, n, e) {
    t[0]=a(t[0], "fn-", null, e);
}
function o(t, n, e) {
    this.method=e, this.timerDuration=isNaN(t[1])?0: +t[1], t[0]=a(t[0], "fn-", this, e);
}
var i=t("ee").get("timer"), a=t(18)(i), s="setTimeout", c="setInterval", f="clearTimeout", u="-start", d="-";
    n.exports=i, a.inPlace(window, [s, "setImmediate"], s+d), a.inPlace(window, [c], c+d), a.inPlace(window, [f, "clearImmediate"], f+d), i.on(c+u, r), i.on(s+u, o);
}
,  {
}
], 9: [function(t, n, e) {
    function r(t, n) {
    d.inPlace(n, ["onreadystatechange"], "fn-", s);
}
function o() {
    var t=this, n=u.context(t);
    t.readyState>3&&!n.resolved&&(n.resolved=!0, u.emit("xhr-resolved", [], t)), d.inPlace(t, y, "fn-", s);
}
function i(t) {
    g.push(t), h&&(x?x.then(a): w?w(a):(E=-E, O.data=E));
}
function a() {
    for(var t=0;
    t<g.length;
    t++)r([], g[t]);
    g.length&&(g=[]);
}
function s(t, n) {
    return n;
}
function c(t, n) {
    for(var e in t)n[e]=t[e];
    return n;
}
t(5);
    var f=t("ee"), u=f.get("xhr"), d=t(18)(u), l=NREUM.o, p=l.XHR, h=l.MO, m=l.PR, w=l.SI, v="readystatechange", y=["onload", "onerror", "onabort", "onloadstart", "onloadend", "onprogress", "ontimeout"], g=[];
    n.exports=u;
    var b=window.XMLHttpRequest=function(t) {
    var n=new p(t);
    try {
    u.emit("new-xhr", [n], n), n.addEventListener(v, o, !1);
}
catch(e) {
    try {
    u.emit("internal-error", [e]);
}
catch(r) {
}
}
return n;
};
    if(c(p, b), b.prototype=p.prototype, d.inPlace(b.prototype, ["open", "send"], "-xhr-", s), u.on("send-xhr-start", function(t, n) {
    r(t, n), i(n);
}
), u.on("open-xhr-start", r), h) {
    var x=m&&m.resolve();
    if(!w&&!m) {
    var E=1, O=document.createTextNode(E);
    new h(a).observe(O,  {
    characterData: !0;
}
);
}}else f.on("fn-end", function(t) {
    t[0]&&t[0].type===v||a();
}
);
},  {
}
], 10: [function(t, n, e) {
    function r(t) {
    var n=this.params, e=this.metrics;
    if(!this.ended) {
    this.ended=!0;
    for(var r=0;
    r<d;
    r++)t.removeEventListener(u[r], this.listener, !1);
    if(!n.aborted) {
    if(e.duration=a.now()-this.startTime, 4===t.readyState) {
    n.status=t.status;
    var i=o(t, this.lastSize);
    if(i&&(e.rxSize=i), this.sameOrigin) {
    var c=t.getResponseHeader("X-NewRelic-App-Data");
    c&&(n.cat=c.split(",  ").pop());
}
}else n.status=0;
    e.cbTime=this.cbTime, f.emit("xhr-done", [t], t), s("xhr", [n, e, this.startTime]);
}
}}function o(t, n) {
    var e=t.responseType;
    if("json"===e&&null!==n)return n;
    var r="arraybuffer"===e||"blob"===e||"json"===e?t.response: t.responseText;
    return h(r);
}
function i(t, n) {
    var e=c(n), r=t.params;
    r.host=e.hostname+": "+e.port, r.pathname=e.pathname, t.sameOrigin=e.sameOrigin;
}
var a=t("loader");
    if(a.xhrWrappable) {
    var s=t("handle"), c=t(11), f=t("ee"), u=["load", "error", "abort", "timeout"], d=u.length, l=t("id"), p=t(14), h=t(13), m=window.XMLHttpRequest;
    a.features.xhr=!0, t(9), f.on("new-xhr", function(t) {
    var n=this;
    n.totalCbs=0, n.called=0, n.cbTime=0, n.end=r, n.ended=!1, n.xhrGuids= {
}
, n.lastSize=null, p&&(p>34||p<10)||window.opera||t.addEventListener("progress", function(t) {
    n.lastSize=t.loaded;
}
, !1);
}), f.on("open-xhr-start", function(t) {
    this.params= {
    method: t[0];
}
, i(this, t[1]), this.metrics= {
}
}
), f.on("open-xhr-end", function(t, n) {
    "loader_config"in NREUM&&"xpid"in NREUM.loader_config&&this.sameOrigin&&n.setRequestHeader("X-NewRelic-ID", NREUM.loader_config.xpid);
}
), f.on("send-xhr-start", function(t, n) {
    var e=this.metrics, r=t[0], o=this;
    if(e&&r) {
    var i=h(r);
    i&&(e.txSize=i);
}
this.startTime=a.now(), this.listener=function(t) {
    try {
    "abort"===t.type&&(o.params.aborted=!0), ("load"!==t.type||o.called===o.totalCbs&&(o.onloadCalled||"function"!=typeof n.onload))&&o.end(n);
}
catch(e) {
    try {
    f.emit("internal-error", [e]);
}
catch(r) {
}
}
};
    for(var s=0;
    s<d;
    s++)n.addEventListener(u[s], this.listener, !1);
}
), f.on("xhr-cb-time", function(t, n, e) {
    this.cbTime+=t, n?this.onloadCalled=!0: this.called+=1, this.called!==this.totalCbs||!this.onloadCalled&&"function"==typeof e.onload||this.end(e);
}
), f.on("xhr-load-added", function(t, n) {
    var e=""+l(t)+!!n;
    this.xhrGuids&&!this.xhrGuids[e]&&(this.xhrGuids[e]=!0, this.totalCbs+=1);
}
), f.on("xhr-load-removed", function(t, n) {
    var e=""+l(t)+!!n;
    this.xhrGuids&&this.xhrGuids[e]&&(delete this.xhrGuids[e], this.totalCbs-=1);
}
), f.on("addEventListener-end", function(t, n) {
    n instanceof m&&"load"===t[0]&&f.emit("xhr-load-added", [t[1], t[2]], n);
}
), f.on("removeEventListener-end", function(t, n) {
    n instanceof m&&"load"===t[0]&&f.emit("xhr-load-removed", [t[1], t[2]], n);
}
), f.on("fn-start", function(t, n, e) {
    n instanceof m&&("onload"===e&&(this.onload=!0), ("load"===(t[0]&&t[0].type)||this.onload)&&(this.xhrCbStart=a.now()));
}
), f.on("fn-end", function(t, n) {
    this.xhrCbStart&&f.emit("xhr-cb-time", [a.now()-this.xhrCbStart, this.onload, n], n);
}
);
}},  {
}
], 11: [function(t, n, e) {
    n.exports=function(t) {
    var n=document.createElement("a"), e=window.location, r= {
}
;
    n.href=t, r.port=n.port;
    var o=n.href.split(": //");
    !r.port&&o[1]&&(r.port=o[1].split("/")[0].split("@").pop().split(": ")[1]), r.port&&"0"!==r.port||(r.port="https"===o[0]?"443":"80"), r.hostname=n.hostname||e.hostname, r.pathname=n.pathname, r.protocol=o[0], "/"!==r.pathname.charAt(0)&&(r.pathname="/"+r.pathname);
    var i=!n.protocol||": "===n.protocol||n.protocol===e.protocol, a=n.hostname===document.domain&&n.port===e.port;
    return r.sameOrigin=i&&(!n.hostname||a), r;
}
},  {
}
], 12: [function(t, n, e) {
    function r() {
}
function o(t, n, e) {
    return function() {
    return i(t, [f.now()].concat(s(arguments)), n?null: this, e), n?void 0:this;
}
}var i=t("handle"), a=t(15), s=t(16), c=t("ee").get("tracer"), f=t("loader"), u=NREUM;
    "undefined"==typeof window.newrelic&&(newrelic=u);
    var d=["setPageViewName", "setCustomAttribute", "setErrorHandler", "finished", "addToTrace", "inlineHit", "addRelease"], l="api-", p=l+"ixn-";
    a(d, function(t, n) {
    u[n]=o(l+n, !0, "api");
}
), u.addPageAction=o(l+"addPageAction", !0), u.setCurrentRouteName=o(l+"routeName", !0), n.exports=newrelic, u.interaction=function() {
    return(new r).get();
}
;
    var h=r.prototype= {
    createTracer: function(t, n) {
    var e= {
}
, r=this, o="function"==typeof n;
    return i(p+"tracer", [f.now(), t, e], r), function() {
    if(c.emit((o?"": "no-")+"fn-start", [f.now(), r, o], e), o)try {
    return n.apply(this, arguments);
}
finally {
    c.emit("fn-end", [f.now()], e);
}
}}};
    a("setName, setAttribute, sa…