!(function (t, e) {
    "use strict";
    "object" == typeof module && module.exports ? (module.exports = e(require("moment"))) : "function" == typeof define && define.amd ? define(["moment"], e) : e(t.moment);
})(this, function (r) {
    "use strict";
    void 0 === r.version && r.default && (r = r.default);
    var e,
        a = {},
        i = {},
        s = {},
        c = {},
        l = {},
        t = ((r && "string" == typeof r.version) || D("Moment Timezone requires Moment.js. See https://momentjs.com/timezone/docs/#/use-it/browser/"), r.version.split(".")),
        n = +t[0],
        o = +t[1];
    function f(t) {
        return 96 < t ? t - 87 : 64 < t ? t - 29 : t - 48;
    }
    function u(t) {
        var e = 0,
            n = t.split("."),
            o = n[0],
            r = n[1] || "",
            i = 1,
            s = 0,
            n = 1;
        for (45 === t.charCodeAt(0) && (n = -(e = 1)); e < o.length; e++) s = 60 * s + f(o.charCodeAt(e));
        for (e = 0; e < r.length; e++) (i /= 60), (s += f(r.charCodeAt(e)) * i);
        return s * n;
    }
    function h(t) {
        for (var e = 0; e < t.length; e++) t[e] = u(t[e]);
    }
    function p(t, e) {
        for (var n = [], o = 0; o < e.length; o++) n[o] = t[e[o]];
        return n;
    }
    function m(t) {
        for (var t = t.split("|"), e = t[2].split(" "), n = t[3].split(""), o = t[4].split(" "), r = (h(e), h(n), h(o), o), i = n.length, s = 0; s < i; s++) r[s] = Math.round((r[s - 1] || 0) + 6e4 * r[s]);
        return (r[i - 1] = 1 / 0), { name: t[0], abbrs: p(t[1].split(" "), n), offsets: p(e, n), untils: o, population: 0 | t[5] };
    }
    function d(t) {
        t && this._set(m(t));
    }
    function z(t, e) {
        (this.name = t), (this.zones = e);
    }
    function v(t) {
        var e = t.toTimeString(),
            n = e.match(/\([a-z ]+\)/i);
        "GMT" === (n = n && n[0] ? ((n = n[0].match(/[A-Z]/g)) ? n.join("") : void 0) : (n = e.match(/[A-Z]{3,5}/g)) ? n[0] : void 0) && (n = void 0), (this.at = +t), (this.abbr = n), (this.offset = t.getTimezoneOffset());
    }
    function b(t) {
        (this.zone = t), (this.offsetScore = 0), (this.abbrScore = 0);
    }
    function g() {
        for (var t, e, n, o = new Date().getFullYear() - 2, r = new v(new Date(o, 0, 1)), i = r.offset, s = [r], f = 1; f < 48; f++)
            (n = new Date(o, f, 1).getTimezoneOffset()) !== i &&
                ((t = (function (t, e) {
                    for (var n; (n = 6e4 * (((e.at - t.at) / 12e4) | 0)); ) (n = new v(new Date(t.at + n))).offset === t.offset ? (t = n) : (e = n);
                    return t;
                })(r, (e = new v(new Date(o, f, 1))))),
                s.push(t),
                s.push(new v(new Date(t.at + 6e4))),
                (r = e),
                (i = n));
        for (f = 0; f < 4; f++) s.push(new v(new Date(o + f, 0, 1))), s.push(new v(new Date(o + f, 6, 1)));
        return s;
    }
    function _(t, e) {
        return t.offsetScore !== e.offsetScore
            ? t.offsetScore - e.offsetScore
            : t.abbrScore !== e.abbrScore
            ? t.abbrScore - e.abbrScore
            : t.zone.population !== e.zone.population
            ? e.zone.population - t.zone.population
            : e.zone.name.localeCompare(t.zone.name);
    }
    function w() {
        try {
            var t = Intl.DateTimeFormat().resolvedOptions().timeZone;
            if (t && 3 < t.length) {
                var e = c[y(t)];
                if (e) return e;
                D("Moment Timezone found " + t + " from the Intl api, but did not have that data loaded.");
            }
        } catch (t) {}
        for (
            var n,
                o,
                r = g(),
                i = r.length,
                s = (function (t) {
                    for (var e, n, o, r = t.length, i = {}, s = [], f = {}, u = 0; u < r; u++)
                        if (((n = t[u].offset), !f.hasOwnProperty(n))) {
                            for (e in (o = l[n] || {})) o.hasOwnProperty(e) && (i[e] = !0);
                            f[n] = !0;
                        }
                    for (u in i) i.hasOwnProperty(u) && s.push(c[u]);
                    return s;
                })(r),
                f = [],
                u = 0;
            u < s.length;
            u++
        ) {
            for (n = new b(S(s[u])), o = 0; o < i; o++) n.scoreOffsetAt(r[o]);
            f.push(n);
        }
        return f.sort(_), 0 < f.length ? f[0].zone.name : void 0;
    }
    function y(t) {
        return (t || "").toLowerCase().replace(/\//g, "_");
    }
    function O(t) {
        var e, n, o, r;
        for ("string" == typeof t && (t = [t]), e = 0; e < t.length; e++) {
            (r = y((n = (o = t[e].split("|"))[0]))), (a[r] = t[e]), (c[r] = n), (s = i = u = f = void 0);
            var i,
                s,
                f = r,
                u = o[2].split(" ");
            for (h(u), i = 0; i < u.length; i++) (s = u[i]), (l[s] = l[s] || {}), (l[s][f] = !0);
        }
    }
    function S(t, e) {
        t = y(t);
        var n = a[t];
        return n instanceof d ? n : "string" == typeof n ? ((n = new d(n)), (a[t] = n)) : i[t] && e !== S && (e = S(i[t], S)) ? ((n = a[t] = new d())._set(e), (n.name = c[t]), n) : null;
    }
    function M(t) {
        var e, n, o, r;
        for ("string" == typeof t && (t = [t]), e = 0; e < t.length; e++) (o = y((n = t[e].split("|"))[0])), (r = y(n[1])), (i[o] = r), (c[o] = n[0]), (i[r] = o), (c[r] = n[1]);
    }
    function j(t) {
        return j.didShowError || ((j.didShowError = !0), D("moment.tz.zoneExists('" + t + "') has been deprecated in favor of !moment.tz.zone('" + t + "')")), !!S(t);
    }
    function A(t) {
        var e = "X" === t._f || "x" === t._f;
        return !(!t._a || void 0 !== t._tzm || e);
    }
    function D(t) {
        "undefined" != typeof console && "function" == typeof console.error && console.error(t);
    }
    function T(t) {
        var e = Array.prototype.slice.call(arguments, 0, -1),
            n = arguments[arguments.length - 1],
            e = r.utc.apply(null, e);
        return !r.isMoment(t) && A(e) && (t = S(n)) && e.add(t.parse(e), "minutes"), e.tz(n), e;
    }
    (n < 2 || (2 == n && o < 6)) && D("Moment Timezone requires Moment.js >= 2.6.0. You are using Moment.js " + r.version + ". See momentjs.com"),
        (d.prototype = {
            _set: function (t) {
                (this.name = t.name), (this.abbrs = t.abbrs), (this.untils = t.untils), (this.offsets = t.offsets), (this.population = t.population);
            },
            _index: function (t) {
                t = (function (t, e) {
                    var n,
                        o = e.length;
                    if (t < e[0]) return 0;
                    if (1 < o && e[o - 1] === 1 / 0 && t >= e[o - 2]) return o - 1;
                    if (t >= e[o - 1]) return -1;
                    for (var r = 0, i = o - 1; 1 < i - r; ) e[(n = Math.floor((r + i) / 2))] <= t ? (r = n) : (i = n);
                    return i;
                })(+t, this.untils);
                if (0 <= t) return t;
            },
            countries: function () {
                var e = this.name;
                return Object.keys(s).filter(function (t) {
                    return -1 !== s[t].zones.indexOf(e);
                });
            },
            parse: function (t) {
                for (var e, n, o, r = +t, i = this.offsets, s = this.untils, f = s.length - 1, u = 0; u < f; u++)
                    if (((e = i[u]), (n = i[u + 1]), (o = i[u && u - 1]), e < n && T.moveAmbiguousForward ? (e = n) : o < e && T.moveInvalidForward && (e = o), r < s[u] - 6e4 * e)) return i[u];
                return i[f];
            },
            abbr: function (t) {
                return this.abbrs[this._index(t)];
            },
            offset: function (t) {
                return D("zone.offset has been deprecated in favor of zone.utcOffset"), this.offsets[this._index(t)];
            },
            utcOffset: function (t) {
                return this.offsets[this._index(t)];
            },
        }),
        (b.prototype.scoreOffsetAt = function (t) {
            (this.offsetScore += Math.abs(this.zone.utcOffset(t.at) - t.offset)), this.zone.abbr(t.at).replace(/[^A-Z]/g, "") !== t.abbr && this.abbrScore++;
        }),
        (T.version = "0.5.44"),
        (T.dataVersion = ""),
        (T._zones = a),
        (T._links = i),
        (T._names = c),
        (T._countries = s),
        (T.add = O),
        (T.link = M),
        (T.load = function (t) {
            O(t.zones), M(t.links);
            var e,
                n,
                o,
                r = t.countries;
            if (r && r.length) for (e = 0; e < r.length; e++) (n = (o = r[e].split("|"))[0].toUpperCase()), (o = o[1].split(" ")), (s[n] = new z(n, o));
            T.dataVersion = t.version;
        }),
        (T.zone = S),
        (T.zoneExists = j),
        (T.guess = function (t) {
            return (e = e && !t ? e : w());
        }),
        (T.names = function () {
            var t,
                e = [];
            for (t in c) c.hasOwnProperty(t) && (a[t] || a[i[t]]) && c[t] && e.push(c[t]);
            return e.sort();
        }),
        (T.Zone = d),
        (T.unpack = m),
        (T.unpackBase60 = u),
        (T.needsOffset = A),
        (T.moveInvalidForward = !0),
        (T.moveAmbiguousForward = !1),
        (T.countries = function () {
            return Object.keys(s);
        }),
        (T.zonesForCountry = function (t, e) {
            var n;
            return (
                (n = (n = t).toUpperCase()),
                (t = s[n] || null)
                    ? ((n = t.zones.sort()),
                      e
                          ? n.map(function (t) {
                                return { name: t, offset: S(t).utcOffset(new Date()) };
                            })
                          : n)
                    : null
            );
        });
    var x,
        t = r.fn;
    function C(t) {
        return function () {
            return this._z ? this._z.abbr(this) : t.call(this);
        };
    }
    function Z(t) {
        return function () {
            return (this._z = null), t.apply(this, arguments);
        };
    }
    (r.tz = T),
        (r.defaultZone = null),
        (r.updateOffset = function (t, e) {
            var n,
                o = r.defaultZone;
            void 0 === t._z && (o && A(t) && !t._isUTC && t.isValid() && ((t._d = r.utc(t._a)._d), t.utc().add(o.parse(t), "minutes")), (t._z = o)),
                t._z && ((o = t._z.utcOffset(t)), Math.abs(o) < 16 && (o /= 60), void 0 !== t.utcOffset ? ((n = t._z), t.utcOffset(-o, e), (t._z = n)) : t.zone(o, e));
        }),
        (t.tz = function (t, e) {
            if (t) {
                if ("string" != typeof t) throw new Error("Time zone name must be a string, got " + t + " [" + typeof t + "]");
                return (this._z = S(t)), this._z ? r.updateOffset(this, e) : D("Moment Timezone has no data for " + t + ". See http://momentjs.com/timezone/docs/#/data-loading/."), this;
            }
            if (this._z) return this._z.name;
        }),
        (t.zoneName = C(t.zoneName)),
        (t.zoneAbbr = C(t.zoneAbbr)),
        (t.utc = Z(t.utc)),
        (t.local = Z(t.local)),
        (t.utcOffset =
            ((x = t.utcOffset),
            function () {
                return 0 < arguments.length && (this._z = null), x.apply(this, arguments);
            })),
        (r.tz.setDefault = function (t) {
            return (n < 2 || (2 == n && o < 9)) && D("Moment Timezone setDefault() requires Moment.js >= 2.9.0. You are using Moment.js " + r.version + "."), (r.defaultZone = t ? S(t) : null), r;
        });
    t = r.momentProperties;
    return "[object Array]" === Object.prototype.toString.call(t) ? (t.push("_z"), t.push("_a")) : t && (t._z = null), r;
});
