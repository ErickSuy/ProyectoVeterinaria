var Prototype = {
    Version: "1.6.1",
    Browser: (function () {
        var B = navigator.userAgent;
        var A = Object.prototype.toString.call(window.opera) == "[object Opera]";
        return {
            IE: !!window.attachEvent && !A,
            Opera: A,
            WebKit: B.indexOf("AppleWebKit/") > -1,
            Gecko: B.indexOf("Gecko") > -1 && B.indexOf("KHTML") === -1,
            MobileSafari: /Apple.*Mobile.*Safari/.test(B)
        }
    })(),
    BrowserFeatures: {
        XPath: !!document.evaluate,
        SelectorsAPI: !!document.querySelector,
        ElementExtensions: (function () {
            var A = window.Element || window.HTMLElement;
            return !!(A && A.prototype)
        })(),
        SpecificElementExtensions: (function () {
            if (typeof window.HTMLDivElement !== "undefined") {
                return true
            }
            var C = document.createElement("div");
            var B = document.createElement("form");
            var A = false;
            if (C.__proto__ && (C.__proto__ !== B.__proto__)) {
                A = true
            }
            C = B = null;
            return A
        })()
    },
    ScriptFragment: "<script[^>]*>([\\S\\s]*?)<\/script>",
    JSONFilter: /^\/\*-secure-([\s\S]*)\*\/\s*$/,
    emptyFunction: function () {
    },
    K: function (A) {
        return A
    }
};
if (Prototype.Browser.MobileSafari) {
    Prototype.BrowserFeatures.SpecificElementExtensions = false
}
var Abstract = {};
var Try = {
    these: function () {
        var C;
        for (var B = 0, D = arguments.length; B < D; B++) {
            var A = arguments[B];
            try {
                C = A();
                break
            } catch (E) {
            }
        }
        return C
    }
};
var Class = (function () {
    function A() {
    }

    function B() {
        var G = null, F = $A(arguments);
        if (Object.isFunction(F[0])) {
            G = F.shift()
        }
        function D() {
            this.initialize.apply(this, arguments)
        }

        Object.extend(D, Class.Methods);
        D.superclass = G;
        D.subclasses = [];
        if (G) {
            A.prototype = G.prototype;
            D.prototype = new A;
            G.subclasses.push(D)
        }
        for (var E = 0; E < F.length; E++) {
            D.addMethods(F[E])
        }
        if (!D.prototype.initialize) {
            D.prototype.initialize = Prototype.emptyFunction
        }
        D.prototype.constructor = D;
        return D
    }

    function C(J) {
        var F = this.superclass && this.superclass.prototype;
        var E = Object.keys(J);
        if (!Object.keys({toString: true}).length) {
            if (J.toString != Object.prototype.toString) {
                E.push("toString")
            }
            if (J.valueOf != Object.prototype.valueOf) {
                E.push("valueOf")
            }
        }
        for (var D = 0, G = E.length; D < G; D++) {
            var I = E[D], H = J[I];
            if (F && Object.isFunction(H) && H.argumentNames().first() == "$super") {
                var K = H;
                H = (function (L) {
                    return function () {
                        return F[L].apply(this, arguments)
                    }
                })(I).wrap(K);
                H.valueOf = K.valueOf.bind(K);
                H.toString = K.toString.bind(K)
            }
            this.prototype[I] = H
        }
        return this
    }

    return {create: B, Methods: {addMethods: C}}
})();
(function () {
    var D = Object.prototype.toString;

    function I(Q, S) {
        for (var R in S) {
            Q[R] = S[R]
        }
        return Q
    }

    function L(Q) {
        try {
            if (E(Q)) {
                return "undefined"
            }
            if (Q === null) {
                return "null"
            }
            return Q.inspect ? Q.inspect() : String(Q)
        } catch (R) {
            if (R instanceof RangeError) {
                return "..."
            }
            throw R
        }
    }

    function K(Q) {
        var S = typeof Q;
        switch (S) {
            case"undefined":
            case"function":
            case"unknown":
                return;
            case"boolean":
                return Q.toString()
        }
        if (Q === null) {
            return "null"
        }
        if (Q.toJSON) {
            return Q.toJSON()
        }
        if (H(Q)) {
            return
        }
        var R = [];
        for (var U in Q) {
            var T = K(Q[U]);
            if (!E(T)) {
                R.push(U.toJSON() + ": " + T)
            }
        }
        return "{" + R.join(", ") + "}"
    }

    function C(Q) {
        return $H(Q).toQueryString()
    }

    function F(Q) {
        return Q && Q.toHTML ? Q.toHTML() : String.interpret(Q)
    }

    function O(Q) {
        var R = [];
        for (var S in Q) {
            R.push(S)
        }
        return R
    }

    function M(Q) {
        var R = [];
        for (var S in Q) {
            R.push(Q[S])
        }
        return R
    }

    function J(Q) {
        return I({}, Q)
    }

    function H(Q) {
        return !!(Q && Q.nodeType == 1)
    }

    function G(Q) {
        return D.call(Q) == "[object Array]"
    }

    function P(Q) {
        return Q instanceof Hash
    }

    function B(Q) {
        return typeof Q === "function"
    }

    function A(Q) {
        return D.call(Q) == "[object String]"
    }

    function N(Q) {
        return D.call(Q) == "[object Number]"
    }

    function E(Q) {
        return typeof Q === "undefined"
    }

    I(Object, {
        extend: I,
        inspect: L,
        toJSON: K,
        toQueryString: C,
        toHTML: F,
        keys: O,
        values: M,
        clone: J,
        isElement: H,
        isArray: G,
        isHash: P,
        isFunction: B,
        isString: A,
        isNumber: N,
        isUndefined: E
    })
})();
Object.extend(Function.prototype, (function () {
    var K = Array.prototype.slice;

    function D(O, L) {
        var N = O.length, M = L.length;
        while (M--) {
            O[N + M] = L[M]
        }
        return O
    }

    function I(M, L) {
        M = K.call(M, 0);
        return D(M, L)
    }

    function G() {
        var L = this.toString().match(/^[\s\(]*function[^(]*\(([^)]*)\)/)[1].replace(/\/\/.*?[\r\n]|\/\*(?:.|[\r\n])*?\*\//g, "").replace(/\s+/g, "").split(",");
        return L.length == 1 && !L[0] ? [] : L
    }

    function H(N) {
        if (arguments.length < 2 && Object.isUndefined(arguments[0])) {
            return this
        }
        var L = this, M = K.call(arguments, 1);
        return function () {
            var O = I(M, arguments);
            return L.apply(N, O)
        }
    }

    function F(N) {
        var L = this, M = K.call(arguments, 1);
        return function (P) {
            var O = D([P || window.event], M);
            return L.apply(N, O)
        }
    }

    function J() {
        if (!arguments.length) {
            return this
        }
        var L = this, M = K.call(arguments, 0);
        return function () {
            var N = I(M, arguments);
            return L.apply(this, N)
        }
    }

    function E(N) {
        var L = this, M = K.call(arguments, 1);
        N = N * 1000;
        return window.setTimeout(function () {
            return L.apply(L, M)
        }, N)
    }

    function A() {
        var L = D([0.01], arguments);
        return this.delay.apply(this, L)
    }

    function C(M) {
        var L = this;
        return function () {
            var N = D([L.bind(this)], arguments);
            return M.apply(this, N)
        }
    }

    function B() {
        if (this._methodized) {
            return this._methodized
        }
        var L = this;
        return this._methodized = function () {
            var M = D([this], arguments);
            return L.apply(null, M)
        }
    }

    return {argumentNames: G, bind: H, bindAsEventListener: F, curry: J, delay: E, defer: A, wrap: C, methodize: B}
})());
Date.prototype.toJSON = function () {
    return '"' + this.getUTCFullYear() + "-" + (this.getUTCMonth() + 1).toPaddedString(2) + "-" + this.getUTCDate().toPaddedString(2) + "T" + this.getUTCHours().toPaddedString(2) + ":" + this.getUTCMinutes().toPaddedString(2) + ":" + this.getUTCSeconds().toPaddedString(2) + 'Z"'
};
RegExp.prototype.match = RegExp.prototype.test;
RegExp.escape = function (A) {
    return String(A).replace(/([.*+?^=!:${}()|[\]\/\\])/g, "\\$1")
};
var PeriodicalExecuter = Class.create({
    initialize: function (B, A) {
        this.callback = B;
        this.frequency = A;
        this.currentlyExecuting = false;
        this.registerCallback()
    }, registerCallback: function () {
        this.timer = setInterval(this.onTimerEvent.bind(this), this.frequency * 1000)
    }, execute: function () {
        this.callback(this)
    }, stop: function () {
        if (!this.timer) {
            return
        }
        clearInterval(this.timer);
        this.timer = null
    }, onTimerEvent: function () {
        if (!this.currentlyExecuting) {
            try {
                this.currentlyExecuting = true;
                this.execute();
                this.currentlyExecuting = false
            } catch (A) {
                this.currentlyExecuting = false;
                throw A
            }
        }
    }
});
Object.extend(String, {
    interpret: function (A) {
        return A == null ? "" : String(A)
    }, specialChar: {"\b": "\\b", "\t": "\\t", "\n": "\\n", "\f": "\\f", "\r": "\\r", "\\": "\\\\"}
});
Object.extend(String.prototype, (function () {
    function prepareReplacement(replacement) {
        if (Object.isFunction(replacement)) {
            return replacement
        }
        var template = new Template(replacement);
        return function (match) {
            return template.evaluate(match)
        }
    }

    function gsub(pattern, replacement) {
        var result = "", source = this, match;
        replacement = prepareReplacement(replacement);
        if (Object.isString(pattern)) {
            pattern = RegExp.escape(pattern)
        }
        if (!(pattern.length || pattern.source)) {
            replacement = replacement("");
            return replacement + source.split("").join(replacement) + replacement
        }
        while (source.length > 0) {
            if (match = source.match(pattern)) {
                result += source.slice(0, match.index);
                result += String.interpret(replacement(match));
                source = source.slice(match.index + match[0].length)
            } else {
                result += source, source = ""
            }
        }
        return result
    }

    function sub(pattern, replacement, count) {
        replacement = prepareReplacement(replacement);
        count = Object.isUndefined(count) ? 1 : count;
        return this.gsub(pattern, function (match) {
            if (--count < 0) {
                return match[0]
            }
            return replacement(match)
        })
    }

    function scan(pattern, iterator) {
        this.gsub(pattern, iterator);
        return String(this)
    }

    function truncate(length, truncation) {
        length = length || 30;
        truncation = Object.isUndefined(truncation) ? "..." : truncation;
        return this.length > length ? this.slice(0, length - truncation.length) + truncation : String(this)
    }

    function strip() {
        return this.replace(/^\s+/, "").replace(/\s+$/, "")
    }

    function stripTags() {
        return this.replace(/<\w+(\s+("[^"]*"|'[^']*'|[^>])+)?>|<\/\w+>/gi, "")
    }

    function stripScripts() {
        return this.replace(new RegExp(Prototype.ScriptFragment, "img"), "")
    }

    function extractScripts() {
        var matchAll = new RegExp(Prototype.ScriptFragment, "img");
        var matchOne = new RegExp(Prototype.ScriptFragment, "im");
        return (this.match(matchAll) || []).map(function (scriptTag) {
            return (scriptTag.match(matchOne) || ["", ""])[1]
        })
    }

    function evalScripts() {
        return this.extractScripts().map(function (script) {
            return eval(script)
        })
    }

    function escapeHTML() {
        return this.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;")
    }

    function unescapeHTML() {
        return this.stripTags().replace(/&lt;/g, "<").replace(/&gt;/g, ">").replace(/&amp;/g, "&")
    }

    function toQueryParams(separator) {
        var match = this.strip().match(/([^?#]*)(#.*)?$/);
        if (!match) {
            return {}
        }
        return match[1].split(separator || "&").inject({}, function (hash, pair) {
            if ((pair = pair.split("="))[0]) {
                var key = decodeURIComponent(pair.shift());
                var value = pair.length > 1 ? pair.join("=") : pair[0];
                if (value != undefined) {
                    value = decodeURIComponent(value)
                }
                if (key in hash) {
                    if (!Object.isArray(hash[key])) {
                        hash[key] = [hash[key]]
                    }
                    hash[key].push(value)
                } else {
                    hash[key] = value
                }
            }
            return hash
        })
    }

    function toArray() {
        return this.split("")
    }

    function succ() {
        return this.slice(0, this.length - 1) + String.fromCharCode(this.charCodeAt(this.length - 1) + 1)
    }

    function times(count) {
        return count < 1 ? "" : new Array(count + 1).join(this)
    }

    function camelize() {
        var parts = this.split("-"), len = parts.length;
        if (len == 1) {
            return parts[0]
        }
        var camelized = this.charAt(0) == "-" ? parts[0].charAt(0).toUpperCase() + parts[0].substring(1) : parts[0];
        for (var i = 1; i < len; i++) {
            camelized += parts[i].charAt(0).toUpperCase() + parts[i].substring(1)
        }
        return camelized
    }

    function capitalize() {
        return this.charAt(0).toUpperCase() + this.substring(1).toLowerCase()
    }

    function underscore() {
        return this.replace(/::/g, "/").replace(/([A-Z]+)([A-Z][a-z])/g, "$1_$2").replace(/([a-z\d])([A-Z])/g, "$1_$2").replace(/-/g, "_").toLowerCase()
    }

    function dasherize() {
        return this.replace(/_/g, "-")
    }

    function inspect(useDoubleQuotes) {
        var escapedString = this.replace(/[\x00-\x1f\\]/g, function (character) {
            if (character in String.specialChar) {
                return String.specialChar[character]
            }
            return "\\u00" + character.charCodeAt().toPaddedString(2, 16)
        });
        if (useDoubleQuotes) {
            return '"' + escapedString.replace(/"/g, '\\"') + '"'
        }
        return "'" + escapedString.replace(/'/g, "\\'") + "'"
    }

    function toJSON() {
        return this.inspect(true)
    }

    function unfilterJSON(filter) {
        return this.replace(filter || Prototype.JSONFilter, "$1")
    }

    function isJSON() {
        var str = this;
        if (str.blank()) {
            return false
        }
        str = this.replace(/\\./g, "@").replace(/"[^"\\\n\r]*"/g, "");
        return (/^[,:{}\[\]0-9.\-+Eaeflnr-u \n\r\t]*$/).test(str)
    }

    function evalJSON(sanitize) {
        var json = this.unfilterJSON();
        try {
            if (!sanitize || json.isJSON()) {
                return eval("(" + json + ")")
            }
        } catch (e) {
        }
        throw new SyntaxError("Badly formed JSON string: " + this.inspect())
    }

    function include(pattern) {
        return this.indexOf(pattern) > -1
    }

    function startsWith(pattern) {
        return this.indexOf(pattern) === 0
    }

    function endsWith(pattern) {
        var d = this.length - pattern.length;
        return d >= 0 && this.lastIndexOf(pattern) === d
    }

    function empty() {
        return this == ""
    }

    function blank() {
        return /^\s*$/.test(this)
    }

    function interpolate(object, pattern) {
        return new Template(this, pattern).evaluate(object)
    }

    return {
        gsub: gsub,
        sub: sub,
        scan: scan,
        truncate: truncate,
        strip: String.prototype.trim ? String.prototype.trim : strip,
        stripTags: stripTags,
        stripScripts: stripScripts,
        extractScripts: extractScripts,
        evalScripts: evalScripts,
        escapeHTML: escapeHTML,
        unescapeHTML: unescapeHTML,
        toQueryParams: toQueryParams,
        parseQuery: toQueryParams,
        toArray: toArray,
        succ: succ,
        times: times,
        camelize: camelize,
        capitalize: capitalize,
        underscore: underscore,
        dasherize: dasherize,
        inspect: inspect,
        toJSON: toJSON,
        unfilterJSON: unfilterJSON,
        isJSON: isJSON,
        evalJSON: evalJSON,
        include: include,
        startsWith: startsWith,
        endsWith: endsWith,
        empty: empty,
        blank: blank,
        interpolate: interpolate
    }
})());
var Template = Class.create({
    initialize: function (A, B) {
        this.template = A.toString();
        this.pattern = B || Template.Pattern
    }, evaluate: function (A) {
        if (A && Object.isFunction(A.toTemplateReplacements)) {
            A = A.toTemplateReplacements()
        }
        return this.template.gsub(this.pattern, function (D) {
            if (A == null) {
                return (D[1] + "")
            }
            var F = D[1] || "";
            if (F == "\\") {
                return D[2]
            }
            var B = A, G = D[3];
            var E = /^([^.[]+|\[((?:.*?[^\\])?)\])(\.|\[|$)/;
            D = E.exec(G);
            if (D == null) {
                return F
            }
            while (D != null) {
                var C = D[1].startsWith("[") ? D[2].replace(/\\\\]/g, "]") : D[1];
                B = B[C];
                if (null == B || "" == D[3]) {
                    break
                }
                G = G.substring("[" == D[3] ? D[1].length : D[0].length);
                D = E.exec(G)
            }
            return F + String.interpret(B)
        })
    }
});
Template.Pattern = /(^|.|\r|\n)(#\{(.*?)\})/;
var $break = {};
var Enumerable = (function () {
    function C(Y, X) {
        var W = 0;
        try {
            this._each(function (a) {
                Y.call(X, a, W++)
            })
        } catch (Z) {
            if (Z != $break) {
                throw Z
            }
        }
        return this
    }

    function R(Z, Y, X) {
        var W = -Z, a = [], b = this.toArray();
        if (Z < 1) {
            return b
        }
        while ((W += Z) < b.length) {
            a.push(b.slice(W, W + Z))
        }
        return a.collect(Y, X)
    }

    function B(Y, X) {
        Y = Y || Prototype.K;
        var W = true;
        this.each(function (a, Z) {
            W = W && !!Y.call(X, a, Z);
            if (!W) {
                throw $break
            }
        });
        return W
    }

    function I(Y, X) {
        Y = Y || Prototype.K;
        var W = false;
        this.each(function (a, Z) {
            if (W = !!Y.call(X, a, Z)) {
                throw $break
            }
        });
        return W
    }

    function J(Y, X) {
        Y = Y || Prototype.K;
        var W = [];
        this.each(function (a, Z) {
            W.push(Y.call(X, a, Z))
        });
        return W
    }

    function T(Y, X) {
        var W;
        this.each(function (a, Z) {
            if (Y.call(X, a, Z)) {
                W = a;
                throw $break
            }
        });
        return W
    }

    function H(Y, X) {
        var W = [];
        this.each(function (a, Z) {
            if (Y.call(X, a, Z)) {
                W.push(a)
            }
        });
        return W
    }

    function G(Z, Y, X) {
        Y = Y || Prototype.K;
        var W = [];
        if (Object.isString(Z)) {
            Z = new RegExp(RegExp.escape(Z))
        }
        this.each(function (b, a) {
            if (Z.match(b)) {
                W.push(Y.call(X, b, a))
            }
        });
        return W
    }

    function A(W) {
        if (Object.isFunction(this.indexOf)) {
            if (this.indexOf(W) != -1) {
                return true
            }
        }
        var X = false;
        this.each(function (Y) {
            if (Y == W) {
                X = true;
                throw $break
            }
        });
        return X
    }

    function Q(X, W) {
        W = Object.isUndefined(W) ? null : W;
        return this.eachSlice(X, function (Y) {
            while (Y.length < X) {
                Y.push(W)
            }
            return Y
        })
    }

    function L(W, Y, X) {
        this.each(function (a, Z) {
            W = Y.call(X, W, a, Z)
        });
        return W
    }

    function V(X) {
        var W = $A(arguments).slice(1);
        return this.map(function (Y) {
            return Y[X].apply(Y, W)
        })
    }

    function P(Y, X) {
        Y = Y || Prototype.K;
        var W;
        this.each(function (a, Z) {
            a = Y.call(X, a, Z);
            if (W == null || a >= W) {
                W = a
            }
        });
        return W
    }

    function N(Y, X) {
        Y = Y || Prototype.K;
        var W;
        this.each(function (a, Z) {
            a = Y.call(X, a, Z);
            if (W == null || a < W) {
                W = a
            }
        });
        return W
    }

    function E(Z, X) {
        Z = Z || Prototype.K;
        var Y = [], W = [];
        this.each(function (b, a) {
            (Z.call(X, b, a) ? Y : W).push(b)
        });
        return [Y, W]
    }

    function F(X) {
        var W = [];
        this.each(function (Y) {
            W.push(Y[X])
        });
        return W
    }

    function D(Y, X) {
        var W = [];
        this.each(function (a, Z) {
            if (!Y.call(X, a, Z)) {
                W.push(a)
            }
        });
        return W
    }

    function M(X, W) {
        return this.map(function (Z, Y) {
            return {value: Z, criteria: X.call(W, Z, Y)}
        }).sort(function (d, c) {
            var Z = d.criteria, Y = c.criteria;
            return Z < Y ? -1 : Z > Y ? 1 : 0
        }).pluck("value")
    }

    function O() {
        return this.map()
    }

    function S() {
        var X = Prototype.K, W = $A(arguments);
        if (Object.isFunction(W.last())) {
            X = W.pop()
        }
        var Y = [this].concat(W).map($A);
        return this.map(function (a, Z) {
            return X(Y.pluck(Z))
        })
    }

    function K() {
        return this.toArray().length
    }

    function U() {
        return "#<Enumerable:" + this.toArray().inspect() + ">"
    }

    return {
        each: C,
        eachSlice: R,
        all: B,
        every: B,
        any: I,
        some: I,
        collect: J,
        map: J,
        detect: T,
        findAll: H,
        select: H,
        filter: H,
        grep: G,
        include: A,
        member: A,
        inGroupsOf: Q,
        inject: L,
        invoke: V,
        max: P,
        min: N,
        partition: E,
        pluck: F,
        reject: D,
        sortBy: M,
        toArray: O,
        entries: O,
        zip: S,
        size: K,
        inspect: U,
        find: T
    }
})();
function $A(C) {
    if (!C) {
        return []
    }
    if ("toArray" in Object(C)) {
        return C.toArray()
    }
    var B = C.length || 0, A = new Array(B);
    while (B--) {
        A[B] = C[B]
    }
    return A
}
function $w(A) {
    if (!Object.isString(A)) {
        return []
    }
    A = A.strip();
    return A ? A.split(/\s+/) : []
}
Array.from = $A;
(function () {
    var S = Array.prototype, M = S.slice, O = S.forEach;

    function B(W) {
        for (var V = 0, X = this.length; V < X; V++) {
            W(this[V])
        }
    }

    if (!O) {
        O = B
    }
    function L() {
        this.length = 0;
        return this
    }

    function D() {
        return this[0]
    }

    function G() {
        return this[this.length - 1]
    }

    function I() {
        return this.select(function (V) {
            return V != null
        })
    }

    function U() {
        return this.inject([], function (W, V) {
            if (Object.isArray(V)) {
                return W.concat(V.flatten())
            }
            W.push(V);
            return W
        })
    }

    function H() {
        var V = M.call(arguments, 0);
        return this.select(function (W) {
            return !V.include(W)
        })
    }

    function F(V) {
        return (V !== false ? this : this.toArray())._reverse()
    }

    function K(V) {
        return this.inject([], function (Y, X, W) {
            if (0 == W || (V ? Y.last() != X : !Y.include(X))) {
                Y.push(X)
            }
            return Y
        })
    }

    function P(V) {
        return this.uniq().findAll(function (W) {
            return V.detect(function (X) {
                return W === X
            })
        })
    }

    function Q() {
        return M.call(this, 0)
    }

    function J() {
        return this.length
    }

    function T() {
        return "[" + this.map(Object.inspect).join(", ") + "]"
    }

    function R() {
        var V = [];
        this.each(function (W) {
            var X = Object.toJSON(W);
            if (!Object.isUndefined(X)) {
                V.push(X)
            }
        });
        return "[" + V.join(", ") + "]"
    }

    function A(X, V) {
        V || (V = 0);
        var W = this.length;
        if (V < 0) {
            V = W + V
        }
        for (; V < W; V++) {
            if (this[V] === X) {
                return V
            }
        }
        return -1
    }

    function N(W, V) {
        V = isNaN(V) ? this.length : (V < 0 ? this.length + V : V) + 1;
        var X = this.slice(0, V).reverse().indexOf(W);
        return (X < 0) ? X : V - X - 1
    }

    function C() {
        var a = M.call(this, 0), Y;
        for (var W = 0, X = arguments.length; W < X; W++) {
            Y = arguments[W];
            if (Object.isArray(Y) && !("callee" in Y)) {
                for (var V = 0, Z = Y.length; V < Z; V++) {
                    a.push(Y[V])
                }
            } else {
                a.push(Y)
            }
        }
        return a
    }

    Object.extend(S, Enumerable);
    if (!S._reverse) {
        S._reverse = S.reverse
    }
    Object.extend(S, {
        _each: O,
        clear: L,
        first: D,
        last: G,
        compact: I,
        flatten: U,
        without: H,
        reverse: F,
        uniq: K,
        intersect: P,
        clone: Q,
        toArray: Q,
        size: J,
        inspect: T,
        toJSON: R
    });
    var E = (function () {
        return [].concat(arguments)[0][0] !== 1
    })(1, 2);
    if (E) {
        S.concat = C
    }
    if (!S.indexOf) {
        S.indexOf = A
    }
    if (!S.lastIndexOf) {
        S.lastIndexOf = N
    }
})();
function $H(A) {
    return new Hash(A)
}
var Hash = Class.create(Enumerable, (function () {
    function E(Q) {
        this._object = Object.isHash(Q) ? Q.toObject() : Object.clone(Q)
    }

    function F(R) {
        for (var Q in this._object) {
            var S = this._object[Q], T = [Q, S];
            T.key = Q;
            T.value = S;
            R(T)
        }
    }

    function K(Q, R) {
        return this._object[Q] = R
    }

    function C(Q) {
        if (this._object[Q] !== Object.prototype[Q]) {
            return this._object[Q]
        }
    }

    function N(Q) {
        var R = this._object[Q];
        delete this._object[Q];
        return R
    }

    function P() {
        return Object.clone(this._object)
    }

    function O() {
        return this.pluck("key")
    }

    function M() {
        return this.pluck("value")
    }

    function G(R) {
        var Q = this.detect(function (S) {
            return S.value === R
        });
        return Q && Q.key
    }

    function I(Q) {
        return this.clone().update(Q)
    }

    function D(Q) {
        return new Hash(Q).inject(this, function (R, S) {
            R.set(S.key, S.value);
            return R
        })
    }

    function B(Q, R) {
        if (Object.isUndefined(R)) {
            return Q
        }
        return Q + "=" + encodeURIComponent(String.interpret(R))
    }

    function A() {
        return this.inject([], function (S, T) {
            var R = encodeURIComponent(T.key), Q = T.value;
            if (Q && typeof Q == "object") {
                if (Object.isArray(Q)) {
                    return S.concat(Q.map(B.curry(R)))
                }
            } else {
                S.push(B(R, Q))
            }
            return S
        }).join("&")
    }

    function L() {
        return "#<Hash:{" + this.map(function (Q) {
            return Q.map(Object.inspect).join(": ")
        }).join(", ") + "}>"
    }

    function J() {
        return Object.toJSON(this.toObject())
    }

    function H() {
        return new Hash(this)
    }

    return {
        initialize: E,
        _each: F,
        set: K,
        get: C,
        unset: N,
        toObject: P,
        toTemplateReplacements: P,
        keys: O,
        values: M,
        index: G,
        merge: I,
        update: D,
        toQueryString: A,
        inspect: L,
        toJSON: J,
        clone: H
    }
})());
Hash.from = $H;
Object.extend(Number.prototype, (function () {
    function D() {
        return this.toPaddedString(2, 16)
    }

    function E() {
        return this + 1
    }

    function A(K, J) {
        $R(0, this, true).each(K, J);
        return this
    }

    function B(L, K) {
        var J = this.toString(K || 10);
        return "0".times(L - J.length) + J
    }

    function F() {
        return isFinite(this) ? this.toString() : "null"
    }

    function I() {
        return Math.abs(this)
    }

    function H() {
        return Math.round(this)
    }

    function G() {
        return Math.ceil(this)
    }

    function C() {
        return Math.floor(this)
    }

    return {toColorPart: D, succ: E, times: A, toPaddedString: B, toJSON: F, abs: I, round: H, ceil: G, floor: C}
})());
function $R(C, A, B) {
    return new ObjectRange(C, A, B)
}
var ObjectRange = Class.create(Enumerable, (function () {
    function B(F, D, E) {
        this.start = F;
        this.end = D;
        this.exclusive = E
    }

    function C(D) {
        var E = this.start;
        while (this.include(E)) {
            D(E);
            E = E.succ()
        }
    }

    function A(D) {
        if (D < this.start) {
            return false
        }
        if (this.exclusive) {
            return D < this.end
        }
        return D <= this.end
    }

    return {initialize: B, _each: C, include: A}
})());
var Ajax = {
    getTransport: function () {
        return Try.these(function () {
            return new XMLHttpRequest()
        }, function () {
            return new ActiveXObject("Msxml2.XMLHTTP")
        }, function () {
            return new ActiveXObject("Microsoft.XMLHTTP")
        }) || false
    }, activeRequestCount: 0
};
Ajax.Responders = {
    responders: [], _each: function (A) {
        this.responders._each(A)
    }, register: function (A) {
        if (!this.include(A)) {
            this.responders.push(A)
        }
    }, unregister: function (A) {
        this.responders = this.responders.without(A)
    }, dispatch: function (D, B, C, A) {
        this.each(function (E) {
            if (Object.isFunction(E[D])) {
                try {
                    E[D].apply(E, [B, C, A])
                } catch (F) {
                }
            }
        })
    }
};
Object.extend(Ajax.Responders, Enumerable);
Ajax.Responders.register({
    onCreate: function () {
        Ajax.activeRequestCount++
    }, onComplete: function () {
        Ajax.activeRequestCount--
    }
});
Ajax.Base = Class.create({
    initialize: function (A) {
        this.options = {
            method: "post",
            asynchronous: true,
            contentType: "application/x-www-form-urlencoded",
            encoding: "UTF-8",
            parameters: "",
            evalJSON: true,
            evalJS: true
        };
        Object.extend(this.options, A || {});
        this.options.method = this.options.method.toLowerCase();
        if (Object.isString(this.options.parameters)) {
            this.options.parameters = this.options.parameters.toQueryParams()
        } else {
            if (Object.isHash(this.options.parameters)) {
                this.options.parameters = this.options.parameters.toObject()
            }
        }
    }
});
Ajax.Request = Class.create(Ajax.Base, {
    _complete: false, initialize: function ($super, B, A) {
        $super(A);
        this.transport = Ajax.getTransport();
        this.request(B)
    }, request: function (B) {
        this.url = B;
        this.method = this.options.method;
        var D = Object.clone(this.options.parameters);
        if (!["get", "post"].include(this.method)) {
            D._method = this.method;
            this.method = "post"
        }
        this.parameters = D;
        if (D = Object.toQueryString(D)) {
            if (this.method == "get") {
                this.url += (this.url.include("?") ? "&" : "?") + D
            } else {
                if (/Konqueror|Safari|KHTML/.test(navigator.userAgent)) {
                    D += "&_="
                }
            }
        }
        try {
            var A = new Ajax.Response(this);
            if (this.options.onCreate) {
                this.options.onCreate(A)
            }
            Ajax.Responders.dispatch("onCreate", this, A);
            this.transport.open(this.method.toUpperCase(), this.url, this.options.asynchronous);
            if (this.options.asynchronous) {
                this.respondToReadyState.bind(this).defer(1)
            }
            this.transport.onreadystatechange = this.onStateChange.bind(this);
            this.setRequestHeaders();
            this.body = this.method == "post" ? (this.options.postBody || D) : null;
            this.transport.send(this.body);
            if (!this.options.asynchronous && this.transport.overrideMimeType) {
                this.onStateChange()
            }
        } catch (C) {
            this.dispatchException(C)
        }
    }, onStateChange: function () {
        var A = this.transport.readyState;
        if (A > 1 && !((A == 4) && this._complete)) {
            this.respondToReadyState(this.transport.readyState)
        }
    }, setRequestHeaders: function () {
        var E = {
            "X-Requested-With": "XMLHttpRequest",
            "X-Prototype-Version": Prototype.Version,
            Accept: "text/javascript, text/html, application/xml, text/xml, */*"
        };
        if (this.method == "post") {
            E["Content-type"] = this.options.contentType + (this.options.encoding ? "; charset=" + this.options.encoding : "");
            if (this.transport.overrideMimeType && (navigator.userAgent.match(/Gecko\/(\d{4})/) || [0, 2005])[1] < 2005) {
                E.Connection = "close"
            }
        }
        if (typeof this.options.requestHeaders == "object") {
            var C = this.options.requestHeaders;
            if (Object.isFunction(C.push)) {
                for (var B = 0, D = C.length; B < D; B += 2) {
                    E[C[B]] = C[B + 1]
                }
            } else {
                $H(C).each(function (F) {
                    E[F.key] = F.value
                })
            }
        }
        for (var A in E) {
            this.transport.setRequestHeader(A, E[A])
        }
    }, success: function () {
        var A = this.getStatus();
        return !A || (A >= 200 && A < 300)
    }, getStatus: function () {
        try {
            return this.transport.status || 0
        } catch (A) {
            return 0
        }
    }, respondToReadyState: function (A) {
        var C = Ajax.Request.Events[A], B = new Ajax.Response(this);
        if (C == "Complete") {
            try {
                this._complete = true;
                (this.options["on" + B.status] || this.options["on" + (this.success() ? "Success" : "Failure")] || Prototype.emptyFunction)(B, B.headerJSON)
            } catch (D) {
                this.dispatchException(D)
            }
            var E = B.getHeader("Content-type");
            if (this.options.evalJS == "force" || (this.options.evalJS && this.isSameOrigin() && E && E.match(/^\s*(text|application)\/(x-)?(java|ecma)script(;.*)?\s*$/i))) {
                this.evalResponse()
            }
        }
        try {
            (this.options["on" + C] || Prototype.emptyFunction)(B, B.headerJSON);
            Ajax.Responders.dispatch("on" + C, this, B, B.headerJSON)
        } catch (D) {
            this.dispatchException(D)
        }
        if (C == "Complete") {
            this.transport.onreadystatechange = Prototype.emptyFunction
        }
    }, isSameOrigin: function () {
        var A = this.url.match(/^\s*https?:\/\/[^\/]*/);
        return !A || (A[0] == "#{protocol}//#{domain}#{port}".interpolate({
            protocol: location.protocol,
            domain: document.domain,
            port: location.port ? ":" + location.port : ""
        }))
    }, getHeader: function (A) {
        try {
            return this.transport.getResponseHeader(A) || null
        } catch (B) {
            return null
        }
    }, evalResponse: function () {
        try {
            return eval((this.transport.responseText || "").unfilterJSON())
        } catch (e) {
            this.dispatchException(e)
        }
    }, dispatchException: function (A) {
        (this.options.onException || Prototype.emptyFunction)(this, A);
        Ajax.Responders.dispatch("onException", this, A)
    }
});
Ajax.Request.Events = ["Uninitialized", "Loading", "Loaded", "Interactive", "Complete"];
Ajax.Response = Class.create({
    initialize: function (C) {
        this.request = C;
        var D = this.transport = C.transport, A = this.readyState = D.readyState;
        if ((A > 2 && !Prototype.Browser.IE) || A == 4) {
            this.status = this.getStatus();
            this.statusText = this.getStatusText();
            this.responseText = String.interpret(D.responseText);
            this.headerJSON = this._getHeaderJSON()
        }
        if (A == 4) {
            var B = D.responseXML;
            this.responseXML = Object.isUndefined(B) ? null : B;
            this.responseJSON = this._getResponseJSON()
        }
    }, status: 0, statusText: "", getStatus: Ajax.Request.prototype.getStatus, getStatusText: function () {
        try {
            return this.transport.statusText || ""
        } catch (A) {
            return ""
        }
    }, getHeader: Ajax.Request.prototype.getHeader, getAllHeaders: function () {
        try {
            return this.getAllResponseHeaders()
        } catch (A) {
            return null
        }
    }, getResponseHeader: function (A) {
        return this.transport.getResponseHeader(A)
    }, getAllResponseHeaders: function () {
        return this.transport.getAllResponseHeaders()
    }, _getHeaderJSON: function () {
        var A = this.getHeader("X-JSON");
        if (!A) {
            return null
        }
        A = decodeURIComponent(escape(A));
        try {
            return A.evalJSON(this.request.options.sanitizeJSON || !this.request.isSameOrigin())
        } catch (B) {
            this.request.dispatchException(B)
        }
    }, _getResponseJSON: function () {
        var A = this.request.options;
        if (!A.evalJSON || (A.evalJSON != "force" && !(this.getHeader("Content-type") || "").include("application/json")) || this.responseText.blank()) {
            return null
        }
        try {
            return this.responseText.evalJSON(A.sanitizeJSON || !this.request.isSameOrigin())
        } catch (B) {
            this.request.dispatchException(B)
        }
    }
});
Ajax.Updater = Class.create(Ajax.Request, {
    initialize: function ($super, A, C, B) {
        this.container = {success: (A.success || A), failure: (A.failure || (A.success ? null : A))};
        B = Object.clone(B);
        var D = B.onComplete;
        B.onComplete = (function (E, F) {
            this.updateContent(E.responseText);
            if (Object.isFunction(D)) {
                D(E, F)
            }
        }).bind(this);
        $super(C, B)
    }, updateContent: function (D) {
        var C = this.container[this.success() ? "success" : "failure"], A = this.options;
        if (!A.evalScripts) {
            D = D.stripScripts()
        }
        if (C = $$(C)) {
            if (A.insertion) {
                if (Object.isString(A.insertion)) {
                    var B = {};
                    B[A.insertion] = D;
                    C.insert(B)
                } else {
                    A.insertion(C, D)
                }
            } else {
                C.update(D)
            }
        }
    }
});
Ajax.PeriodicalUpdater = Class.create(Ajax.Base, {
    initialize: function ($super, A, C, B) {
        $super(B);
        this.onComplete = this.options.onComplete;
        this.frequency = (this.options.frequency || 2);
        this.decay = (this.options.decay || 1);
        this.updater = {};
        this.container = A;
        this.url = C;
        this.start()
    }, start: function () {
        this.options.onComplete = this.updateComplete.bind(this);
        this.onTimerEvent()
    }, stop: function () {
        this.updater.options.onComplete = undefined;
        clearTimeout(this.timer);
        (this.onComplete || Prototype.emptyFunction).apply(this, arguments)
    }, updateComplete: function (A) {
        if (this.options.decay) {
            this.decay = (A.responseText == this.lastText ? this.decay * this.options.decay : 1);
            this.lastText = A.responseText
        }
        this.timer = this.onTimerEvent.bind(this).delay(this.decay * this.frequency)
    }, onTimerEvent: function () {
        this.updater = new Ajax.Updater(this.container, this.url, this.options)
    }
});
function $$(B) {
    if (arguments.length > 1) {
        for (var A = 0, D = [], C = arguments.length; A < C; A++) {
            D.push($$(arguments[A]))
        }
        return D
    }
    if (Object.isString(B)) {
        B = document.getElementById(B)
    }
    return Element.extend(B)
}
if (Prototype.BrowserFeatures.XPath) {
    document._getElementsByXPath = function (F, A) {
        var C = [];
        var E = document.evaluate(F, $$(A) || document, null, XPathResult.ORDERED_NODE_SNAPSHOT_TYPE, null);
        for (var B = 0, D = E.snapshotLength; B < D; B++) {
            C.push(Element.extend(E.snapshotItem(B)))
        }
        return C
    }
}
if (!window.Node) {
    var Node = {}
}
if (!Node.ELEMENT_NODE) {
    Object.extend(Node, {
        ELEMENT_NODE: 1,
        ATTRIBUTE_NODE: 2,
        TEXT_NODE: 3,
        CDATA_SECTION_NODE: 4,
        ENTITY_REFERENCE_NODE: 5,
        ENTITY_NODE: 6,
        PROCESSING_INSTRUCTION_NODE: 7,
        COMMENT_NODE: 8,
        DOCUMENT_NODE: 9,
        DOCUMENT_TYPE_NODE: 10,
        DOCUMENT_FRAGMENT_NODE: 11,
        NOTATION_NODE: 12
    })
}
(function (C) {
    var B = (function () {
        var F = document.createElement("form");
        var E = document.createElement("input");
        var D = document.documentElement;
        E.setAttribute("name", "test");
        F.appendChild(E);
        D.appendChild(F);
        var G = F.elements ? (typeof F.elements.test == "undefined") : null;
        D.removeChild(F);
        F = E = null;
        return G
    })();
    var A = C.Element;
    C.Element = function (F, E) {
        E = E || {};
        F = F.toLowerCase();
        var D = Element.cache;
        if (B && E.name) {
            F = "<" + F + ' name="' + E.name + '">';
            delete E.name;
            return Element.writeAttribute(document.createElement(F), E)
        }
        if (!D[F]) {
            D[F] = Element.extend(document.createElement(F))
        }
        return Element.writeAttribute(D[F].cloneNode(false), E)
    };
    Object.extend(C.Element, A || {});
    if (A) {
        C.Element.prototype = A.prototype
    }
})(this);
Element.cache = {};
Element.idCounter = 1;
Element.Methods = {
    visible: function (A) {
        return $$(A).style.display != "none"
    }, toggle: function (A) {
        A = $$(A);
        Element[Element.visible(A) ? "hide" : "show"](A);
        return A
    }, hide: function (A) {
        A = $$(A);
        A.style.display = "none";
        return A
    }, show: function (A) {
        A = $$(A);
        A.style.display = "";
        return A
    }, remove: function (A) {
        A = $$(A);
        A.parentNode.removeChild(A);
        return A
    }, update: (function () {
        var B = (function () {
            var E = document.createElement("select"), F = true;
            E.innerHTML = '<option value="test">test</option>';
            if (E.options && E.options[0]) {
                F = E.options[0].nodeName.toUpperCase() !== "OPTION"
            }
            E = null;
            return F
        })();
        var A = (function () {
            try {
                var E = document.createElement("table");
                if (E && E.tBodies) {
                    E.innerHTML = "<tbody><tr><td>test</td></tr></tbody>";
                    var G = typeof E.tBodies[0] == "undefined";
                    E = null;
                    return G
                }
            } catch (F) {
                return true
            }
        })();
        var D = (function () {
            var E = document.createElement("script"), G = false;
            try {
                E.appendChild(document.createTextNode(""));
                G = !E.firstChild || E.firstChild && E.firstChild.nodeType !== 3
            } catch (F) {
                G = true
            }
            E = null;
            return G
        })();

        function C(F, G) {
            F = $$(F);
            if (G && G.toElement) {
                G = G.toElement()
            }
            if (Object.isElement(G)) {
                return F.update().insert(G)
            }
            G = Object.toHTML(G);
            var E = F.tagName.toUpperCase();
            if (E === "SCRIPT" && D) {
                F.text = G;
                return F
            }
            if (B || A) {
                if (E in Element._insertionTranslations.tags) {
                    while (F.firstChild) {
                        F.removeChild(F.firstChild)
                    }
                    Element._getContentFromAnonymousElement(E, G.stripScripts()).each(function (H) {
                        F.appendChild(H)
                    })
                } else {
                    F.innerHTML = G.stripScripts()
                }
            } else {
                F.innerHTML = G.stripScripts()
            }
            G.evalScripts.bind(G).defer();
            return F
        }

        return C
    })(), replace: function (B, C) {
        B = $$(B);
        if (C && C.toElement) {
            C = C.toElement()
        } else {
            if (!Object.isElement(C)) {
                C = Object.toHTML(C);
                var A = B.ownerDocument.createRange();
                A.selectNode(B);
                C.evalScripts.bind(C).defer();
                C = A.createContextualFragment(C.stripScripts())
            }
        }
        B.parentNode.replaceChild(C, B);
        return B
    }, insert: function (C, E) {
        C = $$(C);
        if (Object.isString(E) || Object.isNumber(E) || Object.isElement(E) || (E && (E.toElement || E.toHTML))) {
            E = {bottom: E}
        }
        var D, F, B, G;
        for (var A in E) {
            D = E[A];
            A = A.toLowerCase();
            F = Element._insertionTranslations[A];
            if (D && D.toElement) {
                D = D.toElement()
            }
            if (Object.isElement(D)) {
                F(C, D);
                continue
            }
            D = Object.toHTML(D);
            B = ((A == "before" || A == "after") ? C.parentNode : C).tagName.toUpperCase();
            G = Element._getContentFromAnonymousElement(B, D.stripScripts());
            if (A == "top" || A == "after") {
                G.reverse()
            }
            G.each(F.curry(C));
            D.evalScripts.bind(D).defer()
        }
        return C
    }, wrap: function (B, C, A) {
        B = $$(B);
        if (Object.isElement(C)) {
            $$(C).writeAttribute(A || {})
        } else {
            if (Object.isString(C)) {
                C = new Element(C, A)
            } else {
                C = new Element("div", C)
            }
        }
        if (B.parentNode) {
            B.parentNode.replaceChild(C, B)
        }
        C.appendChild(B);
        return C
    }, inspect: function (B) {
        B = $$(B);
        var A = "<" + B.tagName.toLowerCase();
        $H({id: "id", className: "class"}).each(function (F) {
            var E = F.first(), C = F.last();
            var D = (B[E] || "").toString();
            if (D) {
                A += " " + C + "=" + D.inspect(true)
            }
        });
        return A + ">"
    }, recursivelyCollect: function (A, C) {
        A = $$(A);
        var B = [];
        while (A = A[C]) {
            if (A.nodeType == 1) {
                B.push(Element.extend(A))
            }
        }
        return B
    }, ancestors: function (A) {
        return Element.recursivelyCollect(A, "parentNode")
    }, descendants: function (A) {
        return Element.select(A, "*")
    }, firstDescendant: function (A) {
        A = $$(A).firstChild;
        while (A && A.nodeType != 1) {
            A = A.nextSibling
        }
        return $$(A)
    }, immediateDescendants: function (A) {
        if (!(A = $$(A).firstChild)) {
            return []
        }
        while (A && A.nodeType != 1) {
            A = A.nextSibling
        }
        if (A) {
            return [A].concat($$(A).nextSiblings())
        }
        return []
    }, previousSiblings: function (A) {
        return Element.recursivelyCollect(A, "previousSibling")
    }, nextSiblings: function (A) {
        return Element.recursivelyCollect(A, "nextSibling")
    }, siblings: function (A) {
        A = $$(A);
        return Element.previousSiblings(A).reverse().concat(Element.nextSiblings(A))
    }, match: function (B, A) {
        if (Object.isString(A)) {
            A = new Selector(A)
        }
        return A.match($$(B))
    }, up: function (B, D, A) {
        B = $$(B);
        if (arguments.length == 1) {
            return $$(B.parentNode)
        }
        var C = Element.ancestors(B);
        return Object.isNumber(D) ? C[D] : Selector.findElement(C, D, A)
    }, down: function (B, C, A) {
        B = $$(B);
        if (arguments.length == 1) {
            return Element.firstDescendant(B)
        }
        return Object.isNumber(C) ? Element.descendants(B)[C] : Element.select(B, C)[A || 0]
    }, previous: function (B, D, A) {
        B = $$(B);
        if (arguments.length == 1) {
            return $$(Selector.handlers.previousElementSibling(B))
        }
        var C = Element.previousSiblings(B);
        return Object.isNumber(D) ? C[D] : Selector.findElement(C, D, A)
    }, next: function (C, D, B) {
        C = $$(C);
        if (arguments.length == 1) {
            return $$(Selector.handlers.nextElementSibling(C))
        }
        var A = Element.nextSiblings(C);
        return Object.isNumber(D) ? A[D] : Selector.findElement(A, D, B)
    }, select: function (B) {
        var A = Array.prototype.slice.call(arguments, 1);
        return Selector.findChildElements(B, A)
    }, adjacent: function (B) {
        var A = Array.prototype.slice.call(arguments, 1);
        return Selector.findChildElements(B.parentNode, A).without(B)
    }, identify: function (A) {
        A = $$(A);
        var B = Element.readAttribute(A, "id");
        if (B) {
            return B
        }
        do {
            B = "anonymous_element_" + Element.idCounter++
        } while ($$(B));
        Element.writeAttribute(A, "id", B);
        return B
    }, readAttribute: function (C, A) {
        C = $$(C);
        if (Prototype.Browser.IE) {
            var B = Element._attributeTranslations.read;
            if (B.values[A]) {
                return B.values[A](C, A)
            }
            if (B.names[A]) {
                A = B.names[A]
            }
            if (A.include(":")) {
                return (!C.attributes || !C.attributes[A]) ? null : C.attributes[A].value
            }
        }
        return C.getAttribute(A)
    }, writeAttribute: function (E, C, F) {
        E = $$(E);
        var B = {}, D = Element._attributeTranslations.write;
        if (typeof C == "object") {
            B = C
        } else {
            B[C] = Object.isUndefined(F) ? true : F
        }
        for (var A in B) {
            C = D.names[A] || A;
            F = B[A];
            if (D.values[A]) {
                C = D.values[A](E, F)
            }
            if (F === false || F === null) {
                E.removeAttribute(C)
            } else {
                if (F === true) {
                    E.setAttribute(C, C)
                } else {
                    E.setAttribute(C, F)
                }
            }
        }
        return E
    }, getHeight: function (A) {
        return Element.getDimensions(A).height
    }, getWidth: function (A) {
        return Element.getDimensions(A).width
    }, classNames: function (A) {
        return new Element.ClassNames(A)
    }, hasClassName: function (A, B) {
        if (!(A = $$(A))) {
            return
        }
        var C = A.className;
        return (C.length > 0 && (C == B || new RegExp("(^|\\s)" + B + "(\\s|$)").test(C)))
    }, addClassName: function (A, B) {
        if (!(A = $$(A))) {
            return
        }
        if (!Element.hasClassName(A, B)) {
            A.className += (A.className ? " " : "") + B
        }
        return A
    }, removeClassName: function (A, B) {
        if (!(A = $$(A))) {
            return
        }
        A.className = A.className.replace(new RegExp("(^|\\s+)" + B + "(\\s+|$)"), " ").strip();
        return A
    }, toggleClassName: function (A, B) {
        if (!(A = $$(A))) {
            return
        }
        return Element[Element.hasClassName(A, B) ? "removeClassName" : "addClassName"](A, B)
    }, cleanWhitespace: function (B) {
        B = $$(B);
        var C = B.firstChild;
        while (C) {
            var A = C.nextSibling;
            if (C.nodeType == 3 && !/\S/.test(C.nodeValue)) {
                B.removeChild(C)
            }
            C = A
        }
        return B
    }, empty: function (A) {
        return $$(A).innerHTML.blank()
    }, descendantOf: function (B, A) {
        B = $$(B), A = $$(A);
        if (B.compareDocumentPosition) {
            return (B.compareDocumentPosition(A) & 8) === 8
        }
        if (A.contains) {
            return A.contains(B) && A !== B
        }
        while (B = B.parentNode) {
            if (B == A) {
                return true
            }
        }
        return false
    }, scrollTo: function (A) {
        A = $$(A);
        var B = Element.cumulativeOffset(A);
        window.scrollTo(B[0], B[1]);
        return A
    }, getStyle: function (B, C) {
        B = $$(B);
        C = C == "float" ? "cssFloat" : C.camelize();
        var D = B.style[C];
        if (!D || D == "auto") {
            var A = document.defaultView.getComputedStyle(B, null);
            D = A ? A[C] : null
        }
        if (C == "opacity") {
            return D ? parseFloat(D) : 1
        }
        return D == "auto" ? null : D
    }, getOpacity: function (A) {
        return $$(A).getStyle("opacity")
    }, setStyle: function (B, C) {
        B = $$(B);
        var E = B.style, A;
        if (Object.isString(C)) {
            B.style.cssText += ";" + C;
            return C.include("opacity") ? B.setOpacity(C.match(/opacity:\s*(\d?\.?\d*)/)[1]) : B
        }
        for (var D in C) {
            if (D == "opacity") {
                B.setOpacity(C[D])
            } else {
                E[(D == "float" || D == "cssFloat") ? (Object.isUndefined(E.styleFloat) ? "cssFloat" : "styleFloat") : D] = C[D]
            }
        }
        return B
    }, setOpacity: function (A, B) {
        A = $$(A);
        A.style.opacity = (B == 1 || B === "") ? "" : (B < 0.00001) ? 0 : B;
        return A
    }, getDimensions: function (C) {
        C = $$(C);
        var G = Element.getStyle(C, "display");
        if (G != "none" && G != null) {
            return {width: C.offsetWidth, height: C.offsetHeight}
        }
        var B = C.style;
        var F = B.visibility;
        var D = B.position;
        var A = B.display;
        B.visibility = "hidden";
        if (D != "fixed") {
            B.position = "absolute"
        }
        B.display = "block";
        var H = C.clientWidth;
        var E = C.clientHeight;
        B.display = A;
        B.position = D;
        B.visibility = F;
        return {width: H, height: E}
    }, makePositioned: function (A) {
        A = $$(A);
        var B = Element.getStyle(A, "position");
        if (B == "static" || !B) {
            A._madePositioned = true;
            A.style.position = "relative";
            if (Prototype.Browser.Opera) {
                A.style.top = 0;
                A.style.left = 0
            }
        }
        return A
    }, undoPositioned: function (A) {
        A = $$(A);
        if (A._madePositioned) {
            A._madePositioned = undefined;
            A.style.position = A.style.top = A.style.left = A.style.bottom = A.style.right = ""
        }
        return A
    }, makeClipping: function (A) {
        A = $$(A);
        if (A._overflow) {
            return A
        }
        A._overflow = Element.getStyle(A, "overflow") || "auto";
        if (A._overflow !== "hidden") {
            A.style.overflow = "hidden"
        }
        return A
    }, undoClipping: function (A) {
        A = $$(A);
        if (!A._overflow) {
            return A
        }
        A.style.overflow = A._overflow == "auto" ? "" : A._overflow;
        A._overflow = null;
        return A
    }, cumulativeOffset: function (B) {
        var A = 0, C = 0;
        do {
            A += B.offsetTop || 0;
            C += B.offsetLeft || 0;
            B = B.offsetParent
        } while (B);
        return Element._returnOffset(C, A)
    }, positionedOffset: function (B) {
        var A = 0, D = 0;
        do {
            A += B.offsetTop || 0;
            D += B.offsetLeft || 0;
            B = B.offsetParent;
            if (B) {
                if (B.tagName.toUpperCase() == "BODY") {
                    break
                }
                var C = Element.getStyle(B, "position");
                if (C !== "static") {
                    break
                }
            }
        } while (B);
        return Element._returnOffset(D, A)
    }, absolutize: function (B) {
        B = $$(B);
        if (Element.getStyle(B, "position") == "absolute") {
            return B
        }
        var D = Element.positionedOffset(B);
        var F = D[1];
        var E = D[0];
        var C = B.clientWidth;
        var A = B.clientHeight;
        B._originalLeft = E - parseFloat(B.style.left || 0);
        B._originalTop = F - parseFloat(B.style.top || 0);
        B._originalWidth = B.style.width;
        B._originalHeight = B.style.height;
        B.style.position = "absolute";
        B.style.top = F + "px";
        B.style.left = E + "px";
        B.style.width = C + "px";
        B.style.height = A + "px";
        return B
    }, relativize: function (A) {
        A = $$(A);
        if (Element.getStyle(A, "position") == "relative") {
            return A
        }
        A.style.position = "relative";
        var C = parseFloat(A.style.top || 0) - (A._originalTop || 0);
        var B = parseFloat(A.style.left || 0) - (A._originalLeft || 0);
        A.style.top = C + "px";
        A.style.left = B + "px";
        A.style.height = A._originalHeight;
        A.style.width = A._originalWidth;
        return A
    }, cumulativeScrollOffset: function (B) {
        var A = 0, C = 0;
        do {
            A += B.scrollTop || 0;
            C += B.scrollLeft || 0;
            B = B.parentNode
        } while (B);
        return Element._returnOffset(C, A)
    }, getOffsetParent: function (A) {
        if (A.offsetParent) {
            return $$(A.offsetParent)
        }
        if (A == document.body) {
            return $$(A)
        }
        while ((A = A.parentNode) && A != document.body) {
            if (Element.getStyle(A, "position") != "static") {
                return $$(A)
            }
        }
        return $$(document.body)
    }, viewportOffset: function (D) {
        var A = 0, C = 0;
        var B = D;
        do {
            A += B.offsetTop || 0;
            C += B.offsetLeft || 0;
            if (B.offsetParent == document.body && Element.getStyle(B, "position") == "absolute") {
                break
            }
        } while (B = B.offsetParent);
        B = D;
        do {
            if (!Prototype.Browser.Opera || (B.tagName && (B.tagName.toUpperCase() == "BODY"))) {
                A -= B.scrollTop || 0;
                C -= B.scrollLeft || 0
            }
        } while (B = B.parentNode);
        return Element._returnOffset(C, A)
    }, clonePosition: function (B, D) {
        var A = Object.extend({
            setLeft: true,
            setTop: true,
            setWidth: true,
            setHeight: true,
            offsetTop: 0,
            offsetLeft: 0
        }, arguments[2] || {});
        D = $$(D);
        var E = Element.viewportOffset(D);
        B = $$(B);
        var F = [0, 0];
        var C = null;
        if (Element.getStyle(B, "position") == "absolute") {
            C = Element.getOffsetParent(B);
            F = Element.viewportOffset(C)
        }
        if (C == document.body) {
            F[0] -= document.body.offsetLeft;
            F[1] -= document.body.offsetTop
        }
        if (A.setLeft) {
            B.style.left = (E[0] - F[0] + A.offsetLeft) + "px"
        }
        if (A.setTop) {
            B.style.top = (E[1] - F[1] + A.offsetTop) + "px"
        }
        if (A.setWidth) {
            B.style.width = D.offsetWidth + "px"
        }
        if (A.setHeight) {
            B.style.height = D.offsetHeight + "px"
        }
        return B
    }
};
Object.extend(Element.Methods, {
    getElementsBySelector: Element.Methods.select,
    childElements: Element.Methods.immediateDescendants
});
Element._attributeTranslations = {write: {names: {className: "class", htmlFor: "for"}, values: {}}};
if (Prototype.Browser.Opera) {
    Element.Methods.getStyle = Element.Methods.getStyle.wrap(function (D, B, C) {
        switch (C) {
            case"left":
            case"top":
            case"right":
            case"bottom":
                if (D(B, "position") === "static") {
                    return null
                }
            case"height":
            case"width":
                if (!Element.visible(B)) {
                    return null
                }
                var E = parseInt(D(B, C), 10);
                if (E !== B["offset" + C.capitalize()]) {
                    return E + "px"
                }
                var A;
                if (C === "height") {
                    A = ["border-top-width", "padding-top", "padding-bottom", "border-bottom-width"]
                } else {
                    A = ["border-left-width", "padding-left", "padding-right", "border-right-width"]
                }
                return A.inject(E, function (F, G) {
                    var H = D(B, G);
                    return H === null ? F : F - parseInt(H, 10)
                }) + "px";
            default:
                return D(B, C)
        }
    });
    Element.Methods.readAttribute = Element.Methods.readAttribute.wrap(function (C, A, B) {
        if (B === "title") {
            return A.title
        }
        return C(A, B)
    })
} else {
    if (Prototype.Browser.IE) {
        Element.Methods.getOffsetParent = Element.Methods.getOffsetParent.wrap(function (C, B) {
            B = $$(B);
            try {
                B.offsetParent
            } catch (E) {
                return $$(document.body)
            }
            var A = B.getStyle("position");
            if (A !== "static") {
                return C(B)
            }
            B.setStyle({position: "relative"});
            var D = C(B);
            B.setStyle({position: A});
            return D
        });
        $w("positionedOffset viewportOffset").each(function (A) {
            Element.Methods[A] = Element.Methods[A].wrap(function (E, C) {
                C = $$(C);
                try {
                    C.offsetParent
                } catch (G) {
                    return Element._returnOffset(0, 0)
                }
                var B = C.getStyle("position");
                if (B !== "static") {
                    return E(C)
                }
                var D = C.getOffsetParent();
                if (D && D.getStyle("position") === "fixed") {
                    D.setStyle({zoom: 1})
                }
                C.setStyle({position: "relative"});
                var F = E(C);
                C.setStyle({position: B});
                return F
            })
        });
        Element.Methods.cumulativeOffset = Element.Methods.cumulativeOffset.wrap(function (B, A) {
            try {
                A.offsetParent
            } catch (C) {
                return Element._returnOffset(0, 0)
            }
            return B(A)
        });
        Element.Methods.getStyle = function (A, B) {
            A = $$(A);
            B = (B == "float" || B == "cssFloat") ? "styleFloat" : B.camelize();
            var C = A.style[B];
            if (!C && A.currentStyle) {
                C = A.currentStyle[B]
            }
            if (B == "opacity") {
                if (C = (A.getStyle("filter") || "").match(/alpha\(opacity=(.*)\)/)) {
                    if (C[1]) {
                        return parseFloat(C[1]) / 100
                    }
                }
                return 1
            }
            if (C == "auto") {
                if ((B == "width" || B == "height") && (A.getStyle("display") != "none")) {
                    return A["offset" + B.capitalize()] + "px"
                }
                return null
            }
            return C
        };
        Element.Methods.setOpacity = function (B, E) {
            function F(G) {
                return G.replace(/alpha\([^\)]*\)/gi, "")
            }

            B = $$(B);
            var A = B.currentStyle;
            if ((A && !A.hasLayout) || (!A && B.style.zoom == "normal")) {
                B.style.zoom = 1
            }
            var D = B.getStyle("filter"), C = B.style;
            if (E == 1 || E === "") {
                (D = F(D)) ? C.filter = D : C.removeAttribute("filter");
                return B
            } else {
                if (E < 0.00001) {
                    E = 0
                }
            }
            C.filter = F(D) + "alpha(opacity=" + (E * 100) + ")";
            return B
        };
        Element._attributeTranslations = (function () {
            var B = "className";
            var A = "for";
            var C = document.createElement("div");
            C.setAttribute(B, "x");
            if (C.className !== "x") {
                C.setAttribute("class", "x");
                if (C.className === "x") {
                    B = "class"
                }
            }
            C = null;
            C = document.createElement("label");
            C.setAttribute(A, "x");
            if (C.htmlFor !== "x") {
                C.setAttribute("htmlFor", "x");
                if (C.htmlFor === "x") {
                    A = "htmlFor"
                }
            }
            C = null;
            return {
                read: {
                    names: {"class": B, className: B, "for": A, htmlFor: A}, values: {
                        _getAttr: function (D, E) {
                            return D.getAttribute(E)
                        }, _getAttr2: function (D, E) {
                            return D.getAttribute(E, 2)
                        }, _getAttrNode: function (D, F) {
                            var E = D.getAttributeNode(F);
                            return E ? E.value : ""
                        }, _getEv: (function () {
                            var D = document.createElement("div");
                            D.onclick = Prototype.emptyFunction;
                            var F = D.getAttribute("onclick");
                            var E;
                            if (String(F).indexOf("{") > -1) {
                                E = function (G, H) {
                                    H = G.getAttribute(H);
                                    if (!H) {
                                        return null
                                    }
                                    H = H.toString();
                                    H = H.split("{")[1];
                                    H = H.split("}")[0];
                                    return H.strip()
                                }
                            } else {
                                if (F === "") {
                                    E = function (G, H) {
                                        H = G.getAttribute(H);
                                        if (!H) {
                                            return null
                                        }
                                        return H.strip()
                                    }
                                }
                            }
                            D = null;
                            return E
                        })(), _flag: function (D, E) {
                            return $$(D).hasAttribute(E) ? E : null
                        }, style: function (D) {
                            return D.style.cssText.toLowerCase()
                        }, title: function (D) {
                            return D.title
                        }
                    }
                }
            }
        })();
        Element._attributeTranslations.write = {
            names: Object.extend({
                cellpadding: "cellPadding",
                cellspacing: "cellSpacing"
            }, Element._attributeTranslations.read.names), values: {
                checked: function (A, B) {
                    A.checked = !!B
                }, style: function (A, B) {
                    A.style.cssText = B ? B : ""
                }
            }
        };
        Element._attributeTranslations.has = {};
        $w("colSpan rowSpan vAlign dateTime accessKey tabIndex encType maxLength readOnly longDesc frameBorder").each(function (A) {
            Element._attributeTranslations.write.names[A.toLowerCase()] = A;
            Element._attributeTranslations.has[A.toLowerCase()] = A
        });
        (function (A) {
            Object.extend(A, {
                href: A._getAttr2,
                src: A._getAttr2,
                type: A._getAttr,
                action: A._getAttrNode,
                disabled: A._flag,
                checked: A._flag,
                readonly: A._flag,
                multiple: A._flag,
                onload: A._getEv,
                onunload: A._getEv,
                onclick: A._getEv,
                ondblclick: A._getEv,
                onmousedown: A._getEv,
                onmouseup: A._getEv,
                onmouseover: A._getEv,
                onmousemove: A._getEv,
                onmouseout: A._getEv,
                onfocus: A._getEv,
                onblur: A._getEv,
                onkeypress: A._getEv,
                onkeydown: A._getEv,
                onkeyup: A._getEv,
                onsubmit: A._getEv,
                onreset: A._getEv,
                onselect: A._getEv,
                onchange: A._getEv
            })
        })(Element._attributeTranslations.read.values);
        if (Prototype.BrowserFeatures.ElementExtensions) {
            (function () {
                function A(E) {
                    var B = E.getElementsByTagName("*"), D = [];
                    for (var C = 0, F; F = B[C]; C++) {
                        if (F.tagName !== "!") {
                            D.push(F)
                        }
                    }
                    return D
                }

                Element.Methods.down = function (C, D, B) {
                    C = $$(C);
                    if (arguments.length == 1) {
                        return C.firstDescendant()
                    }
                    return Object.isNumber(D) ? A(C)[D] : Element.select(C, D)[B || 0]
                }
            })()
        }
    } else {
        if (Prototype.Browser.Gecko && /rv:1\.8\.0/.test(navigator.userAgent)) {
            Element.Methods.setOpacity = function (A, B) {
                A = $$(A);
                A.style.opacity = (B == 1) ? 0.999999 : (B === "") ? "" : (B < 0.00001) ? 0 : B;
                return A
            }
        } else {
            if (Prototype.Browser.WebKit) {
                Element.Methods.setOpacity = function (A, B) {
                    A = $$(A);
                    A.style.opacity = (B == 1 || B === "") ? "" : (B < 0.00001) ? 0 : B;
                    if (B == 1) {
                        if (A.tagName.toUpperCase() == "IMG" && A.width) {
                            A.width++;
                            A.width--
                        } else {
                            try {
                                var D = document.createTextNode(" ");
                                A.appendChild(D);
                                A.removeChild(D)
                            } catch (C) {
                            }
                        }
                    }
                    return A
                };
                Element.Methods.cumulativeOffset = function (B) {
                    var A = 0, C = 0;
                    do {
                        A += B.offsetTop || 0;
                        C += B.offsetLeft || 0;
                        if (B.offsetParent == document.body) {
                            if (Element.getStyle(B, "position") == "absolute") {
                                break
                            }
                        }
                        B = B.offsetParent
                    } while (B);
                    return Element._returnOffset(C, A)
                }
            }
        }
    }
}
if ("outerHTML" in document.documentElement) {
    Element.Methods.replace = function (C, E) {
        C = $$(C);
        if (E && E.toElement) {
            E = E.toElement()
        }
        if (Object.isElement(E)) {
            C.parentNode.replaceChild(E, C);
            return C
        }
        E = Object.toHTML(E);
        var D = C.parentNode, B = D.tagName.toUpperCase();
        if (Element._insertionTranslations.tags[B]) {
            var F = C.next();
            var A = Element._getContentFromAnonymousElement(B, E.stripScripts());
            D.removeChild(C);
            if (F) {
                A.each(function (G) {
                    D.insertBefore(G, F)
                })
            } else {
                A.each(function (G) {
                    D.appendChild(G)
                })
            }
        } else {
            C.outerHTML = E.stripScripts()
        }
        E.evalScripts.bind(E).defer();
        return C
    }
}
Element._returnOffset = function (B, C) {
    var A = [B, C];
    A.left = B;
    A.top = C;
    return A
};
Element._getContentFromAnonymousElement = function (C, B) {
    var D = new Element("div"), A = Element._insertionTranslations.tags[C];
    if (A) {
        D.innerHTML = A[0] + B + A[1];
        A[2].times(function () {
            D = D.firstChild
        })
    } else {
        D.innerHTML = B
    }
    return $A(D.childNodes)
};
Element._insertionTranslations = {
    before: function (A, B) {
        A.parentNode.insertBefore(B, A)
    },
    top: function (A, B) {
        A.insertBefore(B, A.firstChild)
    },
    bottom: function (A, B) {
        A.appendChild(B)
    },
    after: function (A, B) {
        A.parentNode.insertBefore(B, A.nextSibling)
    },
    tags: {
        TABLE: ["<table>", "</table>", 1],
        TBODY: ["<table><tbody>", "</tbody></table>", 2],
        TR: ["<table><tbody><tr>", "</tr></tbody></table>", 3],
        TD: ["<table><tbody><tr><td>", "</td></tr></tbody></table>", 4],
        SELECT: ["<select>", "</select>", 1]
    }
};
(function () {
    var A = Element._insertionTranslations.tags;
    Object.extend(A, {THEAD: A.TBODY, TFOOT: A.TBODY, TH: A.TD})
})();
Element.Methods.Simulated = {
    hasAttribute: function (A, C) {
        C = Element._attributeTranslations.has[C] || C;
        var B = $$(A).getAttributeNode(C);
        return !!(B && B.specified)
    }
};
Element.Methods.ByTag = {};
Object.extend(Element, Element.Methods);
(function (A) {
    if (!Prototype.BrowserFeatures.ElementExtensions && A.__proto__) {
        window.HTMLElement = {};
        window.HTMLElement.prototype = A.__proto__;
        Prototype.BrowserFeatures.ElementExtensions = true
    }
    A = null
})(document.createElement("div"));
Element.extend = (function () {
    function C(G) {
        if (typeof window.Element != "undefined") {
            var I = window.Element.prototype;
            if (I) {
                var K = "_" + (Math.random() + "").slice(2);
                var H = document.createElement(G);
                I[K] = "x";
                var J = (H[K] !== "x");
                delete I[K];
                H = null;
                return J
            }
        }
        return false
    }

    function B(H, G) {
        for (var J in G) {
            var I = G[J];
            if (Object.isFunction(I) && !(J in H)) {
                H[J] = I.methodize()
            }
        }
    }

    var D = C("object");
    if (Prototype.BrowserFeatures.SpecificElementExtensions) {
        if (D) {
            return function (H) {
                if (H && typeof H._extendedByPrototype == "undefined") {
                    var G = H.tagName;
                    if (G && (/^(?:object|applet|embed)$/i.test(G))) {
                        B(H, Element.Methods);
                        B(H, Element.Methods.Simulated);
                        B(H, Element.Methods.ByTag[G.toUpperCase()])
                    }
                }
                return H
            }
        }
        return Prototype.K
    }
    var A = {}, E = Element.Methods.ByTag;
    var F = Object.extend(function (I) {
        if (!I || typeof I._extendedByPrototype != "undefined" || I.nodeType != 1 || I == window) {
            return I
        }
        var G = Object.clone(A), H = I.tagName.toUpperCase();
        if (E[H]) {
            Object.extend(G, E[H])
        }
        B(I, G);
        I._extendedByPrototype = Prototype.emptyFunction;
        return I
    }, {
        refresh: function () {
            if (!Prototype.BrowserFeatures.ElementExtensions) {
                Object.extend(A, Element.Methods);
                Object.extend(A, Element.Methods.Simulated)
            }
        }
    });
    F.refresh();
    return F
})();
Element.hasAttribute = function (A, B) {
    if (A.hasAttribute) {
        return A.hasAttribute(B)
    }
    return Element.Methods.Simulated.hasAttribute(A, B)
};
Element.addMethods = function (C) {
    var J = Prototype.BrowserFeatures, D = Element.Methods.ByTag;
    if (!C) {
        Object.extend(Form, Form.Methods);
        Object.extend(Form.Element, Form.Element.Methods);
        Object.extend(Element.Methods.ByTag, {
            FORM: Object.clone(Form.Methods),
            INPUT: Object.clone(Form.Element.Methods),
            SELECT: Object.clone(Form.Element.Methods),
            TEXTAREA: Object.clone(Form.Element.Methods)
        })
    }
    if (arguments.length == 2) {
        var B = C;
        C = arguments[1]
    }
    if (!B) {
        Object.extend(Element.Methods, C || {})
    } else {
        if (Object.isArray(B)) {
            B.each(H)
        } else {
            H(B)
        }
    }
    function H(F) {
        F = F.toUpperCase();
        if (!Element.Methods.ByTag[F]) {
            Element.Methods.ByTag[F] = {}
        }
        Object.extend(Element.Methods.ByTag[F], C)
    }

    function A(M, L, F) {
        F = F || false;
        for (var O in M) {
            var N = M[O];
            if (!Object.isFunction(N)) {
                continue
            }
            if (!F || !(O in L)) {
                L[O] = N.methodize()
            }
        }
    }

    function E(N) {
        var F;
        var M = {
            OPTGROUP: "OptGroup",
            TEXTAREA: "TextArea",
            P: "Paragraph",
            FIELDSET: "FieldSet",
            UL: "UList",
            OL: "OList",
            DL: "DList",
            DIR: "Directory",
            H1: "Heading",
            H2: "Heading",
            H3: "Heading",
            H4: "Heading",
            H5: "Heading",
            H6: "Heading",
            Q: "Quote",
            INS: "Mod",
            DEL: "Mod",
            A: "Anchor",
            IMG: "Image",
            CAPTION: "TableCaption",
            COL: "TableCol",
            COLGROUP: "TableCol",
            THEAD: "TableSection",
            TFOOT: "TableSection",
            TBODY: "TableSection",
            TR: "TableRow",
            TH: "TableCell",
            TD: "TableCell",
            FRAMESET: "FrameSet",
            IFRAME: "IFrame"
        };
        if (M[N]) {
            F = "HTML" + M[N] + "Element"
        }
        if (window[F]) {
            return window[F]
        }
        F = "HTML" + N + "Element";
        if (window[F]) {
            return window[F]
        }
        F = "HTML" + N.capitalize() + "Element";
        if (window[F]) {
            return window[F]
        }
        var L = document.createElement(N);
        var O = L.__proto__ || L.constructor.prototype;
        L = null;
        return O
    }

    var I = window.HTMLElement ? HTMLElement.prototype : Element.prototype;
    if (J.ElementExtensions) {
        A(Element.Methods, I);
        A(Element.Methods.Simulated, I, true)
    }
    if (J.SpecificElementExtensions) {
        for (var K in Element.Methods.ByTag) {
            var G = E(K);
            if (Object.isUndefined(G)) {
                continue
            }
            A(D[K], G.prototype)
        }
    }
    Object.extend(Element, Element.Methods);
    delete Element.ByTag;
    if (Element.extend.refresh) {
        Element.extend.refresh()
    }
    Element.cache = {}
};
document.viewport = {
    getDimensions: function () {
        return {width: this.getWidth(), height: this.getHeight()}
    }, getScrollOffsets: function () {
        return Element._returnOffset(window.pageXOffset || document.documentElement.scrollLeft || document.body.scrollLeft, window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop)
    }
};
(function (C) {
    var H = Prototype.Browser, F = document, D, E = {};

    function A() {
        if (H.WebKit && !F.evaluate) {
            return document
        }
        if (H.Opera && window.parseFloat(window.opera.version()) < 9.5) {
            return document.body
        }
        return document.documentElement
    }

    function G(B) {
        if (!D) {
            D = A()
        }
        E[B] = "client" + B;
        C["get" + B] = function () {
            return D[E[B]]
        };
        return C["get" + B]()
    }

    C.getWidth = G.curry("Width");
    C.getHeight = G.curry("Height")
})(document.viewport);
Element.Storage = {UID: 1};
Element.addMethods({
    getStorage: function (B) {
        if (!(B = $$(B))) {
            return
        }
        var A;
        if (B === window) {
            A = 0
        } else {
            if (typeof B._prototypeUID === "undefined") {
                B._prototypeUID = [Element.Storage.UID++]
            }
            A = B._prototypeUID[0]
        }
        if (!Element.Storage[A]) {
            Element.Storage[A] = $H()
        }
        return Element.Storage[A]
    }, store: function (B, A, C) {
        if (!(B = $$(B))) {
            return
        }
        if (arguments.length === 2) {
            Element.getStorage(B).update(A)
        } else {
            Element.getStorage(B).set(A, C)
        }
        return B
    }, retrieve: function (C, B, A) {
        if (!(C = $$(C))) {
            return
        }
        var E = Element.getStorage(C), D = E.get(B);
        if (Object.isUndefined(D)) {
            E.set(B, A);
            D = A
        }
        return D
    }, clone: function (C, A) {
        if (!(C = $$(C))) {
            return
        }
        var E = C.cloneNode(A);
        E._prototypeUID = void 0;
        if (A) {
            var D = Element.select(E, "*"), B = D.length;
            while (B--) {
                D[B]._prototypeUID = void 0
            }
        }
        return Element.extend(E)
    }
});
var Selector = Class.create({
    initialize: function (A) {
        this.expression = A.strip();
        if (this.shouldUseSelectorsAPI()) {
            this.mode = "selectorsAPI"
        } else {
            if (this.shouldUseXPath()) {
                this.mode = "xpath";
                this.compileXPathMatcher()
            } else {
                this.mode = "normal";
                this.compileMatcher()
            }
        }
    }, shouldUseXPath: (function () {
        var A = (function () {
            var E = false;
            if (document.evaluate && window.XPathResult) {
                var D = document.createElement("div");
                D.innerHTML = "<ul><li></li></ul><div><ul><li></li></ul></div>";
                var C = ".//*[local-name()='ul' or local-name()='UL']//*[local-name()='li' or local-name()='LI']";
                var B = document.evaluate(C, D, null, XPathResult.ORDERED_NODE_SNAPSHOT_TYPE, null);
                E = (B.snapshotLength !== 2);
                D = null
            }
            return E
        })();
        return function () {
            if (!Prototype.BrowserFeatures.XPath) {
                return false
            }
            var B = this.expression;
            if (Prototype.Browser.WebKit && (B.include("-of-type") || B.include(":empty"))) {
                return false
            }
            if ((/(\[[\w-]*?:|:checked)/).test(B)) {
                return false
            }
            if (A) {
                return false
            }
            return true
        }
    })(), shouldUseSelectorsAPI: function () {
        if (!Prototype.BrowserFeatures.SelectorsAPI) {
            return false
        }
        if (Selector.CASE_INSENSITIVE_CLASS_NAMES) {
            return false
        }
        if (!Selector._div) {
            Selector._div = new Element("div")
        }
        try {
            Selector._div.querySelector(this.expression)
        } catch (A) {
            return false
        }
        return true
    }, compileMatcher: function () {
        var e = this.expression, ps = Selector.patterns, h = Selector.handlers, c = Selector.criteria, le, p, m, len = ps.length, name;
        if (Selector._cache[e]) {
            this.matcher = Selector._cache[e];
            return
        }
        this.matcher = ["this.matcher = function(root) {", "var r = root, h = Selector.handlers, c = false, n;"];
        while (e && le != e && (/\S/).test(e)) {
            le = e;
            for (var i = 0; i < len; i++) {
                p = ps[i].re;
                name = ps[i].name;
                if (m = e.match(p)) {
                    this.matcher.push(Object.isFunction(c[name]) ? c[name](m) : new Template(c[name]).evaluate(m));
                    e = e.replace(m[0], "");
                    break
                }
            }
        }
        this.matcher.push("return h.unique(n);\n}");
        eval(this.matcher.join("\n"));
        Selector._cache[this.expression] = this.matcher
    }, compileXPathMatcher: function () {
        var G = this.expression, H = Selector.patterns, C = Selector.xpath, F, B, A = H.length, D;
        if (Selector._cache[G]) {
            this.xpath = Selector._cache[G];
            return
        }
        this.matcher = [".//*"];
        while (G && F != G && (/\S/).test(G)) {
            F = G;
            for (var E = 0; E < A; E++) {
                D = H[E].name;
                if (B = G.match(H[E].re)) {
                    this.matcher.push(Object.isFunction(C[D]) ? C[D](B) : new Template(C[D]).evaluate(B));
                    G = G.replace(B[0], "");
                    break
                }
            }
        }
        this.xpath = this.matcher.join("");
        Selector._cache[this.expression] = this.xpath
    }, findElements: function (A) {
        A = A || document;
        var C = this.expression, B;
        switch (this.mode) {
            case"selectorsAPI":
                if (A !== document) {
                    var D = A.id, E = $$(A).identify();
                    E = E.replace(/([\.:])/g, "\\$1");
                    C = "#" + E + " " + C
                }
                B = $A(A.querySelectorAll(C)).map(Element.extend);
                A.id = D;
                return B;
            case"xpath":
                return document._getElementsByXPath(this.xpath, A);
            default:
                return this.matcher(A)
        }
    }, match: function (I) {
        this.tokens = [];
        var M = this.expression, A = Selector.patterns, E = Selector.assertions;
        var B, D, F, L = A.length, C;
        while (M && B !== M && (/\S/).test(M)) {
            B = M;
            for (var H = 0; H < L; H++) {
                D = A[H].re;
                C = A[H].name;
                if (F = M.match(D)) {
                    if (E[C]) {
                        this.tokens.push([C, Object.clone(F)]);
                        M = M.replace(F[0], "")
                    } else {
                        return this.findElements(document).include(I)
                    }
                }
            }
        }
        var K = true, C, J;
        for (var H = 0, G; G = this.tokens[H]; H++) {
            C = G[0], J = G[1];
            if (!Selector.assertions[C](I, J)) {
                K = false;
                break
            }
        }
        return K
    }, toString: function () {
        return this.expression
    }, inspect: function () {
        return "#<Selector:" + this.expression.inspect() + ">"
    }
});
if (Prototype.BrowserFeatures.SelectorsAPI && document.compatMode === "BackCompat") {
    Selector.CASE_INSENSITIVE_CLASS_NAMES = (function () {
        var C = document.createElement("div"), A = document.createElement("span");
        C.id = "prototype_test_id";
        A.className = "Test";
        C.appendChild(A);
        var B = (C.querySelector("#prototype_test_id .test") !== null);
        C = A = null;
        return B
    })()
}
Object.extend(Selector, {
    _cache: {},
    xpath: {
        descendant: "//*",
        child: "/*",
        adjacent: "/following-sibling::*[1]",
        laterSibling: "/following-sibling::*",
        tagName: function (A) {
            if (A[1] == "*") {
                return ""
            }
            return "[local-name()='" + A[1].toLowerCase() + "' or local-name()='" + A[1].toUpperCase() + "']"
        },
        className: "[contains(concat(' ', @class, ' '), ' #{1} ')]",
        id: "[@id='#{1}']",
        attrPresence: function (A) {
            A[1] = A[1].toLowerCase();
            return new Template("[@#{1}]").evaluate(A)
        },
        attr: function (A) {
            A[1] = A[1].toLowerCase();
            A[3] = A[5] || A[6];
            return new Template(Selector.xpath.operators[A[2]]).evaluate(A)
        },
        pseudo: function (A) {
            var B = Selector.xpath.pseudos[A[1]];
            if (!B) {
                return ""
            }
            if (Object.isFunction(B)) {
                return B(A)
            }
            return new Template(Selector.xpath.pseudos[A[1]]).evaluate(A)
        },
        operators: {
            "=": "[@#{1}='#{3}']",
            "!=": "[@#{1}!='#{3}']",
            "^=": "[starts-with(@#{1}, '#{3}')]",
            "$=": "[substring(@#{1}, (string-length(@#{1}) - string-length('#{3}') + 1))='#{3}']",
            "*=": "[contains(@#{1}, '#{3}')]",
            "~=": "[contains(concat(' ', @#{1}, ' '), ' #{3} ')]",
            "|=": "[contains(concat('-', @#{1}, '-'), '-#{3}-')]"
        },
        pseudos: {
            "first-child": "[not(preceding-sibling::*)]",
            "last-child": "[not(following-sibling::*)]",
            "only-child": "[not(preceding-sibling::* or following-sibling::*)]",
            empty: "[count(*) = 0 and (count(text()) = 0)]",
            checked: "[@checked]",
            disabled: "[(@disabled) and (@type!='hidden')]",
            enabled: "[not(@disabled) and (@type!='hidden')]",
            not: function (E) {
                var H = E[6], C = Selector.patterns, I = Selector.xpath, A, J, G = C.length, B;
                var D = [];
                while (H && A != H && (/\S/).test(H)) {
                    A = H;
                    for (var F = 0; F < G; F++) {
                        B = C[F].name;
                        if (E = H.match(C[F].re)) {
                            J = Object.isFunction(I[B]) ? I[B](E) : new Template(I[B]).evaluate(E);
                            D.push("(" + J.substring(1, J.length - 1) + ")");
                            H = H.replace(E[0], "");
                            break
                        }
                    }
                }
                return "[not(" + D.join(" and ") + ")]"
            },
            "nth-child": function (A) {
                return Selector.xpath.pseudos.nth("(count(./preceding-sibling::*) + 1) ", A)
            },
            "nth-last-child": function (A) {
                return Selector.xpath.pseudos.nth("(count(./following-sibling::*) + 1) ", A)
            },
            "nth-of-type": function (A) {
                return Selector.xpath.pseudos.nth("position() ", A)
            },
            "nth-last-of-type": function (A) {
                return Selector.xpath.pseudos.nth("(last() + 1 - position()) ", A)
            },
            "first-of-type": function (A) {
                A[6] = "1";
                return Selector.xpath.pseudos["nth-of-type"](A)
            },
            "last-of-type": function (A) {
                A[6] = "1";
                return Selector.xpath.pseudos["nth-last-of-type"](A)
            },
            "only-of-type": function (A) {
                var B = Selector.xpath.pseudos;
                return B["first-of-type"](A) + B["last-of-type"](A)
            },
            nth: function (E, C) {
                var F, G = C[6], B;
                if (G == "even") {
                    G = "2n+0"
                }
                if (G == "odd") {
                    G = "2n+1"
                }
                if (F = G.match(/^(\d+)$/)) {
                    return "[" + E + "= " + F[1] + "]"
                }
                if (F = G.match(/^(-?\d*)?n(([+-])(\d+))?/)) {
                    if (F[1] == "-") {
                        F[1] = -1
                    }
                    var D = F[1] ? Number(F[1]) : 1;
                    var A = F[2] ? Number(F[2]) : 0;
                    B = "[((#{fragment} - #{b}) mod #{a} = 0) and ((#{fragment} - #{b}) div #{a} >= 0)]";
                    return new Template(B).evaluate({fragment: E, a: D, b: A})
                }
            }
        }
    },
    criteria: {
        tagName: 'n = h.tagName(n, r, "#{1}", c);      c = false;',
        className: 'n = h.className(n, r, "#{1}", c);    c = false;',
        id: 'n = h.id(n, r, "#{1}", c);           c = false;',
        attrPresence: 'n = h.attrPresence(n, r, "#{1}", c); c = false;',
        attr: function (A) {
            A[3] = (A[5] || A[6]);
            return new Template('n = h.attr(n, r, "#{1}", "#{3}", "#{2}", c); c = false;').evaluate(A)
        },
        pseudo: function (A) {
            if (A[6]) {
                A[6] = A[6].replace(/"/g, '\\"')
            }
            return new Template('n = h.pseudo(n, "#{1}", "#{6}", r, c); c = false;').evaluate(A)
        },
        descendant: 'c = "descendant";',
        child: 'c = "child";',
        adjacent: 'c = "adjacent";',
        laterSibling: 'c = "laterSibling";'
    },
    patterns: [{name: "laterSibling", re: /^\s*~\s*/}, {name: "child", re: /^\s*>\s*/}, {
        name: "adjacent",
        re: /^\s*\+\s*/
    }, {name: "descendant", re: /^\s/}, {name: "tagName", re: /^\s*(\*|[\w\-]+)(\b|$)?/}, {
        name: "id",
        re: /^#([\w\-\*]+)(\b|$)/
    }, {name: "className", re: /^\.([\w\-\*]+)(\b|$)/}, {
        name: "pseudo",
        re: /^:((first|last|nth|nth-last|only)(-child|-of-type)|empty|checked|(en|dis)abled|not)(\((.*?)\))?(\b|$|(?=\s|[:+~>]))/
    }, {name: "attrPresence", re: /^\[((?:[\w-]+:)?[\w-]+)\]/}, {
        name: "attr",
        re: /\[((?:[\w-]*:)?[\w-]+)\s*(?:([!^$*~|]?=)\s*((['"])([^\4]*?)\4|([^'"][^\]]*?)))?\]/
    }],
    assertions: {
        tagName: function (A, B) {
            return B[1].toUpperCase() == A.tagName.toUpperCase()
        }, className: function (A, B) {
            return Element.hasClassName(A, B[1])
        }, id: function (A, B) {
            return A.id === B[1]
        }, attrPresence: function (A, B) {
            return Element.hasAttribute(A, B[1])
        }, attr: function (B, C) {
            var A = Element.readAttribute(B, C[1]);
            return A && Selector.operators[C[2]](A, C[5] || C[6])
        }
    },
    handlers: {
        concat: function (B, A) {
            for (var C = 0, D; D = A[C]; C++) {
                B.push(D)
            }
            return B
        }, mark: function (A) {
            var D = Prototype.emptyFunction;
            for (var B = 0, C; C = A[B]; B++) {
                C._countedByPrototype = D
            }
            return A
        }, unmark: (function () {
            var A = (function () {
                var B = document.createElement("div"), E = false, D = "_countedByPrototype", C = "x";
                B[D] = C;
                E = (B.getAttribute(D) === C);
                B = null;
                return E
            })();
            return A ? function (B) {
                for (var C = 0, D; D = B[C]; C++) {
                    D.removeAttribute("_countedByPrototype")
                }
                return B
            } : function (B) {
                for (var C = 0, D; D = B[C]; C++) {
                    D._countedByPrototype = void 0
                }
                return B
            }
        })(), index: function (A, D, G) {
            A._countedByPrototype = Prototype.emptyFunction;
            if (D) {
                for (var B = A.childNodes, E = B.length - 1, C = 1; E >= 0; E--) {
                    var F = B[E];
                    if (F.nodeType == 1 && (!G || F._countedByPrototype)) {
                        F.nodeIndex = C++
                    }
                }
            } else {
                for (var E = 0, C = 1, B = A.childNodes; F = B[E]; E++) {
                    if (F.nodeType == 1 && (!G || F._countedByPrototype)) {
                        F.nodeIndex = C++
                    }
                }
            }
        }, unique: function (B) {
            if (B.length == 0) {
                return B
            }
            var D = [], E;
            for (var C = 0, A = B.length; C < A; C++) {
                if (typeof (E = B[C])._countedByPrototype == "undefined") {
                    E._countedByPrototype = Prototype.emptyFunction;
                    D.push(Element.extend(E))
                }
            }
            return Selector.handlers.unmark(D)
        }, descendant: function (A) {
            var D = Selector.handlers;
            for (var C = 0, B = [], E; E = A[C]; C++) {
                D.concat(B, E.getElementsByTagName("*"))
            }
            return B
        }, child: function (A) {
            var E = Selector.handlers;
            for (var D = 0, C = [], F; F = A[D]; D++) {
                for (var B = 0, G; G = F.childNodes[B]; B++) {
                    if (G.nodeType == 1 && G.tagName != "!") {
                        C.push(G)
                    }
                }
            }
            return C
        }, adjacent: function (A) {
            for (var C = 0, B = [], E; E = A[C]; C++) {
                var D = this.nextElementSibling(E);
                if (D) {
                    B.push(D)
                }
            }
            return B
        }, laterSibling: function (A) {
            var D = Selector.handlers;
            for (var C = 0, B = [], E; E = A[C]; C++) {
                D.concat(B, Element.nextSiblings(E))
            }
            return B
        }, nextElementSibling: function (A) {
            while (A = A.nextSibling) {
                if (A.nodeType == 1) {
                    return A
                }
            }
            return null
        }, previousElementSibling: function (A) {
            while (A = A.previousSibling) {
                if (A.nodeType == 1) {
                    return A
                }
            }
            return null
        }, tagName: function (A, H, C, B) {
            var I = C.toUpperCase();
            var E = [], G = Selector.handlers;
            if (A) {
                if (B) {
                    if (B == "descendant") {
                        for (var F = 0, D; D = A[F]; F++) {
                            G.concat(E, D.getElementsByTagName(C))
                        }
                        return E
                    } else {
                        A = this[B](A)
                    }
                    if (C == "*") {
                        return A
                    }
                }
                for (var F = 0, D; D = A[F]; F++) {
                    if (D.tagName.toUpperCase() === I) {
                        E.push(D)
                    }
                }
                return E
            } else {
                return H.getElementsByTagName(C)
            }
        }, id: function (A, I, B, C) {
            var H = $$(B), G = Selector.handlers;
            if (I == document) {
                if (!H) {
                    return []
                }
                if (!A) {
                    return [H]
                }
            } else {
                if (!I.sourceIndex || I.sourceIndex < 1) {
                    var A = I.getElementsByTagName("*");
                    for (var E = 0, D; D = A[E]; E++) {
                        if (D.id === B) {
                            return [D]
                        }
                    }
                }
            }
            if (A) {
                if (C) {
                    if (C == "child") {
                        for (var F = 0, D; D = A[F]; F++) {
                            if (H.parentNode == D) {
                                return [H]
                            }
                        }
                    } else {
                        if (C == "descendant") {
                            for (var F = 0, D; D = A[F]; F++) {
                                if (Element.descendantOf(H, D)) {
                                    return [H]
                                }
                            }
                        } else {
                            if (C == "adjacent") {
                                for (var F = 0, D; D = A[F]; F++) {
                                    if (Selector.handlers.previousElementSibling(H) == D) {
                                        return [H]
                                    }
                                }
                            } else {
                                A = G[C](A)
                            }
                        }
                    }
                }
                for (var F = 0, D; D = A[F]; F++) {
                    if (D == H) {
                        return [H]
                    }
                }
                return []
            }
            return (H && Element.descendantOf(H, I)) ? [H] : []
        }, className: function (B, A, C, D) {
            if (B && D) {
                B = this[D](B)
            }
            return Selector.handlers.byClassName(B, A, C)
        }, byClassName: function (C, B, F) {
            if (!C) {
                C = Selector.handlers.descendant([B])
            }
            var H = " " + F + " ";
            for (var E = 0, D = [], G, A; G = C[E]; E++) {
                A = G.className;
                if (A.length == 0) {
                    continue
                }
                if (A == F || (" " + A + " ").include(H)) {
                    D.push(G)
                }
            }
            return D
        }, attrPresence: function (C, B, A, G) {
            if (!C) {
                C = B.getElementsByTagName("*")
            }
            if (C && G) {
                C = this[G](C)
            }
            var E = [];
            for (var D = 0, F; F = C[D]; D++) {
                if (Element.hasAttribute(F, A)) {
                    E.push(F)
                }
            }
            return E
        }, attr: function (A, I, H, J, C, B) {
            if (!A) {
                A = I.getElementsByTagName("*")
            }
            if (A && B) {
                A = this[B](A)
            }
            var K = Selector.operators[C], F = [];
            for (var E = 0, D; D = A[E]; E++) {
                var G = Element.readAttribute(D, H);
                if (G === null) {
                    continue
                }
                if (K(G, J)) {
                    F.push(D)
                }
            }
            return F
        }, pseudo: function (B, C, E, A, D) {
            if (B && D) {
                B = this[D](B)
            }
            if (!B) {
                B = A.getElementsByTagName("*")
            }
            return Selector.pseudos[C](B, E, A)
        }
    },
    pseudos: {
        "first-child": function (B, F, A) {
            for (var D = 0, C = [], E; E = B[D]; D++) {
                if (Selector.handlers.previousElementSibling(E)) {
                    continue
                }
                C.push(E)
            }
            return C
        }, "last-child": function (B, F, A) {
            for (var D = 0, C = [], E; E = B[D]; D++) {
                if (Selector.handlers.nextElementSibling(E)) {
                    continue
                }
                C.push(E)
            }
            return C
        }, "only-child": function (B, G, A) {
            var E = Selector.handlers;
            for (var D = 0, C = [], F; F = B[D]; D++) {
                if (!E.previousElementSibling(F) && !E.nextElementSibling(F)) {
                    C.push(F)
                }
            }
            return C
        }, "nth-child": function (B, C, A) {
            return Selector.pseudos.nth(B, C, A)
        }, "nth-last-child": function (B, C, A) {
            return Selector.pseudos.nth(B, C, A, true)
        }, "nth-of-type": function (B, C, A) {
            return Selector.pseudos.nth(B, C, A, false, true)
        }, "nth-last-of-type": function (B, C, A) {
            return Selector.pseudos.nth(B, C, A, true, true)
        }, "first-of-type": function (B, C, A) {
            return Selector.pseudos.nth(B, "1", A, false, true)
        }, "last-of-type": function (B, C, A) {
            return Selector.pseudos.nth(B, "1", A, true, true)
        }, "only-of-type": function (B, D, A) {
            var C = Selector.pseudos;
            return C["last-of-type"](C["first-of-type"](B, D, A), D, A)
        }, getIndices: function (B, A, C) {
            if (B == 0) {
                return A > 0 ? [A] : []
            }
            return $R(1, C).inject([], function (D, E) {
                if (0 == (E - A) % B && (E - A) / B >= 0) {
                    D.push(E)
                }
                return D
            })
        }, nth: function (A, L, N, K, C) {
            if (A.length == 0) {
                return []
            }
            if (L == "even") {
                L = "2n+0"
            }
            if (L == "odd") {
                L = "2n+1"
            }
            var J = Selector.handlers, I = [], B = [], E;
            J.mark(A);
            for (var H = 0, D; D = A[H]; H++) {
                if (!D.parentNode._countedByPrototype) {
                    J.index(D.parentNode, K, C);
                    B.push(D.parentNode)
                }
            }
            if (L.match(/^\d+$/)) {
                L = Number(L);
                for (var H = 0, D; D = A[H]; H++) {
                    if (D.nodeIndex == L) {
                        I.push(D)
                    }
                }
            } else {
                if (E = L.match(/^(-?\d*)?n(([+-])(\d+))?/)) {
                    if (E[1] == "-") {
                        E[1] = -1
                    }
                    var O = E[1] ? Number(E[1]) : 1;
                    var M = E[2] ? Number(E[2]) : 0;
                    var P = Selector.pseudos.getIndices(O, M, A.length);
                    for (var H = 0, D, F = P.length; D = A[H]; H++) {
                        for (var G = 0; G < F; G++) {
                            if (D.nodeIndex == P[G]) {
                                I.push(D)
                            }
                        }
                    }
                }
            }
            J.unmark(A);
            J.unmark(B);
            return I
        }, empty: function (B, F, A) {
            for (var D = 0, C = [], E; E = B[D]; D++) {
                if (E.tagName == "!" || E.firstChild) {
                    continue
                }
                C.push(E)
            }
            return C
        }, not: function (A, D, I) {
            var G = Selector.handlers, J, C;
            var H = new Selector(D).findElements(I);
            G.mark(H);
            for (var F = 0, E = [], B; B = A[F]; F++) {
                if (!B._countedByPrototype) {
                    E.push(B)
                }
            }
            G.unmark(H);
            return E
        }, enabled: function (B, F, A) {
            for (var D = 0, C = [], E; E = B[D]; D++) {
                if (!E.disabled && (!E.type || E.type !== "hidden")) {
                    C.push(E)
                }
            }
            return C
        }, disabled: function (B, F, A) {
            for (var D = 0, C = [], E; E = B[D]; D++) {
                if (E.disabled) {
                    C.push(E)
                }
            }
            return C
        }, checked: function (B, F, A) {
            for (var D = 0, C = [], E; E = B[D]; D++) {
                if (E.checked) {
                    C.push(E)
                }
            }
            return C
        }
    },
    operators: {
        "=": function (B, A) {
            return B == A
        }, "!=": function (B, A) {
            return B != A
        }, "^=": function (B, A) {
            return B == A || B && B.startsWith(A)
        }, "$=": function (B, A) {
            return B == A || B && B.endsWith(A)
        }, "*=": function (B, A) {
            return B == A || B && B.include(A)
        }, "~=": function (B, A) {
            return (" " + B + " ").include(" " + A + " ")
        }, "|=": function (B, A) {
            return ("-" + (B || "").toUpperCase() + "-").include("-" + (A || "").toUpperCase() + "-")
        }
    },
    split: function (B) {
        var A = [];
        B.scan(/(([\w#:.~>+()\s-]+|\*|\[.*?\])+)\s*(,|$)/, function (C) {
            A.push(C[1].strip())
        });
        return A
    },
    matchElements: function (F, G) {
        var E = $$$(G), D = Selector.handlers;
        D.mark(E);
        for (var C = 0, B = [], A; A = F[C]; C++) {
            if (A._countedByPrototype) {
                B.push(A)
            }
        }
        D.unmark(E);
        return B
    },
    findElement: function (B, C, A) {
        if (Object.isNumber(C)) {
            A = C;
            C = false
        }
        return Selector.matchElements(B, C || "*")[A || 0]
    },
    findChildElements: function (E, G) {
        G = Selector.split(G.join(","));
        var D = [], F = Selector.handlers;
        for (var C = 0, B = G.length, A; C < B; C++) {
            A = new Selector(G[C].strip());
            F.concat(D, A.findElements(E))
        }
        return (B > 1) ? F.unique(D) : D
    }
});
if (Prototype.Browser.IE) {
    Object.extend(Selector.handlers, {
        concat: function (B, A) {
            for (var C = 0, D; D = A[C]; C++) {
                if (D.tagName !== "!") {
                    B.push(D)
                }
            }
            return B
        }
    })
}
function $$$() {
    return Selector.findChildElements(document, $A(arguments))
}
var Form = {
    reset: function (A) {
        A = $$(A);
        A.reset();
        return A
    }, serializeElements: function (G, B) {
        if (typeof B != "object") {
            B = {hash: !!B}
        } else {
            if (Object.isUndefined(B.hash)) {
                B.hash = true
            }
        }
        var C, F, A = false, E = B.submit;
        var D = G.inject({}, function (H, I) {
            if (!I.disabled && I.name) {
                C = I.name;
                F = $$(I).getValue();
                if (F != null && I.type != "file" && (I.type != "submit" || (!A && E !== false && (!E || C == E) && (A = true)))) {
                    if (C in H) {
                        if (!Object.isArray(H[C])) {
                            H[C] = [H[C]]
                        }
                        H[C].push(F)
                    } else {
                        H[C] = F
                    }
                }
            }
            return H
        });
        return B.hash ? D : Object.toQueryString(D)
    }
};
Form.Methods = {
    serialize: function (B, A) {
        return Form.serializeElements(Form.getElements(B), A)
    }, getElements: function (E) {
        var F = $$(E).getElementsByTagName("*"), D, A = [], C = Form.Element.Serializers;
        for (var B = 0; D = F[B]; B++) {
            A.push(D)
        }
        return A.inject([], function (G, H) {
            if (C[H.tagName.toLowerCase()]) {
                G.push(Element.extend(H))
            }
            return G
        })
    }, getInputs: function (G, C, D) {
        G = $$(G);
        var A = G.getElementsByTagName("input");
        if (!C && !D) {
            return $A(A).map(Element.extend)
        }
        for (var E = 0, H = [], F = A.length; E < F; E++) {
            var B = A[E];
            if ((C && B.type != C) || (D && B.name != D)) {
                continue
            }
            H.push(Element.extend(B))
        }
        return H
    }, disable: function (A) {
        A = $$(A);
        Form.getElements(A).invoke("disable");
        return A
    }, enable: function (A) {
        A = $$(A);
        Form.getElements(A).invoke("enable");
        return A
    }, findFirstElement: function (B) {
        var C = $$(B).getElements().findAll(function (D) {
            return "hidden" != D.type && !D.disabled
        });
        var A = C.findAll(function (D) {
            return D.hasAttribute("tabIndex") && D.tabIndex >= 0
        }).sortBy(function (D) {
            return D.tabIndex
        }).first();
        return A ? A : C.find(function (D) {
            return /^(?:input|select|textarea)$/i.test(D.tagName)
        })
    }, focusFirstElement: function (A) {
        A = $$(A);
        A.findFirstElement().activate();
        return A
    }, request: function (B, A) {
        B = $$(B), A = Object.clone(A || {});
        var D = A.parameters, C = B.readAttribute("action") || "";
        if (C.blank()) {
            C = window.location.href
        }
        A.parameters = B.serialize(true);
        if (D) {
            if (Object.isString(D)) {
                D = D.toQueryParams()
            }
            Object.extend(A.parameters, D)
        }
        if (B.hasAttribute("method") && !A.method) {
            A.method = B.method
        }
        return new Ajax.Request(C, A)
    }
};
Form.Element = {
    focus: function (A) {
        $$(A).focus();
        return A
    }, select: function (A) {
        $$(A).select();
        return A
    }
};
Form.Element.Methods = {
    serialize: function (A) {
        A = $$(A);
        if (!A.disabled && A.name) {
            var B = A.getValue();
            if (B != undefined) {
                var C = {};
                C[A.name] = B;
                return Object.toQueryString(C)
            }
        }
        return ""
    }, getValue: function (A) {
        A = $$(A);
        var B = A.tagName.toLowerCase();
        return Form.Element.Serializers[B](A)
    }, setValue: function (A, B) {
        A = $$(A);
        var C = A.tagName.toLowerCase();
        Form.Element.Serializers[C](A, B);
        return A
    }, clear: function (A) {
        $$(A).value = "";
        return A
    }, present: function (A) {
        return $$(A).value != ""
    }, activate: function (A) {
        A = $$(A);
        try {
            A.focus();
            if (A.select && (A.tagName.toLowerCase() != "input" || !(/^(?:button|reset|submit)$/i.test(A.type)))) {
                A.select()
            }
        } catch (B) {
        }
        return A
    }, disable: function (A) {
        A = $$(A);
        A.disabled = true;
        return A
    }, enable: function (A) {
        A = $$(A);
        A.disabled = false;
        return A
    }
};
var Field = Form.Element;
var $F = Form.Element.Methods.getValue;
Form.Element.Serializers = {
    input: function (A, B) {
        switch (A.type.toLowerCase()) {
            case"checkbox":
            case"radio":
                return Form.Element.Serializers.inputSelector(A, B);
            default:
                return Form.Element.Serializers.textarea(A, B)
        }
    }, inputSelector: function (A, B) {
        if (Object.isUndefined(B)) {
            return A.checked ? A.value : null
        } else {
            A.checked = !!B
        }
    }, textarea: function (A, B) {
        if (Object.isUndefined(B)) {
            return A.value
        } else {
            A.value = B
        }
    }, select: function (C, F) {
        if (Object.isUndefined(F)) {
            return this[C.type == "select-one" ? "selectOne" : "selectMany"](C)
        } else {
            var B, D, G = !Object.isArray(F);
            for (var A = 0, E = C.length; A < E; A++) {
                B = C.options[A];
                D = this.optionValue(B);
                if (G) {
                    if (D == F) {
                        B.selected = true;
                        return
                    }
                } else {
                    B.selected = F.include(D)
                }
            }
        }
    }, selectOne: function (B) {
        var A = B.selectedIndex;
        return A >= 0 ? this.optionValue(B.options[A]) : null
    }, selectMany: function (D) {
        var A, E = D.length;
        if (!E) {
            return null
        }
        for (var C = 0, A = []; C < E; C++) {
            var B = D.options[C];
            if (B.selected) {
                A.push(this.optionValue(B))
            }
        }
        return A
    }, optionValue: function (A) {
        return Element.extend(A).hasAttribute("value") ? A.value : A.text
    }
};
Abstract.TimedObserver = Class.create(PeriodicalExecuter, {
    initialize: function ($super, A, B, C) {
        $super(C, B);
        this.element = $$(A);
        this.lastValue = this.getValue()
    }, execute: function () {
        var A = this.getValue();
        if (Object.isString(this.lastValue) && Object.isString(A) ? this.lastValue != A : String(this.lastValue) != String(A)) {
            this.callback(this.element, A);
            this.lastValue = A
        }
    }
});
Form.Element.Observer = Class.create(Abstract.TimedObserver, {
    getValue: function () {
        return Form.Element.getValue(this.element)
    }
});
Form.Observer = Class.create(Abstract.TimedObserver, {
    getValue: function () {
        return Form.serialize(this.element)
    }
});
Abstract.EventObserver = Class.create({
    initialize: function (A, B) {
        this.element = $$(A);
        this.callback = B;
        this.lastValue = this.getValue();
        if (this.element.tagName.toLowerCase() == "form") {
            this.registerFormCallbacks()
        } else {
            this.registerCallback(this.element)
        }
    }, onElementEvent: function () {
        var A = this.getValue();
        if (this.lastValue != A) {
            this.callback(this.element, A);
            this.lastValue = A
        }
    }, registerFormCallbacks: function () {
        Form.getElements(this.element).each(this.registerCallback, this)
    }, registerCallback: function (A) {
        if (A.type) {
            switch (A.type.toLowerCase()) {
                case"checkbox":
                case"radio":
                    Event.observe(A, "click", this.onElementEvent.bind(this));
                    break;
                default:
                    Event.observe(A, "change", this.onElementEvent.bind(this));
                    break
            }
        }
    }
});
Form.Element.EventObserver = Class.create(Abstract.EventObserver, {
    getValue: function () {
        return Form.Element.getValue(this.element)
    }
});
Form.EventObserver = Class.create(Abstract.EventObserver, {
    getValue: function () {
        return Form.serialize(this.element)
    }
});
(function () {
    var V = {
        KEY_BACKSPACE: 8,
        KEY_TAB: 9,
        KEY_RETURN: 13,
        KEY_ESC: 27,
        KEY_LEFT: 37,
        KEY_UP: 38,
        KEY_RIGHT: 39,
        KEY_DOWN: 40,
        KEY_DELETE: 46,
        KEY_HOME: 36,
        KEY_END: 35,
        KEY_PAGEUP: 33,
        KEY_PAGEDOWN: 34,
        KEY_INSERT: 45,
        cache: {}
    };
    var E = document.documentElement;
    var W = "onmouseenter" in E && "onmouseleave" in E;
    var O;
    if (Prototype.Browser.IE) {
        var H = {0: 1, 1: 4, 2: 2};
        O = function (Y, X) {
            return Y.button === H[X]
        }
    } else {
        if (Prototype.Browser.WebKit) {
            O = function (Y, X) {
                switch (X) {
                    case 0:
                        return Y.which == 1 && !Y.metaKey;
                    case 1:
                        return Y.which == 1 && Y.metaKey;
                    default:
                        return false
                }
            }
        } else {
            O = function (Y, X) {
                return Y.which ? (Y.which === X + 1) : (Y.button === X)
            }
        }
    }
    function R(X) {
        return O(X, 0)
    }

    function Q(X) {
        return O(X, 1)
    }

    function K(X) {
        return O(X, 2)
    }

    function C(Z) {
        Z = V.extend(Z);
        var Y = Z.target, X = Z.type, a = Z.currentTarget;
        if (a && a.tagName) {
            if (X === "load" || X === "error" || (X === "click" && a.tagName.toLowerCase() === "input" && a.type === "radio")) {
                Y = a
            }
        }
        if (Y.nodeType == Node.TEXT_NODE) {
            Y = Y.parentNode
        }
        return Element.extend(Y)
    }

    function M(Y, a) {
        var X = V.element(Y);
        if (!a) {
            return X
        }
        var Z = [X].concat(X.ancestors());
        return Selector.findElement(Z, a, 0)
    }

    function P(X) {
        return {x: B(X), y: A(X)}
    }

    function B(Z) {
        var Y = document.documentElement, X = document.body || {scrollLeft: 0};
        return Z.pageX || (Z.clientX + (Y.scrollLeft || X.scrollLeft) - (Y.clientLeft || 0))
    }

    function A(Z) {
        var Y = document.documentElement, X = document.body || {scrollTop: 0};
        return Z.pageY || (Z.clientY + (Y.scrollTop || X.scrollTop) - (Y.clientTop || 0))
    }

    function N(X) {
        V.extend(X);
        X.preventDefault();
        X.stopPropagation();
        X.stopped = true
    }

    V.Methods = {
        isLeftClick: R,
        isMiddleClick: Q,
        isRightClick: K,
        element: C,
        findElement: M,
        pointer: P,
        pointerX: B,
        pointerY: A,
        stop: N
    };
    var T = Object.keys(V.Methods).inject({}, function (X, Y) {
        X[Y] = V.Methods[Y].methodize();
        return X
    });
    if (Prototype.Browser.IE) {
        function G(Y) {
            var X;
            switch (Y.type) {
                case"mouseover":
                    X = Y.fromElement;
                    break;
                case"mouseout":
                    X = Y.toElement;
                    break;
                default:
                    return null
            }
            return Element.extend(X)
        }

        Object.extend(T, {
            stopPropagation: function () {
                this.cancelBubble = true
            }, preventDefault: function () {
                this.returnValue = false
            }, inspect: function () {
                return "[object Event]"
            }
        });
        V.extend = function (Y, X) {
            if (!Y) {
                return false
            }
            if (Y._extendedByPrototype) {
                return Y
            }
            Y._extendedByPrototype = Prototype.emptyFunction;
            var Z = V.pointer(Y);
            Object.extend(Y, {target: Y.srcElement || X, relatedTarget: G(Y), pageX: Z.x, pageY: Z.y});
            return Object.extend(Y, T)
        }
    } else {
        V.prototype = window.Event.prototype || document.createEvent("HTMLEvents").__proto__;
        Object.extend(V.prototype, T);
        V.extend = Prototype.K
    }
    function L(b, a, c) {
        var Z = Element.retrieve(b, "prototype_event_registry");
        if (Object.isUndefined(Z)) {
            D.push(b);
            Z = Element.retrieve(b, "prototype_event_registry", $H())
        }
        var X = Z.get(a);
        if (Object.isUndefined(X)) {
            X = [];
            Z.set(a, X)
        }
        if (X.pluck("handler").include(c)) {
            return false
        }
        var Y;
        if (a.include(":")) {
            Y = function (d) {
                if (Object.isUndefined(d.eventName)) {
                    return false
                }
                if (d.eventName !== a) {
                    return false
                }
                V.extend(d, b);
                c.call(b, d)
            }
        } else {
            if (!W && (a === "mouseenter" || a === "mouseleave")) {
                if (a === "mouseenter" || a === "mouseleave") {
                    Y = function (f) {
                        V.extend(f, b);
                        var d = f.relatedTarget;
                        while (d && d !== b) {
                            try {
                                d = d.parentNode
                            } catch (g) {
                                d = b
                            }
                        }
                        if (d === b) {
                            return
                        }
                        c.call(b, f)
                    }
                }
            } else {
                Y = function (d) {
                    V.extend(d, b);
                    c.call(b, d)
                }
            }
        }
        Y.handler = c;
        X.push(Y);
        return Y
    }

    function F() {
        for (var X = 0, Y = D.length; X < Y; X++) {
            V.stopObserving(D[X]);
            D[X] = null
        }
    }

    var D = [];
    if (Prototype.Browser.IE) {
        window.attachEvent("onunload", F)
    }
    if (Prototype.Browser.WebKit) {
        window.addEventListener("unload", Prototype.emptyFunction, false)
    }
    var J = Prototype.K;
    if (!W) {
        J = function (Y) {
            var X = {mouseenter: "mouseover", mouseleave: "mouseout"};
            return Y in X ? X[Y] : Y
        }
    }
    function S(a, Z, b) {
        a = $$(a);
        var Y = L(a, Z, b);
        if (!Y) {
            return a
        }
        if (Z.include(":")) {
            if (a.addEventListener) {
                a.addEventListener("dataavailable", Y, false)
            } else {
                a.attachEvent("ondataavailable", Y);
                a.attachEvent("onfilterchange", Y)
            }
        } else {
            var X = J(Z);
            if (a.addEventListener) {
                a.addEventListener(X, Y, false)
            } else {
                a.attachEvent("on" + X, Y)
            }
        }
        return a
    }

    function I(c, a, d) {
        c = $$(c);
        var Z = Element.retrieve(c, "prototype_event_registry");
        if (Object.isUndefined(Z)) {
            return c
        }
        if (a && !d) {
            var b = Z.get(a);
            if (Object.isUndefined(b)) {
                return c
            }
            b.each(function (e) {
                Element.stopObserving(c, a, e.handler)
            });
            return c
        } else {
            if (!a) {
                Z.each(function (g) {
                    var e = g.key, f = g.value;
                    f.each(function (h) {
                        Element.stopObserving(c, e, h.handler)
                    })
                });
                return c
            }
        }
        var b = Z.get(a);
        if (!b) {
            return
        }
        var Y = b.find(function (e) {
            return e.handler === d
        });
        if (!Y) {
            return c
        }
        var X = J(a);
        if (a.include(":")) {
            if (c.removeEventListener) {
                c.removeEventListener("dataavailable", Y, false)
            } else {
                c.detachEvent("ondataavailable", Y);
                c.detachEvent("onfilterchange", Y)
            }
        } else {
            if (c.removeEventListener) {
                c.removeEventListener(X, Y, false)
            } else {
                c.detachEvent("on" + X, Y)
            }
        }
        Z.set(a, b.without(Y));
        return c
    }

    function U(a, Z, Y, X) {
        a = $$(a);
        if (Object.isUndefined(X)) {
            X = true
        }
        if (a == document && document.createEvent && !a.dispatchEvent) {
            a = document.documentElement
        }
        var b;
        if (document.createEvent) {
            b = document.createEvent("HTMLEvents");
            b.initEvent("dataavailable", true, true)
        } else {
            b = document.createEventObject();
            b.eventType = X ? "ondataavailable" : "onfilterchange"
        }
        b.eventName = Z;
        b.memo = Y || {};
        if (document.createEvent) {
            a.dispatchEvent(b)
        } else {
            a.fireEvent(b.eventType, b)
        }
        return V.extend(b)
    }

    Object.extend(V, V.Methods);
    Object.extend(V, {fire: U, observe: S, stopObserving: I});
    Element.addMethods({fire: U, observe: S, stopObserving: I});
    Object.extend(document, {fire: U.methodize(), observe: S.methodize(), stopObserving: I.methodize(), loaded: false});
    if (window.Event) {
        Object.extend(window.Event, V)
    } else {
        window.Event = V
    }
})();
(function () {
    var D;

    function A() {
        if (document.loaded) {
            return
        }
        if (D) {
            window.clearTimeout(D)
        }
        document.loaded = true;
        document.fire("dom:loaded")
    }

    function C() {
        if (document.readyState === "complete") {
            document.stopObserving("readystatechange", C);
            A()
        }
    }

    function B() {
        try {
            document.documentElement.doScroll("left")
        } catch (E) {
            D = B.defer();
            return
        }
        A()
    }

    if (document.addEventListener) {
        document.addEventListener("DOMContentLoaded", A, false)
    } else {
        document.observe("readystatechange", C);
        if (window == top) {
            D = B.defer()
        }
    }
    Event.observe(window, "load", A)
})();
Element.addMethods();
Hash.toQueryString = Object.toQueryString;
var Toggle = {display: Element.toggle};
Element.Methods.childOf = Element.Methods.descendantOf;
var Insertion = {
    Before: function (A, B) {
        return Element.insert(A, {before: B})
    }, Top: function (A, B) {
        return Element.insert(A, {top: B})
    }, Bottom: function (A, B) {
        return Element.insert(A, {bottom: B})
    }, After: function (A, B) {
        return Element.insert(A, {after: B})
    }
};
var $continue = new Error('"throw $continue" is deprecated, use "return" instead');
var Position = {
    includeScrollOffsets: false,
    prepare: function () {
        this.deltaX = window.pageXOffset || document.documentElement.scrollLeft || document.body.scrollLeft || 0;
        this.deltaY = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0
    },
    within: function (B, A, C) {
        if (this.includeScrollOffsets) {
            return this.withinIncludingScrolloffsets(B, A, C)
        }
        this.xcomp = A;
        this.ycomp = C;
        this.offset = Element.cumulativeOffset(B);
        return (C >= this.offset[1] && C < this.offset[1] + B.offsetHeight && A >= this.offset[0] && A < this.offset[0] + B.offsetWidth)
    },
    withinIncludingScrolloffsets: function (B, A, D) {
        var C = Element.cumulativeScrollOffset(B);
        this.xcomp = A + C[0] - this.deltaX;
        this.ycomp = D + C[1] - this.deltaY;
        this.offset = Element.cumulativeOffset(B);
        return (this.ycomp >= this.offset[1] && this.ycomp < this.offset[1] + B.offsetHeight && this.xcomp >= this.offset[0] && this.xcomp < this.offset[0] + B.offsetWidth)
    },
    overlap: function (B, A) {
        if (!B) {
            return 0
        }
        if (B == "vertical") {
            return ((this.offset[1] + A.offsetHeight) - this.ycomp) / A.offsetHeight
        }
        if (B == "horizontal") {
            return ((this.offset[0] + A.offsetWidth) - this.xcomp) / A.offsetWidth
        }
    },
    cumulativeOffset: Element.Methods.cumulativeOffset,
    positionedOffset: Element.Methods.positionedOffset,
    absolutize: function (A) {
        Position.prepare();
        return Element.absolutize(A)
    },
    relativize: function (A) {
        Position.prepare();
        return Element.relativize(A)
    },
    realOffset: Element.Methods.cumulativeScrollOffset,
    offsetParent: Element.Methods.getOffsetParent,
    page: Element.Methods.viewportOffset,
    clone: function (B, C, A) {
        A = A || {};
        return Element.clonePosition(C, B, A)
    }
};
if (!document.getElementsByClassName) {
    document.getElementsByClassName = function (B) {
        function A(C) {
            return C.blank() ? null : "[contains(concat(' ', @class, ' '), ' " + C + " ')]"
        }

        B.getElementsByClassName = Prototype.BrowserFeatures.XPath ? function (C, E) {
            E = E.toString().strip();
            var D = /\s/.test(E) ? $w(E).map(A).join("") : A(E);
            return D ? document._getElementsByXPath(".//*" + D, C) : []
        } : function (E, F) {
            F = F.toString().strip();
            var G = [], H = (/\s/.test(F) ? $w(F) : null);
            if (!H && !F) {
                return G
            }
            var C = $$(E).getElementsByTagName("*");
            F = " " + F + " ";
            for (var D = 0, J, I; J = C[D]; D++) {
                if (J.className && (I = " " + J.className + " ") && (I.include(F) || (H && H.all(function (K) {
                        return !K.toString().blank() && I.include(" " + K + " ")
                    })))) {
                    G.push(Element.extend(J))
                }
            }
            return G
        };
        return function (D, C) {
            return $$(C || document.body).getElementsByClassName(D)
        }
    }(Element.Methods)
}
Element.ClassNames = Class.create();
Element.ClassNames.prototype = {
    initialize: function (A) {
        this.element = $$(A)
    }, _each: function (A) {
        this.element.className.split(/\s+/).select(function (B) {
            return B.length > 0
        })._each(A)
    }, set: function (A) {
        this.element.className = A
    }, add: function (A) {
        if (this.include(A)) {
            return
        }
        this.set($A(this).concat(A).join(" "))
    }, remove: function (A) {
        if (!this.include(A)) {
            return
        }
        this.set($A(this).without(A).join(" "))
    }, toString: function () {
        return $A(this).join(" ")
    }
};
Object.extend(Element.ClassNames.prototype, Enumerable);
var Scriptaculous = {
    Version: "1.8.3", require: function (B) {
        try {
            document.write('<script type="text/javascript" src="' + B + '"><\/script>')
        } catch (C) {
            var A = document.createElement("script");
            A.type = "text/javascript";
            A.src = B;
            document.getElementsByTagName("head")[0].appendChild(A)
        }
    }, REQUIRED_PROTOTYPE: "1.6.0.3", load: function () {
        function A(B) {
            var C = B.replace(/_.*|\./g, "");
            C = parseInt(C + "0".times(4 - C.length));
            return B.indexOf("_") > -1 ? C - 1 : C
        }

        if ((typeof Prototype == "undefined") || (typeof Element == "undefined") || (typeof Element.Methods == "undefined") || (A(Prototype.Version) < A(Scriptaculous.REQUIRED_PROTOTYPE))) {
            throw ("script.aculo.us requires the Prototype JavaScript framework >= " + Scriptaculous.REQUIRED_PROTOTYPE)
        }
    }
};
Scriptaculous.load();
var Builder = {
    NODEMAP: {
        AREA: "map",
        CAPTION: "table",
        COL: "table",
        COLGROUP: "table",
        LEGEND: "fieldset",
        OPTGROUP: "select",
        OPTION: "select",
        PARAM: "object",
        TBODY: "table",
        TD: "table",
        TFOOT: "table",
        TH: "table",
        THEAD: "table",
        TR: "table"
    }, node: function (A) {
        A = A.toUpperCase();
        var F = this.NODEMAP[A] || "div";
        var B = document.createElement(F);
        try {
            B.innerHTML = "<" + A + "></" + A + ">"
        } catch (E) {
        }
        var D = B.firstChild || null;
        if (D && (D.tagName.toUpperCase() != A)) {
            D = D.getElementsByTagName(A)[0]
        }
        if (!D) {
            D = document.createElement(A)
        }
        if (!D) {
            return
        }
        if (arguments[1]) {
            if (this._isStringOrNumber(arguments[1]) || (arguments[1] instanceof Array) || arguments[1].tagName) {
                this._children(D, arguments[1])
            } else {
                var C = this._attributes(arguments[1]);
                if (C.length) {
                    try {
                        B.innerHTML = "<" + A + " " + C + "></" + A + ">"
                    } catch (E) {
                    }
                    D = B.firstChild || null;
                    if (!D) {
                        D = document.createElement(A);
                        for (attr in arguments[1]) {
                            D[attr == "class" ? "className" : attr] = arguments[1][attr]
                        }
                    }
                    if (D.tagName.toUpperCase() != A) {
                        D = B.getElementsByTagName(A)[0]
                    }
                }
            }
        }
        if (arguments[2]) {
            this._children(D, arguments[2])
        }
        return $$(D)
    }, _text: function (A) {
        return document.createTextNode(A)
    }, ATTR_MAP: {className: "class", htmlFor: "for"}, _attributes: function (A) {
        var B = [];
        for (attribute in A) {
            B.push((attribute in this.ATTR_MAP ? this.ATTR_MAP[attribute] : attribute) + '="' + A[attribute].toString().escapeHTML().gsub(/"/, "&quot;") + '"')
        }
        return B.join(" ")
    }, _children: function (B, A) {
        if (A.tagName) {
            B.appendChild(A);
            return
        }
        if (typeof A == "object") {
            A.flatten().each(function (C) {
                if (typeof C == "object") {
                    B.appendChild(C)
                } else {
                    if (Builder._isStringOrNumber(C)) {
                        B.appendChild(Builder._text(C))
                    }
                }
            })
        } else {
            if (Builder._isStringOrNumber(A)) {
                B.appendChild(Builder._text(A))
            }
        }
    }, _isStringOrNumber: function (A) {
        return (typeof A == "string" || typeof A == "number")
    }, build: function (B) {
        var A = this.node("div");
        $$(A).update(B.strip());
        return A.down()
    }, dump: function (B) {
        if (typeof B != "object" && typeof B != "function") {
            B = window
        }
        var A = ("A ABBR ACRONYM ADDRESS APPLET AREA B BASE BASEFONT BDO BIG BLOCKQUOTE BODY BR BUTTON CAPTION CENTER CITE CODE COL COLGROUP DD DEL DFN DIR DIV DL DT EM FIELDSET FONT FORM FRAME FRAMESET H1 H2 H3 H4 H5 H6 HEAD HR HTML I IFRAME IMG INPUT INS ISINDEX KBD LABEL LEGEND LI LINK MAP MENU META NOFRAMES NOSCRIPT OBJECT OL OPTGROUP OPTION P PARAM PRE Q S SAMP SCRIPT SELECT SMALL SPAN STRIKE STRONG STYLE SUB SUP TABLE TBODY TD TEXTAREA TFOOT TH THEAD TITLE TR TT U UL VAR").split(/\s+/);
        A.each(function (C) {
            B[C] = function () {
                return Builder.node.apply(Builder, [C].concat($A(arguments)))
            }
        })
    }
};
String.prototype.parseColor = function () {
    var A = "#";
    if (this.slice(0, 4) == "rgb(") {
        var C = this.slice(4, this.length - 1).split(",");
        var B = 0;
        do {
            A += parseInt(C[B]).toColorPart()
        } while (++B < 3)
    } else {
        if (this.slice(0, 1) == "#") {
            if (this.length == 4) {
                for (var B = 1; B < 4; B++) {
                    A += (this.charAt(B) + this.charAt(B)).toLowerCase()
                }
            }
            if (this.length == 7) {
                A = this.toLowerCase()
            }
        }
    }
    return (A.length == 7 ? A : (arguments[0] || this))
};
Element.collectTextNodes = function (A) {
    return $A($$(A).childNodes).collect(function (B) {
        return (B.nodeType == 3 ? B.nodeValue : (B.hasChildNodes() ? Element.collectTextNodes(B) : ""))
    }).flatten().join("")
};
Element.collectTextNodesIgnoreClass = function (A, B) {
    return $A($$(A).childNodes).collect(function (C) {
        return (C.nodeType == 3 ? C.nodeValue : ((C.hasChildNodes() && !Element.hasClassName(C, B)) ? Element.collectTextNodesIgnoreClass(C, B) : ""))
    }).flatten().join("")
};
Element.setContentZoom = function (A, B) {
    A = $$(A);
    A.setStyle({fontSize: (B / 100) + "em"});
    if (Prototype.Browser.WebKit) {
        window.scrollBy(0, 0)
    }
    return A
};
Element.getInlineOpacity = function (A) {
    return $$(A).style.opacity || ""
};
Element.forceRerendering = function (A) {
    try {
        A = $$(A);
        var C = document.createTextNode(" ");
        A.appendChild(C);
        A.removeChild(C)
    } catch (B) {
    }
};
var Effect = {
    _elementDoesNotExistError: {
        name: "ElementDoesNotExistError",
        message: "The specified DOM element does not exist, but is required for this effect to operate"
    },
    Transitions: {
        linear: Prototype.K, sinoidal: function (A) {
            return (-Math.cos(A * Math.PI) / 2) + 0.5
        }, reverse: function (A) {
            return 1 - A
        }, flicker: function (A) {
            var A = ((-Math.cos(A * Math.PI) / 4) + 0.75) + Math.random() / 4;
            return A > 1 ? 1 : A
        }, wobble: function (A) {
            return (-Math.cos(A * Math.PI * (9 * A)) / 2) + 0.5
        }, pulse: function (B, A) {
            return (-Math.cos((B * ((A || 5) - 0.5) * 2) * Math.PI) / 2) + 0.5
        }, spring: function (A) {
            return 1 - (Math.cos(A * 4.5 * Math.PI) * Math.exp(-A * 6))
        }, none: function (A) {
            return 0
        }, full: function (A) {
            return 1
        }
    },
    DefaultOptions: {duration: 1, fps: 100, sync: false, from: 0, to: 1, delay: 0, queue: "parallel"},
    tagifyText: function (A) {
        var B = "position:relative";
        if (Prototype.Browser.IE) {
            B += ";zoom:1"
        }
        A = $$(A);
        $A(A.childNodes).each(function (C) {
            if (C.nodeType == 3) {
                C.nodeValue.toArray().each(function (D) {
                    A.insertBefore(new Element("span", {style: B}).update(D == " " ? String.fromCharCode(160) : D), C)
                });
                Element.remove(C)
            }
        })
    },
    multiple: function (B, C) {
        var E;
        if (((typeof B == "object") || Object.isFunction(B)) && (B.length)) {
            E = B
        } else {
            E = $$(B).childNodes
        }
        var A = Object.extend({speed: 0.1, delay: 0}, arguments[2] || {});
        var D = A.delay;
        $A(E).each(function (G, F) {
            new C(G, Object.extend(A, {delay: F * A.speed + D}))
        })
    },
    PAIRS: {slide: ["SlideDown", "SlideUp"], blind: ["BlindDown", "BlindUp"], appear: ["Appear", "Fade"]},
    toggle: function (B, C, A) {
        B = $$(B);
        C = (C || "appear").toLowerCase();
        return Effect[Effect.PAIRS[C][B.visible() ? 1 : 0]](B, Object.extend({
            queue: {
                position: "end",
                scope: (B.id || "global"),
                limit: 1
            }
        }, A || {}))
    }
};
Effect.DefaultOptions.transition = Effect.Transitions.sinoidal;
Effect.ScopedQueue = Class.create(Enumerable, {
    initialize: function () {
        this.effects = [];
        this.interval = null
    }, _each: function (A) {
        this.effects._each(A)
    }, add: function (B) {
        var C = new Date().getTime();
        var A = Object.isString(B.options.queue) ? B.options.queue : B.options.queue.position;
        switch (A) {
            case"front":
                this.effects.findAll(function (D) {
                    return D.state == "idle"
                }).each(function (D) {
                    D.startOn += B.finishOn;
                    D.finishOn += B.finishOn
                });
                break;
            case"with-last":
                C = this.effects.pluck("startOn").max() || C;
                break;
            case"end":
                C = this.effects.pluck("finishOn").max() || C;
                break
        }
        B.startOn += C;
        B.finishOn += C;
        if (!B.options.queue.limit || (this.effects.length < B.options.queue.limit)) {
            this.effects.push(B)
        }
        if (!this.interval) {
            this.interval = setInterval(this.loop.bind(this), 15)
        }
    }, remove: function (A) {
        this.effects = this.effects.reject(function (B) {
            return B == A
        });
        if (this.effects.length == 0) {
            clearInterval(this.interval);
            this.interval = null
        }
    }, loop: function () {
        var C = new Date().getTime();
        for (var B = 0, A = this.effects.length; B < A; B++) {
            this.effects[B] && this.effects[B].loop(C)
        }
    }
});
Effect.Queues = {
    instances: $H(), get: function (A) {
        if (!Object.isString(A)) {
            return A
        }
        return this.instances.get(A) || this.instances.set(A, new Effect.ScopedQueue())
    }
};
Effect.Queue = Effect.Queues.get("global");
Effect.Base = Class.create({
    position: null, start: function (A) {
        if (A && A.transition === false) {
            A.transition = Effect.Transitions.linear
        }
        this.options = Object.extend(Object.extend({}, Effect.DefaultOptions), A || {});
        this.currentFrame = 0;
        this.state = "idle";
        this.startOn = this.options.delay * 1000;
        this.finishOn = this.startOn + (this.options.duration * 1000);
        this.fromToDelta = this.options.to - this.options.from;
        this.totalTime = this.finishOn - this.startOn;
        this.totalFrames = this.options.fps * this.options.duration;
        this.render = (function () {
            function B(D, C) {
                if (D.options[C + "Internal"]) {
                    D.options[C + "Internal"](D)
                }
                if (D.options[C]) {
                    D.options[C](D)
                }
            }

            return function (C) {
                if (this.state === "idle") {
                    this.state = "running";
                    B(this, "beforeSetup");
                    if (this.setup) {
                        this.setup()
                    }
                    B(this, "afterSetup")
                }
                if (this.state === "running") {
                    C = (this.options.transition(C) * this.fromToDelta) + this.options.from;
                    this.position = C;
                    B(this, "beforeUpdate");
                    if (this.update) {
                        this.update(C)
                    }
                    B(this, "afterUpdate")
                }
            }
        })();
        this.event("beforeStart");
        if (!this.options.sync) {
            Effect.Queues.get(Object.isString(this.options.queue) ? "global" : this.options.queue.scope).add(this)
        }
    }, loop: function (C) {
        if (C >= this.startOn) {
            if (C >= this.finishOn) {
                this.render(1);
                this.cancel();
                this.event("beforeFinish");
                if (this.finish) {
                    this.finish()
                }
                this.event("afterFinish");
                return
            }
            var B = (C - this.startOn) / this.totalTime, A = (B * this.totalFrames).round();
            if (A > this.currentFrame) {
                this.render(B);
                this.currentFrame = A
            }
        }
    }, cancel: function () {
        if (!this.options.sync) {
            Effect.Queues.get(Object.isString(this.options.queue) ? "global" : this.options.queue.scope).remove(this)
        }
        this.state = "finished"
    }, event: function (A) {
        if (this.options[A + "Internal"]) {
            this.options[A + "Internal"](this)
        }
        if (this.options[A]) {
            this.options[A](this)
        }
    }, inspect: function () {
        var A = $H();
        for (property in this) {
            if (!Object.isFunction(this[property])) {
                A.set(property, this[property])
            }
        }
        return "#<Effect:" + A.inspect() + ",options:" + $H(this.options).inspect() + ">"
    }
});
Effect.Parallel = Class.create(Effect.Base, {
    initialize: function (A) {
        this.effects = A || [];
        this.start(arguments[1])
    }, update: function (A) {
        this.effects.invoke("render", A)
    }, finish: function (A) {
        this.effects.each(function (B) {
            B.render(1);
            B.cancel();
            B.event("beforeFinish");
            if (B.finish) {
                B.finish(A)
            }
            B.event("afterFinish")
        })
    }
});
Effect.Tween = Class.create(Effect.Base, {
    initialize: function (C, F, E) {
        C = Object.isString(C) ? $$(C) : C;
        var B = $A(arguments), D = B.last(), A = B.length == 5 ? B[3] : null;
        this.method = Object.isFunction(D) ? D.bind(C) : Object.isFunction(C[D]) ? C[D].bind(C) : function (G) {
            C[D] = G
        };
        this.start(Object.extend({from: F, to: E}, A || {}))
    }, update: function (A) {
        this.method(A)
    }
});
Effect.Event = Class.create(Effect.Base, {
    initialize: function () {
        this.start(Object.extend({duration: 0}, arguments[0] || {}))
    }, update: Prototype.emptyFunction
});
Effect.Opacity = Class.create(Effect.Base, {
    initialize: function (B) {
        this.element = $$(B);
        if (!this.element) {
            throw (Effect._elementDoesNotExistError)
        }
        if (Prototype.Browser.IE && (!this.element.currentStyle.hasLayout)) {
            this.element.setStyle({zoom: 1})
        }
        var A = Object.extend({from: this.element.getOpacity() || 0, to: 1}, arguments[1] || {});
        this.start(A)
    }, update: function (A) {
        this.element.setOpacity(A)
    }
});
Effect.Move = Class.create(Effect.Base, {
    initialize: function (B) {
        this.element = $$(B);
        if (!this.element) {
            throw (Effect._elementDoesNotExistError)
        }
        var A = Object.extend({x: 0, y: 0, mode: "relative"}, arguments[1] || {});
        this.start(A)
    }, setup: function () {
        this.element.makePositioned();
        this.originalLeft = parseFloat(this.element.getStyle("left") || "0");
        this.originalTop = parseFloat(this.element.getStyle("top") || "0");
        if (this.options.mode == "absolute") {
            this.options.x = this.options.x - this.originalLeft;
            this.options.y = this.options.y - this.originalTop
        }
    }, update: function (A) {
        this.element.setStyle({
            left: (this.options.x * A + this.originalLeft).round() + "px",
            top: (this.options.y * A + this.originalTop).round() + "px"
        })
    }
});
Effect.MoveBy = function (B, A, C) {
    return new Effect.Move(B, Object.extend({x: C, y: A}, arguments[3] || {}))
};
Effect.Scale = Class.create(Effect.Base, {
    initialize: function (B, C) {
        this.element = $$(B);
        if (!this.element) {
            throw (Effect._elementDoesNotExistError)
        }
        var A = Object.extend({
            scaleX: true,
            scaleY: true,
            scaleContent: true,
            scaleFromCenter: false,
            scaleMode: "box",
            scaleFrom: 100,
            scaleTo: C
        }, arguments[2] || {});
        this.start(A)
    }, setup: function () {
        this.restoreAfterFinish = this.options.restoreAfterFinish || false;
        this.elementPositioning = this.element.getStyle("position");
        this.originalStyle = {};
        ["top", "left", "width", "height", "fontSize"].each(function (B) {
            this.originalStyle[B] = this.element.style[B]
        }.bind(this));
        this.originalTop = this.element.offsetTop;
        this.originalLeft = this.element.offsetLeft;
        var A = this.element.getStyle("font-size") || "100%";
        ["em", "px", "%", "pt"].each(function (B) {
            if (A.indexOf(B) > 0) {
                this.fontSize = parseFloat(A);
                this.fontSizeType = B
            }
        }.bind(this));
        this.factor = (this.options.scaleTo - this.options.scaleFrom) / 100;
        this.dims = null;
        if (this.options.scaleMode == "box") {
            this.dims = [this.element.offsetHeight, this.element.offsetWidth]
        }
        if (/^content/.test(this.options.scaleMode)) {
            this.dims = [this.element.scrollHeight, this.element.scrollWidth]
        }
        if (!this.dims) {
            this.dims = [this.options.scaleMode.originalHeight, this.options.scaleMode.originalWidth]
        }
    }, update: function (A) {
        var B = (this.options.scaleFrom / 100) + (this.factor * A);
        if (this.options.scaleContent && this.fontSize) {
            this.element.setStyle({fontSize: this.fontSize * B + this.fontSizeType})
        }
        this.setDimensions(this.dims[0] * B, this.dims[1] * B)
    }, finish: function (A) {
        if (this.restoreAfterFinish) {
            this.element.setStyle(this.originalStyle)
        }
    }, setDimensions: function (A, D) {
        var E = {};
        if (this.options.scaleX) {
            E.width = D.round() + "px"
        }
        if (this.options.scaleY) {
            E.height = A.round() + "px"
        }
        if (this.options.scaleFromCenter) {
            var C = (A - this.dims[0]) / 2;
            var B = (D - this.dims[1]) / 2;
            if (this.elementPositioning == "absolute") {
                if (this.options.scaleY) {
                    E.top = this.originalTop - C + "px"
                }
                if (this.options.scaleX) {
                    E.left = this.originalLeft - B + "px"
                }
            } else {
                if (this.options.scaleY) {
                    E.top = -C + "px"
                }
                if (this.options.scaleX) {
                    E.left = -B + "px"
                }
            }
        }
        this.element.setStyle(E)
    }
});
Effect.Highlight = Class.create(Effect.Base, {
    initialize: function (B) {
        this.element = $$(B);
        if (!this.element) {
            throw (Effect._elementDoesNotExistError)
        }
        var A = Object.extend({startcolor: "#ffff99"}, arguments[1] || {});
        this.start(A)
    }, setup: function () {
        if (this.element.getStyle("display") == "none") {
            this.cancel();
            return
        }
        this.oldStyle = {};
        if (!this.options.keepBackgroundImage) {
            this.oldStyle.backgroundImage = this.element.getStyle("background-image");
            this.element.setStyle({backgroundImage: "none"})
        }
        if (!this.options.endcolor) {
            this.options.endcolor = this.element.getStyle("background-color").parseColor("#ffffff")
        }
        if (!this.options.restorecolor) {
            this.options.restorecolor = this.element.getStyle("background-color")
        }
        this._base = $R(0, 2).map(function (A) {
            return parseInt(this.options.startcolor.slice(A * 2 + 1, A * 2 + 3), 16)
        }.bind(this));
        this._delta = $R(0, 2).map(function (A) {
            return parseInt(this.options.endcolor.slice(A * 2 + 1, A * 2 + 3), 16) - this._base[A]
        }.bind(this))
    }, update: function (A) {
        this.element.setStyle({
            backgroundColor: $R(0, 2).inject("#", function (B, C, D) {
                return B + ((this._base[D] + (this._delta[D] * A)).round().toColorPart())
            }.bind(this))
        })
    }, finish: function () {
        this.element.setStyle(Object.extend(this.oldStyle, {backgroundColor: this.options.restorecolor}))
    }
});
Effect.ScrollTo = function (C) {
    var B = arguments[1] || {}, A = document.viewport.getScrollOffsets(), D = $$(C).cumulativeOffset();
    if (B.offset) {
        D[1] += B.offset
    }
    return new Effect.Tween(null, A.top, D[1], B, function (E) {
        scrollTo(A.left, E.round())
    })
};
Effect.Fade = function (C) {
    C = $$(C);
    var A = C.getInlineOpacity();
    var B = Object.extend({
        from: C.getOpacity() || 1, to: 0, afterFinishInternal: function (D) {
            if (D.options.to != 0) {
                return
            }
            D.element.hide().setStyle({opacity: A})
        }
    }, arguments[1] || {});
    return new Effect.Opacity(C, B)
};
Effect.Appear = function (B) {
    B = $$(B);
    var A = Object.extend({
        from: (B.getStyle("display") == "none" ? 0 : B.getOpacity() || 0),
        to: 1,
        afterFinishInternal: function (C) {
            C.element.forceRerendering()
        },
        beforeSetup: function (C) {
            C.element.setOpacity(C.options.from).show()
        }
    }, arguments[1] || {});
    return new Effect.Opacity(B, A)
};
Effect.Puff = function (B) {
    B = $$(B);
    var A = {
        opacity: B.getInlineOpacity(),
        position: B.getStyle("position"),
        top: B.style.top,
        left: B.style.left,
        width: B.style.width,
        height: B.style.height
    };
    return new Effect.Parallel([new Effect.Scale(B, 200, {
        sync: true,
        scaleFromCenter: true,
        scaleContent: true,
        restoreAfterFinish: true
    }), new Effect.Opacity(B, {sync: true, to: 0})], Object.extend({
        duration: 1, beforeSetupInternal: function (C) {
            Position.absolutize(C.effects[0].element)
        }, afterFinishInternal: function (C) {
            C.effects[0].element.hide().setStyle(A)
        }
    }, arguments[1] || {}))
};
Effect.BlindUp = function (A) {
    A = $$(A);
    A.makeClipping();
    return new Effect.Scale(A, 0, Object.extend({
        scaleContent: false,
        scaleX: false,
        restoreAfterFinish: true,
        afterFinishInternal: function (B) {
            B.element.hide().undoClipping()
        }
    }, arguments[1] || {}))
};
Effect.BlindDown = function (B) {
    B = $$(B);
    var A = B.getDimensions();
    return new Effect.Scale(B, 100, Object.extend({
        scaleContent: false,
        scaleX: false,
        scaleFrom: 0,
        scaleMode: {originalHeight: A.height, originalWidth: A.width},
        restoreAfterFinish: true,
        afterSetup: function (C) {
            C.element.makeClipping().setStyle({height: "0px"}).show()
        },
        afterFinishInternal: function (C) {
            C.element.undoClipping()
        }
    }, arguments[1] || {}))
};
Effect.SwitchOff = function (B) {
    B = $$(B);
    var A = B.getInlineOpacity();
    return new Effect.Appear(B, Object.extend({
        duration: 0.4,
        from: 0,
        transition: Effect.Transitions.flicker,
        afterFinishInternal: function (C) {
            new Effect.Scale(C.element, 1, {
                duration: 0.3,
                scaleFromCenter: true,
                scaleX: false,
                scaleContent: false,
                restoreAfterFinish: true,
                beforeSetup: function (D) {
                    D.element.makePositioned().makeClipping()
                },
                afterFinishInternal: function (D) {
                    D.element.hide().undoClipping().undoPositioned().setStyle({opacity: A})
                }
            })
        }
    }, arguments[1] || {}))
};
Effect.DropOut = function (B) {
    B = $$(B);
    var A = {top: B.getStyle("top"), left: B.getStyle("left"), opacity: B.getInlineOpacity()};
    return new Effect.Parallel([new Effect.Move(B, {x: 0, y: 100, sync: true}), new Effect.Opacity(B, {
        sync: true,
        to: 0
    })], Object.extend({
        duration: 0.5, beforeSetup: function (C) {
            C.effects[0].element.makePositioned()
        }, afterFinishInternal: function (C) {
            C.effects[0].element.hide().undoPositioned().setStyle(A)
        }
    }, arguments[1] || {}))
};
Effect.Shake = function (D) {
    D = $$(D);
    var B = Object.extend({distance: 20, duration: 0.5}, arguments[1] || {});
    var E = parseFloat(B.distance);
    var C = parseFloat(B.duration) / 10;
    var A = {top: D.getStyle("top"), left: D.getStyle("left")};
    return new Effect.Move(D, {
        x: E, y: 0, duration: C, afterFinishInternal: function (F) {
            new Effect.Move(F.element, {
                x: -E * 2, y: 0, duration: C * 2, afterFinishInternal: function (G) {
                    new Effect.Move(G.element, {
                        x: E * 2, y: 0, duration: C * 2, afterFinishInternal: function (H) {
                            new Effect.Move(H.element, {
                                x: -E * 2, y: 0, duration: C * 2, afterFinishInternal: function (I) {
                                    new Effect.Move(I.element, {
                                        x: E * 2, y: 0, duration: C * 2, afterFinishInternal: function (J) {
                                            new Effect.Move(J.element, {
                                                x: -E, y: 0, duration: C, afterFinishInternal: function (K) {
                                                    K.element.undoPositioned().setStyle(A)
                                                }
                                            })
                                        }
                                    })
                                }
                            })
                        }
                    })
                }
            })
        }
    })
};
Effect.SlideDown = function (C) {
    C = $$(C).cleanWhitespace();
    var A = C.down().getStyle("bottom");
    var B = C.getDimensions();
    return new Effect.Scale(C, 100, Object.extend({
        scaleContent: false,
        scaleX: false,
        scaleFrom: window.opera ? 0 : 1,
        scaleMode: {originalHeight: B.height, originalWidth: B.width},
        restoreAfterFinish: true,
        afterSetup: function (D) {
            D.element.makePositioned();
            D.element.down().makePositioned();
            if (window.opera) {
                D.element.setStyle({top: ""})
            }
            D.element.makeClipping().setStyle({height: "0px"}).show()
        },
        afterUpdateInternal: function (D) {
            D.element.down().setStyle({bottom: (D.dims[0] - D.element.clientHeight) + "px"})
        },
        afterFinishInternal: function (D) {
            D.element.undoClipping().undoPositioned();
            D.element.down().undoPositioned().setStyle({bottom: A})
        }
    }, arguments[1] || {}))
};
Effect.SlideUp = function (C) {
    C = $$(C).cleanWhitespace();
    var A = C.down().getStyle("bottom");
    var B = C.getDimensions();
    return new Effect.Scale(C, window.opera ? 0 : 1, Object.extend({
        scaleContent: false,
        scaleX: false,
        scaleMode: "box",
        scaleFrom: 100,
        scaleMode: {originalHeight: B.height, originalWidth: B.width},
        restoreAfterFinish: true,
        afterSetup: function (D) {
            D.element.makePositioned();
            D.element.down().makePositioned();
            if (window.opera) {
                D.element.setStyle({top: ""})
            }
            D.element.makeClipping().show()
        },
        afterUpdateInternal: function (D) {
            D.element.down().setStyle({bottom: (D.dims[0] - D.element.clientHeight) + "px"})
        },
        afterFinishInternal: function (D) {
            D.element.hide().undoClipping().undoPositioned();
            D.element.down().undoPositioned().setStyle({bottom: A})
        }
    }, arguments[1] || {}))
};
Effect.Squish = function (A) {
    return new Effect.Scale(A, window.opera ? 1 : 0, {
        restoreAfterFinish: true, beforeSetup: function (B) {
            B.element.makeClipping()
        }, afterFinishInternal: function (B) {
            B.element.hide().undoClipping()
        }
    })
};
Effect.Grow = function (C) {
    C = $$(C);
    var B = Object.extend({
        direction: "center",
        moveTransition: Effect.Transitions.sinoidal,
        scaleTransition: Effect.Transitions.sinoidal,
        opacityTransition: Effect.Transitions.full
    }, arguments[1] || {});
    var A = {
        top: C.style.top,
        left: C.style.left,
        height: C.style.height,
        width: C.style.width,
        opacity: C.getInlineOpacity()
    };
    var G = C.getDimensions();
    var H, F;
    var E, D;
    switch (B.direction) {
        case"top-left":
            H = F = E = D = 0;
            break;
        case"top-right":
            H = G.width;
            F = D = 0;
            E = -G.width;
            break;
        case"bottom-left":
            H = E = 0;
            F = G.height;
            D = -G.height;
            break;
        case"bottom-right":
            H = G.width;
            F = G.height;
            E = -G.width;
            D = -G.height;
            break;
        case"center":
            H = G.width / 2;
            F = G.height / 2;
            E = -G.width / 2;
            D = -G.height / 2;
            break
    }
    return new Effect.Move(C, {
        x: H, y: F, duration: 0.01, beforeSetup: function (I) {
            I.element.hide().makeClipping().makePositioned()
        }, afterFinishInternal: function (I) {
            new Effect.Parallel([new Effect.Opacity(I.element, {
                sync: true,
                to: 1,
                from: 0,
                transition: B.opacityTransition
            }), new Effect.Move(I.element, {
                x: E,
                y: D,
                sync: true,
                transition: B.moveTransition
            }), new Effect.Scale(I.element, 100, {
                scaleMode: {originalHeight: G.height, originalWidth: G.width},
                sync: true,
                scaleFrom: window.opera ? 1 : 0,
                transition: B.scaleTransition,
                restoreAfterFinish: true
            })], Object.extend({
                beforeSetup: function (J) {
                    J.effects[0].element.setStyle({height: "0px"}).show()
                }, afterFinishInternal: function (J) {
                    J.effects[0].element.undoClipping().undoPositioned().setStyle(A)
                }
            }, B))
        }
    })
};
Effect.Shrink = function (C) {
    C = $$(C);
    var B = Object.extend({
        direction: "center",
        moveTransition: Effect.Transitions.sinoidal,
        scaleTransition: Effect.Transitions.sinoidal,
        opacityTransition: Effect.Transitions.none
    }, arguments[1] || {});
    var A = {
        top: C.style.top,
        left: C.style.left,
        height: C.style.height,
        width: C.style.width,
        opacity: C.getInlineOpacity()
    };
    var F = C.getDimensions();
    var E, D;
    switch (B.direction) {
        case"top-left":
            E = D = 0;
            break;
        case"top-right":
            E = F.width;
            D = 0;
            break;
        case"bottom-left":
            E = 0;
            D = F.height;
            break;
        case"bottom-right":
            E = F.width;
            D = F.height;
            break;
        case"center":
            E = F.width / 2;
            D = F.height / 2;
            break
    }
    return new Effect.Parallel([new Effect.Opacity(C, {
        sync: true,
        to: 0,
        from: 1,
        transition: B.opacityTransition
    }), new Effect.Scale(C, window.opera ? 1 : 0, {
        sync: true,
        transition: B.scaleTransition,
        restoreAfterFinish: true
    }), new Effect.Move(C, {
        x: E,
        y: D,
        sync: true,
        transition: B.moveTransition
    })], Object.extend({
        beforeStartInternal: function (G) {
            G.effects[0].element.makePositioned().makeClipping()
        }, afterFinishInternal: function (G) {
            G.effects[0].element.hide().undoClipping().undoPositioned().setStyle(A)
        }
    }, B))
};
Effect.Pulsate = function (C) {
    C = $$(C);
    var B = arguments[1] || {}, A = C.getInlineOpacity(), E = B.transition || Effect.Transitions.linear, D = function (F) {
        return 1 - E((-Math.cos((F * (B.pulses || 5) * 2) * Math.PI) / 2) + 0.5)
    };
    return new Effect.Opacity(C, Object.extend(Object.extend({
        duration: 2, from: 0, afterFinishInternal: function (F) {
            F.element.setStyle({opacity: A})
        }
    }, B), {transition: D}))
};
Effect.Fold = function (B) {
    B = $$(B);
    var A = {top: B.style.top, left: B.style.left, width: B.style.width, height: B.style.height};
    B.makeClipping();
    return new Effect.Scale(B, 5, Object.extend({
        scaleContent: false, scaleX: false, afterFinishInternal: function (C) {
            new Effect.Scale(B, 1, {
                scaleContent: false, scaleY: false, afterFinishInternal: function (D) {
                    D.element.hide().undoClipping().setStyle(A)
                }
            })
        }
    }, arguments[1] || {}))
};
Effect.Morph = Class.create(Effect.Base, {
    initialize: function (C) {
        this.element = $$(C);
        if (!this.element) {
            throw (Effect._elementDoesNotExistError)
        }
        var A = Object.extend({style: {}}, arguments[1] || {});
        if (!Object.isString(A.style)) {
            this.style = $H(A.style)
        } else {
            if (A.style.include(":")) {
                this.style = A.style.parseStyle()
            } else {
                this.element.addClassName(A.style);
                this.style = $H(this.element.getStyles());
                this.element.removeClassName(A.style);
                var B = this.element.getStyles();
                this.style = this.style.reject(function (D) {
                    return D.value == B[D.key]
                });
                A.afterFinishInternal = function (D) {
                    D.element.addClassName(D.options.style);
                    D.transforms.each(function (E) {
                        D.element.style[E.style] = ""
                    })
                }
            }
        }
        this.start(A)
    }, setup: function () {
        function A(B) {
            if (!B || ["rgba(0, 0, 0, 0)", "transparent"].include(B)) {
                B = "#ffffff"
            }
            B = B.parseColor();
            return $R(0, 2).map(function (C) {
                return parseInt(B.slice(C * 2 + 1, C * 2 + 3), 16)
            })
        }

        this.transforms = this.style.map(function (G) {
            var F = G[0], E = G[1], D = null;
            if (E.parseColor("#zzzzzz") != "#zzzzzz") {
                E = E.parseColor();
                D = "color"
            } else {
                if (F == "opacity") {
                    E = parseFloat(E);
                    if (Prototype.Browser.IE && (!this.element.currentStyle.hasLayout)) {
                        this.element.setStyle({zoom: 1})
                    }
                } else {
                    if (Element.CSS_LENGTH.test(E)) {
                        var C = E.match(/^([\+\-]?[0-9\.]+)(.*)$/);
                        E = parseFloat(C[1]);
                        D = (C.length == 3) ? C[2] : null
                    }
                }
            }
            var B = this.element.getStyle(F);
            return {
                style: F.camelize(),
                originalValue: D == "color" ? A(B) : parseFloat(B || 0),
                targetValue: D == "color" ? A(E) : E,
                unit: D
            }
        }.bind(this)).reject(function (B) {
            return ((B.originalValue == B.targetValue) || (B.unit != "color" && (isNaN(B.originalValue) || isNaN(B.targetValue))))
        })
    }, update: function (A) {
        var D = {}, B, C = this.transforms.length;
        while (C--) {
            D[(B = this.transforms[C]).style] = B.unit == "color" ? "#" + (Math.round(B.originalValue[0] + (B.targetValue[0] - B.originalValue[0]) * A)).toColorPart() + (Math.round(B.originalValue[1] + (B.targetValue[1] - B.originalValue[1]) * A)).toColorPart() + (Math.round(B.originalValue[2] + (B.targetValue[2] - B.originalValue[2]) * A)).toColorPart() : (B.originalValue + (B.targetValue - B.originalValue) * A).toFixed(3) + (B.unit === null ? "" : B.unit)
        }
        this.element.setStyle(D, true)
    }
});
Effect.Transform = Class.create({
    initialize: function (A) {
        this.tracks = [];
        this.options = arguments[1] || {};
        this.addTracks(A)
    }, addTracks: function (A) {
        A.each(function (B) {
            B = $H(B);
            var C = B.values().first();
            this.tracks.push($H({ids: B.keys().first(), effect: Effect.Morph, options: {style: C}}))
        }.bind(this));
        return this
    }, play: function () {
        return new Effect.Parallel(this.tracks.map(function (A) {
            var D = A.get("ids"), C = A.get("effect"), B = A.get("options");
            var E = [$$(D) || $$$(D)].flatten();
            return E.map(function (F) {
                return new C(F, Object.extend({sync: true}, B))
            })
        }).flatten(), this.options)
    }
});
Element.CSS_PROPERTIES = $w("backgroundColor backgroundPosition borderBottomColor borderBottomStyle borderBottomWidth borderLeftColor borderLeftStyle borderLeftWidth borderRightColor borderRightStyle borderRightWidth borderSpacing borderTopColor borderTopStyle borderTopWidth bottom clip color fontSize fontWeight height left letterSpacing lineHeight marginBottom marginLeft marginRight marginTop markerOffset maxHeight maxWidth minHeight minWidth opacity outlineColor outlineOffset outlineWidth paddingBottom paddingLeft paddingRight paddingTop right textIndent top width wordSpacing zIndex");
Element.CSS_LENGTH = /^(([\+\-]?[0-9\.]+)(em|ex|px|in|cm|mm|pt|pc|\%))|0$/;
String.__parseStyleElement = document.createElement("div");
String.prototype.parseStyle = function () {
    var B, A = $H();
    if (Prototype.Browser.WebKit) {
        B = new Element("div", {style: this}).style
    } else {
        String.__parseStyleElement.innerHTML = '<div style="' + this + '"></div>';
        B = String.__parseStyleElement.childNodes[0].style
    }
    Element.CSS_PROPERTIES.each(function (C) {
        if (B[C]) {
            A.set(C, B[C])
        }
    });
    if (Prototype.Browser.IE && this.include("opacity")) {
        A.set("opacity", this.match(/opacity:\s*((?:0|1)?(?:\.\d*)?)/)[1])
    }
    return A
};
if (document.defaultView && document.defaultView.getComputedStyle) {
    Element.getStyles = function (B) {
        var A = document.defaultView.getComputedStyle($$(B), null);
        return Element.CSS_PROPERTIES.inject({}, function (C, D) {
            C[D] = A[D];
            return C
        })
    }
} else {
    Element.getStyles = function (B) {
        B = $$(B);
        var A = B.currentStyle, C;
        C = Element.CSS_PROPERTIES.inject({}, function (D, E) {
            D[E] = A[E];
            return D
        });
        if (!C.opacity) {
            C.opacity = B.getOpacity()
        }
        return C
    }
}
Effect.Methods = {
    morph: function (A, B) {
        A = $$(A);
        new Effect.Morph(A, Object.extend({style: B}, arguments[2] || {}));
        return A
    }, visualEffect: function (C, E, B) {
        C = $$(C);
        var D = E.dasherize().camelize(), A = D.charAt(0).toUpperCase() + D.substring(1);
        new Effect[A](C, B);
        return C
    }, highlight: function (B, A) {
        B = $$(B);
        new Effect.Highlight(B, A);
        return B
    }
};
$w("fade appear grow shrink fold blindUp blindDown slideUp slideDown pulsate shake puff squish switchOff dropOut").each(function (A) {
    Effect.Methods[A] = function (C, B) {
        C = $$(C);
        Effect[A.charAt(0).toUpperCase() + A.substring(1)](C, B);
        return C
    }
});
$w("getInlineOpacity forceRerendering setContentZoom collectTextNodes collectTextNodesIgnoreClass getStyles").each(function (A) {
    Effect.Methods[A] = Element[A]
});
Element.addMethods(Effect.Methods);
if (Object.isUndefined(Effect)) {
    throw ("dragdrop.js requires including script.aculo.us' effects.js library")
}
var Droppables = {
    drops: [], remove: function (A) {
        this.drops = this.drops.reject(function (B) {
            return B.element == $$(A)
        })
    }, add: function (B) {
        B = $$(B);
        var A = Object.extend({greedy: true, hoverclass: null, tree: false}, arguments[1] || {});
        if (A.containment) {
            A._containers = [];
            var C = A.containment;
            if (Object.isArray(C)) {
                C.each(function (D) {
                    A._containers.push($$(D))
                })
            } else {
                A._containers.push($$(C))
            }
        }
        if (A.accept) {
            A.accept = [A.accept].flatten()
        }
        Element.makePositioned(B);
        A.element = B;
        this.drops.push(A)
    }, findDeepestChild: function (A) {
        deepest = A[0];
        for (i = 1; i < A.length; ++i) {
            if (Element.isParent(A[i].element, deepest.element)) {
                deepest = A[i]
            }
        }
        return deepest
    }, isContained: function (B, A) {
        var C;
        if (A.tree) {
            C = B.treeNode
        } else {
            C = B.parentNode
        }
        return A._containers.detect(function (D) {
            return C == D
        })
    }, isAffected: function (A, C, B) {
        return ((B.element != C) && ((!B._containers) || this.isContained(C, B)) && ((!B.accept) || (Element.classNames(C).detect(function (D) {
            return B.accept.include(D)
        }))) && Position.within(B.element, A[0], A[1]))
    }, deactivate: function (A) {
        if (A.hoverclass) {
            Element.removeClassName(A.element, A.hoverclass)
        }
        this.last_active = null
    }, activate: function (A) {
        if (A.hoverclass) {
            Element.addClassName(A.element, A.hoverclass)
        }
        this.last_active = A
    }, show: function (A, C) {
        if (!this.drops.length) {
            return
        }
        var B, D = [];
        this.drops.each(function (E) {
            if (Droppables.isAffected(A, C, E)) {
                D.push(E)
            }
        });
        if (D.length > 0) {
            B = Droppables.findDeepestChild(D)
        }
        if (this.last_active && this.last_active != B) {
            this.deactivate(this.last_active)
        }
        if (B) {
            Position.within(B.element, A[0], A[1]);
            if (B.onHover) {
                B.onHover(C, B.element, Position.overlap(B.overlap, B.element))
            }
            if (B != this.last_active) {
                Droppables.activate(B)
            }
        }
    }, fire: function (B, A) {
        if (!this.last_active) {
            return
        }
        Position.prepare();
        if (this.isAffected([Event.pointerX(B), Event.pointerY(B)], A, this.last_active)) {
            if (this.last_active.onDrop) {
                this.last_active.onDrop(A, this.last_active.element, B);
                return true
            }
        }
    }, reset: function () {
        if (this.last_active) {
            this.deactivate(this.last_active)
        }
    }
};
var Draggables = {
    drags: [], observers: [], register: function (A) {
        if (this.drags.length == 0) {
            this.eventMouseUp = this.endDrag.bindAsEventListener(this);
            this.eventMouseMove = this.updateDrag.bindAsEventListener(this);
            this.eventKeypress = this.keyPress.bindAsEventListener(this);
            Event.observe(document, "mouseup", this.eventMouseUp);
            Event.observe(document, "mousemove", this.eventMouseMove);
            Event.observe(document, "keypress", this.eventKeypress)
        }
        this.drags.push(A)
    }, unregister: function (A) {
        this.drags = this.drags.reject(function (B) {
            return B == A
        });
        if (this.drags.length == 0) {
            Event.stopObserving(document, "mouseup", this.eventMouseUp);
            Event.stopObserving(document, "mousemove", this.eventMouseMove);
            Event.stopObserving(document, "keypress", this.eventKeypress)
        }
    }, activate: function (A) {
        if (A.options.delay) {
            this._timeout = setTimeout(function () {
                Draggables._timeout = null;
                window.focus();
                Draggables.activeDraggable = A
            }.bind(this), A.options.delay)
        } else {
            window.focus();
            this.activeDraggable = A
        }
    }, deactivate: function () {
        this.activeDraggable = null
    }, updateDrag: function (A) {
        if (!this.activeDraggable) {
            return
        }
        var B = [Event.pointerX(A), Event.pointerY(A)];
        if (this._lastPointer && (this._lastPointer.inspect() == B.inspect())) {
            return
        }
        this._lastPointer = B;
        this.activeDraggable.updateDrag(A, B)
    }, endDrag: function (A) {
        if (this._timeout) {
            clearTimeout(this._timeout);
            this._timeout = null
        }
        if (!this.activeDraggable) {
            return
        }
        this._lastPointer = null;
        this.activeDraggable.endDrag(A);
        this.activeDraggable = null
    }, keyPress: function (A) {
        if (this.activeDraggable) {
            this.activeDraggable.keyPress(A)
        }
    }, addObserver: function (A) {
        this.observers.push(A);
        this._cacheObserverCallbacks()
    }, removeObserver: function (A) {
        this.observers = this.observers.reject(function (B) {
            return B.element == A
        });
        this._cacheObserverCallbacks()
    }, notify: function (B, A, C) {
        if (this[B + "Count"] > 0) {
            this.observers.each(function (D) {
                if (D[B]) {
                    D[B](B, A, C)
                }
            })
        }
        if (A.options[B]) {
            A.options[B](A, C)
        }
    }, _cacheObserverCallbacks: function () {
        ["onStart", "onEnd", "onDrag"].each(function (A) {
            Draggables[A + "Count"] = Draggables.observers.select(function (B) {
                return B[A]
            }).length
        })
    }
};
var Draggable = Class.create({
    initialize: function (B) {
        var C = {
            handle: false,
            reverteffect: function (F, E, D) {
                var G = Math.sqrt(Math.abs(E ^ 2) + Math.abs(D ^ 2)) * 0.02;
                new Effect.Move(F, {x: -D, y: -E, duration: G, queue: {scope: "_draggable", position: "end"}})
            },
            endeffect: function (E) {
                var D = Object.isNumber(E._opacity) ? E._opacity : 1;
                new Effect.Opacity(E, {
                    duration: 0.2,
                    from: 0.7,
                    to: D,
                    queue: {scope: "_draggable", position: "end"},
                    afterFinish: function () {
                        Draggable._dragging[E] = false
                    }
                })
            },
            zindex: 1000,
            revert: false,
            quiet: false,
            scroll: false,
            scrollSensitivity: 20,
            scrollSpeed: 15,
            snap: false,
            delay: 0
        };
        if (!arguments[1] || Object.isUndefined(arguments[1].endeffect)) {
            Object.extend(C, {
                starteffect: function (D) {
                    D._opacity = Element.getOpacity(D);
                    Draggable._dragging[D] = true;
                    new Effect.Opacity(D, {duration: 0.2, from: D._opacity, to: 0.7})
                }
            })
        }
        var A = Object.extend(C, arguments[1] || {});
        this.element = $$(B);
        if (A.handle && Object.isString(A.handle)) {
            this.handle = this.element.down("." + A.handle, 0)
        }
        if (!this.handle) {
            this.handle = $$(A.handle)
        }
        if (!this.handle) {
            this.handle = this.element
        }
        if (A.scroll && !A.scroll.scrollTo && !A.scroll.outerHTML) {
            A.scroll = $$(A.scroll);
            this._isScrollChild = Element.childOf(this.element, A.scroll)
        }
        Element.makePositioned(this.element);
        this.options = A;
        this.dragging = false;
        this.eventMouseDown = this.initDrag.bindAsEventListener(this);
        Event.observe(this.handle, "mousedown", this.eventMouseDown);
        Draggables.register(this)
    }, destroy: function () {
        Event.stopObserving(this.handle, "mousedown", this.eventMouseDown);
        Draggables.unregister(this)
    }, currentDelta: function () {
        return ([parseInt(Element.getStyle(this.element, "left") || "0"), parseInt(Element.getStyle(this.element, "top") || "0")])
    }, initDrag: function (A) {
        if (!Object.isUndefined(Draggable._dragging[this.element]) && Draggable._dragging[this.element]) {
            return
        }
        if (Event.isLeftClick(A)) {
            var C = Event.element(A);
            if ((tag_name = C.tagName.toUpperCase()) && (tag_name == "INPUT" || tag_name == "SELECT" || tag_name == "OPTION" || tag_name == "BUTTON" || tag_name == "TEXTAREA")) {
                return
            }
            var B = [Event.pointerX(A), Event.pointerY(A)];
            var D = this.element.cumulativeOffset();
            this.offset = [0, 1].map(function (E) {
                return (B[E] - D[E])
            });
            Draggables.activate(this);
            Event.stop(A)
        }
    }, startDrag: function (B) {
        this.dragging = true;
        if (!this.delta) {
            this.delta = this.currentDelta()
        }
        if (this.options.zindex) {
            this.originalZ = parseInt(Element.getStyle(this.element, "z-index") || 0);
            this.element.style.zIndex = this.options.zindex
        }
        if (this.options.ghosting) {
            this._clone = this.element.cloneNode(true);
            this._originallyAbsolute = (this.element.getStyle("position") == "absolute");
            if (!this._originallyAbsolute) {
                Position.absolutize(this.element)
            }
            this.element.parentNode.insertBefore(this._clone, this.element)
        }
        if (this.options.scroll) {
            if (this.options.scroll == window) {
                var A = this._getWindowScroll(this.options.scroll);
                this.originalScrollLeft = A.left;
                this.originalScrollTop = A.top
            } else {
                this.originalScrollLeft = this.options.scroll.scrollLeft;
                this.originalScrollTop = this.options.scroll.scrollTop
            }
        }
        Draggables.notify("onStart", this, B);
        if (this.options.starteffect) {
            this.options.starteffect(this.element)
        }
    }, updateDrag: function (event, pointer) {
        if (!this.dragging) {
            this.startDrag(event)
        }
        if (!this.options.quiet) {
            Position.prepare();
            Droppables.show(pointer, this.element)
        }
        Draggables.notify("onDrag", this, event);
        this.draw(pointer);
        if (this.options.change) {
            this.options.change(this)
        }
        if (this.options.scroll) {
            this.stopScrolling();
            var p;
            if (this.options.scroll == window) {
                with (this._getWindowScroll(this.options.scroll)) {
                    p = [left, top, left + width, top + height]
                }
            } else {
                p = Position.page(this.options.scroll);
                p[0] += this.options.scroll.scrollLeft + Position.deltaX;
                p[1] += this.options.scroll.scrollTop + Position.deltaY;
                p.push(p[0] + this.options.scroll.offsetWidth);
                p.push(p[1] + this.options.scroll.offsetHeight)
            }
            var speed = [0, 0];
            if (pointer[0] < (p[0] + this.options.scrollSensitivity)) {
                speed[0] = pointer[0] - (p[0] + this.options.scrollSensitivity)
            }
            if (pointer[1] < (p[1] + this.options.scrollSensitivity)) {
                speed[1] = pointer[1] - (p[1] + this.options.scrollSensitivity)
            }
            if (pointer[0] > (p[2] - this.options.scrollSensitivity)) {
                speed[0] = pointer[0] - (p[2] - this.options.scrollSensitivity)
            }
            if (pointer[1] > (p[3] - this.options.scrollSensitivity)) {
                speed[1] = pointer[1] - (p[3] - this.options.scrollSensitivity)
            }
            this.startScrolling(speed)
        }
        if (Prototype.Browser.WebKit) {
            window.scrollBy(0, 0)
        }
        Event.stop(event)
    }, finishDrag: function (B, E) {
        this.dragging = false;
        if (this.options.quiet) {
            Position.prepare();
            var D = [Event.pointerX(B), Event.pointerY(B)];
            Droppables.show(D, this.element)
        }
        if (this.options.ghosting) {
            if (!this._originallyAbsolute) {
                Position.relativize(this.element)
            }
            delete this._originallyAbsolute;
            Element.remove(this._clone);
            this._clone = null
        }
        var F = false;
        if (E) {
            F = Droppables.fire(B, this.element);
            if (!F) {
                F = false
            }
        }
        if (F && this.options.onDropped) {
            this.options.onDropped(this.element)
        }
        Draggables.notify("onEnd", this, B);
        var A = this.options.revert;
        if (A && Object.isFunction(A)) {
            A = A(this.element)
        }
        var C = this.currentDelta();
        if (A && this.options.reverteffect) {
            if (F == 0 || A != "failure") {
                this.options.reverteffect(this.element, C[1] - this.delta[1], C[0] - this.delta[0])
            }
        } else {
            this.delta = C
        }
        if (this.options.zindex) {
            this.element.style.zIndex = this.originalZ
        }
        if (this.options.endeffect) {
            this.options.endeffect(this.element)
        }
        Draggables.deactivate(this);
        Droppables.reset()
    }, keyPress: function (A) {
        if (A.keyCode != Event.KEY_ESC) {
            return
        }
        this.finishDrag(A, false);
        Event.stop(A)
    }, endDrag: function (A) {
        if (!this.dragging) {
            return
        }
        this.stopScrolling();
        this.finishDrag(A, true);
        Event.stop(A)
    }, draw: function (A) {
        var F = this.element.cumulativeOffset();
        if (this.options.ghosting) {
            var C = Position.realOffset(this.element);
            F[0] += C[0] - Position.deltaX;
            F[1] += C[1] - Position.deltaY
        }
        var E = this.currentDelta();
        F[0] -= E[0];
        F[1] -= E[1];
        if (this.options.scroll && (this.options.scroll != window && this._isScrollChild)) {
            F[0] -= this.options.scroll.scrollLeft - this.originalScrollLeft;
            F[1] -= this.options.scroll.scrollTop - this.originalScrollTop
        }
        var D = [0, 1].map(function (G) {
            return (A[G] - F[G] - this.offset[G])
        }.bind(this));
        if (this.options.snap) {
            if (Object.isFunction(this.options.snap)) {
                D = this.options.snap(D[0], D[1], this)
            } else {
                if (Object.isArray(this.options.snap)) {
                    D = D.map(function (G, H) {
                        return (G / this.options.snap[H]).round() * this.options.snap[H]
                    }.bind(this))
                } else {
                    D = D.map(function (G) {
                        return (G / this.options.snap).round() * this.options.snap
                    }.bind(this))
                }
            }
        }
        var B = this.element.style;
        if ((!this.options.constraint) || (this.options.constraint == "horizontal")) {
            B.left = D[0] + "px"
        }
        if ((!this.options.constraint) || (this.options.constraint == "vertical")) {
            B.top = D[1] + "px"
        }
        if (B.visibility == "hidden") {
            B.visibility = ""
        }
    }, stopScrolling: function () {
        if (this.scrollInterval) {
            clearInterval(this.scrollInterval);
            this.scrollInterval = null;
            Draggables._lastScrollPointer = null
        }
    }, startScrolling: function (A) {
        if (!(A[0] || A[1])) {
            return
        }
        this.scrollSpeed = [A[0] * this.options.scrollSpeed, A[1] * this.options.scrollSpeed];
        this.lastScrolled = new Date();
        this.scrollInterval = setInterval(this.scroll.bind(this), 10)
    }, scroll: function () {
        var current = new Date();
        var delta = current - this.lastScrolled;
        this.lastScrolled = current;
        if (this.options.scroll == window) {
            with (this._getWindowScroll(this.options.scroll)) {
                if (this.scrollSpeed[0] || this.scrollSpeed[1]) {
                    var d = delta / 1000;
                    this.options.scroll.scrollTo(left + d * this.scrollSpeed[0], top + d * this.scrollSpeed[1])
                }
            }
        } else {
            this.options.scroll.scrollLeft += this.scrollSpeed[0] * delta / 1000;
            this.options.scroll.scrollTop += this.scrollSpeed[1] * delta / 1000
        }
        Position.prepare();
        Droppables.show(Draggables._lastPointer, this.element);
        Draggables.notify("onDrag", this);
        if (this._isScrollChild) {
            Draggables._lastScrollPointer = Draggables._lastScrollPointer || $A(Draggables._lastPointer);
            Draggables._lastScrollPointer[0] += this.scrollSpeed[0] * delta / 1000;
            Draggables._lastScrollPointer[1] += this.scrollSpeed[1] * delta / 1000;
            if (Draggables._lastScrollPointer[0] < 0) {
                Draggables._lastScrollPointer[0] = 0
            }
            if (Draggables._lastScrollPointer[1] < 0) {
                Draggables._lastScrollPointer[1] = 0
            }
            this.draw(Draggables._lastScrollPointer)
        }
        if (this.options.change) {
            this.options.change(this)
        }
    }, _getWindowScroll: function (w) {
        var T, L, W, H;
        with (w.document) {
            if (w.document.documentElement && documentElement.scrollTop) {
                T = documentElement.scrollTop;
                L = documentElement.scrollLeft
            } else {
                if (w.document.body) {
                    T = body.scrollTop;
                    L = body.scrollLeft
                }
            }
            if (w.innerWidth) {
                W = w.innerWidth;
                H = w.innerHeight
            } else {
                if (w.document.documentElement && documentElement.clientWidth) {
                    W = documentElement.clientWidth;
                    H = documentElement.clientHeight
                } else {
                    W = body.offsetWidth;
                    H = body.offsetHeight
                }
            }
        }
        return {top: T, left: L, width: W, height: H}
    }
});
Draggable._dragging = {};
var SortableObserver = Class.create({
    initialize: function (B, A) {
        this.element = $$(B);
        this.observer = A;
        this.lastValue = Sortable.serialize(this.element)
    }, onStart: function () {
        this.lastValue = Sortable.serialize(this.element)
    }, onEnd: function () {
        Sortable.unmark();
        if (this.lastValue != Sortable.serialize(this.element)) {
            this.observer(this.element)
        }
    }
});
var Sortable = {
    SERIALIZE_RULE: /^[^_\-](?:[A-Za-z0-9\-\_]*)[_](.*)$/, sortables: {}, _findRootElement: function (A) {
        while (A.tagName.toUpperCase() != "BODY") {
            if (A.id && Sortable.sortables[A.id]) {
                return A
            }
            A = A.parentNode
        }
    }, options: function (A) {
        A = Sortable._findRootElement($$(A));
        if (!A) {
            return
        }
        return Sortable.sortables[A.id]
    }, destroy: function (A) {
        A = $$(A);
        var B = Sortable.sortables[A.id];
        if (B) {
            Draggables.removeObserver(B.element);
            B.droppables.each(function (C) {
                Droppables.remove(C)
            });
            B.draggables.invoke("destroy");
            delete Sortable.sortables[B.element.id]
        }
    }, create: function (C) {
        C = $$(C);
        var B = Object.extend({
            element: C,
            tag: "li",
            dropOnEmpty: false,
            tree: false,
            treeTag: "ul",
            overlap: "vertical",
            constraint: "vertical",
            containment: C,
            handle: false,
            only: false,
            delay: 0,
            hoverclass: null,
            ghosting: false,
            quiet: false,
            scroll: false,
            scrollSensitivity: 20,
            scrollSpeed: 15,
            format: this.SERIALIZE_RULE,
            elements: false,
            handles: false,
            onChange: Prototype.emptyFunction,
            onUpdate: Prototype.emptyFunction
        }, arguments[1] || {});
        this.destroy(C);
        var A = {
            revert: true,
            quiet: B.quiet,
            scroll: B.scroll,
            scrollSpeed: B.scrollSpeed,
            scrollSensitivity: B.scrollSensitivity,
            delay: B.delay,
            ghosting: B.ghosting,
            constraint: B.constraint,
            handle: B.handle
        };
        if (B.starteffect) {
            A.starteffect = B.starteffect
        }
        if (B.reverteffect) {
            A.reverteffect = B.reverteffect
        } else {
            if (B.ghosting) {
                A.reverteffect = function (F) {
                    F.style.top = 0;
                    F.style.left = 0
                }
            }
        }
        if (B.endeffect) {
            A.endeffect = B.endeffect
        }
        if (B.zindex) {
            A.zindex = B.zindex
        }
        var D = {
            overlap: B.overlap,
            containment: B.containment,
            tree: B.tree,
            hoverclass: B.hoverclass,
            onHover: Sortable.onHover
        };
        var E = {
            onHover: Sortable.onEmptyHover,
            overlap: B.overlap,
            containment: B.containment,
            hoverclass: B.hoverclass
        };
        Element.cleanWhitespace(C);
        B.draggables = [];
        B.droppables = [];
        if (B.dropOnEmpty || B.tree) {
            Droppables.add(C, E);
            B.droppables.push(C)
        }
        (B.elements || this.findElements(C, B) || []).each(function (H, F) {
            var G = B.handles ? $$(B.handles[F]) : (B.handle ? $$(H).select("." + B.handle)[0] : H);
            B.draggables.push(new Draggable(H, Object.extend(A, {handle: G})));
            Droppables.add(H, D);
            if (B.tree) {
                H.treeNode = C
            }
            B.droppables.push(H)
        });
        if (B.tree) {
            (Sortable.findTreeElements(C, B) || []).each(function (F) {
                Droppables.add(F, E);
                F.treeNode = C;
                B.droppables.push(F)
            })
        }
        this.sortables[C.identify()] = B;
        Draggables.addObserver(new SortableObserver(C, B.onUpdate))
    }, findElements: function (B, A) {
        return Element.findChildren(B, A.only, A.tree ? true : false, A.tag)
    }, findTreeElements: function (B, A) {
        return Element.findChildren(B, A.only, A.tree ? true : false, A.treeTag)
    }, onHover: function (E, D, A) {
        if (Element.isParent(D, E)) {
            return
        }
        if (A > 0.33 && A < 0.66 && Sortable.options(D).tree) {
            return
        } else {
            if (A > 0.5) {
                Sortable.mark(D, "before");
                if (D.previousSibling != E) {
                    var B = E.parentNode;
                    E.style.visibility = "hidden";
                    D.parentNode.insertBefore(E, D);
                    if (D.parentNode != B) {
                        Sortable.options(B).onChange(E)
                    }
                    Sortable.options(D.parentNode).onChange(E)
                }
            } else {
                Sortable.mark(D, "after");
                var C = D.nextSibling || null;
                if (C != E) {
                    var B = E.parentNode;
                    E.style.visibility = "hidden";
                    D.parentNode.insertBefore(E, C);
                    if (D.parentNode != B) {
                        Sortable.options(B).onChange(E)
                    }
                    Sortable.options(D.parentNode).onChange(E)
                }
            }
        }
    }, onEmptyHover: function (E, G, H) {
        var I = E.parentNode;
        var A = Sortable.options(G);
        if (!Element.isParent(G, E)) {
            var F;
            var C = Sortable.findElements(G, {tag: A.tag, only: A.only});
            var B = null;
            if (C) {
                var D = Element.offsetSize(G, A.overlap) * (1 - H);
                for (F = 0; F < C.length; F += 1) {
                    if (D - Element.offsetSize(C[F], A.overlap) >= 0) {
                        D -= Element.offsetSize(C[F], A.overlap)
                    } else {
                        if (D - (Element.offsetSize(C[F], A.overlap) / 2) >= 0) {
                            B = F + 1 < C.length ? C[F + 1] : null;
                            break
                        } else {
                            B = C[F];
                            break
                        }
                    }
                }
            }
            G.insertBefore(E, B);
            Sortable.options(I).onChange(E);
            A.onChange(E)
        }
    }, unmark: function () {
        if (Sortable._marker) {
            Sortable._marker.hide()
        }
    }, mark: function (B, A) {
        var D = Sortable.options(B.parentNode);
        if (D && !D.ghosting) {
            return
        }
        if (!Sortable._marker) {
            Sortable._marker = ($$("dropmarker") || Element.extend(document.createElement("DIV"))).hide().addClassName("dropmarker").setStyle({position: "absolute"});
            document.getElementsByTagName("body").item(0).appendChild(Sortable._marker)
        }
        var C = B.cumulativeOffset();
        Sortable._marker.setStyle({left: C[0] + "px", top: C[1] + "px"});
        if (A == "after") {
            if (D.overlap == "horizontal") {
                Sortable._marker.setStyle({left: (C[0] + B.clientWidth) + "px"})
            } else {
                Sortable._marker.setStyle({top: (C[1] + B.clientHeight) + "px"})
            }
        }
        Sortable._marker.show()
    }, _tree: function (E, B, F) {
        var D = Sortable.findElements(E, B) || [];
        for (var C = 0; C < D.length; ++C) {
            var A = D[C].id.match(B.format);
            if (!A) {
                continue
            }
            var G = {
                id: encodeURIComponent(A ? A[1] : null),
                element: E,
                parent: F,
                children: [],
                position: F.children.length,
                container: $$(D[C]).down(B.treeTag)
            };
            if (G.container) {
                this._tree(G.container, B, G)
            }
            F.children.push(G)
        }
        return F
    }, tree: function (D) {
        D = $$(D);
        var C = this.options(D);
        var B = Object.extend({
            tag: C.tag,
            treeTag: C.treeTag,
            only: C.only,
            name: D.id,
            format: C.format
        }, arguments[1] || {});
        var A = {id: null, parent: null, children: [], container: D, position: 0};
        return Sortable._tree(D, B, A)
    }, _constructIndex: function (B) {
        var A = "";
        do {
            if (B.id) {
                A = "[" + B.position + "]" + A
            }
        } while ((B = B.parent) != null);
        return A
    }, sequence: function (B) {
        B = $$(B);
        var A = Object.extend(this.options(B), arguments[1] || {});
        return $$(this.findElements(B, A) || []).map(function (C) {
            return C.id.match(A.format) ? C.id.match(A.format)[1] : ""
        })
    }, setSequence: function (B, C) {
        B = $$(B);
        var A = Object.extend(this.options(B), arguments[2] || {});
        var D = {};
        this.findElements(B, A).each(function (E) {
            if (E.id.match(A.format)) {
                D[E.id.match(A.format)[1]] = [E, E.parentNode]
            }
            E.parentNode.removeChild(E)
        });
        C.each(function (E) {
            var F = D[E];
            if (F) {
                F[1].appendChild(F[0]);
                delete D[E]
            }
        })
    }, serialize: function (C) {
        C = $$(C);
        var B = Object.extend(Sortable.options(C), arguments[1] || {});
        var A = encodeURIComponent((arguments[1] && arguments[1].name) ? arguments[1].name : C.id);
        if (B.tree) {
            return Sortable.tree(C, arguments[1]).children.map(function (D) {
                return [A + Sortable._constructIndex(D) + "[id]=" + encodeURIComponent(D.id)].concat(D.children.map(arguments.callee))
            }).flatten().join("&")
        } else {
            return Sortable.sequence(C, arguments[1]).map(function (D) {
                return A + "[]=" + encodeURIComponent(D)
            }).join("&")
        }
    }
};
Element.isParent = function (B, A) {
    if (!B.parentNode || B == A) {
        return false
    }
    if (B.parentNode == A) {
        return true
    }
    return Element.isParent(B.parentNode, A)
};
Element.findChildren = function (D, B, A, C) {
    if (!D.hasChildNodes()) {
        return null
    }
    C = C.toUpperCase();
    if (B) {
        B = [B].flatten()
    }
    var E = [];
    $A(D.childNodes).each(function (G) {
        if (G.tagName && G.tagName.toUpperCase() == C && (!B || (Element.classNames(G).detect(function (H) {
                return B.include(H)
            })))) {
            E.push(G)
        }
        if (A) {
            var F = Element.findChildren(G, B, A, C);
            if (F) {
                E.push(F)
            }
        }
    });
    return (E.length > 0 ? E.flatten() : [])
};
Element.offsetSize = function (A, B) {
    return A["offset" + ((B == "vertical" || B == "height") ? "Height" : "Width")]
};
if (typeof Effect == "undefined") {
    throw ("controls.js requires including script.aculo.us' effects.js library")
}
var Autocompleter = {};
Autocompleter.Base = Class.create({
    baseInitialize: function (B, C, A) {
        B = $$(B);
        this.element = B;
        this.update = $$(C);
        this.hasFocus = false;
        this.changed = false;
        this.active = false;
        this.index = 0;
        this.entryCount = 0;
        this.oldElementValue = this.element.value;
        if (this.setOptions) {
            this.setOptions(A)
        } else {
            this.options = A || {}
        }
        this.options.paramName = this.options.paramName || this.element.name;
        this.options.tokens = this.options.tokens || [];
        this.options.frequency = this.options.frequency || 0.4;
        this.options.minChars = this.options.minChars || 1;
        this.options.onShow = this.options.onShow || function (D, E) {
            if (!E.style.position || E.style.position == "absolute") {
                E.style.position = "absolute";
                Position.clone(D, E, {setHeight: false, offsetTop: D.offsetHeight})
            }
            Effect.Appear(E, {duration: 0.15})
        };
        this.options.onHide = this.options.onHide || function (D, E) {
            new Effect.Fade(E, {duration: 0.15})
        };
        if (typeof (this.options.tokens) == "string") {
            this.options.tokens = new Array(this.options.tokens)
        }
        if (!this.options.tokens.include("\n")) {
            this.options.tokens.push("\n")
        }
        this.observer = null;
        this.element.setAttribute("autocomplete", "off");
        Element.hide(this.update);
        Event.observe(this.element, "blur", this.onBlur.bindAsEventListener(this));
        Event.observe(this.element, "keydown", this.onKeyPress.bindAsEventListener(this))
    }, show: function () {
        if (Element.getStyle(this.update, "display") == "none") {
            this.options.onShow(this.element, this.update)
        }
        if (!this.iefix && (Prototype.Browser.IE) && (Element.getStyle(this.update, "position") == "absolute")) {
            new Insertion.After(this.update, '<iframe id="' + this.update.id + '_iefix" style="display:none;position:absolute;filter:progid:DXImageTransform.Microsoft.Alpha(opacity=0);" src="javascript:false;" frameborder="0" scrolling="no"></iframe>');
            this.iefix = $$(this.update.id + "_iefix")
        }
        if (this.iefix) {
            setTimeout(this.fixIEOverlapping.bind(this), 50)
        }
    }, fixIEOverlapping: function () {
        Position.clone(this.update, this.iefix, {setTop: (!this.update.style.height)});
        this.iefix.style.zIndex = 1;
        this.update.style.zIndex = 2;
        Element.show(this.iefix)
    }, hide: function () {
        this.stopIndicator();
        if (Element.getStyle(this.update, "display") != "none") {
            this.options.onHide(this.element, this.update)
        }
        if (this.iefix) {
            Element.hide(this.iefix)
        }
    }, startIndicator: function () {
        if (this.options.indicator) {
            Element.show(this.options.indicator)
        }
    }, stopIndicator: function () {
        if (this.options.indicator) {
            Element.hide(this.options.indicator)
        }
    }, onKeyPress: function (A) {
        if (this.active) {
            switch (A.keyCode) {
                case Event.KEY_TAB:
                case Event.KEY_RETURN:
                    this.selectEntry();
                    Event.stop(A);
                case Event.KEY_ESC:
                    this.hide();
                    this.active = false;
                    Event.stop(A);
                    return;
                case Event.KEY_LEFT:
                case Event.KEY_RIGHT:
                    return;
                case Event.KEY_UP:
                    this.markPrevious();
                    this.render();
                    Event.stop(A);
                    return;
                case Event.KEY_DOWN:
                    this.markNext();
                    this.render();
                    Event.stop(A);
                    return
            }
        } else {
            if (A.keyCode == Event.KEY_TAB || A.keyCode == Event.KEY_RETURN || (Prototype.Browser.WebKit > 0 && A.keyCode == 0)) {
                return
            }
        }
        this.changed = true;
        this.hasFocus = true;
        if (this.observer) {
            clearTimeout(this.observer)
        }
        this.observer = setTimeout(this.onObserverEvent.bind(this), this.options.frequency * 1000)
    }, activate: function () {
        this.changed = false;
        this.hasFocus = true;
        this.getUpdatedChoices()
    }, onHover: function (B) {
        var A = Event.findElement(B, "LI");
        if (this.index != A.autocompleteIndex) {
            this.index = A.autocompleteIndex;
            this.render()
        }
        Event.stop(B)
    }, onClick: function (B) {
        var A = Event.findElement(B, "LI");
        this.index = A.autocompleteIndex;
        this.selectEntry();
        this.hide()
    }, onBlur: function (A) {
        setTimeout(this.hide.bind(this), 250);
        this.hasFocus = false;
        this.active = false
    }, render: function () {
        if (this.entryCount > 0) {
            for (var A = 0; A < this.entryCount; A++) {
                this.index == A ? Element.addClassName(this.getEntry(A), "selected") : Element.removeClassName(this.getEntry(A), "selected")
            }
            if (this.hasFocus) {
                this.show();
                this.active = true
            }
        } else {
            this.active = false;
            this.hide()
        }
    }, markPrevious: function () {
        if (this.index > 0) {
            this.index--
        } else {
            this.index = this.entryCount - 1
        }
        this.getEntry(this.index).scrollIntoView(true)
    }, markNext: function () {
        if (this.index < this.entryCount - 1) {
            this.index++
        } else {
            this.index = 0
        }
        this.getEntry(this.index).scrollIntoView(false)
    }, getEntry: function (A) {
        return this.update.firstChild.childNodes[A]
    }, getCurrentEntry: function () {
        return this.getEntry(this.index)
    }, selectEntry: function () {
        this.active = false;
        this.updateElement(this.getCurrentEntry())
    }, updateElement: function (F) {
        if (this.options.updateElement) {
            this.options.updateElement(F);
            return
        }
        var D = "";
        if (this.options.select) {
            var A = $$(F).select("." + this.options.select) || [];
            if (A.length > 0) {
                D = Element.collectTextNodes(A[0], this.options.select)
            }
        } else {
            D = Element.collectTextNodesIgnoreClass(F, "informal")
        }
        var C = this.getTokenBounds();
        if (C[0] != -1) {
            var E = this.element.value.substr(0, C[0]);
            var B = this.element.value.substr(C[0]).match(/^\s+/);
            if (B) {
                E += B[0]
            }
            this.element.value = E + D + this.element.value.substr(C[1])
        } else {
            this.element.value = D
        }
        this.oldElementValue = this.element.value;
        this.element.focus();
        if (this.options.afterUpdateElement) {
            this.options.afterUpdateElement(this.element, F)
        }
    }, updateChoices: function (C) {
        if (!this.changed && this.hasFocus) {
            this.update.innerHTML = C;
            Element.cleanWhitespace(this.update);
            Element.cleanWhitespace(this.update.down());
            if (this.update.firstChild && this.update.down().childNodes) {
                this.entryCount = this.update.down().childNodes.length;
                for (var A = 0; A < this.entryCount; A++) {
                    var B = this.getEntry(A);
                    B.autocompleteIndex = A;
                    this.addObservers(B)
                }
            } else {
                this.entryCount = 0
            }
            this.stopIndicator();
            this.index = 0;
            if (this.entryCount == 1 && this.options.autoSelect) {
                this.selectEntry();
                this.hide()
            } else {
                this.render()
            }
        }
    }, addObservers: function (A) {
        Event.observe(A, "mouseover", this.onHover.bindAsEventListener(this));
        Event.observe(A, "click", this.onClick.bindAsEventListener(this))
    }, onObserverEvent: function () {
        this.changed = false;
        this.tokenBounds = null;
        if (this.getToken().length >= this.options.minChars) {
            this.getUpdatedChoices()
        } else {
            this.active = false;
            this.hide()
        }
        this.oldElementValue = this.element.value
    }, getToken: function () {
        var A = this.getTokenBounds();
        return this.element.value.substring(A[0], A[1]).strip()
    }, getTokenBounds: function () {
        if (null != this.tokenBounds) {
            return this.tokenBounds
        }
        var E = this.element.value;
        if (E.strip().empty()) {
            return [-1, 0]
        }
        var F = arguments.callee.getFirstDifferencePos(E, this.oldElementValue);
        var H = (F == this.oldElementValue.length ? 1 : 0);
        var D = -1, C = E.length;
        var G;
        for (var B = 0, A = this.options.tokens.length; B < A; ++B) {
            G = E.lastIndexOf(this.options.tokens[B], F + H - 1);
            if (G > D) {
                D = G
            }
            G = E.indexOf(this.options.tokens[B], F + H);
            if (-1 != G && G < C) {
                C = G
            }
        }
        return (this.tokenBounds = [D + 1, C])
    }
});
Autocompleter.Base.prototype.getTokenBounds.getFirstDifferencePos = function (C, A) {
    var D = Math.min(C.length, A.length);
    for (var B = 0; B < D; ++B) {
        if (C[B] != A[B]) {
            return B
        }
    }
    return D
};
Ajax.Autocompleter = Class.create(Autocompleter.Base, {
    initialize: function (C, D, B, A) {
        this.baseInitialize(C, D, A);
        this.options.asynchronous = true;
        this.options.onComplete = this.onComplete.bind(this);
        this.options.defaultParams = this.options.parameters || null;
        this.url = B
    }, getUpdatedChoices: function () {
        this.startIndicator();
        var A = encodeURIComponent(this.options.paramName) + "=" + encodeURIComponent(this.getToken());
        this.options.parameters = this.options.callback ? this.options.callback(this.element, A) : A;
        if (this.options.defaultParams) {
            this.options.parameters += "&" + this.options.defaultParams
        }
        new Ajax.Request(this.url, this.options)
    }, onComplete: function (A) {
        this.updateChoices(A.responseText)
    }
});
Autocompleter.Local = Class.create(Autocompleter.Base, {
    initialize: function (B, D, C, A) {
        this.baseInitialize(B, D, A);
        this.options.array = C
    }, getUpdatedChoices: function () {
        this.updateChoices(this.options.selector(this))
    }, setOptions: function (A) {
        this.options = Object.extend({
            choices: 10,
            partialSearch: true,
            partialChars: 2,
            ignoreCase: true,
            fullSearch: false,
            selector: function (B) {
                var D = [];
                var C = [];
                var H = B.getToken();
                var G = 0;
                for (var E = 0; E < B.options.array.length && D.length < B.options.choices; E++) {
                    var F = B.options.array[E];
                    var I = B.options.ignoreCase ? F.toLowerCase().indexOf(H.toLowerCase()) : F.indexOf(H);
                    while (I != -1) {
                        if (I == 0 && F.length != H.length) {
                            D.push("<li><strong>" + F.substr(0, H.length) + "</strong>" + F.substr(H.length) + "</li>");
                            break
                        } else {
                            if (H.length >= B.options.partialChars && B.options.partialSearch && I != -1) {
                                if (B.options.fullSearch || /\s/.test(F.substr(I - 1, 1))) {
                                    C.push("<li>" + F.substr(0, I) + "<strong>" + F.substr(I, H.length) + "</strong>" + F.substr(I + H.length) + "</li>");
                                    break
                                }
                            }
                        }
                        I = B.options.ignoreCase ? F.toLowerCase().indexOf(H.toLowerCase(), I + 1) : F.indexOf(H, I + 1)
                    }
                }
                if (C.length) {
                    D = D.concat(C.slice(0, B.options.choices - D.length))
                }
                return "<ul>" + D.join("") + "</ul>"
            }
        }, A || {})
    }
});
Field.scrollFreeActivate = function (A) {
    setTimeout(function () {
        Field.activate(A)
    }, 1)
};
Ajax.InPlaceEditor = Class.create({
    initialize: function (C, B, A) {
        this.url = B;
        this.element = C = $$(C);
        this.prepareOptions();
        this._controls = {};
        arguments.callee.dealWithDeprecatedOptions(A);
        Object.extend(this.options, A || {});
        if (!this.options.formId && this.element.id) {
            this.options.formId = this.element.id + "-inplaceeditor";
            if ($$(this.options.formId)) {
                this.options.formId = ""
            }
        }
        if (this.options.externalControl) {
            this.options.externalControl = $$(this.options.externalControl)
        }
        if (!this.options.externalControl) {
            this.options.externalControlOnly = false
        }
        this._originalBackground = this.element.getStyle("background-color") || "transparent";
        this.element.title = this.options.clickToEditText;
        this._boundCancelHandler = this.handleFormCancellation.bind(this);
        this._boundComplete = (this.options.onComplete || Prototype.emptyFunction).bind(this);
        this._boundFailureHandler = this.handleAJAXFailure.bind(this);
        this._boundSubmitHandler = this.handleFormSubmission.bind(this);
        this._boundWrapperHandler = this.wrapUp.bind(this);
        this.registerListeners()
    }, checkForEscapeOrReturn: function (A) {
        if (!this._editing || A.ctrlKey || A.altKey || A.shiftKey) {
            return
        }
        if (Event.KEY_ESC == A.keyCode) {
            this.handleFormCancellation(A)
        } else {
            if (Event.KEY_RETURN == A.keyCode) {
                this.handleFormSubmission(A)
            }
        }
    }, createControl: function (G, C, B) {
        var E = this.options[G + "Control"];
        var F = this.options[G + "Text"];
        if ("button" == E) {
            var A = document.createElement("input");
            A.type = "submit";
            A.value = F;
            A.className = "editor_" + G + "_button";
            if ("cancel" == G) {
                A.onclick = this._boundCancelHandler
            }
            this._form.appendChild(A);
            this._controls[G] = A
        } else {
            if ("link" == E) {
                var D = document.createElement("a");
                D.href = "#";
                D.appendChild(document.createTextNode(F));
                D.onclick = "cancel" == G ? this._boundCancelHandler : this._boundSubmitHandler;
                D.className = "editor_" + G + "_link";
                if (B) {
                    D.className += " " + B
                }
                this._form.appendChild(D);
                this._controls[G] = D
            }
        }
    }, createEditField: function () {
        var C = (this.options.loadTextURL ? this.options.loadingText : this.getText());
        var B;
        if (1 >= this.options.rows && !/\r|\n/.test(this.getText())) {
            B = document.createElement("input");
            B.type = "text";
            var A = this.options.size || this.options.cols || 0;
            if (0 < A) {
                B.size = A
            }
        } else {
            B = document.createElement("textarea");
            B.rows = (1 >= this.options.rows ? this.options.autoRows : this.options.rows);
            B.cols = this.options.cols || 40
        }
        B.name = this.options.paramName;
        B.value = C;
        B.className = "editor_field";
        if (this.options.submitOnBlur) {
            B.onblur = this._boundSubmitHandler
        }
        this._controls.editor = B;
        if (this.options.loadTextURL) {
            this.loadExternalText()
        }
        this._form.appendChild(this._controls.editor)
    }, createForm: function () {
        var B = this;

        function A(D, E) {
            var C = B.options["text" + D + "Controls"];
            if (!C || E === false) {
                return
            }
            B._form.appendChild(document.createTextNode(C))
        }

        this._form = $$(document.createElement("form"));
        this._form.id = this.options.formId;
        this._form.addClassName(this.options.formClassName);
        this._form.onsubmit = this._boundSubmitHandler;
        this.createEditField();
        if ("textarea" == this._controls.editor.tagName.toLowerCase()) {
            this._form.appendChild(document.createElement("br"))
        }
        if (this.options.onFormCustomization) {
            this.options.onFormCustomization(this, this._form)
        }
        A("Before", this.options.okControl || this.options.cancelControl);
        this.createControl("ok", this._boundSubmitHandler);
        A("Between", this.options.okControl && this.options.cancelControl);
        this.createControl("cancel", this._boundCancelHandler, "editor_cancel");
        A("After", this.options.okControl || this.options.cancelControl)
    }, destroy: function () {
        if (this._oldInnerHTML) {
            this.element.innerHTML = this._oldInnerHTML
        }
        this.leaveEditMode();
        this.unregisterListeners()
    }, enterEditMode: function (A) {
        if (this._saving || this._editing) {
            return
        }
        this._editing = true;
        this.triggerCallback("onEnterEditMode");
        if (this.options.externalControl) {
            this.options.externalControl.hide()
        }
        this.element.hide();
        this.createForm();
        this.element.parentNode.insertBefore(this._form, this.element);
        if (!this.options.loadTextURL) {
            this.postProcessEditField()
        }
        if (A) {
            Event.stop(A)
        }
    }, enterHover: function (A) {
        if (this.options.hoverClassName) {
            this.element.addClassName(this.options.hoverClassName)
        }
        if (this._saving) {
            return
        }
        this.triggerCallback("onEnterHover")
    }, getText: function () {
        return this.element.innerHTML.unescapeHTML()
    }, handleAJAXFailure: function (A) {
        this.triggerCallback("onFailure", A);
        if (this._oldInnerHTML) {
            this.element.innerHTML = this._oldInnerHTML;
            this._oldInnerHTML = null
        }
    }, handleFormCancellation: function (A) {
        this.wrapUp();
        if (A) {
            Event.stop(A)
        }
    }, handleFormSubmission: function (D) {
        var B = this._form;
        var C = $F(this._controls.editor);
        this.prepareSubmission();
        var E = this.options.callback(B, C) || "";
        if (Object.isString(E)) {
            E = E.toQueryParams()
        }
        E.editorId = this.element.id;
        if (this.options.htmlResponse) {
            var A = Object.extend({evalScripts: true}, this.options.ajaxOptions);
            Object.extend(A, {
                parameters: E,
                onComplete: this._boundWrapperHandler,
                onFailure: this._boundFailureHandler
            });
            new Ajax.Updater({success: this.element}, this.url, A)
        } else {
            var A = Object.extend({method: "get"}, this.options.ajaxOptions);
            Object.extend(A, {
                parameters: E,
                onComplete: this._boundWrapperHandler,
                onFailure: this._boundFailureHandler
            });
            new Ajax.Request(this.url, A)
        }
        if (D) {
            Event.stop(D)
        }
    }, leaveEditMode: function () {
        this.element.removeClassName(this.options.savingClassName);
        this.removeForm();
        this.leaveHover();
        this.element.style.backgroundColor = this._originalBackground;
        this.element.show();
        if (this.options.externalControl) {
            this.options.externalControl.show()
        }
        this._saving = false;
        this._editing = false;
        this._oldInnerHTML = null;
        this.triggerCallback("onLeaveEditMode")
    }, leaveHover: function (A) {
        if (this.options.hoverClassName) {
            this.element.removeClassName(this.options.hoverClassName)
        }
        if (this._saving) {
            return
        }
        this.triggerCallback("onLeaveHover")
    }, loadExternalText: function () {
        this._form.addClassName(this.options.loadingClassName);
        this._controls.editor.disabled = true;
        var A = Object.extend({method: "get"}, this.options.ajaxOptions);
        Object.extend(A, {
            parameters: "editorId=" + encodeURIComponent(this.element.id),
            onComplete: Prototype.emptyFunction,
            onSuccess: function (C) {
                this._form.removeClassName(this.options.loadingClassName);
                var B = C.responseText;
                if (this.options.stripLoadedTextTags) {
                    B = B.stripTags()
                }
                this._controls.editor.value = B;
                this._controls.editor.disabled = false;
                this.postProcessEditField()
            }.bind(this),
            onFailure: this._boundFailureHandler
        });
        new Ajax.Request(this.options.loadTextURL, A)
    }, postProcessEditField: function () {
        var A = this.options.fieldPostCreation;
        if (A) {
            $$(this._controls.editor)["focus" == A ? "focus" : "activate"]()
        }
    }, prepareOptions: function () {
        this.options = Object.clone(Ajax.InPlaceEditor.DefaultOptions);
        Object.extend(this.options, Ajax.InPlaceEditor.DefaultCallbacks);
        [this._extraDefaultOptions].flatten().compact().each(function (A) {
            Object.extend(this.options, A)
        }.bind(this))
    }, prepareSubmission: function () {
        this._saving = true;
        this.removeForm();
        this.leaveHover();
        this.showSaving()
    }, registerListeners: function () {
        this._listeners = {};
        var A;
        $H(Ajax.InPlaceEditor.Listeners).each(function (B) {
            A = this[B.value].bind(this);
            this._listeners[B.key] = A;
            if (!this.options.externalControlOnly) {
                this.element.observe(B.key, A)
            }
            if (this.options.externalControl) {
                this.options.externalControl.observe(B.key, A)
            }
        }.bind(this))
    }, removeForm: function () {
        if (!this._form) {
            return
        }
        this._form.remove();
        this._form = null;
        this._controls = {}
    }, showSaving: function () {
        this._oldInnerHTML = this.element.innerHTML;
        this.element.innerHTML = this.options.savingText;
        this.element.addClassName(this.options.savingClassName);
        this.element.style.backgroundColor = this._originalBackground;
        this.element.show()
    }, triggerCallback: function (B, A) {
        if ("function" == typeof this.options[B]) {
            this.options[B](this, A)
        }
    }, unregisterListeners: function () {
        $H(this._listeners).each(function (A) {
            if (!this.options.externalControlOnly) {
                this.element.stopObserving(A.key, A.value)
            }
            if (this.options.externalControl) {
                this.options.externalControl.stopObserving(A.key, A.value)
            }
        }.bind(this))
    }, wrapUp: function (A) {
        this.leaveEditMode();
        this._boundComplete(A, this.element)
    }
});
Object.extend(Ajax.InPlaceEditor.prototype, {dispose: Ajax.InPlaceEditor.prototype.destroy});
Ajax.InPlaceCollectionEditor = Class.create(Ajax.InPlaceEditor, {
    initialize: function ($super, C, B, A) {
        this._extraDefaultOptions = Ajax.InPlaceCollectionEditor.DefaultOptions;
        $super(C, B, A)
    }, createEditField: function () {
        var A = document.createElement("select");
        A.name = this.options.paramName;
        A.size = 1;
        this._controls.editor = A;
        this._collection = this.options.collection || [];
        if (this.options.loadCollectionURL) {
            this.loadCollection()
        } else {
            this.checkForExternalText()
        }
        this._form.appendChild(this._controls.editor)
    }, loadCollection: function () {
        this._form.addClassName(this.options.loadingClassName);
        this.showLoadingText(this.options.loadingCollectionText);
        var options = Object.extend({method: "get"}, this.options.ajaxOptions);
        Object.extend(options, {
            parameters: "editorId=" + encodeURIComponent(this.element.id),
            onComplete: Prototype.emptyFunction,
            onSuccess: function (transport) {
                var js = transport.responseText.strip();
                if (!/^\[.*\]$/.test(js)) {
                    throw ("Server returned an invalid collection representation.")
                }
                this._collection = eval(js);
                this.checkForExternalText()
            }.bind(this),
            onFailure: this.onFailure
        });
        new Ajax.Request(this.options.loadCollectionURL, options)
    }, showLoadingText: function (B) {
        this._controls.editor.disabled = true;
        var A = this._controls.editor.firstChild;
        if (!A) {
            A = document.createElement("option");
            A.value = "";
            this._controls.editor.appendChild(A);
            A.selected = true
        }
        A.update((B || "").stripScripts().stripTags())
    }, checkForExternalText: function () {
        this._text = this.getText();
        if (this.options.loadTextURL) {
            this.loadExternalText()
        } else {
            this.buildOptionList()
        }
    }, loadExternalText: function () {
        this.showLoadingText(this.options.loadingText);
        var A = Object.extend({method: "get"}, this.options.ajaxOptions);
        Object.extend(A, {
            parameters: "editorId=" + encodeURIComponent(this.element.id),
            onComplete: Prototype.emptyFunction,
            onSuccess: function (B) {
                this._text = B.responseText.strip();
                this.buildOptionList()
            }.bind(this),
            onFailure: this.onFailure
        });
        new Ajax.Request(this.options.loadTextURL, A)
    }, buildOptionList: function () {
        this._form.removeClassName(this.options.loadingClassName);
        this._collection = this._collection.map(function (D) {
            return 2 === D.length ? D : [D, D].flatten()
        });
        var B = ("value" in this.options) ? this.options.value : this._text;
        var A = this._collection.any(function (D) {
            return D[0] == B
        }.bind(this));
        this._controls.editor.update("");
        var C;
        this._collection.each(function (E, D) {
            C = document.createElement("option");
            C.value = E[0];
            C.selected = A ? E[0] == B : 0 == D;
            C.appendChild(document.createTextNode(E[1]));
            this._controls.editor.appendChild(C)
        }.bind(this));
        this._controls.editor.disabled = false;
        Field.scrollFreeActivate(this._controls.editor)
    }
});
Ajax.InPlaceEditor.prototype.initialize.dealWithDeprecatedOptions = function (A) {
    if (!A) {
        return
    }
    function B(C, D) {
        if (C in A || D === undefined) {
            return
        }
        A[C] = D
    }

    B("cancelControl", (A.cancelLink ? "link" : (A.cancelButton ? "button" : A.cancelLink == A.cancelButton == false ? false : undefined)));
    B("okControl", (A.okLink ? "link" : (A.okButton ? "button" : A.okLink == A.okButton == false ? false : undefined)));
    B("highlightColor", A.highlightcolor);
    B("highlightEndColor", A.highlightendcolor)
};
Object.extend(Ajax.InPlaceEditor, {
    DefaultOptions: {
        ajaxOptions: {},
        autoRows: 3,
        cancelControl: "link",
        cancelText: "cancel",
        clickToEditText: "Click to edit",
        externalControl: null,
        externalControlOnly: false,
        fieldPostCreation: "activate",
        formClassName: "inplaceeditor-form",
        formId: null,
        highlightColor: "#ffff99",
        highlightEndColor: "#ffffff",
        hoverClassName: "",
        htmlResponse: true,
        loadingClassName: "inplaceeditor-loading",
        loadingText: "Loading...",
        okControl: "button",
        okText: "ok",
        paramName: "value",
        rows: 1,
        savingClassName: "inplaceeditor-saving",
        savingText: "Saving...",
        size: 0,
        stripLoadedTextTags: false,
        submitOnBlur: false,
        textAfterControls: "",
        textBeforeControls: "",
        textBetweenControls: ""
    },
    DefaultCallbacks: {
        callback: function (A) {
            return Form.serialize(A)
        }, onComplete: function (B, A) {
            new Effect.Highlight(A, {startcolor: this.options.highlightColor, keepBackgroundImage: true})
        }, onEnterEditMode: null, onEnterHover: function (A) {
            A.element.style.backgroundColor = A.options.highlightColor;
            if (A._effect) {
                A._effect.cancel()
            }
        }, onFailure: function (B, A) {
            alert("Error communication with the server: " + B.responseText.stripTags())
        }, onFormCustomization: null, onLeaveEditMode: null, onLeaveHover: function (A) {
            A._effect = new Effect.Highlight(A.element, {
                startcolor: A.options.highlightColor,
                endcolor: A.options.highlightEndColor,
                restorecolor: A._originalBackground,
                keepBackgroundImage: true
            })
        }
    },
    Listeners: {
        click: "enterEditMode",
        keydown: "checkForEscapeOrReturn",
        mouseover: "enterHover",
        mouseout: "leaveHover"
    }
});
Ajax.InPlaceCollectionEditor.DefaultOptions = {loadingCollectionText: "Loading options..."};
Form.Element.DelayedObserver = Class.create({
    initialize: function (B, A, C) {
        this.delay = A || 0.5;
        this.element = $$(B);
        this.callback = C;
        this.timer = null;
        this.lastValue = $F(this.element);
        Event.observe(this.element, "keyup", this.delayedListener.bindAsEventListener(this))
    }, delayedListener: function (A) {
        if (this.lastValue == $F(this.element)) {
            return
        }
        if (this.timer) {
            clearTimeout(this.timer)
        }
        this.timer = setTimeout(this.onTimerEvent.bind(this), this.delay * 1000);
        this.lastValue = $F(this.element)
    }, onTimerEvent: function () {
        this.timer = null;
        this.callback(this.element, $F(this.element))
    }
});
if (!Control) {
    var Control = {}
}
Control.Slider = Class.create({
    initialize: function (D, A, B) {
        var C = this;
        if (Object.isArray(D)) {
            this.handles = D.collect(function (E) {
                return $$(E)
            })
        } else {
            this.handles = [$$(D)]
        }
        this.track = $$(A);
        this.options = B || {};
        this.axis = this.options.axis || "horizontal";
        this.increment = this.options.increment || 1;
        this.step = parseInt(this.options.step || "1");
        this.range = this.options.range || $R(0, 1);
        this.value = 0;
        this.values = this.handles.map(function () {
            return 0
        });
        this.spans = this.options.spans ? this.options.spans.map(function (E) {
            return $$(E)
        }) : false;
        this.options.startSpan = $$(this.options.startSpan || null);
        this.options.endSpan = $$(this.options.endSpan || null);
        this.restricted = this.options.restricted || false;
        this.maximum = this.options.maximum || this.range.end;
        this.minimum = this.options.minimum || this.range.start;
        this.alignX = parseInt(this.options.alignX || "0");
        this.alignY = parseInt(this.options.alignY || "0");
        this.trackLength = this.maximumOffset() - this.minimumOffset();
        this.handleLength = this.isVertical() ? (this.handles[0].offsetHeight != 0 ? this.handles[0].offsetHeight : this.handles[0].style.height.replace(/px$/, "")) : (this.handles[0].offsetWidth != 0 ? this.handles[0].offsetWidth : this.handles[0].style.width.replace(/px$/, ""));
        this.active = false;
        this.dragging = false;
        this.disabled = false;
        if (this.options.disabled) {
            this.setDisabled()
        }
        this.allowedValues = this.options.values ? this.options.values.sortBy(Prototype.K) : false;
        if (this.allowedValues) {
            this.minimum = this.allowedValues.min();
            this.maximum = this.allowedValues.max()
        }
        this.eventMouseDown = this.startDrag.bindAsEventListener(this);
        this.eventMouseUp = this.endDrag.bindAsEventListener(this);
        this.eventMouseMove = this.update.bindAsEventListener(this);
        this.handles.each(function (F, E) {
            E = C.handles.length - 1 - E;
            C.setValue(parseFloat((Object.isArray(C.options.sliderValue) ? C.options.sliderValue[E] : C.options.sliderValue) || C.range.start), E);
            F.makePositioned().observe("mousedown", C.eventMouseDown)
        });
        this.track.observe("mousedown", this.eventMouseDown);
        document.observe("mouseup", this.eventMouseUp);
        document.observe("mousemove", this.eventMouseMove);
        this.initialized = true
    }, dispose: function () {
        var A = this;
        Event.stopObserving(this.track, "mousedown", this.eventMouseDown);
        Event.stopObserving(document, "mouseup", this.eventMouseUp);
        Event.stopObserving(document, "mousemove", this.eventMouseMove);
        this.handles.each(function (B) {
            Event.stopObserving(B, "mousedown", A.eventMouseDown)
        })
    }, setDisabled: function () {
        this.disabled = true
    }, setEnabled: function () {
        this.disabled = false
    }, getNearestValue: function (A) {
        if (this.allowedValues) {
            if (A >= this.allowedValues.max()) {
                return (this.allowedValues.max())
            }
            if (A <= this.allowedValues.min()) {
                return (this.allowedValues.min())
            }
            var C = Math.abs(this.allowedValues[0] - A);
            var B = this.allowedValues[0];
            this.allowedValues.each(function (D) {
                var E = Math.abs(D - A);
                if (E <= C) {
                    B = D;
                    C = E
                }
            });
            return B
        }
        if (A > this.range.end) {
            return this.range.end
        }
        if (A < this.range.start) {
            return this.range.start
        }
        return A
    }, setValue: function (B, A) {
        if (!this.active) {
            this.activeHandleIdx = A || 0;
            this.activeHandle = this.handles[this.activeHandleIdx];
            this.updateStyles()
        }
        A = A || this.activeHandleIdx || 0;
        if (this.initialized && this.restricted) {
            if ((A > 0) && (B < this.values[A - 1])) {
                B = this.values[A - 1]
            }
            if ((A < (this.handles.length - 1)) && (B > this.values[A + 1])) {
                B = this.values[A + 1]
            }
        }
        B = this.getNearestValue(B);
        this.values[A] = B;
        this.value = this.values[0];
        this.handles[A].style[this.isVertical() ? "top" : "left"] = this.translateToPx(B);
        this.drawSpans();
        if (!this.dragging || !this.event) {
            this.updateFinished()
        }
    }, setValueBy: function (B, A) {
        this.setValue(this.values[A || this.activeHandleIdx || 0] + B, A || this.activeHandleIdx || 0)
    }, translateToPx: function (A) {
        return Math.round(((this.trackLength - this.handleLength) / (this.range.end - this.range.start)) * (A - this.range.start)) + "px"
    }, translateToValue: function (A) {
        return ((A / (this.trackLength - this.handleLength) * (this.range.end - this.range.start)) + this.range.start)
    }, getRange: function (B) {
        var A = this.values.sortBy(Prototype.K);
        B = B || 0;
        return $R(A[B], A[B + 1])
    }, minimumOffset: function () {
        return (this.isVertical() ? this.alignY : this.alignX)
    }, maximumOffset: function () {
        return (this.isVertical() ? (this.track.offsetHeight != 0 ? this.track.offsetHeight : this.track.style.height.replace(/px$/, "")) - this.alignY : (this.track.offsetWidth != 0 ? this.track.offsetWidth : this.track.style.width.replace(/px$/, "")) - this.alignX)
    }, isVertical: function () {
        return (this.axis == "vertical")
    }, drawSpans: function () {
        var A = this;
        if (this.spans) {
            $R(0, this.spans.length - 1).each(function (B) {
                A.setSpan(A.spans[B], A.getRange(B))
            })
        }
        if (this.options.startSpan) {
            this.setSpan(this.options.startSpan, $R(0, this.values.length > 1 ? this.getRange(0).min() : this.value))
        }
        if (this.options.endSpan) {
            this.setSpan(this.options.endSpan, $R(this.values.length > 1 ? this.getRange(this.spans.length - 1).max() : this.value, this.maximum))
        }
    }, setSpan: function (B, A) {
        if (this.isVertical()) {
            B.style.top = this.translateToPx(A.start);
            B.style.height = this.translateToPx(A.end - A.start + this.range.start)
        } else {
            B.style.left = this.translateToPx(A.start);
            B.style.width = this.translateToPx(A.end - A.start + this.range.start)
        }
    }, updateStyles: function () {
        this.handles.each(function (A) {
            Element.removeClassName(A, "selected")
        });
        Element.addClassName(this.activeHandle, "selected")
    }, startDrag: function (C) {
        if (Event.isLeftClick(C)) {
            if (!this.disabled) {
                this.active = true;
                var D = Event.element(C);
                var E = [Event.pointerX(C), Event.pointerY(C)];
                var A = D;
                if (A == this.track) {
                    var B = this.track.cumulativeOffset();
                    this.event = C;
                    this.setValue(this.translateToValue((this.isVertical() ? E[1] - B[1] : E[0] - B[0]) - (this.handleLength / 2)));
                    var B = this.activeHandle.cumulativeOffset();
                    this.offsetX = (E[0] - B[0]);
                    this.offsetY = (E[1] - B[1])
                } else {
                    while ((this.handles.indexOf(D) == -1) && D.parentNode) {
                        D = D.parentNode
                    }
                    if (this.handles.indexOf(D) != -1) {
                        this.activeHandle = D;
                        this.activeHandleIdx = this.handles.indexOf(this.activeHandle);
                        this.updateStyles();
                        var B = this.activeHandle.cumulativeOffset();
                        this.offsetX = (E[0] - B[0]);
                        this.offsetY = (E[1] - B[1])
                    }
                }
            }
            Event.stop(C)
        }
    }, update: function (A) {
        if (this.active) {
            if (!this.dragging) {
                this.dragging = true
            }
            this.draw(A);
            if (Prototype.Browser.WebKit) {
                window.scrollBy(0, 0)
            }
            Event.stop(A)
        }
    }, draw: function (B) {
        var C = [Event.pointerX(B), Event.pointerY(B)];
        var A = this.track.cumulativeOffset();
        C[0] -= this.offsetX + A[0];
        C[1] -= this.offsetY + A[1];
        this.event = B;
        this.setValue(this.translateToValue(this.isVertical() ? C[1] : C[0]));
        if (this.initialized && this.options.onSlide) {
            this.options.onSlide(this.values.length > 1 ? this.values : this.value, this)
        }
    }, endDrag: function (A) {
        if (this.active && this.dragging) {
            this.finishDrag(A, true);
            Event.stop(A)
        }
        this.active = false;
        this.dragging = false
    }, finishDrag: function (A, B) {
        this.active = false;
        this.dragging = false;
        this.updateFinished()
    }, updateFinished: function () {
        if (this.initialized && this.options.onChange) {
            this.options.onChange(this.values.length > 1 ? this.values : this.value, this)
        }
        this.event = null
    }
});
Sound = {
    tracks: {},
    _enabled: true,
    template: new Template('<embed style="height:0" id="sound_#{track}_#{id}" src="#{url}" loop="false" autostart="true" hidden="true"/>'),
    enable: function () {
        Sound._enabled = true
    },
    disable: function () {
        Sound._enabled = false
    },
    play: function (B) {
        if (!Sound._enabled) {
            return
        }
        var A = Object.extend({track: "global", url: B, replace: false}, arguments[1] || {});
        if (A.replace && this.tracks[A.track]) {
            $R(0, this.tracks[A.track].id).each(function (D) {
                var C = $$("sound_" + A.track + "_" + D);
                C.Stop && C.Stop();
                C.remove()
            });
            this.tracks[A.track] = null
        }
        if (!this.tracks[A.track]) {
            this.tracks[A.track] = {id: 0}
        } else {
            this.tracks[A.track].id++
        }
        A.id = this.tracks[A.track].id;
        $$$("body")[0].insert(Prototype.Browser.IE ? new Element("bgsound", {
            id: "sound_" + A.track + "_" + A.id,
            src: A.url,
            loop: 1,
            autostart: true
        }) : Sound.template.evaluate(A))
    }
};
if (Prototype.Browser.Gecko && navigator.userAgent.indexOf("Win") > 0) {
    if (navigator.plugins && $A(navigator.plugins).detect(function (A) {
            return A.name.indexOf("QuickTime") != -1
        })) {
        Sound.template = new Template('<object id="sound_#{track}_#{id}" width="0" height="0" type="audio/mpeg" data="#{url}"/>')
    } else {
        if (navigator.plugins && $A(navigator.plugins).detect(function (A) {
                return A.name.indexOf("Windows Media") != -1
            })) {
            Sound.template = new Template('<object id="sound_#{track}_#{id}" type="application/x-mplayer2" data="#{url}"></object>')
        } else {
            if (navigator.plugins && $A(navigator.plugins).detect(function (A) {
                    return A.name.indexOf("RealPlayer") != -1
                })) {
                Sound.template = new Template('<embed type="audio/x-pn-realaudio-plugin" style="height:0" id="sound_#{track}_#{id}" src="#{url}" loop="false" autostart="true" hidden="true"/>')
            } else {
                Sound.play = function () {
                }
            }
        }
    }
}
;