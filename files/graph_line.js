(function() {
    function oa(h, m) {
        h.prototype = cb(m.prototype);
        h.prototype.constructor = h;
        h.base = m.prototype
    }

    function cb(h) {
        function m() {}
        m.prototype = h;
        return new m
    }

    function Va(h, m, w) {
        "millisecond" === w ? h.setMilliseconds(h.getMilliseconds() + 1 * m) : "second" === w ? h.setSeconds(h.getSeconds() + 1 * m) : "minute" === w ? h.setMinutes(h.getMinutes() + 1 * m) : "hour" === w ? h.setHours(h.getHours() + 1 * m) : "day" === w ? h.setDate(h.getDate() + 1 * m) : "week" === w ? h.setDate(h.getDate() + 7 * m) : "month" === w ? h.setMonth(h.getMonth() + 1 * m) : "year" === w && h.setFullYear(h.getFullYear() +
            1 * m);
        return h
    }

    function X(h, m) {
        var w = !1;
        0 > h && (w = !0, h *= -1);
        h = "" + h;
        for (m = m ? m : 1; h.length < m;) h = "0" + h;
        return w ? "-" + h : h
    }

    function Fa(h) {
        if (!h) return h;
        h = h.replace(/^\s\s*/, "");
        for (var m = /\s/, w = h.length; m.test(h.charAt(--w)););
        return h.slice(0, w + 1)
    }

    function Ba(h) {
        h.roundRect = function(h, w, s, u, ra, D, y, F) {
            y && (this.fillStyle = y);
            F && (this.strokeStyle = F);
            "undefined" === typeof ra && (ra = 5);
            this.lineWidth = D;
            this.beginPath();
            this.moveTo(h + ra, w);
            this.lineTo(h + s - ra, w);
            this.quadraticCurveTo(h + s, w, h + s, w + ra);
            this.lineTo(h + s,
                w + u - ra);
            this.quadraticCurveTo(h + s, w + u, h + s - ra, w + u);
            this.lineTo(h + ra, w + u);
            this.quadraticCurveTo(h, w + u, h, w + u - ra);
            this.lineTo(h, w + ra);
            this.quadraticCurveTo(h, w, h + ra, w);
            this.closePath();
            y && this.fill();
            F && 0 < D && this.stroke()
        }
    }

    function Pa(h, m) { return h - m }

    function P(h) {
        var m = ((h & 16711680) >> 16).toString(16),
            w = ((h & 65280) >> 8).toString(16);
        h = ((h & 255) >> 0).toString(16);
        m = 2 > m.length ? "0" + m : m;
        w = 2 > w.length ? "0" + w : w;
        h = 2 > h.length ? "0" + h : h;
        return "#" + m + w + h
    }

    function db(h, m) {
        var w = this.length >>> 0,
            s = Number(m) || 0,
            s = 0 > s ? Math.ceil(s) :
            Math.floor(s);
        for (0 > s && (s += w); s < w; s++)
            if (s in this && this[s] === h) return s;
        return -1
    }

    function s(h) { return null === h || "undefined" === typeof h }

    function Ca(h) { h.indexOf || (h.indexOf = db); return h }

    function eb(h) { if (S.fSDec) h[W("`eeDwdouMhrudods")](W("e`u`@ohl`uhnoHuds`uhnoDoe"), function() { S._fTWm && S._fTWm(h) }) }

    function Wa(h, m, w) {
        w = w || "normal";
        var s = h + "_" + m + "_" + w,
            u = Xa[s];
        if (isNaN(u)) {
            try {
                h = "position:absolute; left:0px; top:-20000px; padding:0px;margin:0px;border:none;white-space:pre;line-height:normal;font-family:" +
                    h + "; font-size:" + m + "px; font-weight:" + w + ";";
                if (!wa) {
                    var ra = document.body;
                    wa = document.createElement("span");
                    wa.innerHTML = "";
                    var D = document.createTextNode("Mpgyi");
                    wa.appendChild(D);
                    ra.appendChild(wa)
                }
                wa.style.display = "";
                wa.setAttribute("style", h);
                u = Math.round(wa.offsetHeight);
                wa.style.display = "none"
            } catch (y) { u = Math.ceil(1.1 * m) }
            u = Math.max(u, m);
            Xa[s] = u
        }
        return u
    }

    function N(h, m) {
        var w = [];
        if (w = {
                solid: [],
                shortDash: [3, 1],
                shortDot: [1, 1],
                shortDashDot: [3, 1, 1, 1],
                shortDashDotDot: [3, 1, 1, 1, 1, 1],
                dot: [1, 2],
                dash: [4,
                    2
                ],
                dashDot: [4, 2, 1, 2],
                longDash: [8, 2],
                longDashDot: [8, 2, 1, 2],
                longDashDotDot: [8, 2, 1, 2, 1, 2]
            }[h || "solid"])
            for (var s = 0; s < w.length; s++) w[s] *= m;
        else w = [];
        return w
    }

    function J(h, m, w, u, qa) {
        u = u || [];
        qa = s(qa) ? fb ? { passive: !1, capture: !1 } : !1 : qa;
        u.push([h, m, w, qa]);
        return h.addEventListener ? (h.addEventListener(m, w, qa), w) : h.attachEvent ? (u = function(m) {
            m = m || window.event;
            m.preventDefault = m.preventDefault || function() { m.returnValue = !1 };
            m.stopPropagation = m.stopPropagation || function() { m.cancelBubble = !0 };
            w.call(h, m)
        }, h.attachEvent("on" +
            m, u), u) : !1
    }

    function gb(h) {
        if (h._menuButton) h.exportEnabled ? (ga(h._menuButton, { backgroundColor: h.toolbar.itemBackgroundColor, color: h.toolbar.fontColor }), Ka(h._menuButton), h._menuButton.title = h._cultureInfo.menuText) : ua(h._menuButton);
        else if (h.exportEnabled && u) {
            var m = !1;
            h._menuButton = document.createElement("button");
            va(h, h._menuButton, "menu");
            h._toolBar.appendChild(h._menuButton);
            J(h._menuButton, "touchstart", function(h) { m = !0 }, h.allDOMEventHandlers);
            J(h._menuButton, "click", function() {
                "none" !== h._dropdownMenu.style.display ||
                    h._dropDownCloseTime && 500 >= (new Date).getTime() - h._dropDownCloseTime.getTime() || (h._dropdownMenu.style.display = "block", h._menuButton.blur(), h._dropdownMenu.focus())
            }, h.allDOMEventHandlers, !0);
            J(h._menuButton, "mouseover", function() { m || (ga(h._menuButton, { backgroundColor: h.toolbar.itemBackgroundColorOnHover, color: h.toolbar.fontColorOnHover }), 0 >= navigator.userAgent.search("MSIE") && ga(h._menuButton.childNodes[0], { WebkitFilter: "invert(100%)", filter: "invert(100%)" })) }, h.allDOMEventHandlers, !0);
            J(h._menuButton,
                "mouseout",
                function() { m || (ga(h._menuButton, { backgroundColor: h.toolbar.itemBackgroundColor, color: h.toolbar.fontColor }), 0 >= navigator.userAgent.search("MSIE") && ga(h._menuButton.childNodes[0], { WebkitFilter: "invert(0%)", filter: "invert(0%)" })) }, h.allDOMEventHandlers, !0)
        }
        if (h.exportEnabled && h._dropdownMenu) {
            ga(h._dropdownMenu, { backgroundColor: h.toolbar.itemBackgroundColor, color: h.toolbar.fontColor });
            for (var w = h._dropdownMenu.childNodes, s = [h._cultureInfo.printText, h._cultureInfo.saveJPGText, h._cultureInfo.savePNGText],
                    qa = 0; qa < w.length; qa++) ga(w[qa], { backgroundColor: h.toolbar.itemBackgroundColor, color: h.toolbar.fontColor }), w[qa].innerHTML = s[qa]
        } else !h._dropdownMenu && (h.exportEnabled && u) && (m = !1, h._dropdownMenu = document.createElement("div"), h._dropdownMenu.setAttribute("tabindex", -1), w = -1 !== h.theme.indexOf("dark") ? "black" : "#888888", h._dropdownMenu.style.cssText = "position: absolute; z-index: 1; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; cursor: pointer;right: 0px;top: 25px;min-width: 120px;outline: 0;font-size: 14px; font-family: Arial, Helvetica, sans-serif;padding: 5px 0px 5px 0px;text-align: left;line-height: 10px;background-color:" +
            h.toolbar.itemBackgroundColor + ";box-shadow: 2px 2px 10px " + w, h._dropdownMenu.style.display = "none", h._toolBar.appendChild(h._dropdownMenu), J(h._dropdownMenu, "blur", function() {
                ua(h._dropdownMenu);
                h._dropDownCloseTime = new Date
            }, h.allDOMEventHandlers, !0), w = document.createElement("div"), w.style.cssText = "padding: 12px 8px 12px 8px", w.innerHTML = h._cultureInfo.printText, w.style.backgroundColor = h.toolbar.itemBackgroundColor, w.style.color = h.toolbar.fontColor, h._dropdownMenu.appendChild(w), J(w, "touchstart",
                function(h) { m = !0 }, h.allDOMEventHandlers), J(w, "mouseover", function() { m || (this.style.backgroundColor = h.toolbar.itemBackgroundColorOnHover, this.style.color = h.toolbar.fontColorOnHover) }, h.allDOMEventHandlers, !0), J(w, "mouseout", function() { m || (this.style.backgroundColor = h.toolbar.itemBackgroundColor, this.style.color = h.toolbar.fontColor) }, h.allDOMEventHandlers, !0), J(w, "click", function() {
                h.print();
                ua(h._dropdownMenu)
            }, h.allDOMEventHandlers, !0), w = document.createElement("div"), w.style.cssText = "padding: 12px 8px 12px 8px",
            w.innerHTML = h._cultureInfo.saveJPGText, w.style.backgroundColor = h.toolbar.itemBackgroundColor, w.style.color = h.toolbar.fontColor, h._dropdownMenu.appendChild(w), J(w, "touchstart", function(h) { m = !0 }, h.allDOMEventHandlers), J(w, "mouseover", function() { m || (this.style.backgroundColor = h.toolbar.itemBackgroundColorOnHover, this.style.color = h.toolbar.fontColorOnHover) }, h.allDOMEventHandlers, !0), J(w, "mouseout", function() { m || (this.style.backgroundColor = h.toolbar.itemBackgroundColor, this.style.color = h.toolbar.fontColor) },
                h.allDOMEventHandlers, !0), J(w, "click", function() {
                h.exportChart({ format: "jpeg", fileName: h.exportFileName });
                ua(h._dropdownMenu)
            }, h.allDOMEventHandlers, !0), w = document.createElement("div"), w.style.cssText = "padding: 12px 8px 12px 8px", w.innerHTML = h._cultureInfo.savePNGText, w.style.backgroundColor = h.toolbar.itemBackgroundColor, w.style.color = h.toolbar.fontColor, h._dropdownMenu.appendChild(w), J(w, "touchstart", function(h) { m = !0 }, h.allDOMEventHandlers), J(w, "mouseover", function() {
                m || (this.style.backgroundColor =
                    h.toolbar.itemBackgroundColorOnHover, this.style.color = h.toolbar.fontColorOnHover)
            }, h.allDOMEventHandlers, !0), J(w, "mouseout", function() { m || (this.style.backgroundColor = h.toolbar.itemBackgroundColor, this.style.color = h.toolbar.fontColor) }, h.allDOMEventHandlers, !0), J(w, "click", function() {
                h.exportChart({ format: "png", fileName: h.exportFileName });
                ua(h._dropdownMenu)
            }, h.allDOMEventHandlers, !0))
    }

    function Ya(h, m, w) {
        h *= ka;
        m *= ka;
        h = w.getImageData(h, m, 2, 2).data;
        m = !0;
        for (w = 0; 4 > w; w++)
            if (h[w] !== h[w + 4] | h[w] !== h[w + 8] |
                h[w] !== h[w + 12]) { m = !1; break }
        return m ? h[0] << 16 | h[1] << 8 | h[2] : 0
    }

    function la(h, m, w) { return h in m ? m[h] : w[h] }

    function La(h, m, w) {
        if (u && Za) {
            var s = h.getContext("2d");
            Ma = s.webkitBackingStorePixelRatio || s.mozBackingStorePixelRatio || s.msBackingStorePixelRatio || s.oBackingStorePixelRatio || s.backingStorePixelRatio || 1;
            ka = Qa / Ma;
            h.width = m * ka;
            h.height = w * ka;
            Qa !== Ma && (h.style.width = m + "px", h.style.height = w + "px", s.scale(ka, ka))
        } else h.width = m, h.height = w
    }

    function hb(h) {
        if (!ib) {
            var m = !1,
                w = !1;
            "undefined" === typeof pa.Chart.creditHref ?
                (h.creditHref = W("iuuqr;..b`ow`rkr/bnl."), h.creditText = W("B`ow`rKR/bnl")) : (m = h.updateOption("creditText"), w = h.updateOption("creditHref"));
            if (h.creditHref && h.creditText) {
                h._creditLink || (h._creditLink = document.createElement("a"), h._creditLink.setAttribute("class", "canvasjs-chart-credit"), h._creditLink.setAttribute("title", "JavaScript Charts"), h._creditLink.setAttribute("style", "outline:none;margin:0px;position:absolute;right:2px;top:" + (h.height - 14) + "px;color:dimgrey;text-decoration:none;font-size:11px;font-family: Calibri, Lucida Grande, Lucida Sans Unicode, Arial, sans-serif"),
                    h._creditLink.setAttribute("tabIndex", -1), h._creditLink.setAttribute("target", "_blank"));
                if (0 === h.renderCount || m || w) h._creditLink.setAttribute("href", h.creditHref), h._creditLink.innerHTML = h.creditText;
                h._creditLink && h.creditHref && h.creditText ? (h._creditLink.parentElement || h._canvasJSContainer.appendChild(h._creditLink), h._creditLink.style.top = h.height - 14 + "px") : h._creditLink.parentElement && h._canvasJSContainer.removeChild(h._creditLink)
            }
        }
    }

    function ta(h, m) {
        Ga && (this.canvasCount |= 0, window.console.log(++this.canvasCount));
        var w = document.createElement("canvas");
        w.setAttribute("class", "canvasjs-chart-canvas");
        La(w, h, m);
        u || "undefined" === typeof G_vmlCanvasManager || G_vmlCanvasManager.initElement(w);
        return w
    }

    function ga(h, m) { for (var w in m) h.style[w] = m[w] }

    function va(h, m, w) {
        m.getAttribute("state") || (m.style.backgroundColor = h.toolbar.itemBackgroundColor, m.style.color = h.toolbar.fontColor, m.style.border = "none", ga(m, { WebkitUserSelect: "none", MozUserSelect: "none", msUserSelect: "none", userSelect: "none" }));
        m.getAttribute("state") !==
            w && (m.setAttribute("state", w), m.setAttribute("type", "button"), ga(m, { padding: "5px 12px", cursor: "pointer", "float": "left", width: "40px", height: "25px", outline: "0px", verticalAlign: "baseline", lineHeight: "0" }), m.setAttribute("title", h._cultureInfo[w + "Text"]), m.innerHTML = "<img style='height:95%; pointer-events: none;' src='" + jb[w].image + "' alt='" + h._cultureInfo[w + "Text"] + "' />")
    }

    function Ka() { for (var h = null, m = 0; m < arguments.length; m++) h = arguments[m], h.style && (h.style.display = "inline") }

    function ua() {
        for (var h =
                null, m = 0; m < arguments.length; m++)(h = arguments[m]) && h.style && (h.style.display = "none")
    }

    function Ra(h, m, w, s, u) {
        if (null === h || "undefined" === typeof h) return "undefined" === typeof w ? m : w;
        h = parseFloat(h.toString()) * (0 <= h.toString().indexOf("%") ? m / 100 : 1);
        "undefined" !== typeof s && (h = Math.min(s, h), "undefined" !== typeof u && (h = Math.max(u, h)));
        return !isNaN(h) && h <= m && 0 <= h ? h : "undefined" === typeof w ? m : w
    }

    function U(h, m, w, u, qa) {
        this._defaultsKey = h;
        this._themeOptionsKey = m;
        this._index = u;
        this.parent = qa;
        this._eventListeners = [];
        h = {};
        this.theme && s(this.parent) && s(m) && s(u) ? h = s(this.predefinedThemes[this.theme]) ? this.predefinedThemes.light1 : this.predefinedThemes[this.theme] : this.parent && (this.parent.themeOptions && this.parent.themeOptions[m]) && (null === u ? h = this.parent.themeOptions[m] : 0 < this.parent.themeOptions[m].length && (u = Math.min(this.parent.themeOptions[m].length - 1, u), h = this.parent.themeOptions[m][u]));
        this.themeOptions = h;
        this.options = w ? w : { _isPlaceholder: !0 };
        this.setOptions(this.options, h)
    }

    function Da(h, m, w, s, u) {
        "undefined" ===
        typeof u && (u = 0);
        this._padding = u;
        this._x1 = h;
        this._y1 = m;
        this._x2 = w;
        this._y2 = s;
        this._rightOccupied = this._leftOccupied = this._bottomOccupied = this._topOccupied = this._padding
    }

    function ia(h, m) {
        ia.base.constructor.call(this, "TextBlock", null, m, null, null);
        this.ctx = h;
        this._isDirty = !0;
        this._wrappedText = null;
        this._initialize()
    }

    function Sa(h, m) {
        Sa.base.constructor.call(this, "Toolbar", "toolbar", m, null, h);
        this.chart = h;
        this.canvas = h.canvas;
        this.ctx = this.chart.ctx;
        this.optionsName = "toolbar"
    }

    function ya(h, m) {
        ya.base.constructor.call(this,
            "Title", "title", m, null, h);
        this.chart = h;
        this.canvas = h.canvas;
        this.ctx = this.chart.ctx;
        this.optionsName = "title";
        if (s(this.options.margin) && h.options.subtitles)
            for (var w = h.options.subtitles, u = 0; u < w.length; u++)
                if ((s(w[u].horizontalAlign) && "center" === this.horizontalAlign || w[u].horizontalAlign === this.horizontalAlign) && (s(w[u].verticalAlign) && "top" === this.verticalAlign || w[u].verticalAlign === this.verticalAlign) && !w[u].dockInsidePlotArea === !this.dockInsidePlotArea) { this.margin = 0; break }
                "undefined" === typeof this.options.fontSize &&
            (this.fontSize = this.chart.getAutoFontSize(this.fontSize));
        this.height = this.width = null;
        this.bounds = { x1: null, y1: null, x2: null, y2: null }
    }

    function Ha(h, m, w) {
        Ha.base.constructor.call(this, "Subtitle", "subtitles", m, w, h);
        this.chart = h;
        this.canvas = h.canvas;
        this.ctx = this.chart.ctx;
        this.optionsName = "subtitles";
        this.isOptionsInArray = !0;
        "undefined" === typeof this.options.fontSize && (this.fontSize = this.chart.getAutoFontSize(this.fontSize));
        this.height = this.width = null;
        this.bounds = { x1: null, y1: null, x2: null, y2: null }
    }

    function Ta() {
        this.pool = []
    }

    function Ia(h) {
        var m;
        h && Ja[h] && (m = Ja[h]);
        Ia.base.constructor.call(this, "CultureInfo", null, m, null, null)
    }
    var Ga = !1,
        S = {},
        u = !!document.createElement("canvas").getContext,
        pa = {
            Chart: {
                width: 500,
                height: 400,
                zoomEnabled: !1,
                zoomType: "x",
                backgroundColor: "white",
                theme: "light1",
                animationEnabled: !1,
                animationDuration: 1200,
                dataPointWidth: null,
                dataPointMinWidth: null,
                dataPointMaxWidth: null,
                colorSet: "colorSet1",
                culture: "en",
                creditHref: "",
                // creditText: "CanvasJS",
                interactivityEnabled: !0,
                exportEnabled: !1,
                exportFileName: "Chart",
                rangeChanging: null,
                rangeChanged: null,
                publicProperties: { title: "readWrite", subtitles: "readWrite", toolbar: "readWrite", toolTip: "readWrite", legend: "readWrite", axisX: "readWrite", axisY: "readWrite", axisX2: "readWrite", axisY2: "readWrite", data: "readWrite", options: "readWrite", bounds: "readOnly", container: "readOnly", selectedColorSet: "readOnly" }
            },
            Title: {
                padding: 0,
                text: null,
                verticalAlign: "top",
                horizontalAlign: "center",
                fontSize: 20,
                fontFamily: "Calibri",
                fontWeight: "normal",
                fontColor: "black",
                fontStyle: "normal",
                borderThickness: 0,
                borderColor: "black",
                cornerRadius: 0,
                backgroundColor: u ? "transparent" : null,
                margin: 5,
                wrap: !0,
                maxWidth: null,
                dockInsidePlotArea: !1,
                publicProperties: { options: "readWrite", bounds: "readOnly", chart: "readOnly" }
            },
            Subtitle: {
                padding: 0,
                text: null,
                verticalAlign: "top",
                horizontalAlign: "center",
                fontSize: 14,
                fontFamily: "Calibri",
                fontWeight: "normal",
                fontColor: "black",
                fontStyle: "normal",
                borderThickness: 0,
                borderColor: "black",
                cornerRadius: 0,
                backgroundColor: null,
                margin: 2,
                wrap: !0,
                maxWidth: null,
                dockInsidePlotArea: !1,
                publicProperties: {
                    options: "readWrite",
                    bounds: "readOnly",
                    chart: "readOnly"
                }
            },
            Toolbar: { itemBackgroundColor: "white", itemBackgroundColorOnHover: "#2196f3", buttonBorderColor: "#2196f3", buttonBorderThickness: 1, fontColor: "black", fontColorOnHover: "white", publicProperties: { options: "readWrite", chart: "readOnly" } },
            Legend: {
                name: null,
                verticalAlign: "center",
                horizontalAlign: "right",
                fontSize: 14,
                fontFamily: "calibri",
                fontWeight: "normal",
                fontColor: "black",
                fontStyle: "normal",
                cursor: null,
                itemmouseover: null,
                itemmouseout: null,
                itemmousemove: null,
                itemclick: null,
                dockInsidePlotArea: !1,
                reversed: !1,
                backgroundColor: u ? "transparent" : null,
                borderColor: u ? "transparent" : null,
                borderThickness: 0,
                cornerRadius: 0,
                maxWidth: null,
                maxHeight: null,
                markerMargin: null,
                itemMaxWidth: null,
                itemWidth: null,
                itemWrap: !0,
                itemTextFormatter: null,
                publicProperties: { options: "readWrite", bounds: "readOnly", chart: "readOnly" }
            },
            ToolTip: {
                enabled: !0,
                shared: !1,
                animationEnabled: !0,
                content: null,
                contentFormatter: null,
                reversed: !1,
                backgroundColor: u ? "rgba(255,255,255,.9)" : "rgb(255,255,255)",
                borderColor: null,
                borderThickness: 2,
                cornerRadius: 5,
                fontSize: 14,
                fontColor: "black",
                fontFamily: "Calibri, Arial, Georgia, serif;",
                fontWeight: "normal",
                fontStyle: "italic",
                updated: null,
                hidden: null,
                publicProperties: { options: "readWrite", chart: "readOnly" }
            },
            Axis: {
                minimum: null,
                maximum: null,
                viewportMinimum: null,
                viewportMaximum: null,
                interval: null,
                intervalType: null,
                reversed: !1,
                logarithmic: !1,
                logarithmBase: 10,
                title: null,
                titleFontColor: "black",
                titleFontSize: 20,
                titleFontFamily: "arial",
                titleFontWeight: "normal",
                titleFontStyle: "normal",
                titleWrap: !0,
                titleMaxWidth: null,
                titleBackgroundColor: u ? "transparent" : null,
                titleBorderColor: u ? "transparent" : null,
                titleBorderThickness: 0,
                titleCornerRadius: 0,
                labelAngle: 0,
                labelFontFamily: "arial",
                labelFontColor: "black",
                labelFontSize: 12,
                labelFontWeight: "normal",
                labelFontStyle: "normal",
                labelAutoFit: !0,
                labelWrap: !0,
                labelMaxWidth: null,
                labelFormatter: null,
                labelBackgroundColor: u ? "transparent" : null,
                labelBorderColor: u ? "transparent" : null,
                labelBorderThickness: 0,
                labelCornerRadius: 0,
                labelPlacement: "outside",
                labelTextAlign: "left",
                prefix: "",
                suffix: "",
                includeZero: !1,
                tickLength: 5,
                tickColor: "black",
                tickThickness: 1,
                tickPlacement: "outside",
                lineColor: "black",
                lineThickness: 1,
                lineDashType: "solid",
                gridColor: "#A0A0A0",
                gridThickness: 0,
                gridDashType: "solid",
                interlacedColor: u ? "transparent" : null,
                valueFormatString: null,
                margin: 2,
                publicProperties: { options: "readWrite", stripLines: "readWrite", scaleBreaks: "readWrite", crosshair: "readWrite", bounds: "readOnly", chart: "readOnly" }
            },
            StripLine: {
                value: null,
                startValue: null,
                endValue: null,
                color: "orange",
                opacity: null,
                thickness: 2,
                lineDashType: "solid",
                label: "",
                labelPlacement: "inside",
                labelAlign: "far",
                labelWrap: !0,
                labelMaxWidth: null,
                labelBackgroundColor: null,
                labelBorderColor: u ? "transparent" : null,
                labelBorderThickness: 0,
                labelCornerRadius: 0,
                labelFontFamily: "arial",
                labelFontColor: "orange",
                labelFontSize: 12,
                labelFontWeight: "normal",
                labelFontStyle: "normal",
                labelFormatter: null,
                showOnTop: !1,
                publicProperties: { options: "readWrite", axis: "readOnly", bounds: "readOnly", chart: "readOnly" }
            },
            ScaleBreaks: {
                autoCalculate: !1,
                collapsibleThreshold: "25%",
                maxNumberOfAutoBreaks: 2,
                spacing: 8,
                type: "straight",
                color: "#FFFFFF",
                fillOpacity: 0.9,
                lineThickness: 2,
                lineColor: "#E16E6E",
                lineDashType: "solid",
                publicProperties: { options: "readWrite", customBreaks: "readWrite", axis: "readOnly", autoBreaks: "readOnly", bounds: "readOnly", chart: "readOnly" }
            },
            Break: {
                startValue: null,
                endValue: null,
                spacing: 8,
                type: "straight",
                color: "#FFFFFF",
                fillOpacity: 0.9,
                lineThickness: 2,
                lineColor: "#E16E6E",
                lineDashType: "solid",
                publicProperties: {
                    options: "readWrite",
                    scaleBreaks: "readOnly",
                    bounds: "readOnly",
                    chart: "readOnly"
                }
            },
            Crosshair: {
                enabled: !1,
                snapToDataPoint: !1,
                color: "grey",
                opacity: null,
                thickness: 2,
                lineDashType: "solid",
                label: "",
                labelWrap: !0,
                labelMaxWidth: null,
                labelBackgroundColor: u ? "grey" : null,
                labelBorderColor: u ? "grey" : null,
                labelBorderThickness: 0,
                labelCornerRadius: 0,
                labelFontFamily: u ? "Calibri, Optima, Candara, Verdana, Geneva, sans-serif" : "calibri",
                labelFontSize: 12,
                labelFontColor: "#fff",
                labelFontWeight: "normal",
                labelFontStyle: "normal",
                labelFormatter: null,
                valueFormatString: null,
                updated: null,
                hidden: null,
                publicProperties: { options: "readWrite", axis: "readOnly", bounds: "readOnly", chart: "readOnly" }
            },
            DataSeries: {
                name: null,
                dataPoints: null,
                label: "",
                bevelEnabled: !1,
                highlightEnabled: !0,
                cursor: "default",
                indexLabel: "",
                indexLabelPlacement: "auto",
                indexLabelOrientation: "horizontal",
                indexLabelTextAlign: "left",
                indexLabelFontColor: "black",
                indexLabelFontSize: 12,
                indexLabelFontStyle: "normal",
                indexLabelFontFamily: "Arial",
                indexLabelFontWeight: "normal",
                indexLabelBackgroundColor: null,
                indexLabelLineColor: "gray",
                indexLabelLineThickness: 1,
                indexLabelLineDashType: "solid",
                indexLabelMaxWidth: null,
                indexLabelWrap: !0,
                indexLabelFormatter: null,
                lineThickness: 2,
                lineDashType: "solid",
                connectNullData: !1,
                nullDataLineDashType: "dash",
                color: null,
                lineColor: null,
                risingColor: "white",
                fallingColor: "red",
                fillOpacity: null,
                startAngle: 0,
                radius: null,
                innerRadius: null,
                neckHeight: null,
                neckWidth: null,
                reversed: !1,
                valueRepresents: null,
                linkedDataSeriesIndex: null,
                whiskerThickness: 2,
                whiskerDashType: "solid",
                whiskerColor: null,
                whiskerLength: null,
                stemThickness: 2,
                stemColor: null,
                stemDashType: "solid",
                upperBoxColor: "white",
                lowerBoxColor: "white",
                type: "column",
                xValueType: "number",
                axisXType: "primary",
                axisYType: "primary",
                axisXIndex: 0,
                axisYIndex: 0,
                xValueFormatString: null,
                yValueFormatString: null,
                zValueFormatString: null,
                percentFormatString: null,
                showInLegend: null,
                legendMarkerType: null,
                legendMarkerColor: null,
                legendText: null,
                legendMarkerBorderColor: u ? "transparent" : null,
                legendMarkerBorderThickness: 0,
                markerType: "circle",
                markerColor: null,
                markerSize: null,
                markerBorderColor: u ? "transparent" : null,
                markerBorderThickness: 0,
                mouseover: null,
                mouseout: null,
                mousemove: null,
                click: null,
                toolTipContent: null,
                visible: !0,
                publicProperties: { options: "readWrite", axisX: "readWrite", axisY: "readWrite", chart: "readOnly" }
            },
            TextBlock: {
                x: 0,
                y: 0,
                width: null,
                height: null,
                maxWidth: null,
                maxHeight: null,
                padding: 0,
                angle: 0,
                text: "",
                horizontalAlign: "center",
                textAlign: "left",
                fontSize: 12,
                fontFamily: "calibri",
                fontWeight: "normal",
                fontColor: "black",
                fontStyle: "normal",
                borderThickness: 0,
                borderColor: "black",
                cornerRadius: 0,
                backgroundColor: null,
                textBaseline: "top"
            },
            CultureInfo: { decimalSeparator: ".", digitGroupSeparator: ",", zoomText: "Zoom", panText: "Pan", resetText: "Reset", menuText: "More Options", saveJPGText: "Save as JPEG", savePNGText: "Save as PNG", printText: "Print", days: "Sunday Monday Tuesday Wednesday Thursday Friday Saturday".split(" "), shortDays: "Sun Mon Tue Wed Thu Fri Sat".split(" "), months: "January February March April May June July August September October November December".split(" "), shortMonths: "Jan Feb Mar Apr May Jun Jul Aug Sep Oct Nov Dec".split(" ") }
        },
        Ja = { en: {} },
        y = u ? "Trebuchet MS, Helvetica, sans-serif" : "Arial",
        Ea = u ? "Impact, Charcoal, sans-serif" : "Arial",
        za = { colorSet1: "#4F81BC #C0504E #9BBB58 #23BFAA #8064A1 #4AACC5 #F79647 #7F6084 #77A033 #33558B #E59566".split(" "), colorSet2: "#6D78AD #51CDA0 #DF7970 #4C9CA0 #AE7D99 #C9D45C #5592AD #DF874D #52BCA8 #8E7AA3 #E3CB64 #C77B85 #C39762 #8DD17E #B57952 #FCC26C".split(" "), colorSet3: "#8CA1BC #36845C #017E82 #8CB9D0 #708C98 #94838D #F08891 #0366A7 #008276 #EE7757 #E5BA3A #F2990B #03557B #782970".split(" ") },
        H, $, Z, da, ha;
    $ = "#333333";
    Z = "#000000";
    H = "#666666";
    ha = da = "#000000";
    var T = 20,
        F = 14,
        Ua = {
            colorSet: "colorSet1",
            backgroundColor: "#FFFFFF",
            title: { fontFamily: Ea, fontSize: 32, fontColor: $, fontWeight: "normal", verticalAlign: "top", margin: 5 },
            subtitles: [{ fontFamily: Ea, fontSize: F, fontColor: $, fontWeight: "normal", verticalAlign: "top", margin: 5 }],
            data: [{ indexLabelFontFamily: y, indexLabelFontSize: F, indexLabelFontColor: $, indexLabelFontWeight: "normal", indexLabelLineThickness: 1 }],
            axisX: [{
                titleFontFamily: y,
                titleFontSize: T,
                titleFontColor: $,
                titleFontWeight: "normal",
                labelFontFamily: y,
                labelFontSize: F,
                labelFontColor: Z,
                labelFontWeight: "normal",
                lineThickness: 1,
                lineColor: H,
                tickThickness: 1,
                tickColor: H,
                gridThickness: 0,
                gridColor: H,
                stripLines: [{ labelFontFamily: y, labelFontSize: F, labelFontColor: "#FF7300", labelFontWeight: "normal", labelBackgroundColor: null, color: "#FF7300", thickness: 1 }],
                crosshair: { labelFontFamily: y, labelFontSize: F, labelFontColor: "#EEEEEE", labelFontWeight: "normal", labelBackgroundColor: ha, color: da, thickness: 1, lineDashType: "dash" },
                scaleBreaks: {
                    type: "zigzag",
                    spacing: "2%",
                    lineColor: "#BBBBBB",
                    lineThickness: 1,
                    lineDashType: "solid"
                }
            }],
            axisX2: [{
                titleFontFamily: y,
                titleFontSize: T,
                titleFontColor: $,
                titleFontWeight: "normal",
                labelFontFamily: y,
                labelFontSize: F,
                labelFontColor: Z,
                labelFontWeight: "normal",
                lineThickness: 1,
                lineColor: H,
                tickThickness: 1,
                tickColor: H,
                gridThickness: 0,
                gridColor: H,
                stripLines: [{ labelFontFamily: y, labelFontSize: F, labelFontColor: "#FF7300", labelFontWeight: "normal", labelBackgroundColor: null, color: "#FF7300", thickness: 1 }],
                crosshair: {
                    labelFontFamily: y,
                    labelFontSize: F,
                    labelFontColor: "#EEEEEE",
                    labelFontWeight: "normal",
                    labelBackgroundColor: ha,
                    color: da,
                    thickness: 1,
                    lineDashType: "dash"
                },
                scaleBreaks: { type: "zigzag", spacing: "2%", lineColor: "#BBBBBB", lineThickness: 1, lineDashType: "solid" }
            }],
            axisY: [{
                titleFontFamily: y,
                titleFontSize: T,
                titleFontColor: $,
                titleFontWeight: "normal",
                labelFontFamily: y,
                labelFontSize: F,
                labelFontColor: Z,
                labelFontWeight: "normal",
                lineThickness: 1,
                lineColor: H,
                tickThickness: 1,
                tickColor: H,
                gridThickness: 1,
                gridColor: H,
                stripLines: [{
                    labelFontFamily: y,
                    labelFontSize: F,
                    labelFontColor: "#FF7300",
                    labelFontWeight: "normal",
                    labelBackgroundColor: null,
                    color: "#FF7300",
                    thickness: 1
                }],
                crosshair: { labelFontFamily: y, labelFontSize: F, labelFontColor: "#EEEEEE", labelFontWeight: "normal", labelBackgroundColor: ha, color: da, thickness: 1, lineDashType: "dash" },
                scaleBreaks: { type: "zigzag", spacing: "2%", lineColor: "#BBBBBB", lineThickness: 1, lineDashType: "solid" }
            }],
            axisY2: [{
                titleFontFamily: y,
                titleFontSize: T,
                titleFontColor: $,
                titleFontWeight: "normal",
                labelFontFamily: y,
                labelFontSize: F,
                labelFontColor: Z,
                labelFontWeight: "normal",
                lineThickness: 1,
                lineColor: H,
                tickThickness: 1,
                tickColor: H,
                gridThickness: 1,
                gridColor: H,
                stripLines: [{ labelFontFamily: y, labelFontSize: F, labelFontColor: "#FF7300", labelFontWeight: "normal", labelBackgroundColor: null, color: "#FF7300", thickness: 1 }],
                crosshair: { labelFontFamily: y, labelFontSize: F, labelFontColor: "#EEEEEE", labelFontWeight: "normal", labelBackgroundColor: ha, color: da, thickness: 1, lineDashType: "dash" },
                scaleBreaks: { type: "zigzag", spacing: "2%", lineColor: "#BBBBBB", lineThickness: 1, lineDashType: "solid" }
            }],
            legend: { fontFamily: y, fontSize: 14, fontColor: $, fontWeight: "bold", verticalAlign: "bottom", horizontalAlign: "center" },
            toolTip: { fontFamily: y, fontSize: 14, fontStyle: "normal", cornerRadius: 0, borderThickness: 1 },
            toolbar: { itemBackgroundColor: "white", itemBackgroundColorOnHover: "#2196f3", buttonBorderColor: "#2196f3", buttonBorderThickness: 1, fontColor: "black", fontColorOnHover: "white" }
        };
    Z = $ = "#F5F5F5";
    H = "#FFFFFF";
    da = "#40BAF1";
    ha = "#F5F5F5";
    var T = 20,
        F = 14,
        $a = {
            colorSet: "colorSet2",
            title: {
                fontFamily: y,
                fontSize: 33,
                fontColor: "#3A3A3A",
                fontWeight: "bold",
                verticalAlign: "top",
                margin: 5
            },
            subtitles: [{ fontFamily: y, fontSize: F, fontColor: "#3A3A3A", fontWeight: "normal", verticalAlign: "top", margin: 5 }],
            data: [{ indexLabelFontFamily: y, indexLabelFontSize: F, indexLabelFontColor: "#666666", indexLabelFontWeight: "normal", indexLabelLineThickness: 1 }],
            axisX: [{
                titleFontFamily: y,
                titleFontSize: T,
                titleFontColor: "#666666",
                titleFontWeight: "normal",
                labelFontFamily: y,
                labelFontSize: F,
                labelFontColor: "#666666",
                labelFontWeight: "normal",
                lineThickness: 1,
                lineColor: "#BBBBBB",
                tickThickness: 1,
                tickColor: "#BBBBBB",
                gridThickness: 1,
                gridColor: "#BBBBBB",
                stripLines: [{ labelFontFamily: y, labelFontSize: F, labelFontColor: "#FFA500", labelFontWeight: "normal", labelBackgroundColor: null, color: "#FFA500", thickness: 1 }],
                crosshair: { labelFontFamily: y, labelFontSize: F, labelFontColor: "#EEEEEE", labelFontWeight: "normal", labelBackgroundColor: "black", color: "black", thickness: 1, lineDashType: "dot" },
                scaleBreaks: { type: "zigzag", spacing: "2%", lineColor: "#BBBBBB", lineThickness: 1, lineDashType: "solid" }
            }],
            axisX2: [{
                titleFontFamily: y,
                titleFontSize: T,
                titleFontColor: "#666666",
                titleFontWeight: "normal",
                labelFontFamily: y,
                labelFontSize: F,
                labelFontColor: "#666666",
                labelFontWeight: "normal",
                lineThickness: 1,
                lineColor: "#BBBBBB",
                tickColor: "#BBBBBB",
                tickThickness: 1,
                gridThickness: 1,
                gridColor: "#BBBBBB",
                stripLines: [{ labelFontFamily: y, labelFontSize: F, labelFontColor: "#FFA500", labelFontWeight: "normal", labelBackgroundColor: null, color: "#FFA500", thickness: 1 }],
                crosshair: {
                    labelFontFamily: y,
                    labelFontSize: F,
                    labelFontColor: "#EEEEEE",
                    labelFontWeight: "normal",
                    labelBackgroundColor: "black",
                    color: "black",
                    thickness: 1,
                    lineDashType: "dot"
                },
                scaleBreaks: { type: "zigzag", spacing: "2%", lineColor: "#BBBBBB", lineThickness: 1, lineDashType: "solid" }
            }],
            axisY: [{
                titleFontFamily: y,
                titleFontSize: T,
                titleFontColor: "#666666",
                titleFontWeight: "normal",
                labelFontFamily: y,
                labelFontSize: F,
                labelFontColor: "#666666",
                labelFontWeight: "normal",
                lineThickness: 0,
                lineColor: "#BBBBBB",
                tickColor: "#BBBBBB",
                tickThickness: 1,
                gridThickness: 1,
                gridColor: "#BBBBBB",
                stripLines: [{
                    labelFontFamily: y,
                    labelFontSize: F,
                    labelFontColor: "#FFA500",
                    labelFontWeight: "normal",
                    labelBackgroundColor: null,
                    color: "#FFA500",
                    thickness: 1
                }],
                crosshair: { labelFontFamily: y, labelFontSize: F, labelFontColor: "#EEEEEE", labelFontWeight: "normal", labelBackgroundColor: "black", color: "black", thickness: 1, lineDashType: "dot" },
                scaleBreaks: { type: "zigzag", spacing: "2%", lineColor: "#BBBBBB", lineThickness: 1, lineDashType: "solid" }
            }],
            axisY2: [{
                titleFontFamily: y,
                titleFontSize: T,
                titleFontColor: "#666666",
                titleFontWeight: "normal",
                labelFontFamily: y,
                labelFontSize: F,
                labelFontColor: "#666666",
                labelFontWeight: "normal",
                lineThickness: 0,
                lineColor: "#BBBBBB",
                tickColor: "#BBBBBB",
                tickThickness: 1,
                gridThickness: 1,
                gridColor: "#BBBBBB",
                stripLines: [{ labelFontFamily: y, labelFontSize: F, labelFontColor: "#FFA500", labelFontWeight: "normal", labelBackgroundColor: null, color: "#FFA500", thickness: 1 }],
                crosshair: { labelFontFamily: y, labelFontSize: F, labelFontColor: "#EEEEEE", labelFontWeight: "normal", labelBackgroundColor: "black", color: "black", thickness: 1, lineDashType: "dot" },
                scaleBreaks: {
                    type: "zigzag",
                    spacing: "2%",
                    lineColor: "#BBBBBB",
                    lineThickness: 1,
                    lineDashType: "solid"
                }
            }],
            legend: { fontFamily: y, fontSize: 14, fontColor: "#3A3A3A", fontWeight: "bold", verticalAlign: "bottom", horizontalAlign: "center" },
            toolTip: { fontFamily: y, fontSize: 14, fontStyle: "normal", cornerRadius: 0, borderThickness: 1 },
            toolbar: { itemBackgroundColor: "white", itemBackgroundColorOnHover: "#2196f3", buttonBorderColor: "#2196f3", buttonBorderThickness: 1, fontColor: "black", fontColorOnHover: "white" }
        };
    Z = $ = "#F5F5F5";
    H = "#FFFFFF";
    da = "#40BAF1";
    ha = "#F5F5F5";
    T = 20;
    F = 14;
    Ea = {
        colorSet: "colorSet12",
        backgroundColor: "#2A2A2A",
        title: { fontFamily: Ea, fontSize: 32, fontColor: $, fontWeight: "normal", verticalAlign: "top", margin: 5 },
        subtitles: [{ fontFamily: Ea, fontSize: F, fontColor: $, fontWeight: "normal", verticalAlign: "top", margin: 5 }],
        toolbar: { itemBackgroundColor: "#666666", itemBackgroundColorOnHover: "#FF7372", buttonBorderColor: "#FF7372", buttonBorderThickness: 1, fontColor: "#F5F5F5", fontColorOnHover: "#F5F5F5" },
        data: [{
            indexLabelFontFamily: y,
            indexLabelFontSize: F,
            indexLabelFontColor: Z,
            indexLabelFontWeight: "normal",
            indexLabelLineThickness: 1
        }],
        axisX: [{
            titleFontFamily: y,
            titleFontSize: T,
            titleFontColor: Z,
            titleFontWeight: "normal",
            labelFontFamily: y,
            labelFontSize: F,
            labelFontColor: Z,
            labelFontWeight: "normal",
            lineThickness: 1,
            lineColor: H,
            tickThickness: 1,
            tickColor: H,
            gridThickness: 0,
            gridColor: H,
            stripLines: [{ labelFontFamily: y, labelFontSize: F, labelFontColor: "#FF7300", labelFontWeight: "normal", labelBackgroundColor: null, color: "#FF7300", thickness: 1 }],
            crosshair: {
                labelFontFamily: y,
                labelFontSize: F,
                labelFontColor: "#000000",
                labelFontWeight: "normal",
                labelBackgroundColor: ha,
                color: da,
                thickness: 1,
                lineDashType: "dash"
            },
            scaleBreaks: { type: "zigzag", spacing: "2%", lineColor: "#777777", lineThickness: 1, lineDashType: "solid", color: "#111111" }
        }],
        axisX2: [{
            titleFontFamily: y,
            titleFontSize: T,
            titleFontColor: Z,
            titleFontWeight: "normal",
            labelFontFamily: y,
            labelFontSize: F,
            labelFontColor: Z,
            labelFontWeight: "normal",
            lineThickness: 1,
            lineColor: H,
            tickThickness: 1,
            tickColor: H,
            gridThickness: 0,
            gridColor: H,
            stripLines: [{
                labelFontFamily: y,
                labelFontSize: F,
                labelFontColor: "#FF7300",
                labelFontWeight: "normal",
                labelBackgroundColor: null,
                color: "#FF7300",
                thickness: 1
            }],
            crosshair: { labelFontFamily: y, labelFontSize: F, labelFontColor: "#000000", labelFontWeight: "normal", labelBackgroundColor: ha, color: da, thickness: 1, lineDashType: "dash" },
            scaleBreaks: { type: "zigzag", spacing: "2%", lineColor: "#777777", lineThickness: 1, lineDashType: "solid", color: "#111111" }
        }],
        axisY: [{
            titleFontFamily: y,
            titleFontSize: T,
            titleFontColor: Z,
            titleFontWeight: "normal",
            labelFontFamily: y,
            labelFontSize: F,
            labelFontColor: Z,
            labelFontWeight: "normal",
            lineThickness: 1,
            lineColor: H,
            tickThickness: 1,
            tickColor: H,
            gridThickness: 1,
            gridColor: H,
            stripLines: [{ labelFontFamily: y, labelFontSize: F, labelFontColor: "#FF7300", labelFontWeight: "normal", labelBackgroundColor: null, color: "#FF7300", thickness: 1 }],
            crosshair: { labelFontFamily: y, labelFontSize: F, labelFontColor: "#000000", labelFontWeight: "normal", labelBackgroundColor: ha, color: da, thickness: 1, lineDashType: "dash" },
            scaleBreaks: { type: "zigzag", spacing: "2%", lineColor: "#777777", lineThickness: 1, lineDashType: "solid", color: "#111111" }
        }],
        axisY2: [{
            titleFontFamily: y,
            titleFontSize: T,
            titleFontColor: Z,
            titleFontWeight: "normal",
            labelFontFamily: y,
            labelFontSize: F,
            labelFontColor: Z,
            labelFontWeight: "normal",
            lineThickness: 1,
            lineColor: H,
            tickThickness: 1,
            tickColor: H,
            gridThickness: 1,
            gridColor: H,
            stripLines: [{ labelFontFamily: y, labelFontSize: F, labelFontColor: "#FF7300", labelFontWeight: "normal", labelBackgroundColor: null, color: "#FF7300", thickness: 1 }],
            crosshair: {
                labelFontFamily: y,
                labelFontSize: F,
                labelFontColor: "#000000",
                labelFontWeight: "normal",
                labelBackgroundColor: ha,
                color: da,
                thickness: 1,
                lineDashType: "dash"
            },
            scaleBreaks: { type: "zigzag", spacing: "2%", lineColor: "#777777", lineThickness: 1, lineDashType: "solid", color: "#111111" }
        }],
        legend: { fontFamily: y, fontSize: 14, fontColor: $, fontWeight: "bold", verticalAlign: "bottom", horizontalAlign: "center" },
        toolTip: { fontFamily: y, fontSize: 14, fontStyle: "normal", cornerRadius: 0, borderThickness: 1, fontColor: Z, backgroundColor: "rgba(0, 0, 0, .7)" }
    };
    H = "#FFFFFF";
    Z = $ = "#FAFAFA";
    da = "#40BAF1";
    ha = "#F5F5F5";
    var T = 20,
        F = 14,
        ab = {
            light1: Ua,
            light2: $a,
            dark1: Ea,
            dark2: {
                colorSet: "colorSet2",
                backgroundColor: "#32373A",
                title: { fontFamily: y, fontSize: 32, fontColor: $, fontWeight: "normal", verticalAlign: "top", margin: 5 },
                subtitles: [{ fontFamily: y, fontSize: F, fontColor: $, fontWeight: "normal", verticalAlign: "top", margin: 5 }],
                toolbar: { itemBackgroundColor: "#666666", itemBackgroundColorOnHover: "#FF7372", buttonBorderColor: "#FF7372", buttonBorderThickness: 1, fontColor: "#F5F5F5", fontColorOnHover: "#F5F5F5" },
                data: [{
                    indexLabelFontFamily: y,
                    indexLabelFontSize: F,
                    indexLabelFontColor: Z,
                    indexLabelFontWeight: "normal",
                    indexLabelLineThickness: 1
                }],
                axisX: [{
                    titleFontFamily: y,
                    titleFontSize: T,
                    titleFontColor: Z,
                    titleFontWeight: "normal",
                    labelFontFamily: y,
                    labelFontSize: F,
                    labelFontColor: Z,
                    labelFontWeight: "normal",
                    lineThickness: 1,
                    lineColor: H,
                    tickThickness: 1,
                    tickColor: H,
                    gridThickness: 0,
                    gridColor: H,
                    stripLines: [{ labelFontFamily: y, labelFontSize: F, labelFontColor: "#FF7300", labelFontWeight: "normal", labelBackgroundColor: null, color: "#FF7300", thickness: 1 }],
                    crosshair: {
                        labelFontFamily: y,
                        labelFontSize: F,
                        labelFontColor: "#000000",
                        labelFontWeight: "normal",
                        labelBackgroundColor: ha,
                        color: da,
                        thickness: 1,
                        lineDashType: "dash"
                    },
                    scaleBreaks: { type: "zigzag", spacing: "2%", lineColor: "#777777", lineThickness: 1, lineDashType: "solid", color: "#111111" }
                }],
                axisX2: [{
                    titleFontFamily: y,
                    titleFontSize: T,
                    titleFontColor: Z,
                    titleFontWeight: "normal",
                    labelFontFamily: y,
                    labelFontSize: F,
                    labelFontColor: Z,
                    labelFontWeight: "normal",
                    lineThickness: 1,
                    lineColor: H,
                    tickThickness: 1,
                    tickColor: H,
                    gridThickness: 0,
                    gridColor: H,
                    stripLines: [{
                        labelFontFamily: y,
                        labelFontSize: F,
                        labelFontColor: "#FF7300",
                        labelFontWeight: "normal",
                        labelBackgroundColor: null,
                        color: "#FF7300",
                        thickness: 1
                    }],
                    crosshair: { labelFontFamily: y, labelFontSize: F, labelFontColor: "#000000", labelFontWeight: "normal", labelBackgroundColor: ha, color: da, thickness: 1, lineDashType: "dash" },
                    scaleBreaks: { type: "zigzag", spacing: "2%", lineColor: "#777777", lineThickness: 1, lineDashType: "solid", color: "#111111" }
                }],
                axisY: [{
                    titleFontFamily: y,
                    titleFontSize: T,
                    titleFontColor: Z,
                    titleFontWeight: "normal",
                    labelFontFamily: y,
                    labelFontSize: F,
                    labelFontColor: Z,
                    labelFontWeight: "normal",
                    lineThickness: 0,
                    lineColor: H,
                    tickThickness: 1,
                    tickColor: H,
                    gridThickness: 1,
                    gridColor: H,
                    stripLines: [{ labelFontFamily: y, labelFontSize: F, labelFontColor: "#FF7300", labelFontWeight: "normal", labelBackgroundColor: null, color: "#FF7300", thickness: 1 }],
                    crosshair: { labelFontFamily: y, labelFontSize: F, labelFontColor: "#000000", labelFontWeight: "normal", labelBackgroundColor: ha, color: da, thickness: 1, lineDashType: "dash" },
                    scaleBreaks: { type: "zigzag", spacing: "2%", lineColor: "#777777", lineThickness: 1, lineDashType: "solid", color: "#111111" }
                }],
                axisY2: [{
                    titleFontFamily: y,
                    titleFontSize: T,
                    titleFontColor: Z,
                    titleFontWeight: "normal",
                    labelFontFamily: y,
                    labelFontSize: F,
                    labelFontColor: Z,
                    labelFontWeight: "normal",
                    lineThickness: 0,
                    lineColor: H,
                    tickThickness: 1,
                    tickColor: H,
                    gridThickness: 1,
                    gridColor: H,
                    stripLines: [{ labelFontFamily: y, labelFontSize: F, labelFontColor: "#FF7300", labelFontWeight: "normal", labelBackgroundColor: null, color: "#FF7300", thickness: 1 }],
                    crosshair: {
                        labelFontFamily: y,
                        labelFontSize: F,
                        labelFontColor: "#000000",
                        labelFontWeight: "normal",
                        labelBackgroundColor: ha,
                        color: da,
                        thickness: 1,
                        lineDashType: "dash"
                    },
                    scaleBreaks: { type: "zigzag", spacing: "2%", lineColor: "#777777", lineThickness: 1, lineDashType: "solid", color: "#111111" }
                }],
                legend: { fontFamily: y, fontSize: 14, fontColor: $, fontWeight: "bold", verticalAlign: "bottom", horizontalAlign: "center" },
                toolTip: { fontFamily: y, fontSize: 14, fontStyle: "normal", cornerRadius: 0, borderThickness: 1, fontColor: Z, backgroundColor: "rgba(0, 0, 0, .7)" }
            },
            theme1: Ua,
            theme2: $a,
            theme3: Ua
        },
        R = {
            numberDuration: 1,
            yearDuration: 314496E5,
            monthDuration: 2592E6,
            weekDuration: 6048E5,
            dayDuration: 864E5,
            hourDuration: 36E5,
            minuteDuration: 6E4,
            secondDuration: 1E3,
            millisecondDuration: 1,
            dayOfWeekFromInt: "Sunday Monday Tuesday Wednesday Thursday Friday Saturday".split(" ")
        };
    (function() {
        S.fSDec = function(h) { for (var m = "", w = 0; w < h.length; w++) m += String.fromCharCode(Math.ceil(h.length / 57 / 5) ^ h.charCodeAt(w)); return m };
        delete pa[S.fSDec("Bi`su")][S.fSDec("bsdehuIsdg")];
        S.pro = { sCH: pa[S.fSDec("Bi`su")][S.fSDec("bsdehuIsdg")] };
        S.pos = ["cm", "cs", "um"];
        S._fTWm = function(h) {
            if ("undefined" === typeof S.pro.sCH && !bb) try {
                var m =
                    (new Date).getMonth() % 3,
                    w, s, u = h[S.fSDec("buy")];
                u[S.fSDec("udyuC`rdmhod")] = S.fSDec("unq");
                u[S.fSDec("gnou")] = 11 + S.fSDec("qy!B`mhcsh-!Mtbhe`!Fs`oed-!Mtbhe`!R`or!Tohbned-!@sh`m-!r`or,rdshg");
                u[S.fSDec("ghmmRuxmd")] = S.fSDec("fsdx");
                w = "cm" === S.pos[m] || "um" === S.pos[m] ? 0 : h.width - u[S.fSDec("ld`rtsdUdyu")](S.fSDec("B`ow`rKR!Ush`m")).width;
                s = "um" === S.pos[m] ? 0 : h.height - 11;
                u[S.fSDec("ghmmUdyu")](S.fSDec("B`ow`rKR!Ush`m"), w, s);
                "cs" === S.pos[m] && (h[S.fSDec("^bsdehuMhoj")].style.right = S.fSDec("`tun"))
            } catch (y) {}
        }
    })();
    var fb = function() {
            var h = !1;
            try {
                var m = Object.defineProperty && Object.defineProperty({}, "passive", { get: function() { h = !0; return !1 } });
                window.addEventListener && (window.addEventListener("test", null, m), window.removeEventListener("test", null, m))
            } catch (w) { h = !1 }
            return h
        }(),
        Xa = {},
        wa = null,
        kb = function() {
            this.ctx.clearRect(0, 0, this.width, this.height);
            this.backgroundColor && (this.ctx.fillStyle = this.backgroundColor, this.ctx.fillRect(0, 0, this.width, this.height))
        },
        lb = function(h, m, w) {
            m = Math.min(this.width, this.height);
            return Math.max("theme4" === this.theme ? 0 : 300 <= m ? 12 : 11, Math.round(m * (h / 400)))
        },
        Aa = function() {
            var h = /D{1,4}|M{1,4}|Y{1,4}|h{1,2}|H{1,2}|m{1,2}|s{1,2}|f{1,3}|t{1,2}|T{1,2}|K|z{1,3}|"[^"]*"|'[^']*'/g,
                m = "Sunday Monday Tuesday Wednesday Thursday Friday Saturday".split(" "),
                w = "Sun Mon Tue Wed Thu Fri Sat".split(" "),
                s = "January February March April May June July August September October November December".split(" "),
                u = "Jan Feb Mar Apr May Jun Jul Aug Sep Oct Nov Dec".split(" "),
                y = /\b(?:[PMCEA][SDP]T|(?:Pacific|Mountain|Central|Eastern|Atlantic) (?:Standard|Daylight|Prevailing) Time|(?:GMT|UTC)(?:[-+]\d{4})?)\b/g,
                D = /[^-+\dA-Z]/g;
            return function(F, N, M) {
                var H = M ? M.days : m,
                    P = M ? M.months : s,
                    J = M ? M.shortDays : w,
                    R = M ? M.shortMonths : u;
                M = "";
                var S = !1;
                F = F && F.getTime ? F : F ? new Date(F) : new Date;
                if (isNaN(F)) throw SyntaxError("invalid date");
                "UTC:" === N.slice(0, 4) && (N = N.slice(4), S = !0);
                M = S ? "getUTC" : "get";
                var U = F[M + "Date"](),
                    K = F[M + "Day"](),
                    V = F[M + "Month"](),
                    a = F[M + "FullYear"](),
                    d = F[M + "Hours"](),
                    c = F[M + "Minutes"](),
                    b = F[M + "Seconds"](),
                    e = F[M + "Milliseconds"](),
                    f = S ? 0 : F.getTimezoneOffset();
                return M = N.replace(h, function(l) {
                    switch (l) {
                        case "D":
                            return U;
                        case "DD":
                            return X(U, 2);
                        case "DDD":
                            return J[K];
                        case "DDDD":
                            return H[K];
                        case "M":
                            return V + 1;
                        case "MM":
                            return X(V + 1, 2);
                        case "MMM":
                            return R[V];
                        case "MMMM":
                            return P[V];
                        case "Y":
                            return parseInt(String(a).slice(-2));
                        case "YY":
                            return X(String(a).slice(-2), 2);
                        case "YYY":
                            return X(String(a).slice(-3), 3);
                        case "YYYY":
                            return X(a, 4);
                        case "h":
                            return d % 12 || 12;
                        case "hh":
                            return X(d % 12 || 12, 2);
                        case "H":
                            return d;
                        case "HH":
                            return X(d, 2);
                        case "m":
                            return c;
                        case "mm":
                            return X(c, 2);
                        case "s":
                            return b;
                        case "ss":
                            return X(b, 2);
                        case "f":
                            return X(String(e), 3).slice(0, 1);
                        case "ff":
                            return X(String(e), 3).slice(0, 2);
                        case "fff":
                            return X(String(e), 3).slice(0, 3);
                        case "t":
                            return 12 > d ? "a" : "p";
                        case "tt":
                            return 12 > d ? "am" : "pm";
                        case "T":
                            return 12 > d ? "A" : "P";
                        case "TT":
                            return 12 > d ? "AM" : "PM";
                        case "K":
                            return S ? "UTC" : (String(F).match(y) || [""]).pop().replace(D, "");
                        case "z":
                            return (0 < f ? "-" : "+") + Math.floor(Math.abs(f) / 60);
                        case "zz":
                            return (0 < f ? "-" : "+") + X(Math.floor(Math.abs(f) / 60), 2);
                        case "zzz":
                            return (0 < f ? "-" : "+") + X(Math.floor(Math.abs(f) / 60), 2) +
                                X(Math.abs(f) % 60, 2);
                        default:
                            return l.slice(1, l.length - 1)
                    }
                })
            }
        }(),
        ea = function(h, m, w) {
            if (null === h) return "";
            if (!isFinite(h)) return h;
            h = Number(h);
            var s = 0 > h ? !0 : !1;
            s && (h *= -1);
            var u = w ? w.decimalSeparator : ".",
                y = w ? w.digitGroupSeparator : ",",
                D = "";
            m = String(m);
            var D = 1,
                F = w = "",
                N = -1,
                M = [],
                H = [],
                P = 0,
                J = 0,
                R = 0,
                S = !1,
                U = 0,
                F = m.match(/"[^"]*"|'[^']*'|[eE][+-]*[0]+|[,]+[.]|\u2030|./g);
            m = null;
            for (var K = 0; F && K < F.length; K++)
                if (m = F[K], "." === m && 0 > N) N = K;
                else {
                    if ("%" === m) D *= 100;
                    else if ("\u2030" === m) { D *= 1E3; continue } else if ("," === m[0] &&
                        "." === m[m.length - 1]) {
                        D /= Math.pow(1E3, m.length - 1);
                        N = K + m.length - 1;
                        continue
                    } else "E" !== m[0] && "e" !== m[0] || "0" !== m[m.length - 1] || (S = !0);
                    0 > N ? (M.push(m), "#" === m || "0" === m ? P++ : "," === m && R++) : (H.push(m), "#" !== m && "0" !== m || J++)
                }
            S && (m = Math.floor(h), F = -Math.floor(Math.log(h) / Math.LN10 + 1), U = 0 === h ? 0 : 0 === m ? -(P + F) : String(m).length - P, D /= Math.pow(10, U));
            0 > N && (N = K);
            D = (h * D).toFixed(J);
            m = D.split(".");
            D = (m[0] + "").split("");
            h = (m[1] + "").split("");
            D && "0" === D[0] && D.shift();
            for (S = F = K = J = N = 0; 0 < M.length;)
                if (m = M.pop(), "#" === m || "0" ===
                    m)
                    if (N++, N === P) {
                        var V = D,
                            D = [];
                        if ("0" === m)
                            for (m = P - J - (V ? V.length : 0); 0 < m;) V.unshift("0"), m--;
                        for (; 0 < V.length;) w = V.pop() + w, S++, 0 === S % F && (K === R && 0 < V.length) && (w = y + w)
                    } else 0 < D.length ? (w = D.pop() + w, J++, S++) : "0" === m && (w = "0" + w, J++, S++), 0 === S % F && (K === R && 0 < D.length) && (w = y + w);
            else "E" !== m[0] && "e" !== m[0] || "0" !== m[m.length - 1] || !/[eE][+-]*[0]+/.test(m) ? "," === m ? (K++, F = S, S = 0, 0 < D.length && (w = y + w)) : w = 1 < m.length && ('"' === m[0] && '"' === m[m.length - 1] || "'" === m[0] && "'" === m[m.length - 1]) ? m.slice(1, m.length - 1) + w : m + w : (m = 0 > U ? m.replace("+",
                "").replace("-", "") : m.replace("-", ""), w += m.replace(/[0]+/, function(a) { return X(U, a.length) }));
            y = "";
            for (M = !1; 0 < H.length;) m = H.shift(), "#" === m || "0" === m ? 0 < h.length && 0 !== Number(h.join("")) ? (y += h.shift(), M = !0) : "0" === m && (y += "0", M = !0) : 1 < m.length && ('"' === m[0] && '"' === m[m.length - 1] || "'" === m[0] && "'" === m[m.length - 1]) ? y += m.slice(1, m.length - 1) : "E" !== m[0] && "e" !== m[0] || "0" !== m[m.length - 1] || !/[eE][+-]*[0]+/.test(m) ? y += m : (m = 0 > U ? m.replace("+", "").replace("-", "") : m.replace("-", ""), y += m.replace(/[0]+/, function(a) {
                return X(U,
                    a.length)
            }));
            w += (M ? u : "") + y;
            return s ? "-" + w : w
        },
        Na = function(h) {
            var m = 0,
                w = 0;
            h = h || window.event;
            h.offsetX || 0 === h.offsetX ? (m = h.offsetX, w = h.offsetY) : h.layerX || 0 == h.layerX ? (m = h.layerX, w = h.layerY) : (m = h.pageX - h.target.offsetLeft, w = h.pageY - h.target.offsetTop);
            return { x: m, y: w }
        },
        Za = !0,
        Qa = window.devicePixelRatio || 1,
        Ma = 1,
        ka = Za ? Qa / Ma : 1,
        ca = function(h, m, w, s, u, y, D, F, N, M, P, S, H) {
            "undefined" === typeof H && (H = 1);
            D = D || 0;
            F = F || "black";
            var J = 15 < s - m && 15 < u - w ? 8 : 0.35 * Math.min(s - m, u - w);
            h.beginPath();
            h.moveTo(m, w);
            h.save();
            h.fillStyle =
                y;
            h.globalAlpha = H;
            h.fillRect(m, w, s - m, u - w);
            h.globalAlpha = 1;
            0 < D && (H = 0 === D % 2 ? 0 : 0.5, h.beginPath(), h.lineWidth = D, h.strokeStyle = F, h.moveTo(m, w), h.rect(m - H, w - H, s - m + 2 * H, u - w + 2 * H), h.stroke());
            h.restore();
            !0 === N && (h.save(), h.beginPath(), h.moveTo(m, w), h.lineTo(m + J, w + J), h.lineTo(s - J, w + J), h.lineTo(s, w), h.closePath(), D = h.createLinearGradient((s + m) / 2, w + J, (s + m) / 2, w), D.addColorStop(0, y), D.addColorStop(1, "rgba(255, 255, 255, .4)"), h.fillStyle = D, h.fill(), h.restore());
            !0 === M && (h.save(), h.beginPath(), h.moveTo(m, u), h.lineTo(m +
                J, u - J), h.lineTo(s - J, u - J), h.lineTo(s, u), h.closePath(), D = h.createLinearGradient((s + m) / 2, u - J, (s + m) / 2, u), D.addColorStop(0, y), D.addColorStop(1, "rgba(255, 255, 255, .4)"), h.fillStyle = D, h.fill(), h.restore());
            !0 === P && (h.save(), h.beginPath(), h.moveTo(m, w), h.lineTo(m + J, w + J), h.lineTo(m + J, u - J), h.lineTo(m, u), h.closePath(), D = h.createLinearGradient(m + J, (u + w) / 2, m, (u + w) / 2), D.addColorStop(0, y), D.addColorStop(1, "rgba(255, 255, 255, 0.1)"), h.fillStyle = D, h.fill(), h.restore());
            !0 === S && (h.save(), h.beginPath(), h.moveTo(s,
                w), h.lineTo(s - J, w + J), h.lineTo(s - J, u - J), h.lineTo(s, u), D = h.createLinearGradient(s - J, (u + w) / 2, s, (u + w) / 2), D.addColorStop(0, y), D.addColorStop(1, "rgba(255, 255, 255, 0.1)"), h.fillStyle = D, D.addColorStop(0, y), D.addColorStop(1, "rgba(255, 255, 255, 0.1)"), h.fillStyle = D, h.fill(), h.closePath(), h.restore())
        },
        W = function(h) { for (var m = "", s = 0; s < h.length; s++) m += String.fromCharCode(Math.ceil(h.length / 57 / 5) ^ h.charCodeAt(s)); return m },
        bb = window && window[W("mnb`uhno")] && window[W("mnb`uhno")].href && window[W("mnb`uhno")].href.indexOf &&
        (-1 !== window[W("mnb`uhno")].href.indexOf(W("b`ow`rkr/bnl")) || -1 !== window[W("mnb`uhno")].href.indexOf(W("gdonqhy/bnl")) || -1 !== window[W("mnb`uhno")].href.indexOf(W("gheemd"))),
        ib = bb && -1 === window[W("mnb`uhno")].href.indexOf(W("gheemd")),
        jb = {
            reset: { image: "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACIAAAAeCAYAAABJ/8wUAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAPjSURBVFhHxVdJaFNRFP1J/jwkP5MxsbaC1WJEglSxOFAXIsFpVRE3ggi1K90obioRRBA33XXnQnciirhQcMCdorgQxBkXWlREkFKsWkv5npvckp/XnzRpKh64kLw733fffe9L/wrL0+mVUdO8uTSZ3MBL/we2qg4rkuSpodCELstXE46ziVkLQ6FQcGOmeSSq6wd4aV50d3drWjj8kQKZJTUc9kxFGenv79dZrDksTSTWWJp2QYtEPiErysyzdX0LsxsCQR8keX8gs6RHIk8ysdgKFg2G53mhuOPsshTlBjKaFo1g7SqLNoShKLdFXT8huQ/paLSbxatYnc2mHMM4hr18Vi8TIvCmXF3vYrW6cF23gGTOk0M1wA4RKvOmq6vLZRVJipvmSWT6tZ6CSEYkco5V50VPT4+D7RwOqi6RiSZm0fJ+vggSqkeoypdsNmuyelNwbXsbgvkWYMtzDWNvWaijoyOBqE+hVK8abcssUeXQ/YfKyi0gFYv1Ipgfoj34fYGTJLOYJA0ODirok32GLN8XhUWCwSes1hIwBg6LydJ/tEeRRapAdUp+wSAiZchtZZWWgAZ+JNpD8peYXQVK9UwUxNpzOK8pq97kURZhYTCKBwPD7h2zK+js7Myi7D8Fod+0TkMI8+EMAngLGc/WtBFWawkFHFnoj/t9KLgGmF0B3QfkxC+EarxkdhnFYlFLY06USqUwL7UMjICHfh/wOc2sCqhpxGbCkLvL7EUDbF73+6DkmVWB6zi7xUDQSLeYvWjAILvm9zEnkJhlbRcDQZcv6Kg2AipyT/Axw6wKlqVSqxDdjF8Izfod13qURdrG/nxehY+xGh+h0CSzKygGvSNQIcc097BI24jb9hax6kj2E7OrMFX1il+ICEf2NrPbhiXLl+fYl+U7zK4iYdsDcyLGf+ofFlkwcN+s10KhmpuYhhtm0hCLVIFL0MDsqNlDIqy9x2CLs1jL6OvrI7vPRbtohXG6eFmsFnHDGAp6n9AgyuVySRZrGvROxRgIfLXhzjrNYnNBUxNX/dMgRWT1mt4XLDovaApD53E9W3ilNX5M55LJHpRtIsgAvciR4WWcgK2Dvb1YqgXevmF8z2zEBTcKG39EfSKsT9EbhVUaI2FZO+oZIqImxol6j66/hcAu4sSN4vc1ZPoKeoE6RGhYL2YYA+ymOSSi0Z0wWntbtkGUWCvfSDXIxONraZ/FY90KUfNTpfC5spnNLgxoYNnR9RO4F8ofXEHOgogCQE99w+fF2Xw+b7O59rEOsyRqGEfpVoaDMQQ1CZrG46bcM6AZ0C/wPqNfHliqejyTySxh9TqQpL+xmbIlkB9SlAAAAABJRU5ErkJggg==" },
            pan: { image: "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAICSURBVEhLxZbPahNRGMUn/5MpuAiBEAIufQGfzr5E40YptBXajYzudCEuGqS+gGlrFwquDGRTutBdYfydzJ3LzeQmJGZue+Dw/Z17Mnfmu5Pof9Hr9Z61Wq0bWZMKj263O6xWq99wU9lOpzPMKgEhEcRucNOcioOK+0RzBhNvt9tPV4nmVF19+OWhVqt9xXgFXZq+8lCv119UKpUJ7iX2FmvFTKz8RH34YdBsNk8wVtjE4fGYwm8wrrDi3WBG5oKXZGRSS9hGuNFojLTe2lFz5xThWZIktayyiE2FdT3rzXBXz7krKiL8c17wAKFDjCus2AvW+YGZ9y2JF0VFRuMPfI//rsCE/C+s26s4gQu9ul7r4NteKx7H8XOC724xNNGbaNu++IrBqbOV7Tj3FgMRvc/YKOr3+3sE47wgEt/Bl/gaK5cHbNU11vYSXylfpK7XOvjuumPp4Wcoipu30Qsez2uMXYz4lfI+mOmwothY+SLiXJy7mKVpWs3Si0CoOMfeI9Od43Wic+jO+ZVv+crsm9QSNhUW9LXSeoPBYLXopthGuFQgdIxxhY+UDwlt1x5CZ1hX+NTUdt/OIvjKaDSmuOJfaIVNPKX+W18j/PLA2/kR44p5Sd8HbHngT/yTfNRWUXX14ZcL3wmX0+TLf8YO7CGT8yFE5zB3/gney25/OETRP9CtPDFe5jShAAAAAElFTkSuQmCC" },
            zoom: { image: "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAAAAJcEhZcwAADsMAAA7DAcdvqGQAAALWSURBVEhLvZZLaBNRFIabyftBIgEfqCCBoCC6MYqiXYiIj4U76U4X7sUHbhQhUBfixhZEUBDB16YuFERaUaQLK7ooCOJj4UKtYEFU0EptShO/A9Ph3js3k8lo/eHnP7n3nP/M3LlzMz1hkUwmNziOcyKRSFyFt+LxeD/c2Wq1Ym7Kv0M2m11Os1OxWGycn1OwZXCGuXfwIhezkd9/jRgNT2L4ldhs1pbkX5OLJe4euVxuGQaPCa3mnUjtJx7BDuKusJTCV6jVVGHTMuYRjxma7yIOhTgFY6jNaAKew2xPKpVay9ganmkvj+M448/MfJdT5K5Gg4HJacRngPFgqVRaRNwW1B4i7yehWfsEDdz1K+A01AoxPIqGAiuwGfkOTY8+1A6u7AyiFTB2Hu0KPIrdiOnzHLWDybeImvy+Wq2mZa5bUHsD0Zpz+KxHdWQymV6kAb1ElqeORgJLvgnRdj1+R1AfzkIvSUjxVjQSarVakrueIPT8+H1F5jSUy+WXiJrUYBVWyVxU4PEU8TzhfaijUqnMIWrjaY492eWRwdKOIqrnIxnXwLLeRLwk2GQzrEMjg0avEbXxkIxr4OoOImpj2QwyFgms1koa/SZUG8s+0iGnEhNfCNXEhzIXBVz0McTzEvJ+70P9oNFtxEzei3aFYrFYxmuSUPWSv9Yi9IMm2xE1We56Mp1OV4nDwqFmBDV9gk9AEh4gZtFHNt8W4kAUCoXF5MorY9Z/kDni9nDv7hc0i2fhgLvTtX8a99PoMPPagTFPxofRzmDJ9yM+AyEmTfgGysYbQcfhDzPPJDmX0c7gDg4gs9BqFIWhm/Nct5H8gtBq1I7UfIbtvmIuoaGQcp+fdpbbSM43eEH5wrwLbXmhm/fU63VHXjcuok7hEByFY/AeHGC8L5/PL3HT5xGH1uYwfPOICGo+CBcU0vwO1BqzUqILDl/z/9VYIMfpddiAc47jDP8BsUpb13wOLRwAAAAASUVORK5CYII=" },
            menu: { image: "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACQAAAAeCAYAAABE4bxTAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAAAAJcEhZcwAADsMAAA7DAcdvqGQAAADoSURBVFhH7dc9CsJAFATgRxIIBCwCqZKATX5sbawsY2MvWOtF9AB6AU8gguAJbD2AnZ2VXQT/Ko2TYGCL2OYtYQc+BuYA+1hCtnCVwMm27SGaXpDJIAiCvCkVR05hGOZNN3HkFMdx3nQRR06+76/R1IcFLJlNQEWlmWlBTwJtKLKHynehZqnjOGM0PYWRVXk61C37p7xlZ3Hk5HneCk1dmMH811xGoKLSzDiQwIBZB4ocoPJdqNkDt2yKlueWRVGUtzy3rPwo3sWRU3nLjuLI6OO67oZM00wMw3hrmpZx0XU9syxrR0T0BeMpb9dneSR2AAAAAElFTkSuQmCC" }
        };
    U.prototype.setOptions = function(h, m) {
        if (pa[this._defaultsKey]) {
            var s = pa[this._defaultsKey],
                u;
            for (u in s) "publicProperties" !== u && s.hasOwnProperty(u) && (this[u] = h && u in h ? h[u] : m && u in m ? m[u] : s[u])
        } else Ga && window.console && console.log("defaults not set")
    };
    U.prototype.get = function(h) {
        var m = pa[this._defaultsKey];
        if ("options" === h) return this.options && this.options._isPlaceholder ? null : this.options;
        if (m.hasOwnProperty(h) || m.publicProperties && m.publicProperties.hasOwnProperty(h)) return this[h];
        window.console &&
            window.console.log('Property "' + h + "\" doesn't exist. Please check for typo.")
    };
    U.prototype.set = function(h, m, s) {
        s = "undefined" === typeof s ? !0 : s;
        var u = pa[this._defaultsKey];
        if ("options" === h) this.createUserOptions(m);
        else if (u.hasOwnProperty(h) || u.publicProperties && u.publicProperties.hasOwnProperty(h) && "readWrite" === u.publicProperties[h]) this.options._isPlaceholder && this.createUserOptions(), this.options[h] = m;
        else {
            window.console && (u.publicProperties && u.publicProperties.hasOwnProperty(h) && "readOnly" === u.publicProperties[h] ?
                window.console.log('Property "' + h + '" is read-only.') : window.console.log('Property "' + h + "\" doesn't exist. Please check for typo."));
            return
        }
        s && (this.stockChart || this.chart || this).render()
    };
    U.prototype.addTo = function(h, m, s, u) {
        u = "undefined" === typeof u ? !0 : u;
        var y = pa[this._defaultsKey];
        y.hasOwnProperty(h) || y.publicProperties && y.publicProperties.hasOwnProperty(h) && "readWrite" === y.publicProperties[h] ? (this.options._isPlaceholder && this.createUserOptions(), "undefined" === typeof this.options[h] && (this.options[h] = []), h = this.options[h], s = "undefined" === typeof s || null === s ? h.length : s, h.splice(s, 0, m), u && (this.stockChart || this.chart || this).render()) : window.console && (y.publicProperties && y.publicProperties.hasOwnProperty(h) && "readOnly" === y.publicProperties[h] ? window.console.log('Property "' + h + '" is read-only.') : window.console.log('Property "' + h + "\" doesn't exist. Please check for typo."))
    };
    U.prototype.createUserOptions = function(h) {
        if ("undefined" !== typeof h || this.options._isPlaceholder)
            if (this.parent.options._isPlaceholder &&
                this.parent.createUserOptions(), this.isOptionsInArray) {
                this.parent.options[this.optionsName] || (this.parent.options[this.optionsName] = []);
                var m = this.parent.options[this.optionsName],
                    s = m.length;
                this.options._isPlaceholder || (Ca(m), s = m.indexOf(this.options));
                this.options = "undefined" === typeof h ? {} : h;
                m[s] = this.options
            } else this.options = "undefined" === typeof h ? {} : h, h = this.parent.options, this.optionsName ? m = this.optionsName : (m = this._defaultsKey) && 0 !== m.length ? (s = m.charAt(0).toLowerCase(), 1 < m.length && (s = s.concat(m.slice(1))),
                m = s) : m = void 0, h[m] = this.options
    };
    U.prototype.remove = function(h) {
        h = "undefined" === typeof h ? !0 : h;
        if (this.isOptionsInArray) {
            var m = this.parent.options[this.optionsName];
            Ca(m);
            var s = m.indexOf(this.options);
            0 <= s && m.splice(s, 1)
        } else delete this.parent.options[this.optionsName];
        h && (this.stockChart || this.chart || this).render()
    };
    U.prototype.updateOption = function(h) {
        !pa[this._defaultsKey] && (Ga && window.console) && console.log("defaults not set");
        var m = pa[this._defaultsKey],
            u = {},
            y = this[h],
            F = this._themeOptionsKey,
            J = this._index;
        this.theme && s(this.parent) && s(F) && s(J) ? u = s(this.predefinedThemes[this.theme]) ? this.predefinedThemes.light1 : this.predefinedThemes[this.theme] : this.parent && (this.parent.themeOptions && this.parent.themeOptions[F]) && (null === J ? u = this.parent.themeOptions[F] : 0 < this.parent.themeOptions[F].length && (u = Math.min(this.parent.themeOptions[F].length - 1, J), u = this.parent.themeOptions[F][u]));
        this.themeOptions = u;
        h in m && (y = h in this.options ? this.options[h] : u && h in u ? u[h] : m[h]);
        if (y === this[h]) return !1;
        this[h] =
            y;
        return !0
    };
    U.prototype.trackChanges = function(h) {
        if (!this.sessionVariables) throw "Session Variable Store not set";
        this.sessionVariables[h] = this.options[h]
    };
    U.prototype.isBeingTracked = function(h) { this.options._oldOptions || (this.options._oldOptions = {}); return this.options._oldOptions[h] ? !0 : !1 };
    U.prototype.hasOptionChanged = function(h) { if (!this.sessionVariables) throw "Session Variable Store not set"; return this.sessionVariables[h] !== this.options[h] };
    U.prototype.addEventListener = function(h, m, s) {
        h && m && (this._eventListeners[h] =
            this._eventListeners[h] || [], this._eventListeners[h].push({ context: s || this, eventHandler: m }))
    };
    U.prototype.removeEventListener = function(h, m) {
        if (h && m && this._eventListeners[h])
            for (var s = this._eventListeners[h], u = 0; u < s.length; u++)
                if (s[u].eventHandler === m) { s[u].splice(u, 1); break }
    };
    U.prototype.removeAllEventListeners = function() { this._eventListeners = [] };
    U.prototype.dispatchEvent = function(h, m, s) {
        if (h && this._eventListeners[h]) {
            m = m || {};
            for (var u = this._eventListeners[h], y = 0; y < u.length; y++) u[y].eventHandler.call(u[y].context,
                m)
        }
        "function" === typeof this[h] && this[h].call(s || this.chart, m)
    };
    Da.prototype.registerSpace = function(h, m) { "top" === h ? this._topOccupied += m.height : "bottom" === h ? this._bottomOccupied += m.height : "left" === h ? this._leftOccupied += m.width : "right" === h && (this._rightOccupied += m.width) };
    Da.prototype.unRegisterSpace = function(h, m) { "top" === h ? this._topOccupied -= m.height : "bottom" === h ? this._bottomOccupied -= m.height : "left" === h ? this._leftOccupied -= m.width : "right" === h && (this._rightOccupied -= m.width) };
    Da.prototype.getFreeSpace =
        function() { return { x1: this._x1 + this._leftOccupied, y1: this._y1 + this._topOccupied, x2: this._x2 - this._rightOccupied, y2: this._y2 - this._bottomOccupied, width: this._x2 - this._x1 - this._rightOccupied - this._leftOccupied, height: this._y2 - this._y1 - this._bottomOccupied - this._topOccupied } };
    Da.prototype.reset = function() { this._rightOccupied = this._leftOccupied = this._bottomOccupied = this._topOccupied = this._padding };
    oa(ia, U);
    ia.prototype._initialize = function() {
        s(this.padding) || "object" !== typeof this.padding ? this.topPadding =
            this.rightPadding = this.bottomPadding = this.leftPadding = Number(this.padding) | 0 : (this.topPadding = s(this.padding.top) ? 0 : Number(this.padding.top) | 0, this.rightPadding = s(this.padding.right) ? 0 : Number(this.padding.right) | 0, this.bottomPadding = s(this.padding.bottom) ? 0 : Number(this.padding.bottom) | 0, this.leftPadding = s(this.padding.left) ? 0 : Number(this.padding.left) | 0)
    };
    ia.prototype.render = function(h) {
        if (0 !== this.fontSize) {
            h && this.ctx.save();
            var m = this.ctx.font;
            this.ctx.textBaseline = this.textBaseline;
            var s = 0;
            this._isDirty &&
                this.measureText(this.ctx);
            this.ctx.translate(this.x, this.y + s);
            "middle" === this.textBaseline && (s = -this._lineHeight / 2);
            this.ctx.font = this._getFontString();
            this.ctx.rotate(Math.PI / 180 * this.angle);
            var u = 0,
                y = this.topPadding,
                F = null;
            this.ctx.roundRect || Ba(this.ctx);
            (0 < this.borderThickness && this.borderColor || this.backgroundColor) && this.ctx.roundRect(0, s, this.width, this.height, this.cornerRadius, this.borderThickness, this.backgroundColor, this.borderColor);
            this.ctx.fillStyle = this.fontColor;
            for (s = 0; s < this._wrappedText.lines.length; s++) {
                F =
                    this._wrappedText.lines[s];
                if ("right" === this.horizontalAlign || "right" === this.textAlign) u = this.width - F.width - this.rightPadding;
                else if ("left" === this.horizontalAlign || "left" === this.textAlign) u = this.leftPadding;
                else if ("center" === this.horizontalAlign || "center" === this.textAlign) u = (this.width - (this.leftPadding + this.rightPadding)) / 2 - F.width / 2 + this.leftPadding;
                this.ctx.fillText(F.text, u, y);
                y += F.height
            }
            this.ctx.font = m;
            h && this.ctx.restore()
        }
    };
    ia.prototype.setText = function(h) {
        this.text = h;
        this._isDirty = !0;
        this._wrappedText =
            null
    };
    ia.prototype.measureText = function() {
        this._lineHeight = Wa(this.fontFamily, this.fontSize, this.fontWeight);
        if (null === this.maxWidth) throw "Please set maxWidth and height for TextBlock";
        this._wrapText(this.ctx);
        this._isDirty = !1;
        return { width: this.width, height: this.height }
    };
    ia.prototype._getLineWithWidth = function(h, m, s) {
        h = String(h);
        if (!h) return { text: "", width: 0 };
        var u = s = 0,
            y = h.length - 1,
            F = Infinity;
        for (this.ctx.font = this._getFontString(); u <= y;) {
            var F = Math.floor((u + y) / 2),
                D = h.substr(0, F + 1);
            s = this.ctx.measureText(D).width;
            if (s < m) u = F + 1;
            else if (s > m) y = F - 1;
            else break
        }
        s > m && 1 < D.length && (D = D.substr(0, D.length - 1), s = this.ctx.measureText(D).width);
        m = !0;
        if (D.length === h.length || " " === h[D.length]) m = !1;
        m && (h = D.split(" "), 1 < h.length && h.pop(), D = h.join(" "), s = this.ctx.measureText(D).width);
        return { text: D, width: s }
    };
    ia.prototype._wrapText = function() {
        var h = new String(Fa(String(this.text))),
            m = [],
            s = this.ctx.font,
            u = 0,
            y = 0;
        this.ctx.font = this._getFontString();
        if (0 === this.frontSize) y = u = 0;
        else
            for (; 0 < h.length;) {
                var F = this.maxHeight - (this.topPadding +
                        this.bottomPadding),
                    D = this._getLineWithWidth(h, this.maxWidth - (this.leftPadding + this.rightPadding), !1);
                D.height = this._lineHeight;
                m.push(D);
                var J = y,
                    y = Math.max(y, D.width),
                    u = u + D.height,
                    h = Fa(h.slice(D.text.length, h.length));
                F && u > F && (D = m.pop(), u -= D.height, y = J)
            }
        this._wrappedText = { lines: m, width: y, height: u };
        this.width = y + (this.leftPadding + this.rightPadding);
        this.height = u + (this.topPadding + this.bottomPadding);
        this.ctx.font = s
    };
    ia.prototype._getFontString = function() {
        var h;
        h = "" + (this.fontStyle ? this.fontStyle + " " :
            "");
        h += this.fontWeight ? this.fontWeight + " " : "";
        h += this.fontSize ? this.fontSize + "px " : "";
        var m = this.fontFamily ? this.fontFamily + "" : "";
        !u && m && (m = m.split(",")[0], "'" !== m[0] && '"' !== m[0] && (m = "'" + m + "'"));
        return h += m
    };
    oa(Sa, U);
    oa(ya, U);
    ya.prototype.setLayout = function() {
        if (this.text) {
            var h = this.dockInsidePlotArea ? this.chart.plotArea : this.chart,
                m = h.layoutManager.getFreeSpace(),
                u = m.x1,
                y = m.y1,
                F = 0,
                J = 0,
                D = this.chart._menuButton && this.chart.exportEnabled && "top" === this.verticalAlign ? 22 : 0,
                N, H;
            "top" === this.verticalAlign ||
                "bottom" === this.verticalAlign ? (null === this.maxWidth && (this.maxWidth = m.width - 4 - D * ("center" === this.horizontalAlign ? 2 : 1)), J = 0.5 * m.height - this.margin - 2, F = 0) : "center" === this.verticalAlign && ("left" === this.horizontalAlign || "right" === this.horizontalAlign ? (null === this.maxWidth && (this.maxWidth = m.height - 4), J = 0.5 * m.width - this.margin - 2) : "center" === this.horizontalAlign && (null === this.maxWidth && (this.maxWidth = m.width - 4), J = 0.5 * m.height - 4));
            var M;
            s(this.padding) || "number" !== typeof this.padding ? s(this.padding) || "object" !==
                typeof this.padding || (M = this.padding.top ? this.padding.top : this.padding.bottom ? this.padding.bottom : 0, M += this.padding.bottom ? this.padding.bottom : this.padding.top ? this.padding.top : 0) : M = 2 * this.padding;
            this.wrap || (J = Math.min(J, 1.5 * this.fontSize + M));
            J = new ia(this.ctx, {
                fontSize: this.fontSize,
                fontFamily: this.fontFamily,
                fontColor: this.fontColor,
                fontStyle: this.fontStyle,
                fontWeight: this.fontWeight,
                horizontalAlign: this.horizontalAlign,
                verticalAlign: this.verticalAlign,
                borderColor: this.borderColor,
                borderThickness: this.borderThickness,
                backgroundColor: this.backgroundColor,
                maxWidth: this.maxWidth,
                maxHeight: J,
                cornerRadius: this.cornerRadius,
                text: this.text,
                padding: this.padding,
                textBaseline: "top"
            });
            M = J.measureText();
            "top" === this.verticalAlign || "bottom" === this.verticalAlign ? ("top" === this.verticalAlign ? (y = m.y1 + 2, H = "top") : "bottom" === this.verticalAlign && (y = m.y2 - 2 - M.height, H = "bottom"), "left" === this.horizontalAlign ? u = m.x1 + 2 : "center" === this.horizontalAlign ? u = m.x1 + m.width / 2 - M.width / 2 : "right" === this.horizontalAlign && (u = m.x2 - 2 - M.width - D), N = this.horizontalAlign,
                this.width = M.width, this.height = M.height) : "center" === this.verticalAlign && ("left" === this.horizontalAlign ? (u = m.x1 + 2, y = m.y2 - 2 - (this.maxWidth / 2 - M.width / 2), F = -90, H = "left", this.width = M.height, this.height = M.width) : "right" === this.horizontalAlign ? (u = m.x2 - 2, y = m.y1 + 2 + (this.maxWidth / 2 - M.width / 2), F = 90, H = "right", this.width = M.height, this.height = M.width) : "center" === this.horizontalAlign && (y = h.y1 + (h.height / 2 - M.height / 2), u = h.x1 + (h.width / 2 - M.width / 2), H = "center", this.width = M.width, this.height = M.height), N = "center");
            J.x =
                u;
            J.y = y;
            J.angle = F;
            J.horizontalAlign = N;
            this._textBlock = J;
            h.layoutManager.registerSpace(H, { width: this.width + ("left" === H || "right" === H ? this.margin + 2 : 0), height: this.height + ("top" === H || "bottom" === H ? this.margin + 2 : 0) });
            this.bounds = { x1: u, y1: y, x2: u + this.width, y2: y + this.height };
            this.ctx.textBaseline = "top"
        }
    };
    ya.prototype.render = function() { this._textBlock && this._textBlock.render(!0) };
    oa(Ha, U);
    Ha.prototype.setLayout = ya.prototype.setLayout;
    Ha.prototype.render = ya.prototype.render;
    Ta.prototype.get = function(h, m) {
        var u =
            null;
        0 < this.pool.length ? (u = this.pool.pop(), La(u, h, m)) : u = ta(h, m);
        return u
    };
    Ta.prototype.release = function(h) { this.pool.push(h) };
    oa(Ia, U);
    var Oa = { addTheme: function(h, m) { ab[h] = m }, addColorSet: function(h, m) { za[h] = m }, addCultureInfo: function(h, m) { Ja[h] = m }, formatNumber: function(h, m, u) { u = u || "en"; if (Ja[u]) return ea(h, m || "#,##0.##", new Ia(u)); throw "Unknown Culture Name"; }, formatDate: function(h, m, u) { u = u || "en"; if (Ja[u]) return Aa(h, m || "DD MMM YYYY", new Ia(u)); throw "Unknown Culture Name"; } };
    "undefined" !== typeof module &&
        "undefined" !== typeof module.exports ? module.exports = Oa : "function" === typeof define && define.amd ? define([], function() { return Oa }) : (window.CanvasJS && window.console && window.console.log("CanvasJS namespace already exists. If you are loading both chart and stockchart scripts, just load stockchart alone as it includes all chart features."), window.CanvasJS = window.CanvasJS ? window.CanvasJS : Oa);
    y = Oa.Chart = function() {
        function h(a, d) { return a.x - d.x }

        function m(a, d, c) {
            d = d || {};
            s(c) ? (this.predefinedThemes = ab, this.optionsName =
                this.parent = this.index = null) : (this.parent = c.parent, this.index = c.index, this.predefinedThemes = c.predefinedThemes, this.optionsName = c.optionsName, this.stockChart = c.stockChart, this.panel = a, this.isOptionsInArray = c.isOptionsInArray);
            this.theme = s(d.theme) || s(this.predefinedThemes[d.theme]) ? "light1" : d.theme;
            m.base.constructor.call(this, "Chart", this.optionsName, d, this.index, this.parent);
            var b = this;
            this._containerId = a;
            this._objectsInitialized = !1;
            this.overlaidCanvasCtx = this.ctx = null;
            this._indexLabels = [];
            this._panTimerId =
                0;
            this._lastTouchEventType = "";
            this._lastTouchData = null;
            this.isAnimating = !1;
            this.renderCount = 0;
            this.disableToolTip = this.animatedRender = !1;
            this.canvasPool = new Ta;
            this.allDOMEventHandlers = [];
            this.panEnabled = !1;
            this._defaultCursor = "default";
            this.plotArea = { canvas: null, ctx: null, x1: 0, y1: 0, x2: 0, y2: 0, width: 0, height: 0 };
            this._dataInRenderedOrder = [];
            (this.container = "string" === typeof this._containerId ? document.getElementById(this._containerId) : this._containerId) ? (this.container.innerHTML = "", d = a = 0, a = this.options.width ?
                this.width : 0 < this.container.clientWidth ? this.container.clientWidth : this.width, d = this.options.height ? this.height : 0 < this.container.clientHeight ? this.container.clientHeight : this.height, this.width = a, this.height = d, this.x1 = this.y1 = 0, this.x2 = this.width, this.y2 = this.height, this.selectedColorSet = "undefined" !== typeof za[this.colorSet] ? za[this.colorSet] : za.colorSet1, this._canvasJSContainer = document.createElement("div"), this._canvasJSContainer.setAttribute("class", "canvasjs-chart-container"), this._canvasJSContainer.style.position =
                "relative", this._canvasJSContainer.style.textAlign = "left", this._canvasJSContainer.style.cursor = "auto", this._canvasJSContainer.style.direction = "ltr", u || (this._canvasJSContainer.style.height = "0px"), this.container.appendChild(this._canvasJSContainer), this.canvas = ta(a, d), this._preRenderCanvas = ta(a, d), this.canvas.style.position = "absolute", this.canvas.style.WebkitUserSelect = "none", this.canvas.style.MozUserSelect = "none", this.canvas.style.msUserSelect = "none", this.canvas.style.userSelect = "none", this.canvas.getContext &&
                (this._canvasJSContainer.appendChild(this.canvas), this.ctx = this.canvas.getContext("2d"), this.ctx.textBaseline = "top", Ba(this.ctx), this._preRenderCtx = this._preRenderCanvas.getContext("2d"), this._preRenderCtx.textBaseline = "top", Ba(this._preRenderCtx), u ? this.plotArea.ctx = this.ctx : (this.plotArea.canvas = ta(a, d), this.plotArea.canvas.style.position = "absolute", this.plotArea.canvas.setAttribute("class", "plotAreaCanvas"), this._canvasJSContainer.appendChild(this.plotArea.canvas), this.plotArea.ctx = this.plotArea.canvas.getContext("2d")),
                    this.overlaidCanvas = ta(a, d), this.overlaidCanvas.style.position = "absolute", this.overlaidCanvas.style.webkitTapHighlightColor = "transparent", this.overlaidCanvas.style.WebkitUserSelect = "none", this.overlaidCanvas.style.MozUserSelect = "none", this.overlaidCanvas.style.msUserSelect = "none", this.overlaidCanvas.style.userSelect = "none", this.overlaidCanvas.getContext && (this._canvasJSContainer.appendChild(this.overlaidCanvas), this.overlaidCanvasCtx = this.overlaidCanvas.getContext("2d"), this.overlaidCanvasCtx.textBaseline =
                        "top", Ba(this.overlaidCanvasCtx)), this._eventManager = new da(this), this.windowResizeHandler = J(window, "resize", function() { b._updateSize() && b.render() }, this.allDOMEventHandlers), this._toolBar = document.createElement("div"), this._toolBar.setAttribute("class", "canvasjs-chart-toolbar"), this._toolBar.style.cssText = "position: absolute; right: 1px; top: 1px;", this._canvasJSContainer.appendChild(this._toolBar), this.bounds = { x1: 0, y1: 0, x2: this.width, y2: this.height }, J(this.overlaidCanvas, "click", function(a) { b._mouseEventHandler(a) },
                        this.allDOMEventHandlers), J(this.overlaidCanvas, "mousemove", function(a) { b._mouseEventHandler(a) }, this.allDOMEventHandlers), J(this.overlaidCanvas, "mouseup", function(a) { b._mouseEventHandler(a) }, this.allDOMEventHandlers), J(this.overlaidCanvas, "mousedown", function(a) {
                        b._mouseEventHandler(a);
                        ua(b._dropdownMenu)
                    }, this.allDOMEventHandlers), J(this.overlaidCanvas, "mouseout", function(a) { b._mouseEventHandler(a) }, this.allDOMEventHandlers), J(this.overlaidCanvas, window.navigator.msPointerEnabled ? "MSPointerDown" :
                        "touchstart",
                        function(a) { b._touchEventHandler(a) }, this.allDOMEventHandlers), J(this.overlaidCanvas, window.navigator.msPointerEnabled ? "MSPointerMove" : "touchmove", function(a) { b._touchEventHandler(a) }, this.allDOMEventHandlers), J(this.overlaidCanvas, window.navigator.msPointerEnabled ? "MSPointerUp" : "touchend", function(a) { b._touchEventHandler(a) }, this.allDOMEventHandlers), J(this.overlaidCanvas, window.navigator.msPointerEnabled ? "MSPointerCancel" : "touchcancel", function(a) { b._touchEventHandler(a) }, this.allDOMEventHandlers),
                    this.toolTip = new X(this, this.options.toolTip), this.data = null, this.axisX = [], this.axisX2 = [], this.axisY = [], this.axisY2 = [], this.sessionVariables = { axisX: [], axisX2: [], axisY: [], axisY2: [] })) : window.console && window.console.log('CanvasJS Error: Chart Container with id "' + this._containerId + '" was not found')
        }

        function w(a, d) {
            for (var c = [], b, e = 0; e < a.length; e++)
                if (0 == e) c.push(a[0]);
                else {
                    var f, l, v;
                    v = e - 1;
                    f = 0 === v ? 0 : v - 1;
                    l = v === a.length - 1 ? v : v + 1;
                    b = Math.abs((a[l].x - a[f].x) / (0 === a[l].x - a[v].x ? 0.01 : a[l].x - a[v].x)) * (d - 1) /
                        2 + 1;
                    var A = (a[l].x - a[f].x) / b;
                    b = (a[l].y - a[f].y) / b;
                    c[c.length] = a[v].x > a[f].x && 0 < A || a[v].x < a[f].x && 0 > A ? { x: a[v].x + A / 3, y: a[v].y + b / 3 } : { x: a[v].x, y: a[v].y + b / 9 };
                    v = e;
                    f = 0 === v ? 0 : v - 1;
                    l = v === a.length - 1 ? v : v + 1;
                    b = Math.abs((a[l].x - a[f].x) / (0 === a[v].x - a[f].x ? 0.01 : a[v].x - a[f].x)) * (d - 1) / 2 + 1;
                    A = (a[l].x - a[f].x) / b;
                    b = (a[l].y - a[f].y) / b;
                    c[c.length] = a[v].x > a[f].x && 0 < A || a[v].x < a[f].x && 0 > A ? { x: a[v].x - A / 3, y: a[v].y - b / 3 } : { x: a[v].x, y: a[v].y - b / 9 };
                    c[c.length] = a[e]
                }
            return c
        }

        function y(a, d, c, b, e, f, l, v, A, k) {
            var n = 0;
            k ? (l.color = f, v.color = f) :
                k = 1;
            n = A ? Math.abs(e - c) : Math.abs(b - d);
            n = 0 < l.trimLength ? Math.abs(n * l.trimLength / 100) : Math.abs(n - l.length);
            A ? (c += n / 2, e -= n / 2) : (d += n / 2, b -= n / 2);
            var n = 1 === Math.round(l.thickness) % 2 ? 0.5 : 0,
                p = 1 === Math.round(v.thickness) % 2 ? 0.5 : 0;
            a.save();
            a.globalAlpha = k;
            a.strokeStyle = v.color || f;
            a.lineWidth = v.thickness || 2;
            a.setLineDash && a.setLineDash(N(v.dashType, v.thickness));
            a.beginPath();
            A && 0 < v.thickness ? (a.moveTo(b - l.thickness / 2, Math.round((c + e) / 2) - p), a.lineTo(d + l.thickness / 2, Math.round((c + e) / 2) - p)) : 0 < v.thickness && (a.moveTo(Math.round((d +
                b) / 2) - p, c + l.thickness / 2), a.lineTo(Math.round((d + b) / 2) - p, e - l.thickness / 2));
            a.stroke();
            a.strokeStyle = l.color || f;
            a.lineWidth = l.thickness || 2;
            a.setLineDash && a.setLineDash(N(l.dashType, l.thickness));
            a.beginPath();
            A && 0 < l.thickness ? (a.moveTo(b - n, c), a.lineTo(b - n, e), a.moveTo(d + n, c), a.lineTo(d + n, e)) : 0 < l.thickness && (a.moveTo(d, c + n), a.lineTo(b, c + n), a.moveTo(d, e - n), a.lineTo(b, e - n));
            a.stroke();
            a.restore()
        }

        function F(a, d) {
            F.base.constructor.call(this, "Legend", "legend", d, null, a);
            this.chart = a;
            this.canvas = a.canvas;
            this.ctx = this.chart.ctx;
            this.ghostCtx = this.chart._eventManager.ghostCtx;
            this.items = [];
            this.optionsName = "legend";
            this.height = this.width = 0;
            this.orientation = null;
            this.dataSeries = [];
            this.bounds = { x1: null, y1: null, x2: null, y2: null };
            "undefined" === typeof this.options.fontSize && (this.fontSize = this.chart.getAutoFontSize(this.fontSize));
            this.lineHeight = Wa(this.fontFamily, this.fontSize, this.fontWeight);
            this.horizontalSpacing = this.fontSize
        }

        function H(a, d, c, b) {
            H.base.constructor.call(this, "DataSeries", "data", d, c,
                a);
            this.chart = a;
            this.canvas = a.canvas;
            this._ctx = a.canvas.ctx;
            this.index = c;
            this.noDataPointsInPlotArea = 0;
            this.id = b;
            this.chart._eventManager.objectMap[b] = { id: b, objectType: "dataSeries", dataSeriesIndex: c };
            a = d.dataPoints ? d.dataPoints.length : 0;
            this.dataPointEOs = [];
            for (d = 0; d < a; d++) this.dataPointEOs[d] = {};
            this.dataPointIds = [];
            this.plotUnit = [];
            this.axisY = this.axisX = null;
            this.optionsName = "data";
            this.isOptionsInArray = !0;
            null === this.fillOpacity && (this.type.match(/area/i) ? this.fillOpacity = 0.7 : this.fillOpacity =
                1);
            this.axisPlacement = this.getDefaultAxisPlacement();
            "undefined" === typeof this.options.indexLabelFontSize && (this.indexLabelFontSize = this.chart.getAutoFontSize(this.indexLabelFontSize))
        }

        function D(a, d, c, b, e, f) {
            D.base.constructor.call(this, "Axis", d, c, b, a);
            this.chart = a;
            this.canvas = a.canvas;
            this.ctx = a.ctx;
            this.intervalStartPosition = this.maxHeight = this.maxWidth = 0;
            this.labels = [];
            this.dataSeries = [];
            this._stripLineLabels = this._ticks = this._labels = null;
            this.dataInfo = {
                min: Infinity,
                max: -Infinity,
                viewPortMin: Infinity,
                viewPortMax: -Infinity,
                minDiff: Infinity
            };
            this.isOptionsInArray = !0;
            "axisX" === e ? ("left" === f || "bottom" === f ? (this.optionsName = "axisX", s(this.chart.sessionVariables.axisX[b]) && (this.chart.sessionVariables.axisX[b] = {}), this.sessionVariables = this.chart.sessionVariables.axisX[b]) : (this.optionsName = "axisX2", s(this.chart.sessionVariables.axisX2[b]) && (this.chart.sessionVariables.axisX2[b] = {}), this.sessionVariables = this.chart.sessionVariables.axisX2[b]), this.options.interval || (this.intervalType = null)) : "left" ===
                f || "bottom" === f ? (this.optionsName = "axisY", s(this.chart.sessionVariables.axisY[b]) && (this.chart.sessionVariables.axisY[b] = {}), this.sessionVariables = this.chart.sessionVariables.axisY[b]) : (this.optionsName = "axisY2", s(this.chart.sessionVariables.axisY2[b]) && (this.chart.sessionVariables.axisY2[b] = {}), this.sessionVariables = this.chart.sessionVariables.axisY2[b]);
            "undefined" === typeof this.options.titleFontSize && (this.titleFontSize = this.chart.getAutoFontSize(this.titleFontSize));
            "undefined" === typeof this.options.labelFontSize &&
                (this.labelFontSize = this.chart.getAutoFontSize(this.labelFontSize));
            this.type = e;
            "axisX" !== e || c && "undefined" !== typeof c.gridThickness || (this.gridThickness = 0);
            this._position = f;
            this.lineCoordinates = { x1: null, y1: null, x2: null, y2: null, width: null };
            this.labelAngle = (this.labelAngle % 360 + 360) % 360;
            90 < this.labelAngle && 270 > this.labelAngle ? this.labelAngle -= 180 : 270 <= this.labelAngle && 360 >= this.labelAngle && (this.labelAngle -= 360);
            this.options.scaleBreaks && (this.scaleBreaks = new Z(this.chart, this.options.scaleBreaks, ++this.chart._eventManager.lastObjectId,
                this));
            this.stripLines = [];
            if (this.options.stripLines && 0 < this.options.stripLines.length)
                for (a = 0; a < this.options.stripLines.length; a++) this.stripLines.push(new M(this.chart, this.options.stripLines[a], a, ++this.chart._eventManager.lastObjectId, this));
            this.options.crosshair && (this.crosshair = new $(this.chart, this.options.crosshair, this));
            this._titleTextBlock = null;
            this.hasOptionChanged("viewportMinimum") && null === this.viewportMinimum && (this.options.viewportMinimum = void 0, this.sessionVariables.viewportMinimum =
                null);
            this.hasOptionChanged("viewportMinimum") || isNaN(this.sessionVariables.newViewportMinimum) || null === this.sessionVariables.newViewportMinimum ? this.sessionVariables.newViewportMinimum = null : this.viewportMinimum = this.sessionVariables.newViewportMinimum;
            this.hasOptionChanged("viewportMaximum") && null === this.viewportMaximum && (this.options.viewportMaximum = void 0, this.sessionVariables.viewportMaximum = null);
            this.hasOptionChanged("viewportMaximum") || isNaN(this.sessionVariables.newViewportMaximum) || null ===
                this.sessionVariables.newViewportMaximum ? this.sessionVariables.newViewportMaximum = null : this.viewportMaximum = this.sessionVariables.newViewportMaximum;
            null !== this.minimum && null !== this.viewportMinimum && (this.viewportMinimum = Math.max(this.viewportMinimum, this.minimum));
            null !== this.maximum && null !== this.viewportMaximum && (this.viewportMaximum = Math.min(this.viewportMaximum, this.maximum));
            this.trackChanges("viewportMinimum");
            this.trackChanges("viewportMaximum")
        }

        function Z(a, d, c, b) {
            Z.base.constructor.call(this,
                "ScaleBreaks", "scaleBreaks", d, null, b);
            this.id = c;
            this.chart = a;
            this.ctx = this.chart.ctx;
            this.axis = b;
            this.optionsName = "scaleBreaks";
            this.isOptionsInArray = !1;
            this._appliedBreaks = [];
            this.customBreaks = [];
            this.autoBreaks = [];
            "string" === typeof this.spacing ? (this.spacing = parseFloat(this.spacing), this.spacing = isNaN(this.spacing) ? 8 : (10 < this.spacing ? 10 : this.spacing) + "%") : "number" !== typeof this.spacing && (this.spacing = 8);
            this.autoCalculate && (this.maxNumberOfAutoBreaks = Math.min(this.maxNumberOfAutoBreaks, 5));
            if (this.options.customBreaks &&
                0 < this.options.customBreaks.length) {
                for (a = 0; a < this.options.customBreaks.length; a++) this.customBreaks.push(new T(this.chart, "customBreaks", this.options.customBreaks[a], a, ++this.chart._eventManager.lastObjectId, this)), "number" === typeof this.customBreaks[a].startValue && ("number" === typeof this.customBreaks[a].endValue && this.customBreaks[a].endValue !== this.customBreaks[a].startValue) && this._appliedBreaks.push(this.customBreaks[a]);
                this._appliedBreaks.sort(function(a, b) { return a.startValue - b.startValue });
                for (a = 0; a < this._appliedBreaks.length - 1; a++) this._appliedBreaks[a].endValue >= this._appliedBreaks[a + 1].startValue && (this._appliedBreaks[a].endValue = Math.max(this._appliedBreaks[a].endValue, this._appliedBreaks[a + 1].endValue), window.console && window.console.log("CanvasJS Error: Breaks " + a + " and " + (a + 1) + " are overlapping."), this._appliedBreaks.splice(a, 2), a--)
            }
        }

        function T(a, d, c, b, e, f) {
            T.base.constructor.call(this, "Break", d, c, b, f);
            this.id = e;
            this.chart = a;
            this.ctx = this.chart.ctx;
            this.scaleBreaks = f;
            this.optionsName =
                d;
            this.isOptionsInArray = !0;
            this.type = c.type ? this.type : f.type;
            this.fillOpacity = s(c.fillOpacity) ? f.fillOpacity : this.fillOpacity;
            this.lineThickness = s(c.lineThickness) ? f.lineThickness : this.lineThickness;
            this.color = c.color ? this.color : f.color;
            this.lineColor = c.lineColor ? this.lineColor : f.lineColor;
            this.lineDashType = c.lineDashType ? this.lineDashType : f.lineDashType;
            !s(this.startValue) && this.startValue.getTime && (this.startValue = this.startValue.getTime());
            !s(this.endValue) && this.endValue.getTime && (this.endValue =
                this.endValue.getTime());
            "number" === typeof this.startValue && ("number" === typeof this.endValue && this.endValue < this.startValue) && (a = this.startValue, this.startValue = this.endValue, this.endValue = a);
            this.spacing = "undefined" === typeof c.spacing ? f.spacing : c.spacing;
            "string" === typeof this.options.spacing ? (this.spacing = parseFloat(this.spacing), this.spacing = isNaN(this.spacing) ? 0 : (10 < this.spacing ? 10 : this.spacing) + "%") : "number" !== typeof this.options.spacing && (this.spacing = f.spacing);
            this.size = f.parent.logarithmic ?
                1 : 0
        }

        function M(a, d, c, b, e) {
            M.base.constructor.call(this, "StripLine", "stripLines", d, c, e);
            this.id = b;
            this.chart = a;
            this.ctx = this.chart.ctx;
            this.label = this.label;
            this.axis = e;
            this.optionsName = "stripLines";
            this.isOptionsInArray = !0;
            this._thicknessType = "pixel";
            null !== this.startValue && null !== this.endValue && (this.value = e.logarithmic ? Math.sqrt((this.startValue.getTime ? this.startValue.getTime() : this.startValue) * (this.endValue.getTime ? this.endValue.getTime() : this.endValue)) : ((this.startValue.getTime ? this.startValue.getTime() :
                this.startValue) + (this.endValue.getTime ? this.endValue.getTime() : this.endValue)) / 2, this._thicknessType = null)
        }

        function $(a, d, c) {
            $.base.constructor.call(this, "Crosshair", "crosshair", d, null, c);
            this.chart = a;
            this.ctx = this.chart.ctx;
            this.axis = c;
            this.optionsName = "crosshair";
            this._thicknessType = "pixel"
        }

        function X(a, d) {
            X.base.constructor.call(this, "ToolTip", "toolTip", d, null, a);
            this.chart = a;
            this.canvas = a.canvas;
            this.ctx = this.chart.ctx;
            this.currentDataPointIndex = this.currentSeriesIndex = -1;
            this._prevY = this._prevX =
                NaN;
            this.containerTransitionDuration = 0.1;
            this.mozContainerTransition = this.getContainerTransition(this.containerTransitionDuration);
            this.optionsName = "toolTip";
            this._initialize()
        }

        function da(a) {
            this.chart = a;
            this.lastObjectId = 0;
            this.objectMap = [];
            this.rectangularRegionEventSubscriptions = [];
            this.previousDataPointEventObject = null;
            this.ghostCanvas = ta(this.chart.width, this.chart.height);
            this.ghostCtx = this.ghostCanvas.getContext("2d");
            this.mouseoveredObjectMaps = []
        }

        function ha(a) {
            this.chart = a;
            this.ctx = this.chart.plotArea.ctx;
            this.animations = [];
            this.animationRequestId = null
        }
        oa(m, U);
        m.prototype.destroy = function() {
            var a = this.allDOMEventHandlers;
            this._animator && this._animator.cancelAllAnimations();
            this._panTimerId && clearTimeout(this._panTimerId);
            for (var d = 0; d < a.length; d++) {
                var c = a[d][0],
                    b = a[d][1],
                    e = a[d][2],
                    f = a[d][3],
                    f = f || !1;
                c.removeEventListener ? c.removeEventListener(b, e, f) : c.detachEvent && c.detachEvent("on" + b, e)
            }
            this.allDOMEventHandlers = [];
            for (this.removeAllEventListeners(); this._canvasJSContainer && this._canvasJSContainer.hasChildNodes();) this._canvasJSContainer.removeChild(this._canvasJSContainer.lastChild);
            for (; this.container && this.container.hasChildNodes();) this.container.removeChild(this.container.lastChild);
            for (; this._dropdownMenu && this._dropdownMenu.hasChildNodes();) this._dropdownMenu.removeChild(this._dropdownMenu.lastChild);
            this.overlaidCanvas = this.canvas = this.container = this._canvasJSContainer = null;
            this._toolBar = this._dropdownMenu = this._menuButton = this._resetButton = this._zoomButton = this._breaksCanvas = this._preRenderCanvas = this.toolTip.container = null
        };
        m.prototype._updateOptions = function() {
            var a =
                this;
            this.updateOption("width");
            this.updateOption("height");
            this.updateOption("dataPointWidth");
            this.updateOption("dataPointMinWidth");
            this.updateOption("dataPointMaxWidth");
            this.updateOption("interactivityEnabled");
            this.updateOption("theme");
            this.updateOption("colorSet") && (this.selectedColorSet = "undefined" !== typeof za[this.colorSet] ? za[this.colorSet] : za.colorSet1);
            this.updateOption("backgroundColor");
            this.backgroundColor || (this.backgroundColor = "rgba(0,0,0,0)");
            this.updateOption("culture");
            this._cultureInfo =
                new Ia(this.options.culture);
            this.updateOption("animationEnabled");
            this.animationEnabled = this.animationEnabled && u;
            this.updateOption("animationDuration");
            this.updateOption("rangeChanging");
            this.updateOption("rangeChanged");
            this.updateOption("exportEnabled");
            this.updateOption("exportFileName");
            this.updateOption("zoomType");
            this.toolbar = new Sa(this, this.options.toolbar);
            if (this.options.zoomEnabled || this.panEnabled) {
                if (this._zoomButton) ga(this._zoomButton, {
                    borderRight: this.toolbar.buttonBorderThickness +
                        "px solid " + this.toolbar.buttonBorderColor,
                    backgroundColor: a.toolbar.itemBackgroundColor,
                    color: a.toolbar.fontColor
                }), this._zoomButton.title = this._cultureInfo.zoomText;
                else {
                    var d = !1;
                    ua(this._zoomButton = document.createElement("button"));
                    va(this, this._zoomButton, "pan");
                    this._zoomButton.title = this._cultureInfo.panText;
                    this._toolBar.appendChild(this._zoomButton);
                    this._zoomButton.style.borderRight = this.toolbar.buttonBorderThickness + "px solid " + this.toolbar.buttonBorderColor;
                    J(this._zoomButton, "touchstart",
                        function(a) { d = !0 }, this.allDOMEventHandlers);
                    J(this._zoomButton, "click", function() {
                        a.zoomEnabled ? (a.zoomEnabled = !1, a.panEnabled = !0, va(a, a._zoomButton, "zoom")) : (a.zoomEnabled = !0, a.panEnabled = !1, va(a, a._zoomButton, "pan"));
                        a.render()
                    }, this.allDOMEventHandlers);
                    J(this._zoomButton, "mouseover", function() {
                        d ? d = !1 : (ga(a._zoomButton, { backgroundColor: a.toolbar.itemBackgroundColorOnHover, color: a.toolbar.fontColorOnHover, transition: "0.4s", WebkitTransition: "0.4s" }), 0 >= navigator.userAgent.search("MSIE") && ga(a._zoomButton.childNodes[0], { WebkitFilter: "invert(100%)", filter: "invert(100%)" }))
                    }, this.allDOMEventHandlers);
                    J(this._zoomButton, "mouseout", function() { d || (ga(a._zoomButton, { backgroundColor: a.toolbar.itemBackgroundColor, color: a.toolbar.fontColor, transition: "0.4s", WebkitTransition: "0.4s" }), 0 >= navigator.userAgent.search("MSIE") && ga(a._zoomButton.childNodes[0], { WebkitFilter: "invert(0%)", filter: "invert(0%)" })) }, this.allDOMEventHandlers)
                }
                this._resetButton ? (ga(this._resetButton, {
                    borderRight: this.toolbar.buttonBorderThickness + "px solid " +
                        this.toolbar.buttonBorderColor,
                    backgroundColor: a.toolbar.itemBackgroundColor,
                    color: a.toolbar.fontColor
                }), this._resetButton.title = this._cultureInfo.resetText) : (d = !1, ua(this._resetButton = document.createElement("button")), va(this, this._resetButton, "reset"), this._resetButton.style.borderRight = (this.exportEnabled ? this.toolbar.buttonBorderThickness : 0) + "px solid " + this.toolbar.buttonBorderColor, this._toolBar.appendChild(this._resetButton), J(this._resetButton, "touchstart", function(a) { d = !0 }, this.allDOMEventHandlers),
                    J(this._resetButton, "click", function() {
                        a.toolTip.hide();
                        a.toolTip.dispatchEvent("hidden", { chart: a, toolTip: a.toolTip }, a.toolTip);
                        a.zoomEnabled || a.panEnabled ? (a.zoomEnabled = !0, a.panEnabled = !1, va(a, a._zoomButton, "pan"), a._defaultCursor = "default", a.overlaidCanvas.style.cursor = a._defaultCursor) : (a.zoomEnabled = !1, a.panEnabled = !1);
                        if (a.sessionVariables.axisX)
                            for (var b = 0; b < a.sessionVariables.axisX.length; b++) a.sessionVariables.axisX[b].newViewportMinimum = null, a.sessionVariables.axisX[b].newViewportMaximum =
                                null;
                        if (a.sessionVariables.axisX2)
                            for (b = 0; b < a.sessionVariables.axisX2.length; b++) a.sessionVariables.axisX2[b].newViewportMinimum = null, a.sessionVariables.axisX2[b].newViewportMaximum = null;
                        if (a.sessionVariables.axisY)
                            for (b = 0; b < a.sessionVariables.axisY.length; b++) a.sessionVariables.axisY[b].newViewportMinimum = null, a.sessionVariables.axisY[b].newViewportMaximum = null;
                        if (a.sessionVariables.axisY2)
                            for (b = 0; b < a.sessionVariables.axisY2.length; b++) a.sessionVariables.axisY2[b].newViewportMinimum = null, a.sessionVariables.axisY2[b].newViewportMaximum =
                                null;
                        a.resetOverlayedCanvas();
                        ua(a._zoomButton, a._resetButton);
                        a.stockChart && (a.stockChart._rangeEventParameter = { stockChart: a.stockChart, source: "chart", index: a.stockChart.charts.indexOf(a), minimum: null, maximum: null });
                        a._dispatchRangeEvent("rangeChanging", "reset");
                        a.stockChart && (a.stockChart._rangeEventParameter.type = "rangeChanging", a.stockChart.dispatchEvent("rangeChanging", a.stockChart._rangeEventParameter, a.stockChart));
                        a.render();
                        a.syncCharts && a.syncCharts(null, null);
                        a._dispatchRangeEvent("rangeChanged",
                            "reset");
                        a.stockChart && (a.stockChart._rangeEventParameter.type = "rangeChanged", a.stockChart.dispatchEvent("rangeChanged", a.stockChart._rangeEventParameter, a.stockChart))
                    }, this.allDOMEventHandlers), J(this._resetButton, "mouseover", function() { d || (ga(a._resetButton, { backgroundColor: a.toolbar.itemBackgroundColorOnHover, color: a.toolbar.fontColorOnHover, transition: "0.4s", WebkitTransition: "0.4s" }), 0 >= navigator.userAgent.search("MSIE") && ga(a._resetButton.childNodes[0], { WebkitFilter: "invert(100%)", filter: "invert(100%)" })) },
                        this.allDOMEventHandlers), J(this._resetButton, "mouseout", function() { d || (ga(a._resetButton, { backgroundColor: a.toolbar.itemBackgroundColor, color: a.toolbar.fontColor, transition: "0.4s", WebkitTransition: "0.4s" }), 0 >= navigator.userAgent.search("MSIE") && ga(a._resetButton.childNodes[0], { WebkitFilter: "invert(0%)", filter: "invert(0%)" })) }, this.allDOMEventHandlers), this.overlaidCanvas.style.cursor = a._defaultCursor);
                this.zoomEnabled || this.panEnabled || (this._zoomButton ? (a._zoomButton.getAttribute("state") === a._cultureInfo.zoomText ?
                    (this.panEnabled = !0, this.zoomEnabled = !1) : (this.zoomEnabled = !0, this.panEnabled = !1), Ka(a._zoomButton, a._resetButton)) : (this.zoomEnabled = !0, this.panEnabled = !1))
            } else this.panEnabled = this.zoomEnabled = !1;
            gb(this);
            "none" !== this._toolBar.style.display && this._zoomButton && (this.panEnabled ? va(a, a._zoomButton, "zoom") : va(a, a._zoomButton, "pan"), a._resetButton.getAttribute("state") !== a._cultureInfo.resetText && va(a, a._resetButton, "reset"));
            this.options.toolTip && this.toolTip.options !== this.options.toolTip && (this.toolTip.options =
                this.options.toolTip);
            for (var c in this.toolTip.options) this.toolTip.options.hasOwnProperty(c) && this.toolTip.updateOption(c)
        };
        m.prototype._updateSize = function() {
            var a;
            a = [this.canvas, this.overlaidCanvas, this._eventManager.ghostCanvas];
            var d = 0,
                c = 0;
            this.options.width ? d = this.width : this.width = d = 0 < this.container.clientWidth ? this.container.clientWidth : this.width;
            this.options.height ? c = this.height : this.height = c = 0 < this.container.clientHeight ? this.container.clientHeight : this.height;
            if (this.canvas.width !== d *
                ka || this.canvas.height !== c * ka) {
                for (var b = 0; b < a.length; b++) La(a[b], d, c);
                this.bounds = { x1: 0, y1: 0, x2: this.width, y2: this.height, width: this.width, height: this.height };
                a = !0
            } else a = !1;
            return a
        };
        m.prototype._initialize = function() {
            this.isNavigator = s(this.parent) || s(this.parent._defaultsKey) || "Navigator" !== this.parent._defaultsKey ? !1 : !0;
            this._animator ? this._animator.cancelAllAnimations() : this._animator = new ha(this);
            this.removeAllEventListeners();
            this.disableToolTip = !1;
            this._axes = [];
            this.funnelPyramidClickHandler =
                this.pieDoughnutClickHandler = null;
            this._updateOptions();
            this.animatedRender = u && this.animationEnabled && 0 === this.renderCount;
            this._updateSize();
            this.clearCanvas();
            this.ctx.beginPath();
            this.axisX = [];
            this.axisX2 = [];
            this.axisY = [];
            this.axisY2 = [];
            this._indexLabels = [];
            this._dataInRenderedOrder = [];
            this._events = [];
            this._eventManager && this._eventManager.reset();
            this.plotInfo = { axisPlacement: null, plotTypes: [] };
            this.layoutManager = new Da(0, 0, this.width, this.height, this.isNavigator ? 0 : 2);
            this.plotArea.layoutManager &&
                this.plotArea.layoutManager.reset();
            this.data = [];
            this.title = null;
            this.subtitles = [];
            var a = 0,
                d = null;
            if (this.options.data) {
                for (var c = 0; c < this.options.data.length; c++)
                    if (a++, !this.options.data[c].type || 0 <= m._supportedChartTypes.indexOf(this.options.data[c].type)) {
                        var b = new H(this, this.options.data[c], a - 1, ++this._eventManager.lastObjectId);
                        "error" === b.type && (b.linkedDataSeriesIndex = s(this.options.data[c].linkedDataSeriesIndex) ? c - 1 : this.options.data[c].linkedDataSeriesIndex, 0 > b.linkedDataSeriesIndex || b.linkedDataSeriesIndex >=
                            this.options.data.length || "number" !== typeof b.linkedDataSeriesIndex || "error" === this.options.data[b.linkedDataSeriesIndex].type) && (b.linkedDataSeriesIndex = null);
                        null === b.name && (b.name = "DataSeries " + a);
                        null === b.color ? 1 < this.options.data.length ? (b._colorSet = [this.selectedColorSet[b.index % this.selectedColorSet.length]], b.color = this.selectedColorSet[b.index % this.selectedColorSet.length]) : b._colorSet = "line" === b.type || "stepLine" === b.type || "spline" === b.type || "area" === b.type || "stepArea" === b.type || "splineArea" ===
                            b.type || "stackedArea" === b.type || "stackedArea100" === b.type || "rangeArea" === b.type || "rangeSplineArea" === b.type || "candlestick" === b.type || "ohlc" === b.type || "waterfall" === b.type || "boxAndWhisker" === b.type ? [this.selectedColorSet[0]] : this.selectedColorSet : b._colorSet = [b.color];
                        null === b.markerSize && (("line" === b.type || "stepLine" === b.type || "spline" === b.type || 0 <= b.type.toLowerCase().indexOf("area")) && b.dataPoints && b.dataPoints.length < this.width / 16 || "scatter" === b.type) && (b.markerSize = 8);
                        "bubble" !== b.type && "scatter" !==
                            b.type || !b.dataPoints || (b.dataPoints.some ? b.dataPoints.some(function(a) { return a.x }) && b.dataPoints.sort(h) : b.dataPoints.sort(h));
                        this.data.push(b);
                        var e = b.axisPlacement,
                            d = d || e,
                            f;
                        "normal" === e ? "xySwapped" === this.plotInfo.axisPlacement ? f = 'You cannot combine "' + b.type + '" with bar chart' : "none" === this.plotInfo.axisPlacement ? f = 'You cannot combine "' + b.type + '" with pie chart' : null === this.plotInfo.axisPlacement && (this.plotInfo.axisPlacement = "normal") : "xySwapped" === e ? "normal" === this.plotInfo.axisPlacement ?
                            f = 'You cannot combine "' + b.type + '" with line, area, column or pie chart' : "none" === this.plotInfo.axisPlacement ? f = 'You cannot combine "' + b.type + '" with pie chart' : null === this.plotInfo.axisPlacement && (this.plotInfo.axisPlacement = "xySwapped") : "none" === e ? "normal" === this.plotInfo.axisPlacement ? f = 'You cannot combine "' + b.type + '" with line, area, column or bar chart' : "xySwapped" === this.plotInfo.axisPlacement ? f = 'You cannot combine "' + b.type + '" with bar chart' : null === this.plotInfo.axisPlacement && (this.plotInfo.axisPlacement =
                                "none") : null === e && "none" === this.plotInfo.axisPlacement && (f = 'You cannot combine "' + b.type + '" with pie chart');
                        if (f && window.console) { window.console.log(f); return }
                    }
                for (c = 0; c < this.data.length; c++) {
                    if ("none" == d && "error" === this.data[c].type && window.console) { window.console.log('You cannot combine "' + b.type + '" with error chart'); return }
                    "error" === this.data[c].type && (this.data[c].axisPlacement = this.plotInfo.axisPlacement = d || "normal", this.data[c]._linkedSeries = null === this.data[c].linkedDataSeriesIndex ? null :
                        this.data[this.data[c].linkedDataSeriesIndex])
                }
            }
            this._objectsInitialized = !0;
            this._plotAreaElements = []
        };
        m._supportedChartTypes = Ca("line stepLine spline column area stepArea splineArea bar bubble scatter stackedColumn stackedColumn100 stackedBar stackedBar100 stackedArea stackedArea100 candlestick ohlc boxAndWhisker rangeColumn error rangeBar rangeArea rangeSplineArea pie doughnut funnel pyramid waterfall".split(" "));
        m.prototype.setLayout = function() {
            for (var a = this._plotAreaElements, d = 0; d < this.data.length; d++)
                if ("normal" ===
                    this.plotInfo.axisPlacement || "xySwapped" === this.plotInfo.axisPlacement) {
                    if (!this.data[d].axisYType || "primary" === this.data[d].axisYType)
                        if (this.options.axisY && 0 < this.options.axisY.length) {
                            if (!this.axisY.length)
                                for (var c = 0; c < this.options.axisY.length; c++) "normal" === this.plotInfo.axisPlacement ? this._axes.push(this.axisY[c] = new D(this, "axisY", this.options.axisY[c], c, "axisY", "left")) : "xySwapped" === this.plotInfo.axisPlacement && this._axes.push(this.axisY[c] = new D(this, "axisY", this.options.axisY[c], c, "axisY",
                                    "bottom"));
                            this.data[d].axisY = this.axisY[0 <= this.data[d].axisYIndex && this.data[d].axisYIndex < this.axisY.length ? this.data[d].axisYIndex : 0];
                            this.axisY[0 <= this.data[d].axisYIndex && this.data[d].axisYIndex < this.axisY.length ? this.data[d].axisYIndex : 0].dataSeries.push(this.data[d])
                        } else this.axisY.length || ("normal" === this.plotInfo.axisPlacement ? this._axes.push(this.axisY[0] = new D(this, "axisY", this.options.axisY, 0, "axisY", "left")) : "xySwapped" === this.plotInfo.axisPlacement && this._axes.push(this.axisY[0] = new D(this,
                            "axisY", this.options.axisY, 0, "axisY", "bottom"))), this.data[d].axisY = this.axisY[0], this.axisY[0].dataSeries.push(this.data[d]);
                    if ("secondary" === this.data[d].axisYType)
                        if (this.options.axisY2 && 0 < this.options.axisY2.length) {
                            if (!this.axisY2.length)
                                for (c = 0; c < this.options.axisY2.length; c++) "normal" === this.plotInfo.axisPlacement ? this._axes.push(this.axisY2[c] = new D(this, "axisY2", this.options.axisY2[c], c, "axisY", "right")) : "xySwapped" === this.plotInfo.axisPlacement && this._axes.push(this.axisY2[c] = new D(this,
                                    "axisY2", this.options.axisY2[c], c, "axisY", "top"));
                            this.data[d].axisY = this.axisY2[0 <= this.data[d].axisYIndex && this.data[d].axisYIndex < this.axisY2.length ? this.data[d].axisYIndex : 0];
                            this.axisY2[0 <= this.data[d].axisYIndex && this.data[d].axisYIndex < this.axisY2.length ? this.data[d].axisYIndex : 0].dataSeries.push(this.data[d])
                        } else this.axisY2.length || ("normal" === this.plotInfo.axisPlacement ? this._axes.push(this.axisY2[0] = new D(this, "axisY2", this.options.axisY2, 0, "axisY", "right")) : "xySwapped" === this.plotInfo.axisPlacement &&
                            this._axes.push(this.axisY2[0] = new D(this, "axisY2", this.options.axisY2, 0, "axisY", "top"))), this.data[d].axisY = this.axisY2[0], this.axisY2[0].dataSeries.push(this.data[d]);
                    if (!this.data[d].axisXType || "primary" === this.data[d].axisXType)
                        if (this.options.axisX && 0 < this.options.axisX.length) {
                            if (!this.axisX.length)
                                for (c = 0; c < this.options.axisX.length; c++) "normal" === this.plotInfo.axisPlacement ? this._axes.push(this.axisX[c] = new D(this, "axisX", this.options.axisX[c], c, "axisX", "bottom")) : "xySwapped" === this.plotInfo.axisPlacement &&
                                    this._axes.push(this.axisX[c] = new D(this, "axisX", this.options.axisX[c], c, "axisX", "left"));
                            this.data[d].axisX = this.axisX[0 <= this.data[d].axisXIndex && this.data[d].axisXIndex < this.axisX.length ? this.data[d].axisXIndex : 0];
                            this.axisX[0 <= this.data[d].axisXIndex && this.data[d].axisXIndex < this.axisX.length ? this.data[d].axisXIndex : 0].dataSeries.push(this.data[d])
                        } else this.axisX.length || ("normal" === this.plotInfo.axisPlacement ? this._axes.push(this.axisX[0] = new D(this, "axisX", this.options.axisX, 0, "axisX", "bottom")) :
                            "xySwapped" === this.plotInfo.axisPlacement && this._axes.push(this.axisX[0] = new D(this, "axisX", this.options.axisX, 0, "axisX", "left"))), this.data[d].axisX = this.axisX[0], this.axisX[0].dataSeries.push(this.data[d]);
                    if ("secondary" === this.data[d].axisXType)
                        if (this.options.axisX2 && 0 < this.options.axisX2.length) {
                            if (!this.axisX2.length)
                                for (c = 0; c < this.options.axisX2.length; c++) "normal" === this.plotInfo.axisPlacement ? this._axes.push(this.axisX2[c] = new D(this, "axisX2", this.options.axisX2[c], c, "axisX", "top")) : "xySwapped" ===
                                    this.plotInfo.axisPlacement && this._axes.push(this.axisX2[c] = new D(this, "axisX2", this.options.axisX2[c], c, "axisX", "right"));
                            this.data[d].axisX = this.axisX2[0 <= this.data[d].axisXIndex && this.data[d].axisXIndex < this.axisX2.length ? this.data[d].axisXIndex : 0];
                            this.axisX2[0 <= this.data[d].axisXIndex && this.data[d].axisXIndex < this.axisX2.length ? this.data[d].axisXIndex : 0].dataSeries.push(this.data[d])
                        } else this.axisX2.length || ("normal" === this.plotInfo.axisPlacement ? this._axes.push(this.axisX2[0] = new D(this, "axisX2",
                            this.options.axisX2, 0, "axisX", "top")) : "xySwapped" === this.plotInfo.axisPlacement && this._axes.push(this.axisX2[0] = new D(this, "axisX2", this.options.axisX2, 0, "axisX", "right"))), this.data[d].axisX = this.axisX2[0], this.axisX2[0].dataSeries.push(this.data[d])
                }
            if (this.axisY) { for (c = 1; c < this.axisY.length; c++) "undefined" === typeof this.axisY[c].options.gridThickness && (this.axisY[c].gridThickness = 0); for (c = 0; c < this.axisY.length - 1; c++) "undefined" === typeof this.axisY[c].options.margin && (this.axisY[c].margin = 10) }
            if (this.axisY2) {
                for (c =
                    1; c < this.axisY2.length; c++) "undefined" === typeof this.axisY2[c].options.gridThickness && (this.axisY2[c].gridThickness = 0);
                for (c = 0; c < this.axisY2.length - 1; c++) "undefined" === typeof this.axisY2[c].options.margin && (this.axisY2[c].margin = 10)
            }
            this.axisY && 0 < this.axisY.length && (this.axisY2 && 0 < this.axisY2.length) && (0 < this.axisY[0].gridThickness && "undefined" === typeof this.axisY2[0].options.gridThickness ? this.axisY2[0].gridThickness = 0 : 0 < this.axisY2[0].gridThickness && "undefined" === typeof this.axisY[0].options.gridThickness &&
                (this.axisY[0].gridThickness = 0));
            if (this.axisX)
                for (c = 0; c < this.axisX.length; c++) "undefined" === typeof this.axisX[c].options.gridThickness && (this.axisX[c].gridThickness = 0);
            if (this.axisX2)
                for (c = 0; c < this.axisX2.length; c++) "undefined" === typeof this.axisX2[c].options.gridThickness && (this.axisX2[c].gridThickness = 0);
            this.axisX && 0 < this.axisX.length && (this.axisX2 && 0 < this.axisX2.length) && (0 < this.axisX[0].gridThickness && "undefined" === typeof this.axisX2[0].options.gridThickness ? this.axisX2[0].gridThickness = 0 : 0 <
                this.axisX2[0].gridThickness && "undefined" === typeof this.axisX[0].options.gridThickness && (this.axisX[0].gridThickness = 0));
            c = !1;
            if (0 < this._axes.length && this.options.zoomEnabled && (this.zoomEnabled || this.panEnabled))
                for (d = 0; d < this._axes.length; d++)
                    if (null !== this._axes[d].viewportMinimum || null !== this._axes[d].viewportMaximum) { c = !0; break }
            c ? (Ka(this._zoomButton, this._resetButton), this._toolBar.style.border = this.toolbar.buttonBorderThickness + "px solid " + this.toolbar.buttonBorderColor, this._zoomButton.style.borderRight =
                this.toolbar.buttonBorderThickness + "px solid " + this.toolbar.buttonBorderColor, this._resetButton.style.borderRight = (this.exportEnabled ? this.toolbar.buttonBorderThickness : 0) + "px solid " + this.toolbar.buttonBorderColor) : (ua(this._zoomButton, this._resetButton), this._toolBar.style.border = this.toolbar.buttonBorderThickness + "px solid transparent", this.options.zoomEnabled && (this.zoomEnabled = !0, this.panEnabled = !1));
            eb(this);
            this._processData();
            this.options.title && (this.title = new ya(this, this.options.title),
                this.title.dockInsidePlotArea ? a.push(this.title) : this.title.setLayout());
            if (this.options.subtitles)
                for (d = 0; d < this.options.subtitles.length; d++) c = new Ha(this, this.options.subtitles[d], d), this.subtitles.push(c), c.dockInsidePlotArea ? a.push(c) : c.setLayout();
            this.legend = new F(this, this.options.legend);
            for (d = 0; d < this.data.length; d++)(this.data[d].showInLegend || "pie" === this.data[d].type || "doughnut" === this.data[d].type || "funnel" === this.data[d].type || "pyramid" === this.data[d].type) && this.legend.dataSeries.push(this.data[d]);
            this.legend.dockInsidePlotArea ? a.push(this.legend) : this.legend.setLayout();
            for (d = 0; d < this._axes.length; d++)
                if (this._axes[d].scaleBreaks && this._axes[d].scaleBreaks._appliedBreaks.length) { u ? (this._breaksCanvas = ta(this.width, this.height, !0), this._breaksCanvasCtx = this._breaksCanvas.getContext("2d")) : (this._breaksCanvas = this.canvas, this._breaksCanvasCtx = this.ctx); break }
            this._preRenderCanvas = ta(this.width, this.height);
            this._preRenderCtx = this._preRenderCanvas.getContext("2d");
            "normal" !== this.plotInfo.axisPlacement &&
                "xySwapped" !== this.plotInfo.axisPlacement || D.setLayout(this.axisX, this.axisX2, this.axisY, this.axisY2, this.plotInfo.axisPlacement, this.layoutManager.getFreeSpace())
        };
        m.prototype.renderElements = function() {
            var a = this._plotAreaElements;
            this.title && !this.title.dockInsidePlotArea && this.title.render();
            for (var d = 0; d < this.subtitles.length; d++) this.subtitles[d].dockInsidePlotArea || this.subtitles[d].render();
            this.legend.dockInsidePlotArea || this.legend.render();
            if ("normal" === this.plotInfo.axisPlacement || "xySwapped" ===
                this.plotInfo.axisPlacement) D.render(this.axisX, this.axisX2, this.axisY, this.axisY2, this.plotInfo.axisPlacement);
            else if ("none" === this.plotInfo.axisPlacement) this.preparePlotArea();
            else return;
            for (d = 0; d < a.length; d++) a[d].setLayout(), a[d].render();
            var c = [];
            if (this.animatedRender) {
                var b = ta(this.width, this.height);
                b.getContext("2d").drawImage(this.canvas, 0, 0, this.width, this.height)
            }
            hb(this);
            var a = this.ctx.miterLimit,
                e;
            this.ctx.miterLimit = 3;
            u && this._breaksCanvas && (this._preRenderCtx.drawImage(this.canvas,
                0, 0, this.width, this.height), this._preRenderCtx.drawImage(this._breaksCanvas, 0, 0, this.width, this.height), this._breaksCanvasCtx.globalCompositeOperation = "source-atop", this._breaksCanvasCtx.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), this._preRenderCtx.clearRect(0, 0, this.width, this.height));
            for (d = 0; d < this.plotInfo.plotTypes.length; d++)
                for (var f = this.plotInfo.plotTypes[d], l = 0; l < f.plotUnits.length; l++) {
                    var v = f.plotUnits[l],
                        A = null;
                    v.targetCanvas = null;
                    this.animatedRender && (v.targetCanvas =
                        ta(this.width, this.height), v.targetCanvasCtx = v.targetCanvas.getContext("2d"), e = v.targetCanvasCtx.miterLimit, v.targetCanvasCtx.miterLimit = 3);
                    "line" === v.type ? A = this.renderLine(v) : "stepLine" === v.type ? A = this.renderStepLine(v) : "spline" === v.type ? A = this.renderSpline(v) : "column" === v.type ? A = this.renderColumn(v) : "bar" === v.type ? A = this.renderBar(v) : "area" === v.type ? A = this.renderArea(v) : "stepArea" === v.type ? A = this.renderStepArea(v) : "splineArea" === v.type ? A = this.renderSplineArea(v) : "stackedColumn" === v.type ? A = this.renderStackedColumn(v) :
                        "stackedColumn100" === v.type ? A = this.renderStackedColumn100(v) : "stackedBar" === v.type ? A = this.renderStackedBar(v) : "stackedBar100" === v.type ? A = this.renderStackedBar100(v) : "stackedArea" === v.type ? A = this.renderStackedArea(v) : "stackedArea100" === v.type ? A = this.renderStackedArea100(v) : "bubble" === v.type ? A = A = this.renderBubble(v) : "scatter" === v.type ? A = this.renderScatter(v) : "pie" === v.type ? this.renderPie(v) : "doughnut" === v.type ? this.renderPie(v) : "funnel" === v.type ? A = this.renderFunnel(v) : "pyramid" === v.type ? A = this.renderFunnel(v) :
                        "candlestick" === v.type ? A = this.renderCandlestick(v) : "ohlc" === v.type ? A = this.renderCandlestick(v) : "rangeColumn" === v.type ? A = this.renderRangeColumn(v) : "error" === v.type ? A = this.renderError(v) : "rangeBar" === v.type ? A = this.renderRangeBar(v) : "rangeArea" === v.type ? A = this.renderRangeArea(v) : "rangeSplineArea" === v.type ? A = this.renderRangeSplineArea(v) : "waterfall" === v.type ? A = this.renderWaterfall(v) : "boxAndWhisker" === v.type && (A = this.renderBoxAndWhisker(v));
                    for (var k = 0; k < v.dataSeriesIndexes.length; k++) this._dataInRenderedOrder.push(this.data[v.dataSeriesIndexes[k]]);
                    this.animatedRender && (v.targetCanvasCtx.miterLimit = e, A && c.push(A))
                }
            this.ctx.miterLimit = a;
            this.animatedRender && this._breaksCanvasCtx && c.push({ source: this._breaksCanvasCtx, dest: this.plotArea.ctx, animationCallback: K.fadeInAnimation, easingFunction: K.easing.easeInQuad, animationBase: 0, startTimePercent: 0.7 });
            this.animatedRender && 0 < this._indexLabels.length && (e = ta(this.width, this.height).getContext("2d"), c.push(this.renderIndexLabels(e)));
            var n = this;
            if (0 < c.length) n.disableToolTip = !0, n._animator.animate(200,
                n.animationDuration,
                function(a) {
                    n.ctx.clearRect(0, 0, n.width, n.height);
                    n.ctx.drawImage(b, 0, 0, Math.floor(n.width * ka), Math.floor(n.height * ka), 0, 0, n.width, n.height);
                    for (var e = 0; e < c.length; e++) A = c[e], 1 > a && "undefined" !== typeof A.startTimePercent ? a >= A.startTimePercent && A.animationCallback(A.easingFunction(a - A.startTimePercent, 0, 1, 1 - A.startTimePercent), A) : A.animationCallback(A.easingFunction(a, 0, 1, 1), A);
                    n.dispatchEvent("dataAnimationIterationEnd", { chart: n })
                },
                function() {
                    c = [];
                    for (var a = 0; a < n.plotInfo.plotTypes.length; a++)
                        for (var e =
                                n.plotInfo.plotTypes[a], d = 0; d < e.plotUnits.length; d++) e.plotUnits[d].targetCanvas = null;
                    b = null;
                    n.disableToolTip = !1;
                    n.dispatchEvent("dataAnimationEnd", { chart: n })
                });
            else {
                if (n._breaksCanvas)
                    if (u) n.plotArea.ctx.drawImage(n._breaksCanvas, 0, 0, this.width, this.height);
                    else
                        for (k = 0; k < n._axes.length; k++) n._axes[k].createMask();
                0 < n._indexLabels.length && n.renderIndexLabels();
                n.dispatchEvent("dataAnimationIterationEnd", { chart: n });
                n.dispatchEvent("dataAnimationEnd", { chart: n })
            }
            this.attachPlotAreaEventHandlers();
            this.zoomEnabled ||
                (this.panEnabled || !this._zoomButton || "none" === this._zoomButton.style.display) || ua(this._zoomButton, this._resetButton);
            this.toolTip._updateToolTip();
            this.renderCount++;
            Ga && (n = this, setTimeout(function() {
                var a = document.getElementById("ghostCanvasCopy");
                a && (La(a, n.width, n.height), a.getContext("2d").drawImage(n._eventManager.ghostCanvas, 0, 0))
            }, 2E3));
            this._breaksCanvas && (delete this._breaksCanvas, delete this._breaksCanvasCtx);
            for (k = 0; k < this._axes.length; k++) this._axes[k].maskCanvas && (delete this._axes[k].maskCanvas,
                delete this._axes[k].maskCtx)
        };
        m.prototype.render = function(a) {
            a && (this.options = a);
            this._initialize();
            this.setLayout();
            this.renderElements();
            this._preRenderCanvas = null
        };
        m.prototype.attachPlotAreaEventHandlers = function() { this.attachEvent({ context: this, chart: this, mousedown: this._plotAreaMouseDown, mouseup: this._plotAreaMouseUp, mousemove: this._plotAreaMouseMove, cursor: this.panEnabled ? "move" : "default", capture: !0, bounds: this.plotArea }) };
        m.prototype.categoriseDataSeries = function() {
            for (var a = "", d = 0; d < this.data.length; d++)
                if (a =
                    this.data[d], a.dataPoints && (0 !== a.dataPoints.length && a.visible) && 0 <= m._supportedChartTypes.indexOf(a.type)) {
                    for (var c = null, b = !1, e = null, f = !1, l = 0; l < this.plotInfo.plotTypes.length; l++)
                        if (this.plotInfo.plotTypes[l].type === a.type) {
                            b = !0;
                            c = this.plotInfo.plotTypes[l];
                            break
                        }
                    b || (c = { type: a.type, totalDataSeries: 0, plotUnits: [] }, this.plotInfo.plotTypes.push(c));
                    for (l = 0; l < c.plotUnits.length; l++)
                        if (c.plotUnits[l].axisYType === a.axisYType && c.plotUnits[l].axisXType === a.axisXType && c.plotUnits[l].axisYIndex === a.axisYIndex &&
                            c.plotUnits[l].axisXIndex === a.axisXIndex) {
                            f = !0;
                            e = c.plotUnits[l];
                            break
                        }
                    f || (e = {
                        type: a.type,
                        previousDataSeriesCount: 0,
                        index: c.plotUnits.length,
                        plotType: c,
                        axisXType: a.axisXType,
                        axisYType: a.axisYType,
                        axisYIndex: a.axisYIndex,
                        axisXIndex: a.axisXIndex,
                        axisY: "primary" === a.axisYType ? this.axisY[0 <= a.axisYIndex && a.axisYIndex < this.axisY.length ? a.axisYIndex : 0] : this.axisY2[0 <= a.axisYIndex && a.axisYIndex < this.axisY2.length ? a.axisYIndex : 0],
                        axisX: "primary" === a.axisXType ? this.axisX[0 <= a.axisXIndex && a.axisXIndex < this.axisX.length ?
                            a.axisXIndex : 0] : this.axisX2[0 <= a.axisXIndex && a.axisXIndex < this.axisX2.length ? a.axisXIndex : 0],
                        dataSeriesIndexes: [],
                        yTotals: [],
                        yAbsTotals: []
                    }, c.plotUnits.push(e));
                    c.totalDataSeries++;
                    e.dataSeriesIndexes.push(d);
                    a.plotUnit = e
                }
            for (d = 0; d < this.plotInfo.plotTypes.length; d++)
                for (c = this.plotInfo.plotTypes[d], l = a = 0; l < c.plotUnits.length; l++) c.plotUnits[l].previousDataSeriesCount = a, a += c.plotUnits[l].dataSeriesIndexes.length
        };
        m.prototype.assignIdToDataPoints = function() {
            for (var a = 0; a < this.data.length; a++) {
                var d =
                    this.data[a];
                if (d.dataPoints)
                    for (var c = d.dataPoints.length, b = 0; b < c; b++) d.dataPointIds[b] = ++this._eventManager.lastObjectId
            }
        };
        m.prototype._processData = function() {
            this.assignIdToDataPoints();
            this.categoriseDataSeries();
            for (var a = 0; a < this.plotInfo.plotTypes.length; a++)
                for (var d = this.plotInfo.plotTypes[a], c = 0; c < d.plotUnits.length; c++) {
                    var b = d.plotUnits[c];
                    "line" === b.type || "stepLine" === b.type || "spline" === b.type || "column" === b.type || "area" === b.type || "stepArea" === b.type || "splineArea" === b.type || "bar" === b.type ||
                        "bubble" === b.type || "scatter" === b.type ? this._processMultiseriesPlotUnit(b) : "stackedColumn" === b.type || "stackedBar" === b.type || "stackedArea" === b.type ? this._processStackedPlotUnit(b) : "stackedColumn100" === b.type || "stackedBar100" === b.type || "stackedArea100" === b.type ? this._processStacked100PlotUnit(b) : "candlestick" === b.type || "ohlc" === b.type || "rangeColumn" === b.type || "rangeBar" === b.type || "rangeArea" === b.type || "rangeSplineArea" === b.type || "error" === b.type || "boxAndWhisker" === b.type ? this._processMultiYPlotUnit(b) :
                        "waterfall" === b.type && this._processSpecificPlotUnit(b)
                }
            this.calculateAutoBreaks()
        };
        m.prototype._processMultiseriesPlotUnit = function(a) {
            if (a.dataSeriesIndexes && !(1 > a.dataSeriesIndexes.length))
                for (var d = a.axisY.dataInfo, c = a.axisX.dataInfo, b, e, f = !1, l = 0; l < a.dataSeriesIndexes.length; l++) {
                    var v = this.data[a.dataSeriesIndexes[l]],
                        A = 0,
                        k = !1,
                        n = !1,
                        p;
                    if ("normal" === v.axisPlacement || "xySwapped" === v.axisPlacement) var q = a.axisX.sessionVariables.newViewportMinimum ? a.axisX.sessionVariables.newViewportMinimum : this.options.axisX &&
                        this.options.axisX.viewportMinimum ? this.options.axisX.viewportMinimum : this.options.axisX && this.options.axisX.minimum ? this.options.axisX.minimum : a.axisX.logarithmic ? 0 : -Infinity,
                        g = a.axisX.sessionVariables.newViewportMaximum ? a.axisX.sessionVariables.newViewportMaximum : this.options.axisX && this.options.axisX.viewportMaximum ? this.options.axisX.viewportMaximum : this.options.axisX && this.options.axisX.maximum ? this.options.axisX.maximum : Infinity;
                    if (v.dataPoints[A].x && v.dataPoints[A].x.getTime || "dateTime" ===
                        v.xValueType) f = !0;
                    for (A = 0; A < v.dataPoints.length; A++) {
                        "undefined" === typeof v.dataPoints[A].x && (v.dataPoints[A].x = A + (a.axisX.logarithmic ? 1 : 0));
                        v.dataPoints[A].x.getTime ? (f = !0, b = v.dataPoints[A].x.getTime()) : b = v.dataPoints[A].x;
                        e = v.dataPoints[A].y;
                        b < c.min && (c.min = b);
                        b > c.max && (c.max = b);
                        e < d.min && "number" === typeof e && (d.min = e);
                        e > d.max && "number" === typeof e && (d.max = e);
                        if (0 < A) {
                            if (a.axisX.logarithmic) {
                                var r = b / v.dataPoints[A - 1].x;
                                1 > r && (r = 1 / r);
                                c.minDiff > r && 1 !== r && (c.minDiff = r)
                            } else r = b - v.dataPoints[A - 1].x, 0 > r &&
                                (r *= -1), c.minDiff > r && 0 !== r && (c.minDiff = r);
                            null !== e && null !== v.dataPoints[A - 1].y && (a.axisY.logarithmic ? (r = e / v.dataPoints[A - 1].y, 1 > r && (r = 1 / r), d.minDiff > r && 1 !== r && (d.minDiff = r)) : (r = e - v.dataPoints[A - 1].y, 0 > r && (r *= -1), d.minDiff > r && 0 !== r && (d.minDiff = r)))
                        }
                        if (b < q && !k) null !== e && (p = b);
                        else {
                            if (!k && (k = !0, 0 < A)) { A -= 2; continue }
                            if (b > g && !n) n = !0;
                            else if (b > g && n) continue;
                            v.dataPoints[A].label && (a.axisX.labels[b] = v.dataPoints[A].label);
                            b < c.viewPortMin && (c.viewPortMin = b);
                            b > c.viewPortMax && (c.viewPortMax = b);
                            null === e ? c.viewPortMin ===
                                b && p < b && (c.viewPortMin = p) : (e < d.viewPortMin && "number" === typeof e && (d.viewPortMin = e), e > d.viewPortMax && "number" === typeof e && (d.viewPortMax = e))
                        }
                    }
                    v.axisX.valueType = v.xValueType = f ? "dateTime" : "number"
                }
        };
        m.prototype._processStackedPlotUnit = function(a) {
            if (a.dataSeriesIndexes && !(1 > a.dataSeriesIndexes.length)) {
                for (var d = a.axisY.dataInfo, c = a.axisX.dataInfo, b, e, f = !1, l = [], v = [], A = Infinity, k = -Infinity, n = 0; n < a.dataSeriesIndexes.length; n++) {
                    var p = this.data[a.dataSeriesIndexes[n]],
                        q = 0,
                        g = !1,
                        r = !1,
                        h;
                    if ("normal" === p.axisPlacement ||
                        "xySwapped" === p.axisPlacement) var m = a.axisX.sessionVariables.newViewportMinimum ? a.axisX.sessionVariables.newViewportMinimum : this.options.axisX && this.options.axisX.viewportMinimum ? this.options.axisX.viewportMinimum : this.options.axisX && this.options.axisX.minimum ? this.options.axisX.minimum : -Infinity,
                        t = a.axisX.sessionVariables.newViewportMaximum ? a.axisX.sessionVariables.newViewportMaximum : this.options.axisX && this.options.axisX.viewportMaximum ? this.options.axisX.viewportMaximum : this.options.axisX && this.options.axisX.maximum ?
                        this.options.axisX.maximum : Infinity;
                    if (p.dataPoints[q].x && p.dataPoints[q].x.getTime || "dateTime" === p.xValueType) f = !0;
                    for (q = 0; q < p.dataPoints.length; q++) {
                        "undefined" === typeof p.dataPoints[q].x && (p.dataPoints[q].x = q + (a.axisX.logarithmic ? 1 : 0));
                        p.dataPoints[q].x.getTime ? (f = !0, b = p.dataPoints[q].x.getTime()) : b = p.dataPoints[q].x;
                        e = s(p.dataPoints[q].y) ? 0 : p.dataPoints[q].y;
                        b < c.min && (c.min = b);
                        b > c.max && (c.max = b);
                        if (0 < q) {
                            if (a.axisX.logarithmic) {
                                var x = b / p.dataPoints[q - 1].x;
                                1 > x && (x = 1 / x);
                                c.minDiff > x && 1 !== x && (c.minDiff =
                                    x)
                            } else x = b - p.dataPoints[q - 1].x, 0 > x && (x *= -1), c.minDiff > x && 0 !== x && (c.minDiff = x);
                            null !== e && null !== p.dataPoints[q - 1].y && (a.axisY.logarithmic ? 0 < e && (x = e / p.dataPoints[q - 1].y, 1 > x && (x = 1 / x), d.minDiff > x && 1 !== x && (d.minDiff = x)) : (x = e - p.dataPoints[q - 1].y, 0 > x && (x *= -1), d.minDiff > x && 0 !== x && (d.minDiff = x)))
                        }
                        if (b < m && !g) null !== p.dataPoints[q].y && (h = b);
                        else {
                            if (!g && (g = !0, 0 < q)) { q -= 2; continue }
                            if (b > t && !r) r = !0;
                            else if (b > t && r) continue;
                            p.dataPoints[q].label && (a.axisX.labels[b] = p.dataPoints[q].label);
                            b < c.viewPortMin && (c.viewPortMin =
                                b);
                            b > c.viewPortMax && (c.viewPortMax = b);
                            null === p.dataPoints[q].y ? c.viewPortMin === b && h < b && (c.viewPortMin = h) : (a.yTotals[b] = (a.yTotals[b] ? a.yTotals[b] : 0) + e, a.yAbsTotals[b] = (a.yAbsTotals[b] ? a.yAbsTotals[b] : 0) + Math.abs(e), 0 <= e ? l[b] ? l[b] += e : (l[b] = e, A = Math.min(e, A)) : v[b] ? v[b] += e : (v[b] = e, k = Math.max(e, k)))
                        }
                    }
                    a.axisY.scaleBreaks && (a.axisY.scaleBreaks.autoCalculate && 1 <= a.axisY.scaleBreaks.maxNumberOfAutoBreaks) && (d.dataPointYPositiveSums ? (d.dataPointYPositiveSums.push.apply(d.dataPointYPositiveSums, l), d.dataPointYNegativeSums.push.apply(d.dataPointYPositiveSums,
                        v)) : (d.dataPointYPositiveSums = l, d.dataPointYNegativeSums = v));
                    p.axisX.valueType = p.xValueType = f ? "dateTime" : "number"
                }
                for (q in l) l.hasOwnProperty(q) && !isNaN(q) && (a = l[q], a < d.min && (d.min = Math.min(a, A)), a > d.max && (d.max = a), q < c.viewPortMin || q > c.viewPortMax || (a < d.viewPortMin && (d.viewPortMin = Math.min(a, A)), a > d.viewPortMax && (d.viewPortMax = a)));
                for (q in v) v.hasOwnProperty(q) && !isNaN(q) && (a = v[q], a < d.min && (d.min = a), a > d.max && (d.max = Math.max(a, k)), q < c.viewPortMin || q > c.viewPortMax || (a < d.viewPortMin && (d.viewPortMin =
                    a), a > d.viewPortMax && (d.viewPortMax = Math.max(a, k))))
            }
        };
        m.prototype._processStacked100PlotUnit = function(a) {
            if (a.dataSeriesIndexes && !(1 > a.dataSeriesIndexes.length)) {
                for (var d = a.axisY.dataInfo, c = a.axisX.dataInfo, b, e, f = !1, l = !1, v = !1, A = [], k = 0; k < a.dataSeriesIndexes.length; k++) {
                    var n = this.data[a.dataSeriesIndexes[k]],
                        p = 0,
                        q = !1,
                        g = !1,
                        r;
                    if ("normal" === n.axisPlacement || "xySwapped" === n.axisPlacement) var h = a.axisX.sessionVariables.newViewportMinimum ? a.axisX.sessionVariables.newViewportMinimum : this.options.axisX &&
                        this.options.axisX.viewportMinimum ? this.options.axisX.viewportMinimum : this.options.axisX && this.options.axisX.minimum ? this.options.axisX.minimum : -Infinity,
                        m = a.axisX.sessionVariables.newViewportMaximum ? a.axisX.sessionVariables.newViewportMaximum : this.options.axisX && this.options.axisX.viewportMaximum ? this.options.axisX.viewportMaximum : this.options.axisX && this.options.axisX.maximum ? this.options.axisX.maximum : Infinity;
                    if (n.dataPoints[p].x && n.dataPoints[p].x.getTime || "dateTime" === n.xValueType) f = !0;
                    for (p =
                        0; p < n.dataPoints.length; p++) {
                        "undefined" === typeof n.dataPoints[p].x && (n.dataPoints[p].x = p + (a.axisX.logarithmic ? 1 : 0));
                        n.dataPoints[p].x.getTime ? (f = !0, b = n.dataPoints[p].x.getTime()) : b = n.dataPoints[p].x;
                        e = s(n.dataPoints[p].y) ? null : n.dataPoints[p].y;
                        b < c.min && (c.min = b);
                        b > c.max && (c.max = b);
                        if (0 < p) {
                            if (a.axisX.logarithmic) {
                                var t = b / n.dataPoints[p - 1].x;
                                1 > t && (t = 1 / t);
                                c.minDiff > t && 1 !== t && (c.minDiff = t)
                            } else t = b - n.dataPoints[p - 1].x, 0 > t && (t *= -1), c.minDiff > t && 0 !== t && (c.minDiff = t);
                            s(e) || null === n.dataPoints[p - 1].y ||
                                (a.axisY.logarithmic ? 0 < e && (t = e / n.dataPoints[p - 1].y, 1 > t && (t = 1 / t), d.minDiff > t && 1 !== t && (d.minDiff = t)) : (t = e - n.dataPoints[p - 1].y, 0 > t && (t *= -1), d.minDiff > t && 0 !== t && (d.minDiff = t)))
                        }
                        if (b < h && !q) null !== e && (r = b);
                        else {
                            if (!q && (q = !0, 0 < p)) { p -= 2; continue }
                            if (b > m && !g) g = !0;
                            else if (b > m && g) continue;
                            n.dataPoints[p].label && (a.axisX.labels[b] = n.dataPoints[p].label);
                            b < c.viewPortMin && (c.viewPortMin = b);
                            b > c.viewPortMax && (c.viewPortMax = b);
                            null === e ? c.viewPortMin === b && r < b && (c.viewPortMin = r) : (a.yTotals[b] = (a.yTotals[b] ? a.yTotals[b] :
                                0) + e, a.yAbsTotals[b] = (a.yAbsTotals[b] ? a.yAbsTotals[b] : 0) + Math.abs(e), 0 <= e ? l = !0 : 0 > e && (v = !0), A[b] = A[b] ? A[b] + Math.abs(e) : Math.abs(e))
                        }
                    }
                    n.axisX.valueType = n.xValueType = f ? "dateTime" : "number"
                }
                a.axisY.logarithmic ? (d.max = s(d.viewPortMax) ? 99 * Math.pow(a.axisY.logarithmBase, -0.05) : Math.max(d.viewPortMax, 99 * Math.pow(a.axisY.logarithmBase, -0.05)), d.min = s(d.viewPortMin) ? 1 : Math.min(d.viewPortMin, 1)) : l && !v ? (d.max = s(d.viewPortMax) ? 99 : Math.max(d.viewPortMax, 99), d.min = s(d.viewPortMin) ? 1 : Math.min(d.viewPortMin, 1)) : l &&
                    v ? (d.max = s(d.viewPortMax) ? 99 : Math.max(d.viewPortMax, 99), d.min = s(d.viewPortMin) ? -99 : Math.min(d.viewPortMin, -99)) : !l && v && (d.max = s(d.viewPortMax) ? -1 : Math.max(d.viewPortMax, -1), d.min = s(d.viewPortMin) ? -99 : Math.min(d.viewPortMin, -99));
                d.viewPortMin = d.min;
                d.viewPortMax = d.max;
                a.dataPointYSums = A
            }
        };
        m.prototype._processMultiYPlotUnit = function(a) {
            if (a.dataSeriesIndexes && !(1 > a.dataSeriesIndexes.length))
                for (var d = a.axisY.dataInfo, c = a.axisX.dataInfo, b, e, f, l, v = !1, A = 0; A < a.dataSeriesIndexes.length; A++) {
                    var k = this.data[a.dataSeriesIndexes[A]],
                        n = 0,
                        p = !1,
                        q = !1,
                        g, r, h;
                    if ("normal" === k.axisPlacement || "xySwapped" === k.axisPlacement) var m = a.axisX.sessionVariables.newViewportMinimum ? a.axisX.sessionVariables.newViewportMinimum : this.options.axisX && this.options.axisX.viewportMinimum ? this.options.axisX.viewportMinimum : this.options.axisX && this.options.axisX.minimum ? this.options.axisX.minimum : a.axisX.logarithmic ? 0 : -Infinity,
                        t = a.axisX.sessionVariables.newViewportMaximum ? a.axisX.sessionVariables.newViewportMaximum : this.options.axisX && this.options.axisX.viewportMaximum ?
                        this.options.axisX.viewportMaximum : this.options.axisX && this.options.axisX.maximum ? this.options.axisX.maximum : Infinity;
                    if (k.dataPoints[n].x && k.dataPoints[n].x.getTime || "dateTime" === k.xValueType) v = !0;
                    for (n = 0; n < k.dataPoints.length; n++) {
                        "undefined" === typeof k.dataPoints[n].x && (k.dataPoints[n].x = n + (a.axisX.logarithmic ? 1 : 0));
                        k.dataPoints[n].x.getTime ? (v = !0, b = k.dataPoints[n].x.getTime()) : b = k.dataPoints[n].x;
                        if ((e = k.dataPoints[n].y) && e.length) {
                            f = Math.min.apply(null, e);
                            l = Math.max.apply(null, e);
                            r = !0;
                            for (var x =
                                    0; x < e.length; x++) null === e.k && (r = !1);
                            r && (p || (h = g), g = b)
                        }
                        b < c.min && (c.min = b);
                        b > c.max && (c.max = b);
                        f < d.min && (d.min = f);
                        l > d.max && (d.max = l);
                        0 < n && (a.axisX.logarithmic ? (r = b / k.dataPoints[n - 1].x, 1 > r && (r = 1 / r), c.minDiff > r && 1 !== r && (c.minDiff = r)) : (r = b - k.dataPoints[n - 1].x, 0 > r && (r *= -1), c.minDiff > r && 0 !== r && (c.minDiff = r)), e && (null !== e[0] && k.dataPoints[n - 1].y && null !== k.dataPoints[n - 1].y[0]) && (a.axisY.logarithmic ? (r = e[0] / k.dataPoints[n - 1].y[0], 1 > r && (r = 1 / r), d.minDiff > r && 1 !== r && (d.minDiff = r)) : (r = e[0] - k.dataPoints[n - 1].y[0],
                            0 > r && (r *= -1), d.minDiff > r && 0 !== r && (d.minDiff = r))));
                        if (!(b < m) || p) {
                            if (!p && (p = !0, 0 < n)) {
                                n -= 2;
                                g = h;
                                continue
                            }
                            if (b > t && !q) q = !0;
                            else if (b > t && q) continue;
                            k.dataPoints[n].label && (a.axisX.labels[b] = k.dataPoints[n].label);
                            b < c.viewPortMin && (c.viewPortMin = b);
                            b > c.viewPortMax && (c.viewPortMax = b);
                            if (c.viewPortMin === b && e)
                                for (x = 0; x < e.length; x++)
                                    if (null === e[x] && g < b) { c.viewPortMin = g; break }
                            null === e ? c.viewPortMin === b && g < b && (c.viewPortMin = g) : (f < d.viewPortMin && (d.viewPortMin = f), l > d.viewPortMax && (d.viewPortMax = l))
                        }
                    }
                    k.axisX.valueType =
                        k.xValueType = v ? "dateTime" : "number"
                }
        };
        m.prototype._processSpecificPlotUnit = function(a) {
            if ("waterfall" === a.type && a.dataSeriesIndexes && !(1 > a.dataSeriesIndexes.length))
                for (var d = a.axisY.dataInfo, c = a.axisX.dataInfo, b, e, f = !1, l = 0; l < a.dataSeriesIndexes.length; l++) {
                    var v = this.data[a.dataSeriesIndexes[l]],
                        A = 0,
                        k = !1,
                        n = !1,
                        p = b = 0;
                    if ("normal" === v.axisPlacement || "xySwapped" === v.axisPlacement) var q = a.axisX.sessionVariables.newViewportMinimum ? a.axisX.sessionVariables.newViewportMinimum : this.options.axisX && this.options.axisX.viewportMinimum ?
                        this.options.axisX.viewportMinimum : this.options.axisX && this.options.axisX.minimum ? this.options.axisX.minimum : a.axisX.logarithmic ? 0 : -Infinity,
                        g = a.axisX.sessionVariables.newViewportMaximum ? a.axisX.sessionVariables.newViewportMaximum : this.options.axisX && this.options.axisX.viewportMaximum ? this.options.axisX.viewportMaximum : this.options.axisX && this.options.axisX.maximum ? this.options.axisX.maximum : Infinity;
                    if (v.dataPoints[A].x && v.dataPoints[A].x.getTime || "dateTime" === v.xValueType) f = !0;
                    for (A = 0; A < v.dataPoints.length; A++) "undefined" !==
                        typeof v.dataPoints[A].isCumulativeSum && !0 === v.dataPoints[A].isCumulativeSum ? (v.dataPointEOs[A].cumulativeSumYStartValue = 0, v.dataPointEOs[A].cumulativeSum = 0 === A ? 0 : v.dataPointEOs[A - 1].cumulativeSum, v.dataPoints[A].y = 0 === A ? 0 : v.dataPointEOs[A - 1].cumulativeSum) : "undefined" !== typeof v.dataPoints[A].isIntermediateSum && !0 === v.dataPoints[A].isIntermediateSum ? (v.dataPointEOs[A].cumulativeSumYStartValue = p, v.dataPointEOs[A].cumulativeSum = 0 === A ? 0 : v.dataPointEOs[A - 1].cumulativeSum, v.dataPoints[A].y = 0 === A ? 0 : b,
                            p = 0 === A ? 0 : v.dataPointEOs[A - 1].cumulativeSum, b = 0) : (e = "number" !== typeof v.dataPoints[A].y ? 0 : v.dataPoints[A].y, v.dataPointEOs[A].cumulativeSumYStartValue = 0 === A ? 0 : v.dataPointEOs[A - 1].cumulativeSum, v.dataPointEOs[A].cumulativeSum = 0 === A ? e : v.dataPointEOs[A - 1].cumulativeSum + e, b += e);
                    for (A = 0; A < v.dataPoints.length; A++)
                        if ("undefined" === typeof v.dataPoints[A].x && (v.dataPoints[A].x = A + (a.axisX.logarithmic ? 1 : 0)), v.dataPoints[A].x.getTime ? (f = !0, b = v.dataPoints[A].x.getTime()) : b = v.dataPoints[A].x, e = v.dataPoints[A].y,
                            b < c.min && (c.min = b), b > c.max && (c.max = b), v.dataPointEOs[A].cumulativeSum < d.min && (d.min = v.dataPointEOs[A].cumulativeSum), v.dataPointEOs[A].cumulativeSum > d.max && (d.max = v.dataPointEOs[A].cumulativeSum), 0 < A && (a.axisX.logarithmic ? (p = b / v.dataPoints[A - 1].x, 1 > p && (p = 1 / p), c.minDiff > p && 1 !== p && (c.minDiff = p)) : (p = b - v.dataPoints[A - 1].x, 0 > p && (p *= -1), c.minDiff > p && 0 !== p && (c.minDiff = p)), null !== e && null !== v.dataPoints[A - 1].y && (a.axisY.logarithmic ? (e = v.dataPointEOs[A].cumulativeSum / v.dataPointEOs[A - 1].cumulativeSum, 1 > e &&
                                (e = 1 / e), d.minDiff > e && 1 !== e && (d.minDiff = e)) : (e = v.dataPointEOs[A].cumulativeSum - v.dataPointEOs[A - 1].cumulativeSum, 0 > e && (e *= -1), d.minDiff > e && 0 !== e && (d.minDiff = e)))), !(b < q) || k) {
                            if (!k && (k = !0, 0 < A)) { A -= 2; continue }
                            if (b > g && !n) n = !0;
                            else if (b > g && n) continue;
                            v.dataPoints[A].label && (a.axisX.labels[b] = v.dataPoints[A].label);
                            b < c.viewPortMin && (c.viewPortMin = b);
                            b > c.viewPortMax && (c.viewPortMax = b);
                            0 < A && (v.dataPointEOs[A - 1].cumulativeSum < d.viewPortMin && (d.viewPortMin = v.dataPointEOs[A - 1].cumulativeSum), v.dataPointEOs[A -
                                1].cumulativeSum > d.viewPortMax && (d.viewPortMax = v.dataPointEOs[A - 1].cumulativeSum));
                            v.dataPointEOs[A].cumulativeSum < d.viewPortMin && (d.viewPortMin = v.dataPointEOs[A].cumulativeSum);
                            v.dataPointEOs[A].cumulativeSum > d.viewPortMax && (d.viewPortMax = v.dataPointEOs[A].cumulativeSum)
                        }
                    v.axisX.valueType = v.xValueType = f ? "dateTime" : "number"
                }
        };
        m.prototype.calculateAutoBreaks = function() {
            function a(a, b, c, e) {
                if (e) return c = Math.pow(Math.min(c * a / b, b / a), 0.2), 1 >= c && (c = Math.pow(1 > a ? 1 / a : Math.min(b / a, a), 0.25)), {
                    startValue: a *
                        c,
                    endValue: b / c
                };
                c = 0.2 * Math.min(c - b + a, b - a);
                0 >= c && (c = 0.25 * Math.min(b - a, Math.abs(a)));
                return { startValue: a + c, endValue: b - c }
            }

            function d(a) {
                if (a.dataSeriesIndexes && !(1 > a.dataSeriesIndexes.length)) {
                    var b = a.axisX.scaleBreaks && a.axisX.scaleBreaks.autoCalculate && 1 <= a.axisX.scaleBreaks.maxNumberOfAutoBreaks,
                        c = a.axisY.scaleBreaks && a.axisY.scaleBreaks.autoCalculate && 1 <= a.axisY.scaleBreaks.maxNumberOfAutoBreaks;
                    if (b || c)
                        for (var d = a.axisY.dataInfo, f = a.axisX.dataInfo, g, k = f.min, l = f.max, n = d.min, p = d.max, f = f._dataRanges,
                                d = d._dataRanges, q, v = 0, A = 0; A < a.dataSeriesIndexes.length; A++) {
                            var h = e.data[a.dataSeriesIndexes[A]];
                            if (!(4 > h.dataPoints.length))
                                for (v = 0; v < h.dataPoints.length; v++)
                                    if (b && (q = (l + 1 - k) * Math.max(parseFloat(a.axisX.scaleBreaks.collapsibleThreshold) || 10, 10) / 100, g = h.dataPoints[v].x.getTime ? h.dataPoints[v].x.getTime() : h.dataPoints[v].x, q = Math.floor((g - k) / q), g < f[q].min && (f[q].min = g), g > f[q].max && (f[q].max = g)), c) {
                                        var m = (p + 1 - n) * Math.max(parseFloat(a.axisY.scaleBreaks.collapsibleThreshold) || 10, 10) / 100;
                                        if ((g = "waterfall" ===
                                                a.type ? h.dataPointEOs[v].cumulativeSum : h.dataPoints[v].y) && g.length)
                                            for (var u = 0; u < g.length; u++) q = Math.floor((g[u] - n) / m), g[u] < d[q].min && (d[q].min = g[u]), g[u] > d[q].max && (d[q].max = g[u]);
                                        else s(g) || (q = Math.floor((g - n) / m), g < d[q].min && (d[q].min = g), g > d[q].max && (d[q].max = g))
                                    }
                        }
                }
            }

            function c(a) {
                if (a.dataSeriesIndexes && !(1 > a.dataSeriesIndexes.length) && a.axisX.scaleBreaks && a.axisX.scaleBreaks.autoCalculate && 1 <= a.axisX.scaleBreaks.maxNumberOfAutoBreaks)
                    for (var b = a.axisX.dataInfo, c = b.min, d = b.max, f = b._dataRanges,
                            g, k = 0, l = 0; l < a.dataSeriesIndexes.length; l++) {
                        var n = e.data[a.dataSeriesIndexes[l]];
                        if (!(4 > n.dataPoints.length))
                            for (k = 0; k < n.dataPoints.length; k++) g = (d + 1 - c) * Math.max(parseFloat(a.axisX.scaleBreaks.collapsibleThreshold) || 10, 10) / 100, b = n.dataPoints[k].x.getTime ? n.dataPoints[k].x.getTime() : n.dataPoints[k].x, g = Math.floor((b - c) / g), b < f[g].min && (f[g].min = b), b > f[g].max && (f[g].max = b)
                    }
            }
            for (var b, e = this, f = !1, l = 0; l < this._axes.length; l++)
                if (this._axes[l].scaleBreaks && this._axes[l].scaleBreaks.autoCalculate && 1 <= this._axes[l].scaleBreaks.maxNumberOfAutoBreaks) {
                    f = !0;
                    this._axes[l].dataInfo._dataRanges = [];
                    for (var v = 0; v < 100 / Math.max(parseFloat(this._axes[l].scaleBreaks.collapsibleThreshold) || 10, 10); v++) this._axes[l].dataInfo._dataRanges.push({ min: Infinity, max: -Infinity })
                }
            if (f) {
                for (l = 0; l < this.plotInfo.plotTypes.length; l++)
                    for (f = this.plotInfo.plotTypes[l], v = 0; v < f.plotUnits.length; v++) b = f.plotUnits[v], "line" === b.type || "stepLine" === b.type || "spline" === b.type || "column" === b.type || "area" === b.type || "stepArea" === b.type || "splineArea" === b.type || "bar" === b.type || "bubble" ===
                        b.type || "scatter" === b.type || "candlestick" === b.type || "ohlc" === b.type || "rangeColumn" === b.type || "rangeBar" === b.type || "rangeArea" === b.type || "rangeSplineArea" === b.type || "waterfall" === b.type || "error" === b.type || "boxAndWhisker" === b.type ? d(b) : 0 <= b.type.indexOf("stacked") && c(b);
                for (l = 0; l < this._axes.length; l++)
                    if (this._axes[l].dataInfo._dataRanges) {
                        var A = this._axes[l].dataInfo.min;
                        b = (this._axes[l].dataInfo.max + 1 - A) * Math.max(parseFloat(this._axes[l].scaleBreaks.collapsibleThreshold) || 10, 10) / 100;
                        var k = this._axes[l].dataInfo._dataRanges,
                            n, p, f = [];
                        if (this._axes[l].dataInfo.dataPointYPositiveSums) {
                            var q = this._axes[l].dataInfo.dataPointYPositiveSums;
                            n = k;
                            for (v in q)
                                if (q.hasOwnProperty(v) && !isNaN(v) && (p = q[v], !s(p))) {
                                    var g = Math.floor((p - A) / b);
                                    p < n[g].min && (n[g].min = p);
                                    p > n[g].max && (n[g].max = p)
                                }
                            delete this._axes[l].dataInfo.dataPointYPositiveSums
                        }
                        if (this._axes[l].dataInfo.dataPointYNegativeSums) {
                            q = this._axes[l].dataInfo.dataPointYNegativeSums;
                            n = k;
                            for (v in q) q.hasOwnProperty(v) && !isNaN(v) && (p = -1 * q[v], s(p) || (g = Math.floor((p - A) / b), p < n[g].min &&
                                (n[g].min = p), p > n[g].max && (n[g].max = p)));
                            delete this._axes[l].dataInfo.dataPointYNegativeSums
                        }
                        for (v = 0; v < k.length - 1; v++)
                            if (n = k[v].max, isFinite(n))
                                for (; v < k.length - 1;)
                                    if (A = k[v + 1].min, isFinite(A)) {
                                        p = A - n;
                                        p > b && f.push({ diff: p, start: n, end: A });
                                        break
                                    } else v++;
                        if (this._axes[l].scaleBreaks.customBreaks)
                            for (v = 0; v < this._axes[l].scaleBreaks.customBreaks.length; v++)
                                for (b = 0; b < f.length; b++)
                                    if (this._axes[l].scaleBreaks.customBreaks[v].startValue <= f[b].start && f[b].start <= this._axes[l].scaleBreaks.customBreaks[v].endValue ||
                                        this._axes[l].scaleBreaks.customBreaks[v].startValue <= f[b].start && f[b].start <= this._axes[l].scaleBreaks.customBreaks[v].endValue || f[b].start <= this._axes[l].scaleBreaks.customBreaks[v].startValue && this._axes[l].scaleBreaks.customBreaks[v].startValue <= f[b].end || f[b].start <= this._axes[l].scaleBreaks.customBreaks[v].endValue && this._axes[l].scaleBreaks.customBreaks[v].endValue <= f[b].end) f.splice(b, 1), b--;
                        f.sort(function(a, b) { return b.diff - a.diff });
                        for (v = 0; v < Math.min(f.length, this._axes[l].scaleBreaks.maxNumberOfAutoBreaks); v++) b =
                            a(f[v].start, f[v].end, this._axes[l].logarithmic ? this._axes[l].dataInfo.max / this._axes[l].dataInfo.min : this._axes[l].dataInfo.max - this._axes[l].dataInfo.min, this._axes[l].logarithmic), this._axes[l].scaleBreaks.autoBreaks.push(new T(this, "autoBreaks", b, v, ++this._eventManager.lastObjectId, this._axes[l].scaleBreaks)), this._axes[l].scaleBreaks._appliedBreaks.push(this._axes[l].scaleBreaks.autoBreaks[this._axes[l].scaleBreaks.autoBreaks.length - 1]);
                        this._axes[l].scaleBreaks._appliedBreaks.sort(function(a,
                            b) { return a.startValue - b.startValue })
                    }
            }
        };
        m.prototype.renderCrosshairs = function(a) {
            for (var d = 0; d < this.axisX.length; d++) this.axisX[d] != a && (this.axisX[d].crosshair && this.axisX[d].crosshair.enabled && !this.axisX[d].crosshair._hidden) && this.axisX[d].showCrosshair(this.axisX[d].crosshair._updatedValue);
            for (d = 0; d < this.axisX2.length; d++) this.axisX2[d] != a && (this.axisX2[d].crosshair && this.axisX2[d].crosshair.enabled && !this.axisX2[d].crosshair._hidden) && this.axisX2[d].showCrosshair(this.axisX2[d].crosshair._updatedValue);
            for (d = 0; d < this.axisY.length; d++) this.axisY[d] != a && (this.axisY[d].crosshair && this.axisY[d].crosshair.enabled && !this.axisY[d].crosshair._hidden) && this.axisY[d].showCrosshair(this.axisY[d].crosshair._updatedValue);
            for (d = 0; d < this.axisY2.length; d++) this.axisY2[d] != a && (this.axisY2[d].crosshair && this.axisY2[d].crosshair.enabled && !this.axisY2[d].crosshair._hidden) && this.axisY2[d].showCrosshair(this.axisY2[d].crosshair._updatedValue)
        };
        m.prototype.getDataPointAtXY = function(a, d, c) {
            c = c || !1;
            for (var b = [], e = this._dataInRenderedOrder.length -
                    1; 0 <= e; e--) {
                var f = null;
                (f = this._dataInRenderedOrder[e].getDataPointAtXY(a, d, c)) && b.push(f)
            }
            a = null;
            d = !1;
            for (c = 0; c < b.length; c++)
                if ("line" === b[c].dataSeries.type || "stepLine" === b[c].dataSeries.type || "area" === b[c].dataSeries.type || "stepArea" === b[c].dataSeries.type)
                    if (e = la("markerSize", b[c].dataPoint, b[c].dataSeries) || 8, b[c].distance <= e / 2) { d = !0; break }
            for (c = 0; c < b.length; c++) d && "line" !== b[c].dataSeries.type && "stepLine" !== b[c].dataSeries.type && "area" !== b[c].dataSeries.type && "stepArea" !== b[c].dataSeries.type ||
                (a ? b[c].distance <= a.distance && (a = b[c]) : a = b[c]);
            return a
        };
        m.prototype.getObjectAtXY = function(a, d, c) {
            var b = null;
            if (c = this.getDataPointAtXY(a, d, c || !1)) b = c.dataSeries.dataPointIds[c.dataPointIndex];
            else if (u) b = Ya(a, d, this._eventManager.ghostCtx);
            else
                for (c = 0; c < this.legend.items.length; c++) {
                    var e = this.legend.items[c];
                    a >= e.x1 && (a <= e.x2 && d >= e.y1 && d <= e.y2) && (b = e.id)
                }
            return b
        };
        m.prototype.getAutoFontSize = lb;
        m.prototype.resetOverlayedCanvas = function() { this.overlaidCanvasCtx.clearRect(0, 0, this.width, this.height) };
        m.prototype.clearCanvas = kb;
        m.prototype.attachEvent = function(a) { this._events.push(a) };
        m.prototype._touchEventHandler = function(a) {
            if (a.changedTouches && this.interactivityEnabled) {
                var d = [],
                    c = a.changedTouches,
                    b = c ? c[0] : a,
                    e = null;
                switch (a.type) {
                    case "touchstart":
                    case "MSPointerDown":
                        d = ["mousemove", "mousedown"];
                        this._lastTouchData = Na(b);
                        this._lastTouchData.time = new Date;
                        break;
                    case "touchmove":
                    case "MSPointerMove":
                        d = ["mousemove"];
                        break;
                    case "touchend":
                    case "MSPointerUp":
                        var f = this._lastTouchData && this._lastTouchData.time ?
                            new Date - this._lastTouchData.time : 0,
                            d = "touchstart" === this._lastTouchEventType || "MSPointerDown" === this._lastTouchEventType || 300 > f ? ["mouseup", "click"] : ["mouseup"];
                        break;
                    default:
                        return
                }
                if (!(c && 1 < c.length)) {
                    e = Na(b);
                    e.time = new Date;
                    try {
                        var l = e.y - this._lastTouchData.y,
                            f = e.time - this._lastTouchData.time;
                        if (1 < Math.abs(l) && this._lastTouchData.scroll || 5 < Math.abs(l) && 250 > f) this._lastTouchData.scroll = !0
                    } catch (v) {}
                    this._lastTouchEventType = a.type;
                    if (this._lastTouchData.scroll && this.zoomEnabled) this.isDrag && this.resetOverlayedCanvas(),
                        this.isDrag = !1;
                    else
                        for (c = 0; c < d.length; c++)
                            if (e = d[c], l = document.createEvent("MouseEvent"), l.initMouseEvent(e, !0, !0, window, 1, b.screenX, b.screenY, b.clientX, b.clientY, !1, !1, !1, !1, 0, null), b.target.dispatchEvent(l), !s(this._lastTouchData.scroll) && !this._lastTouchData.scroll || !this._lastTouchData.scroll && 250 < f || "click" === e) a.preventManipulation && a.preventManipulation(), a.preventDefault && a.cancelable && a.preventDefault()
                }
            }
        };
        m.prototype._dispatchRangeEvent = function(a, d) {
            var c = { chart: this };
            c.type = a;
            c.trigger =
                d;
            var b = [];
            this.axisX && 0 < this.axisX.length && b.push("axisX");
            this.axisX2 && 0 < this.axisX2.length && b.push("axisX2");
            this.axisY && 0 < this.axisY.length && b.push("axisY");
            this.axisY2 && 0 < this.axisY2.length && b.push("axisY2");
            for (var e = 0; e < b.length; e++)
                if (s(c[b[e]]) && (c[b[e]] = []), "axisY" === b[e])
                    for (var f = 0; f < this.axisY.length; f++) c[b[e]].push({ viewportMinimum: this[b[e]][f].sessionVariables.newViewportMinimum, viewportMaximum: this[b[e]][f].sessionVariables.newViewportMaximum });
                else if ("axisY2" === b[e])
                for (f = 0; f <
                    this.axisY2.length; f++) c[b[e]].push({ viewportMinimum: this[b[e]][f].sessionVariables.newViewportMinimum, viewportMaximum: this[b[e]][f].sessionVariables.newViewportMaximum });
            else if ("axisX" === b[e])
                for (f = 0; f < this.axisX.length; f++) c[b[e]].push({ viewportMinimum: this[b[e]][f].sessionVariables.newViewportMinimum, viewportMaximum: this[b[e]][f].sessionVariables.newViewportMaximum });
            else if ("axisX2" === b[e])
                for (f = 0; f < this.axisX2.length; f++) c[b[e]].push({
                    viewportMinimum: this[b[e]][f].sessionVariables.newViewportMinimum,
                    viewportMaximum: this[b[e]][f].sessionVariables.newViewportMaximum
                });
            this.dispatchEvent(a, c, this)
        };
        m.prototype._mouseEventHandler = function(a) {
            "undefined" === typeof a.target && a.srcElement && (a.target = a.srcElement);
            var d = Na(a),
                c = a.type,
                b, e;
            a.which ? e = 3 == a.which : a.button && (e = 2 == a.button);
            m.capturedEventParam && (b = m.capturedEventParam, "mouseup" === c && (m.capturedEventParam = null, b.chart.overlaidCanvas.releaseCapture ? b.chart.overlaidCanvas.releaseCapture() : document.documentElement.removeEventListener("mouseup",
                b.chart._mouseEventHandler, !1)), b.hasOwnProperty(c) && ("mouseup" !== c || b.chart.overlaidCanvas.releaseCapture ? a.target !== b.chart.overlaidCanvas && u || b[c].call(b.context, d.x, d.y) : a.target !== b.chart.overlaidCanvas && (b.chart.isDrag = !1)));
            if (this.interactivityEnabled)
                if (this._ignoreNextEvent) this._ignoreNextEvent = !1;
                else if (a.preventManipulation && a.preventManipulation(), a.preventDefault && a.preventDefault(), Ga && window.console && (window.console.log(c + " --\x3e x: " + d.x + "; y:" + d.y), e && window.console.log(a.which),
                    "mouseup" === c && window.console.log("mouseup")), !e) {
                if (!m.capturedEventParam && this._events) {
                    for (var f = 0; f < this._events.length; f++)
                        if (this._events[f].hasOwnProperty(c))
                            if (b = this._events[f], e = b.bounds, d.x >= e.x1 && d.x <= e.x2 && d.y >= e.y1 && d.y <= e.y2) {
                                b[c].call(b.context, d.x, d.y);
                                "mousedown" === c && !0 === b.capture ? (m.capturedEventParam = b, this.overlaidCanvas.setCapture ? this.overlaidCanvas.setCapture() : document.documentElement.addEventListener("mouseup", this._mouseEventHandler, !1)) : "mouseup" === c && (b.chart.overlaidCanvas.releaseCapture ?
                                    b.chart.overlaidCanvas.releaseCapture() : document.documentElement.removeEventListener("mouseup", this._mouseEventHandler, !1));
                                break
                            } else b = null;
                    a.target.style.cursor = b && b.cursor ? b.cursor : this._defaultCursor
                }
                c = this.plotArea;
                if (d.x < c.x1 || d.x > c.x2 || d.y < c.y1 || d.y > c.y2)
                    if (this.toolTip && this.toolTip.enabled) {
                        this.toolTip.hide();
                        this.toolTip.dispatchEvent("hidden", { chart: this, toolTip: this.toolTip }, this.toolTip);
                        for (f = 0; f < this.axisX.length; f++) this.axisX[f].crosshair && this.axisX[f].crosshair.enabled && this.axisX[f].crosshair.dispatchEvent("hidden", { chart: this, axis: this.axisX[f].options }, this.axisX[f].crosshair);
                        for (f = 0; f < this.axisX2.length; f++) this.axisX2[f].crosshair && this.axisX2[f].crosshair.enabled && this.axisX2[f].crosshair.dispatchEvent("hidden", { chart: this, axis: this.axisX2[f].options }, this.axisX2[f].crosshair);
                        for (f = 0; f < this.axisY.length; f++) this.axisY[f].crosshair && this.axisY[f].crosshair.enabled && this.axisY[f].crosshair.dispatchEvent("hidden", { chart: this, axis: this.axisY[f].options }, this.axisY[f].crosshair);
                        for (f = 0; f < this.axisY2.length; f++) this.axisY2[f].crosshair &&
                            this.axisY2[f].crosshair.enabled && this.axisY2[f].crosshair.dispatchEvent("hidden", { chart: this, axis: this.axisY2[f].options }, this.axisY2[f].crosshair)
                    } else this.resetOverlayedCanvas();
                this.isDrag && this.zoomEnabled || !this._eventManager || this._eventManager.mouseEventHandler(a)
            }
        };
        m.prototype._plotAreaMouseDown = function(a, d) {
            this.isDrag = !0;
            this.dragStartPoint = { x: a, y: d }
        };
        m.prototype._plotAreaMouseUp = function(a, d) {
            if (("normal" === this.plotInfo.axisPlacement || "xySwapped" === this.plotInfo.axisPlacement) && this.isDrag) {
                var c =
                    d - this.dragStartPoint.y,
                    b = a - this.dragStartPoint.x,
                    e = 0 <= this.zoomType.indexOf("x"),
                    f = 0 <= this.zoomType.indexOf("y"),
                    l = !1;
                this.resetOverlayedCanvas();
                if ("xySwapped" === this.plotInfo.axisPlacement) var v = f,
                    f = e,
                    e = v;
                if (this.panEnabled || this.zoomEnabled) {
                    if (this.panEnabled)
                        for (e = f = 0; e < this._axes.length; e++) c = this._axes[e], c.logarithmic ? c.viewportMinimum < c.minimum ? (f = c.minimum / c.viewportMinimum, c.sessionVariables.newViewportMinimum = c.viewportMinimum * f, c.sessionVariables.newViewportMaximum = c.viewportMaximum *
                            f, l = !0) : c.viewportMaximum > c.maximum && (f = c.viewportMaximum / c.maximum, c.sessionVariables.newViewportMinimum = c.viewportMinimum / f, c.sessionVariables.newViewportMaximum = c.viewportMaximum / f, l = !0) : c.viewportMinimum < c.minimum ? (f = c.minimum - c.viewportMinimum, c.sessionVariables.newViewportMinimum = c.viewportMinimum + f, c.sessionVariables.newViewportMaximum = c.viewportMaximum + f, l = !0) : c.viewportMaximum > c.maximum && (f = c.viewportMaximum - c.maximum, c.sessionVariables.newViewportMinimum = c.viewportMinimum - f, c.sessionVariables.newViewportMaximum =
                            c.viewportMaximum - f, l = !0);
                    else if ((!e || 2 < Math.abs(b)) && (!f || 2 < Math.abs(c)) && this.zoomEnabled) {
                        if (!this.dragStartPoint) return;
                        c = e ? this.dragStartPoint.x : this.plotArea.x1;
                        b = f ? this.dragStartPoint.y : this.plotArea.y1;
                        e = e ? a : this.plotArea.x2;
                        f = f ? d : this.plotArea.y2;
                        2 < Math.abs(c - e) && 2 < Math.abs(b - f) && this._zoomPanToSelectedRegion(c, b, e, f) && (l = !0)
                    }
                    l && (this._ignoreNextEvent = !0, this._dispatchRangeEvent("rangeChanging", "zoom"), this.stockChart && (this.stockChart.navigator && this.stockChart.navigator.enabled) && (this.stockChart._rangeEventParameter ||
                            (this.stockChart._rangeEventParameter = { stockChart: this.stockChart, source: "chart", index: this.stockChart.charts.indexOf(this), minimum: this.stockChart.sessionVariables._axisXMin, maximum: this.stockChart.sessionVariables._axisXMax }), this.stockChart._rangeEventParameter.type = "rangeChanging", this.stockChart.dispatchEvent("rangeChanging", this.stockChart._rangeEventParameter, this.stockChart)), this.render(), this._dispatchRangeEvent("rangeChanged", "zoom"), this.stockChart && (this.stockChart.navigator && this.stockChart.navigator.enabled) &&
                        (this.stockChart._rangeEventParameter.type = "rangeChanged", this.stockChart.dispatchEvent("rangeChanged", this.stockChart._rangeEventParameter, this.stockChart)), l && (this.zoomEnabled && "none" === this._zoomButton.style.display) && (Ka(this._zoomButton, this._resetButton), va(this, this._zoomButton, "pan"), va(this, this._resetButton, "reset")))
                }
            }
            this.isDrag = !1;
            if ("none" !== this.plotInfo.axisPlacement) {
                this.resetOverlayedCanvas();
                if (this.axisX && 0 < this.axisX.length)
                    for (l = 0; l < this.axisX.length; l++) this.axisX[l].crosshair &&
                        this.axisX[l].crosshair.enabled && this.axisX[l].renderCrosshair(a, d);
                if (this.axisX2 && 0 < this.axisX2.length)
                    for (l = 0; l < this.axisX2.length; l++) this.axisX2[l].crosshair && this.axisX2[l].crosshair.enabled && this.axisX2[l].renderCrosshair(a, d);
                if (this.axisY && 0 < this.axisY.length)
                    for (l = 0; l < this.axisY.length; l++) this.axisY[l].crosshair && this.axisY[l].crosshair.enabled && this.axisY[l].renderCrosshair(a, d);
                if (this.axisY2 && 0 < this.axisY2.length)
                    for (l = 0; l < this.axisY2.length; l++) this.axisY2[l].crosshair && this.axisY2[l].crosshair.enabled &&
                        this.axisY2[l].renderCrosshair(a, d)
            }
        };
        m.prototype._plotAreaMouseMove = function(a, d) {
            if (this.isDrag && "none" !== this.plotInfo.axisPlacement) {
                var c = 0,
                    b = 0,
                    e = c = null,
                    e = 0 <= this.zoomType.indexOf("x"),
                    f = 0 <= this.zoomType.indexOf("y"),
                    l = this;
                "xySwapped" === this.plotInfo.axisPlacement && (c = f, f = e, e = c);
                c = this.dragStartPoint.x - a;
                b = this.dragStartPoint.y - d;
                2 < Math.abs(c) && 8 > Math.abs(c) && (this.panEnabled || this.zoomEnabled) ? (this.toolTip.hide(), this.toolTip.dispatchEvent("hidden", { chart: this, toolTip: this.toolTip }, this.toolTip)) :
                    this.panEnabled || this.zoomEnabled || this.toolTip.mouseMoveHandler(a, d);
                if ((!e || 2 < Math.abs(c) || !f || 2 < Math.abs(b)) && (this.panEnabled || this.zoomEnabled))
                    if (this.panEnabled) e = { x1: e ? this.plotArea.x1 + c : this.plotArea.x1, y1: f ? this.plotArea.y1 + b : this.plotArea.y1, x2: e ? this.plotArea.x2 + c : this.plotArea.x2, y2: f ? this.plotArea.y2 + b : this.plotArea.y2 }, clearTimeout(l._panTimerId), l._panTimerId = setTimeout(function(b, c, e, f) {
                        return function() {
                            l._zoomPanToSelectedRegion(b, c, e, f, !0) && (l._dispatchRangeEvent("rangeChanging",
                                    "pan"), l.stockChart && (l.stockChart.navigator && l.stockChart.navigator.enabled) && (l.stockChart._rangeEventParameter.type = "rangeChanging", l.stockChart.dispatchEvent("rangeChanging", l.stockChart._rangeEventParameter, l.stockChart)), l.render(), l._dispatchRangeEvent("rangeChanged", "pan"), l.stockChart && (l.stockChart.navigator && l.stockChart.navigator.enabled) && (l.stockChart._rangeEventParameter.type = "rangeChanged", l.stockChart.dispatchEvent("rangeChanged", l.stockChart._rangeEventParameter, l.stockChart)), l.dragStartPoint.x =
                                a, l.dragStartPoint.y = d)
                        }
                    }(e.x1, e.y1, e.x2, e.y2), 0);
                    else if (this.zoomEnabled) {
                    this.resetOverlayedCanvas();
                    c = this.overlaidCanvasCtx.globalAlpha;
                    this.overlaidCanvasCtx.fillStyle = "#A89896";
                    var b = e ? this.dragStartPoint.x : this.plotArea.x1,
                        v = f ? this.dragStartPoint.y : this.plotArea.y1,
                        A = e ? a - this.dragStartPoint.x : this.plotArea.x2 - this.plotArea.x1,
                        k = f ? d - this.dragStartPoint.y : this.plotArea.y2 - this.plotArea.y1;
                    this.validateRegion(b, v, e ? a : this.plotArea.x2 - this.plotArea.x1, f ? d : this.plotArea.y2 - this.plotArea.y1, "xy" !==
                        this.zoomType).isValid && (this.resetOverlayedCanvas(), this.overlaidCanvasCtx.fillStyle = "#99B2B5");
                    this.overlaidCanvasCtx.globalAlpha = 0.7;
                    this.overlaidCanvasCtx.fillRect(b, v, A, k);
                    this.overlaidCanvasCtx.globalAlpha = c
                }
            } else if (this.toolTip.mouseMoveHandler(a, d), "none" !== this.plotInfo.axisPlacement) {
                if (this.axisX && 0 < this.axisX.length)
                    for (e = 0; e < this.axisX.length; e++) this.axisX[e].crosshair && this.axisX[e].crosshair.enabled && this.axisX[e].renderCrosshair(a, d);
                if (this.axisX2 && 0 < this.axisX2.length)
                    for (e = 0; e <
                        this.axisX2.length; e++) this.axisX2[e].crosshair && this.axisX2[e].crosshair.enabled && this.axisX2[e].renderCrosshair(a, d);
                if (this.axisY && 0 < this.axisY.length)
                    for (e = 0; e < this.axisY.length; e++) this.axisY[e].crosshair && this.axisY[e].crosshair.enabled && this.axisY[e].renderCrosshair(a, d);
                if (this.axisY2 && 0 < this.axisY2.length)
                    for (e = 0; e < this.axisY2.length; e++) this.axisY2[e].crosshair && this.axisY2[e].crosshair.enabled && this.axisY2[e].renderCrosshair(a, d)
            }
        };
        m.prototype._zoomPanToSelectedRegion = function(a, d, c, b,
            e) {
            a = this.validateRegion(a, d, c, b, e);
            d = a.axesWithValidRange;
            c = a.axesRanges;
            if (a.isValid)
                for (b = 0; b < d.length; b++) e = c[b], d[b].setViewPortRange(e.val1, e.val2), this.syncCharts && "y" != this.zoomType && this.syncCharts(e.val1, e.val2), this.stockChart && (this.stockChart._rangeEventParameter = { stockChart: this.stockChart, source: "chart", index: this.stockChart.charts.indexOf(this), minimum: e.val1, maximum: e.val2 });
            return a.isValid
        };
        m.prototype.validateRegion = function(a, d, c, b, e) {
            e = e || !1;
            for (var f = 0 <= this.zoomType.indexOf("x"),
                    l = 0 <= this.zoomType.indexOf("y"), v = !1, A = [], k = [], n = [], p = 0; p < this._axes.length; p++)("axisX" === this._axes[p].type && f || "axisY" === this._axes[p].type && l) && k.push(this._axes[p]);
            for (l = 0; l < k.length; l++) {
                var p = k[l],
                    f = !1,
                    q = p.convertPixelToValue({ x: a, y: d }),
                    g = p.convertPixelToValue({ x: c, y: b });
                if (q > g) var r = g,
                    g = q,
                    q = r;
                if (p.scaleBreaks)
                    for (r = 0; !f && r < p.scaleBreaks._appliedBreaks.length; r++) f = p.scaleBreaks._appliedBreaks[r].startValue <= q && p.scaleBreaks._appliedBreaks[r].endValue >= g;
                if (isFinite(p.dataInfo.minDiff))
                    if (r =
                        p.getApparentDifference(q, g, null, !0), !(f || !(this.panEnabled && p.scaleBreaks && p.scaleBreaks._appliedBreaks.length) && (p.logarithmic && r < Math.pow(p.dataInfo.minDiff, 3) || !p.logarithmic && r < 3 * Math.abs(p.dataInfo.minDiff)) || q < p.minimum || g > p.maximum)) A.push(p), n.push({ val1: q, val2: g }), v = !0;
                    else if (!e) { v = !1; break }
            }
            return { isValid: v, axesWithValidRange: A, axesRanges: n }
        };
        m.prototype.preparePlotArea = function() {
            var a = this.plotArea;
            !u && (0 < a.x1 || 0 < a.y1) && a.ctx.translate(a.x1, a.y1);
            if ((this.axisX[0] || this.axisX2[0]) &&
                (this.axisY[0] || this.axisY2[0])) {
                var d = this.axisX[0] ? this.axisX[0].lineCoordinates : this.axisX2[0].lineCoordinates;
                if (this.axisY && 0 < this.axisY.length && this.axisY[0]) {
                    var c = this.axisY[0];
                    a.x1 = d.x1 < d.x2 ? d.x1 : c.lineCoordinates.x1;
                    a.y1 = d.y1 < c.lineCoordinates.y1 ? d.y1 : c.lineCoordinates.y1;
                    a.x2 = d.x2 > c.lineCoordinates.x2 ? d.x2 : c.lineCoordinates.x2;
                    a.y2 = d.y2 > d.y1 ? d.y2 : c.lineCoordinates.y2;
                    a.width = a.x2 - a.x1;
                    a.height = a.y2 - a.y1
                }
                this.axisY2 && 0 < this.axisY2.length && this.axisY2[0] && (c = this.axisY2[0], a.x1 = d.x1 < d.x2 ?
                    d.x1 : c.lineCoordinates.x1, a.y1 = d.y1 < c.lineCoordinates.y1 ? d.y1 : c.lineCoordinates.y1, a.x2 = d.x2 > c.lineCoordinates.x2 ? d.x2 : c.lineCoordinates.x2, a.y2 = d.y2 > d.y1 ? d.y2 : c.lineCoordinates.y2, a.width = a.x2 - a.x1, a.height = a.y2 - a.y1)
            } else d = this.layoutManager.getFreeSpace(), a.x1 = d.x1, a.x2 = d.x2, a.y1 = d.y1, a.y2 = d.y2, a.width = d.width, a.height = d.height;
            u || (a.canvas.width = a.width, a.canvas.height = a.height, a.canvas.style.left = a.x1 + "px", a.canvas.style.top = a.y1 + "px", (0 < a.x1 || 0 < a.y1) && a.ctx.translate(-a.x1, -a.y1));
            a.layoutManager =
                new Da(a.x1, a.y1, a.x2, a.y2, 2)
        };
        m.prototype.renderIndexLabels = function(a) {
            var d = a || this.plotArea.ctx,
                c = this.plotArea,
                b = 0,
                e = 0,
                f = 0,
                l = f = e = 0,
                v = 0,
                A = b = 0,
                k = 0;
            for (a = 0; a < this._indexLabels.length; a++) {
                var n = this._indexLabels[a],
                    p = n.chartType.toLowerCase(),
                    q, g, l = la("indexLabelFontColor", n.dataPoint, n.dataSeries),
                    r = la("indexLabelFontSize", n.dataPoint, n.dataSeries),
                    v = la("indexLabelFontFamily", n.dataPoint, n.dataSeries),
                    A = la("indexLabelFontStyle", n.dataPoint, n.dataSeries),
                    k = la("indexLabelFontWeight", n.dataPoint,
                        n.dataSeries),
                    h = la("indexLabelBackgroundColor", n.dataPoint, n.dataSeries);
                q = la("indexLabelMaxWidth", n.dataPoint, n.dataSeries);
                g = la("indexLabelWrap", n.dataPoint, n.dataSeries);
                var m = la("indexLabelLineDashType", n.dataPoint, n.dataSeries),
                    t = la("indexLabelLineColor", n.dataPoint, n.dataSeries),
                    x = s(n.dataPoint.indexLabelLineThickness) ? s(n.dataSeries.options.indexLabelLineThickness) ? 0 : n.dataSeries.options.indexLabelLineThickness : n.dataPoint.indexLabelLineThickness,
                    b = 0 < x ? Math.min(10, ("normal" === this.plotInfo.axisPlacement ?
                        this.plotArea.height : this.plotArea.width) << 0) : 0,
                    E = { percent: null, total: null },
                    B = null;
                if (0 <= n.dataSeries.type.indexOf("stacked") || "pie" === n.dataSeries.type || "doughnut" === n.dataSeries.type) E = this.getPercentAndTotal(n.dataSeries, n.dataPoint);
                if (n.dataSeries.indexLabelFormatter || n.dataPoint.indexLabelFormatter) B = { chart: this, dataSeries: n.dataSeries, dataPoint: n.dataPoint, index: n.indexKeyword, total: E.total, percent: E.percent };
                var C = n.dataPoint.indexLabelFormatter ? n.dataPoint.indexLabelFormatter(B) : n.dataPoint.indexLabel ?
                    this.replaceKeywordsWithValue(n.dataPoint.indexLabel, n.dataPoint, n.dataSeries, null, n.indexKeyword) : n.dataSeries.indexLabelFormatter ? n.dataSeries.indexLabelFormatter(B) : n.dataSeries.indexLabel ? this.replaceKeywordsWithValue(n.dataSeries.indexLabel, n.dataPoint, n.dataSeries, null, n.indexKeyword) : null;
                if (null !== C && "" !== C) {
                    var E = la("indexLabelPlacement", n.dataPoint, n.dataSeries),
                        B = la("indexLabelOrientation", n.dataPoint, n.dataSeries),
                        z = la("indexLabelTextAlign", n.dataPoint, n.dataSeries),
                        w = n.direction,
                        e = n.dataSeries.axisX,
                        f = n.dataSeries.axisY,
                        y = !1,
                        h = new ia(d, { x: 0, y: 0, maxWidth: q ? q : 0.5 * this.width, maxHeight: g ? 5 * r : 1.5 * r, angle: "horizontal" === B ? 0 : -90, text: C, padding: 0, backgroundColor: h, textAlign: z, fontSize: r, fontFamily: v, fontWeight: k, fontColor: l, fontStyle: A, textBaseline: "middle" });
                    h.measureText();
                    n.dataSeries.indexLabelMaxWidth = h.maxWidth;
                    if ("stackedarea100" === p) { if (n.point.x < c.x1 || n.point.x > c.x2 || n.point.y < c.y1 - 1 || n.point.y > c.y2 + 1) continue } else if ("rangearea" === p || "rangesplinearea" === p) {
                        if (n.dataPoint.x < e.viewportMinimum ||
                            n.dataPoint.x > e.viewportMaximum || Math.max.apply(null, n.dataPoint.y) < f.viewportMinimum || Math.min.apply(null, n.dataPoint.y) > f.viewportMaximum) continue
                    } else if (0 <= p.indexOf("line") || 0 <= p.indexOf("area") || 0 <= p.indexOf("bubble") || 0 <= p.indexOf("scatter")) { if (n.dataPoint.x < e.viewportMinimum || n.dataPoint.x > e.viewportMaximum || n.dataPoint.y < f.viewportMinimum || n.dataPoint.y > f.viewportMaximum) continue } else if (0 <= p.indexOf("column") || "waterfall" === p || "error" === p && !n.axisSwapped) {
                        if (n.dataPoint.x < e.viewportMinimum ||
                            n.dataPoint.x > e.viewportMaximum || n.bounds.y1 > c.y2 || n.bounds.y2 < c.y1) continue
                    } else if (0 <= p.indexOf("bar") || "error" === p) { if (n.dataPoint.x < e.viewportMinimum || n.dataPoint.x > e.viewportMaximum || n.bounds.x1 > c.x2 || n.bounds.x2 < c.x1) continue } else if ("candlestick" === p || "ohlc" === p) { if (n.dataPoint.x < e.viewportMinimum || n.dataPoint.x > e.viewportMaximum || Math.max.apply(null, n.dataPoint.y) < f.viewportMinimum || Math.min.apply(null, n.dataPoint.y) > f.viewportMaximum) continue } else if (n.dataPoint.x < e.viewportMinimum || n.dataPoint.x >
                        e.viewportMaximum) continue;
                    l = v = 2;
                    "horizontal" === B ? (A = h.width, k = h.height) : (k = h.width, A = h.height);
                    if ("normal" === this.plotInfo.axisPlacement) {
                        if (0 <= p.indexOf("line") || 0 <= p.indexOf("area")) E = "auto", v = 4;
                        else if (0 <= p.indexOf("stacked")) "auto" === E && (E = "inside");
                        else if ("bubble" === p || "scatter" === p) E = "inside";
                        q = n.point.x - ("horizontal" === B ? A / 2 : A / 2 - r / 2);
                        "inside" !== E ? (e = c.y1, f = c.y2, 0 < w ? (g = n.point.y + ("horizontal" === B ? r / 2 : 0) - k - v - b, g < e && (g = "auto" === E ? Math.max(n.point.y, e) + r / 2 + v : e + r / 2 + v, y = g + k > n.point.y)) : (g = n.point.y +
                            r / 2 + v + b, g > f - k && (g = "auto" === E ? Math.min(n.point.y, f) + r / 2 - k - v : f + r / 2 - k, y = g < n.point.y))) : (e = Math.max(n.bounds.y1, c.y1), f = Math.min(n.bounds.y2, c.y2 - k + r / 2), b = 0 <= p.indexOf("range") || "error" === p ? 0 < w ? Math.max(n.bounds.y1, c.y1) + r / 2 + v : Math.min(n.bounds.y2, c.y2) + r / 2 - k + v : (Math.max(n.bounds.y1, c.y1) + Math.min(n.bounds.y2, c.y2)) / 2 - k / 2 + r / 2 + ("horizontal" === B ? v : 0), 0 < w ? (g = Math.max(n.point.y, b), g < e && ("bubble" === p || "scatter" === p) && (g = Math.max(n.point.y - k - v, c.y1 + v))) : (g = Math.min(n.point.y, b), g > f - k - v && ("bubble" === p || "scatter" ===
                            p) && (g = Math.min(n.point.y + v, c.y2 - k - v))), g = Math.min(g, f))
                    } else 0 <= p.indexOf("line") || 0 <= p.indexOf("area") || 0 <= p.indexOf("scatter") ? (E = "auto", l = 4) : 0 <= p.indexOf("stacked") ? "auto" === E && (E = "inside") : "bubble" === p && (E = "inside"), g = n.point.y + r / 2 - k / 2 + v, "inside" !== E ? (e = c.x1, f = c.x2, 0 > w ? (q = n.point.x - ("horizontal" === B ? A : A - r / 2) - l - b, q < e && (q = "auto" === E ? Math.max(n.point.x, e) + l : e + l, y = q + A > n.point.x)) : (q = n.point.x + ("horizontal" === B ? 0 : r / 2) + l + b, q > f - A - l - b && (q = "auto" === E ? Math.min(n.point.x, f) - ("horizontal" === B ? A : A / 2) - l : f -
                        A - l, y = q < n.point.x))) : (e = Math.max(n.bounds.x1, c.x1), Math.min(n.bounds.x2, c.x2), b = 0 <= p.indexOf("range") || "error" === p ? 0 > w ? Math.max(n.bounds.x1, c.x1) + r / 2 + l : Math.min(n.bounds.x2, c.x2) - A / 2 - l + ("horizontal" === B ? 0 : r / 2) : (Math.max(n.bounds.x1, c.x1) + Math.min(n.bounds.x2, c.x2)) / 2 + ("horizontal" === B ? 0 : r / 2), q = 0 > w ? Math.max(n.point.x, b) - ("horizontal" === B ? A / 2 : 0) : Math.min(n.point.x, b) - A / 2, q = Math.max(q, e));
                    "vertical" === B && (g += k - r / 2);
                    h.x = q;
                    h.y = g;
                    h.render(!0);
                    x && ("inside" !== E && (0 > p.indexOf("bar") && ("error" !== p || !n.axisSwapped) &&
                        n.point.x > c.x1 && n.point.x < c.x2 || !y) && (0 > p.indexOf("column") && ("error" !== p || n.axisSwapped) && n.point.y > c.y1 && n.point.y < c.y2 || !y)) && (d.lineWidth = x, d.strokeStyle = t ? t : "gray", d.setLineDash && d.setLineDash(N(m, x)), d.beginPath(), d.moveTo(n.point.x, n.point.y), 0 <= p.indexOf("bar") || "error" === p && n.axisSwapped ? d.lineTo(q + (0 < n.direction ? -l : A + l) + ("vertical" === B ? -r / 2 : 0), g + ("vertical" === B ? -k / 2 : k / 2 - r / 2) - v) : 0 <= p.indexOf("column") || "error" === p && !n.axisSwapped ? d.lineTo(q + A / 2 - ("horizontal" === B ? 0 : r / 2), g + ("vertical" === B ?
                        (g - k < n.point.y ? 0 : -k) + v : (g - r / 2 < n.point.y ? k : 0) - r / 2)) : d.lineTo(q + A / 2 - ("horizontal" === B ? 0 : r / 2), g + ("vertical" === B ? g - k < n.point.y ? 0 : -k : (g - r / 2 < n.point.y ? k : 0) - r / 2)), d.stroke())
                }
            }
            d = { source: d, dest: this.plotArea.ctx, animationCallback: K.fadeInAnimation, easingFunction: K.easing.easeInQuad, animationBase: 0, startTimePercent: 0.7 };
            for (a = 0; a < this._indexLabels.length; a++) n = this._indexLabels[a], h = la("indexLabelBackgroundColor", n.dataPoint, n.dataSeries), n.dataSeries.indexLabelBackgroundColor = s(h) ? u ? "transparent" : null : h;
            return d
        };
        m.prototype.renderLine = function(a) {
            var d = a.targetCanvasCtx || this.plotArea.ctx,
                c = u ? this._preRenderCtx : d;
            if (!(0 >= a.dataSeriesIndexes.length)) {
                var b = this._eventManager.ghostCtx;
                c.save();
                var e = this.plotArea;
                c.beginPath();
                c.rect(e.x1, e.y1, e.width, e.height);
                c.clip();
                for (var f = [], l, v = 0; v < a.dataSeriesIndexes.length; v++) {
                    var A = a.dataSeriesIndexes[v],
                        k = this.data[A];
                    c.lineWidth = k.lineThickness;
                    var n = k.dataPoints,
                        p = "solid";
                    if (c.setLineDash) {
                        var q = N(k.nullDataLineDashType, k.lineThickness),
                            p = k.lineDashType,
                            g = N(p, k.lineThickness);
                        c.setLineDash(g)
                    }
                    var r = k.id;
                    this._eventManager.objectMap[r] = { objectType: "dataSeries", dataSeriesIndex: A };
                    r = P(r);
                    b.strokeStyle = r;
                    b.lineWidth = 0 < k.lineThickness ? Math.max(k.lineThickness, 4) : 0;
                    var r = k._colorSet,
                        h = r = k.lineColor = k.options.lineColor ? k.options.lineColor : r[0];
                    c.strokeStyle = r;
                    var m = !0,
                        t = 0,
                        x, s;
                    c.beginPath();
                    if (0 < n.length) {
                        for (var B = !1, t = 0; t < n.length; t++)
                            if (x = n[t].x.getTime ? n[t].x.getTime() : n[t].x, !(x < a.axisX.dataInfo.viewPortMin || x > a.axisX.dataInfo.viewPortMax && (!k.connectNullData ||
                                    !B)))
                                if ("number" !== typeof n[t].y) 0 < t && !(k.connectNullData || B || m) && (c.stroke(), u && b.stroke()), B = !0;
                                else {
                                    x = a.axisX.convertValueToPixel(x);
                                    s = a.axisY.convertValueToPixel(n[t].y);
                                    var C = k.dataPointIds[t];
                                    this._eventManager.objectMap[C] = { id: C, objectType: "dataPoint", dataSeriesIndex: A, dataPointIndex: t, x1: x, y1: s };
                                    m || B ? (!m && k.connectNullData ? (c.setLineDash && (k.options.nullDataLineDashType || p === k.lineDashType && k.lineDashType !== k.nullDataLineDashType) && (c.stroke(), c.beginPath(), c.moveTo(l.x, l.y), p = k.nullDataLineDashType,
                                        c.setLineDash(q)), c.lineTo(x, s), u && b.lineTo(x, s)) : (c.beginPath(), c.moveTo(x, s), u && (b.beginPath(), b.moveTo(x, s))), B = m = !1) : (c.lineTo(x, s), u && b.lineTo(x, s), 0 == t % 500 && (c.stroke(), c.beginPath(), c.moveTo(x, s), u && (b.stroke(), b.beginPath(), b.moveTo(x, s))));
                                    l = { x: x, y: s };
                                    t < n.length - 1 && (h !== (n[t].lineColor || r) || p !== (n[t].lineDashType || k.lineDashType)) && (c.stroke(), c.beginPath(), c.moveTo(x, s), h = n[t].lineColor || r, c.strokeStyle = h, c.setLineDash && (n[t].lineDashType ? (p = n[t].lineDashType, c.setLineDash(N(p, k.lineThickness))) :
                                        (p = k.lineDashType, c.setLineDash(g))));
                                    if (0 < n[t].markerSize || 0 < k.markerSize) {
                                        var z = k.getMarkerProperties(t, x, s, c);
                                        f.push(z);
                                        C = P(C);
                                        u && f.push({ x: x, y: s, ctx: b, type: z.type, size: z.size, color: C, borderColor: C, borderThickness: z.borderThickness })
                                    }(n[t].indexLabel || k.indexLabel || n[t].indexLabelFormatter || k.indexLabelFormatter) && this._indexLabels.push({ chartType: "line", dataPoint: n[t], dataSeries: k, point: { x: x, y: s }, direction: 0 > n[t].y === a.axisY.reversed ? 1 : -1, color: r })
                                }
                        c.stroke();
                        u && b.stroke()
                    }
                }
                V.drawMarkers(f);
                u &&
                    (d.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), c.globalCompositeOperation = "source-atop", a.axisX.maskCanvas && c.drawImage(a.axisX.maskCanvas, 0, 0, this.width, this.height), a.axisY.maskCanvas && c.drawImage(a.axisY.maskCanvas, 0, 0, this.width, this.height), this._breaksCanvasCtx && this._breaksCanvasCtx.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), c.clearRect(e.x1, e.y1, e.width, e.height), b.beginPath());
                c.restore();
                c.beginPath();
                return {
                    source: d,
                    dest: this.plotArea.ctx,
                    animationCallback: K.xClipAnimation,
                    easingFunction: K.easing.linear,
                    animationBase: 0
                }
            }
        };
        m.prototype.renderStepLine = function(a) {
            var d = a.targetCanvasCtx || this.plotArea.ctx,
                c = u ? this._preRenderCtx : d;
            if (!(0 >= a.dataSeriesIndexes.length)) {
                var b = this._eventManager.ghostCtx;
                c.save();
                var e = this.plotArea;
                c.beginPath();
                c.rect(e.x1, e.y1, e.width, e.height);
                c.clip();
                for (var f = [], l, v = 0; v < a.dataSeriesIndexes.length; v++) {
                    var A = a.dataSeriesIndexes[v],
                        k = this.data[A];
                    c.lineWidth = k.lineThickness;
                    var n = k.dataPoints,
                        p = "solid";
                    if (c.setLineDash) {
                        var q = N(k.nullDataLineDashType,
                                k.lineThickness),
                            p = k.lineDashType,
                            g = N(p, k.lineThickness);
                        c.setLineDash(g)
                    }
                    var r = k.id;
                    this._eventManager.objectMap[r] = { objectType: "dataSeries", dataSeriesIndex: A };
                    r = P(r);
                    b.strokeStyle = r;
                    b.lineWidth = 0 < k.lineThickness ? Math.max(k.lineThickness, 4) : 0;
                    var r = k._colorSet,
                        h = r = k.lineColor = k.options.lineColor ? k.options.lineColor : r[0];
                    c.strokeStyle = r;
                    var m = !0,
                        t = 0,
                        x, s;
                    c.beginPath();
                    if (0 < n.length) {
                        for (var B = !1, t = 0; t < n.length; t++)
                            if (x = n[t].getTime ? n[t].x.getTime() : n[t].x, !(x < a.axisX.dataInfo.viewPortMin || x > a.axisX.dataInfo.viewPortMax &&
                                    (!k.connectNullData || !B)))
                                if ("number" !== typeof n[t].y) 0 < t && !(k.connectNullData || B || m) && (c.stroke(), u && b.stroke()), B = !0;
                                else {
                                    var C = s;
                                    x = a.axisX.convertValueToPixel(x);
                                    s = a.axisY.convertValueToPixel(n[t].y);
                                    var z = k.dataPointIds[t];
                                    this._eventManager.objectMap[z] = { id: z, objectType: "dataPoint", dataSeriesIndex: A, dataPointIndex: t, x1: x, y1: s };
                                    m || B ? (!m && k.connectNullData ? (c.setLineDash && (k.options.nullDataLineDashType || p === k.lineDashType && k.lineDashType !== k.nullDataLineDashType) && (c.stroke(), c.beginPath(), c.moveTo(l.x,
                                        l.y), p = k.nullDataLineDashType, c.setLineDash(q)), c.lineTo(x, C), c.lineTo(x, s), u && (b.lineTo(x, C), b.lineTo(x, s))) : (c.beginPath(), c.moveTo(x, s), u && (b.beginPath(), b.moveTo(x, s))), B = m = !1) : (c.lineTo(x, C), u && b.lineTo(x, C), c.lineTo(x, s), u && b.lineTo(x, s), 0 == t % 500 && (c.stroke(), c.beginPath(), c.moveTo(x, s), u && (b.stroke(), b.beginPath(), b.moveTo(x, s))));
                                    l = { x: x, y: s };
                                    t < n.length - 1 && (h !== (n[t].lineColor || r) || p !== (n[t].lineDashType || k.lineDashType)) && (c.stroke(), c.beginPath(), c.moveTo(x, s), h = n[t].lineColor || r, c.strokeStyle =
                                        h, c.setLineDash && (n[t].lineDashType ? (p = n[t].lineDashType, c.setLineDash(N(p, k.lineThickness))) : (p = k.lineDashType, c.setLineDash(g))));
                                    if (0 < n[t].markerSize || 0 < k.markerSize) C = k.getMarkerProperties(t, x, s, c), f.push(C), z = P(z), u && f.push({ x: x, y: s, ctx: b, type: C.type, size: C.size, color: z, borderColor: z, borderThickness: C.borderThickness });
                                    (n[t].indexLabel || k.indexLabel || n[t].indexLabelFormatter || k.indexLabelFormatter) && this._indexLabels.push({
                                        chartType: "stepLine",
                                        dataPoint: n[t],
                                        dataSeries: k,
                                        point: { x: x, y: s },
                                        direction: 0 >
                                            n[t].y === a.axisY.reversed ? 1 : -1,
                                        color: r
                                    })
                                }
                        c.stroke();
                        u && b.stroke()
                    }
                }
                V.drawMarkers(f);
                u && (d.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), c.globalCompositeOperation = "source-atop", a.axisX.maskCanvas && c.drawImage(a.axisX.maskCanvas, 0, 0, this.width, this.height), a.axisY.maskCanvas && c.drawImage(a.axisY.maskCanvas, 0, 0, this.width, this.height), this._breaksCanvasCtx && this._breaksCanvasCtx.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), c.clearRect(e.x1, e.y1, e.width, e.height), b.beginPath());
                c.restore();
                c.beginPath();
                return { source: d, dest: this.plotArea.ctx, animationCallback: K.xClipAnimation, easingFunction: K.easing.linear, animationBase: 0 }
            }
        };
        m.prototype.renderSpline = function(a) {
            function d(a) {
                a = w(a, 2);
                if (0 < a.length) {
                    b.beginPath();
                    u && e.beginPath();
                    b.moveTo(a[0].x, a[0].y);
                    a[0].newStrokeStyle && (b.strokeStyle = a[0].newStrokeStyle);
                    a[0].newLineDashArray && b.setLineDash(a[0].newLineDashArray);
                    u && e.moveTo(a[0].x, a[0].y);
                    for (var c = 0; c < a.length - 3; c += 3)
                        if (b.bezierCurveTo(a[c + 1].x, a[c + 1].y, a[c + 2].x, a[c +
                                2].y, a[c + 3].x, a[c + 3].y), u && e.bezierCurveTo(a[c + 1].x, a[c + 1].y, a[c + 2].x, a[c + 2].y, a[c + 3].x, a[c + 3].y), 0 < c && 0 === c % 3E3 || a[c + 3].newStrokeStyle || a[c + 3].newLineDashArray) b.stroke(), b.beginPath(), b.moveTo(a[c + 3].x, a[c + 3].y), a[c + 3].newStrokeStyle && (b.strokeStyle = a[c + 3].newStrokeStyle), a[c + 3].newLineDashArray && b.setLineDash(a[c + 3].newLineDashArray), u && (e.stroke(), e.beginPath(), e.moveTo(a[c + 3].x, a[c + 3].y));
                    b.stroke();
                    u && e.stroke()
                }
            }
            var c = a.targetCanvasCtx || this.plotArea.ctx,
                b = u ? this._preRenderCtx : c;
            if (!(0 >= a.dataSeriesIndexes.length)) {
                var e =
                    this._eventManager.ghostCtx;
                b.save();
                var f = this.plotArea;
                b.beginPath();
                b.rect(f.x1, f.y1, f.width, f.height);
                b.clip();
                for (var l = [], v = 0; v < a.dataSeriesIndexes.length; v++) {
                    var A = a.dataSeriesIndexes[v],
                        k = this.data[A];
                    b.lineWidth = k.lineThickness;
                    var n = k.dataPoints,
                        p = "solid";
                    if (b.setLineDash) {
                        var q = N(k.nullDataLineDashType, k.lineThickness),
                            p = k.lineDashType,
                            g = N(p, k.lineThickness);
                        b.setLineDash(g)
                    }
                    var r = k.id;
                    this._eventManager.objectMap[r] = { objectType: "dataSeries", dataSeriesIndex: A };
                    r = P(r);
                    e.strokeStyle = r;
                    e.lineWidth =
                        0 < k.lineThickness ? Math.max(k.lineThickness, 4) : 0;
                    var r = k._colorSet,
                        h = r = k.lineColor = k.options.lineColor ? k.options.lineColor : r[0];
                    b.strokeStyle = r;
                    var m = 0,
                        t, x, s = [];
                    b.beginPath();
                    if (0 < n.length)
                        for (x = !1, m = 0; m < n.length; m++)
                            if (t = n[m].getTime ? n[m].x.getTime() : n[m].x, !(t < a.axisX.dataInfo.viewPortMin || t > a.axisX.dataInfo.viewPortMax && (!k.connectNullData || !x)))
                                if ("number" !== typeof n[m].y) 0 < m && !x && (k.connectNullData ? b.setLineDash && (0 < s.length && (k.options.nullDataLineDashType || !n[m - 1].lineDashType)) && (s[s.length -
                                    1].newLineDashArray = q, p = k.nullDataLineDashType) : (d(s), s = [])), x = !0;
                                else {
                                    t = a.axisX.convertValueToPixel(t);
                                    x = a.axisY.convertValueToPixel(n[m].y);
                                    var B = k.dataPointIds[m];
                                    this._eventManager.objectMap[B] = { id: B, objectType: "dataPoint", dataSeriesIndex: A, dataPointIndex: m, x1: t, y1: x };
                                    s[s.length] = { x: t, y: x };
                                    m < n.length - 1 && (h !== (n[m].lineColor || r) || p !== (n[m].lineDashType || k.lineDashType)) && (h = n[m].lineColor || r, s[s.length - 1].newStrokeStyle = h, b.setLineDash && (n[m].lineDashType ? (p = n[m].lineDashType, s[s.length - 1].newLineDashArray =
                                        N(p, k.lineThickness)) : (p = k.lineDashType, s[s.length - 1].newLineDashArray = g)));
                                    if (0 < n[m].markerSize || 0 < k.markerSize) {
                                        var C = k.getMarkerProperties(m, t, x, b);
                                        l.push(C);
                                        B = P(B);
                                        u && l.push({ x: t, y: x, ctx: e, type: C.type, size: C.size, color: B, borderColor: B, borderThickness: C.borderThickness })
                                    }(n[m].indexLabel || k.indexLabel || n[m].indexLabelFormatter || k.indexLabelFormatter) && this._indexLabels.push({ chartType: "spline", dataPoint: n[m], dataSeries: k, point: { x: t, y: x }, direction: 0 > n[m].y === a.axisY.reversed ? 1 : -1, color: r });
                                    x = !1
                                }
                    d(s)
                }
                V.drawMarkers(l);
                u && (c.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), b.globalCompositeOperation = "source-atop", a.axisX.maskCanvas && b.drawImage(a.axisX.maskCanvas, 0, 0, this.width, this.height), a.axisY.maskCanvas && b.drawImage(a.axisY.maskCanvas, 0, 0, this.width, this.height), this._breaksCanvasCtx && this._breaksCanvasCtx.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), b.clearRect(f.x1, f.y1, f.width, f.height), e.beginPath());
                b.restore();
                b.beginPath();
                return {
                    source: c,
                    dest: this.plotArea.ctx,
                    animationCallback: K.xClipAnimation,
                    easingFunction: K.easing.linear,
                    animationBase: 0
                }
            }
        };
        m.prototype.renderColumn = function(a) {
            var d = a.targetCanvasCtx || this.plotArea.ctx,
                c = u ? this._preRenderCtx : d;
            if (!(0 >= a.dataSeriesIndexes.length)) {
                var b = null,
                    e = this.plotArea,
                    f = 0,
                    l, v, A, k = a.axisY.convertValueToPixel(a.axisY.logarithmic ? a.axisY.viewportMinimum : 0),
                    f = this.options.dataPointMinWidth ? this.dataPointMinWidth : this.options.dataPointWidth ? this.dataPointWidth : 1,
                    n = this.options.dataPointMaxWidth ? this.dataPointMaxWidth : this.options.dataPointWidth ? this.dataPointWidth :
                    Math.min(0.15 * this.width, 0.9 * (this.plotArea.width / a.plotType.totalDataSeries)) << 0,
                    p = a.axisX.dataInfo.minDiff;
                isFinite(p) || (p = 0.3 * Math.abs(a.axisX.range));
                p = this.dataPointWidth = this.options.dataPointWidth ? this.dataPointWidth : 0.9 * (e.width * (a.axisX.logarithmic ? Math.log(p) / Math.log(a.axisX.range) : Math.abs(p) / Math.abs(a.axisX.range)) / a.plotType.totalDataSeries) << 0;
                this.dataPointMaxWidth && f > n && (f = Math.min(this.options.dataPointWidth ? this.dataPointWidth : Infinity, n));
                !this.dataPointMaxWidth && (this.dataPointMinWidth &&
                    n < f) && (n = Math.max(this.options.dataPointWidth ? this.dataPointWidth : -Infinity, f));
                p < f && (p = f);
                p > n && (p = n);
                c.save();
                u && this._eventManager.ghostCtx.save();
                c.beginPath();
                c.rect(e.x1, e.y1, e.width, e.height);
                c.clip();
                u && (this._eventManager.ghostCtx.beginPath(), this._eventManager.ghostCtx.rect(e.x1, e.y1, e.width, e.height), this._eventManager.ghostCtx.clip());
                for (n = 0; n < a.dataSeriesIndexes.length; n++) {
                    var q = a.dataSeriesIndexes[n],
                        g = this.data[q],
                        r = g.dataPoints;
                    if (0 < r.length)
                        for (var h = 5 < p && g.bevelEnabled ? !0 : !1, f =
                                0; f < r.length; f++)
                            if (r[f].getTime ? A = r[f].x.getTime() : A = r[f].x, !(A < a.axisX.dataInfo.viewPortMin || A > a.axisX.dataInfo.viewPortMax) && "number" === typeof r[f].y) {
                                l = a.axisX.convertValueToPixel(A);
                                v = a.axisY.convertValueToPixel(r[f].y);
                                l = a.axisX.reversed ? l + a.plotType.totalDataSeries * p / 2 - (a.previousDataSeriesCount + n) * p << 0 : l - a.plotType.totalDataSeries * p / 2 + (a.previousDataSeriesCount + n) * p << 0;
                                var m = a.axisX.reversed ? l - p << 0 : l + p << 0,
                                    t;
                                0 <= r[f].y ? t = k : (t = v, v = k);
                                v > t && (b = v, v = t, t = b);
                                b = r[f].color ? r[f].color : g._colorSet[f % g._colorSet.length];
                                ca(c, l, v, m, t, b, 0, null, h && 0 <= r[f].y, 0 > r[f].y && h, !1, !1, g.fillOpacity);
                                b = g.dataPointIds[f];
                                this._eventManager.objectMap[b] = { id: b, objectType: "dataPoint", dataSeriesIndex: q, dataPointIndex: f, x1: l, y1: v, x2: m, y2: t };
                                b = P(b);
                                u && ca(this._eventManager.ghostCtx, l, v, m, t, b, 0, null, !1, !1, !1, !1);
                                (r[f].indexLabel || g.indexLabel || r[f].indexLabelFormatter || g.indexLabelFormatter) && this._indexLabels.push({
                                    chartType: "column",
                                    dataPoint: r[f],
                                    dataSeries: g,
                                    point: { x: l + (m - l) / 2, y: 0 > r[f].y === a.axisY.reversed ? v : t },
                                    direction: 0 > r[f].y ===
                                        a.axisY.reversed ? 1 : -1,
                                    bounds: { x1: l, y1: Math.min(v, t), x2: m, y2: Math.max(v, t) },
                                    color: b
                                })
                            }
                }
                u && (d.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), c.globalCompositeOperation = "source-atop", a.axisX.maskCanvas && c.drawImage(a.axisX.maskCanvas, 0, 0, this.width, this.height), a.axisY.maskCanvas && c.drawImage(a.axisY.maskCanvas, 0, 0, this.width, this.height), this._breaksCanvasCtx && this._breaksCanvasCtx.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), c.clearRect(e.x1, e.y1, e.width, e.height), this._eventManager.ghostCtx.restore());
                c.restore();
                return { source: d, dest: this.plotArea.ctx, animationCallback: K.yScaleAnimation, easingFunction: K.easing.easeOutQuart, animationBase: k < a.axisY.bounds.y1 ? a.axisY.bounds.y1 : k > a.axisY.bounds.y2 ? a.axisY.bounds.y2 : k }
            }
        };
        m.prototype.renderStackedColumn = function(a) {
            var d = a.targetCanvasCtx || this.plotArea.ctx,
                c = u ? this._preRenderCtx : d;
            if (!(0 >= a.dataSeriesIndexes.length)) {
                var b = null,
                    e = this.plotArea,
                    f = [],
                    l = [],
                    v = [],
                    A = [],
                    k = 0,
                    n, p, q = a.axisY.convertValueToPixel(a.axisY.logarithmic ? a.axisY.viewportMinimum : 0),
                    k =
                    this.options.dataPointMinWidth ? this.dataPointMinWidth : this.options.dataPointWidth ? this.dataPointWidth : 1;
                n = this.options.dataPointMaxWidth ? this.dataPointMaxWidth : this.options.dataPointWidth ? this.dataPointWidth : 0.15 * this.width << 0;
                var g = a.axisX.dataInfo.minDiff;
                isFinite(g) || (g = 0.3 * Math.abs(a.axisX.range));
                g = this.options.dataPointWidth ? this.dataPointWidth : 0.9 * (e.width * (a.axisX.logarithmic ? Math.log(g) / Math.log(a.axisX.range) : Math.abs(g) / Math.abs(a.axisX.range)) / a.plotType.plotUnits.length) << 0;
                this.dataPointMaxWidth &&
                    k > n && (k = Math.min(this.options.dataPointWidth ? this.dataPointWidth : Infinity, n));
                !this.dataPointMaxWidth && (this.dataPointMinWidth && n < k) && (n = Math.max(this.options.dataPointWidth ? this.dataPointWidth : -Infinity, k));
                g < k && (g = k);
                g > n && (g = n);
                c.save();
                u && this._eventManager.ghostCtx.save();
                c.beginPath();
                c.rect(e.x1, e.y1, e.width, e.height);
                c.clip();
                u && (this._eventManager.ghostCtx.beginPath(), this._eventManager.ghostCtx.rect(e.x1, e.y1, e.width, e.height), this._eventManager.ghostCtx.clip());
                for (var r = 0; r < a.dataSeriesIndexes.length; r++) {
                    var h =
                        a.dataSeriesIndexes[r],
                        m = this.data[h],
                        t = m.dataPoints;
                    if (0 < t.length) {
                        var x = 5 < g && m.bevelEnabled ? !0 : !1;
                        c.strokeStyle = "#4572A7 ";
                        for (k = 0; k < t.length; k++)
                            if (b = t[k].x.getTime ? t[k].x.getTime() : t[k].x, !(b < a.axisX.dataInfo.viewPortMin || b > a.axisX.dataInfo.viewPortMax) && "number" === typeof t[k].y) {
                                n = a.axisX.convertValueToPixel(b);
                                var s = n - a.plotType.plotUnits.length * g / 2 + a.index * g << 0,
                                    B = s + g << 0,
                                    C;
                                if (a.axisY.logarithmic || a.axisY.scaleBreaks && 0 < a.axisY.scaleBreaks._appliedBreaks.length && 0 < t[k].y) v[b] = t[k].y + (v[b] ? v[b] :
                                    0), 0 < v[b] && (p = a.axisY.convertValueToPixel(v[b]), C = "undefined" !== typeof f[b] ? f[b] : q, f[b] = p);
                                else if (a.axisY.scaleBreaks && 0 < a.axisY.scaleBreaks._appliedBreaks.length && 0 >= t[k].y) A[b] = t[k].y + (A[b] ? A[b] : 0), C = a.axisY.convertValueToPixel(A[b]), p = "undefined" !== typeof l[b] ? l[b] : q, l[b] = C;
                                else if (p = a.axisY.convertValueToPixel(t[k].y), 0 <= t[k].y) {
                                    var z = "undefined" !== typeof f[b] ? f[b] : 0;
                                    p -= z;
                                    C = q - z;
                                    f[b] = z + (C - p)
                                } else z = l[b] ? l[b] : 0, C = p + z, p = q + z, l[b] = z + (C - p);
                                b = t[k].color ? t[k].color : m._colorSet[k % m._colorSet.length];
                                ca(c, s, p, B, C, b, 0, null, x && 0 <= t[k].y, 0 > t[k].y && x, !1, !1, m.fillOpacity);
                                b = m.dataPointIds[k];
                                this._eventManager.objectMap[b] = { id: b, objectType: "dataPoint", dataSeriesIndex: h, dataPointIndex: k, x1: s, y1: p, x2: B, y2: C };
                                b = P(b);
                                u && ca(this._eventManager.ghostCtx, s, p, B, C, b, 0, null, !1, !1, !1, !1);
                                (t[k].indexLabel || m.indexLabel || t[k].indexLabelFormatter || m.indexLabelFormatter) && this._indexLabels.push({
                                    chartType: "stackedColumn",
                                    dataPoint: t[k],
                                    dataSeries: m,
                                    point: { x: n, y: 0 <= t[k].y ? p : C },
                                    direction: 0 > t[k].y === a.axisY.reversed ?
                                        1 : -1,
                                    bounds: { x1: s, y1: Math.min(p, C), x2: B, y2: Math.max(p, C) },
                                    color: b
                                })
                            }
                    }
                }
                u && (d.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), c.globalCompositeOperation = "source-atop", a.axisX.maskCanvas && c.drawImage(a.axisX.maskCanvas, 0, 0, this.width, this.height), a.axisY.maskCanvas && c.drawImage(a.axisY.maskCanvas, 0, 0, this.width, this.height), this._breaksCanvasCtx && this._breaksCanvasCtx.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), c.clearRect(e.x1, e.y1, e.width, e.height), this._eventManager.ghostCtx.restore());
                c.restore();
                return { source: d, dest: this.plotArea.ctx, animationCallback: K.yScaleAnimation, easingFunction: K.easing.easeOutQuart, animationBase: q < a.axisY.bounds.y1 ? a.axisY.bounds.y1 : q > a.axisY.bounds.y2 ? a.axisY.bounds.y2 : q }
            }
        };
        m.prototype.renderStackedColumn100 = function(a) {
            var d = a.targetCanvasCtx || this.plotArea.ctx,
                c = u ? this._preRenderCtx : d;
            if (!(0 >= a.dataSeriesIndexes.length)) {
                var b = null,
                    e = this.plotArea,
                    f = [],
                    l = [],
                    v = [],
                    A = [],
                    k = 0,
                    n, p, q = a.axisY.convertValueToPixel(a.axisY.logarithmic ? a.axisY.viewportMinimum : 0),
                    k = this.options.dataPointMinWidth ? this.dataPointMinWidth : this.options.dataPointWidth ? this.dataPointWidth : 1;
                n = this.options.dataPointMaxWidth ? this.dataPointMaxWidth : this.options.dataPointWidth ? this.dataPointWidth : 0.15 * this.width << 0;
                var g = a.axisX.dataInfo.minDiff;
                isFinite(g) || (g = 0.3 * Math.abs(a.axisX.range));
                g = this.options.dataPointWidth ? this.dataPointWidth : 0.9 * (e.width * (a.axisX.logarithmic ? Math.log(g) / Math.log(a.axisX.range) : Math.abs(g) / Math.abs(a.axisX.range)) / a.plotType.plotUnits.length) << 0;
                this.dataPointMaxWidth &&
                    k > n && (k = Math.min(this.options.dataPointWidth ? this.dataPointWidth : Infinity, n));
                !this.dataPointMaxWidth && (this.dataPointMinWidth && n < k) && (n = Math.max(this.options.dataPointWidth ? this.dataPointWidth : -Infinity, k));
                g < k && (g = k);
                g > n && (g = n);
                c.save();
                u && this._eventManager.ghostCtx.save();
                c.beginPath();
                c.rect(e.x1, e.y1, e.width, e.height);
                c.clip();
                u && (this._eventManager.ghostCtx.beginPath(), this._eventManager.ghostCtx.rect(e.x1, e.y1, e.width, e.height), this._eventManager.ghostCtx.clip());
                for (var r = 0; r < a.dataSeriesIndexes.length; r++) {
                    var h =
                        a.dataSeriesIndexes[r],
                        m = this.data[h],
                        t = m.dataPoints;
                    if (0 < t.length)
                        for (var x = 5 < g && m.bevelEnabled ? !0 : !1, k = 0; k < t.length; k++)
                            if (b = t[k].x.getTime ? t[k].x.getTime() : t[k].x, !(b < a.axisX.dataInfo.viewPortMin || b > a.axisX.dataInfo.viewPortMax) && "number" === typeof t[k].y) {
                                n = a.axisX.convertValueToPixel(b);
                                p = 0 !== a.dataPointYSums[b] ? 100 * (t[k].y / a.dataPointYSums[b]) : 0;
                                var s = n - a.plotType.plotUnits.length * g / 2 + a.index * g << 0,
                                    B = s + g << 0,
                                    C;
                                if (a.axisY.logarithmic || a.axisY.scaleBreaks && 0 < a.axisY.scaleBreaks._appliedBreaks.length &&
                                    0 < t[k].y) {
                                    v[b] = p + ("undefined" !== typeof v[b] ? v[b] : 0);
                                    if (0 >= v[b]) continue;
                                    p = a.axisY.convertValueToPixel(v[b]);
                                    C = f[b] ? f[b] : q;
                                    f[b] = p
                                } else if (a.axisY.scaleBreaks && 0 < a.axisY.scaleBreaks._appliedBreaks.length && 0 >= t[k].y) A[b] = p + ("undefined" !== typeof A[b] ? A[b] : 0), C = a.axisY.convertValueToPixel(A[b]), p = l[b] ? l[b] : q, l[b] = C;
                                else if (p = a.axisY.convertValueToPixel(p), 0 <= t[k].y) {
                                    var z = "undefined" !== typeof f[b] ? f[b] : 0;
                                    p -= z;
                                    C = q - z;
                                    a.dataSeriesIndexes.length - 1 === r && 1 >= Math.abs(e.y1 - p) && (p = e.y1);
                                    f[b] = z + (C - p)
                                } else z = "undefined" !==
                                    typeof l[b] ? l[b] : 0, C = p + z, p = q + z, a.dataSeriesIndexes.length - 1 === r && 1 >= Math.abs(e.y2 - C) && (C = e.y2), l[b] = z + (C - p);
                                b = t[k].color ? t[k].color : m._colorSet[k % m._colorSet.length];
                                ca(c, s, p, B, C, b, 0, null, x && 0 <= t[k].y, 0 > t[k].y && x, !1, !1, m.fillOpacity);
                                b = m.dataPointIds[k];
                                this._eventManager.objectMap[b] = { id: b, objectType: "dataPoint", dataSeriesIndex: h, dataPointIndex: k, x1: s, y1: p, x2: B, y2: C };
                                b = P(b);
                                u && ca(this._eventManager.ghostCtx, s, p, B, C, b, 0, null, !1, !1, !1, !1);
                                (t[k].indexLabel || m.indexLabel || t[k].indexLabelFormatter || m.indexLabelFormatter) &&
                                this._indexLabels.push({ chartType: "stackedColumn100", dataPoint: t[k], dataSeries: m, point: { x: n, y: 0 <= t[k].y ? p : C }, direction: 0 > t[k].y === a.axisY.reversed ? 1 : -1, bounds: { x1: s, y1: Math.min(p, C), x2: B, y2: Math.max(p, C) }, color: b })
                            }
                }
                u && (d.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), c.globalCompositeOperation = "source-atop", a.axisX.maskCanvas && c.drawImage(a.axisX.maskCanvas, 0, 0, this.width, this.height), a.axisY.maskCanvas && c.drawImage(a.axisY.maskCanvas, 0, 0, this.width, this.height), this._breaksCanvasCtx &&
                    this._breaksCanvasCtx.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), c.clearRect(e.x1, e.y1, e.width, e.height), this._eventManager.ghostCtx.restore());
                c.restore();
                return { source: d, dest: this.plotArea.ctx, animationCallback: K.yScaleAnimation, easingFunction: K.easing.easeOutQuart, animationBase: q < a.axisY.bounds.y1 ? a.axisY.bounds.y1 : q > a.axisY.bounds.y2 ? a.axisY.bounds.y2 : q }
            }
        };
        m.prototype.renderBar = function(a) {
            var d = a.targetCanvasCtx || this.plotArea.ctx,
                c = u ? this._preRenderCtx : d;
            if (!(0 >= a.dataSeriesIndexes.length)) {
                var b =
                    null,
                    e = this.plotArea,
                    f = 0,
                    l, v, A, k = a.axisY.convertValueToPixel(a.axisY.logarithmic ? a.axisY.viewportMinimum : 0),
                    f = this.options.dataPointMinWidth ? this.dataPointMinWidth : this.options.dataPointWidth ? this.dataPointWidth : 1,
                    n = this.options.dataPointMaxWidth ? this.dataPointMaxWidth : this.options.dataPointWidth ? this.dataPointWidth : Math.min(0.15 * this.height, 0.9 * (this.plotArea.height / a.plotType.totalDataSeries)) << 0,
                    p = a.axisX.dataInfo.minDiff;
                isFinite(p) || (p = 0.3 * Math.abs(a.axisX.range));
                p = this.options.dataPointWidth ?
                    this.dataPointWidth : 0.9 * (e.height * (a.axisX.logarithmic ? Math.log(p) / Math.log(a.axisX.range) : Math.abs(p) / Math.abs(a.axisX.range)) / a.plotType.totalDataSeries) << 0;
                this.dataPointMaxWidth && f > n && (f = Math.min(this.options.dataPointWidth ? this.dataPointWidth : Infinity, n));
                !this.dataPointMaxWidth && (this.dataPointMinWidth && n < f) && (n = Math.max(this.options.dataPointWidth ? this.dataPointWidth : -Infinity, f));
                p < f && (p = f);
                p > n && (p = n);
                c.save();
                u && this._eventManager.ghostCtx.save();
                c.beginPath();
                c.rect(e.x1, e.y1, e.width, e.height);
                c.clip();
                u && (this._eventManager.ghostCtx.beginPath(), this._eventManager.ghostCtx.rect(e.x1, e.y1, e.width, e.height), this._eventManager.ghostCtx.clip());
                for (n = 0; n < a.dataSeriesIndexes.length; n++) {
                    var q = a.dataSeriesIndexes[n],
                        g = this.data[q],
                        r = g.dataPoints;
                    if (0 < r.length) {
                        var h = 5 < p && g.bevelEnabled ? !0 : !1;
                        c.strokeStyle = "#4572A7 ";
                        for (f = 0; f < r.length; f++)
                            if (r[f].getTime ? A = r[f].x.getTime() : A = r[f].x, !(A < a.axisX.dataInfo.viewPortMin || A > a.axisX.dataInfo.viewPortMax) && "number" === typeof r[f].y) {
                                v = a.axisX.convertValueToPixel(A);
                                l = a.axisY.convertValueToPixel(r[f].y);
                                v = a.axisX.reversed ? v + a.plotType.totalDataSeries * p / 2 - (a.previousDataSeriesCount + n) * p << 0 : v - a.plotType.totalDataSeries * p / 2 + (a.previousDataSeriesCount + n) * p << 0;
                                var m = a.axisX.reversed ? v - p << 0 : v + p << 0,
                                    t;
                                0 <= r[f].y ? t = k : (t = l, l = k);
                                b = r[f].color ? r[f].color : g._colorSet[f % g._colorSet.length];
                                ca(c, t, v, l, m, b, 0, null, h, !1, !1, !1, g.fillOpacity);
                                b = g.dataPointIds[f];
                                this._eventManager.objectMap[b] = { id: b, objectType: "dataPoint", dataSeriesIndex: q, dataPointIndex: f, x1: t, y1: v, x2: l, y2: m };
                                b =
                                    P(b);
                                u && ca(this._eventManager.ghostCtx, t, v, l, m, b, 0, null, !1, !1, !1, !1);
                                (r[f].indexLabel || g.indexLabel || r[f].indexLabelFormatter || g.indexLabelFormatter) && this._indexLabels.push({ chartType: "bar", dataPoint: r[f], dataSeries: g, point: { x: 0 <= r[f].y ? l : t, y: v + (m - v) / 2 }, direction: 0 > r[f].y === a.axisY.reversed ? 1 : -1, bounds: { x1: Math.min(t, l), y1: v, x2: Math.max(t, l), y2: m }, color: b })
                            }
                    }
                }
                u && (d.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), c.globalCompositeOperation = "source-atop", a.axisX.maskCanvas && c.drawImage(a.axisX.maskCanvas,
                    0, 0, this.width, this.height), a.axisY.maskCanvas && c.drawImage(a.axisY.maskCanvas, 0, 0, this.width, this.height), this._breaksCanvasCtx && this._breaksCanvasCtx.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), c.clearRect(e.x1, e.y1, e.width, e.height), this._eventManager.ghostCtx.restore());
                c.restore();
                return {
                    source: d,
                    dest: this.plotArea.ctx,
                    animationCallback: K.xScaleAnimation,
                    easingFunction: K.easing.easeOutQuart,
                    animationBase: k < a.axisY.bounds.x1 ? a.axisY.bounds.x1 : k > a.axisY.bounds.x2 ? a.axisY.bounds.x2 : k
                }
            }
        };
        m.prototype.renderStackedBar = function(a) {
            var d = a.targetCanvasCtx || this.plotArea.ctx,
                c = u ? this._preRenderCtx : d;
            if (!(0 >= a.dataSeriesIndexes.length)) {
                var b = null,
                    e = this.plotArea,
                    f = [],
                    l = [],
                    v = [],
                    A = [],
                    k = 0,
                    n, p, q = a.axisY.convertValueToPixel(a.axisY.logarithmic ? a.axisY.viewportMinimum : 0),
                    k = this.options.dataPointMinWidth ? this.dataPointMinWidth : this.options.dataPointWidth ? this.dataPointWidth : 1;
                p = this.options.dataPointMaxWidth ? this.dataPointMaxWidth : this.options.dataPointWidth ? this.dataPointWidth : 0.15 * this.height <<
                    0;
                var g = a.axisX.dataInfo.minDiff;
                isFinite(g) || (g = 0.3 * Math.abs(a.axisX.range));
                g = this.options.dataPointWidth ? this.dataPointWidth : 0.9 * (e.height * (a.axisX.logarithmic ? Math.log(g) / Math.log(a.axisX.range) : Math.abs(g) / Math.abs(a.axisX.range)) / a.plotType.plotUnits.length) << 0;
                this.dataPointMaxWidth && k > p && (k = Math.min(this.options.dataPointWidth ? this.dataPointWidth : Infinity, p));
                !this.dataPointMaxWidth && (this.dataPointMinWidth && p < k) && (p = Math.max(this.options.dataPointWidth ? this.dataPointWidth : -Infinity, k));
                g <
                    k && (g = k);
                g > p && (g = p);
                c.save();
                u && this._eventManager.ghostCtx.save();
                c.beginPath();
                c.rect(e.x1, e.y1, e.width, e.height);
                c.clip();
                u && (this._eventManager.ghostCtx.beginPath(), this._eventManager.ghostCtx.rect(e.x1, e.y1, e.width, e.height), this._eventManager.ghostCtx.clip());
                for (var r = 0; r < a.dataSeriesIndexes.length; r++) {
                    var h = a.dataSeriesIndexes[r],
                        m = this.data[h],
                        t = m.dataPoints;
                    if (0 < t.length) {
                        var x = 5 < g && m.bevelEnabled ? !0 : !1;
                        c.strokeStyle = "#4572A7 ";
                        for (k = 0; k < t.length; k++)
                            if (b = t[k].x.getTime ? t[k].x.getTime() :
                                t[k].x, !(b < a.axisX.dataInfo.viewPortMin || b > a.axisX.dataInfo.viewPortMax) && "number" === typeof t[k].y) {
                                p = a.axisX.convertValueToPixel(b);
                                var s = p - a.plotType.plotUnits.length * g / 2 + a.index * g << 0,
                                    B = s + g << 0,
                                    C;
                                if (a.axisY.logarithmic || a.axisY.scaleBreaks && 0 < a.axisY.scaleBreaks._appliedBreaks.length && 0 < t[k].y) v[b] = t[k].y + (v[b] ? v[b] : 0), 0 < v[b] && (C = f[b] ? f[b] : q, f[b] = n = a.axisY.convertValueToPixel(v[b]));
                                else if (a.axisY.scaleBreaks && 0 < a.axisY.scaleBreaks._appliedBreaks.length && 0 >= t[k].y) A[b] = t[k].y + (A[b] ? A[b] : 0), n = l[b] ?
                                    l[b] : q, l[b] = C = a.axisY.convertValueToPixel(A[b]);
                                else if (n = a.axisY.convertValueToPixel(t[k].y), 0 <= t[k].y) {
                                    var z = f[b] ? f[b] : 0;
                                    C = q + z;
                                    n += z;
                                    f[b] = z + (n - C)
                                } else z = l[b] ? l[b] : 0, C = n - z, n = q - z, l[b] = z + (n - C);
                                b = t[k].color ? t[k].color : m._colorSet[k % m._colorSet.length];
                                ca(c, C, s, n, B, b, 0, null, x, !1, !1, !1, m.fillOpacity);
                                b = m.dataPointIds[k];
                                this._eventManager.objectMap[b] = { id: b, objectType: "dataPoint", dataSeriesIndex: h, dataPointIndex: k, x1: C, y1: s, x2: n, y2: B };
                                b = P(b);
                                u && ca(this._eventManager.ghostCtx, C, s, n, B, b, 0, null, !1, !1, !1, !1);
                                (t[k].indexLabel || m.indexLabel || t[k].indexLabelFormatter || m.indexLabelFormatter) && this._indexLabels.push({ chartType: "stackedBar", dataPoint: t[k], dataSeries: m, point: { x: 0 <= t[k].y ? n : C, y: p }, direction: 0 > t[k].y === a.axisY.reversed ? 1 : -1, bounds: { x1: Math.min(C, n), y1: s, x2: Math.max(C, n), y2: B }, color: b })
                            }
                    }
                }
                u && (d.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), c.globalCompositeOperation = "source-atop", a.axisX.maskCanvas && c.drawImage(a.axisX.maskCanvas, 0, 0, this.width, this.height), a.axisY.maskCanvas &&
                    c.drawImage(a.axisY.maskCanvas, 0, 0, this.width, this.height), this._breaksCanvasCtx && this._breaksCanvasCtx.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), c.clearRect(e.x1, e.y1, e.width, e.height), this._eventManager.ghostCtx.restore());
                c.restore();
                return { source: d, dest: this.plotArea.ctx, animationCallback: K.xScaleAnimation, easingFunction: K.easing.easeOutQuart, animationBase: q < a.axisY.bounds.x1 ? a.axisY.bounds.x1 : q > a.axisY.bounds.x2 ? a.axisY.bounds.x2 : q }
            }
        };
        m.prototype.renderStackedBar100 = function(a) {
            var d =
                a.targetCanvasCtx || this.plotArea.ctx,
                c = u ? this._preRenderCtx : d;
            if (!(0 >= a.dataSeriesIndexes.length)) {
                var b = null,
                    e = this.plotArea,
                    f = [],
                    l = [],
                    v = [],
                    A = [],
                    k = 0,
                    n, p, q = a.axisY.convertValueToPixel(a.axisY.logarithmic ? a.axisY.viewportMinimum : 0),
                    k = this.options.dataPointMinWidth ? this.dataPointMinWidth : this.options.dataPointWidth ? this.dataPointWidth : 1;
                p = this.options.dataPointMaxWidth ? this.dataPointMaxWidth : this.options.dataPointWidth ? this.dataPointWidth : 0.15 * this.height << 0;
                var g = a.axisX.dataInfo.minDiff;
                isFinite(g) ||
                    (g = 0.3 * Math.abs(a.axisX.range));
                g = this.options.dataPointWidth ? this.dataPointWidth : 0.9 * (e.height * (a.axisX.logarithmic ? Math.log(g) / Math.log(a.axisX.range) : Math.abs(g) / Math.abs(a.axisX.range)) / a.plotType.plotUnits.length) << 0;
                this.dataPointMaxWidth && k > p && (k = Math.min(this.options.dataPointWidth ? this.dataPointWidth : Infinity, p));
                !this.dataPointMaxWidth && (this.dataPointMinWidth && p < k) && (p = Math.max(this.options.dataPointWidth ? this.dataPointWidth : -Infinity, k));
                g < k && (g = k);
                g > p && (g = p);
                c.save();
                u && this._eventManager.ghostCtx.save();
                c.beginPath();
                c.rect(e.x1, e.y1, e.width, e.height);
                c.clip();
                u && (this._eventManager.ghostCtx.beginPath(), this._eventManager.ghostCtx.rect(e.x1, e.y1, e.width, e.height), this._eventManager.ghostCtx.clip());
                for (var r = 0; r < a.dataSeriesIndexes.length; r++) {
                    var h = a.dataSeriesIndexes[r],
                        m = this.data[h],
                        t = m.dataPoints;
                    if (0 < t.length) {
                        var s = 5 < g && m.bevelEnabled ? !0 : !1;
                        c.strokeStyle = "#4572A7 ";
                        for (k = 0; k < t.length; k++)
                            if (b = t[k].x.getTime ? t[k].x.getTime() : t[k].x, !(b < a.axisX.dataInfo.viewPortMin || b > a.axisX.dataInfo.viewPortMax) &&
                                "number" === typeof t[k].y) {
                                p = a.axisX.convertValueToPixel(b);
                                var E;
                                E = 0 !== a.dataPointYSums[b] ? 100 * (t[k].y / a.dataPointYSums[b]) : 0;
                                var B = p - a.plotType.plotUnits.length * g / 2 + a.index * g << 0,
                                    C = B + g << 0;
                                if (a.axisY.logarithmic || a.axisY.scaleBreaks && 0 < a.axisY.scaleBreaks._appliedBreaks.length && 0 < t[k].y) {
                                    v[b] = E + (v[b] ? v[b] : 0);
                                    if (0 >= v[b]) continue;
                                    E = f[b] ? f[b] : q;
                                    f[b] = n = a.axisY.convertValueToPixel(v[b])
                                } else if (a.axisY.scaleBreaks && 0 < a.axisY.scaleBreaks._appliedBreaks.length && 0 >= t[k].y) A[b] = E + (A[b] ? A[b] : 0), n = l[b] ? l[b] :
                                    q, l[b] = E = a.axisY.convertValueToPixel(A[b]);
                                else if (n = a.axisY.convertValueToPixel(E), 0 <= t[k].y) {
                                    var z = f[b] ? f[b] : 0;
                                    E = q + z;
                                    n += z;
                                    a.dataSeriesIndexes.length - 1 === r && 1 >= Math.abs(e.x2 - n) && (n = e.x2);
                                    f[b] = z + (n - E)
                                } else z = l[b] ? l[b] : 0, E = n - z, n = q - z, a.dataSeriesIndexes.length - 1 === r && 1 >= Math.abs(e.x1 - E) && (E = e.x1), l[b] = z + (n - E);
                                b = t[k].color ? t[k].color : m._colorSet[k % m._colorSet.length];
                                ca(c, E, B, n, C, b, 0, null, s, !1, !1, !1, m.fillOpacity);
                                b = m.dataPointIds[k];
                                this._eventManager.objectMap[b] = {
                                    id: b,
                                    objectType: "dataPoint",
                                    dataSeriesIndex: h,
                                    dataPointIndex: k,
                                    x1: E,
                                    y1: B,
                                    x2: n,
                                    y2: C
                                };
                                b = P(b);
                                u && ca(this._eventManager.ghostCtx, E, B, n, C, b, 0, null, !1, !1, !1, !1);
                                (t[k].indexLabel || m.indexLabel || t[k].indexLabelFormatter || m.indexLabelFormatter) && this._indexLabels.push({ chartType: "stackedBar100", dataPoint: t[k], dataSeries: m, point: { x: 0 <= t[k].y ? n : E, y: p }, direction: 0 > t[k].y === a.axisY.reversed ? 1 : -1, bounds: { x1: Math.min(E, n), y1: B, x2: Math.max(E, n), y2: C }, color: b })
                            }
                    }
                }
                u && (d.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), c.globalCompositeOperation = "source-atop",
                    a.axisX.maskCanvas && c.drawImage(a.axisX.maskCanvas, 0, 0, this.width, this.height), a.axisY.maskCanvas && c.drawImage(a.axisY.maskCanvas, 0, 0, this.width, this.height), this._breaksCanvasCtx && this._breaksCanvasCtx.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), c.clearRect(e.x1, e.y1, e.width, e.height), this._eventManager.ghostCtx.restore());
                c.restore();
                return {
                    source: d,
                    dest: this.plotArea.ctx,
                    animationCallback: K.xScaleAnimation,
                    easingFunction: K.easing.easeOutQuart,
                    animationBase: q < a.axisY.bounds.x1 ? a.axisY.bounds.x1 : q > a.axisY.bounds.x2 ? a.axisY.bounds.x2 : q
                }
            }
        };
        m.prototype.renderArea = function(a) {
            var d, c;

            function b() { C && (0 < g.lineThickness && f.stroke(), a.axisY.logarithmic || 0 >= a.axisY.viewportMinimum && 0 <= a.axisY.viewportMaximum ? B = E : 0 > a.axisY.viewportMaximum ? B = v.y1 : 0 < a.axisY.viewportMinimum && (B = E), f.lineTo(m, B), f.lineTo(C.x, B), f.closePath(), f.globalAlpha = g.fillOpacity, f.fill(), f.globalAlpha = 1, u && (l.lineTo(m, B), l.lineTo(C.x, B), l.closePath(), l.fill()), f.beginPath(), f.moveTo(m, t), l.beginPath(), l.moveTo(m, t), C = { x: m, y: t }) }
            var e = a.targetCanvasCtx || this.plotArea.ctx,
                f = u ? this._preRenderCtx : e;
            if (!(0 >= a.dataSeriesIndexes.length)) {
                var l = this._eventManager.ghostCtx,
                    v = a.axisY.lineCoordinates,
                    A = [],
                    k = this.plotArea,
                    n;
                f.save();
                u && l.save();
                f.beginPath();
                f.rect(k.x1, k.y1, k.width, k.height);
                f.clip();
                u && (l.beginPath(), l.rect(k.x1, k.y1, k.width, k.height), l.clip());
                for (var p = 0; p < a.dataSeriesIndexes.length; p++) {
                    var q = a.dataSeriesIndexes[p],
                        g = this.data[q],
                        r = g.dataPoints,
                        A = g.id;
                    this._eventManager.objectMap[A] = { objectType: "dataSeries", dataSeriesIndex: q };
                    A = P(A);
                    l.fillStyle = A;
                    A = [];
                    d = !0;
                    var h = 0,
                        m, t, s, E = a.axisY.convertValueToPixel(a.axisY.logarithmic ? a.axisY.viewportMinimum : 0),
                        B, C = null;
                    if (0 < r.length) {
                        var z = g._colorSet[h % g._colorSet.length],
                            w = g.lineColor = g.options.lineColor || z,
                            y = w;
                        f.fillStyle = z;
                        f.strokeStyle = w;
                        f.lineWidth = g.lineThickness;
                        c = "solid";
                        if (f.setLineDash) {
                            var Q = N(g.nullDataLineDashType, g.lineThickness);
                            c = g.lineDashType;
                            var aa = N(c, g.lineThickness);
                            f.setLineDash(aa)
                        }
                        for (var ja = !0; h < r.length; h++)
                            if (s = r[h].x.getTime ? r[h].x.getTime() : r[h].x, !(s <
                                    a.axisX.dataInfo.viewPortMin || s > a.axisX.dataInfo.viewPortMax && (!g.connectNullData || !ja)))
                                if ("number" !== typeof r[h].y) g.connectNullData || (ja || d) || b(), ja = !0;
                                else {
                                    m = a.axisX.convertValueToPixel(s);
                                    t = a.axisY.convertValueToPixel(r[h].y);
                                    d || ja ? (!d && g.connectNullData ? (f.setLineDash && (g.options.nullDataLineDashType || c === g.lineDashType && g.lineDashType !== g.nullDataLineDashType) && (d = m, c = t, m = n.x, t = n.y, b(), f.moveTo(n.x, n.y), m = d, t = c, C = n, c = g.nullDataLineDashType, f.setLineDash(Q)), f.lineTo(m, t), u && l.lineTo(m, t)) :
                                        (f.beginPath(), f.moveTo(m, t), u && (l.beginPath(), l.moveTo(m, t)), C = { x: m, y: t }), ja = d = !1) : (f.lineTo(m, t), u && l.lineTo(m, t), 0 == h % 250 && b());
                                    n = { x: m, y: t };
                                    h < r.length - 1 && (y !== (r[h].lineColor || w) || c !== (r[h].lineDashType || g.lineDashType)) && (b(), y = r[h].lineColor || w, f.strokeStyle = y, f.setLineDash && (r[h].lineDashType ? (c = r[h].lineDashType, f.setLineDash(N(c, g.lineThickness))) : (c = g.lineDashType, f.setLineDash(aa))));
                                    var Y = g.dataPointIds[h];
                                    this._eventManager.objectMap[Y] = {
                                        id: Y,
                                        objectType: "dataPoint",
                                        dataSeriesIndex: q,
                                        dataPointIndex: h,
                                        x1: m,
                                        y1: t
                                    };
                                    0 !== r[h].markerSize && (0 < r[h].markerSize || 0 < g.markerSize) && (s = g.getMarkerProperties(h, m, t, f), A.push(s), Y = P(Y), u && A.push({ x: m, y: t, ctx: l, type: s.type, size: s.size, color: Y, borderColor: Y, borderThickness: s.borderThickness }));
                                    (r[h].indexLabel || g.indexLabel || r[h].indexLabelFormatter || g.indexLabelFormatter) && this._indexLabels.push({ chartType: "area", dataPoint: r[h], dataSeries: g, point: { x: m, y: t }, direction: 0 > r[h].y === a.axisY.reversed ? 1 : -1, color: z })
                                }
                        b();
                        V.drawMarkers(A)
                    }
                }
                u && (e.drawImage(this._preRenderCanvas,
                    0, 0, this.width, this.height), f.globalCompositeOperation = "source-atop", a.axisX.maskCanvas && f.drawImage(a.axisX.maskCanvas, 0, 0, this.width, this.height), a.axisY.maskCanvas && f.drawImage(a.axisY.maskCanvas, 0, 0, this.width, this.height), this._breaksCanvasCtx && this._breaksCanvasCtx.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), f.clearRect(k.x1, k.y1, k.width, k.height), this._eventManager.ghostCtx.restore());
                f.restore();
                return {
                    source: e,
                    dest: this.plotArea.ctx,
                    animationCallback: K.xClipAnimation,
                    easingFunction: K.easing.linear,
                    animationBase: 0
                }
            }
        };
        m.prototype.renderSplineArea = function(a) {
            function d() {
                var c = w(s, 2);
                if (0 < c.length) {
                    if (0 < n.lineThickness) {
                        b.beginPath();
                        b.moveTo(c[0].x, c[0].y);
                        c[0].newStrokeStyle && (b.strokeStyle = c[0].newStrokeStyle);
                        c[0].newLineDashArray && b.setLineDash(c[0].newLineDashArray);
                        for (var d = 0; d < c.length - 3; d += 3)
                            if (b.bezierCurveTo(c[d + 1].x, c[d + 1].y, c[d + 2].x, c[d + 2].y, c[d + 3].x, c[d + 3].y), u && e.bezierCurveTo(c[d + 1].x, c[d + 1].y, c[d + 2].x, c[d + 2].y, c[d + 3].x, c[d + 3].y), c[d + 3].newStrokeStyle || c[d + 3].newLineDashArray) b.stroke(),
                                b.beginPath(), b.moveTo(c[d + 3].x, c[d + 3].y), c[d + 3].newStrokeStyle && (b.strokeStyle = c[d + 3].newStrokeStyle), c[d + 3].newLineDashArray && b.setLineDash(c[d + 3].newLineDashArray);
                        b.stroke()
                    }
                    b.beginPath();
                    b.moveTo(c[0].x, c[0].y);
                    u && (e.beginPath(), e.moveTo(c[0].x, c[0].y));
                    for (d = 0; d < c.length - 3; d += 3) b.bezierCurveTo(c[d + 1].x, c[d + 1].y, c[d + 2].x, c[d + 2].y, c[d + 3].x, c[d + 3].y), u && e.bezierCurveTo(c[d + 1].x, c[d + 1].y, c[d + 2].x, c[d + 2].y, c[d + 3].x, c[d + 3].y);
                    a.axisY.logarithmic || 0 >= a.axisY.viewportMinimum && 0 <= a.axisY.viewportMaximum ?
                        m = h : 0 > a.axisY.viewportMaximum ? m = f.y1 : 0 < a.axisY.viewportMinimum && (m = h);
                    t = { x: c[0].x, y: c[0].y };
                    b.lineTo(c[c.length - 1].x, m);
                    b.lineTo(t.x, m);
                    b.closePath();
                    b.globalAlpha = n.fillOpacity;
                    b.fill();
                    b.globalAlpha = 1;
                    u && (e.lineTo(c[c.length - 1].x, m), e.lineTo(t.x, m), e.closePath(), e.fill())
                }
            }
            var c = a.targetCanvasCtx || this.plotArea.ctx,
                b = u ? this._preRenderCtx : c;
            if (!(0 >= a.dataSeriesIndexes.length)) {
                var e = this._eventManager.ghostCtx,
                    f = a.axisY.lineCoordinates,
                    l = [],
                    v = this.plotArea;
                b.save();
                u && e.save();
                b.beginPath();
                b.rect(v.x1,
                    v.y1, v.width, v.height);
                b.clip();
                u && (e.beginPath(), e.rect(v.x1, v.y1, v.width, v.height), e.clip());
                for (var A = 0; A < a.dataSeriesIndexes.length; A++) {
                    var k = a.dataSeriesIndexes[A],
                        n = this.data[k],
                        p = n.dataPoints,
                        l = n.id;
                    this._eventManager.objectMap[l] = { objectType: "dataSeries", dataSeriesIndex: k };
                    l = P(l);
                    e.fillStyle = l;
                    var l = [],
                        q = 0,
                        g, r, h = a.axisY.convertValueToPixel(a.axisY.logarithmic ? a.axisY.viewportMinimum : 0),
                        m, t = null,
                        s = [];
                    if (0 < p.length) {
                        var E = n._colorSet[q % n._colorSet.length],
                            B = n.lineColor = n.options.lineColor ||
                            E,
                            C = B;
                        b.fillStyle = E;
                        b.strokeStyle = B;
                        b.lineWidth = n.lineThickness;
                        var z = "solid";
                        if (b.setLineDash) {
                            var y = N(n.nullDataLineDashType, n.lineThickness),
                                z = n.lineDashType,
                                D = N(z, n.lineThickness);
                            b.setLineDash(D)
                        }
                        for (r = !1; q < p.length; q++)
                            if (g = p[q].x.getTime ? p[q].x.getTime() : p[q].x, !(g < a.axisX.dataInfo.viewPortMin || g > a.axisX.dataInfo.viewPortMax && (!n.connectNullData || !r)))
                                if ("number" !== typeof p[q].y) 0 < q && !r && (n.connectNullData ? b.setLineDash && (0 < s.length && (n.options.nullDataLineDashType || !p[q - 1].lineDashType)) &&
                                    (s[s.length - 1].newLineDashArray = y, z = n.nullDataLineDashType) : (d(), s = [])), r = !0;
                                else {
                                    g = a.axisX.convertValueToPixel(g);
                                    r = a.axisY.convertValueToPixel(p[q].y);
                                    var Q = n.dataPointIds[q];
                                    this._eventManager.objectMap[Q] = { id: Q, objectType: "dataPoint", dataSeriesIndex: k, dataPointIndex: q, x1: g, y1: r };
                                    s[s.length] = { x: g, y: r };
                                    q < p.length - 1 && (C !== (p[q].lineColor || B) || z !== (p[q].lineDashType || n.lineDashType)) && (C = p[q].lineColor || B, s[s.length - 1].newStrokeStyle = C, b.setLineDash && (p[q].lineDashType ? (z = p[q].lineDashType, s[s.length -
                                        1].newLineDashArray = N(z, n.lineThickness)) : (z = n.lineDashType, s[s.length - 1].newLineDashArray = D)));
                                    if (0 !== p[q].markerSize && (0 < p[q].markerSize || 0 < n.markerSize)) {
                                        var aa = n.getMarkerProperties(q, g, r, b);
                                        l.push(aa);
                                        Q = P(Q);
                                        u && l.push({ x: g, y: r, ctx: e, type: aa.type, size: aa.size, color: Q, borderColor: Q, borderThickness: aa.borderThickness })
                                    }(p[q].indexLabel || n.indexLabel || p[q].indexLabelFormatter || n.indexLabelFormatter) && this._indexLabels.push({
                                        chartType: "splineArea",
                                        dataPoint: p[q],
                                        dataSeries: n,
                                        point: { x: g, y: r },
                                        direction: 0 >
                                            p[q].y === a.axisY.reversed ? 1 : -1,
                                        color: E
                                    });
                                    r = !1
                                }
                        d();
                        V.drawMarkers(l)
                    }
                }
                u && (c.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), b.globalCompositeOperation = "source-atop", a.axisX.maskCanvas && b.drawImage(a.axisX.maskCanvas, 0, 0, this.width, this.height), a.axisY.maskCanvas && b.drawImage(a.axisY.maskCanvas, 0, 0, this.width, this.height), this._breaksCanvasCtx && this._breaksCanvasCtx.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), b.clearRect(v.x1, v.y1, v.width, v.height), this._eventManager.ghostCtx.restore());
                b.restore();
                return { source: c, dest: this.plotArea.ctx, animationCallback: K.xClipAnimation, easingFunction: K.easing.linear, animationBase: 0 }
            }
        };
        m.prototype.renderStepArea = function(a) {
            var d, c;

            function b() {
                C && (0 < g.lineThickness && f.stroke(), a.axisY.logarithmic || 0 >= a.axisY.viewportMinimum && 0 <= a.axisY.viewportMaximum ? B = E : 0 > a.axisY.viewportMaximum ? B = v.y1 : 0 < a.axisY.viewportMinimum && (B = E), f.lineTo(m, B), f.lineTo(C.x, B), f.closePath(), f.globalAlpha = g.fillOpacity, f.fill(), f.globalAlpha = 1, u && (l.lineTo(m, B), l.lineTo(C.x,
                    B), l.closePath(), l.fill()), f.beginPath(), f.moveTo(m, t), l.beginPath(), l.moveTo(m, t), C = { x: m, y: t })
            }
            var e = a.targetCanvasCtx || this.plotArea.ctx,
                f = u ? this._preRenderCtx : e;
            if (!(0 >= a.dataSeriesIndexes.length)) {
                var l = this._eventManager.ghostCtx,
                    v = a.axisY.lineCoordinates,
                    A = [],
                    k = this.plotArea,
                    n;
                f.save();
                u && l.save();
                f.beginPath();
                f.rect(k.x1, k.y1, k.width, k.height);
                f.clip();
                u && (l.beginPath(), l.rect(k.x1, k.y1, k.width, k.height), l.clip());
                for (var p = 0; p < a.dataSeriesIndexes.length; p++) {
                    var q = a.dataSeriesIndexes[p],
                        g = this.data[q],
                        r = g.dataPoints,
                        A = g.id;
                    this._eventManager.objectMap[A] = { objectType: "dataSeries", dataSeriesIndex: q };
                    A = P(A);
                    l.fillStyle = A;
                    A = [];
                    d = !0;
                    var h = 0,
                        m, t, s, E = a.axisY.convertValueToPixel(a.axisY.logarithmic ? a.axisY.viewportMinimum : 0),
                        B, C = null;
                    c = !1;
                    if (0 < r.length) {
                        var z = g._colorSet[h % g._colorSet.length],
                            w = g.lineColor = g.options.lineColor || z,
                            y = w;
                        f.fillStyle = z;
                        f.strokeStyle = w;
                        f.lineWidth = g.lineThickness;
                        var Q = "solid";
                        if (f.setLineDash) {
                            var aa = N(g.nullDataLineDashType, g.lineThickness),
                                Q = g.lineDashType,
                                D = N(Q, g.lineThickness);
                            f.setLineDash(D)
                        }
                        for (; h < r.length; h++)
                            if (s = r[h].x.getTime ? r[h].x.getTime() : r[h].x, !(s < a.axisX.dataInfo.viewPortMin || s > a.axisX.dataInfo.viewPortMax && (!g.connectNullData || !c))) {
                                var Y = t;
                                "number" !== typeof r[h].y ? (g.connectNullData || (c || d) || b(), c = !0) : (m = a.axisX.convertValueToPixel(s), t = a.axisY.convertValueToPixel(r[h].y), d || c ? (!d && g.connectNullData ? (f.setLineDash && (g.options.nullDataLineDashType || Q === g.lineDashType && g.lineDashType !== g.nullDataLineDashType) && (d = m, c = t, m = n.x, t = n.y, b(),
                                    f.moveTo(n.x, n.y), m = d, t = c, C = n, Q = g.nullDataLineDashType, f.setLineDash(aa)), f.lineTo(m, Y), f.lineTo(m, t), u && (l.lineTo(m, Y), l.lineTo(m, t))) : (f.beginPath(), f.moveTo(m, t), u && (l.beginPath(), l.moveTo(m, t)), C = { x: m, y: t }), c = d = !1) : (f.lineTo(m, Y), u && l.lineTo(m, Y), f.lineTo(m, t), u && l.lineTo(m, t), 0 == h % 250 && b()), n = { x: m, y: t }, h < r.length - 1 && (y !== (r[h].lineColor || w) || Q !== (r[h].lineDashType || g.lineDashType)) && (b(), y = r[h].lineColor || w, f.strokeStyle = y, f.setLineDash && (r[h].lineDashType ? (Q = r[h].lineDashType, f.setLineDash(N(Q,
                                    g.lineThickness))) : (Q = g.lineDashType, f.setLineDash(D)))), s = g.dataPointIds[h], this._eventManager.objectMap[s] = { id: s, objectType: "dataPoint", dataSeriesIndex: q, dataPointIndex: h, x1: m, y1: t }, 0 !== r[h].markerSize && (0 < r[h].markerSize || 0 < g.markerSize) && (Y = g.getMarkerProperties(h, m, t, f), A.push(Y), s = P(s), u && A.push({ x: m, y: t, ctx: l, type: Y.type, size: Y.size, color: s, borderColor: s, borderThickness: Y.borderThickness })), (r[h].indexLabel || g.indexLabel || r[h].indexLabelFormatter || g.indexLabelFormatter) && this._indexLabels.push({
                                    chartType: "stepArea",
                                    dataPoint: r[h],
                                    dataSeries: g,
                                    point: { x: m, y: t },
                                    direction: 0 > r[h].y === a.axisY.reversed ? 1 : -1,
                                    color: z
                                }))
                            }
                        b();
                        V.drawMarkers(A)
                    }
                }
                u && (e.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), f.globalCompositeOperation = "source-atop", a.axisX.maskCanvas && f.drawImage(a.axisX.maskCanvas, 0, 0, this.width, this.height), a.axisY.maskCanvas && f.drawImage(a.axisY.maskCanvas, 0, 0, this.width, this.height), this._breaksCanvasCtx && this._breaksCanvasCtx.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), f.clearRect(k.x1,
                    k.y1, k.width, k.height), this._eventManager.ghostCtx.restore());
                f.restore();
                return { source: e, dest: this.plotArea.ctx, animationCallback: K.xClipAnimation, easingFunction: K.easing.linear, animationBase: 0 }
            }
        };
        m.prototype.renderStackedArea = function(a) {
            function d() {
                if (!(1 > k.length)) {
                    for (0 < z.lineThickness && b.stroke(); 0 < k.length;) {
                        var a = k.pop();
                        b.lineTo(a.x, a.y);
                        u && s.lineTo(a.x, a.y)
                    }
                    b.closePath();
                    b.globalAlpha = z.fillOpacity;
                    b.fill();
                    b.globalAlpha = 1;
                    b.beginPath();
                    u && (s.closePath(), s.fill(), s.beginPath());
                    k = []
                }
            }
            var c =
                a.targetCanvasCtx || this.plotArea.ctx,
                b = u ? this._preRenderCtx : c;
            if (!(0 >= a.dataSeriesIndexes.length)) {
                var e = null,
                    f = null,
                    l = [],
                    v = this.plotArea,
                    h = [],
                    k = [],
                    n = [],
                    p = [],
                    q = 0,
                    g, r, m = a.axisY.convertValueToPixel(a.axisY.logarithmic ? a.axisY.viewportMinimum : 0),
                    s = this._eventManager.ghostCtx,
                    t, x, E;
                u && s.beginPath();
                b.save();
                u && s.save();
                b.beginPath();
                b.rect(v.x1, v.y1, v.width, v.height);
                b.clip();
                u && (s.beginPath(), s.rect(v.x1, v.y1, v.width, v.height), s.clip());
                for (var e = [], B = 0; B < a.dataSeriesIndexes.length; B++) {
                    var C = a.dataSeriesIndexes[B],
                        z = this.data[C],
                        w = z.dataPoints;
                    z.dataPointIndexes = [];
                    for (q = 0; q < w.length; q++) C = w[q].x.getTime ? w[q].x.getTime() : w[q].x, z.dataPointIndexes[C] = q, e[C] || (n.push(C), e[C] = !0);
                    n.sort(Pa)
                }
                for (B = 0; B < a.dataSeriesIndexes.length; B++) {
                    C = a.dataSeriesIndexes[B];
                    z = this.data[C];
                    w = z.dataPoints;
                    x = !0;
                    k = [];
                    q = z.id;
                    this._eventManager.objectMap[q] = { objectType: "dataSeries", dataSeriesIndex: C };
                    q = P(q);
                    s.fillStyle = q;
                    if (0 < n.length) {
                        var e = z._colorSet[0],
                            y = z.lineColor = z.options.lineColor || e,
                            Q = y;
                        b.fillStyle = e;
                        b.strokeStyle = y;
                        b.lineWidth =
                            z.lineThickness;
                        E = "solid";
                        if (b.setLineDash) {
                            var aa = N(z.nullDataLineDashType, z.lineThickness);
                            E = z.lineDashType;
                            var D = N(E, z.lineThickness);
                            b.setLineDash(D)
                        }
                        for (var Y = !0, q = 0; q < n.length; q++) {
                            var f = n[q],
                                fa = null,
                                fa = 0 <= z.dataPointIndexes[f] ? w[z.dataPointIndexes[f]] : { x: f, y: null };
                            if (!(f < a.axisX.dataInfo.viewPortMin || f > a.axisX.dataInfo.viewPortMax && (!z.connectNullData || !Y)))
                                if ("number" !== typeof fa.y) z.connectNullData || (Y || x) || d(), Y = !0;
                                else {
                                    g = a.axisX.convertValueToPixel(f);
                                    var ma = h[f] ? h[f] : 0;
                                    if (a.axisY.logarithmic ||
                                        a.axisY.scaleBreaks && 0 < a.axisY.scaleBreaks._appliedBreaks.length) {
                                        p[f] = fa.y + (p[f] ? p[f] : 0);
                                        if (0 >= p[f] && a.axisY.logarithmic) continue;
                                        r = a.axisY.convertValueToPixel(p[f])
                                    } else r = a.axisY.convertValueToPixel(fa.y), r -= ma;
                                    k.push({ x: g, y: m - ma });
                                    h[f] = m - r;
                                    x || Y ? (!x && z.connectNullData ? (b.setLineDash && (z.options.nullDataLineDashType || E === z.lineDashType && z.lineDashType !== z.nullDataLineDashType) && (x = k.pop(), E = k[k.length - 1], d(), b.moveTo(t.x, t.y), k.push(E), k.push(x), E = z.nullDataLineDashType, b.setLineDash(aa)), b.lineTo(g,
                                        r), u && s.lineTo(g, r)) : (b.beginPath(), b.moveTo(g, r), u && (s.beginPath(), s.moveTo(g, r))), Y = x = !1) : (b.lineTo(g, r), u && s.lineTo(g, r), 0 == q % 250 && (d(), b.moveTo(g, r), u && s.moveTo(g, r), k.push({ x: g, y: m - ma })));
                                    t = { x: g, y: r };
                                    q < w.length - 1 && (Q !== (w[q].lineColor || y) || E !== (w[q].lineDashType || z.lineDashType)) && (d(), b.beginPath(), b.moveTo(g, r), k.push({ x: g, y: m - ma }), Q = w[q].lineColor || y, b.strokeStyle = Q, b.setLineDash && (w[q].lineDashType ? (E = w[q].lineDashType, b.setLineDash(N(E, z.lineThickness))) : (E = z.lineDashType, b.setLineDash(D))));
                                    if (0 <= z.dataPointIndexes[f]) {
                                        var F = z.dataPointIds[z.dataPointIndexes[f]];
                                        this._eventManager.objectMap[F] = { id: F, objectType: "dataPoint", dataSeriesIndex: C, dataPointIndex: z.dataPointIndexes[f], x1: g, y1: r }
                                    }
                                    0 <= z.dataPointIndexes[f] && 0 !== fa.markerSize && (0 < fa.markerSize || 0 < z.markerSize) && (ma = z.getMarkerProperties(z.dataPointIndexes[f], g, r, b), l.push(ma), f = P(F), u && l.push({ x: g, y: r, ctx: s, type: ma.type, size: ma.size, color: f, borderColor: f, borderThickness: ma.borderThickness }));
                                    (fa.indexLabel || z.indexLabel || fa.indexLabelFormatter ||
                                        z.indexLabelFormatter) && this._indexLabels.push({ chartType: "stackedArea", dataPoint: fa, dataSeries: z, point: { x: g, y: r }, direction: 0 > w[q].y === a.axisY.reversed ? 1 : -1, color: e })
                                }
                        }
                        d();
                        b.moveTo(g, r);
                        u && s.moveTo(g, r)
                    }
                    delete z.dataPointIndexes
                }
                V.drawMarkers(l);
                u && (c.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), b.globalCompositeOperation = "source-atop", a.axisX.maskCanvas && b.drawImage(a.axisX.maskCanvas, 0, 0, this.width, this.height), a.axisY.maskCanvas && b.drawImage(a.axisY.maskCanvas, 0, 0, this.width, this.height),
                    this._breaksCanvasCtx && this._breaksCanvasCtx.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), b.clearRect(v.x1, v.y1, v.width, v.height), s.restore());
                b.restore();
                return { source: c, dest: this.plotArea.ctx, animationCallback: K.xClipAnimation, easingFunction: K.easing.linear, animationBase: 0 }
            }
        };
        m.prototype.renderStackedArea100 = function(a) {
            function d() {
                for (0 < z.lineThickness && b.stroke(); 0 < k.length;) {
                    var a = k.pop();
                    b.lineTo(a.x, a.y);
                    u && E.lineTo(a.x, a.y)
                }
                b.closePath();
                b.globalAlpha = z.fillOpacity;
                b.fill();
                b.globalAlpha = 1;
                b.beginPath();
                u && (E.closePath(), E.fill(), E.beginPath());
                k = []
            }
            var c = a.targetCanvasCtx || this.plotArea.ctx,
                b = u ? this._preRenderCtx : c;
            if (!(0 >= a.dataSeriesIndexes.length)) {
                var e = null,
                    f = null,
                    l = this.plotArea,
                    v = [],
                    h = [],
                    k = [],
                    n = [],
                    p = [],
                    q = 0,
                    g, r, m, s, t, x = a.axisY.convertValueToPixel(a.axisY.logarithmic ? a.axisY.viewportMinimum : 0),
                    E = this._eventManager.ghostCtx;
                b.save();
                u && E.save();
                b.beginPath();
                b.rect(l.x1, l.y1, l.width, l.height);
                b.clip();
                u && (E.beginPath(), E.rect(l.x1, l.y1, l.width, l.height), E.clip());
                for (var e = [], B = 0; B < a.dataSeriesIndexes.length; B++) {
                    var w = a.dataSeriesIndexes[B],
                        z = this.data[w],
                        y = z.dataPoints;
                    z.dataPointIndexes = [];
                    for (q = 0; q < y.length; q++) w = y[q].x.getTime ? y[q].x.getTime() : y[q].x, z.dataPointIndexes[w] = q, e[w] || (n.push(w), e[w] = !0);
                    n.sort(Pa)
                }
                for (B = 0; B < a.dataSeriesIndexes.length; B++) {
                    w = a.dataSeriesIndexes[B];
                    z = this.data[w];
                    y = z.dataPoints;
                    s = !0;
                    e = z.id;
                    this._eventManager.objectMap[e] = { objectType: "dataSeries", dataSeriesIndex: w };
                    e = P(e);
                    E.fillStyle = e;
                    k = [];
                    if (0 < n.length) {
                        var e = z._colorSet[q %
                                z._colorSet.length],
                            D = z.lineColor = z.options.lineColor || e,
                            Q = D;
                        b.fillStyle = e;
                        b.strokeStyle = D;
                        b.lineWidth = z.lineThickness;
                        t = "solid";
                        if (b.setLineDash) {
                            var aa = N(z.nullDataLineDashType, z.lineThickness);
                            t = z.lineDashType;
                            var F = N(t, z.lineThickness);
                            b.setLineDash(F)
                        }
                        for (var Y = !0, q = 0; q < n.length; q++) {
                            var f = n[q],
                                fa = null,
                                fa = 0 <= z.dataPointIndexes[f] ? y[z.dataPointIndexes[f]] : { x: f, y: null };
                            if (!(f < a.axisX.dataInfo.viewPortMin || f > a.axisX.dataInfo.viewPortMax && (!z.connectNullData || !Y)))
                                if ("number" !== typeof fa.y) z.connectNullData ||
                                    (Y || s) || d(), Y = !0;
                                else {
                                    var ma;
                                    ma = 0 !== a.dataPointYSums[f] ? 100 * (fa.y / a.dataPointYSums[f]) : 0;
                                    g = a.axisX.convertValueToPixel(f);
                                    var ba = h[f] ? h[f] : 0;
                                    if (a.axisY.logarithmic || a.axisY.scaleBreaks && 0 < a.axisY.scaleBreaks._appliedBreaks.length) {
                                        p[f] = ma + (p[f] ? p[f] : 0);
                                        if (0 >= p[f] && a.axisY.logarithmic) continue;
                                        r = a.axisY.convertValueToPixel(p[f])
                                    } else r = a.axisY.convertValueToPixel(ma), r -= ba;
                                    k.push({ x: g, y: x - ba });
                                    h[f] = x - r;
                                    s || Y ? (!s && z.connectNullData ? (b.setLineDash && (z.options.nullDataLineDashType || t === z.lineDashType &&
                                        z.lineDashType !== z.nullDataLineDashType) && (s = k.pop(), t = k[k.length - 1], d(), b.moveTo(m.x, m.y), k.push(t), k.push(s), t = z.nullDataLineDashType, b.setLineDash(aa)), b.lineTo(g, r), u && E.lineTo(g, r)) : (b.beginPath(), b.moveTo(g, r), u && (E.beginPath(), E.moveTo(g, r))), Y = s = !1) : (b.lineTo(g, r), u && E.lineTo(g, r), 0 == q % 250 && (d(), b.moveTo(g, r), u && E.moveTo(g, r), k.push({ x: g, y: x - ba })));
                                    m = { x: g, y: r };
                                    q < y.length - 1 && (Q !== (y[q].lineColor || D) || t !== (y[q].lineDashType || z.lineDashType)) && (d(), b.beginPath(), b.moveTo(g, r), k.push({
                                        x: g,
                                        y: x -
                                            ba
                                    }), Q = y[q].lineColor || D, b.strokeStyle = Q, b.setLineDash && (y[q].lineDashType ? (t = y[q].lineDashType, b.setLineDash(N(t, z.lineThickness))) : (t = z.lineDashType, b.setLineDash(F))));
                                    if (0 <= z.dataPointIndexes[f]) {
                                        var G = z.dataPointIds[z.dataPointIndexes[f]];
                                        this._eventManager.objectMap[G] = { id: G, objectType: "dataPoint", dataSeriesIndex: w, dataPointIndex: z.dataPointIndexes[f], x1: g, y1: r }
                                    }
                                    0 <= z.dataPointIndexes[f] && 0 !== fa.markerSize && (0 < fa.markerSize || 0 < z.markerSize) && (ba = z.getMarkerProperties(q, g, r, b), v.push(ba), f = P(G),
                                        u && v.push({ x: g, y: r, ctx: E, type: ba.type, size: ba.size, color: f, borderColor: f, borderThickness: ba.borderThickness }));
                                    (fa.indexLabel || z.indexLabel || fa.indexLabelFormatter || z.indexLabelFormatter) && this._indexLabels.push({ chartType: "stackedArea100", dataPoint: fa, dataSeries: z, point: { x: g, y: r }, direction: 0 > y[q].y === a.axisY.reversed ? 1 : -1, color: e })
                                }
                        }
                        d();
                        b.moveTo(g, r);
                        u && E.moveTo(g, r)
                    }
                    delete z.dataPointIndexes
                }
                V.drawMarkers(v);
                u && (c.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), b.globalCompositeOperation =
                    "source-atop", a.axisX.maskCanvas && b.drawImage(a.axisX.maskCanvas, 0, 0, this.width, this.height), a.axisY.maskCanvas && b.drawImage(a.axisY.maskCanvas, 0, 0, this.width, this.height), this._breaksCanvasCtx && this._breaksCanvasCtx.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), b.clearRect(l.x1, l.y1, l.width, l.height), E.restore());
                b.restore();
                return { source: c, dest: this.plotArea.ctx, animationCallback: K.xClipAnimation, easingFunction: K.easing.linear, animationBase: 0 }
            }
        };
        m.prototype.renderBubble = function(a) {
            var d =
                a.targetCanvasCtx || this.plotArea.ctx,
                c = u ? this._preRenderCtx : d;
            if (!(0 >= a.dataSeriesIndexes.length)) {
                var b = this.plotArea,
                    e = 0,
                    f, l;
                c.save();
                u && this._eventManager.ghostCtx.save();
                c.beginPath();
                c.rect(b.x1, b.y1, b.width, b.height);
                c.clip();
                u && (this._eventManager.ghostCtx.beginPath(), this._eventManager.ghostCtx.rect(b.x1, b.y1, b.width, b.height), this._eventManager.ghostCtx.clip());
                for (var v = -Infinity, h = Infinity, k = 0; k < a.dataSeriesIndexes.length; k++)
                    for (var n = a.dataSeriesIndexes[k], p = this.data[n], q = p.dataPoints,
                            g = 0, e = 0; e < q.length; e++) f = q[e].getTime ? f = q[e].x.getTime() : f = q[e].x, f < a.axisX.dataInfo.viewPortMin || f > a.axisX.dataInfo.viewPortMax || "undefined" === typeof q[e].z || (g = q[e].z, g > v && (v = g), g < h && (h = g));
                for (var r = 25 * Math.PI, m = Math.max(Math.pow(0.25 * Math.min(b.height, b.width) / 2, 2) * Math.PI, r), k = 0; k < a.dataSeriesIndexes.length; k++)
                    if (n = a.dataSeriesIndexes[k], p = this.data[n], q = p.dataPoints, 0 < q.length)
                        for (c.strokeStyle = "#4572A7 ", e = 0; e < q.length; e++)
                            if (f = q[e].getTime ? f = q[e].x.getTime() : f = q[e].x, !(f < a.axisX.dataInfo.viewPortMin ||
                                    f > a.axisX.dataInfo.viewPortMax) && "number" === typeof q[e].y) {
                                f = a.axisX.convertValueToPixel(f);
                                l = a.axisY.convertValueToPixel(q[e].y);
                                var g = q[e].z,
                                    s = 2 * Math.max(Math.sqrt((v === h ? m / 2 : r + (m - r) / (v - h) * (g - h)) / Math.PI) << 0, 1),
                                    g = p.getMarkerProperties(e, c);
                                g.size = s;
                                c.globalAlpha = p.fillOpacity;
                                V.drawMarker(f, l, c, g.type, g.size, g.color, g.borderColor, g.borderThickness);
                                c.globalAlpha = 1;
                                var t = p.dataPointIds[e];
                                this._eventManager.objectMap[t] = { id: t, objectType: "dataPoint", dataSeriesIndex: n, dataPointIndex: e, x1: f, y1: l, size: s };
                                s = P(t);
                                u && V.drawMarker(f, l, this._eventManager.ghostCtx, g.type, g.size, s, s, g.borderThickness);
                                (q[e].indexLabel || p.indexLabel || q[e].indexLabelFormatter || p.indexLabelFormatter) && this._indexLabels.push({ chartType: "bubble", dataPoint: q[e], dataSeries: p, point: { x: f, y: l }, direction: 1, bounds: { x1: f - g.size / 2, y1: l - g.size / 2, x2: f + g.size / 2, y2: l + g.size / 2 }, color: null })
                            }
                u && (d.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), c.globalCompositeOperation = "source-atop", a.axisX.maskCanvas && c.drawImage(a.axisX.maskCanvas,
                    0, 0, this.width, this.height), a.axisY.maskCanvas && c.drawImage(a.axisY.maskCanvas, 0, 0, this.width, this.height), this._breaksCanvasCtx && this._breaksCanvasCtx.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), c.clearRect(b.x1, b.y1, b.width, b.height), this._eventManager.ghostCtx.restore());
                c.restore();
                return { source: d, dest: this.plotArea.ctx, animationCallback: K.fadeInAnimation, easingFunction: K.easing.easeInQuad, animationBase: 0 }
            }
        };
        m.prototype.renderScatter = function(a) {
            var d = a.targetCanvasCtx || this.plotArea.ctx,
                c = u ? this._preRenderCtx : d;
            if (!(0 >= a.dataSeriesIndexes.length)) {
                var b = this.plotArea,
                    e = 0,
                    f, l;
                c.save();
                u && this._eventManager.ghostCtx.save();
                c.beginPath();
                c.rect(b.x1, b.y1, b.width, b.height);
                c.clip();
                u && (this._eventManager.ghostCtx.beginPath(), this._eventManager.ghostCtx.rect(b.x1, b.y1, b.width, b.height), this._eventManager.ghostCtx.clip());
                for (var v = 0; v < a.dataSeriesIndexes.length; v++) {
                    var h = a.dataSeriesIndexes[v],
                        k = this.data[h],
                        n = k.dataPoints;
                    if (0 < n.length) {
                        c.strokeStyle = "#4572A7 ";
                        Math.pow(0.3 * Math.min(b.height,
                            b.width) / 2, 2);
                        for (var p = 0, q = 0, e = 0; e < n.length; e++)
                            if (f = n[e].getTime ? f = n[e].x.getTime() : f = n[e].x, !(f < a.axisX.dataInfo.viewPortMin || f > a.axisX.dataInfo.viewPortMax) && "number" === typeof n[e].y) {
                                f = a.axisX.convertValueToPixel(f);
                                l = a.axisY.convertValueToPixel(n[e].y);
                                var g = k.getMarkerProperties(e, f, l, c);
                                c.globalAlpha = k.fillOpacity;
                                V.drawMarker(g.x, g.y, g.ctx, g.type, g.size, g.color, g.borderColor, g.borderThickness);
                                c.globalAlpha = 1;
                                Math.sqrt((p - f) * (p - f) + (q - l) * (q - l)) < Math.min(g.size, 5) && n.length > Math.min(this.plotArea.width,
                                    this.plotArea.height) || (p = k.dataPointIds[e], this._eventManager.objectMap[p] = { id: p, objectType: "dataPoint", dataSeriesIndex: h, dataPointIndex: e, x1: f, y1: l }, p = P(p), u && V.drawMarker(g.x, g.y, this._eventManager.ghostCtx, g.type, g.size, p, p, g.borderThickness), (n[e].indexLabel || k.indexLabel || n[e].indexLabelFormatter || k.indexLabelFormatter) && this._indexLabels.push({ chartType: "scatter", dataPoint: n[e], dataSeries: k, point: { x: f, y: l }, direction: 1, bounds: { x1: f - g.size / 2, y1: l - g.size / 2, x2: f + g.size / 2, y2: l + g.size / 2 }, color: null }),
                                    p = f, q = l)
                            }
                    }
                }
                u && (d.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), c.globalCompositeOperation = "source-atop", a.axisX.maskCanvas && c.drawImage(a.axisX.maskCanvas, 0, 0, this.width, this.height), a.axisY.maskCanvas && c.drawImage(a.axisY.maskCanvas, 0, 0, this.width, this.height), this._breaksCanvasCtx && this._breaksCanvasCtx.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), c.clearRect(b.x1, b.y1, b.width, b.height), this._eventManager.ghostCtx.restore());
                c.restore();
                return {
                    source: d,
                    dest: this.plotArea.ctx,
                    animationCallback: K.fadeInAnimation,
                    easingFunction: K.easing.easeInQuad,
                    animationBase: 0
                }
            }
        };
        m.prototype.renderCandlestick = function(a) {
            var d = a.targetCanvasCtx || this.plotArea.ctx,
                c = u ? this._preRenderCtx : d,
                b = this._eventManager.ghostCtx;
            if (!(0 >= a.dataSeriesIndexes.length)) {
                var e = null,
                    f = null,
                    l = this.plotArea,
                    v = 0,
                    h, k, n, p, q, g, e = this.options.dataPointMinWidth ? this.dataPointMinWidth : this.options.dataPointWidth ? this.dataPointWidth : 1,
                    f = this.options.dataPointMaxWidth ? this.dataPointMaxWidth : this.options.dataPointWidth ?
                    this.dataPointWidth : 0.015 * this.width,
                    r = a.axisX.dataInfo.minDiff;
                isFinite(r) || (r = 0.3 * Math.abs(a.axisX.range));
                r = this.options.dataPointWidth ? this.dataPointWidth : 0.7 * l.width * (a.axisX.logarithmic ? Math.log(r) / Math.log(a.axisX.range) : Math.abs(r) / Math.abs(a.axisX.range)) << 0;
                this.dataPointMaxWidth && e > f && (e = Math.min(this.options.dataPointWidth ? this.dataPointWidth : Infinity, f));
                !this.dataPointMaxWidth && (this.dataPointMinWidth && f < e) && (f = Math.max(this.options.dataPointWidth ? this.dataPointWidth : -Infinity, e));
                r <
                    e && (r = e);
                r > f && (r = f);
                c.save();
                u && b.save();
                c.beginPath();
                c.rect(l.x1, l.y1, l.width, l.height);
                c.clip();
                u && (b.beginPath(), b.rect(l.x1, l.y1, l.width, l.height), b.clip());
                for (var m = 0; m < a.dataSeriesIndexes.length; m++) {
                    var w = a.dataSeriesIndexes[m],
                        t = this.data[w],
                        x = t.dataPoints;
                    if (0 < x.length)
                        for (var E = 5 < r && t.bevelEnabled ? !0 : !1, v = 0; v < x.length; v++)
                            if (x[v].getTime ? g = x[v].x.getTime() : g = x[v].x, !(g < a.axisX.dataInfo.viewPortMin || g > a.axisX.dataInfo.viewPortMax) && !s(x[v].y) && x[v].y.length && "number" === typeof x[v].y[0] &&
                                "number" === typeof x[v].y[1] && "number" === typeof x[v].y[2] && "number" === typeof x[v].y[3]) {
                                h = a.axisX.convertValueToPixel(g);
                                k = a.axisY.convertValueToPixel(x[v].y[0]);
                                n = a.axisY.convertValueToPixel(x[v].y[1]);
                                p = a.axisY.convertValueToPixel(x[v].y[2]);
                                q = a.axisY.convertValueToPixel(x[v].y[3]);
                                var B = h - r / 2 << 0,
                                    C = B + r << 0,
                                    f = t.options.fallingColor ? t.fallingColor : t._colorSet[0],
                                    e = x[v].color ? x[v].color : t._colorSet[0],
                                    z = Math.round(Math.max(1, 0.15 * r)),
                                    y = 0 === z % 2 ? 0 : 0.5,
                                    D = t.dataPointIds[v];
                                this._eventManager.objectMap[D] = { id: D, objectType: "dataPoint", dataSeriesIndex: w, dataPointIndex: v, x1: B, y1: k, x2: C, y2: n, x3: h, y3: p, x4: h, y4: q, borderThickness: z, color: e };
                                c.strokeStyle = e;
                                c.beginPath();
                                c.lineWidth = z;
                                b.lineWidth = Math.max(z, 4);
                                "candlestick" === t.type ? (c.moveTo(h - y, n), c.lineTo(h - y, Math.min(k, q)), c.stroke(), c.moveTo(h - y, Math.max(k, q)), c.lineTo(h - y, p), c.stroke(), ca(c, B, Math.min(k, q), C, Math.max(k, q), x[v].y[0] <= x[v].y[3] ? t.risingColor : f, z, e, E, E, !1, !1, t.fillOpacity), u && (e = P(D), b.strokeStyle = e, b.moveTo(h - y, n), b.lineTo(h - y, Math.min(k,
                                    q)), b.stroke(), b.moveTo(h - y, Math.max(k, q)), b.lineTo(h - y, p), b.stroke(), ca(b, B, Math.min(k, q), C, Math.max(k, q), e, 0, null, !1, !1, !1, !1))) : "ohlc" === t.type && (c.moveTo(h - y, n), c.lineTo(h - y, p), c.stroke(), c.beginPath(), c.moveTo(h, k), c.lineTo(B, k), c.stroke(), c.beginPath(), c.moveTo(h, q), c.lineTo(C, q), c.stroke(), u && (e = P(D), b.strokeStyle = e, b.moveTo(h - y, n), b.lineTo(h - y, p), b.stroke(), b.beginPath(), b.moveTo(h, k), b.lineTo(B, k), b.stroke(), b.beginPath(), b.moveTo(h, q), b.lineTo(C, q), b.stroke()));
                                (x[v].indexLabel || t.indexLabel ||
                                    x[v].indexLabelFormatter || t.indexLabelFormatter) && this._indexLabels.push({ chartType: t.type, dataPoint: x[v], dataSeries: t, point: { x: B + (C - B) / 2, y: a.axisY.reversed ? p : n }, direction: 1, bounds: { x1: B, y1: Math.min(n, p), x2: C, y2: Math.max(n, p) }, color: e })
                            }
                }
                u && (d.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), c.globalCompositeOperation = "source-atop", a.axisX.maskCanvas && c.drawImage(a.axisX.maskCanvas, 0, 0, this.width, this.height), a.axisY.maskCanvas && c.drawImage(a.axisY.maskCanvas, 0, 0, this.width, this.height),
                    this._breaksCanvasCtx && this._breaksCanvasCtx.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), c.clearRect(l.x1, l.y1, l.width, l.height), b.restore());
                c.restore();
                return { source: d, dest: this.plotArea.ctx, animationCallback: K.fadeInAnimation, easingFunction: K.easing.easeInQuad, animationBase: 0 }
            }
        };
        m.prototype.renderBoxAndWhisker = function(a) {
            var d = a.targetCanvasCtx || this.plotArea.ctx,
                c = u ? this._preRenderCtx : d,
                b = this._eventManager.ghostCtx;
            if (!(0 >= a.dataSeriesIndexes.length)) {
                var e = null,
                    f = this.plotArea,
                    l = 0,
                    v, h, k, n, p, q, g, e = this.options.dataPointMinWidth ? this.dataPointMinWidth : this.options.dataPointWidth ? this.dataPointWidth : 1,
                    l = this.options.dataPointMaxWidth ? this.dataPointMaxWidth : this.options.dataPointWidth ? this.dataPointWidth : 0.015 * this.width,
                    r = a.axisX.dataInfo.minDiff;
                isFinite(r) || (r = 0.3 * Math.abs(a.axisX.range));
                r = this.options.dataPointWidth ? this.dataPointWidth : 0.7 * f.width * (a.axisX.logarithmic ? Math.log(r) / Math.log(a.axisX.range) : Math.abs(r) / Math.abs(a.axisX.range)) << 0;
                this.dataPointMaxWidth && e >
                    l && (e = Math.min(this.options.dataPointWidth ? this.dataPointWidth : Infinity, l));
                !this.dataPointMaxWidth && (this.dataPointMinWidth && l < e) && (l = Math.max(this.options.dataPointWidth ? this.dataPointWidth : -Infinity, e));
                r < e && (r = e);
                r > l && (r = l);
                c.save();
                u && b.save();
                c.beginPath();
                c.rect(f.x1, f.y1, f.width, f.height);
                c.clip();
                u && (b.beginPath(), b.rect(f.x1, f.y1, f.width, f.height), b.clip());
                for (var m = !1, m = !!a.axisY.reversed, w = 0; w < a.dataSeriesIndexes.length; w++) {
                    var t = a.dataSeriesIndexes[w],
                        x = this.data[t],
                        E = x.dataPoints;
                    if (0 < E.length)
                        for (var B = 5 < r && x.bevelEnabled ? !0 : !1, l = 0; l < E.length; l++)
                            if (E[l].getTime ? g = E[l].x.getTime() : g = E[l].x, !(g < a.axisX.dataInfo.viewPortMin || g > a.axisX.dataInfo.viewPortMax) && !s(E[l].y) && E[l].y.length && "number" === typeof E[l].y[0] && "number" === typeof E[l].y[1] && "number" === typeof E[l].y[2] && "number" === typeof E[l].y[3] && "number" === typeof E[l].y[4] && 5 === E[l].y.length) {
                                v = a.axisX.convertValueToPixel(g);
                                h = a.axisY.convertValueToPixel(E[l].y[0]);
                                k = a.axisY.convertValueToPixel(E[l].y[1]);
                                n = a.axisY.convertValueToPixel(E[l].y[2]);
                                p = a.axisY.convertValueToPixel(E[l].y[3]);
                                q = a.axisY.convertValueToPixel(E[l].y[4]);
                                var C = v - r / 2 << 0,
                                    z = v + r / 2 << 0,
                                    e = E[l].color ? E[l].color : x._colorSet[0],
                                    y = Math.round(Math.max(1, 0.15 * r)),
                                    D = 0 === y % 2 ? 0 : 0.5,
                                    Q = E[l].whiskerColor ? E[l].whiskerColor : E[l].color ? x.whiskerColor ? x.whiskerColor : E[l].color : x.whiskerColor ? x.whiskerColor : e,
                                    aa = "number" === typeof E[l].whiskerThickness ? E[l].whiskerThickness : "number" === typeof x.options.whiskerThickness ? x.whiskerThickness : y,
                                    F = E[l].whiskerDashType ? E[l].whiskerDashType : x.whiskerDashType,
                                    Y = s(E[l].whiskerLength) ? s(x.options.whiskerLength) ? r : x.whiskerLength : E[l].whiskerLength,
                                    Y = "number" === typeof Y ? 0 >= Y ? 0 : Y >= r ? r : Y : "string" === typeof Y ? parseInt(Y) * r / 100 > r ? r : parseInt(Y) * r / 100 : r,
                                    fa = 1 === Math.round(aa) % 2 ? 0.5 : 0,
                                    ma = E[l].stemColor ? E[l].stemColor : E[l].color ? x.stemColor ? x.stemColor : E[l].color : x.stemColor ? x.stemColor : e,
                                    ba = "number" === typeof E[l].stemThickness ? E[l].stemThickness : "number" === typeof x.options.stemThickness ? x.stemThickness : y,
                                    G = 1 === Math.round(ba) % 2 ? 0.5 : 0,
                                    J = E[l].stemDashType ? E[l].stemDashType :
                                    x.stemDashType,
                                    H = E[l].lineColor ? E[l].lineColor : E[l].color ? x.lineColor ? x.lineColor : E[l].color : x.lineColor ? x.lineColor : e,
                                    M = "number" === typeof E[l].lineThickness ? E[l].lineThickness : "number" === typeof x.options.lineThickness ? x.lineThickness : y,
                                    S = E[l].lineDashType ? E[l].lineDashType : x.lineDashType,
                                    L = 1 === Math.round(M) % 2 ? 0.5 : 0,
                                    R = x.upperBoxColor,
                                    xa = x.lowerBoxColor,
                                    sa = s(x.options.fillOpacity) ? 1 : x.fillOpacity,
                                    O = x.dataPointIds[l];
                                this._eventManager.objectMap[O] = {
                                    id: O,
                                    objectType: "dataPoint",
                                    dataSeriesIndex: t,
                                    dataPointIndex: l,
                                    x1: C,
                                    y1: h,
                                    x2: z,
                                    y2: k,
                                    x3: v,
                                    y3: n,
                                    x4: v,
                                    y4: p,
                                    y5: q,
                                    borderThickness: y,
                                    color: e,
                                    stemThickness: ba,
                                    stemColor: ma,
                                    whiskerThickness: aa,
                                    whiskerLength: Y,
                                    whiskerColor: Q,
                                    lineThickness: M,
                                    lineColor: H
                                };
                                c.save();
                                0 < ba && (c.beginPath(), c.strokeStyle = ma, c.lineWidth = ba, c.setLineDash && c.setLineDash(N(J, ba)), c.moveTo(v - G, k), c.lineTo(v - G, h), c.stroke(), c.moveTo(v - G, p), c.lineTo(v - G, n), c.stroke());
                                c.restore();
                                b.lineWidth = Math.max(y, 4);
                                c.beginPath();
                                ca(c, C, Math.min(q, k), z, Math.max(k, q), xa, 0, e, m ? B : !1, m ? !1 : B, !1, !1, sa);
                                c.beginPath();
                                ca(c, C, Math.min(n, q), z, Math.max(q, n), R, 0, e, m ? !1 : B, m ? B : !1, !1, !1, sa);
                                c.beginPath();
                                c.lineWidth = y;
                                c.strokeStyle = e;
                                c.rect(C - D, Math.min(k, n) - D, z - C + 2 * D, Math.max(k, n) - Math.min(k, n) + 2 * D);
                                c.stroke();
                                c.save();
                                0 < M && (c.beginPath(), c.globalAlpha = 1, c.setLineDash && c.setLineDash(N(S, M)), c.strokeStyle = H, c.lineWidth = M, c.moveTo(C, q - L), c.lineTo(z, q - L), c.stroke());
                                c.restore();
                                c.save();
                                0 < aa && (c.beginPath(), c.setLineDash && c.setLineDash(N(F, aa)), c.strokeStyle = Q, c.lineWidth = aa, c.moveTo(v - Y / 2 << 0, p - fa), c.lineTo(v + Y / 2 << 0, p -
                                    fa), c.stroke(), c.moveTo(v - Y / 2 << 0, h + fa), c.lineTo(v + Y / 2 << 0, h + fa), c.stroke());
                                c.restore();
                                u && (e = P(O), b.strokeStyle = e, b.lineWidth = ba, 0 < ba && (b.moveTo(v - D - G, k), b.lineTo(v - D - G, Math.max(h, p)), b.stroke(), b.moveTo(v - D - G, Math.min(h, p)), b.lineTo(v - D - G, n), b.stroke()), ca(b, C, Math.max(k, n), z, Math.min(k, n), e, 0, null, !1, !1, !1, !1), 0 < aa && (b.beginPath(), b.lineWidth = aa, b.moveTo(v + Y / 2, p - fa), b.lineTo(v - Y / 2, p - fa), b.stroke(), b.moveTo(v + Y / 2, h + fa), b.lineTo(v - Y / 2, h + fa), b.stroke()));
                                (E[l].indexLabel || x.indexLabel || E[l].indexLabelFormatter ||
                                    x.indexLabelFormatter) && this._indexLabels.push({ chartType: x.type, dataPoint: E[l], dataSeries: x, point: { x: C + (z - C) / 2, y: a.axisY.reversed ? h : p }, direction: 1, bounds: { x1: C, y1: Math.min(h, p), x2: z, y2: Math.max(h, p) }, color: e })
                            }
                }
                u && (d.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), c.globalCompositeOperation = "source-atop", a.axisX.maskCanvas && c.drawImage(a.axisX.maskCanvas, 0, 0, this.width, this.height), a.axisY.maskCanvas && c.drawImage(a.axisY.maskCanvas, 0, 0, this.width, this.height), this._breaksCanvasCtx && this._breaksCanvasCtx.drawImage(this._preRenderCanvas,
                    0, 0, this.width, this.height), c.clearRect(f.x1, f.y1, f.width, f.height), b.restore());
                c.restore();
                return { source: d, dest: this.plotArea.ctx, animationCallback: K.fadeInAnimation, easingFunction: K.easing.easeInQuad, animationBase: 0 }
            }
        };
        m.prototype.renderRangeColumn = function(a) {
            var d = a.targetCanvasCtx || this.plotArea.ctx,
                c = u ? this._preRenderCtx : d;
            if (!(0 >= a.dataSeriesIndexes.length)) {
                var b = null,
                    e = this.plotArea,
                    f = 0,
                    l, v, h, f = this.options.dataPointMinWidth ? this.dataPointMinWidth : this.options.dataPointWidth ? this.dataPointWidth :
                    1;
                l = this.options.dataPointMaxWidth ? this.dataPointMaxWidth : this.options.dataPointWidth ? this.dataPointWidth : 0.03 * this.width;
                var k = a.axisX.dataInfo.minDiff;
                isFinite(k) || (k = 0.3 * Math.abs(a.axisX.range));
                k = this.options.dataPointWidth ? this.dataPointWidth : 0.9 * (e.width * (a.axisX.logarithmic ? Math.log(k) / Math.log(a.axisX.range) : Math.abs(k) / Math.abs(a.axisX.range)) / a.plotType.totalDataSeries) << 0;
                this.dataPointMaxWidth && f > l && (f = Math.min(this.options.dataPointWidth ? this.dataPointWidth : Infinity, l));
                !this.dataPointMaxWidth &&
                    (this.dataPointMinWidth && l < f) && (l = Math.max(this.options.dataPointWidth ? this.dataPointWidth : -Infinity, f));
                k < f && (k = f);
                k > l && (k = l);
                c.save();
                u && this._eventManager.ghostCtx.save();
                c.beginPath();
                c.rect(e.x1, e.y1, e.width, e.height);
                c.clip();
                u && (this._eventManager.ghostCtx.beginPath(), this._eventManager.ghostCtx.rect(e.x1, e.y1, e.width, e.height), this._eventManager.ghostCtx.clip());
                for (var n = 0; n < a.dataSeriesIndexes.length; n++) {
                    var p = a.dataSeriesIndexes[n],
                        q = this.data[p],
                        g = q.dataPoints;
                    if (0 < g.length)
                        for (var r =
                                5 < k && q.bevelEnabled ? !0 : !1, f = 0; f < g.length; f++)
                            if (g[f].getTime ? h = g[f].x.getTime() : h = g[f].x, !(h < a.axisX.dataInfo.viewPortMin || h > a.axisX.dataInfo.viewPortMax) && !s(g[f].y) && g[f].y.length && "number" === typeof g[f].y[0] && "number" === typeof g[f].y[1]) {
                                b = a.axisX.convertValueToPixel(h);
                                l = a.axisY.convertValueToPixel(g[f].y[0]);
                                v = a.axisY.convertValueToPixel(g[f].y[1]);
                                var m = a.axisX.reversed ? b + a.plotType.totalDataSeries * k / 2 - (a.previousDataSeriesCount + n) * k << 0 : b - a.plotType.totalDataSeries * k / 2 + (a.previousDataSeriesCount +
                                        n) * k << 0,
                                    w = a.axisX.reversed ? m - k << 0 : m + k << 0,
                                    b = g[f].color ? g[f].color : q._colorSet[f % q._colorSet.length];
                                if (l > v) {
                                    var t = l;
                                    l = v;
                                    v = t
                                }
                                t = q.dataPointIds[f];
                                this._eventManager.objectMap[t] = { id: t, objectType: "dataPoint", dataSeriesIndex: p, dataPointIndex: f, x1: m, y1: l, x2: w, y2: v };
                                ca(c, m, l, w, v, b, 0, b, r, r, !1, !1, q.fillOpacity);
                                b = P(t);
                                u && ca(this._eventManager.ghostCtx, m, l, w, v, b, 0, null, !1, !1, !1, !1);
                                if (g[f].indexLabel || q.indexLabel || g[f].indexLabelFormatter || q.indexLabelFormatter) this._indexLabels.push({
                                    chartType: "rangeColumn",
                                    dataPoint: g[f],
                                    dataSeries: q,
                                    indexKeyword: 0,
                                    point: { x: m + (w - m) / 2, y: g[f].y[1] >= g[f].y[0] ? v : l },
                                    direction: g[f].y[1] >= g[f].y[0] ? -1 : 1,
                                    bounds: { x1: m, y1: Math.min(l, v), x2: w, y2: Math.max(l, v) },
                                    color: b
                                }), this._indexLabels.push({ chartType: "rangeColumn", dataPoint: g[f], dataSeries: q, indexKeyword: 1, point: { x: m + (w - m) / 2, y: g[f].y[1] >= g[f].y[0] ? l : v }, direction: g[f].y[1] >= g[f].y[0] ? 1 : -1, bounds: { x1: m, y1: Math.min(l, v), x2: w, y2: Math.max(l, v) }, color: b })
                            }
                }
                u && (d.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), c.globalCompositeOperation =
                    "source-atop", a.axisX.maskCanvas && c.drawImage(a.axisX.maskCanvas, 0, 0, this.width, this.height), a.axisY.maskCanvas && c.drawImage(a.axisY.maskCanvas, 0, 0, this.width, this.height), this._breaksCanvasCtx && this._breaksCanvasCtx.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), c.clearRect(e.x1, e.y1, e.width, e.height), this._eventManager.ghostCtx.restore());
                c.restore();
                return { source: d, dest: this.plotArea.ctx, animationCallback: K.fadeInAnimation, easingFunction: K.easing.easeInQuad, animationBase: 0 }
            }
        };
        m.prototype.renderError =
            function(a) {
                var d = a.targetCanvasCtx || this.plotArea.ctx,
                    c = u ? this._preRenderCtx : d,
                    b = a.axisY._position ? "left" === a.axisY._position || "right" === a.axisY._position ? !1 : !0 : !1;
                if (!(0 >= a.dataSeriesIndexes.length)) {
                    var e = null,
                        f = !1,
                        l = this.plotArea,
                        v = 0,
                        h, k, n, p, q, g, r, m = a.axisX.dataInfo.minDiff;
                    isFinite(m) || (m = 0.3 * Math.abs(a.axisX.range));
                    c.save();
                    u && this._eventManager.ghostCtx.save();
                    c.beginPath();
                    c.rect(l.x1, l.y1, l.width, l.height);
                    c.clip();
                    u && (this._eventManager.ghostCtx.beginPath(), this._eventManager.ghostCtx.rect(l.x1,
                        l.y1, l.width, l.height), this._eventManager.ghostCtx.clip());
                    for (var w = 0, t = 0; t < this.data.length; t++) !this.data[t].type.match(/(bar|column)/ig) || !this.data[t].visible || this.data[t].type.match(/(stacked)/ig) && w || w++;
                    for (var x = 0; x < a.dataSeriesIndexes.length; x++) {
                        var E = a.dataSeriesIndexes[x],
                            B = this.data[E],
                            C = B.dataPoints,
                            z = s(B._linkedSeries) ? !1 : B._linkedSeries.type.match(/(bar|column)/ig) && B._linkedSeries.visible ? !0 : !1,
                            D = 0;
                        if (z)
                            for (e = B._linkedSeries.id, t = 0; t < e; t++) !this.data[t].type.match(/(bar|column)/ig) ||
                                !this.data[t].visible || this.data[t].type.match(/(stacked)/ig) && D || (this.data[t].type.match(/(range)/ig) && (f = !0), D++);
                        e = this.options.dataPointMinWidth ? this.dataPointMinWidth : this.options.dataPointWidth ? this.dataPointWidth : 1;
                        v = this.options.dataPointMaxWidth ? this.dataPointMaxWidth : this.options.dataPointWidth ? this.dataPointWidth : b ? Math.min(0.15 * this.height, 0.9 * (this.plotArea.height / (z ? w : 1))) << 0 : 0.3 * this.width;
                        f && (v = this.options.dataPointMaxWidth ? this.dataPointMaxWidth : this.options.dataPointWidth ? this.dataPointWidth :
                            b ? Math.min(0.15 * this.height, 0.9 * (this.plotArea.height / (z ? w : 1))) << 0 : 0.03 * this.width);
                        t = this.options.dataPointWidth ? this.dataPointWidth : 0.9 * ((b ? l.height : l.width) * (a.axisX.logarithmic ? Math.log(m) / Math.log(a.axisX.range) : Math.abs(m) / Math.abs(a.axisX.range)) / (z ? w : 1)) << 0;
                        this.dataPointMaxWidth && e > v && (e = Math.min(this.options.dataPointWidth ? this.dataPointWidth : Infinity, v));
                        !this.dataPointMaxWidth && (this.dataPointMinWidth && v < e) && (v = Math.max(this.options.dataPointWidth ? this.dataPointWidth : -Infinity, e));
                        t < e &&
                            (t = e);
                        t > v && (t = v);
                        if (0 < C.length)
                            for (var F = B._colorSet, v = 0; v < C.length; v++) {
                                var e = B.lineColor = B.options.color ? B.options.color : F[0],
                                    Q = {
                                        color: C[v].whiskerColor ? C[v].whiskerColor : C[v].color ? B.whiskerColor ? B.whiskerColor : C[v].color : B.whiskerColor ? B.whiskerColor : e,
                                        thickness: s(C[v].whiskerThickness) ? B.whiskerThickness : C[v].whiskerThickness,
                                        dashType: C[v].whiskerDashType ? C[v].whiskerDashType : B.whiskerDashType,
                                        length: s(C[v].whiskerLength) ? s(B.options.whiskerLength) ? t : B.options.whiskerLength : C[v].whiskerLength,
                                        trimLength: s(C[v].whiskerLength) ? s(B.options.whiskerLength) ? 50 : 0 : 0
                                    };
                                Q.length = "number" === typeof Q.length ? 0 >= Q.length ? 0 : Q.length >= t ? t : Q.length : "string" === typeof Q.length ? parseInt(Q.length) * t / 100 > t ? t : parseInt(Q.length) * t / 100 > t : t;
                                Q.thickness = "number" === typeof Q.thickness ? 0 > Q.thickness ? 0 : Math.round(Q.thickness) : 2;
                                var aa = {
                                    color: C[v].stemColor ? C[v].stemColor : C[v].color ? B.stemColor ? B.stemColor : C[v].color : B.stemColor ? B.stemColor : e,
                                    thickness: C[v].stemThickness ? C[v].stemThickness : B.stemThickness,
                                    dashType: C[v].stemDashType ?
                                        C[v].stemDashType : B.stemDashType
                                };
                                aa.thickness = "number" === typeof aa.thickness ? 0 > aa.thickness ? 0 : Math.round(aa.thickness) : 2;
                                C[v].getTime ? r = C[v].x.getTime() : r = C[v].x;
                                if (!(r < a.axisX.dataInfo.viewPortMin || r > a.axisX.dataInfo.viewPortMax) && !s(C[v].y) && C[v].y.length && "number" === typeof C[v].y[0] && "number" === typeof C[v].y[1]) {
                                    var ja = a.axisX.convertValueToPixel(r);
                                    b ? k = ja : h = ja;
                                    ja = a.axisY.convertValueToPixel(C[v].y[0]);
                                    b ? n = ja : q = ja;
                                    ja = a.axisY.convertValueToPixel(C[v].y[1]);
                                    b ? p = ja : g = ja;
                                    b ? (q = a.axisX.reversed ? k + (z ?
                                        w : 1) * t / 2 - (z ? D - 1 : 0) * t << 0 : k - (z ? w : 1) * t / 2 + (z ? D - 1 : 0) * t << 0, g = a.axisX.reversed ? q - t << 0 : q + t << 0) : (n = a.axisX.reversed ? h + (z ? w : 1) * t / 2 - (z ? D - 1 : 0) * t << 0 : h - (z ? w : 1) * t / 2 + (z ? D - 1 : 0) * t << 0, p = a.axisX.reversed ? n - t << 0 : n + t << 0);
                                    !b && q > g && (ja = q, q = g, g = ja);
                                    b && n > p && (ja = n, n = p, p = ja);
                                    ja = B.dataPointIds[v];
                                    this._eventManager.objectMap[ja] = { id: ja, objectType: "dataPoint", dataSeriesIndex: E, dataPointIndex: v, x1: Math.min(n, p), y1: Math.min(q, g), x2: Math.max(p, n), y2: Math.max(g, q), isXYSwapped: b, stemProperties: aa, whiskerProperties: Q };
                                    y(c, Math.min(n,
                                        p), Math.min(q, g), Math.max(p, n), Math.max(g, q), e, Q, aa, b);
                                    u && y(this._eventManager.ghostCtx, n, q, p, g, e, Q, aa, b);
                                    if (C[v].indexLabel || B.indexLabel || C[v].indexLabelFormatter || B.indexLabelFormatter) this._indexLabels.push({ chartType: "error", dataPoint: C[v], dataSeries: B, indexKeyword: 0, point: { x: b ? C[v].y[1] >= C[v].y[0] ? n : p : n + (p - n) / 2, y: b ? q + (g - q) / 2 : C[v].y[1] >= C[v].y[0] ? g : q }, direction: C[v].y[1] >= C[v].y[0] ? -1 : 1, bounds: { x1: b ? Math.min(n, p) : n, y1: b ? q : Math.min(q, g), x2: b ? Math.max(n, p) : p, y2: b ? g : Math.max(q, g) }, color: e, axisSwapped: b }),
                                        this._indexLabels.push({ chartType: "error", dataPoint: C[v], dataSeries: B, indexKeyword: 1, point: { x: b ? C[v].y[1] >= C[v].y[0] ? p : n : n + (p - n) / 2, y: b ? q + (g - q) / 2 : C[v].y[1] >= C[v].y[0] ? q : g }, direction: C[v].y[1] >= C[v].y[0] ? 1 : -1, bounds: { x1: b ? Math.min(n, p) : n, y1: b ? q : Math.min(q, g), x2: b ? Math.max(n, p) : p, y2: b ? g : Math.max(q, g) }, color: e, axisSwapped: b })
                                }
                            }
                    }
                    u && (d.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), c.globalCompositeOperation = "source-atop", a.axisX.maskCanvas && c.drawImage(a.axisX.maskCanvas, 0, 0, this.width, this.height),
                        a.axisY.maskCanvas && c.drawImage(a.axisY.maskCanvas, 0, 0, this.width, this.height), this._breaksCanvasCtx && this._breaksCanvasCtx.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), c.clearRect(l.x1, l.y1, l.width, l.height), this._eventManager.ghostCtx.restore());
                    c.restore();
                    return { source: d, dest: this.plotArea.ctx, animationCallback: K.fadeInAnimation, easingFunction: K.easing.easeInQuad, animationBase: 0 }
                }
            };
        m.prototype.renderRangeBar = function(a) {
            var d = a.targetCanvasCtx || this.plotArea.ctx,
                c = u ? this._preRenderCtx :
                d;
            if (!(0 >= a.dataSeriesIndexes.length)) {
                var b = null,
                    e = this.plotArea,
                    f = 0,
                    l, v, h, k, f = this.options.dataPointMinWidth ? this.dataPointMinWidth : this.options.dataPointWidth ? this.dataPointWidth : 1;
                l = this.options.dataPointMaxWidth ? this.dataPointMaxWidth : this.options.dataPointWidth ? this.dataPointWidth : Math.min(0.15 * this.height, 0.9 * (this.plotArea.height / a.plotType.totalDataSeries)) << 0;
                var n = a.axisX.dataInfo.minDiff;
                isFinite(n) || (n = 0.3 * Math.abs(a.axisX.range));
                n = this.options.dataPointWidth ? this.dataPointWidth : 0.9 *
                    (e.height * (a.axisX.logarithmic ? Math.log(n) / Math.log(a.axisX.range) : Math.abs(n) / Math.abs(a.axisX.range)) / a.plotType.totalDataSeries) << 0;
                this.dataPointMaxWidth && f > l && (f = Math.min(this.options.dataPointWidth ? this.dataPointWidth : Infinity, l));
                !this.dataPointMaxWidth && (this.dataPointMinWidth && l < f) && (l = Math.max(this.options.dataPointWidth ? this.dataPointWidth : -Infinity, f));
                n < f && (n = f);
                n > l && (n = l);
                c.save();
                u && this._eventManager.ghostCtx.save();
                c.beginPath();
                c.rect(e.x1, e.y1, e.width, e.height);
                c.clip();
                u && (this._eventManager.ghostCtx.beginPath(),
                    this._eventManager.ghostCtx.rect(e.x1, e.y1, e.width, e.height), this._eventManager.ghostCtx.clip());
                for (var p = 0; p < a.dataSeriesIndexes.length; p++) {
                    var q = a.dataSeriesIndexes[p],
                        g = this.data[q],
                        r = g.dataPoints;
                    if (0 < r.length) {
                        var m = 5 < n && g.bevelEnabled ? !0 : !1;
                        c.strokeStyle = "#4572A7 ";
                        for (f = 0; f < r.length; f++)
                            if (r[f].getTime ? k = r[f].x.getTime() : k = r[f].x, !(k < a.axisX.dataInfo.viewPortMin || k > a.axisX.dataInfo.viewPortMax) && !s(r[f].y) && r[f].y.length && "number" === typeof r[f].y[0] && "number" === typeof r[f].y[1]) {
                                l = a.axisY.convertValueToPixel(r[f].y[0]);
                                v = a.axisY.convertValueToPixel(r[f].y[1]);
                                h = a.axisX.convertValueToPixel(k);
                                h = a.axisX.reversed ? h + a.plotType.totalDataSeries * n / 2 - (a.previousDataSeriesCount + p) * n << 0 : h - a.plotType.totalDataSeries * n / 2 + (a.previousDataSeriesCount + p) * n << 0;
                                var w = a.axisX.reversed ? h - n << 0 : h + n << 0;
                                l > v && (b = l, l = v, v = b);
                                b = r[f].color ? r[f].color : g._colorSet[f % g._colorSet.length];
                                ca(c, l, h, v, w, b, 0, null, m, !1, !1, !1, g.fillOpacity);
                                b = g.dataPointIds[f];
                                this._eventManager.objectMap[b] = {
                                    id: b,
                                    objectType: "dataPoint",
                                    dataSeriesIndex: q,
                                    dataPointIndex: f,
                                    x1: l,
                                    y1: h,
                                    x2: v,
                                    y2: w
                                };
                                b = P(b);
                                u && ca(this._eventManager.ghostCtx, l, h, v, w, b, 0, null, !1, !1, !1, !1);
                                if (r[f].indexLabel || g.indexLabel || r[f].indexLabelFormatter || g.indexLabelFormatter) this._indexLabels.push({ chartType: "rangeBar", dataPoint: r[f], dataSeries: g, indexKeyword: 0, point: { x: r[f].y[1] >= r[f].y[0] ? l : v, y: h + (w - h) / 2 }, direction: r[f].y[1] >= r[f].y[0] ? -1 : 1, bounds: { x1: Math.min(l, v), y1: h, x2: Math.max(l, v), y2: w }, color: b }), this._indexLabels.push({
                                    chartType: "rangeBar",
                                    dataPoint: r[f],
                                    dataSeries: g,
                                    indexKeyword: 1,
                                    point: {
                                        x: r[f].y[1] >=
                                            r[f].y[0] ? v : l,
                                        y: h + (w - h) / 2
                                    },
                                    direction: r[f].y[1] >= r[f].y[0] ? 1 : -1,
                                    bounds: { x1: Math.min(l, v), y1: h, x2: Math.max(l, v), y2: w },
                                    color: b
                                })
                            }
                    }
                }
                u && (d.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), c.globalCompositeOperation = "source-atop", a.axisX.maskCanvas && c.drawImage(a.axisX.maskCanvas, 0, 0, this.width, this.height), a.axisY.maskCanvas && c.drawImage(a.axisY.maskCanvas, 0, 0, this.width, this.height), this._breaksCanvasCtx && this._breaksCanvasCtx.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), c.clearRect(e.x1,
                    e.y1, e.width, e.height), this._eventManager.ghostCtx.restore());
                c.restore();
                return { source: d, dest: this.plotArea.ctx, animationCallback: K.fadeInAnimation, easingFunction: K.easing.easeInQuad, animationBase: 0 }
            }
        };
        m.prototype.renderRangeArea = function(a) {
            function d() {
                if (x) {
                    for (var a = null, c = m.length - 1; 0 <= c; c--) a = m[c], b.lineTo(a.x, a.y2), e.lineTo(a.x, a.y2);
                    b.closePath();
                    b.globalAlpha = n.fillOpacity;
                    b.fill();
                    b.globalAlpha = 1;
                    e.fill();
                    if (0 < n.lineThickness) {
                        b.beginPath();
                        b.moveTo(a.x, a.y2);
                        for (c = 0; c < m.length; c++) a = m[c],
                            b.lineTo(a.x, a.y2);
                        b.moveTo(m[0].x, m[0].y1);
                        for (c = 0; c < m.length; c++) a = m[c], b.lineTo(a.x, a.y1);
                        b.stroke()
                    }
                    b.beginPath();
                    b.moveTo(r, s);
                    e.beginPath();
                    e.moveTo(r, s);
                    x = { x: r, y: s };
                    m = [];
                    m.push({ x: r, y1: s, y2: w })
                }
            }
            var c = a.targetCanvasCtx || this.plotArea.ctx,
                b = u ? this._preRenderCtx : c;
            if (!(0 >= a.dataSeriesIndexes.length)) {
                var e = this._eventManager.ghostCtx,
                    f = [],
                    l = this.plotArea;
                b.save();
                u && e.save();
                b.beginPath();
                b.rect(l.x1, l.y1, l.width, l.height);
                b.clip();
                u && (e.beginPath(), e.rect(l.x1, l.y1, l.width, l.height), e.clip());
                for (var h = 0; h < a.dataSeriesIndexes.length; h++) {
                    var m = [],
                        k = a.dataSeriesIndexes[h],
                        n = this.data[k],
                        p = n.dataPoints,
                        f = n.id;
                    this._eventManager.objectMap[f] = { objectType: "dataSeries", dataSeriesIndex: k };
                    f = P(f);
                    e.fillStyle = f;
                    var f = [],
                        q = !0,
                        g = 0,
                        r, s, w, t, x = null;
                    if (0 < p.length) {
                        var E = n._colorSet[g % n._colorSet.length],
                            B = n.lineColor = n.options.lineColor || E,
                            C = B;
                        b.fillStyle = E;
                        b.strokeStyle = B;
                        b.lineWidth = n.lineThickness;
                        var z = "solid";
                        if (b.setLineDash) {
                            var y = N(n.nullDataLineDashType, n.lineThickness),
                                z = n.lineDashType,
                                D = N(z,
                                    n.lineThickness);
                            b.setLineDash(D)
                        }
                        for (var Q = !0; g < p.length; g++)
                            if (t = p[g].x.getTime ? p[g].x.getTime() : p[g].x, !(t < a.axisX.dataInfo.viewPortMin || t > a.axisX.dataInfo.viewPortMax && (!n.connectNullData || !Q)))
                                if (null !== p[g].y && p[g].y.length && "number" === typeof p[g].y[0] && "number" === typeof p[g].y[1]) {
                                    r = a.axisX.convertValueToPixel(t);
                                    s = a.axisY.convertValueToPixel(p[g].y[0]);
                                    w = a.axisY.convertValueToPixel(p[g].y[1]);
                                    q || Q ? (n.connectNullData && !q ? (b.setLineDash && (n.options.nullDataLineDashType || z === n.lineDashType &&
                                        n.lineDashType !== n.nullDataLineDashType) && (m[m.length - 1].newLineDashArray = D, z = n.nullDataLineDashType, b.setLineDash(y)), b.lineTo(r, s), u && e.lineTo(r, s), m.push({ x: r, y1: s, y2: w })) : (b.beginPath(), b.moveTo(r, s), x = { x: r, y: s }, m = [], m.push({ x: r, y1: s, y2: w }), u && (e.beginPath(), e.moveTo(r, s))), Q = q = !1) : (b.lineTo(r, s), m.push({ x: r, y1: s, y2: w }), u && e.lineTo(r, s), 0 == g % 250 && d());
                                    t = n.dataPointIds[g];
                                    this._eventManager.objectMap[t] = { id: t, objectType: "dataPoint", dataSeriesIndex: k, dataPointIndex: g, x1: r, y1: s, y2: w };
                                    g < p.length -
                                        1 && (C !== (p[g].lineColor || B) || z !== (p[g].lineDashType || n.lineDashType)) && (d(), C = p[g].lineColor || B, m[m.length - 1].newStrokeStyle = C, b.strokeStyle = C, b.setLineDash && (p[g].lineDashType ? (z = p[g].lineDashType, m[m.length - 1].newLineDashArray = N(z, n.lineThickness), b.setLineDash(m[m.length - 1].newLineDashArray)) : (z = n.lineDashType, m[m.length - 1].newLineDashArray = D, b.setLineDash(D))));
                                    if (0 !== p[g].markerSize && (0 < p[g].markerSize || 0 < n.markerSize)) {
                                        var aa = n.getMarkerProperties(g, r, w, b);
                                        f.push(aa);
                                        var F = P(t);
                                        u && f.push({
                                            x: r,
                                            y: w,
                                            ctx: e,
                                            type: aa.type,
                                            size: aa.size,
                                            color: F,
                                            borderColor: F,
                                            borderThickness: aa.borderThickness
                                        });
                                        aa = n.getMarkerProperties(g, r, s, b);
                                        f.push(aa);
                                        F = P(t);
                                        u && f.push({ x: r, y: s, ctx: e, type: aa.type, size: aa.size, color: F, borderColor: F, borderThickness: aa.borderThickness })
                                    }
                                    if (p[g].indexLabel || n.indexLabel || p[g].indexLabelFormatter || n.indexLabelFormatter) this._indexLabels.push({ chartType: "rangeArea", dataPoint: p[g], dataSeries: n, indexKeyword: 0, point: { x: r, y: s }, direction: p[g].y[0] > p[g].y[1] === a.axisY.reversed ? -1 : 1, color: E }),
                                        this._indexLabels.push({ chartType: "rangeArea", dataPoint: p[g], dataSeries: n, indexKeyword: 1, point: { x: r, y: w }, direction: p[g].y[0] > p[g].y[1] === a.axisY.reversed ? 1 : -1, color: E })
                                } else Q || q || d(), Q = !0;
                        d();
                        V.drawMarkers(f)
                    }
                }
                u && (c.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), b.globalCompositeOperation = "source-atop", a.axisX.maskCanvas && b.drawImage(a.axisX.maskCanvas, 0, 0, this.width, this.height), a.axisY.maskCanvas && b.drawImage(a.axisY.maskCanvas, 0, 0, this.width, this.height), this._breaksCanvasCtx && this._breaksCanvasCtx.drawImage(this._preRenderCanvas,
                    0, 0, this.width, this.height), b.clearRect(l.x1, l.y1, l.width, l.height), this._eventManager.ghostCtx.restore());
                b.restore();
                return { source: c, dest: this.plotArea.ctx, animationCallback: K.xClipAnimation, easingFunction: K.easing.linear, animationBase: 0 }
            }
        };
        m.prototype.renderRangeSplineArea = function(a) {
            function d(a, c) {
                var d = w(s, 2);
                if (0 < d.length) {
                    if (0 < k.lineThickness) {
                        b.strokeStyle = c;
                        b.setLineDash && b.setLineDash(a);
                        b.beginPath();
                        b.moveTo(d[0].x, d[0].y);
                        for (var f = 0; f < d.length - 3; f += 3) {
                            if (d[f].newStrokeStyle || d[f].newLineDashArray) b.stroke(),
                                b.beginPath(), b.moveTo(d[f].x, d[f].y), d[f].newStrokeStyle && (b.strokeStyle = d[f].newStrokeStyle), d[f].newLineDashArray && b.setLineDash(d[f].newLineDashArray);
                            b.bezierCurveTo(d[f + 1].x, d[f + 1].y, d[f + 2].x, d[f + 2].y, d[f + 3].x, d[f + 3].y)
                        }
                    }
                    b.beginPath();
                    b.moveTo(d[0].x, d[0].y);
                    u && (e.beginPath(), e.moveTo(d[0].x, d[0].y));
                    for (f = 0; f < d.length - 3; f += 3) b.bezierCurveTo(d[f + 1].x, d[f + 1].y, d[f + 2].x, d[f + 2].y, d[f + 3].x, d[f + 3].y), u && e.bezierCurveTo(d[f + 1].x, d[f + 1].y, d[f + 2].x, d[f + 2].y, d[f + 3].x, d[f + 3].y);
                    d = w(y, 2);
                    b.lineTo(y[y.length -
                        1].x, y[y.length - 1].y);
                    for (f = d.length - 1; 2 < f; f -= 3) b.bezierCurveTo(d[f - 1].x, d[f - 1].y, d[f - 2].x, d[f - 2].y, d[f - 3].x, d[f - 3].y), u && e.bezierCurveTo(d[f - 1].x, d[f - 1].y, d[f - 2].x, d[f - 2].y, d[f - 3].x, d[f - 3].y);
                    b.closePath();
                    b.globalAlpha = k.fillOpacity;
                    b.fill();
                    u && (e.closePath(), e.fill());
                    b.globalAlpha = 1;
                    if (0 < k.lineThickness) {
                        b.strokeStyle = c;
                        b.setLineDash && b.setLineDash(a);
                        b.beginPath();
                        b.moveTo(d[0].x, d[0].y);
                        for (var g = f = 0; f < d.length - 3; f += 3, g++) {
                            if (s[g].newStrokeStyle || s[g].newLineDashArray) b.stroke(), b.beginPath(),
                                b.moveTo(d[f].x, d[f].y), s[g].newStrokeStyle && (b.strokeStyle = s[g].newStrokeStyle), s[g].newLineDashArray && b.setLineDash(s[g].newLineDashArray);
                            b.bezierCurveTo(d[f + 1].x, d[f + 1].y, d[f + 2].x, d[f + 2].y, d[f + 3].x, d[f + 3].y)
                        }
                        d = w(s, 2);
                        b.moveTo(d[0].x, d[0].y);
                        for (g = f = 0; f < d.length - 3; f += 3, g++) {
                            if (s[g].newStrokeStyle || s[g].newLineDashArray) b.stroke(), b.beginPath(), b.moveTo(d[f].x, d[f].y), s[g].newStrokeStyle && (b.strokeStyle = s[g].newStrokeStyle), s[g].newLineDashArray && b.setLineDash(s[g].newLineDashArray);
                            b.bezierCurveTo(d[f +
                                1].x, d[f + 1].y, d[f + 2].x, d[f + 2].y, d[f + 3].x, d[f + 3].y)
                        }
                        b.stroke()
                    }
                    b.beginPath()
                }
            }
            var c = a.targetCanvasCtx || this.plotArea.ctx,
                b = u ? this._preRenderCtx : c;
            if (!(0 >= a.dataSeriesIndexes.length)) {
                var e = this._eventManager.ghostCtx,
                    f = [],
                    l = this.plotArea;
                b.save();
                u && e.save();
                b.beginPath();
                b.rect(l.x1, l.y1, l.width, l.height);
                b.clip();
                u && (e.beginPath(), e.rect(l.x1, l.y1, l.width, l.height), e.clip());
                for (var h = 0; h < a.dataSeriesIndexes.length; h++) {
                    var m = a.dataSeriesIndexes[h],
                        k = this.data[m],
                        n = k.dataPoints,
                        f = k.id;
                    this._eventManager.objectMap[f] = { objectType: "dataSeries", dataSeriesIndex: m };
                    f = P(f);
                    e.fillStyle = f;
                    var f = [],
                        p = 0,
                        q, g, r, s = [],
                        y = [];
                    if (0 < n.length) {
                        var t = k._colorSet[p % k._colorSet.length],
                            x = k.lineColor = k.options.lineColor || t,
                            E = x;
                        b.fillStyle = t;
                        b.lineWidth = k.lineThickness;
                        var B = "solid",
                            C;
                        if (b.setLineDash) {
                            var z = N(k.nullDataLineDashType, k.lineThickness),
                                B = k.lineDashType;
                            C = N(B, k.lineThickness)
                        }
                        for (g = !1; p < n.length; p++)
                            if (q = n[p].x.getTime ? n[p].x.getTime() : n[p].x, !(q < a.axisX.dataInfo.viewPortMin || q > a.axisX.dataInfo.viewPortMax && (!k.connectNullData ||
                                    !g)))
                                if (null !== n[p].y && n[p].y.length && "number" === typeof n[p].y[0] && "number" === typeof n[p].y[1]) {
                                    q = a.axisX.convertValueToPixel(q);
                                    g = a.axisY.convertValueToPixel(n[p].y[0]);
                                    r = a.axisY.convertValueToPixel(n[p].y[1]);
                                    var D = k.dataPointIds[p];
                                    this._eventManager.objectMap[D] = { id: D, objectType: "dataPoint", dataSeriesIndex: m, dataPointIndex: p, x1: q, y1: g, y2: r };
                                    s[s.length] = { x: q, y: g };
                                    y[y.length] = { x: q, y: r };
                                    p < n.length - 1 && (E !== (n[p].lineColor || x) || B !== (n[p].lineDashType || k.lineDashType)) && (E = n[p].lineColor || x, s[s.length -
                                        1].newStrokeStyle = E, b.setLineDash && (n[p].lineDashType ? (B = n[p].lineDashType, s[s.length - 1].newLineDashArray = N(B, k.lineThickness)) : (B = k.lineDashType, s[s.length - 1].newLineDashArray = C)));
                                    if (0 !== n[p].markerSize && (0 < n[p].markerSize || 0 < k.markerSize)) {
                                        var F = k.getMarkerProperties(p, q, g, b);
                                        f.push(F);
                                        var Q = P(D);
                                        u && f.push({ x: q, y: g, ctx: e, type: F.type, size: F.size, color: Q, borderColor: Q, borderThickness: F.borderThickness });
                                        F = k.getMarkerProperties(p, q, r, b);
                                        f.push(F);
                                        Q = P(D);
                                        u && f.push({
                                            x: q,
                                            y: r,
                                            ctx: e,
                                            type: F.type,
                                            size: F.size,
                                            color: Q,
                                            borderColor: Q,
                                            borderThickness: F.borderThickness
                                        })
                                    }
                                    if (n[p].indexLabel || k.indexLabel || n[p].indexLabelFormatter || k.indexLabelFormatter) this._indexLabels.push({ chartType: "rangeSplineArea", dataPoint: n[p], dataSeries: k, indexKeyword: 0, point: { x: q, y: g }, direction: n[p].y[0] <= n[p].y[1] ? -1 : 1, color: t }), this._indexLabels.push({ chartType: "rangeSplineArea", dataPoint: n[p], dataSeries: k, indexKeyword: 1, point: { x: q, y: r }, direction: n[p].y[0] <= n[p].y[1] ? 1 : -1, color: t });
                                    g = !1
                                } else 0 < p && !g && (k.connectNullData ? b.setLineDash &&
                                    (0 < s.length && (k.options.nullDataLineDashType || !n[p - 1].lineDashType)) && (s[s.length - 1].newLineDashArray = z, B = k.nullDataLineDashType) : (d(C, x), s = [], y = [])), g = !0;
                        d(C, x);
                        V.drawMarkers(f)
                    }
                }
                u && (c.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), b.globalCompositeOperation = "source-atop", a.axisX.maskCanvas && b.drawImage(a.axisX.maskCanvas, 0, 0, this.width, this.height), a.axisY.maskCanvas && b.drawImage(a.axisY.maskCanvas, 0, 0, this.width, this.height), this._breaksCanvasCtx && this._breaksCanvasCtx.drawImage(this._preRenderCanvas,
                    0, 0, this.width, this.height), b.clearRect(l.x1, l.y1, l.width, l.height), this._eventManager.ghostCtx.restore());
                b.restore();
                return { source: c, dest: this.plotArea.ctx, animationCallback: K.xClipAnimation, easingFunction: K.easing.linear, animationBase: 0 }
            }
        };
        m.prototype.renderWaterfall = function(a) {
            var d = a.targetCanvasCtx || this.plotArea.ctx,
                c = u ? this._preRenderCtx : d;
            if (!(0 >= a.dataSeriesIndexes.length)) {
                var b = this._eventManager.ghostCtx,
                    e = null,
                    f = this.plotArea,
                    l = 0,
                    h, m, k, n, p = a.axisY.convertValueToPixel(a.axisY.logarithmic ?
                        a.axisY.viewportMinimum : 0),
                    l = this.options.dataPointMinWidth ? this.dataPointMinWidth : this.options.dataPointWidth ? this.dataPointWidth : 1;
                m = this.options.dataPointMaxWidth ? this.dataPointMaxWidth : this.options.dataPointWidth ? this.dataPointWidth : Math.min(0.15 * this.width, 0.9 * (this.plotArea.width / a.plotType.totalDataSeries)) << 0;
                var q = a.axisX.dataInfo.minDiff;
                isFinite(q) || (q = 0.3 * Math.abs(a.axisX.range));
                q = this.options.dataPointWidth ? this.dataPointWidth : 0.6 * (f.width * (a.axisX.logarithmic ? Math.log(q) / Math.log(a.axisX.range) :
                    Math.abs(q) / Math.abs(a.axisX.range)) / a.plotType.totalDataSeries) << 0;
                this.dataPointMaxWidth && l > m && (l = Math.min(this.options.dataPointWidth ? this.dataPointWidth : Infinity, m));
                !this.dataPointMaxWidth && (this.dataPointMinWidth && m < l) && (m = Math.max(this.options.dataPointWidth ? this.dataPointWidth : -Infinity, l));
                q < l && (q = l);
                q > m && (q = m);
                c.save();
                u && this._eventManager.ghostCtx.save();
                c.beginPath();
                c.rect(f.x1, f.y1, f.width, f.height);
                c.clip();
                u && (this._eventManager.ghostCtx.beginPath(), this._eventManager.ghostCtx.rect(f.x1,
                    f.y1, f.width, f.height), this._eventManager.ghostCtx.clip());
                for (var g = 0; g < a.dataSeriesIndexes.length; g++) {
                    var r = a.dataSeriesIndexes[g],
                        s = this.data[r],
                        w = s.dataPoints,
                        e = s._colorSet[0];
                    s.risingColor = s.options.risingColor ? s.options.risingColor : e;
                    s.fallingColor = s.options.fallingColor ? s.options.fallingColor : "#e40a0a";
                    var t = "number" === typeof s.options.lineThickness ? Math.round(s.lineThickness) : 1,
                        x = 1 === Math.round(t) % 2 ? -0.5 : 0;
                    if (0 < w.length)
                        for (var E = 5 < q && s.bevelEnabled ? !0 : !1, B = !1, y = null, z = null, l = 0; l < w.length; l++)
                            if (w[l].getTime ?
                                n = w[l].x.getTime() : n = w[l].x, "number" !== typeof w[l].y) {
                                if (0 < l && !B && s.connectNullData) var D = s.options.nullDataLineDashType || !w[l - 1].lineDashType ? s.nullDataLineDashType : w[l - 1].lineDashType;
                                B = !0
                            } else {
                                h = a.axisX.convertValueToPixel(n);
                                m = 0 === s.dataPointEOs[l].cumulativeSum ? p : a.axisY.convertValueToPixel(s.dataPointEOs[l].cumulativeSum);
                                k = 0 === s.dataPointEOs[l].cumulativeSumYStartValue ? p : a.axisY.convertValueToPixel(s.dataPointEOs[l].cumulativeSumYStartValue);
                                h = a.axisX.reversed ? h + a.plotType.totalDataSeries *
                                    q / 2 - (a.previousDataSeriesCount + g) * q << 0 : h - a.plotType.totalDataSeries * q / 2 + (a.previousDataSeriesCount + g) * q << 0;
                                var F = a.axisX.reversed ? h - q << 0 : h + q << 0;
                                m > k && (e = m, m = k, k = e);
                                a.axisY.reversed && (e = m, m = k, k = e);
                                e = s.dataPointIds[l];
                                this._eventManager.objectMap[e] = { id: e, objectType: "dataPoint", dataSeriesIndex: r, dataPointIndex: l, x1: h, y1: m, x2: F, y2: k };
                                var Q = w[l].color ? w[l].color : 0 < w[l].y ? s.risingColor : s.fallingColor;
                                ca(c, h, m, F, k, Q, 0, Q, E, E, !1, !1, s.fillOpacity);
                                e = P(e);
                                u && ca(this._eventManager.ghostCtx, h, m, F, k, e, 0, null, !1, !1, !1, !1);
                                var aa, Q = h;
                                aa = "undefined" !== typeof w[l].isIntermediateSum && !0 === w[l].isIntermediateSum || "undefined" !== typeof w[l].isCumulativeSum && !0 === w[l].isCumulativeSum ? 0 < w[l].y ? m : k : 0 < w[l].y ? k : m;
                                0 < l && y && (!B || s.connectNullData) && (B && c.setLineDash && c.setLineDash(N(D, t)), c.beginPath(), c.moveTo(y, z - x), c.lineTo(Q, aa - x), 0 < t && c.stroke(), u && (b.beginPath(), b.moveTo(y, z - x), b.lineTo(Q, aa - x), 0 < t && b.stroke()));
                                B = !1;
                                y = F;
                                z = 0 < w[l].y ? m : k;
                                Q = w[l].lineDashType ? w[l].lineDashType : s.options.lineDashType ? s.options.lineDashType :
                                    "shortDash";
                                c.strokeStyle = w[l].lineColor ? w[l].lineColor : s.options.lineColor ? s.options.lineColor : "#9e9e9e";
                                c.lineWidth = t;
                                c.setLineDash && (Q = N(Q, t), c.setLineDash(Q));
                                (w[l].indexLabel || s.indexLabel || w[l].indexLabelFormatter || s.indexLabelFormatter) && this._indexLabels.push({ chartType: "waterfall", dataPoint: w[l], dataSeries: s, point: { x: h + (F - h) / 2, y: 0 <= w[l].y ? m : k }, direction: 0 > w[l].y === a.axisY.reversed ? 1 : -1, bounds: { x1: h, y1: Math.min(m, k), x2: F, y2: Math.max(m, k) }, color: e })
                            }
                }
                u && (d.drawImage(this._preRenderCanvas, 0,
                    0, this.width, this.height), c.globalCompositeOperation = "source-atop", a.axisX.maskCanvas && c.drawImage(a.axisX.maskCanvas, 0, 0, this.width, this.height), a.axisY.maskCanvas && c.drawImage(a.axisY.maskCanvas, 0, 0, this.width, this.height), this._breaksCanvasCtx && this._breaksCanvasCtx.drawImage(this._preRenderCanvas, 0, 0, this.width, this.height), c.clearRect(f.x1, f.y1, f.width, f.height), this._eventManager.ghostCtx.restore());
                c.restore();
                return {
                    source: d,
                    dest: this.plotArea.ctx,
                    animationCallback: K.fadeInAnimation,
                    easingFunction: K.easing.easeInQuad,
                    animationBase: 0
                }
            }
        };
        var W = function(a, d, c, b, e, f, l, h, m) {
            if (!(0 > c)) {
                "undefined" === typeof h && (h = 1);
                if (!u) {
                    var k = Number((l % (2 * Math.PI)).toFixed(8));
                    Number((f % (2 * Math.PI)).toFixed(8)) === k && (l -= 1E-4)
                }
                a.save();
                a.globalAlpha = h;
                "pie" === e ? (a.beginPath(), a.moveTo(d.x, d.y), a.arc(d.x, d.y, c, f, l, !1), a.fillStyle = b, a.strokeStyle = "white", a.lineWidth = 2, a.closePath(), a.fill()) : "doughnut" === e && (a.beginPath(), a.arc(d.x, d.y, c, f, l, !1), 0 <= m && a.arc(d.x, d.y, m * c, l, f, !0), a.closePath(), a.fillStyle = b, a.strokeStyle = "white", a.lineWidth =
                    2, a.fill());
                a.globalAlpha = 1;
                a.restore()
            }
        };
        m.prototype.renderPie = function(a) {
            function d() {
                if (k && n) {
                    for (var a = 0, b = 0, c = 0, d = 0, e = 0; e < n.length; e++) {
                        var f = n[e],
                            l = k.dataPointIds[e];
                        g[e].id = l;
                        g[e].objectType = "dataPoint";
                        g[e].dataPointIndex = e;
                        g[e].dataSeriesIndex = 0;
                        var p = g[e],
                            h = { percent: null, total: null },
                            v = null,
                            h = m.getPercentAndTotal(k, f);
                        if (k.indexLabelFormatter || f.indexLabelFormatter) v = { chart: m.options, dataSeries: k, dataPoint: f, total: h.total, percent: h.percent };
                        h = f.indexLabelFormatter ? f.indexLabelFormatter(v) :
                            f.indexLabel ? m.replaceKeywordsWithValue(f.indexLabel, f, k, e) : k.indexLabelFormatter ? k.indexLabelFormatter(v) : k.indexLabel ? m.replaceKeywordsWithValue(k.indexLabel, f, k, e) : f.label ? f.label : "";
                        m._eventManager.objectMap[l] = p;
                        p.center = { x: E.x, y: E.y };
                        p.y = f.y;
                        p.radius = z;
                        p.percentInnerRadius = F;
                        p.indexLabelText = h;
                        p.indexLabelPlacement = k.indexLabelPlacement;
                        p.indexLabelLineColor = f.indexLabelLineColor ? f.indexLabelLineColor : k.options.indexLabelLineColor ? k.options.indexLabelLineColor : f.color ? f.color : k._colorSet[e %
                            k._colorSet.length];
                        p.indexLabelLineThickness = s(f.indexLabelLineThickness) ? k.indexLabelLineThickness : f.indexLabelLineThickness;
                        p.indexLabelLineDashType = f.indexLabelLineDashType ? f.indexLabelLineDashType : k.indexLabelLineDashType;
                        p.indexLabelFontColor = f.indexLabelFontColor ? f.indexLabelFontColor : k.indexLabelFontColor;
                        p.indexLabelFontStyle = f.indexLabelFontStyle ? f.indexLabelFontStyle : k.indexLabelFontStyle;
                        p.indexLabelFontWeight = f.indexLabelFontWeight ? f.indexLabelFontWeight : k.indexLabelFontWeight;
                        p.indexLabelFontSize =
                            s(f.indexLabelFontSize) ? k.indexLabelFontSize : f.indexLabelFontSize;
                        p.indexLabelFontFamily = f.indexLabelFontFamily ? f.indexLabelFontFamily : k.indexLabelFontFamily;
                        p.indexLabelBackgroundColor = f.indexLabelBackgroundColor ? f.indexLabelBackgroundColor : k.options.indexLabelBackgroundColor ? k.options.indexLabelBackgroundColor : k.indexLabelBackgroundColor;
                        p.indexLabelMaxWidth = f.indexLabelMaxWidth ? f.indexLabelMaxWidth : k.indexLabelMaxWidth ? k.indexLabelMaxWidth : 0.33 * q.width;
                        p.indexLabelWrap = "undefined" !== typeof f.indexLabelWrap ?
                            f.indexLabelWrap : k.indexLabelWrap;
                        p.indexLabelTextAlign = f.indexLabelTextAlign ? f.indexLabelTextAlign : k.indexLabelTextAlign ? k.indexLabelTextAlign : "left";
                        p.startAngle = 0 === e ? k.startAngle ? k.startAngle / 180 * Math.PI : 0 : g[e - 1].endAngle;
                        p.startAngle = (p.startAngle + 2 * Math.PI) % (2 * Math.PI);
                        p.endAngle = p.startAngle + 2 * Math.PI / B * Math.abs(f.y);
                        f = (p.endAngle + p.startAngle) / 2;
                        f = (f + 2 * Math.PI) % (2 * Math.PI);
                        p.midAngle = f;
                        if (p.midAngle > Math.PI / 2 - t && p.midAngle < Math.PI / 2 + t) {
                            if (0 === a || g[c].midAngle > p.midAngle) c = e;
                            a++
                        } else if (p.midAngle >
                            3 * Math.PI / 2 - t && p.midAngle < 3 * Math.PI / 2 + t) {
                            if (0 === b || g[d].midAngle > p.midAngle) d = e;
                            b++
                        }
                        p.hemisphere = f > Math.PI / 2 && f <= 3 * Math.PI / 2 ? "left" : "right";
                        p.indexLabelTextBlock = new ia(m.plotArea.ctx, {
                            fontSize: p.indexLabelFontSize,
                            fontFamily: p.indexLabelFontFamily,
                            fontColor: p.indexLabelFontColor,
                            fontStyle: p.indexLabelFontStyle,
                            fontWeight: p.indexLabelFontWeight,
                            textAlign: p.indexLabelTextAlign,
                            backgroundColor: p.indexLabelBackgroundColor,
                            maxWidth: p.indexLabelMaxWidth,
                            maxHeight: p.indexLabelWrap ? 5 * p.indexLabelFontSize : 1.5 * p.indexLabelFontSize,
                            text: p.indexLabelText,
                            padding: 0,
                            textBaseline: "top"
                        });
                        p.indexLabelTextBlock.measureText()
                    }
                    l = f = 0;
                    h = !1;
                    for (e = 0; e < n.length; e++) p = g[(c + e) % n.length], 1 < a && (p.midAngle > Math.PI / 2 - t && p.midAngle < Math.PI / 2 + t) && (f <= a / 2 && !h ? (p.hemisphere = "right", f++) : (p.hemisphere = "left", h = !0));
                    h = !1;
                    for (e = 0; e < n.length; e++) p = g[(d + e) % n.length], 1 < b && (p.midAngle > 3 * Math.PI / 2 - t && p.midAngle < 3 * Math.PI / 2 + t) && (l <= b / 2 && !h ? (p.hemisphere = "left", l++) : (p.hemisphere = "right", h = !0))
                }
            }

            function c(a) {
                var b = m.plotArea.ctx;
                b.clearRect(q.x1,
                    q.y1, q.width, q.height);
                b.fillStyle = m.backgroundColor;
                b.fillRect(q.x1, q.y1, q.width, q.height);
                for (b = 0; b < n.length; b++) {
                    var c = g[b].startAngle,
                        d = g[b].endAngle;
                    if (d > c) {
                        var e = 0.07 * z * Math.cos(g[b].midAngle),
                            f = 0.07 * z * Math.sin(g[b].midAngle),
                            l = !1;
                        if (n[b].exploded) { if (1E-9 < Math.abs(g[b].center.x - (E.x + e)) || 1E-9 < Math.abs(g[b].center.y - (E.y + f))) g[b].center.x = E.x + e * a, g[b].center.y = E.y + f * a, l = !0 } else if (0 < Math.abs(g[b].center.x - E.x) || 0 < Math.abs(g[b].center.y - E.y)) g[b].center.x = E.x + e * (1 - a), g[b].center.y = E.y + f * (1 - a),
                            l = !0;
                        l && (e = {}, e.dataSeries = k, e.dataPoint = k.dataPoints[b], e.index = b, m.toolTip.highlightObjects([e]));
                        W(m.plotArea.ctx, g[b].center, g[b].radius, n[b].color ? n[b].color : k._colorSet[b % k._colorSet.length], k.type, c, d, k.fillOpacity, g[b].percentInnerRadius)
                    }
                }
                a = m.plotArea.ctx;
                a.save();
                a.fillStyle = "black";
                a.strokeStyle = "grey";
                a.textBaseline = "middle";
                a.lineJoin = "round";
                for (b = b = 0; b < n.length; b++) c = g[b], c.indexLabelText && (c.indexLabelTextBlock.y -= c.indexLabelTextBlock.height / 2, d = 0, d = "left" === c.hemisphere ? "inside" !==
                    k.indexLabelPlacement ? -(c.indexLabelTextBlock.width + p) : -c.indexLabelTextBlock.width / 2 : "inside" !== k.indexLabelPlacement ? p : -c.indexLabelTextBlock.width / 2, c.indexLabelTextBlock.x += d, c.indexLabelTextBlock.render(!0), c.indexLabelTextBlock.x -= d, c.indexLabelTextBlock.y += c.indexLabelTextBlock.height / 2, "inside" !== c.indexLabelPlacement && 0 < c.indexLabelLineThickness && (d = c.center.x + z * Math.cos(c.midAngle), e = c.center.y + z * Math.sin(c.midAngle), a.strokeStyle = c.indexLabelLineColor, a.lineWidth = c.indexLabelLineThickness,
                        a.setLineDash && a.setLineDash(N(c.indexLabelLineDashType, c.indexLabelLineThickness)), a.beginPath(), a.moveTo(d, e), a.lineTo(c.indexLabelTextBlock.x, c.indexLabelTextBlock.y), a.lineTo(c.indexLabelTextBlock.x + ("left" === c.hemisphere ? -p : p), c.indexLabelTextBlock.y), a.stroke()), a.lineJoin = "miter");
                a.save()
            }

            function b(a, b) {
                var c = 0,
                    c = a.indexLabelTextBlock.y - a.indexLabelTextBlock.height / 2,
                    d = a.indexLabelTextBlock.y + a.indexLabelTextBlock.height / 2,
                    e = b.indexLabelTextBlock.y - b.indexLabelTextBlock.height / 2,
                    f = b.indexLabelTextBlock.y +
                    b.indexLabelTextBlock.height / 2;
                return c = b.indexLabelTextBlock.y > a.indexLabelTextBlock.y ? e - d : c - f
            }

            function e(a) {
                for (var c = null, d = 1; d < n.length; d++)
                    if (c = (a + d + g.length) % g.length, g[c].hemisphere !== g[a].hemisphere) { c = null; break } else if (g[c].indexLabelText && c !== a && (0 > b(g[c], g[a]) || ("right" === g[a].hemisphere ? g[c].indexLabelTextBlock.y >= g[a].indexLabelTextBlock.y : g[c].indexLabelTextBlock.y <= g[a].indexLabelTextBlock.y))) break;
                else c = null;
                return c
            }

            function f(a, c, d) {
                d = (d || 0) + 1;
                if (1E3 < d) return 0;
                c = c || 0;
                var k = 0,
                    l = E.y - 1 * u,
                    p = E.y + 1 * u;
                if (0 <= a && a < n.length) {
                    var h = g[a];
                    if (0 > c && h.indexLabelTextBlock.y < l || 0 < c && h.indexLabelTextBlock.y > p) return 0;
                    var q = 0,
                        m = 0,
                        m = q = q = 0;
                    0 > c ? h.indexLabelTextBlock.y - h.indexLabelTextBlock.height / 2 > l && h.indexLabelTextBlock.y - h.indexLabelTextBlock.height / 2 + c < l && (c = -(l - (h.indexLabelTextBlock.y - h.indexLabelTextBlock.height / 2 + c))) : h.indexLabelTextBlock.y + h.indexLabelTextBlock.height / 2 < l && h.indexLabelTextBlock.y + h.indexLabelTextBlock.height / 2 + c > p && (c = h.indexLabelTextBlock.y + h.indexLabelTextBlock.height /
                        2 + c - p);
                    c = h.indexLabelTextBlock.y + c;
                    l = 0;
                    l = "right" === h.hemisphere ? E.x + Math.sqrt(Math.pow(u, 2) - Math.pow(c - E.y, 2)) : E.x - Math.sqrt(Math.pow(u, 2) - Math.pow(c - E.y, 2));
                    m = E.x + z * Math.cos(h.midAngle);
                    q = E.y + z * Math.sin(h.midAngle);
                    q = Math.sqrt(Math.pow(l - m, 2) + Math.pow(c - q, 2));
                    m = Math.acos(z / u);
                    q = Math.acos((u * u + z * z - q * q) / (2 * z * u));
                    c = q < m ? c - h.indexLabelTextBlock.y : 0;
                    l = null;
                    for (p = 1; p < n.length; p++)
                        if (l = (a - p + g.length) % g.length, g[l].hemisphere !== g[a].hemisphere) { l = null; break } else if (g[l].indexLabelText && g[l].hemisphere ===
                        g[a].hemisphere && l !== a && (0 > b(g[l], g[a]) || ("right" === g[a].hemisphere ? g[l].indexLabelTextBlock.y <= g[a].indexLabelTextBlock.y : g[l].indexLabelTextBlock.y >= g[a].indexLabelTextBlock.y))) break;
                    else l = null;
                    m = l;
                    q = e(a);
                    p = l = 0;
                    0 > c ? (p = "right" === h.hemisphere ? m : q, k = c, null !== p && (m = -c, c = h.indexLabelTextBlock.y - h.indexLabelTextBlock.height / 2 - (g[p].indexLabelTextBlock.y + g[p].indexLabelTextBlock.height / 2), c - m < r && (l = -m, p = f(p, l, d + 1), +p.toFixed(x) > +l.toFixed(x) && (k = c > r ? -(c - r) : -(m - (p - l)))))) : 0 < c && (p = "right" === h.hemisphere ?
                        q : m, k = c, null !== p && (m = c, c = g[p].indexLabelTextBlock.y - g[p].indexLabelTextBlock.height / 2 - (h.indexLabelTextBlock.y + h.indexLabelTextBlock.height / 2), c - m < r && (l = m, p = f(p, l, d + 1), +p.toFixed(x) < +l.toFixed(x) && (k = c > r ? c - r : m - (l - p)))));
                    k && (d = h.indexLabelTextBlock.y + k, c = 0, c = "right" === h.hemisphere ? E.x + Math.sqrt(Math.pow(u, 2) - Math.pow(d - E.y, 2)) : E.x - Math.sqrt(Math.pow(u, 2) - Math.pow(d - E.y, 2)), h.midAngle > Math.PI / 2 - t && h.midAngle < Math.PI / 2 + t ? (l = (a - 1 + g.length) % g.length, l = g[l], a = g[(a + 1 + g.length) % g.length], "left" === h.hemisphere &&
                        "right" === l.hemisphere && c > l.indexLabelTextBlock.x ? c = l.indexLabelTextBlock.x - 15 : "right" === h.hemisphere && ("left" === a.hemisphere && c < a.indexLabelTextBlock.x) && (c = a.indexLabelTextBlock.x + 15)) : h.midAngle > 3 * Math.PI / 2 - t && h.midAngle < 3 * Math.PI / 2 + t && (l = (a - 1 + g.length) % g.length, l = g[l], a = g[(a + 1 + g.length) % g.length], "right" === h.hemisphere && "left" === l.hemisphere && c < l.indexLabelTextBlock.x ? c = l.indexLabelTextBlock.x + 15 : "left" === h.hemisphere && ("right" === a.hemisphere && c > a.indexLabelTextBlock.x) && (c = a.indexLabelTextBlock.x -
                        15)), h.indexLabelTextBlock.y = d, h.indexLabelTextBlock.x = c, h.indexLabelAngle = Math.atan2(h.indexLabelTextBlock.y - E.y, h.indexLabelTextBlock.x - E.x))
                }
                return k
            }

            function l() {
                var a = m.plotArea.ctx;
                a.fillStyle = "grey";
                a.strokeStyle = "grey";
                a.font = "16px Arial";
                a.textBaseline = "middle";
                for (var c = a = 0, d = 0, l = !0, c = 0; 10 > c && (1 > c || 0 < d); c++) {
                    if (k.radius || !k.radius && "undefined" !== typeof k.innerRadius && null !== k.innerRadius && z - d <= D) l = !1;
                    l && (z -= d);
                    d = 0;
                    if ("inside" !== k.indexLabelPlacement) {
                        u = z * w;
                        for (a = 0; a < n.length; a++) {
                            var h =
                                g[a];
                            h.indexLabelTextBlock.x = E.x + u * Math.cos(h.midAngle);
                            h.indexLabelTextBlock.y = E.y + u * Math.sin(h.midAngle);
                            h.indexLabelAngle = h.midAngle;
                            h.radius = z;
                            h.percentInnerRadius = F
                        }
                        for (var v, s, a = 0; a < n.length; a++) {
                            var h = g[a],
                                t = e(a);
                            if (null !== t) {
                                v = g[a];
                                s = g[t];
                                var B = 0,
                                    B = b(v, s) - r;
                                if (0 > B) {
                                    for (var y = s = 0, C = 0; C < n.length; C++) C !== a && g[C].hemisphere === h.hemisphere && (g[C].indexLabelTextBlock.y < h.indexLabelTextBlock.y ? s++ : y++);
                                    s = B / (s + y || 1) * y;
                                    var y = -1 * (B - s),
                                        J = C = 0;
                                    "right" === h.hemisphere ? (C = f(a, s), y = -1 * (B - C), J = f(t, y), +J.toFixed(x) <
                                        +y.toFixed(x) && +C.toFixed(x) <= +s.toFixed(x) && f(a, -(y - J))) : (C = f(t, s), y = -1 * (B - C), J = f(a, y), +J.toFixed(x) < +y.toFixed(x) && +C.toFixed(x) <= +s.toFixed(x) && f(t, -(y - J)))
                                }
                            }
                        }
                    } else
                        for (a = 0; a < n.length; a++) h = g[a], u = "pie" === k.type ? 0.7 * z : 0.8 * z, t = E.x + u * Math.cos(h.midAngle), s = E.y + u * Math.sin(h.midAngle), h.indexLabelTextBlock.x = t, h.indexLabelTextBlock.y = s;
                    for (a = 0; a < n.length; a++)
                        if (h = g[a], t = h.indexLabelTextBlock.measureText(), 0 !== t.height && 0 !== t.width) t = t = 0, "right" === h.hemisphere ? (t = q.x2 - (h.indexLabelTextBlock.x + h.indexLabelTextBlock.width +
                            p), t *= -1) : t = q.x1 - (h.indexLabelTextBlock.x - h.indexLabelTextBlock.width - p), 0 < t && (!l && h.indexLabelText && (s = "right" === h.hemisphere ? q.x2 - h.indexLabelTextBlock.x : h.indexLabelTextBlock.x - q.x1, 0.3 * h.indexLabelTextBlock.maxWidth > s ? h.indexLabelText = "" : h.indexLabelTextBlock.maxWidth = 0.85 * s, 0.3 * h.indexLabelTextBlock.maxWidth < s && (h.indexLabelTextBlock.x -= "right" === h.hemisphere ? 2 : -2)), Math.abs(h.indexLabelTextBlock.y - h.indexLabelTextBlock.height / 2 - E.y) < z || Math.abs(h.indexLabelTextBlock.y + h.indexLabelTextBlock.height /
                            2 - E.y) < z) && (t /= Math.abs(Math.cos(h.indexLabelAngle)), 9 < t && (t *= 0.3), t > d && (d = t)), t = t = 0, 0 < h.indexLabelAngle && h.indexLabelAngle < Math.PI ? (t = q.y2 - (h.indexLabelTextBlock.y + h.indexLabelTextBlock.height / 2 + 5), t *= -1) : t = q.y1 - (h.indexLabelTextBlock.y - h.indexLabelTextBlock.height / 2 - 5), 0 < t && (!l && h.indexLabelText && (s = 0 < h.indexLabelAngle && h.indexLabelAngle < Math.PI ? -1 : 1, 0 === f(a, t * s) && f(a, 2 * s)), Math.abs(h.indexLabelTextBlock.x - E.x) < z && (t /= Math.abs(Math.sin(h.indexLabelAngle)), 9 < t && (t *= 0.3), t > d && (d = t)));
                    var H = function(a,
                        b, c) {
                        for (var d = [], e = 0; d.push(g[b]), b !== c; b = (b + 1 + n.length) % n.length);
                        d.sort(function(a, b) { return a.y - b.y });
                        for (b = 0; b < d.length; b++)
                            if (c = d[b], e < 0.7 * a) e += c.indexLabelTextBlock.height, c.indexLabelTextBlock.text = "", c.indexLabelText = "", c.indexLabelTextBlock.measureText();
                            else break
                    };
                    (function() {
                        for (var a = -1, c = -1, d = 0, f = !1, k = 0; k < n.length; k++)
                            if (f = !1, v = g[k], v.indexLabelText) {
                                var l = e(k);
                                if (null !== l) {
                                    var h = g[l];
                                    B = 0;
                                    B = b(v, h);
                                    var q;
                                    if (q = 0 > B) {
                                        q = v.indexLabelTextBlock.x;
                                        var m = v.indexLabelTextBlock.y - v.indexLabelTextBlock.height /
                                            2,
                                            r = v.indexLabelTextBlock.y + v.indexLabelTextBlock.height / 2,
                                            s = h.indexLabelTextBlock.y - h.indexLabelTextBlock.height / 2,
                                            t = h.indexLabelTextBlock.x + h.indexLabelTextBlock.width,
                                            A = h.indexLabelTextBlock.y + h.indexLabelTextBlock.height / 2;
                                        q = v.indexLabelTextBlock.x + v.indexLabelTextBlock.width < h.indexLabelTextBlock.x - p || q > t + p || m > A + p || r < s - p ? !1 : !0
                                    }
                                    q ? (0 > a && (a = k), l !== a && (c = l, d += -B), 0 === k % Math.max(n.length / 10, 3) && (f = !0)) : f = !0;
                                    f && (0 < d && 0 <= a && 0 <= c) && (H(d, a, c), c = a = -1, d = 0)
                                }
                            }
                        0 < d && H(d, a, c)
                    })()
                }
            }

            function h() {
                m.plotArea.layoutManager.reset();
                m.title && (m.title.dockInsidePlotArea || "center" === m.title.horizontalAlign && "center" === m.title.verticalAlign) && m.title.render();
                if (m.subtitles)
                    for (var a = 0; a < m.subtitles.length; a++) {
                        var b = m.subtitles[a];
                        (b.dockInsidePlotArea || "center" === b.horizontalAlign && "center" === b.verticalAlign) && b.render()
                    }
                m.legend && (m.legend.dockInsidePlotArea || "center" === m.legend.horizontalAlign && "center" === m.legend.verticalAlign) && (m.legend.setLayout(), m.legend.render())
            }
            var m = this;
            if (!(0 >= a.dataSeriesIndexes.length)) {
                var k =
                    this.data[a.dataSeriesIndexes[0]],
                    n = k.dataPoints,
                    p = 10,
                    q = this.plotArea,
                    g = k.dataPointEOs,
                    r = 2,
                    u, w = 1.3,
                    t = 20 / 180 * Math.PI,
                    x = 6,
                    E = { x: (q.x2 + q.x1) / 2, y: (q.y2 + q.y1) / 2 },
                    B = 0;
                a = !1;
                for (var y = 0; y < n.length; y++) B += Math.abs(n[y].y), !a && ("undefined" !== typeof n[y].indexLabel && null !== n[y].indexLabel && 0 < n[y].indexLabel.toString().length) && (a = !0), !a && ("undefined" !== typeof n[y].label && null !== n[y].label && 0 < n[y].label.toString().length) && (a = !0);
                if (0 !== B) {
                    a = a || "undefined" !== typeof k.indexLabel && null !== k.indexLabel && 0 < k.indexLabel.toString().length;
                    var z = "inside" !== k.indexLabelPlacement && a ? 0.75 * Math.min(q.width, q.height) / 2 : 0.92 * Math.min(q.width, q.height) / 2;
                    k.radius && (z = Ra(k.radius, z));
                    var D = "undefined" !== typeof k.innerRadius && null !== k.innerRadius ? Ra(k.innerRadius, z) : 0.7 * z;
                    k.radius = z;
                    "doughnut" === k.type && (k.innerRadius = D);
                    var F = Math.min(D / z, (z - 1) / z);
                    this.pieDoughnutClickHandler = function(a) {
                        m.isAnimating || !s(a.dataSeries.explodeOnClick) && !a.dataSeries.explodeOnClick || (a = a.dataPoint, a.exploded = a.exploded ? !1 : !0, 1 < this.dataPoints.length && m._animator.animate(0,
                            500,
                            function(a) {
                                c(a);
                                h();
                                m.dispatchEvent("dataAnimationIterationEnd", { chart: m });
                                m.dispatchEvent("dataAnimationEnd", { chart: m })
                            }))
                    };
                    d();
                    l();
                    l();
                    l();
                    l();
                    this.disableToolTip = !0;
                    this._animator.animate(0, this.animatedRender ? this.animationDuration : 0, function(a) {
                        var b = m.plotArea.ctx;
                        b.clearRect(q.x1, q.y1, q.width, q.height);
                        b.fillStyle = m.backgroundColor;
                        b.fillRect(q.x1, q.y1, q.width, q.height);
                        for (var b = g[0].startAngle + 2 * Math.PI * a, c = 0; c < n.length; c++) {
                            var d = 0 === c ? g[c].startAngle : e,
                                e = d + (g[c].endAngle - g[c].startAngle),
                                f = !1;
                            e > b && (e = b, f = !0);
                            var l = n[c].color ? n[c].color : k._colorSet[c % k._colorSet.length];
                            e > d && W(m.plotArea.ctx, g[c].center, g[c].radius, l, k.type, d, e, k.fillOpacity, g[c].percentInnerRadius);
                            if (f) break
                        }
                        h();
                        m.dispatchEvent("dataAnimationIterationEnd", { chart: m });
                        1 <= a && m.dispatchEvent("dataAnimationEnd", { chart: m })
                    }, function() {
                        m.disableToolTip = !1;
                        m._animator.animate(0, m.animatedRender ? 500 : 0, function(a) {
                            c(a);
                            h();
                            m.dispatchEvent("dataAnimationIterationEnd", { chart: m })
                        });
                        m.dispatchEvent("dataAnimationEnd", { chart: m })
                    })
                }
            }
        };
        var pa = function(a, d, c, b) {
            "undefined" === typeof c && (c = 1);
            0 >= Math.round(d.y4 - d.y1) || (a.save(), a.globalAlpha = c, a.beginPath(), a.moveTo(Math.round(d.x1), Math.round(d.y1)), a.lineTo(Math.round(d.x2), Math.round(d.y2)), a.lineTo(Math.round(d.x3), Math.round(d.y3)), a.lineTo(Math.round(d.x4), Math.round(d.y4)), "undefined" !== d.x5 && (a.lineTo(Math.round(d.x5), Math.round(d.y5)), a.lineTo(Math.round(d.x6), Math.round(d.y6))), a.closePath(), a.fillStyle = b ? b : d.color, a.fill(), a.globalAplha = 1, a.restore())
        };
        m.prototype.renderFunnel =
            function(a) {
                function d() {
                    for (var a = 0, b = [], c = 0; c < x.length; c++) {
                        if ("undefined" === typeof x[c].y) return -1;
                        x[c].y = "number" === typeof x[c].y ? x[c].y : 0;
                        a += Math.abs(x[c].y)
                    }
                    if (0 === a) return -1;
                    for (c = b[0] = 0; c < x.length; c++) b.push(Math.abs(x[c].y) * D / a);
                    return b
                }

                function c() {
                    var a = X,
                        b = Z,
                        c = L,
                        d = V,
                        e, f;
                    e = Y;
                    f = P - ba;
                    d = Math.abs((f - e) * (b - a + (d - c)) / 2);
                    c = V - L;
                    e = f - e;
                    f = c * (f - P);
                    f = Math.abs(f);
                    f = d + f;
                    for (var d = [], g = 0, k = 0; k < x.length; k++) {
                        if ("undefined" === typeof x[k].y) return -1;
                        x[k].y = "number" === typeof x[k].y ? x[k].y : 0;
                        g += Math.abs(x[k].y)
                    }
                    if (0 ===
                        g) return -1;
                    for (var l = d[0] = 0, n = 0, h, p, b = b - a, l = !1, k = 0; k < x.length; k++) a = Math.abs(x[k].y) * f / g, l ? h = 0 == Number(c.toFixed(3)) ? 0 : a / c : (p = $ * $ * b * b - 4 * Math.abs($) * a, 0 > p ? (p = c, l = (b + p) * (e - n) / 2, a -= l, h = e - n, n += e - n, h += 0 == p ? 0 : a / p, n += a / p, l = !0) : (h = (Math.abs($) * b - Math.sqrt(p)) / 2, p = b - 2 * h / Math.abs($), n += h, n > e && (n -= h, p = c, l = (b + p) * (e - n) / 2, a -= l, h = e - n, n += e - n, h += a / p, n += a / p, l = !0), b = p)), d.push(h);
                    return d
                }

                function b() {
                    if (t && x) {
                        for (var a, b, c, d, e, f, k, l, n, h, p, q, m, v, r, A = [], w = [], E = { percent: null, total: null }, B = null, z = 0; z < x.length; z++) r = O[z],
                            r = "undefined" !== typeof r.x5 ? (r.y2 + r.y4) / 2 : (r.y2 + r.y3) / 2, r = g(r).x2 + 1, A[z] = M - r - U;
                        r = 0.5 * U;
                        for (var z = 0, C = x.length - 1; z < x.length || 0 <= C; z++, C--) {
                            b = t.reversed ? x[C] : x[z];
                            a = b.color ? b.color : t.reversed ? t._colorSet[(x.length - 1 - z) % t._colorSet.length] : t._colorSet[z % t._colorSet.length];
                            c = b.indexLabelPlacement || t.indexLabelPlacement || "outside";
                            v = b.indexLabelTextAlign || t.indexLabelTextAlign || "left";
                            d = b.indexLabelBackgroundColor || t.indexLabelBackgroundColor || (u ? "transparent" : null);
                            e = b.indexLabelFontColor || t.indexLabelFontColor ||
                                "#979797";
                            f = s(b.indexLabelFontSize) ? t.indexLabelFontSize : b.indexLabelFontSize;
                            k = b.indexLabelFontStyle || t.indexLabelFontStyle || "normal";
                            l = b.indexLabelFontFamily || t.indexLabelFontFamily || "arial";
                            n = b.indexLabelFontWeight || t.indexLabelFontWeight || "normal";
                            a = b.indexLabelLineColor || t.options.indexLabelLineColor || a;
                            h = "number" === typeof b.indexLabelLineThickness ? b.indexLabelLineThickness : "number" === typeof t.indexLabelLineThickness ? t.indexLabelLineThickness : 2;
                            p = b.indexLabelLineDashType || t.indexLabelLineDashType ||
                                "solid";
                            q = "undefined" !== typeof b.indexLabelWrap ? b.indexLabelWrap : "undefined" !== typeof t.indexLabelWrap ? t.indexLabelWrap : !0;
                            m = t.dataPointIds[z];
                            y._eventManager.objectMap[m] = { id: m, objectType: "dataPoint", dataPointIndex: z, dataSeriesIndex: 0, funnelSection: O[t.reversed ? x.length - 1 - z : z] };
                            "inside" === t.indexLabelPlacement && (A[z] = z !== ca ? t.reversed ? O[z].x2 - O[z].x1 : O[z].x3 - O[z].x4 : O[z].x3 - O[z].x6, 20 > A[z] && (A[z] = z !== ca ? t.reversed ? O[z].x3 - O[z].x4 : O[z].x2 - O[z].x1 : O[z].x2 - O[z].x1, A[z] /= 2));
                            m = b.indexLabelMaxWidth ? b.indexLabelMaxWidth :
                                t.options.indexLabelMaxWidth ? t.indexLabelMaxWidth : A[z];
                            if (m > A[z] || 0 > m) m = A[z];
                            w[z] = "inside" === t.indexLabelPlacement ? O[z].height : !1;
                            E = y.getPercentAndTotal(t, b);
                            if (t.indexLabelFormatter || b.indexLabelFormatter) B = { chart: y.options, dataSeries: t, dataPoint: b, total: E.total, percent: E.percent };
                            b = b.indexLabelFormatter ? b.indexLabelFormatter(B) : b.indexLabel ? y.replaceKeywordsWithValue(b.indexLabel, b, t, z) : t.indexLabelFormatter ? t.indexLabelFormatter(B) : t.indexLabel ? y.replaceKeywordsWithValue(t.indexLabel, b, t, z) : b.label ?
                                b.label : "";
                            0 >= h && (h = 0);
                            1E3 > m && 1E3 - m < r && (m += 1E3 - m);
                            R.roundRect || Ba(R);
                            c = new ia(R, { fontSize: f, fontFamily: l, fontColor: e, fontStyle: k, fontWeight: n, horizontalAlign: c, textAlign: v, backgroundColor: d, maxWidth: m, maxHeight: !1 === w[z] ? q ? 4.28571429 * f : 1.5 * f : w[z], text: b, padding: da });
                            c.measureText();
                            I.push({ textBlock: c, id: t.reversed ? C : z, isDirty: !1, lineColor: a, lineThickness: h, lineDashType: p, height: c.height < c.maxHeight ? c.height : c.maxHeight, width: c.width < c.maxWidth ? c.width : c.maxWidth })
                        }
                    }
                }

                function e() {
                    var a, b, c, d, e, f = [];
                    e = !1;
                    c = 0;
                    for (var g, k = M - Z - U / 2, k = t.options.indexLabelMaxWidth ? t.indexLabelMaxWidth > k ? k : t.indexLabelMaxWidth : k, l = I.length - 1; 0 <= l; l--) {
                        g = x[I[l].id];
                        c = I[l];
                        d = c.textBlock;
                        b = (a = q(l) < O.length ? I[q(l)] : null) ? a.textBlock : null;
                        c = c.height;
                        a && d.y + c + da > b.y && (e = !0);
                        c = g.indexLabelMaxWidth || k;
                        if (c > k || 0 > c) c = k;
                        f.push(c)
                    }
                    if (e)
                        for (l = I.length - 1; 0 <= l; l--) a = O[l], I[l].textBlock.maxWidth = f[f.length - (l + 1)], I[l].textBlock.measureText(), I[l].textBlock.x = M - k, c = I[l].textBlock.height < I[l].textBlock.maxHeight ? I[l].textBlock.height :
                            I[l].textBlock.maxHeight, e = I[l].textBlock.width < I[l].textBlock.maxWidth ? I[l].textBlock.width : I[l].textBlock.maxWidth, I[l].height = c, I[l].width = e, c = "undefined" !== typeof a.x5 ? (a.y2 + a.y4) / 2 : (a.y2 + a.y3) / 2, I[l].textBlock.y = c - I[l].height / 2, t.reversed ? (I[l].textBlock.y + I[l].height > na + B && (I[l].textBlock.y = na + B - I[l].height), I[l].textBlock.y < sa - B && (I[l].textBlock.y = sa - B)) : (I[l].textBlock.y < na - B && (I[l].textBlock.y = na - B), I[l].textBlock.y + I[l].height > sa + B && (I[l].textBlock.y = sa + B - I[l].height))
                }

                function f() {
                    var a,
                        b, c, d;
                    if ("inside" !== t.indexLabelPlacement)
                        for (var e = 0; e < O.length; e++) 0 == I[e].textBlock.text.length ? I[e].isDirty = !0 : (a = O[e], c = "undefined" !== typeof a.x5 ? (a.y2 + a.y4) / 2 : (a.y2 + a.y3) / 2, b = t.reversed ? "undefined" !== typeof a.x5 ? c > xa ? g(c).x2 + 1 : (a.x2 + a.x3) / 2 + 1 : (a.x2 + a.x3) / 2 + 1 : "undefined" !== typeof a.x5 ? c < xa ? g(c).x2 + 1 : (a.x4 + a.x3) / 2 + 1 : (a.x2 + a.x3) / 2 + 1, I[e].textBlock.x = b + U, I[e].textBlock.y = c - I[e].height / 2, t.reversed ? (I[e].textBlock.y + I[e].height > na + B && (I[e].textBlock.y = na + B - I[e].height), I[e].textBlock.y < sa - B && (I[e].textBlock.y =
                            sa - B)) : (I[e].textBlock.y < na - B && (I[e].textBlock.y = na - B), I[e].textBlock.y + I[e].height > sa + B && (I[e].textBlock.y = sa + B - I[e].height)));
                    else
                        for (e = 0; e < O.length; e++) 0 == I[e].textBlock.text.length ? I[e].isDirty = !0 : (a = O[e], b = a.height, c = I[e].height, d = I[e].width, b >= c ? (b = e != ca ? (a.x4 + a.x3) / 2 - d / 2 : (a.x5 + a.x4) / 2 - d / 2, c = e != ca ? (a.y1 + a.y3) / 2 - c / 2 : (a.y1 + a.y4) / 2 - c / 2, I[e].textBlock.x = b, I[e].textBlock.y = c) : I[e].isDirty = !0)
                }

                function l() {
                    function a(b, c) {
                        var d;
                        if (0 > b || b >= I.length) return 0;
                        var e, f = I[b].textBlock;
                        if (0 > c) {
                            c *= -1;
                            e =
                                p(b);
                            d = h(e, b);
                            if (d >= c) return f.y -= c, c;
                            if (0 == b) return 0 < d && (f.y -= d), d;
                            d += a(e, -(c - d));
                            0 < d && (f.y -= d);
                            return d
                        }
                        e = q(b);
                        d = h(b, e);
                        if (d >= c) return f.y += c, c;
                        if (b == O.length - 1) return 0 < d && (f.y += d), d;
                        d += a(e, c - d);
                        0 < d && (f.y += d);
                        return d
                    }

                    function b() {
                        var a, d, e, f, g = 0,
                            k;
                        f = (P - Y + 2 * B) / n;
                        k = n;
                        for (var l, h = 1; h < k; h++) {
                            e = h * f;
                            for (var m = I.length - 1; 0 <= m; m--) !I[m].isDirty && (I[m].textBlock.y < e && I[m].textBlock.y + I[m].height > e) && (l = q(m), !(l >= I.length - 1) && I[m].textBlock.y + I[m].height + da > I[l].textBlock.y && (I[m].textBlock.y = I[m].textBlock.y +
                                I[m].height - e > e - I[m].textBlock.y ? e + 1 : e - I[m].height - 1))
                        }
                        for (l = O.length - 1; 0 < l; l--)
                            if (!I[l].isDirty) {
                                e = p(l);
                                if (0 > e && (e = 0, I[e].isDirty)) break;
                                if (I[l].textBlock.y < I[e].textBlock.y + I[e].height) {
                                    d = d || l;
                                    f = l;
                                    for (k = 0; I[f].textBlock.y < I[e].textBlock.y + I[e].height + da;) {
                                        a = a || I[f].textBlock.y + I[f].height;
                                        k += I[f].height;
                                        k += da;
                                        f = e;
                                        if (0 >= f) {
                                            f = 0;
                                            k += I[f].height;
                                            break
                                        }
                                        e = p(f);
                                        if (0 > e) {
                                            f = 0;
                                            k += I[f].height;
                                            break
                                        }
                                    }
                                    if (f != l) {
                                        g = I[f].textBlock.y;
                                        a -= g;
                                        a = k - a;
                                        g = c(a, d, f);
                                        break
                                    }
                                }
                            }
                        return g
                    }

                    function c(a, b, d) {
                        var e = [],
                            f = 0,
                            g = 0;
                        for (a = Math.abs(a); d <=
                            b; d++) e.push(O[d]);
                        e.sort(function(a, b) { return a.height - b.height });
                        for (d = 0; d < e.length; d++)
                            if (b = e[d], f < a) g++, f += I[b.id].height + da, I[b.id].textBlock.text = "", I[b.id].indexLabelText = "", I[b.id].isDirty = !0, I[b.id].textBlock.measureText();
                            else break;
                        return g
                    }
                    for (var d, e, f, g, k, l, n = 1, m = 0; m < 2 * n; m++) {
                        for (var r = I.length - 1; 0 <= r && !(0 <= p(r) && p(r), f = I[r], g = f.textBlock, l = (k = q(r) < O.length ? I[q(r)] : null) ? k.textBlock : null, d = +f.height.toFixed(6), e = +g.y.toFixed(6), !f.isDirty && (k && e + d + da > +l.y.toFixed(6)) && (d = g.y + d + da - l.y,
                                e = a(r, -d), e < d && (0 < e && (d -= e), e = a(q(r), d), e != d))); r--);
                        b()
                    }
                }

                function h(a, b) { return (b < O.length ? I[b].textBlock.y : t.reversed ? na + B : sa + B) - (0 > a ? t.reversed ? sa - B : na - B : I[a].textBlock.y + I[a].height + da) }

                function m(a, b, c) {
                    var d, e, g, l = [],
                        h = B,
                        p = []; - 1 !== b && (0 <= W.indexOf(b) ? (e = W.indexOf(b), W.splice(e, 1)) : (W.push(b), W = W.sort(function(a, b) { return a - b })));
                    if (0 === W.length) l = ga;
                    else {
                        e = B * (1 != W.length || 0 != W[0] && W[0] != O.length - 1 ? 2 : 1) / k();
                        for (var q = 0; q < O.length; q++) {
                            if (1 == W.length && 0 == W[0]) {
                                if (0 === q) {
                                    l.push(ga[q]);
                                    d = h;
                                    continue
                                }
                            } else 0 ===
                                q && (d = -1 * h);
                            l.push(ga[q] + d);
                            if (0 <= W.indexOf(q) || q < O.length && 0 <= W.indexOf(q + 1)) d += e
                        }
                    }
                    g = function() { for (var a = [], b = 0; b < O.length; b++) a.push(l[b] - O[b].y1); return a }();
                    var v = {
                        startTime: (new Date).getTime(),
                        duration: c || 500,
                        easingFunction: function(a, b, c, d) { return K.easing.easeOutQuart(a, b, c, d) },
                        changeSection: function(a) {
                            for (var b, c, d = 0; d < O.length; d++) b = g[d], c = O[d], b *= a, "undefined" === typeof p[d] && (p[d] = 0), 0 > p && (p *= -1), c.y1 += b - p[d], c.y2 += b - p[d], c.y3 += b - p[d], c.y4 += b - p[d], c.y5 && (c.y5 += b - p[d], c.y6 += b - p[d]), p[d] =
                                b
                        }
                    };
                    a._animator.animate(0, c, function(c) {
                        var d = a.plotArea.ctx || a.ctx;
                        ha = !0;
                        d.clearRect(E.x1, E.y1, E.x2 - E.x1, E.y2 - E.y1);
                        d.fillStyle = a.backgroundColor;
                        d.fillRect(E.x1, E.y1, E.width, E.height);
                        v.changeSection(c, b);
                        var e = {};
                        e.dataSeries = t;
                        e.dataPoint = t.reversed ? t.dataPoints[x.length - 1 - b] : t.dataPoints[b];
                        e.index = t.reversed ? x.length - 1 - b : b;
                        a.toolTip.highlightObjects([e]);
                        for (e = 0; e < O.length; e++) pa(d, O[e], t.fillOpacity);
                        w(d);
                        J && ("inside" !== t.indexLabelPlacement ? n(d) : f(), r(d));
                        1 <= c && (ha = !1)
                    }, null, K.easing.easeOutQuart)
                }

                function k() { for (var a = 0, b = 0; b < O.length - 1; b++)(0 <= W.indexOf(b) || 0 <= W.indexOf(b + 1)) && a++; return a }

                function n(a) { for (var b, c, d, e, f = 0; f < O.length; f++) e = 1 === I[f].lineThickness % 2 ? 0.5 : 0, c = ((O[f].y2 + O[f].y4) / 2 << 0) + e, b = g(c).x2 - 1, d = I[f].textBlock.x, e = (I[f].textBlock.y + I[f].height / 2 << 0) + e, I[f].isDirty || 0 == I[f].lineThickness || (a.strokeStyle = I[f].lineColor, a.lineWidth = I[f].lineThickness, a.setLineDash && a.setLineDash(N(I[f].lineDashType, I[f].lineThickness)), a.beginPath(), a.moveTo(b, c), a.lineTo(d, e), a.stroke()) }

                function p(a) { for (a -= 1; - 1 <= a && -1 != a && I[a].isDirty; a--); return a }

                function q(a) { for (a += 1; a <= O.length && a != O.length && I[a].isDirty; a++); return a }

                function g(a) {
                    for (var b, c = 0; c < x.length; c++)
                        if (O[c].y1 < a && O[c].y4 > a) { b = O[c]; break }
                    return b ? (a = b.y6 ? a > b.y6 ? b.x3 + (b.x4 - b.x3) / (b.y4 - b.y3) * (a - b.y3) : b.x2 + (b.x3 - b.x2) / (b.y3 - b.y2) * (a - b.y2) : b.x2 + (b.x3 - b.x2) / (b.y3 - b.y2) * (a - b.y2), { x1: a, x2: a }) : -1
                }

                function r(a) { for (var b = 0; b < O.length; b++) I[b].isDirty || (a && (I[b].textBlock.ctx = a), I[b].textBlock.render(!0)) }

                function w(a) {
                    y.plotArea.layoutManager.reset();
                    a.roundRect || Ba(a);
                    y.title && (y.title.dockInsidePlotArea || "center" === y.title.horizontalAlign && "center" === y.title.verticalAlign) && (y.title.ctx = a, y.title.render());
                    if (y.subtitles)
                        for (var b = 0; b < y.subtitles.length; b++) { var c = y.subtitles[b]; if (c.dockInsidePlotArea || "center" === c.horizontalAlign && "center" === c.verticalAlign) y.subtitles.ctx = a, c.render() }
                    y.legend && (y.legend.dockInsidePlotArea || "center" === y.legend.horizontalAlign && "center" === y.legend.verticalAlign) && (y.legend.ctx = a, y.legend.setLayout(), y.legend.render());
                    S.fNg && S.fNg(y)
                }
                var y = this;
                if (!(0 >= a.dataSeriesIndexes.length)) {
                    for (var t = this.data[a.dataSeriesIndexes[0]], x = t.dataPoints, E = this.plotArea, B = 0.025 * E.width, C = 0.01 * E.width, z = 0, D = E.height - 2 * B, F = Math.min(E.width - 2 * C, 2.8 * E.height), J = !1, H = 0; H < x.length; H++)
                        if (!J && ("undefined" !== typeof x[H].indexLabel && null !== x[H].indexLabel && 0 < x[H].indexLabel.toString().length) && (J = !0), !J && ("undefined" !== typeof x[H].label && null !== x[H].label && 0 < x[H].label.toString().length) && (J = !0), !J && "function" === typeof t.indexLabelFormatter ||
                            "function" === typeof x[H].indexLabelFormatter) J = !0;
                    J = J || "undefined" !== typeof t.indexLabel && null !== t.indexLabel && 0 < t.indexLabel.toString().length;
                    "inside" !== t.indexLabelPlacement && J || (C = (E.width - 0.75 * F) / 2);
                    var H = E.x1 + C,
                        M = E.x2 - C,
                        Y = E.y1 + B,
                        P = E.y2 - B,
                        R = a.targetCanvasCtx || this.plotArea.ctx || this.ctx;
                    if (0 != t.length && (t.dataPoints && t.visible) && 0 !== x.length) {
                        var ba, G;
                        a = 75 * F / 100;
                        var U = 30 * (M - a) / 100;
                        "funnel" === t.type ? (ba = s(t.options.neckHeight) ? 0.35 * D : t.neckHeight, G = s(t.options.neckWidth) ? 0.25 * a : t.neckWidth, "string" ===
                            typeof ba && ba.match(/%$/) ? (ba = parseInt(ba), ba = ba * D / 100) : ba = parseInt(ba), "string" === typeof G && G.match(/%$/) ? (G = parseInt(G), G = G * a / 100) : G = parseInt(G), ba > D ? ba = D : 0 >= ba && (ba = 0), G > a ? G = a - 0.5 : 0 >= G && (G = 0)) : "pyramid" === t.type && (G = ba = 0, t.reversed = t.reversed ? !1 : !0);
                        var C = H + a / 2,
                            X = H,
                            Z = H + a,
                            na = t.reversed ? P : Y,
                            L = C - G / 2,
                            V = C + G / 2,
                            xa = t.reversed ? Y + ba : P - ba,
                            sa = t.reversed ? Y : P;
                        a = [];
                        var C = [],
                            O = [],
                            F = [],
                            T = Y,
                            ca, $ = (xa - na) / (L - X),
                            ea = -$,
                            H = "area" === (t.valueRepresents ? t.valueRepresents : "height") ? c() : d();
                        if (-1 !== H) {
                            if (t.reversed)
                                for (F.push(T),
                                    G = H.length - 1; 0 < G; G--) T += H[G], F.push(T);
                            else
                                for (G = 0; G < H.length; G++) T += H[G], F.push(T);
                            if (t.reversed)
                                for (G = 0; G < H.length; G++) F[G] < xa ? (a.push(L), C.push(V), ca = G) : (a.push((F[G] - na + $ * X) / $), C.push((F[G] - na + ea * Z) / ea));
                            else
                                for (G = 0; G < H.length; G++) F[G] < xa ? (a.push((F[G] - na + $ * X) / $), C.push((F[G] - na + ea * Z) / ea), ca = G) : (a.push(L), C.push(V));
                            for (G = 0; G < H.length - 1; G++) T = t.reversed ? x[x.length - 1 - G].color ? x[x.length - 1 - G].color : t._colorSet[(x.length - 1 - G) % t._colorSet.length] : x[G].color ? x[G].color : t._colorSet[G % t._colorSet.length],
                                G === ca ? O.push({ x1: a[G], y1: F[G], x2: C[G], y2: F[G], x3: V, y3: xa, x4: C[G + 1], y4: F[G + 1], x5: a[G + 1], y5: F[G + 1], x6: L, y6: xa, id: G, height: F[G + 1] - F[G], color: T }) : O.push({ x1: a[G], y1: F[G], x2: C[G], y2: F[G], x3: C[G + 1], y3: F[G + 1], x4: a[G + 1], y4: F[G + 1], id: G, height: F[G + 1] - F[G], color: T });
                            var da = 2,
                                I = [],
                                ha = !1,
                                W = [],
                                ga = [],
                                H = !1;
                            a = a = 0;
                            Ca(W);
                            for (G = 0; G < x.length; G++) x[G].exploded && (H = !0, t.reversed ? W.push(x.length - 1 - G) : W.push(G));
                            R.clearRect(E.x1, E.y1, E.width, E.height);
                            R.fillStyle = y.backgroundColor;
                            R.fillRect(E.x1, E.y1, E.width, E.height);
                            if (J && t.visible && (b(), f(), e(), "inside" !== t.indexLabelPlacement)) { l(); for (G = 0; G < x.length; G++) I[G].isDirty || (a = I[G].textBlock.x + I[G].width, a = (M - a) / 2, 0 == G && (z = a), z > a && (z = a)); for (G = 0; G < O.length; G++) O[G].x1 += z, O[G].x2 += z, O[G].x3 += z, O[G].x4 += z, O[G].x5 && (O[G].x5 += z, O[G].x6 += z), I[G].textBlock.x += z }
                            for (G = 0; G < O.length; G++) z = O[G], pa(R, z, t.fillOpacity), ga.push(z.y1);
                            w(R);
                            J && t.visible && ("inside" === t.indexLabelPlacement || y.animationEnabled || n(R), y.animationEnabled || r());
                            if (!J)
                                for (G = 0; G < x.length; G++) z = t.dataPointIds[G],
                                    a = { id: z, objectType: "dataPoint", dataPointIndex: G, dataSeriesIndex: 0, funnelSection: O[t.reversed ? x.length - 1 - G : G] }, y._eventManager.objectMap[z] = a;
                            !y.animationEnabled && H ? m(y, -1, 0) : y.animationEnabled && !y.animatedRender && m(y, -1, 0);
                            this.funnelPyramidClickHandler = function(a) {
                                var b = -1;
                                if (!ha && !y.isAnimating && (s(a.dataSeries.explodeOnClick) || a.dataSeries.explodeOnClick) && (b = t.reversed ? x.length - 1 - a.dataPointIndex : a.dataPointIndex, 0 <= b)) {
                                    a = b;
                                    if ("funnel" === t.type || "pyramid" === t.type) t.reversed ? x[x.length - 1 - a].exploded =
                                        x[x.length - 1 - a].exploded ? !1 : !0 : x[a].exploded = x[a].exploded ? !1 : !0;
                                    m(y, b, 500)
                                }
                            };
                            return {
                                source: R,
                                dest: this.plotArea.ctx,
                                animationCallback: function(a, b) {
                                    K.fadeInAnimation(a, b);
                                    1 <= a && (m(y, -1, 500), w(y.plotArea.ctx || y.ctx))
                                },
                                easingFunction: K.easing.easeInQuad,
                                animationBase: 0
                            }
                        }
                    }
                }
            };
        m.prototype.requestAnimFrame = function() {
            return window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame || function(a) {
                window.setTimeout(a,
                    1E3 / 60)
            }
        }();
        m.prototype.cancelRequestAnimFrame = window.cancelAnimationFrame || window.webkitCancelRequestAnimationFrame || window.mozCancelRequestAnimationFrame || window.oCancelRequestAnimationFrame || window.msCancelRequestAnimationFrame || clearTimeout;
        m.prototype.set = function(a, d, c) { c = "undefined" === typeof c ? !0 : c; "options" === a ? (this.options = d, c && this.render()) : m.base.set.call(this, a, d, c) };
        m.prototype.exportChart = function(a) {
            a = "undefined" === typeof a ? {} : a;
            var d = a.format ? a.format : "png",
                c = a.fileName ? a.fileName :
                this.exportFileName;
            if (a.toDataURL) return this.canvas.toDataURL("image/" + d);
            var b = this.canvas;
            if (b && d && c) {
                c = c + "." + d;
                a = "image/" + d;
                var b = b.toDataURL(a),
                    e = !1,
                    f = document.createElement("a");
                f.download = c;
                f.href = b;
                if ("undefined" !== typeof Blob && new Blob) {
                    for (var l = b.replace(/^data:[a-z\/]*;base64,/, ""), l = atob(l), h = new ArrayBuffer(l.length), h = new Uint8Array(h), m = 0; m < l.length; m++) h[m] = l.charCodeAt(m);
                    d = new Blob([h.buffer], { type: "image/" + d });
                    try { window.navigator.msSaveBlob(d, c), e = !0 } catch (k) {
                        f.dataset.downloadurl = [a, f.download, f.href].join(":"), f.href = window.URL.createObjectURL(d)
                    }
                }
                if (!e) try { event = document.createEvent("MouseEvents"), event.initMouseEvent("click", !0, !1, window, 0, 0, 0, 0, 0, !1, !1, !1, !1, 0, null), f.dispatchEvent ? f.dispatchEvent(event) : f.fireEvent && f.fireEvent("onclick") } catch (n) { d = window.open(), d.document.write("<img src='" + b + "'></img><div>Please right click on the image and save it to your device</div>"), d.document.close() }
            }
        };
        m.prototype.print = function() {
            var a = this.exportChart({ toDataURL: !0 }),
                d = document.createElement("iframe");
            d.setAttribute("class", "canvasjs-chart-print-frame");
            d.setAttribute("style", "position:absolute; width:100%; border: 0px; margin: 0px 0px 0px 0px; padding 0px 0px 0px 0px;");
            d.style.height = this.height + "px";
            this._canvasJSContainer.appendChild(d);
            var c = this,
                b = d.contentWindow || d.contentDocument.document || d.contentDocument;
            b.document.open();
            b.document.write('<!DOCTYPE HTML>\n<html><body style="margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px;"><img src="' + a + '"/><body/></html>');
            b.document.close();
            setTimeout(function() {
                b.focus();
                b.print();
                setTimeout(function() { c._canvasJSContainer.removeChild(d) }, 1E3)
            }, 500)
        };
        m.prototype.getPercentAndTotal = function(a, d) {
            var c = null,
                b = null,
                e = c = null;
            if (0 <= a.type.indexOf("stacked")) b = 0, c = d.x.getTime ? d.x.getTime() : d.x, c in a.plotUnit.yTotals && (b = a.plotUnit.yTotals[c], c = a.plotUnit.yAbsTotals[c], e = isNaN(d.y) ? 0 : 0 === c ? 0 : 100 * (d.y / c));
            else if ("pie" === a.type || "doughnut" === a.type || "funnel" === a.type || "pyramid" === a.type) {
                for (c = b = 0; c < a.dataPoints.length; c++) isNaN(a.dataPoints[c].y) || (b += a.dataPoints[c].y);
                e = isNaN(d.y) ? 0 : 100 * (d.y / b)
            }
            return { percent: e, total: b }
        };
        m.prototype.replaceKeywordsWithValue = function(a, d, c, b, e) {
            var f = this;
            e = "undefined" === typeof e ? 0 : e;
            if ((0 <= c.type.indexOf("stacked") || "pie" === c.type || "doughnut" === c.type || "funnel" === c.type || "pyramid" === c.type) && (0 <= a.indexOf("#percent") || 0 <= a.indexOf("#total"))) {
                var l = "#percent",
                    h = "#total",
                    m = this.getPercentAndTotal(c, d),
                    h = isNaN(m.total) ? h : m.total,
                    l = isNaN(m.percent) ? l : m.percent;
                do {
                    m = "";
                    if (c.percentFormatString) m = c.percentFormatString;
                    else {
                        var m = "#,##0.",
                            k = Math.max(Math.ceil(Math.log(1 / Math.abs(l)) / Math.LN10), 2);
                        if (isNaN(k) || !isFinite(k)) k = 2;
                        for (var n = 0; n < k; n++) m += "#";
                        c.percentFormatString = m
                    }
                    a = a.replace("#percent", ea(l, m, f._cultureInfo));
                    a = a.replace("#total", ea(h, c.yValueFormatString ? c.yValueFormatString : "#,##0.########", f._cultureInfo))
                } while (0 <= a.indexOf("#percent") || 0 <= a.indexOf("#total"))
            }
            return a.replace(/\{.*?\}|"[^"]*"|'[^']*'/g, function(a) {
                if ('"' === a[0] && '"' === a[a.length - 1] || "'" === a[0] && "'" === a[a.length - 1]) return a.slice(1, a.length - 1);
                a = Fa(a.slice(1,
                    a.length - 1));
                a = a.replace("#index", e);
                var k = null;
                try {
                    var g = a.match(/(.*?)\s*\[\s*(.*?)\s*\]/);
                    g && 0 < g.length && (k = Fa(g[2]), a = Fa(g[1]))
                } catch (l) {}
                g = null;
                if ("color" === a) return "waterfall" === c.type ? d.color ? d.color : 0 < d.y ? c.risingColor : c.fallingColor : "error" === c.type ? c.color ? c.color : c._colorSet[k % c._colorSet.length] : d.color ? d.color : c.color ? c.color : c._colorSet[b % c._colorSet.length];
                if (d.hasOwnProperty(a)) g = d;
                else if (c.hasOwnProperty(a)) g = c;
                else return "";
                g = g[a];
                null !== k && (g = g[k]);
                if ("x" === a)
                    if (c.axisX && "dateTime" ===
                        c.axisX.valueType || "dateTime" === c.xValueType || d.x && d.x.getTime) { if (!c.axisX.logarithmic) return Aa(g, d.xValueFormatString ? d.xValueFormatString : c.xValueFormatString ? c.xValueFormatString : c.xValueFormatString = f.axisX && f.axisX.autoValueFormatString ? f.axisX.autoValueFormatString : "DD MMM YY", f._cultureInfo) } else return ea(g, d.xValueFormatString ? d.xValueFormatString : c.xValueFormatString ? c.xValueFormatString : c.xValueFormatString = "#,##0.########", f._cultureInfo);
                else return "y" === a ? ea(g, d.yValueFormatString ?
                    d.yValueFormatString : c.yValueFormatString ? c.yValueFormatString : c.yValueFormatString = "#,##0.########", f._cultureInfo) : "z" === a ? ea(g, d.zValueFormatString ? d.zValueFormatString : c.zValueFormatString ? c.zValueFormatString : c.zValueFormatString = "#,##0.########", f._cultureInfo) : g
            })
        };
        oa(F, U);
        F.prototype.setLayout = function() {
            var a = this.dockInsidePlotArea ? this.chart.plotArea : this.chart,
                d = a.layoutManager.getFreeSpace(),
                c = null,
                b = 0,
                e = 0,
                f = 0,
                l = 0,
                h = this.markerMargin = this.chart.options.legend && !s(this.chart.options.legend.markerMargin) ?
                this.chart.options.legend.markerMargin : 0.3 * this.fontSize;
            this.height = 0;
            var m = [],
                k = [];
            if ("top" === this.verticalAlign || "bottom" === this.verticalAlign) this.orientation = "horizontal", c = this.verticalAlign, f = this.maxWidth = null !== this.maxWidth ? this.maxWidth : d.width, l = this.maxHeight = null !== this.maxHeight ? this.maxHeight : 0.5 * d.height;
            else if ("center" === this.verticalAlign) {
                this.orientation = "vertical";
                if ("left" === this.horizontalAlign || "center" === this.horizontalAlign || "right" === this.horizontalAlign) c = this.horizontalAlign;
                f = this.maxWidth = null !== this.maxWidth ? this.maxWidth : 0.5 * d.width;
                l = this.maxHeight = null !== this.maxHeight ? this.maxHeight : d.height
            }
            this.errorMarkerColor = [];
            for (var n = 0; n < this.dataSeries.length; n++) {
                var p = this.dataSeries[n];
                if (p.dataPoints && p.dataPoints.length)
                    if ("pie" !== p.type && "doughnut" !== p.type && "funnel" !== p.type && "pyramid" !== p.type) {
                        var q = p.legendMarkerType = p.legendMarkerType ? p.legendMarkerType : "line" !== p.type && "stepLine" !== p.type && "spline" !== p.type && "scatter" !== p.type && "bubble" !== p.type || !p.markerType ?
                            "error" === p.type && p._linkedSeries ? p._linkedSeries.legendMarkerType ? p._linkedSeries.legendMarkerType : H.getDefaultLegendMarker(p._linkedSeries.type) : H.getDefaultLegendMarker(p.type) : p.markerType,
                            g = p.legendText ? p.legendText : this.itemTextFormatter ? this.itemTextFormatter({ chart: this.chart, legend: this.options, dataSeries: p, dataPoint: null }) : p.name,
                            r = p.legendMarkerColor = p.legendMarkerColor ? p.legendMarkerColor : p.markerColor ? p.markerColor : "error" === p.type ? s(p.whiskerColor) ? p._colorSet[0] : p.whiskerColor : p._colorSet[0],
                            u = p.markerSize || "line" !== p.type && "stepLine" !== p.type && "spline" !== p.type ? 0.75 * this.lineHeight : 0,
                            w = p.legendMarkerBorderColor ? p.legendMarkerBorderColor : p.markerBorderColor,
                            t = p.legendMarkerBorderThickness ? p.legendMarkerBorderThickness : p.markerBorderThickness ? Math.max(1, Math.round(0.2 * u)) : 0;
                        "error" === p.type && this.errorMarkerColor.push(r);
                        g = this.chart.replaceKeywordsWithValue(g, p.dataPoints[0], p, n);
                        q = {
                            markerType: q,
                            markerColor: r,
                            text: g,
                            textBlock: null,
                            chartType: p.type,
                            markerSize: u,
                            lineColor: p._colorSet[0],
                            dataSeriesIndex: p.index,
                            dataPointIndex: null,
                            markerBorderColor: w,
                            markerBorderThickness: t
                        };
                        m.push(q)
                    } else
                        for (var x = 0; x < p.dataPoints.length; x++) {
                            var y = p.dataPoints[x],
                                q = y.legendMarkerType ? y.legendMarkerType : p.legendMarkerType ? p.legendMarkerType : H.getDefaultLegendMarker(p.type),
                                g = y.legendText ? y.legendText : p.legendText ? p.legendText : this.itemTextFormatter ? this.itemTextFormatter({ chart: this.chart, legend: this.options, dataSeries: p, dataPoint: y }) : y.name ? y.name : "DataPoint: " + (x + 1),
                                r = y.legendMarkerColor ? y.legendMarkerColor :
                                p.legendMarkerColor ? p.legendMarkerColor : y.color ? y.color : p.color ? p.color : p._colorSet[x % p._colorSet.length],
                                u = 0.75 * this.lineHeight,
                                w = y.legendMarkerBorderColor ? y.legendMarkerBorderColor : p.legendMarkerBorderColor ? p.legendMarkerBorderColor : y.markerBorderColor ? y.markerBorderColor : p.markerBorderColor,
                                t = y.legendMarkerBorderThickness ? y.legendMarkerBorderThickness : p.legendMarkerBorderThickness ? p.legendMarkerBorderThickness : y.markerBorderThickness || p.markerBorderThickness ? Math.max(1, Math.round(0.2 * u)) : 0,
                                g = this.chart.replaceKeywordsWithValue(g,
                                    y, p, x),
                                q = { markerType: q, markerColor: r, text: g, textBlock: null, chartType: p.type, markerSize: u, dataSeriesIndex: n, dataPointIndex: x, markerBorderColor: w, markerBorderThickness: t };
                            (y.showInLegend || p.showInLegend && !1 !== y.showInLegend) && m.push(q)
                        }
            }!0 === this.reversed && m.reverse();
            if (0 < m.length) {
                p = null;
                r = g = y = x = 0;
                y = null !== this.itemWidth ? null !== this.itemMaxWidth ? Math.min(this.itemWidth, this.itemMaxWidth, f) : this.itemMaxWidth = Math.min(this.itemWidth, f) : null !== this.itemMaxWidth ? Math.min(this.itemMaxWidth, f) : this.itemMaxWidth =
                    f;
                u = 0 === u ? 0.75 * this.lineHeight : u;
                y -= u + h;
                for (n = 0; n < m.length; n++) {
                    q = m[n];
                    w = y;
                    if ("line" === q.chartType || "spline" === q.chartType || "stepLine" === q.chartType) w -= 2 * 0.1 * this.lineHeight;
                    if (!(0 >= l || "undefined" === typeof l || 0 >= w || "undefined" === typeof w)) {
                        if ("horizontal" === this.orientation) {
                            q.textBlock = new ia(this.ctx, {
                                x: 0,
                                y: 0,
                                maxWidth: w,
                                maxHeight: this.itemWrap ? l : this.lineHeight,
                                angle: 0,
                                text: q.text,
                                horizontalAlign: "left",
                                fontSize: this.fontSize,
                                fontFamily: this.fontFamily,
                                fontWeight: this.fontWeight,
                                fontColor: this.fontColor,
                                fontStyle: this.fontStyle,
                                textBaseline: "middle"
                            });
                            q.textBlock.measureText();
                            null !== this.itemWidth && (q.textBlock.width = this.itemWidth - (u + h + ("line" === q.chartType || "spline" === q.chartType || "stepLine" === q.chartType ? 2 * 0.1 * this.lineHeight : 0)));
                            if (!p || p.width + Math.round(q.textBlock.width + u + h + (0 === p.width ? 0 : this.horizontalSpacing) + ("line" === q.chartType || "spline" === q.chartType || "stepLine" === q.chartType ? 2 * 0.1 * this.lineHeight : 0)) > f) p = { items: [], width: 0 }, k.push(p), this.height += g, g = 0;
                            g = Math.max(g, q.textBlock.height)
                        } else q.textBlock =
                            new ia(this.ctx, { x: 0, y: 0, maxWidth: y, maxHeight: !0 === this.itemWrap ? l : 1.5 * this.fontSize, angle: 0, text: q.text, horizontalAlign: "left", fontSize: this.fontSize, fontFamily: this.fontFamily, fontWeight: this.fontWeight, fontColor: this.fontColor, fontStyle: this.fontStyle, textBaseline: "middle" }), q.textBlock.measureText(), null !== this.itemWidth && (q.textBlock.width = this.itemWidth - (u + h + ("line" === q.chartType || "spline" === q.chartType || "stepLine" === q.chartType ? 2 * 0.1 * this.lineHeight : 0))), this.height < l - this.lineHeight ? (p = {
                                items: [],
                                width: 0
                            }, k.push(p)) : (p = k[x], x = (x + 1) % k.length), this.height += q.textBlock.height;
                        q.textBlock.x = p.width;
                        q.textBlock.y = 0;
                        p.width += Math.round(q.textBlock.width + u + h + (0 === p.width ? 0 : this.horizontalSpacing) + ("line" === q.chartType || "spline" === q.chartType || "stepLine" === q.chartType ? 2 * 0.1 * this.lineHeight : 0));
                        p.items.push(q);
                        this.width = Math.max(p.width, this.width);
                        r = q.textBlock.width + (u + h + ("line" === q.chartType || "spline" === q.chartType || "stepLine" === q.chartType ? 2 * 0.1 * this.lineHeight : 0))
                    }
                }
                this.itemWidth = r;
                this.height = !1 === this.itemWrap ? k.length * this.lineHeight : this.height + g;
                this.height = Math.min(l, this.height);
                this.width = Math.min(f, this.width)
            }
            "top" === this.verticalAlign ? (e = "left" === this.horizontalAlign ? d.x1 : "right" === this.horizontalAlign ? d.x2 - this.width : d.x1 + d.width / 2 - this.width / 2, b = d.y1) : "center" === this.verticalAlign ? (e = "left" === this.horizontalAlign ? d.x1 : "right" === this.horizontalAlign ? d.x2 - this.width : d.x1 + d.width / 2 - this.width / 2, b = d.y1 + d.height / 2 - this.height / 2) : "bottom" === this.verticalAlign && (e = "left" === this.horizontalAlign ?
                d.x1 : "right" === this.horizontalAlign ? d.x2 - this.width : d.x1 + d.width / 2 - this.width / 2, b = d.y2 - this.height);
            this.items = m;
            for (n = 0; n < this.items.length; n++) q = m[n], q.id = ++this.chart._eventManager.lastObjectId, this.chart._eventManager.objectMap[q.id] = { id: q.id, objectType: "legendItem", legendItemIndex: n, dataSeriesIndex: q.dataSeriesIndex, dataPointIndex: q.dataPointIndex };
            this.markerSize = u;
            this.rows = k;
            0 < m.length && a.layoutManager.registerSpace(c, { width: this.width + 2 + 2, height: this.height + 5 + 5 });
            this.bounds = {
                x1: e,
                y1: b,
                x2: e +
                    this.width,
                y2: b + this.height
            }
        };
        F.prototype.render = function() {
            var a = this.bounds.x1,
                d = this.bounds.y1,
                c = this.markerMargin,
                b = this.maxWidth,
                e = this.maxHeight,
                f = this.markerSize,
                l = this.rows;
            (0 < this.borderThickness && this.borderColor || this.backgroundColor) && this.ctx.roundRect(a, d, this.width, this.height, this.cornerRadius, this.borderThickness, this.backgroundColor, this.borderColor);
            for (var h = 0, m = 0; m < l.length; m++) {
                for (var k = l[m], n = 0, p = 0; p < k.items.length; p++) {
                    var q = k.items[p],
                        g = q.textBlock.x + a + (0 === p ? 0.2 * f : this.horizontalSpacing),
                        r = d + h,
                        s = g;
                    this.chart.data[q.dataSeriesIndex].visible || (this.ctx.globalAlpha = 0.5);
                    this.ctx.save();
                    this.ctx.beginPath();
                    this.ctx.rect(a, d, b, Math.max(e - e % this.lineHeight, 0));
                    this.ctx.clip();
                    if ("line" === q.chartType || "stepLine" === q.chartType || "spline" === q.chartType) this.ctx.strokeStyle = q.lineColor, this.ctx.lineWidth = Math.ceil(this.lineHeight / 8), this.ctx.beginPath(), this.ctx.moveTo(g - 0.1 * this.lineHeight, r + this.lineHeight / 2), this.ctx.lineTo(g + 0.85 * this.lineHeight, r + this.lineHeight / 2), this.ctx.stroke(), s -=
                        0.1 * this.lineHeight;
                    if ("error" === q.chartType) {
                        this.ctx.strokeStyle = this.errorMarkerColor[0];
                        this.ctx.lineWidth = f / 8;
                        this.ctx.beginPath();
                        var u = g - 0.08 * this.lineHeight + 0.1 * this.lineHeight,
                            t = r + 0.15 * this.lineHeight,
                            x = 0.7 * this.lineHeight,
                            w = x + 0.02 * this.lineHeight;
                        this.ctx.moveTo(u, t);
                        this.ctx.lineTo(u + x, t);
                        this.ctx.stroke();
                        this.ctx.beginPath();
                        this.ctx.moveTo(u + x / 2, t);
                        this.ctx.lineTo(u + x / 2, t + w);
                        this.ctx.stroke();
                        this.ctx.beginPath();
                        this.ctx.moveTo(u, t + w);
                        this.ctx.lineTo(u + x, t + w);
                        this.ctx.stroke();
                        this.errorMarkerColor.shift()
                    }
                    V.drawMarker(g +
                        f / 2, r + this.lineHeight / 2, this.ctx, q.markerType, "error" === q.chartType || "line" === q.chartType || "spline" === q.chartType ? q.markerSize / 2 : q.markerSize, q.markerColor, q.markerBorderColor, q.markerBorderThickness);
                    q.textBlock.x = g + c + f;
                    if ("line" === q.chartType || "stepLine" === q.chartType || "spline" === q.chartType) q.textBlock.x += 0.1 * this.lineHeight;
                    q.textBlock.y = Math.round(r + this.lineHeight / 2);
                    q.textBlock.render(!0);
                    this.ctx.restore();
                    n = 0 < p ? Math.max(n, q.textBlock.height) : q.textBlock.height;
                    this.chart.data[q.dataSeriesIndex].visible ||
                        (this.ctx.globalAlpha = 1);
                    g = P(q.id);
                    this.ghostCtx.fillStyle = g;
                    this.ghostCtx.beginPath();
                    this.ghostCtx.fillRect(s, q.textBlock.y - this.lineHeight / 2, q.textBlock.x + q.textBlock.width - s, q.textBlock.height);
                    q.x1 = this.chart._eventManager.objectMap[q.id].x1 = s;
                    q.y1 = this.chart._eventManager.objectMap[q.id].y1 = q.textBlock.y - this.lineHeight / 2;
                    q.x2 = this.chart._eventManager.objectMap[q.id].x2 = q.textBlock.x + q.textBlock.width;
                    q.y2 = this.chart._eventManager.objectMap[q.id].y2 = q.textBlock.y + q.textBlock.height - this.lineHeight /
                        2
                }
                h += n
            }
        };
        oa(H, U);
        H.prototype.getDefaultAxisPlacement = function() {
            var a = this.type;
            if ("column" === a || "line" === a || "stepLine" === a || "spline" === a || "area" === a || "stepArea" === a || "splineArea" === a || "stackedColumn" === a || "stackedLine" === a || "bubble" === a || "scatter" === a || "stackedArea" === a || "stackedColumn100" === a || "stackedLine100" === a || "stackedArea100" === a || "candlestick" === a || "ohlc" === a || "rangeColumn" === a || "rangeArea" === a || "rangeSplineArea" === a || "boxAndWhisker" === a || "waterfall" === a) return "normal";
            if ("bar" === a || "stackedBar" ===
                a || "stackedBar100" === a || "rangeBar" === a) return "xySwapped";
            if ("pie" === a || "doughnut" === a || "funnel" === a || "pyramid" === a) return "none";
            "error" !== a && window.console.log("Unknown Chart Type: " + a);
            return null
        };
        H.getDefaultLegendMarker = function(a) {
            if ("column" === a || "stackedColumn" === a || "stackedLine" === a || "bar" === a || "stackedBar" === a || "stackedBar100" === a || "bubble" === a || "scatter" === a || "stackedColumn100" === a || "stackedLine100" === a || "stepArea" === a || "candlestick" === a || "ohlc" === a || "rangeColumn" === a || "rangeBar" === a || "rangeArea" ===
                a || "rangeSplineArea" === a || "boxAndWhisker" === a || "waterfall" === a) return "square";
            if ("line" === a || "stepLine" === a || "spline" === a || "pie" === a || "doughnut" === a) return "circle";
            if ("area" === a || "splineArea" === a || "stackedArea" === a || "stackedArea100" === a || "funnel" === a || "pyramid" === a) return "triangle";
            if ("error" === a) return "none";
            window.console.log("Unknown Chart Type: " + a);
            return null
        };
        H.prototype.getDataPointAtX = function(a, d) {
            if (!this.dataPoints || 0 === this.dataPoints.length) return null;
            var c = {
                    dataPoint: null,
                    distance: Infinity,
                    index: NaN
                },
                b = null,
                e = 0,
                f = 0,
                l = 1,
                h = Infinity,
                m = 0,
                k = 0,
                n = 0;
            "none" !== this.chart.plotInfo.axisPlacement && (this.axisX.logarithmic ? (n = Math.log(this.dataPoints[this.dataPoints.length - 1].x / this.dataPoints[0].x), n = 1 < n ? Math.min(Math.max((this.dataPoints.length - 1) / n * Math.log(a / this.dataPoints[0].x) >> 0, 0), this.dataPoints.length) : 0) : (n = this.dataPoints[this.dataPoints.length - 1].x - this.dataPoints[0].x, n = 0 < n ? Math.min(Math.max((this.dataPoints.length - 1) / n * (a - this.dataPoints[0].x) >> 0, 0), this.dataPoints.length) : 0));
            for (;;) {
                f =
                    0 < l ? n + e : n - e;
                if (0 <= f && f < this.dataPoints.length) {
                    var b = this.dataPoints[f],
                        p = this.axisX.logarithmic ? b.x > a ? b.x / a : a / b.x : Math.abs(b.x - a);
                    p < c.distance && (c.dataPoint = b, c.distance = p, c.index = f);
                    b = p;
                    b <= h ? h = b : 0 < l ? m++ : k++;
                    if (1E3 < m && 1E3 < k) break
                } else if (0 > n - e && n + e >= this.dataPoints.length) break; - 1 === l ? (e++, l = 1) : l = -1
            }
            return d || (c.dataPoint.x.getTime ? c.dataPoint.x.getTime() : c.dataPoint.x) !== (a.getTime ? a.getTime() : a) ? d && null !== c.dataPoint ? c : null : c
        };
        H.prototype.getDataPointAtXY = function(a, d, c) {
            if (!this.dataPoints || 0 ===
                this.dataPoints.length || a < this.chart.plotArea.x1 || a > this.chart.plotArea.x2 || d < this.chart.plotArea.y1 || d > this.chart.plotArea.y2) return null;
            c = c || !1;
            var b = [],
                e = 0,
                f = 0,
                l = 1,
                h = !1,
                m = Infinity,
                k = 0,
                n = 0,
                p = 0;
            if ("none" !== this.chart.plotInfo.axisPlacement)
                if (p = (this.chart.axisX[0] ? this.chart.axisX[0] : this.chart.axisX2[0]).getXValueAt({ x: a, y: d }), this.axisX.logarithmic) var q = Math.log(this.dataPoints[this.dataPoints.length - 1].x / this.dataPoints[0].x),
                    p = 1 < q ? Math.min(Math.max((this.dataPoints.length - 1) / q * Math.log(p /
                        this.dataPoints[0].x) >> 0, 0), this.dataPoints.length) : 0;
                else q = this.dataPoints[this.dataPoints.length - 1].x - this.dataPoints[0].x, p = 0 < q ? Math.min(Math.max((this.dataPoints.length - 1) / q * (p - this.dataPoints[0].x) >> 0, 0), this.dataPoints.length) : 0;
            for (;;) {
                f = 0 < l ? p + e : p - e;
                if (0 <= f && f < this.dataPoints.length) {
                    var q = this.chart._eventManager.objectMap[this.dataPointIds[f]],
                        g = this.dataPoints[f],
                        r = null;
                    if (q) {
                        switch (this.type) {
                            case "column":
                            case "stackedColumn":
                            case "stackedColumn100":
                            case "bar":
                            case "stackedBar":
                            case "stackedBar100":
                            case "rangeColumn":
                            case "rangeBar":
                            case "waterfall":
                            case "error":
                                a >=
                                    q.x1 && (a <= q.x2 && d >= q.y1 && d <= q.y2) && (b.push({ dataPoint: g, dataPointIndex: f, dataSeries: this, distance: Math.min(Math.abs(q.x1 - a), Math.abs(q.x2 - a), Math.abs(q.y1 - d), Math.abs(q.y2 - d)) }), h = !0);
                                break;
                            case "line":
                            case "stepLine":
                            case "spline":
                            case "area":
                            case "stepArea":
                            case "stackedArea":
                            case "stackedArea100":
                            case "splineArea":
                            case "scatter":
                                var s = la("markerSize", g, this) || 4,
                                    u = c ? 20 : s,
                                    r = Math.sqrt(Math.pow(q.x1 - a, 2) + Math.pow(q.y1 - d, 2));
                                r <= u && b.push({ dataPoint: g, dataPointIndex: f, dataSeries: this, distance: r });
                                q =
                                    Math.abs(q.x1 - a);
                                q <= m ? m = q : 0 < l ? k++ : n++;
                                r <= s / 2 && (h = !0);
                                break;
                            case "rangeArea":
                            case "rangeSplineArea":
                                s = la("markerSize", g, this) || 4;
                                u = c ? 20 : s;
                                r = Math.min(Math.sqrt(Math.pow(q.x1 - a, 2) + Math.pow(q.y1 - d, 2)), Math.sqrt(Math.pow(q.x1 - a, 2) + Math.pow(q.y2 - d, 2)));
                                r <= u && b.push({ dataPoint: g, dataPointIndex: f, dataSeries: this, distance: r });
                                q = Math.abs(q.x1 - a);
                                q <= m ? m = q : 0 < l ? k++ : n++;
                                r <= s / 2 && (h = !0);
                                break;
                            case "bubble":
                                s = q.size;
                                r = Math.sqrt(Math.pow(q.x1 - a, 2) + Math.pow(q.y1 - d, 2));
                                r <= s / 2 && (b.push({
                                    dataPoint: g,
                                    dataPointIndex: f,
                                    dataSeries: this,
                                    distance: r
                                }), h = !0);
                                break;
                            case "pie":
                            case "doughnut":
                                s = q.center;
                                u = "doughnut" === this.type ? q.percentInnerRadius * q.radius : 0;
                                r = Math.sqrt(Math.pow(s.x - a, 2) + Math.pow(s.y - d, 2));
                                r < q.radius && r > u && (r = Math.atan2(d - s.y, a - s.x), 0 > r && (r += 2 * Math.PI), r = Number(((180 * (r / Math.PI) % 360 + 360) % 360).toFixed(12)), s = Number(((180 * (q.startAngle / Math.PI) % 360 + 360) % 360).toFixed(12)), u = Number(((180 * (q.endAngle / Math.PI) % 360 + 360) % 360).toFixed(12)), 0 === u && 1 < q.endAngle && (u = 360), s >= u && 0 !== g.y && (u += 360, r < s && (r += 360)), r > s && r < u && (b.push({
                                    dataPoint: g,
                                    dataPointIndex: f,
                                    dataSeries: this,
                                    distance: 0
                                }), h = !0));
                                break;
                            case "funnel":
                            case "pyramid":
                                r = q.funnelSection;
                                d > r.y1 && d < r.y4 && (r.y6 ? d > r.y6 ? (f = r.x6 + (r.x5 - r.x6) / (r.y5 - r.y6) * (d - r.y6), r = r.x3 + (r.x4 - r.x3) / (r.y4 - r.y3) * (d - r.y3)) : (f = r.x1 + (r.x6 - r.x1) / (r.y6 - r.y1) * (d - r.y1), r = r.x2 + (r.x3 - r.x2) / (r.y3 - r.y2) * (d - r.y2)) : (f = r.x1 + (r.x4 - r.x1) / (r.y4 - r.y1) * (d - r.y1), r = r.x2 + (r.x3 - r.x2) / (r.y3 - r.y2) * (d - r.y2)), a > f && a < r && (b.push({ dataPoint: g, dataPointIndex: q.dataPointIndex, dataSeries: this, distance: 0 }), h = !0));
                                break;
                            case "boxAndWhisker":
                                if (a >=
                                    q.x1 - q.borderThickness / 2 && a <= q.x2 + q.borderThickness / 2 && d >= q.y4 - q.borderThickness / 2 && d <= q.y1 + q.borderThickness / 2 || Math.abs(q.x2 - a + q.x1 - a) < q.borderThickness && d >= q.y1 && d <= q.y4) b.push({ dataPoint: g, dataPointIndex: f, dataSeries: this, distance: Math.min(Math.abs(q.x1 - a), Math.abs(q.x2 - a), Math.abs(q.y2 - d), Math.abs(q.y3 - d)) }), h = !0;
                                break;
                            case "candlestick":
                                if (a >= q.x1 - q.borderThickness / 2 && a <= q.x2 + q.borderThickness / 2 && d >= q.y2 - q.borderThickness / 2 && d <= q.y3 + q.borderThickness / 2 || Math.abs(q.x2 - a + q.x1 - a) < q.borderThickness &&
                                    d >= q.y1 && d <= q.y4) b.push({ dataPoint: g, dataPointIndex: f, dataSeries: this, distance: Math.min(Math.abs(q.x1 - a), Math.abs(q.x2 - a), Math.abs(q.y2 - d), Math.abs(q.y3 - d)) }), h = !0;
                                break;
                            case "ohlc":
                                if (Math.abs(q.x2 - a + q.x1 - a) < q.borderThickness && d >= q.y2 && d <= q.y3 || a >= q.x1 && a <= (q.x2 + q.x1) / 2 && d >= q.y1 - q.borderThickness / 2 && d <= q.y1 + q.borderThickness / 2 || a >= (q.x1 + q.x2) / 2 && a <= q.x2 && d >= q.y4 - q.borderThickness / 2 && d <= q.y4 + q.borderThickness / 2) b.push({
                                    dataPoint: g,
                                    dataPointIndex: f,
                                    dataSeries: this,
                                    distance: Math.min(Math.abs(q.x1 - a),
                                        Math.abs(q.x2 - a), Math.abs(q.y2 - d), Math.abs(q.y3 - d))
                                }), h = !0
                        }
                        if (h || 1E3 < k && 1E3 < n) break
                    }
                } else if (0 > p - e && p + e >= this.dataPoints.length) break; - 1 === l ? (e++, l = 1) : l = -1
            }
            a = null;
            for (d = 0; d < b.length; d++) a ? b[d].distance <= a.distance && (a = b[d]) : a = b[d];
            return a
        };
        H.prototype.getMarkerProperties = function(a, d, c, b) {
            var e = this.dataPoints;
            return {
                x: d,
                y: c,
                ctx: b,
                type: e[a].markerType ? e[a].markerType : this.markerType,
                size: e[a].markerSize ? e[a].markerSize : this.markerSize,
                color: e[a].markerColor ? e[a].markerColor : this.markerColor ? this.markerColor : e[a].color ? e[a].color : this.color ? this.color : this._colorSet[a % this._colorSet.length],
                borderColor: e[a].markerBorderColor ? e[a].markerBorderColor : this.markerBorderColor ? this.markerBorderColor : null,
                borderThickness: e[a].markerBorderThickness ? e[a].markerBorderThickness : this.markerBorderThickness ? this.markerBorderThickness : null
            }
        };
        oa(D, U);
        D.prototype.createExtraLabelsForLog = function(a) {
            a = (a || 0) + 1;
            if (!(5 < a)) {
                var d = this.logLabelValues[0] || this.intervalStartPosition;
                if (Math.log(this.range) / Math.log(d / this.viewportMinimum) <
                    this.noTicks - 1) {
                    for (var c = D.getNiceNumber((d - this.viewportMinimum) / Math.min(Math.max(2, this.noTicks - this.logLabelValues.length), 3), !0), b = Math.ceil(this.viewportMinimum / c) * c; b < d; b += c) b < this.viewportMinimum || this.logLabelValues.push(b);
                    this.logLabelValues.sort(Pa);
                    this.createExtraLabelsForLog(a)
                }
            }
        };
        D.prototype.createLabels = function() {
            var a, d, c = 0,
                b = 0,
                e, f = 0,
                l = 0,
                b = 0,
                b = this.interval,
                h = 0,
                m, k = 0.6 * this.chart.height,
                n;
            a = !1;
            var p = this.scaleBreaks ? this.scaleBreaks._appliedBreaks : [],
                q = p.length ? s(this.scaleBreaks.firstBreakIndex) ?
                0 : this.scaleBreaks.firstBreakIndex : 0;
            if ("axisX" !== this.type || "dateTime" !== this.valueType || this.logarithmic) {
                e = this.viewportMaximum;
                if (this.labels) {
                    a = Math.ceil(b);
                    for (var b = Math.ceil(this.intervalStartPosition), g = !1, c = b; c < this.viewportMaximum; c += a)
                        if (this.labels[c]) g = !0;
                        else { g = !1; break }
                    g && (this.interval = a, this.intervalStartPosition = b)
                }
                if (this.logarithmic && !this.equidistantInterval)
                    for (this.logLabelValues || (this.logLabelValues = [], this.createExtraLabelsForLog()), b = 0, g = q; b < this.logLabelValues.length; b++)
                        if (c =
                            this.logLabelValues[b], c < this.viewportMinimum) b++;
                        else {
                            for (; g < p.length && c > p[g].endValue; g++);
                            a = g < p.length && c >= p[g].startValue && c <= p[g].endValue;
                            n = c;
                            a || (a = this.labelFormatter ? this.labelFormatter({ chart: this.chart, axis: this.options, value: n, label: this.labels[n] ? this.labels[n] : null }) : "axisX" === this.type && this.labels[n] ? this.labels[n] : ea(n, this.valueFormatString, this.chart._cultureInfo), a = new ia(this.ctx, {
                                x: 0,
                                y: 0,
                                maxWidth: f,
                                maxHeight: l,
                                angle: this.labelAngle,
                                text: this.prefix + a + this.suffix,
                                backgroundColor: this.labelBackgroundColor,
                                borderColor: this.labelBorderColor,
                                cornerRadius: this.labelCornerRadius,
                                textAlign: this.labelTextAlign,
                                fontSize: this.labelFontSize,
                                fontFamily: this.labelFontFamily,
                                fontWeight: this.labelFontWeight,
                                fontColor: this.labelFontColor,
                                fontStyle: this.labelFontStyle,
                                textBaseline: "middle",
                                borderThickness: 0
                            }), this._labels.push({ position: n, textBlock: a, effectiveHeight: null }))
                        }
                g = q;
                for (c = this.intervalStartPosition; c <= e; c = parseFloat(1E-12 > this.interval ? this.logarithmic && this.equidistantInterval ? c * Math.pow(this.logarithmBase,
                        this.interval) : c + this.interval : (this.logarithmic && this.equidistantInterval ? c * Math.pow(this.logarithmBase, this.interval) : c + this.interval).toFixed(12))) {
                    for (; g < p.length && c > p[g].endValue; g++);
                    a = g < p.length && c >= p[g].startValue && c <= p[g].endValue;
                    n = c;
                    a || (a = this.labelFormatter ? this.labelFormatter({ chart: this.chart, axis: this.options, value: n, label: this.labels[n] ? this.labels[n] : null }) : "axisX" === this.type && this.labels[n] ? this.labels[n] : ea(n, this.valueFormatString, this.chart._cultureInfo), a = new ia(this.ctx, {
                        x: 0,
                        y: 0,
                        maxWidth: f,
                        maxHeight: l,
                        angle: this.labelAngle,
                        text: this.prefix + a + this.suffix,
                        textAlign: this.labelTextAlign,
                        backgroundColor: this.labelBackgroundColor,
                        borderColor: this.labelBorderColor,
                        borderThickness: this.labelBorderThickness,
                        cornerRadius: this.labelCornerRadius,
                        fontSize: this.labelFontSize,
                        fontFamily: this.labelFontFamily,
                        fontWeight: this.labelFontWeight,
                        fontColor: this.labelFontColor,
                        fontStyle: this.labelFontStyle,
                        textBaseline: "middle"
                    }), this._labels.push({ position: n, textBlock: a, effectiveHeight: null }))
                }
            } else
                for (this.intervalStartPosition =
                    this.getLabelStartPoint(new Date(this.viewportMinimum), this.intervalType, this.interval), e = Va(new Date(this.viewportMaximum), this.interval, this.intervalType), g = q, c = this.intervalStartPosition; c < e; Va(c, b, this.intervalType)) {
                    for (a = c.getTime(); g < p.length && a > p[g].endValue; g++);
                    n = a;
                    a = g < p.length && a >= p[g].startValue && a <= p[g].endValue;
                    a || (a = this.labelFormatter ? this.labelFormatter({ chart: this.chart, axis: this.options, value: new Date(n), label: this.labels[n] ? this.labels[n] : null }) : "axisX" === this.type && this.labels[n] ?
                        this.labels[n] : Aa(n, this.valueFormatString, this.chart._cultureInfo), a = new ia(this.ctx, {
                            x: 0,
                            y: 0,
                            maxWidth: f,
                            backgroundColor: this.labelBackgroundColor,
                            borderColor: this.labelBorderColor,
                            borderThickness: this.labelBorderThickness,
                            cornerRadius: this.labelCornerRadius,
                            maxHeight: l,
                            angle: this.labelAngle,
                            text: this.prefix + a + this.suffix,
                            textAlign: this.labelTextAlign,
                            fontSize: this.labelFontSize,
                            fontFamily: this.labelFontFamily,
                            fontWeight: this.labelFontWeight,
                            fontColor: this.labelFontColor,
                            fontStyle: this.labelFontStyle,
                            textBaseline: "middle"
                        }), this._labels.push({ position: n, textBlock: a, effectiveHeight: null, breaksLabelType: void 0 }))
                }
            if ("bottom" === this._position || "top" === this._position) h = this.logarithmic && !this.equidistantInterval && 2 <= this._labels.length ? this.lineCoordinates.width * Math.log(Math.min(this._labels[this._labels.length - 1].position / this._labels[this._labels.length - 2].position, this._labels[1].position / this._labels[0].position)) / Math.log(this.range) : this.lineCoordinates.width / (this.logarithmic && this.equidistantInterval ?
                Math.log(this.range) / Math.log(this.logarithmBase) : Math.abs(this.range)) * R[this.intervalType + "Duration"] * this.interval, f = "undefined" === typeof this.options.labelMaxWidth ? 0.5 * this.chart.width >> 0 : this.options.labelMaxWidth, this.chart.panEnabled || (l = "undefined" === typeof this.options.labelWrap || this.labelWrap ? 0.8 * this.chart.height >> 0 : 1.5 * this.labelFontSize);
            else if ("left" === this._position || "right" === this._position) h = this.logarithmic && !this.equidistantInterval && 2 <= this._labels.length ? this.lineCoordinates.height *
                Math.log(Math.min(this._labels[this._labels.length - 1].position / this._labels[this._labels.length - 2].position, this._labels[1].position / this._labels[0].position)) / Math.log(this.range) : this.lineCoordinates.height / (this.logarithmic && this.equidistantInterval ? Math.log(this.range) / Math.log(this.logarithmBase) : Math.abs(this.range)) * R[this.intervalType + "Duration"] * this.interval, this.chart.panEnabled || (f = "undefined" === typeof this.options.labelMaxWidth ? 0.3 * this.chart.width >> 0 : this.options.labelMaxWidth), l = "undefined" ===
                typeof this.options.labelWrap || this.labelWrap ? 0.3 * this.chart.height >> 0 : 1.5 * this.labelFontSize;
            for (b = 0; b < this._labels.length; b++) {
                a = this._labels[b].textBlock;
                a.maxWidth = f;
                a.maxHeight = l;
                var r = a.measureText();
                m = r.height
            }
            e = [];
            q = p = 0;
            if (this.labelAutoFit || this.options.labelAutoFit)
                if (s(this.labelAngle) || (this.labelAngle = (this.labelAngle % 360 + 360) % 360, 90 < this.labelAngle && 270 > this.labelAngle ? this.labelAngle -= 180 : 270 <= this.labelAngle && 360 >= this.labelAngle && (this.labelAngle -= 360)), "bottom" === this._position ||
                    "top" === this._position)
                    if (f = 0.9 * h >> 0, q = 0, !this.chart.panEnabled && 1 <= this._labels.length) {
                        this.sessionVariables.labelFontSize = this.labelFontSize;
                        this.sessionVariables.labelMaxWidth = f;
                        this.sessionVariables.labelMaxHeight = l;
                        this.sessionVariables.labelAngle = this.labelAngle;
                        this.sessionVariables.labelWrap = this.labelWrap;
                        for (c = 0; c < this._labels.length; c++)
                            if (!this._labels[c].breaksLabelType) {
                                a = this._labels[c].textBlock;
                                for (var w, g = a.text.split(" "), b = 0; b < g.length; b++) n = g[b], this.ctx.font = a.fontStyle + " " +
                                    a.fontWeight + " " + a.fontSize + "px " + a.fontFamily, n = this.ctx.measureText(n), n.width > q && (w = c, q = n.width)
                            }
                        c = 0;
                        for (c = this.intervalStartPosition < this.viewportMinimum ? 1 : 0; c < this._labels.length; c++)
                            if (!this._labels[c].breaksLabelType) {
                                a = this._labels[c].textBlock;
                                r = a.measureText();
                                for (g = c + 1; g < this._labels.length; g++)
                                    if (!this._labels[g].breaksLabelType) {
                                        d = this._labels[g].textBlock;
                                        d = d.measureText();
                                        break
                                    }
                                e.push(a.height);
                                this.sessionVariables.labelMaxHeight = Math.max.apply(Math, e);
                                Math.cos(Math.PI / 180 * Math.abs(this.labelAngle));
                                Math.sin(Math.PI / 180 * Math.abs(this.labelAngle));
                                b = f * Math.sin(Math.PI / 180 * Math.abs(this.labelAngle)) + (l - a.fontSize / 2) * Math.cos(Math.PI / 180 * Math.abs(this.labelAngle));
                                if (s(this.options.labelAngle) && isNaN(this.options.labelAngle) && 0 !== this.options.labelAngle)
                                    if (this.sessionVariables.labelMaxHeight = 0 === this.labelAngle ? l : Math.min((b - f * Math.cos(Math.PI / 180 * Math.abs(this.labelAngle))) / Math.sin(Math.PI / 180 * Math.abs(this.labelAngle)), b), n = (k - (m + a.fontSize / 2) * Math.cos(Math.PI / 180 * Math.abs(-25))) / Math.sin(Math.PI /
                                            180 * Math.abs(-25)), !s(this.options.labelWrap)) this.labelWrap ? s(this.options.labelMaxWidth) ? (this.sessionVariables.labelMaxWidth = Math.min(Math.max(f, q), n), this.sessionVariables.labelWrap = this.labelWrap, d && r.width + d.width >> 0 > 2 * f && (this.sessionVariables.labelAngle = -25)) : (this.sessionVariables.labelWrap = this.labelWrap, this.sessionVariables.labelMaxWidth = this.options.labelMaxWidth, this.sessionVariables.labelAngle = this.sessionVariables.labelMaxWidth > f ? -25 : this.sessionVariables.labelAngle) : s(this.options.labelMaxWidth) ?
                                        (this.sessionVariables.labelWrap = this.labelWrap, this.sessionVariables.labelMaxHeight = l, this.sessionVariables.labelMaxWidth = f, d && r.width + d.width >> 0 > 2 * f && (this.sessionVariables.labelAngle = -25, this.sessionVariables.labelMaxWidth = n)) : (this.sessionVariables.labelAngle = this.sessionVariables.labelMaxWidth > f ? -25 : this.sessionVariables.labelAngle, this.sessionVariables.labelMaxWidth = this.options.labelMaxWidth, this.sessionVariables.labelMaxHeight = l, this.sessionVariables.labelWrap = this.labelWrap);
                                    else {
                                        if (s(this.options.labelWrap))
                                            if (!s(this.options.labelMaxWidth)) this.options.labelMaxWidth <
                                                f ? (this.sessionVariables.labelMaxWidth = this.options.labelMaxWidth, this.sessionVariables.labelMaxHeight = b) : (this.sessionVariables.labelAngle = -25, this.sessionVariables.labelMaxWidth = this.options.labelMaxWidth, this.sessionVariables.labelMaxHeight = l);
                                            else if (!s(d))
                                            if (b = r.width + d.width >> 0, g = this.labelFontSize, q < f) b - 2 * f > p && (p = b - 2 * f, b >= 2 * f && b < 2.2 * f ? (this.sessionVariables.labelMaxWidth = f, s(this.options.labelFontSize) && 12 < g && (g = Math.floor(12 / 13 * g), a.measureText()), this.sessionVariables.labelFontSize = s(this.options.labelFontSize) ?
                                                g : this.options.labelFontSize, this.sessionVariables.labelAngle = this.labelAngle) : b >= 2.2 * f && b < 2.8 * f ? (this.sessionVariables.labelAngle = -25, this.sessionVariables.labelMaxWidth = n, this.sessionVariables.labelFontSize = g) : b >= 2.8 * f && b < 3.2 * f ? (this.sessionVariables.labelMaxWidth = Math.max(f, q), this.sessionVariables.labelWrap = !0, s(this.options.labelFontSize) && 12 < this.labelFontSize && (this.labelFontSize = Math.floor(12 / 13 * this.labelFontSize), a.measureText()), this.sessionVariables.labelFontSize = s(this.options.labelFontSize) ?
                                                g : this.options.labelFontSize, this.sessionVariables.labelAngle = this.labelAngle) : b >= 3.2 * f && b < 3.6 * f ? (this.sessionVariables.labelAngle = -25, this.sessionVariables.labelWrap = !0, this.sessionVariables.labelMaxWidth = n, this.sessionVariables.labelFontSize = this.labelFontSize) : b > 3.6 * f && b < 5 * f ? (s(this.options.labelFontSize) && 12 < g && (g = Math.floor(12 / 13 * g), a.measureText()), this.sessionVariables.labelFontSize = s(this.options.labelFontSize) ? g : this.options.labelFontSize, this.sessionVariables.labelWrap = !0, this.sessionVariables.labelAngle = -25, this.sessionVariables.labelMaxWidth = n) : b > 5 * f && (this.sessionVariables.labelWrap = !0, this.sessionVariables.labelMaxWidth = f, this.sessionVariables.labelFontSize = g, this.sessionVariables.labelMaxHeight = l, this.sessionVariables.labelAngle = this.labelAngle));
                                            else if (w === c && (0 === w && q + this._labels[w + 1].textBlock.measureText().width - 2 * f > p || w === this._labels.length - 1 && q + this._labels[w - 1].textBlock.measureText().width - 2 * f > p || 0 < w && w < this._labels.length - 1 && q + this._labels[w + 1].textBlock.measureText().width - 2 * f > p &&
                                                q + this._labels[w - 1].textBlock.measureText().width - 2 * f > p)) p = 0 === w ? q + this._labels[w + 1].textBlock.measureText().width - 2 * f : q + this._labels[w - 1].textBlock.measureText().width - 2 * f, this.sessionVariables.labelFontSize = s(this.options.labelFontSize) ? g : this.options.labelFontSize, this.sessionVariables.labelWrap = !0, this.sessionVariables.labelAngle = -25, this.sessionVariables.labelMaxWidth = n;
                                        else if (0 === p)
                                            for (this.sessionVariables.labelFontSize = s(this.options.labelFontSize) ? g : this.options.labelFontSize, this.sessionVariables.labelWrap = !0, b = 0; b < this._labels.length; b++) a = this._labels[b].textBlock, a.maxWidth = this.sessionVariables.labelMaxWidth = Math.min(Math.max(f, q), n), r = a.measureText(), b < this._labels.length - 1 && (g = b + 1, d = this._labels[g].textBlock, d.maxWidth = this.sessionVariables.labelMaxWidth = Math.min(Math.max(f, q), n), d = d.measureText(), r.width + d.width >> 0 > 2 * f && (this.sessionVariables.labelAngle = -25))
                                    }
                                else(this.sessionVariables.labelAngle = this.labelAngle, this.sessionVariables.labelMaxHeight = 0 === this.labelAngle ? l : Math.min((b - f * Math.cos(Math.PI /
                                    180 * Math.abs(this.labelAngle))) / Math.sin(Math.PI / 180 * Math.abs(this.labelAngle)), b), n = 0 != this.labelAngle ? (k - (m + a.fontSize / 2) * Math.cos(Math.PI / 180 * Math.abs(this.labelAngle))) / Math.sin(Math.PI / 180 * Math.abs(this.labelAngle)) : f, this.sessionVariables.labelMaxHeight = this.labelWrap ? (k - n * Math.sin(Math.PI / 180 * Math.abs(this.labelAngle))) / Math.cos(Math.PI / 180 * Math.abs(this.labelAngle)) : 1.5 * this.labelFontSize, s(this.options.labelWrap)) ? s(this.options.labelWrap) && (this.labelWrap && !s(this.options.labelMaxWidth) ?
                                    (this.sessionVariables.labelWrap = this.labelWrap, this.sessionVariables.labelMaxWidth = this.options.labelMaxWidth ? this.options.labelMaxWidth : n, this.sessionVariables.labelMaxHeight = l) : (this.sessionVariables.labelAngle = this.labelAngle, this.sessionVariables.labelMaxWidth = n, this.sessionVariables.labelMaxHeight = b < 0.9 * h ? 0.9 * h : b, this.sessionVariables.labelWrap = this.labelWrap)) : (this.options.labelWrap ? (this.sessionVariables.labelWrap = this.labelWrap, this.sessionVariables.labelMaxWidth = this.options.labelMaxWidth ?
                                    this.options.labelMaxWidth : n) : (s(this.options.labelMaxWidth), this.sessionVariables.labelMaxWidth = this.options.labelMaxWidth ? this.options.labelMaxWidth : n, this.sessionVariables.labelWrap = this.labelWrap), this.sessionVariables.labelMaxHeight = l)
                            }
                        for (b = 0; b < this._labels.length; b++) a = this._labels[b].textBlock, a.maxWidth = this.labelMaxWidth = this.sessionVariables.labelMaxWidth, a.fontSize = this.sessionVariables.labelFontSize, a.angle = this.labelAngle = this.sessionVariables.labelAngle, a.wrap = this.labelWrap = this.sessionVariables.labelWrap,
                            a.maxHeight = this.sessionVariables.labelMaxHeight, a.measureText()
                    } else
                        for (c = 0; c < this._labels.length; c++) a = this._labels[c].textBlock, a.maxWidth = this.labelMaxWidth = s(this.options.labelMaxWidth) ? s(this.sessionVariables.labelMaxWidth) ? this.sessionVariables.labelMaxWidth = f : this.sessionVariables.labelMaxWidth : this.options.labelMaxWidth, a.fontSize = this.labelFontSize = s(this.options.labelFontSize) ? s(this.sessionVariables.labelFontSize) ? this.sessionVariables.labelFontSize = this.labelFontSize : this.sessionVariables.labelFontSize :
                            this.options.labelFontSize, a.angle = this.labelAngle = s(this.options.labelAngle) ? s(this.sessionVariables.labelAngle) ? this.sessionVariables.labelAngle = this.labelAngle : this.sessionVariables.labelAngle : this.labelAngle, a.wrap = this.labelWrap = s(this.options.labelWrap) ? s(this.sessionVariables.labelWrap) ? this.sessionVariables.labelWrap = this.labelWrap : this.sessionVariables.labelWrap : this.options.labelWrap, a.maxHeight = s(this.sessionVariables.labelMaxHeight) ? this.sessionVariables.labelMaxHeight = l : this.sessionVariables.labelMaxHeight,
                            a.measureText();
            else if ("left" === this._position || "right" === this._position)
                if (f = s(this.options.labelMaxWidth) ? 0.3 * this.chart.width >> 0 : this.options.labelMaxWidth, l = "undefined" === typeof this.options.labelWrap || this.labelWrap ? 0.3 * this.chart.height >> 0 : 1.5 * this.labelFontSize, !this.chart.panEnabled && 1 <= this._labels.length) {
                    this.sessionVariables.labelFontSize = this.labelFontSize;
                    this.sessionVariables.labelMaxWidth = f;
                    this.sessionVariables.labelMaxHeight = l;
                    this.sessionVariables.labelAngle = s(this.sessionVariables.labelAngle) ?
                        0 : this.sessionVariables.labelAngle;
                    this.sessionVariables.labelWrap = this.labelWrap;
                    for (c = 0; c < this._labels.length; c++)
                        if (!this._labels[c].breaksLabelType) {
                            a = this._labels[c].textBlock;
                            r = a.measureText();
                            for (g = c + 1; g < this._labels.length; g++)
                                if (!this._labels[g].breaksLabelType) {
                                    d = this._labels[g].textBlock;
                                    d = d.measureText();
                                    break
                                }
                            e.push(a.height);
                            this.sessionVariables.labelMaxHeight = Math.max.apply(Math, e);
                            b = f * Math.sin(Math.PI / 180 * Math.abs(this.labelAngle)) + (l - a.fontSize / 2) * Math.cos(Math.PI / 180 * Math.abs(this.labelAngle));
                            Math.cos(Math.PI / 180 * Math.abs(this.labelAngle));
                            Math.sin(Math.PI / 180 * Math.abs(this.labelAngle));
                            s(this.options.labelAngle) && isNaN(this.options.labelAngle) && 0 !== this.options.labelAngle ? s(this.options.labelWrap) ? s(this.options.labelWrap) && (s(this.options.labelMaxWidth) ? s(d) || (h = r.height + d.height >> 0, h - 2 * l > q && (q = h - 2 * l, h >= 2 * l && h < 2.4 * l ? (s(this.options.labelFontSize) && 12 < this.labelFontSize && (this.labelFontSize = Math.floor(12 / 13 * this.labelFontSize), a.measureText()), this.sessionVariables.labelMaxHeight = l, this.sessionVariables.labelFontSize =
                                s(this.options.labelFontSize) ? this.labelFontSize : this.options.labelFontSize) : h >= 2.4 * l && h < 2.8 * l ? (this.sessionVariables.labelMaxHeight = b, this.sessionVariables.labelFontSize = this.labelFontSize, this.sessionVariables.labelWrap = !0) : h >= 2.8 * l && h < 3.2 * l ? (this.sessionVariables.labelMaxHeight = l, this.sessionVariables.labelWrap = !0, s(this.options.labelFontSize) && 12 < this.labelFontSize && (this.labelFontSize = Math.floor(12 / 13 * this.labelFontSize), a.measureText()), this.sessionVariables.labelFontSize = s(this.options.labelFontSize) ?
                                this.labelFontSize : this.options.labelFontSize, this.sessionVariables.labelAngle = s(this.sessionVariables.labelAngle) ? 0 : this.sessionVariables.labelAngle) : h >= 3.2 * l && h < 3.6 * l ? (this.sessionVariables.labelMaxHeight = b, this.sessionVariables.labelWrap = !0, this.sessionVariables.labelFontSize = this.labelFontSize) : h > 3.6 * l && h < 10 * l ? (s(this.options.labelFontSize) && 12 < this.labelFontSize && (this.labelFontSize = Math.floor(12 / 13 * this.labelFontSize), a.measureText()), this.sessionVariables.labelFontSize = s(this.options.labelFontSize) ?
                                this.labelFontSize : this.options.labelFontSize, this.sessionVariables.labelMaxWidth = f, this.sessionVariables.labelMaxHeight = l, this.sessionVariables.labelAngle = s(this.sessionVariables.labelAngle) ? 0 : this.sessionVariables.labelAngle) : h > 10 * l && h < 50 * l && (s(this.options.labelFontSize) && 12 < this.labelFontSize && (this.labelFontSize = Math.floor(12 / 13 * this.labelFontSize), a.measureText()), this.sessionVariables.labelFontSize = s(this.options.labelFontSize) ? this.labelFontSize : this.options.labelFontSize, this.sessionVariables.labelMaxHeight =
                                l, this.sessionVariables.labelMaxWidth = f, this.sessionVariables.labelAngle = s(this.sessionVariables.labelAngle) ? 0 : this.sessionVariables.labelAngle))) : (this.sessionVariables.labelMaxHeight = l, this.sessionVariables.labelMaxWidth = this.options.labelMaxWidth ? this.options.labelMaxWidth : this.sessionVariables.labelMaxWidth)) : (this.sessionVariables.labelMaxWidth = this.labelWrap ? this.options.labelMaxWidth ? this.options.labelMaxWidth : this.sessionVariables.labelMaxWidth : this.labelMaxWidth ? this.options.labelMaxWidth ?
                                this.options.labelMaxWidth : this.sessionVariables.labelMaxWidth : f, this.sessionVariables.labelMaxHeight = l) : (this.sessionVariables.labelAngle = this.labelAngle, this.sessionVariables.labelMaxWidth = 0 === this.labelAngle ? f : Math.min((b - l * Math.sin(Math.PI / 180 * Math.abs(this.labelAngle))) / Math.cos(Math.PI / 180 * Math.abs(this.labelAngle)), l), s(this.options.labelWrap)) ? s(this.options.labelWrap) && (this.labelWrap && !s(this.options.labelMaxWidth) ? (this.sessionVariables.labelMaxWidth = this.options.labelMaxWidth ? this.options.labelMaxWidth :
                                this.sessionVariables.labelMaxWidth, this.sessionVariables.labelWrap = this.labelWrap, this.sessionVariables.labelMaxHeight = b) : (this.sessionVariables.labelMaxWidth = this.options.labelMaxWidth ? this.options.labelMaxWidth : f, this.sessionVariables.labelMaxHeight = 0 === this.labelAngle ? l : b, s(this.options.labelMaxWidth) && (this.sessionVariables.labelAngle = this.labelAngle))) : this.options.labelWrap ? (this.sessionVariables.labelMaxHeight = 0 === this.labelAngle ? l : b, this.sessionVariables.labelWrap = this.labelWrap, this.sessionVariables.labelMaxWidth =
                                f) : (this.sessionVariables.labelMaxHeight = l, s(this.options.labelMaxWidth), this.sessionVariables.labelMaxWidth = this.options.labelMaxWidth ? this.options.labelMaxWidth : this.sessionVariables.labelMaxWidth, this.sessionVariables.labelWrap = this.labelWrap)
                        }
                    for (b = 0; b < this._labels.length; b++) a = this._labels[b].textBlock, a.maxWidth = this.labelMaxWidth = this.sessionVariables.labelMaxWidth, a.fontSize = this.labelFontSize = this.sessionVariables.labelFontSize, a.angle = this.labelAngle = this.sessionVariables.labelAngle, a.wrap =
                        this.labelWrap = this.sessionVariables.labelWrap, a.maxHeight = this.sessionVariables.labelMaxHeight, a.measureText()
                } else
                    for (c = 0; c < this._labels.length; c++) a = this._labels[c].textBlock, a.maxWidth = this.labelMaxWidth = s(this.options.labelMaxWidth) ? s(this.sessionVariables.labelMaxWidth) ? this.sessionVariables.labelMaxWidth = f : this.sessionVariables.labelMaxWidth : this.options.labelMaxWidth, a.fontSize = this.labelFontSize = s(this.options.labelFontSize) ? s(this.sessionVariables.labelFontSize) ? this.sessionVariables.labelFontSize =
                        this.labelFontSize : this.sessionVariables.labelFontSize : this.options.labelFontSize, a.angle = this.labelAngle = s(this.options.labelAngle) ? s(this.sessionVariables.labelAngle) ? this.sessionVariables.labelAngle = this.labelAngle : this.sessionVariables.labelAngle : this.labelAngle, a.wrap = this.labelWrap = s(this.options.labelWrap) ? s(this.sessionVariables.labelWrap) ? this.sessionVariables.labelWrap = this.labelWrap : this.sessionVariables.labelWrap : this.options.labelWrap, a.maxHeight = s(this.sessionVariables.labelMaxHeight) ?
                        this.sessionVariables.labelMaxHeight = l : this.sessionVariables.labelMaxHeight, a.measureText();
            for (c = 0; c < this.stripLines.length; c++) {
                var f = this.stripLines[c],
                    y;
                if ("outside" === f.labelPlacement) {
                    l = this.sessionVariables.labelMaxWidth;
                    if ("bottom" === this._position || "top" === this._position) s(f.options.labelWrap) && !s(this.sessionVariables.stripLineLabelMaxHeight) ? y = this.sessionVariables.stripLineLabelMaxHeight : this.sessionVariables.stripLineLabelMaxHeight = y = f.labelWrap ? 0.8 * this.chart.height >> 0 : 1.5 * this.labelFontSize;
                    if ("left" === this._position || "right" === this._position) s(f.options.labelWrap) && !s(this.sessionVariables.stripLineLabelMaxHeight) ? y = this.sessionVariables.stripLineLabelMaxHeight : this.sessionVariables.stripLineLabelMaxHeight = y = f.labelWrap ? 0.8 * this.chart.width >> 0 : 1.5 * this.labelFontSize;
                    s(f.labelBackgroundColor) && (f.labelBackgroundColor = "#EEEEEE")
                } else l = "bottom" === this._position || "top" === this._position ? 0.9 * this.chart.width >> 0 : 0.9 * this.chart.height >> 0, y = s(f.options.labelWrap) || f.labelWrap ? "bottom" === this._position ||
                    "top" === this._position ? 0.8 * this.chart.width >> 0 : 0.8 * this.chart.height >> 0 : 1.5 * this.labelFontSize, s(f.labelBackgroundColor) && (s(f.startValue) && 0 !== f.startValue ? f.labelBackgroundColor = u ? "transparent" : null : f.labelBackgroundColor = "#EEEEEE");
                a = new ia(this.ctx, {
                    x: 0,
                    y: 0,
                    backgroundColor: f.labelBackgroundColor,
                    borderColor: f.labelBorderColor,
                    borderThickness: f.labelBorderThickness,
                    cornerRadius: f.labelCornerRadius,
                    maxWidth: f.options.labelMaxWidth ? f.options.labelMaxWidth : l,
                    maxHeight: y,
                    angle: this.labelAngle,
                    text: f.labelFormatter ?
                        f.labelFormatter({ chart: this.chart, axis: this, stripLine: f }) : f.label,
                    textAlign: this.labelTextAlign,
                    fontSize: "outside" === f.labelPlacement ? f.options.labelFontSize ? f.labelFontSize : this.labelFontSize : f.labelFontSize,
                    fontFamily: "outside" === f.labelPlacement ? f.options.labelFontFamily ? f.labelFontFamily : this.labelFontFamily : f.labelFontFamily,
                    fontWeight: "outside" === f.labelPlacement ? f.options.labelFontWeight ? f.labelFontWeight : this.labelFontWeight : f.labelFontWeight,
                    fontColor: f.labelFontColor || f.color,
                    fontStyle: "outside" ===
                        f.labelPlacement ? f.options.labelFontStyle ? f.labelFontStyle : this.fontWeight : f.labelFontStyle,
                    textBaseline: "middle"
                });
                this._stripLineLabels.push({ position: f.value, textBlock: a, effectiveHeight: null, stripLine: f })
            }
        };
        D.prototype.createLabelsAndCalculateWidth = function() {
            var a = 0,
                d = 0;
            this._labels = [];
            this._stripLineLabels = [];
            var c = this.chart.isNavigator ? 0 : 5;
            if ("left" === this._position || "right" === this._position) {
                this.createLabels();
                if ("inside" != this.labelPlacement || "inside" === this.labelPlacement && 0 < this._index)
                    for (d =
                        0; d < this._labels.length; d++) {
                        var b = this._labels[d].textBlock,
                            e = b.measureText(),
                            f = 0,
                            f = 0 === this.labelAngle ? e.width : e.width * Math.cos(Math.PI / 180 * Math.abs(this.labelAngle)) + (e.height - b.fontSize / 2) * Math.sin(Math.PI / 180 * Math.abs(this.labelAngle));
                        a < f && (a = f);
                        this._labels[d].effectiveWidth = f
                    }
                for (d = 0; d < this._stripLineLabels.length; d++) "outside" === this._stripLineLabels[d].stripLine.labelPlacement && (this._stripLineLabels[d].stripLine.value >= this.viewportMinimum && this._stripLineLabels[d].stripLine.value <= this.viewportMaximum) &&
                    (b = this._stripLineLabels[d].textBlock, e = b.measureText(), f = 0 === this.labelAngle ? e.width : e.width * Math.cos(Math.PI / 180 * Math.abs(this.labelAngle)) + (e.height - b.fontSize / 2) * Math.sin(Math.PI / 180 * Math.abs(this.labelAngle)), a < f && (a = f), this._stripLineLabels[d].effectiveWidth = f)
            }
            return (this.title ? this._titleTextBlock.measureText().height + 2 : 0) + a + ("inside" === this.tickPlacement ? 0 : this.tickLength) + c
        };
        D.prototype.createLabelsAndCalculateHeight = function() {
            var a = 0;
            this._labels = [];
            this._stripLineLabels = [];
            var d, c = 0,
                b = this.chart.isNavigator ? 0 : 5;
            if ("bottom" === this._position || "top" === this._position) {
                this.createLabels();
                if ("inside" != this.labelPlacement || "inside" === this.labelPlacement && 0 < this._index)
                    for (c = 0; c < this._labels.length; c++) {
                        d = this._labels[c].textBlock;
                        var e = d.measureText(),
                            f = 0,
                            f = 0 === this.labelAngle ? e.height : e.width * Math.sin(Math.PI / 180 * Math.abs(this.labelAngle)) + (e.height - d.fontSize / 2) * Math.cos(Math.PI / 180 * Math.abs(this.labelAngle));
                        a < f && (a = f);
                        this._labels[c].effectiveHeight = f
                    }
                for (c = 0; c < this._stripLineLabels.length; c++) "outside" ===
                    this._stripLineLabels[c].stripLine.labelPlacement && (this._stripLineLabels[c].stripLine.value >= this.viewportMinimum && this._stripLineLabels[c].stripLine.value <= this.viewportMaximum) && (d = this._stripLineLabels[c].textBlock, e = d.measureText(), f = 0 === this.labelAngle ? e.height : e.width * Math.sin(Math.PI / 180 * Math.abs(this.labelAngle)) + (e.height - d.fontSize / 2) * Math.cos(Math.PI / 180 * Math.abs(this.labelAngle)), a < f && (a = f), this._stripLineLabels[c].effectiveHeight = f)
            }
            return (this.title ? this._titleTextBlock.measureText().height +
                2 : 0) + a + ("inside" === this.tickPlacement ? 0 : this.tickLength) + b
        };
        D.setLayout = function(a, d, c, b, e, f) {
            var l, h, m, k, n = a[0] ? a[0].chart : d[0].chart,
                p = n.isNavigator ? 0 : 10,
                q = n._axes;
            if (a && 0 < a.length)
                for (var g = 0; g < a.length; g++) a[g] && a[g].calculateAxisParameters();
            if (d && 0 < d.length)
                for (g = 0; g < d.length; g++) d[g].calculateAxisParameters();
            if (c && 0 < c.length)
                for (g = 0; g < c.length; g++) c[g].calculateAxisParameters();
            if (b && 0 < b.length)
                for (g = 0; g < b.length; g++) b[g].calculateAxisParameters();
            for (g = 0; g < q.length; g++)
                if (q[g] && q[g].scaleBreaks &&
                    q[g].scaleBreaks._appliedBreaks.length)
                    for (var r = q[g].scaleBreaks._appliedBreaks, u = 0; u < r.length && !(r[u].startValue > q[g].viewportMaximum); u++) r[u].endValue < q[g].viewportMinimum || (s(q[g].scaleBreaks.firstBreakIndex) && (q[g].scaleBreaks.firstBreakIndex = u), r[u].startValue >= q[g].viewPortMinimum && (q[g].scaleBreaks.lastBreakIndex = u));
            for (var w = u = 0, t = 0, x = 0, y = 0, B = 0, C = 0, z, D, F = h = 0, H, J, K, r = H = J = K = !1, g = 0; g < q.length; g++) q[g] && q[g].title && (q[g]._titleTextBlock = new ia(q[g].ctx, {
                text: q[g].title,
                horizontalAlign: "center",
                fontSize: q[g].titleFontSize,
                fontFamily: q[g].titleFontFamily,
                fontWeight: q[g].titleFontWeight,
                fontColor: q[g].titleFontColor,
                fontStyle: q[g].titleFontStyle,
                borderColor: q[g].titleBorderColor,
                borderThickness: q[g].titleBorderThickness,
                backgroundColor: q[g].titleBackgroundColor,
                cornerRadius: q[g].titleCornerRadius,
                textBaseline: "top"
            }));
            for (g = 0; g < q.length; g++)
                if (q[g].title) switch (q[g]._position) {
                    case "left":
                        q[g]._titleTextBlock.maxWidth = q[g].titleMaxWidth || f.height;
                        q[g]._titleTextBlock.maxHeight = q[g].titleWrap ?
                            0.8 * f.width : 1.5 * q[g].titleFontSize;
                        q[g]._titleTextBlock.angle = -90;
                        break;
                    case "right":
                        q[g]._titleTextBlock.maxWidth = q[g].titleMaxWidth || f.height;
                        q[g]._titleTextBlock.maxHeight = q[g].titleWrap ? 0.8 * f.width : 1.5 * q[g].titleFontSize;
                        q[g]._titleTextBlock.angle = 90;
                        break;
                    default:
                        q[g]._titleTextBlock.maxWidth = q[g].titleMaxWidth || f.width, q[g]._titleTextBlock.maxHeight = q[g].titleWrap ? 0.8 * f.height : 1.5 * q[g].titleFontSize, q[g]._titleTextBlock.angle = 0
                }
            if ("normal" === e) {
                for (var x = [], y = [], B = [], C = [], M = [], N = [], P = [], R = []; 4 >
                    u;) {
                    var G = 0,
                        S = 0,
                        U = 0,
                        X = 0,
                        W = e = 0,
                        L = 0,
                        Z = 0,
                        T = 0,
                        V = 0,
                        O = 0,
                        $ = 0;
                    if (c && 0 < c.length)
                        for (B = [], g = O = 0; g < c.length; g++) B.push(Math.ceil(c[g] ? c[g].createLabelsAndCalculateWidth() : 0)), O += B[g], L += c[g] && !n.isNavigator ? c[g].margin : 0;
                    else B.push(Math.ceil(c[0] ? c[0].createLabelsAndCalculateWidth() : 0));
                    P.push(B);
                    if (b && 0 < b.length)
                        for (C = [], g = $ = 0; g < b.length; g++) C.push(Math.ceil(b[g] ? b[g].createLabelsAndCalculateWidth() : 0)), $ += C[g], Z += b[g] ? b[g].margin : 0;
                    else C.push(Math.ceil(b[0] ? b[0].createLabelsAndCalculateWidth() : 0));
                    R.push(C);
                    l = Math.round(f.x1 + O + L);
                    m = Math.round(f.x2 - $ - Z > n.width - p ? n.width - p : f.x2 - $ - Z);
                    if (a && 0 < a.length)
                        for (x = [], g = T = 0; g < a.length; g++) a[g] && (a[g].lineCoordinates = {}), a[g].lineCoordinates.width = Math.abs(m - l), a[g].title && (a[g]._titleTextBlock.maxWidth = 0 < a[g].titleMaxWidth && a[g].titleMaxWidth < a[g].lineCoordinates.width ? a[g].titleMaxWidth : a[g].lineCoordinates.width), x.push(Math.ceil(a[g] ? a[g].createLabelsAndCalculateHeight() : 0)), T += x[g], e += a[g] && !n.isNavigator ? a[g].margin : 0;
                    else x.push(Math.ceil(a[0] ? a[0].createLabelsAndCalculateHeight() :
                        0));
                    M.push(x);
                    if (d && 0 < d.length)
                        for (y = [], g = V = 0; g < d.length; g++) d[g] && (d[g].lineCoordinates = {}), d[g].lineCoordinates.width = Math.abs(m - l), d[g].title && (d[g]._titleTextBlock.maxWidth = 0 < d[g].titleMaxWidth && d[g].titleMaxWidth < d[g].lineCoordinates.width ? d[g].titleMaxWidth : d[g].lineCoordinates.width), y.push(Math.ceil(d[g] ? d[g].createLabelsAndCalculateHeight() : 0)), V += y[g], W += d[g] && !n.isNavigator ? d[g].margin : 0;
                    else y.push(Math.ceil(d[0] ? d[0].createLabelsAndCalculateHeight() : 0));
                    N.push(y);
                    if (a && 0 < a.length)
                        for (g =
                            0; g < a.length; g++) a[g] && (a[g].lineCoordinates.x1 = l, m = Math.round(f.x2 - $ - Z > n.width - p ? n.width - p : f.x2 - $ - Z), a[g]._labels && 1 < a[g]._labels.length && (h = k = 0, k = a[g]._labels[1], h = "dateTime" === a[g].valueType ? a[g]._labels[a[g]._labels.length - 2] : a[g]._labels[a[g]._labels.length - 1], w = k.textBlock.width * Math.cos(Math.PI / 180 * Math.abs(k.textBlock.angle)) + (k.textBlock.height - h.textBlock.fontSize / 2) * Math.sin(Math.PI / 180 * Math.abs(k.textBlock.angle)), t = h.textBlock.width * Math.cos(Math.PI / 180 * Math.abs(h.textBlock.angle)) + (h.textBlock.height -
                                h.textBlock.fontSize / 2) * Math.sin(Math.PI / 180 * Math.abs(h.textBlock.angle))), !a[g] || (!a[g].labelAutoFit || s(z) || s(D) || n.isNavigator || n.stockChart) || (h = 0, 0 < a[g].labelAngle ? D + t > m && (h += 0 < a[g].labelAngle ? D + t - m - $ : 0) : 0 > a[g].labelAngle ? z - w < l && z - w < a[g].viewportMinimum && (F = l - (L + a[g].tickLength + B + z - w + a[g].labelFontSize / 2)) : 0 === a[g].labelAngle && (D + t > m && (h = D + t / 2 - m - $), z - w < l && z - w < a[g].viewportMinimum && (F = l - L - a[g].tickLength - B - z + w / 2)), a[g].viewportMaximum === a[g].maximum && a[g].viewportMinimum === a[g].minimum && 0 < a[g].labelAngle &&
                                0 < h ? m -= h : a[g].viewportMaximum === a[g].maximum && a[g].viewportMinimum === a[g].minimum && 0 > a[g].labelAngle && 0 < F ? l += F : a[g].viewportMaximum === a[g].maximum && a[g].viewportMinimum === a[g].minimum && 0 === a[g].labelAngle && (0 < F && (l += F), 0 < h && (m -= h))), n.panEnabled ? T = s(n.sessionVariables.axisX.height) ? n.sessionVariables.axisX.height = T : n.sessionVariables.axisX.height : n.sessionVariables.axisX.height = T, h = Math.round(f.y2 - T - e + G), k = Math.round(f.y2), a[g].lineCoordinates.x2 = m, a[g].lineCoordinates.width = m - l, a[g].lineCoordinates.y1 =
                            h, a[g].lineCoordinates.y2 = h + a[g].lineThickness / 2, "inside" === a[g].labelPlacement && 0 < g && (a[g].lineCoordinates.y1 = a[g].lineCoordinates.y1 + x[g] - (a[g]._titleTextBlock ? a[g]._titleTextBlock.height : 0) - ("inside" === a[g].tickPlacement ? a[g].tickLength : 0), a[g].lineCoordinates.y2 = a[g].lineCoordinates.y1 + a[g].lineThickness / 2), a[g].bounds = { x1: l, y1: h, x2: m, y2: k - (T + e - x[g] - G), width: m - l, height: k - h }), G += x[g] + a[g].margin;
                    if (d && 0 < d.length)
                        for (g = 0; g < d.length; g++) d[g].lineCoordinates.x1 = Math.round(f.x1 + O + L), d[g].lineCoordinates.x2 =
                            Math.round(f.x2 - $ - Z > n.width - p ? n.width - p : f.x2 - $ - Z), d[g].lineCoordinates.width = Math.abs(m - l), d[g]._labels && 1 < d[g]._labels.length && (k = d[g]._labels[1], h = "dateTime" === d[g].valueType ? d[g]._labels[d[g]._labels.length - 2] : d[g]._labels[d[g]._labels.length - 1], w = k.textBlock.width * Math.cos(Math.PI / 180 * Math.abs(k.textBlock.angle)) + (k.textBlock.height - h.textBlock.fontSize / 2) * Math.sin(Math.PI / 180 * Math.abs(k.textBlock.angle)), t = h.textBlock.width * Math.cos(Math.PI / 180 * Math.abs(h.textBlock.angle)) + (h.textBlock.height -
                                h.textBlock.fontSize / 2) * Math.sin(Math.PI / 180 * Math.abs(h.textBlock.angle))), n.panEnabled ? V = s(n.sessionVariables.axisX2.height) ? n.sessionVariables.axisX2.height = V : n.sessionVariables.axisX2.height : n.sessionVariables.axisX2.height = V, h = Math.round(f.y1), k = Math.round(f.y2 + d[g].margin), d[g].lineCoordinates.y1 = h + V + W - S, d[g].lineCoordinates.y2 = h, "inside" === d[g].labelPlacement && 0 < g && (d[g].lineCoordinates.y1 = d[g - 1].bounds.y1 - y[g] + (d[g]._titleTextBlock ? d[g]._titleTextBlock.height : 0)), d[g].bounds = {
                                x1: l,
                                y1: h + (V +
                                    W - y[g] - S),
                                x2: m,
                                y2: k,
                                width: m - l,
                                height: k - h
                            }, S += y[g] + d[g].margin;
                    if (c && 0 < c.length)
                        for (g = 0; g < c.length; g++) L = n.isNavigator ? 0 : 10, c[g] && (l = Math.round(a[0] ? a[0].lineCoordinates.x1 : d[0].lineCoordinates.x1), L = c[g]._labels && 0 < c[g]._labels.length ? c[g]._labels[c[g]._labels.length - 1].textBlock.height / 2 : p, h = Math.round(f.y1 + V + W < Math.max(L, p) ? Math.max(L, p) : f.y1 + V + W), m = Math.round(a[0] ? a[0].lineCoordinates.x1 : d[0].lineCoordinates.x1), L = 0 < a.length ? 0 : c[g]._labels && 0 < c[g]._labels.length ? c[g]._labels[0].textBlock.height /
                            2 : p, k = Math.round(f.y2 - T - e - L), c[g].lineCoordinates = { x1: l - U, y1: h, x2: m - U, y2: k, height: Math.abs(k - h) }, "inside" === c[g].labelPlacement && 0 < g && (c[g].lineCoordinates.x1 = c[g].lineCoordinates.x1 - (B[g] - (c[g]._titleTextBlock ? c[g]._titleTextBlock.height : 0)) + ("outside" === c[g].tickPlacement ? c[g].tickLength : 0), c[g].lineCoordinates.x2 = c[g].lineCoordinates.x1 + c[g].lineThickness / 2), c[g].bounds = { x1: l - (B[g] + U), y1: h, x2: m, y2: k, width: m - l, height: k - h }, c[g].title && (c[g]._titleTextBlock.maxWidth = 0 < c[g].titleMaxWidth && c[g].titleMaxWidth <
                                c[g].lineCoordinates.height ? c[g].titleMaxWidth : c[g].lineCoordinates.height), U += B[g] + c[g].margin);
                    if (b && 0 < b.length)
                        for (g = 0; g < b.length; g++) b[g] && (l = Math.round(a[0] ? a[0].lineCoordinates.x2 : d[0].lineCoordinates.x2), m = Math.round(l), L = b[g]._labels && 0 < b[g]._labels.length ? b[g]._labels[b[g]._labels.length - 1].textBlock.height / 2 : 0, h = Math.round(f.y1 + V + W < Math.max(L, p) ? Math.max(L, p) : f.y1 + V + W), L = 0 < a.length ? 0 : b[g]._labels && 0 < b[g]._labels.length ? b[g]._labels[0].textBlock.height / 2 : 0, k = Math.round(f.y2 - (T + e + L)), b[g].lineCoordinates = { x1: l + X, y1: h, x2: l + X, y2: k, height: Math.abs(k - h) }, "inside" === b[g].labelPlacement && 0 < g && (b[g].lineCoordinates.x1 = b[g].lineCoordinates.x1 + (C[g] - (b[g]._titleTextBlock ? b[g]._titleTextBlock.height : 0)) - ("outside" === b[g].tickPlacement ? b[g].tickLength : 0) - 2, b[g].lineCoordinates.x2 = b[g].lineCoordinates.x1 + b[g].lineThickness / 2), b[g].bounds = { x1: l, y1: h, x2: m + (C[g] + X), y2: k, width: m - l, height: k - h }, b[g].title && (b[g]._titleTextBlock.maxWidth = 0 < b[g].titleMaxWidth && b[g].titleMaxWidth < b[g].lineCoordinates.height ? b[g].titleMaxWidth :
                            b[g].lineCoordinates.height), X += C[g] + b[g].margin);
                    if (a && 0 < a.length)
                        for (g = 0; g < a.length; g++) a[g] && (a[g].calculateValueToPixelConversionParameters(), a[g].calculateBreaksSizeInValues(), a[g]._labels && 1 < a[g]._labels.length && (z = (a[g].logarithmic ? Math.log(a[g]._labels[1].position / a[g].viewportMinimum) / a[g].conversionParameters.lnLogarithmBase : a[g]._labels[1].position - a[g].viewportMinimum) * Math.abs(a[g].conversionParameters.pixelPerUnit) + a[g].lineCoordinates.x1, l = a[g]._labels[a[g]._labels.length - ("dateTime" ===
                            a[g].valueType ? 2 : 1)].position, l = a[g].getApparentDifference(a[g].viewportMinimum, l), D = a[g].logarithmic ? (1 < l ? Math.log(l) / a[g].conversionParameters.lnLogarithmBase * Math.abs(a[g].conversionParameters.pixelPerUnit) : 0) + a[g].lineCoordinates.x1 : (0 < l ? l * Math.abs(a[g].conversionParameters.pixelPerUnit) : 0) + a[g].lineCoordinates.x1));
                    if (d && 0 < d.length)
                        for (g = 0; g < d.length; g++) d[g].calculateValueToPixelConversionParameters(), d[g].calculateBreaksSizeInValues(), d[g]._labels && 1 < d[g]._labels.length && (z = (d[g].logarithmic ?
                                Math.log(d[g]._labels[1].position / d[g].viewportMinimum) / d[g].conversionParameters.lnLogarithmBase : d[g]._labels[1].position - d[g].viewportMinimum) * Math.abs(d[g].conversionParameters.pixelPerUnit) + d[g].lineCoordinates.x1, l = d[g]._labels[d[g]._labels.length - ("dateTime" === d[g].valueType ? 2 : 1)].position, l = d[g].getApparentDifference(d[g].viewportMinimum, l), D = d[g].logarithmic ? (1 < l ? Math.log(l) / d[g].conversionParameters.lnLogarithmBase * Math.abs(d[g].conversionParameters.pixelPerUnit) : 0) + d[g].lineCoordinates.x1 :
                            (0 < l ? l * Math.abs(d[g].conversionParameters.pixelPerUnit) : 0) + d[g].lineCoordinates.x1);
                    for (g = 0; g < q.length; g++) "axisY" === q[g].type && (q[g].calculateValueToPixelConversionParameters(), q[g].calculateBreaksSizeInValues());
                    if (0 < u) {
                        if (a && 0 < a.length)
                            for (g = 0; g < a.length; g++) r = M[u - 1][g] === M[u][g] ? !0 : !1;
                        else r = !0;
                        if (d && 0 < d.length)
                            for (g = 0; g < d.length; g++) H = N[u - 1][g] === N[u][g] ? !0 : !1;
                        else H = !0;
                        if (c && 0 < c.length)
                            for (g = 0; g < c.length; g++) J = P[u - 1][g] === P[u][g] ? !0 : !1;
                        else J = !0;
                        if (b && 0 < b.length)
                            for (g = 0; g < b.length; g++) K = R[u -
                                1][g] === R[u][g] ? !0 : !1;
                        else K = !0
                    }
                    if (r && H && J && K) break;
                    u++
                }
                if (a && 0 < a.length)
                    for (g = 0; g < a.length; g++) a[g].calculateStripLinesThicknessInValues(), a[g].calculateBreaksInPixels();
                if (d && 0 < d.length)
                    for (g = 0; g < d.length; g++) d[g].calculateStripLinesThicknessInValues(), d[g].calculateBreaksInPixels();
                if (c && 0 < c.length)
                    for (g = 0; g < c.length; g++) c[g].calculateStripLinesThicknessInValues(), c[g].calculateBreaksInPixels();
                if (b && 0 < b.length)
                    for (g = 0; g < b.length; g++) b[g].calculateStripLinesThicknessInValues(), b[g].calculateBreaksInPixels()
            } else {
                p = [];
                z = [];
                F = [];
                w = [];
                D = [];
                t = [];
                M = [];
                for (N = []; 4 > u;) {
                    T = X = S = U = Z = L = W = e = R = P = G = V = 0;
                    if (a && 0 < a.length)
                        for (F = [], g = X = 0; g < a.length; g++) F.push(Math.ceil(a[g] ? a[g].createLabelsAndCalculateWidth() : 0)), X += F[g], e += a[g] && !n.isNavigator ? a[g].margin : 0;
                    else F.push(Math.ceil(a[0] ? a[0].createLabelsAndCalculateWidth() : 0));
                    M.push(F);
                    if (d && 0 < d.length)
                        for (w = [], g = T = 0; g < d.length; g++) w.push(Math.ceil(d[g] ? d[g].createLabelsAndCalculateWidth() : 0)), T += w[g], W += d[g] ? d[g].margin : 0;
                    else w.push(Math.ceil(d[0] ? d[0].createLabelsAndCalculateWidth() :
                        0));
                    N.push(w);
                    if (c && 0 < c.length)
                        for (g = 0; g < c.length; g++) c[g].lineCoordinates = {}, l = Math.round(f.x1 + X + e), m = Math.round(f.x2 - T - W > n.width - 10 ? n.width - 10 : f.x2 - T - W), c[g].labelAutoFit && !s(x) && (0 < !a.length && (l = 0 > c[g].labelAngle ? Math.max(l, x) : 0 === c[g].labelAngle ? Math.max(l, x / 2) : l), 0 < !d.length && (m = 0 < c[g].labelAngle ? m - y / 2 : 0 === c[g].labelAngle ? m - y / 2 : m)), c[g].lineCoordinates.x1 = l, c[g].lineCoordinates.x2 = m, c[g].lineCoordinates.width = Math.abs(m - l), c[g].title && (c[g]._titleTextBlock.maxWidth = 0 < c[g].titleMaxWidth && c[g].titleMaxWidth <
                            c[g].lineCoordinates.width ? c[g].titleMaxWidth : c[g].lineCoordinates.width);
                    if (b && 0 < b.length)
                        for (g = 0; g < b.length; g++) b[g].lineCoordinates = {}, l = Math.round(f.x1 + X + e), m = Math.round(f.x2 - T - W > b[g].chart.width - 10 ? b[g].chart.width - 10 : f.x2 - T - W), b[g] && b[g].labelAutoFit && !s(B) && (0 < !a.length && (l = 0 < b[g].labelAngle ? Math.max(l, B) : 0 === b[g].labelAngle ? Math.max(l, B / 2) : l), 0 < !d.length && (m -= C / 2)), b[g].lineCoordinates.x1 = l, b[g].lineCoordinates.x2 = m, b[g].lineCoordinates.width = Math.abs(m - l), b[g].title && (b[g]._titleTextBlock.maxWidth =
                            0 < b[g].titleMaxWidth && b[g].titleMaxWidth < b[g].lineCoordinates.width ? b[g].titleMaxWidth : b[g].lineCoordinates.width);
                    if (c && 0 < c.length)
                        for (p = [], g = U = 0; g < c.length; g++) p.push(Math.ceil(c[g] ? c[g].createLabelsAndCalculateHeight() : 0)), U += p[g] + c[g].margin, L += c[g].margin;
                    else p.push(Math.ceil(c[0] ? c[0].createLabelsAndCalculateHeight() : 0));
                    D.push(p);
                    if (b && 0 < b.length)
                        for (z = [], g = S = 0; g < b.length; g++) z.push(Math.ceil(b[g] ? b[g].createLabelsAndCalculateHeight() : 0)), S += z[g], Z += b[g].margin;
                    else z.push(Math.ceil(b[0] ?
                        b[0].createLabelsAndCalculateHeight() : 0));
                    t.push(z);
                    if (c && 0 < c.length)
                        for (g = 0; g < c.length; g++) 0 < c[g]._labels.length && (k = c[g]._labels[0], h = c[g]._labels[c[g]._labels.length - 1], x = k.textBlock.width * Math.cos(Math.PI / 180 * Math.abs(k.textBlock.angle)) + (k.textBlock.height - h.textBlock.fontSize / 2) * Math.sin(Math.PI / 180 * Math.abs(k.textBlock.angle)), y = h.textBlock.width * Math.cos(Math.PI / 180 * Math.abs(h.textBlock.angle)) + (h.textBlock.height - h.textBlock.fontSize / 2) * Math.sin(Math.PI / 180 * Math.abs(h.textBlock.angle)));
                    if (b && 0 < b.length)
                        for (g = 0; g < b.length; g++) b[g] && 0 < b[g]._labels.length && (k = b[g]._labels[0], h = b[g]._labels[b[g]._labels.length - 1], B = k.textBlock.width * Math.cos(Math.PI / 180 * Math.abs(k.textBlock.angle)) + (k.textBlock.height - h.textBlock.fontSize / 2) * Math.sin(Math.PI / 180 * Math.abs(k.textBlock.angle)), C = h.textBlock.width * Math.cos(Math.PI / 180 * Math.abs(h.textBlock.angle)) + (h.textBlock.height - h.textBlock.fontSize / 2) * Math.sin(Math.PI / 180 * Math.abs(h.textBlock.angle)));
                    if (n.panEnabled)
                        for (g = 0; g < c.length; g++) p[g] = s(n.sessionVariables.axisY.height) ?
                            n.sessionVariables.axisY.height = p[g] : n.sessionVariables.axisY.height;
                    else
                        for (g = 0; g < c.length; g++) n.sessionVariables.axisY.height = p[g];
                    if (c && 0 < c.length)
                        for (g = c.length - 1; 0 <= g; g--) h = Math.round(f.y2), k = Math.round(f.y2 > c[g].chart.height ? c[g].chart.height : f.y2), c[g].lineCoordinates.y1 = h - (p[g] + c[g].margin + V), c[g].lineCoordinates.y2 = h - (p[g] + c[g].margin + V), "inside" === c[g].labelPlacement && 0 < g && (c[g].lineCoordinates.y1 = c[g].lineCoordinates.y1 + p[g] - (c[g]._titleTextBlock ? c[g]._titleTextBlock.height : 0) - ("inside" ===
                            c[g].tickPlacement ? c[g].tickLength : 0), c[g].lineCoordinates.y2 = c[g].lineCoordinates.y1 + c[g].lineThickness / 2), c[g].bounds = { x1: l, y1: h - (p[g] + V + c[g].margin), x2: m, y2: k - (V + c[g].margin), width: m - l, height: p[g] }, c[g].title && (c[g]._titleTextBlock.maxWidth = 0 < c[g].titleMaxWidth && c[g].titleMaxWidth < c[g].lineCoordinates.width ? c[g].titleMaxWidth : c[g].lineCoordinates.width), V += p[g] + c[g].margin;
                    if (b && 0 < b.length)
                        for (g = b.length - 1; 0 <= g; g--) b[g] && (h = Math.round(f.y1), k = Math.round(f.y1 + (z[g] + b[g].margin + G)), b[g].lineCoordinates.y1 =
                            k, b[g].lineCoordinates.y2 = k, "inside" === b[g].labelPlacement && 0 < g && (b[g].lineCoordinates.y1 = k - z[g] + (b[g]._titleTextBlock ? b[g]._titleTextBlock.height : 0)), b[g].bounds = { x1: l, y1: h + (b[g].margin + G), x2: m, y2: k, width: m - l, height: S }, b[g].title && (b[g]._titleTextBlock.maxWidth = 0 < b[g].titleMaxWidth && b[g].titleMaxWidth < b[g].lineCoordinates.width ? b[g].titleMaxWidth : b[g].lineCoordinates.width), G += z[g] + b[g].margin);
                    if (a && 0 < a.length)
                        for (g = 0; g < a.length; g++) {
                            L = a[g]._labels && 0 < a[g]._labels.length ? a[g]._labels[0].textBlock.fontSize /
                                2 : 0;
                            l = Math.round(f.x1 + e);
                            h = b && 0 < b.length ? Math.round(b[0] ? b[0].lineCoordinates.y2 : f.y1 < Math.max(L, 10) ? Math.max(L, 10) : f.y1) : f.y1 < Math.max(L, 10) ? Math.max(L, 10) : f.y1;
                            m = Math.round(f.x1 + X + e);
                            k = c && 0 < c.length ? Math.round(c[0] ? c[0].lineCoordinates.y1 : f.y2 - U > n.height - Math.max(L, 10) ? n.height - Math.max(L, 10) : f.y2 - U) : f.y2 > n.height - Math.max(L, 10) ? n.height - Math.max(L, 10) : f.y2;
                            if (c && 0 < c.length)
                                for (L = 0; L < c.length; L++) c[L] && c[L].labelAutoFit && (m = 0 > c[L].labelAngle ? Math.max(m, x) : 0 === c[L].labelAngle ? Math.max(m, x / 2) : m, l =
                                    0 > c[L].labelAngle || 0 === c[L].labelAngle ? m - X : l);
                            if (b && 0 < b.length)
                                for (L = 0; L < b.length; L++) b[L] && b[L].labelAutoFit && (m = b[L].lineCoordinates.x1, l = m - X);
                            a[g].lineCoordinates = { x1: m - P, y1: h, x2: m - P, y2: k, height: Math.abs(k - h) };
                            "inside" === a[g].labelPlacement && 0 < g && (a[g].lineCoordinates.x1 = a[g].lineCoordinates.x1 - (F[g] - (a[g]._titleTextBlock ? a[g]._titleTextBlock.height : 0)) + ("outside" === a[g].tickPlacement ? a[g].tickLength : 0), a[g].lineCoordinates.x2 = a[g].lineCoordinates.x1 + a[g].lineThickness / 2);
                            a[g].bounds = {
                                x1: m - (F[g] +
                                    P),
                                y1: h,
                                x2: m,
                                y2: k,
                                width: m - l,
                                height: k - h
                            };
                            a[g].title && (a[g]._titleTextBlock.maxWidth = 0 < a[g].titleMaxWidth && a[g].titleMaxWidth < a[g].lineCoordinates.height ? a[g].titleMaxWidth : a[g].lineCoordinates.height);
                            a[g].calculateValueToPixelConversionParameters();
                            a[g].calculateBreaksSizeInValues();
                            P += F[g] + a[g].margin
                        }
                    if (d && 0 < d.length)
                        for (g = 0; g < d.length; g++) {
                            L = d[g]._labels && 0 < d[g]._labels.length ? d[g]._labels[0].textBlock.fontSize / 2 : 0;
                            l = Math.round(f.x1 - e);
                            h = b && 0 < b.length ? Math.round(b[0] ? b[0].lineCoordinates.y2 : f.y1 <
                                Math.max(L, 10) ? Math.max(L, 10) : f.y1) : f.y1 < Math.max(L, 10) ? Math.max(L, 10) : f.y1;
                            m = Math.round(f.x2 - T - W);
                            k = c && 0 < c.length ? Math.round(c[0] ? c[0].lineCoordinates.y1 : f.y2 - U > n.height - Math.max(L, 10) ? n.height - Math.max(L, 10) : f.y2 - U) : f.y2 > n.height - Math.max(L, 10) ? n.height - Math.max(L, 10) : f.y2;
                            if (c && 0 < c.length)
                                for (L = 0; L < c.length; L++) c[L] && c[L].labelAutoFit && (m = 0 > c[L].labelAngle ? Math.max(m, x) : 0 === c[L].labelAngle ? Math.max(m, x / 2) : m, l = 0 > c[L].labelAngle || 0 === c[L].labelAngle ? m - T : l);
                            if (b && 0 < b.length)
                                for (L = 0; L < b.length; L++) b[L] &&
                                    b[L].labelAutoFit && (m = b[L].lineCoordinates.x2, l = m - T);
                            d[g].lineCoordinates = { x1: m + R, y1: h, x2: m + R, y2: k, height: Math.abs(k - h) };
                            "inside" === d[g].labelPlacement && 0 < g && (d[g].lineCoordinates.x1 = d[g].lineCoordinates.x1 + (w[g] - (d[g]._titleTextBlock ? d[g]._titleTextBlock.height : 0) - 2) - ("outside" === d[g].tickPlacement ? d[g].tickLength : 0), d[g].lineCoordinates.x2 = d[g].lineCoordinates.x1 + d[g].lineThickness / 2);
                            d[g].bounds = { x1: d[g].lineCoordinates.x1, y1: h, x2: m + w[g] + R, y2: k, width: m - l, height: k - h };
                            d[g].title && (d[g]._titleTextBlock.maxWidth =
                                0 < d[g].titleMaxWidth && d[g].titleMaxWidth < d[g].lineCoordinates.height ? d[g].titleMaxWidth : d[g].lineCoordinates.height);
                            d[g].calculateValueToPixelConversionParameters();
                            d[g].calculateBreaksSizeInValues();
                            R += w[g] + d[g].margin
                        }
                    for (g = 0; g < q.length; g++) "axisY" === q[g].type && (q[g].calculateValueToPixelConversionParameters(), q[g].calculateBreaksSizeInValues());
                    if (0 < u) {
                        if (a && 0 < a.length)
                            for (g = 0; g < a.length; g++) r = M[u - 1][g] === M[u][g] ? !0 : !1;
                        else r = !0;
                        if (d && 0 < d.length)
                            for (g = 0; g < d.length; g++) H = N[u - 1][g] === N[u][g] ? !0 :
                                !1;
                        else H = !0;
                        if (c && 0 < c.length)
                            for (g = 0; g < c.length; g++) J = D[u - 1][g] === D[u][g] ? !0 : !1;
                        else J = !0;
                        if (b && 0 < b.length)
                            for (g = 0; g < b.length; g++) K = t[u - 1][g] === t[u][g] ? !0 : !1;
                        else K = !0
                    }
                    if (r && H && J && K) break;
                    u++
                }
                if (c && 0 < c.length)
                    for (g = 0; g < c.length; g++) c[g].calculateStripLinesThicknessInValues(), c[g].calculateBreaksInPixels();
                if (b && 0 < b.length)
                    for (g = 0; g < b.length; g++) b[g].calculateStripLinesThicknessInValues(), b[g].calculateBreaksInPixels();
                if (a && 0 < a.length)
                    for (g = 0; g < a.length; g++) a[g].calculateStripLinesThicknessInValues(),
                        a[g].calculateBreaksInPixels();
                if (d && 0 < d.length)
                    for (g = 0; g < d.length; g++) d[g].calculateStripLinesThicknessInValues(), d[g].calculateBreaksInPixels()
            }
        };
        D.render = function(a, d, c, b, e) {
            var f = a[0] ? a[0].chart : d[0].chart;
            e = f.ctx;
            f.alignVerticalAxes && f.alignVerticalAxes();
            e.save();
            e.beginPath();
            a[0] && e.rect(5, a[0].bounds.y1, a[0].chart.width - 10, a[0].bounds.height);
            d[0] && e.rect(5, d[d.length - 1].bounds.y1, d[0].chart.width - 10, d[0].bounds.height);
            e.clip();
            if (a && 0 < a.length)
                for (var l = 0; l < a.length; l++) a[l].renderLabelsTicksAndTitle();
            if (d && 0 < d.length)
                for (l = 0; l < d.length; l++) d[l].renderLabelsTicksAndTitle();
            e.restore();
            if (c && 0 < c.length)
                for (l = 0; l < c.length; l++) c[l].renderLabelsTicksAndTitle();
            if (b && 0 < b.length)
                for (l = 0; l < b.length; l++) b[l].renderLabelsTicksAndTitle();
            f.preparePlotArea();
            f = f.plotArea;
            e.save();
            e.beginPath();
            e.rect(f.x1, f.y1, Math.abs(f.x2 - f.x1), Math.abs(f.y2 - f.y1));
            e.clip();
            if (a && 0 < a.length)
                for (l = 0; l < a.length; l++) a[l].renderStripLinesOfThicknessType("value");
            if (d && 0 < d.length)
                for (l = 0; l < d.length; l++) d[l].renderStripLinesOfThicknessType("value");
            if (c && 0 < c.length)
                for (l = 0; l < c.length; l++) c[l].renderStripLinesOfThicknessType("value");
            if (b && 0 < b.length)
                for (l = 0; l < b.length; l++) b[l].renderStripLinesOfThicknessType("value");
            if (a && 0 < a.length)
                for (l = 0; l < a.length; l++) a[l].renderInterlacedColors();
            if (d && 0 < d.length)
                for (l = 0; l < d.length; l++) d[l].renderInterlacedColors();
            if (c && 0 < c.length)
                for (l = 0; l < c.length; l++) c[l].renderInterlacedColors();
            if (b && 0 < b.length)
                for (l = 0; l < b.length; l++) b[l].renderInterlacedColors();
            e.restore();
            if (a && 0 < a.length)
                for (l = 0; l < a.length; l++) a[l].renderGrid(),
                    u && (a[l].createMask(), a[l].renderBreaksBackground());
            if (d && 0 < d.length)
                for (l = 0; l < d.length; l++) d[l].renderGrid(), u && (d[l].createMask(), d[l].renderBreaksBackground());
            if (c && 0 < c.length)
                for (l = 0; l < c.length; l++) c[l].renderGrid(), u && (c[l].createMask(), c[l].renderBreaksBackground());
            if (b && 0 < b.length)
                for (l = 0; l < b.length; l++) b[l].renderGrid(), u && (b[l].createMask(), b[l].renderBreaksBackground());
            if (a && 0 < a.length)
                for (l = 0; l < a.length; l++) a[l].renderAxisLine();
            if (d && 0 < d.length)
                for (l = 0; l < d.length; l++) d[l].renderAxisLine();
            if (c && 0 < c.length)
                for (l = 0; l < c.length; l++) c[l].renderAxisLine();
            if (b && 0 < b.length)
                for (l = 0; l < b.length; l++) b[l].renderAxisLine();
            if (a && 0 < a.length)
                for (l = 0; l < a.length; l++) a[l].renderStripLinesOfThicknessType("pixel");
            if (d && 0 < d.length)
                for (l = 0; l < d.length; l++) d[l].renderStripLinesOfThicknessType("pixel");
            if (c && 0 < c.length)
                for (l = 0; l < c.length; l++) c[l].renderStripLinesOfThicknessType("pixel");
            if (b && 0 < b.length)
                for (l = 0; l < b.length; l++) b[l].renderStripLinesOfThicknessType("pixel")
        };
        D.prototype.calculateStripLinesThicknessInValues =
            function() {
                for (var a = 0; a < this.stripLines.length; a++)
                    if (null !== this.stripLines[a].startValue && null !== this.stripLines[a].endValue) {
                        var d = Math.min(this.stripLines[a].startValue, this.stripLines[a].endValue),
                            c = Math.max(this.stripLines[a].startValue, this.stripLines[a].endValue),
                            d = this.getApparentDifference(d, c);
                        this.stripLines[a].value = this.logarithmic ? this.stripLines[a].value * Math.sqrt(Math.log(Math.max(this.stripLines[a].startValue, this.stripLines[a].endValue) / Math.min(this.stripLines[a].startValue, this.stripLines[a].endValue)) /
                            Math.log(d)) : this.stripLines[a].value + (Math.abs(this.stripLines[a].endValue - this.stripLines[a].startValue) - d) / 2;
                        this.stripLines[a].thickness = d;
                        this.stripLines[a]._thicknessType = "value"
                    }
            };
        D.prototype.calculateBreaksSizeInValues = function() {
            for (var a = "left" === this._position || "right" === this._position ? this.lineCoordinates.height || this.chart.height : this.lineCoordinates.width || this.chart.width, d = this.scaleBreaks ? this.scaleBreaks._appliedBreaks : [], c = this.conversionParameters.pixelPerUnit || a / (this.logarithmic ?
                    this.conversionParameters.maximum / this.conversionParameters.minimum : this.conversionParameters.maximum - this.conversionParameters.minimum), b = this.scaleBreaks && !s(this.scaleBreaks.options.spacing), e, f = 0; f < d.length; f++) e = b || !s(d[f].options.spacing), d[f].spacing = Ra(d[f].spacing, a, 8, e ? 0.1 * a : 8, e ? 0 : 3) << 0, d[f].size = 0 > d[f].spacing ? 0 : Math.abs(d[f].spacing / c), this.logarithmic && (d[f].size = Math.pow(this.logarithmBase, d[f].size))
        };
        D.prototype.calculateBreaksInPixels = function() {
            if (!(this.scaleBreaks && 0 >= this.scaleBreaks._appliedBreaks.length)) {
                var a =
                    this.scaleBreaks ? this.scaleBreaks._appliedBreaks : [];
                a.length && (this.scaleBreaks.firstBreakIndex = this.scaleBreaks.lastBreakIndex = null);
                for (var d = 0; d < a.length && !(a[d].startValue > this.conversionParameters.maximum); d++) a[d].endValue < this.conversionParameters.minimum || (s(this.scaleBreaks.firstBreakIndex) && (this.scaleBreaks.firstBreakIndex = d), a[d].startValue >= this.conversionParameters.minimum && (a[d].startPixel = this.convertValueToPixel(a[d].startValue), this.scaleBreaks.lastBreakIndex = d), a[d].endValue <= this.conversionParameters.maximum &&
                    (a[d].endPixel = this.convertValueToPixel(a[d].endValue)))
            }
        };
        D.prototype.renderLabelsTicksAndTitle = function() {
            var a = this,
                d = !1,
                c = 0,
                b = 0,
                e = 1,
                f = 0;
            0 !== this.labelAngle && 360 !== this.labelAngle && (e = 1.2);
            if ("undefined" === typeof this.options.interval) {
                if ("bottom" === this._position || "top" === this._position)
                    if (this.logarithmic && !this.equidistantInterval && this.labelAutoFit) {
                        for (var c = [], e = 0 !== this.labelAngle && 360 !== this.labelAngle ? 1 : 1.2, l, h = this.viewportMaximum, m = this.lineCoordinates.width / Math.log(this.range), k = this._labels.length -
                                1; 0 <= k; k--) {
                            p = this._labels[k];
                            if (p.position < this.viewportMinimum) break;
                            p.position > this.viewportMaximum || !(k === this._labels.length - 1 || l < Math.log(h / p.position) * m / e) || (c.push(p), h = p.position, l = p.textBlock.width * Math.abs(Math.cos(Math.PI / 180 * this.labelAngle)) + p.textBlock.height * Math.abs(Math.sin(Math.PI / 180 * this.labelAngle)))
                        }
                        this._labels = c
                    } else {
                        for (k = 0; k < this._labels.length; k++) p = this._labels[k], p.position < this.viewportMinimum || (l = p.textBlock.width * Math.abs(Math.cos(Math.PI / 180 * this.labelAngle)) + p.textBlock.height *
                            Math.abs(Math.sin(Math.PI / 180 * this.labelAngle)), c += l);
                        c > this.lineCoordinates.width * e && this.labelAutoFit && (d = !0)
                    }
                if ("left" === this._position || "right" === this._position)
                    if (this.logarithmic && !this.equidistantInterval && this.labelAutoFit) {
                        for (var c = [], n, h = this.viewportMaximum, m = this.lineCoordinates.height / Math.log(this.range), k = this._labels.length - 1; 0 <= k; k--) {
                            p = this._labels[k];
                            if (p.position < this.viewportMinimum) break;
                            p.position > this.viewportMaximum || !(k === this._labels.length - 1 || n < Math.log(h / p.position) *
                                m) || (c.push(p), h = p.position, n = p.textBlock.height * Math.abs(Math.cos(Math.PI / 180 * this.labelAngle)) + p.textBlock.width * Math.abs(Math.sin(Math.PI / 180 * this.labelAngle)))
                        }
                        this._labels = c
                    } else {
                        for (k = 0; k < this._labels.length; k++) p = this._labels[k], p.position < this.viewportMinimum || (n = p.textBlock.height * Math.abs(Math.cos(Math.PI / 180 * this.labelAngle)) + p.textBlock.width * Math.abs(Math.sin(Math.PI / 180 * this.labelAngle)), b += n);
                        b > this.lineCoordinates.height * e && this.labelAutoFit && (d = !0)
                    }
            }
            this.logarithmic && (!this.equidistantInterval &&
                this.labelAutoFit) && this._labels.sort(function(a, b) { return a.position - b.position });
            var k = 0,
                p, q;
            if ("bottom" === this._position) {
                for (k = 0; k < this._labels.length; k++) p = this._labels[k], p.position < this.viewportMinimum || p.position > this.viewportMaximum || (q = this.getPixelCoordinatesOnAxis(p.position), this.tickThickness && "inside" != this.tickPlacement && (this.ctx.lineWidth = this.tickThickness, this.ctx.strokeStyle = this.tickColor, b = 1 === this.ctx.lineWidth % 2 ? (q.x << 0) + 0.5 : q.x << 0, this.ctx.beginPath(), this.ctx.moveTo(b, q.y <<
                    0), this.ctx.lineTo(b, q.y + this.tickLength << 0), this.ctx.stroke()), d && 0 !== f++ % 2 && this.labelAutoFit || (0 === p.textBlock.angle ? (q.x -= p.textBlock.width / 2, q.y = "inside" === this.labelPlacement ? q.y - (("inside" === this.tickPlacement ? this.tickLength : 0) + p.textBlock.height - p.textBlock.fontSize / 2) : q.y + ("inside" === this.tickPlacement ? 0 : this.tickLength) + p.textBlock.fontSize / 2 + 5) : (q.x = "inside" === this.labelPlacement ? 0 > this.labelAngle ? q.x : q.x - p.textBlock.width * Math.cos(Math.PI / 180 * this.labelAngle) : q.x - (0 > this.labelAngle ? p.textBlock.width *
                    Math.cos(Math.PI / 180 * this.labelAngle) : 0), q.y = "inside" === this.labelPlacement ? 0 > this.labelAngle ? q.y - ("inside" === this.tickPlacement ? this.tickLength : 0) - 5 : q.y - ("inside" === this.tickPlacement ? this.tickLength : 0) - Math.abs(p.textBlock.width * Math.sin(Math.PI / 180 * this.labelAngle) + 5) : q.y + ("inside" === this.tickPlacement ? 0 : this.tickLength) + Math.abs(0 > this.labelAngle ? p.textBlock.width * Math.sin(Math.PI / 180 * this.labelAngle) - 5 : 5)), p.textBlock.x = q.x, p.textBlock.y = q.y));
                "inside" === this.tickPlacement && this.chart.addEventListener("dataAnimationEnd",
                    function() {
                        for (k = 0; k < a._labels.length; k++)
                            if (p = a._labels[k], !(p.position < a.viewportMinimum || p.position > a.viewportMaximum) && (q = a.getPixelCoordinatesOnAxis(p.position), a.tickThickness)) {
                                a.ctx.lineWidth = a.tickThickness;
                                a.ctx.strokeStyle = a.tickColor;
                                var b = 1 === a.ctx.lineWidth % 2 ? (q.x << 0) + 0.5 : q.x << 0;
                                a.ctx.save();
                                a.ctx.beginPath();
                                a.ctx.moveTo(b, q.y << 0);
                                a.ctx.lineTo(b, q.y - a.tickLength << 0);
                                a.ctx.stroke();
                                a.ctx.restore()
                            }
                    }, this);
                this.title && (this._titleTextBlock.measureText(), this._titleTextBlock.x = this.lineCoordinates.x1 +
                    this.lineCoordinates.width / 2 - this._titleTextBlock.width / 2, this._titleTextBlock.y = this.bounds.y2 - this._titleTextBlock.height - 3, this.titleMaxWidth = this._titleTextBlock.maxWidth, this._titleTextBlock.render(!0))
            } else if ("top" === this._position) {
                for (k = 0; k < this._labels.length; k++) p = this._labels[k], p.position < this.viewportMinimum || p.position > this.viewportMaximum || (q = this.getPixelCoordinatesOnAxis(p.position), this.tickThickness && "inside" != this.tickPlacement && (this.ctx.lineWidth = this.tickThickness, this.ctx.strokeStyle =
                    this.tickColor, b = 1 === this.ctx.lineWidth % 2 ? (q.x << 0) + 0.5 : q.x << 0, this.ctx.beginPath(), this.ctx.moveTo(b, q.y << 0), this.ctx.lineTo(b, q.y - this.tickLength << 0), this.ctx.stroke()), d && 0 !== f++ % 2 && this.labelAutoFit || (0 === p.textBlock.angle ? (q.x -= p.textBlock.width / 2, q.y = "inside" === this.labelPlacement ? q.y + this.labelFontSize / 2 + ("inside" === this.tickPlacement ? this.tickLength : 0) + 5 : q.y - (("inside" === this.tickPlacement ? 0 : this.tickLength) + p.textBlock.height - p.textBlock.fontSize / 2)) : (q.x = "inside" === this.labelPlacement ? 0 <
                    this.labelAngle ? q.x : q.x - p.textBlock.width * Math.cos(Math.PI / 180 * this.labelAngle) : q.x + (p.textBlock.height - this.labelFontSize) * Math.sin(Math.PI / 180 * this.labelAngle) - (0 < this.labelAngle ? p.textBlock.width * Math.cos(Math.PI / 180 * this.labelAngle) : 0), q.y = "inside" === this.labelPlacement ? 0 < this.labelAngle ? q.y + ("inside" === this.tickPlacement ? this.tickLength : 0) + 5 : q.y - p.textBlock.width * Math.sin(Math.PI / 180 * this.labelAngle) + ("inside" === this.tickPlacement ? this.tickLength : 0) + 5 : q.y - (("inside" === this.tickPlacement ? 0 : this.tickLength) +
                        ((p.textBlock.height - p.textBlock.fontSize / 2) * Math.cos(Math.PI / 180 * this.labelAngle) + (0 < this.labelAngle ? p.textBlock.width * Math.sin(Math.PI / 180 * this.labelAngle) : 0)))), p.textBlock.x = q.x, p.textBlock.y = q.y));
                "inside" === this.tickPlacement && this.chart.addEventListener("dataAnimationEnd", function() {
                    for (k = 0; k < a._labels.length; k++)
                        if (p = a._labels[k], !(p.position < a.viewportMinimum || p.position > a.viewportMaximum) && (q = a.getPixelCoordinatesOnAxis(p.position), a.tickThickness)) {
                            a.ctx.lineWidth = a.tickThickness;
                            a.ctx.strokeStyle =
                                a.tickColor;
                            var b = 1 === a.ctx.lineWidth % 2 ? (q.x << 0) + 0.5 : q.x << 0;
                            a.ctx.save();
                            a.ctx.beginPath();
                            a.ctx.moveTo(b, q.y << 0);
                            a.ctx.lineTo(b, q.y + a.tickLength << 0);
                            a.ctx.stroke();
                            a.ctx.restore()
                        }
                }, this);
                this.title && (this._titleTextBlock.measureText(), this._titleTextBlock.x = this.lineCoordinates.x1 + this.lineCoordinates.width / 2 - this._titleTextBlock.width / 2, this._titleTextBlock.y = this.bounds.y1 + 1, this.titleMaxWidth = this._titleTextBlock.maxWidth, this._titleTextBlock.render(!0))
            } else if ("left" === this._position) {
                for (k =
                    0; k < this._labels.length; k++) p = this._labels[k], p.position < this.viewportMinimum || p.position > this.viewportMaximum || (q = this.getPixelCoordinatesOnAxis(p.position), this.tickThickness && "inside" != this.tickPlacement && (this.ctx.lineWidth = this.tickThickness, this.ctx.strokeStyle = this.tickColor, b = 1 === this.ctx.lineWidth % 2 ? (q.y << 0) + 0.5 : q.y << 0, this.ctx.beginPath(), this.ctx.moveTo(q.x << 0, b), this.ctx.lineTo(q.x - this.tickLength << 0, b), this.ctx.stroke()), d && 0 !== f++ % 2 && this.labelAutoFit || (0 === this.labelAngle ? (p.textBlock.y =
                    q.y, p.textBlock.x = "inside" === this.labelPlacement ? q.x + ("inside" === this.tickPlacement ? this.tickLength : 0) + 5 : q.x - p.textBlock.width * Math.cos(Math.PI / 180 * this.labelAngle) - ("inside" === this.tickPlacement ? 0 : this.tickLength) - 5) : (p.textBlock.y = "inside" === this.labelPlacement ? q.y : q.y - p.textBlock.width * Math.sin(Math.PI / 180 * this.labelAngle), p.textBlock.x = "inside" === this.labelPlacement ? q.x + ("inside" === this.tickPlacement ? this.tickLength : 0) + 5 : 0 < this.labelAngle ? q.x - p.textBlock.width * Math.cos(Math.PI / 180 * this.labelAngle) -
                    ("inside" === this.tickPlacement ? 0 : this.tickLength) - 5 : q.x - p.textBlock.width * Math.cos(Math.PI / 180 * this.labelAngle) + (p.textBlock.height - p.textBlock.fontSize / 2 - 5) * Math.sin(Math.PI / 180 * this.labelAngle) - ("inside" === this.tickPlacement ? 0 : this.tickLength))));
                "inside" === this.tickPlacement && this.chart.addEventListener("dataAnimationEnd", function() {
                    for (k = 0; k < a._labels.length; k++)
                        if (p = a._labels[k], !(p.position < a.viewportMinimum || p.position > a.viewportMaximum) && (q = a.getPixelCoordinatesOnAxis(p.position), a.tickThickness)) {
                            a.ctx.lineWidth =
                                a.tickThickness;
                            a.ctx.strokeStyle = a.tickColor;
                            var b = 1 === a.ctx.lineWidth % 2 ? (q.y << 0) + 0.5 : q.y << 0;
                            a.ctx.save();
                            a.ctx.beginPath();
                            a.ctx.moveTo(q.x << 0, b);
                            a.ctx.lineTo(q.x + a.tickLength << 0, b);
                            a.ctx.stroke();
                            a.ctx.restore()
                        }
                }, this);
                this.title && (this._titleTextBlock.measureText(), this._titleTextBlock.x = this.bounds.x1 + 1, this._titleTextBlock.y = this.lineCoordinates.height / 2 + this._titleTextBlock.width / 2 + this.lineCoordinates.y1, this.titleMaxWidth = this._titleTextBlock.maxWidth, this._titleTextBlock.render(!0))
            } else if ("right" ===
                this._position) {
                for (k = 0; k < this._labels.length; k++) p = this._labels[k], p.position < this.viewportMinimum || p.position > this.viewportMaximum || (q = this.getPixelCoordinatesOnAxis(p.position), this.tickThickness && "inside" != this.tickPlacement && (this.ctx.lineWidth = this.tickThickness, this.ctx.strokeStyle = this.tickColor, b = 1 === this.ctx.lineWidth % 2 ? (q.y << 0) + 0.5 : q.y << 0, this.ctx.beginPath(), this.ctx.moveTo(q.x << 0, b), this.ctx.lineTo(q.x + this.tickLength << 0, b), this.ctx.stroke()), d && 0 !== f++ % 2 && this.labelAutoFit || (0 === this.labelAngle ?
                    (p.textBlock.y = q.y, p.textBlock.x = "inside" === this.labelPlacement ? q.x - p.textBlock.width - ("inside" === this.tickPlacement ? this.tickLength : 0) - 5 : q.x + ("inside" === this.tickPlacement ? 0 : this.tickLength) + 5) : (p.textBlock.y = "inside" === this.labelPlacement ? q.y - p.textBlock.width * Math.sin(Math.PI / 180 * this.labelAngle) : 0 > this.labelAngle ? q.y : q.y - (p.textBlock.height - p.textBlock.fontSize / 2 - 5) * Math.cos(Math.PI / 180 * this.labelAngle), p.textBlock.x = "inside" === this.labelPlacement ? q.x - p.textBlock.width * Math.cos(Math.PI / 180 * this.labelAngle) -
                        ("inside" === this.tickPlacement ? this.tickLength : 0) - 5 : 0 < this.labelAngle ? q.x + (p.textBlock.height - p.textBlock.fontSize / 2 - 5) * Math.sin(Math.PI / 180 * this.labelAngle) + ("inside" === this.tickPlacement ? 0 : this.tickLength) : q.x + ("inside" === this.tickPlacement ? 0 : this.tickLength) + 5)));
                "inside" === this.tickPlacement && this.chart.addEventListener("dataAnimationEnd", function() {
                    for (k = 0; k < a._labels.length; k++)
                        if (p = a._labels[k], !(p.position < a.viewportMinimum || p.position > a.viewportMaximum) && (q = a.getPixelCoordinatesOnAxis(p.position),
                                a.tickThickness)) {
                            a.ctx.lineWidth = a.tickThickness;
                            a.ctx.strokeStyle = a.tickColor;
                            var b = 1 === a.ctx.lineWidth % 2 ? (q.y << 0) + 0.5 : q.y << 0;
                            a.ctx.save();
                            a.ctx.beginPath();
                            a.ctx.moveTo(q.x << 0, b);
                            a.ctx.lineTo(q.x - a.tickLength << 0, b);
                            a.ctx.stroke();
                            a.ctx.restore()
                        }
                }, this);
                this.title && (this._titleTextBlock.measureText(), this._titleTextBlock.x = this.bounds.x2 - 1, this._titleTextBlock.y = this.lineCoordinates.height / 2 - this._titleTextBlock.width / 2 + this.lineCoordinates.y1, this.titleMaxWidth = this._titleTextBlock.maxWidth, this._titleTextBlock.render(!0))
            }
            f =
                0;
            if ("inside" === this.labelPlacement) this.chart.addEventListener("dataAnimationEnd", function() { for (k = 0; k < a._labels.length; k++) p = a._labels[k], p.position < a.viewportMinimum || (p.position > a.viewportMaximum || d && 0 !== f++ % 2 && a.labelAutoFit) || (a.ctx.save(), a.ctx.beginPath(), p.textBlock.render(!0), a.ctx.restore()) }, this);
            else
                for (k = 0; k < this._labels.length; k++) p = this._labels[k], p.position < this.viewportMinimum || (p.position > this.viewportMaximum || d && 0 !== f++ % 2 && this.labelAutoFit) || p.textBlock.render(!0)
        };
        D.prototype.renderInterlacedColors =
            function() {
                var a = this.chart.plotArea.ctx,
                    d, c, b = this.chart.plotArea,
                    e = 0;
                d = !0;
                if (("bottom" === this._position || "top" === this._position) && this.interlacedColor)
                    for (a.fillStyle = this.interlacedColor, e = 0; e < this._labels.length; e++) d ? (d = this.getPixelCoordinatesOnAxis(this._labels[e].position), c = e + 1 > this._labels.length - 1 ? this.getPixelCoordinatesOnAxis(this.viewportMaximum) : this.getPixelCoordinatesOnAxis(this._labels[e + 1].position), a.fillRect(Math.min(c.x, d.x), b.y1, Math.abs(c.x - d.x), Math.abs(b.y1 - b.y2)), d = !1) :
                        d = !0;
                else if (("left" === this._position || "right" === this._position) && this.interlacedColor)
                    for (a.fillStyle = this.interlacedColor, e = 0; e < this._labels.length; e++) d ? (c = this.getPixelCoordinatesOnAxis(this._labels[e].position), d = e + 1 > this._labels.length - 1 ? this.getPixelCoordinatesOnAxis(this.viewportMaximum) : this.getPixelCoordinatesOnAxis(this._labels[e + 1].position), a.fillRect(b.x1, Math.min(c.y, d.y), Math.abs(b.x1 - b.x2), Math.abs(d.y - c.y)), d = !1) : d = !0;
                a.beginPath()
            };
        D.prototype.renderStripLinesOfThicknessType = function(a) {
            if (this.stripLines &&
                0 < this.stripLines.length && a) {
                for (var d = this, c, b = 0, e = 0, f = !1, l = !1, h = [], m = [], l = !1, b = 0; b < this.stripLines.length; b++) {
                    var k = this.stripLines[b];
                    k._thicknessType === a && ("pixel" === a && (k.value < this.viewportMinimum || k.value > this.viewportMaximum || s(k.value) || isNaN(this.range)) || "value" === a && (k.startValue <= this.viewportMinimum && k.endValue <= this.viewportMinimum || k.startValue >= this.viewportMaximum && k.endValue >= this.viewportMaximum || s(k.startValue) || s(k.endValue) || isNaN(this.range)) || h.push(k))
                }
                for (b = 0; b < this._stripLineLabels.length; b++)
                    if (k =
                        this.stripLines[b], c = this._stripLineLabels[b], !(c.position < this.viewportMinimum || c.position > this.viewportMaximum || isNaN(this.range))) {
                        a = this.getPixelCoordinatesOnAxis(c.position);
                        if ("outside" === c.stripLine.labelPlacement)
                            if (k && (this.ctx.strokeStyle = k.color, "pixel" === k._thicknessType && (this.ctx.lineWidth = k.thickness)), "bottom" === this._position) {
                                var n = 1 === this.ctx.lineWidth % 2 ? (a.x << 0) + 0.5 : a.x << 0;
                                this.ctx.beginPath();
                                this.ctx.moveTo(n, a.y << 0);
                                this.ctx.lineTo(n, a.y + this.tickLength << 0);
                                this.ctx.stroke();
                                0 === this.labelAngle ? (a.x -= c.textBlock.width / 2, a.y += this.tickLength + c.textBlock.fontSize / 2) : (a.x -= 0 > this.labelAngle ? c.textBlock.width * Math.cos(Math.PI / 180 * this.labelAngle) : 0, a.y += this.tickLength + Math.abs(0 > this.labelAngle ? c.textBlock.width * Math.sin(Math.PI / 180 * this.labelAngle) - 5 : 5))
                            } else "top" === this._position ? (n = 1 === this.ctx.lineWidth % 2 ? (a.x << 0) + 0.5 : a.x << 0, this.ctx.beginPath(), this.ctx.moveTo(n, a.y << 0), this.ctx.lineTo(n, a.y - this.tickLength << 0), this.ctx.stroke(), 0 === this.labelAngle ? (a.x -= c.textBlock.width /
                                    2, a.y -= this.tickLength + c.textBlock.height) : (a.x += (c.textBlock.height - this.tickLength - this.labelFontSize / 2) * Math.sin(Math.PI / 180 * this.labelAngle) - (0 < this.labelAngle ? c.textBlock.width * Math.cos(Math.PI / 180 * this.labelAngle) : 0), a.y -= this.tickLength + (c.textBlock.height * Math.cos(Math.PI / 180 * this.labelAngle) + (0 < this.labelAngle ? c.textBlock.width * Math.sin(Math.PI / 180 * this.labelAngle) : 0)))) : "left" === this._position ? (n = 1 === this.ctx.lineWidth % 2 ? (a.y << 0) + 0.5 : a.y << 0, this.ctx.beginPath(), this.ctx.moveTo(a.x << 0, n),
                                    this.ctx.lineTo(a.x - this.tickLength << 0, n), this.ctx.stroke(), 0 === this.labelAngle ? a.x = a.x - c.textBlock.width * Math.cos(Math.PI / 180 * this.labelAngle) - this.tickLength - 5 : (a.y -= c.textBlock.width * Math.sin(Math.PI / 180 * this.labelAngle), a.x = 0 < this.labelAngle ? a.x - c.textBlock.width * Math.cos(Math.PI / 180 * this.labelAngle) - this.tickLength - 5 : a.x - c.textBlock.width * Math.cos(Math.PI / 180 * this.labelAngle) + (c.textBlock.height - c.textBlock.fontSize / 2 - 5) * Math.sin(Math.PI / 180 * this.labelAngle) - this.tickLength)) : "right" === this._position &&
                                (n = 1 === this.ctx.lineWidth % 2 ? (a.y << 0) + 0.5 : a.y << 0, this.ctx.beginPath(), this.ctx.moveTo(a.x << 0, n), this.ctx.lineTo(a.x + this.tickLength << 0, n), this.ctx.stroke(), 0 === this.labelAngle ? a.x = a.x + this.tickLength + 5 : (a.y = 0 > this.labelAngle ? a.y : a.y - (c.textBlock.height - c.textBlock.fontSize / 2 - 5) * Math.cos(Math.PI / 180 * this.labelAngle), a.x = 0 < this.labelAngle ? a.x + (c.textBlock.height - c.textBlock.fontSize / 2 - 5) * Math.sin(Math.PI / 180 * this.labelAngle) + this.tickLength : a.x + this.tickLength + 5));
                        else c.textBlock.angle = -90, "bottom" ===
                            this._position ? (c.textBlock.maxWidth = this.options.stripLines[b].labelMaxWidth ? this.options.stripLines[b].labelMaxWidth : this.chart.plotArea.height - 3, c.textBlock.measureText(), a.x - c.textBlock.height > this.chart.plotArea.x1 ? s(k.startValue) ? a.x -= c.textBlock.height - c.textBlock.fontSize / 2 : a.x -= c.textBlock.height / 2 - c.textBlock.fontSize / 2 + 3 : (c.textBlock.angle = 90, s(k.startValue) ? a.x += c.textBlock.height - c.textBlock.fontSize / 2 : a.x += c.textBlock.height / 2 - c.textBlock.fontSize / 2 + 3), a.y = -90 === c.textBlock.angle ? "near" ===
                                c.stripLine.labelAlign ? this.chart.plotArea.y2 - 3 : "center" === c.stripLine.labelAlign ? (this.chart.plotArea.y2 + this.chart.plotArea.y1 + c.textBlock.width) / 2 : this.chart.plotArea.y1 + c.textBlock.width + 3 : "near" === c.stripLine.labelAlign ? this.chart.plotArea.y2 - c.textBlock.width - 3 : "center" === c.stripLine.labelAlign ? (this.chart.plotArea.y2 + this.chart.plotArea.y1 - c.textBlock.width) / 2 : this.chart.plotArea.y1 + 3) : "top" === this._position ? (c.textBlock.maxWidth = this.options.stripLines[b].labelMaxWidth ? this.options.stripLines[b].labelMaxWidth :
                                this.chart.plotArea.height - 3, c.textBlock.measureText(), a.x - c.textBlock.height > this.chart.plotArea.x1 ? s(k.startValue) ? a.x -= c.textBlock.height - c.textBlock.fontSize / 2 : a.x -= c.textBlock.height / 2 - c.textBlock.fontSize / 2 + 3 : (c.textBlock.angle = 90, s(k.startValue) ? a.x += c.textBlock.height - c.textBlock.fontSize / 2 : a.x += c.textBlock.height / 2 - c.textBlock.fontSize / 2 + 3), a.y = -90 === c.textBlock.angle ? "near" === c.stripLine.labelAlign ? this.chart.plotArea.y1 + c.textBlock.width + 3 : "center" === c.stripLine.labelAlign ? (this.chart.plotArea.y2 +
                                    this.chart.plotArea.y1 + c.textBlock.width) / 2 : this.chart.plotArea.y2 - 3 : "near" === c.stripLine.labelAlign ? this.chart.plotArea.y1 + 3 : "center" === c.stripLine.labelAlign ? (this.chart.plotArea.y2 + this.chart.plotArea.y1 - c.textBlock.width) / 2 : this.chart.plotArea.y2 - c.textBlock.width - 3) : "left" === this._position ? (c.textBlock.maxWidth = this.options.stripLines[b].labelMaxWidth ? this.options.stripLines[b].labelMaxWidth : this.chart.plotArea.width - 3, c.textBlock.angle = 0, c.textBlock.measureText(), a.y - c.textBlock.height > this.chart.plotArea.y1 ?
                                s(k.startValue) ? a.y -= c.textBlock.height - c.textBlock.fontSize / 2 : a.y -= c.textBlock.height / 2 - c.textBlock.fontSize + 3 : a.y - c.textBlock.height < this.chart.plotArea.y2 ? a.y += c.textBlock.fontSize / 2 + 3 : s(k.startValue) ? a.y -= c.textBlock.height - c.textBlock.fontSize / 2 : a.y -= c.textBlock.height / 2 - c.textBlock.fontSize + 3, a.x = "near" === c.stripLine.labelAlign ? this.chart.plotArea.x1 + 3 : "center" === c.stripLine.labelAlign ? (this.chart.plotArea.x2 + this.chart.plotArea.x1) / 2 - c.textBlock.width / 2 : this.chart.plotArea.x2 - c.textBlock.width -
                                3) : "right" === this._position && (c.textBlock.maxWidth = this.options.stripLines[b].labelMaxWidth ? this.options.stripLines[b].labelMaxWidth : this.chart.plotArea.width - 3, c.textBlock.angle = 0, c.textBlock.measureText(), a.y - +c.textBlock.height > this.chart.plotArea.y1 ? s(k.startValue) ? a.y -= c.textBlock.height - c.textBlock.fontSize / 2 : a.y -= c.textBlock.height / 2 - c.textBlock.fontSize / 2 - 3 : a.y - c.textBlock.height < this.chart.plotArea.y2 ? a.y += c.textBlock.fontSize / 2 + 3 : s(k.startValue) ? a.y -= c.textBlock.height - c.textBlock.fontSize /
                                2 : a.y -= c.textBlock.height / 2 - c.textBlock.fontSize / 2 + 3, a.x = "near" === c.stripLine.labelAlign ? this.chart.plotArea.x2 - c.textBlock.width - 3 : "center" === c.stripLine.labelAlign ? (this.chart.plotArea.x2 + this.chart.plotArea.x1) / 2 - c.textBlock.width / 2 : this.chart.plotArea.x1 + 3);
                        c.textBlock.x = a.x;
                        c.textBlock.y = a.y;
                        m.push(c)
                    }
                if (!l) {
                    l = !1;
                    this.ctx.save();
                    this.ctx.beginPath();
                    this.ctx.rect(this.chart.plotArea.x1, this.chart.plotArea.y1, this.chart.plotArea.width, this.chart.plotArea.height);
                    this.ctx.clip();
                    for (b = 0; b < h.length; b++) k =
                        h[b], k.showOnTop ? f || (f = !0, this.chart.addEventListener("dataAnimationIterationEnd", function() {
                            this.ctx.save();
                            this.ctx.beginPath();
                            this.ctx.rect(this.chart.plotArea.x1, this.chart.plotArea.y1, this.chart.plotArea.width, this.chart.plotArea.height);
                            this.ctx.clip();
                            for (e = 0; e < h.length; e++) k = h[e], k.showOnTop && k.render();
                            this.ctx.restore()
                        }, k)) : k.render();
                    for (b = 0; b < m.length; b++) c = m[b], c.stripLine.showOnTop ? l || (l = !0, this.chart.addEventListener("dataAnimationIterationEnd", function() {
                        for (e = 0; e < m.length; e++) c =
                            m[e], "inside" === c.stripLine.labelPlacement && c.stripLine.showOnTop && (d.ctx.save(), d.ctx.beginPath(), d.ctx.rect(d.chart.plotArea.x1, d.chart.plotArea.y1, d.chart.plotArea.width, d.chart.plotArea.height), d.ctx.clip(), c.textBlock.render(!0), d.ctx.restore())
                    }, c.textBlock)) : "inside" === c.stripLine.labelPlacement && c.textBlock.render(!0);
                    this.ctx.restore();
                    l = !0
                }
                if (l)
                    for (l = !1, b = 0; b < m.length; b++) c = m[b], "outside" === c.stripLine.labelPlacement && c.textBlock.render(!0)
            }
        };
        D.prototype.renderBreaksBackground = function() {
            this.chart._breaksCanvas &&
                (this.scaleBreaks && 0 < this.scaleBreaks._appliedBreaks.length && this.maskCanvas) && (this.chart._breaksCanvasCtx.save(), this.chart._breaksCanvasCtx.beginPath(), this.chart._breaksCanvasCtx.rect(this.chart.plotArea.x1, this.chart.plotArea.y1, this.chart.plotArea.width, this.chart.plotArea.height), this.chart._breaksCanvasCtx.clip(), this.chart._breaksCanvasCtx.drawImage(this.maskCanvas, 0, 0, this.chart.width, this.chart.height), this.chart._breaksCanvasCtx.restore())
        };
        D.prototype.createMask = function() {
            if (this.scaleBreaks &&
                0 < this.scaleBreaks._appliedBreaks.length) {
                var a = this.scaleBreaks._appliedBreaks;
                u ? (this.maskCanvas = ta(this.chart.width, this.chart.height), this.maskCtx = this.maskCanvas.getContext("2d")) : (this.maskCanvas = this.chart.plotArea.canvas, this.maskCtx = this.chart.plotArea.ctx);
                this.maskCtx.save();
                this.maskCtx.beginPath();
                this.maskCtx.rect(this.chart.plotArea.x1, this.chart.plotArea.y1, this.chart.plotArea.width, this.chart.plotArea.height);
                this.maskCtx.clip();
                for (var d = 0; d < a.length; d++) a[d].endValue < this.viewportMinimum ||
                    (a[d].startValue > this.viewportMaximum || isNaN(this.range)) || a[d].render(this.maskCtx);
                this.maskCtx.restore()
            }
        };
        D.prototype.renderCrosshair = function(a, d) { isFinite(this.minimum) && isFinite(this.maximum) && (this.crosshair.render(a, d), this.crosshair.dispatchEvent("updated", { chart: this.chart, crosshair: this.options, axis: this, value: this.crosshair.value }, this)) };
        D.prototype.showCrosshair = function(a) {
            s(a) || (a < this.viewportMinimum || a > this.viewportMaximum) || ("top" === this._position || "bottom" === this._position ? this.crosshair.render(this.convertValueToPixel(a),
                null, a) : this.crosshair.render(null, this.convertValueToPixel(a), a))
        };
        D.prototype.renderGrid = function() {
            if (this.gridThickness && 0 < this.gridThickness) {
                var a = this.chart.ctx;
                a.save();
                var d, c = this.chart.plotArea;
                a.lineWidth = this.gridThickness;
                a.strokeStyle = this.gridColor;
                a.setLineDash && a.setLineDash(N(this.gridDashType, this.gridThickness));
                if ("bottom" === this._position || "top" === this._position)
                    for (b = 0; b < this._labels.length; b++) this._labels[b].position < this.viewportMinimum || (this._labels[b].position > this.viewportMaximum ||
                        this._labels[b].breaksLabelType) || (a.beginPath(), d = this.getPixelCoordinatesOnAxis(this._labels[b].position), d = 1 === a.lineWidth % 2 ? (d.x << 0) + 0.5 : d.x << 0, a.moveTo(d, c.y1 << 0), a.lineTo(d, c.y2 << 0), a.stroke());
                else if ("left" === this._position || "right" === this._position)
                    for (var b = 0; b < this._labels.length; b++) this._labels[b].position < this.viewportMinimum || (this._labels[b].position > this.viewportMaximum || this._labels[b].breaksLabelType) || (a.beginPath(), d = this.getPixelCoordinatesOnAxis(this._labels[b].position), d =
                        1 === a.lineWidth % 2 ? (d.y << 0) + 0.5 : d.y << 0, a.moveTo(c.x1 << 0, d), a.lineTo(c.x2 << 0, d), a.stroke());
                a.restore()
            }
        };
        D.prototype.renderAxisLine = function() {
            var a = this.chart.ctx,
                d = u ? this.chart._preRenderCtx : a,
                c = Math.ceil(this.tickThickness / (this.reversed ? -2 : 2)),
                b = Math.ceil(this.tickThickness / (this.reversed ? 2 : -2)),
                e, f;
            d.save();
            if ("bottom" === this._position || "top" === this._position) {
                if (this.lineThickness) {
                    this.reversed ? (e = this.lineCoordinates.x2, f = this.lineCoordinates.x1) : (e = this.lineCoordinates.x1, f = this.lineCoordinates.x2);
                    d.lineWidth = this.lineThickness;
                    d.strokeStyle = this.lineColor ? this.lineColor : "black";
                    d.setLineDash && d.setLineDash(N(this.lineDashType, this.lineThickness));
                    var l = 1 === this.lineThickness % 2 ? (this.lineCoordinates.y1 << 0) + 0.5 : this.lineCoordinates.y1 << 0;
                    d.beginPath();
                    if (this.scaleBreaks && !s(this.scaleBreaks.firstBreakIndex))
                        if (s(this.scaleBreaks.lastBreakIndex)) e = this.scaleBreaks._appliedBreaks[this.scaleBreaks.firstBreakIndex].endPixel + b;
                        else
                            for (var h = this.scaleBreaks.firstBreakIndex; h <= this.scaleBreaks.lastBreakIndex; h++) d.moveTo(e,
                                l), d.lineTo(this.scaleBreaks._appliedBreaks[h].startPixel + c, l), e = this.scaleBreaks._appliedBreaks[h].endPixel + b;
                    e && (d.moveTo(e, l), d.lineTo(f, l));
                    d.stroke()
                }
            } else if (("left" === this._position || "right" === this._position) && this.lineThickness) {
                this.reversed ? (e = this.lineCoordinates.y1, f = this.lineCoordinates.y2) : (e = this.lineCoordinates.y2, f = this.lineCoordinates.y1);
                d.lineWidth = this.lineThickness;
                d.strokeStyle = this.lineColor;
                d.setLineDash && d.setLineDash(N(this.lineDashType, this.lineThickness));
                l = 1 === this.lineThickness %
                    2 ? (this.lineCoordinates.x1 << 0) + 0.5 : this.lineCoordinates.x1 << 0;
                d.beginPath();
                if (this.scaleBreaks && !s(this.scaleBreaks.firstBreakIndex))
                    if (s(this.scaleBreaks.lastBreakIndex)) e = this.scaleBreaks._appliedBreaks[this.scaleBreaks.firstBreakIndex].endPixel + c;
                    else
                        for (h = this.scaleBreaks.firstBreakIndex; h <= this.scaleBreaks.lastBreakIndex; h++) d.moveTo(l, e), d.lineTo(l, this.scaleBreaks._appliedBreaks[h].startPixel + b), e = this.scaleBreaks._appliedBreaks[h].endPixel + c;
                e && (d.moveTo(l, e), d.lineTo(l, f));
                d.stroke()
            }
            u &&
                (a.drawImage(this.chart._preRenderCanvas, 0, 0, this.chart.width, this.chart.height), this.chart._breaksCanvasCtx && this.chart._breaksCanvasCtx.drawImage(this.chart._preRenderCanvas, 0, 0, this.chart.width, this.chart.height), d.clearRect(0, 0, this.chart.width, this.chart.height));
            d.restore()
        };
        D.prototype.getPixelCoordinatesOnAxis = function(a) {
            var d = {};
            if ("bottom" === this._position || "top" === this._position) d.x = this.convertValueToPixel(a), d.y = this.lineCoordinates.y1;
            if ("left" === this._position || "right" === this._position) d.y =
                this.convertValueToPixel(a), d.x = this.lineCoordinates.x2;
            return d
        };
        D.prototype.convertPixelToValue = function(a) {
            if ("undefined" === typeof a) return null;
            var d = 0,
                c = 0,
                b, d = !0,
                e = this.scaleBreaks ? this.scaleBreaks._appliedBreaks : [],
                c = "number" === typeof a ? a : "left" === this._position || "right" === this._position ? a.y : a.x;
            if (this.logarithmic) {
                a = b = Math.pow(this.logarithmBase, (c - this.conversionParameters.reference) / this.conversionParameters.pixelPerUnit);
                if (c <= this.conversionParameters.reference === ("left" === this._position ||
                        "right" === this._position) !== this.reversed)
                    for (c = 0; c < e.length; c++) {
                        if (!(e[c].endValue < this.conversionParameters.minimum))
                            if (d)
                                if (e[c].startValue < this.conversionParameters.minimum) {
                                    if (1 < e[c].size && this.conversionParameters.minimum * Math.pow(e[c].endValue / e[c].startValue, Math.log(b) / Math.log(e[c].size)) < e[c].endValue) { a = Math.pow(e[c].endValue / e[c].startValue, Math.log(b) / Math.log(e[c].size)); break } else a *= e[c].endValue / this.conversionParameters.minimum / Math.pow(e[c].size, Math.log(e[c].endValue / this.conversionParameters.minimum) /
                                        Math.log(e[c].endValue / e[c].startValue)), b /= Math.pow(e[c].size, Math.log(e[c].endValue / this.conversionParameters.minimum) / Math.log(e[c].endValue / e[c].startValue));
                                    d = !1
                                } else if (b > e[c].startValue / this.conversionParameters.minimum) {
                            b /= e[c].startValue / this.conversionParameters.minimum;
                            if (b < e[c].size) { a *= Math.pow(e[c].endValue / e[c].startValue, 1 === e[c].size ? 1 : Math.log(b) / Math.log(e[c].size)) / b; break } else a *= e[c].endValue / e[c].startValue / e[c].size;
                            b /= e[c].size;
                            d = !1
                        } else break;
                        else if (b > e[c].startValue / e[c -
                                1].endValue) {
                            b /= e[c].startValue / e[c - 1].endValue;
                            if (b < e[c].size) { a *= Math.pow(e[c].endValue / e[c].startValue, 1 === e[c].size ? 1 : Math.log(b) / Math.log(e[c].size)) / b; break } else a *= e[c].endValue / e[c].startValue / e[c].size;
                            b /= e[c].size
                        } else break
                    } else
                        for (c = e.length - 1; 0 <= c; c--)
                            if (!(e[c].startValue > this.conversionParameters.minimum))
                                if (d)
                                    if (e[c].endValue > this.conversionParameters.minimum) {
                                        if (1 < e[c].size && this.conversionParameters.minimum * Math.pow(e[c].endValue / e[c].startValue, Math.log(b) / Math.log(e[c].size)) >
                                            e[c].startValue) { a = Math.pow(e[c].endValue / e[c].startValue, Math.log(b) / Math.log(e[c].size)); break } else a *= e[c].startValue / this.conversionParameters.minimum * Math.pow(e[c].size, Math.log(e[c].startValue / this.conversionParameters.minimum) / Math.log(e[c].endValue / e[c].startValue)) * b, b *= Math.pow(e[c].size, Math.log(this.conversionParameters.minimum / e[c].startValue) / Math.log(e[c].endValue / e[c].startValue));
                                        d = !1
                                    } else if (b < e[c].endValue / this.conversionParameters.minimum) {
                    b /= e[c].endValue / this.conversionParameters.minimum;
                    if (b > 1 / e[c].size) { a *= Math.pow(e[c].endValue / e[c].startValue, 1 >= e[c].size ? 1 : Math.log(b) / Math.log(e[c].size)) * b; break } else a /= e[c].endValue / e[c].startValue / e[c].size;
                    b *= e[c].size;
                    d = !1
                } else break;
                else if (b < e[c].endValue / e[c + 1].startValue) {
                    b /= e[c].endValue / e[c + 1].startValue;
                    if (b > 1 / e[c].size) { a *= Math.pow(e[c].endValue / e[c].startValue, 1 >= e[c].size ? 1 : Math.log(b) / Math.log(e[c].size)) * b; break } else a /= e[c].endValue / e[c].startValue / e[c].size;
                    b *= e[c].size
                } else break;
                d = a * this.viewportMinimum
            } else {
                a = b = (c - this.conversionParameters.reference) /
                    this.conversionParameters.pixelPerUnit;
                if (c <= this.conversionParameters.reference === ("left" === this._position || "right" === this._position) !== this.reversed)
                    for (c = 0; c < e.length; c++) {
                        if (!(e[c].endValue < this.conversionParameters.minimum))
                            if (d)
                                if (e[c].startValue < this.conversionParameters.minimum) {
                                    if (e[c].size && this.conversionParameters.minimum + b * (e[c].endValue - e[c].startValue) / e[c].size < e[c].endValue) { a = 0 >= e[c].size ? 0 : b * (e[c].endValue - e[c].startValue) / e[c].size; break } else a += e[c].endValue - this.conversionParameters.minimum -
                                        e[c].size * (e[c].endValue - this.conversionParameters.minimum) / (e[c].endValue - e[c].startValue), b -= e[c].size * (e[c].endValue - this.conversionParameters.minimum) / (e[c].endValue - e[c].startValue);
                                    d = !1
                                } else if (b > e[c].startValue - this.conversionParameters.minimum) {
                            b -= e[c].startValue - this.conversionParameters.minimum;
                            if (b < e[c].size) { a += (e[c].endValue - e[c].startValue) * (0 === e[c].size ? 1 : b / e[c].size) - b; break } else a += e[c].endValue - e[c].startValue - e[c].size;
                            b -= e[c].size;
                            d = !1
                        } else break;
                        else if (b > e[c].startValue - e[c -
                                1].endValue) {
                            b -= e[c].startValue - e[c - 1].endValue;
                            if (b < e[c].size) { a += (e[c].endValue - e[c].startValue) * (0 === e[c].size ? 1 : b / e[c].size) - b; break } else a += e[c].endValue - e[c].startValue - e[c].size;
                            b -= e[c].size
                        } else break
                    } else
                        for (c = e.length - 1; 0 <= c; c--)
                            if (!(e[c].startValue > this.conversionParameters.minimum))
                                if (d)
                                    if (e[c].endValue > this.conversionParameters.minimum)
                                        if (e[c].size && this.conversionParameters.minimum + b * (e[c].endValue - e[c].startValue) / e[c].size > e[c].startValue) {
                                            a = 0 >= e[c].size ? 0 : b * (e[c].endValue - e[c].startValue) /
                                                e[c].size;
                                            break
                                        } else a += e[c].startValue - this.conversionParameters.minimum + e[c].size * (this.conversionParameters.minimum - e[c].startValue) / (e[c].endValue - e[c].startValue), b += e[c].size * (this.conversionParameters.minimum - e[c].startValue) / (e[c].endValue - e[c].startValue), d = !1;
                else if (b < e[c].endValue - this.conversionParameters.minimum) {
                    b -= e[c].endValue - this.conversionParameters.minimum;
                    if (b > -1 * e[c].size) { a += (e[c].endValue - e[c].startValue) * (0 === e[c].size ? 1 : b / e[c].size) + b; break } else a -= e[c].endValue - e[c].startValue -
                        e[c].size;
                    b += e[c].size;
                    d = !1
                } else break;
                else if (b < e[c].endValue - e[c + 1].startValue) {
                    b -= e[c].endValue - e[c + 1].startValue;
                    if (b > -1 * e[c].size) { a += (e[c].endValue - e[c].startValue) * (0 === e[c].size ? 1 : b / e[c].size) + b; break } else a -= e[c].endValue - e[c].startValue - e[c].size;
                    b += e[c].size
                } else break;
                d = this.conversionParameters.minimum + a
            }
            return d
        };
        D.prototype.convertValueToPixel = function(a) {
            a = this.getApparentDifference(this.conversionParameters.minimum, a, a);
            return this.logarithmic ? this.conversionParameters.reference +
                this.conversionParameters.pixelPerUnit * Math.log(a / this.conversionParameters.minimum) / this.conversionParameters.lnLogarithmBase + 0.5 << 0 : "axisX" === this.type ? this.conversionParameters.reference + this.conversionParameters.pixelPerUnit * (a - this.conversionParameters.minimum) + 0.5 << 0 : this.conversionParameters.reference + this.conversionParameters.pixelPerUnit * (a - this.conversionParameters.minimum) + 0.5
        };
        D.prototype.getApparentDifference = function(a, d, c, b) {
            var e = this.scaleBreaks ? this.scaleBreaks._appliedBreaks : [];
            if (this.logarithmic) {
                c = s(c) ? d / a : c;
                for (var f = 0; f < e.length && !(d < e[f].startValue); f++) a > e[f].endValue || (a <= e[f].startValue && d >= e[f].endValue ? c = c / e[f].endValue * e[f].startValue * e[f].size : a >= e[f].startValue && d >= e[f].endValue ? c = c / e[f].endValue * a * Math.pow(e[f].size, Math.log(e[f].endValue / a) / Math.log(e[f].endValue / e[f].startValue)) : a <= e[f].startValue && d <= e[f].endValue ? c = c / d * e[f].startValue * Math.pow(e[f].size, Math.log(d / e[f].startValue) / Math.log(e[f].endValue / e[f].startValue)) : !b && (a > e[f].startValue && d < e[f].endValue) &&
                    (c = a * Math.pow(e[f].size, Math.log(d / a) / Math.log(e[f].endValue / e[f].startValue))))
            } else
                for (c = s(c) ? Math.abs(d - a) : c, f = 0; f < e.length && !(d < e[f].startValue); f++) a > e[f].endValue || (a <= e[f].startValue && d >= e[f].endValue ? c = c - e[f].endValue + e[f].startValue + e[f].size : a > e[f].startValue && d >= e[f].endValue ? c = c - e[f].endValue + a + e[f].size * (e[f].endValue - a) / (e[f].endValue - e[f].startValue) : a <= e[f].startValue && d < e[f].endValue ? c = c - d + e[f].startValue + e[f].size * (d - e[f].startValue) / (e[f].endValue - e[f].startValue) : !b && (a > e[f].startValue &&
                    d < e[f].endValue) && (c = a + e[f].size * (d - a) / (e[f].endValue - e[f].startValue)));
            return c
        };
        D.prototype.setViewPortRange = function(a, d) {
            this.sessionVariables.newViewportMinimum = this.viewportMinimum = Math.min(a, d);
            this.sessionVariables.newViewportMaximum = this.viewportMaximum = Math.max(a, d)
        };
        D.prototype.getXValueAt = function(a) { if (!a) return null; var d = null; "left" === this._position ? d = this.convertPixelToValue(a.y) : "bottom" === this._position && (d = this.convertPixelToValue(a.x)); return d };
        D.prototype.calculateValueToPixelConversionParameters =
            function(a) {
                a = this.scaleBreaks ? this.scaleBreaks._appliedBreaks : [];
                var d = { pixelPerUnit: null, minimum: null, reference: null },
                    c = this.lineCoordinates.width,
                    b = this.lineCoordinates.height,
                    c = "bottom" === this._position || "top" === this._position ? c : b,
                    b = Math.abs(this.range);
                if (this.logarithmic)
                    for (var e = 0; e < a.length && !(this.viewportMaximum < a[e].startValue); e++) this.viewportMinimum > a[e].endValue || (this.viewportMinimum >= a[e].startValue && this.viewportMaximum <= a[e].endValue ? c = 0 : this.viewportMinimum <= a[e].startValue &&
                        this.viewportMaximum >= a[e].endValue ? (b = b / a[e].endValue * a[e].startValue, c = 0 < a[e].spacing.toString().indexOf("%") ? c * (1 - parseFloat(a[e].spacing) / 100) : c - Math.min(a[e].spacing, 0.1 * c)) : this.viewportMinimum > a[e].startValue && this.viewportMaximum >= a[e].endValue ? (b = b / a[e].endValue * this.viewportMinimum, c = 0 < a[e].spacing.toString().indexOf("%") ? c * (1 - parseFloat(a[e].spacing) / 100 * Math.log(a[e].endValue / this.viewportMinimum) / Math.log(a[e].endValue / a[e].startValue)) : c - Math.min(a[e].spacing, 0.1 * c) * Math.log(a[e].endValue /
                            this.viewportMinimum) / Math.log(a[e].endValue / a[e].startValue)) : this.viewportMinimum <= a[e].startValue && this.viewportMaximum < a[e].endValue && (b = b / this.viewportMaximum * a[e].startValue, c = 0 < a[e].spacing.toString().indexOf("%") ? c * (1 - parseFloat(a[e].spacing) / 100 * Math.log(this.viewportMaximum / a[e].startValue) / Math.log(a[e].endValue / a[e].startValue)) : c - Math.min(a[e].spacing, 0.1 * c) * Math.log(this.viewportMaximum / a[e].startValue) / Math.log(a[e].endValue / a[e].startValue)));
                else
                    for (e = 0; e < a.length && !(this.viewportMaximum <
                            a[e].startValue); e++) this.viewportMinimum > a[e].endValue || (this.viewportMinimum >= a[e].startValue && this.viewportMaximum <= a[e].endValue ? c = 0 : this.viewportMinimum <= a[e].startValue && this.viewportMaximum >= a[e].endValue ? (b = b - a[e].endValue + a[e].startValue, c = 0 < a[e].spacing.toString().indexOf("%") ? c * (1 - parseFloat(a[e].spacing) / 100) : c - Math.min(a[e].spacing, 0.1 * c)) : this.viewportMinimum > a[e].startValue && this.viewportMaximum >= a[e].endValue ? (b = b - a[e].endValue + this.viewportMinimum, c = 0 < a[e].spacing.toString().indexOf("%") ?
                        c * (1 - parseFloat(a[e].spacing) / 100 * (a[e].endValue - this.viewportMinimum) / (a[e].endValue - a[e].startValue)) : c - Math.min(a[e].spacing, 0.1 * c) * (a[e].endValue - this.viewportMinimum) / (a[e].endValue - a[e].startValue)) : this.viewportMinimum <= a[e].startValue && this.viewportMaximum < a[e].endValue && (b = b - this.viewportMaximum + a[e].startValue, c = 0 < a[e].spacing.toString().indexOf("%") ? c * (1 - parseFloat(a[e].spacing) / 100 * (this.viewportMaximum - a[e].startValue) / (a[e].endValue - a[e].startValue)) : c - Math.min(a[e].spacing, 0.1 * c) * (this.viewportMaximum -
                        a[e].startValue) / (a[e].endValue - a[e].startValue)));
                d.minimum = this.viewportMinimum;
                d.maximum = this.viewportMaximum;
                d.range = b;
                if ("bottom" === this._position || "top" === this._position) this.logarithmic ? (d.lnLogarithmBase = Math.log(this.logarithmBase), d.pixelPerUnit = (this.reversed ? -1 : 1) * c * d.lnLogarithmBase / Math.log(Math.abs(b))) : d.pixelPerUnit = (this.reversed ? -1 : 1) * c / Math.abs(b), d.reference = this.reversed ? this.lineCoordinates.x2 : this.lineCoordinates.x1;
                if ("left" === this._position || "right" === this._position) this.logarithmic ?
                    (d.lnLogarithmBase = Math.log(this.logarithmBase), d.pixelPerUnit = (this.reversed ? 1 : -1) * c * d.lnLogarithmBase / Math.log(Math.abs(b))) : d.pixelPerUnit = (this.reversed ? 1 : -1) * c / Math.abs(b), d.reference = this.reversed ? this.lineCoordinates.y1 : this.lineCoordinates.y2;
                this.conversionParameters = d
            };
        D.prototype.calculateAxisParameters = function() {
            if (this.logarithmic) this.calculateLogarithmicAxisParameters();
            else {
                var a = this.chart.layoutManager.getFreeSpace(),
                    d = !1,
                    c = !1;
                "bottom" === this._position || "top" === this._position ? (this.maxWidth =
                    a.width, this.maxHeight = a.height) : (this.maxWidth = a.height, this.maxHeight = a.width);
                var a = "axisX" === this.type ? "xySwapped" === this.chart.plotInfo.axisPlacement ? 62 : 70 : "xySwapped" === this.chart.plotInfo.axisPlacement ? 50 : 40,
                    b = 4;
                "axisX" === this.type && (b = 600 > this.maxWidth ? 8 : 6);
                var a = Math.max(b, Math.floor(this.maxWidth / a)),
                    e, f, l, b = 0;
                !s(this.options.viewportMinimum) && (!s(this.options.viewportMaximum) && this.options.viewportMinimum >= this.options.viewportMaximum) && (this.viewportMinimum = this.viewportMaximum = null);
                if (s(this.options.viewportMinimum) && !s(this.sessionVariables.newViewportMinimum) && !isNaN(this.sessionVariables.newViewportMinimum)) this.viewportMinimum = this.sessionVariables.newViewportMinimum;
                else if (null === this.viewportMinimum || isNaN(this.viewportMinimum)) this.viewportMinimum = this.minimum;
                if (s(this.options.viewportMaximum) && !s(this.sessionVariables.newViewportMaximum) && !isNaN(this.sessionVariables.newViewportMaximum)) this.viewportMaximum = this.sessionVariables.newViewportMaximum;
                else if (null === this.viewportMaximum ||
                    isNaN(this.viewportMaximum)) this.viewportMaximum = this.maximum;
                if (this.scaleBreaks)
                    for (b = 0; b < this.scaleBreaks._appliedBreaks.length; b++)
                        if ((!s(this.sessionVariables.newViewportMinimum) && this.sessionVariables.newViewportMinimum >= this.scaleBreaks._appliedBreaks[b].startValue || !s(this.options.minimum) && this.options.minimum >= this.scaleBreaks._appliedBreaks[b].startValue || !s(this.options.viewportMinimum) && this.viewportMinimum >= this.scaleBreaks._appliedBreaks[b].startValue) && (!s(this.sessionVariables.newViewportMaximum) &&
                                this.sessionVariables.newViewportMaximum <= this.scaleBreaks._appliedBreaks[b].endValue || !s(this.options.maximum) && this.options.maximum <= this.scaleBreaks._appliedBreaks[b].endValue || !s(this.options.viewportMaximum) && this.viewportMaximum <= this.scaleBreaks._appliedBreaks[b].endValue)) { this.scaleBreaks._appliedBreaks.splice(b, 1); break }
                if ("axisX" === this.type) {
                    if (this.dataSeries && 0 < this.dataSeries.length)
                        for (e = 0; e < this.dataSeries.length; e++) "dateTime" === this.dataSeries[e].xValueType && (c = !0);
                    e = null !== this.viewportMinimum ?
                        this.viewportMinimum : this.dataInfo.viewPortMin;
                    f = null !== this.viewportMaximum ? this.viewportMaximum : this.dataInfo.viewPortMax;
                    0 === f - e && (b = "undefined" === typeof this.options.interval ? 0.4 : this.options.interval, f += b, e -= b);
                    Infinity !== this.dataInfo.minDiff ? l = this.dataInfo.minDiff : 1 < f - e ? l = 0.5 * Math.abs(f - e) : (l = 1, c && (d = !0))
                } else "axisY" === this.type && (e = null !== this.viewportMinimum ? this.viewportMinimum : this.dataInfo.viewPortMin, f = null !== this.viewportMaximum ? this.viewportMaximum : this.dataInfo.viewPortMax, isFinite(e) ||
                    isFinite(f) ? isFinite(e) ? isFinite(f) || (f = e) : e = f : (f = "undefined" === typeof this.options.interval ? -Infinity : this.options.interval, e = "undefined" !== typeof this.options.interval || isFinite(this.dataInfo.minDiff) ? 0 : Infinity), 0 === e && 0 === f ? (f += 9, e = 0) : 0 === f - e ? (b = Math.min(Math.abs(0.01 * Math.abs(f)), 5), f += b, e -= b) : e > f ? (b = Math.min(0.01 * Math.abs(this.getApparentDifference(f, e, null, !0)), 5), 0 <= f ? e = f - b : f = isFinite(e) ? e + b : 0) : (b = Math.min(0.01 * Math.abs(this.getApparentDifference(e, f, null, !0)), 0.05), 0 !== f && (f += b), 0 !== e && (e -=
                        b)), l = Infinity !== this.dataInfo.minDiff ? this.dataInfo.minDiff : 1 < f - e ? 0.5 * Math.abs(f - e) : 1, this.includeZero && (null === this.viewportMinimum || isNaN(this.viewportMinimum)) && 0 < e && (e = 0), this.includeZero && (null === this.viewportMaximum || isNaN(this.viewportMaximum)) && 0 > f && (f = 0));
                b = this.getApparentDifference(isNaN(this.viewportMinimum) || null === this.viewportMinimum ? e : this.viewportMinimum, isNaN(this.viewportMaximum) || null === this.viewportMaximum ? f : this.viewportMaximum, null, !0);
                if ("axisX" === this.type && c) {
                    this.intervalType ||
                        (b / 1 <= a ? (this.interval = 1, this.intervalType = "millisecond") : b / 2 <= a ? (this.interval = 2, this.intervalType = "millisecond") : b / 5 <= a ? (this.interval = 5, this.intervalType = "millisecond") : b / 10 <= a ? (this.interval = 10, this.intervalType = "millisecond") : b / 20 <= a ? (this.interval = 20, this.intervalType = "millisecond") : b / 50 <= a ? (this.interval = 50, this.intervalType = "millisecond") : b / 100 <= a ? (this.interval = 100, this.intervalType = "millisecond") : b / 200 <= a ? (this.interval = 200, this.intervalType = "millisecond") : b / 250 <= a ? (this.interval = 250, this.intervalType =
                                "millisecond") : b / 300 <= a ? (this.interval = 300, this.intervalType = "millisecond") : b / 400 <= a ? (this.interval = 400, this.intervalType = "millisecond") : b / 500 <= a ? (this.interval = 500, this.intervalType = "millisecond") : b / (1 * R.secondDuration) <= a ? (this.interval = 1, this.intervalType = "second") : b / (2 * R.secondDuration) <= a ? (this.interval = 2, this.intervalType = "second") : b / (5 * R.secondDuration) <= a ? (this.interval = 5, this.intervalType = "second") : b / (10 * R.secondDuration) <= a ? (this.interval = 10, this.intervalType = "second") : b / (15 * R.secondDuration) <=
                            a ? (this.interval = 15, this.intervalType = "second") : b / (20 * R.secondDuration) <= a ? (this.interval = 20, this.intervalType = "second") : b / (30 * R.secondDuration) <= a ? (this.interval = 30, this.intervalType = "second") : b / (1 * R.minuteDuration) <= a ? (this.interval = 1, this.intervalType = "minute") : b / (2 * R.minuteDuration) <= a ? (this.interval = 2, this.intervalType = "minute") : b / (5 * R.minuteDuration) <= a ? (this.interval = 5, this.intervalType = "minute") : b / (10 * R.minuteDuration) <= a ? (this.interval = 10, this.intervalType = "minute") : b / (15 * R.minuteDuration) <=
                            a ? (this.interval = 15, this.intervalType = "minute") : b / (20 * R.minuteDuration) <= a ? (this.interval = 20, this.intervalType = "minute") : b / (30 * R.minuteDuration) <= a ? (this.interval = 30, this.intervalType = "minute") : b / (1 * R.hourDuration) <= a ? (this.interval = 1, this.intervalType = "hour") : b / (2 * R.hourDuration) <= a ? (this.interval = 2, this.intervalType = "hour") : b / (3 * R.hourDuration) <= a ? (this.interval = 3, this.intervalType = "hour") : b / (6 * R.hourDuration) <= a ? (this.interval = 6, this.intervalType = "hour") : b / (1 * R.dayDuration) <= a ? (this.interval = 1,
                                this.intervalType = "day") : b / (2 * R.dayDuration) <= a ? (this.interval = 2, this.intervalType = "day") : b / (4 * R.dayDuration) <= a ? (this.interval = 4, this.intervalType = "day") : b / (1 * R.weekDuration) <= a ? (this.interval = 1, this.intervalType = "week") : b / (2 * R.weekDuration) <= a ? (this.interval = 2, this.intervalType = "week") : b / (3 * R.weekDuration) <= a ? (this.interval = 3, this.intervalType = "week") : b / (1 * R.monthDuration) <= a ? (this.interval = 1, this.intervalType = "month") : b / (2 * R.monthDuration) <= a ? (this.interval = 2, this.intervalType = "month") : b / (3 * R.monthDuration) <=
                            a ? (this.interval = 3, this.intervalType = "month") : b / (6 * R.monthDuration) <= a ? (this.interval = 6, this.intervalType = "month") : (this.interval = b / (1 * R.yearDuration) <= a ? 1 : b / (2 * R.yearDuration) <= a ? 2 : b / (4 * R.yearDuration) <= a ? 4 : Math.floor(D.getNiceNumber(b / (a - 1), !0) / R.yearDuration), this.intervalType = "year"));
                    if (null === this.viewportMinimum || isNaN(this.viewportMinimum)) this.viewportMinimum = e - l / 2;
                    if (null === this.viewportMaximum || isNaN(this.viewportMaximum)) this.viewportMaximum = f + l / 2;
                    d ? this.autoValueFormatString = "MMM DD YYYY HH:mm" :
                        "year" === this.intervalType ? this.autoValueFormatString = "YYYY" : "month" === this.intervalType ? this.autoValueFormatString = "MMM YYYY" : "week" === this.intervalType ? this.autoValueFormatString = "MMM DD YYYY" : "day" === this.intervalType ? this.autoValueFormatString = "MMM DD YYYY" : "hour" === this.intervalType ? this.autoValueFormatString = "hh:mm TT" : "minute" === this.intervalType ? this.autoValueFormatString = "hh:mm TT" : "second" === this.intervalType ? this.autoValueFormatString = "hh:mm:ss TT" : "millisecond" === this.intervalType && (this.autoValueFormatString =
                            "fff'ms'");
                    this.valueFormatString || (this.valueFormatString = this.autoValueFormatString)
                } else {
                    this.intervalType = "number";
                    b = D.getNiceNumber(b, !1);
                    this.interval = this.options && 0 < this.options.interval ? this.options.interval : D.getNiceNumber(b / (a - 1), !0);
                    if (null === this.viewportMinimum || isNaN(this.viewportMinimum)) this.viewportMinimum = "axisX" === this.type ? e - l / 2 : Math.floor(e / this.interval) * this.interval;
                    if (null === this.viewportMaximum || isNaN(this.viewportMaximum)) this.viewportMaximum = "axisX" === this.type ? f + l / 2 :
                        Math.ceil(f / this.interval) * this.interval;
                    0 === this.viewportMaximum && 0 === this.viewportMinimum && (0 === this.options.viewportMinimum ? this.viewportMaximum += 10 : 0 === this.options.viewportMaximum && (this.viewportMinimum -= 10), this.options && "undefined" === typeof this.options.interval && (this.interval = D.getNiceNumber((this.viewportMaximum - this.viewportMinimum) / (a - 1), !0)))
                }
                if (null === this.minimum || null === this.maximum)
                    if ("axisX" === this.type ? (e = null !== this.minimum ? this.minimum : this.dataInfo.min, f = null !== this.maximum ?
                            this.maximum : this.dataInfo.max, 0 === f - e && (b = "undefined" === typeof this.options.interval ? 0.4 : this.options.interval, f += b, e -= b), l = Infinity !== this.dataInfo.minDiff ? this.dataInfo.minDiff : 1 < f - e ? 0.5 * Math.abs(f - e) : 1) : "axisY" === this.type && (e = null !== this.minimum ? this.minimum : this.dataInfo.min, f = null !== this.maximum ? this.maximum : this.dataInfo.max, isFinite(e) || isFinite(f) ? 0 === e && 0 === f ? (f += 9, e = 0) : 0 === f - e ? (b = Math.min(Math.abs(0.01 * Math.abs(f)), 5), f += b, e -= b) : e > f ? (b = Math.min(0.01 * Math.abs(this.getApparentDifference(f,
                            e, null, !0)), 5), 0 <= f ? e = f - b : f = isFinite(e) ? e + b : 0) : (b = Math.min(0.01 * Math.abs(this.getApparentDifference(e, f, null, !0)), 0.05), 0 !== f && (f += b), 0 !== e && (e -= b)) : (f = "undefined" === typeof this.options.interval ? -Infinity : this.options.interval, e = "undefined" !== typeof this.options.interval || isFinite(this.dataInfo.minDiff) ? 0 : Infinity), l = Infinity !== this.dataInfo.minDiff ? this.dataInfo.minDiff : 1 < f - e ? 0.5 * Math.abs(f - e) : 1, this.includeZero && (null === this.minimum || isNaN(this.minimum)) && 0 < e && (e = 0), this.includeZero && (null === this.maximum ||
                            isNaN(this.maximum)) && 0 > f && (f = 0)), Math.abs(this.getApparentDifference(e, f, null, !0)), "axisX" === this.type && c) {
                        this.valueType = "dateTime";
                        if (null === this.minimum || isNaN(this.minimum)) this.minimum = e - l / 2, this.minimum = Math.min(this.minimum, null === this.sessionVariables.viewportMinimum || isNaN(this.sessionVariables.viewportMinimum) ? Infinity : this.sessionVariables.viewportMinimum);
                        if (null === this.maximum || isNaN(this.maximum)) this.maximum = f + l / 2, this.maximum = Math.max(this.maximum, null === this.sessionVariables.viewportMaximum ||
                            isNaN(this.sessionVariables.viewportMaximum) ? -Infinity : this.sessionVariables.viewportMaximum)
                    } else this.intervalType = this.valueType = "number", null === this.minimum && (this.minimum = "axisX" === this.type ? e - l / 2 : Math.floor(e / this.interval) * this.interval, this.minimum = Math.min(this.minimum, null === this.sessionVariables.viewportMinimum || isNaN(this.sessionVariables.viewportMinimum) ? Infinity : this.sessionVariables.viewportMinimum)), null === this.maximum && (this.maximum = "axisX" === this.type ? f + l / 2 : Math.ceil(f / this.interval) *
                        this.interval, this.maximum = Math.max(this.maximum, null === this.sessionVariables.viewportMaximum || isNaN(this.sessionVariables.viewportMaximum) ? -Infinity : this.sessionVariables.viewportMaximum)), 0 === this.maximum && 0 === this.minimum && (0 === this.options.minimum ? this.maximum += 10 : 0 === this.options.maximum && (this.minimum -= 10));
                s(this.sessionVariables.newViewportMinimum) && (this.viewportMinimum = Math.max(this.viewportMinimum, this.minimum));
                s(this.sessionVariables.newViewportMaximum) && (this.viewportMaximum = Math.min(this.viewportMaximum,
                    this.maximum));
                this.range = this.viewportMaximum - this.viewportMinimum;
                this.intervalStartPosition = "axisX" === this.type && c ? this.getLabelStartPoint(new Date(this.viewportMinimum), this.intervalType, this.interval) : Math.floor((this.viewportMinimum + 0.2 * this.interval) / this.interval) * this.interval;
                this.valueFormatString || (this.valueFormatString = D.generateValueFormatString(this.range, 2))
            }
        };
        D.prototype.calculateLogarithmicAxisParameters = function() {
            var a = this.chart.layoutManager.getFreeSpace(),
                d = Math.log(this.logarithmBase),
                c;
            "bottom" === this._position || "top" === this._position ? (this.maxWidth = a.width, this.maxHeight = a.height) : (this.maxWidth = a.height, this.maxHeight = a.width);
            var a = "axisX" === this.type ? 500 > this.maxWidth ? 7 : Math.max(7, Math.floor(this.maxWidth / 100)) : Math.max(Math.floor(this.maxWidth / 50), 3),
                b, e, f, l;
            l = 1;
            if (null === this.viewportMinimum || isNaN(this.viewportMinimum)) this.viewportMinimum = this.minimum;
            if (null === this.viewportMaximum || isNaN(this.viewportMaximum)) this.viewportMaximum = this.maximum;
            if (this.scaleBreaks)
                for (l =
                    0; l < this.scaleBreaks._appliedBreaks.length; l++)
                    if ((!s(this.sessionVariables.newViewportMinimum) && this.sessionVariables.newViewportMinimum >= this.scaleBreaks._appliedBreaks[l].startValue || !s(this.options.minimum) && this.options.minimum >= this.scaleBreaks._appliedBreaks[l].startValue || !s(this.options.viewportMinimum) && this.viewportMinimum >= this.scaleBreaks._appliedBreaks[l].startValue) && (!s(this.sessionVariables.newViewportMaximum) && this.sessionVariables.newViewportMaximum <= this.scaleBreaks._appliedBreaks[l].endValue ||
                            !s(this.options.maximum) && this.options.maximum <= this.scaleBreaks._appliedBreaks[l].endValue || !s(this.options.viewportMaximum) && this.viewportMaximum <= this.scaleBreaks._appliedBreaks[l].endValue)) { this.scaleBreaks._appliedBreaks.splice(l, 1); break }
                    "axisX" === this.type ? (b = null !== this.viewportMinimum ? this.viewportMinimum : this.dataInfo.viewPortMin, e = null !== this.viewportMaximum ? this.viewportMaximum : this.dataInfo.viewPortMax, 1 === e / b && (l = Math.pow(this.logarithmBase, "undefined" === typeof this.options.interval ?
                0.4 : this.options.interval), e *= l, b /= l), f = Infinity !== this.dataInfo.minDiff ? this.dataInfo.minDiff : e / b > this.logarithmBase ? e / b * Math.pow(this.logarithmBase, 0.5) : this.logarithmBase) : "axisY" === this.type && (b = null !== this.viewportMinimum ? this.viewportMinimum : this.dataInfo.viewPortMin, e = null !== this.viewportMaximum ? this.viewportMaximum : this.dataInfo.viewPortMax, 0 >= b && !isFinite(e) ? (e = "undefined" === typeof this.options.interval ? 0 : this.options.interval, b = 1) : 0 >= b ? b = e : isFinite(e) || (e = b), 1 === b && 1 === e ? (e *= this.logarithmBase -
                1 / this.logarithmBase, b = 1) : 1 === e / b ? (l = Math.min(e * Math.pow(this.logarithmBase, 0.01), Math.pow(this.logarithmBase, 5)), e *= l, b /= l) : b > e ? (l = Math.min(b / e * Math.pow(this.logarithmBase, 0.01), Math.pow(this.logarithmBase, 5)), 1 <= e ? b = e / l : e = b * l) : (l = Math.min(e / b * Math.pow(this.logarithmBase, 0.01), Math.pow(this.logarithmBase, 0.04)), 1 !== e && (e *= l), 1 !== b && (b /= l)), f = Infinity !== this.dataInfo.minDiff ? this.dataInfo.minDiff : e / b > this.logarithmBase ? e / b * Math.pow(this.logarithmBase, 0.5) : this.logarithmBase, this.includeZero && (null ===
                this.viewportMinimum || isNaN(this.viewportMinimum)) && 1 < b && (b = 1), this.includeZero && (null === this.viewportMaximum || isNaN(this.viewportMaximum)) && 1 > e && (e = 1));
            l = (isNaN(this.viewportMaximum) || null === this.viewportMaximum ? e : this.viewportMaximum) / (isNaN(this.viewportMinimum) || null === this.viewportMinimum ? b : this.viewportMinimum);
            var h = (isNaN(this.viewportMaximum) || null === this.viewportMaximum ? e : this.viewportMaximum) - (isNaN(this.viewportMinimum) || null === this.viewportMinimum ? b : this.viewportMinimum);
            this.intervalType =
                "number";
            l = Math.pow(this.logarithmBase, D.getNiceNumber(Math.abs(Math.log(l) / d), !1));
            this.options && 0 < this.options.interval ? this.interval = this.options.interval : (this.interval = D.getNiceExponent(Math.log(l) / d / (a - 1), !0), c = D.getNiceNumber(h / (a - 1), !0));
            if (null === this.viewportMinimum || isNaN(this.viewportMinimum)) this.viewportMinimum = "axisX" === this.type ? b / Math.sqrt(f) : Math.pow(this.logarithmBase, this.interval * Math.floor(Math.log(b) / d / this.interval));
            if (null === this.viewportMaximum || isNaN(this.viewportMaximum)) this.viewportMaximum =
                "axisX" === this.type ? e * Math.sqrt(f) : Math.pow(this.logarithmBase, this.interval * Math.ceil(Math.log(e) / d / this.interval));
            1 === this.viewportMaximum && 1 === this.viewportMinimum && (1 === this.options.viewportMinimum ? this.viewportMaximum *= this.logarithmBase - 1 / this.logarithmBase : 1 === this.options.viewportMaximum && (this.viewportMinimum /= this.logarithmBase - 1 / this.logarithmBase), this.options && "undefined" === typeof this.options.interval && (this.interval = D.getNiceExponent(Math.ceil(Math.log(l) / d) / (a - 1)), c = D.getNiceNumber((this.viewportMaximum -
                this.viewportMinimum) / (a - 1), !0)));
            if (null === this.minimum || null === this.maximum) "axisX" === this.type ? (b = null !== this.minimum ? this.minimum : this.dataInfo.min, e = null !== this.maximum ? this.maximum : this.dataInfo.max, 1 === e / b && (l = Math.pow(this.logarithmBase, "undefined" === typeof this.options.interval ? 0.4 : this.options.interval), e *= l, b /= l), f = Infinity !== this.dataInfo.minDiff ? this.dataInfo.minDiff : e / b > this.logarithmBase ? e / b * Math.pow(this.logarithmBase, 0.5) : this.logarithmBase) : "axisY" === this.type && (b = null !== this.minimum ?
                this.minimum : this.dataInfo.min, e = null !== this.maximum ? this.maximum : this.dataInfo.max, isFinite(b) || isFinite(e) ? 1 === b && 1 === e ? (e *= this.logarithmBase, b /= this.logarithmBase) : 1 === e / b ? (l = Math.pow(this.logarithmBase, this.interval), e *= l, b /= l) : b > e ? (l = Math.min(0.01 * (b / e), 5), 1 <= e ? b = e / l : e = b * l) : (l = Math.min(e / b * Math.pow(this.logarithmBase, 0.01), Math.pow(this.logarithmBase, 0.04)), 1 !== e && (e *= l), 1 !== b && (b /= l)) : (e = "undefined" === typeof this.options.interval ? 0 : this.options.interval, b = 1), f = Infinity !== this.dataInfo.minDiff ?
                this.dataInfo.minDiff : e / b > this.logarithmBase ? e / b * Math.pow(this.logarithmBase, 0.5) : this.logarithmBase, this.includeZero && (null === this.minimum || isNaN(this.minimum)) && 1 < b && (b = 1), this.includeZero && (null === this.maximum || isNaN(this.maximum)) && 1 > e && (e = 1)), this.intervalType = "number", null === this.minimum && (this.minimum = "axisX" === this.type ? b / Math.sqrt(f) : Math.pow(this.logarithmBase, this.interval * Math.floor(Math.log(b) / d / this.interval)), s(null === this.sessionVariables.viewportMinimum || isNaN(this.sessionVariables.viewportMinimum) ?
                "undefined" === typeof this.sessionVariables.newViewportMinimum ? Infinity : this.sessionVariables.newViewportMinimum : this.sessionVariables.viewportMinimum) || (this.minimum = Math.min(this.minimum, null === this.sessionVariables.viewportMinimum || isNaN(this.sessionVariables.viewportMinimum) ? "undefined" === typeof this.sessionVariables.newViewportMinimum ? Infinity : this.sessionVariables.newViewportMinimum : this.sessionVariables.viewportMinimum))), null === this.maximum && (this.maximum = "axisX" === this.type ? e * Math.sqrt(f) :
                Math.pow(this.logarithmBase, this.interval * Math.ceil(Math.log(e) / d / this.interval)), s(null === this.sessionVariables.viewportMaximum || isNaN(this.sessionVariables.viewportMaximum) ? "undefined" === typeof this.sessionVariables.newViewportMaximum ? 0 : this.sessionVariables.newViewportMaximum : this.sessionVariables.viewportMaximum) || (this.maximum = Math.max(this.maximum, null === this.sessionVariables.viewportMaximum || isNaN(this.sessionVariables.viewportMaximum) ? "undefined" === typeof this.sessionVariables.newViewportMaximum ?
                    0 : this.sessionVariables.newViewportMaximum : this.sessionVariables.viewportMaximum))), 1 === this.maximum && 1 === this.minimum && (1 === this.options.minimum ? this.maximum *= this.logarithmBase - 1 / this.logarithmBase : 1 === this.options.maximum && (this.minimum /= this.logarithmBase - 1 / this.logarithmBase));
            this.viewportMinimum = Math.max(this.viewportMinimum, this.minimum);
            this.viewportMaximum = Math.min(this.viewportMaximum, this.maximum);
            this.viewportMinimum > this.viewportMaximum && (!this.options.viewportMinimum && !this.options.minimum ||
                this.options.viewportMaximum || this.options.maximum ? this.options.viewportMinimum || this.options.minimum || !this.options.viewportMaximum && !this.options.maximum || (this.viewportMinimum = this.minimum = (this.options.viewportMaximum || this.options.maximum) / Math.pow(this.logarithmBase, 2 * Math.ceil(this.interval))) : this.viewportMaximum = this.maximum = this.options.viewportMinimum || this.options.minimum);
            b = Math.pow(this.logarithmBase, Math.floor(Math.log(this.viewportMinimum) / (d * this.interval) + 0.2) * this.interval);
            this.range =
                this.viewportMaximum / this.viewportMinimum;
            this.noTicks = a;
            if (!this.options.interval && this.range < Math.pow(this.logarithmBase, 8 > this.viewportMaximum || 3 > a ? 2 : 3)) {
                for (d = Math.floor(this.viewportMinimum / c + 0.5) * c; d < this.viewportMinimum;) d += c;
                this.equidistantInterval = !1;
                this.intervalStartPosition = d;
                this.interval = c
            } else this.options.interval || (c = Math.ceil(this.interval), this.range > this.interval && (this.interval = c, b = Math.pow(this.logarithmBase, Math.floor(Math.log(this.viewportMinimum) / (d * this.interval) + 0.2) * this.interval))),
                this.equidistantInterval = !0, this.intervalStartPosition = b;
            if (!this.valueFormatString && (this.valueFormatString = "#,##0.##", 1 > this.viewportMinimum)) {
                d = Math.floor(Math.abs(Math.log(this.viewportMinimum) / Math.LN10)) + 2;
                if (isNaN(d) || !isFinite(d)) d = 2;
                if (2 < d)
                    for (l = 0; l < d - 2; l++) this.valueFormatString += "#"
            }
        };
        D.generateValueFormatString = function(a, d) {
            var c = "#,##0.",
                b = d;
            1 > a && (b += Math.floor(Math.abs(Math.log(a) / Math.LN10)), isNaN(b) || !isFinite(b)) && (b = d);
            for (var e = 0; e < b; e++) c += "#";
            return c
        };
        D.getNiceExponent = function(a,
            d) {
            var c = Math.floor(Math.log(a) / Math.LN10),
                b = a / Math.pow(10, c),
                b = 0 > c ? 1 >= b ? 1 : 5 >= b ? 5 : 10 : Math.max(Math.floor(b), 1);
            return -20 > c ? Number(b * Math.pow(10, c)) : Number((b * Math.pow(10, c)).toFixed(20))
        };
        D.getNiceNumber = function(a, d) {
            var c = Math.floor(Math.log(a) / Math.LN10),
                b = a / Math.pow(10, c),
                b = d ? 1.5 > b ? 1 : 3 > b ? 2 : 7 > b ? 5 : 10 : 1 >= b ? 1 : 2 >= b ? 2 : 5 >= b ? 5 : 10;
            return -20 > c ? Number(b * Math.pow(10, c)) : Number((b * Math.pow(10, c)).toFixed(20))
        };
        D.prototype.getLabelStartPoint = function() {
            var a = R[this.intervalType + "Duration"] * this.interval,
                a =
                new Date(Math.floor(this.viewportMinimum / a) * a);
            if ("millisecond" !== this.intervalType)
                if ("second" === this.intervalType) 0 < a.getMilliseconds() && (a.setSeconds(a.getSeconds() + 1), a.setMilliseconds(0));
                else if ("minute" === this.intervalType) { if (0 < a.getSeconds() || 0 < a.getMilliseconds()) a.setMinutes(a.getMinutes() + 1), a.setSeconds(0), a.setMilliseconds(0) } else if ("hour" === this.intervalType) { if (0 < a.getMinutes() || 0 < a.getSeconds() || 0 < a.getMilliseconds()) a.setHours(a.getHours() + 1), a.setMinutes(0), a.setSeconds(0), a.setMilliseconds(0) } else if ("day" ===
                this.intervalType) { if (0 < a.getHours() || 0 < a.getMinutes() || 0 < a.getSeconds() || 0 < a.getMilliseconds()) a.setDate(a.getDate() + 1), a.setHours(0), a.setMinutes(0), a.setSeconds(0), a.setMilliseconds(0) } else if ("week" === this.intervalType) { if (0 < a.getDay() || 0 < a.getHours() || 0 < a.getMinutes() || 0 < a.getSeconds() || 0 < a.getMilliseconds()) a.setDate(a.getDate() + (7 - a.getDay())), a.setHours(0), a.setMinutes(0), a.setSeconds(0), a.setMilliseconds(0) } else if ("month" === this.intervalType) {
                if (1 < a.getDate() || 0 < a.getHours() || 0 < a.getMinutes() ||
                    0 < a.getSeconds() || 0 < a.getMilliseconds()) a.setMonth(a.getMonth() + 1), a.setDate(1), a.setHours(0), a.setMinutes(0), a.setSeconds(0), a.setMilliseconds(0)
            } else "year" === this.intervalType && (0 < a.getMonth() || 1 < a.getDate() || 0 < a.getHours() || 0 < a.getMinutes() || 0 < a.getSeconds() || 0 < a.getMilliseconds()) && (a.setFullYear(a.getFullYear() + 1), a.setMonth(0), a.setDate(1), a.setHours(0), a.setMinutes(0), a.setSeconds(0), a.setMilliseconds(0));
            return a
        };
        oa(Z, U);
        oa(T, U);
        T.prototype.createUserOptions = function(a) {
            if ("undefined" !==
                typeof a || this.options._isPlaceholder) {
                var d = 0;
                this.parent.options._isPlaceholder && this.parent.createUserOptions();
                this.options._isPlaceholder || (Ca(this.parent[this.optionsName]), d = this.parent.options[this.optionsName].indexOf(this.options));
                this.options = "undefined" === typeof a ? {} : a;
                this.parent.options[this.optionsName][d] = this.options
            }
        };
        T.prototype.render = function(a) {
            if (0 !== this.spacing || 0 !== this.options.lineThickness && ("undefined" !== typeof this.options.lineThickness || 0 !== this.parent.lineThickness)) {
                var d =
                    this.ctx,
                    c = this.ctx.globalAlpha;
                this.ctx = a || this.ctx;
                this.ctx.save();
                this.ctx.beginPath();
                this.ctx.rect(this.chart.plotArea.x1, this.chart.plotArea.y1, this.chart.plotArea.width, this.chart.plotArea.height);
                this.ctx.clip();
                var b = this.scaleBreaks.parent.getPixelCoordinatesOnAxis(this.startValue),
                    e = this.scaleBreaks.parent.getPixelCoordinatesOnAxis(this.endValue);
                this.ctx.strokeStyle = this.lineColor;
                this.ctx.fillStyle = this.color;
                this.ctx.beginPath();
                this.ctx.globalAlpha = 1;
                P(this.id);
                var f, l, h, m, k, n;
                a = Math.max(this.spacing,
                    3);
                var p = Math.max(0, this.lineThickness);
                this.ctx.lineWidth = p;
                this.ctx.setLineDash && this.ctx.setLineDash(N(this.lineDashType, p));
                if ("bottom" === this.scaleBreaks.parent._position || "top" === this.scaleBreaks.parent._position)
                    if (b = 1 === p % 2 ? (b.x << 0) + 0.5 : b.x << 0, l = 1 === p % 2 ? (e.x << 0) + 0.5 : e.x << 0, "top" === this.scaleBreaks.parent._position ? (e = this.chart.plotArea.y1, h = this.chart.plotArea.y2 + p / 2 + 0.5 << 0) : (e = this.chart.plotArea.y2, h = this.chart.plotArea.y1 - p / 2 + 0.5 << 0, a *= -1), this.bounds = { x1: b - p / 2, y1: e, x2: l + p / 2, y2: h }, this.ctx.moveTo(b,
                            e), "straight" === this.type || "top" === this.scaleBreaks.parent._position && 0 >= a || "bottom" === this.scaleBreaks.parent._position && 0 <= a) this.ctx.lineTo(b, h), this.ctx.lineTo(l, h), this.ctx.lineTo(l, e);
                    else if ("wavy" === this.type) {
                    m = b;
                    k = e;
                    f = 0.5;
                    n = (h - k) / a / 3;
                    for (var q = 0; q < n; q++) this.ctx.bezierCurveTo(m + f * a, k + a, m + f * a, k + 2 * a, m, k + 3 * a), k += 3 * a, f *= -1;
                    this.ctx.bezierCurveTo(m + f * a, k + a, m + f * a, k + 2 * a, m, k + 3 * a);
                    m = l;
                    f *= -1;
                    this.ctx.lineTo(m, k);
                    for (q = 0; q < n; q++) this.ctx.bezierCurveTo(m + f * a, k - a, m + f * a, k - 2 * a, m, k - 3 * a), k -= 3 * a, f *= -1
                } else {
                    if ("zigzag" ===
                        this.type) {
                        f = -1;
                        k = e + a;
                        m = b + a;
                        n = (h - k) / a / 2;
                        for (q = 0; q < n; q++) this.ctx.lineTo(m, k), m += 2 * f * a, k += 2 * a, f *= -1;
                        this.ctx.lineTo(m, k);
                        m += l - b;
                        for (q = 0; q < n + 1; q++) this.ctx.lineTo(m, k), m += 2 * f * a, k -= 2 * a, f *= -1;
                        this.ctx.lineTo(m + f * a, k + a)
                    }
                } else if ("left" === this.scaleBreaks.parent._position || "right" === this.scaleBreaks.parent._position)
                    if (e = 1 === p % 2 ? (e.y << 0) + 0.5 : e.y << 0, h = 1 === p % 2 ? (b.y << 0) + 0.5 : b.y << 0, "left" === this.scaleBreaks.parent._position ? (b = this.chart.plotArea.x1, l = this.chart.plotArea.x2 + p / 2 + 0.5 << 0) : (b = this.chart.plotArea.x2,
                            l = this.chart.plotArea.x1 - p / 2 + 0.5 << 0, a *= -1), this.bounds = { x1: b, y1: e - p / 2, x2: l, y2: h + p / 2 }, this.ctx.moveTo(b, e), "straight" === this.type || "left" === this.scaleBreaks.parent._position && 0 >= a || "right" === this.scaleBreaks.parent._position && 0 <= a) this.ctx.lineTo(l, e), this.ctx.lineTo(l, h), this.ctx.lineTo(b, h);
                    else if ("wavy" === this.type) {
                    m = b;
                    k = e;
                    f = 0.5;
                    n = (l - m) / a / 3;
                    for (q = 0; q < n; q++) this.ctx.bezierCurveTo(m + a, k + f * a, m + 2 * a, k + f * a, m + 3 * a, k), m += 3 * a, f *= -1;
                    this.ctx.bezierCurveTo(m + a, k + f * a, m + 2 * a, k + f * a, m + 3 * a, k);
                    k = h;
                    f *= -1;
                    this.ctx.lineTo(m,
                        k);
                    for (q = 0; q < n; q++) this.ctx.bezierCurveTo(m - a, k + f * a, m - 2 * a, k + f * a, m - 3 * a, k), m -= 3 * a, f *= -1
                } else if ("zigzag" === this.type) {
                    f = 1;
                    k = e - a;
                    m = b + a;
                    n = (l - m) / a / 2;
                    for (q = 0; q < n; q++) this.ctx.lineTo(m, k), k += 2 * f * a, m += 2 * a, f *= -1;
                    this.ctx.lineTo(m, k);
                    k += h - e;
                    for (q = 0; q < n + 1; q++) this.ctx.lineTo(m, k), k += 2 * f * a, m -= 2 * a, f *= -1;
                    this.ctx.lineTo(m + a, k + f * a)
                }
                0 < p && this.ctx.stroke();
                this.ctx.closePath();
                this.ctx.globalAlpha = this.fillOpacity;
                this.ctx.globalCompositeOperation = "destination-over";
                this.ctx.fill();
                this.ctx.restore();
                this.ctx.globalAlpha =
                    c;
                this.ctx = d
            }
        };
        oa(M, U);
        M.prototype.createUserOptions = function(a) {
            if ("undefined" !== typeof a || this.options._isPlaceholder) {
                var d = 0;
                this.parent.options._isPlaceholder && this.parent.createUserOptions();
                this.options._isPlaceholder || (Ca(this.parent.stripLines), d = this.parent.options.stripLines.indexOf(this.options));
                this.options = "undefined" === typeof a ? {} : a;
                this.parent.options.stripLines[d] = this.options
            }
        };
        M.prototype.render = function() {
            this.ctx.save();
            var a = this.parent.getPixelCoordinatesOnAxis(this.value),
                d = Math.abs("pixel" === this._thicknessType ? this.thickness : Math.abs(this.parent.convertValueToPixel(this.endValue) - this.parent.convertValueToPixel(this.startValue)));
            if (0 < d) {
                var c = null === this.opacity ? 1 : this.opacity;
                this.ctx.strokeStyle = this.color;
                this.ctx.beginPath();
                var b = this.ctx.globalAlpha;
                this.ctx.globalAlpha = c;
                P(this.id);
                var e, f, l, h;
                this.ctx.lineWidth = d;
                this.ctx.setLineDash && this.ctx.setLineDash(N(this.lineDashType, d));
                if ("bottom" === this.parent._position || "top" === this.parent._position) e = f = 1 ===
                    this.ctx.lineWidth % 2 ? (a.x << 0) + 0.5 : a.x << 0, l = this.chart.plotArea.y1, h = this.chart.plotArea.y2, this.bounds = { x1: e - d / 2, y1: l, x2: f + d / 2, y2: h };
                else if ("left" === this.parent._position || "right" === this.parent._position) l = h = 1 === this.ctx.lineWidth % 2 ? (a.y << 0) + 0.5 : a.y << 0, e = this.chart.plotArea.x1, f = this.chart.plotArea.x2, this.bounds = { x1: e, y1: l - d / 2, x2: f, y2: h + d / 2 };
                this.ctx.moveTo(e, l);
                this.ctx.lineTo(f, h);
                this.ctx.stroke();
                this.ctx.globalAlpha = b
            }
            this.ctx.restore()
        };
        oa($, U);
        $.prototype.showAt = function(a) {
            if (!this.enabled) return !1;
            var d = this.chart,
                c = !1;
            d.resetOverlayedCanvas();
            d.clearedOverlayedCanvas = this.parent.type;
            if ("xySwapped" === d.plotInfo.axisPlacement)
                if ("bottom" === this.parent._position)
                    for (var b = 0; b < d.axisY.length; b++) this.parent === d.axisY[b] && (d.axisY[b]._crosshairValue = a >= d.axisY[b].viewportMinimum && a <= d.axisY[b].viewportMaximum ? a : null);
                else if ("top" === this.parent._position)
                for (b = 0; b < d.axisY2.length; b++) this.parent === d.axisY2[b] && (d.axisY2[b]._crosshairValue = a >= d.axisY2[b].viewportMinimum && a <= d.axisY2[b].viewportMaximum ?
                    a : null);
            else if ("left" === this.parent._position)
                for (b = 0; b < d.axisX.length; b++) this.parent === d.axisX[b] && (d.axisX[b]._crosshairValue = a >= d.axisX[b].viewportMinimum && a <= d.axisX[b].viewportMaximum ? a : null);
            else {
                if ("right" === this.parent._position)
                    for (b = 0; b < d.axisX2.length; b++) this.parent === d.axisX2[b] && (d.axisX2[b]._crosshairValue = a >= d.axisX2[b].viewportMinimum && a <= d.axisX2[b].viewportMaximum ? a : null)
            } else if ("bottom" === this.parent._position)
                for (b = 0; b < d.axisX.length; b++) this.parent === d.axisX[b] && (d.axisX[b]._crosshairValue =
                    a >= d.axisX[b].viewportMinimum && a <= d.axisX[b].viewportMaximum ? a : null);
            else if ("top" === this.parent._position)
                for (b = 0; b < d.axisX2.length; b++) this.parent === d.axisX2[b] && (d.axisX2[b]._crosshairValue = a >= d.axisX2[b].viewportMinimum && a <= d.axisX2[b].viewportMaximum ? a : null);
            else if ("left" === this.parent._position)
                for (b = 0; b < d.axisY.length; b++) this.parent === d.axisY[b] && (d.axisY[b]._crosshairValue = a >= d.axisY[b].viewportMinimum && a <= d.axisY[b].viewportMaximum ? a : null);
            else if ("right" === this.parent._position)
                for (b =
                    0; b < d.axisY2.length; b++) this.parent === d.axisY2[b] && (d.axisY2[b]._crosshairValue = a >= d.axisY2[b].viewportMinimum && a <= d.axisY2[b].viewportMaximum ? a : null);
            for (b = 0; b < d.axisX.length; b++) a = d.axisX[b]._crosshairValue, d.axisX[b].crosshair && (d.axisX[b].crosshair.enabled && !s(a) && a >= d.axisX[b].viewportMinimum && a <= d.axisX[b].viewportMaximum) && (d.axisX[b].showCrosshair(a), d.axisX[b].crosshair._updatedValue = a, this === d.axisX[b].crosshair && (c = !0));
            for (b = 0; b < d.axisX2.length; b++) a = d.axisX2[b]._crosshairValue, d.axisX2[b].crosshair &&
                (d.axisX2[b].crosshair.enabled && !s(a) && a >= d.axisX2[b].viewportMinimum && a <= d.axisX2[b].viewportMaximum) && (d.axisX2[b].showCrosshair(a), d.axisX2[b].crosshair._updatedValue = a, this === d.axisX2[b].crosshair && (c = !0));
            for (b = 0; b < d.axisY.length; b++) a = d.axisY[b]._crosshairValue, d.axisY[b].crosshair && (d.axisY[b].crosshair.enabled && !s(a) && a >= d.axisY[b].viewportMinimum && a <= d.axisY[b].viewportMaximum) && (d.axisY[b].showCrosshair(a), d.axisY[b].crosshair._updatedValue = a, this === d.axisY[b].crosshair && (c = !0));
            for (b = 0; b <
                d.axisY2.length; b++) a = d.axisY2[b]._crosshairValue, d.axisY2[b].crosshair && (d.axisY2[b].crosshair.enabled && !s(a) && d._crosshairY2Value >= d.axisY2[b].viewportMinimum && d._crosshairY2Value <= d.axisY2[b].viewportMaximum) && (d.axisY2[b].showCrosshair(a), d.axisY2[b].crosshair._updatedValue = a, this === d.axisY2[b].crosshair && (c = !0));
            this.chart.toolTip && this.chart.toolTip._entries && this.chart.toolTip.highlightObjects(this.chart.toolTip._entries);
            return c
        };
        $.prototype.hide = function() {
            this.chart.resetOverlayedCanvas();
            this.chart.renderCrosshairs(this.parent);
            this._hidden = !0
        };
        $.prototype.render = function(a, d, c) {
            var b, e, f, l, h = null,
                m = null,
                k = null,
                n = "";
            if (!this.valueFormatString)
                if ("dateTime" === this.parent.valueType) this.valueFormatString = this.parent.valueFormatString;
                else {
                    var p = 0,
                        p = "xySwapped" === this.chart.plotInfo.axisPlacement ? 50 < this.parent.range ? 0 : 500 < this.chart.width && 25 > this.parent.range ? 2 : Math.floor(Math.abs(Math.log(this.parent.range) / Math.LN10)) + (5 > this.parent.range ? 2 : 10 > this.parent.range ? 1 : 0) : 50 < this.parent.range ?
                        0 : Math.floor(Math.abs(Math.log(this.parent.range) / Math.LN10)) + (5 > this.parent.range ? 2 : 10 > this.parent.range ? 1 : 0);
                    this.valueFormatString = D.generateValueFormatString(this.parent.range, p)
                }
            var k = null === this.opacity ? 1 : this.opacity,
                p = Math.abs("pixel" === this._thicknessType ? this.thickness : this.parent.conversionParameters.pixelPerUnit * this.thickness),
                q = this.chart.overlaidCanvasCtx,
                g = q.globalAlpha;
            q.globalAlpha = k;
            q.beginPath();
            q.strokeStyle = this.color;
            q.lineWidth = p;
            q.save();
            this.labelFontSize = Math.abs(s(this.options.labelFontSize) ?
                this.parent.labelFontSize : this.labelFontSize);
            this.labelMaxWidth = s(this.options.labelMaxWidth) ? 0.3 * this.chart.width : this.labelMaxWidth;
            this.labelMaxHeight = s(this.options.labelWrap) || this.labelWrap ? 0.3 * this.chart.height : 2 * this.labelFontSize;
            0 < p && q.setLineDash && q.setLineDash(N(this.lineDashType, p));
            k = new ia(q, {
                x: 0,
                y: 0,
                padding: { top: 2, right: 3, bottom: 2, left: 4 },
                backgroundColor: this.labelBackgroundColor,
                borderColor: this.labelBorderColor,
                borderThickness: this.labelBorderThickness,
                cornerRadius: this.labelCornerRadius,
                maxWidth: this.labelMaxWidth,
                maxHeight: this.labelMaxHeight,
                angle: this.labelAngle,
                text: n,
                horizontalAlign: "left",
                fontSize: this.labelFontSize,
                fontFamily: this.labelFontFamily,
                fontWeight: this.labelFontWeight,
                fontColor: this.labelFontColor,
                fontStyle: this.labelFontStyle,
                textBaseline: "middle"
            });
            if (this.snapToDataPoint) {
                var r = 0,
                    n = [];
                if ("xySwapped" === this.chart.plotInfo.axisPlacement) {
                    var u = null;
                    if ("bottom" === this.parent._position || "top" === this.parent._position) r = this.parent.dataSeries[0].axisX.convertPixelToValue({ y: d });
                    else if ("left" === this.parent._position || "right" === this.parent._position) r = this.parent.convertPixelToValue({ y: d });
                    for (var w = 0; w < this.parent.dataSeries.length; w++)(u = this.parent.dataSeries[w].getDataPointAtX(r, !0)) && 0 <= u.index && (u.dataSeries = this.parent.dataSeries[w], null !== u.dataPoint.y && u.dataSeries.visible && n.push(u));
                    u = null;
                    if (0 === n.length) return;
                    n.sort(function(a, b) { return a.distance - b.distance });
                    u = Math.abs(a - this.parent.convertValueToPixel(n[0].dataPoint.y));
                    w = 0;
                    if ("rangeBar" === n[0].dataSeries.type ||
                        "error" === n[0].dataSeries.type)
                        for (var u = Math.abs(a - this.parent.convertValueToPixel(n[w].dataPoint.y[0])), t = 0, r = 0; r < n.length; r++)
                            if (n[r].dataPoint.y && n[r].dataPoint.y.length)
                                for (var x = 0; x < n[r].dataPoint.y.length; x++) t = Math.abs(a - this.parent.convertValueToPixel(n[r].dataPoint.y[x])), t < u && (u = t, w = r);
                            else t = Math.abs(a - this.parent.convertValueToPixel(n[r].dataPoint.y)), t < u && (u = t, w = r);
                    else if ("stackedBar" === n[0].dataSeries.type)
                        for (var u = Math.abs(a - this.parent.convertValueToPixel(n[0].dataPoint.y)), y = t =
                                0, r = w = 0; r < n.length; r++)
                            if (n[r].dataPoint.y && n[r].dataPoint.y.length)
                                for (x = 0; x < n[r].dataPoint.y.length; x++) t = Math.abs(a - this.parent.convertValueToPixel(n[r].dataPoint.y[x])), t < u && (u = t, w = r);
                            else y += n[r].dataPoint.y, t = Math.abs(a - this.parent.convertValueToPixel(y)), t < u && (u = t, w = r);
                    else if ("stackedBar100" === n[0].dataSeries.type)
                        for (var u = Math.abs(a - this.parent.convertValueToPixel(n[0].dataPoint.y)), B = y = t = 0, r = 0; r < n.length; r++)
                            if (n[r].dataPoint.y && n[r].dataPoint.y.length)
                                for (x = 0; x < n[r].dataPoint.y.length; x++) t =
                                    Math.abs(a - this.parent.convertValueToPixel(n[r].dataPoint.y[x])), t < u && (u = t, w = r);
                            else y += n[r].dataPoint.y, B = n[r].dataPoint.x.getTime ? n[r].dataPoint.x.getTime() : n[r].dataPoint.x, B = 100 * (y / n[r].dataSeries.plotUnit.dataPointYSums[B]), t = Math.abs(a - this.parent.convertValueToPixel(B)), t < u && (u = t, w = r);
                    else
                        for (u = Math.abs(a - this.parent.convertValueToPixel(n[0].dataPoint.y)), r = w = t = 0; r < n.length; r++)
                            if (n[r].dataPoint.y && n[r].dataPoint.y.length)
                                for (x = 0; x < n[r].dataPoint.y.length; x++) t = Math.abs(a - this.parent.convertValueToPixel(n[r].dataPoint.y[x])),
                                    t < u && (u = t, w = r);
                            else t = Math.abs(a - this.parent.convertValueToPixel(n[r].dataPoint.y)), t < u && (u = t, w = r);
                    x = n[w];
                    if ("bottom" === this.parent._position || "top" === this.parent._position) {
                        b = 0;
                        if ("rangeBar" === this.parent.dataSeries[w].type || "error" === this.parent.dataSeries[w].type) {
                            u = Math.abs(a - this.parent.convertValueToPixel(x.dataPoint.y[0]));
                            for (r = t = 0; r < x.dataPoint.y.length; r++) t = Math.abs(a - this.parent.convertValueToPixel(x.dataPoint.y[r])), t < u && (u = t, b = r);
                            h = 1 === q.lineWidth % 2 ? (this.parent.convertValueToPixel(x.dataPoint.y[b]) <<
                                0) + 0.5 : this.parent.convertValueToPixel(x.dataPoint.y[b]) << 0;
                            this.value = x.dataPoint.y[b];
                            k.text = this.labelFormatter ? this.labelFormatter({ chart: this.chart, axis: this.parent.options, crosshair: this.options, value: c ? c : x.dataPoint.y[b] }) : s(this.options.label) ? ea(c ? c : x.dataPoint.y[b], this.valueFormatString, this.chart._cultureInfo) : this.label
                        } else if ("stackedBar" === this.parent.dataSeries[w].type) {
                            u = Math.abs(a - this.parent.convertValueToPixel(n[0].dataPoint.y));
                            y = t = 0;
                            for (r = w; 0 <= r; r--) y += n[r].dataPoint.y, t = Math.abs(a -
                                this.parent.convertValueToPixel(y)), t < u && (u = t, b = r);
                            h = 1 === q.lineWidth % 2 ? (this.parent.convertValueToPixel(y) << 0) + 0.5 : this.parent.convertValueToPixel(y) << 0;
                            this.value = y;
                            k.text = this.labelFormatter ? this.labelFormatter({ chart: this.chart, axis: this.parent.options, crosshair: this.options, value: c ? c : x.dataPoint.y }) : s(this.options.label) ? ea(c ? c : x.dataPoint.y, this.valueFormatString, this.chart._cultureInfo) : this.label
                        } else if ("stackedBar100" === this.parent.dataSeries[w].type) {
                            u = Math.abs(a - this.parent.convertValueToPixel(n[0].dataPoint.y));
                            B = y = t = 0;
                            for (r = w; 0 <= r; r--) y += n[r].dataPoint.y, B = n[r].dataPoint.x.getTime ? n[r].dataPoint.x.getTime() : n[r].dataPoint.x, B = 100 * (y / n[r].dataSeries.plotUnit.dataPointYSums[B]), t = Math.abs(a - this.parent.convertValueToPixel(B)), t < u && (u = t, b = r);
                            h = 1 === q.lineWidth % 2 ? (this.parent.convertValueToPixel(B) << 0) + 0.5 : this.parent.convertValueToPixel(B) << 0;
                            this.value = B;
                            k.text = this.labelFormatter ? this.labelFormatter({ chart: this.chart, axis: this.parent.options, crosshair: this.options, value: c ? c : B }) : s(this.options.label) ? ea(c ?
                                c : B, this.valueFormatString, this.chart._cultureInfo) : this.label
                        } else h = 1 === q.lineWidth % 2 ? (this.parent.convertValueToPixel(x.dataPoint.y) << 0) + 0.5 : this.parent.convertValueToPixel(x.dataPoint.y) << 0, this.value = x.dataPoint.y, k.text = this.labelFormatter ? this.labelFormatter({ chart: this.chart, axis: this.parent.options, crosshair: this.options, value: c ? c : x.dataPoint.y }) : s(this.options.label) ? ea(c ? c : x.dataPoint.y, this.valueFormatString, this.chart._cultureInfo) : this.label;
                        b = e = h;
                        f = this.chart.plotArea.y1;
                        l = this.chart.plotArea.y2;
                        this.bounds = { x1: b - p / 2, y1: f, x2: e + p / 2, y2: l };
                        k.x = b - k.measureText().width / 2;
                        k.x + k.width > this.chart.bounds.x2 ? k.x = this.chart.bounds.x2 - k.width : k.x < this.chart.bounds.x1 && (k.x = this.chart.bounds.x1);
                        k.y = this.parent.lineCoordinates.y2 + ("top" === this.parent._position ? -k.height + this.parent.tickLength : k.fontSize / 2) + 2;
                        k.y + k.height > this.chart.bounds.y2 ? k.y = this.chart.bounds.y2 - k.height : k.y < this.chart.bounds.y1 && (k.y = this.chart.bounds.y1)
                    } else if ("left" === this.parent._position || "right" === this.parent._position) {
                        f =
                            l = m = 1 === q.lineWidth % 2 ? (this.parent.convertValueToPixel(x.dataPoint.x) << 0) + 0.5 : this.parent.convertValueToPixel(x.dataPoint.x) << 0;
                        b = this.chart.plotArea.x1;
                        e = this.chart.plotArea.x2;
                        this.bounds = { x1: b, y1: f - p / 2, x2: e, y2: l + p / 2 };
                        B = !1;
                        if (this.parent.labels)
                            for (n = Math.ceil(this.parent.interval), r = 0; r < this.parent.viewportMaximum; r += n)
                                if (this.parent.labels[r]) B = !0;
                                else { B = !1; break }
                        if (B) {
                            if ("axisX" === this.parent.type)
                                for (r = this.parent.convertPixelToValue({ y: d }), u = null, w = 0; w < this.parent.dataSeries.length; w++)(u =
                                    this.parent.dataSeries[w].getDataPointAtX(r, !0)) && 0 <= u.index && (k.text = this.labelFormatter ? this.labelFormatter({ chart: this.chart, axis: this.parent.options, crosshair: this.options, value: x.dataPoint.x }) : s(this.options.label) ? u.dataPoint.label : this.label)
                        } else "dateTime" === this.parent.valueType ? k.text = this.labelFormatter ? this.labelFormatter({ chart: this.chart, axis: this.parent.options, crosshair: this.options, value: c ? c : x.dataPoint.x }) : s(this.options.label) ? Aa(c ? c : x.dataPoint.x, this.valueFormatString, this.chart._cultureInfo) :
                            this.label : "number" === this.parent.valueType && (k.text = this.labelFormatter ? this.labelFormatter({ chart: this.chart, axis: this.parent.options, crosshair: this.options, value: c ? c : x.dataPoint.x }) : s(this.options.label) ? ea(c ? c : x.dataPoint.x, this.valueFormatString, this.chart._cultureInfo) : this.label);
                        this.value = x.dataPoint.x;
                        k.y = l + k.fontSize / 2 - k.measureText().height / 2 + 2;
                        k.y - k.fontSize / 2 < this.chart.bounds.y1 ? k.y = this.chart.bounds.y1 + k.fontSize / 2 + 2 : k.y + k.measureText().height - k.fontSize / 2 > this.chart.bounds.y2 && (k.y =
                            this.chart.bounds.y2 - k.measureText().height + k.fontSize / 2);
                        "left" === this.parent._position ? k.x = this.parent.lineCoordinates.x2 - k.measureText().width : "right" === this.parent._position && (k.x = this.parent.lineCoordinates.x2)
                    }
                } else if ("bottom" === this.parent._position || "top" === this.parent._position) {
                    r = this.parent.convertPixelToValue({ x: a });
                    for (w = 0; w < this.parent.dataSeries.length; w++)(u = this.parent.dataSeries[w].getDataPointAtX(r, !0)) && 0 <= u.index && (u.dataSeries = this.parent.dataSeries[w], null !== u.dataPoint.y &&
                        u.dataSeries.visible && n.push(u));
                    if (0 === n.length) return;
                    n.sort(function(a, b) { return a.distance - b.distance });
                    x = n[0];
                    b = e = h = 1 === q.lineWidth % 2 ? (this.parent.convertValueToPixel(x.dataPoint.x) << 0) + 0.5 : this.parent.convertValueToPixel(x.dataPoint.x) << 0;
                    f = this.chart.plotArea.y1;
                    l = this.chart.plotArea.y2;
                    this.bounds = { x1: b - p / 2, y1: f, x2: e + p / 2, y2: l };
                    B = !1;
                    if (this.parent.labels)
                        for (n = Math.ceil(this.parent.interval), r = 0; r < this.parent.viewportMaximum; r += n)
                            if (this.parent.labels[r]) B = !0;
                            else { B = !1; break }
                    if (B) {
                        if ("axisX" ===
                            this.parent.type)
                            for (r = this.parent.convertPixelToValue({ x: a }), u = null, w = 0; w < this.parent.dataSeries.length; w++)(u = this.parent.dataSeries[w].getDataPointAtX(r, !0)) && 0 <= u.index && (k.text = this.labelFormatter ? this.labelFormatter({ chart: this.chart, axis: this.parent.options, crosshair: this.options, value: x.dataPoint.x }) : s(this.options.label) ? u.dataPoint.label : this.label)
                    } else "dateTime" === this.parent.valueType ? k.text = this.labelFormatter ? this.labelFormatter({
                        chart: this.chart,
                        axis: this.parent.options,
                        crosshair: this.options,
                        value: x.dataPoint.x
                    }) : s(this.options.label) ? Aa(c ? c : x.dataPoint.x, this.valueFormatString, this.chart._cultureInfo) : this.label : "number" === this.parent.valueType && (k.text = this.labelFormatter ? this.labelFormatter({ chart: this.chart, axis: this.parent.options, crosshair: this.options, value: x.dataPoint.x }) : s(this.options.label) ? ea(c ? c : x.dataPoint.x, this.valueFormatString, this.chart._cultureInfo) : this.label);
                    this.value = x.dataPoint.x;
                    k.x = b - k.measureText().width / 2;
                    k.x + k.width > this.chart.bounds.x2 && (k.x = this.chart.bounds.x2 -
                        k.width);
                    k.x < this.chart.bounds.x1 && (k.x = this.chart.bounds.x1);
                    "bottom" === this.parent._position ? k.y = this.parent.lineCoordinates.y2 + k.fontSize / 2 + 2 : "top" === this.parent._position && (k.y = this.parent.lineCoordinates.y1 - k.height + k.fontSize / 2 + 2)
                } else if ("left" === this.parent._position || "right" === this.parent._position) {
                    !s(this.parent.dataSeries) && 0 < this.parent.dataSeries.length && (r = this.parent.dataSeries[0].axisX.convertPixelToValue({ x: a }));
                    for (w = 0; w < this.parent.dataSeries.length; w++)(u = this.parent.dataSeries[w].getDataPointAtX(r, !0)) && 0 <= u.index && (u.dataSeries = this.parent.dataSeries[w], null !== u.dataPoint.y && u.dataSeries.visible && n.push(u));
                    if (0 === n.length) return;
                    n.sort(function(a, b) { return a.distance - b.distance });
                    w = 0;
                    if ("rangeColumn" === n[0].dataSeries.type || "rangeArea" === n[0].dataSeries.type || "error" === n[0].dataSeries.type || "rangeSplineArea" === n[0].dataSeries.type || "candlestick" === n[0].dataSeries.type || "ohlc" === n[0].dataSeries.type || "boxAndWhisker" === n[0].dataSeries.type)
                        for (u = Math.abs(d - this.parent.convertValueToPixel(n[0].dataPoint.y[0])),
                            r = t = 0; r < n.length; r++)
                            if (n[r].dataPoint.y && n[r].dataPoint.y.length)
                                for (x = 0; x < n[r].dataPoint.y.length; x++) t = Math.abs(d - this.parent.convertValueToPixel(n[r].dataPoint.y[x])), t < u && (u = t, w = r);
                            else t = Math.abs(d - this.parent.convertValueToPixel(n[r].dataPoint.y)), t < u && (u = t, w = r);
                    else if ("stackedColumn" === n[0].dataSeries.type || "stackedArea" === n[0].dataSeries.type)
                        for (u = Math.abs(d - this.parent.convertValueToPixel(n[0].dataPoint.y)), r = y = t = 0; r < n.length; r++)
                            if (n[r].dataPoint.y && n[r].dataPoint.y.length)
                                for (x = 0; x <
                                    n[r].dataPoint.y.length; x++) t = Math.abs(d - this.parent.convertValueToPixel(n[r].dataPoint.y[x])), t < u && (u = t, w = r);
                            else y += n[r].dataPoint.y, t = Math.abs(d - this.parent.convertValueToPixel(y)), t < u && (u = t, w = r);
                    else if ("stackedColumn100" === n[0].dataSeries.type || "stackedArea100" === n[0].dataSeries.type)
                        for (u = Math.abs(d - this.parent.convertValueToPixel(n[0].dataPoint.y)), r = B = y = t = 0; r < n.length; r++)
                            if (n[r].dataPoint.y && n[r].dataPoint.y.length)
                                for (x = 0; x < n[r].dataPoint.y.length; x++) t = Math.abs(d - this.parent.convertValueToPixel(n[r].dataPoint.y[x])),
                                    t < u && (u = t, w = r);
                            else y += n[r].dataPoint.y, B = n[r].dataPoint.x.getTime ? n[r].dataPoint.x.getTime() : n[r].dataPoint.x, B = 100 * (y / n[r].dataSeries.plotUnit.dataPointYSums[B]), t = Math.abs(d - this.parent.convertValueToPixel(B)), t < u && (u = t, w = r);
                    else
                        for (u = Math.abs(d - this.parent.convertValueToPixel(n[0].dataPoint.y)), r = t = 0; r < n.length; r++)
                            if (n[r].dataPoint.y && n[r].dataPoint.y.length)
                                for (x = 0; x < n[r].dataPoint.y.length; x++) t = Math.abs(d - this.parent.convertValueToPixel(n[r].dataPoint.y[x])), t < u && (u = t, w = r);
                            else t = Math.abs(d -
                                this.parent.convertValueToPixel(n[r].dataPoint.y)), t < u && (u = t, w = r);
                    x = n[w];
                    b = 0;
                    if ("rangeColumn" === this.parent.dataSeries[w].type || "rangeArea" === this.parent.dataSeries[w].type || "error" === this.parent.dataSeries[w].type || "rangeSplineArea" === this.parent.dataSeries[w].type || "candlestick" === this.parent.dataSeries[w].type || "ohlc" === this.parent.dataSeries[w].type || "boxAndWhisker" === this.parent.dataSeries[w].type) {
                        u = Math.abs(d - this.parent.convertValueToPixel(x.dataPoint.y[0]));
                        for (r = t = 0; r < x.dataPoint.y.length; r++) t =
                            Math.abs(d - this.parent.convertValueToPixel(x.dataPoint.y[r])), t < u && (u = t, b = r);
                        m = 1 === q.lineWidth % 2 ? (this.parent.convertValueToPixel(x.dataPoint.y[b]) << 0) + 0.5 : this.parent.convertValueToPixel(x.dataPoint.y[b]) << 0;
                        k.text = this.labelFormatter ? this.labelFormatter({ chart: this.chart, axis: this.parent.options, crosshair: this.options, value: c ? c : x.dataPoint.y[b] }) : s(this.options.label) ? ea(c ? c : x.dataPoint.y[b], this.valueFormatString, this.chart._cultureInfo) : this.label;
                        this.value = x.dataPoint.y[b]
                    } else if ("stackedColumn" ===
                        this.parent.dataSeries[w].type || "stackedArea" === this.parent.dataSeries[w].type) {
                        u = Math.abs(d - this.parent.convertValueToPixel(n[0].dataPoint.y));
                        y = t = 0;
                        for (r = w; 0 <= r; r--) y += n[r].dataPoint.y, t = Math.abs(d - this.parent.convertValueToPixel(y)), t < u && (u = t, b = r);
                        m = 1 === q.lineWidth % 2 ? (this.parent.convertValueToPixel(y) << 0) + 0.5 : this.parent.convertValueToPixel(y) << 0;
                        k.text = this.labelFormatter ? this.labelFormatter({ chart: this.chart, axis: this.parent.options, crosshair: this.options, value: c ? c : x.dataPoint.y }) : s(this.options.label) ?
                            ea(c ? c : x.dataPoint.y, this.valueFormatString, this.chart._cultureInfo) : this.label;
                        this.value = y
                    } else if ("stackedColumn100" === this.parent.dataSeries[w].type || "stackedArea100" === this.parent.dataSeries[w].type) {
                        u = Math.abs(d - this.parent.convertValueToPixel(n[0].dataPoint.y));
                        y = t = 0;
                        for (r = w; 0 <= r; r--) y += n[r].dataPoint.y, B = n[r].dataPoint.x.getTime ? n[r].dataPoint.x.getTime() : n[r].dataPoint.x, B = 100 * (y / n[r].dataSeries.plotUnit.dataPointYSums[B]), t = Math.abs(d - this.parent.convertValueToPixel(B)), t < u && (u = t, b = r);
                        m =
                            1 === q.lineWidth % 2 ? (this.parent.convertValueToPixel(B) << 0) + 0.5 : this.parent.convertValueToPixel(B) << 0;
                        k.text = this.labelFormatter ? this.labelFormatter({ chart: this.chart, axis: this.parent.options, crosshair: this.options, value: c ? c : B }) : s(this.options.label) ? ea(c ? c : B, this.valueFormatString, this.chart._cultureInfo) : this.label;
                        this.value = B
                    } else "waterfall" === this.parent.dataSeries[w].type ? (m = 1 === q.lineWidth % 2 ? (this.parent.convertValueToPixel(x.dataSeries.dataPointEOs[x.index].cumulativeSum) << 0) + 0.5 : this.parent.convertValueToPixel(x.dataSeries.dataPointEOs[x.index].cumulativeSum) <<
                        0, k.text = this.labelFormatter ? this.labelFormatter({ chart: this.chart, axis: this.parent.options, crosshair: this.options, value: c ? c : x.dataSeries.dataPointEOs[x.index].cumulativeSum }) : s(this.options.label) ? ea(c ? c : x.dataSeries.dataPointEOs[x.index].cumulativeSum, this.valueFormatString, this.chart._cultureInfo) : this.label, this.value = x.dataSeries.dataPointEOs[x.index].cumulativeSum) : (m = 1 === q.lineWidth % 2 ? (s(a) ? d : this.parent.convertValueToPixel(x.dataPoint.y) << 0) + 0.5 : s(a) ? d : this.parent.convertValueToPixel(x.dataPoint.y) <<
                        0, k.text = this.labelFormatter ? this.labelFormatter({ chart: this.chart, axis: this.parent.options, crosshair: this.options, value: x.dataPoint.y }) : s(this.options.label) ? ea(c ? c : x.dataPoint.y, this.valueFormatString, this.chart._cultureInfo) : this.label, this.value = x.dataPoint.y);
                    f = l = m;
                    b = this.chart.plotArea.x1;
                    e = this.chart.plotArea.x2;
                    this.bounds = { x1: b, y1: f - p / 2, x2: e, y2: l + p / 2 };
                    k.y = l + k.fontSize / 2 - k.measureText().height / 2 + 2;
                    k.y - k.fontSize / 2 < this.chart.bounds.y1 ? k.y = this.chart.bounds.y1 + k.fontSize / 2 + 2 : k.y + k.measureText().height -
                        k.fontSize / 2 > this.chart.bounds.y2 && (k.y = this.chart.bounds.y2 - k.measureText().height + k.fontSize / 2);
                    "left" === this.parent._position ? k.x = this.parent.lineCoordinates.x2 - k.measureText().width : "right" === this.parent._position && (k.x = this.parent.lineCoordinates.x2)
                }
                n = null;
                if ("bottom" === this.parent._position || "top" === this.parent._position) "top" === this.parent._position && k.y - k.fontSize / 2 < this.chart.bounds.y1 && (k.y = this.chart.bounds.y1 + k.fontSize / 2), "bottom" === this.parent._position && this.parent.lineCoordinates.y2 -
                    k.fontSize / 2 + k.measureText().height > this.chart.bounds.y2 && (k.y = this.chart.bounds.y2 - k.height + k.fontSize / 2 + 2), b >= this.parent.convertValueToPixel(this.parent.reversed ? this.parent.viewportMaximum : this.parent.viewportMinimum) && e <= this.parent.convertValueToPixel(this.parent.reversed ? this.parent.viewportMinimum : this.parent.viewportMaximum) && (0 < p && (q.moveTo(b, f), q.lineTo(e, l), q.stroke(), this._hidden = !1), q.restore(), !s(k.text) && ("number" === typeof k.text.valueOf() || 0 < k.text.length) && k.render(!0));
                if ("left" ===
                    this.parent._position || "right" === this.parent._position) "left" === this.parent._position && k.x < this.chart.bounds.x1 && (k.x = this.chart.bounds.x1), "right" === this.parent._position && k.x + k.measureText().width > this.chart.bounds.x2 && (k.x = this.chart.bounds.x2 - k.measureText().width), l >= this.parent.convertValueToPixel(this.parent.reversed ? this.parent.viewportMinimum : this.parent.viewportMaximum) && f <= this.parent.convertValueToPixel(this.parent.reversed ? this.parent.viewportMaximum : this.parent.viewportMinimum) && (0 <
                    p && (q.moveTo(b, f), q.lineTo(e, l), q.stroke(), this._hidden = !1), q.restore(), !s(k.text) && ("number" === typeof k.text.valueOf() || 0 < k.text.length) && k.render(!0))
            } else {
                if ("bottom" === this.parent._position || "top" === this.parent._position) b = e = h = 1 === q.lineWidth % 2 ? (a << 0) + 0.5 : a << 0, f = this.chart.plotArea.y1, l = this.chart.plotArea.y2, this.bounds = { x1: b - p / 2, y1: f, x2: e + p / 2, y2: l };
                else if ("left" === this.parent._position || "right" === this.parent._position) f = l = m = 1 === q.lineWidth % 2 ? (d << 0) + 0.5 : d << 0, b = this.chart.plotArea.x1, e = this.chart.plotArea.x2,
                    this.bounds = { x1: b, y1: f - p / 2, x2: e, y2: l + p / 2 };
                if ("xySwapped" === this.chart.plotInfo.axisPlacement)
                    if ("left" === this.parent._position || "right" === this.parent._position) {
                        B = !1;
                        if (this.parent.labels)
                            for (n = Math.ceil(this.parent.interval), r = 0; r < this.parent.viewportMaximum; r += n)
                                if (this.parent.labels[r]) B = !0;
                                else { B = !1; break }
                        if (B) {
                            if ("axisX" === this.parent.type)
                                for (r = this.parent.convertPixelToValue({ y: d }), u = null, w = 0; w < this.parent.dataSeries.length; w++)(u = this.parent.dataSeries[w].getDataPointAtX(r, !0)) && 0 <= u.index &&
                                    (k.text = this.labelFormatter ? this.labelFormatter({ chart: this.chart, axis: this.parent.options, crosshair: this.options, value: c ? c : this.parent.convertPixelToValue(a) }) : s(this.options.label) ? u.dataPoint.label : this.label)
                        } else "dateTime" === this.parent.valueType ? k.text = this.labelFormatter ? this.labelFormatter({ chart: this.chart, axis: this.parent.options, crosshair: this.options, value: c ? c : this.parent.convertPixelToValue(d) }) : s(this.options.label) ? Aa(c ? c : this.parent.convertPixelToValue(d), this.valueFormatString, this.chart._cultureInfo) :
                            this.label : "number" === this.parent.valueType && (k.text = this.labelFormatter ? this.labelFormatter({ chart: this.chart, axis: this.parent.options, crosshair: this.options, value: c ? c : this.parent.convertPixelToValue(d) }) : s(this.options.label) ? ea(c ? c : this.parent.convertPixelToValue(d), this.valueFormatString, this.chart._cultureInfo) : this.label);
                        k.y = d + k.fontSize / 2 - k.measureText().height / 2 + 2;
                        k.y - k.fontSize / 2 < this.chart.bounds.y1 ? k.y = this.chart.bounds.y1 + k.fontSize / 2 + 2 : k.y + k.measureText().height - k.fontSize / 2 > this.chart.bounds.y2 &&
                            (k.y = this.chart.bounds.y2 - k.measureText().height + k.fontSize / 2);
                        "left" === this.parent._position ? k.x = this.parent.lineCoordinates.x1 - k.measureText().width : "right" === this.parent._position && (k.x = this.parent.lineCoordinates.x2)
                    } else {
                        if ("bottom" === this.parent._position || "top" === this.parent._position) k.text = this.labelFormatter ? this.labelFormatter({ chart: this.chart, axis: this.parent.options, crosshair: this.options, value: c ? c : this.parent.convertPixelToValue(a) }) : s(this.options.label) ? ea(c ? c : this.parent.convertPixelToValue(a),
                            this.valueFormatString, this.chart._cultureInfo) : this.label, k.x = b - k.measureText().width / 2, k.x + k.width > this.chart.bounds.x2 && (k.x = this.chart.bounds.x2 - k.width), k.x < this.chart.bounds.x1 && (k.x = this.chart.bounds.x1), "bottom" === this.parent._position ? k.y = this.parent.lineCoordinates.y2 + k.fontSize / 2 + 2 : "top" === this.parent._position && (k.y = this.parent.lineCoordinates.y1 - k.height + k.fontSize / 2 + 2)
                    }
                else if ("bottom" === this.parent._position || "top" === this.parent._position) {
                    B = !1;
                    n = "";
                    if (this.parent.labels)
                        for (n = Math.ceil(this.parent.interval),
                            r = 0; r < this.parent.viewportMaximum; r += n)
                            if (this.parent.labels[r]) B = !0;
                            else { B = !1; break }
                    if (B) {
                        if ("axisX" === this.parent.type)
                            for (r = this.parent.convertPixelToValue({ x: a }), u = null, w = 0; w < this.parent.dataSeries.length; w++)(u = this.parent.dataSeries[w].getDataPointAtX(r, !0)) && 0 <= u.index && (k.text = this.labelFormatter ? this.labelFormatter({ chart: this.chart, axis: this.parent.options, crosshair: this.options, value: c ? c : this.parent.convertPixelToValue(a) }) : s(this.options.label) ? c ? c : u.dataPoint.label : this.label)
                    } else "dateTime" ===
                        this.parent.valueType ? k.text = this.labelFormatter ? this.labelFormatter({ chart: this.chart, axis: this.parent.options, crosshair: this.options, value: c ? c : this.parent.convertPixelToValue(a) }) : s(this.options.label) ? Aa(c ? c : this.parent.convertPixelToValue(a), this.valueFormatString, this.chart._cultureInfo) : this.label : "number" === this.parent.valueType && (k.text = this.labelFormatter ? this.labelFormatter({
                            chart: this.chart,
                            axis: this.parent.options,
                            crosshair: this.options,
                            value: c ? c : 0 < this.parent.dataSeries.length ? this.parent.convertPixelToValue(a) : ""
                        }) : s(this.options.label) ? ea(c ? c : this.parent.convertPixelToValue(a), this.valueFormatString, this.chart._cultureInfo) : this.label);
                    k.x = b - k.measureText().width / 2;
                    k.x + k.width > this.chart.bounds.x2 && (k.x = this.chart.bounds.x2 - k.width);
                    k.x < this.chart.bounds.x1 && (k.x = this.chart.bounds.x1);
                    "bottom" === this.parent._position ? k.y = this.parent.lineCoordinates.y2 + k.fontSize / 2 + 2 : "top" === this.parent._position && (k.y = this.parent.lineCoordinates.y1 - k.height + k.fontSize / 2 + 2)
                } else if ("left" === this.parent._position || "right" ===
                    this.parent._position) k.text = this.labelFormatter ? this.labelFormatter({ chart: this.chart, axis: this.parent.options, crosshair: this.options, value: c ? c : this.parent.convertPixelToValue(d) }) : s(this.options.label) ? ea(c ? c : this.parent.convertPixelToValue(d), this.valueFormatString, this.chart._cultureInfo) : this.label, k.y = d + k.fontSize / 2 - k.measureText().height / 2 + 2, k.y - k.fontSize / 2 < this.chart.bounds.y1 ? k.y = this.chart.bounds.y1 + k.fontSize / 2 + 2 : k.y + k.measureText().height - k.fontSize / 2 > this.chart.bounds.y2 && (k.y = this.chart.bounds.y2 -
                    k.measureText().height + k.fontSize / 2), "left" === this.parent._position ? k.x = this.parent.lineCoordinates.x2 - k.measureText().width : "right" === this.parent._position && (k.x = this.parent.lineCoordinates.x2);
                "left" === this.parent._position && k.x < this.chart.bounds.x1 ? k.x = this.chart.bounds.x1 : "right" === this.parent._position && k.x + k.measureText().width > this.chart.bounds.x2 ? k.x = this.chart.bounds.x2 - k.measureText().width : "top" === this.parent._position && k.y - k.fontSize / 2 < this.chart.bounds.y1 ? k.y = this.chart.bounds.y1 + k.fontSize /
                    2 : "bottom" === this.parent._position && this.parent.lineCoordinates.y2 - k.fontSize / 2 + k.measureText().height > this.chart.bounds.y2 && (k.y = this.chart.bounds.y2 - k.height + k.fontSize / 2 + 2);
                0 < p && (q.moveTo(b, f), q.lineTo(e, l), q.stroke(), this._hidden = !1);
                q.restore();
                !s(k.text) && ("number" === typeof k.text.valueOf() || 0 < k.text.length) && k.render(!0);
                this.value = "bottom" === this.parent._position || "top" === this.parent._position ? this.parent.convertPixelToValue(a) : this.parent.convertPixelToValue(d)
            }
            if ("bottom" === this.parent._position ||
                "top" === this.parent._position) this._updatedValue = this.parent.convertPixelToValue(h);
            if ("left" === this.parent._position || "right" === this.parent._position) this._updatedValue = this.parent.convertPixelToValue(m);
            q.globalAlpha = g
        };
        oa(X, U);
        X.prototype._initialize = function() {
            this.updateOption("updated");
            this.updateOption("hidden");
            if (this.enabled) {
                this.container = document.createElement("div");
                this.container.setAttribute("class", "canvasjs-chart-tooltip");
                this.container.style.position = "absolute";
                this.container.style.height =
                    "auto";
                this.container.style.boxShadow = "1px 1px 2px 2px rgba(0,0,0,0.1)";
                this.container.style.zIndex = "1000";
                this.container.style.pointerEvents = "none";
                this.container.style.display = "none";
                var a;
                a = '<div style=" width: auto;height: auto;min-width: 50px;';
                a += "line-height: auto;";
                a += "margin: 0px 0px 0px 0px;";
                a += "padding: 5px;";
                a += "font-family: Calibri, Arial, Georgia, serif;";
                a += "font-weight: normal;";
                a += "font-style: " + (u ? "italic;" : "normal;");
                a += "font-size: 14px;";
                a += "color: #000000;";
                a += "text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.1);";
                a += "text-align: left;";
                a += "border: 2px solid gray;";
                a += u ? "background: rgba(255,255,255,.9);" : "background: rgb(255,255,255);";
                a += "text-indent: 0px;";
                a += "white-space: nowrap;";
                a += "border-radius: 5px;";
                a += "-moz-user-select:none;";
                a += "-khtml-user-select: none;";
                a += "-webkit-user-select: none;";
                a += "-ms-user-select: none;";
                a += "user-select: none;";
                u || (a += "filter: alpha(opacity = 90);", a += "filter: progid:DXImageTransform.Microsoft.Shadow(Strength=3, Direction=135, Color='#666666');");
                a += '} "> Sample Tooltip</div>';
                this.container.innerHTML = a;
                this.contentDiv = this.container.firstChild;
                this.container.style.borderRadius = this.contentDiv.style.borderRadius;
                this.chart._canvasJSContainer.appendChild(this.container)
            }
        };
        X.prototype.mouseMoveHandler = function(a, d) { this._lastUpdated && 4 > (new Date).getTime() - this._lastUpdated || (this._lastUpdated = (new Date).getTime(), this.chart.resetOverlayedCanvas(), this._updateToolTip(a, d)) };
        X.prototype._updateToolTip = function(a, d, c) {
            c = "undefined" === typeof c ? !0 : c;
            this.container || this._initialize();
            this.enabled || (this.hide(), this.dispatchEvent("hidden", { chart: this.chart, toolTip: this }, this));
            if (!this.chart.disableToolTip) {
                if ("undefined" === typeof a || "undefined" === typeof d) {
                    if (isNaN(this._prevX) || isNaN(this._prevY)) return;
                    a = this._prevX;
                    d = this._prevY
                } else this._prevX = a, this._prevY = d;
                var b = null,
                    e = null,
                    f = [],
                    l = 0;
                if (this.shared && this.enabled && "none" !== this.chart.plotInfo.axisPlacement) {
                    if ("xySwapped" === this.chart.plotInfo.axisPlacement) {
                        var h = [];
                        if (this.chart.axisX)
                            for (var m = 0; m < this.chart.axisX.length; m++) {
                                for (var l =
                                        this.chart.axisX[m].convertPixelToValue({ y: d }), k = null, b = 0; b < this.chart.axisX[m].dataSeries.length; b++)(k = this.chart.axisX[m].dataSeries[b].getDataPointAtX(l, c)) && 0 <= k.index && (k.dataSeries = this.chart.axisX[m].dataSeries[b], null !== k.dataPoint.y && h.push(k));
                                k = null
                            }
                        if (this.chart.axisX2)
                            for (m = 0; m < this.chart.axisX2.length; m++) {
                                l = this.chart.axisX2[m].convertPixelToValue({ y: d });
                                k = null;
                                for (b = 0; b < this.chart.axisX2[m].dataSeries.length; b++)(k = this.chart.axisX2[m].dataSeries[b].getDataPointAtX(l, c)) && 0 <= k.index &&
                                    (k.dataSeries = this.chart.axisX2[m].dataSeries[b], null !== k.dataPoint.y && h.push(k));
                                k = null
                            }
                    } else {
                        h = [];
                        if (this.chart.axisX)
                            for (m = 0; m < this.chart.axisX.length; m++)
                                for (l = this.chart.axisX[m].convertPixelToValue({ x: a }), k = null, b = 0; b < this.chart.axisX[m].dataSeries.length; b++)(k = this.chart.axisX[m].dataSeries[b].getDataPointAtX(l, c)) && 0 <= k.index && (k.dataSeries = this.chart.axisX[m].dataSeries[b], null !== k.dataPoint.y && h.push(k));
                        if (this.chart.axisX2)
                            for (m = 0; m < this.chart.axisX2.length; m++)
                                for (l = this.chart.axisX2[m].convertPixelToValue({ x: a }),
                                    k = null, b = 0; b < this.chart.axisX2[m].dataSeries.length; b++)(k = this.chart.axisX2[m].dataSeries[b].getDataPointAtX(l, c)) && 0 <= k.index && (k.dataSeries = this.chart.axisX2[m].dataSeries[b], null !== k.dataPoint.y && h.push(k))
                    }
                    if (0 === h.length) return;
                    h.sort(function(a, b) { return a.distance - b.distance });
                    c = h[0];
                    for (b = 0; b < h.length; b++) h[b].dataPoint.x.valueOf() === c.dataPoint.x.valueOf() && f.push(h[b]);
                    h = null
                } else {
                    if (k = this.chart.getDataPointAtXY(a, d, c)) this.currentDataPointIndex = k.dataPointIndex, this.currentSeriesIndex =
                        k.dataSeries.index;
                    else if (u)
                        if (k = Ya(a, d, this.chart._eventManager.ghostCtx), 0 < k && "undefined" !== typeof this.chart._eventManager.objectMap[k]) {
                            k = this.chart._eventManager.objectMap[k];
                            if ("legendItem" === k.objectType) return;
                            this.currentSeriesIndex = k.dataSeriesIndex;
                            this.currentDataPointIndex = 0 <= k.dataPointIndex ? k.dataPointIndex : -1
                        } else this.currentDataPointIndex = -1;
                    else this.currentDataPointIndex = -1;
                    if (0 <= this.currentSeriesIndex) {
                        e = this.chart.data[this.currentSeriesIndex];
                        k = {};
                        if (0 <= this.currentDataPointIndex) b =
                            e.dataPoints[this.currentDataPointIndex], k.dataSeries = e, k.dataPoint = b, k.index = this.currentDataPointIndex, k.distance = Math.abs(b.x - l), "waterfall" === e.type && (k.cumulativeSumYStartValue = e.dataPointEOs[this.currentDataPointIndex].cumulativeSumYStartValue, k.cumulativeSum = e.dataPointEOs[this.currentDataPointIndex].cumulativeSum);
                        else {
                            if (!this.enabled || "line" !== e.type && "stepLine" !== e.type && "spline" !== e.type && "area" !== e.type && "stepArea" !== e.type && "splineArea" !== e.type && "stackedArea" !== e.type && "stackedArea100" !==
                                e.type && "rangeArea" !== e.type && "rangeSplineArea" !== e.type && "candlestick" !== e.type && "ohlc" !== e.type && "boxAndWhisker" !== e.type) return;
                            l = e.axisX.convertPixelToValue({ x: a });
                            k = e.getDataPointAtX(l, c);
                            s(k) || (k.dataSeries = e, this.currentDataPointIndex = k.index, b = k.dataPoint)
                        }
                        if (!s(k) && !s(k.dataPoint) && !s(k.dataPoint.y))
                            if (k.dataSeries.axisY)
                                if (0 < k.dataPoint.y.length) {
                                    for (b = c = 0; b < k.dataPoint.y.length; b++) k.dataPoint.y[b] < k.dataSeries.axisY.viewportMinimum ? c-- : k.dataPoint.y[b] > k.dataSeries.axisY.viewportMaximum &&
                                        c++;
                                    c < k.dataPoint.y.length && c > -k.dataPoint.y.length && f.push(k)
                                } else "column" === e.type || "bar" === e.type ? 0 > k.dataPoint.y ? 0 > k.dataSeries.axisY.viewportMinimum && k.dataSeries.axisY.viewportMaximum >= k.dataPoint.y && f.push(k) : k.dataSeries.axisY.viewportMinimum <= k.dataPoint.y && 0 <= k.dataSeries.axisY.viewportMaximum && f.push(k) : "bubble" === e.type ? (c = this.chart._eventManager.objectMap[e.dataPointIds[k.index]].size / 2, k.dataPoint.y >= k.dataSeries.axisY.viewportMinimum - c && k.dataPoint.y <= k.dataSeries.axisY.viewportMaximum +
                                    c && f.push(k)) : "waterfall" === e.type ? (c = 0, k.cumulativeSumYStartValue < k.dataSeries.axisY.viewportMinimum ? c-- : k.cumulativeSumYStartValue > k.dataSeries.axisY.viewportMaximum && c++, k.cumulativeSum < k.dataSeries.axisY.viewportMinimum ? c-- : k.cumulativeSum > k.dataSeries.axisY.viewportMaximum && c++, 2 > c && -2 < c && f.push(k)) : (0 <= k.dataSeries.type.indexOf("100") || "stackedColumn" === e.type || "stackedBar" === e.type || k.dataPoint.y >= k.dataSeries.axisY.viewportMinimum && k.dataPoint.y <= k.dataSeries.axisY.viewportMaximum) && f.push(k);
                        else f.push(k)
                    }
                }
                if (0 < f.length) {
                    this.highlightObjects(f);
                    if (this.enabled) {
                        var n = "",
                            n = this.getToolTipInnerHTML({ entries: f });
                        if (null !== n) {
                            this.contentDiv.innerHTML = n;
                            c = !1;
                            "none" === this.container.style.display && (c = !0, this.container.style.display = "block");
                            try {
                                this.contentDiv.style.background = this.backgroundColor ? this.backgroundColor : u ? "rgba(255,255,255,.9)" : "rgb(255,255,255)", this.borderColor = "waterfall" === f[0].dataSeries.type ? this.contentDiv.style.borderRightColor = this.contentDiv.style.borderLeftColor =
                                    this.contentDiv.style.borderColor = this.options.borderColor ? this.options.borderColor : f[0].dataPoint.color ? f[0].dataPoint.color : 0 < f[0].dataPoint.y ? f[0].dataSeries.risingColor : f[0].dataSeries.fallingColor : "error" === f[0].dataSeries.type ? this.contentDiv.style.borderRightColor = this.contentDiv.style.borderLeftColor = this.contentDiv.style.borderColor = this.options.borderColor ? this.options.borderColor : f[0].dataSeries.color ? f[0].dataSeries.color : f[0].dataSeries._colorSet[e.index % f[0].dataSeries._colorSet.length] :
                                    this.contentDiv.style.borderRightColor = this.contentDiv.style.borderLeftColor = this.contentDiv.style.borderColor = this.options.borderColor ? this.options.borderColor : f[0].dataPoint.color ? f[0].dataPoint.color : f[0].dataSeries.color ? f[0].dataSeries.color : f[0].dataSeries._colorSet[f[0].index % f[0].dataSeries._colorSet.length], this.contentDiv.style.borderWidth = this.borderThickness || 0 === this.borderThickness ? this.borderThickness + "px" : "2px", this.contentDiv.style.borderRadius = this.cornerRadius || 0 === this.cornerRadius ?
                                    this.cornerRadius + "px" : "5px", this.container.style.borderRadius = this.contentDiv.style.borderRadius, this.contentDiv.style.fontSize = this.fontSize || 0 === this.fontSize ? this.fontSize + "px" : "14px", this.contentDiv.style.color = this.fontColor ? this.fontColor : "#000000", this.contentDiv.style.fontFamily = this.fontFamily ? this.fontFamily : "Calibri, Arial, Georgia, serif;", this.contentDiv.style.fontWeight = this.fontWeight ? this.fontWeight : "normal", this.contentDiv.style.fontStyle = this.fontStyle ? this.fontStyle : u ? "italic" :
                                    "normal"
                            } catch (p) {}
                            "pie" === f[0].dataSeries.type || "doughnut" === f[0].dataSeries.type || "funnel" === f[0].dataSeries.type || "pyramid" === f[0].dataSeries.type || "bar" === f[0].dataSeries.type || "rangeBar" === f[0].dataSeries.type || "stackedBar" === f[0].dataSeries.type || "stackedBar100" === f[0].dataSeries.type ? a = a - 10 - this.container.clientWidth : (a = f[0].dataSeries.axisX.convertValueToPixel(f[0].dataPoint.x) - this.container.clientWidth << 0, a -= 10);
                            0 > a && (a += this.container.clientWidth + 20);
                            a + this.container.clientWidth > Math.max(this.chart.container.clientWidth,
                                this.chart.width) && (a = Math.max(0, Math.max(this.chart.container.clientWidth, this.chart.width) - this.container.clientWidth));
                            d = 1 !== f.length || this.shared || "line" !== f[0].dataSeries.type && "stepLine" !== f[0].dataSeries.type && "spline" !== f[0].dataSeries.type && "area" !== f[0].dataSeries.type && "stepArea" !== f[0].dataSeries.type && "splineArea" !== f[0].dataSeries.type ? "bar" === f[0].dataSeries.type || "rangeBar" === f[0].dataSeries.type || "stackedBar" === f[0].dataSeries.type || "stackedBar100" === f[0].dataSeries.type ? f[0].dataSeries.axisX.convertValueToPixel(f[0].dataPoint.x) :
                                d : f[0].dataSeries.axisY.convertValueToPixel(f[0].dataPoint.y);
                            d = -d + 10;
                            0 < d + this.container.clientHeight + 5 && (d -= d + this.container.clientHeight + 5 - 0);
                            this.fixMozTransitionDelay(a, d);
                            !this.animationEnabled || c ? this.disableAnimation() : (this.enableAnimation(), this.container.style.MozTransition = this.mozContainerTransition);
                            this.positionLeft = a;
                            this.positionBottom = d;
                            this.container.style.left = a + "px";
                            this.container.style.bottom = d + "px"
                        } else this.hide(!1), this.dispatchEvent("hidden", { chart: this.chart, toolTip: this },
                            this)
                    }
                    d = [];
                    for (b = 0; b < f.length; b++) d.push({ xValue: f[b].dataPoint.x, dataPoint: f[b].dataPoint, dataSeries: f[b].dataSeries, dataPointIndex: f[b].index, dataSeriesIndex: f[b].dataSeries._index });
                    n = { chart: this.chart, toolTip: this.options, content: n, entries: d };
                    this._entries = f;
                    this.dispatchEvent("updated", n, this)
                } else this.hide()
            }
        };
        X.prototype.highlightObjects = function(a) {
            var d = this.chart.overlaidCanvasCtx;
            if (s(this.chart.clearedOverlayedCanvas) || "toolTip" === this.chart.clearedOverlayedCanvas) this.chart.resetOverlayedCanvas(),
                d.clearRect(0, 0, this.chart.width, this.chart.height), this.chart.clearedOverlayedCanvas = "toolTip";
            d.save();
            var c = this.chart.plotArea,
                b = 0;
            d.beginPath();
            d.rect(c.x1, c.y1, c.x2 - c.x1, c.y2 - c.y1);
            d.clip();
            for (c = 0; c < a.length; c++) {
                var e = a[c];
                if ((e = this.chart._eventManager.objectMap[e.dataSeries.dataPointIds[e.index]]) && e.objectType && "dataPoint" === e.objectType) {
                    var b = this.chart.data[e.dataSeriesIndex],
                        f = b.dataPoints[e.dataPointIndex],
                        h = e.dataPointIndex;
                    !1 === f.highlightEnabled || !0 !== b.highlightEnabled && !0 !== f.highlightEnabled ||
                        ("line" === b.type || "stepLine" === b.type || "spline" === b.type || "scatter" === b.type || "area" === b.type || "stepArea" === b.type || "splineArea" === b.type || "stackedArea" === b.type || "stackedArea100" === b.type || "rangeArea" === b.type || "rangeSplineArea" === b.type ? (f = b.getMarkerProperties(h, e.x1, e.y1, this.chart.overlaidCanvasCtx), f.size = Math.max(1.5 * f.size << 0, 10), f.borderColor = f.borderColor || "#FFFFFF", f.borderThickness = f.borderThickness || Math.ceil(0.1 * f.size), V.drawMarkers([f]), "undefined" !== typeof e.y2 && (f = b.getMarkerProperties(h,
                                e.x1, e.y2, this.chart.overlaidCanvasCtx), f.size = Math.max(1.5 * f.size << 0, 10), f.borderColor = f.borderColor || "#FFFFFF", f.borderThickness = f.borderThickness || Math.ceil(0.1 * f.size), V.drawMarkers([f]))) : "bubble" === b.type ? (f = b.getMarkerProperties(h, e.x1, e.y1, this.chart.overlaidCanvasCtx), f.size = e.size, f.color = "white", f.borderColor = "white", d.globalAlpha = 0.3, V.drawMarkers([f]), d.globalAlpha = 1) : "column" === b.type || "stackedColumn" === b.type || "stackedColumn100" === b.type || "bar" === b.type || "rangeBar" === b.type || "stackedBar" ===
                            b.type || "stackedBar100" === b.type || "rangeColumn" === b.type || "waterfall" === b.type ? ca(d, e.x1, e.y1, e.x2, e.y2, "white", 0, null, !1, !1, !1, !1, 0.3) : "pie" === b.type || "doughnut" === b.type ? W(d, e.center, e.radius, "white", b.type, e.startAngle, e.endAngle, 0.3, e.percentInnerRadius) : "funnel" === b.type || "pyramid" === b.type ? pa(d, e.funnelSection, 0.3, "white") : "candlestick" === b.type ? (d.globalAlpha = 1, d.strokeStyle = e.color, d.lineWidth = 2 * e.borderThickness, b = 0 === d.lineWidth % 2 ? 0 : 0.5, d.beginPath(), d.moveTo(e.x3 - b, Math.min(e.y2, e.y3)),
                                d.lineTo(e.x3 - b, Math.min(e.y1, e.y4)), d.stroke(), d.beginPath(), d.moveTo(e.x3 - b, Math.max(e.y1, e.y4)), d.lineTo(e.x3 - b, Math.max(e.y2, e.y3)), d.stroke(), ca(d, e.x1, Math.min(e.y1, e.y4), e.x2, Math.max(e.y1, e.y4), "transparent", 2 * e.borderThickness, e.color, !1, !1, !1, !1), d.globalAlpha = 1) : "ohlc" === b.type ? (d.globalAlpha = 1, d.strokeStyle = e.color, d.lineWidth = 2 * e.borderThickness, b = 0 === d.lineWidth % 2 ? 0 : 0.5, d.beginPath(), d.moveTo(e.x3 - b, e.y2), d.lineTo(e.x3 - b, e.y3), d.stroke(), d.beginPath(), d.moveTo(e.x3, e.y1), d.lineTo(e.x1,
                                e.y1), d.stroke(), d.beginPath(), d.moveTo(e.x3, e.y4), d.lineTo(e.x2, e.y4), d.stroke(), d.globalAlpha = 1) : "boxAndWhisker" === b.type ? (d.save(), d.globalAlpha = 1, d.strokeStyle = e.stemColor, d.lineWidth = 2 * e.stemThickness, 0 < e.stemThickness && (d.beginPath(), d.moveTo(e.x3, e.y2 + e.borderThickness / 2), d.lineTo(e.x3, e.y1 + e.whiskerThickness / 2), d.stroke(), d.beginPath(), d.moveTo(e.x3, e.y4 - e.whiskerThickness / 2), d.lineTo(e.x3, e.y3 - e.borderThickness / 2), d.stroke()), d.beginPath(), ca(d, e.x1 - e.borderThickness / 2, Math.max(e.y2 + e.borderThickness /
                                2, e.y3 + e.borderThickness / 2), e.x2 + e.borderThickness / 2, Math.min(e.y2 - e.borderThickness / 2, e.y3 - e.borderThickness / 2), "transparent", e.borderThickness, e.color, !1, !1, !1, !1), d.globalAlpha = 1, d.strokeStyle = e.whiskerColor, d.lineWidth = 2 * e.whiskerThickness, 0 < e.whiskerThickness && (d.beginPath(), d.moveTo(Math.floor(e.x3 - e.whiskerLength / 2), e.y4), d.lineTo(Math.ceil(e.x3 + e.whiskerLength / 2), e.y4), d.stroke(), d.beginPath(), d.moveTo(Math.floor(e.x3 - e.whiskerLength / 2), e.y1), d.lineTo(Math.ceil(e.x3 + e.whiskerLength / 2), e.y1),
                                d.stroke()), d.globalAlpha = 1, d.strokeStyle = e.lineColor, d.lineWidth = 2 * e.lineThickness, 0 < e.lineThickness && (d.beginPath(), d.moveTo(e.x1, e.y5), d.lineTo(e.x2, e.y5), d.stroke()), d.restore(), d.globalAlpha = 1) : "error" === b.type && y(d, e.x1, e.y1, e.x2, e.y2, "white", e.whiskerProperties, e.stemProperties, e.isXYSwapped, 0.3))
                }
            }
            d.restore();
            d.globalAlpha = 1;
            d.beginPath()
        };
        X.prototype.getToolTipInnerHTML = function(a) {
            a = a.entries;
            for (var d = null, c = null, b = null, e = 0, f = "", h = !0, m = 0; m < a.length; m++)
                if (a[m].dataSeries.toolTipContent ||
                    a[m].dataPoint.toolTipContent) { h = !1; break }
            if (h && (this.content && "function" === typeof this.content || this.contentFormatter)) a = { chart: this.chart, toolTip: this.options, entries: a }, d = this.contentFormatter ? this.contentFormatter(a) : this.content(a);
            else if (this.shared && "none" !== this.chart.plotInfo.axisPlacement) {
                for (var s = null, k = "", m = 0; m < a.length; m++) c = a[m].dataSeries, b = a[m].dataPoint, e = a[m].index, f = "", 0 === m && (h && !this.content) && (this.chart.axisX && 0 < this.chart.axisX.length ? k += "undefined" !== typeof this.chart.axisX[0].labels[b.x] ?
                    this.chart.axisX[0].labels[b.x] : "{x}" : this.chart.axisX2 && 0 < this.chart.axisX2.length && (k += "undefined" !== typeof this.chart.axisX2[0].labels[b.x] ? this.chart.axisX2[0].labels[b.x] : "{x}"), k += "</br>", k = this.chart.replaceKeywordsWithValue(k, b, c, e)), null === b.toolTipContent || "undefined" === typeof b.toolTipContent && null === c.options.toolTipContent || ("line" === c.type || "stepLine" === c.type || "spline" === c.type || "area" === c.type || "stepArea" === c.type || "splineArea" === c.type || "column" === c.type || "bar" === c.type || "scatter" ===
                    c.type || "stackedColumn" === c.type || "stackedColumn100" === c.type || "stackedBar" === c.type || "stackedBar100" === c.type || "stackedArea" === c.type || "stackedArea100" === c.type || "waterfall" === c.type ? (this.chart.axisX && 1 < this.chart.axisX.length && (f += s != c.axisXIndex ? c.axisX.title ? c.axisX.title + "<br/>" : "X:{axisXIndex}<br/>" : ""), f += b.toolTipContent ? b.toolTipContent : c.toolTipContent ? c.toolTipContent : this.content && "function" !== typeof this.content ? this.content : "<span style='\"" + (this.options.fontColor ? "" : "'color:{color};'") +
                        "\"'>{name}:</span>&nbsp;&nbsp;{y}", s = c.axisXIndex) : "bubble" === c.type ? (this.chart.axisX && 1 < this.chart.axisX.length && (f += s != c.axisXIndex ? c.axisX.title ? c.axisX.title + "<br/>" : "X:{axisXIndex}<br/>" : ""), f += b.toolTipContent ? b.toolTipContent : c.toolTipContent ? c.toolTipContent : this.content && "function" !== typeof this.content ? this.content : "<span style='\"" + (this.options.fontColor ? "" : "'color:{color};'") + "\"'>{name}:</span>&nbsp;&nbsp;{y}, &nbsp;&nbsp;{z}") : "rangeColumn" === c.type || "rangeBar" === c.type || "rangeArea" ===
                    c.type || "rangeSplineArea" === c.type || "error" === c.type ? (this.chart.axisX && 1 < this.chart.axisX.length && (f += s != c.axisXIndex ? c.axisX.title ? c.axisX.title + "<br/>" : "X:{axisXIndex}<br/>" : ""), f += b.toolTipContent ? b.toolTipContent : c.toolTipContent ? c.toolTipContent : this.content && "function" !== typeof this.content ? this.content : "<span style='\"" + (this.options.fontColor ? "" : "'color:{color};'") + "\"'>{name}:</span>&nbsp;&nbsp;{y[0]},&nbsp;{y[1]}") : "candlestick" === c.type || "ohlc" === c.type ? (this.chart.axisX && 1 < this.chart.axisX.length &&
                        (f += s != c.axisXIndex ? c.axisX.title ? c.axisX.title + "<br/>" : "X:{axisXIndex}<br/>" : ""), f += b.toolTipContent ? b.toolTipContent : c.toolTipContent ? c.toolTipContent : this.content && "function" !== typeof this.content ? this.content : "<span style='\"" + (this.options.fontColor ? "" : "'color:{color};'") + "\"'>{name}:</span><br/>Open: &nbsp;&nbsp;{y[0]}<br/>High: &nbsp;&nbsp;&nbsp;{y[1]}<br/>Low:&nbsp;&nbsp;&nbsp;{y[2]}<br/>Close: &nbsp;&nbsp;{y[3]}") : "boxAndWhisker" === c.type && (this.chart.axisX && 1 < this.chart.axisX.length && (f +=
                        s != c.axisXIndex ? c.axisX.title ? c.axisX.title + "<br/>" : "X:{axisXIndex}<br/>" : ""), f += b.toolTipContent ? b.toolTipContent : c.toolTipContent ? c.toolTipContent : this.content && "function" !== typeof this.content ? this.content : "<span style='\"" + (this.options.fontColor ? "" : "'color:{color};'") + "\"'>{name}:</span><br/>Minimum: &nbsp;&nbsp;{y[0]}<br/>Q1: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{y[1]}<br/>Q2: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{y[4]}<br/>Q3: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{y[2]}<br/>Maximum: &nbsp;{y[3]}"),
                    null === d && (d = ""), !0 === this.reversed ? (d = this.chart.replaceKeywordsWithValue(f, b, c, e) + d, m < a.length - 1 && (d = "</br>" + d)) : (d += this.chart.replaceKeywordsWithValue(f, b, c, e), m < a.length - 1 && (d += "</br>")));
                null !== d && (d = k + d)
            } else {
                c = a[0].dataSeries;
                b = a[0].dataPoint;
                e = a[0].index;
                if (null === b.toolTipContent || "undefined" === typeof b.toolTipContent && null === c.options.toolTipContent) return null;
                "line" === c.type || "stepLine" === c.type || "spline" === c.type || "area" === c.type || "stepArea" === c.type || "splineArea" === c.type || "column" ===
                    c.type || "bar" === c.type || "scatter" === c.type || "stackedColumn" === c.type || "stackedColumn100" === c.type || "stackedBar" === c.type || "stackedBar100" === c.type || "stackedArea" === c.type || "stackedArea100" === c.type || "waterfall" === c.type ? f = b.toolTipContent ? b.toolTipContent : c.toolTipContent ? c.toolTipContent : this.content && "function" !== typeof this.content ? this.content : "<span style='\"" + (this.options.fontColor ? "" : "'color:{color};'") + "\"'>" + (b.label ? "{label}" : "{x}") + ":</span>&nbsp;&nbsp;{y}" : "bubble" === c.type ? f = b.toolTipContent ?
                    b.toolTipContent : c.toolTipContent ? c.toolTipContent : this.content && "function" !== typeof this.content ? this.content : "<span style='\"" + (this.options.fontColor ? "" : "'color:{color};'") + "\"'>" + (b.label ? "{label}" : "{x}") + ":</span>&nbsp;&nbsp;{y}, &nbsp;&nbsp;{z}" : "pie" === c.type || "doughnut" === c.type || "funnel" === c.type || "pyramid" === c.type ? f = b.toolTipContent ? b.toolTipContent : c.toolTipContent ? c.toolTipContent : this.content && "function" !== typeof this.content ? this.content : "<span style='\"" + (this.options.fontColor ? "" :
                        "'color:{color};'") + "\"'>" + (b.name ? "{name}:</span>&nbsp;&nbsp;" : b.label ? "{label}:</span>&nbsp;&nbsp;" : "</span>") + "{y}" : "rangeColumn" === c.type || "rangeBar" === c.type || "rangeArea" === c.type || "rangeSplineArea" === c.type || "error" === c.type ? f = b.toolTipContent ? b.toolTipContent : c.toolTipContent ? c.toolTipContent : this.content && "function" !== typeof this.content ? this.content : "<span style='\"" + (this.options.fontColor ? "" : "'color:{color};'") + "\"'>" + (b.label ? "{label}" : "{x}") + " :</span>&nbsp;&nbsp;{y[0]}, &nbsp;{y[1]}" :
                    "candlestick" === c.type || "ohlc" === c.type ? f = b.toolTipContent ? b.toolTipContent : c.toolTipContent ? c.toolTipContent : this.content && "function" !== typeof this.content ? this.content : "<span style='\"" + (this.options.fontColor ? "" : "'color:{color};'") + "\"'>" + (b.label ? "{label}" : "{x}") + "</span><br/>Open: &nbsp;&nbsp;{y[0]}<br/>High: &nbsp;&nbsp;&nbsp;{y[1]}<br/>Low: &nbsp;&nbsp;&nbsp;&nbsp;{y[2]}<br/>Close: &nbsp;&nbsp;{y[3]}" : "boxAndWhisker" === c.type && (f = b.toolTipContent ? b.toolTipContent : c.toolTipContent ? c.toolTipContent :
                        this.content && "function" !== typeof this.content ? this.content : "<span style='\"" + (this.options.fontColor ? "" : "'color:{color};'") + "\"'>" + (b.label ? "{label}" : "{x}") + "</span><br/>Minimum: &nbsp;&nbsp;{y[0]}<br/>Q1: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{y[1]}<br/>Q2: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{y[4]}<br/>Q3: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{y[2]}<br/>Maximum: &nbsp;{y[3]}");
                null === d && (d = "");
                d += this.chart.replaceKeywordsWithValue(f, b, c, e)
            }
            return d
        };
        X.prototype.enableAnimation = function() {
            if (!this.container.style.WebkitTransition) {
                var a = this.getContainerTransition(this.containerTransitionDuration);
                this.container.style.WebkitTransition = a;
                this.container.style.MsTransition = a;
                this.container.style.transition = a;
                this.container.style.MozTransition = this.mozContainerTransition
            }
        };
        X.prototype.disableAnimation = function() {
            this.container.style.WebkitTransition && (this.container.style.WebkitTransition =
                "", this.container.style.MozTransition = "", this.container.style.MsTransition = "", this.container.style.transition = "")
        };
        X.prototype.hide = function(a) { this.container && (this.container.style.display = "none", this.currentSeriesIndex = -1, this._prevY = this._prevX = NaN, ("undefined" === typeof a || a) && this.chart.resetOverlayedCanvas()) };
        X.prototype.show = function(a, d, c) { this._updateToolTip(a, d, "undefined" === typeof c ? !1 : c) };
        X.prototype.showAtIndex = function(a, d) {};
        X.prototype.showAtX = function(a, d) {
            if (!this.enabled) return !1;
            this.chart.clearedOverlayedCanvas = null;
            var c, b, e, f = [];
            e = !1;
            d = !s(d) && 0 <= d && d < this.chart.data.length ? d : 0;
            if (this.shared)
                for (var h = 0; h < this.chart.data.length; h++) c = this.chart.data[h], (b = c.getDataPointAtX(a, !1)) && (b.dataPoint && !s(b.dataPoint.y) && c.visible) && (b.dataSeries = c, f.push(b));
            else c = this.chart.data[d], (b = c.getDataPointAtX(a, !1)) && (b.dataPoint && !s(b.dataPoint.y) && c.visible) && (b.dataSeries = c, f.push(b));
            if (0 < f.length) {
                for (h = 0; h < f.length; h++)
                    if (b = f[h], b.dataPoint.x < b.dataSeries.axisX.viewportMinimum ||
                        b.dataPoint.x > b.dataSeries.axisX.viewportMaximum || b.dataPoint.y < b.dataSeries.axisY.viewportMinimum || b.dataPoint.y > b.dataSeries.axisY.viewportMaximum) e = !0;
                    else { e = !1; break }
                if (e) return this.hide(), !1;
                this.highlightObjects(f);
                this._entries = f;
                b = "";
                b = this.getToolTipInnerHTML({ entries: f });
                if (null !== b) {
                    this.contentDiv.innerHTML = b;
                    b = !1;
                    "none" === this.container.style.display && (b = !0, this.container.style.display = "block");
                    try {
                        this.contentDiv.style.background = this.backgroundColor ? this.backgroundColor : u ? "rgba(255,255,255,.9)" :
                            "rgb(255,255,255)", this.borderColor = "waterfall" === f[0].dataSeries.type ? this.contentDiv.style.borderRightColor = this.contentDiv.style.borderLeftColor = this.contentDiv.style.borderColor = this.options.borderColor ? this.options.borderColor : f[0].dataPoint.color ? f[0].dataPoint.color : 0 < f[0].dataPoint.y ? f[0].dataSeries.risingColor : f[0].dataSeries.fallingColor : "error" === f[0].dataSeries.type ? this.contentDiv.style.borderRightColor = this.contentDiv.style.borderLeftColor = this.contentDiv.style.borderColor = this.options.borderColor ?
                            this.options.borderColor : f[0].dataSeries.color ? f[0].dataSeries.color : f[0].dataSeries._colorSet[c.index % f[0].dataSeries._colorSet.length] : this.contentDiv.style.borderRightColor = this.contentDiv.style.borderLeftColor = this.contentDiv.style.borderColor = this.options.borderColor ? this.options.borderColor : f[0].dataPoint.color ? f[0].dataPoint.color : f[0].dataSeries.color ? f[0].dataSeries.color : f[0].dataSeries._colorSet[f[0].index % f[0].dataSeries._colorSet.length], this.contentDiv.style.borderWidth = this.borderThickness ||
                            0 === this.borderThickness ? this.borderThickness + "px" : "2px", this.contentDiv.style.borderRadius = this.cornerRadius || 0 === this.cornerRadius ? this.cornerRadius + "px" : "5px", this.container.style.borderRadius = this.contentDiv.style.borderRadius, this.contentDiv.style.fontSize = this.fontSize || 0 === this.fontSize ? this.fontSize + "px" : "14px", this.contentDiv.style.color = this.fontColor ? this.fontColor : "#000000", this.contentDiv.style.fontFamily = this.fontFamily ? this.fontFamily : "Calibri, Arial, Georgia, serif;", this.contentDiv.style.fontWeight =
                            this.fontWeight ? this.fontWeight : "normal", this.contentDiv.style.fontStyle = this.fontStyle ? this.fontStyle : u ? "italic" : "normal"
                    } catch (m) {}
                    "pie" === f[0].dataSeries.type || "doughnut" === f[0].dataSeries.type || "funnel" === f[0].dataSeries.type || "pyramid" === f[0].dataSeries.type ? c = mouseX - 10 - this.container.clientWidth : (c = "bar" === f[0].dataSeries.type || "rangeBar" === f[0].dataSeries.type || "stackedBar" === f[0].dataSeries.type || "stackedBar100" === f[0].dataSeries.type ? f[0].dataSeries.axisY.convertValueToPixel(f[0].dataPoint.y) -
                        this.container.clientWidth << 0 : f[0].dataSeries.axisX.convertValueToPixel(f[0].dataPoint.x) - this.container.clientWidth << 0, c -= 10);
                    0 > c && (c += this.container.clientWidth + 20);
                    c + this.container.clientWidth > Math.max(this.chart.container.clientWidth, this.chart.width) && (c = Math.max(0, Math.max(this.chart.container.clientWidth, this.chart.width) - this.container.clientWidth));
                    f = 1 !== f.length || this.shared || "line" !== f[0].dataSeries.type && "stepLine" !== f[0].dataSeries.type && "spline" !== f[0].dataSeries.type && "area" !== f[0].dataSeries.type &&
                        "stepArea" !== f[0].dataSeries.type && "splineArea" !== f[0].dataSeries.type ? "bar" === f[0].dataSeries.type || "rangeBar" === f[0].dataSeries.type || "stackedBar" === f[0].dataSeries.type || "stackedBar100" === f[0].dataSeries.type ? f[0].dataSeries.axisX.convertValueToPixel(f[0].dataPoint.x) : f[0].dataSeries.axisY.convertValueToPixel(f[0].dataPoint.y) : f[0].dataSeries.axisY.convertValueToPixel(f[0].dataPoint.y);
                    f = -f + 10;
                    0 < f + this.container.clientHeight + 5 && (f -= f + this.container.clientHeight + 5 - 0);
                    this.fixMozTransitionDelay(c,
                        f);
                    !this.animationEnabled || b ? this.disableAnimation() : (this.enableAnimation(), this.container.style.MozTransition = this.mozContainerTransition);
                    this.container.style.left = c + "px";
                    this.container.style.bottom = f + "px"
                } else return this.hide(!1), !1
            } else return this.hide(), !1;
            return !0
        };
        X.prototype.fixMozTransitionDelay = function(a, d) {
            if (20 < this.chart._eventManager.lastObjectId) this.mozContainerTransition = this.getContainerTransition(0);
            else {
                var c = parseFloat(this.container.style.left),
                    c = isNaN(c) ? 0 : c,
                    b = parseFloat(this.container.style.bottom),
                    b = isNaN(b) ? 0 : b;
                10 < Math.sqrt(Math.pow(c - a, 2) + Math.pow(b - d, 2)) ? this.mozContainerTransition = this.getContainerTransition(0.1) : this.mozContainerTransition = this.getContainerTransition(0)
            }
        };
        X.prototype.getContainerTransition = function(a) { return "left " + a + "s ease-out 0s, bottom " + a + "s ease-out 0s" };
        da.prototype.reset = function() {
            this.lastObjectId = 0;
            this.objectMap = [];
            this.rectangularRegionEventSubscriptions = [];
            this.previousDataPointEventObject = null;
            this.eventObjects = [];
            u && (this.ghostCtx.clearRect(0, 0, this.chart.width,
                this.chart.height), this.ghostCtx.beginPath())
        };
        da.prototype.getNewObjectTrackingId = function() { return ++this.lastObjectId };
        da.prototype.mouseEventHandler = function(a) {
            if ("mousemove" === a.type || "click" === a.type) {
                var d = [],
                    c = Na(a),
                    b = null;
                if ((b = this.chart.getObjectAtXY(c.x, c.y, !1)) && "undefined" !== typeof this.objectMap[b])
                    if (b = this.objectMap[b], "dataPoint" === b.objectType) {
                        var e = this.chart.data[b.dataSeriesIndex],
                            f = e.dataPoints[b.dataPointIndex],
                            h = b.dataPointIndex;
                        b.eventParameter = {
                            x: c.x,
                            y: c.y,
                            dataPoint: f,
                            dataSeries: e.options,
                            dataPointIndex: h,
                            dataSeriesIndex: e.index,
                            chart: this.chart
                        };
                        b.eventContext = { context: f, userContext: f, mouseover: "mouseover", mousemove: "mousemove", mouseout: "mouseout", click: "click" };
                        d.push(b);
                        b = this.objectMap[e.id];
                        b.eventParameter = { x: c.x, y: c.y, dataPoint: f, dataSeries: e.options, dataPointIndex: h, dataSeriesIndex: e.index, chart: this.chart };
                        b.eventContext = { context: e, userContext: e.options, mouseover: "mouseover", mousemove: "mousemove", mouseout: "mouseout", click: "click" };
                        d.push(this.objectMap[e.id])
                    } else "legendItem" ===
                        b.objectType && (e = this.chart.data[b.dataSeriesIndex], f = null !== b.dataPointIndex ? e.dataPoints[b.dataPointIndex] : null, b.eventParameter = { x: c.x, y: c.y, dataSeries: e.options, dataPoint: f, dataPointIndex: b.dataPointIndex, dataSeriesIndex: b.dataSeriesIndex, chart: this.chart }, b.eventContext = { context: this.chart.legend, userContext: this.chart.legend.options, mouseover: "itemmouseover", mousemove: "itemmousemove", mouseout: "itemmouseout", click: "itemclick" }, d.push(b));
                e = [];
                for (c = 0; c < this.mouseoveredObjectMaps.length; c++) {
                    f = !0;
                    for (b = 0; b < d.length; b++)
                        if (d[b].id === this.mouseoveredObjectMaps[c].id) { f = !1; break }
                    f ? this.fireEvent(this.mouseoveredObjectMaps[c], "mouseout", a) : e.push(this.mouseoveredObjectMaps[c])
                }
                this.mouseoveredObjectMaps = e;
                for (c = 0; c < d.length; c++) {
                    e = !1;
                    for (b = 0; b < this.mouseoveredObjectMaps.length; b++)
                        if (d[c].id === this.mouseoveredObjectMaps[b].id) { e = !0; break }
                    e || (this.fireEvent(d[c], "mouseover", a), this.mouseoveredObjectMaps.push(d[c]));
                    "click" === a.type ? this.fireEvent(d[c], "click", a) : "mousemove" === a.type && this.fireEvent(d[c],
                        "mousemove", a)
                }
            }
        };
        da.prototype.fireEvent = function(a, d, c) {
            if (a && d) {
                var b = a.eventParameter,
                    e = a.eventContext,
                    f = a.eventContext.userContext;
                f && (e && f[e[d]]) && f[e[d]].call(f, b);
                "mouseout" !== d ? f.cursor && f.cursor !== c.target.style.cursor && (c.target.style.cursor = f.cursor) : (c.target.style.cursor = this.chart._defaultCursor, delete a.eventParameter, delete a.eventContext);
                "click" === d && ("dataPoint" === a.objectType && this.chart.pieDoughnutClickHandler) && this.chart.pieDoughnutClickHandler.call(this.chart.data[a.dataSeriesIndex],
                    b);
                "click" === d && ("dataPoint" === a.objectType && this.chart.funnelPyramidClickHandler) && this.chart.funnelPyramidClickHandler.call(this.chart.data[a.dataSeriesIndex], b)
            }
        };
        ha.prototype.animate = function(a, d, c, b, e) {
            var f = this;
            this.chart.isAnimating = !0;
            e = e || K.easing.linear;
            c && this.animations.push({ startTime: (new Date).getTime() + (a ? a : 0), duration: d, animationCallback: c, onComplete: b });
            for (a = []; 0 < this.animations.length;)
                if (d = this.animations.shift(), c = (new Date).getTime(), b = 0, d.startTime <= c && (b = e(Math.min(c - d.startTime,
                        d.duration), 0, 1, d.duration), b = Math.min(b, 1), isNaN(b) || !isFinite(b)) && (b = 1), 1 > b && a.push(d), d.animationCallback(b), 1 <= b && d.onComplete) d.onComplete();
            this.animations = a;
            0 < this.animations.length ? this.animationRequestId = this.chart.requestAnimFrame.call(window, function() { f.animate.call(f) }) : this.chart.isAnimating = !1
        };
        ha.prototype.cancelAllAnimations = function() {
            this.animations = [];
            this.animationRequestId && this.chart.cancelRequestAnimFrame.call(window, this.animationRequestId);
            this.animationRequestId = null;
            this.chart.isAnimating = !1
        };
        var K = {
                yScaleAnimation: function(a, d) {
                    if (0 !== a) {
                        var c = d.dest,
                            b = d.source.canvas,
                            e = d.animationBase;
                        c.drawImage(b, 0, 0, b.width, b.height, 0, e - e * a, c.canvas.width / ka, a * c.canvas.height / ka)
                    }
                },
                xScaleAnimation: function(a, d) {
                    if (0 !== a) {
                        var c = d.dest,
                            b = d.source.canvas,
                            e = d.animationBase;
                        c.drawImage(b, 0, 0, b.width, b.height, e - e * a, 0, a * c.canvas.width / ka, c.canvas.height / ka)
                    }
                },
                xClipAnimation: function(a, d) {
                    if (0 !== a) {
                        var c = d.dest,
                            b = d.source.canvas;
                        c.save();
                        0 < a && c.drawImage(b, 0, 0, b.width * a, b.height, 0, 0, b.width * a / ka, b.height /
                            ka);
                        c.restore()
                    }
                },
                fadeInAnimation: function(a, d) {
                    if (0 !== a) {
                        var c = d.dest,
                            b = d.source.canvas;
                        c.save();
                        c.globalAlpha = a;
                        c.drawImage(b, 0, 0, b.width, b.height, 0, 0, c.canvas.width / ka, c.canvas.height / ka);
                        c.restore()
                    }
                },
                easing: { linear: function(a, d, c, b) { return c * a / b + d }, easeOutQuad: function(a, d, c, b) { return -c * (a /= b) * (a - 2) + d }, easeOutQuart: function(a, d, c, b) { return -c * ((a = a / b - 1) * a * a * a - 1) + d }, easeInQuad: function(a, d, c, b) { return c * (a /= b) * a + d }, easeInQuart: function(a, d, c, b) { return c * (a /= b) * a * a * a + d } }
            },
            V = {
                drawMarker: function(a,
                    d, c, b, e, f, h, m) {
                    if (c) {
                        var s = 1;
                        c.fillStyle = f ? f : "#000000";
                        c.strokeStyle = h ? h : "#000000";
                        c.lineWidth = m ? m : 0;
                        c.setLineDash && c.setLineDash(N("solid", m));
                        "circle" === b ? (c.moveTo(a, d), c.beginPath(), c.arc(a, d, e / 2, 0, 2 * Math.PI, !1), f && c.fill(), m && (h ? c.stroke() : (s = c.globalAlpha, c.globalAlpha = 0.15, c.strokeStyle = "black", c.stroke(), c.globalAlpha = s))) : "square" === b ? (c.beginPath(), c.rect(a - e / 2, d - e / 2, e, e), f && c.fill(), m && (h ? c.stroke() : (s = c.globalAlpha, c.globalAlpha = 0.15, c.strokeStyle = "black", c.stroke(), c.globalAlpha = s))) :
                            "triangle" === b ? (c.beginPath(), c.moveTo(a - e / 2, d + e / 2), c.lineTo(a + e / 2, d + e / 2), c.lineTo(a, d - e / 2), c.closePath(), f && c.fill(), m && (h ? c.stroke() : (s = c.globalAlpha, c.globalAlpha = 0.15, c.strokeStyle = "black", c.stroke(), c.globalAlpha = s)), c.beginPath()) : "cross" === b && (c.strokeStyle = f, c.lineWidth = e / 4, c.beginPath(), c.moveTo(a - e / 2, d - e / 2), c.lineTo(a + e / 2, d + e / 2), c.stroke(), c.moveTo(a + e / 2, d - e / 2), c.lineTo(a - e / 2, d + e / 2), c.stroke())
                    }
                },
                drawMarkers: function(a) {
                    for (var d = 0; d < a.length; d++) {
                        var c = a[d];
                        V.drawMarker(c.x, c.y, c.ctx, c.type,
                            c.size, c.color, c.borderColor, c.borderThickness)
                    }
                }
            };
        return m
    }();
    y.version = "v3.4.2 GA";
    window.CanvasJS && (y && !window.CanvasJS.Chart) && (window.CanvasJS.Chart = y)
})();

document.createElement("canvas").getContext || function() {
    function V() { return this.context_ || (this.context_ = new C(this)) }

    function W(a, b, c) { var g = M.call(arguments, 2); return function() { return a.apply(b, g.concat(M.call(arguments))) } }

    function N(a) { return String(a).replace(/&/g, "&amp;").replace(/"/g, "&quot;") }

    function O(a) {
        a.namespaces.g_vml_ || a.namespaces.add("g_vml_", "urn:schemas-microsoft-com:vml", "#default#VML");
        a.namespaces.g_o_ || a.namespaces.add("g_o_", "urn:schemas-microsoft-com:office:office", "#default#VML");
        a.styleSheets.ex_canvas_ || (a = a.createStyleSheet(), a.owningElement.id = "ex_canvas_", a.cssText = "canvas{display:inline-block;overflow:hidden;text-align:left;width:300px;height:150px}")
    }

    function X(a) {
        var b = a.srcElement;
        switch (a.propertyName) {
            case "width":
                b.getContext().clearRect();
                b.style.width = b.attributes.width.nodeValue + "px";
                b.firstChild.style.width = b.clientWidth + "px";
                break;
            case "height":
                b.getContext().clearRect(), b.style.height = b.attributes.height.nodeValue + "px", b.firstChild.style.height = b.clientHeight +
                    "px"
        }
    }

    function Y(a) {
        a = a.srcElement;
        a.firstChild && (a.firstChild.style.width = a.clientWidth + "px", a.firstChild.style.height = a.clientHeight + "px")
    }

    function D() {
        return [
            [1, 0, 0],
            [0, 1, 0],
            [0, 0, 1]
        ]
    }

    function t(a, b) {
        for (var c = D(), g = 0; 3 > g; g++)
            for (var e = 0; 3 > e; e++) {
                for (var f = 0, d = 0; 3 > d; d++) f += a[g][d] * b[d][e];
                c[g][e] = f
            }
        return c
    }

    function P(a, b) {
        b.fillStyle = a.fillStyle;
        b.lineCap = a.lineCap;
        b.lineJoin = a.lineJoin;
        b.lineWidth = a.lineWidth;
        b.miterLimit = a.miterLimit;
        b.shadowBlur = a.shadowBlur;
        b.shadowColor = a.shadowColor;
        b.shadowOffsetX =
            a.shadowOffsetX;
        b.shadowOffsetY = a.shadowOffsetY;
        b.strokeStyle = a.strokeStyle;
        b.globalAlpha = a.globalAlpha;
        b.font = a.font;
        b.textAlign = a.textAlign;
        b.textBaseline = a.textBaseline;
        b.arcScaleX_ = a.arcScaleX_;
        b.arcScaleY_ = a.arcScaleY_;
        b.lineScale_ = a.lineScale_
    }

    function Q(a) {
        var b = a.indexOf("(", 3),
            c = a.indexOf(")", b + 1),
            b = a.substring(b + 1, c).split(",");
        if (4 != b.length || "a" != a.charAt(3)) b[3] = 1;
        return b
    }

    function E(a, b, c) { return Math.min(c, Math.max(b, a)) }

    function F(a, b, c) {
        0 > c && c++;
        1 < c && c--;
        return 1 > 6 * c ? a + 6 * (b - a) * c :
            1 > 2 * c ? b : 2 > 3 * c ? a + 6 * (b - a) * (2 / 3 - c) : a
    }

    function G(a) {
        if (a in H) return H[a];
        var b, c = 1;
        a = String(a);
        if ("#" == a.charAt(0)) b = a;
        else if (/^rgb/.test(a)) {
            c = Q(a);
            b = "#";
            for (var g, e = 0; 3 > e; e++) g = -1 != c[e].indexOf("%") ? Math.floor(255 * (parseFloat(c[e]) / 100)) : +c[e], b += v[E(g, 0, 255)];
            c = +c[3]
        } else if (/^hsl/.test(a)) {
            e = c = Q(a);
            b = parseFloat(e[0]) / 360 % 360;
            0 > b && b++;
            g = E(parseFloat(e[1]) / 100, 0, 1);
            e = E(parseFloat(e[2]) / 100, 0, 1);
            if (0 == g) g = e = b = e;
            else {
                var f = 0.5 > e ? e * (1 + g) : e + g - e * g,
                    d = 2 * e - f;
                g = F(d, f, b + 1 / 3);
                e = F(d, f, b);
                b = F(d, f, b - 1 / 3)
            }
            b = "#" +
                v[Math.floor(255 * g)] + v[Math.floor(255 * e)] + v[Math.floor(255 * b)];
            c = c[3]
        } else b = Z[a] || a;
        return H[a] = { color: b, alpha: c }
    }

    function C(a) {
        this.m_ = D();
        this.mStack_ = [];
        this.aStack_ = [];
        this.currentPath_ = [];
        this.fillStyle = this.strokeStyle = "#000";
        this.lineWidth = 1;
        this.lineJoin = "miter";
        this.lineCap = "butt";
        this.miterLimit = 1 * q;
        this.globalAlpha = 1;
        this.font = "10px sans-serif";
        this.textAlign = "left";
        this.textBaseline = "alphabetic";
        this.canvas = a;
        var b = "width:" + a.clientWidth + "px;height:" + a.clientHeight + "px;overflow:hidden;position:absolute",
            c = a.ownerDocument.createElement("div");
        c.style.cssText = b;
        a.appendChild(c);
        b = c.cloneNode(!1);
        b.style.backgroundColor = "red";
        b.style.filter = "alpha(opacity=0)";
        a.appendChild(b);
        this.element_ = c;
        this.lineScale_ = this.arcScaleY_ = this.arcScaleX_ = 1
    }

    function R(a, b, c, g) {
        a.currentPath_.push({ type: "bezierCurveTo", cp1x: b.x, cp1y: b.y, cp2x: c.x, cp2y: c.y, x: g.x, y: g.y });
        a.currentX_ = g.x;
        a.currentY_ = g.y
    }

    function S(a, b) {
        var c = G(a.strokeStyle),
            g = c.color,
            c = c.alpha * a.globalAlpha,
            e = a.lineScale_ * a.lineWidth;
        1 > e && (c *= e);
        b.push("<g_vml_:stroke",
            ' opacity="', c, '"', ' joinstyle="', a.lineJoin, '"', ' miterlimit="', a.miterLimit, '"', ' endcap="', $[a.lineCap] || "square", '"', ' weight="', e, 'px"', ' color="', g, '" />')
    }

    function T(a, b, c, g) {
        var e = a.fillStyle,
            f = a.arcScaleX_,
            d = a.arcScaleY_,
            k = g.x - c.x,
            n = g.y - c.y;
        if (e instanceof w) {
            var h = 0,
                l = g = 0,
                u = 0,
                m = 1;
            if ("gradient" == e.type_) {
                h = e.x1_ / f;
                c = e.y1_ / d;
                var p = s(a, e.x0_ / f, e.y0_ / d),
                    h = s(a, h, c),
                    h = 180 * Math.atan2(h.x - p.x, h.y - p.y) / Math.PI;
                0 > h && (h += 360);
                1E-6 > h && (h = 0)
            } else p = s(a, e.x0_, e.y0_), g = (p.x - c.x) / k, l = (p.y - c.y) / n, k /= f * q,
                n /= d * q, m = x.max(k, n), u = 2 * e.r0_ / m, m = 2 * e.r1_ / m - u;
            f = e.colors_;
            f.sort(function(a, b) { return a.offset - b.offset });
            d = f.length;
            p = f[0].color;
            c = f[d - 1].color;
            k = f[0].alpha * a.globalAlpha;
            a = f[d - 1].alpha * a.globalAlpha;
            for (var n = [], r = 0; r < d; r++) {
                var t = f[r];
                n.push(t.offset * m + u + " " + t.color)
            }
            b.push('<g_vml_:fill type="', e.type_, '"', ' method="none" focus="100%"', ' color="', p, '"', ' color2="', c, '"', ' colors="', n.join(","), '"', ' opacity="', a, '"', ' g_o_:opacity2="', k, '"', ' angle="', h, '"', ' focusposition="', g, ",", l, '" />')
        } else e instanceof
        I ? k && n && b.push("<g_vml_:fill", ' position="', -c.x / k * f * f, ",", -c.y / n * d * d, '"', ' type="tile"', ' src="', e.src_, '" />') : (e = G(a.fillStyle), b.push('<g_vml_:fill color="', e.color, '" opacity="', e.alpha * a.globalAlpha, '" />'))
    }

    function s(a, b, c) { a = a.m_; return { x: q * (b * a[0][0] + c * a[1][0] + a[2][0]) - r, y: q * (b * a[0][1] + c * a[1][1] + a[2][1]) - r } }

    function z(a, b, c) {
        isFinite(b[0][0]) && (isFinite(b[0][1]) && isFinite(b[1][0]) && isFinite(b[1][1]) && isFinite(b[2][0]) && isFinite(b[2][1])) && (a.m_ = b, c && (a.lineScale_ = aa(ba(b[0][0] * b[1][1] - b[0][1] *
            b[1][0]))))
    }

    function w(a) {
        this.type_ = a;
        this.r1_ = this.y1_ = this.x1_ = this.r0_ = this.y0_ = this.x0_ = 0;
        this.colors_ = []
    }

    function I(a, b) {
        if (!a || 1 != a.nodeType || "IMG" != a.tagName) throw new A("TYPE_MISMATCH_ERR");
        if ("complete" != a.readyState) throw new A("INVALID_STATE_ERR");
        switch (b) {
            case "repeat":
            case null:
            case "":
                this.repetition_ = "repeat";
                break;
            case "repeat-x":
            case "repeat-y":
            case "no-repeat":
                this.repetition_ = b;
                break;
            default:
                throw new A("SYNTAX_ERR");
        }
        this.src_ = a.src;
        this.width_ = a.width;
        this.height_ = a.height
    }

    function A(a) {
        this.code = this[a];
        this.message = a + ": DOM Exception " + this.code
    }
    var x = Math,
        k = x.round,
        J = x.sin,
        K = x.cos,
        ba = x.abs,
        aa = x.sqrt,
        q = 10,
        r = q / 2;
    navigator.userAgent.match(/MSIE ([\d.]+)?/);
    var M = Array.prototype.slice;
    O(document);
    var U = {
        init: function(a) {
            a = a || document;
            a.createElement("canvas");
            a.attachEvent("onreadystatechange", W(this.init_, this, a))
        },
        init_: function(a) { a = a.getElementsByTagName("canvas"); for (var b = 0; b < a.length; b++) this.initElement(a[b]) },
        initElement: function(a) {
            if (!a.getContext) {
                a.getContext =
                    V;
                O(a.ownerDocument);
                a.innerHTML = "";
                a.attachEvent("onpropertychange", X);
                a.attachEvent("onresize", Y);
                var b = a.attributes;
                b.width && b.width.specified ? a.style.width = b.width.nodeValue + "px" : a.width = a.clientWidth;
                b.height && b.height.specified ? a.style.height = b.height.nodeValue + "px" : a.height = a.clientHeight
            }
            return a
        }
    };
    U.init();
    for (var v = [], d = 0; 16 > d; d++)
        for (var B = 0; 16 > B; B++) v[16 * d + B] = d.toString(16) + B.toString(16);
    var Z = {
            aliceblue: "#F0F8FF",
            antiquewhite: "#FAEBD7",
            aquamarine: "#7FFFD4",
            azure: "#F0FFFF",
            beige: "#F5F5DC",
            bisque: "#FFE4C4",
            black: "#000000",
            blanchedalmond: "#FFEBCD",
            blueviolet: "#8A2BE2",
            brown: "#A52A2A",
            burlywood: "#DEB887",
            cadetblue: "#5F9EA0",
            chartreuse: "#7FFF00",
            chocolate: "#D2691E",
            coral: "#FF7F50",
            cornflowerblue: "#6495ED",
            cornsilk: "#FFF8DC",
            crimson: "#DC143C",
            cyan: "#00FFFF",
            darkblue: "#00008B",
            darkcyan: "#008B8B",
            darkgoldenrod: "#B8860B",
            darkgray: "#A9A9A9",
            darkgreen: "#006400",
            darkgrey: "#A9A9A9",
            darkkhaki: "#BDB76B",
            darkmagenta: "#8B008B",
            darkolivegreen: "#556B2F",
            darkorange: "#FF8C00",
            darkorchid: "#9932CC",
            darkred: "#8B0000",
            darksalmon: "#E9967A",
            darkseagreen: "#8FBC8F",
            darkslateblue: "#483D8B",
            darkslategray: "#2F4F4F",
            darkslategrey: "#2F4F4F",
            darkturquoise: "#00CED1",
            darkviolet: "#9400D3",
            deeppink: "#FF1493",
            deepskyblue: "#00BFFF",
            dimgray: "#696969",
            dimgrey: "#696969",
            dodgerblue: "#1E90FF",
            firebrick: "#B22222",
            floralwhite: "#FFFAF0",
            forestgreen: "#228B22",
            gainsboro: "#DCDCDC",
            ghostwhite: "#F8F8FF",
            gold: "#FFD700",
            goldenrod: "#DAA520",
            grey: "#808080",
            greenyellow: "#ADFF2F",
            honeydew: "#F0FFF0",
            hotpink: "#FF69B4",
            indianred: "#CD5C5C",
            indigo: "#4B0082",
            ivory: "#FFFFF0",
            khaki: "#F0E68C",
            lavender: "#E6E6FA",
            lavenderblush: "#FFF0F5",
            lawngreen: "#7CFC00",
            lemonchiffon: "#FFFACD",
            lightblue: "#ADD8E6",
            lightcoral: "#F08080",
            lightcyan: "#E0FFFF",
            lightgoldenrodyellow: "#FAFAD2",
            lightgreen: "#90EE90",
            lightgrey: "#D3D3D3",
            lightpink: "#FFB6C1",
            lightsalmon: "#FFA07A",
            lightseagreen: "#20B2AA",
            lightskyblue: "#87CEFA",
            lightslategray: "#778899",
            lightslategrey: "#778899",
            lightsteelblue: "#B0C4DE",
            lightyellow: "#FFFFE0",
            limegreen: "#32CD32",
            linen: "#FAF0E6",
            magenta: "#FF00FF",
            mediumaquamarine: "#66CDAA",
            mediumblue: "#0000CD",
            mediumorchid: "#BA55D3",
            mediumpurple: "#9370DB",
            mediumseagreen: "#3CB371",
            mediumslateblue: "#7B68EE",
            mediumspringgreen: "#00FA9A",
            mediumturquoise: "#48D1CC",
            mediumvioletred: "#C71585",
            midnightblue: "#191970",
            mintcream: "#F5FFFA",
            mistyrose: "#FFE4E1",
            moccasin: "#FFE4B5",
            navajowhite: "#FFDEAD",
            oldlace: "#FDF5E6",
            olivedrab: "#6B8E23",
            orange: "#FFA500",
            orangered: "#FF4500",
            orchid: "#DA70D6",
            palegoldenrod: "#EEE8AA",
            palegreen: "#98FB98",
            paleturquoise: "#AFEEEE",
            palevioletred: "#DB7093",
            papayawhip: "#FFEFD5",
            peachpuff: "#FFDAB9",
            peru: "#CD853F",
            pink: "#FFC0CB",
            plum: "#DDA0DD",
            powderblue: "#B0E0E6",
            rosybrown: "#BC8F8F",
            royalblue: "#4169E1",
            saddlebrown: "#8B4513",
            salmon: "#FA8072",
            sandybrown: "#F4A460",
            seagreen: "#2E8B57",
            seashell: "#FFF5EE",
            sienna: "#A0522D",
            skyblue: "#87CEEB",
            slateblue: "#6A5ACD",
            slategray: "#708090",
            slategrey: "#708090",
            snow: "#FFFAFA",
            springgreen: "#00FF7F",
            steelblue: "#4682B4",
            tan: "#D2B48C",
            thistle: "#D8BFD8",
            tomato: "#FF6347",
            turquoise: "#40E0D0",
            violet: "#EE82EE",
            wheat: "#F5DEB3",
            whitesmoke: "#F5F5F5",
            yellowgreen: "#9ACD32"
        },
        H = {},
        L = {},
        $ = { butt: "flat", round: "round" },
        d = C.prototype;
    d.clearRect = function() {
        this.textMeasureEl_ && (this.textMeasureEl_.removeNode(!0), this.textMeasureEl_ = null);
        this.element_.innerHTML = ""
    };
    d.beginPath = function() { this.currentPath_ = [] };
    d.moveTo = function(a, b) {
        var c = s(this, a, b);
        this.currentPath_.push({ type: "moveTo", x: c.x, y: c.y });
        this.currentX_ = c.x;
        this.currentY_ = c.y
    };
    d.lineTo = function(a, b) {
        var c = s(this, a, b);
        this.currentPath_.push({ type: "lineTo", x: c.x, y: c.y });
        this.currentX_ = c.x;
        this.currentY_ = c.y
    };
    d.bezierCurveTo =
        function(a, b, c, g, e, f) {
            e = s(this, e, f);
            a = s(this, a, b);
            c = s(this, c, g);
            R(this, a, c, e)
        };
    d.quadraticCurveTo = function(a, b, c, g) {
        a = s(this, a, b);
        c = s(this, c, g);
        g = { x: this.currentX_ + 2 / 3 * (a.x - this.currentX_), y: this.currentY_ + 2 / 3 * (a.y - this.currentY_) };
        R(this, g, { x: g.x + (c.x - this.currentX_) / 3, y: g.y + (c.y - this.currentY_) / 3 }, c)
    };
    d.arc = function(a, b, c, g, e, f) {
        c *= q;
        var d = f ? "at" : "wa",
            k = a + K(g) * c - r,
            n = b + J(g) * c - r;
        g = a + K(e) * c - r;
        e = b + J(e) * c - r;
        k != g || f || (k += 0.125);
        a = s(this, a, b);
        k = s(this, k, n);
        g = s(this, g, e);
        this.currentPath_.push({
            type: d,
            x: a.x,
            y: a.y,
            radius: c,
            xStart: k.x,
            yStart: k.y,
            xEnd: g.x,
            yEnd: g.y
        })
    };
    d.rect = function(a, b, c, g) {
        this.moveTo(a, b);
        this.lineTo(a + c, b);
        this.lineTo(a + c, b + g);
        this.lineTo(a, b + g);
        this.closePath()
    };
    d.strokeRect = function(a, b, c, g) {
        var e = this.currentPath_;
        this.beginPath();
        this.moveTo(a, b);
        this.lineTo(a + c, b);
        this.lineTo(a + c, b + g);
        this.lineTo(a, b + g);
        this.closePath();
        this.stroke();
        this.currentPath_ = e
    };
    d.fillRect = function(a, b, c, g) {
        var e = this.currentPath_;
        this.beginPath();
        this.moveTo(a, b);
        this.lineTo(a + c, b);
        this.lineTo(a +
            c, b + g);
        this.lineTo(a, b + g);
        this.closePath();
        this.fill();
        this.currentPath_ = e
    };
    d.createLinearGradient = function(a, b, c, g) {
        var e = new w("gradient");
        e.x0_ = a;
        e.y0_ = b;
        e.x1_ = c;
        e.y1_ = g;
        return e
    };
    d.createRadialGradient = function(a, b, c, g, e, f) {
        var d = new w("gradientradial");
        d.x0_ = a;
        d.y0_ = b;
        d.r0_ = c;
        d.x1_ = g;
        d.y1_ = e;
        d.r1_ = f;
        return d
    };
    d.drawImage = function(a, b) {
        var c, g, e, d, r, y, n, h;
        e = a.runtimeStyle.width;
        d = a.runtimeStyle.height;
        a.runtimeStyle.width = "auto";
        a.runtimeStyle.height = "auto";
        var l = a.width,
            u = a.height;
        a.runtimeStyle.width =
            e;
        a.runtimeStyle.height = d;
        if (3 == arguments.length) c = arguments[1], g = arguments[2], r = y = 0, n = e = l, h = d = u;
        else if (5 == arguments.length) c = arguments[1], g = arguments[2], e = arguments[3], d = arguments[4], r = y = 0, n = l, h = u;
        else if (9 == arguments.length) r = arguments[1], y = arguments[2], n = arguments[3], h = arguments[4], c = arguments[5], g = arguments[6], e = arguments[7], d = arguments[8];
        else throw Error("Invalid number of arguments");
        var m = s(this, c, g),
            p = [];
        p.push(" <g_vml_:group", ' coordsize="', 10 * q, ",", 10 * q, '"', ' coordorigin="0,0"', ' style="width:',
            10, "px;height:", 10, "px;position:absolute;");
        if (1 != this.m_[0][0] || this.m_[0][1] || 1 != this.m_[1][1] || this.m_[1][0]) {
            var t = [];
            t.push("M11=", this.m_[0][0], ",", "M12=", this.m_[1][0], ",", "M21=", this.m_[0][1], ",", "M22=", this.m_[1][1], ",", "Dx=", k(m.x / q), ",", "Dy=", k(m.y / q), "");
            var v = s(this, c + e, g),
                w = s(this, c, g + d);
            c = s(this, c + e, g + d);
            m.x = x.max(m.x, v.x, w.x, c.x);
            m.y = x.max(m.y, v.y, w.y, c.y);
            p.push("padding:0 ", k(m.x / q), "px ", k(m.y / q), "px 0;filter:progid:DXImageTransform.Microsoft.Matrix(", t.join(""), ", sizingmethod='clip');")
        } else p.push("top:",
            k(m.y / q), "px;left:", k(m.x / q), "px;");
        p.push(' ">', '<g_vml_:image src="', a.src, '"', ' style="width:', q * e, "px;", " height:", q * d, 'px"', ' cropleft="', r / l, '"', ' croptop="', y / u, '"', ' cropright="', (l - r - n) / l, '"', ' cropbottom="', (u - y - h) / u, '"', " />", "</g_vml_:group>");
        this.element_.insertAdjacentHTML("BeforeEnd", p.join(""))
    };
    d.stroke = function(a) {
        var b = [];
        b.push("<g_vml_:shape", ' filled="', !!a, '"', ' style="position:absolute;width:', 10, "px;height:", 10, 'px;"', ' coordorigin="0,0"', ' coordsize="', 10 * q, ",", 10 * q, '"',
            ' stroked="', !a, '"', ' path="');
        for (var c = { x: null, y: null }, d = { x: null, y: null }, e = 0; e < this.currentPath_.length; e++) {
            var f = this.currentPath_[e];
            switch (f.type) {
                case "moveTo":
                    b.push(" m ", k(f.x), ",", k(f.y));
                    break;
                case "lineTo":
                    b.push(" l ", k(f.x), ",", k(f.y));
                    break;
                case "close":
                    b.push(" x ");
                    f = null;
                    break;
                case "bezierCurveTo":
                    b.push(" c ", k(f.cp1x), ",", k(f.cp1y), ",", k(f.cp2x), ",", k(f.cp2y), ",", k(f.x), ",", k(f.y));
                    break;
                case "at":
                case "wa":
                    b.push(" ", f.type, " ", k(f.x - this.arcScaleX_ * f.radius), ",", k(f.y - this.arcScaleY_ *
                        f.radius), " ", k(f.x + this.arcScaleX_ * f.radius), ",", k(f.y + this.arcScaleY_ * f.radius), " ", k(f.xStart), ",", k(f.yStart), " ", k(f.xEnd), ",", k(f.yEnd))
            }
            if (f) { if (null == c.x || f.x < c.x) c.x = f.x; if (null == d.x || f.x > d.x) d.x = f.x; if (null == c.y || f.y < c.y) c.y = f.y; if (null == d.y || f.y > d.y) d.y = f.y }
        }
        b.push(' ">');
        a ? T(this, b, c, d) : S(this, b);
        b.push("</g_vml_:shape>");
        this.element_.insertAdjacentHTML("beforeEnd", b.join(""))
    };
    d.fill = function() { this.stroke(!0) };
    d.closePath = function() { this.currentPath_.push({ type: "close" }) };
    d.save = function() {
        var a = {};
        P(this, a);
        this.aStack_.push(a);
        this.mStack_.push(this.m_);
        this.m_ = t(D(), this.m_)
    };
    d.restore = function() { this.aStack_.length && (P(this.aStack_.pop(), this), this.m_ = this.mStack_.pop()) };
    d.translate = function(a, b) {
        z(this, t([
            [1, 0, 0],
            [0, 1, 0],
            [a, b, 1]
        ], this.m_), !1)
    };
    d.rotate = function(a) {
        var b = K(a);
        a = J(a);
        z(this, t([
            [b, a, 0],
            [-a, b, 0],
            [0, 0, 1]
        ], this.m_), !1)
    };
    d.scale = function(a, b) {
        this.arcScaleX_ *= a;
        this.arcScaleY_ *= b;
        z(this, t([
            [a, 0, 0],
            [0, b, 0],
            [0, 0, 1]
        ], this.m_), !0)
    };
    d.transform = function(a, b, c, d, e, f) {
        z(this, t([
            [a,
                b, 0
            ],
            [c, d, 0],
            [e, f, 1]
        ], this.m_), !0)
    };
    d.setTransform = function(a, b, c, d, e, f) {
        z(this, [
            [a, b, 0],
            [c, d, 0],
            [e, f, 1]
        ], !0)
    };
    d.drawText_ = function(a, b, c, d, e) {
        var f = this.m_;
        d = 0;
        var r = 1E3,
            t = 0,
            n = [],
            h;
        h = this.font;
        if (L[h]) h = L[h];
        else {
            var l = document.createElement("div").style;
            try { l.font = h } catch (u) {}
            h = L[h] = { style: l.fontStyle || "normal", variant: l.fontVariant || "normal", weight: l.fontWeight || "normal", size: l.fontSize || 10, family: l.fontFamily || "sans-serif" }
        }
        var l = h,
            m = this.element_;
        h = {};
        for (var p in l) h[p] = l[p];
        p = parseFloat(m.currentStyle.fontSize);
        m = parseFloat(l.size);
        "number" == typeof l.size ? h.size = l.size : -1 != l.size.indexOf("px") ? h.size = m : -1 != l.size.indexOf("em") ? h.size = p * m : -1 != l.size.indexOf("%") ? h.size = p / 100 * m : -1 != l.size.indexOf("pt") ? h.size = m / 0.75 : h.size = p;
        h.size *= 0.981;
        p = h.style + " " + h.variant + " " + h.weight + " " + h.size + "px " + h.family;
        m = this.element_.currentStyle;
        l = this.textAlign.toLowerCase();
        switch (l) {
            case "left":
            case "center":
            case "right":
                break;
            case "end":
                l = "ltr" == m.direction ? "right" : "left";
                break;
            case "start":
                l = "rtl" == m.direction ? "right" :
                    "left";
                break;
            default:
                l = "left"
        }
        switch (this.textBaseline) {
            case "hanging":
            case "top":
                t = h.size / 1.75;
                break;
            case "middle":
                break;
            default:
            case null:
            case "alphabetic":
            case "ideographic":
            case "bottom":
                t = -h.size / 2.25
        }
        switch (l) {
            case "right":
                d = 1E3;
                r = 0.05;
                break;
            case "center":
                d = r = 500
        }
        b = s(this, b + 0, c + t);
        n.push('<g_vml_:line from="', -d, ' 0" to="', r, ' 0.05" ', ' coordsize="100 100" coordorigin="0 0"', ' filled="', !e, '" stroked="', !!e, '" style="position:absolute;width:1px;height:1px;">');
        e ? S(this, n) : T(this, n, { x: -d, y: 0 }, { x: r, y: h.size });
        e = f[0][0].toFixed(3) + "," + f[1][0].toFixed(3) + "," + f[0][1].toFixed(3) + "," + f[1][1].toFixed(3) + ",0,0";
        b = k(b.x / q) + "," + k(b.y / q);
        n.push('<g_vml_:skew on="t" matrix="', e, '" ', ' offset="', b, '" origin="', d, ' 0" />', '<g_vml_:path textpathok="true" />', '<g_vml_:textpath on="true" string="', N(a), '" style="v-text-align:', l, ";font:", N(p), '" /></g_vml_:line>');
        this.element_.insertAdjacentHTML("beforeEnd", n.join(""))
    };
    d.fillText = function(a, b, c, d) { this.drawText_(a, b, c, d, !1) };
    d.strokeText = function(a,
        b, c, d) { this.drawText_(a, b, c, d, !0) };
    d.measureText = function(a) {
        this.textMeasureEl_ || (this.element_.insertAdjacentHTML("beforeEnd", '<span style="position:absolute;top:-20000px;left:0;padding:0;margin:0;border:none;white-space:pre;"></span>'), this.textMeasureEl_ = this.element_.lastChild);
        var b = this.element_.ownerDocument;
        this.textMeasureEl_.innerHTML = "";
        this.textMeasureEl_.style.font = this.font;
        this.textMeasureEl_.appendChild(b.createTextNode(a));
        return { width: this.textMeasureEl_.offsetWidth }
    };
    d.clip = function() {};
    d.arcTo = function() {};
    d.createPattern = function(a, b) { return new I(a, b) };
    w.prototype.addColorStop = function(a, b) {
        b = G(b);
        this.colors_.push({ offset: a, color: b.color, alpha: b.alpha })
    };
    d = A.prototype = Error();
    d.INDEX_SIZE_ERR = 1;
    d.DOMSTRING_SIZE_ERR = 2;
    d.HIERARCHY_REQUEST_ERR = 3;
    d.WRONG_DOCUMENT_ERR = 4;
    d.INVALID_CHARACTER_ERR = 5;
    d.NO_DATA_ALLOWED_ERR = 6;
    d.NO_MODIFICATION_ALLOWED_ERR = 7;
    d.NOT_FOUND_ERR = 8;
    d.NOT_SUPPORTED_ERR = 9;
    d.INUSE_ATTRIBUTE_ERR = 10;
    d.INVALID_STATE_ERR = 11;
    d.SYNTAX_ERR = 12;
    d.INVALID_MODIFICATION_ERR =
        13;
    d.NAMESPACE_ERR = 14;
    d.INVALID_ACCESS_ERR = 15;
    d.VALIDATION_ERR = 16;
    d.TYPE_MISMATCH_ERR = 17;
    G_vmlCanvasManager = U;
    CanvasRenderingContext2D = C;
    CanvasGradient = w;
    CanvasPattern = I;
    DOMException = A
}();
/*eslint-enable*/
/*jshint ignore:end*/