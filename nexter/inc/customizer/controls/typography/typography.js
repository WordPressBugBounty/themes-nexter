/**
 * Typograghy Control
 *
 * @package Nexter
 * @since	1.0.0
 */
! function(r) {
    var v = wp.customize,
        g = {
            100: "100",
            "100italic": "100 Italic",
            200: "200",
            "200italic": "200 Italic",
            300: "300",
            "300italic": "300 Italic",
            400: "400",
            italic: "400 Italic",
            500: "500",
            "500italic": "500 Italic",
            600: "600",
            "600italic": "600 Italic",
            700: "700",
            "700italic": "700 Italic",
            800: "800",
            "800italic": "800 Italic",
            900: "900",
            "900italic": "900 Italic"
        };
    NexterTypo = {
        init: function() {
            NexterTypo.initFonts()
        },
        initFonts: function() {
            r(".customize-control-nxt-font-family select").each(function() {
                var t = r(this),
                    e = t.data("customize-setting-link"),
                    i = t.data("field-control"),
                    t = t.data("field-variant");
                void 0 !== i && (v(e).bind(NexterTypo.selectFontChange), NexterTypo.setFontWeightOptions.apply(v(e), [!0])), void 0 !== t && (v(e).bind(NexterTypo.selectFontChange), NexterTypo.setFontVarianttOptions.apply(v(e), [!0]))
            }), r(".customize-control-nxt-font-family select, .customize-control-nxt-font-variant select").select2({
                placeholder: "Select Font Family"
            }), r(".customize-control-nxt-font-variant select").on("select2:unselecting", function(t) {
                var e = r(this).data("customize-setting-link");
                (t.params.args.data.id || "") && (r(this).find('option[value="' + t.params.args.data.id + '"]').removeAttr("selected"), null === r(this).val() && v(e).set(""))
            })
        },
        selectFontChange: function() {
            var t = v.control(this.id).container.find("select").data("field-variant");
            NexterTypo.setFontWeightOptions.apply(this, [!1]), void 0 !== t && NexterTypo.setFontVarianttOptions.apply(this, [!1])
        },
        replaceFontsRegExp: function(t) {
            if (!t.includes(",")) return t;
            var e = t.split(","),
                i = new RegExp("'", "gi"),
                i = e[0].replace(i, "");
            return void 0 !== NxtLoadFontFamily.google[i] && (t = i), t
        },
        getWeightObject: function(t) {
            var e = ["400", "600"];
            return "inherit" == t ? e = ["100", "200", "300", "400", "500", "600", "700", "800", "900"] : void 0 !== NxtLoadFontFamily.system[t] ? e = NxtLoadFontFamily.system[t].weights : void 0 !== NxtLoadFontFamily.google[t] ? (e = NxtLoadFontFamily.google[t][0], e = Object.keys(e).map(function(t) {
                return e[t]
            })) : void 0 !== NxtLoadFontFamily.custom[t.split(",")[0]] && (e = NxtLoadFontFamily.custom[t.split(",")[0]].weights), e
        },
        setFontWeightOptions: function(t) {
            var e = 0,
                i = v.control(this.id).container.find("select"),
                n = this(),
                o = i.data("field-control"),
                i = v.control(o).container.find("select"),
                a = t ? i.val() : "400",
                l = ["400", "600"],
                c = g;
            "inherit" == n && (a = t ? i.val() : "inherit");
            n = NexterTypo.replaceFontsRegExp(n), l = NexterTypo.getWeightObject(n);
            l = r.merge(["inherit"], l), c.inherit = i.data("inherit");
            for (var s = selected = ""; e < l.length; e++) selected = 0 === e && -1 === r.inArray(a, l) ? (a = l[0], ' selected="selected"') : l[e] == a ? ' selected="selected"' : "", l[e].includes("italic") || (s += '<option value="' + l[e] + '"' + selected + ">" + c[l[e]] + "</option>");
            i.html(s), t || (v(o).set(""), v(o).set(a))
        },
        setFontVarianttOptions: function(t) {
            var e = 0,
                i = "",
                n = v.control(this.id).container.find("select"),
                o = this(),
                a = n.data("field-variant"),
                l = v.control(a).container.find("select"),
                c = v.control(a).container.find(".typo-variant-hidden-value"),
                s = g,
                r = c.val().split(",");
            "Google Fonts" == (n.find('option[value="' + n.val() + '"]').closest("optgroup").attr("label") || "") ? l.parent().removeClass("nxt-hide"): l.parent().addClass("nxt-hide");
            var o = NexterTypo.replaceFontsRegExp(o),
                d = NexterTypo.getWeightObject(o);
            s.inherit = l.data("inherit");
            for (var p = selected = "", e = 0; e < d.length; e++) {
                for (var h = 0; h < r.length; h++) selected = d[e] === r[h] ? (i = d[e], ' selected="selected"') : d[e] == i ? ' selected="selected"' : "";
                p += '<option value="' + d[e] + '"' + selected + ">" + s[d[e]] + "</option>"
            }
            l.html(p), t || v(a).set("")
        }
    }, r(function() {
        NexterTypo.init()
    })
}(jQuery);