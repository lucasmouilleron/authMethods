/////////////////////////////////////////////////////////////////////
// LIBRARIES CONFIG
/////////////////////////////////////////////////////////////////////
require.config({
// libraries paths
paths: {
// main libs
"jquery": "vendor/jquery-1.8.2.min",
"bootstrap": "vendor/bootstrap.min",
// utils
"tools": "tools",
"console": "vendor/console",
"toc": "vendor/toc.min"

},
// dependencies and exports
shim: {
    "bootstrap": ["jquery"],
    "throbber": ["jquery"],
    "console": ["jquery"],
    "tools": ["jquery"],
    "toc": ["jquery"]
}
});

/////////////////////////////////////////////////////////////////////
// BOOTSRAP !!!!
/////////////////////////////////////////////////////////////////////
require(["jquery", "bootstrap", "console", "tools","toc"], function($) {
    $(function() {
        if($("#toc").length) {
            $("#toc").affix({
                offset: {
                    top: $("#toc").offset().top-$(".navbar").height()
                }
            });
            $("#toc").toc({
                "selectors": "h1,h2,h3",
                "container": "#content",
                "prefix": "toc",
                "scrollToOffset": 60
            });
        }
    });
});