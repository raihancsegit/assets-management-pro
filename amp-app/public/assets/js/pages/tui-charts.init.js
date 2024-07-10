function getChartColorsArray(e) {
    if (null !== document.getElementById(e)) {
        var a = document.getElementById(e).getAttribute("data-colors");
        return (a = JSON.parse(a)).map(function (e) {
            var a = e.replace(" ", "");
            if (-1 == a.indexOf("--")) return a;
            var t = getComputedStyle(document.documentElement).getPropertyValue(a);
            return t || void 0;
        });
    }
}
