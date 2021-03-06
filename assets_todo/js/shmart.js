var JSEncryptExports = {};
(function(M) {
    function e(a, b, c) {
        null != a && ("number" == typeof a ? this.fromNumber(a, b, c) : null == b && "string" != typeof a ? this.fromString(a, 256) : this.fromString(a, b))
    }

    function m() {
        return new e(null)
    }

    function Z(a, b, c, d, f, g) {
        for (; 0 <= --g;) {
            var h = b * this[a++] + c[d] + f;
            f = Math.floor(h / 67108864);
            c[d++] = h & 67108863
        }
        return f
    }

    function $(a, b, c, d, f, g) {
        var h = b & 32767;
        for (b >>= 15; 0 <= --g;) {
            var k = this[a] & 32767,
                e = this[a++] >> 15,
                y = b * k + e * h,
                k = h * k + ((y & 32767) << 15) + c[d] + (f & 1073741823);
            f = (k >>> 30) + (y >>> 15) + b * e + (f >>> 30);
            c[d++] = k & 1073741823
        }
        return f
    }

    function aa(a, b, c, d, f, g) {
        var h = b & 16383;
        for (b >>= 14; 0 <= --g;) {
            var k = this[a] & 16383,
                e = this[a++] >> 14,
                y = b * k + e * h,
                k = h * k + ((y & 16383) << 14) + c[d] + f;
            f = (k >> 28) + (y >> 14) + b * e;
            c[d++] = k & 268435455
        }
        return f
    }

    function S(a, b) {
        var c = H[a.charCodeAt(b)];
        return null == c ? -1 : c
    }

    function A(a) {
        var b = m();
        b.fromInt(a);
        return b
    }

    function I(a) {
        var b = 1,
            c;
        0 != (c = a >>> 16) && (a = c, b += 16);
        0 != (c = a >> 8) && (a = c, b += 8);
        0 != (c = a >> 4) && (a = c, b += 4);
        0 != (c = a >> 2) && (a = c, b += 2);
        0 != a >> 1 && (b += 1);
        return b
    }

    function B(a) {
        this.m = a
    }

    function C(a) {
        this.m = a;
        this.mp = a.invDigit();
        this.mpl = this.mp & 32767;
        this.mph = this.mp >> 15;
        this.um = (1 << a.DB - 15) - 1;
        this.mt2 = 2 * a.t
    }

    function ba(a, b) {
        return a & b
    }

    function J(a, b) {
        return a | b
    }

    function T(a, b) {
        return a ^ b
    }

    function U(a, b) {
        return a & ~b
    }

    function F() {}

    function V(a) {
        return a
    }

    function D(a) {
        this.r2 = m();
        this.q3 = m();
        e.ONE.dlShiftTo(2 * a.t, this.r2);
        this.mu = this.r2.divide(a);
        this.m = a
    }

    function N() {
        this.j = this.i = 0;
        this.S = []
    }

    function W(a) {
        w[r++] ^= a & 255;
        w[r++] ^= a >> 8 & 255;
        w[r++] ^= a >> 16 & 255;
        w[r++] ^= a >> 24 & 255;
        r >= O && (r -= O)
    }

    function K() {}

    function q(a, b) {
        return new e(a,
            b)
    }

    function n() {
        this.n = null;
        this.e = 0;
        this.coeff = this.dmq1 = this.dmp1 = this.q = this.p = this.d = null
    }

    function P(a) {
        var b, c, d = "";
        for (b = 0; b + 3 <= a.length; b += 3) c = parseInt(a.substring(b, b + 3), 16), d += E.charAt(c >> 6) + E.charAt(c & 63);
        b + 1 == a.length ? (c = parseInt(a.substring(b, b + 1), 16), d += E.charAt(c << 2)) : b + 2 == a.length && (c = parseInt(a.substring(b, b + 2), 16), d += E.charAt(c >> 2) + E.charAt((c & 3) << 4));
        for (; 0 < (d.length & 3);) d += X;
        return d
    }

    function ca(a) {
        var b = "",
            c, d = 0,
            f;
        for (c = 0; c < a.length && a.charAt(c) != X; ++c) v = E.indexOf(a.charAt(c)),
            0 > v || (0 == d ? (b += z.charAt(v >> 2), f = v & 3, d = 1) : 1 == d ? (b += z.charAt(f << 2 | v >> 4), f = v & 15, d = 2) : 2 == d ? (b += z.charAt(f), b += z.charAt(v >> 2), f = v & 3, d = 3) : (b += z.charAt(f << 2 | v >> 4), b += z.charAt(v & 15), d = 0));
        1 == d && (b += z.charAt(f << 2));
        return b
    }
    var p;
    "Microsoft Internet Explorer" == navigator.appName ? (e.prototype.am = $, p = 30) : "Netscape" != navigator.appName ? (e.prototype.am = Z, p = 26) : (e.prototype.am = aa, p = 28);
    e.prototype.DB = p;
    e.prototype.DM = (1 << p) - 1;
    e.prototype.DV = 1 << p;
    e.prototype.FV = Math.pow(2, 52);
    e.prototype.F1 = 52 - p;
    e.prototype.F2 = 2 *
        p - 52;
    var z = "0123456789abcdefghijklmnopqrstuvwxyz",
        H = [],
        t;
    p = 48;
    for (t = 0; 9 >= t; ++t) H[p++] = t;
    p = 97;
    for (t = 10; 36 > t; ++t) H[p++] = t;
    p = 65;
    for (t = 10; 36 > t; ++t) H[p++] = t;
    B.prototype.convert = function(a) {
        return 0 > a.s || 0 <= a.compareTo(this.m) ? a.mod(this.m) : a
    };
    B.prototype.revert = function(a) {
        return a
    };
    B.prototype.reduce = function(a) {
        a.divRemTo(this.m, null, a)
    };
    B.prototype.mulTo = function(a, b, c) {
        a.multiplyTo(b, c);
        this.reduce(c)
    };
    B.prototype.sqrTo = function(a, b) {
        a.squareTo(b);
        this.reduce(b)
    };
    C.prototype.convert = function(a) {
        var b =
            m();
        a.abs().dlShiftTo(this.m.t, b);
        b.divRemTo(this.m, null, b);
        0 > a.s && 0 < b.compareTo(e.ZERO) && this.m.subTo(b, b);
        return b
    };
    C.prototype.revert = function(a) {
        var b = m();
        a.copyTo(b);
        this.reduce(b);
        return b
    };
    C.prototype.reduce = function(a) {
        for (; a.t <= this.mt2;) a[a.t++] = 0;
        for (var b = 0; b < this.m.t; ++b) {
            var c = a[b] & 32767,
                d = c * this.mpl + ((c * this.mph + (a[b] >> 15) * this.mpl & this.um) << 15) & a.DM,
                c = b + this.m.t;
            for (a[c] += this.m.am(0, d, a, b, 0, this.m.t); a[c] >= a.DV;) a[c] -= a.DV, a[++c] ++
        }
        a.clamp();
        a.drShiftTo(this.m.t, a);
        0 <= a.compareTo(this.m) &&
            a.subTo(this.m, a)
    };
    C.prototype.mulTo = function(a, b, c) {
        a.multiplyTo(b, c);
        this.reduce(c)
    };
    C.prototype.sqrTo = function(a, b) {
        a.squareTo(b);
        this.reduce(b)
    };
    e.prototype.copyTo = function(a) {
        for (var b = this.t - 1; 0 <= b; --b) a[b] = this[b];
        a.t = this.t;
        a.s = this.s
    };
    e.prototype.fromInt = function(a) {
        this.t = 1;
        this.s = 0 > a ? -1 : 0;
        0 < a ? this[0] = a : -1 > a ? this[0] = a + DV : this.t = 0
    };
    e.prototype.fromString = function(a, b) {
        var c;
        if (16 == b) c = 4;
        else if (8 == b) c = 3;
        else if (256 == b) c = 8;
        else if (2 == b) c = 1;
        else if (32 == b) c = 5;
        else if (4 == b) c = 2;
        else {
            this.fromRadix(a,
                b);
            return
        }
        this.s = this.t = 0;
        for (var d = a.length, f = !1, g = 0; 0 <= --d;) {
            var h = 8 == c ? a[d] & 255 : S(a, d);
            0 > h ? "-" == a.charAt(d) && (f = !0) : (f = !1, 0 == g ? this[this.t++] = h : g + c > this.DB ? (this[this.t - 1] |= (h & (1 << this.DB - g) - 1) << g, this[this.t++] = h >> this.DB - g) : this[this.t - 1] |= h << g, g += c, g >= this.DB && (g -= this.DB))
        }
        8 == c && 0 != (a[0] & 128) && (this.s = -1, 0 < g && (this[this.t - 1] |= (1 << this.DB - g) - 1 << g));
        this.clamp();
        f && e.ZERO.subTo(this, this)
    };
    e.prototype.clamp = function() {
        for (var a = this.s & this.DM; 0 < this.t && this[this.t - 1] == a;) --this.t
    };
    e.prototype.dlShiftTo =
        function(a, b) {
            var c;
            for (c = this.t - 1; 0 <= c; --c) b[c + a] = this[c];
            for (c = a - 1; 0 <= c; --c) b[c] = 0;
            b.t = this.t + a;
            b.s = this.s
        };
    e.prototype.drShiftTo = function(a, b) {
        for (var c = a; c < this.t; ++c) b[c - a] = this[c];
        b.t = Math.max(this.t - a, 0);
        b.s = this.s
    };
    e.prototype.lShiftTo = function(a, b) {
        var c = a % this.DB,
            d = this.DB - c,
            f = (1 << d) - 1,
            g = Math.floor(a / this.DB),
            h = this.s << c & this.DM,
            k;
        for (k = this.t - 1; 0 <= k; --k) b[k + g + 1] = this[k] >> d | h, h = (this[k] & f) << c;
        for (k = g - 1; 0 <= k; --k) b[k] = 0;
        b[g] = h;
        b.t = this.t + g + 1;
        b.s = this.s;
        b.clamp()
    };
    e.prototype.rShiftTo = function(a,
        b) {
        b.s = this.s;
        var c = Math.floor(a / this.DB);
        if (c >= this.t) b.t = 0;
        else {
            var d = a % this.DB,
                f = this.DB - d,
                g = (1 << d) - 1;
            b[0] = this[c] >> d;
            for (var h = c + 1; h < this.t; ++h) b[h - c - 1] |= (this[h] & g) << f, b[h - c] = this[h] >> d;
            0 < d && (b[this.t - c - 1] |= (this.s & g) << f);
            b.t = this.t - c;
            b.clamp()
        }
    };
    e.prototype.subTo = function(a, b) {
        for (var c = 0, d = 0, f = Math.min(a.t, this.t); c < f;) d += this[c] - a[c], b[c++] = d & this.DM, d >>= this.DB;
        if (a.t < this.t) {
            for (d -= a.s; c < this.t;) d += this[c], b[c++] = d & this.DM, d >>= this.DB;
            d += this.s
        } else {
            for (d += this.s; c < a.t;) d -= a[c], b[c++] = d &
                this.DM, d >>= this.DB;
            d -= a.s
        }
        b.s = 0 > d ? -1 : 0; - 1 > d ? b[c++] = this.DV + d : 0 < d && (b[c++] = d);
        b.t = c;
        b.clamp()
    };
    e.prototype.multiplyTo = function(a, b) {
        var c = this.abs(),
            d = a.abs(),
            f = c.t;
        for (b.t = f + d.t; 0 <= --f;) b[f] = 0;
        for (f = 0; f < d.t; ++f) b[f + c.t] = c.am(0, d[f], b, f, 0, c.t);
        b.s = 0;
        b.clamp();
        this.s != a.s && e.ZERO.subTo(b, b)
    };
    e.prototype.squareTo = function(a) {
        for (var b = this.abs(), c = a.t = 2 * b.t; 0 <= --c;) a[c] = 0;
        for (c = 0; c < b.t - 1; ++c) {
            var d = b.am(c, b[c], a, 2 * c, 0, 1);
            (a[c + b.t] += b.am(c + 1, 2 * b[c], a, 2 * c + 1, d, b.t - c - 1)) >= b.DV && (a[c + b.t] -= b.DV, a[c + b.t +
                1] = 1)
        }
        0 < a.t && (a[a.t - 1] += b.am(c, b[c], a, 2 * c, 0, 1));
        a.s = 0;
        a.clamp()
    };
    e.prototype.divRemTo = function(a, b, c) {
        var d = a.abs();
        if (!(0 >= d.t)) {
            var f = this.abs();
            if (f.t < d.t) null != b && b.fromInt(0), null != c && this.copyTo(c);
            else {
                null == c && (c = m());
                var g = m(),
                    h = this.s;
                a = a.s;
                var k = this.DB - I(d[d.t - 1]);
                0 < k ? (d.lShiftTo(k, g), f.lShiftTo(k, c)) : (d.copyTo(g), f.copyTo(c));
                d = g.t;
                f = g[d - 1];
                if (0 != f) {
                    var x = f * (1 << this.F1) + (1 < d ? g[d - 2] >> this.F2 : 0),
                        y = this.FV / x,
                        x = (1 << this.F1) / x,
                        l = 1 << this.F2,
                        s = c.t,
                        n = s - d,
                        p = null == b ? m() : b;
                    g.dlShiftTo(n, p);
                    0 <= c.compareTo(p) &&
                        (c[c.t++] = 1, c.subTo(p, c));
                    e.ONE.dlShiftTo(d, p);
                    for (p.subTo(g, g); g.t < d;) g[g.t++] = 0;
                    for (; 0 <= --n;) {
                        var q = c[--s] == f ? this.DM : Math.floor(c[s] * y + (c[s - 1] + l) * x);
                        if ((c[s] += g.am(0, q, c, n, 0, d)) < q)
                            for (g.dlShiftTo(n, p), c.subTo(p, c); c[s] < --q;) c.subTo(p, c)
                    }
                    null != b && (c.drShiftTo(d, b), h != a && e.ZERO.subTo(b, b));
                    c.t = d;
                    c.clamp();
                    0 < k && c.rShiftTo(k, c);
                    0 > h && e.ZERO.subTo(c, c)
                }
            }
        }
    };
    e.prototype.invDigit = function() {
        if (1 > this.t) return 0;
        var a = this[0];
        if (0 == (a & 1)) return 0;
        var b = a & 3,
            b = b * (2 - (a & 15) * b) & 15,
            b = b * (2 - (a & 255) * b) & 255,
            b = b * (2 -
                ((a & 65535) * b & 65535)) & 65535,
            b = b * (2 - a * b % this.DV) % this.DV;
        return 0 < b ? this.DV - b : -b
    };
    e.prototype.isEven = function() {
        return 0 == (0 < this.t ? this[0] & 1 : this.s)
    };
    e.prototype.exp = function(a, b) {
        if (4294967295 < a || 1 > a) return e.ONE;
        var c = m(),
            d = m(),
            f = b.convert(this),
            g = I(a) - 1;
        for (f.copyTo(c); 0 <= --g;)
            if (b.sqrTo(c, d), 0 < (a & 1 << g)) b.mulTo(d, f, c);
            else var h = c,
                c = d,
                d = h;
        return b.revert(c)
    };
    e.prototype.toString = function(a) {
        if (0 > this.s) return "-" + this.negate().toString(a);
        if (16 == a) a = 4;
        else if (8 == a) a = 3;
        else if (2 == a) a = 1;
        else if (32 ==
            a) a = 5;
        else if (4 == a) a = 2;
        else return this.toRadix(a);
        var b = (1 << a) - 1,
            c, d = !1,
            f = "",
            g = this.t,
            h = this.DB - g * this.DB % a;
        if (0 < g--)
            for (h < this.DB && 0 < (c = this[g] >> h) && (d = !0, f = z.charAt(c)); 0 <= g;) h < a ? (c = (this[g] & (1 << h) - 1) << a - h, c |= this[--g] >> (h += this.DB - a)) : (c = this[g] >> (h -= a) & b, 0 >= h && (h += this.DB, --g)), 0 < c && (d = !0), d && (f += z.charAt(c));
        return d ? f : "0"
    };
    e.prototype.negate = function() {
        var a = m();
        e.ZERO.subTo(this, a);
        return a
    };
    e.prototype.abs = function() {
        return 0 > this.s ? this.negate() : this
    };
    e.prototype.compareTo = function(a) {
        var b =
            this.s - a.s;
        if (0 != b) return b;
        var c = this.t,
            b = c - a.t;
        if (0 != b) return 0 > this.s ? -b : b;
        for (; 0 <= --c;)
            if (0 != (b = this[c] - a[c])) return b;
        return 0
    };
    e.prototype.bitLength = function() {
        return 0 >= this.t ? 0 : this.DB * (this.t - 1) + I(this[this.t - 1] ^ this.s & this.DM)
    };
    e.prototype.mod = function(a) {
        var b = m();
        this.abs().divRemTo(a, null, b);
        0 > this.s && 0 < b.compareTo(e.ZERO) && a.subTo(b, b);
        return b
    };
    e.prototype.modPowInt = function(a, b) {
        var c;
        c = 256 > a || b.isEven() ? new B(b) : new C(b);
        return this.exp(a, c)
    };
    e.ZERO = A(0);
    e.ONE = A(1);
    F.prototype.convert =
        V;
    F.prototype.revert = V;
    F.prototype.mulTo = function(a, b, c) {
        a.multiplyTo(b, c)
    };
    F.prototype.sqrTo = function(a, b) {
        a.squareTo(b)
    };
    D.prototype.convert = function(a) {
        if (0 > a.s || a.t > 2 * this.m.t) return a.mod(this.m);
        if (0 > a.compareTo(this.m)) return a;
        var b = m();
        a.copyTo(b);
        this.reduce(b);
        return b
    };
    D.prototype.revert = function(a) {
        return a
    };
    D.prototype.reduce = function(a) {
        a.drShiftTo(this.m.t - 1, this.r2);
        a.t > this.m.t + 1 && (a.t = this.m.t + 1, a.clamp());
        this.mu.multiplyUpperTo(this.r2, this.m.t + 1, this.q3);
        for (this.m.multiplyLowerTo(this.q3,
                this.m.t + 1, this.r2); 0 > a.compareTo(this.r2);) a.dAddOffset(1, this.m.t + 1);
        for (a.subTo(this.r2, a); 0 <= a.compareTo(this.m);) a.subTo(this.m, a)
    };
    D.prototype.mulTo = function(a, b, c) {
        a.multiplyTo(b, c);
        this.reduce(c)
    };
    D.prototype.sqrTo = function(a, b) {
        a.squareTo(b);
        this.reduce(b)
    };
    var u = [2, 3, 5, 7, 11, 13, 17, 19, 23, 29, 31, 37, 41, 43, 47, 53, 59, 61, 67, 71, 73, 79, 83, 89, 97, 101, 103, 107, 109, 113, 127, 131, 137, 139, 149, 151, 157, 163, 167, 173, 179, 181, 191, 193, 197, 199, 211, 223, 227, 229, 233, 239, 241, 251, 257, 263, 269, 271, 277, 281, 283, 293, 307, 311,
            313, 317, 331, 337, 347, 349, 353, 359, 367, 373, 379, 383, 389, 397, 401, 409, 419, 421, 431, 433, 439, 443, 449, 457, 461, 463, 467, 479, 487, 491, 499, 503, 509, 521, 523, 541, 547, 557, 563, 569, 571, 577, 587, 593, 599, 601, 607, 613, 617, 619, 631, 641, 643, 647, 653, 659, 661, 673, 677, 683, 691, 701, 709, 719, 727, 733, 739, 743, 751, 757, 761, 769, 773, 787, 797, 809, 811, 821, 823, 827, 829, 839, 853, 857, 859, 863, 877, 881, 883, 887, 907, 911, 919, 929, 937, 941, 947, 953, 967, 971, 977, 983, 991, 997
        ],
        da = 67108864 / u[u.length - 1];
    e.prototype.chunkSize = function(a) {
        return Math.floor(Math.LN2 *
            this.DB / Math.log(a))
    };
    e.prototype.toRadix = function(a) {
        null == a && (a = 10);
        if (0 == this.signum() || 2 > a || 36 < a) return "0";
        var b = this.chunkSize(a),
            b = Math.pow(a, b),
            c = A(b),
            d = m(),
            f = m(),
            g = "";
        for (this.divRemTo(c, d, f); 0 < d.signum();) g = (b + f.intValue()).toString(a).substr(1) + g, d.divRemTo(c, d, f);
        return f.intValue().toString(a) + g
    };
    e.prototype.fromRadix = function(a, b) {
        this.fromInt(0);
        null == b && (b = 10);
        for (var c = this.chunkSize(b), d = Math.pow(b, c), f = !1, g = 0, h = 0, k = 0; k < a.length; ++k) {
            var x = S(a, k);
            0 > x ? "-" == a.charAt(k) && 0 == this.signum() &&
                (f = !0) : (h = b * h + x, ++g >= c && (this.dMultiply(d), this.dAddOffset(h, 0), h = g = 0))
        }
        0 < g && (this.dMultiply(Math.pow(b, g)), this.dAddOffset(h, 0));
        f && e.ZERO.subTo(this, this)
    };
    e.prototype.fromNumber = function(a, b, c) {
        if ("number" == typeof b)
            if (2 > a) this.fromInt(1);
            else
                for (this.fromNumber(a, c), this.testBit(a - 1) || this.bitwiseTo(e.ONE.shiftLeft(a - 1), J, this), this.isEven() && this.dAddOffset(1, 0); !this.isProbablePrime(b);) this.dAddOffset(2, 0), this.bitLength() > a && this.subTo(e.ONE.shiftLeft(a - 1), this);
        else {
            c = [];
            var d = a & 7;
            c.length =
                (a >> 3) + 1;
            b.nextBytes(c);
            c[0] = 0 < d ? c[0] & (1 << d) - 1 : 0;
            this.fromString(c, 256)
        }
    };
    e.prototype.bitwiseTo = function(a, b, c) {
        var d, f, g = Math.min(a.t, this.t);
        for (d = 0; d < g; ++d) c[d] = b(this[d], a[d]);
        if (a.t < this.t) {
            f = a.s & this.DM;
            for (d = g; d < this.t; ++d) c[d] = b(this[d], f);
            c.t = this.t
        } else {
            f = this.s & this.DM;
            for (d = g; d < a.t; ++d) c[d] = b(f, a[d]);
            c.t = a.t
        }
        c.s = b(this.s, a.s);
        c.clamp()
    };
    e.prototype.changeBit = function(a, b) {
        var c = e.ONE.shiftLeft(a);
        this.bitwiseTo(c, b, c);
        return c
    };
    e.prototype.addTo = function(a, b) {
        for (var c = 0, d = 0, f = Math.min(a.t,
                this.t); c < f;) d += this[c] + a[c], b[c++] = d & this.DM, d >>= this.DB;
        if (a.t < this.t) {
            for (d += a.s; c < this.t;) d += this[c], b[c++] = d & this.DM, d >>= this.DB;
            d += this.s
        } else {
            for (d += this.s; c < a.t;) d += a[c], b[c++] = d & this.DM, d >>= this.DB;
            d += a.s
        }
        b.s = 0 > d ? -1 : 0;
        0 < d ? b[c++] = d : -1 > d && (b[c++] = this.DV + d);
        b.t = c;
        b.clamp()
    };
    e.prototype.dMultiply = function(a) {
        this[this.t] = this.am(0, a - 1, this, 0, 0, this.t);
        ++this.t;
        this.clamp()
    };
    e.prototype.dAddOffset = function(a, b) {
        if (0 != a) {
            for (; this.t <= b;) this[this.t++] = 0;
            for (this[b] += a; this[b] >= this.DV;) this[b] -=
                this.DV, ++b >= this.t && (this[this.t++] = 0), ++this[b]
        }
    };
    e.prototype.multiplyLowerTo = function(a, b, c) {
        var d = Math.min(this.t + a.t, b);
        c.s = 0;
        for (c.t = d; 0 < d;) c[--d] = 0;
        var f;
        for (f = c.t - this.t; d < f; ++d) c[d + this.t] = this.am(0, a[d], c, d, 0, this.t);
        for (f = Math.min(a.t, b); d < f; ++d) this.am(0, a[d], c, d, 0, b - d);
        c.clamp()
    };
    e.prototype.multiplyUpperTo = function(a, b, c) {
        --b;
        var d = c.t = this.t + a.t - b;
        for (c.s = 0; 0 <= --d;) c[d] = 0;
        for (d = Math.max(b - this.t, 0); d < a.t; ++d) c[this.t + d - b] = this.am(b - d, a[d], c, 0, 0, this.t + d - b);
        c.clamp();
        c.drShiftTo(1, c)
    };
    e.prototype.modInt = function(a) {
        if (0 >= a) return 0;
        var b = this.DV % a,
            c = 0 > this.s ? a - 1 : 0;
        if (0 < this.t)
            if (0 == b) c = this[0] % a;
            else
                for (var d = this.t - 1; 0 <= d; --d) c = (b * c + this[d]) % a;
        return c
    };
    e.prototype.millerRabin = function(a) {
        var b = this.subtract(e.ONE),
            c = b.getLowestSetBit();
        if (0 >= c) return !1;
        var d = b.shiftRight(c);
        a = a + 1 >> 1;
        a > u.length && (a = u.length);
        for (var f = m(), g = 0; g < a; ++g) {
            f.fromInt(u[Math.floor(Math.random() * u.length)]);
            var h = f.modPow(d, this);
            if (0 != h.compareTo(e.ONE) && 0 != h.compareTo(b)) {
                for (var k = 1; k++ < c && 0 != h.compareTo(b);)
                    if (h =
                        h.modPowInt(2, this), 0 == h.compareTo(e.ONE)) return !1;
                if (0 != h.compareTo(b)) return !1
            }
        }
        return !0
    };
    e.prototype.clone = function() {
        var a = m();
        this.copyTo(a);
        return a
    };
    e.prototype.intValue = function() {
        if (0 > this.s) {
            if (1 == this.t) return this[0] - this.DV;
            if (0 == this.t) return -1
        } else {
            if (1 == this.t) return this[0];
            if (0 == this.t) return 0
        }
        return (this[1] & (1 << 32 - this.DB) - 1) << this.DB | this[0]
    };
    e.prototype.byteValue = function() {
        return 0 == this.t ? this.s : this[0] << 24 >> 24
    };
    e.prototype.shortValue = function() {
        return 0 == this.t ? this.s : this[0] <<
            16 >> 16
    };
    e.prototype.signum = function() {
        return 0 > this.s ? -1 : 0 >= this.t || 1 == this.t && 0 >= this[0] ? 0 : 1
    };
    e.prototype.toByteArray = function() {
        var a = this.t,
            b = [];
        b[0] = this.s;
        var c = this.DB - a * this.DB % 8,
            d, f = 0;
        if (0 < a--)
            for (c < this.DB && (d = this[a] >> c) != (this.s & this.DM) >> c && (b[f++] = d | this.s << this.DB - c); 0 <= a;)
                if (8 > c ? (d = (this[a] & (1 << c) - 1) << 8 - c, d |= this[--a] >> (c += this.DB - 8)) : (d = this[a] >> (c -= 8) & 255, 0 >= c && (c += this.DB, --a)), 0 != (d & 128) && (d |= -256), 0 == f && (this.s & 128) != (d & 128) && ++f, 0 < f || d != this.s) b[f++] = d;
        return b
    };
    e.prototype.equals =
        function(a) {
            return 0 == this.compareTo(a)
        };
    e.prototype.min = function(a) {
        return 0 > this.compareTo(a) ? this : a
    };
    e.prototype.max = function(a) {
        return 0 < this.compareTo(a) ? this : a
    };
    e.prototype.and = function(a) {
        var b = m();
        this.bitwiseTo(a, ba, b);
        return b
    };
    e.prototype.or = function(a) {
        var b = m();
        this.bitwiseTo(a, J, b);
        return b
    };
    e.prototype.xor = function(a) {
        var b = m();
        this.bitwiseTo(a, T, b);
        return b
    };
    e.prototype.andNot = function(a) {
        var b = m();
        this.bitwiseTo(a, U, b);
        return b
    };
    e.prototype.not = function() {
        for (var a = m(), b = 0; b < this.t; ++b) a[b] =
            this.DM & ~this[b];
        a.t = this.t;
        a.s = ~this.s;
        return a
    };
    e.prototype.shiftLeft = function(a) {
        var b = m();
        0 > a ? this.rShiftTo(-a, b) : this.lShiftTo(a, b);
        return b
    };
    e.prototype.shiftRight = function(a) {
        var b = m();
        0 > a ? this.lShiftTo(-a, b) : this.rShiftTo(a, b);
        return b
    };
    e.prototype.getLowestSetBit = function() {
        for (var a = 0; a < this.t; ++a)
            if (0 != this[a]) {
                var b = a * this.DB;
                a = this[a];
                if (0 == a) a = -1;
                else {
                    var c = 0;
                    0 == (a & 65535) && (a >>= 16, c += 16);
                    0 == (a & 255) && (a >>= 8, c += 8);
                    0 == (a & 15) && (a >>= 4, c += 4);
                    0 == (a & 3) && (a >>= 2, c += 2);
                    0 == (a & 1) && ++c;
                    a = c
                }
                return b +
                    a
            }
        return 0 > this.s ? this.t * this.DB : -1
    };
    e.prototype.bitCount = function() {
        for (var a = 0, b = this.s & this.DM, c = 0; c < this.t; ++c) {
            for (var d = this[c] ^ b, f = 0; 0 != d;) d &= d - 1, ++f;
            a += f
        }
        return a
    };
    e.prototype.testBit = function(a) {
        var b = Math.floor(a / this.DB);
        return b >= this.t ? 0 != this.s : 0 != (this[b] & 1 << a % this.DB)
    };
    e.prototype.setBit = function(a) {
        return this.changeBit(a, J)
    };
    e.prototype.clearBit = function(a) {
        return this.changeBit(a, U)
    };
    e.prototype.flipBit = function(a) {
        return this.changeBit(a, T)
    };
    e.prototype.add = function(a) {
        var b = m();
        this.addTo(a, b);
        return b
    };
    e.prototype.subtract = function(a) {
        var b = m();
        this.subTo(a, b);
        return b
    };
    e.prototype.multiply = function(a) {
        var b = m();
        this.multiplyTo(a, b);
        return b
    };
    e.prototype.divide = function(a) {
        var b = m();
        this.divRemTo(a, b, null);
        return b
    };
    e.prototype.remainder = function(a) {
        var b = m();
        this.divRemTo(a, null, b);
        return b
    };
    e.prototype.divideAndRemainder = function(a) {
        var b = m(),
            c = m();
        this.divRemTo(a, b, c);
        return [b, c]
    };
    e.prototype.modPow = function(a, b) {
        var c = a.bitLength(),
            d, f = A(1),
            g;
        if (0 >= c) return f;
        d = 18 >
            c ? 1 : 48 > c ? 3 : 144 > c ? 4 : 768 > c ? 5 : 6;
        g = 8 > c ? new B(b) : b.isEven() ? new D(b) : new C(b);
        var h = [],
            k = 3,
            e = d - 1,
            y = (1 << d) - 1;
        h[1] = g.convert(this);
        if (1 < d)
            for (c = m(), g.sqrTo(h[1], c); k <= y;) h[k] = m(), g.mulTo(c, h[k - 2], h[k]), k += 2;
        for (var l = a.t - 1, s, p = !0, n = m(), c = I(a[l]) - 1; 0 <= l;) {
            c >= e ? s = a[l] >> c - e & y : (s = (a[l] & (1 << c + 1) - 1) << e - c, 0 < l && (s |= a[l - 1] >> this.DB + c - e));
            for (k = d; 0 == (s & 1);) s >>= 1, --k;
            0 > (c -= k) && (c += this.DB, --l);
            if (p) h[s].copyTo(f), p = !1;
            else {
                for (; 1 < k;) g.sqrTo(f, n), g.sqrTo(n, f), k -= 2;
                0 < k ? g.sqrTo(f, n) : (k = f, f = n, n = k);
                g.mulTo(n, h[s], f)
            }
            for (; 0 <=
                l && 0 == (a[l] & 1 << c);) g.sqrTo(f, n), k = f, f = n, n = k, 0 > --c && (c = this.DB - 1, --l)
        }
        return g.revert(f)
    };
    e.prototype.modInverse = function(a) {
        var b = a.isEven();
        if (this.isEven() && b || 0 == a.signum()) return e.ZERO;
        for (var c = a.clone(), d = this.clone(), f = A(1), g = A(0), h = A(0), k = A(1); 0 != c.signum();) {
            for (; c.isEven();) c.rShiftTo(1, c), b ? (f.isEven() && g.isEven() || (f.addTo(this, f), g.subTo(a, g)), f.rShiftTo(1, f)) : g.isEven() || g.subTo(a, g), g.rShiftTo(1, g);
            for (; d.isEven();) d.rShiftTo(1, d), b ? (h.isEven() && k.isEven() || (h.addTo(this, h), k.subTo(a,
                k)), h.rShiftTo(1, h)) : k.isEven() || k.subTo(a, k), k.rShiftTo(1, k);
            0 <= c.compareTo(d) ? (c.subTo(d, c), b && f.subTo(h, f), g.subTo(k, g)) : (d.subTo(c, d), b && h.subTo(f, h), k.subTo(g, k))
        }
        if (0 != d.compareTo(e.ONE)) return e.ZERO;
        if (0 <= k.compareTo(a)) return k.subtract(a);
        if (0 > k.signum()) k.addTo(a, k);
        else return k;
        return 0 > k.signum() ? k.add(a) : k
    };
    e.prototype.pow = function(a) {
        return this.exp(a, new F)
    };
    e.prototype.gcd = function(a) {
        var b = 0 > this.s ? this.negate() : this.clone();
        a = 0 > a.s ? a.negate() : a.clone();
        if (0 > b.compareTo(a)) {
            var c =
                b,
                b = a;
            a = c
        }
        var c = b.getLowestSetBit(),
            d = a.getLowestSetBit();
        if (0 > d) return b;
        c < d && (d = c);
        0 < d && (b.rShiftTo(d, b), a.rShiftTo(d, a));
        for (; 0 < b.signum();) 0 < (c = b.getLowestSetBit()) && b.rShiftTo(c, b), 0 < (c = a.getLowestSetBit()) && a.rShiftTo(c, a), 0 <= b.compareTo(a) ? (b.subTo(a, b), b.rShiftTo(1, b)) : (a.subTo(b, a), a.rShiftTo(1, a));
        0 < d && a.lShiftTo(d, a);
        return a
    };
    e.prototype.isProbablePrime = function(a) {
        var b, c = this.abs();
        if (1 == c.t && c[0] <= u[u.length - 1]) {
            for (b = 0; b < u.length; ++b)
                if (c[0] == u[b]) return !0;
            return !1
        }
        if (c.isEven()) return !1;
        for (b = 1; b < u.length;) {
            for (var d = u[b], f = b + 1; f < u.length && d < da;) d *= u[f++];
            for (d = c.modInt(d); b < f;)
                if (0 == d % u[b++]) return !1
        }
        return c.millerRabin(a)
    };
    e.prototype.square = function() {
        var a = m();
        this.squareTo(a);
        return a
    };
    N.prototype.init = function(a) {
        var b, c, d;
        for (b = 0; 256 > b; ++b) this.S[b] = b;
        for (b = c = 0; 256 > b; ++b) c = c + this.S[b] + a[b % a.length] & 255, d = this.S[b], this.S[b] = this.S[c], this.S[c] = d;
        this.j = this.i = 0
    };
    N.prototype.next = function() {
        var a;
        this.i = this.i + 1 & 255;
        this.j = this.j + this.S[this.i] & 255;
        a = this.S[this.i];
        this.S[this.i] =
            this.S[this.j];
        this.S[this.j] = a;
        return this.S[a + this.S[this.i] & 255]
    };
    var O = 256,
        L, w, r;
    if (null == w) {
        w = [];
        r = 0;
        if ("Netscape" == navigator.appName && "5" > navigator.appVersion && window.crypto)
            for (t = window.crypto.random(32), p = 0; p < t.length; ++p) w[r++] = t.charCodeAt(p) & 255;
        for (; r < O;) p = Math.floor(65536 * Math.random()), w[r++] = p >>> 8, w[r++] = p & 255;
        r = 0;
        W((new Date).getTime())
    }
    K.prototype.nextBytes = function(a) {
        var b;
        for (b = 0; b < a.length; ++b) {
            var c = a,
                d = b,
                f;
            if (null == L) {
                W((new Date).getTime());
                L = new N;
                L.init(w);
                for (r = 0; r < w.length; ++r) w[r] =
                    0;
                r = 0
            }
            f = L.next();
            c[d] = f
        }
    };
    n.prototype.doPublic = function(a) {
        return a.modPowInt(this.e, this.n)
    };
    n.prototype.setPublic = function(a, b) {
        null != a && null != b && 0 < a.length && 0 < b.length ? (this.n = q(a, 16), this.e = parseInt(b, 16)) : console.error("Invalid RSA public key")
    };
    n.prototype.encrypt = function(a) {
        var b;
        b = this.n.bitLength() + 7 >> 3;
        if (b < a.length + 11) console.error("Message too long for RSA"), b = null;
        else {
            for (var c = [], d = a.length - 1; 0 <= d && 0 < b;) {
                var f = a.charCodeAt(d--);
                128 > f ? c[--b] = f : 127 < f && 2048 > f ? (c[--b] = f & 63 | 128, c[--b] =
                    f >> 6 | 192) : (c[--b] = f & 63 | 128, c[--b] = f >> 6 & 63 | 128, c[--b] = f >> 12 | 224)
            }
            c[--b] = 0;
            a = new K;
            for (d = []; 2 < b;) {
                for (d[0] = 0; 0 == d[0];) a.nextBytes(d);
                c[--b] = d[0]
            }
            c[--b] = 2;
            c[--b] = 0;
            b = new e(c)
        }
        if (null == b) return null;
        b = this.doPublic(b);
        if (null == b) return null;
        b = b.toString(16);
        return 0 == (b.length & 1) ? b : "0" + b
    };
    n.prototype.doPrivate = function(a) {
        if (null == this.p || null == this.q) return a.modPow(this.d, this.n);
        var b = a.mod(this.p).modPow(this.dmp1, this.p);
        for (a = a.mod(this.q).modPow(this.dmq1, this.q); 0 > b.compareTo(a);) b = b.add(this.p);
        return b.subtract(a).multiply(this.coeff).mod(this.p).multiply(this.q).add(a)
    };
    n.prototype.setPrivate = function(a, b, c) {
        null != a && null != b && 0 < a.length && 0 < b.length ? (this.n = q(a, 16), this.e = parseInt(b, 16), this.d = q(c, 16)) : console.error("Invalid RSA private key")
    };
    n.prototype.setPrivateEx = function(a, b, c, d, f, g, h, k) {
        null != a && null != b && 0 < a.length && 0 < b.length ? (this.n = q(a, 16), this.e = parseInt(b, 16), this.d = q(c, 16), this.p = q(d, 16), this.q = q(f, 16), this.dmp1 = q(g, 16), this.dmq1 = q(h, 16), this.coeff = q(k, 16)) : console.error("Invalid RSA private key")
    };
    n.prototype.generate = function(a, b) {
        var c = new K,
            d = a >> 1;
        this.e = parseInt(b, 16);
        for (var f = new e(b, 16);;) {
            for (; this.p = new e(a - d, 1, c), 0 != this.p.subtract(e.ONE).gcd(f).compareTo(e.ONE) || !this.p.isProbablePrime(10););
            for (; this.q = new e(d, 1, c), 0 != this.q.subtract(e.ONE).gcd(f).compareTo(e.ONE) || !this.q.isProbablePrime(10););
            if (0 >= this.p.compareTo(this.q)) {
                var g = this.p;
                this.p = this.q;
                this.q = g
            }
            var g = this.p.subtract(e.ONE),
                h = this.q.subtract(e.ONE),
                k = g.multiply(h);
            if (0 == k.gcd(f).compareTo(e.ONE)) {
                this.n = this.p.multiply(this.q);
                this.d = f.modInverse(k);
                this.dmp1 = this.d.mod(g);
                this.dmq1 = this.d.mod(h);
                this.coeff = this.q.modInverse(this.p);
                break
            }
        }
    };
    n.prototype.decrypt = function(a) {
        a = q(a, 16);
        a = this.doPrivate(a);
        if (null == a) return null;
        a: {
            var b = this.n.bitLength() + 7 >> 3;
            a = a.toByteArray();
            for (var c = 0; c < a.length && 0 == a[c];) ++c;
            if (a.length - c != b - 1 || 2 != a[c]) a = null;
            else {
                for (++c; 0 != a[c];)
                    if (++c >= a.length) {
                        a = null;
                        break a
                    }
                for (b = ""; ++c < a.length;) {
                    var d = a[c] & 255;
                    128 > d ? b += String.fromCharCode(d) : 191 < d && 224 > d ? (b += String.fromCharCode((d & 31) << 6 | a[c +
                        1] & 63), ++c) : (b += String.fromCharCode((d & 15) << 12 | (a[c + 1] & 63) << 6 | a[c + 2] & 63), c += 2)
                }
                a = b
            }
        }
        return a
    };
    (function() {
        n.prototype.generateAsync = function(a, b, c) {
            var d = new K,
                f = a >> 1;
            this.e = parseInt(b, 16);
            var g = new e(b, 16),
                h = this,
                k = function() {
                    var b = function() {
                            if (0 >= h.p.compareTo(h.q)) {
                                var a = h.p;
                                h.p = h.q;
                                h.q = a
                            }
                            var a = h.p.subtract(e.ONE),
                                b = h.q.subtract(e.ONE),
                                d = a.multiply(b);
                            0 == d.gcd(g).compareTo(e.ONE) ? (h.n = h.p.multiply(h.q), h.d = g.modInverse(d), h.dmp1 = h.d.mod(a), h.dmq1 = h.d.mod(b), h.coeff = h.q.modInverse(h.p), setTimeout(function() {
                                    c()
                                },
                                0)) : setTimeout(k, 0)
                        },
                        l = function() {
                            h.q = m();
                            h.q.fromNumberAsync(f, 1, d, function() {
                                h.q.subtract(e.ONE).gcda(g, function(a) {
                                    0 == a.compareTo(e.ONE) && h.q.isProbablePrime(10) ? setTimeout(b, 0) : setTimeout(l, 0)
                                })
                            })
                        },
                        n = function() {
                            h.p = m();
                            h.p.fromNumberAsync(a - f, 1, d, function() {
                                h.p.subtract(e.ONE).gcda(g, function(a) {
                                    0 == a.compareTo(e.ONE) && h.p.isProbablePrime(10) ? setTimeout(l, 0) : setTimeout(n, 0)
                                })
                            })
                        };
                    setTimeout(n, 0)
                };
            setTimeout(k, 0)
        };
        e.prototype.gcda = function(a, b) {
            var c = 0 > this.s ? this.negate() : this.clone(),
                d = 0 > a.s ? a.negate() :
                a.clone();
            if (0 > c.compareTo(d)) var f = c,
                c = d,
                d = f;
            var g = c.getLowestSetBit(),
                h = d.getLowestSetBit();
            if (0 > h) b(c);
            else {
                g < h && (h = g);
                0 < h && (c.rShiftTo(h, c), d.rShiftTo(h, d));
                var k = function() {
                    0 < (g = c.getLowestSetBit()) && c.rShiftTo(g, c);
                    0 < (g = d.getLowestSetBit()) && d.rShiftTo(g, d);
                    0 <= c.compareTo(d) ? (c.subTo(d, c), c.rShiftTo(1, c)) : (d.subTo(c, d), d.rShiftTo(1, d));
                    0 < c.signum() ? setTimeout(k, 0) : (0 < h && d.lShiftTo(h, d), setTimeout(function() {
                        b(d)
                    }, 0))
                };
                setTimeout(k, 10)
            }
        };
        e.prototype.fromNumberAsync = function(a, b, c, d) {
            if ("number" ==
                typeof b)
                if (2 > a) this.fromInt(1);
                else {
                    this.fromNumber(a, c);
                    this.testBit(a - 1) || this.bitwiseTo(e.ONE.shiftLeft(a - 1), J, this);
                    this.isEven() && this.dAddOffset(1, 0);
                    var f = this,
                        g = function() {
                            f.dAddOffset(2, 0);
                            f.bitLength() > a && f.subTo(e.ONE.shiftLeft(a - 1), f);
                            f.isProbablePrime(b) ? setTimeout(function() {
                                d()
                            }, 0) : setTimeout(g, 0)
                        };
                    setTimeout(g, 0)
                } else {
                c = [];
                var h = a & 7;
                c.length = (a >> 3) + 1;
                b.nextBytes(c);
                c[0] = 0 < h ? c[0] & (1 << h) - 1 : 0;
                this.fromString(c, 256)
            }
        }
    })();
    var E = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",
        X = "=",
        l = l || {};
    l.env = l.env || {};
    var Q = l,
        R = Object.prototype,
        Y = ["toString", "valueOf"];
    l.env.parseUA = function(a) {
        var b = function(a) {
                var b = 0;
                return parseFloat(a.replace(/\./g, function() {
                    return 1 == b++ ? "" : "."
                }))
            },
            c = navigator,
            c = {
                ie: 0,
                opera: 0,
                gecko: 0,
                webkit: 0,
                chrome: 0,
                mobile: null,
                air: 0,
                ipad: 0,
                iphone: 0,
                ipod: 0,
                ios: null,
                android: 0,
                webos: 0,
                caja: c && c.cajaVersion,
                secure: !1,
                os: null
            };
        a = a || navigator && navigator.userAgent;
        var d = window && window.location,
            d = d && d.href;
        c.secure = d && 0 === d.toLowerCase().indexOf("https");
        if (a) {
            /windows|win32/i.test(a) ?
                c.os = "windows" : /macintosh/i.test(a) ? c.os = "macintosh" : /rhino/i.test(a) && (c.os = "rhino");
            /KHTML/.test(a) && (c.webkit = 1);
            if ((d = a.match(/AppleWebKit\/([^\s]*)/)) && d[1]) {
                c.webkit = b(d[1]);
                if (/ Mobile\//.test(a)) c.mobile = "Apple", (d = a.match(/OS ([^\s]*)/)) && d[1] && (d = b(d[1].replace("_", "."))), c.ios = d, c.ipad = c.ipod = c.iphone = 0, (d = a.match(/iPad|iPod|iPhone/)) && d[0] && (c[d[0].toLowerCase()] = c.ios);
                else {
                    if (d = a.match(/NokiaN[^\/]*|Android \d\.\d|webOS\/\d\.\d/)) c.mobile = d[0];
                    /webOS/.test(a) && (c.mobile = "WebOS", (d = a.match(/webOS\/([^\s]*);/)) &&
                        d[1] && (c.webos = b(d[1])));
                    / Android/.test(a) && (c.mobile = "Android", (d = a.match(/Android ([^\s]*);/)) && d[1] && (c.android = b(d[1])))
                }
                if ((d = a.match(/Chrome\/([^\s]*)/)) && d[1]) c.chrome = b(d[1]);
                else if (d = a.match(/AdobeAIR\/([^\s]*)/)) c.air = d[0]
            }
            if (!c.webkit)
                if ((d = a.match(/Opera[\s\/]([^\s]*)/)) && d[1]) {
                    if (c.opera = b(d[1]), (d = a.match(/Version\/([^\s]*)/)) && d[1] && (c.opera = b(d[1])), d = a.match(/Opera Mini[^;]*/)) c.mobile = d[0]
                } else if ((d = a.match(/MSIE\s([^;]*)/)) && d[1]) c.ie = b(d[1]);
            else if (d = a.match(/Gecko\/([^\s]*)/)) c.gecko =
                1, (d = a.match(/rv:([^\s\)]*)/)) && d[1] && (c.gecko = b(d[1]))
        }
        return c
    };
    l.env.ua = l.env.parseUA();
    l.isFunction = function(a) {
        return "function" === typeof a || "[object Function]" === R.toString.apply(a)
    };
    l._IEEnumFix = l.env.ua.ie ? function(a, b) {
        var c, d, f;
        for (c = 0; c < Y.length; c += 1) d = Y[c], f = b[d], Q.isFunction(f) && f != R[d] && (a[d] = f)
    } : function() {};
    l.extend = function(a, b, c) {
        if (!b || !a) throw Error("extend failed, please check that all dependencies are included.");
        var d = function() {},
            f;
        d.prototype = b.prototype;
        a.prototype = new d;
        a.prototype.constructor = a;
        a.superclass = b.prototype;
        b.prototype.constructor == R.constructor && (b.prototype.constructor = b);
        if (c) {
            for (f in c) Q.hasOwnProperty(c, f) && (a.prototype[f] = c[f]);
            Q._IEEnumFix(a.prototype, c)
        }
    };
    "undefined" != typeof KJUR && KJUR || (KJUR = {});
    "undefined" != typeof KJUR.asn1 && KJUR.asn1 || (KJUR.asn1 = {});
    KJUR.asn1.ASN1Util = new function() {
        this.integerToByteHex = function(a) {
            a = a.toString(16);
            1 == a.length % 2 && (a = "0" + a);
            return a
        };
        this.bigIntToMinTwosComplementsHex = function(a) {
            var b = a.toString(16);
            if ("-" !=
                b.substr(0, 1)) 1 == b.length % 2 ? b = "0" + b : b.match(/^[0-7]/) || (b = "00" + b);
            else {
                var c = b.substr(1).length;
                1 == c % 2 ? c += 1 : b.match(/^[0-7]/) || (c += 2);
                for (var b = "", d = 0; d < c; d++) b += "f";
                b = (new e(b, 16)).xor(a).add(e.ONE).toString(16).replace(/^-/, "")
            }
            return b
        };
        this.getPEMStringFromHex = function(a, b) {
            var c = CryptoJS.enc.Hex.parse(a),
                c = CryptoJS.enc.Base64.stringify(c).replace(/(.{64})/g, "$1\r\n"),
                c = c.replace(/\r\n$/, "");
            return "-----BEGIN " + b + "-----\r\n" + c + "\r\n-----END " + b + "-----\r\n"
        }
    };
    KJUR.asn1.ASN1Object = function() {
        this.getLengthHexFromValue =
            function() {
                if ("undefined" == typeof this.hV || null == this.hV) throw "this.hV is null or undefined.";
                if (1 == this.hV.length % 2) throw "value hex must be even length: n=0,v=" + this.hV;
                var a = this.hV.length / 2,
                    b = a.toString(16);
                1 == b.length % 2 && (b = "0" + b);
                if (128 > a) return b;
                var c = b.length / 2;
                if (15 < c) throw "ASN.1 length too long to represent by 8x: n = " + a.toString(16);
                return (128 + c).toString(16) + b
            };
        this.getEncodedHex = function() {
            if (null == this.hTLV || this.isModified) this.hV = this.getFreshValueHex(), this.hL = this.getLengthHexFromValue(),
                this.hTLV = this.hT + this.hL + this.hV, this.isModified = !1;
            return this.hTLV
        };
        this.getValueHex = function() {
            this.getEncodedHex();
            return this.hV
        };
        this.getFreshValueHex = function() {
            return ""
        }
    };
    KJUR.asn1.DERAbstractString = function(a) {
        KJUR.asn1.DERAbstractString.superclass.constructor.call(this);
        this.getString = function() {
            return this.s
        };
        this.setString = function(a) {
            this.hTLV = null;
            this.isModified = !0;
            this.s = a;
            this.hV = stohex(this.s)
        };
        this.setStringHex = function(a) {
            this.hTLV = null;
            this.isModified = !0;
            this.s = null;
            this.hV =
                a
        };
        this.getFreshValueHex = function() {
            return this.hV
        };
        "undefined" != typeof a && ("undefined" != typeof a.str ? this.setString(a.str) : "undefined" != typeof a.hex && this.setStringHex(a.hex))
    };
    l.extend(KJUR.asn1.DERAbstractString, KJUR.asn1.ASN1Object);
    KJUR.asn1.DERAbstractTime = function(a) {
        KJUR.asn1.DERAbstractTime.superclass.constructor.call(this);
        this.localDateToUTC = function(a) {
            utc = a.getTime() + 6E4 * a.getTimezoneOffset();
            return new Date(utc)
        };
        this.formatDate = function(a, c) {
            var d = this.zeroPadding,
                f = this.localDateToUTC(a),
                g = String(f.getFullYear());
            "utc" == c && (g = g.substr(2, 2));
            var h = d(String(f.getMonth() + 1), 2),
                k = d(String(f.getDate()), 2),
                e = d(String(f.getHours()), 2),
                l = d(String(f.getMinutes()), 2),
                d = d(String(f.getSeconds()), 2);
            return g + h + k + e + l + d + "Z"
        };
        this.zeroPadding = function(a, c) {
            return a.length >= c ? a : Array(c - a.length + 1).join("0") + a
        };
        this.getString = function() {
            return this.s
        };
        this.setString = function(a) {
            this.hTLV = null;
            this.isModified = !0;
            this.s = a;
            this.hV = stohex(this.s)
        };
        this.setByDateValue = function(a, c, d, f, g, h) {
            a = new Date(Date.UTC(a,
                c - 1, d, f, g, h, 0));
            this.setByDate(a)
        };
        this.getFreshValueHex = function() {
            return this.hV
        }
    };
    l.extend(KJUR.asn1.DERAbstractTime, KJUR.asn1.ASN1Object);
    KJUR.asn1.DERAbstractStructured = function(a) {
        KJUR.asn1.DERAbstractString.superclass.constructor.call(this);
        this.setByASN1ObjectArray = function(a) {
            this.hTLV = null;
            this.isModified = !0;
            this.asn1Array = a
        };
        this.appendASN1Object = function(a) {
            this.hTLV = null;
            this.isModified = !0;
            this.asn1Array.push(a)
        };
        this.asn1Array = [];
        "undefined" != typeof a && "undefined" != typeof a.array && (this.asn1Array =
            a.array)
    };
    l.extend(KJUR.asn1.DERAbstractStructured, KJUR.asn1.ASN1Object);
    KJUR.asn1.DERBoolean = function() {
        KJUR.asn1.DERBoolean.superclass.constructor.call(this);
        this.hT = "01";
        this.hTLV = "0101ff"
    };
    l.extend(KJUR.asn1.DERBoolean, KJUR.asn1.ASN1Object);
    KJUR.asn1.DERInteger = function(a) {
        KJUR.asn1.DERInteger.superclass.constructor.call(this);
        this.hT = "02";
        this.setByBigInteger = function(a) {
            this.hTLV = null;
            this.isModified = !0;
            this.hV = KJUR.asn1.ASN1Util.bigIntToMinTwosComplementsHex(a)
        };
        this.setByInteger = function(a) {
            a =
                new e(String(a), 10);
            this.setByBigInteger(a)
        };
        this.setValueHex = function(a) {
            this.hV = a
        };
        this.getFreshValueHex = function() {
            return this.hV
        };
        "undefined" != typeof a && ("undefined" != typeof a.bigint ? this.setByBigInteger(a.bigint) : "undefined" != typeof a["int"] ? this.setByInteger(a["int"]) : "undefined" != typeof a.hex && this.setValueHex(a.hex))
    };
    l.extend(KJUR.asn1.DERInteger, KJUR.asn1.ASN1Object);
    KJUR.asn1.DERBitString = function(a) {
        KJUR.asn1.DERBitString.superclass.constructor.call(this);
        this.hT = "03";
        this.setHexValueIncludingUnusedBits =
            function(a) {
                this.hTLV = null;
                this.isModified = !0;
                this.hV = a
            };
        this.setUnusedBitsAndHexValue = function(a, c) {
            if (0 > a || 7 < a) throw "unused bits shall be from 0 to 7: u = " + a;
            this.hTLV = null;
            this.isModified = !0;
            this.hV = "0" + a + c
        };
        this.setByBinaryString = function(a) {
            a = a.replace(/0+$/, "");
            var c = 8 - a.length % 8;
            8 == c && (c = 0);
            for (var d = 0; d <= c; d++) a += "0";
            for (var f = "", d = 0; d < a.length - 1; d += 8) {
                var g = a.substr(d, 8),
                    g = parseInt(g, 2).toString(16);
                1 == g.length && (g = "0" + g);
                f += g
            }
            this.hTLV = null;
            this.isModified = !0;
            this.hV = "0" + c + f
        };
        this.setByBooleanArray =
            function(a) {
                for (var c = "", d = 0; d < a.length; d++) c = !0 == a[d] ? c + "1" : c + "0";
                this.setByBinaryString(c)
            };
        this.newFalseArray = function(a) {
            for (var c = Array(a), d = 0; d < a; d++) c[d] = !1;
            return c
        };
        this.getFreshValueHex = function() {
            return this.hV
        };
        "undefined" != typeof a && ("undefined" != typeof a.hex ? this.setHexValueIncludingUnusedBits(a.hex) : "undefined" != typeof a.bin ? this.setByBinaryString(a.bin) : "undefined" != typeof a.array && this.setByBooleanArray(a.array))
    };
    l.extend(KJUR.asn1.DERBitString, KJUR.asn1.ASN1Object);
    KJUR.asn1.DEROctetString =
        function(a) {
            KJUR.asn1.DEROctetString.superclass.constructor.call(this, a);
            this.hT = "04"
        };
    l.extend(KJUR.asn1.DEROctetString, KJUR.asn1.DERAbstractString);
    KJUR.asn1.DERNull = function() {
        KJUR.asn1.DERNull.superclass.constructor.call(this);
        this.hT = "05";
        this.hTLV = "0500"
    };
    l.extend(KJUR.asn1.DERNull, KJUR.asn1.ASN1Object);
    KJUR.asn1.DERObjectIdentifier = function(a) {
        var b = function(a) {
            a = a.toString(16);
            1 == a.length && (a = "0" + a);
            return a
        };
        KJUR.asn1.DERObjectIdentifier.superclass.constructor.call(this);
        this.hT = "06";
        this.setValueHex =
            function(a) {
                this.hTLV = null;
                this.isModified = !0;
                this.s = null;
                this.hV = a
            };
        this.setValueOidString = function(a) {
            if (!a.match(/^[0-9.]+$/)) throw "malformed oid string: " + a;
            var d = "";
            a = a.split(".");
            var f = 40 * parseInt(a[0]) + parseInt(a[1]),
                d = d + b(f);
            a.splice(0, 2);
            for (f = 0; f < a.length; f++) {
                var g = "",
                    h = (new e(a[f], 10)).toString(2),
                    k = 7 - h.length % 7;
                7 == k && (k = 0);
                for (var x = "", l = 0; l < k; l++) x += "0";
                h = x + h;
                for (l = 0; l < h.length - 1; l += 7) k = h.substr(l, 7), l != h.length - 7 && (k = "1" + k), g += b(parseInt(k, 2));
                d += g
            }
            this.hTLV = null;
            this.isModified = !0;
            this.s = null;
            this.hV = d
        };
        this.setValueName = function(a) {
            if ("undefined" != typeof KJUR.asn1.x509.OID.name2oidList[a]) this.setValueOidString(KJUR.asn1.x509.OID.name2oidList[a]);
            else throw "DERObjectIdentifier oidName undefined: " + a;
        };
        this.getFreshValueHex = function() {
            return this.hV
        };
        "undefined" != typeof a && ("undefined" != typeof a.oid ? this.setValueOidString(a.oid) : "undefined" != typeof a.hex ? this.setValueHex(a.hex) : "undefined" != typeof a.name && this.setValueName(a.name))
    };
    l.extend(KJUR.asn1.DERObjectIdentifier, KJUR.asn1.ASN1Object);
    KJUR.asn1.DERUTF8String = function(a) {
        KJUR.asn1.DERUTF8String.superclass.constructor.call(this, a);
        this.hT = "0c"
    };
    l.extend(KJUR.asn1.DERUTF8String, KJUR.asn1.DERAbstractString);
    KJUR.asn1.DERNumericString = function(a) {
        KJUR.asn1.DERNumericString.superclass.constructor.call(this, a);
        this.hT = "12"
    };
    l.extend(KJUR.asn1.DERNumericString, KJUR.asn1.DERAbstractString);
    KJUR.asn1.DERPrintableString = function(a) {
        KJUR.asn1.DERPrintableString.superclass.constructor.call(this, a);
        this.hT = "13"
    };
    l.extend(KJUR.asn1.DERPrintableString,
        KJUR.asn1.DERAbstractString);
    KJUR.asn1.DERTeletexString = function(a) {
        KJUR.asn1.DERTeletexString.superclass.constructor.call(this, a);
        this.hT = "14"
    };
    l.extend(KJUR.asn1.DERTeletexString, KJUR.asn1.DERAbstractString);
    KJUR.asn1.DERIA5String = function(a) {
        KJUR.asn1.DERIA5String.superclass.constructor.call(this, a);
        this.hT = "16"
    };
    l.extend(KJUR.asn1.DERIA5String, KJUR.asn1.DERAbstractString);
    KJUR.asn1.DERUTCTime = function(a) {
        KJUR.asn1.DERUTCTime.superclass.constructor.call(this, a);
        this.hT = "17";
        this.setByDate = function(a) {
            this.hTLV =
                null;
            this.isModified = !0;
            this.date = a;
            this.s = this.formatDate(this.date, "utc");
            this.hV = stohex(this.s)
        };
        "undefined" != typeof a && ("undefined" != typeof a.str ? this.setString(a.str) : "undefined" != typeof a.hex ? this.setStringHex(a.hex) : "undefined" != typeof a.date && this.setByDate(a.date))
    };
    l.extend(KJUR.asn1.DERUTCTime, KJUR.asn1.DERAbstractTime);
    KJUR.asn1.DERGeneralizedTime = function(a) {
        KJUR.asn1.DERGeneralizedTime.superclass.constructor.call(this, a);
        this.hT = "18";
        this.setByDate = function(a) {
            this.hTLV = null;
            this.isModified = !0;
            this.date = a;
            this.s = this.formatDate(this.date, "gen");
            this.hV = stohex(this.s)
        };
        "undefined" != typeof a && ("undefined" != typeof a.str ? this.setString(a.str) : "undefined" != typeof a.hex ? this.setStringHex(a.hex) : "undefined" != typeof a.date && this.setByDate(a.date))
    };
    l.extend(KJUR.asn1.DERGeneralizedTime, KJUR.asn1.DERAbstractTime);
    KJUR.asn1.DERSequence = function(a) {
        KJUR.asn1.DERSequence.superclass.constructor.call(this, a);
        this.hT = "30";
        this.getFreshValueHex = function() {
            for (var a = "", c = 0; c < this.asn1Array.length; c++) a +=
                this.asn1Array[c].getEncodedHex();
            return this.hV = a
        }
    };
    l.extend(KJUR.asn1.DERSequence, KJUR.asn1.DERAbstractStructured);
    KJUR.asn1.DERSet = function(a) {
        KJUR.asn1.DERSet.superclass.constructor.call(this, a);
        this.hT = "31";
        this.getFreshValueHex = function() {
            for (var a = [], c = 0; c < this.asn1Array.length; c++) a.push(this.asn1Array[c].getEncodedHex());
            a.sort();
            return this.hV = a.join("")
        }
    };
    l.extend(KJUR.asn1.DERSet, KJUR.asn1.DERAbstractStructured);
    KJUR.asn1.DERTaggedObject = function(a) {
        KJUR.asn1.DERTaggedObject.superclass.constructor.call(this);
        this.hT = "a0";
        this.hV = "";
        this.isExplicit = !0;
        this.asn1Object = null;
        this.setASN1Object = function(a, c, d) {
            this.hT = c;
            this.isExplicit = a;
            this.asn1Object = d;
            this.isExplicit ? (this.hV = this.asn1Object.getEncodedHex(), this.hTLV = null, this.isModified = !0) : (this.hV = null, this.hTLV = d.getEncodedHex(), this.hTLV = this.hTLV.replace(/^../, c), this.isModified = !1)
        };
        this.getFreshValueHex = function() {
            return this.hV
        };
        "undefined" != typeof a && ("undefined" != typeof a.tag && (this.hT = a.tag), "undefined" != typeof a.explicit && (this.isExplicit =
            a.explicit), "undefined" != typeof a.obj && (this.asn1Object = a.obj, this.setASN1Object(this.isExplicit, this.hT, this.asn1Object)))
    };
    l.extend(KJUR.asn1.DERTaggedObject, KJUR.asn1.ASN1Object);
    (function(a) {
        var b = {},
            c;
        b.decode = function(b) {
            var f;
            if (c === a) {
                var g = "0123456789ABCDEF";
                c = [];
                for (f = 0; 16 > f; ++f) c[g.charAt(f)] = f;
                g = g.toLowerCase();
                for (f = 10; 16 > f; ++f) c[g.charAt(f)] = f;
                for (f = 0; 8 > f; ++f) c[" \f\n\r\t\u00a0\u2028\u2029".charAt(f)] = -1
            }
            var g = [],
                h = 0,
                k = 0;
            for (f = 0; f < b.length; ++f) {
                var e = b.charAt(f);
                if ("=" == e) break;
                e = c[e];
                if (-1 != e) {
                    if (e === a) throw "Illegal character at offset " + f;
                    h |= e;
                    2 <= ++k ? (g[g.length] = h, k = h = 0) : h <<= 4
                }
            }
            if (k) throw "Hex encoding incomplete: 4 bits missing";
            return g
        };
        window.Hex = b
    })();
    (function(a) {
        var b = {},
            c;
        b.decode = function(b) {
            var f;
            if (c === a) {
                c = [];
                for (f = 0; 64 > f; ++f) c["ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/".charAt(f)] = f;
                for (f = 0; 9 > f; ++f) c["= \f\n\r\t\u00a0\u2028\u2029".charAt(f)] = -1
            }
            var g = [],
                h = 0,
                k = 0;
            for (f = 0; f < b.length; ++f) {
                var e = b.charAt(f);
                if ("=" == e) break;
                e = c[e];
                if (-1 != e) {
                    if (e ===
                        a) throw "Illegal character at offset " + f;
                    h |= e;
                    4 <= ++k ? (g[g.length] = h >> 16, g[g.length] = h >> 8 & 255, g[g.length] = h & 255, k = h = 0) : h <<= 6
                }
            }
            switch (k) {
                case 1:
                    throw "Base64 encoding incomplete: at least 2 bits missing";
                case 2:
                    g[g.length] = h >> 10;
                    break;
                case 3:
                    g[g.length] = h >> 16, g[g.length] = h >> 8 & 255
            }
            return g
        };
        b.re = /-----BEGIN [^-]+-----([A-Za-z0-9+\/=\s]+)-----END [^-]+-----|begin-base64[^\n]+\n([A-Za-z0-9+\/=\s]+)====/;
        b.unarmor = function(a) {
            var c = b.re.exec(a);
            if (c)
                if (c[1]) a = c[1];
                else if (c[2]) a = c[2];
            else throw "RegExp out of sync";
            return b.decode(a)
        };
        window.Base64 = b
    })();
    (function(a) {
        function b(a, c) {
            a instanceof b ? (this.enc = a.enc, this.pos = a.pos) : (this.enc = a, this.pos = c)
        }

        function c(a, b, c, d, e) {
            this.stream = a;
            this.header = b;
            this.length = c;
            this.tag = d;
            this.sub = e
        }
        var d = {
            tag: function(a, b) {
                var c = document.createElement(a);
                c.className = b;
                return c
            },
            text: function(a) {
                return document.createTextNode(a)
            }
        };
        b.prototype.get = function(b) {
            b === a && (b = this.pos++);
            if (b >= this.enc.length) throw "Requesting byte offset " + b + " on a stream of length " + this.enc.length;
            return this.enc[b]
        };
        b.prototype.hexDigits = "0123456789ABCDEF";
        b.prototype.hexByte = function(a) {
            return this.hexDigits.charAt(a >> 4 & 15) + this.hexDigits.charAt(a & 15)
        };
        b.prototype.hexDump = function(a, b, c) {
            for (var d = ""; a < b; ++a)
                if (d += this.hexByte(this.get(a)), !0 !== c) switch (a & 15) {
                    case 7:
                        d += "  ";
                        break;
                    case 15:
                        d += "\n";
                        break;
                    default:
                        d += " "
                }
                return d
        };
        b.prototype.parseStringISO = function(a, b) {
            for (var c = "", d = a; d < b; ++d) c += String.fromCharCode(this.get(d));
            return c
        };
        b.prototype.parseStringUTF = function(a, b) {
            for (var c = "",
                    d = a; d < b;) var e = this.get(d++),
                c = 128 > e ? c + String.fromCharCode(e) : 191 < e && 224 > e ? c + String.fromCharCode((e & 31) << 6 | this.get(d++) & 63) : c + String.fromCharCode((e & 15) << 12 | (this.get(d++) & 63) << 6 | this.get(d++) & 63);
            return c
        };
        b.prototype.parseStringBMP = function(a, b) {
            for (var c = "", d = a; d < b; d += 2) var e = this.get(d),
                l = this.get(d + 1),
                c = c + String.fromCharCode((e << 8) + l);
            return c
        };
        b.prototype.reTime = /^((?:1[89]|2\d)?\d\d)(0[1-9]|1[0-2])(0[1-9]|[12]\d|3[01])([01]\d|2[0-3])(?:([0-5]\d)(?:([0-5]\d)(?:[.,](\d{1,3}))?)?)?(Z|[-+](?:[0]\d|1[0-2])([0-5]\d)?)?$/;
        b.prototype.parseTime = function(a, b) {
            var c = this.parseStringISO(a, b),
                d = this.reTime.exec(c);
            if (!d) return "Unrecognized time: " + c;
            c = d[1] + "-" + d[2] + "-" + d[3] + " " + d[4];
            d[5] && (c += ":" + d[5], d[6] && (c += ":" + d[6], d[7] && (c += "." + d[7])));
            d[8] && (c += " UTC", "Z" != d[8] && (c += d[8], d[9] && (c += ":" + d[9])));
            return c
        };
        b.prototype.parseInteger = function(a, c) {
            var b = c - a;
            if (4 < b) {
                var b = b << 3,
                    d = this.get(a);
                if (0 === d) b -= 8;
                else
                    for (; 128 > d;) d <<= 1, --b;
                return "(" + b + " bit)"
            }
            b = 0;
            for (d = a; d < c; ++d) b = b << 8 | this.get(d);
            return b
        };
        b.prototype.parseBitString =
            function(a, b) {
                var c = this.get(a),
                    d = (b - a - 1 << 3) - c,
                    e = "(" + d + " bit)";
                if (20 >= d)
                    for (var l = c, e = e + " ", c = b - 1; c > a; --c) {
                        for (d = this.get(c); 8 > l; ++l) e += d >> l & 1 ? "1" : "0";
                        l = 0
                    }
                return e
            };
        b.prototype.parseOctetString = function(a, b) {
            var c = b - a,
                d = "(" + c + " byte) ";
            100 < c && (b = a + 100);
            for (var e = a; e < b; ++e) d += this.hexByte(this.get(e));
            100 < c && (d += "\u2026");
            return d
        };
        b.prototype.parseOID = function(a, b) {
            for (var c = "", d = 0, e = 0, l = a; l < b; ++l) {
                var m = this.get(l),
                    d = d << 7 | m & 127,
                    e = e + 7;
                m & 128 || ("" === c ? (c = 80 > d ? 40 > d ? 0 : 1 : 2, c = c + "." + (d - 40 * c)) : c += "." + (31 <=
                    e ? "bigint" : d), d = e = 0)
            }
            return c
        };
        c.prototype.typeName = function() {
            if (this.tag === a) return "unknown";
            var c = this.tag & 31;
            switch (this.tag >> 6) {
                case 0:
                    switch (c) {
                        case 0:
                            return "EOC";
                        case 1:
                            return "BOOLEAN";
                        case 2:
                            return "INTEGER";
                        case 3:
                            return "BIT_STRING";
                        case 4:
                            return "OCTET_STRING";
                        case 5:
                            return "NULL";
                        case 6:
                            return "OBJECT_IDENTIFIER";
                        case 7:
                            return "ObjectDescriptor";
                        case 8:
                            return "EXTERNAL";
                        case 9:
                            return "REAL";
                        case 10:
                            return "ENUMERATED";
                        case 11:
                            return "EMBEDDED_PDV";
                        case 12:
                            return "UTF8String";
                        case 16:
                            return "SEQUENCE";
                        case 17:
                            return "SET";
                        case 18:
                            return "NumericString";
                        case 19:
                            return "PrintableString";
                        case 20:
                            return "TeletexString";
                        case 21:
                            return "VideotexString";
                        case 22:
                            return "IA5String";
                        case 23:
                            return "UTCTime";
                        case 24:
                            return "GeneralizedTime";
                        case 25:
                            return "GraphicString";
                        case 26:
                            return "VisibleString";
                        case 27:
                            return "GeneralString";
                        case 28:
                            return "UniversalString";
                        case 30:
                            return "BMPString";
                        default:
                            return "Universal_" + c.toString(16)
                    }
                case 1:
                    return "Application_" + c.toString(16);
                case 2:
                    return "[" + c + "]";
                case 3:
                    return "Private_" +
                        c.toString(16)
            }
        };
        c.prototype.reSeemsASCII = /^[ -~]+$/;
        c.prototype.content = function() {
            if (this.tag === a) return null;
            var c = this.tag >> 6,
                b = this.tag & 31,
                d = this.posContent(),
                e = Math.abs(this.length);
            if (0 !== c) {
                if (null !== this.sub) return "(" + this.sub.length + " elem)";
                c = this.stream.parseStringISO(d, d + Math.min(e, 100));
                return this.reSeemsASCII.test(c) ? c.substring(0, 200) + (200 < c.length ? "\u2026" : "") : this.stream.parseOctetString(d, d + e)
            }
            switch (b) {
                case 1:
                    return 0 === this.stream.get(d) ? "false" : "true";
                case 2:
                    return this.stream.parseInteger(d,
                        d + e);
                case 3:
                    return this.sub ? "(" + this.sub.length + " elem)" : this.stream.parseBitString(d, d + e);
                case 4:
                    return this.sub ? "(" + this.sub.length + " elem)" : this.stream.parseOctetString(d, d + e);
                case 6:
                    return this.stream.parseOID(d, d + e);
                case 16:
                case 17:
                    return "(" + this.sub.length + " elem)";
                case 12:
                    return this.stream.parseStringUTF(d, d + e);
                case 18:
                case 19:
                case 20:
                case 21:
                case 22:
                case 26:
                    return this.stream.parseStringISO(d, d + e);
                case 30:
                    return this.stream.parseStringBMP(d, d + e);
                case 23:
                case 24:
                    return this.stream.parseTime(d,
                        d + e)
            }
            return null
        };
        c.prototype.toString = function() {
            return this.typeName() + "@" + this.stream.pos + "[header:" + this.header + ",length:" + this.length + ",sub:" + (null === this.sub ? "null" : this.sub.length) + "]"
        };
        c.prototype.print = function(c) {
            c === a && (c = "");
            document.writeln(c + this);
            if (null !== this.sub) {
                c += "  ";
                for (var b = 0, d = this.sub.length; b < d; ++b) this.sub[b].print(c)
            }
        };
        c.prototype.toPrettyString = function(c) {
            c === a && (c = "");
            var b = c + this.typeName() + " @" + this.stream.pos;
            0 <= this.length && (b += "+");
            b += this.length;
            this.tag & 32 ?
                b += " (constructed)" : 3 != this.tag && 4 != this.tag || null === this.sub || (b += " (encapsulates)");
            b += "\n";
            if (null !== this.sub) {
                c += "  ";
                for (var d = 0, e = this.sub.length; d < e; ++d) b += this.sub[d].toPrettyString(c)
            }
            return b
        };
        c.prototype.toDOM = function() {
            var a = d.tag("div", "node");
            a.asn1 = this;
            var c = d.tag("div", "head"),
                b = this.typeName().replace(/_/g, " ");
            c.innerHTML = b;
            var e = this.content();
            null !== e && (e = String(e).replace(/</g, "&lt;"), b = d.tag("span", "preview"), b.appendChild(d.text(e)), c.appendChild(b));
            a.appendChild(c);
            this.node =
                a;
            this.head = c;
            var l = d.tag("div", "value"),
                b = "Offset: " + this.stream.pos + "<br/>",
                b = b + ("Length: " + this.header + "+"),
                b = 0 <= this.length ? b + this.length : b + (-this.length + " (undefined)");
            this.tag & 32 ? b += "<br/>(constructed)" : 3 != this.tag && 4 != this.tag || null === this.sub || (b += "<br/>(encapsulates)");
            null !== e && (b += "<br/>Value:<br/><b>" + e + "</b>", "object" === typeof oids && 6 == this.tag && (e = oids[e])) && (e.d && (b += "<br/>" + e.d), e.c && (b += "<br/>" + e.c), e.w && (b += "<br/>(warning!)"));
            l.innerHTML = b;
            a.appendChild(l);
            b = d.tag("div", "sub");
            if (null !== this.sub)
                for (e = 0, l = this.sub.length; e < l; ++e) b.appendChild(this.sub[e].toDOM());
            a.appendChild(b);
            c.onclick = function() {
                a.className = "node collapsed" == a.className ? "node" : "node collapsed"
            };
            return a
        };
        c.prototype.posStart = function() {
            return this.stream.pos
        };
        c.prototype.posContent = function() {
            return this.stream.pos + this.header
        };
        c.prototype.posEnd = function() {
            return this.stream.pos + this.header + Math.abs(this.length)
        };
        c.prototype.fakeHover = function(a) {
            this.node.className += " hover";
            a && (this.head.className +=
                " hover")
        };
        c.prototype.fakeOut = function(a) {
            var b = / ?hover/;
            this.node.className = this.node.className.replace(b, "");
            a && (this.head.className = this.head.className.replace(b, ""))
        };
        c.prototype.toHexDOM_sub = function(a, b, c, e, l) {
            e >= l || (b = d.tag("span", b), b.appendChild(d.text(c.hexDump(e, l))), a.appendChild(b))
        };
        c.prototype.toHexDOM = function(b) {
            var c = d.tag("span", "hex");
            b === a && (b = c);
            this.head.hexNode = c;
            this.head.onmouseover = function() {
                this.hexNode.className = "hexCurrent"
            };
            this.head.onmouseout = function() {
                this.hexNode.className =
                    "hex"
            };
            c.asn1 = this;
            c.onmouseover = function() {
                var a = !b.selected;
                a && (b.selected = this.asn1, this.className = "hexCurrent");
                this.asn1.fakeHover(a)
            };
            c.onmouseout = function() {
                var a = b.selected == this.asn1;
                this.asn1.fakeOut(a);
                a && (b.selected = null, this.className = "hex")
            };
            this.toHexDOM_sub(c, "tag", this.stream, this.posStart(), this.posStart() + 1);
            this.toHexDOM_sub(c, 0 <= this.length ? "dlen" : "ulen", this.stream, this.posStart() + 1, this.posContent());
            if (null === this.sub) c.appendChild(d.text(this.stream.hexDump(this.posContent(),
                this.posEnd())));
            else if (0 < this.sub.length) {
                var e = this.sub[0],
                    k = this.sub[this.sub.length - 1];
                this.toHexDOM_sub(c, "intro", this.stream, this.posContent(), e.posStart());
                for (var e = 0, l = this.sub.length; e < l; ++e) c.appendChild(this.sub[e].toHexDOM(b));
                this.toHexDOM_sub(c, "outro", this.stream, k.posEnd(), this.posEnd())
            }
            return c
        };
        c.prototype.toHexString = function(a) {
            return this.stream.hexDump(this.posStart(), this.posEnd(), !0)
        };
        c.decodeLength = function(a) {
            var b = a.get(),
                c = b & 127;
            if (c == b) return c;
            if (3 < c) throw "Length over 24 bits not supported at position " +
                (a.pos - 1);
            if (0 === c) return -1;
            for (var d = b = 0; d < c; ++d) b = b << 8 | a.get();
            return b
        };
        c.hasContent = function(a, d, e) {
            if (a & 32) return !0;
            if (3 > a || 4 < a) return !1;
            var k = new b(e);
            3 == a && k.get();
            if (k.get() >> 6 & 1) return !1;
            try {
                var l = c.decodeLength(k);
                return k.pos - e.pos + l == d
            } catch (m) {
                return !1
            }
        };
        c.decode = function(a) {
            a instanceof b || (a = new b(a, 0));
            var d = new b(a),
                e = a.get(),
                k = c.decodeLength(a),
                l = a.pos - d.pos,
                m = null;
            if (c.hasContent(e, k, a)) {
                var n = a.pos;
                3 == e && a.get();
                m = [];
                if (0 <= k) {
                    for (var p = n + k; a.pos < p;) m[m.length] = c.decode(a);
                    if (a.pos !=
                        p) throw "Content size is not correct for container starting at offset " + n;
                } else try {
                    for (;;) {
                        p = c.decode(a);
                        if (0 === p.tag) break;
                        m[m.length] = p
                    }
                    k = n - a.pos
                } catch (q) {
                    throw "Exception while decoding undefined length content: " + q;
                }
            } else a.pos += k;
            return new c(d, l, k, e, m)
        };
        c.test = function() {
            for (var a = [{
                    value: [39],
                    expected: 39
                }, {
                    value: [129, 201],
                    expected: 201
                }, {
                    value: [131, 254, 220, 186],
                    expected: 16702650
                }], d = 0, e = a.length; d < e; ++d) {
                var k = new b(a[d].value, 0),
                    k = c.decodeLength(k);
                k != a[d].expected && document.write("In test[" +
                    d + "] expected " + a[d].expected + " got " + k + "\n")
            }
        };
        window.ASN1 = c
    })();
    ASN1.prototype.getHexStringValue = function() {
        return this.toHexString().substr(2 * this.header, 2 * this.length)
    };
    n.prototype.parseKey = function(a) {
        try {
            var b = /^\s*(?:[0-9A-Fa-f][0-9A-Fa-f]\s*)+$/.test(a) ? Hex.decode(a) : Base64.unarmor(a),
                c = ASN1.decode(b);
            if (9 === c.sub.length) {
                var d = c.sub[1].getHexStringValue();
                this.n = q(d, 16);
                var e = c.sub[2].getHexStringValue();
                this.e = parseInt(e, 16);
                var g = c.sub[3].getHexStringValue();
                this.d = q(g, 16);
                var h = c.sub[4].getHexStringValue();
                this.p = q(h, 16);
                var k = c.sub[5].getHexStringValue();
                this.q = q(k, 16);
                var l = c.sub[6].getHexStringValue();
                this.dmp1 = q(l, 16);
                var m = c.sub[7].getHexStringValue();
                this.dmq1 = q(m, 16);
                var n = c.sub[8].getHexStringValue();
                this.coeff = q(n, 16)
            } else if (2 === c.sub.length) {
                var p = c.sub[1].sub[0],
                    d = p.sub[0].getHexStringValue();
                this.n = q(d, 16);
                e = p.sub[1].getHexStringValue();
                this.e = parseInt(e, 16)
            } else return !1;
            return !0
        } catch (r) {
            return !1
        }
    };
    n.prototype.getPrivateBaseKey = function() {
        var a = {
            array: [new KJUR.asn1.DERInteger({
                    "int": 0
                }),
                new KJUR.asn1.DERInteger({
                    bigint: this.n
                }), new KJUR.asn1.DERInteger({
                    "int": this.e
                }), new KJUR.asn1.DERInteger({
                    bigint: this.d
                }), new KJUR.asn1.DERInteger({
                    bigint: this.p
                }), new KJUR.asn1.DERInteger({
                    bigint: this.q
                }), new KJUR.asn1.DERInteger({
                    bigint: this.dmp1
                }), new KJUR.asn1.DERInteger({
                    bigint: this.dmq1
                }), new KJUR.asn1.DERInteger({
                    bigint: this.coeff
                })
            ]
        };
        return (new KJUR.asn1.DERSequence(a)).getEncodedHex()
    };
    n.prototype.getPrivateBaseKeyB64 = function() {
        return P(this.getPrivateBaseKey())
    };
    n.prototype.getPublicBaseKey =
        function() {
            var a = {
                    array: [new KJUR.asn1.DERObjectIdentifier({
                        oid: "1.2.840.113549.1.1.1"
                    }), new KJUR.asn1.DERNull]
                },
                b = new KJUR.asn1.DERSequence(a),
                a = {
                    array: [new KJUR.asn1.DERInteger({
                        bigint: this.n
                    }), new KJUR.asn1.DERInteger({
                        "int": this.e
                    })]
                },
                a = {
                    hex: "00" + (new KJUR.asn1.DERSequence(a)).getEncodedHex()
                },
                a = new KJUR.asn1.DERBitString(a),
                a = {
                    array: [b, a]
                };
            return (new KJUR.asn1.DERSequence(a)).getEncodedHex()
        };
    n.prototype.getPublicBaseKeyB64 = function() {
        return P(this.getPublicBaseKey())
    };
    n.prototype.wordwrap = function(a,
        b) {
        b = b || 64;
        return a ? a.match(RegExp("(.{1," + b + "})( +|$\n?)|(.{1," + b + "})", "g")).join("\n") : a
    };
    n.prototype.getPrivateKey = function() {
        var a;
        a = "-----BEGIN RSA PRIVATE KEY-----\n" + (this.wordwrap(this.getPrivateBaseKeyB64()) + "\n");
        return a + "-----END RSA PRIVATE KEY-----"
    };
    n.prototype.getPublicKey = function() {
        var a;
        a = "-----BEGIN PUBLIC KEY-----\n" + (this.wordwrap(this.getPublicBaseKeyB64()) + "\n");
        return a + "-----END PUBLIC KEY-----"
    };
    n.prototype.hasPublicKeyProperty = function(a) {
        a = a || {};
        return a.hasOwnProperty("n") &&
            a.hasOwnProperty("e")
    };
    n.prototype.hasPrivateKeyProperty = function(a) {
        a = a || {};
        return a.hasOwnProperty("n") && a.hasOwnProperty("e") && a.hasOwnProperty("d") && a.hasOwnProperty("p") && a.hasOwnProperty("q") && a.hasOwnProperty("dmp1") && a.hasOwnProperty("dmq1") && a.hasOwnProperty("coeff")
    };
    n.prototype.parsePropertiesFrom = function(a) {
        this.n = a.n;
        this.e = a.e;
        a.hasOwnProperty("d") && (this.d = a.d, this.p = a.p, this.q = a.q, this.dmp1 = a.dmp1, this.dmq1 = a.dmq1, this.coeff = a.coeff)
    };
    var G = function(a) {
        n.call(this);
        a && ("string" ===
            typeof a ? this.parseKey(a) : (this.hasPrivateKeyProperty(a) || this.hasPublicKeyProperty(a)) && this.parsePropertiesFrom(a))
    };
    G.prototype = new n;
    G.prototype.constructor = G;
    l = function(a) {
        a = a || {};
        this.default_key_size = parseInt(a.default_key_size) || 1024;
        this.default_public_exponent = a.default_public_exponent || "010001";
        this.log = a.log || !1;
        this.key = null
    };
    l.prototype.setKey = function(a) {
        this.log && this.key && console.warn("A key was already set, overriding existing.");
        this.key = new G(a)
    };
    l.prototype.setPrivateKey = function(a) {
        this.setKey(a)
    };
    l.prototype.setPublicKey = function(a) {
        this.setKey(a)
    };
    l.prototype.decrypt = function(a) {
        try {
            return this.getKey().decrypt(ca(a))
        } catch (b) {
            return !1
        }
    };
    l.prototype.encrypt = function(a) {
        try {
            return P(this.getKey().encrypt(a))
        } catch (b) {
            return !1
        }
    };
    l.prototype.getKey = function(a) {
        if (!this.key) {
            this.key = new G;
            if (a && "[object Function]" === {}.toString.call(a)) {
                this.key.generateAsync(this.default_key_size, this.default_public_exponent, a);
                return
            }
            this.key.generate(this.default_key_size, this.default_public_exponent)
        }
        return this.key
    };
    l.prototype.getPrivateKey = function() {
        return this.getKey().getPrivateKey()
    };
    l.prototype.getPrivateKeyB64 = function() {
        return this.getKey().getPrivateBaseKeyB64()
    };
    l.prototype.getPublicKey = function() {
        return this.getKey().getPublicKey()
    };
    l.prototype.getPublicKeyB64 = function() {
        return this.getKey().getPublicBaseKeyB64()
    };
    M.JSEncrypt = l
})(JSEncryptExports);
var JSEncrypt = JSEncryptExports.JSEncrypt;

function TxEncrypt(M, e) {
    var m = new JSEncrypt;
    m.setPublicKey(M);
    m = m.encrypt(e);
    return !1 === m ? "Encryption error: Specified key was not a valid RSA key." : m
};