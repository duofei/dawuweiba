/**
 * @preserve Constructor for kissy editor and module dependency definition
 *      thanks to CKSource's intelligent work on CKEditor
 * @author: yiminghe@gmail.com, lifesinger@gmail.com
 * @version: 2.0
 * @buildtime: 2010-11-10 13:03:14
 */
KISSY.add("editor", function(S, undefined) {
    var DOM = S.DOM,
        TRUE = true,
        FALSE = false,
        NULL = null;

    /**
     * 初始化编辑器
     * @constructor
     * @param textarea {(string|KISSY.Node)} 将要替换的 textarea
     * @param cfg {Object} 编辑器配置
     * @return {Editor} 返回编辑器实例
     */
    function Editor(textarea, cfg) {
        var self = this;

        if (!(self instanceof Editor)) {
            return new Editor(textarea, cfg);
        }

        if (S.isString(textarea)) {
            textarea = S.one(textarea);
        }
        textarea = DOM._4e_wrap(textarea);
        cfg = cfg || {};
        cfg.pluginConfig = cfg["pluginConfig"] || {};
        self.cfg = cfg;
        //export for closure compiler
        cfg["pluginConfig"] = cfg.pluginConfig;
        self["cfg"] = cfg;
        S.app(self, S.EventTarget);

        /**
         * templates,separator,image,separator ->
         * templates,separator,image,separator2
         * work around for 重复 attach
         * @param mods {Array.<string>}
         */
        function duplicateMods(mods) {
            var existMods = Editor["Env"]["mods"];
            for (var i = 0; i < mods.length; i++) {
                var mod = mods[i],dup = FALSE;
                for (var j = 0; j < i; j++) {
                    var mod2 = mods[j];
                    if (mod == mod2) {
                        dup = TRUE;
                        break;
                    }
                }
                var existMod = existMods[mod];

                if (dup && existMod) {
                    var newMod = S.clone(existMod),
                        newName = mod + "_" + i;
                    newMod["name"] = newName;
                    mods[i] = newName;
                    if (!existMods[newName]) {
                        existMods[newName] = newMod;
                    }
                }
            }
        }


        var BASIC = ["htmldataprocessor", "enterkey", "clipboard"],
            initial = FALSE;
        /**
         * 存在问题：
         * use 涉及动态加载时
         * 1.相同的模块名不会重复 attach
         * 2.不同模块名相同 js 路径也不会重复 attach
         * @param mods {Array.<string>} ，模块名可以重复
         * @param callback {function()} ，插件载入后回调
         */
        self.use = function(mods, callback) {
            mods = mods.split(",");
            duplicateMods(mods);
            if (!initial) {
                for (var i = 0; i < BASIC.length; i++) {
                    var b = BASIC[i];
                    if (!S.inArray(b, mods)) {
                        mods.unshift(b);
                    }
                }
            }
            S.use.call(self, mods.join(","), function() {

                self.ready(function() {
                    callback && callback.call(self);
                    //也用在窗口按需加载，只有在初始化时才进行内容设置
                    if (!initial) {
                        self.setData(textarea.val());
                        //是否自动focus
                        if (cfg["focus"]) {
                            self.focus();
                        }
                        //否则清空选择区域
                        else {
                            var sel = self.getSelection();
                            sel && sel.removeAllRanges();
                        }
                        self.fire("save");
                        initial = TRUE;
                    }
                });

            }, { "order":  TRUE, "global":  Editor });
            return self;
        };
        self["use"] = self.use;
        self.init(textarea);
        return self;
    }

    S.app(Editor, S.EventTarget);
    Editor["Config"]["base"] = S["Config"]["base"] + "editor/";

    /**
     * 便于在开发环境与部署环境下切换
     * @param url {string}
     * @return {string} 对应环境的 url
     */
    function debugUrl(url) {
        var debug = S["Config"]["debug"],re;
        if (!debug) re = url.replace(/\.(js|css)/i, "-min.$1");
        else if (debug === "dev") {
            re = "../src/" + url;
        } else {
            re = url
        }
        if (re.indexOf("?") != -1) {
            re += "&";
        } else {
            re += "?";
        }
        re += "t=" + encodeURIComponent("2010-11-10 13:03:14");
        return  re;
    }

    var debug = S["Config"]["debug"],
        /**
         * @type {Array.<string>}
         */
        core_mods = [
            "utils",
            "focusmanager",
            "definition",
            "dtd",
            "dom",
            "elementpath",
            "walker",
            "range",
            "domiterator",
            "selection",
            "styles",
            "htmlparser",
            "htmlparser-basicwriter",
            "htmlparser-comment",
            "htmlparser-element",
            "htmlparser-filter",
            "htmlparser-fragment",
            "htmlparser-htmlwriter",
            "htmlparser-text"
        ],
        /**
         * @type {Array.<(string|{name:string,requires:Array.<string>})>}
         */
        plugin_mods = [
            "separator",
            "sourcearea/support",
            "tabs",
            "flashbridge",
            "flashutils",
            "clipboard",

            {
                "name": "colorsupport",
                "requires":["overlay"]
            },
            {
                "name": "colorsupport/dialog"
            },
            {
                "name": "forecolor",
                "requires":["colorsupport"]
            },
            {
                "name": "bgcolor",
                "requires":["colorsupport"]
            },
            {
                "name": "elementpaths"
            },
            "enterkey",
            {
                "name":"pagebreak",
                "requires":["fakeobjects"]
            },
            {
                "name":"fakeobjects",
                "requires":["htmldataprocessor"]
            },
            {
                "name":"draft",
                "requires":["localStorage"]
            },
            {
                "name":"flash",
                "requires":["flash/support"]
            },
            {
                "name":"flash/dialog"
            },
            {
                "name": "flash/support",
                "requires": ["flashutils","contextmenu",
                    "fakeobjects","bubbleview"]
            },
            {
                "name":"font",
                "requires":["select"]
            },
            "format",
            {
                "name": "htmldataprocessor"
            },
            {
                "name": "image",
                "requires": ["contextmenu","bubbleview"]
            },
            {
                "name":"image/dialog",
                "requires":["tabs"]
            },
            "indent",
            "justify",
            {
                "name":"link",
                "requires": ["bubbleview"]
            },
            {
                "name":"link/dialog"
            },
            "list",
            "maximize",
            {
                "name":"music",
                "requires":["flash/support"]
            },
            {
                "name":"music/dialog",
                "requires":["flash/dialog"]
            },
            "preview",
            "removeformat",
            {
                "name": "smiley"
            },
            {
                "name":"sourcearea",
                "requires":["sourcearea/support"]
            },
            {
                "name": "table",
                "requires": ["contextmenu"]
            },
            {
                "name": "table/dialog"
            },
            {
                "name": "templates",
                "requires": ["overlay"]
            },
            "undo",
            {
                "name":"resize",
                "requires":["dd"]
            }
        ],
        /**
         * @type {Array.<(string|{name:string,requires:Array.<string>})>}
         */
        mis_mods = [
            {
                "name":"localStorage",
                "requires":["flashutils",
                    "flashbridge"]
            },
            {"name":"button"},
            {"name":"dd"},
            {"name":"progressbar"},
            {
                "name":"overlay",
                "requires":["dd"]
            },
            {
                "name": "contextmenu",
                "requires": ["overlay"]
            },
            {
                "name": "bubbleview",
                "requires": ["overlay"]
            },
            {
                "name": "select",
                "requires": ["overlay"]
            }
        ],
        i, len,
        /**
         * @type {(string|{name:string,requires:Array.<string>})}
         */
        mod,
        name, requires,mods = {};
    for (i = 0,len = plugin_mods.length; i < len; i++) {
        mod = plugin_mods[i];
        if (S.isString(mod)) {
            mod = plugin_mods[i] = {
                //强制转型，防止compiler报错
                "name":(mod + ""),
                //强制转型，防止compiler报错
                "requires":NULL
            };
        }
        requires = mod["requires"] || [];
        var basicMod = ["button"];
        if (mod["name"].indexOf("/dialog") != -1) {
            basicMod.push("overlay");
        }
        mod["requires"] = requires.concat(basicMod);
    }
    plugin_mods = mis_mods.concat(plugin_mods);
    // ui modules
    // plugins modules
    for (i = 0,len = plugin_mods.length; i < len; i++) {
        mod = plugin_mods[i];
        name = mod["name"];
        mods[name] = {
            "attach": FALSE,
            "charset":"utf-8",
            "requires": mod["requires"],
            "csspath": (mod.useCss ? debugUrl("plugins/" + name + "/plugin.css") : undefined),
            "path": debugUrl("plugins/" + name + "/plugin.js")
        };
    }
    Editor.add(mods);
    /**
     * @constructor
     */
    S.Editor = Editor;
    /**
     * @constructor
     */
    S["Editor"] = Editor;
    //S.log(core_mods);
});
/**
 * common utils for kissy editor
 * @author: <yiminghe@gmail.com>
 */
KISSY.Editor.add("utils", function(KE) {

    var
        TRUE = true,
        FALSE = false,
        NULL = null,
        S = KISSY,
        Node = S.Node,
        DOM = S.DOM,
        UA = S.UA,
        Event = S.Event,
        Utils = {
            /**
             * for debug and production switch
             * @param url {string}
             * @return {string}
             */
            debugUrl:function (url) {
                var debug = S["Config"]["debug"],re;
                if (!debug) re = url.replace(/\.(js|css)/i, "-min.$1");
                else if (debug === "dev") {
                    re = "../src/" + url;
                } else {
                    re = url
                }
                if (re.indexOf("?") != -1) {
                    re += "&";
                } else {
                    re += "?";
                }
                re += "t=" + encodeURIComponent("2010-11-16 12:37:26");
                return  re;
            },
            /**
             * 懒惰一下
             * @param obj {Object} 包含方法的对象
             * @param before {string} 准备方法
             * @param after {string} 真正方法
             */
            lazyRun:function(obj, before, after) {
                var b = obj[before],a = obj[after];
                obj[before] = function() {
                    b.apply(this, arguments);
                    obj[before] = obj[after];
                    return a.apply(this, arguments);
                };
            }
            ,

            /**
             * srcDoc 中的位置在 destDoc 的对应位置
             * @param x {number}
             * @param y {number}
             * @param srcDoc {Document}
             * @param destDoc {Document}
             * @return {{left:number,top:number}} 在最终文档中的位置
             */
            getXY:function(x, y, srcDoc, destDoc) {
                var currentWindow = srcDoc.defaultView || srcDoc.parentWindow;

                //x,y相对于当前iframe文档,防止当前iframe有滚动条
                x -= DOM.scrollLeft(currentWindow);
                y -= DOM.scrollTop(currentWindow);
                if (destDoc) {
                    var refWindow = destDoc.defaultView || destDoc.parentWindow;
                    if (currentWindow != refWindow && currentWindow.frameElement) {
                        //note:when iframe is static ,still some mistake
                        var iframePosition = DOM._4e_getOffset(currentWindow.frameElement, destDoc);
                        x += iframePosition.left;
                        y += iframePosition.top;
                    }
                }
                return {left:x,top:y};
            }
            ,
            /**
             * 执行一系列函数
             * @param var_args {...function()}
             * @return {*} 得到成功的返回
             */
            tryThese : function(var_args) {
                var returnValue;
                for (var i = 0, length = arguments.length; i < length; i++) {
                    var lambda = arguments[i];
                    try {
                        returnValue = lambda();
                        break;
                    }
                    catch (e) {
                    }
                }
                return returnValue;
            },

            /**
             * 是否两个数组完全相同
             * @param arrayA {Array}
             * @param arrayB {Array}
             * @return {boolean}
             */
            arrayCompare: function(arrayA, arrayB) {
                if (!arrayA && !arrayB)
                    return TRUE;

                if (!arrayA || !arrayB || arrayA.length != arrayB.length)
                    return FALSE;

                for (var i = 0; i < arrayA.length; i++) {
                    if (arrayA[ i ] !== arrayB[ i ])
                        return FALSE;
                }

                return TRUE;
            }
            ,

            /**
             * 根据dom路径得到某个节点
             * @param doc {Document}
             * @param address {Array.<number>}
             * @param normalized {boolean}
             * @return {KISSY.Node}
             */
            getByAddress : function(doc, address, normalized) {
                var $ = doc.documentElement;

                for (var i = 0; $ && i < address.length; i++) {
                    var target = address[ i ];

                    if (!normalized) {
                        $ = $.childNodes[ target ];
                        continue;
                    }

                    var currentIndex = -1;

                    for (var j = 0; j < $.childNodes.length; j++) {
                        var candidate = $.childNodes[ j ];

                        if (normalized === TRUE &&
                            candidate.nodeType == 3 &&
                            candidate.previousSibling &&
                            candidate.previousSibling.nodeType == 3) {
                            continue;
                        }

                        currentIndex++;

                        if (currentIndex == target) {
                            $ = candidate;
                            break;
                        }
                    }
                }

                return $ ? new Node($) : NULL;
            }
            ,
            /**
             * @param database {Object.<string,KISSY.Node>}
             */
            clearAllMarkers:function(database) {
                for (var i in database)
                    database[i]._4e_clearMarkers(database, TRUE);
            }
            ,
            /**
             *
             * @param text {string}
             * @return {string}
             */
            htmlEncodeAttr : function(text) {
                return text.replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/, '&gt;');
            }
            ,
            /**
             *
             * @param str {string}
             * @return {string}
             */
            ltrim:function(str) {
                return str.replace(/^\s+/, "");
            }
            ,
            /**
             *
             * @param str {string}
             * @return {string}
             */
            rtrim:function(str) {
                return str.replace(/\s+$/, "");
            }
            ,
            /**
             *
             * @param str {string}
             * @return {string}
             */
            trim:function(str) {
                return this.ltrim(this.rtrim(str));
            }
            ,
            /**
             *
             * @param var_args {...Object}
             * @return {Object}
             */
            mix:function(var_args) {
                var r = {};
                for (var i = 0; i < arguments.length; i++) {
                    var ob = arguments[i];
                    r = S.mix(r, ob);
                }
                return r;
            }
            ,
            isCustomDomain : function() {
                if (!UA.ie)
                    return FALSE;

                var domain = document.domain,
                    hostname = window.location.hostname;

                return domain != hostname &&
                    domain != ( '[' + hostname + ']' );	// IPv6 IP support (#5434)
            },
            /**
             *
             * @param delim {string} 分隔符
             * @param loop {number}
             * @return {string}
             */
            duplicateStr:function(delim, loop) {
                return new Array(loop + 1).join(delim);
            },
            /**
             * Throttles a call to a method based on the time between calls.
             * Based on work by Simon Willison: http://gist.github.com/292562
             * @param fn {function()} The function call to throttle.
             * @param ms {number} The number of milliseconds to throttle the method call. Defaults to 150
             * @return {function()} Returns a wrapped function that calls fn throttled.
             */
            throttle : function(fn, scope, ms) {
                ms = ms || 150;

                if (ms === -1) {
                    return (function() {
                        fn.apply(scope, arguments);
                    });
                }

                var last = (new Date()).getTime();

                return function() {
                    var now = (new Date()).getTime();
                    if (now - last > ms) {
                        last = now;
                        fn.apply(scope, arguments);
                    }
                };
            },
            /**
             *
             * @param fn {function()}
             * @param scope {Object}
             * @param ms {number}
             * @return {function()}
             */
            buffer : function(fn, scope, ms) {
                ms = ms || 0;
                var timer = NULL;
                return (function() {
                    timer && clearTimeout(timer);
                    var args = arguments;
                    timer = setTimeout(function() {
                        return fn.apply(scope, args);
                    }, ms);
                });
            },

            isNumber:function(n) {
                return /^\d+(.\d+)?$/.test(S.trim(n));
            },

            /**
             *
             * @param inputs {Array.<Node>}
             * @param warn {string}
             * @return {boolean} 是否验证成功
             */
            verifyInputs:function(inputs, warn) {
                for (var i = 0; i < inputs.length; i++) {
                    var input = DOM._4e_wrap(inputs[i]),
                        v = S.trim(input.val()),
                        verify = input.attr("data-verify"),
                        warning = input.attr("data-warning");
                    if (verify &&
                        !new RegExp(verify).test(v)) {
                        alert(warning);
                        return FALSE;
                    }
                }
                return TRUE;
            },
            /**
             *
             * @param editor {KISSY.Editor}
             * @param plugin {Object}
             */
            sourceDisable:function(editor, plugin) {
                editor.on("sourcemode", plugin.disable, plugin);
                editor.on("wysiwygmode", plugin.enable, plugin);
            },

            /**
             *
             * @param inp {Node}
             */
            resetInput:function(inp) {
                var placeholder = inp.attr("placeholder");
                if (placeholder && !UA.webkit) {
                    inp.addClass("ke-input-tip");
                    inp.val(placeholder);
                } else if (UA.webkit) {
                    inp.val("");
                }
            },

            valInput:function(inp, val) {
                inp.removeClass("ke-input-tip");
                inp.val(val);
            },

            /**
             *
             * @param inp {Node}
             * @param tip {string}
             */
            placeholder:function(inp, tip) {
                inp.attr("placeholder", tip);
                if (UA.webkit) {
                    return;
                }
                inp.on("blur", function() {
                    if (!S.trim(inp.val())) {
                        inp.addClass("ke-input-tip");
                        inp.val(tip);
                    }
                });
                inp.on("focus", function() {
                    inp.removeClass("ke-input-tip");
                    if (S.trim(inp.val()) == tip) {
                        inp.val("");
                    }
                });
            },

            /**
             *
             * @param node {(Node|KISSY.Node)}
             */
            clean:function(node) {
                node = node[0] || node;
                var cs = S.makeArray(node.childNodes);
                for (var i = 0; i < cs.length; i++) {
                    var c = cs[i];
                    if (c.nodeType == KE.NODE.NODE_TEXT && !S.trim(c.nodeValue)) {
                        node.removeChild(c);
                    }
                }
            },
            /**
             * Convert certain characters (&, <, >, and ') to their HTML character equivalents
             *  for literal display in web pages.
             * @param {string} value The string to encode
             * @return {string} The encoded text
             */
            htmlEncode : function(value) {
                return !value ? value : String(value).replace(/&/g, "&amp;").replace(/>/g, "&gt;").replace(/</g, "&lt;").replace(/"/g, "&quot;");
            },

            /**
             * Convert certain characters (&, <, >, and ') from their HTML character equivalents.
             * @param {string} value The string to decode
             * @return {string} The decoded text
             */
            htmlDecode : function(value) {
                return !value ? value : String(value).replace(/&gt;/g, ">").replace(/&lt;/g, "<").replace(/&quot;/g, '"').replace(/&amp;/g, "&");
            },


            equalsIgnoreCase:function(str1, str2) {
                return str1.toLowerCase() == str2.toLowerCase();
            },

            /**
             *
             * @param params {Object.<string,(function|string|number)>}
             * @return {Object.<string,(string|number)>}
             */
            normParams:function (params) {
                params = S.clone(params);
                for (var p in params) {
                    if (params.hasOwnProperty(p)) {
                        var v = params[p];
                        if (S.isFunction(v)) {
                            params[p] = v();
                        }
                    }
                }
                return params;
            },

            /**
             *
             * @param o {Object} 提交 form 配置
             * @param ps {Object} 动态参数
             * @param url {string} 目的地 url
             */
            doFormUpload : function(o, ps, url) {
                var id = S.guid("form-upload-");
                var frame = document.createElement('iframe');
                frame.id = id;
                frame.name = id;
                frame.className = 'ke-hidden';

                var srcScript = 'document.open();' +
                    // The document domain must be set any time we
                    // call document.open().
                    ( Utils.isCustomDomain() ? ( 'document.domain="' + document.domain + '";' ) : '' ) +
                    'document.close();';
                if (UA.ie) {
                    frame.src = UA.ie ? 'javascript:void(function(){' + encodeURIComponent(srcScript) + '}())' : '';
                }
                S.log("doFormUpload : " + frame.src);
                document.body.appendChild(frame);

                if (UA.ie) {
                    document.frames[id].name = id;
                }

                var form = DOM._4e_unwrap(o.form),
                    buf = {
                        target: form.target,
                        method: form.method,
                        encoding: form.encoding,
                        enctype: form.enctype,
                        action: form.action
                    };
                form.target = id;
                form.method = 'POST';
                form.enctype = form.encoding = 'multipart/form-data';
                if (url) {
                    form.action = url;
                }

                var hiddens, hd;
                if (ps) { // add dynamic params
                    hiddens = [];
                    ps = KE.Utils.normParams(ps);
                    for (var k in ps) {
                        if (ps.hasOwnProperty(k)) {
                            hd = document.createElement('input');
                            hd.type = 'hidden';
                            hd.name = k;
                            hd.value = ps[k];
                            form.appendChild(hd);
                            hiddens.push(hd);
                        }
                    }
                }

                function cb() {
                    var r = {  // bogus response object
                        responseText : '',
                        responseXML : NULL
                    };

                    r.argument = o ? o.argument : NULL;

                    try { //
                        var doc;
                        if (UA.ie) {
                            doc = frame.contentWindow.document;
                        } else {
                            doc = (frame.contentDocument || window.frames[id].document);
                        }

                        if (doc && doc.body) {
                            r.responseText = doc.body.innerHTML;
                        }
                        if (doc && doc.XMLDocument) {
                            r.responseXML = doc.XMLDocument;
                        } else {
                            r.responseXML = doc;
                        }

                    }
                    catch(e) {
                        // ignore
                        //2010-11-15 由于外边设置了document.domain导致读不到数据抛异常
                        S.log(e);
                    }

                    Event.remove(frame, 'load', cb);
                    o.callback && o.callback(r);

                    setTimeout(function() {
                        DOM._4e_remove(frame);
                    }, 100);

                }

                Event.on(frame, 'load', cb);

                form.submit();

                form.target = buf.target;
                form.method = buf.method;
                form.enctype = buf.enctype;
                form.encoding = buf.encoding;
                form.action = buf.action;

                if (hiddens) { // remove dynamic params
                    for (var i = 0, len = hiddens.length; i < len; i++) {
                        DOM._4e_remove(hiddens[i]);
                    }
                }
                return frame;
            },
            /**
             * extern for closure compiler
             */
            extern:function(obj, cfg) {
                for (var i in cfg) {
                    obj[i] = cfg[i];
                }
            },
            map:function(arr, callback) {
                for (var i = 0; i < arr.length; i++) {
                    arr[i] = callback(arr[i]);
                }
                return arr;
            }
        };
    KE.Utils = Utils;
    /**
     * export for closure compiler
     */
    KE["Utils"] = Utils;
    Utils.extern(Utils, {
        "debugUrl": Utils.debugUrl,
        "lazyRun": Utils.lazyRun,
        "getXY": Utils.getXY,
        "tryThese": Utils.tryThese,
        "arrayCompare": Utils.arrayCompare,
        "getByAddress": Utils.getByAddress,
        "clearAllMarkers": Utils.clearAllMarkers,
        "htmlEncodeAttr": Utils.htmlEncodeAttr,
        "ltrim": Utils.ltrim,
        "rtrim": Utils.rtrim,
        "trim": Utils.trim,
        "mix": Utils.mix,
        "isCustomDomain": Utils.isCustomDomain,
        "duplicateStr": Utils.duplicateStr,
        "buffer": Utils.buffer,
        "isNumber": Utils.isNumber,
        "verifyInputs": Utils.verifyInputs,
        "sourceDisable": Utils.sourceDisable,
        "resetInput": Utils.resetInput,
        "placeholder": Utils.placeholder,
        "clean": Utils.clean,
        "htmlEncode": Utils.htmlEncode,
        "htmlDecode": Utils.htmlDecode,
        "equalsIgnoreCase": Utils.equalsIgnoreCase,
        "normParams": Utils.normParams,
        "throttle": Utils.throttle,
        "doFormUpload": Utils.doFormUpload,
        "map": Utils.map
    });
});
/**
 * 多实例的管理，主要是焦点控制，主要是为了
 * 1.firefox 焦点失去 bug，记录当前状态
 * 2.窗口隐藏后能够恢复焦点
 * @author: <yiminghe@gmail.com>
 */
KISSY.Editor.add("focusmanager", function(KE) {
    var S = KISSY,
        DOM = S.DOM,
        Event = S.Event,
        INSTANCES = {},
        //当前焦点所在处
        currentInstance,
        focusManager = {
            /**
             * 刷新全部实例
             */
            refreshAll:function() {
                for (var i in INSTANCES) {
                    var e = INSTANCES[i];
                    e.document.designMode = "off";
                    e.document.designMode = "on";
                }
            },
            /**
             * 得到当前获得焦点的实例
             */
            currentInstance :function() {
                return currentInstance;
            },
            /**
             *
             * @param id {string}
             */
            getInstance : function(id) {
                return INSTANCES[id];
            },
            /**
             *
             * @param editor {KISSY.Editor}
             */
            add : function(editor) {
                var win = DOM._4e_getWin(editor.document);
                Event.on(win, "focus", focus, editor);
                Event.on(win, "blur", blur, editor);
            },
            /**
             *
             * @param editor {KISSY.Editor}
             */
            register : function(editor) {
                INSTANCES[editor._UUID] = editor;
            },
            /**
             *
             * @param editor {KISSY.Editor}
             */
            remove : function(editor) {
                delete INSTANCES[editor._UUID];
                var win = DOM._4e_getWin(editor.document);
                Event.remove(win, "focus", focus, editor);
                Event.remove(win, "blur", blur, editor);
            }
        },
        TRUE = true,
        FALSE = false,
        NULL = null;

    /**
     * @this {KISSY.Editor}
     */
    function focus() {
        var editor = this;
        editor.iframeFocus = TRUE;
        currentInstance = editor;
    }

    /**
     * @this {KISSY.Editor}
     */
    function blur() {
        var editor = this;
        editor.iframeFocus = FALSE;
        currentInstance = NULL;
    }

    KE.focusManager = focusManager;
    KE["focusManager"] = focusManager;
    KE.getInstances = function() {
        return INSTANCES;
    };
    KE["getInstances"] = KE.getInstances;
});
/**
 * definition of editor class for kissy editor
 * @author: <yiminghe@gmail.com>
 */
KISSY.Editor.add("definition", function(KE) {
    var
        TRUE = true,
        FALSE = false,
        NULL = null,
        DOC = document,
        /** @const */S = KISSY,
        /**
         * @const
         */
        UA = S.UA,
        /**
         * @const
         */
        DOM = S.DOM,
        /**
         * @const
         */
        Node = S.Node,
        /**
         * @const
         */
        Event = S.Event,
        /**
         * @const
         */
        DISPLAY = "display",
        /**
         * @const
         */
        WIDTH = "width",
        /**
         * @const
         */
        HEIGHT = "height",
        /**
         * @const
         */
        NONE = "none",
        focusManager = KE.focusManager,
        tryThese = KE.Utils.tryThese,
        /**
         * @const
         */
        HTML5_DTD = '<!doctype html>',
        /**
         * @const
         */
        DTD = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" ' +
            '"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
        /**
         * @const
         */
        ke_textarea_wrap = ".ke-textarea-wrap",
        /**
         * @const
         */
        ke_editor_tools = ".ke-editor-tools",
        /**
         * @const
         */
        ke_editor_status = ".ke-editor-status",
        /**
         * @const
         */
        CSS_FILE = KE.Utils.debugUrl("theme/editor-iframe.css");

    /**
     *
     * @param id {string}
     * @param customStyle {string}
     */
    function prepareIFrameHtml(id, customStyle, customLink) {
        var links = "";
        if (customLink) {
            for (var i = 0; i < customLink.length; i++) {
                links += '<link href="' +
                    customLink[i]
                    + '" rel="stylesheet"/>';
            }
        }
        return HTML5_DTD
            + "<html>"
            + "<head>"
            + "<title>${title}</title>"
            + "<link href='"
            + KE["Config"]["base"]
            + CSS_FILE
            + "'" +
            " rel='stylesheet'/>"
            + "<style>"
            + (customStyle || "")
            + "</style>"
            + links
            + "</head>"
            + "<body class='ke-editor'>"
            //firefox 必须里面有东西，否则编辑前不能删除!
            + "&nbsp;"
            //使用 setData 加强安全性
            // + (textarea.value || "")
            + (id ?
            // The script that launches the bootstrap logic on 'domReady', so the document
            // is fully editable even before the editing iframe is fully loaded (#4455).
            //确保iframe确实载入成功,过早的话 document.domain 会出现无法访问
            '<script id="ke_actscript" type="text/javascript">' +
                ( KE.Utils.isCustomDomain() ? ( 'document.domain="' + DOC.domain + '";' ) : '' ) +
                'window.parent.KISSY.Editor._initIFrame("' + id + '");' +
                '</script>' : ''
            )
            + "</body>"
            + "</html>";

    }

    var INSTANCE_ID = 1,

        srcScript = 'document.open();' +
            // The document domain must be set any time we
            // call document.open().
            ( KE.Utils.isCustomDomain() ? ( 'document.domain="' + DOC.domain + '";' ) : '' ) +
            'document.close();',

        editorHtml = "<div " +
            " class='ke-editor-wrap' " +
            " > " +
            "<div class='" + ke_editor_tools.substring(1) + "'></div>" +
            "<div class='" + ke_textarea_wrap.substring(1) + "'><" + "iframe " +
            ' style="' + WIDTH + ':100%;' + HEIGHT + ':100%;border:none;" ' +
            ' ' + WIDTH + '="100%" ' +
            ' ' + HEIGHT + '="100%" ' +
            ' frameborder="0" ' +
            ' title="' + "kissy-editor" + '" ' +
            // With IE, the custom domain has to be taken care at first,
            // for other browsers, the 'src' attribute should be left empty to
            // trigger iframe's 'load' event.
            ' src="' + ( UA.ie ? 'javascript:void(function(){' + encodeURIComponent(srcScript) + '}())' : '' ) + '" ' +
            ' tabIndex="' + ( UA.webkit ? -1 : "$(tabIndex)" ) + '" ' +
            ' allowTransparency="true" ' +
            '></iframe></div>' +
            "<div class='" + ke_editor_status.substring(1) + "'></div>" +
            "</div>";

    //所有link,flash,music的悬浮小提示
    //KE.Tips = {};

    KE.SOURCE_MODE = 0;
    KE.WYSIWYG_MODE = 1;
    KE["SOURCE_MODE"] = KE.SOURCE_MODE;
    KE["WYSIWYG_MODE"] = KE.WYSIWYG_MODE;

    S.augment(KE,
        /**
         * @lends {KISSY.Editor.prototype}
         */
    {
        /**
         * @this {KISSY.Editor}
         * @param textarea {KISSY.Node}
         */
        init:function(textarea) {
            if (UA.ie)DOM.addClass(DOC.body, "ie" + UA.ie);
            else if (UA.gecko) DOM.addClass(DOC.body, "gecko");
            else if (UA.webkit) DOM.addClass(DOC.body, "webkit");
            var self = this,
                editorWrap = new Node(editorHtml.replace(/\$\(tabIndex\)/, textarea.attr("tabIndex")));
            //!!编辑器内焦点不失去,firefox?
            editorWrap.on("mousedown", function(ev) {
                if (UA.webkit) {
                    //chrome select 弹不出来
                    var n = DOM._4e_name(ev.target);
                    if (n == "select" || n == "option")return TRUE;
                }
                ev.halt();
            });

            //由于上面的 mousedown 阻止，这里使得 textarea 上的事件不被阻止，可以被编辑 - firefox
            textarea.on("mousedown", function(ev) {
                ev.stopPropagation();
            });

            self.editorWrap = editorWrap;
            self._UUID = INSTANCE_ID++;
            //实例集中管理
            focusManager.register(self);
            self.wrap = editorWrap.one(ke_textarea_wrap);
            self["wrap"] = self.wrap;
            self.iframe = self.wrap.one("iframe");
            self["iframe"] = self.iframe;
            self.toolBarDiv = editorWrap.one(ke_editor_tools);
            self["toolBarDiv"] = self.toolBarDiv;
            self.textarea = textarea;
            self["textarea"] = self.textarea;
            self.statusDiv = editorWrap.one(ke_editor_status);
            self["statusDiv"] = self.statusDiv;
            //ie 点击按钮不丢失焦点
            self.toolBarDiv._4e_unselectable();
            //可以直接调用插件功能
            self._commands = {};
            self._dialogs = {};
            var tw = textarea._4e_style(WIDTH),th = textarea._4e_style(HEIGHT);
            if (tw) {
                editorWrap.css(WIDTH, tw);
                textarea.css(WIDTH, "100%");
            }
            self.textarea.css(DISPLAY, NONE);
            editorWrap.insertAfter(textarea);
            self.wrap[0].appendChild(textarea[0]);

            if (th) {
                self.wrap.css(HEIGHT, th);
                textarea.css(HEIGHT, "100%");
            }

            var iframe = self.iframe;

            self.on("dataReady", function() {
                self._ready = TRUE;
                KE.fire("instanceCreated", {editor:self});
            });
            // With FF, it's better to load the data on iframe.load. (#3894,#4058)
            if (UA.gecko) {
                iframe.on('load', self._setUpIFrame, self);
            } else {
                //webkit(chrome) load等不来！
                self._setUpIFrame();
            }
            if (self.cfg.attachForm && textarea[0].form)
                self._attachForm();
        },
        /**
         *  @this {KISSY.Editor}
         */
        _attachForm:function() {
            var self = this,
                textarea = self.textarea,
                form = new Node(textarea[0].form);
            form.on("submit", self.sync, self);
        },
        /**
         * @this {KISSY.Editor}
         * @param name {string}
         * @param callback {function(Object)}
         * @param cfg {Object}
         */
        useDialog:function(name, callback, cfg) {
            var self = this,
                Overlay = KE.SimpleOverlay;
            cfg = cfg || {};
            Overlay.loading();
            self.use(name, function() {
                var dialog = self.getDialog(name);
                callback(dialog);
                Overlay.unloading();
            });
        },
        /**
         *@this {KISSY.Editor}
         * @param name {string}
         * @param obj {Object}
         */
        addDialog:function(name, obj) {
            this._dialogs[name] = obj;
        },
        /**
         *@this {KISSY.Editor}
         * @param name {string}
         */
        getDialog:function(name) {
            return this._dialogs[name];
        },
        /**
         *@this {KISSY.Editor}
         * @param func {function()}
         */
        addPlugin:function(func) {
            this.ready(func);
        },
        /**
         *@this {KISSY.Editor}
         * @param name {string}
         * @param obj {Object}
         */
        addCommand:function(name, obj) {
            this._commands[name] = obj;
        },
        /**
         *@this {KISSY.Editor}
         * @param name {string}
         */
        hasCommand:function(name) {
            return this._commands[name];
        },
        /**
         *@this {KISSY.Editor}
         * @param name {string}
         */
        execCommand:function(name) {
            var self = this,
                cmd = self._commands[name],
                args = S.makeArray(arguments);
            args.shift();
            args.unshift(self);
            return cmd.exec.apply(cmd, args);
        },
        /**
         * @this {KISSY.Editor}
         * @return {number}
         */
        getMode:function() {
            return this.textarea.css("display") == "none" ?
                KE.WYSIWYG_MODE :
                KE.SOURCE_MODE;
        },
        /**
         *@this {KISSY.Editor}
         * @param format {boolean}
         */
        getData:function(format) {
            var self = this,
                html;
            if (self.getMode() == KE.WYSIWYG_MODE) {
                html = self.document.body.innerHTML;
            } else {
                //代码模式下不需过滤
                html = self.textarea.val();
            }
            //如果不需要要格式化，例如提交数据给服务器
            if (self["htmlDataProcessor"]) {
                if (format) {
                    html = self["htmlDataProcessor"]["toHtml"](html, "p");
                } else {
                    html = self["htmlDataProcessor"]["toServer"](html, "p");
                }
            }
            html = S.trim(html);
            /*
             如果内容为空，对 parser 自动加的空行滤掉
             */
            if (/^<p>((&nbsp;)|\s)*<\/p>$/.test(html)) html = "";
            return html;
        } ,

        /**
         *@this {KISSY.Editor}
         * @param data {string}
         */
        setData:function(data) {
            var self = this,
                afterData = data;
            if (self["htmlDataProcessor"])
                afterData = self["htmlDataProcessor"]["toDataFormat"](data, "p");
            self.document.body.innerHTML = afterData;
            if (self.getMode() == KE.WYSIWYG_MODE) {
            } else {
                //代码模式下不需过滤
                self.textarea.val(data);
            }
        },
        /**
         * @this {KISSY.Editor}
         */
        sync:function() {
            this.textarea.val(this.getData());
        },

        /**
         * ie6 其他节点z-index干扰，编辑器z-index必须比baseZIndex大
         * @this {KISSY.Editor}
         * @param v {number}
         */
        baseZIndex:function(v) {
            v = v || 0;
            var zIndex = this.cfg.baseZIndex || 0;
            return v + zIndex;
        },

        /**
         * 撤销重做时，不需要格式化代码，直接取自身
         * @this {KISSY.Editor}
         */

        _getRawData:function() {
            return this.document.body.innerHTML;
        },


        /**
         * 撤销重做时，不需要格式化代码，直接取自身
         * @this {KISSY.Editor}
         * @param data {string}
         */
        _setRawData:function(data) {
            this.document.body.innerHTML = data;
        },
        /**
         * @this {KISSY.Editor}
         */
        _prepareIFrameHtml:function(id) {
            var cfg = this.cfg;
            return prepareIFrameHtml(id, cfg.customStyle, cfg.customLink);
        },
        /**
         * @this {KISSY.Editor}
         */
        getSelection:function() {
            return KE.Selection.getSelection(this.document);
        },
        /**
         * @this {KISSY.Editor}
         */
        focus:function() {
            var self = this,
                doc = self.document,
                win = DOM._4e_getWin(doc);
            UA.webkit && win && win.parent && win.parent.focus();
            //yiminghe note:webkit need win.focus
            win && win.focus();
            //ie and firefox need body focus
            doc && doc.body.focus();
            self.notifySelectionChange();
        } ,
        /**
         * @this {KISSY.Editor}
         */
        blur:function() {
            var self = this,
                win = DOM._4e_getWin(self.document);
            win.blur();
            self.document && self.document.body.blur();
        },

        /**
         *@this {KISSY.Editor}
         * @param cssText {string}
         */
        addCustomStyle:function(cssText) {
            var self = this,
                cfg = self.cfg,
                doc = self.document;
            cfg.customStyle = cfg.customStyle || "";
            cfg.customStyle += "\n" + cssText;

            var elem = doc.createElement("style");
            // 先添加到 DOM 树中，再给 cssText 赋值，否则 css hack 会失效
            doc.getElementsByTagName("head")[0].appendChild(elem);
            if (elem.styleSheet) { // IE
                elem.styleSheet.cssText = cssText;
            } else { // W3C
                elem.appendChild(doc.createTextNode(cssText));
            }
        },
        addCustomLink:function(link) {
            var self = this,
                cfg = self.cfg,
                doc = self.document;
            cfg.customLink = cfg.customLink || [];
            cfg.customLink.push(link);
            var elem = doc.createElement("link");
            elem.rel = "stylesheet";
            doc.getElementsByTagName("head")[0].appendChild(elem);
            elem.href = link;
        },
        removeCustomLink:function(link) {
            var self = this,
                cfg = self.cfg,
                doc = self.document;
            var links = S.makeArray(doc.getElementsByTagName("link"));
            for (var i = 0; i < links.length; i++) {
                if (links[i].href == link) {
                    DOM._4e_remove(links[i]);
                }
            }
            cfg.customLink = cfg.customLink || [];
            var cls = cfg.customLink;
            var ind = S.indexOf(link, cls);
            if (ind != -1) {
                cls.splice(ind, 1);
            }
        },
        /**
         * @this {KISSY.Editor}
         */
        _setUpIFrame:function() {
            var self = this,
                iframe = self.iframe,
                KES = KE.SELECTION,
                textarea = self.textarea[0],
                cfg = self.cfg,

                data = self._prepareIFrameHtml(self._UUID),
                win = iframe[0].contentWindow,doc;

            try {
                // In IE, with custom document.domain, it may happen that
                // the iframe is not yet available, resulting in "Access
                // Denied" for the following property access.
                //ie 设置domain 有问题：yui也有
                //http://yuilibrary.com/projects/yui2/ticket/2052000
                //http://waelchatila.com/2007/10/31/1193851500000.html
                //http://nagoon97.wordpress.com/tag/designmode/
                doc = win.document;
            } catch(e) {
                // Trick to solve this issue, forcing the iframe to get ready
                // by simply setting its "src" property.
                //noinspection SillyAssignmentJS
                iframe[0].src = iframe[0].src;
                // In IE6 though, the above is not enough, so we must pause the
                // execution for a while, giving it time to think.
                if (UA.ie < 7) {
                    setTimeout(run, 10);
                    return;
                }
            }
            run();
            function run() {
                doc = win.document;
                self.document = doc;
                iframe.detach();
                // Don't leave any history log in IE. (#5657)
                doc.open("text/html", "replace");
                doc.write(data);
                doc.close();
            }
        },
        /**
         *@this {KISSY.Editor}
         * @param func {function()}
         */
        ready:function(func) {
            var self = this;
            if (self._ready)func();
            else {
                self.on("dataReady", func);
            }
        },
        /**
         * @this {KISSY.Editor}
         */
        _monitor:function() {
            var self = this;
            if (self._monitorId) {
                clearTimeout(self._monitorId);
            }
            //console.log("selectionChange");
            self._monitorId = setTimeout(function() {
                var selection = self.getSelection();
                if (selection && !selection.isInvalid) {
                    var startElement = selection.getStartElement(),
                        currentPath = new KE.ElementPath(startElement);
                    if (!self.previousPath || !self.previousPath.compare(currentPath)) {
                        self.previousPath = currentPath;
                        //console.log("selectionChange");
                        self.fire("selectionChange", { selection : selection, path : currentPath, element : startElement });
                    }
                }
            }, 100);
        }
        ,
        /**
         * 强制通知插件更新状态，防止插件修改编辑器内容，自己反而得不到通知
         * @this {KISSY.Editor}
         */
        notifySelectionChange:function() {
            var self = this;
            self.previousPath = NULL;
            self._monitor();
        },

        /**
         *@this {KISSY.Editor}
         * @param element {KISSY.Node}
         * @param init {function()}
         */
        insertElement:function(element, init) {
            var self = this;
            self.focus();

            var elementName = element._4e_name(),
                xhtml_dtd = KE.XHTML_DTD,
                KER = KE.RANGE,
                KEN = KE.NODE,
                isBlock = xhtml_dtd.$block[ elementName ],
                selection = self.getSelection(),
                ranges = selection && selection.getRanges(),
                range,
                clone,
                lastElement,
                current, dtd;
            //give sometime to breath
            if (!ranges
                ||
                ranges.length == 0) {
                var args = arguments,fn = args.callee;
                setTimeout(function() {
                    fn.apply(self, args);
                }, 30);
                return;
            }

            self.fire("save");
            for (var i = ranges.length - 1; i >= 0; i--) {
                range = ranges[ i ];
                // Remove the original contents.
                range.deleteContents();
                clone = !i && element || element._4e_clone(TRUE);
                init && init(clone);
                // If we're inserting a block at dtd-violated position, split
                // the parent blocks until we reach blockLimit.
                if (isBlock) {
                    while (( current = range.getCommonAncestor(FALSE, TRUE) )
                        && ( dtd = xhtml_dtd[ current._4e_name() ] )
                        && !( dtd && dtd [ elementName ] )) {
                        // Split up inline elements.
                        if (current._4e_name() in xhtml_dtd["span"])
                            range.splitElement(current);
                        // If we're in an empty block which indicate a new paragraph,
                        // simply replace it with the inserting block.(#3664)
                        else if (range.checkStartOfBlock()
                            && range.checkEndOfBlock()) {
                            range.setStartBefore(current);
                            range.collapse(TRUE);
                            current._4e_remove();
                        }
                        else
                            range.splitBlock();
                    }
                }

                // Insert the new node.
                range.insertNode(clone);
                // Save the last element reference so we can make the
                // selection later.
                if (!lastElement)
                    lastElement = clone;
            }
            if (!lastElement) return;

            var next = lastElement._4e_nextSourceNode(TRUE),p,
                doc = self.document;
            dtd = KE.XHTML_DTD;

            //行内元素不用加换行
            if (!dtd.$inline[clone._4e_name()]) {
                //末尾时 ie 不会自动产生br，手动产生
                if (!next) {
                    p = new Node("<p>&nbsp;</p>", NULL, doc);
                    p.insertAfter(lastElement);
                    next = p;
                }
                //firefox,replace br with p，和编辑器整体换行保持一致
                else if (next._4e_name() == "br"
                    &&
                    //必须符合嵌套规则
                    dtd[next.parent()._4e_name()]["p"]
                    ) {
                    p = new Node("<p>&nbsp;</p>", NULL, doc);
                    next[0].parentNode.replaceChild(p[0], next[0]);
                    next = p;
                }
            } else {
                //qc #3803 ，插入行内后给个位置放置光标
                next = new Node(doc.createTextNode(" "));
                next.insertAfter(lastElement);
            }
            range.moveToPosition(lastElement, KER.POSITION_AFTER_END);
            if (next && next[0].nodeType == KEN.NODE_ELEMENT)
                range.moveToElementEditablePosition(next);

            selection.selectRanges([ range ]);
            self.focus();
            //http://code.google.com/p/kissy/issues/detail?can=1&start=100&id=121
            clone && clone._4e_scrollIntoView();
            setTimeout(function() {
                self.fire("save");
            }, 10);
            return clone;
        },

        /**
         *@this {KISSY.Editor}
         * @param data {string}
         */
        insertHtml:function(data) {
            var self = this;
            if (self["htmlDataProcessor"])
                data = self["htmlDataProcessor"]["toDataFormat"](data);//, "p");
            /**
             * webkit insert html 有问题！会把标签去掉，算了直接用insertElement
             */
            if (UA.webkit) {
                var nodes = DOM.create(data, NULL, this.document);
                if (nodes.nodeType == 11) nodes = S.makeArray(nodes.childNodes);
                else nodes = [nodes];
                for (var i = 0; i < nodes.length; i++)
                    self.insertElement(new Node(nodes[i]));
                return;
            }
            self.focus();

            var selection = self.getSelection(),
                ranges = selection && selection.getRanges();

            //give sometime to breath
            if (!ranges
                ||
                ranges.length == 0) {
                var args = arguments,fn = args.callee;
                setTimeout(function() {
                    fn.apply(self, args);
                }, 30);
                return;
            }

            self.fire("save");
            if (UA.ie) {
                var $sel = selection.getNative();
                if ($sel.type == 'Control')
                    $sel.clear();
                $sel.createRange().pasteHTML(data);
            } else {
                self.document.execCommand('inserthtml', FALSE, data);
            }

            self.focus();
            setTimeout(function() {
                self.fire("save");
            }, 10);
        }
    });
    /**
     * 初始化iframe内容以及浏览器间兼容性处理，
     * 必须等待iframe内的脚本向父窗口通知
     * @this {KISSY.Editor}
     * @param id {string}
     */
    KE["_initIFrame"] = function(id) {

        var self = focusManager.getInstance(id),
            iframe = self.iframe,
            textarea = self.textarea[0],
            win = iframe[0].contentWindow,
            doc = self.document,
            cfg = self.cfg,
            // Remove bootstrap script from the DOM.
            script = doc.getElementById("ke_actscript");
        DOM._4e_remove(script);

        var body = doc.body;

        /**
         * from kissy editor 1.0
         *
         * // 注1：在 tinymce 里，designMode = "on" 放在 try catch 里。
         //     原因是在 firefox 下，当iframe 在 display: none 的容器里，会导致错误。
         //     但经过我测试，firefox 3+ 以上已无此现象。
         // 注2： ie 用 contentEditable = true.
         //     原因是在 ie 下，IE needs to use contentEditable or
         // it will display non secure items for HTTPS
         // Ref:
         //   - Differences between designMode and contentEditable
         //     http://74.125.153.132/search?q=cache:5LveNs1yHyMJ:nagoon97.wordpress.com/2008/04/20/differences-between-designmode-and-contenteditable/+ie+contentEditable+designMode+different&cd=6&hl=en&ct=clnk
         */

        //这里对主流浏览器全部使用 contenteditable
        //那么不同于 kissy editor 1.0
        //在body范围外右键，不会出现 复制，粘贴等菜单
        //因为这时右键作用在document而不是body
        //1.0 document.designMode='on' 是编辑模式
        //2.0 body.contentEditable=true body外不是编辑模式
        if (UA.ie) {
            // Don't display the focus border.
            body.hideFocus = TRUE;

            // Disable and re-enable the body to avoid IE from
            // taking the editing focus at startup. (#141 / #523)
            body.disabled = TRUE;
            body.contentEditable = TRUE;
            body.removeAttribute('disabled');
        } else {
            // Avoid opening design mode in a frame window thread,
            // which will cause host page scrolling.(#4397)
            setTimeout(function() {
                // Prefer 'contentEditable' instead of 'designMode'. (#3593)
                if (UA.gecko || UA.opera) {
                    body.contentEditable = TRUE;
                }
                else if (UA.webkit)
                    body.parentNode.contentEditable = TRUE;
                else
                    doc.designMode = 'on';
            }, 0);
        }

        // Gecko need a key event to 'wake up' the editing
        // ability when document is empty.(#3864)
        //activateEditing 删掉，初始引起屏幕滚动了


        // Webkit: avoid from editing form control elements content.
        if (UA.webkit) {
            Event.on(doc, "click", function(ev) {
                var control = new Node(ev.target);
                if (S.inArray(control._4e_name(), ['input', 'select'])) {
                    ev.preventDefault();
                }
            });
            // Prevent from editig textfield/textarea value.
            Event.on(doc, "mouseup", function(ev) {
                var control = new Node(ev.target);
                if (S.inArray(control._4e_name(), ['input', 'textarea'])) {
                    ev.preventDefault();
                }
            });
        }

        function blinkCursor(retry) {
            tryThese(
                function() {
                    doc.designMode = 'on';
                    //异步引起时序问题，尽可能小间隔
                    setTimeout(function () {
                        doc.designMode = 'off';
                        //console.log("path1");
                        body.focus();
                        // Try it again once..
                        if (!arguments.callee.retry) {
                            arguments.callee.retry = TRUE;
                            //arguments.callee();
                        }
                    }, 50);
                },
                function() {
                    // The above call is known to fail when parent DOM
                    // tree layout changes may break design mode. (#5782)
                    // Refresh the 'contentEditable' is a cue to this.
                    doc.designMode = 'off';

                    DOM.attr(body, 'contentEditable', FALSE);
                    DOM.attr(body, 'contentEditable', TRUE);
                    // Try it again once..
                    !retry && blinkCursor(1);
                    //console.log("path2");
                });
        }

        // Create an invisible element to grab focus.
        if (UA.gecko || UA.ie || UA.opera) {
            var focusGrabber;
            focusGrabber = new Node(DOM.insertAfter(new Node(
                // Use 'span' instead of anything else to fly under the screen-reader radar. (#5049)
                '<span ' +
                    'tabindex="-1" ' +
                    'style="position:absolute; left:-10000"' +
                    ' role="presentation"' +
                    '></span>')[0], textarea));
            focusGrabber.on('focus', function() {
                self.focus();
            });
            self.activateGecko = function() {
                if (UA.gecko && self.iframeFocus)
                    focusGrabber[0].focus();
            };
            self.on('destroy', function() {
            });
        }

        // IE standard compliant in editing frame doesn't focus the editor when
        // clicking outside actual content, manually apply the focus. (#1659)
        if (UA.ie
            && doc.compatMode == 'CSS1Compat'
            || UA.gecko
            || UA.opera) {
            var htmlElement = new Node(doc.documentElement);
            htmlElement.on('mousedown', function(evt) {
                // Setting focus directly on editor doesn't work, we
                // have to use here a temporary element to 'redirect'
                // the focus.
                //firefox 不能直接设置，需要先失去焦点
                //return;
                //左键激活
                if (evt.target == htmlElement[0]) {
                    //S.log("click");
                    //self.focus();
                    //return;
                    if (UA.gecko)
                        blinkCursor(FALSE);
                    //setTimeout(function() {
                    //这种：html mousedown -> body beforedeactivate
                    //    self.focus();
                    //}, 30);

                    //这种：body beforedeactivate -> html mousedown
                    focusGrabber[0].focus();
                }
            });
        }


        Event.on(win, 'focus', function() {
            //console.log(" i am  focus inner");
            /**
             * yiminghe特别注意：firefox光标丢失bug
             * blink后光标出现在最后，这就需要实现保存range
             * focus后再恢复range
             */
            if (UA.gecko)
                blinkCursor(FALSE);
            else if (UA.opera)
                body.focus();

            // focus 后强制刷新自己状态
            self.notifySelectionChange();
        });


        if (UA.gecko) {
            /**
             * firefox 焦点丢失后，再点编辑器区域焦点会移不过来，要点两下
             */
            Event.on(self.document, "mousedown", function() {
                if (!self.iframeFocus) {
                    //console.log("i am fixed");
                    blinkCursor(FALSE);
                }
            });
        }

        if (UA.ie) {
            //DOM.addClass(doc.documentElement, doc.compatMode);
            // Override keystrokes which should have deletion behavior
            //  on control types in IE . (#4047)
            /**
             * 选择img，出现缩放框后不能直接删除
             */
            Event.on(doc, 'keydown', function(evt) {
                var keyCode = evt.keyCode;
                // Backspace OR Delete.
                if (keyCode in { 8 : 1, 46 : 1 }) {
                    //debugger
                    var sel = self.getSelection(),
                        control = sel.getSelectedElement();
                    if (control) {
                        // Make undo snapshot.
                        self.fire('save');
                        // Delete any element that 'hasLayout' (e.g. hr,table) in IE8 will
                        // break up the selection, safely manage it here. (#4795)
                        var bookmark = sel.getRanges()[ 0 ].createBookmark();
                        // Remove the control manually.
                        control._4e_remove();
                        sel.selectBookmarks([ bookmark ]);
                        self.fire('save');
                        evt.preventDefault();
                    }
                }
            });

            // PageUp/PageDown scrolling is broken in document
            // with standard doctype, manually fix it. (#4736)
            //ie8 主窗口滚动？？
            if (doc.compatMode == 'CSS1Compat') {
                var pageUpDownKeys = { 33 : 1, 34 : 1 };
                Event.on(doc, 'keydown', function(evt) {
                    if (evt.keyCode in pageUpDownKeys) {
                        setTimeout(function () {
                            self.getSelection().scrollIntoView();
                        }, 0);
                    }
                });
            }
        }

        // Adds the document body as a context menu target.

        setTimeout(function() {
            /*
             * IE BUG: IE might have rendered the iframe with invisible contents.
             * (#3623). Push some inconsequential CSS style changes to force IE to
             * refresh it.
             *
             * Also, for some unknown reasons, short timeouts (e.g. 100ms) do not
             * fix the problem. :(
             */
            if (UA.ie) {
                setTimeout(function() {
                    if (doc) {
                        body.runtimeStyle.marginBottom = '0px';
                        body.runtimeStyle.marginBottom = '';
                    }
                }, 1000);
            }
        }, 0);


        setTimeout(function() {
            self.fire("dataReady");
            /*
             some break for firefox ，不能立即设置
             */
            var disableObjectResizing = cfg.disableObjectResizing,
                disableInlineTableEditing = cfg.disableInlineTableEditing;
            if (disableObjectResizing || disableInlineTableEditing) {
                // IE, Opera and Safari may not support it and throw errors.
                try {
                    doc.execCommand('enableObjectResizing', FALSE, !disableObjectResizing);
                    doc.execCommand('enableInlineTableEditing', FALSE, !disableInlineTableEditing);
                }
                catch(e) {
                    //只能ie能用？，目前只有firefox,ie支持图片缩放
                    // For browsers which don't support the above methods,
                    // we can use the the resize event or resizestart for IE (#4208)
                    Event.on(body, UA.ie ? 'resizestart' : 'resize', function(evt) {
                        if (
                            disableObjectResizing ||
                                (
                                    DOM._4e_name(evt.target) === 'table'
                                        &&
                                        disableInlineTableEditing )
                            )
                            evt.preventDefault();
                    });
                }
            }
        }, 10);


        // Gecko/Webkit need some help when selecting control type elements. (#3448)
        //if (!( UA.ie || UA.opera)) {
        if (UA.webkit) {
            Event.on(doc, "mousedown", function(ev) {
                var control = new Node(ev.target);
                if (S.inArray(control._4e_name(), ['img', 'hr', 'input', 'textarea', 'select'])) {
                    self.getSelection().selectElement(control);
                }
            });
        }


        if (UA.gecko) {
            Event.on(doc, "dragstart", function(ev) {
                var control = new Node(ev.target);
                if (control._4e_name() === 'img' &&
                    /ke_/.test(control[0].className)
                    ) {
                    //firefox禁止拖放
                    ev.preventDefault();
                }
            });
        }

        //注意：必须放在这个位置，等iframe加载好再开始运行
        //加入焦点管理，和其他实例联系起来
        focusManager.add(self);
    };

    // Fixing Firefox 'Back-Forward Cache' break design mode. (#4514)
    //不知道为什么
    /*
     if (UA.gecko) {
     ( function () {
     var body = document.body;
     if (!body)
     window.addEventListener('load', arguments.callee, false);
     else {
     var currentHandler = body.getAttribute('onpageshow');
     body.setAttribute('onpageshow', ( currentHandler ? currentHandler + ';' : '') +
     'event.persisted && KISSY.Editor.focusManager.refreshAll();');
     }
     } )();
     }
     */


    var KEP = KE.prototype;
    KE.Utils.extern(KEP, {
        "setData":KEP.setData,
        "getData":KEP.getData,
        "insertElement":KEP.insertElement,
        "insertHtml":KEP.insertHtml,
        "ready":KEP.ready,
        "addCustomStyle":KEP.addCustomStyle,
        "addCommand":KEP.addCommand,
        "hasCommand":KEP.hasCommand,
        "execCommand":KEP.execCommand,
        "addPlugin":KEP.addPlugin,


        "useDialog":KEP.useDialog,
        "addDialog":KEP.addDialog,
        "getDialog":KEP.getDialog,
        "getMode":KEP.getMode,
        "sync":KEP.sync,
        "baseZIndex":KEP.baseZIndex,
        "getSelection":KEP.getSelection,
        "focus":KEP.focus,
        "blur":KEP.blur,
        "notifySelectionChange":KEP.notifySelectionChange
    });

});/**
 * 集中管理各个z-index
 * @author:yiminghe@gmail.com
 */
KISSY.Editor.add("zindex", function() {
    var S = KISSY,KE = S.Editor;

    if (KE.zIndexManager) return;

    /**
     * z-index manager
     *@enum {number}
     */
    KE.zIndexManager = {
        BUBBLE_VIEW:(1100),
        POPUP_MENU:(1200),
        //拖动垫片要最最高
        DD_PG: (99999),
        MAXIMIZE:(900),
        OVERLAY:(9999),
        LOADING:(11000),
        LOADING_CANCEL:12000,
        SELECT:(1200)
    };


    /**
     * 获得全局最大值
     */
    KE.baseZIndex = function(z) {
        var r = z,instances = KE.getInstances();
        for (var i in instances) {
            if (!instances.hasOwnProperty(i)) return;
            var instance = instances[i];
            r = Math.max(r, instance.baseZIndex(z));
        }
        return r;
    };

    KE["baseZIndex"] = KE.baseZIndex;
    KE["zIndexManager"] = KE.zIndexManager;
});/**
 * modified from ckeditor ,xhtml1.1 transitional dtd translation
 * @author: <yiminghe@gmail.com>
 */
/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
KISSY.Editor.add("dtd", function(KE) {
    /**
     * Holds and object representation of the HTML DTD to be used by the editor in
     * its internal operations.
     *
     * Each element in the DTD is represented by a
     * property in this object. Each property contains the list of elements that
     * can be contained by the element. Text is represented by the "#" property.
     *
     * Several special grouping properties are also available. Their names start
     * with the "$" character.
     * @namespace
     * @example
     * // Check if "div" can be contained in a "p" element.
     * alert( !!dtd[ 'p' ][ 'div' ] );  "false"
     * @example
     * // Check if "p" can be contained in a "div" element.
     * alert( !!dtd[ 'div' ][ 'p' ] );  "true"
     * @example
     * // Check if "p" is a block element.
     * alert( !!dtd.$block[ 'p' ] );  "true"
     */
    KE.XHTML_DTD = (function() {
        /**
         *
         * @param {...Object} r
         */
        function X(r) {
            var i = arguments.length - 1;
            while (i > 0) {
                KISSY.mix(r, arguments[i--]);
            }
            return r;
        }

        var A = {"isindex":1,"fieldset":1},
            B = {"input":1,"button":1,"select":1,"textarea":1,"label":1},
            C = X({"a":1}, B),
            D = X({"iframe":1}, C),
            E = {"hr":1,"ul":1,"menu":1,"div":1,
                "blockquote":1,"noscript":1,"table":1,
                "center":1,"address":1,"dir":1,"pre":1,"h5":1,
                "dl":1,"h4":1,"noframes":1,"h6":1,
                "ol":1,"h1":1,"h3":1,"h2":1},
            F = {"ins":1,"del":1,"script":1,"style":1},
            G = X({"b":1,"acronym":1,"bdo":1,'var':1,'#':1,
                "abbr":1,"code":1,
                "br":1,"i":1,"cite":1,
                "kbd":1,
                "u":1,
                "strike":1,
                "s":1,
                "tt":1,
                "strong":1,
                "q":1,
                "samp":1,
                "em":1,
                "dfn":1,
                "span":1}, F),
            H = X({"sub":1,
                "img":1,
                "object":1,
                "sup":1,
                "basefont":1,
                "map":1,
                "applet":1,
                "font":1,
                "big":1,
                "small":1
            }, G),
            I = X({"p":1}, H),
            J = X({"iframe":1}, H, B),
            K = {"img":1,"noscript":1,"br":1,"kbd":1,"center":1,"button":1,
                "basefont":1,"h5":1,"h4":1,"samp":1,"h6":1,"ol":1,
                "h1":1,"h3":1,"h2":1,
                "form":1,
                "font":1,
                '#':1,
                "select":1,
                "menu":1,
                "ins":1,
                "abbr":1,
                "label":1,
                "code":1,
                "table":1,
                "script":1,"cite":1,"input":1,"iframe":1,"strong":1,"textarea":1,"noframes":1,"big":1,"small":1,"span":1,"hr":1,"sub":1,"bdo":1,
                'var':1,"div":1,"object":1,"sup":1,"strike":1,"dir":1,"map":1,"dl":1,"applet":1,"del":1,"isindex":1,"fieldset":1,"ul":1,"b":1,"acronym":1,"a":1,"blockquote":1,"i":1,"u":1,"s":1,"tt":1,"address":1,"q":1,"pre":1,"p":1,"em":1,"dfn":1},

            L = X({"a":1}, J),
            M = {"tr":1},
            N = {'#':1},
            O = X({"param":1}, K),
            P = X({"form":1}, A, D, E, I),
            Q = {"li":1},
            R = {"style":1,"script":1},
            S = {"base":1,"link":1,"meta":1,"title":1},
            T = X(S, R),
            U = {"head":1,"body":1},
            V = {"html":1};

        var block = {"address":1,"blockquote":1,"center":1,"dir":1,"div":1,"dl":1,"fieldset":1,"form":1,"h1":1,"h2":1,"h3":1,"h4":1,"h5":1,"h6":1,"hr":1,"isindex":1,"menu":1,"noframes":1,"ol":1,"p":1,"pre":1,"table":1,"ul":1};

        return  {

            // The "$" items have been added manually.

            // List of elements living outside body.
            $nonBodyContent: X(V, U, S),

            /**
             * List of block elements, like "p" or "div".
             * @type {Object}
             * @example
             */
            $block : block,

            /**
             * List of block limit elements.
             * @type {Object}
             * @example
             */
            $blockLimit : {"body":1,"div":1,"td":1,"th":1,"caption":1,"form":1 },

            $inline : L,    // Just like span.

            $body : X({"script":1,"style":1}, block),

            $cdata : {"script":1,"style":1},

            /**
             * List of empty (self-closing) elements, like "br" or "img".
             * @type {Object}
             * @example
             */
            $empty : {"area":1,"base":1,"br":1,"col":1,"hr":1,"img":1,"input":1,"link":1,"meta":1,"param":1},

            /**
             * List of list item elements, like "li" or "dd".
             * @type {Object}
             * @example
             */
            $listItem : {"dd":1,"dt":1,"li":1},

            /**
             * List of list root elements.
             * @type {Object}
             * @example
             */
            $list: {"ul":1,"ol":1,"dl":1},

            /**
             * Elements that accept text nodes, but are not possible to edit into
             * the browser.
             * @type {Object}
             * @example
             */
            $nonEditable : {"applet":1,"button":1,"embed":1,"iframe":1,"map":1,"object":1,"option":1,"script":1,"textarea":1,"param":1},

            /**
             * List of elements that can be ignored if empty, like "b" or "span".
             * @type {Object}
             * @example
             */
            $removeEmpty : {"abbr":1,"acronym":1,"address":1,"b":1,"bdo":1,"big":1,"cite":1,"code":1,"del":1,"dfn":1,"em":1,"font":1,"i":1,"ins":1,"label":1,"kbd":1,"q":1,"s":1,"samp":1,"small":1,"span":1,"strike":1,"strong":1,"sub":1,"sup":1,"tt":1,"u":1,'var':1},

            /**
             * List of elements that have tabindex set to zero by default.
             * @type {Object}
             * @example
             */
            $tabIndex : {"a":1,"area":1,"button":1,"input":1,"object":1,"select":1,"textarea":1},

            /**
             * List of elements used inside the "table" element, like "tbody" or "td".
             * @type {Object}
             * @example
             */
            $tableContent : {"caption":1,"col":1,"colgroup":1,
                "tbody":1,"td":1,"tfoot":1,"th":1,"thead":1,"tr":1},
            "html": U,
            "head": T,
            "style": N,
            "body": P,
            "base": {},
            "link": {},
            "meta": {},
            "title": N,
            "col": {},
            "tr": {"td":1,"th":1},
            "img": {},
            "colgroup": {"col":1},
            "noscript": P,
            "td": P,
            "br": {},
            "th": P,
            "center": P,
            "kbd": L,
            "button": X(I, E),
            "basefont": {},
            "h5": L,
            "h4": L,
            "samp": L,
            "h6": L,
            "ol": Q,
            "h1": L,
            "h3": L,
            "option": N,
            "h2": L,
            "form" : X(A, D, E, I),
            "select" : {"optgroup":1,"option":1},
            "font" : L,
            "ins": L,
            "menu" : Q,
            "abbr": L,
            "label": L,
            "table": {"thead":1,"col":1,"tbody":1,"tr":1,"colgroup":1,"caption":1,"tfoot":1},
            "code": L,
            "script": N,
            "tfoot": M,
            "cite": L,
            "li": P,
            "input": {},
            "iframe": P,
            "strong": L,
            "textarea": N,
            "noframes": P,
            "big": L,
            "small": L,
            "span": L,
            "hr": {},
            "dt": L,
            "sub": L,
            "optgroup": {"option":1},
            "param": {},
            "bdo": L,
            'var' : L,
            "div": P,
            "object": O,
            "sup": L,
            "dd": P,
            "strike": L,
            "area": {},
            "dir": Q,
            "map": X({"area":1,"form":1,"p":1}, A, F, E),
            "applet": O,
            "dl": {"dt":1,"dd":1},
            "del": L,
            "isindex": {},
            "fieldset": X({"legend":1}, K),
            "thead": M,
            "ul": Q,
            "acronym": L,
            "b": L,
            "a": J,
            "blockquote": P,
            "caption": L,
            "i": L,
            "u": L,
            "tbody": M,
            "s": L,
            "address": X(D, I),
            "tt": L,
            "legend": L,
            "q": L,
            "pre": X(G, C),
            "p": L,
            "em": L,
            "dfn": L
        };
    })();
    KE["XHTML_DTD"] = KE.XHTML_DTD;
});
/**
 * dom utils for kissy editor,mainly from ckeditor
 * @author: <yiminghe@gmail.com>
 */
/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
KISSY.Editor.add("dom", function(KE) {

    var TRUE = true,
        FALSE = false,
        NULL = null,
        S = KISSY,
        DOM = S.DOM,
        UA = S.UA,
        doc = document,
        Node = S.Node,
        Utils = KE.Utils,
        GET_BOUNDING_CLIENT_RECT = 'getBoundingClientRect',
        REMOVE_EMPTY = {
            "abbr":1,
            "acronym":1,
            "address":1,
            "b":1,
            "bdo":1,
            "big":1,
            "cite":1,
            "code":1,
            "del":1,
            "dfn":1,
            "em":1,
            "font":1,
            "i":1,
            "ins":1,
            "label":1,
            "kbd":1,
            "q":1,
            "s":1,
            "samp":1,
            "small":1,
            "span":1,
            "strike":1,
            "strong":1,
            "sub":1,
            "sup":1,
            "tt":1,
            "u":1,
            'var':1
        };
    /**
     * Enum for node type
     * @enum {number}
     */
    KE.NODE = {
        NODE_ELEMENT:1,
        NODE_TEXT:3,
        NODE_COMMENT : 8,
        NODE_DOCUMENT_FRAGMENT:11
    };
    KE["NODE"] = KE.NODE;
    /**
     * Enum for node position
     * @enum {number}
     */
    KE.POSITION = {
        POSITION_IDENTICAL:0,
        POSITION_DISCONNECTED:1,
        POSITION_FOLLOWING:2,
        POSITION_PRECEDING:4,
        POSITION_IS_CONTAINED:8,
        POSITION_CONTAINS:16
    };
    KE["POSITION"] = KE.POSITION;
    var KEN = KE.NODE,KEP = KE.POSITION;

    /*
     * Anything whose display computed style is block, list-item, table,
     * table-row-group, table-header-group, table-footer-group, table-row,
     * table-column-group, table-column, table-cell, table-caption, or whose node
     * name is hr, br (when enterMode is br only) is a block boundary.
     */
    var customData = {},
        blockBoundaryDisplayMatch = {
            "block": 1,
            'list-item' : 1,
            "table": 1,
            'table-row-group' : 1,
            'table-header-group' : 1,
            'table-footer-group' : 1,
            'table-row' : 1,
            'table-column-group' : 1,
            'table-column' : 1,
            'table-cell' : 1,
            'table-caption' : 1
        },
        blockBoundaryNodeNameMatch = { "hr": 1 },
        /**
         * @param el {(Node|KISSY.Node)}
         */
        normalElDom = function(el) {
            return   el[0] || el;
        },
        /**
         * @param el {(Node|KISSY.Node)}
         */
        normalEl = function(el) {
            if (el && !el[0]) return new Node(el);
            return el;
        },
        editorDom = {
            _4e_wrap:normalEl,
            _4e_unwrap:normalElDom,
            /**
             *
             * @param e1 {(Node|KISSY.Node)}
             * @param e2 {(Node|KISSY.Node)}
             */
            _4e_equals:function(e1, e2) {
                //全部为空
                if (!e1 && !e2)return TRUE;
                //一个为空，一个不为空
                if (!e1 || !e2)return FALSE;
                e1 = normalElDom(e1);
                e2 = normalElDom(e2);
                return e1 === e2;
            },
            /**
             *
             * @param el {(Node|KISSY.Node)}
             * @param customNodeNames {Object.<string,number>}
             */
            _4e_isBlockBoundary:function(el, customNodeNames) {
                el = normalEl(el);
                var nodeNameMatches = S.mix(S.mix({}, blockBoundaryNodeNameMatch), customNodeNames || {});

                return blockBoundaryDisplayMatch[ el.css('display') ] ||
                    nodeNameMatches[ el._4e_name() ];
            },

            /**
             *
             * @param elem {Node|Document}
             */
            _4e_getWin:function(elem) {
                return (elem && ('scrollTo' in elem) && elem["document"]) ?
                    elem :
                    elem && elem.nodeType === 9 ?
                        elem.defaultView || elem.parentWindow :
                        FALSE;
            },
            /**
             *
             * @param el {(Node|KISSY.Node)}
             */
            _4e_index:function(el) {
                el = normalElDom(el);
                var siblings = el.parentNode.childNodes;
                for (var i = 0; i < siblings.length; i++) {
                    if (siblings[i] === el) return i;
                }
                return -1;
            },
            /**
             *
             * @param el {(Node|KISSY.Node)}
             * @param evaluator {function(KISSY.Node)}
             */
            _4e_first:function(el, evaluator) {
                el = normalElDom(el);
                var first = el.firstChild,
                    retval = first && new Node(first);
                if (retval && evaluator && !evaluator(retval))
                    retval = retval._4e_next(evaluator);

                return retval;
            },
            /**
             *
             * @param thisElement {(Node|KISSY.Node)}
             * @param target {(Node|KISSY.Node)}
             * @param toStart {boolean}
             */
            _4e_move : function(thisElement, target, toStart) {
                thisElement = normalElDom(thisElement);
                DOM._4e_remove(thisElement);
                target = normalElDom(target);
                if (toStart) {
                    target.insertBefore(thisElement, target.firstChild);
                }
                else {
                    target.appendChild(thisElement);
                }
            },

            /**
             *
             * @param thisElement {(Node|KISSY.Node)}
             */
            _4e_name:function(thisElement) {
                thisElement = normalElDom(thisElement);
                var nodeName = thisElement.nodeName.toLowerCase();
                //note by yiminghe:http://msdn.microsoft.com/en-us/library/ms534388(VS.85).aspx
                if (UA.ie) {
                    var scopeName = thisElement.scopeName;
                    if (scopeName && scopeName != 'HTML')
                        nodeName = scopeName.toLowerCase() + ':' + nodeName;
                }
                return nodeName;
            },
            /**
             *
             * @param thisElement {(Node|KISSY.Node)}
             * @param otherElement {(Node|KISSY.Node)}
             */
            _4e_isIdentical : function(thisElement, otherElement) {
                if (thisElement._4e_name() != otherElement._4e_name())
                    return FALSE;

                var thisAttribs = thisElement[0].attributes,
                    otherAttribs = otherElement[0].attributes,thisLength = thisAttribs.length,
                    otherLength = otherAttribs.length;

                if (!UA.ie && thisLength != otherLength)
                    return FALSE;

                for (var i = 0; i < thisLength; i++) {
                    var attribute = thisAttribs[ i ];

                    if (( !UA.ie || ( attribute.specified && attribute.nodeName != '_ke_expando' ) ) && attribute.nodeValue != otherElement.attr(attribute.nodeName))
                        return FALSE;
                }

                // For IE, we have to for both elements, because it's difficult to
                // know how the atttibutes collection is organized in its DOM.
                if (UA.ie) {
                    for (i = 0; i < otherLength; i++) {
                        attribute = otherAttribs[ i ];
                        if (attribute.specified && attribute.nodeName != '_ke_expando'
                            && attribute.nodeValue != thisElement.attr(attribute.nodeName))
                            return FALSE;
                    }
                }

                return TRUE;
            },

            /**
             *
             * @param thisElement {(Node|KISSY.Node)}
             */
            _4e_isEmptyInlineRemoveable : function(thisElement) {
                var children = normalElDom(thisElement).childNodes;
                for (var i = 0, count = children.length; i < count; i++) {
                    var child = children[i],
                        nodeType = child.nodeType;

                    if (nodeType == KEN.NODE_ELEMENT && child.getAttribute('_ke_bookmark'))
                        continue;

                    if (nodeType == KEN.NODE_ELEMENT && !editorDom._4e_isEmptyInlineRemoveable(child)
                        || nodeType == KEN.NODE_TEXT && S.trim(child.nodeValue)) {
                        return FALSE;
                    }
                }
                return TRUE;
            },

            /**
             *
             * @param thisElement {(Node|KISSY.Node)}
             * @param target {(Node|KISSY.Node)}
             * @param toStart {boolean}
             */
            _4e_moveChildren : function(thisElement, target, toStart) {
                var $ = normalElDom(thisElement);
                target = target[0] || target;
                if ($ == target)
                    return;

                var child;

                if (toStart) {
                    while (( child = $.lastChild ))
                        target.insertBefore($.removeChild(child), target.firstChild);
                }
                else {
                    while (( child = $.firstChild ))
                        target.appendChild($.removeChild(child));
                }
            },

            /**
             *
             * @param elem {(Node|KISSY.Node)}
             */
            _4e_mergeSiblings : ( function() {

                /**
                 *
                 * @param element {(Node|KISSY.Node)}
                 * @param sibling {(Node|KISSY.Node)}
                 * @param  {boolean=} isNext
                 */
                function mergeElements(element, sibling, isNext) {
                    if (sibling[0] && sibling[0].nodeType == KEN.NODE_ELEMENT) {
                        // Jumping over bookmark nodes and empty inline elements, e.g. <b><i></i></b>,
                        // queuing them to be moved later. (#5567)
                        var pendingNodes = [];

                        while (sibling.attr('_ke_bookmark')
                            || sibling._4e_isEmptyInlineRemoveable()) {
                            pendingNodes.push(sibling);
                            sibling = isNext ? new Node(sibling[0].nextSibling) : new Node(sibling[0].previousSibling);
                            if (!sibling[0] || sibling[0].nodeType != KEN.NODE_ELEMENT)
                                return;
                        }

                        if (element._4e_isIdentical(sibling)) {
                            // Save the last child to be checked too, to merge things like
                            // <b><i></i></b><b><i></i></b> => <b><i></i></b>
                            var innerSibling = isNext ? element[0].lastChild : element[0].firstChild;

                            // Move pending nodes first into the target element.
                            while (pendingNodes.length)
                                pendingNodes.shift()._4e_move(element, !isNext);

                            sibling._4e_moveChildren(element, !isNext);
                            sibling._4e_remove();

                            // Now check the last inner child (see two comments above).
                            if (innerSibling[0] && innerSibling[0].nodeType == KEN.NODE_ELEMENT)
                                innerSibling._4e_mergeSiblings();
                        }
                    }
                }

                return function(thisElement) {
                    if (!thisElement[0]) return;
                    //note by yiminghe,why not just merge whatever
                    // Merge empty links and anchors also. (#5567)
                    if (!( REMOVE_EMPTY[ thisElement._4e_name() ] || thisElement._4e_name() == "a" ))
                        return;

                    mergeElements(thisElement, new Node(thisElement[0].nextSibling), TRUE);
                    mergeElements(thisElement, new Node(thisElement[0].previousSibling));
                };
            } )(),

            /**
             *
             * @param elem {(Node|KISSY.Node)}
             */
            _4e_unselectable :
                UA.gecko ?
                    function(el) {
                        el = normalElDom(el);
                        el.style.MozUserSelect = 'none';
                    }
                    : UA.webkit ?
                    function(el) {
                        el = normalElDom(el);
                        el.style.KhtmlUserSelect = 'none';
                    }
                    :
                    function(el) {
                        el = normalElDom(el);
                        if (UA.ie || UA.opera) {
                            var
                                e,
                                i = 0;

                            el.unselectable = 'on';

                            while (( e = el.all[ i++ ] )) {
                                switch (e.tagName.toLowerCase()) {
                                    case 'iframe' :
                                    case 'textarea' :
                                    case 'input' :
                                    case 'select' :
                                        /* Ignore the above tags */
                                        break;
                                    default :
                                        e.unselectable = 'on';
                                }
                            }
                        }
                    },

            /**
             *
             * @param elem {(Node|KISSY.Node)}
             * @param refDocument {Document}
             */
            _4e_getOffset:function(elem, refDocument) {
                elem = normalElDom(elem);
                var box,
                    x = 0,
                    y = 0,
                    currentWindow = elem.ownerDocument.defaultView || elem.ownerDocument.parentWindow,
                    currentDoc = elem.ownerDocument,
                    currentDocElem = currentDoc.documentElement;
                refDocument = refDocument || currentDoc;
                //same with DOM.offset
                if (elem[GET_BOUNDING_CLIENT_RECT]) {
                    if (elem !== currentDoc.body && currentDocElem !== elem) {
                        box = elem[GET_BOUNDING_CLIENT_RECT]();
                        //相对于refDocument，里层iframe的滚动不计
                        x = box.left + (refDocument === currentDoc ? DOM["scrollLeft"](currentWindow) : 0);
                        y = box.top + (refDocument === currentDoc ? DOM["scrollTop"](currentWindow) : 0);
                    }
                    if (refDocument) {
                        var refWindow = refDocument.defaultView || refDocument.parentWindow;
                        if (currentWindow != refWindow && currentWindow['frameElement']) {
                            //note:when iframe is static ,still some mistake
                            var iframePosition = editorDom._4e_getOffset(currentWindow.frameElement, refDocument);
                            x += iframePosition.left;
                            y += iframePosition.top;
                        }
                    }
                }
                return { left: x, top: y };
            },

            /**
             *
             * @param el {(Node|KISSY.Node)}
             */
            _4e_getFrameDocument : function(el) {
                var $ = normalElDom(el),t;

                try {
                    // In IE, with custom document.domain, it may happen that
                    // the iframe is not yet available, resulting in "Access
                    // Denied" for the following property access.
                    t = $.contentWindow.document;
                }
                catch (e) {
                    // Trick to solve this issue, forcing the iframe to get ready
                    // by simply setting its "src" property.
                    t = $.src;
                    $.src = t;

                    // In IE6 though, the above is not enough, so we must pause the
                    // execution for a while, giving it time to think.
                    if (UA.ie && UA.ie < 7) {
                        window.showModalDialog(
                            'javascript:document.write("' +
                                '<script>' +
                                'window.setTimeout(' +
                                'function(){window.close();}' +
                                ',50);' +
                                '</scrip' +
                                't' +
                                '>' +
                                '")');
                    }
                }
                return $ && $.contentWindow.document;
            },

            /**
             *
             * @param el {(Node|KISSY.Node)}
             * @param offset {number}
             */
            _4e_splitText : function(el, offset) {
                el = normalElDom(el);
                var doc = el.ownerDocument;
                if (!el || el.nodeType != KEN.NODE_TEXT) return;
                // If the offset is after the last char, IE creates the text node
                // on split, but don't include it into the DOM. So, we have to do
                // that manually here.
                if (UA.ie && offset == el.nodeValue.length) {
                    var next = doc.createTextNode("");
                    DOM.insertAfter(next, el);
                    return new Node(next);
                }


                var retval = new Node(el.splitText(offset));

                // IE BUG: IE8 does not update the childNodes array in DOM after splitText(),
                // we need to make some DOM changes to make it update. (#3436)
                //我靠！UA.ie==8 不对，
                //判断不出来:UA.ie==7 && doc.documentMode==7
                //浏览器模式：当ie8处于兼容视图以及ie7时，UA.ie==7
                //文本模式: mode=5 ,mode=7, mode=8
                //alert("ua:"+UA.ie);
                //alert("mode:"+doc.documentMode);
                //ie8 浏览器有问题，而不在于是否哪个模式
                if (!!doc.documentMode) {
                    var workaround = doc.createTextNode("");
                    DOM.insertAfter(workaround, retval[0]);
                    DOM._4e_remove(workaround);
                }

                return retval;
            },

            /**
             *
             * @param node {(Node|KISSY.Node)}
             * @param closerFirst {boolean}
             */
            _4e_parents : function(node, closerFirst) {
                node = normalEl(node);
                var parents = [];
                do {
                    parents[  closerFirst ? 'push' : 'unshift' ](node);
                } while (( node = node.parent() ));

                return parents;
            },

            /**
             *
             * @param el {(Node|KISSY.Node)}
             * @param includeChildren {boolean}
             * @param cloneId {string}
             */
            _4e_clone : function(el, includeChildren, cloneId) {
                el = normalElDom(el);
                var $clone = el.cloneNode(includeChildren);

                if (!cloneId) {
                    var removeIds = function(node) {
                        if (node.nodeType != KEN.NODE_ELEMENT)
                            return;

                        node.removeAttribute('id', FALSE);
                        //复制时不要复制expando
                        node.removeAttribute('_ke_expando', FALSE);

                        var childs = node.childNodes;
                        for (var i = 0; i < childs.length; i++)
                            removeIds(childs[ i ]);
                    };

                    // The "id" attribute should never be cloned to avoid duplication.
                    removeIds($clone);
                }
                return new Node($clone);
            },
            /**
             * 深度优先遍历获取下一结点
             * @param el {(Node|KISSY.Node)}
             * @param startFromSibling {boolean}
             * @param nodeType {number}
             * @param guard {function(KISSY.Node)}
             */
            _4e_nextSourceNode : function(el, startFromSibling, nodeType, guard) {
                el = normalElDom(el);
                // If "guard" is a node, transform it in a function.
                if (guard && !guard.call) {
                    var guardNode = guard[0] || guard;
                    guard = function(node) {
                        node = node[0] || node;
                        return node !== guardNode;
                    };
                }

                var node = !startFromSibling && el.firstChild ,
                    parent = new Node(el);

                // Guarding when we're skipping the current element( no children or 'startFromSibling' ).
                // send the 'moving out' signal even we don't actually dive into.
                if (!node) {
                    if (el.nodeType == KEN.NODE_ELEMENT && guard && guard(el, TRUE) === FALSE)
                        return NULL;
                    node = el.nextSibling;
                }

                while (!node && ( parent = parent.parent())) {
                    // The guard check sends the "TRUE" paramenter to indicate that
                    // we are moving "out" of the element.
                    if (guard && guard(parent, TRUE) === FALSE)
                        return NULL;

                    node = parent[0].nextSibling;
                }

                if (!node)
                    return NULL;
                node = DOM._4e_wrap(node);
                if (guard && guard(node) === FALSE)
                    return NULL;

                if (nodeType && nodeType != node[0].nodeType)
                    return node._4e_nextSourceNode(FALSE, nodeType, guard);

                return node;
            },

            /**
             *
             * @param el {(Node|KISSY.Node)}
             * @param startFromSibling {boolean}
             * @param nodeType {number}
             * @param guard {function(KISSY.Node)}
             */
            _4e_previousSourceNode : function(el, startFromSibling, nodeType, guard) {
                el = normalElDom(el);
                if (guard && !guard.call) {
                    var guardNode = guard[0] || guard;
                    guard = function(node) {
                        node = node[0] || node;
                        return node !== guardNode;
                    };
                }

                var node = ( !startFromSibling && el.lastChild),
                    parent = new Node(el);

                // Guarding when we're skipping the current element( no children or 'startFromSibling' ).
                // send the 'moving out' signal even we don't actually dive into.
                if (!node) {
                    if (el.nodeType == KEN.NODE_ELEMENT && guard && guard(el, TRUE) === FALSE)
                        return NULL;
                    node = el.previousSibling;
                }

                while (!node && ( parent = parent.parent() )) {
                    // The guard check sends the "TRUE" paramenter to indicate that
                    // we are moving "out" of the element.
                    if (guard && guard(parent, TRUE) === FALSE)
                        return NULL;
                    node = parent[0].previousSibling;
                }

                if (!node)
                    return NULL;
                node = DOM._4e_wrap(node);
                if (guard && guard(node) === FALSE)
                    return NULL;

                if (nodeType && node[0].nodeType != nodeType)
                    return node._4e_previousSourceNode(FALSE, nodeType, guard);

                return node;
            },

            /**
             *
             * @param el {(Node|KISSY.Node)}
             * @param node {(Node|KISSY.Node)}
             */
            _4e_contains :
                UA.ie || UA.webkit ?
                    function(el, node) {
                        el = normalElDom(el);
                        node = normalElDom(node);
                        return node.nodeType != KEN.NODE_ELEMENT ?
                            el.contains(node.parentNode) :
                            el != node && el.contains(node);
                    }
                    :
                    function(el, node) {
                        el = normalElDom(el);
                        node = normalElDom(node);
                        return !!( el.compareDocumentPosition(node) & 16 );
                    },

            /**
             *
             * @param el {(Node|KISSY.Node)}
             * @param node {(Node|KISSY.Node)}
             */
            _4e_commonAncestor:function(el, node) {
                if (el._4e_equals(node))
                    return el;

                if (node[0].nodeType != KEN.NODE_TEXT && node._4e_contains(el))
                    return node;

                var start = el[0].nodeType == KEN.NODE_TEXT ? el.parent() : el;

                do   {
                    if (start[0].nodeType != KEN.NODE_TEXT && start._4e_contains(node))
                        return start;
                } while (( start = start.parent() ));

                return NULL;
            },

            /**
             *
             * @param el {(Node|KISSY.Node)}
             * @param name {string}
             * @param includeSelf {boolean}
             */
            _4e_ascendant : function(el, name, includeSelf) {
                var $ = normalElDom(el);

                if (!includeSelf)
                    $ = $.parentNode;
                if (name && !S.isFunction(name)) {
                    var n = name;
                    name = function(node) {
                        return node._4e_name() == n;
                    };
                }
                //到document就完了
                while ($ && $.nodeType != 9) {
                    if (!name || name(new Node($)) === TRUE)
                        return new Node($);

                    $ = $.parentNode;
                }
                return NULL;
            },

            /**
             *
             * @param el {(Node|KISSY.Node)}
             * @param name {string}
             */
            _4e_hasAttribute : function(el, name) {
                el = normalElDom(el);
                var $attr = el.attributes.getNamedItem(name);
                return !!( $attr && $attr.specified );
            },
            /**
             *
             * @param el {(Node|KISSY.Node)}
             * @param otherNode {(Node|KISSY.Node)}
             */
            _4e_hasAttributes: UA.ie ?
                function(el) {
                    el = normalElDom(el);
                    var attributes = el.attributes;

                    for (var i = 0; i < attributes.length; i++) {
                        var attribute = attributes[i];

                        switch (attribute.nodeName) {
                            case 'class' :
                                // IE has a strange bug. If calling removeAttribute('className'),
                                // the attributes collection will still contain the "class"
                                // attribute, which will be marked as "specified", even if the
                                // outerHTML of the element is not displaying the class attribute.
                                // Note : I was not able to reproduce it outside the editor,
                                // but I've faced it while working on the TC of #1391.
                                if (el.getAttribute('class'))
                                    return TRUE;
                                break;
                            // Attributes to be ignored.
                            case '_ke_expando' :
                                continue;

                            /*jsl:fallthru*/

                            default :
                                if (attribute.specified)
                                    return TRUE;
                        }
                    }

                    return FALSE;
                }
                :
                function(el) {
                    el = normalElDom(el);
                    //删除firefox自己添加的标志
                    UA.gecko && el.removeAttribute("_moz_dirty");
                    var attributes = el.attributes;
                    return ( attributes.length > 1 || ( attributes.length == 1 && attributes[0].nodeName != '_ke_expando' ) );
                },

            /**
             *
             * @param el {(Node|KISSY.Node)}
             * @param otherNode {(Node|KISSY.Node)}
             */
            _4e_position : function(el, otherNode) {
                var $ = normalElDom(el),$other = normalElDom(otherNode);


                if ($.compareDocumentPosition)
                    return $.compareDocumentPosition($other);

                // IE and Safari have no support for compareDocumentPosition.

                if ($ == $other)
                    return KEP.POSITION_IDENTICAL;

                // Only element nodes support contains and sourceIndex.
                if ($.nodeType == KEN.NODE_ELEMENT && $other.nodeType == KEN.NODE_ELEMENT) {
                    if ($.contains) {
                        if ($.contains($other))
                            return KEP.POSITION_CONTAINS + KEP.POSITION_PRECEDING;

                        if ($other.contains($))
                            return KEP.POSITION_IS_CONTAINED + KEP.POSITION_FOLLOWING;
                    }

                    if ('sourceIndex' in $) {
                        return ( $.sourceIndex < 0 || $other.sourceIndex < 0 ) ? KEP.POSITION_DISCONNECTED :
                            ( $.sourceIndex < $other.sourceIndex ) ? KEP.POSITION_PRECEDING :
                                KEP.POSITION_FOLLOWING;
                    }
                }

                // For nodes that don't support compareDocumentPosition, contains
                // or sourceIndex, their "address" is compared.

                var addressOfThis = el._4e_address(),
                    addressOfOther = otherNode._4e_address(),
                    minLevel = Math.min(addressOfThis.length, addressOfOther.length);

                // Determinate preceed/follow relationship.
                for (var i = 0; i <= minLevel - 1; i++) {
                    if (addressOfThis[ i ] != addressOfOther[ i ]) {
                        if (i < minLevel) {
                            return addressOfThis[ i ] < addressOfOther[ i ] ?
                                KEP.POSITION_PRECEDING : KEP.POSITION_FOLLOWING;
                        }
                        break;
                    }
                }

                // Determinate contains/contained relationship.
                return ( addressOfThis.length < addressOfOther.length ) ?
                    KEP.POSITION_CONTAINS + KEP.POSITION_PRECEDING :
                    KEP.POSITION_IS_CONTAINED + KEP.POSITION_FOLLOWING;
            },

            /**
             *
             * @param el {(Node|KISSY.Node)}
             * @param normalized {boolean}
             */
            _4e_address:function(el, normalized) {
                el = normalElDom(el);
                var address = [],

                    $documentElement = el.ownerDocument.documentElement,
                    node = el;

                while (node && node != $documentElement) {
                    var parentNode = node.parentNode,
                        currentIndex = -1;

                    if (parentNode) {
                        for (var i = 0; i < parentNode.childNodes.length; i++) {
                            var candidate = parentNode.childNodes[i];

                            if (normalized &&
                                candidate.nodeType == 3 &&
                                candidate.previousSibling &&
                                candidate.previousSibling.nodeType == 3) {
                                continue;
                            }

                            currentIndex++;

                            if (candidate == node)
                                break;
                        }

                        address.unshift(currentIndex);
                    }

                    node = parentNode;
                }
                return address;
            },

            /**
             *
             * @param el {(Node|KISSY.Node)}
             * @param parent {(Node|KISSY.Node)}
             */
            _4e_breakParent : function(el, parent) {
                var KERange = KE.Range,range = new KERange(el[0].ownerDocument);

                // We'll be extracting part of this element, so let's use our
                // range to get the correct piece.
                range.setStartAfter(el);
                range.setEndAfter(parent);

                // Extract it.
                var docFrag = range.extractContents();

                // Move the element outside the broken element.
                range.insertNode(el._4e_remove());

                // Re-insert the extracted piece after the element.
                el[0].parentNode.insertBefore(docFrag, el[0].nextSibling);
            },

            /**
             *
             * @param el {(Node|KISSY.Node)}
             * @param styleName {string}
             * @param val {string=}
             */
            _4e_style:function(el, styleName, val) {
                el = normalEl(el);
                if (val !== undefined) {
                    return el.css(styleName, val);
                }
                el = normalElDom(el);
                return el.style[normalizeStyle(styleName)];
            },

            /**
             *
             * @param el {(Node|KISSY.Node)}
             * @param preserveChildren {boolean}
             */
            _4e_remove : function(el, preserveChildren) {
                var $ = normalElDom(el), parent = $.parentNode;
                if (parent) {
                    if (preserveChildren) {
                        // Move all children before the node.
                        for (var child; ( child = $.firstChild );) {
                            parent.insertBefore($.removeChild(child), $);
                        }
                    }
                    parent.removeChild($);
                }
                return el;
            },
            /**
             *
             * @param el {(Node|KISSY.Node)}
             */
            _4e_trim : function(el) {
                DOM._4e_ltrim(el);
                DOM._4e_rtrim(el);
            },

            /**
             *
             * @param el {(Node|KISSY.Node)}
             */
            _4e_ltrim : function(el) {
                el = normalElDom(el);
                var child;
                while (( child = el.firstChild )) {
                    if (child.nodeType == KEN.NODE_TEXT) {
                        var trimmed = Utils.ltrim(child.nodeValue),
                            originalLength = child.nodeValue.length;

                        if (!trimmed) {
                            el.removeChild(child);
                            continue;
                        }
                        else if (trimmed.length < originalLength) {
                            new Node(child)._4e_splitText(originalLength - trimmed.length);
                            // IE BUG: child.remove() may raise JavaScript errors here. (#81)
                            el.removeChild(el.firstChild);
                        }
                    }
                    break;
                }
            },

            /**
             *
             * @param el {(Node|KISSY.Node)}
             */
            _4e_rtrim : function(el) {
                el = normalElDom(el);
                var child;
                while (( child = el.lastChild )) {
                    if (child.type == KEN.NODE_TEXT) {
                        var trimmed = Utils.rtrim(child.nodeValue),
                            originalLength = child.nodeValue.length;

                        if (!trimmed) {
                            el.removeChild(child);
                            continue;
                        } else if (trimmed.length < originalLength) {
                            new Node(child)._4e_splitText(trimmed.length);
                            // IE BUG: child.getNext().remove() may raise JavaScript errors here.
                            // (#81)
                            el.removeChild(el.lastChild);
                        }
                    }
                    break;
                }

                if (!UA.ie && !UA.opera) {
                    child = el.lastChild;
                    if (child && child.nodeType == 1 && child.nodeName.toLowerCase() == 'br') {
                        // Use "eChildNode.parentNode" instead of "node" to avoid IE bug (#324).
                        child.parentNode.removeChild(child);
                    }
                }
            },

            /**
             *
             * @param el {(Node|KISSY.Node)}
             */
            _4e_appendBogus : function(el) {
                el = normalElDom(el);
                var lastChild = el.lastChild;

                // Ignore empty/spaces text.
                while (lastChild && lastChild.nodeType == KEN.NODE_TEXT && !S.trim(lastChild.nodeValue))
                    lastChild = lastChild.previousSibling;
                if (!lastChild ||
                    lastChild.nodeType == KEN.NODE_TEXT ||
                    DOM._4e_name(lastChild) !== 'br') {
                    var bogus = UA.opera ?
                        el.ownerDocument.createTextNode('') :
                        el.ownerDocument.createElement('br');

                    UA.gecko && bogus.setAttribute('type', '_moz');
                    el.appendChild(bogus);
                }
            },

            /**
             *
             * @param el {(Node|KISSY.Node)}
             * @param evaluator {function(KISSY.Node)}
             */
            _4e_previous : function(el, evaluator) {
                var previous = normalElDom(el), retval;
                do {
                    previous = previous.previousSibling;
                    retval = previous && new Node(previous);
                } while (retval && evaluator && !evaluator(retval));
                return retval;
            },

            /**
             *
             * @param el {(Node|KISSY.Node)}
             * @param evaluator {function(KISSY.Node)}
             */
            _4e_last : function(el, evaluator) {
                el = DOM._4e_wrap(el);
                var last = el[0].lastChild,
                    retval = last && new Node(last);
                if (retval && evaluator && !evaluator(retval))
                    retval = retval._4e_previous(evaluator);

                return retval;
            },
            /**
             *
             * @param el {(Node|KISSY.Node)}
             * @param evaluator {function(KISSY.Node)}
             */
            _4e_next : function(el, evaluator) {
                var next = normalElDom(el), retval;
                do {
                    next = next.nextSibling;
                    retval = next && new Node(next);
                } while (retval && evaluator && !evaluator(retval));
                return retval;
            },
            /**
             *
             * @param el {(Node|KISSY.Node)}
             */
            _4e_outerHtml : function(el) {
                el = normalElDom(el);
                if (el.outerHTML) {
                    // IE includes the <?xml:namespace> tag in the outerHTML of
                    // namespaced element. So, we must strip it here. (#3341)
                    return el.outerHTML.replace(/<\?[^>]*>/, '');
                }

                var tmpDiv = el.ownerDocument.createElement('div');
                tmpDiv.appendChild(el.cloneNode(TRUE));
                return tmpDiv.innerHTML;
            },

            /**
             *
             * @param element {(Node|KISSY.Node)}
             * @param database {Object.<string,KISSY.Node>}
             * @param name {string}
             * @param value {string}
             */
            _4e_setMarker : function(element, database, name, value) {
                element = DOM._4e_wrap(element);
                var id = element._4e_getData('list_marker_id') ||
                    ( element._4e_setData('list_marker_id', S.guid())._4e_getData('list_marker_id')),
                    markerNames = element._4e_getData('list_marker_names') ||
                        ( element._4e_setData('list_marker_names', {})._4e_getData('list_marker_names'));
                database[id] = element;
                markerNames[name] = 1;

                return element._4e_setData(name, value);
            },

            /**
             *
             * @param element {(Node|KISSY.Node)}
             * @param database {Object.<string,KISSY.Node>}
             * @param removeFromDatabase {boolean}
             */
            _4e_clearMarkers : function(element, database, removeFromDatabase) {

                element = normalEl(element);
                var names = element._4e_getData('list_marker_names'),
                    id = element._4e_getData('list_marker_id');
                for (var i in names)
                    element._4e_removeData(i);
                element._4e_removeData('list_marker_names');
                if (removeFromDatabase) {
                    element._4e_removeData('list_marker_id');
                    delete database[id];
                }
            },

            /**
             *
             * @param el {(Node|KISSY.Node)}
             * @param key {string}
             * @param value {string}
             */
            _4e_setData : function(el, key, value) {
                var expandoNumber = DOM._4e_getUniqueId(el),
                    dataSlot = customData[ expandoNumber ] || ( customData[ expandoNumber ] = {} );
                dataSlot[ key ] = value;
                return el;
            },

            /**
             *
             * @param el {(Node|KISSY.Node)}
             * @param key {string}
             */
            _4e_getData :function(el, key) {
                el = normalElDom(el);
                var expandoNumber = el.getAttribute('_ke_expando'),
                    dataSlot = expandoNumber && customData[ expandoNumber ];
                return dataSlot && dataSlot[ key ];
            },

            /**
             *
             * @param el {(Node|KISSY.Node)}
             * @param key {string}
             */
            _4e_removeData : function(el, key) {
                el = normalElDom(el);
                var expandoNumber = el.getAttribute('_ke_expando'),
                    dataSlot = expandoNumber && customData[ expandoNumber ],
                    retval = dataSlot && dataSlot[ key ];

                if (typeof retval != 'undefined' && dataSlot)
                    delete dataSlot[ key ];
                if (S.isEmptyObject(dataSlot))
                    DOM._4e_clearData(el);

                return retval || NULL;
            },

            /**
             *
             * @param el {(Node|KISSY.Node)}
             */
            _4e_clearData : function(el) {
                el = normalElDom(el);
                var expandoNumber = el.getAttribute('_ke_expando');
                expandoNumber && delete customData[ expandoNumber ];
                //ie inner html 会把属性带上，删掉！
                expandoNumber && el.removeAttribute("_ke_expando");
            },

            /**
             *
             * @param el {(Node|KISSY.Node)}
             */
            _4e_getUniqueId : function(el) {
                el = normalElDom(el);
                var id = el.getAttribute('_ke_expando');
                if (id) return id;
                id = S.guid();
                el.setAttribute('_ke_expando', id);
                return id;
            },


            /**
             *
             * @param el {(Node|KISSY.Node)}
             * @param dest  {(Node|KISSY.Node)}
             * @param skipAttributes {Object.<string,number>}
             */
            _4e_copyAttributes : function(el, dest, skipAttributes) {
                el = normalElDom(el);
                dest = normalEl(dest);
                var attributes = el.attributes;
                skipAttributes = skipAttributes || {};

                for (var n = 0; n < attributes.length; n++) {
                    // Lowercase attribute name hard rule is broken for
                    // some attribute on IE, e.g. CHECKED.
                    var attribute = attributes[n],
                        attrName = attribute.nodeName.toLowerCase(),
                        attrValue;

                    // We can set the type only once, so do it with the proper value, not copying it.
                    if (attrName in skipAttributes)
                        continue;

                    if (attrName == 'checked' && ( attrValue = DOM.attr(el, attrName) ))
                        dest.attr(attrName, attrValue);
                    // IE BUG: value attribute is never specified even if it exists.
                    else if (attribute.specified ||
                        ( UA.ie && attribute.nodeValue && attrName == 'value' )) {
                        attrValue = DOM.attr(el, attrName);
                        if (attrValue === NULL)
                            attrValue = attribute.nodeValue;
                        dest.attr(attrName, attrValue);
                    }
                }

                // The style:
                if (el.style.cssText !== '')
                    dest[0].style.cssText = el.style.cssText;
            },

            /**
             *
             * @param el {(Node|KISSY.Node)}
             */
            _4e_isEditable : function(el) {

                // Get the element DTD (defaults to span for unknown elements).
                var name = DOM._4e_name(el),
                    xhtml_dtd = KE.XHTML_DTD,
                    dtd = !xhtml_dtd.$nonEditable[ name ]
                        && ( xhtml_dtd[ name ] || xhtml_dtd["span"] );

                // In the DTD # == text node.
                return ( dtd && dtd['#'] );
            },
            /**
             * 修正scrollIntoView在可视区域内不需要滚动
             * @param elem {(Node|KISSY.Node)}
             */
            _4e_scrollIntoView:function(elem) {
                elem = normalEl(elem);
                var doc = elem[0].ownerDocument;
                var l = DOM.scrollLeft(doc),
                    t = DOM.scrollTop(doc),
                    eoffset = elem.offset(),
                    el = eoffset.left,
                    et = eoffset.top;
                if (DOM.viewportHeight(doc) + t < et ||
                    et < t ||
                    DOM.viewportWidth(doc) + l < el
                    ||
                    el < l
                    ) {
                    elem.scrollIntoView(doc);
                }
            },

            /**
             *
             * @param elem {(Node|KISSY.Node)}
             * @param tag {string}
             * @param namespace {string=}
             * @return {Array.<KISSY.Node>}
             */
            _4e_getElementsByTagName:function(elem, tag, namespace) {
                elem = normalElDom(elem);
                if (!UA.ie && namespace) {
                    tag = namespace + ":" + tag
                }
                var re = [],els = elem.getElementsByTagName(tag);
                for (var i = 0; i < els.length; i++)
                    re.push(new Node(els[i]));
                return re;
            }
        };

    /**
     *
     * @param styleName {string}
     */
    function normalizeStyle(styleName) {
        return styleName.replace(/-(\w)/g, function(m, g1) {
            return g1.toUpperCase();
        })
    }

    /**
     *
     * @param editorDom {Object}
     */
    S.DOM._4e_inject = function(editorDom) {
        S.mix(DOM, editorDom);
        for (var dm in editorDom) {
            if (editorDom.hasOwnProperty(dm))
                (function(dm) {
                    Node.prototype[dm] = function() {
                        var args = [].slice.call(arguments, 0);
                        args.unshift(this);
                        return editorDom[dm].apply(NULL, args);
                    };
                })(dm);
        }
    };


    Utils.extern(editorDom, {
        "_4e_wrap":editorDom._4e_wrap,
        "_4e_unwrap":editorDom._4e_unwrap,
        "_4e_equals":editorDom._4e_equals,
        "_4e_isBlockBoundary":editorDom._4e_isBlockBoundary,
        "_4e_getWin":editorDom._4e_getWin,
        "_4e_index":editorDom._4e_index,
        "_4e_first":editorDom._4e_first,
        "_4e_move":editorDom._4e_move,
        "_4e_name":editorDom._4e_name,
        "_4e_isIdentical":editorDom._4e_isIdentical,
        "_4e_isEmptyInlineRemoveable":editorDom._4e_isEmptyInlineRemoveable,
        "_4e_moveChildren":editorDom._4e_moveChildren,
        "_4e_mergeSiblings":editorDom._4e_mergeSiblings,
        "_4e_unselectable":editorDom._4e_unselectable,
        "_4e_getOffset":editorDom._4e_getOffset,
        "_4e_getFrameDocument":editorDom._4e_getFrameDocument,
        "_4e_splitText":editorDom._4e_splitText,
        "_4e_parents":editorDom._4e_parents,
        "_4e_clone":editorDom._4e_clone,
        "_4e_nextSourceNode":editorDom._4e_nextSourceNode,
        "_4e_previousSourceNode":editorDom._4e_previousSourceNode,
        "_4e_contains":editorDom._4e_contains,
        "_4e_commonAncestor":editorDom._4e_commonAncestor,
        "_4e_ascendant":editorDom._4e_ascendant,
        "_4e_hasAttribute":editorDom._4e_hasAttribute,
        "_4e_hasAttributes":editorDom._4e_hasAttributes,
        "_4e_position":editorDom._4e_position,
        "_4e_address":editorDom._4e_address,
        "_4e_breakParent":editorDom._4e_breakParent,
        "_4e_style":editorDom._4e_style,
        "_4e_remove":editorDom._4e_remove,
        "_4e_trim":editorDom._4e_trim,
        "_4e_ltrim":editorDom._4e_ltrim,
        "_4e_rtrim":editorDom._4e_rtrim,
        "_4e_appendBogus":editorDom._4e_appendBogus,
        "_4e_last":editorDom._4e_last,
        "_4e_previous":editorDom._4e_previous,
        "_4e_next":editorDom._4e_next,
        "_4e_outerHtml":editorDom._4e_outerHtml,
        "_4e_setMarker":editorDom._4e_setMarker,
        "_4e_clearMarkers":editorDom._4e_clearMarkers,
        "_4e_setData":editorDom._4e_setData,
        "_4e_getData":editorDom._4e_getData,
        "_4e_removeData":editorDom._4e_removeData,
        "_4e_clearData":editorDom._4e_clearData,
        "_4e_removeData":editorDom._4e_removeData,
        "_4e_getUniqueId":editorDom._4e_getUniqueId,
        "_4e_copyAttributes":editorDom._4e_copyAttributes,
        "_4e_isEditable":editorDom._4e_isEditable,
        "_4e_scrollIntoView":editorDom._4e_scrollIntoView,
        "_4e_getElementsByTagName":editorDom._4e_getElementsByTagName
    });

    DOM._4e_inject(editorDom);
});
/**
 * modified from ckeditor ,elementpath represents element's tree path from body
 * @author: <yiminghe@gmail.com>
 */
/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
KISSY.Editor.add("elementpath", function(KE) {
    var S = KISSY,
        DOM = S.DOM,
        dtd = KE.XHTML_DTD,
        KEN = KE.NODE,
        UA = S.UA,
        TRUE = true,
        FALSE = false,
        NULL = null;
    // Elements that may be considered the "Block boundary" in an element path.
    var pathBlockElements = {
        "address":1,
        "blockquote":1,
        "dl":1,
        "h1":1,
        "h2":1,
        "h3":1,
        "h4":1,
        "h5":1,
        "h6":1,
        "p":1,
        "pre":1,
        "li":1,
        "dt":1,
        "dd":1
    };

    // Elements that may be considered the "Block limit" in an element path.
    var pathBlockLimitElements = {
        "body":1,
        "div":1,
        "table":1,
        "tbody":1,
        "tr":1,
        "td":1,
        "th":1,
        "caption":1,
        "form":1
    };

    // Check if an element contains any block element.
    var checkHasBlock = function(element) {
        element = element[0] || element;
        var childNodes = element.childNodes;

        for (var i = 0, count = childNodes.length; i < count; i++) {
            var child = childNodes[i];

            if (child.nodeType == KEN.NODE_ELEMENT
                && dtd.$block[ child.nodeName.toLowerCase() ])
                return TRUE;
        }

        return FALSE;
    };

    /**
     * @constructor
     * @param lastNode {KISSY.Node}
     */
    function ElementPath(lastNode) {
        var block = NULL;
        var blockLimit = NULL;
        var elements = [];
        var e = lastNode;

        while (e && e[0]) {
            if (e[0].nodeType == KEN.NODE_ELEMENT) {
                if (!this.lastElement)
                    this.lastElement = e;

                var elementName = e._4e_name();

                if (!blockLimit) {
                    if (!block && pathBlockElements[ elementName ])
                        block = e;

                    if (pathBlockLimitElements[ elementName ]) {
                        // DIV is considered the Block, if no block is available (#525)
                        // and if it doesn't contain other blocks.
                        if (!block && elementName == 'div' && !checkHasBlock(e))
                            block = e;
                        else
                            blockLimit = e;
                    }
                }

                elements.push(e);
                if (elementName == 'body')
                    break;
            }
            e = e.parent();
        }

        this["block"] = this.block = block;
        this["blockLimit"] = this.blockLimit = blockLimit;
        this["elements"] = this.elements = elements;
    }

    ElementPath.prototype = {
        /**
         * Compares this element path with another one.
         * @param otherPath {ElementPath} The elementPath object to be
         * compared with this one.
         * @return {boolean} "TRUE" if the paths are equal, containing the same
         * number of elements and the same elements in the same order.
         */
        compare : function(otherPath) {
            var thisElements = this.elements;
            var otherElements = otherPath && otherPath.elements;

            if (!otherElements || thisElements.length != otherElements.length)
                return FALSE;

            for (var i = 0; i < thisElements.length; i++) {
                if (!DOM._4e_equals(thisElements[ i ], otherElements[ i ]))
                    return FALSE;
            }

            return TRUE;
        },

        contains : function(tagNames) {
            var elements = this.elements;
            for (var i = 0; i < elements.length; i++) {
                if (elements[ i ]._4e_name() in tagNames)
                    return elements[ i ];
            }
            return NULL;
        }
    };

    KE["ElementPath"] = KE.ElementPath = ElementPath;
    var ElementPathP = ElementPath.prototype;
    KE.Utils.extern(ElementPathP, {
        "compare":ElementPathP.compare,
        "contains":ElementPathP.contains
    });

});
/**
 * modified from ckeditor for kissy editor ,walker implementation
 * refer: http://www.w3.org/TR/DOM-Level-2-Traversal-Range/traversal#TreeWalker
 * @author: <yiminghe@gmail.com>
 */
/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
KISSY.Editor.add("walker", function(KE) {

    var TRUE = true,
        FALSE = false,
        NULL = null,
        S = KISSY,
        KEN = KE.NODE,
        DOM = S.DOM,
        Node = S.Node;

    /**
     * @this {Walker}
     * @param  {boolean=} rtl
     * @param  {boolean=} breakOnFalse
     * @return {(KISSY.Node|boolean)}
     */
    function iterate(rtl, breakOnFalse) {
        var self = this;
        // Return NULL if we have reached the end.
        if (this._.end)
            return NULL;

        var node,
            range = self.range,
            guard,
            userGuard = self.guard,
            type = self.type,
            getSourceNodeFn = ( rtl ? '_4e_previousSourceNode' : '_4e_nextSourceNode' );

        // This is the first call. Initialize it.
        if (!self._.start) {
            self._.start = 1;

            // Trim text nodes and optmize the range boundaries. DOM changes
            // may happen at this point.
            range.trim();

            // A collapsed range must return NULL at first call.
            if (range.collapsed) {
                self.end();
                return NULL;
            }
        }

        // Create the LTR guard function, if necessary.
        if (!rtl && !self._.guardLTR) {
            // Gets the node that stops the walker when going LTR.
            var limitLTR = range.endContainer,
                blockerLTR = new Node(limitLTR[0].childNodes[range.endOffset]);
            //从左到右保证在 range 区间内获取 nextSourceNode
            this._.guardLTR = function(node, movingOut) {
                node = DOM._4e_wrap(node);
                //从endContainer移出去，失败返回false
                return (
                    node
                        && node[0]
                        &&
                        (!movingOut
                            ||
                            ! DOM._4e_equals(limitLTR, node)
                            )
                        //到达深度遍历的最后一个节点，结束
                        &&

                        (!blockerLTR[0] || !node._4e_equals(blockerLTR))

                        //从body移出也结束
                        && ( node[0].nodeType != KEN.NODE_ELEMENT
                        || !movingOut
                        || node._4e_name() != 'body' )
                    );
            };
        }

        // Create the RTL guard function, if necessary.
        if (rtl && !self._.guardRTL) {
            // Gets the node that stops the walker when going LTR.
            var limitRTL = range.startContainer,
                blockerRTL = ( range.startOffset > 0 ) && new Node(limitRTL[0].childNodes[range.startOffset - 1]);

            self._.guardRTL = function(node, movingOut) {
                node = DOM._4e_wrap(node);
                return (
                    node
                        && node[0]
                        && ( !movingOut || !node._4e_equals(limitRTL)  )
                        && ( !blockerRTL[0] || !node._4e_equals(blockerRTL) )
                        && ( node[0].nodeType != KEN.NODE_ELEMENT || !movingOut || node._4e_name() != 'body' )
                    );
            };
        }

        // Define which guard function to use.
        var stopGuard = rtl ? self._.guardRTL : self._.guardLTR;

        // Make the user defined guard function participate in the process,
        // otherwise simply use the boundary guard.
        if (userGuard) {
            guard = function(node, movingOut) {
                if (stopGuard(node, movingOut) === FALSE)
                    return FALSE;

                return userGuard(node, movingOut);
            };
        }
        else
            guard = stopGuard;

        if (self.current)
            node = this.current[ getSourceNodeFn ](FALSE, type, guard);
        else {
            // Get the first node to be returned.

            if (rtl) {
                node = range.endContainer;

                if (range.endOffset > 0) {
                    node = new Node(node[0].childNodes[range.endOffset - 1]);
                    if (guard(node) === FALSE)
                        node = NULL;
                }
                else
                    node = ( guard(node, TRUE) === FALSE ) ?
                        NULL : node._4e_previousSourceNode(TRUE, type, guard);
            }
            else {
                node = range.startContainer;
                node = new Node(node[0].childNodes[range.startOffset]);

                if (node && node[0]) {
                    if (guard(node) === FALSE)
                        node = NULL;
                }
                else
                    node = ( guard(range.startContainer, TRUE) === FALSE ) ?
                        NULL : range.startContainer._4e_nextSourceNode(TRUE, type, guard);
            }
        }

        while (node && node[0] && !self._.end) {
            self.current = node;

            if (!this.evaluator || self.evaluator(node) !== FALSE) {
                if (!breakOnFalse)
                    return node;
            }
            else if (breakOnFalse && self.evaluator)
                return FALSE;

            node = node[ getSourceNodeFn ](FALSE, type, guard);
        }

        self.end();
        return self.current = NULL;
    }

    /**
     * @this {Walker}
     * @param  {boolean=} rtl
     * @return {(KISSY.Node|boolean)}
     */
    function iterateToLast(rtl) {
        var node, last = NULL;

        while (( node = iterate.call(this, rtl) ))
            last = node;

        return last;
    }

    /**
     * @constructor
     */
    function Walker(range) {
        this.range = range;

        /**
         * A function executed for every matched node, to check whether
         * it's to be considered into the walk or not. If not provided, all
         * matched nodes are considered good.
         * If the function returns "FALSE" the node is ignored.
         * @name CKEDITOR.dom.walker.prototype.evaluator
         * @property
         * @type Function
         */
        // this.evaluator = NULL;

        /**
         * A function executed for every node the walk pass by to check
         * whether the walk is to be finished. It's called when both
         * entering and exiting nodes, as well as for the matched nodes.
         * If this function returns "FALSE", the walking ends and no more
         * nodes are evaluated.
         * @name CKEDITOR.dom.walker.prototype.guard
         * @property
         * @type Function
         */
        // this.guard = NULL;

        /** @private */
        this._ = {};
    }


    S.augment(Walker, {
        /**
         * Stop walking. No more nodes are retrieved if this function gets
         * called.
         */
        end : function() {
            this._.end = 1;
        },

        /**
         * Retrieves the next node (at right).
         * @returns {(KISSY.Node|boolean)} The next node or NULL if no more
         *        nodes are available.
         */
        next : function() {
            return iterate.call(this);
        },

        /**
         * Retrieves the previous node (at left).
         * @returns {(KISSY.Node|boolean)} The previous node or NULL if no more
         *        nodes are available.
         */
        previous : function() {
            return iterate.call(this, TRUE);
        },

        /**
         * Check all nodes at right, executing the evaluation fuction.
         * @returns {boolean} "FALSE" if the evaluator function returned
         *        "FALSE" for any of the matched nodes. Otherwise "TRUE".
         */
        checkForward : function() {
            return iterate.call(this, FALSE, TRUE) !== FALSE;
        },

        /**
         * Check all nodes at left, executing the evaluation fuction.
         * 是不是 (不能后退了)
         * @returns {boolean} "FALSE" if the evaluator function returned
         *        "FALSE" for any of the matched nodes. Otherwise "TRUE".
         */
        checkBackward : function() {
            return iterate.call(this, TRUE, TRUE) !== FALSE;
        },

        /**
         * Executes a full walk forward (to the right), until no more nodes
         * are available, returning the last valid node.
         * @returns {(KISSY.Node|boolean)} The last node at the right or NULL
         *        if no valid nodes are available.
         */
        lastForward : function() {
            return iterateToLast.call(this);
        },

        /**
         * Executes a full walk backwards (to the left), until no more nodes
         * are available, returning the last valid node.
         * @returns {(KISSY.Node|boolean)} The last node at the left or NULL
         *        if no valid nodes are available.
         */
        lastBackward : function() {
            return iterateToLast.call(this, TRUE);
        },

        reset : function() {
            delete this.current;
            this._ = {};
        }

    });


    Walker.blockBoundary = function(customNodeNames) {
        return function(node) {
            node = DOM._4e_wrap(node);
            return ! ( node && node[0].nodeType == KEN.NODE_ELEMENT
                && node._4e_isBlockBoundary(customNodeNames) );
        };
    };

    Walker.listItemBoundary = function() {
        return this.blockBoundary({ br : 1 });
    };
    /**
     * Whether the node is a bookmark node's inner text node.
     */
    //Walker.bookmarkContents = function(node) {
    // },

    /**
     * Whether the to-be-evaluated node is a bookmark node OR bookmark node
     * inner contents.
     * @param {boolean} contentOnly Whether only test againt the text content of
     * bookmark node instead of the element itself(default).
     * @param {boolean} isReject Whether should return 'FALSE' for the bookmark
     * node instead of 'TRUE'(default).
     */
    Walker.bookmark = function(contentOnly, isReject) {
        function isBookmarkNode(node) {
            return ( node && node[0]
                && node._4e_name() == 'span'
                && node.attr('_ke_bookmark') );
        }

        return function(node) {
            var isBookmark, parent;
            // Is bookmark inner text node?
            isBookmark = ( node &&
                node[0] &&
                node[0].nodeType == KEN.NODE_TEXT &&
                ( parent = node.parent() )
                && isBookmarkNode(parent) );
            // Is bookmark node?
            isBookmark = contentOnly ? isBookmark : isBookmark || isBookmarkNode(node);
            return isReject ^ isBookmark;
        };
    };

    /**
     * Whether the node is a text node containing only whitespaces characters.
     * @param {boolean=} isReject
     */
    Walker.whitespaces = function(isReject) {
        return function(node) {
            node = node[0] || node;
            var isWhitespace = node && ( node.nodeType == KEN.NODE_TEXT )
                && !S.trim(node.nodeValue);
            return isReject ^ isWhitespace;
        };
    };

    /**
     * Whether the node is invisible in wysiwyg mode.
     * @param isReject
     */
    Walker.invisible = function(isReject) {
        var whitespace = Walker.whitespaces();
        return function(node) {
            // Nodes that take no spaces in wysiwyg:
            // 1. White-spaces but not including NBSP;
            // 2. Empty inline elements, e.g. <b></b> we're checking here
            // 'offsetHeight' instead of 'offsetWidth' for properly excluding
            // all sorts of empty paragraph, e.g. <br />.
            var isInvisible = whitespace(node) || node[0].nodeType == KEN.NODE_ELEMENT && !node[0].offsetHeight;
            return isReject ^ isInvisible;
        };
    };


    KE.Walker = Walker;
    KE["Walker"] = Walker;
    var WalkP = Walker.prototype;
    KE.Utils.extern(WalkP, {
        "end":WalkP.end,
        "next":WalkP.next,
        "previous":WalkP.previous,
        "checkForward":WalkP.checkForward,
        "checkBackward":WalkP.checkBackward,
        "lastForward":WalkP.lastForward,
        "lastBackward":WalkP.lastBackward,
        "reset":WalkP.reset
    });


    KE.Utils.extern(Walker, {
        "blockBoundary":Walker.blockBoundary,
        "listItemBoundary":Walker.listItemBoundary,
        "bookmark":Walker.bookmark,
        "whitespaces":Walker.whitespaces,
        "invisible":Walker.invisible
    });
});
/**
 * modified from ckeditor,range implementation across browsers for kissy editor
 * @author: <yiminghe@gmail.com>
 */
/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
KISSY.Editor.add("range", function(KE) {

    /**
     * Enum for range
     * @enum {number}
     */
    KE.RANGE = {
        POSITION_AFTER_START:1,// <element>^contents</element>		"^text"
        POSITION_BEFORE_END:2,// <element>contents^</element>		"text^"
        POSITION_BEFORE_START:3,// ^<element>contents</element>		^"text"
        POSITION_AFTER_END:4,// <element>contents</element>^		"text"
        ENLARGE_ELEMENT:1,
        ENLARGE_BLOCK_CONTENTS:2,
        ENLARGE_LIST_ITEM_CONTENTS:3,
        START:1,
        END:2,
        STARTEND:3,
        SHRINK_ELEMENT:1,
        SHRINK_TEXT:2
    };
    KE["RANGE"] = KE.RANGE;

    var TRUE = true,
        FALSE = false,
        NULL = null,
        S = KISSY,
        KEN = KE.NODE,
        KER = KE.RANGE,
        KEP = KE.POSITION,
        Walker = KE.Walker,
        DOM = S.DOM,
        getByAddress = KE.Utils.getByAddress,
        UA = S.UA,
        dtd = KE.XHTML_DTD,
        ElementPath = KE.ElementPath,
        Node = S.Node,
        EMPTY = {"area":1,"base":1,"br":1,"col":1,"hr":1,"img":1,"input":1,"link":1,"meta":1,"param":1};

    /**
     * @constructor
     * @param document {Document}
     */
    function KERange(document) {
        var self = this;
        self.startContainer = NULL;
        self.startOffset = NULL;
        self.endContainer = NULL;
        self.endOffset = NULL;
        self.collapsed = TRUE;
        self.document = document;
    }

    KERange.prototype.toString = function() {
        var s = [],self = this;
        s.push((self.startContainer[0].id || self.startContainer[0].nodeName) + ":" + self.startOffset);
        s.push((self.endContainer[0].id || self.endContainer[0].nodeName) + ":" + self.endOffset);
        return s.join("<br/>");
    };
    S.augment(KERange, {

        updateCollapsed:function() {
            var self = this;
            self.collapsed = (
                self.startContainer &&
                    self.endContainer &&
                    DOM._4e_equals(self.startContainer, self.endContainer) &&
                    self.startOffset == self.endOffset );
        },
        /**
         * Transforms the startContainer and endContainer properties from text
         * nodes to element nodes, whenever possible. This is actually possible
         * if either of the boundary containers point to a text node, and its
         * offset is set to zero, or after the last char in the node.
         */
        optimize : function() {
            var self = this,container = self.startContainer,offset = self.startOffset;

            if (container[0].nodeType != KEN.NODE_ELEMENT) {
                if (!offset)
                    self.setStartBefore(container);
                else if (offset >= container[0].nodeValue.length)
                    self.setStartAfter(container);
            }

            container = self.endContainer;
            offset = self.endOffset;

            if (container[0].nodeType != KEN.NODE_ELEMENT) {
                if (!offset)
                    self.setEndBefore(container);
                else if (offset >= container[0].nodeValue.length)
                    self.setEndAfter(container);
            }
        },
        setStartAfter : function(node) {
            this.setStart(node.parent(), node._4e_index() + 1);
        },

        setStartBefore : function(node) {
            this.setStart(node.parent(), node._4e_index());
        },

        setEndAfter : function(node) {
            this.setEnd(node.parent(), node._4e_index() + 1);
        },

        setEndBefore : function(node) {
            this.setEnd(node.parent(), node._4e_index());
        },
        optimizeBookmark: function() {
            var self = this,startNode = self.startContainer,
                endNode = self.endContainer;

            if (startNode && startNode._4e_name() == 'span'
                && startNode.attr('_ke_bookmark'))
                self.setStartAt(startNode, KER.POSITION_BEFORE_START);
            if (endNode && endNode._4e_name() == 'span'
                && endNode.attr('_ke_bookmark'))
                self.setEndAt(endNode, KER.POSITION_AFTER_END);
        },
        /**
         * Sets the start position of a Range.
         * @param {Node} startNode The node to start the range.
         * @param {Number} startOffset An integer greater than or equal to zero
         *        representing the offset for the start of the range from the start
         *        of startNode.
         */
        setStart : function(startNode, startOffset) {
            // W3C requires a check for the new position. If it is after the end
            // boundary, the range should be collapsed to the new start. It seams
            // we will not need this check for our use of this class so we can
            // ignore it for now.

            // Fixing invalid range start inside dtd empty elements.
            var self = this;
            if (startNode[0].nodeType == KEN.NODE_ELEMENT
                && EMPTY[ startNode._4e_name() ])
                startNode = startNode.parent(),startOffset = startNode._4e_index();

            self.startContainer = startNode;
            self.startOffset = startOffset;

            if (!self.endContainer) {
                self.endContainer = startNode;
                self.endOffset = startOffset;
            }

            self.updateCollapsed();
        },

        /**
         * Sets the end position of a Range.
         * @param {Node} endNode The node to end the range.
         * @param {Number} endOffset An integer greater than or equal to zero
         *        representing the offset for the end of the range from the start
         *        of endNode.
         */
        setEnd : function(endNode, endOffset) {
            // W3C requires a check for the new position. If it is before the start
            // boundary, the range should be collapsed to the new end. It seams we
            // will not need this check for our use of this class so we can ignore
            // it for now.

            // Fixing invalid range end inside dtd empty elements.
            var self = this;
            if (endNode[0].nodeType == KEN.NODE_ELEMENT
                && EMPTY[ endNode._4e_name() ])
                endNode = endNode.parent(),endOffset = endNode._4e_index() + 1;

            self.endContainer = endNode;
            self.endOffset = endOffset;

            if (!self.startContainer) {
                self.startContainer = endNode;
                self.startOffset = endOffset;
            }

            self.updateCollapsed();
        },
        setStartAt : function(node, position) {
            var self = this;
            switch (position) {
                case KER.POSITION_AFTER_START :
                    self.setStart(node, 0);
                    break;

                case KER.POSITION_BEFORE_END :
                    if (node[0].nodeType == KEN.NODE_TEXT)
                        self.setStart(node, node[0].nodeValue.length);
                    else
                        self.setStart(node, node[0].childNodes.length);
                    break;

                case KER.POSITION_BEFORE_START :
                    self.setStartBefore(node);
                    break;

                case KER.POSITION_AFTER_END :
                    self.setStartAfter(node);
            }

            self.updateCollapsed();
        },

        setEndAt : function(node, position) {
            var self = this;
            switch (position) {
                case KER.POSITION_AFTER_START :
                    self.setEnd(node, 0);
                    break;

                case KER.POSITION_BEFORE_END :
                    if (node[0].nodeType == KEN.NODE_TEXT)
                        self.setEnd(node, node[0].nodeValue.length);
                    else
                        self.setEnd(node, node[0].childNodes.length);
                    break;

                case KER.POSITION_BEFORE_START :
                    self.setEndBefore(node);
                    break;

                case KER.POSITION_AFTER_END :
                    self.setEndAfter(node);
            }

            self.updateCollapsed();
        },
        execContentsAction:    function(action, docFrag) {
            var self = this,
                startNode = self.startContainer,
                endNode = self.endContainer,
                startOffset = self.startOffset,
                endOffset = self.endOffset,
                removeStartNode,
                t,
                doc = self.document,
                removeEndNode;
            self.optimizeBookmark();
            // For text containers, we must simply split the node and point to the
            // second part. The removal will be handled by the rest of the code .
            if (endNode[0].nodeType == KEN.NODE_TEXT)
                endNode = endNode._4e_splitText(endOffset);
            else {
                // If the end container has children and the offset is pointing
                // to a child, then we should start from it.
                if (endNode[0].childNodes.length > 0) {
                    // If the offset points after the last node.
                    if (endOffset >= endNode[0].childNodes.length) {
                        // Let's create a temporary node and mark it for removal.
                        endNode = new Node(
                            endNode[0].appendChild(doc.createTextNode(""))
                            );
                        removeEndNode = TRUE;
                    }
                    else
                        endNode = new Node(endNode[0].childNodes[endOffset]);
                }
            }

            // For text containers, we must simply split the node. The removal will
            // be handled by the rest of the code .
            if (startNode[0].nodeType == KEN.NODE_TEXT) {
                startNode._4e_splitText(startOffset);
                // In cases the end node is the same as the start node, the above
                // splitting will also split the end, so me must move the end to
                // the second part of the split.
                if (startNode._4e_equals(endNode))
                    endNode = new Node(startNode[0].nextSibling);
            }
            else {
                // If the start container has children and the offset is pointing
                // to a child, then we should start from its previous sibling.

                // If the offset points to the first node, we don't have a
                // sibling, so let's use the first one, but mark it for removal.
                if (!startOffset) {
                    // Let's create a temporary node and mark it for removal.
                    t = new Node(doc.createTextNode(""));
                    var sf = startNode[0].firstChild;
                    if (sf)
                        DOM.insertBefore(t[0], sf);
                    else
                        startNode.append(t);
                    startNode = t;
                    removeStartNode = TRUE;
                }
                else if (startOffset >= startNode[0].childNodes.length) {
                    // Let's create a temporary node and mark it for removal.
                    //startNode = startNode[0].appendChild(self.document.createTextNode(''));
                    t = new Node(doc.createTextNode(""));
                    startNode.append(t);
                    startNode = t;
                    removeStartNode = TRUE;
                } else
                    startNode = new Node(
                        startNode[0].childNodes[startOffset].previousSibling
                        );
            }

            // Get the parent nodes tree for the start and end boundaries.
            //从根到自己
            var startParents = startNode._4e_parents(),
                endParents = endNode._4e_parents();

            // Compare them, to find the top most siblings.
            var i, topStart, topEnd;

            for (i = 0; i < startParents.length; i++) {
                topStart = startParents[ i ];
                topEnd = endParents[ i ];

                // The compared nodes will match until we find the top most
                // siblings (different nodes that have the same parent).
                // "i" will hold the index in the parents array for the top
                // most element.
                if (!topStart._4e_equals(topEnd))
                    break;
            }

            var clone = docFrag, levelStartNode, levelClone, currentNode, currentSibling;

            // Remove all successive sibling nodes for every node in the
            // startParents tree.
            for (var j = i; j < startParents.length; j++) {
                levelStartNode = startParents[j];

                // For Extract and Clone, we must clone this level.
                if (
                    clone
                        &&
                        !levelStartNode._4e_equals(startNode)
                    )        // action = 0 = Delete
                    levelClone = clone.appendChild(levelStartNode._4e_clone()[0]);

                currentNode = levelStartNode[0].nextSibling;

                while (currentNode) {
                    // Stop processing when the current node matches a node in the
                    // endParents tree or if it is the endNode.
                    if (DOM._4e_equals(endParents[ j ], currentNode)
                        ||
                        DOM._4e_equals(endNode, currentNode))
                        break;

                    // Cache the next sibling.
                    currentSibling = currentNode.nextSibling;

                    // If cloning, just clone it.
                    if (action == 2)    // 2 = Clone
                        clone.appendChild(currentNode.cloneNode(TRUE));
                    else {
                        // Both Delete and Extract will remove the node.
                        currentNode.parentNode.removeChild(currentNode);

                        // When Extracting, move the removed node to the docFrag.
                        if (action == 1)    // 1 = Extract
                            clone.appendChild(currentNode);
                    }

                    currentNode = currentSibling;
                }
                //ckeditor这里错了，当前节点的路径所在父节点不能clone(TRUE)，要在后面深入子节点处理
                if (levelClone)
                    clone = levelClone;
            }

            clone = docFrag;

            // Remove all previous sibling nodes for every node in the
            // endParents tree.
            for (var k = i; k < endParents.length; k++) {
                levelStartNode = endParents[ k ];

                // For Extract and Clone, we must clone this level.
                if (
                    action > 0
                        &&
                        !levelStartNode._4e_equals(endNode)
                    )        // action = 0 = Delete
                    levelClone = clone.appendChild(levelStartNode._4e_clone()[0]);

                // The processing of siblings may have already been done by the parent.
                if (
                    !startParents[ k ]
                        ||
                        !levelStartNode.parent()._4e_equals(startParents[ k ].parent())
                    ) {
                    currentNode = levelStartNode[0].previousSibling;
                    while (currentNode) {
                        // Stop processing when the current node matches a node in the
                        // startParents tree or if it is the startNode.
                        if (DOM._4e_equals(startParents[ k ], currentNode)
                            ||
                            DOM._4e_equals(startNode, currentNode))
                            break;

                        // Cache the next sibling.
                        currentSibling = currentNode.previousSibling;

                        // If cloning, just clone it.
                        if (action == 2) {    // 2 = Clone
                            clone.insertBefore(currentNode.cloneNode(TRUE),
                                clone.firstChild);
                        } else {
                            // Both Delete and Extract will remove the node.
                            DOM._4e_remove(currentNode);

                            // When Extracting, mode the removed node to the docFrag.
                            if (action == 1)    // 1 = Extract
                                clone.insertBefore(currentNode, clone.firstChild);
                        }

                        currentNode = currentSibling;
                    }
                }

                if (levelClone)
                    clone = levelClone;
            }

            if (action == 2) {   // 2 = Clone.

                // No changes in the DOM should be done, so fix the split text (if any).

                var startTextNode = self.startContainer[0];
                if (startTextNode.nodeType == KEN.NODE_TEXT
                    && startTextNode.nextSibling
                    //yiminghe note:careful,nextsilbling should be text node
                    && startTextNode.nextSibling.nodeType == KEN.NODE_TEXT) {
                    startTextNode.data += startTextNode.nextSibling.data;
                    startTextNode.parentNode.removeChild(startTextNode.nextSibling);
                }

                var endTextNode = self.endContainer[0];
                if (endTextNode.nodeType == KEN.NODE_TEXT &&
                    endTextNode.nextSibling &&
                    endTextNode.nextSibling.nodeType == KEN.NODE_TEXT) {
                    endTextNode.data += endTextNode.nextSibling.data;
                    endTextNode.parentNode.removeChild(endTextNode.nextSibling);
                }
            }
            else {
                // Collapse the range.

                // If a node has been partially selected, collapse the range between
                // topStart and topEnd. Otherwise, simply collapse it to the start. (W3C specs).
                if (
                    topStart && topEnd
                        &&
                        (
                            !startNode.parent()._4e_equals(topStart.parent())
                                ||
                                !endNode.parent()._4e_equals(topEnd.parent())
                            )
                    ) {
                    var endIndex = topEnd._4e_index();

                    // If the start node is to be removed, we must correct the
                    // index to reflect the removal.
                    if (removeStartNode &&
                        topEnd.parent()._4e_equals(startNode.parent()))
                        endIndex--;

                    self.setStart(topEnd.parent(), endIndex);
                }

                // Collapse it to the start.
                self.collapse(TRUE);
            }

            // Cleanup any marked node.
            if (removeStartNode)
                startNode._4e_remove();

            if (removeEndNode && endNode[0].parentNode)
            //不能使用remove()
                endNode._4e_remove();
        },

        collapse : function(toStart) {
            var self = this;
            if (toStart) {
                self.endContainer = self.startContainer;
                self.endOffset = self.startOffset;
            } else {
                self.startContainer = self.endContainer;
                self.startOffset = self.endOffset;
            }
            self.collapsed = TRUE;
        },

        clone : function() {
            var self = this,
                clone = new KERange(self.document);

            clone.startContainer = self.startContainer;
            clone.startOffset = self.startOffset;
            clone.endContainer = self.endContainer;
            clone.endOffset = self.endOffset;
            clone.collapsed = self.collapsed;

            return clone;
        },
        getEnclosedNode : function() {
            var walkerRange = this.clone();
            // Optimize and analyze the range to avoid DOM destructive nature of walker.
            walkerRange.optimize();
            if (walkerRange.startContainer[0].nodeType != KEN.NODE_ELEMENT
                || walkerRange.endContainer[0].nodeType != KEN.NODE_ELEMENT)
                return NULL;
            //var current = walkerRange.startContainer[0].childNodes[walkerRange.startOffset];
            var walker = new KE.Walker(walkerRange),
                isNotBookmarks = bookmark(TRUE, undefined),
                isNotWhitespaces = whitespaces(TRUE),node,pre;
            walkerRange.evaluator = function(node) {
                return isNotWhitespaces(node) && isNotBookmarks(node);
            };

            //深度优先遍历的第一个元素
            //        x
            //     y     z
            // x->y ,return y
            node = walker.next();
            walker.reset();
            pre = walker.previous();
            //前后相等，则脱一层皮 :)
            return node && node._4e_equals(pre) ? node : NULL;
        },
        shrink : function(mode, selectContents) {
            // Unable to shrink a collapsed range.
            var self = this;
            if (!self.collapsed) {
                mode = mode || KER.SHRINK_TEXT;

                var walkerRange = self.clone(),
                    startContainer = self.startContainer,
                    endContainer = self.endContainer,
                    startOffset = self.startOffset,
                    endOffset = self.endOffset;
                //collapsed = self.collapsed;

                // Whether the start/end boundary is moveable.
                var moveStart = 1,
                    moveEnd = 1;

                if (startContainer && startContainer[0].nodeType == KEN.NODE_TEXT) {
                    if (!startOffset)
                        walkerRange.setStartBefore(startContainer);
                    else if (startOffset >= startContainer[0].nodeValue.length)
                        walkerRange.setStartAfter(startContainer);
                    else {
                        // Enlarge the range properly to avoid walker making
                        // DOM changes caused by triming the text nodes later.
                        walkerRange.setStartBefore(startContainer);
                        moveStart = 0;
                    }
                }

                if (endContainer && endContainer[0].nodeType == KEN.NODE_TEXT) {
                    if (!endOffset)
                        walkerRange.setEndBefore(endContainer);
                    else if (endOffset >= endContainer[0].nodeValue.length)
                        walkerRange.setEndAfter(endContainer);
                    else {
                        walkerRange.setEndAfter(endContainer);
                        moveEnd = 0;
                    }
                }

                var walker = new Walker(walkerRange);

                walker.evaluator = function(node) {
                    node = node[0] || node;
                    return node.nodeType == ( mode == KER.SHRINK_ELEMENT ?
                        KEN.NODE_ELEMENT : KEN.NODE_TEXT );
                };

                var currentElement;
                walker.guard = function(node, movingOut) {

                    node = node[0] || node;
                    // Stop when we're shrink in element mode while encountering a text node.
                    if (mode == KER.SHRINK_ELEMENT && node.nodeType == KEN.NODE_TEXT)
                        return FALSE;

                    // Stop when we've already walked "through" an element.
                    if (movingOut && node == currentElement)
                        return FALSE;

                    if (!movingOut && node.nodeType == KEN.NODE_ELEMENT)
                        currentElement = node;

                    return TRUE;
                };

                if (moveStart) {
                    var textStart = walker[ mode == KER.SHRINK_ELEMENT ? 'lastForward' : 'next']();
                    textStart && self.setStartAt(textStart, selectContents ? KER.POSITION_AFTER_START : KER.POSITION_BEFORE_START);
                }

                if (moveEnd) {
                    walker.reset();
                    var textEnd = walker[ mode == KER.SHRINK_ELEMENT ? 'lastBackward' : 'previous']();
                    textEnd && self.setEndAt(textEnd, selectContents ? KER.POSITION_BEFORE_END : KER.POSITION_AFTER_END);
                }

                return !!( moveStart || moveEnd );
            }
        },
        getTouchedStartNode : function() {
            var self = this,container = self.startContainer;

            if (self.collapsed || container[0].nodeType != KEN.NODE_ELEMENT)
                return container;

            return container.childNodes[self.startOffset] || container;
        },
        createBookmark2 : function(normalized) {
            //debugger;
            var self = this,startContainer = self.startContainer,
                endContainer = self.endContainer,
                startOffset = self.startOffset,
                endOffset = self.endOffset,
                child, previous;

            // If there is no range then get out of here.
            // It happens on initial load in Safari #962 and if the editor it's
            // hidden also in Firefox
            if (!startContainer || !endContainer)
                return { start : 0, end : 0 };

            if (normalized) {
                // Find out if the start is pointing to a text node that will
                // be normalized.
                if (startContainer[0].nodeType == KEN.NODE_ELEMENT) {
                    child = new Node(startContainer[0].childNodes[startOffset]);

                    // In this case, move the start information to that text
                    // node.
                    if (child && child[0] && child[0].nodeType == KEN.NODE_TEXT
                        && startOffset > 0 && child[0].previousSibling.nodeType == KEN.NODE_TEXT) {
                        startContainer = child;
                        startOffset = 0;
                    }
                }

                // Normalize the start.
                while (startContainer[0].nodeType == KEN.NODE_TEXT
                    && ( previous = startContainer._4e_previous() )
                    && previous[0].nodeType == KEN.NODE_TEXT) {
                    startContainer = previous;
                    startOffset += previous[0].nodeValue.length;
                }

                // Process the end only if not normalized.
                if (!self.collapsed) {
                    // Find out if the start is pointing to a text node that
                    // will be normalized.
                    if (endContainer[0].nodeType == KEN.NODE_ELEMENT) {
                        child = new Node(endContainer[0].childNodes[endOffset]);

                        // In this case, move the start information to that
                        // text node.
                        if (child && child[0] && child[0].nodeType == KEN.NODE_TEXT
                            && endOffset > 0 && child[0].previousSibling.nodeType == KEN.NODE_TEXT) {
                            endContainer = child;
                            endOffset = 0;
                        }
                    }

                    // Normalize the end.
                    while (endContainer[0].nodeType == KEN.NODE_TEXT
                        && ( previous = endContainer._4e_previous() )
                        && previous[0].nodeType == KEN.NODE_TEXT) {
                        endContainer = previous;
                        endOffset += previous[0].nodeValue.length;
                    }
                }
            }

            return {
                start        : startContainer._4e_address(normalized),
                end            : self.collapsed ? NULL : endContainer._4e_address(normalized),
                startOffset    : startOffset,
                endOffset    : endOffset,
                normalized    : normalized,
                is2            : TRUE        // It's a createBookmark2 bookmark.
            };
        },
        createBookmark : function(serializable) {
            var startNode,
                endNode,
                baseId,
                clone,
                self = this,
                collapsed = self.collapsed;
            startNode = new Node("<span>", NULL, self.document);
            startNode.attr('_ke_bookmark', 1);
            startNode.css('display', 'none');

            // For IE, it must have something inside, otherwise it may be
            // removed during DOM operations.
            startNode.html('&nbsp;');

            if (serializable) {
                baseId = S.guid('ke_bm_');
                startNode.attr('id', baseId + 'S');
            }

            // If collapsed, the endNode will not be created.
            if (!collapsed) {
                endNode = startNode._4e_clone();
                endNode.html('&nbsp;');

                if (serializable)
                    endNode.attr('id', baseId + 'E');

                clone = self.clone();
                clone.collapse();
                //S.log(clone.endContainer[0].nodeType);
                //S.log(clone.endOffset);
                clone.insertNode(endNode);
            }
            //S.log(endNode[0].parentNode.outerHTML);
            clone = self.clone();
            clone.collapse(TRUE);
            clone.insertNode(startNode);

            // Update the range position.
            if (endNode) {
                self.setStartAfter(startNode);
                self.setEndBefore(endNode);
            }
            else
                self.moveToPosition(startNode, KER.POSITION_AFTER_END);

            return {
                startNode : serializable ? baseId + 'S' : startNode,
                endNode : serializable ? baseId + 'E' : endNode,
                serializable : serializable,
                collapsed:collapsed
            };
        },
        moveToPosition : function(node, position) {
            var self = this;
            self.setStartAt(node, position);
            self.collapse(TRUE);
        },
        trim : function(ignoreStart, ignoreEnd) {
            var self = this,
                startContainer = self.startContainer,
                startOffset = self.startOffset,
                collapsed = self.collapsed;
            if (( !ignoreStart || collapsed )
                && startContainer[0] && startContainer[0].nodeType == KEN.NODE_TEXT) {
                // If the offset is zero, we just insert the new node before
                // the start.
                if (!startOffset) {
                    startOffset = startContainer._4e_index();
                    startContainer = startContainer.parent();
                }
                // If the offset is at the end, we'll insert it after the text
                // node.
                else if (startOffset >= startContainer[0].nodeValue.length) {
                    startOffset = startContainer._4e_index() + 1;
                    startContainer = startContainer.parent();
                }
                // In other case, we split the text node and insert the new
                // node at the split point.
                else {
                    var nextText = startContainer._4e_splitText(startOffset);

                    startOffset = startContainer._4e_index() + 1;
                    startContainer = startContainer.parent();

                    // Check all necessity of updating the end boundary.
                    if (DOM._4e_equals(self.startContainer, self.endContainer))
                        self.setEnd(nextText, self.endOffset - self.startOffset);
                    else if (DOM._4e_equals(startContainer, self.endContainer))
                        self.endOffset += 1;
                }

                self.setStart(startContainer, startOffset);

                if (collapsed) {
                    self.collapse(TRUE);
                    return;
                }
            }

            var endContainer = self.endContainer,endOffset = self.endOffset;

            if (!( ignoreEnd || collapsed )
                && endContainer[0] && endContainer[0].nodeType == KEN.NODE_TEXT) {
                // If the offset is zero, we just insert the new node before
                // the start.
                if (!endOffset) {
                    endOffset = endContainer._4e_index();
                    endContainer = endContainer.parent();
                }
                // If the offset is at the end, we'll insert it after the text
                // node.
                else if (endOffset >= endContainer.nodeValue.length) {
                    endOffset = endContainer._4e_index() + 1;
                    endContainer = endContainer.parent();
                }
                // In other case, we split the text node and insert the new
                // node at the split point.
                else {
                    endContainer._4e_splitText(endOffset);

                    endOffset = endContainer._4e_index() + 1;
                    endContainer = endContainer.parent();
                }

                self.setEnd(endContainer, endOffset);
            }
        },

        insertNode : function(node) {
            var self = this;
            self.optimizeBookmark();
            self.trim(FALSE, TRUE);
            var startContainer = self.startContainer,
                startOffset = self.startOffset,
                nextNode = startContainer[0].childNodes[startOffset];

            if (nextNode) {
                DOM.insertBefore(node[0] || node, nextNode);
            } else
                startContainer[0].appendChild(node[0] || node);

            // Check if we need to update the end boundary.
            if (DOM._4e_equals(node.parent(), self.endContainer))
                self.endOffset++;

            // Expand the range to embrace the new node.
            self.setStartBefore(node);
        },

        moveToBookmark : function(bookmark) {
            // Created with createBookmark2().
            var self = this;
            if (bookmark.is2) {
                // Get the start information.
                var startContainer = getByAddress(self.document, bookmark.start, bookmark.normalized),
                    startOffset = bookmark.startOffset,
                    endContainer = bookmark.end && getByAddress(self.document, bookmark.end, bookmark.normalized),
                    endOffset = bookmark.endOffset;

                // Set the start boundary.
                self.setStart(startContainer, startOffset);

                // Set the end boundary. If not available, collapse it.
                if (endContainer)
                    self.setEnd(endContainer, endOffset);
                else
                    self.collapse(TRUE);
            } else {
                // Created with createBookmark().
                var serializable = bookmark.serializable,
                    startNode = serializable ? S.one("#" + bookmark.startNode, self.document) : bookmark.startNode,
                    endNode = serializable ? S.one("#" + bookmark.endNode, self.document) : bookmark.endNode;

                // Set the range start at the bookmark start node position.
                self.setStartBefore(startNode);

                // Remove it, because it may interfere in the setEndBefore call.
                startNode._4e_remove();

                // Set the range end at the bookmark end node position, or simply
                // collapse it if it is not available.
                if (endNode && endNode[0]) {
                    self.setEndBefore(endNode);
                    endNode._4e_remove();
                }
                else
                    self.collapse(TRUE);
            }
        },
        getCommonAncestor : function(includeSelf, ignoreTextNode) {
            var self = this,start = self.startContainer,
                end = self.endContainer,
                ancestor;

            if (DOM._4e_equals(start, end)) {
                if (includeSelf
                    && start[0].nodeType == KEN.NODE_ELEMENT
                    && self.startOffset == self.endOffset - 1)
                    ancestor = new Node(start[0].childNodes[self.startOffset]);
                else
                    ancestor = start;
            }
            else
                ancestor = start._4e_commonAncestor(end);

            return ignoreTextNode && ancestor[0].nodeType == KEN.NODE_TEXT
                ? ancestor.parent() : ancestor;
        },
        enlarge : function(unit) {
            var self = this;
            switch (unit) {
                case KER.ENLARGE_ELEMENT :

                    if (self.collapsed)
                        return;

                    // Get the common ancestor.
                    var commonAncestor = self.getCommonAncestor(), body = new Node(self.document.body),
                        // For each boundary
                        //		a. Depending on its position, find out the first node to be checked (a sibling) or, if not available, to be enlarge.
                        //		b. Go ahead checking siblings and enlarging the boundary as much as possible until the common ancestor is not reached. After reaching the common ancestor, just save the enlargeable node to be used later.

                        startTop, endTop,
                        enlargeable, sibling, commonReached,

                        // Indicates that the node can be added only if whitespace
                        // is available before it.
                        needsWhiteSpace = FALSE, isWhiteSpace, siblingText,

                        // Process the start boundary.

                        container = self.startContainer,
                        offset = self.startOffset;

                    if (container[0].nodeType == KEN.NODE_TEXT) {
                        if (offset) {
                            // Check if there is any non-space text before the
                            // offset. Otherwise, container is NULL.
                            container = !S.trim(container[0].nodeValue.substring(0, offset)).length && container;

                            // If we found only whitespace in the node, it
                            // means that we'll need more whitespace to be able
                            // to expand. For example, <i> can be expanded in
                            // "A <i> [B]</i>", but not in "A<i> [B]</i>".
                            needsWhiteSpace = !!container;
                        }

                        if (container) {
                            if (!( sibling = container[0].previousSibling ))
                                enlargeable = container.parent();
                        }
                    }
                    else {
                        // If we have offset, get the node preceeding it as the
                        // first sibling to be checked.
                        if (offset)
                            sibling = container[0].childNodes[offset - 1] || container[0].lastChild;

                        // If there is no sibling, mark the container to be
                        // enlarged.
                        if (!sibling)
                            enlargeable = container;
                    }

                    while (enlargeable || sibling) {
                        if (enlargeable && !sibling) {
                            // If we reached the common ancestor, mark the flag
                            // for it.
                            if (!commonReached && DOM._4e_equals(enlargeable, commonAncestor))
                                commonReached = TRUE;

                            if (!body._4e_contains(enlargeable))
                                break;

                            // If we don't need space or this element breaks
                            // the line, then enlarge it.
                            if (!needsWhiteSpace || enlargeable.css('display') != 'inline') {
                                needsWhiteSpace = FALSE;

                                // If the common ancestor has been reached,
                                // we'll not enlarge it immediately, but just
                                // mark it to be enlarged later if the end
                                // boundary also enlarges it.
                                if (commonReached)
                                    startTop = enlargeable;
                                else
                                    self.setStartBefore(enlargeable);
                            }

                            sibling = enlargeable[0].previousSibling;
                        }

                        // Check all sibling nodes preceeding the enlargeable
                        // node. The node wil lbe enlarged only if none of them
                        // blocks it.
                        while (sibling) {
                            // This flag indicates that this node has
                            // whitespaces at the end.
                            isWhiteSpace = FALSE;

                            if (sibling.nodeType == KEN.NODE_TEXT) {
                                siblingText = sibling.nodeValue;

                                if (/[^\s\ufeff]/.test(siblingText))
                                    sibling = NULL;

                                isWhiteSpace = /[\s\ufeff]$/.test(siblingText);
                            }
                            else {
                                // If this is a visible element.
                                // We need to check for the bookmark attribute because IE insists on
                                // rendering the display:none nodes we use for bookmarks. (#3363)
                                if (sibling.offsetWidth > 0 && !sibling.getAttribute('_ke_bookmark')) {
                                    // We'll accept it only if we need
                                    // whitespace, and this is an inline
                                    // element with whitespace only.
                                    if (needsWhiteSpace && dtd.$removeEmpty[ sibling.nodeName.toLowerCase() ]) {
                                        // It must contains spaces and inline elements only.

                                        siblingText = DOM.text(sibling);

                                        if ((/[^\s\ufeff]/).test(siblingText))    // Spaces + Zero Width No-Break Space (U+FEFF)
                                            sibling = NULL;
                                        else {
                                            var allChildren = sibling.all || sibling.getElementsByTagName('*');
                                            for (var i = 0, child; child = allChildren[ i++ ];) {
                                                if (!dtd.$removeEmpty[ child.nodeName.toLowerCase() ]) {
                                                    sibling = NULL;
                                                    break;
                                                }
                                            }
                                        }

                                        if (sibling)
                                            isWhiteSpace = !!siblingText.length;
                                    }
                                    else
                                        sibling = NULL;
                                }
                            }

                            // A node with whitespaces has been found.
                            if (isWhiteSpace) {
                                // Enlarge the last enlargeable node, if we
                                // were waiting for spaces.
                                if (needsWhiteSpace) {
                                    if (commonReached)
                                        startTop = enlargeable;
                                    else if (enlargeable)
                                        self.setStartBefore(enlargeable);
                                }
                                else
                                    needsWhiteSpace = TRUE;
                            }

                            if (sibling) {
                                var next = sibling.previousSibling;

                                if (!enlargeable && !next) {
                                    // Set the sibling as enlargeable, so it's
                                    // parent will be get later outside this while.
                                    enlargeable = new Node(sibling);
                                    sibling = NULL;
                                    break;
                                }

                                sibling = next;
                            }
                            else {
                                // If sibling has been set to NULL, then we
                                // need to stop enlarging.
                                enlargeable = NULL;
                            }
                        }

                        if (enlargeable)
                            enlargeable = enlargeable.parent();
                    }

                    // Process the end boundary. This is basically the same
                    // code used for the start boundary, with small changes to
                    // make it work in the opposite side (to the right). This
                    // makes it difficult to reuse the code here. So, fixes to
                    // the above code are likely to be replicated here.

                    container = self.endContainer;
                    offset = self.endOffset;

                    // Reset the common variables.
                    enlargeable = sibling = NULL;
                    commonReached = needsWhiteSpace = FALSE;

                    if (container[0].nodeType == KEN.NODE_TEXT) {
                        // Check if there is any non-space text after the
                        // offset. Otherwise, container is NULL.
                        container = !S.trim(container[0].nodeValue.substring(offset)).length && container;

                        // If we found only whitespace in the node, it
                        // means that we'll need more whitespace to be able
                        // to expand. For example, <i> can be expanded in
                        // "A <i> [B]</i>", but not in "A<i> [B]</i>".
                        needsWhiteSpace = !( container && container[0].nodeValue.length );

                        if (container) {
                            if (!( sibling = container[0].nextSibling ))
                                enlargeable = container.parent();
                        }
                    }
                    else {
                        // Get the node right after the boudary to be checked
                        // first.
                        sibling = container[0].childNodes[offset];

                        if (!sibling)
                            enlargeable = container;
                    }

                    while (enlargeable || sibling) {
                        if (enlargeable && !sibling) {
                            if (!commonReached && DOM._4e_equals(enlargeable, commonAncestor))
                                commonReached = TRUE;

                            if (!body._4e_contains(enlargeable))
                                break;

                            if (!needsWhiteSpace || enlargeable.css('display') != 'inline') {
                                needsWhiteSpace = FALSE;

                                if (commonReached)
                                    endTop = enlargeable;
                                else if (enlargeable)
                                    self.setEndAfter(enlargeable);
                            }

                            sibling = enlargeable[0].nextSibling;
                        }

                        while (sibling) {
                            isWhiteSpace = FALSE;

                            if (sibling.nodeType == KEN.NODE_TEXT) {
                                siblingText = sibling.nodeValue;

                                if (/[^\s\ufeff]/.test(siblingText))
                                    sibling = NULL;

                                isWhiteSpace = /^[\s\ufeff]/.test(siblingText);
                            }
                            else {
                                // If this is a visible element.
                                // We need to check for the bookmark attribute because IE insists on
                                // rendering the display:none nodes we use for bookmarks. (#3363)
                                if (sibling.offsetWidth > 0 && !sibling.getAttribute('_ke_bookmark')) {
                                    // We'll accept it only if we need
                                    // whitespace, and this is an inline
                                    // element with whitespace only.
                                    if (needsWhiteSpace && dtd.$removeEmpty[ sibling.nodeName.toLowerCase() ]) {
                                        // It must contains spaces and inline elements only.

                                        siblingText = DOM.text(sibling);

                                        if ((/[^\s\ufeff]/).test(siblingText))
                                            sibling = NULL;
                                        else {
                                            allChildren = sibling.all || sibling.getElementsByTagName('*');
                                            for (i = 0; child = allChildren[ i++ ];) {
                                                if (!dtd.$removeEmpty[ child.nodeName.toLowerCase() ]) {
                                                    sibling = NULL;
                                                    break;
                                                }
                                            }
                                        }

                                        if (sibling)
                                            isWhiteSpace = !!siblingText.length;
                                    }
                                    else
                                        sibling = NULL;
                                }
                            }

                            if (isWhiteSpace) {
                                if (needsWhiteSpace) {
                                    if (commonReached)
                                        endTop = enlargeable;
                                    else
                                        self.setEndAfter(enlargeable);
                                }
                            }

                            if (sibling) {
                                next = sibling.nextSibling;

                                if (!enlargeable && !next) {
                                    enlargeable = new Node(sibling);
                                    sibling = NULL;
                                    break;
                                }

                                sibling = next;
                            }
                            else {
                                // If sibling has been set to NULL, then we
                                // need to stop enlarging.
                                enlargeable = NULL;
                            }
                        }

                        if (enlargeable)
                            enlargeable = enlargeable.parent();
                    }

                    // If the common ancestor can be enlarged by both boundaries, then include it also.
                    if (startTop && endTop) {
                        commonAncestor = startTop._4e_contains(endTop) ? endTop : startTop;
                        self.setStartBefore(commonAncestor);
                        self.setEndAfter(commonAncestor);
                    }
                    break;

                case KER.ENLARGE_BLOCK_CONTENTS:
                case KER.ENLARGE_LIST_ITEM_CONTENTS:

                    // Enlarging the start boundary.
                    var walkerRange = new KERange(self.document);
                    body = new Node(self.document.body);

                    walkerRange.setStartAt(body, KER.POSITION_AFTER_START);
                    walkerRange.setEnd(self.startContainer, self.startOffset);

                    var walker = new Walker(walkerRange),
                        blockBoundary,  // The node on which the enlarging should stop.
                        tailBr, //
                        defaultGuard = Walker.blockBoundary(
                            ( unit == KER.ENLARGE_LIST_ITEM_CONTENTS ) ?
                            { br : 1 } : NULL),
                        // Record the encountered 'blockBoundary' for later use.
                        boundaryGuard = function(node) {
                            var retval = defaultGuard(node);
                            if (!retval)
                                blockBoundary = node;
                            return retval;
                        },
                        // Record the encounted 'tailBr' for later use.
                        tailBrGuard = function(node) {
                            var retval = boundaryGuard(node);
                            if (!retval && node[0] && node._4e_name() == 'br')
                                tailBr = node;
                            return retval;
                        };

                    walker.guard = boundaryGuard;

                    enlargeable = walker.lastBackward();

                    // It's the body which stop the enlarging if no block boundary found.
                    blockBoundary = blockBoundary || body;

                    // Start the range at different position by comparing
                    // the document position of it with 'enlargeable' node.
                    self.setStartAt(
                        blockBoundary,
                        blockBoundary._4e_name() != 'br' &&
                            ( !enlargeable && self.checkStartOfBlock()
                                || enlargeable && blockBoundary._4e_contains(enlargeable) ) ?
                            KER.POSITION_AFTER_START :
                            KER.POSITION_AFTER_END);

                    // Enlarging the end boundary.
                    walkerRange = self.clone();
                    walkerRange.collapse();
                    walkerRange.setEndAt(body, KER.POSITION_BEFORE_END);
                    walker = new Walker(walkerRange);

                    // tailBrGuard only used for on range end.
                    walker.guard = ( unit == KER.ENLARGE_LIST_ITEM_CONTENTS ) ?
                        tailBrGuard : boundaryGuard;
                    blockBoundary = NULL;
                    // End the range right before the block boundary node.

                    enlargeable = walker.lastForward();

                    // It's the body which stop the enlarging if no block boundary found.
                    blockBoundary = blockBoundary || body;

                    // Start the range at different position by comparing
                    // the document position of it with 'enlargeable' node.
                    self.setEndAt(
                        blockBoundary,
                        ( !enlargeable && self.checkEndOfBlock()
                            || enlargeable && blockBoundary._4e_contains(enlargeable) ) ?
                            KER.POSITION_BEFORE_END :
                            KER.POSITION_BEFORE_START);
                    // We must include the <br> at the end of range if there's
                    // one and we're expanding list item contents
                    if (tailBr)
                        self.setEndAfter(tailBr);
            }
        },
        checkStartOfBlock : function() {
            var self = this,startContainer = self.startContainer,
                startOffset = self.startOffset;

            // If the starting node is a text node, and non-empty before the offset,
            // then we're surely not at the start of block.
            if (startOffset && startContainer[0].nodeType == KEN.NODE_TEXT) {
                var textBefore = S.trim(startContainer[0].nodeValue.substring(0, startOffset));
                if (textBefore.length)
                    return FALSE;
            }

            // Antecipate the trim() call here, so the walker will not make
            // changes to the DOM, which would not get reflected into this
            // range otherwise.
            self.trim();

            // We need to grab the block element holding the start boundary, so
            // let's use an element path for it.
            var path = new ElementPath(self.startContainer);

            // Creates a range starting at the block start until the range start.
            var walkerRange = self.clone();
            walkerRange.collapse(TRUE);
            walkerRange.setStartAt(path.block || path.blockLimit, KER.POSITION_AFTER_START);

            var walker = new Walker(walkerRange);
            walker.evaluator = getCheckStartEndBlockEvalFunction(TRUE);

            return walker.checkBackward();
        },

        checkEndOfBlock : function() {
            var self = this,endContainer = self.endContainer,
                endOffset = self.endOffset;

            // If the ending node is a text node, and non-empty after the offset,
            // then we're surely not at the end of block.
            if (endContainer[0].nodeType == KEN.NODE_TEXT) {
                var textAfter = S.trim(endContainer[0].nodeValue.substring(endOffset));
                if (textAfter.length)
                    return FALSE;
            }

            // Antecipate the trim() call here, so the walker will not make
            // changes to the DOM, which would not get reflected into this
            // range otherwise.
            self.trim();

            // We need to grab the block element holding the start boundary, so
            // let's use an element path for it.
            var path = new ElementPath(self.endContainer);

            // Creates a range starting at the block start until the range start.
            var walkerRange = self.clone();
            walkerRange.collapse(FALSE);
            walkerRange.setEndAt(path.block || path.blockLimit, KER.POSITION_BEFORE_END);

            var walker = new Walker(walkerRange);
            walker.evaluator = getCheckStartEndBlockEvalFunction(FALSE);

            return walker.checkForward();
        },
        deleteContents:function() {
            var self = this;
            if (self.collapsed)
                return;
            self.execContentsAction(0);
        },
        extractContents : function() {
            var self = this, docFrag = self.document.createDocumentFragment();
            if (!self.collapsed)
                self.execContentsAction(1, docFrag);
            return docFrag;
        },
        /**
         * Check whether current range is on the inner edge of the specified element.
         * @param {Number} checkType ( CKEDITOR.START | CKEDITOR.END ) The checking side.
         * @param {Node} element The target element to check.
         */
        checkBoundaryOfElement : function(element, checkType) {
            var walkerRange = this.clone();
            // Expand the range to element boundary.
            walkerRange[ checkType == KER.START ?
                'setStartAt' : 'setEndAt' ]
                (element, checkType == KER.START ?
                    KER.POSITION_AFTER_START
                    : KER.POSITION_BEFORE_END);

            var walker = new Walker(walkerRange);

            walker.evaluator = elementBoundaryEval;
            return walker[ checkType == KER.START ?
                'checkBackward' : 'checkForward' ]();
        },

        getBoundaryNodes : function() {
            var self = this,startNode = self.startContainer,
                endNode = self.endContainer,
                startOffset = self.startOffset,
                endOffset = self.endOffset,
                childCount;

            if (startNode[0].nodeType == KEN.NODE_ELEMENT) {
                childCount = startNode[0].childNodes.length;
                if (childCount > startOffset)
                    startNode = new Node(startNode[0].childNodes[startOffset]);
                else if (childCount < 1)
                    startNode = startNode._4e_previousSourceNode();
                else        // startOffset > childCount but childCount is not 0
                {
                    // Try to take the node just after the current position.
                    startNode = startNode[0];
                    while (startNode.lastChild)
                        startNode = startNode.lastChild;
                    startNode = new Node(startNode);

                    // Normally we should take the next node in DFS order. But it
                    // is also possible that we've already reached the end of
                    // document.
                    startNode = startNode._4e_nextSourceNode() || startNode;
                }
            }

            if (endNode[0].nodeType == KEN.NODE_ELEMENT) {
                childCount = endNode[0].childNodes.length;
                if (childCount > endOffset)
                    endNode = new Node(endNode[0].childNodes[endOffset])._4e_previousSourceNode(TRUE);
                else if (childCount < 1)
                    endNode = endNode._4e_previousSourceNode();
                else        // endOffset > childCount but childCount is not 0
                {
                    // Try to take the node just before the current position.
                    endNode = endNode[0];
                    while (endNode.lastChild)
                        endNode = endNode.lastChild;
                    endNode = new Node(endNode);
                }
            }

            // Sometimes the endNode will come right before startNode for collapsed
            // ranges. Fix it. (#3780)
            if (startNode._4e_position(endNode) & KEP.POSITION_FOLLOWING)
                startNode = endNode;

            return { startNode : startNode, endNode : endNode };
        },
        fixBlock : function(isStart, blockTag) {
            var self = this,bookmark = self.createBookmark(),
                fixedBlock = new Node(self.document.createElement(blockTag));

            self.collapse(isStart);

            self.enlarge(KER.ENLARGE_BLOCK_CONTENTS);
            fixedBlock[0].appendChild(self.extractContents());
            fixedBlock._4e_trim();

            if (!UA.ie)
                fixedBlock._4e_appendBogus();

            self.insertNode(fixedBlock);

            self.moveToBookmark(bookmark);

            return fixedBlock;
        },
        splitBlock : function(blockTag) {
            var self = this,startPath = new ElementPath(self.startContainer),
                endPath = new ElementPath(self.endContainer),
                startBlockLimit = startPath.blockLimit,
                endBlockLimit = endPath.blockLimit,
                startBlock = startPath.block,
                endBlock = endPath.block,
                elementPath = NULL;
            // Do nothing if the boundaries are in different block limits.
            if (!startBlockLimit._4e_equals(endBlockLimit))
                return NULL;

            // Get or fix current blocks.
            if (blockTag != 'br') {
                if (!startBlock) {
                    startBlock = self.fixBlock(TRUE, blockTag);
                    endBlock = new ElementPath(self.endContainer).block;
                }

                if (!endBlock)
                    endBlock = self.fixBlock(FALSE, blockTag);
            }

            // Get the range position.
            var isStartOfBlock = startBlock && self.checkStartOfBlock(),
                isEndOfBlock = endBlock && self.checkEndOfBlock();

            // Delete the current contents.
            // TODO: Why is 2.x doing CheckIsEmpty()?
            self.deleteContents();

            if (startBlock && DOM._4e_equals(startBlock, endBlock)) {
                if (isEndOfBlock) {
                    elementPath = new ElementPath(self.startContainer);
                    self.moveToPosition(endBlock, KER.POSITION_AFTER_END);
                    endBlock = NULL;
                }
                else if (isStartOfBlock) {
                    elementPath = new ElementPath(self.startContainer);
                    self.moveToPosition(startBlock, KER.POSITION_BEFORE_START);
                    startBlock = NULL;
                }
                else {
                    endBlock = self.splitElement(startBlock);

                    // In Gecko, the last child node must be a bogus <br>.
                    // Note: bogus <br> added under <ul> or <ol> would cause
                    // lists to be incorrectly rendered.
                    if (!UA.ie && !S.inArray(startBlock._4e_name(), ['ul', 'ol']))
                        startBlock._4e_appendBogus();
                }
            }

            return {
                previousBlock : startBlock,
                nextBlock : endBlock,
                wasStartOfBlock : isStartOfBlock,
                wasEndOfBlock : isEndOfBlock,
                elementPath : elementPath
            };
        },
        splitElement : function(toSplit) {
            var self = this;
            if (!self.collapsed)
                return NULL;

            // Extract the contents of the block from the selection point to the end
            // of its contents.
            self.setEndAt(toSplit, KER.POSITION_BEFORE_END);
            var documentFragment = self.extractContents(),

                // Duplicate the element after it.
                clone = toSplit._4e_clone(FALSE);

            // Place the extracted contents into the duplicated element.
            clone[0].appendChild(documentFragment);
            clone.insertAfter(toSplit);
            self.moveToPosition(toSplit, KER.POSITION_AFTER_END);
            return clone;
        },
        moveToElementEditablePosition : function(el, isMoveToEnd) {
            var self = this,isEditable,xhtml_dtd = KE.XHTML_DTD;

            // Empty elements are rejected.
            if (xhtml_dtd.$empty[ el._4e_name() ])
                return FALSE;

            while (el && el[0].nodeType == KEN.NODE_ELEMENT) {
                isEditable = el._4e_isEditable();

                // If an editable element is found, move inside it.
                if (isEditable)
                    self.moveToPosition(el, isMoveToEnd ?
                        KER.POSITION_BEFORE_END :
                        KER.POSITION_AFTER_START);
                // Stop immediately if we've found a non editable inline element (e.g <img>).
                else if (xhtml_dtd.$inline[ el._4e_name() ]) {
                    self.moveToPosition(el, isMoveToEnd ?
                        KER.POSITION_AFTER_END :
                        KER.POSITION_BEFORE_START);
                    return TRUE;
                }

                // Non-editable non-inline elements are to be bypassed, getting the next one.
                if (xhtml_dtd.$empty[ el._4e_name() ])
                    el = el[ isMoveToEnd ? '_4e_previous' : '_4e_next' ](nonWhitespaceOrBookmarkEval);
                else
                    el = el[ isMoveToEnd ? '_4e_last' : '_4e_first' ](nonWhitespaceOrBookmarkEval);

                // Stop immediately if we've found a text node.
                if (el && el[0].nodeType == KEN.NODE_TEXT) {
                    self.moveToPosition(el, isMoveToEnd ?
                        KER.POSITION_AFTER_END :
                        KER.POSITION_BEFORE_START);
                    return TRUE;
                }
            }

            return isEditable;
        },

        selectNodeContents : function(node) {
            this.setStart(node, 0);
            this.setEnd(node, node[0].nodeType == KEN.NODE_TEXT ?
                node[0].nodeValue.length :
                node[0].childNodes.length);
        }
    });
    var inlineChildReqElements = { "abbr":1,"acronym":1,"b":1,"bdo":1,
        "big":1,"cite":1,"code":1,"del":1,"dfn":1,
        "em":1,"font":1,"i":1,"ins":1,"label":1,
        "kbd":1,"q":1,"samp":1,"small":1,"span":1,
        "strike":1,"strong":1,"sub":1,"sup":1,"tt":1,"u":1,'var':1 };

    // Evaluator for CKEDITOR.dom.element::checkBoundaryOfElement, reject any
    // text node and non-empty elements unless it's being bookmark text.
    function elementBoundaryEval(node) {
        // Reject any text node unless it's being bookmark
        // OR it's spaces. (#3883)
        //如果不是文本节点并且是空的，可以继续取下一个判断边界
        var c1 = node[0].nodeType != KEN.NODE_TEXT
            && node._4e_name() in dtd.$removeEmpty,
            //文本为空，可以继续取下一个判断边界
            c2 = !S.trim(node[0].nodeValue),
            //恩，进去了书签，可以继续取下一个判断边界
            c3 = !!node.parent().attr('_ke_bookmark');
        return c1 || c2 || c3;
    }

    var whitespaceEval = new Walker.whitespaces(),
        bookmarkEval = new Walker.bookmark();

    function nonWhitespaceOrBookmarkEval(node) {
        // Whitespaces and bookmark nodes are to be ignored.
        return !whitespaceEval(node) && !bookmarkEval(node);
    }

    function getCheckStartEndBlockEvalFunction(isStart) {
        var hadBr = FALSE, bookmarkEvaluator = Walker.bookmark(TRUE);
        return function(node) {
            // First ignore bookmark nodes.
            if (bookmarkEvaluator(node))
                return TRUE;

            if (node[0].nodeType == KEN.NODE_TEXT) {
                // If there's any visible text, then we're not at the start.
                if (S.trim(node[0].nodeValue).length)
                    return FALSE;
            }
            else if (node[0].nodeType == KEN.NODE_ELEMENT) {
                // If there are non-empty inline elements (e.g. <img />), then we're not
                // at the start.
                if (!inlineChildReqElements[ node._4e_name() ]) {
                    // If we're working at the end-of-block, forgive the first <br /> in non-IE
                    // browsers.
                    if (!isStart && !UA.ie && node._4e_name() == 'br' && !hadBr)
                        hadBr = TRUE;
                    else
                        return FALSE;
                }
            }
            return TRUE;
        };
    }

    function bookmark(contentOnly, isReject) {
        function isBookmarkNode(node) {
            return ( node && node.nodeName == 'span'
                && node.getAttribute('_ke_bookmark') );
        }

        return function(node) {
            var isBookmark, parent;
            // Is bookmark inner text node?
            isBookmark = ( node && !node.nodeName && ( parent = node.parentNode )
                && isBookmarkNode(parent) );
            // Is bookmark node?
            isBookmark = contentOnly ? isBookmark : isBookmark || isBookmarkNode(node);
            return isReject ^ isBookmark;
        };
    }

    function whitespaces(isReject) {
        return function(node) {
            node = node[0] || node;
            var isWhitespace = node && ( node.nodeType == KEN.NODE_TEXT )
                && !S.trim(node.nodeValue);
            return isReject ^ isWhitespace;
        };
    }


    KE.Range = KERange;
    KE["Range"] = KERange;
    var RangeP = KERange.prototype;
    KE.Utils.extern(RangeP, {
        "updateCollapsed":RangeP.updateCollapsed,
        "optimize":RangeP.optimize,
        "setStartAfter":RangeP.setStartAfter,
        "setEndAfter":RangeP.setEndAfter,
        "setStartBefore":RangeP.setStartBefore,
        "setEndBefore":RangeP.setEndBefore,
        "optimizeBookmark":RangeP.optimizeBookmark,

        "setStart":RangeP.setStart,
        "setEnd":RangeP.setEnd,
        "setStartAt":RangeP.setStartAt,
        "setEndAt":RangeP.setEndAt,
        "execContentsAction":RangeP.execContentsAction,
        "collapse":RangeP.collapse,
        "clone":RangeP.clone,
        "getEnclosedNode":RangeP.getEnclosedNode,
        "shrink":RangeP.shrink,
        "getTouchedStartNode":RangeP.getTouchedStartNode,
        "createBookmark2":RangeP.createBookmark2,
        "createBookmark":RangeP.createBookmark,



        "moveToPosition":RangeP.moveToPosition,
        "trim":RangeP.trim,
        "insertNode":RangeP.insertNode,
        "moveToBookmark":RangeP.moveToBookmark,
        "getCommonAncestor":RangeP.getCommonAncestor,
        "enlarge":RangeP.enlarge,
        "checkStartOfBlock":RangeP.checkStartOfBlock,
        "checkEndOfBlock":RangeP.checkEndOfBlock,
        "deleteContents":RangeP.deleteContents,
        "extractContents":RangeP.extractContents,


        "checkBoundaryOfElement":RangeP.checkBoundaryOfElement,
        "getBoundaryNodes":RangeP.getBoundaryNodes,
        "fixBlock":RangeP.fixBlock,
        "splitBlock":RangeP.splitBlock,
        "splitElement":RangeP.splitElement,
        "moveToElementEditablePosition":RangeP.moveToElementEditablePosition,
        "selectNodeContents":RangeP.selectNodeContents
    });
});
/**
 * modified from ckeditor ,dom iterator implementation using walker and nextSourceNode
 * @author: <yiminghe@gmail.com>
 */
/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
KISSY.Editor.add("domiterator", function(KE) {
    var

        TRUE = true,
        FALSE = false,
        NULL = null,
        S = KISSY,
        UA = S.UA,
        Walker = KE.Walker,
        KERange = KE.Range,
        KER = KE.RANGE,
        KEN = KE.NODE,
        ElementPath = KE.ElementPath,
        Node = S.Node,
        DOM = S.DOM;

    /**
     * @constructor
     * @param range {KISSY.Editor.Range}
     */
    function Iterator(range) {
        if (arguments.length < 1)
            return;
        var self = this;
        self.range = range;
        self.forceBrBreak = FALSE;

        // Whether include <br>s into the enlarged range.(#3730).
        self.enlargeBr = TRUE;
        self.enforceRealBlocks = FALSE;

        self._ || ( self._ = {} );
    }

    var beginWhitespaceRegex = /^[\r\n\t ]*$/,///^[\r\n\t ]+$/,//+:*??不匹配空串
        isBookmark = Walker.bookmark();

    S.augment(Iterator, {
        //奇怪点：
        //<ul>
        // <li>
        // x
        // </li>
        // <li>
        // y
        // </li>
        // </ul>
        //会返回两次 li,li,而不是一次 ul ，
        // 可能只是返回包含文字的段落概念？

        /**
         * @this {Iterator}
         */
        getNextParagraph : function(blockTag) {
            // The block element to be returned.
            var block,self = this;

            // The range object used to identify the paragraph contents.
            var range;

            // Indicats that the current element in the loop is the last one.
            var isLast;

            // Instructs to cleanup remaining BRs.
            var removePreviousBr, removeLastBr;

            // self is the first iteration. Let's initialize it.
            if (!self._.lastNode) {
                range = self.range.clone();

                //2010-09-30 shrink
                //3.4.2 新增，
                // Shrink the range to exclude harmful "noises" (#4087, #4450, #5435).
                range.shrink(KER.SHRINK_ELEMENT, TRUE);

                range.enlarge(self.forceBrBreak || !self.enlargeBr ?
                    KER.ENLARGE_LIST_ITEM_CONTENTS : KER.ENLARGE_BLOCK_CONTENTS);

                var walker = new Walker(range),
                    ignoreBookmarkTextEvaluator = Walker.bookmark(TRUE, TRUE);
                // Avoid anchor inside bookmark inner text.
                walker.evaluator = ignoreBookmarkTextEvaluator;
                self._.nextNode = walker.next();
                // TODO: It's better to have walker.reset() used here.
                walker = new Walker(range);
                walker.evaluator = ignoreBookmarkTextEvaluator;
                var lastNode = walker.previous();
                self._.lastNode = lastNode._4e_nextSourceNode(TRUE);

                // We may have an empty text node at the end of block due to [3770].
                // If that node is the lastNode, it would cause our logic to leak to the
                // next block.(#3887)
                if (self._.lastNode &&
                    self._.lastNode[0].nodeType == KEN.NODE_TEXT &&
                    !S.trim(self._.lastNode[0].nodeValue) &&
                    self._.lastNode.parent()._4e_isBlockBoundary()) {
                    var testRange = new KERange(range.document);
                    testRange.moveToPosition(self._.lastNode, KER.POSITION_AFTER_END);
                    if (testRange.checkEndOfBlock()) {
                        var path = new ElementPath(testRange.endContainer);
                        var lastBlock = path.block || path.blockLimit;
                        self._.lastNode = lastBlock._4e_nextSourceNode(TRUE);
                    }
                }

                // Probably the document end is reached, we need a marker node.
                if (!self._.lastNode) {
                    self._.lastNode = self._.docEndMarker = new Node(range.document.createTextNode(''));
                    DOM.insertAfter(self._.lastNode[0], lastNode[0]);
                }

                // Let's reuse self variable.
                range = NULL;
            }

            var currentNode = self._.nextNode;
            lastNode = self._.lastNode;

            self._.nextNode = NULL;
            while (currentNode) {
                // closeRange indicates that a paragraph boundary has been found,
                // so the range can be closed.
                var closeRange = FALSE;

                // includeNode indicates that the current node is good to be part
                // of the range. By default, any non-element node is ok for it.
                var includeNode = ( currentNode[0].nodeType != KEN.NODE_ELEMENT ),
                    continueFromSibling = FALSE;

                // If it is an element node, let's check if it can be part of the
                // range.
                if (!includeNode) {
                    var nodeName = currentNode._4e_name();

                    if (currentNode._4e_isBlockBoundary(self.forceBrBreak && { br : 1 })) {
                        // <br> boundaries must be part of the range. It will
                        // happen only if ForceBrBreak.
                        if (nodeName == 'br')
                            includeNode = TRUE;
                        else if (!range && !currentNode[0].childNodes.length && nodeName != 'hr') {
                            // If we have found an empty block, and haven't started
                            // the range yet, it means we must return self block.
                            block = currentNode;
                            isLast = currentNode._4e_equals(lastNode);
                            break;
                        }

                        // The range must finish right before the boundary,
                        // including possibly skipped empty spaces. (#1603)
                        if (range) {
                            range.setEndAt(currentNode, KER.POSITION_BEFORE_START);

                            // The found boundary must be set as the next one at self
                            // point. (#1717)
                            if (nodeName != 'br')
                                self._.nextNode = currentNode;
                        }

                        closeRange = TRUE;
                    } else {
                        // If we have child nodes, let's check them.
                        if (currentNode[0].firstChild) {
                            // If we don't have a range yet, let's start it.
                            if (!range) {
                                range = new KERange(self.range.document);
                                range.setStartAt(currentNode, KER.POSITION_BEFORE_START);
                            }

                            currentNode = new Node(currentNode[0].firstChild);
                            continue;
                        }
                        includeNode = TRUE;
                    }
                }
                else if (currentNode[0].nodeType == KEN.NODE_TEXT) {
                    // Ignore normal whitespaces (i.e. not including &nbsp; or
                    // other unicode whitespaces) before/after a block node.
                    if (beginWhitespaceRegex.test(currentNode[0].nodeValue))
                        includeNode = FALSE;
                }

                // The current node is good to be part of the range and we are
                // starting a new range, initialize it first.
                if (includeNode && !range) {
                    range = new KERange(self.range.document);
                    range.setStartAt(currentNode, KER.POSITION_BEFORE_START);
                }

                // The last node has been found.
                isLast = ( !closeRange || includeNode ) && currentNode._4e_equals(lastNode);

                // If we are in an element boundary, let's check if it is time
                // to close the range, otherwise we include the parent within it.
                if (range && !closeRange) {
                    while (!currentNode[0].nextSibling && !isLast) {
                        var parentNode = currentNode.parent();

                        if (parentNode._4e_isBlockBoundary(self.forceBrBreak && { br : 1 })) {
                            closeRange = TRUE;
                            isLast = isLast || parentNode._4e_equals(lastNode);
                            break;
                        }

                        currentNode = parentNode;
                        includeNode = TRUE;
                        isLast = currentNode._4e_equals(lastNode);
                        continueFromSibling = TRUE;
                    }
                }

                // Now finally include the node.
                if (includeNode)
                    range.setEndAt(currentNode, KER.POSITION_AFTER_END);

                currentNode = currentNode._4e_nextSourceNode(continueFromSibling, NULL, lastNode);
                isLast = !currentNode;

                // We have found a block boundary. Let's close the range and move out of the
                // loop.
                if (isLast || ( closeRange && range ))
                    break;

                //3.4.2 中被去掉了！不要了，改作一开始就shrink，参见开头 2010-09-30 shrink 注释 
                ////qc #3879 ，选择td内所有问题，这里被出发了
                //禁止，只有td内全部为空时才会略过
                /*
                 if (FALSE) {
                 if (( closeRange || isLast ) && range) {
                 var boundaryNodes = range.getBoundaryNodes(),
                 startPath = new ElementPath(range.startContainer);

                 // Drop the range if it only contains bookmark nodes, and is
                 // not because of the original collapsed range. (#4087,#4450)
                 if (boundaryNodes.startNode.parent()._4e_equals(startPath.blockLimit)
                 && isBookmark(boundaryNodes.startNode)
                 && isBookmark(boundaryNodes.endNode)
                 ) {
                 range = NULL;
                 self._.nextNode = NULL;
                 }
                 else
                 break;
                 }
                 if (isLast)
                 break;
                 }*/


            }

            // Now, based on the processed range, look for (or create) the block to be returned.
            if (!block) {
                // If no range has been found, self is the end.
                if (!range) {
                    self._.docEndMarker && self._.docEndMarker._4e_remove();
                    self._.nextNode = NULL;
                    return NULL;
                }

                var startPath = new ElementPath(range.startContainer);
                var startBlockLimit = startPath.blockLimit,
                    checkLimits = { div : 1, th : 1, td : 1 };
                block = startPath.block;

                if ((!block || !block[0])
                    && !self.enforceRealBlocks
                    && checkLimits[ startBlockLimit._4e_name() ]
                    && range.checkStartOfBlock()
                    && range.checkEndOfBlock())
                    block = startBlockLimit;
                else if (!block || ( self.enforceRealBlocks && block._4e_name() == 'li' )) {
                    // Create the fixed block.
                    block = new Node(self.range.document.createElement(blockTag || 'p'));
                    // Move the contents of the temporary range to the fixed block.
                    block[0].appendChild(range.extractContents());
                    block._4e_trim();
                    // Insert the fixed block into the DOM.
                    range.insertNode(block);
                    removePreviousBr = removeLastBr = TRUE;
                }
                else if (block._4e_name() != 'li') {
                    // If the range doesn't includes the entire contents of the
                    // block, we must split it, isolating the range in a dedicated
                    // block.
                    if (!range.checkStartOfBlock() || !range.checkEndOfBlock()) {
                        // The resulting block will be a clone of the current one.
                        block = block._4e_clone(FALSE);

                        // Extract the range contents, moving it to the new block.
                        block[0].appendChild(range.extractContents());
                        block._4e_trim();

                        // Split the block. At self point, the range will be in the
                        // right position for our intents.
                        var splitInfo = range.splitBlock();

                        removePreviousBr = !splitInfo.wasStartOfBlock;
                        removeLastBr = !splitInfo.wasEndOfBlock;

                        // Insert the new block into the DOM.
                        range.insertNode(block);
                    }
                }
                else if (!isLast) {
                    // LIs are returned as is, with all their children (due to the
                    // nested lists). But, the next node is the node right after
                    // the current range, which could be an <li> child (nested
                    // lists) or the next sibling <li>.

                    self._.nextNode = ( block._4e_equals(lastNode) ? NULL :
                        range.getBoundaryNodes().endNode._4e_nextSourceNode(TRUE, NULL, lastNode) );
                }
            }

            if (removePreviousBr) {
                var previousSibling = new Node(block[0].previousSibling);
                if (previousSibling[0] && previousSibling[0].nodeType == KEN.NODE_ELEMENT) {
                    if (previousSibling._4e_name() == 'br')
                        previousSibling._4e_remove();
                    else if (previousSibling[0].lastChild && DOM._4e_name(previousSibling[0].lastChild) == 'br')
                        DOM._4e_remove(previousSibling[0].lastChild);
                }
            }

            if (removeLastBr) {
                // Ignore bookmark nodes.(#3783)
                var bookmarkGuard = Walker.bookmark(FALSE, TRUE);

                var lastChild = new Node(block[0].lastChild);
                if (lastChild[0] && lastChild[0].nodeType == KEN.NODE_ELEMENT && lastChild._4e_name() == 'br') {
                    // Take care not to remove the block expanding <br> in non-IE browsers.
                    if (UA.ie
                        || lastChild._4e_previous(bookmarkGuard)
                        || lastChild._4e_next(bookmarkGuard))
                        lastChild._4e_remove();
                }
            }

            // Get a reference for the next element. self is important because the
            // above block can be removed or changed, so we can rely on it for the
            // next interation.
            if (!self._.nextNode) {
                self._.nextNode = ( isLast || block._4e_equals(lastNode) ) ? NULL :
                    block._4e_nextSourceNode(TRUE, NULL, lastNode);
            }

            return block;
        }
    });

    KERange.prototype.createIterator = function() {
        return new Iterator(this);
    };
});
/**
 * modified from ckeditor core plugin - selection
 * @author: <yiminghe@gmail.com>
 */
/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
KISSY.Editor.add("selection", function(KE) {
    /**
     * selection type enum
     * @enum {number}
     */
    KE.SELECTION = {
        SELECTION_NONE:1,
        SELECTION_TEXT:2,
        SELECTION_ELEMENT:3

    };
    var
        TRUE = true,
        FALSE = false,
        NULL = null,
        S = KISSY,
        UA = S.UA,
        DOM = S.DOM,
        Event = S.Event,
        //tryThese = KE.Utils.tryThese,
        Node = S.Node,
        KES = KE.SELECTION,
        KER = KE.RANGE,
        KEN = KE.NODE,
        //EventTarget = S.EventTarget,
        Walker = KE.Walker,
        //ElementPath = KE.ElementPath,
        KERange = KE.Range;

    /**
     * @constructor
     * @param document {Document}
     */
    function KESelection(document) {
        var self = this;
        self["document"] = self.document = document;
        self._ = {
            cache : {}
        };

        /**
         * IE BUG: The selection's document may be a different document than the
         * editor document. Return NULL if that's the case.
         */
        if (UA.ie) {
            var range = self.getNative().createRange();
            if (!range
                || ( range.item && range.item(0).ownerDocument != document )
                || ( range.parentElement && range.parentElement().ownerDocument != document )) {
                self.isInvalid = TRUE;
            }
        }
    }

    var styleObjectElements = {
        "img":1,"hr":1,"li":1,"table":1,"tr":1,"td":1,"th":1,"embed":1,"object":1,"ol":1,"ul":1,
        "a":1, "input":1, "form":1, "select":1, "textarea":1, "button":1, "fieldset":1, "thead":1, "tfoot":1
    };

    S.augment(KESelection, {


        /**
         * Gets the native selection object from the browser.
         * @returns {Object} The native selection object.
         * @example
         * var selection = editor.getSelection().<b>getNative()</b>;
         */
        getNative :
            UA.ie ?
                function() {
                    var self = this,cache = self._.cache;
                    return cache.nativeSel || ( cache.nativeSel = self.document.selection );
                }
                :
                function() {
                    var self = this,cache = self._.cache;
                    return cache.nativeSel || ( cache.nativeSel = DOM._4e_getWin(self.document).getSelection() );
                },

        /**
         * Gets the type of the current selection. The following values are
         * available:
         * <ul>
         *        <li> SELECTION_NONE (1): No selection.</li>
         *        <li> SELECTION_TEXT (2): Text is selected or
         *            collapsed selection.</li>
         *        <li> SELECTION_ELEMENT (3): A element
         *            selection.</li>
         * </ul>
         * @returns {number} One of the following constant values:
         *         SELECTION_NONE,  SELECTION_TEXT or
         *         SELECTION_ELEMENT.
         * @example
         * if ( editor.getSelection().<b>getType()</b> == SELECTION_TEXT )
         *     alert( 'Text is selected' );
         */
        getType :
            UA.ie ?
                function() {
                    var self = this,cache = self._.cache;
                    if (cache.type)
                        return cache.type;

                    var type = KES.SELECTION_NONE;

                    try {
                        var sel = self.getNative(),
                            ieType = sel.type;

                        if (ieType == 'Text')
                            type = KES.SELECTION_TEXT;

                        if (ieType == 'Control')
                            type = KES.SELECTION_ELEMENT;

                        // It is possible that we can still get a text range
                        // object even when type == 'None' is returned by IE.
                        // So we'd better check the object returned by
                        // createRange() rather than by looking at the type.
                        //当前一个操作选中文本，后一个操作右键点了字串中间就会出现了
                        if (sel.createRange().parentElement)
                            type = KES.SELECTION_TEXT;
                    }
                    catch(e) {
                    }

                    return ( cache.type = type );
                }
                :
                function() {
                    var self = this,cache = self._.cache;
                    if (cache.type)
                        return cache.type;

                    var type = KES.SELECTION_TEXT,
                        sel = self.getNative();

                    if (!sel)
                        type = KES.SELECTION_NONE;
                    else if (sel.rangeCount == 1) {
                        // Check if the actual selection is a control (IMG,
                        // TABLE, HR, etc...).

                        var range = sel.getRangeAt(0),
                            startContainer = range.startContainer;

                        if (startContainer == range.endContainer
                            && startContainer.nodeType == KEN.NODE_ELEMENT
                            && ( range.endOffset - range.startOffset ) === 1
                            && styleObjectElements[ startContainer.childNodes[ range.startOffset ].nodeName.toLowerCase() ]) {
                            type = KES.SELECTION_ELEMENT;
                        }
                    }

                    return ( cache.type = type );
                },

        getRanges :
            UA.ie ?
                ( function() {
                    // Finds the container and offset for a specific boundary
                    // of an IE range.
                    /**
                     *
                     * @param {KISSY.Editor.Range} range
                     * @param {boolean=} start
                     */
                    var getBoundaryInformation = function(range, start) {
                        // Creates a collapsed range at the requested boundary.
                        range = range.duplicate();
                        range.collapse(start);

                        // Gets the element that encloses the range entirely.
                        var parent = range.parentElement(), siblings = parent.childNodes,
                            testRange;

                        for (var i = 0; i < siblings.length; i++) {
                            var child = siblings[ i ];
                            //console.log("child:" + child.nodeType == KEN.NODE_ELEMENT ?
                            //    ("el: " + child.innerHTML) : ("text:" + child.nodeValue));
                            if (child.nodeType == KEN.NODE_ELEMENT) {
                                testRange = range.duplicate();

                                testRange.moveToElementText(child);

                                var comparisonStart = testRange.compareEndPoints('StartToStart', range),
                                    comparisonEnd = testRange.compareEndPoints('EndToStart', range);

                                testRange.collapse();
                                //中间有其他标签
                                if (comparisonStart > 0)
                                    break;
                                // When selection stay at the side of certain self-closing elements, e.g. BR,
                                // our comparison will never shows an equality. (#4824)
                                else if (!comparisonStart
                                    || comparisonEnd == 1 && comparisonStart == -1)
                                    return { container : parent, offset : i };
                                else if (!comparisonEnd)
                                    return { container : parent, offset : i + 1 };

                                testRange = NULL;
                            }
                        }

                        if (!testRange) {
                            testRange = range.duplicate();
                            testRange.moveToElementText(parent);
                            testRange.collapse(FALSE);
                        }

                        testRange.setEndPoint('StartToStart', range);
                        // IE report line break as CRLF with range.text but
                        // only LF with textnode.nodeValue, normalize them to avoid
                        // breaking character counting logic below. (#3949)
                        var distance = testRange.text.replace(/(\r\n|\r)/g, "\n").length;

                        try {
                            while (distance > 0)
                                //bug? 可能不是文本节点 nodeValue undefined
                                //永远不会出现 textnode<img/>textnode
                                //停止时，前面一定为textnode
                                distance -= siblings[ --i ].nodeValue.length;
                        }
                            // Measurement in IE could be somtimes wrong because of <select> element. (#4611)
                        catch(e) {
                            distance = 0;
                        }


                        if (distance === 0) {
                            return {
                                container : parent,
                                offset : i
                            };
                        }
                        else {
                            return {
                                container : siblings[ i ],
                                offset : -distance
                            };
                        }
                    };

                    return function() {
                        var self = this,cache = self._.cache;
                        if (cache.ranges)
                            return cache.ranges;

                        // IE doesn't have range support (in the W3C way), so we
                        // need to do some magic to transform selections into
                        // CKEDITOR.dom.range instances.

                        var sel = self.getNative(),
                            nativeRange = sel && sel.createRange(),
                            type = self.getType(),
                            range;

                        if (!sel)
                            return [];

                        if (type == KES.SELECTION_TEXT) {
                            range = new KERange(self.document);
                            var boundaryInfo = getBoundaryInformation(nativeRange, TRUE);
                            range.setStart(new Node(boundaryInfo.container), boundaryInfo.offset);
                            boundaryInfo = getBoundaryInformation(nativeRange);
                            range.setEnd(new Node(boundaryInfo.container), boundaryInfo.offset);
                            return ( cache.ranges = [ range ] );
                        } else if (type == KES.SELECTION_ELEMENT) {
                            var retval = cache.ranges = [];

                            for (var i = 0; i < nativeRange.length; i++) {
                                var element = nativeRange.item(i),
                                    parentElement = element.parentNode,
                                    j = 0;

                                range = new KERange(self.document);

                                for (; j < parentElement.childNodes.length && parentElement.childNodes[j] != element; j++) { /*jsl:pass*/
                                }

                                range.setStart(new Node(parentElement), j);
                                range.setEnd(new Node(parentElement), j + 1);
                                retval.push(range);
                            }

                            return retval;
                        }

                        return ( cache.ranges = [] );
                    };
                })()
                :
                function() {
                    var self = this,cache = self._.cache;
                    if (cache.ranges)
                        return cache.ranges;

                    // On browsers implementing the W3C range, we simply
                    // tranform the native ranges in CKEDITOR.dom.range
                    // instances.

                    var ranges = [], sel = self.getNative();

                    if (!sel)
                        return [];

                    for (var i = 0; i < sel.rangeCount; i++) {
                        var nativeRange = sel.getRangeAt(i), range = new KERange(self.document);

                        range.setStart(new Node(nativeRange.startContainer), nativeRange.startOffset);
                        range.setEnd(new Node(nativeRange.endContainer), nativeRange.endOffset);
                        ranges.push(range);
                    }

                    return ( cache.ranges = ranges );
                },

        /**
         * Gets the DOM element in which the selection starts.
         * @returns {KISSY.Node} The element at the beginning of the
         *        selection.
         * @example
         * var element = editor.getSelection().<b>getStartElement()</b>;
         * alert( element._4e_name() );
         */
        getStartElement : function() {
            var self = this,cache = self._.cache;
            if (cache.startElement !== undefined)
                return cache.startElement;

            var node,
                sel = self.getNative();

            switch (self.getType()) {
                case KES.SELECTION_ELEMENT :
                    return this.getSelectedElement();

                case KES.SELECTION_TEXT :

                    var range = self.getRanges()[0];

                    if (range) {
                        if (!range.collapsed) {
                            range.optimize();

                            // Decrease the range content to exclude particial
                            // selected node on the start which doesn't have
                            // visual impact. ( #3231 )
                            while (TRUE) {
                                var startContainer = range.startContainer,
                                    startOffset = range.startOffset;
                                // Limit the fix only to non-block elements.(#3950)
                                if (startOffset == ( startContainer[0].nodeType === KEN.NODE_ELEMENT ?
                                    startContainer[0].childNodes.length : startContainer[0].nodeValue.length )
                                    && !startContainer._4e_isBlockBoundary())
                                    range.setStartAfter(startContainer);
                                else break;
                            }

                            node = range.startContainer;

                            if (node[0].nodeType != KEN.NODE_ELEMENT)
                                return node.parent();

                            node = new Node(node[0].childNodes[range.startOffset]);

                            if (!node[0] || node[0].nodeType != KEN.NODE_ELEMENT)
                                return range.startContainer;

                            var child = node[0].firstChild;
                            while (child && child.nodeType == KEN.NODE_ELEMENT) {
                                node = new Node(child);
                                child = child.firstChild;
                            }
                            return node;
                        }
                    }

                    if (UA.ie) {
                        range = sel.createRange();
                        range.collapse(TRUE);
                        node = range.parentElement();
                    }
                    else {
                        node = sel.anchorNode;
                        if (node && node.nodeType != KEN.NODE_ELEMENT)
                            node = node.parentNode;
                    }
            }

            return cache.startElement = ( node ? DOM._4e_wrap(node) : NULL );
        },

        /**
         * Gets the current selected element.
         * @returns {KISSY.Node} The selected element. Null if no
         *        selection is available or the selection type is not
         *       SELECTION_ELEMENT.
         * @example
         * var element = editor.getSelection().<b>getSelectedElement()</b>;
         * alert( element._4e_name() );
         */
        getSelectedElement : function() {
            var self = this,
                node,
                cache = self._.cache;
            if (cache.selectedElement !== undefined)
                return cache.selectedElement;


            // Is it native IE control type selection?

            if (UA.ie) {
                var range = self.getNative().createRange();
                node = range.item && range.item(0);

            }// Figure it out by checking if there's a single enclosed
            // node of the range.
            if (!node) {
                node = (function() {
                    var range = self.getRanges()[ 0 ],
                        enclosed,
                        selected;

                    // Check first any enclosed element, e.g. <ul>[<li><a href="#">item</a></li>]</ul>
                    //脱两层？？2是啥意思？
                    for (var i = 2;
                         i && !
                             (
                                 ( enclosed = range.getEnclosedNode() )
                                     && ( enclosed[0].nodeType == KEN.NODE_ELEMENT )
                                     //某些值得这么多的元素？？
                                     && styleObjectElements[ enclosed._4e_name() ]
                                     && ( selected = enclosed )
                                 ); i--) {
                        // Then check any deep wrapped element, e.g. [<b><i><img /></i></b>]
                        //一下子退到底  ^<a><span><span><img/></span></span></a>^
                        // ->
                        //<a><span><span>^<img/>^</span></span></a>
                        range.shrink(KER.SHRINK_ELEMENT);
                    }

                    return  selected && selected[0];
                })();
            }

            return cache.selectedElement = DOM._4e_wrap(node);
        },



        reset : function() {
            this._.cache = {};
        },

        selectElement : function(element) {
            var range,self = this;
            if (UA.ie) {
                //do not use empty()，编辑器内滚动条重置了
                //选择的 img 内容前后莫名被清除
                //self.getNative().empty();
                try {
                    // Try to select the node as a control.
                    range = self.document.body.createControlRange();
                    range.addElement(element[0]);
                    range.select();
                } catch(e) {
                    // If failed, select it as a text range.
                    range = self.document.body.createTextRange();
                    range.moveToElementText(element[0]);
                    range.select();
                } finally {
                    //this.document.fire('selectionchange');
                }
                self.reset();
            } else {
                // Create the range for the element.
                range = self.document.createRange();
                range.selectNode(element[0]);
                // Select the range.
                var sel = self.getNative();
                sel.removeAllRanges();
                sel.addRange(range);
                self.reset();
            }
        },

        selectRanges : function(ranges) {
            var self = this;
            if (UA.ie) {

                if (ranges.length > 1) {
                    // IE doesn't accept multiple ranges selection, so we join all into one.
                    var last = ranges[ ranges.length - 1 ];
                    ranges[ 0 ].setEnd(last.endContainer, last.endOffset);
                    ranges.length = 1;
                }

                // IE doesn't accept multiple ranges selection, so we just
                // select the first one.
                if (ranges[ 0 ])
                    ranges[ 0 ].select();

                self.reset();
            }
            else {
                var sel = self.getNative();
                if (!sel) return;
                sel.removeAllRanges();
                for (var i = 0; i < ranges.length; i++) {
                    var range = ranges[ i ], nativeRange = self.document.createRange(),
                        startContainer = range.startContainer;

                    // In FF2, if we have a collapsed range, inside an empty
                    // element, we must add something to it otherwise the caret
                    // will not be visible.
                    if (range.collapsed &&
                        ( UA.gecko && UA.gecko < 1.0900 ) &&
                        startContainer[0].nodeType == KEN.NODE_ELEMENT &&
                        !startContainer[0].childNodes.length) {
                        startContainer[0].appendChild(self.document.createTextNode(""));
                    }
                    nativeRange.setStart(startContainer[0], range.startOffset);
                    nativeRange.setEnd(range.endContainer[0], range.endOffset);
                    // Select the range.
                    sel.addRange(nativeRange);
                }
                self.reset();
            }
        },
        createBookmarks2 : function(normalized) {
            var bookmarks = [],
                ranges = this.getRanges();

            for (var i = 0; i < ranges.length; i++)
                bookmarks.push(ranges[i].createBookmark2(normalized));

            return bookmarks;
        },
        createBookmarks : function(serializable, ranges) {
            var self = this,
                retval = [],
                doc = self.document,
                bookmark;
            ranges = ranges || self.getRanges();
            var length = ranges.length;
            for (var i = 0; i < length; i++) {
                retval.push(bookmark = ranges[ i ].createBookmark(serializable, TRUE));
                serializable = bookmark.serializable;

                var bookmarkStart = serializable ? S.one("#" + bookmark.startNode, doc) : bookmark.startNode,
                    bookmarkEnd = serializable ? S.one("#" + bookmark.endNode, doc) : bookmark.endNode;

                // Updating the offset values for rest of ranges which have been mangled(#3256).
                for (var j = i + 1; j < length; j++) {
                    var dirtyRange = ranges[ j ],
                        rangeStart = dirtyRange.startContainer,
                        rangeEnd = dirtyRange.endContainer;

                    DOM._4e_equals(rangeStart, bookmarkStart.parent()) && dirtyRange.startOffset++;
                    DOM._4e_equals(rangeStart, bookmarkEnd.parent()) && dirtyRange.startOffset++;
                    DOM._4e_equals(rangeEnd, bookmarkStart.parent()) && dirtyRange.endOffset++;
                    DOM._4e_equals(rangeEnd, bookmarkEnd.parent()) && dirtyRange.endOffset++;
                }
            }

            return retval;
        },

        selectBookmarks : function(bookmarks) {
            var self = this,ranges = [];
            for (var i = 0; i < bookmarks.length; i++) {
                var range = new KERange(self.document);
                range.moveToBookmark(bookmarks[i]);
                ranges.push(range);
            }
            self.selectRanges(ranges);
            return self;
        },

        getCommonAncestor : function() {
            var ranges = this.getRanges(),
                startNode = ranges[ 0 ].startContainer,
                endNode = ranges[ ranges.length - 1 ].endContainer;
            return startNode._4e_commonAncestor(endNode);
        },

        // Moving scroll bar to the current selection's start position.
        scrollIntoView : function() {
            // If we have split the block, adds a temporary span at the
            // range position and scroll relatively to it.
            var start = this.getStartElement();
            start && start._4e_scrollIntoView();
        },
        removeAllRanges:function() {
            var sel = this.getNative();
            if (UA.ie) {
                sel && sel.clear();
            } else {
                sel && sel.removeAllRanges();
            }
        }
    });


    var nonCells = { "table":1,"tbody":1,"tr":1 }, notWhitespaces = Walker.whitespaces(TRUE),
        fillerTextRegex = /\ufeff|\u00a0/;
    KERange.prototype["select"] = KERange.prototype.select = UA.ie ?
        // V2
        function(forceExpand) {

            var self = this,
                collapsed = self.collapsed,
                isStartMarkerAlone,
                dummySpan;
            //选的是元素，直接使用selectElement
            //还是有差异的，特别是img选择框问题
            if (self.startContainer[0] === self.endContainer[0]
                && self.endOffset - self.startOffset == 1) {
                var selEl = self.startContainer[0].childNodes[self.startOffset];
                if (selEl.nodeType == KEN.NODE_ELEMENT) {
                    new KESelection(self.document).selectElement(new Node(selEl));
                    return;
                }
            }
            // IE doesn't support selecting the entire table row/cell, move the selection into cells, e.g.
            // <table><tbody><tr>[<td>cell</b></td>... => <table><tbody><tr><td>[cell</td>...
            if (self.startContainer[0].nodeType == KEN.NODE_ELEMENT &&
                self.startContainer._4e_name() in nonCells
                || self.endContainer[0].nodeType == KEN.NODE_ELEMENT &&
                self.endContainer._4e_name() in nonCells) {
                self.shrink(KER.SHRINK_ELEMENT, TRUE);
            }

            var bookmark = self.createBookmark(),
                // Create marker tags for the start and end boundaries.
                startNode = bookmark.startNode,
                endNode;
            if (!collapsed)
                endNode = bookmark.endNode;

            // Create the main range which will be used for the selection.
            var ieRange = self.document.body.createTextRange();

            // Position the range at the start boundary.
            ieRange.moveToElementText(startNode[0]);
            //跳过开始 bookmark 标签
            ieRange.moveStart('character', 1);

            if (endNode) {
                // Create a tool range for the end.
                var ieRangeEnd = self.document.body.createTextRange();
                // Position the tool range at the end.
                ieRangeEnd.moveToElementText(endNode[0]);
                // Move the end boundary of the main range to match the tool range.
                ieRange.setEndPoint('EndToEnd', ieRangeEnd);
                ieRange.moveEnd('character', -1);
            }
            else {
                // The isStartMarkerAlone logic comes from V2. It guarantees that the lines
                // will expand and that the cursor will be blinking on the right place.
                // Actually, we are using this flag just to avoid using this hack in all
                // situations, but just on those needed.
                var next = startNode[0].nextSibling;
                while (next && !notWhitespaces(next)) {
                    next = next.nextSibling;
                }
                isStartMarkerAlone =
                    (
                        !( next && next.nodeValue && next.nodeValue.match(fillerTextRegex) )     // already a filler there?
                            && ( forceExpand
                            ||
                            !startNode[0].previousSibling
                            ||
                            (
                                startNode[0].previousSibling &&
                                    DOM._4e_name(startNode[0].previousSibling) == 'br'
                                )
                            )
                        );

                // Append a temporary <span>&#65279;</span> before the selection.
                // This is needed to avoid IE destroying selections inside empty
                // inline elements, like <b></b> (#253).
                // It is also needed when placing the selection right after an inline
                // element to avoid the selection moving inside of it.
                dummySpan = self.document.createElement('span');
                dummySpan.innerHTML = '&#65279;';	// Zero Width No-Break Space (U+FEFF). See #1359.
                dummySpan = new Node(dummySpan);
                DOM.insertBefore(dummySpan[0], startNode[0]);
                if (isStartMarkerAlone) {
                    // To expand empty blocks or line spaces after <br>, we need
                    // instead to have any char, which will be later deleted using the
                    // selection.
                    // \ufeff = Zero Width No-Break Space (U+FEFF). (#1359)
                    DOM.insertBefore(self.document.createTextNode('\ufeff'), startNode[0]);
                }
            }

            // Remove the markers (reset the position, because of the changes in the DOM tree).
            self.setStartBefore(startNode);
            startNode._4e_remove();

            if (collapsed) {
                if (isStartMarkerAlone) {
                    // Move the selection start to include the temporary \ufeff.
                    ieRange.moveStart('character', -1);
                    ieRange.select();
                    // Remove our temporary stuff.
                    self.document.selection.clear();
                } else
                    ieRange.select();
                if (dummySpan) {
                    self.moveToPosition(dummySpan, KER.POSITION_BEFORE_START);
                    dummySpan._4e_remove();
                }
            }
            else {
                self.setEndBefore(endNode);
                endNode._4e_remove();
                ieRange.select();
            }
            // this.document.fire('selectionchange');
        } : function() {
        var self = this,startContainer = self.startContainer;

        // If we have a collapsed range, inside an empty element, we must add
        // something to it, otherwise the caret will not be visible.
        if (self.collapsed && startContainer[0].nodeType == KEN.NODE_ELEMENT && !startContainer[0].childNodes.length)
            startContainer[0].appendChild(self.document.createTextNode(""));

        var nativeRange = self.document.createRange();
        nativeRange.setStart(startContainer[0], self.startOffset);

        try {
            nativeRange.setEnd(self.endContainer[0], self.endOffset);
        } catch (e) {
            // There is a bug in Firefox implementation (it would be too easy
            // otherwise). The new start can't be after the end (W3C says it can).
            // So, let's create a new range and collapse it to the desired point.
            if (e.toString().indexOf('NS_ERROR_ILLEGAL_VALUE') >= 0) {
                self.collapse(TRUE);
                nativeRange.setEnd(self.endContainer[0], self.endOffset);
            }
            else
                throw( e );
        }

        var selection = getSelection(self.document).getNative();
        selection.removeAllRanges();
        selection.addRange(nativeRange);
    };


    function getSelection(doc) {
        var sel = new KESelection(doc);
        return ( !sel || sel.isInvalid ) ? NULL : sel;
    }

    KESelection.getSelection = getSelection;

    /**
     * 监控选择区域变化
     * @param editor
     */
    function monitorAndFix(editor) {
        var doc = editor.document,
            body = new Node(doc.body),
            html = new Node(doc.documentElement);

        if (UA.ie) {
            //ie 焦点管理不行 ,编辑器 iframe 失去焦点，选择区域/光标位置也丢失了
            //ie中事件都是同步，focus();xx(); 会立即触发事件处理函数，然后再运行xx();

            // In IE6/7 the blinking cursor appears, but contents are
            // not editable. (#5634)
            //终于和ck同步了，我也发现了这个bug，哈哈,ck3.3.2解决
            if (UA.ie < 8 ||
                //ie8 的 7 兼容模式
                document.documentMode == 7) {
                // The 'click' event is not fired when clicking the
                // scrollbars, so we can use it to check whether
                // the empty space following <body> has been clicked.
                html.on('click', function(evt) {
                    if (DOM._4e_name(evt.target) === "html")
                        editor.getSelection().getRanges()[ 0 ].select();
                });
            }


            // Other browsers don't loose the selection if the
            // editor document loose the focus. In IE, we don't
            // have support for it, so we reproduce it here, other
            // than firing the selection change event.

            var savedRange,
                saveEnabled,
                //2010-10-08 import from ckeditor 3.4.1
                //ie 点击(mousedown-focus-mouseup)空白处，不保留原有的 selection
                restoreEnabled = 1;

            // Listening on document element ensures that
            // scrollbar is included. (#5280)
            html.on('mousedown', function () {
                // Lock restore selection now, as we have
                // a followed 'click' event which introduce
                // new selection. (#5735)
                //点击时不要恢复了，点击就意味着原来的选择区域作废
                restoreEnabled = 0;
                //console.log("html mousedown");
            });

            html.on('mouseup', function () {
                restoreEnabled = 1;
                //console.log("html mouseup");
            });
            //事件顺序
            // 1.body mousedown
            // 2.html mousedown
            // body  blur
            // window blur
            // 3.body focusin
            // 4.body focus
            // 5.window focus
            // 6.body mouseup
            // 7.body mousedown
            // 8.body click
            // 9.html click
            // 10.doc click

            // "onfocusin" is fired before "onfocus". It makes it
            // possible to restore the selection before click
            // events get executed.
            body.on('focusin', function(evt) {
                //S.log(restoreEnabled);
                // If there are elements with layout they fire this event but
                // it must be ignored to allow edit its contents #4682
                if (DOM._4e_name(evt.target) != 'body')
                    return;

                //console.log("body focusin :" + restoreEnabled);
                // If we have saved a range, restore it at this
                // point.
                if (savedRange) {
                    // Well not break because of this.
                    try {
                        restoreEnabled && savedRange.select();
                    }
                    catch (e) {
                    }

                    savedRange = NULL;
                }
            });

            body.on('focus', function() {
                //S.log("body focus");
                // Enable selections to be saved.
                saveEnabled = TRUE;
                saveSelection();
            });

            body.on('beforedeactivate', function(evt) {
                // Ignore this event if it's caused by focus switch between
                // internal editable control type elements, e.g. layouted paragraph. (#4682)
                if (evt.relatedTarget)
                    return;
                //console.log("body beforedeactivate");
                // Disable selections from being saved.
                saveEnabled = FALSE;
                restoreEnabled = 1;
            });

            // IE before version 8 will leave cursor blinking inside the document after
            // editor blurred unless we clean up the selection. (#4716)
            //if (UA.ie < 8) {
            Event.on(DOM._4e_getWin(doc), 'blur', function() {
                //console.log("win blur");
                //把选择区域与光标清除                               
                doc && doc.selection.empty();
            });
            /*
             Event.on(body, 'blur', function() {

             });

             Event.on(DOM._4e_getWin(doc), 'focus', function() {

             });
             Event.on(doc, 'click', function() {

             });
             body.on('click', function() {

             });
             html.on('click', function() {

             });*/
            //}

            // IE fires the "selectionchange" event when clicking
            // inside a selection. We don't want to capture that.
            body.on('mousedown', function() {
                //console.log("body mousedown");
                disableSave();
            });
            body.on('mouseup', function() {
                //console.log("body mouseup");
                saveEnabled = TRUE;
                setTimeout(function() {
                    saveSelection(TRUE);
                }, 0);
            });
            function disableSave() {
                saveEnabled = FALSE;
                //console.log("disableSave");
            }

            /**
             *
             * @param {boolean=} testIt
             */
            function saveSelection(testIt) {
                //console.log("saveSelection");
                if (saveEnabled) {
                    var doc = editor.document,
                        sel = editor.getSelection(),
                        type = sel && sel.getType(),
                        nativeSel = sel && sel.getNative();

                    // There is a very specific case, when clicking
                    // inside a text selection. In that case, the
                    // selection collapses at the clicking point,
                    // but the selection object remains in an
                    // unknown state, making createRange return a
                    // range at the very start of the document. In
                    // such situation we have to test the range, to
                    // be sure it's valid.
                    //右键时，若前一个操作选中，则该次一直为None
                    if (testIt && nativeSel && type == KES.SELECTION_NONE) {
                        // The "InsertImage" command can be used to
                        // test whether the selection is good or not.
                        // If not, it's enough to give some time to
                        // IE to put things in order for us.
                        if (!doc.queryCommandEnabled('InsertImage')) {
                            setTimeout(function() {
                                //console.log("retry");
                                saveSelection(TRUE);
                            }, 50);
                            return;
                        }
                    }

                    // Avoid saving selection from within text input. (#5747)
                    var parentTag;
                    if (nativeSel && type == KES.SELECTION_TEXT
                        && ( parentTag = DOM._4e_name(nativeSel.createRange().parentElement()))
                        && parentTag in { "input": 1, "textarea": 1 }) {
                        return;
                    }
                    savedRange = nativeSel && sel.getRanges()[ 0 ];
                    //console.log("save range : " + savedRange.collapsed);
                    editor._monitor();
                }
            }

            body.on('keydown', disableSave);
            body.on('keyup', function() {
                saveEnabled = TRUE;
                saveSelection();
            });

            // IE is the only to provide the "selectionchange"
            // event.
            // 注意：ie右键短暂点击并不能改变选择范围
            Event.on(doc, 'selectionchange', saveSelection);


        } else {
            // In other browsers, we make the selection change
            // check based on other events, like clicks or keys
            // press.
            Event.on(doc, 'mouseup', editor._monitor, editor);
            Event.on(doc, 'keyup', editor._monitor, editor);
        }

        // List of elements in which has no way to move editing focus outside.
        var nonExitableElementNames = { "table":1,"pre":1 };

        // Matching an empty paragraph at the end of document.
        var emptyParagraphRegexp = /\s*<(p|div|address|h\d|center)[^>]*>\s*(?:<br[^>]*>|&nbsp;|\u00A0|&#160;)?\s*(:?<\/\1>)?(?=\s*$|<\/body>)/gi;


        function isBlankParagraph(block) {
            return block._4e_outerHtml().match(emptyParagraphRegexp);
        }

        var isNotWhitespace = KE.Walker.whitespaces(TRUE);//,
        //isNotBookmark = KE.Walker.bookmark(FALSE, TRUE);

        /**
         * 如果选择了body下面的直接inline元素，则新建p
         */
        editor.on("selectionChange", function(ev) {
            var path = ev.path,
                selection = ev.selection,
                range = selection && selection.getRanges()[0],
                blockLimit = path.blockLimit;
            if (!range) return;
            if (range.collapse
                && !path.block
                && blockLimit._4e_name() == "body") {
                var fixedBlock = range.fixBlock(TRUE, "p");
                //firefox选择区域变化时自动添加空行，不要出现裸的text
                if (isBlankParagraph(fixedBlock)) {
                    var element = fixedBlock._4e_next(isNotWhitespace);
                    if (element &&
                        element[0].nodeType == KEN.NODE_ELEMENT &&
                        !nonExitableElementNames[ element._4e_name() ]) {
                        range.moveToElementEditablePosition(element);
                        fixedBlock._4e_remove();
                    } else {
                        element = fixedBlock._4e_previous(isNotWhitespace);
                        if (element &&
                            element[0].nodeType == KEN.NODE_ELEMENT &&
                            !nonExitableElementNames[element._4e_name()]) {
                            range.moveToElementEditablePosition(element,
                                //空行的话还是要移到开头的
                                isBlankParagraph(element) ? FALSE : TRUE);
                            fixedBlock._4e_remove();
                        }
                    }
                }
                range.select();
                if (!UA.ie) {
                    //选择区域变了，通知其他插件更新状态
                    editor.notifySelectionChange();
                }
            }

        });
    }

    KE.Selection = KESelection;
    KE["Selection"] = KESelection;
    var SelectionP = KESelection.prototype;
    KE.Utils.extern(SelectionP, {
        "getNative":SelectionP.getNative,
        "getType":SelectionP.getType,
        "getRanges":SelectionP.getRanges,
        "getStartElement":SelectionP.getStartElement,
        "getSelectedElement":SelectionP.getSelectedElement,
        "reset":SelectionP.reset,
        "selectElement":SelectionP.selectElement,
        "selectRanges":SelectionP.selectRanges,
        "createBookmarks2":SelectionP.createBookmarks2,
        "createBookmarks":SelectionP.createBookmarks,
        "getCommonAncestor":SelectionP.getCommonAncestor,
        "scrollIntoView":SelectionP.scrollIntoView,
        "selectBookmarks":SelectionP.selectBookmarks,
        "removeAllRanges":SelectionP.removeAllRanges
    });


    KE.on("instanceCreated", function(ev) {
        var editor = ev.editor;
        monitorAndFix(editor);
    });
});
/**
 * modified from ckeditor for kissy editor,use style to gen element and wrap range's elements
 * @author: <yiminghe@gmail.com>
 */
/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
KISSY.Editor.add("styles", function(KE) {

    var TRUE = true,
        FALSE = false,
        NULL = null,
        S = KISSY,
        DOM = S.DOM,
        /**
         * enum for style type
         * @enum {number}
         */
        KEST = {
            STYLE_BLOCK:1,
            STYLE_INLINE:2,
            STYLE_OBJECT:3
        },
        KER = KE.RANGE,
        KESelection = KE.Selection,
        KEN = KE.NODE,
        KEP = KE.POSITION,
        KERange = KE.Range,
        //Walker = KE.Walker,
        Node = S.Node,
        UA = S.UA,
        ElementPath = KE.ElementPath,
        blockElements = {
            "address":1,
            "div":1,
            "h1":1,
            "h2":1,
            "h3":1,
            "h4":1,
            "h5":1,
            "h6":1,
            "p":1,
            "pre":1
        },
        objectElements = {
            //why? a should be same to inline? 但是不能互相嵌套
            //a:1,
            "embed":1,
            "hr":1,
            "img":1,
            "li":1,
            "object":1,
            "ol":1,
            "table":1,
            "td":1,
            "tr":1,
            "th":1,
            "ul":1,
            "dl":1,
            "dt":1,
            "dd":1,
            "form":1
        },
        semicolonFixRegex = /\s*(?:;\s*|$)/,
        varRegex = /#\((.+?)\)/g;

    KE.STYLE = KEST;
    function replaceVariables(list, variablesValues) {
        for (var item in list) {
            list[ item ] = list[ item ].replace(varRegex, function(match, varName) {
                return variablesValues[ varName ];
            });
        }
    }

    /**
     * @constructor
     * @param styleDefinition {Object}
     * @param variablesValues {Object}
     */
    function KEStyle(styleDefinition, variablesValues) {
        if (variablesValues) {
            styleDefinition = S.clone(styleDefinition);
            replaceVariables(styleDefinition["attributes"], variablesValues);
            replaceVariables(styleDefinition["styles"], variablesValues);
        }

        var element = this["element"] = this.element = ( styleDefinition["element"] || '*' ).toLowerCase();

        this["type"] = this.type = ( element == '#' || blockElements[ element ] ) ?
            KEST.STYLE_BLOCK
            : objectElements[ element ] ?
            KEST.STYLE_OBJECT : KEST.STYLE_INLINE;

        this._ = {
            "definition" : styleDefinition
        };
    }

    /**
     * @this {KEStyle}
     * @param {Document} document
     * @param {boolean=} remove
     */
    function applyStyle(document, remove) {
        // Get all ranges from the selection.
        var self = this,
            func = remove ? self.removeFromRange : self.applyToRange;
        // Apply the style to the ranges.
        //ie select 选中期间document得不到range
        document.body.focus();
        var selection = new KESelection(document);
        // Bookmark the range so we can re-select it after processing.
        var ranges = selection.getRanges();
        for (var i = 0; i < ranges.length; i++) {
            //格式化后，range进入格式标签内
            func.call(self, ranges[ i ]);
        }
        selection.selectRanges(ranges);
    }

    KEStyle.prototype = {
        apply : function(document) {
            applyStyle.call(this, document, FALSE);
        },

        remove : function(document) {
            applyStyle.call(this, document, TRUE);
        },

        applyToRange : function(range) {
            var self = this;
            return ( self.applyToRange =
                this.type == KEST.STYLE_INLINE ?
                    applyInlineStyle
                    : self.type == KEST.STYLE_BLOCK ?
                    applyBlockStyle
                    : self.type == KEST.STYLE_OBJECT ?
                    NULL
                    //yiminghe note:no need!
                    //applyObjectStyle
                    : NULL ).call(self, range);
        },

        removeFromRange : function(range) {
            var self = this;
            return ( self.removeFromRange =
                self.type == KEST.STYLE_INLINE ?
                    removeInlineStyle
                    : NULL ).call(self, range);
        },

        applyToObject : function(element) {
            setupElement(element, this);
        },
        // Checks if an element, or any of its attributes, is removable by the
        // current style definition.
        checkElementRemovable : function(element, fullMatch) {
            if (!element)
                return FALSE;

            var def = this._.definition,
                attribs;

            // If the element name is the same as the style name.
            if (element._4e_name() == this.element) {
                // If no attributes are defined in the element.
                if (!fullMatch && !element._4e_hasAttributes())
                    return TRUE;

                attribs = getAttributesForComparison(def);

                if (attribs["_length"]) {
                    for (var attName in attribs) {
                        if (attName == '_length')
                            continue;

                        var elementAttr = element.attr(attName) || '';
                        if (attName == 'style' ?
                            compareCssText(attribs[ attName ],
                                normalizeCssText(elementAttr, FALSE))
                            : attribs[ attName ] == elementAttr) {
                            if (!fullMatch)
                                return TRUE;
                        }
                        else if (fullMatch)
                            return FALSE;
                    }
                    if (fullMatch)
                        return TRUE;
                }
                else
                    return TRUE;
            }

            // Check if the element can be somehow overriden.
            var override = getOverrides(this)[ element._4e_name() ];

            if (override) {
                // If no attributes have been defined, remove the element.
                if (!( attribs = override.attributes ))
                    return TRUE;

                for (var i = 0; i < attribs.length; i++) {
                    attName = attribs[i][0];
                    var actualAttrValue = element.attr(attName);
                    if (actualAttrValue) {
                        var attValue = attribs[i][1];
                        // Remove the attribute if:
                        //    - The override definition value is NULL;
                        //    - The override definition value is a string that
                        //      matches the attribute value exactly.
                        //    - The override definition value is a regex that
                        //      has matches in the attribute value.
                        if (attValue === NULL ||
                            ( typeof attValue == 'string'
                                && actualAttrValue == attValue ) ||
                            attValue.test && attValue.test(actualAttrValue))
                            return TRUE;
                    }
                }
            }
            return FALSE;
        },

        /**
         * Get the style state inside an element path. Returns "TRUE" if the
         * element is active in the path.
         */
        checkActive : function(elementPath) {
            switch (this.type) {
                case KEST.STYLE_BLOCK :
                    return this.checkElementRemovable(elementPath.block
                        || elementPath.blockLimit, TRUE);

                case KEST.STYLE_OBJECT :
                case KEST.STYLE_INLINE :

                    var elements = elementPath.elements;

                    for (var i = 0, element; i < elements.length; i++) {
                        element = elements[ i ];

                        if (this.type == KEST.STYLE_INLINE
                            && ( DOM._4e_equals(element, elementPath.block)
                            || DOM._4e_equals(element, elementPath.blockLimit) ))
                            continue;

                        if (this.type == KEST.STYLE_OBJECT
                            && !( element._4e_name() in objectElements ))
                            continue;

                        if (this.checkElementRemovable(element, TRUE))
                            return TRUE;
                    }
            }
            return FALSE;
        }

    };

    KEStyle.getStyleText = function(styleDefinition) {
        // If we have already computed it, just return it.
        var stylesDef = styleDefinition._ST;
        if (stylesDef)
            return stylesDef;

        stylesDef = styleDefinition["styles"];

        // Builds the StyleText.
        var stylesText = ( styleDefinition["attributes"]
            && styleDefinition["attributes"][ 'style' ] ) || '',
            specialStylesText = '';

        if (stylesText.length)
            stylesText = stylesText.replace(semicolonFixRegex, ';');

        for (var style in stylesDef) {
            var styleVal = stylesDef[ style ],
                text = ( style + ':' + styleVal ).replace(semicolonFixRegex, ';');

            // Some browsers don't support 'inherit' property value, leave them intact. (#5242)
            if (styleVal == 'inherit')
                specialStylesText += text;
            else
                stylesText += text;
        }

        // Browsers make some changes to the style when applying them. So, here
        // we normalize it to the browser format.
        if (stylesText.length)
            stylesText = normalizeCssText(stylesText);

        stylesText += specialStylesText;

        // Return it, saving it to the next request.
        return ( styleDefinition._ST = stylesText );
    };

    function getElement(style, targetDocument) {
        var el,
            //def = style._.definition,
            elementName = style["element"];

        // The "*" element name will always be a span for this function.
        if (elementName == '*')
            elementName = 'span';

        // Create the element.
        el = new Node(targetDocument.createElement(elementName));

        return setupElement(el, style);
    }

    function setupElement(el, style) {
        var def = style._["definition"],
            attributes = def["attributes"],
            styles = KEStyle.getStyleText(def);

        // Assign all defined attributes.
        if (attributes) {
            for (var att in attributes) {
                el.attr(att, attributes[ att ]);
            }
        }

        // Assign all defined styles.

        if (styles)
            el[0].style.cssText = styles;

        return el;
    }

    function applyBlockStyle(range) {
        // Serializible bookmarks is needed here since
        // elements may be merged.
        var bookmark = range.createBookmark(TRUE),
            iterator = range.createIterator();
        iterator.enforceRealBlocks = TRUE;

        // make recognize <br /> tag as a separator in ENTER_BR mode (#5121)
        //if (this._.enterMode)
        iterator.enlargeBr = TRUE;//( this._.enterMode != CKEDITOR.ENTER_BR );

        var block, doc = range.document;
        // Only one =
        while (( block = iterator.getNextParagraph() )) {
            var newBlock = getElement(this, doc);
            replaceBlock(block, newBlock);
        }
        range.moveToBookmark(bookmark);
    }

    // Wrapper function of String::replace without considering of head/tail bookmarks nodes.
    function replace(str, regexp, replacement) {
        var headBookmark = '',
            tailBookmark = '';

        str = str.replace(/(^<span[^>]+_ke_bookmark.*?\/span>)|(<span[^>]+_ke_bookmark.*?\/span>$)/gi,
            function(str, m1, m2) {
                m1 && ( headBookmark = m1 );
                m2 && ( tailBookmark = m2 );
                return '';
            });
        return headBookmark + str.replace(regexp, replacement) + tailBookmark;
    }

    /**
     * Converting from a non-PRE block to a PRE block in formatting operations.
     */
    function toPre(block, newBlock) {
        // First trim the block content.
        var preHtml = block.html();

        // 1. Trim head/tail spaces, they're not visible.
        preHtml = replace(preHtml, /(?:^[ \t\n\r]+)|(?:[ \t\n\r]+$)/g, '');
        // 2. Delete ANSI whitespaces immediately before and after <BR> because
        //    they are not visible.
        preHtml = preHtml.replace(/[ \t\r\n]*(<br[^>]*>)[ \t\r\n]*/gi, '$1');
        // 3. Compress other ANSI whitespaces since they're only visible as one
        //    single space previously.
        // 4. Convert &nbsp; to spaces since &nbsp; is no longer needed in <PRE>.
        preHtml = preHtml.replace(/([ \t\n\r]+|&nbsp;)/g, ' ');
        // 5. Convert any <BR /> to \n. This must not be done earlier because
        //    the \n would then get compressed.
        preHtml = preHtml.replace(/<br\b[^>]*>/gi, '\n');

        // Krugle: IE normalizes innerHTML to <pre>, breaking whitespaces.
        if (UA.ie) {
            var temp = block[0].ownerDocument.createElement('div');
            temp.appendChild(newBlock[0]);
            newBlock[0].outerHTML = '<pre>' + preHtml + '</pre>';
            newBlock = new Node(temp.firstChild);
            newBlock._4e_remove();
        }
        else
            newBlock.html(preHtml);

        return newBlock;
    }

    /**
     * Split into multiple <pre> blocks separated by double line-break.
     * @param preBlock
     */
    function splitIntoPres(preBlock) {
        // Exclude the ones at header OR at tail,
        // and ignore bookmark content between them.
        var duoBrRegex = /(\S\s*)\n(?:\s|(<span[^>]+_ck_bookmark.*?\/span>))*\n(?!$)/gi,
            //blockName = preBlock._4e_name(),
            splittedHtml = replace(preBlock._4e_outerHtml(),
                duoBrRegex,
                function(match, charBefore, bookmark) {
                    return charBefore + '</pre>' + bookmark + '<pre>';
                });

        var pres = [];
        splittedHtml.replace(/<pre\b.*?>([\s\S]*?)<\/pre>/gi,
            function(match, preContent) {
                pres.push(preContent);
            });
        return pres;
    }

    // Replace the original block with new one, with special treatment
    // for <pre> blocks to make sure content format is well preserved, and merging/splitting adjacent
    // when necessary.(#3188)
    function replaceBlock(block, newBlock) {
        var newBlockIsPre = newBlock._4e_name == ('pre'),
            blockIsPre = block._4e_name == ('pre'),
            isToPre = newBlockIsPre && !blockIsPre,
            isFromPre = !newBlockIsPre && blockIsPre;

        if (isToPre)
            newBlock = toPre(block, newBlock);
        else if (isFromPre)
        // Split big <pre> into pieces before start to convert.
            newBlock = fromPres(splitIntoPres(block), newBlock);
        else
            block._4e_moveChildren(newBlock);

        block[0].parentNode.replaceChild(newBlock[0], block[0]);
        if (newBlockIsPre) {
            // Merge previous <pre> blocks.
            mergePre(newBlock);
        }
    }

    /**
     * Merge a <pre> block with a previous sibling if available.
     */
    function mergePre(preBlock) {
        var previousBlock;
        if (!( ( previousBlock = preBlock._4e_previousSourceNode(TRUE, KEN.NODE_ELEMENT) )
            && previousBlock._4e_name() == 'pre' ))
            return;

        // Merge the previous <pre> block contents into the current <pre>
        // block.
        //
        // Another thing to be careful here is that currentBlock might contain
        // a '\n' at the beginning, and previousBlock might contain a '\n'
        // towards the end. These new lines are not normally displayed but they
        // become visible after merging.
        var mergedHtml = replace(previousBlock.html(), /\n$/, '') + '\n\n' +
            replace(preBlock.html(), /^\n/, '');

        // Krugle: IE normalizes innerHTML from <pre>, breaking whitespaces.
        if (UA.ie)
            preBlock[0].outerHTML = '<pre>' + mergedHtml + '</pre>';
        else
            preBlock.html(mergedHtml);

        previousBlock._4e_remove();
    }

    /**
     * Converting a list of <pre> into blocks with format well preserved.
     */
    function fromPres(preHtmls, newBlock) {
        var docFrag = newBlock[0].ownerDocument.createDocumentFragment();
        for (var i = 0; i < preHtmls.length; i++) {
            var blockHtml = preHtmls[ i ];

            // 1. Trim the first and last line-breaks immediately after and before <pre>,
            // they're not visible.
            blockHtml = blockHtml.replace(/(\r\n|\r)/g, '\n');
            blockHtml = replace(blockHtml, /^[ \t]*\n/, '');
            blockHtml = replace(blockHtml, /\n$/, '');
            // 2. Convert spaces or tabs at the beginning or at the end to &nbsp;
            blockHtml = replace(blockHtml, /^[ \t]+|[ \t]+$/g, function(match, offset) {
                if (match.length == 1)    // one space, preserve it
                    return '&nbsp;';
                else if (!offset)        // beginning of block
                    return new Array(match.length).join('&nbsp;') + ' ';
                else                // end of block
                    return ' ' + new Array(match.length).join('&nbsp;');
            });

            // 3. Convert \n to <BR>.
            // 4. Convert contiguous (i.e. non-singular) spaces or tabs to &nbsp;
            blockHtml = blockHtml.replace(/\n/g, '<br>');
            blockHtml = blockHtml.replace(/[ \t]{2,}/g,
                function (match) {
                    return new Array(match.length).join('&nbsp;') + ' ';
                });

            var newBlockClone = newBlock._4e_clone();
            newBlockClone.html(blockHtml);
            docFrag.appendChild(newBlockClone[0]);
        }
        return docFrag;
    }

    /**
     * @this {KEStyle}
     * @param range {KISSY.Editor.Range}
     */
    function applyInlineStyle(range) {
        var self = this,document = range.document;

        if (range.collapsed) {
            // Create the element to be inserted in the DOM.
            var collapsedElement = getElement(this, document);
            // Insert the empty element into the DOM at the range position.
            range.insertNode(collapsedElement);
            // Place the selection right inside the empty element.
            range.moveToPosition(collapsedElement, KER.POSITION_BEFORE_END);
            return;
        }
        var elementName = this["element"],
            def = this._["definition"],
            isUnknownElement,
            // Get the DTD definition for the element. Defaults to "span".
            dtd = KE.XHTML_DTD[ elementName ]
                || ( isUnknownElement = TRUE,KE.XHTML_DTD["span"] );

        // Bookmark the range so we can re-select it after processing.
        var bookmark = range.createBookmark();

        // Expand the range.

        range.enlarge(KER.ENLARGE_ELEMENT);
        range.trim();
        // Get the first node to be processed and the last, which concludes the
        // processing.
        var boundaryNodes = range.createBookmark(),
            firstNode = boundaryNodes.startNode,
            lastNode = boundaryNodes.endNode,
            currentNode = firstNode,
            styleRange;

        while (currentNode && currentNode[0]) {
            var applyStyle = FALSE;

            if (DOM._4e_equals(currentNode, lastNode)) {
                currentNode = NULL;
                applyStyle = TRUE;
            }
            else {
                var nodeType = currentNode[0].nodeType,
                    nodeName = nodeType == KEN.NODE_ELEMENT ?
                        currentNode._4e_name() : NULL;

                if (nodeName && currentNode.attr('_ke_bookmark')) {
                    currentNode = currentNode._4e_nextSourceNode(TRUE);
                    continue;
                }

                // Check if the current node can be a child of the style element.
                if (!nodeName || ( dtd[ nodeName ]
                    && ( currentNode._4e_position(lastNode) |
                    ( KEP.POSITION_PRECEDING |
                        KEP.POSITION_IDENTICAL |
                        KEP.POSITION_IS_CONTAINED) )
                    == ( KEP.POSITION_PRECEDING +
                    KEP.POSITION_IDENTICAL +
                    KEP.POSITION_IS_CONTAINED )
                    && ( !def["childRule"] || def["childRule"](currentNode) ) )) {
                    var currentParent = currentNode.parent();

                    // Check if the style element can be a child of the current
                    // node parent or if the element is not defined in the DTD.
                    if (currentParent && currentParent[0]
                        && ( ( KE.XHTML_DTD[currentParent._4e_name()] ||
                        KE.XHTML_DTD["span"] )[ elementName ] ||
                        isUnknownElement )
                        && ( !def["parentRule"] || def["parentRule"](currentParent) )) {
                        // This node will be part of our range, so if it has not
                        // been started, place its start right before the node.
                        // In the case of an element node, it will be included
                        // only if it is entirely inside the range.
                        if (!styleRange &&
                            ( !nodeName
                                || !KE.XHTML_DTD.$removeEmpty[ nodeName ]
                                || ( currentNode._4e_position(lastNode) |
                                ( KEP.POSITION_PRECEDING |
                                    KEP.POSITION_IDENTICAL |
                                    KEP.POSITION_IS_CONTAINED ))
                                ==
                                ( KEP.POSITION_PRECEDING +
                                    KEP.POSITION_IDENTICAL +
                                    KEP.POSITION_IS_CONTAINED )
                                )) {
                            styleRange = new KERange(document);
                            styleRange.setStartBefore(currentNode);
                        }

                        // Non element nodes, or empty elements can be added
                        // completely to the range.
                        if (nodeType == KEN.NODE_TEXT ||
                            ( nodeType == KEN.NODE_ELEMENT &&
                                !currentNode[0].childNodes.length )) {
                            var includedNode = currentNode,
                                parentNode;

                            // This node is about to be included completelly, but,
                            // if this is the last node in its parent, we must also
                            // check if the parent itself can be added completelly
                            // to the range.
                            while (!includedNode[0].nextSibling
                                && ( parentNode = includedNode.parent(),
                                dtd[ parentNode._4e_name() ] )
                                && ( parentNode._4e_position(firstNode) |
                                KEP.POSITION_FOLLOWING |
                                KEP.POSITION_IDENTICAL |
                                KEP.POSITION_IS_CONTAINED) ==
                                ( KEP.POSITION_FOLLOWING +
                                    KEP.POSITION_IDENTICAL +
                                    KEP.POSITION_IS_CONTAINED )
                                && ( !def["childRule"] ||
                                def["childRule"](parentNode) )) {
                                includedNode = parentNode;
                            }

                            styleRange.setEndAfter(includedNode);

                            // If the included node still is the last node in its
                            // parent, it means that the parent can't be included
                            // in this style DTD, so apply the style immediately.
                            if (!includedNode[0].nextSibling)
                                applyStyle = TRUE;

                        }
                    }
                    else
                        applyStyle = TRUE;
                }
                else
                    applyStyle = TRUE;

                // Get the next node to be processed.
                currentNode = currentNode._4e_nextSourceNode();
            }

            // Apply the style if we have something to which apply it.
            if (applyStyle && styleRange && !styleRange.collapsed) {
                // Build the style element, based on the style object definition.
                var styleNode = getElement(self, document),

                    // Get the element that holds the entire range.
                    parent = styleRange.getCommonAncestor();

                // Loop through the parents, removing the redundant attributes
                // from the element to be applied.
                while (styleNode && parent && styleNode[0] && parent[0]) {
                    if (parent._4e_name() == elementName) {
                        for (var attName in def["attributes"]) {
                            if (styleNode.attr(attName) == parent.attr(attName))
                                styleNode[0].removeAttribute(attName);
                        }
                        //bug notice add by yiminghe@gmail.com
                        //<span style="font-size:70px"><span style="font-size:30px">xcxx</span></span>
                        //下一次格式xxx为70px
                        //var exit = FALSE;
                        for (var styleName in def["styles"]) {
                            if (styleNode._4e_style(styleName) ==
                                parent._4e_style(styleName)) {
                                styleNode._4e_style(styleName, "");
                            }
                        }

                        if (!styleNode._4e_hasAttributes()) {
                            styleNode = NULL;
                            break;
                        }
                    }

                    parent = parent.parent();
                }

                if (styleNode) {
                    // Move the contents of the range to the style element.
                    styleNode[0].appendChild(styleRange.extractContents());

                    // Here we do some cleanup, removing all duplicated
                    // elements from the style element.
                    removeFromInsideElement(self, styleNode);

                    // Insert it into the range position (it is collapsed after
                    // extractContents.
                    styleRange.insertNode(styleNode);

                    // Let's merge our new style with its neighbors, if possible.
                    styleNode._4e_mergeSiblings();

                    // As the style system breaks text nodes constantly, let's normalize
                    // things for performance.
                    // With IE, some paragraphs get broken when calling normalize()
                    // repeatedly. Also, for IE, we must normalize body, not documentElement.
                    // IE is also known for having a "crash effect" with normalize().
                    // We should try to normalize with IE too in some way, somewhere.
                    if (!UA.ie)
                        styleNode[0].normalize();
                }

                // Style applied, let's release the range, so it gets
                // re-initialization in the next loop.
                styleRange = NULL;
            }
        }

        firstNode._4e_remove();
        lastNode._4e_remove();
        range.moveToBookmark(bookmark);
        // Minimize the result range to exclude empty text nodes. (#5374)
        range.shrink(KER.SHRINK_TEXT);

    }

    /**
     * @this {KEStyle}
     * @param range {KISSY.Editor.Range}
     */
    function removeInlineStyle(range) {
        /*
         * Make sure our range has included all "collpased" parent inline nodes so
         * that our operation logic can be simpler.
         */
        range.enlarge(KER.ENLARGE_ELEMENT);

        var bookmark = range.createBookmark(),
            startNode = bookmark.startNode;

        if (range.collapsed) {

            var startPath = new ElementPath(startNode.parent()),
                // The topmost element in elementspatch which we should jump out of.
                boundaryElement;


            for (var i = 0, element; i < startPath.elements.length
                && ( element = startPath.elements[i] ); i++) {
                /*
                 * 1. If it's collaped inside text nodes, try to remove the style from the whole element.
                 *
                 * 2. Otherwise if it's collapsed on element boundaries, moving the selection
                 *  outside the styles instead of removing the whole tag,
                 *  also make sure other inner styles were well preserverd.(#3309)
                 */
                if (element == startPath.block ||
                    element == startPath.blockLimit)
                    break;

                if (this.checkElementRemovable(element)) {
                    var endOfElement = range.checkBoundaryOfElement(element, KER.END),
                        startOfElement = !endOfElement &&
                            range.checkBoundaryOfElement(element, KER.START);
                    if (startOfElement || endOfElement) {
                        boundaryElement = element;
                        boundaryElement.match = startOfElement ? 'start' : 'end';
                    } else {
                        /*
                         * Before removing the style node, there may be a sibling to the style node
                         * that's exactly the same to the one to be removed. To the user, it makes
                         * no difference that they're separate entities in the DOM tree. So, merge
                         * them before removal.
                         */
                        element._4e_mergeSiblings();
                        //yiminghe:note,bug for ckeditor
                        //qc #3700 for chengyu(yiminghe)
                        //从word复制过来的已编辑文本无法使用粗体和斜体等功能取消
                        if (element._4e_name() == this.element)
                            removeFromElement(this, element);
                        else
                            removeOverrides(element,
                                getOverrides(this)[ element._4e_name() ]);
                    }
                }
            }

            // Re-create the style tree after/before the boundary element,
            // the replication start from bookmark start node to define the
            // new range.
            if (boundaryElement && boundaryElement[0]) {
                var clonedElement = startNode;
                for (i = 0; ; i++) {
                    var newElement = startPath.elements[ i ];
                    if (DOM._4e_equals(newElement, boundaryElement))
                        break;
                    // Avoid copying any matched element.
                    else if (newElement.match)
                        continue;
                    else
                        newElement = newElement._4e_clone();
                    newElement[0].appendChild(clonedElement[0]);
                    clonedElement = newElement;
                }
                DOM[ boundaryElement.match == 'start' ?
                    'insertBefore' : 'insertAfter' ](clonedElement[0], boundaryElement[0]);
            }
        } else {
            /*
             * Now our range isn't collapsed. Lets walk from the start node to the end
             * node via DFS and remove the styles one-by-one.
             */
            var endNode = bookmark.endNode,
                me = this;

            /*
             * Find out the style ancestor that needs to be broken down at startNode
             * and endNode.
             */
            function breakNodes() {
                var startPath = new ElementPath(startNode.parent()),
                    endPath = new ElementPath(endNode.parent()),
                    breakStart = NULL,
                    breakEnd = NULL;
                for (var i = 0; i < startPath.elements.length; i++) {
                    var element = startPath.elements[ i ];

                    if (element == startPath.block ||
                        element == startPath.blockLimit)
                        break;

                    if (me.checkElementRemovable(element))
                        breakStart = element;
                }
                for (i = 0; i < endPath.elements.length; i++) {
                    element = endPath.elements[ i ];

                    if (element == endPath.block ||
                        element == endPath.blockLimit)
                        break;

                    if (me.checkElementRemovable(element))
                        breakEnd = element;
                }

                if (breakEnd)
                    endNode._4e_breakParent(breakEnd);
                if (breakStart)
                    startNode._4e_breakParent(breakStart);
            }

            breakNodes();

            // Now, do the DFS walk.
            var currentNode = new Node(startNode[0].nextSibling);
            while (currentNode[0] !== endNode[0]) {
                /*
                 * Need to get the next node first because removeFromElement() can remove
                 * the current node from DOM tree.
                 */
                var nextNode = currentNode._4e_nextSourceNode();
                if (currentNode[0] &&
                    currentNode[0].nodeType == KEN.NODE_ELEMENT &&
                    this.checkElementRemovable(currentNode)) {
                    // Remove style from element or overriding element.
                    if (currentNode._4e_name() == this["element"])
                        removeFromElement(this, currentNode);
                    else
                        removeOverrides(currentNode,
                            getOverrides(this)[ currentNode._4e_name() ]);

                    /*
                     * removeFromElement() may have merged the next node with something before
                     * the startNode via mergeSiblings(). In that case, the nextNode would
                     * contain startNode and we'll have to call breakNodes() again and also
                     * reassign the nextNode to something after startNode.
                     */
                    if (nextNode[0].nodeType == KEN.NODE_ELEMENT &&
                        nextNode._4e_contains(startNode)) {
                        breakNodes();
                        nextNode = new Node(startNode[0].nextSibling);
                    }
                }
                currentNode = nextNode;
            }
        }
        range.moveToBookmark(bookmark);
    }

    // Turn inline style text properties into one hash.
    function parseStyleText(styleText) {
        var retval = {};
        styleText.replace(/&quot;/g, '"')
            .replace(/\s*([^ :;]+)\s*:\s*([^;]+)\s*(?=;|$)/g,
            function(match, name, value) {
                retval[ name ] = value;
            });
        return retval;
    }

    function compareCssText(source, target) {
        typeof source == 'string' && ( source = parseStyleText(source) );
        typeof target == 'string' && ( target = parseStyleText(target) );
        for (var name in source) {
            // Value 'inherit'  is treated as a wildcard,
            // which will match any value.
            if (!( name in target &&
                ( target[ name ] == source[ name ]
                    || source[ name ] == 'inherit'
                    || target[ name ] == 'inherit' ) )) {
                return FALSE;
            }
        }
        return TRUE;
    }

    /**
     *
     * @param {string} unparsedCssText
     * @param {boolean=} nativeNormalize
     */
    function normalizeCssText(unparsedCssText, nativeNormalize) {
        var styleText;
        if (nativeNormalize !== FALSE) {
            // Injects the style in a temporary span object, so the browser parses it,
            // retrieving its final format.
            var temp = document.createElement('span');
            temp.style.cssText = unparsedCssText;
            //temp.setAttribute('style', unparsedCssText);
            styleText = temp.style.cssText || '';
        }
        else
            styleText = unparsedCssText;

        // Shrinking white-spaces around colon and semi-colon (#4147).
        // Compensate tail semi-colon.
        return styleText.replace(/\s*([;:])\s*/, '$1')
            .replace(/([^\s;])$/, "$1;")
            .replace(/,\s+/g, ',')// Trimming spaces after comma (e.g. font-family name)(#4107).
            .toLowerCase();
    }

    function getAttributesForComparison(styleDefinition) {
        // If we have already computed it, just return it.
        var attribs = styleDefinition._AC;
        if (attribs)
            return attribs;

        attribs = {};

        var length = 0,

            // Loop through all defined attributes.
            styleAttribs = styleDefinition["attributes"];
        if (styleAttribs) {
            for (var styleAtt in styleAttribs) {
                length++;
                attribs[ styleAtt ] = styleAttribs[ styleAtt ];
            }
        }

        // Includes the style definitions.
        var styleText = KEStyle.getStyleText(styleDefinition);
        if (styleText) {
            if (!attribs[ 'style' ])
                length++;
            attribs[ 'style' ] = styleText;
        }

        // Appends the "length" information to the object.
        //防止被compiler优化
        attribs["_length"] = length;

        // Return it, saving it to the next request.
        return ( styleDefinition._AC = attribs );
    }


    /**
     * Get the the collection used to compare the elements and attributes,
     * defined in this style overrides, with other element. All information in
     * it is lowercased.
     * @param  style
     */
    function getOverrides(style) {
        if (style._.overrides)
            return style._.overrides;

        var overrides = ( style._.overrides = {} ),
            definition = style._.definition["overrides"];

        if (definition) {
            // The override description can be a string, object or array.
            // Internally, well handle arrays only, so transform it if needed.
            if (!S.isArray(definition))
                definition = [ definition ];

            // Loop through all override definitions.
            for (var i = 0; i < definition.length; i++) {
                var override = definition[i];
                var elementName;
                var overrideEl;
                var attrs;

                // If can be a string with the element name.
                if (typeof override == 'string')
                    elementName = override.toLowerCase();
                // Or an object.
                else {
                    elementName = override["element"] ?
                        override["element"].toLowerCase() :
                        style.element;
                    attrs = override["attributes"];
                }

                // We can have more than one override definition for the same
                // element name, so we attempt to simply append information to
                // it if it already exists.
                overrideEl = overrides[ elementName ] ||
                    ( overrides[ elementName ] = {} );

                if (attrs) {
                    // The returning attributes list is an array, because we
                    // could have different override definitions for the same
                    // attribute name.
                    var overrideAttrs = ( overrideEl["attributes"] =
                        overrideEl["attributes"] || new Array() );
                    for (var attName in attrs) {
                        // Each item in the attributes array is also an array,
                        // where [0] is the attribute name and [1] is the
                        // override value.
                        overrideAttrs.push([ attName.toLowerCase(), attrs[ attName ] ]);
                    }
                }
            }
        }

        return overrides;
    }


    // Removes a style from an element itself, don't care about its subtree.
    function removeFromElement(style, element) {
        var def = style._.definition,
            attributes = S.mix(S.mix({}, def["attributes"]),
                getOverrides(style)[ element._4e_name()]),
            styles = def["styles"],
            // If the style is only about the element itself, we have to remove the element.
            removeEmpty = S.isEmptyObject(attributes) &&
                S.isEmptyObject(styles);

        // Remove definition attributes/style from the elemnt.
        for (var attName in attributes) {
            // The 'class' element value must match (#1318).
            if (( attName == 'class' || style._.definition["fullMatch"] )
                && element.attr(attName) != normalizeProperty(attName,
                attributes[ attName ]))
                continue;
            removeEmpty = removeEmpty || !!element._4e_hasAttribute(attName);
            element.removeAttr(attName);
        }

        for (var styleName in styles) {
            // Full match style insist on having fully equivalence. (#5018)
            if (style._.definition["fullMatch"]
                && element._4e_style(styleName) != normalizeProperty(styleName, styles[ styleName ], TRUE))
                continue;

            removeEmpty = removeEmpty || !!element._4e_style(styleName);
            element._4e_style(styleName, "");
        }

        removeEmpty && removeNoAttribsElement(element);
    }

    /**
     *
     * @param {string} name
     * @param {string} value
     * @param {boolean=} isStyle
     */
    function normalizeProperty(name, value, isStyle) {
        var temp = new Node('<span>');
        temp [ isStyle ? '_4e_style' : 'attr' ](name, value);
        return temp[ isStyle ? '_4e_style' : 'attr' ](name);
    }


    // Removes a style from inside an element.
    function removeFromInsideElement(style, element) {
        var //def = style._.definition,
            //attribs = def.attributes,
            //styles = def.styles,
            overrides = getOverrides(style),
            innerElements = element.all(style["element"]);

        for (var i = innerElements.length; --i >= 0;)
            removeFromElement(style, new Node(innerElements[i]));

        // Now remove any other element with different name that is
        // defined to be overriden.
        for (var overrideElement in overrides) {
            if (overrideElement != style["element"]) {
                innerElements = element.all(overrideElement);
                for (i = innerElements.length - 1; i >= 0; i--) {
                    var innerElement = new Node(innerElements[i]);
                    removeOverrides(innerElement, overrides[ overrideElement ]);
                }
            }
        }

    }

    /**
     *  Remove overriding styles/attributes from the specific element.
     *  Note: Remove the element if no attributes remain.
     * @param {Object} element
     * @param {Object} overrides
     */
    function removeOverrides(element, overrides) {
        var attributes = overrides && overrides["attributes"];

        if (attributes) {
            for (var i = 0; i < attributes.length; i++) {
                var attName = attributes[i][0], actualAttrValue;

                if (( actualAttrValue = element.attr(attName) )) {
                    var attValue = attributes[i][1];

                    // Remove the attribute if:
                    //    - The override definition value is NULL ;
                    //    - The override definition valie is a string that
                    //      matches the attribute value exactly.
                    //    - The override definition value is a regex that
                    //      has matches in the attribute value.
                    if (attValue === NULL ||
                        ( attValue.test && attValue.test(actualAttrValue) ) ||
                        ( typeof attValue == 'string' && actualAttrValue == attValue ))
                        element[0].removeAttribute(attName);
                }
            }
        }

        removeNoAttribsElement(element);
    }

    // If the element has no more attributes, remove it.
    function removeNoAttribsElement(element) {
        // If no more attributes remained in the element, remove it,
        // leaving its children.
        if (!element._4e_hasAttributes()) {
            // Removing elements may open points where merging is possible,
            // so let's cache the first and last nodes for later checking.
            var firstChild = element[0].firstChild,
                lastChild = element[0].lastChild;

            element._4e_remove(TRUE);

            if (firstChild) {
                // Check the cached nodes for merging.
                firstChild.nodeType == KEN.NODE_ELEMENT &&
                DOM._4e_mergeSiblings(firstChild);

                if (lastChild && !firstChild === lastChild
                    && lastChild.nodeType == KEN.NODE_ELEMENT)
                    DOM._4e_mergeSiblings(lastChild);
            }
        }
    }

    KE.Style = KEStyle;
    KE["Style"] = KEStyle;

    var StyleP = KEStyle.prototype;
    KE.Utils.extern(StyleP, {
        "apply":StyleP.apply,
        "remove":StyleP.remove,
        "applyToRange":StyleP.applyToRange,
        "removeFromRange":StyleP.removeFromRange,
        "applyToObject":StyleP.applyToObject,
        "checkElementRemovable":StyleP.checkElementRemovable,
        "checkActive":StyleP.checkActive
    });
});/**
 * modified from ckeditor,htmlparser for malform html string
 * @author: yiminghe@gmail.com
 */
/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
KISSY.Editor.add("htmlparser", function(
    // editor
    ) {

    var S = KISSY
        ,
        TRUE = true,
        FALSE = false,
        NULL = null,
        emptyFunc = function() {
        },
        KE = S.Editor;
    //if (KE.HtmlParser) return;
    var attribsRegex = /([\w\-:.]+)(?:(?:\s*=\s*(?:(?:"([^"]*)")|(?:'([^']*)')|([^\s>]+)))|(?=\s|$))/g,
        emptyAttribs = {
            "checked":1,
            "compact":1,
            "declare":1,
            "defer":1,
            "disabled":1,
            "ismap":1,
            "multiple":1,
            "nohref":1,
            "noresize":1,
            "noshade":1,
            "nowrap":1,
            "readonly":1,
            "selected":1
        },
        XHTML_DTD = KE.XHTML_DTD;

    /**
     * @constructor
     */
    function HtmlParser() {
        this._ = {
            htmlPartsRegex :new RegExp('<(?:(?:\\/([^>]+)>)|(?:!--([\\S|\\s]*?)-->)|(?:([^\\s>]+)\\s*((?:(?:[^"\'>]+)|(?:"[^"]*")|(?:\'[^\']*\'))*)\\/?>))', 'g')
        };
    }


    S.augment(HtmlParser, {
        /**
         * Function to be fired when a tag opener is found. This function
         * should be overriden when using this class.
         *  {string} tagName The tag name. The name is guarantted to be
         *        lowercased.
         *  {Object} attributes An object containing all tag attributes. Each
         *        property in this object represent and attribute name and its
         *        value is the attribute value.
         * {boolean} selfClosing TRUE if the tag closes itself, FALSE if the
         *         tag doesn't.
         * @example
         * var parser = new HtmlParser();
         * parser.onTagOpen = function( tagName, attributes, selfClosing )
         *     {
         *         alert( tagName );  // e.g. "b"
         *     });
         * parser.parse( "&lt;!-- Example --&gt;&lt;b&gt;Hello&lt;/b&gt;" );
         */
        onTagOpen    : emptyFunc,

        /**
         * Function to be fired when a tag closer is found. This function
         * should be overriden when using this class.

         * @example
         * var parser = new HtmlParser();
         * parser.onTagClose = function( tagName )
         *     {
         *         alert( tagName );  // e.g. "b"
         *     });
         * parser.parse( "&lt;!-- Example --&gt;&lt;b&gt;Hello&lt;/b&gt;" );
         */
        onTagClose    : emptyFunc,

        /**
         * Function to be fired when text is found. This function
         * should be overriden when using this class.

         * @example
         * var parser = new HtmlParser();
         * parser.onText = function( text )
         *     {
         *         alert( text );  // e.g. "Hello"
         *     });
         * parser.parse( "&lt;!-- Example --&gt;&lt;b&gt;Hello&lt;/b&gt;" );
         */
        onText        : emptyFunc,

        /**
         * Function to be fired when CDATA section is found. This function
         * should be overriden when using this class.

         */
        onCDATA        : emptyFunc,

        /**
         * Function to be fired when a commend is found. This function
         * should be overriden when using this class.


         */
        onComment :emptyFunc,

        /**
         * Parses text, looking for HTML tokens, like tag openers or closers,
         * or comments. This function fires the onTagOpen, onTagClose, onText
         * and onComment function during its execution.
         * @param {string} html The HTML to be parsed.

         */
        parse : function(html) {
            var parts,
                tagName,

                nextIndex = 0,
                cdata;	// The collected data inside a CDATA section.

            while (( parts = this._.htmlPartsRegex.exec(html) )) {

                var tagIndex = parts.index;
                if (tagIndex > nextIndex) {
                    var text = html.substring(nextIndex, tagIndex);

                    if (cdata)
                        cdata.push(text);
                    else
                        this.onText(text);
                }

                nextIndex = this._.htmlPartsRegex.lastIndex;

                /*
                 "parts" is an array with the following items:
                 0 : The entire match for opening/closing tags and comments.
                 1 : Group filled with the tag name for closing tags.
                 2 : Group filled with the comment text.
                 3 : Group filled with the tag name for opening tags.
                 4 : Group filled with the attributes part of opening tags.
                 */

                // Closing tag
                if (( tagName = parts[ 1 ] )) {
                    tagName = tagName.toLowerCase();

                    if (cdata && XHTML_DTD.$cdata[ tagName ]) {
                        // Send the CDATA data.
                        this.onCDATA(cdata.join(''));
                        cdata = NULL;
                    }

                    if (!cdata) {
                        this.onTagClose(tagName);
                        continue;
                    }
                }

                // If CDATA is enabled, just save the raw match.
                if (cdata) {
                    cdata.push(parts[ 0 ]);
                    continue;
                }

                // Opening tag
                if (( tagName = parts[ 3 ] )) {
                    tagName = tagName.toLowerCase();

                    // There are some tag names that can break things, so let's
                    // simply ignore them when parsing. (#5224)
                    if (/="/.test(tagName))
                        continue;

                    var attribs = {},
                        attribMatch,
                        attribsPart = parts[ 4 ],
                        selfClosing = !!( attribsPart && attribsPart.charAt(attribsPart.length - 1) == '/' );

                    if (attribsPart) {
                        while (( attribMatch = attribsRegex.exec(attribsPart) )) {
                            var attName = attribMatch[1].toLowerCase(),
                                attValue = attribMatch[2] || attribMatch[3] || attribMatch[4] || '';

                            if (!attValue && emptyAttribs[ attName ])
                                attribs[ attName ] = attName;
                            else
                                attribs[ attName ] = attValue;
                        }
                    }

                    this.onTagOpen(tagName, attribs, selfClosing);

                    // Open CDATA mode when finding the appropriate tags.
                    if (!cdata && XHTML_DTD.$cdata[ tagName ])
                        cdata = [];

                    continue;
                }

                // Comment
                if (( tagName = parts[ 2 ] ))
                    this.onComment(tagName);
            }

            if (html.length > nextIndex)
                this.onText(html.substring(nextIndex, html.length));
        }
    });

    KE.HtmlParser = HtmlParser;
    KE["HtmlParser"] = HtmlParser;
});
/**
 * modified from ckeditor,html generator for kissy editor
 * @author: <yiminghe@gmail.com>
 */
/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
KISSY.Editor.add("htmlparser-basicwriter", function() {
    var S = KISSY,KE = S.Editor,Utils = KE.Utils,
        TRUE = true,
        FALSE = false,
        NULL = null;

    /**
     * @constructor
     */
    function BasicWriter() {
        this._ = {
            output : []
        };
    }

    S.augment(BasicWriter, {
        /**
         * Writes the tag opening part for a opener tag.
         * @param {string} tagName The element name for this tag.
         * param {Object=} attributes The attributes defined for this tag. The
         *        attributes could be used to inspect the tag.
         * @example
         * // Writes "&lt;p".
         * writer.openTag( 'p', { class : 'MyClass', id : 'MyId' } );
         */
        openTag : function(tagName, attributes) {
            this._.output.push('<', tagName);
        },

        /**
         * Writes the tag closing part for a opener tag.
         * @param {string} tagName The element name for this tag.
         * @param {boolean=} isSelfClose Indicates that this is a self-closing tag,
         *        like "br" or "img".
         * @example
         * // Writes "&gt;".
         * writer.openTagClose( 'p', FALSE );
         * @example
         * // Writes " /&gt;".
         * writer.openTagClose( 'br', TRUE );
         */
        openTagClose : function(tagName, isSelfClose) {
            if (isSelfClose)
                this._.output.push(' />');
            else
                this._.output.push('>');
        },

        /**
         * Writes an attribute. This function should be called after opening the
         * tag with {@link #openTagClose}.
         * @param {string} attName The attribute name.
         * @param {string} attValue The attribute value.
         * @example
         * // Writes ' class="MyClass"'.
         * writer.attribute( 'class', 'MyClass' );
         */
        attribute : function(attName, attValue) {
            // Browsers don't always escape special character in attribute values. (#4683, #4719).
            if (typeof attValue == 'string')
                attValue = Utils.htmlEncodeAttr(attValue);

            this._.output.push(' ', attName, '="', attValue, '"');
        },

        /**
         * Writes a closer tag.
         * @param {string} tagName The element name for this tag.
         * @example
         * // Writes "&lt;/p&gt;".
         * writer.closeTag( 'p' );
         */
        closeTag : function(tagName) {
            this._.output.push('</', tagName, '>');
        },

        /**
         * Writes text.
         * @param {string} text The text value
         * @example
         * // Writes "Hello Word".
         * writer.text( 'Hello Word' );
         */
        text : function(text) {
            this._.output.push(text);
        },

        /**
         * Writes a comment.
         * @param {string} comment The comment text.
         * @example
         * // Writes "&lt;!-- My comment --&gt;".
         * writer.comment( ' My comment ' );
         */
        comment : function(comment) {
            this._.output.push('<!--', comment, '-->');
        },

        /**
         * Writes any kind of data to the ouput.
         * @example
         * writer.write( 'This is an &lt;b&gt;example&lt;/b&gt;.' );
         */
        write : function(data) {
            this._.output.push(data);
        },

        /**
         * Empties the current output buffer.
         * @example
         * writer.reset();
         */
        reset : function() {
            this._.output = [];
            this._.indent = FALSE;
        },

        /**
         * Empties the current output buffer.
         * @param {boolean} reset Indicates that the { reset} function is to
         *        be automatically called after retrieving the HTML.
         * @returns {string} The HTML written to the writer so far.
         * @example
         * var html = writer.getHtml();
         */
        getHtml : function(reset) {
            var html = this._.output.join('');

            if (reset)
                this.reset();

            return html;
        }
    });

    KE.HtmlParser.BasicWriter = BasicWriter;
    KE.HtmlParser["BasicWriter"] = BasicWriter;
    var BasicWriterP = BasicWriter.prototype;
    KE.Utils.extern(BasicWriterP, {
        "openTag":BasicWriterP.openTag,
        "openTagClose":BasicWriterP.openTagClose,
        "attribute":BasicWriterP.attribute,
        "closeTag":BasicWriterP.closeTag,
        "text":BasicWriterP.text,
        "comment":BasicWriterP.comment,
        "write":BasicWriterP.write,
        "reset":BasicWriterP.reset,
        "getHtml":BasicWriterP.getHtml
    });
});
/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
KISSY.Editor.add("htmlparser-htmlwriter", function(
    ) {
    var S = KISSY,
        KE = S.Editor,
        Utils = KE.Utils,
        TRUE = true,
        FALSE = false,
        NULL = null;

    /**
     * @constructor
     */
    function HtmlWriter() {
        // Call the base contructor.

        HtmlWriter.superclass.constructor.call(this);

        /**
         * The characters to be used for each identation step.
         * @type {string}
         * @default "\t" (tab)
         * @example
         * // Use two spaces for indentation.
         * editorInstance.dataProcessor.writer.indentationChars = '  ';
         */
        this.indentationChars = '\t';

        /**
         * The characters to be used to close "self-closing" elements, like "br" or
         * "img".
         * @type {string}
         * @default " /&gt;"
         * @example
         * // Use HTML4 notation for self-closing elements.
         * editorInstance.dataProcessor.writer.selfClosingEnd = '>';
         */
        this.selfClosingEnd = ' />';

        /**
         * The characters to be used for line breaks.
         * @type {string}
         * @default "\n" (LF)
         * @example
         * // Use CRLF for line breaks.
         * editorInstance.dataProcessor.writer.lineBreakChars = '\r\n';
         */
        this.lineBreakChars = '\n';

        this.forceSimpleAmpersand = FALSE;

        this.sortAttributes = TRUE;

        this._.indent = FALSE;
        this._.indentation = '';
        this._.rules = {};

        var dtd = KE.XHTML_DTD;

        for (var e in Utils.mix({},
            dtd.$nonBodyContent,
            dtd.$block, dtd.$listItem,
            dtd.$tableContent)) {
            this.setRules(e, {
                indent : TRUE,
                breakBeforeOpen : TRUE,
                breakAfterOpen : TRUE,
                breakBeforeClose : !dtd[ e ][ '#' ],
                breakAfterClose : TRUE
            });
        }

        this.setRules('br',
        {
            breakAfterOpen : TRUE
        });

        this.setRules('title',
        {
            indent : FALSE,
            breakAfterOpen : FALSE
        });

        this.setRules('style',
        {
            indent : FALSE,
            breakBeforeClose : TRUE
        });

        // Disable indentation on <pre>.
        this.setRules('pre',
        {
            indent: FALSE
        });
    }

    S.extend(HtmlWriter, KE.HtmlParser.BasicWriter, {
        /**
         * Writes the tag opening part for a opener tag.
         * @param {String} tagName The element name for this tag.
         * @param {Object} attributes The attributes defined for this tag. The
         *        attributes could be used to inspect the tag.
         * @example
         * // Writes "&lt;p".
         * writer.openTag( 'p', { class : 'MyClass', id : 'MyId' } );
         */
        openTag : function(tagName, attributes) {
            var rules = this._.rules[ tagName ];

            if (this._.indent)
                this.indentation();
            // Do not break if indenting.
            else if (rules && rules.breakBeforeOpen) {
                this.lineBreak();
                this.indentation();
            }

            this._.output.push('<', tagName);
        },

        /**
         * Writes the tag closing part for a opener tag.
         * @param {String} tagName The element name for this tag.
         * @param {Boolean} isSelfClose Indicates that this is a self-closing tag,
         *        like "br" or "img".
         * @example
         * // Writes "&gt;".
         * writer.openTagClose( 'p', FALSE );
         * @example
         * // Writes " /&gt;".
         * writer.openTagClose( 'br', TRUE );
         */
        openTagClose : function(tagName, isSelfClose) {
            var rules = this._.rules[ tagName ];

            if (isSelfClose)
                this._.output.push(this.selfClosingEnd);
            else {
                this._.output.push('>');
                if (rules && rules.indent)
                    this._.indentation += this.indentationChars;
            }

            if (rules && rules.breakAfterOpen)
                this.lineBreak();
        },

        /**
         * Writes an attribute. This function should be called after opening the
         * tag with {@link #openTagClose}.
         * @param {String} attName The attribute name.
         * @param {String} attValue The attribute value.
         * @example
         * // Writes ' class="MyClass"'.
         * writer.attribute( 'class', 'MyClass' );
         */
        attribute : function(attName, attValue) {

            if (typeof attValue == 'string') {
                this.forceSimpleAmpersand && ( attValue = attValue.replace(/&amp;/g, '&') );
                // Browsers don't always escape special character in attribute values. (#4683, #4719).
                attValue = Utils.htmlEncodeAttr(attValue);
            }

            this._.output.push(' ', attName, '="', attValue, '"');
        },

        /**
         * Writes a closer tag.
         * @param {String} tagName The element name for this tag.
         * @example
         * // Writes "&lt;/p&gt;".
         * writer.closeTag( 'p' );
         */
        closeTag : function(tagName) {
            var rules = this._.rules[ tagName ];

            if (rules && rules.indent)
                this._.indentation = this._.indentation.substr(this.indentationChars.length);

            if (this._.indent)
                this.indentation();
            // Do not break if indenting.
            else if (rules && rules.breakBeforeClose) {
                this.lineBreak();
                this.indentation();
            }

            this._.output.push('</', tagName, '>');

            if (rules && rules.breakAfterClose)
                this.lineBreak();
        },

        /**
         * Writes text.
         * @param {String} text The text value
         * @example
         * // Writes "Hello Word".
         * writer.text( 'Hello Word' );
         */
        text : function(text) {
            if (this._.indent) {
                this.indentation();
                text = Utils.ltrim(text);
            }

            this._.output.push(text);
        },

        /**
         * Writes a comment.
         * @param {String} comment The comment text.
         * @example
         * // Writes "&lt;!-- My comment --&gt;".
         * writer.comment( ' My comment ' );
         */
        comment : function(comment) {
            if (this._.indent)
                this.indentation();

            this._.output.push('<!--', comment, '-->');
        },

        /**
         * Writes a line break. It uses the { #lineBreakChars} property for it.
         * @example
         * // Writes "\n" (e.g.).
         * writer.lineBreak();
         */
        lineBreak : function() {
            if (this._.output.length > 0)
                this._.output.push(this.lineBreakChars);
            this._.indent = TRUE;
        },

        /**
         * Writes the current indentation chars. It uses the
         * { #indentationChars} property, repeating it for the current
         * indentation steps.
         * @example
         * // Writes "\t" (e.g.).
         * writer.indentation();
         */
        indentation : function() {
            this._.output.push(this._.indentation);
            this._.indent = FALSE;
        },

        /**
         * Sets formatting rules for a give element. The possible rules are:
         * <ul>
         *    <li><b>indent</b>: indent the element contents.</li>
         *    <li><b>breakBeforeOpen</b>: break line before the opener tag for this element.</li>
         *    <li><b>breakAfterOpen</b>: break line after the opener tag for this element.</li>
         *    <li><b>breakBeforeClose</b>: break line before the closer tag for this element.</li>
         *    <li><b>breakAfterClose</b>: break line after the closer tag for this element.</li>
         * </ul>
         *
         * All rules default to "FALSE". Each call to the function overrides
         * already present rules, leaving the undefined untouched.
         *
         * By default, all elements available in the { XHTML_DTD.$block),
         * { XHTML_DTD.$listItem} and { XHTML_DTD.$tableContent}
         * lists have all the above rules set to "TRUE". Additionaly, the "br"
         * element has the "breakAfterOpen" set to "TRUE".
         * @param {String} tagName The element name to which set the rules.
         * @param {Object} rules An object containing the element rules.
         * @example
         * // Break line before and after "img" tags.
         * writer.setRules( 'img',
         *     {
         *         breakBeforeOpen : TRUE
         *         breakAfterOpen : TRUE
         *     });
         * @example
         * // Reset the rules for the "h1" tag.
         * writer.setRules( 'h1', {} );
         */
        setRules : function(tagName, rules) {
            var currentRules = this._.rules[ tagName ];

            if (currentRules)
                currentRules = Utils.mix(currentRules, rules);
            else
                this._.rules[ tagName ] = rules;
        }
    });

    KE.HtmlParser.HtmlWriter = HtmlWriter;
    KE.HtmlParser["HtmlWriter"] = HtmlWriter;
    var HtmlWriterP = HtmlWriter.prototype;
    KE.Utils.extern(HtmlWriterP, {
        "openTag":HtmlWriterP.openTag,
        "openTagClose":HtmlWriterP.openTagClose,
        "attribute":HtmlWriterP.attribute,
        "closeTag":HtmlWriterP.closeTag,
        "text":HtmlWriterP.text,
        "comment":HtmlWriterP.comment,
        "lineBreak":HtmlWriterP.lineBreak,
        "indentation":HtmlWriterP.indentation,
        "setRules":HtmlWriterP.setRules
    });
});
/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
KISSY.Editor.add("htmlparser-fragment", function(
    ) {
    var
        TRUE = true,
        FALSE = false,
        NULL = null,
        KE = KISSY.Editor;

    /**
     * A lightweight representation of an HTML DOM structure.
     * @constructor
     * @example
     */
    function Fragment() {
        /**
         * The nodes contained in the root of this fragment.
         * @type Array
         * @example
         * var fragment = Fragment.fromHtml( '<b>Sample</b> Text' );
         * alert( fragment.children.length );  "2"
         */
        this.children = [];

        /**
         * Get the fragment parent. Should always be NULL.
         * @type Object
         * @default NULL
         * @example
         */
        this.parent = NULL;

        /** @private */
        this._ = {
            isBlockLike : TRUE,
            hasInlineStarted : FALSE
        };
    }

    // Elements which the end tag is marked as optional in the HTML 4.01 DTD
    // (expect empty elements).
    var optionalClose = {"colgroup":1,"dd":1,"dt":1,"li":1,"option":1,"p":1,"td":1,"tfoot":1,"th":1,"thead":1,"tr":1};

    // Block-level elements whose internal structure should be respected during
    // parser fixing.
    var S = KISSY,
        Utils = KE.Utils,
        KEN = KE.NODE,
        XHTML_DTD = KE.XHTML_DTD,
        nonBreakingBlocks = Utils.mix({"table":1,"ul":1,"ol":1,"dl":1},
            XHTML_DTD["table"], XHTML_DTD["ul"], XHTML_DTD["ol"], XHTML_DTD["dl"]),
        listBlocks = XHTML_DTD.$list,
        listItems = XHTML_DTD.$listItem;

    /**
     * Creates a  Fragment from an HTML string.
     * @param {String} fragmentHtml The HTML to be parsed, filling the fragment.
     * @param {boolean|string|undefined} [fixForBody=FALSE] Wrap body with specified element if needed.
     * @returns Fragment The fragment created.
     * @example
     * var fragment = Fragment.fromHtml( '<b>Sample</b> Text' );
     * alert( fragment.children[0].name );  "b"
     * alert( fragment.children[1].value );  " Text"
     * 特例：
     * 自动加p，自动处理标签嵌套规则
     * "<img src='xx'><span>5<div>6</div>7</span>"
     * ="<p><img><span>5</span></p><div><span>6</span></div><p><span>7</span></p>"
     * 自动处理ul嵌套，以及li ie不闭合
     * "<ul><ul><li>xxx</ul><li>1<li>2<ul>");
     */
    Fragment.FromHtml = function(fragmentHtml, fixForBody) {

        var parser = new KE.HtmlParser(),
            //html = [],
            fragment = new Fragment(),
            pendingInline = [],
            pendingBRs = [],
            currentNode = fragment,
            // Indicate we're inside a <pre> element, spaces should be touched differently.
            inPre = FALSE,
            returnPoint;

        /**
         *
         * @param {boolean|undefined|string=} newTagName
         */
        function checkPending(newTagName) {
            var pendingBRsSent;

            if (pendingInline.length > 0) {
                for (var i = 0; i < pendingInline.length; i++) {
                    var pendingElement = pendingInline[ i ],
                        pendingName = pendingElement.name,
                        pendingDtd = XHTML_DTD[ pendingName ],
                        currentDtd = currentNode.name && XHTML_DTD[ currentNode.name ];

                    if (( !currentDtd || currentDtd[ pendingName ] ) && ( !newTagName || !pendingDtd || pendingDtd[ newTagName ] || !XHTML_DTD[ newTagName ] )) {
                        if (!pendingBRsSent) {
                            sendPendingBRs();
                            pendingBRsSent = 1;
                        }

                        // Get a clone for the pending element.
                        pendingElement = pendingElement.clone();

                        // Add it to the current node and make it the current,
                        // so the new element will be added inside of it.
                        pendingElement.parent = currentNode;
                        currentNode = pendingElement;

                        // Remove the pending element (back the index by one
                        // to properly process the next entry).
                        pendingInline.splice(i, 1);
                        i--;
                    }
                }
            }
        }

        function sendPendingBRs() {
            while (pendingBRs.length)
                currentNode.add(pendingBRs.shift());
        }

        /**
         *
         * @param  element
         * @param  {*=} target
         * @param {boolean=} enforceCurrent
         */
        function addElement(element, target, enforceCurrent) {
            target = target || currentNode || fragment;

            // If the target is the fragment and this element can't go inside
            // body
            if (fixForBody && !target.type) {
                var elementName, realElementName;
                if (element.attributes
                    && ( realElementName =
                    element.attributes[ '_ke_real_element_type' ] ))
                    elementName = realElementName;
                else
                    elementName = element.name;
                if (elementName
                    && !( elementName in XHTML_DTD.$body )
                    && !( elementName in XHTML_DTD.$nonBodyContent )) {
                    var savedCurrent = currentNode;

                    // Create a <p> in the fragment.
                    currentNode = target;
                    parser.onTagOpen(fixForBody, {});

                    // The new target now is the <p>.
                    target = currentNode;

                    if (enforceCurrent)
                        currentNode = savedCurrent;
                }
            }

            // Rtrim empty spaces on block end boundary. (#3585)
            if (element._.isBlockLike
                && element.name != 'pre') {

                var length = element.children.length,
                    lastChild = element.children[ length - 1 ],
                    text;
                if (lastChild && lastChild.type == KEN.NODE_TEXT) {
                    if (!( text = Utils.rtrim(lastChild.value) ))
                        element.children.length = length - 1;
                    else
                        lastChild.value = text;
                }
            }

            target.add(element);

            //<ul><ul></ul></ul> -> <ul><li><ul></ul></li></ul>
            //跳过隐形添加的li直接到ul
            if (element.returnPoint) {
                currentNode = element.returnPoint;
                delete element.returnPoint;
            }
        }

        /**
         * 遇到标签开始建立节点和父亲关联 ==  node.parent=parent
         * @param {string|boolean|undefined} tagName
         * @param {Object} attributes
         * @param {boolean=} selfClosing
         */
        parser.onTagOpen = function(tagName, attributes, selfClosing) {
            var element = new KE.HtmlParser.Element(tagName, attributes);

            // "isEmpty" will be always "FALSE" for unknown elements, so we
            // must force it if the parser has identified it as a selfClosing tag.
            if (element.isUnknown && selfClosing)
                element.isEmpty = TRUE;

            // This is a tag to be removed if empty, so do not add it immediately.
            if (XHTML_DTD.$removeEmpty[ tagName ]) {
                pendingInline.push(element);
                return;
            }
            else if (tagName == 'pre')
                inPre = TRUE;
            else if (tagName == 'br' && inPre) {
                currentNode.add(new KE.HtmlParser.Text('\n'));
                return;
            }

            if (tagName == 'br') {
                pendingBRs.push(element);
                return;
            }

            var currentName = currentNode.name;

            var currentDtd = currentName
                && ( XHTML_DTD[ currentName ]
                || ( currentNode._.isBlockLike ? XHTML_DTD["div"] : XHTML_DTD["span"] ) );

            // If the element cannot be child of the current element.
            if (currentDtd   // Fragment could receive any elements.
                && !element.isUnknown && !currentNode.isUnknown && !currentDtd[ tagName ]) {

                var reApply = FALSE,
                    addPoint;   // New position to start adding nodes.

                // Fixing malformed nested lists by moving it into a previous list item. (#3828)
                if (tagName in listBlocks
                    && currentName in listBlocks) {
                    var children = currentNode.children,
                        lastChild = children[ children.length - 1 ];

                    // Establish the list item if it's not existed.
                    if (!( lastChild && lastChild.name in listItems ))
                    //直接添加到父亲
                        addElement(( lastChild = new KE.HtmlParser.Element('li') ), currentNode);
                    //以后直接跳到父亲不用再向父亲添加
                    returnPoint = currentNode,addPoint = lastChild;
                }
                // If the element name is the same as the current element name,
                // then just close the current one and append the new one to the
                // parent. This situation usually happens with <p>, <li>, <dt> and
                // <dd>, specially in IE. Do not enter in this if block in this case.
                else if (tagName == currentName) {
                    //直接把上一个<p>,<li>结束掉，不要再等待</p>,</li>执行此项操作了
                    addElement(currentNode, currentNode.parent);
                }
                else {
                    if (nonBreakingBlocks[ currentName ]) {
                        if (!returnPoint)
                            returnPoint = currentNode;
                    }
                    else {
                        //拆分，闭合掉
                        addElement(currentNode, currentNode.parent, TRUE);
                        //li,p等现在就闭合，以后都不用再管了
                        if (!optionalClose[ currentName ]) {
                            // The current element is an inline element, which
                            // cannot hold the new one. Put it in the pending list,
                            // and try adding the new one after it.
                            pendingInline.unshift(currentNode);
                        }
                    }

                    reApply = TRUE;
                }

                if (addPoint)
                    currentNode = addPoint;
                // Try adding it to the return point, or the parent element.
                else
                //前面都调用 addElement 将当前节点闭合了，只能往 parent 添加了
                    currentNode = currentNode.returnPoint || currentNode.parent;

                if (reApply) {
                    parser.onTagOpen.apply(this, arguments);
                    return;
                }
            }

            checkPending(tagName);
            sendPendingBRs();

            element.parent = currentNode;
            element.returnPoint = returnPoint;
            returnPoint = 0;

            //自闭合的，不等结束标签，立即加到父亲
            if (element.isEmpty)
                addElement(element);
            else
                currentNode = element;
        };

        /**
         * 遇到标签结束，将open生成的节点添加到dom树中 == 父亲接纳自己 node.parent.add(node)
         * @param tagName
         */
        parser.onTagClose = function(tagName) {
            // Check if there is any pending tag to be closed.
            for (var i = pendingInline.length - 1; i >= 0; i--) {
                // If found, just remove it from the list.
                if (tagName == pendingInline[ i ].name) {
                    pendingInline.splice(i, 1);
                    return;
                }
            }

            var pendingAdd = [],
                newPendingInline = [],
                candidate = currentNode;

            while (candidate.type && candidate.name != tagName) {
                // If this is an inline element, add it to the pending list, if we're
                // really closing one of the parents element later, they will continue
                // after it.
                if (!candidate._.isBlockLike)
                    newPendingInline.unshift(candidate);

                // This node should be added to it's parent at this point. But,
                // it should happen only if the closing tag is really closing
                // one of the nodes. So, for now, we just cache it.
                pendingAdd.push(candidate);

                candidate = candidate.parent;
            }

            if (candidate.type) {
                // Add all elements that have been found in the above loop.
                for (i = 0; i < pendingAdd.length; i++) {
                    var node = pendingAdd[ i ];
                    addElement(node, node.parent);
                }

                currentNode = candidate;

                if (currentNode.name == 'pre')
                    inPre = FALSE;

                if (candidate._.isBlockLike)
                    sendPendingBRs();

                addElement(candidate, candidate.parent);

                // The parent should start receiving new nodes now, except if
                // addElement changed the currentNode.
                if (candidate == currentNode)
                    currentNode = currentNode.parent;

                pendingInline = pendingInline.concat(newPendingInline);
            }

            if (tagName == 'body')
                fixForBody = FALSE;
        };

        parser.onText = function(text) {
            // Trim empty spaces at beginning of element contents except <pre>.
            if (!currentNode._.hasInlineStarted && !inPre) {
                text = Utils.ltrim(text);

                if (text.length === 0)
                    return;
            }

            sendPendingBRs();
            checkPending();

            if (fixForBody
                && ( !currentNode.type || currentNode.name == 'body' )
                && Utils.trim(text)) {
                this.onTagOpen(fixForBody, {});
            }

            // Shrinking consequential spaces into one single for all elements
            // text contents.
            if (!inPre)
                text = text.replace(/[\t\r\n ]{2,}|[\t\r\n]/g, ' ');

            currentNode.add(new KE.HtmlParser.Text(text));
        };

        parser.onCDATA = function(
            //cdata
            ) {
            //不做
            //currentNode.add(new KE.HtmlParser.cdata(cdata));
        };

        parser.onComment = function(comment) {
            currentNode.add(new KE.HtmlParser.Comment(comment));
        };

        // Parse it.
        parser.parse(fragmentHtml);

        sendPendingBRs();

        // Close all pending nodes.
        //<p>xxxxxxxxxxxxx
        //到最后也灭有结束标签
        while (currentNode.type) {
            var parent = currentNode.parent,
                node = currentNode;

            if (fixForBody
                && ( !parent.type || parent.name == 'body' )
                && !XHTML_DTD.$body[ node.name ]) {
                currentNode = parent;
                parser.onTagOpen(fixForBody, {});
                parent = currentNode;
            }

            parent.add(node);
            currentNode = parent;
        }

        return fragment;
    };

    S.augment(Fragment, {
        /**
         * Adds a node to this fragment.
         * @param {Object} node The node to be added. It can be any of of the
         *        following types: {@link Element},
         *        {@link Text}
         * @example
         */
        add : function(node) {
            var len = this.children.length,
                previous = len > 0 && this.children[ len - 1 ] || NULL;

            if (previous) {
                // If the block to be appended is following text, trim spaces at
                // the right of it.
                if (node._.isBlockLike && previous.type == KEN.NODE_TEXT) {
                    previous.value = Utils.rtrim(previous.value);
                    // If we have completely cleared the previous node.
                    if (previous.value.length === 0) {
                        // Remove it from the list and add the node again.
                        this.children.pop();
                        this.add(node);
                        return;
                    }
                }

                previous.next = node;
            }

            node.previous = previous;
            node.parent = this;

            this.children.push(node);
            this._.hasInlineStarted = node.type == KEN.NODE_TEXT || ( node.type == KEN.NODE_ELEMENT && !node._.isBlockLike );
        },

        /**
         * Writes the fragment HTML to a CKEDITOR.htmlWriter.
         * @param writer The writer to which write the HTML.
         * @example
         * var writer = new HtmlWriter();
         * var fragment = Fragment.fromHtml( '&lt;P&gt;&lt;B&gt;Example' );
         * fragment.writeHtml( writer )
         * alert( writer.getHtml() );  "&lt;p&gt;&lt;b&gt;Example&lt;/b&gt;&lt;/p&gt;"
         */
        writeHtml : function(writer, filter) {
            var isChildrenFiltered;
            this.filterChildren = function() {
                var writer = new KE.HtmlParser.BasicWriter();
                this.writeChildrenHtml.call(this, writer, filter, TRUE);
                var html = writer.getHtml();
                this.children = new Fragment.FromHtml(html).children;
                isChildrenFiltered = 1;
            };
            this["filterChildren"] = this.filterChildren;
            // Filtering the root fragment before anything else.
            !this.name && filter && filter.onFragment(this);

            this.writeChildrenHtml(writer, isChildrenFiltered ? NULL : filter);
        },

        writeChildrenHtml : function(writer, filter) {
            for (var i = 0; i < this.children.length; i++)
                this.children[i].writeHtml(writer, filter);
        }
    });

    KE.HtmlParser.Fragment = Fragment;
    KE.HtmlParser["Fragment"] = Fragment;
    Fragment["FromHtml"] = Fragment.FromHtml;
    var FragmentP = Fragment.prototype;
    KE.Utils.extern(FragmentP, {
        "add":FragmentP.add,
        "writeHtml":FragmentP.writeHtml,
        "writeChildrenHtml":FragmentP.writeChildrenHtml
    });
});
/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
KISSY.Editor.add("htmlparser-element", function() {
    var KE = KISSY.Editor,
        TRUE = true,
        FALSE = false,
        NULL = null;

    /**
     * A lightweight representation of an HTML element.
     * @constructor
     * @param {!String} name The element name.
     * @param {Object} attributes And object holding all attributes defined for
     *        this element.
     * @example
     */
    function MElement(name, attributes) {
        /**
         * The element name.
         * @type String
         * @example
         */
        this.name = name;

        /**
         * Holds the attributes defined for this element.
         * @type Object
         * @example
         */
        this.attributes = attributes || ( attributes = {} );

        /**
         * The nodes that are direct children of this element.
         * @type Array
         * @example
         */
        this.children = [];

        var tagName = attributes._ke_real_element_type || name;

        var dtd = KE.XHTML_DTD,
            isBlockLike = !!( dtd.$nonBodyContent[ tagName ] || dtd.$block[ tagName ] || dtd.$listItem[ tagName ] || dtd.$tableContent[ tagName ] || dtd.$nonEditable[ tagName ] || tagName == 'br' ),
            isEmpty = !!dtd.$empty[ name ];

        this.isEmpty = isEmpty;
        this.isUnknown = !dtd[ name ];

        /** @private */
        this._ =
        {
            isBlockLike : isBlockLike,
            hasInlineStarted : isEmpty || !isBlockLike
        };
    }

    // Used to sort attribute entries in an array, where the first element of
    // each object is the attribute name.
    var S = KISSY,
        KEN = KE.NODE,
        sortAttribs = function(a, b) {
            a = a[0];
            b = b[0];
            return a < b ? -1 : a > b ? 1 : 0;
        };
    S.augment(MElement, {
        /**
         * The node type. This is a constant value set to { KEN.NODE_ELEMENT}.
         * @type Number
         * @example
         */
        type : KEN.NODE_ELEMENT,

        /**
         * Adds a node to the element children list.
         * @param {Object} node The node to be added.
         * @function
         * @example
         */
        add : KE.HtmlParser.Fragment.prototype.add,

        /**
         * Clone this element.
         * @returns {MElement} The element clone.
         * @example
         */
        clone : function() {
            return new MElement(this.name, this.attributes);
        },

        /**
         * Writes the element HTML to a CKEDITOR.htmlWriter.
         * @param  writer The writer to which write the HTML.
         * @example
         */
        writeHtml : function(writer, filter) {
            var attributes = this.attributes;

            // Ignore cke: prefixes when writing HTML.
            var element = this,
                writeName = element.name,
                a, newAttrName, value;

            var isChildrenFiltered;

            /**
             * Providing an option for bottom-up filtering order ( element
             * children to be pre-filtered before the element itself ).
             */
            element.filterChildren = function() {
                if (!isChildrenFiltered) {
                    var writer = new KE.HtmlParser.BasicWriter();
                    KE.HtmlParser.Fragment.prototype.writeChildrenHtml.call(element, writer, filter);
                    element.children = new KE.HtmlParser.Fragment.FromHtml(writer.getHtml()).children;
                    isChildrenFiltered = 1;
                }
            };
            element["filterChildren"] = element.filterChildren;
            if (filter) {
                while (TRUE) {
                    if (!( writeName = filter.onElementName(writeName) ))
                        return;

                    element.name = writeName;

                    if (!( element = filter.onElement(element) ))
                        return;

                    element.parent = this.parent;

                    if (element.name == writeName)
                        break;

                    // If the element has been replaced with something of a
                    // different type, then make the replacement write itself.
                    if (element.type != KEN.NODE_ELEMENT) {
                        element.writeHtml(writer, filter);
                        return;
                    }

                    writeName = element.name;

                    // This indicate that the element has been dropped by
                    // filter but not the children.
                    if (!writeName) {
                        this.writeChildrenHtml.call(element, writer, isChildrenFiltered ? NULL : filter);
                        return;
                    }
                }

                // The element may have been changed, so update the local
                // references.
                attributes = element.attributes;
            }

            // Open element tag.
            writer.openTag(writeName, attributes);

            // Copy all attributes to an array.
            var attribsArray = [];
            // Iterate over the attributes twice since filters may alter
            // other attributes.
            for (var i = 0; i < 2; i++) {
                for (a in attributes) {
                    newAttrName = a;
                    value = attributes[ a ];
                    if (i == 1)
                        attribsArray.push([ a, value ]);
                    else if (filter) {
                        while (TRUE) {
                            if (!( newAttrName = filter.onAttributeName(a) )) {
                                delete attributes[ a ];
                                break;
                            }
                            else if (newAttrName != a) {
                                delete attributes[ a ];
                                a = newAttrName;
                                //continue;
                            }
                            else
                                break;
                        }
                        if (newAttrName) {
                            if (( value = filter.onAttribute(element, newAttrName, value) ) === FALSE)
                                delete attributes[ newAttrName ];
                            else
                                attributes [ newAttrName ] = value;
                        }
                    }
                }
            }
            // Sort the attributes by name.
            if (writer.sortAttributes)
                attribsArray.sort(sortAttribs);

            // Send the attributes.
            var len = attribsArray.length;
            for (i = 0; i < len; i++) {
                var attrib = attribsArray[ i ];
                writer.attribute(attrib[0], attrib[1]);
            }

            // Close the tag.
            writer.openTagClose(writeName, element.isEmpty);

            if (!element.isEmpty) {
                this.writeChildrenHtml.call(element, writer, isChildrenFiltered ? NULL : filter);
                // Close the element.
                writer.closeTag(writeName);
            }
        },

        writeChildrenHtml : function(writer, filter) {
            // Send children.
            KE.HtmlParser.Fragment.prototype.writeChildrenHtml.apply(this, arguments);
        }
    });
    /**
     * @constructor
     */
    KE.HtmlParser.Element = MElement;

    KE.HtmlParser["Element"] = MElement;
    var MElementP = MElement.prototype;
    KE.Utils.extern(MElementP, {
        "type":MElementP.type,
        "add":MElementP.add,
        "clone":MElementP.clone,
        "writeHtml":MElementP.writeHtml,
        "writeChildrenHtml":MElementP.writeChildrenHtml
    });
});
/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
KISSY.Editor.add("htmlparser-filter", function(
    ) {
    var S = KISSY,KE = S.Editor,KEN = KE.NODE,
        TRUE = true,
        FALSE = false,
        NULL = null;

    /**
     * @constructor
     * @param rules {Object}
     */
    function Filter(rules) {
        this._ = {
            elementNames : [],
            attributeNames : [],
            elements : { $length : 0 },
            attributes : { $length : 0 }
        };

        if (rules)
            this.addRules(rules, 10);
    }

    S.augment(Filter, {
        addRules : function(rules, priority) {
            if (typeof priority != 'number')
                priority = 10;

            // Add the elementNames.
            addItemsToList(this._.elementNames, rules.elementNames, priority);

            // Add the attributeNames.
            addItemsToList(this._.attributeNames, rules.attributeNames, priority);

            // Add the elements.
            addNamedItems(this._.elements, rules.elements, priority);

            // Add the attributes.
            addNamedItems(this._.attributes, rules.attributes, priority);

            // Add the text.
            this._.text = transformNamedItem(this._.text, rules.text, priority) || this._.text;

            // Add the comment.
            this._.comment = transformNamedItem(this._.comment, rules.comment, priority) || this._.comment;

            // Add root fragment.
            this._.root = transformNamedItem(this._.root, rules.root, priority) || this._.root;
        },

        onElementName : function(name) {
            return filterName(name, this._.elementNames);
        },

        onAttributeName : function(name) {
            return filterName(name, this._.attributeNames);
        },

        onText : function(text) {
            var textFilter = this._.text;
            return textFilter ? textFilter.filter(text) : text;
        },

        onComment : function(commentText, comment) {
            var textFilter = this._.comment;
            return textFilter ? textFilter.filter(commentText, comment) : commentText;
        },

        onFragment : function(element) {
            var rootFilter = this._.root;
            return rootFilter ? rootFilter.filter(element) : element;
        },

        onElement : function(element) {
            // We must apply filters set to the specific element name as
            // well as those set to the generic $ name. So, add both to an
            // array and process them in a small loop.
            var filters = [ this._.elements[ '^' ], this._.elements[ element.name ], this._.elements.$ ],
                filter, ret;

            for (var i = 0; i < 3; i++) {
                filter = filters[ i ];
                if (filter) {
                    ret = filter.filter(element, this);

                    if (ret === FALSE)
                        return NULL;

                    if (ret && ret != element)
                        return this.onNode(ret);

                    // The non-root element has been dismissed by one of the filters.
                    if (element.parent && !element.name)
                        break;
                }
            }

            return element;
        },

        onNode : function(node) {
            var type = node.type;

            return type == KEN.NODE_ELEMENT ? this.onElement(node) :
                type == KEN.NODE_TEXT ? new KE.HtmlParser.Text(this.onText(node.value)) :
                    NULL;
        },

        onAttribute : function(element, name, value) {
            var filter = this._.attributes[ name ];

            if (filter) {
                var ret = filter.filter(value, element, this);

                if (ret === FALSE)
                    return FALSE;

                if (typeof ret != 'undefined')
                    return ret;
            }

            return value;
        }
    });
    function filterName(name, filters) {
        for (var i = 0; name && i < filters.length; i++) {
            var filter = filters[ i ];
            name = name.replace(filter[ 0 ], filter[ 1 ]);
        }
        return name;
    }

    function addItemsToList(list, items, priority) {
        if (typeof items == 'function')
            items = [ items ];

        var i, j,
            listLength = list.length,
            itemsLength = items && items.length;

        if (itemsLength) {
            // Find the index to insert the items at.
            for (i = 0; i < listLength && list[ i ].pri < priority; i++) { /*jsl:pass*/
            }

            // Add all new items to the list at the specific index.
            for (j = itemsLength - 1; j >= 0; j--) {
                var item = items[ j ];
                if (item) {
                    item.pri = priority;
                    list.splice(i, 0, item);
                }
            }
        }
    }

    function addNamedItems(hashTable, items, priority) {
        if (items) {
            for (var name in items) {
                var current = hashTable[ name ];

                hashTable[ name ] =
                    transformNamedItem(
                        current,
                        items[ name ],
                        priority);

                if (!current)
                    hashTable.$length++;
            }
        }
    }

    function transformNamedItem(current, item, priority) {
        if (item) {
            item.pri = priority;

            if (current) {
                // If the current item is not an Array, transform it.
                if (!current.splice) {
                    if (current.pri > priority)
                        current = [ item, current ];
                    else
                        current = [ current, item ];

                    current.filter = callItems;
                }
                else
                    addItemsToList(current, item, priority);

                return current;
            }
            else {
                item.filter = item;
                return item;
            }
        }
        return undefined;
    }

    // Invoke filters sequentially on the array, break the iteration
    // when it doesn't make sense to continue anymore.
    function callItems(currentEntry) {
        var isNode = currentEntry.type
            || currentEntry instanceof KE.HtmlParser.Fragment;

        for (var i = 0; i < this.length; i++) {
            // Backup the node info before filtering.
            if (isNode) {
                var orgType = currentEntry.type,
                    orgName = currentEntry.name;
            }

            var item = this[ i ],
                ret = item.apply(window, arguments);

            if (ret === FALSE)
                return ret;

            // We're filtering node (element/fragment).
            if (isNode) {
                // No further filtering if it's not anymore
                // fitable for the subsequent filters.
                if (ret && ( ret.name != orgName
                    || ret.type != orgType )) {
                    return ret;
                }
            }
            // Filtering value (nodeName/textValue/attrValue).
            else {
                // No further filtering if it's not
                // any more values.
                if (typeof ret != 'string')
                    return ret;
            }

            ret != undefined && ( currentEntry = ret );
        }
        return currentEntry;
    }

    KE.HtmlParser.Filter = Filter;
    var FilterP = Filter.prototype;
    KE.Utils.extern(FilterP, {
        "addRules":FilterP.addRules,
        "onElementName":FilterP.onElementName,
        "onAttributeName":FilterP.onAttributeName,
        "onText":FilterP.onText,
        "onComment":FilterP.onComment,
        "onFragment":FilterP.onFragment,
        "onElement":FilterP.onElement,
        "onNode":FilterP.onNode,
        "onAttribute":FilterP.onAttribute
    });
    KE.HtmlParser["Filter"] = Filter;
});
/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
KISSY.Editor.add("htmlparser-text", function() {
    var S = KISSY,
        KE = S.Editor,
        KEN = KE.NODE,
        TRUE = true,
        FALSE = false,
        NULL = null;

    /**
     * A lightweight representation of HTML text.
     * @constructor
     * @example
     */
    function MText(value) {
        /**
         * The text value.
         * @type String
         * @example
         */
        this.value = value;

        /** @private */
        this._ = {
            isBlockLike : FALSE
        };
    }

    S.augment(MText, {
        /**
         * The node type. This is a constant value set to { KEN.NODE_TEXT}.
         * @type Number
         * @example
         */
        type : KEN.NODE_TEXT,

        /**
         * Writes the HTML representation of this text to a HtmlWriter.
         *  {HtmlWriter} writer The writer to which write the HTML.
         * @example
         */
        writeHtml : function(writer, filter) {
            var text = this.value;

            if (filter && !( text = filter.onText(text, this) ))
                return;

            writer.text(text);
        }
    });

    KE.HtmlParser.Text = MText;
    KE.HtmlParser["Text"] = MText;
});
/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
KISSY.Editor.add("htmlparser-comment", function() {
    var KE = KISSY.Editor,KEN = KE.NODE;

    /**
     * @constructor
     * @param value
     */
    function MComment(value) {
        /**
         * The comment text.
         * @type String
         * @example
         */
        this.value = value;

        /** @private */
        this._ =
        {
            isBlockLike : false
        };
    }

    KE.HtmlParser.Comment = MComment;
    KE.HtmlParser["Comment"] = MComment;
    MComment.prototype = {
        constructor:MComment,
        /**
         * The node type. This is a constant value set to  NODE_COMMENT.
         * @type Number
         * @example
         */
        type : KEN.NODE_COMMENT,

        /**
         * Writes the HTML representation of this comment to a CKEDITOR.htmlWriter.
         * @param  writer The writer to which write the HTML.
         * @example
         */
        writeHtml : function(writer, filter) {
            var comment = this.value;

            if (filter) {
                if (!( comment = filter.onComment(comment, this) ))
                    return;

                if (typeof comment != 'string') {
                    comment.parent = this.parent;
                    comment.writeHtml(writer, filter);
                    return;
                }
            }

            writer.comment(comment);
        }
    };
});
/**
 * background-color support for kissy editor
 * @author : yiminghe@gmail.com
 */
KISSY.Editor.add("bgcolor", function(editor) {
    var S = KISSY,
        KE = S.Editor,
        ColorSupport = KE.ColorSupport,
        colorButton_backStyle = {
            element        : 'span',
            styles        : { 'background-color' : '#(color)' }
        };


    editor.addPlugin(function() {
        new ColorSupport({
            editor:editor,
            styles:colorButton_backStyle,
            title:"背景颜色",
            contentCls:"ke-toolbar-bgcolor",
            text:"bgcolor"
        });
    });
});
/**
 * bubble or tip view for kissy editor
 * @author:yiminghe@gmail.com
 */
KISSY.Editor.add("bubbleview", function() {
    var KE = KISSY.Editor,
        S = KISSY,
        Event = S.Event,
        DOM = S.DOM,
        Node = S.Node,
        markup = '<div class="ke-bubbleview-bubble" onmousedown="return false;"></div>';

    if (KE.BubbleView) return;
    function BubbleView(cfg) {
        BubbleView.superclass.constructor.apply(this, arguments);
        if (cfg.init)
            cfg.init.call(this);
    }

    var holder = {};


    /**
     * 延迟化创建实例
     * @param cfg
     */
    BubbleView.attach = function(cfg) {
        var pluginInstance = cfg.pluginInstance,
            pluginName = cfg.pluginName,
            editor = pluginInstance.editor,
            h = holder[pluginName];
        if (!h) return;
        var func = h.cfg.func,
            bubble = holder[pluginName].bubble;
        //借鉴google doc tip提示显示
        editor.on("selectionChange", function(ev) {
            var elementPath = ev.path,
                elements = elementPath.elements,
                a,
                lastElement;
            if (elementPath && elements) {
                lastElement = elementPath.lastElement;
                if (!lastElement) return;
                a = func(lastElement);

                if (a) {
                    bubble = getInstance(pluginName);
                    bubble._selectedEl = a;
                    bubble._plugin = pluginInstance;
                    bubble.show();
                } else if (bubble) {
                    bubble._selectedEl = bubble._plugin = null;
                    bubble.hide();
                }
            }
        });

        Event.on(DOM._4e_getWin(editor.document), "scroll blur", function() {
            bubble && bubble.hide();
        });
        Event.on(document, "click", function() {
            bubble && bubble.hide();
        });
    };
    function getInstance(pluginName) {
        var h = holder[pluginName];
        if (!h.bubble)
            h.bubble = new BubbleView(h.cfg);
        return h.bubble;
    }

    BubbleView.register = function(cfg) {
        var pluginName = cfg.pluginName;
        holder[pluginName] = {
            cfg:cfg
        };
    };
    BubbleView.ATTRS = {
        //bubble 默认false
        focusMgr:{
            value:false
        },
        draggable:{
            value:false
        },
        "zIndex":{value:KE.baseZIndex(KE.zIndexManager.BUBBLE_VIEW)}
    };
    S.extend(BubbleView, KE.SimpleOverlay, {
        /**
         * 当前选中元素
         */
        //_selectedEl,
        /**
         * 当前关联插件实例
         */
        //_plugin
        _createEl:function() {
            var self = this,el = new Node(markup).appendTo(document.body);
            self.el = el;
            self.set("el", el);
        },
        show:function() {
            var self = this,
                a = self._selectedEl,
                xy = a._4e_getOffset(document);
            xy.top += a.height() + 5;
            BubbleView.superclass.show.call(self, xy);
        }
    });

    KE.BubbleView = BubbleView;
});/**
 * triple state button for kissy editor
 * @author: yiminghe@gmail.com
 */
KISSY.Editor.add("button", function(editor) {
    var KE = KISSY.Editor,
        S = KISSY,
        ON = "on",
        OFF = "off",
        DISABLED = "disabled",
        Node = S.Node,
        BUTTON_CLASS = "ke-triplebutton",
        ON_CLASS = "ke-triplebutton-on",
        OFF_CLASS = "ke-triplebutton-off",
        ACTIVE_CLASS = "ke-triplebutton-active",
        DISABLED_CLASS = "ke-triplebutton-disabled",
        BUTTON_HTML = "<a class='" +
            [BUTTON_CLASS,OFF_CLASS].join(" ")
            + "' href='#'" +
            "" +
            //' tabindex="-1"' +
            //' hidefocus="true"' +
            ' role="button"' +
            //' onblur="this.style.cssText = this.style.cssText;"' +
            //' onfocus="event&&event.preventBubble();return false;"' +
            "></a>";
    if (KE.TripleButton) return;

    function TripleButton(cfg) {
        TripleButton.superclass.constructor.call(this, cfg);
        this._init();
    }

    TripleButton.ON = ON;
    TripleButton.OFF = OFF;
    TripleButton.DISABLED = DISABLED;

    TripleButton.ON_CLASS = ON_CLASS;
    TripleButton.OFF_CLASS = OFF_CLASS;
    TripleButton.DISABLED_CLASS = DISABLED_CLASS;

    TripleButton.ATTRS = {
        state: {value:OFF},
        container:{},
        text:{},
        contentCls:{},
        cls:{},
        el:{}
    };


    S.extend(TripleButton, S.Base, {
        _init:function() {
            var self = this,
                container = self.get("container"),
                elHolder = self.get("el"),
                title = self.get("title"),
                text = self.get("text"),
                contentCls = self.get("contentCls");
            self.el = new Node(BUTTON_HTML);
            var el = self.el;
            el._4e_unselectable();
            self._attachCls();
            //button有文子
            if (text) {
                el.html(text);
                //直接上图标
            } else if (contentCls) {
                el.html("<span class='ke-toolbar-item " +
                    contentCls + "'></span>");
                el.one("span")._4e_unselectable();
            }
            if (title) el.attr("title", title);
            //替换已有元素
            if (elHolder) {
                elHolder[0].parentNode.replaceChild(el[0], elHolder[0]);
            }
            //加入容器
            else if (container) {
                container.append(el);
            }
            el.on("click", self._action, self);
            self.on("afterStateChange", self._stateChange, self);


            if (!self.get("cls")) {
                //添加鼠标点击视觉效果
                el.on("mousedown", function() {
                    if (self.get("state") == OFF) {
                        el.addClass(ACTIVE_CLASS);
                    }
                });
                el.on("mouseup mouseleave", function() {
                    if (self.get("state") == OFF &&
                        el.hasClass(ACTIVE_CLASS)) {
                        //click 后出发
                        setTimeout(function() {
                            el.removeClass(ACTIVE_CLASS);
                        }, 300);
                    }
                });
            }
        },
        _attachCls:function() {
            var self = this;
            var cls = self.get("cls");
            if (cls) self.el.addClass(cls);
        },

        _stateChange:function(ev) {
            var n = ev.newVal,self = this;
            self["_" + n]();
            self._attachCls();
        },
        disable:function() {
            var self = this;
            self._savedState = self.get("state");
            self.set("state", DISABLED);
        },
        enable:function() {
            var self = this;
            if (self.get("state") == DISABLED)
                self.set("state", self._savedState);
        },
        _action:function(ev) {
            var self = this;
            self.fire(self.get("state") + "Click", ev);
            self.fire("click", ev);
            ev.preventDefault();
        },
        bon:function() {
            this.set("state", ON);
        },
        boff:function() {
            this.set("state", OFF);
        },
        _on:function() {
            this.el[0].className = [BUTTON_CLASS,ON_CLASS].join(" ");
        },
        _off:function() {
            this.el[0].className = [BUTTON_CLASS,OFF_CLASS].join(" ");
        },
        _disabled:function() {
            this.el[0].className = [BUTTON_CLASS,DISABLED_CLASS].join(" ");
        }
    });
    KE.TripleButton = TripleButton;
});
/**
 * monitor user's paste key ,clear user input,modified from ckeditor
 * @author: yiminghe@gmail.com
 */
KISSY.Editor.add("clipboard", function(editor) {
    var S = KISSY,
        KE = S.Editor,
        Node = S.Node,
        UA = S.UA,
        KERange = KE.Range,
        KER = KE.RANGE,
        Event = S.Event;
    if (!KE.Paste) {
        (function() {
            function Paste(editor) {
                this.editor = editor;
                this._init();
            }

            S.augment(Paste, {
                _init:function() {
                    var self = this,editor = self.editor;
                    if (UA.ie)
                        Event.on(editor.document, "keydown", self._paste, self);
                    else  Event.on(editor.document, "paste", self._paste, self);

                    editor.addCommand("copy", new cutCopyCmd("copy"));
                    editor.addCommand("cut", new cutCopyCmd("cut"));
                    editor.addCommand("paste", new cutCopyCmd("paste"));

                },
                _paste:function(ev) {
                    if (ev.type === 'keydown' &&
                        !(ev.keyCode === 86 &&
                            (ev.ctrlKey || ev.metaKey)
                            )) {
                        return;
                    }


                    var self = this,editor = self.editor,doc = editor.document;
                    //防止 ie 过快报错
                    if (self._running) {
                        ev.halt();
                        return;
                    }
                    var sel = editor.getSelection(),
                        range = new KERange(doc);

                    // Create container to paste into
                    var pastebin = new Node(UA.webkit ? '<body></body>' : '<div></div>', null, doc);
                    // Safari requires a filler node inside the div to have the content pasted into it. (#4882)
                    UA.webkit && pastebin[0].appendChild(doc.createTextNode('\xa0'));
                    doc.body.appendChild(pastebin[0]);

                    pastebin.css({
                        position : 'absolute',
                        // Position the bin exactly at the position of the selected element
                        // to avoid any subsequent document scroll.
                        top : sel.getStartElement().offset().top + 'px',
                        width : '1px',
                        height : '1px',
                        overflow : 'hidden'
                    });

                    // It's definitely a better user experience if we make the paste-bin pretty unnoticed
                    // by pulling it off the screen.
                    pastebin.css('left', '-1000px');

                    var bms = sel.createBookmarks();

                    // Turn off design mode temporarily before give focus to the paste bin.

                    range.setStartAt(pastebin, KER.POSITION_AFTER_START);
                    range.setEndAt(pastebin, KER.POSITION_BEFORE_END);
                    range.select(true);
                    self._running = true;
                    // Wait a while and grab the pasted contents
                    setTimeout(function() {
                        pastebin._4e_remove();

                        // Grab the HTML contents.
                        // We need to look for a apple style wrapper on webkit it also adds
                        // a div wrapper if you copy/paste the body of the editor.
                        // Remove hidden div and restore selection.
                        var bogusSpan;

                        pastebin = ( UA.webkit
                            && ( bogusSpan = pastebin._4e_first() )
                            && (bogusSpan.hasClass('Apple-style-span') ) ?
                            bogusSpan : pastebin );
                        sel.selectBookmarks(bms);
                        //console.log(pastebin.html());
                        editor.insertHtml(pastebin.html());
                        self._running = false;
                    }, 0);
                }
            });
            KE.Paste = Paste;


            // Tries to execute any of the paste, cut or copy commands in IE. Returns a
            // boolean indicating that the operation succeeded.
            var execIECommand = function(editor, command) {
                var doc = editor.document,
                    body = new Node(doc.body);

                var enabled = false;
                var onExec = function() {
                    enabled = true;
                };

                // The following seems to be the only reliable way to detect that
                // clipboard commands are enabled in IE. It will fire the
                // onpaste/oncut/oncopy events only if the security settings allowed
                // the command to execute.
                body.on(command, onExec);

                // IE6/7: document.execCommand has problem to paste into positioned element.
                ( UA.ie > 7 ? doc : doc.selection.createRange() ) [ 'execCommand' ](command);

                body.detach(command, onExec);

                return enabled;
            };

            // Attempts to execute the Cut and Copy operations.
            var tryToCutCopy =
                UA.ie ?
                    function(editor, type) {
                        return execIECommand(editor, type);
                    }
                    : // !IE.
                    function(editor, type) {
                        try {
                            // Other browsers throw an error if the command is disabled.
                            return editor.document.execCommand(type);
                        }
                        catch(e) {
                            return false;
                        }
                    };

            var error_types = {
                "cut":"您的浏览器安全设置不允许编辑器自动执行剪切操作，请使用键盘快捷键(Ctrl/Cmd+X)来完成",
                "copy":"您的浏览器安全设置不允许编辑器自动执行复制操作，请使用键盘快捷键(Ctrl/Cmd+C)来完成",
                "paste":"您的浏览器安全设置不允许编辑器自动执行粘贴操作，请使用键盘快捷键(Ctrl/Cmd+V)来完成"
            };

            // A class that represents one of the cut or copy commands.
            var cutCopyCmd = function(type) {
                this.type = type;
                this.canUndo = ( this.type == 'cut' );		// We can't undo copy to clipboard.
            };

            cutCopyCmd.prototype =
            {
                exec : function(editor) {
                    this.type == 'cut' && fixCut(editor);

                    var success = tryToCutCopy(editor, this.type);

                    if (!success)
                        alert(error_types[this.type]);		// Show cutError or copyError.

                    return success;
                }
            };

            // Paste command.
            var pasteCmd =
            {
                canUndo : false,

                exec :
                    UA.ie ?
                        function(editor) {
                            // Prevent IE from pasting at the begining of the document.
                            editor.focus();

                            if (!execIECommand(editor, 'paste')) {
                                alert(error_types["paste"]);
                                return false;
                            }
                        }
                        :
                        function(editor) {
                            try {
                                if (!editor.document.$.execCommand('Paste', false, null)) {
                                    throw 0;
                                }
                            }
                            catch (e) {
                                alert(error_types["paste"]);
                                return false;
                            }
                        }
            };


            var KES = KE.Selection;
            // Cutting off control type element in IE standards breaks the selection entirely. (#4881)
            function fixCut(editor) {
                if (!UA.ie ||
                    editor.document.compatMode == 'BackCompat')
                    return;

                var sel = editor.getSelection();
                var control;
                if (( sel.getType() == KES.SELECTION_ELEMENT ) && ( control = sel.getSelectedElement() )) {
                    var range = sel.getRanges()[ 0 ];
                    var dummy = new Node(editor.document.createTextNode(''));
                    dummy.insertBefore(control);
                    range.setStartBefore(dummy);
                    range.setEndAfter(control);
                    sel.selectRanges([ range ]);

                    // Clear up the fix if the paste wasn't succeeded.
                    setTimeout(function() {
                        // Element still online?
                        if (control.parent()) {
                            dummy.remove();
                            sel.selectElement(control);
                        }
                    }, 0);
                }
            }

            var lang = {
                "copy":"复制",
                "paste":"粘贴",
                "cut":"剪切"
            };

            function stateFromNamedCommand(command, doc) {
                // IE Bug: queryCommandEnabled('paste') fires also 'beforepaste(copy/cut)',
                // guard to distinguish from the ordinary sources( either
                // keyboard paste or execCommand ) (#4874).
                //UA.ie && ( depressBeforeEvent = 1 );
                var retval = doc.queryCommandEnabled(command) ?
                    true :
                    false;
                //depressBeforeEvent = 0;
                return retval;
            }

            KE.on("contextmenu", function(ev) {
                //debugger
                var contextmenu = ev.contextmenu,
                    editor = contextmenu.cfg["editor"],
                    //原始内容
                    el = contextmenu.el.originalEl,
                    pastes = {"copy":0,"cut":0,"paste":0};
                for (var i in pastes) {
                    if (!pastes.hasOwnProperty(i))return;
                    pastes[i] = el.one(".ke-paste-" + i);
                    (function(cmd) {
                        var cmdObj = pastes[cmd];
                        if (!cmdObj) {
                            cmdObj = new Node("<a href='#'" +
                                "class='ke-paste-" + cmd + "'>"
                                + lang[cmd]
                                + "</a>").appendTo(el);
                            cmdObj.on("click", function(ev) {
                                ev.halt();
                                if (cmdObj.hasClass("ke-menuitem-disable"))
                                    return;
                                contextmenu.hide();

                                //给 ie 一点 hide() 中的事件触发 handler 运行机会，
                                // 原编辑器获得焦点后再进行下步操作
                                setTimeout(function() {
                                    editor.execCommand(cmd);
                                }, 30);
                            });
                        }
                        pastes[cmd] = cmdObj;
                    })(i);
                    var cmdObj = pastes[i];
                    if (stateFromNamedCommand(i, editor.document)) {
                        cmdObj.removeClass("ke-menuitem-disable");
                    } else {
                        cmdObj.addClass("ke-menuitem-disable");
                    }
                }
            });
        })();
    }

    editor.addPlugin(function() {
        new KE.Paste(editor);
    });
});
/**
 * color support for kissy editor
 * @author : yiminghe@gmail.com
 */
KISSY.Editor.add("colorsupport", function(editor) {
    var KE = KISSY.Editor,
        S = KISSY,
        Node = S.Node,
        Event = S.Event,
        Overlay = KE.SimpleOverlay,
        TripleButton = KE.TripleButton,
        //KEStyle = KE.Style,
        DOM = S.DOM;
    if (KE.ColorSupport) return;

    DOM.addStyleSheet(".ke-color-panel a {" +
        "display: block;" +
        "color:black;" +
        "text-decoration: none;" +
        "}" +
        "" +
        ".ke-color-panel a:hover {" +
        "color:black;" +
        "text-decoration: none;" +
        "}" +
        ".ke-color-panel a:active {" +
        "color:black;" +
        "}" +

        ".ke-color-palette {" +
        "    margin: 5px 8px 8px;" +
        "}" +

        ".ke-color-palette table {" +
        "    border: 1px solid #666666;" +
        "    border-collapse: collapse;" +
        "}" +

        ".ke-color-palette td {" +
        "    border-right: 1px solid #666666;" +
        "    height: 18px;" +
        "    width: 18px;" +
        "}" +

        "a.ke-color-a {" +
        "    height: 18px;" +
        "    width: 18px;" +
        "}" +

        "a.ke-color-a:hover {" +
        "    border: 1px solid #ffffff;" +
        "    height: 16px;" +
        "    width: 16px;" +
        "}" +
        "a.ke-color-remove {" +
        "  padding:3px 8px;" +
        "  margin:2px 0 3px 0;" +
        "}" +
        "a.ke-color-remove:hover {" +
        "    background-color: #D6E9F8;" +
        "}", "ke-color-plugin");

    function padding2(str) {
        return ("0" + str).slice(str.length - 1, str.length + 1);
    }

    var rgbColorReg = /^rgb\((\d+),(\d+),(\d+)\)$/i;

    function normalColor(color) {
        color = S.trim(color);
        if (color.charAt(0) == "#") color = color.substring(1);
        //console.log(color);
        color = color.replace(/\s+/g, "");
        var str = "",simpleColorReg = /^[0-9a-f]{3,3}$/i;

        if (simpleColorReg.test(color)) {
            str = color.replace(/[0-9a-f]/ig, function(m) {
                return m + m;
            });
        } else {
            var m = color.match(rgbColorReg);
            if (m && m[0]) {
                for (var i = 1; i < 4; i++) {
                    str += padding2(parseInt(m[i]).toString(16));
                }
            } else {
                str = color;
            }
        }
        return "#" + str.toLowerCase();
    }

    var COLORS = [
        ["000", "444", "666", "999", "CCC", "EEE", "F3F3F3", "FFF"],
        ["F00", "F90", "FF0", "0F0", "0FF", "00F", "90F", "F0F"],
        [
            "F4CCCC", "FCE5CD", "FFF2CC", "D9EAD3", "D0E0E3", "CFE2F3", "D9D2E9", "EAD1DC",
            "EA9999", "F9CB9C", "FFE599", "B6D7A8", "A2C4C9", "9FC5E8", "B4A7D6", "D5A6BD",
            "E06666", "F6B26B", "FFD966", "93C47D", "76A5AF", "6FA8DC", "8E7CC3", "C27BAD",
            "CC0000", "E69138", "F1C232", "6AA84F", "45818E", "3D85C6", "674EA7", "A64D79",
            "990000", "B45F06", "BF9000", "38761D", "134F5C", "0B5394", "351C75", "741B47",
            "660000", "783F04", "7F6000", "274E13", "0C343D", "073763", "20124D", "4C1130"
        ]
    ],
        html = "<div class='ke-color-panel'>" +
            "<a class='ke-color-remove' " +
            "href=\"javascript:void('清除');\">" +
            "清除" +
            "</a>";
    for (var i = 0; i < 3; i++) {
        html += "<div class='ke-color-palette'><table>";
        var c = COLORS[i],l = c.length / 8;
        for (var k = 0; k < l; k++) {
            html += "<tr>";
            for (var j = 0; j < 8; j++) {
                var currentColor = normalColor(c[8 * k + j]);
                html += "<td>";
                html += "<a href='javascript:void(0);' " +
                    "class='ke-color-a' " +
                    "style='background-color:"
                    + currentColor
                    + "'" +
                    "></a>";
                html += "</td>";
            }
            html += "</tr>";
        }
        html += "</table></div>";
    }
    html += "" +
        "<div>" +
        "<a class='ke-button ke-color-others'>其他颜色</a>" +
        "</div>" +
        "</div>";

    function ColorSupport(cfg) {
        var self = this;
        ColorSupport.superclass.constructor.call(self, cfg);
        self._init();
    }

    ColorSupport.ATTRS = {
        editor:{},
        styles:{},
        contentCls:{},
        text:{}
    };
    S.extend(ColorSupport, S.Base, {
        _init:function() {
            var self = this,
                editor = self.get("editor"),
                toolBarDiv = editor.toolBarDiv,
                el = new TripleButton({
                    container:toolBarDiv,
                    title:self.get("title"),
                    contentCls:self.get("contentCls")
                });

            el.on("offClick", self._showColors, self);
            self.el = el;
            KE.Utils.lazyRun(self, "_prepare", "_real");
            KE.Utils.sourceDisable(editor, self);
        },
        disable:function() {
            this.el.disable();
        },
        enable:function() {
            this.el.enable();
        },
        _hidePanel:function(ev) {
            var self = this,
                el = self.el.el,
                t = ev.target,
                colorWin = self.colorWin;
            //当前按钮点击无效
            if (el._4e_equals(t) || el._4e_contains(t)) {
                return;
            }
            colorWin.hide();
        },
        _selectColor:function(ev) {
            ev.halt();
            var self = this,
                t = ev.target;
            if (DOM._4e_name(t) == "a" && !DOM.hasClass(t, "ke-button")) {
                t = new Node(t);
                self._applyColor(normalColor(t._4e_style("background-color")));
                self.colorWin.hide();
            }
        },
        _applyColor:function(c) {
            var self = this,
                editor = self.get("editor"),
                doc = editor.document,
                styles = self.get("styles");
            editor.fire("save");
            if (c)
                new KE.Style(styles, {
                    color:c
                }).apply(doc);
            else
            // Value 'inherit'  is treated as a wildcard,
            // which will match any value.
            //清除已设格式
                new KE.Style(styles, {
                    color:"inherit"
                }).remove(doc);
            editor.fire("save");
        },
        _prepare:function() {
            var self = this,
                doc = document,
                el = self.el,
                editor = self.get("editor"),
                colorPanel = new Node(html);
            self.colorWin = new Overlay({
                el:colorPanel,
                width:"170px",
                zIndex:editor.baseZIndex(KE.zIndexManager.POPUP_MENU),
                mask:false,
                focusMgr:false
            });

            colorPanel._4e_unselectable();
            colorPanel.on("click", self._selectColor, self);
            self.colorPanel = colorPanel;
            Event.on(doc, "click", self._hidePanel, self);
            Event.on(editor.document, "click", self._hidePanel, self);

            var colorWin = self.colorWin;
            colorWin.on("show", el.bon, el);
            colorWin.on("hide", el.boff, el);
            var others = colorPanel.one(".ke-color-others");
            others.on("click", function(ev) {
                ev.halt();
                colorWin.hide();
                editor.useDialog("colorsupport/dialog", function(dialog) {
                    dialog.show(self);
                });
            });
        },
        _real:function() {
            var self = this,
                el = self.el.el,
                colorPanel = self.colorPanel,
                xy = el.offset();
            xy.top += el.height() + 5;
            if (xy.left + colorPanel.width() > DOM.viewportWidth() - 60) {
                xy.left = DOM.viewportWidth() - colorPanel.width() - 60;
            }
            self.colorWin.show(xy);
        },
        _showColors:function(ev) {
            var self = this,
                colorWin = self.colorWin;
            if (colorWin && colorWin.get("visible")) {
                colorWin.hide();
            } else {
                self._prepare(ev);
            }
        }
    });
    KE.ColorSupport = ColorSupport;
});
/**
 * contextmenu for kissy editor
 * @author: yiminghe@gmail.com
 */
KISSY.Editor.add("contextmenu", function() {
    var KE = KISSY.Editor,
        S = KISSY,
        Node = S.Node,
        DOM = S.DOM,
        Event = S.Event,
        HTML = "<div onmousedown='return false;'>";
    if (KE.ContextMenu) return;

    /**
     * 组合使用 overlay
     * @param config
     */
    function ContextMenu(config) {
        this.cfg = config;
        //editor太复杂，防止循环引用
        //S.clone(config);
        KE.Utils.lazyRun(this, "_prepareShow", "_realShow");
    }

    //暂时将 editor 同 右键关联。
    ContextMenu.ATTRS = {
        editor:{}
    };

    var global_rules = [];
    /**
     * 多菜单管理
     */
    ContextMenu.register = function(cfg) {

        var cm = new ContextMenu(cfg),
            editor = cfg.editor,
            doc = editor.document;

        global_rules.push({
            doc:doc,
            rules:cfg.rules || [],
            instance:cm
        });

        if (!doc.ke_contextmenu) {
            doc.ke_contextmenu = 1;
            Event.on(doc, "mousedown", ContextMenu.hide);
            /*
             Event.on(doc, "contextmenu", function(ev) {
             ev.preventDefault();
             });*/
            Event.on(doc,
                //"mouseup"
                "contextmenu",
                function(ev) {
                    /*
                     if (ev.which != 3)
                     return;
                     */
                    ContextMenu.hide.call(this);
                    var t = new Node(ev.target);
                    while (t) {
                        var name = t._4e_name(),stop = false;
                        if (name == "body")break;
                        for (var i = 0; i < global_rules.length; i++) {
                            var instance = global_rules[i].instance,
                                rules = global_rules[i].rules,
                                doc2 = global_rules[i].doc;
                            if (doc === doc2 && applyRules(t[0], rules)) {
                                ev.preventDefault();
                                stop = true;
                                //ie 右键作用中，不会发生焦点转移，光标移动
                                //只能右键作用完后才能，才会发生光标移动,range变化
                                //异步右键操作
                                //qc #3764,#3767
                                setTimeout(function() {
                                    //console.log("show");
                                    instance.show(KE.Utils.getXY(ev.pageX,
                                        ev.pageY, doc,
                                        document));
                                }, 30);

                                break;
                            }
                        }
                        if (stop) break;
                        t = t.parent();
                    }
                });
        }
        return cm;
    };

    function applyRules(elem, rules) {
        for (var i = 0; i < rules.length; i++) {
            var rule = rules[i];
            //增加函数判断
            if (S.isFunction(rule)) {
                if (rule(new Node(elem))) return true;
            }
            else if (DOM.test(elem, rule))return true;
        }
        return false;
    }

    ContextMenu.hide = function() {
        var doc = this;
        for (var i = 0; i < global_rules.length; i++) {
            var instance = global_rules[i].instance,doc2 = global_rules[i].doc;
            if (doc === doc2)
                instance.hide();
        }
    };

    var Overlay = KE.SimpleOverlay;
    S.augment(ContextMenu, {
        /**
         * 根据配置构造右键菜单内容
         */
        _init:function() {
            var self = this,
                cfg = self.cfg,
                funcs = cfg.funcs;
            self.elDom = new Node(HTML);
            var el = self.elDom;

            //使它具备 overlay 的能力，其实这里并不是实体化
            self.el = new Overlay({
                el:el,
                width:cfg.width,
                cls:"ke-menu"
            });

            for (var f in funcs) {
                var a = new Node("<a href='#'>" + f + "</a>");
                el[0].appendChild(a[0]);
                (function(a, func) {
                    a._4e_unselectable();
                    a.on("click", function(ev) {
                        //先 hide 还原编辑器内焦点
                        self.hide();
                        //console.log("contextmenu hide");
                        ev.halt();
                        //给 ie 一点 hide() 中的事件触发 handler 运行机会，原编辑器获得焦点后再进行下步操作
                        setTimeout(func, 30);
                    });
                })(a, funcs[f]);
            }

        },

        hide : function() {
            this.el && this.el.hide();
        },
        _realShow:function(offset) {
            var self = this;
            //防止ie 失去焦点，取不到复制等状态
            KE.fire("contextmenu", {
                contextmenu:self
            });
            this.el.show(offset);
        },
        _prepareShow:function() {
            this._init();
        },
        show:function(offset) {
            this._prepareShow(offset);
        }
    });

    KE.ContextMenu = ContextMenu;
});
/**
 * dd support for kissy editor
 * @author:yiminghe@gmail.com
 */
KISSY.Editor.add("dd", function() {
    var S = KISSY,
        KE = S.Editor,
        Event = S.Event,
        UA = S.UA,
        DOM = S.DOM,
        Node = S.Node;
    if (KE.DD) return;
    KE.DD = {};

    function Manager() {
        var self = this;
        Manager.superclass.constructor.apply(self, arguments);
        self._init();
    }

    Manager.ATTRS = {
        /**
         * mousedown 后 buffer 触发时间  timeThred
         */
        bufferTime: { value: 200 },

        /**
         * 当前激活的拖动对象，在同一时间只有一个值，所以不是数组
         */
        activeDrag: { }
    };

    /*
     负责拖动涉及的全局事件：
     1.全局统一的鼠标移动监控
     2.全局统一的鼠标弹起监控，用来通知当前拖动对象停止
     3.为了跨越iframe而统一在底下的遮罩层
     */
    S.extend(Manager, S.Base, {
        _init: function() {
            var self = this;
            self._showShimMove = KE.Utils.throttle(self._move, self, 30);
        },

        /*
         全局鼠标移动事件通知当前拖动对象正在移动
         注意：chrome8: click 时 mousedown-mousemove-mouseup-click 也会触发 mousemove
         */
        _move: function(ev) {
            var activeDrag = this.get('activeDrag');
            S.log("move");
            if (!activeDrag) return;
            //防止 ie 选择到字
            ev.preventDefault();
            this._clearSelection();
            activeDrag._move(ev);
        },

        /**
         * 当前拖动对象通知全局：我要开始啦
         * 全局设置当前拖动对象，
         * 还要根据配置进行 buffer 处理
         * @param drag
         */
        _start: function(drag) {
            var self = this,
                bufferTime = self.get("bufferTime") || 0;

            //事件先要注册好，防止点击，导致 mouseup 时还没注册事件
            self._registerEvent();

            //是否中央管理，强制限制拖放延迟
            if (bufferTime) {
                self._bufferTimer = setTimeout(function() {
                    self._bufferStart(drag);
                }, bufferTime);
            } else {
                self._bufferStart(drag);
            }
        },

        _bufferStart: function(drag) {
            var self = this;
            self.set('activeDrag', drag);

            //真正开始移动了才激活垫片
            self._activeShim();
            drag._start();
        },

        /**
         * 全局通知当前拖动对象：你结束拖动了！
         * @param ev
         */
        _end: function(ev) {
            var self = this,
                activeDrag = self.get("activeDrag");
            self._unregisterEvent();
            if (self._bufferTimer) {
                clearTimeout(self._bufferTimer);
                self._bufferTimer = null;
            }
            self._shim && self._shim.css({
                display:"none"
            });

            if (!activeDrag) return;
            activeDrag._end(ev);
            self.set("activeDrag", null);
        },

        /**
         * 垫片只需创建一次
         */
        _activeShim: function() {
            var self = this,doc = document;
            //创造垫片，防止进入iframe，外面document监听不到 mousedown/up/move
            self._shim = new Node("<div " +
                "style='" +
                //red for debug
                "background-color:red;" +
                "position:absolute;" +
                "left:0;" +
                "width:100%;" +
                "top:0;" +
                "z-index:" +
                //覆盖iframe上面即可
                KE.baseZIndex(KE.zIndexManager.DD_PG)
                + ";" +
                "'></div>").appendTo(doc.body);
            //0.5 for debug
            self._shim.css("opacity", 0);
            self._activeShim = self._showShim;
            self._showShim();
        },

        _showShim: function() {
            var self = this;
            self._shim.css({
                display: "",
                height: DOM.docHeight()
            });
            self._clearSelection();
        },
        _clearSelection:function() {
            //清除由于浏览器导致的选择文字
            if (window.getSelection) {
                window.getSelection().removeAllRanges();
            }
            //防止 ie 莫名选择文字
            else if (document.selection) {
                document.selection.empty();
            }
        },

        /**
         * 开始时注册全局监听事件
         */
        _registerEvent: function() {
            var self = this,doc = document;
            S.log("_registerEvent");
            Event.on(doc, "mouseup", self._end, self);
            Event.on(doc, "mousemove", self._showShimMove, self);
        },

        /**
         * 结束时需要取消掉，防止平时无谓的监听
         */
        _unregisterEvent: function() {
            var self = this,doc = document;
            S.log("_unregisterEvent");
            Event.remove(doc, "mousemove", self._showShimMove, self);
            Event.remove(doc, "mouseup", self._end, self);
        }
    });

    KE.DD.DDM = new Manager();
    var DDM = KE.DD.DDM;

    /*
     拖放纯功能类
     */
    function Draggable() {
        var self = this;
        Draggable.superclass.constructor.apply(self, arguments);
        self._init();
    }

    Draggable.ATTRS = {
        //拖放节点
        node:{},
        //handler 集合，注意暂时必须在 node 里面
        handlers:{value:{}}
    };

    S.extend(Draggable, S.Base, {
        _init:function() {
            var self = this,
                node = self.get("node"),
                handlers = self.get("handlers");
            //DDM.reg(node);
            if (S.isEmptyObject(handlers)) {
                handlers[node[0].id] = node;
            }
            for (var h in handlers) {
                if (!handlers.hasOwnProperty(h)) continue;
                var hl = handlers[h],ori = hl.css("cursor");
                if (!hl._4e_equals(node)) {
                    if (!ori || ori === "auto")
                        hl.css("cursor", "move");
                    //ie 不能被选择了
                    //hl._4e_unselectable();
                }
            }
            node.on("mousedown", self._handleMouseDown, self);
        },
        _check:function(t) {
            var handlers = this.get("handlers");
            for (var h in handlers) {
                if (!handlers.hasOwnProperty(h)) continue;
                if (handlers[h]._4e_contains(t)
                    ||
                    //子区域内点击也可以启动
                    handlers[h]._4e_equals(t)) return true;
            }
            return false;
        },

        /**
         * 鼠标按下时，查看触发源是否是属于 handler 集合，
         * 保存当前状态
         * 通知全局管理器开始作用
         * @param ev
         */
        _handleMouseDown:function(ev) {
            var self = this,
                t = new Node(ev.target);
            if (!self._check(t)) return;
            //chrome 阻止了 flash 点击？？
            if (!UA.webkit) {
                //firefox 默认会拖动对象地址
                ev.preventDefault();
            }
            //
            DDM._start(self);

            var node = self.get("node"),
                mx = ev.pageX,
                my = ev.pageY,
                nxy = node.offset();
            self.startMousePos = {
                left:mx,
                top:my
            };
            self.startNodePos = nxy;
            self._diff = {
                left:mx - nxy.left,
                top:my - nxy.top
            };

        },
        _move:function(ev) {
            this.fire("move", ev)
        },
        _end:function() {
            this.fire("end");
        },
        _start:function() {
            this.fire("start");
        }
    });

    /*
     拖放实体，功能反应移动时，同时移动节点
     */
    function Drag() {
        Drag.superclass.constructor.apply(this, arguments);
    }

    S.extend(Drag, Draggable, {
        _init:function() {
            var self = this;
            Drag.superclass._init.apply(self, arguments);
            var node = self.get("node");
            self.on("move", function(ev) {
                var left = ev.pageX - self._diff.left,
                    top = ev.pageY - self._diff.top;
                node.offset({
                    left:left,
                    top:top
                })
            });
        }
    });

    KE.Draggable = Draggable;
    KE.Drag = Drag;

});/**
 * draft support for kissy editor
 * @author:yiminghe@gmail.com
 */
KISSY.Editor.add("draft", function(editor) {
    var S = KISSY,KE = S.Editor;
    if (!KE.Draft) {
        (function() {
            var Node = S.Node,
                LIMIT = 5,
                Event = S.Event,
                INTERVAL = 5,
                UA = KISSY.UA,
                JSON = S.JSON,
                DRAFT_SAVE = "ke-draft-save",
                localStorage = window[KE.STORE];

            function padding(n, l, p) {
                n += "";
                while (n.length < l) {
                    n = p + n;
                }
                return n;
            }

            function date(d) {
                if (S.isNumber(d)) {
                    d = new Date(d);
                }
                if (d instanceof Date)
                    return [
                        d.getFullYear(),
                        "-",
                        padding(d.getMonth() + 1, 2, "0"),
                        "-",
                        padding(d.getDate(), 2, "0"),
                        " ",
                        //"&nbsp;",
                        padding(d.getHours(), 2, "0"),
                        ":",
                        padding(d.getMinutes(), 2, "0"),
                        ":",
                        padding(d.getSeconds(), 2, "0")
                        //"&nbsp;",
                        //"&nbsp;"
                    ].join("");
                else
                    return d;
            }

            function Draft(editor) {
                this.editor = editor;
                this._init();
            }

            S.augment(Draft, {
                _init:function() {
                    var self = this,
                        editor = self.editor,
                        toolbar = editor.toolBarDiv,
                        statusbar = editor.statusDiv,
                        cfg = editor.cfg.pluginConfig;
                    cfg.draft = cfg.draft || {};
                    self.draftInterval = cfg.draft.interval
                        = cfg.draft.interval || INTERVAL;
                    self.draftLimit = cfg.draft.limit
                        = cfg.draft.limit || LIMIT;
                    var holder = new Node(
                        "<div class='ke-draft'>" +
                            "<spa class='ke-draft-title'>" +
                            "内容正文每" +
                            cfg.draft.interval
                            + "分钟自动保存一次。" +
                            "</span>" +
                            "</div>").appendTo(statusbar);
                    self.timeTip = new Node("<span class='ke-draft-time'>")
                        .appendTo(holder);

                    var save = new Node(
                        "<a " +
                            "class='ke-button ke-draft-save-btn' " +
                            "style='" +
                            "vertical-align:middle;" +
                            "padding:1px 9px;" +
                            "'>" +
                            "<span class='ke-draft-mansave'>" +
                            "</span>" +
                            "<span>立即保存</span>" +
                            "</a>"
                        ).appendTo(holder),
                        versions = new KE.Select({
                            container: holder,
                            menuContainer:document.body,
                            doc:editor.document,
                            width:"85px",
                            popUpWidth:"225px",
                            align:["r","t"],
                            title:"恢复编辑历史"
                        }),
                        str = localStorage.getItem(DRAFT_SAVE),
                        drafts = [],date;
                    self.versions = versions;
                    if (str) {
                        drafts = S.isString(str) ?
                            JSON.parse(decodeURIComponent(str)) : str;
                    }
                    self.drafts = drafts;
                    self.sync();

                    save.on("click", function() {
                        self.save(false);
                    });

                    /*
                     监控form提交，每次提交前保存一次，防止出错
                     */
                    (function() {
                        var textarea = editor.textarea,
                            form = textarea[0].form;
                        form && Event.on(form, "submit", function() {
                            self.save(false);
                        });
                    })();


                    setInterval(function() {
                        self.save(true);
                    }, self.draftInterval * 60 * 1000);

                    versions.on("click", self.recover, self);
                    self.holder = holder;
                    //KE.Utils.sourceDisable(editor, self);
                    if (cfg.draft.helpHtml) {
                        var help = new KE.TripleButton({
                            cls:"ke-draft-help",
                            title:"帮助",
                            text:"帮助",
                            container: holder
                        });
                        help.on("click", function() {
                            self._prepareHelp();
                        });
                        KE.Utils.lazyRun(self, "_prepareHelp", "_realHelp");
                        self.helpBtn = help.el;
                    }
                    self._holder = holder;

                },
                _prepareHelp:function() {
                    var self = this,
                        editor = self.editor,
                        cfg = editor.cfg.pluginConfig,
                        draftCfg = cfg.draft,
                        helpBtn = self.helpBtn,
                        help = new Node(draftCfg.helpHtml || "").appendTo(document.body);
                    var arrowCss = "height:0;" +
                        "position:absolute;" +
                        "font-size:0;" +
                        "width:0;" +
                        "border:8px #000 solid;" +
                        "border-color:#000 transparent transparent transparent;" +
                        "border-style:solid dashed dashed dashed;";
                    var arrow = new Node("<div style='" +
                        arrowCss +
                        "border-top-color:#CED5E0;" +
                        "'>" +
                        "<div style='" +
                        arrowCss +
                        "left:-8px;" +
                        "top:-10px;" +
                        "border-top-color:white;" +
                        "'>" +
                        "</div>" +
                        "</div>");
                    help.append(arrow);
                    help.css({
                        border:"1px solid #ACB4BE",
                        "text-align":"left"
                    });
                    self._help = new KE.SimpleOverlay({
                        el:help,
                        focusMgr:false,
                        draggable:false,
                        width:help.width() + "px",
                        mask:false
                    });
                    self._help.el.css("border", "none");
                    self._help.arrow = arrow;
                    Event.on([document,editor.document], "click", function(ev) {
                        var t = ev.target;
                        if (t == helpBtn[0] || helpBtn._4e_contains(t))
                            return;
                        self._help.hide();
                    })
                },
                _realHelp:function() {
                    var win = this._help,
                        helpBtn = this.helpBtn,
                        arrow = win.arrow;
                    win.show();
                    var off = helpBtn.offset();
                    win.el.offset({
                        left:(off.left - win.el.width()) + 17,
                        top:(off.top - win.el.height()) - 7
                    });
                    arrow.offset({
                        left:off.left - 2,
                        top:off.top - 8
                    });
                },
                disable:function() {
                    this.holder.css("visibility", "hidden");
                },
                enable:function() {
                    this.holder.css("visibility", "");
                },
                sync:function() {
                    var self = this,
                        draftLimit = self.draftLimit,
                        timeTip = self.timeTip,
                        versions = self.versions,drafts = self.drafts;
                    if (drafts.length > draftLimit)
                        drafts.splice(0, drafts.length - draftLimit);
                    var items = [],draft,tip;
                    for (var i = 0; i < drafts.length; i++) {
                        draft = drafts[i];
                        tip = (draft.auto ? "自动" : "手动") + "保存于 : "
                            + date(draft.date);
                        items.push({
                            name:tip,
                            value:i
                        });
                    }
                    versions.set("items", items.reverse());
                    timeTip.html(tip);
                    localStorage.setItem(DRAFT_SAVE, encodeURIComponent(JSON.stringify(drafts)));
                },

                save:function(auto) {
                    var self = this,
                        drafts = self.drafts,
                        //不使用rawdata
                        //undo 只需获得可视区域内代码
                        //可视区域内代码！= 最终代码
                        //代码模式也要支持草稿功能
                        //统一获得最终代码
                        data = editor.getData(true);

                    //如果当前内容为空，不保存版本
                    if (!data) return;

                    if (drafts[drafts.length - 1] &&
                        data == drafts[drafts.length - 1].content) {
                        drafts.length -= 1;
                    }
                    self.drafts = drafts.concat({
                        content:data,
                        date:new Date().getTime(),
                        auto:auto
                    });
                    self.sync();
                },

                recover:function(ev) {
                    var self = this,
                        editor = self.editor,
                        versions = self.versions,
                        drafts = self.drafts,
                        v = ev.newVal;
                    versions.reset("value");
                    if (confirm("确认恢复 " + date(drafts[v].date) + " 的编辑历史？")) {
                        editor.fire("save");
                        editor.setData(drafts[v].content);
                        editor.fire("save");
                    }
                }
            });
            KE.Draft = Draft;
        })();
    }

    editor.addPlugin(function() {
        KE.storeReady(function() {
            new KE.Draft(editor);
        });
    });


});/**
 * element path shown in status bar,modified from ckeditor
 * @modifier: yiminghe@gmail.com
 */
KISSY.Editor.add("elementpaths", function(editor) {
    var KE = KISSY.Editor,S = KISSY,Node = S.Node,DOM = S.DOM;
    if (!KE.ElementPaths) {

        (function() {

            DOM.addStyleSheet(".elementpath {" +
                "   padding: 0 5px;" +
                "    text-decoration: none;" +
                "}" +
                ".elementpath:hover {" +
                "    background: #CCFFFF;" +
                "    text-decoration: none;" +
                "}", "ke-ElementPaths");
            function ElementPaths(cfg) {
                this.cfg = cfg;
                this._cache = [];
                this._init();
            }

            S.augment(ElementPaths, {
                _init:function() {
                    var self = this,cfg = self.cfg,
                        editor = cfg.editor,
                        textarea = editor.textarea[0];
                    self.holder = new Node("<span>");
                    self.holder.appendTo(editor.statusDiv);
                    editor.on("selectionChange", self._selectionChange, self);
                    KE.Utils.sourceDisable(editor, self);
                },
                disable:function() {
                    this.holder.css("visibility", "hidden");
                },
                enable:function() {
                    this.holder.css("visibility", "");
                },
                _selectionChange:function(ev) {
                    //console.log(ev);
                    var self = this,
                        cfg = self.cfg,
                        editor = cfg.editor,
                        holder = self.holder,
                        statusDom = holder[0] || holder;
                    var elementPath = ev.path,
                        elements = elementPath.elements,
                        element,i,
                        cache = self._cache;

                    for (i = 0; i < cache.length; i++) {
                        cache[i].detach("click");
                        cache[i]._4e_remove();
                    }
                    self._cache = [];
                    // For each element into the elements path.
                    for (i = 0; i < elements.length; i++) {
                        element = elements[i];

                        var a = new Node("<a href='#' class='elementpath'>" +
                            //考虑 fake objects
                            (element.attr("_ke_real_element_type") || element._4e_name())
                            + "</a>");
                        self._cache.push(a);
                        (function(element) {
                            a.on("click", function(ev2) {
                                ev2.halt();
                                editor.focus();
                                setTimeout(function() {
                                    editor.getSelection().selectElement(element);
                                }, 50);
                            });
                        })(element);
                        if (statusDom.firstChild) {
                            DOM.insertBefore(a[0], statusDom.firstChild);
                        }
                        else {
                            statusDom.appendChild(a[0]);
                        }
                    }

                }
            });
            KE.ElementPaths = ElementPaths;
        })();
    }

    editor.addPlugin(function() {
        new KE.ElementPaths({
            editor:editor
        });
    });
});
/**
 * monitor user's enter and shift enter keydown,modified from ckeditor
 * @author: yiminghe@gmail.com
 */
KISSY.Editor.add("enterkey", function(editor) {
    var KE = KISSY.Editor,
        S = KISSY,
        //DOM = S.DOM,
        UA = S.UA,
        headerTagRegex = /^h[1-6]$/,
        dtd = KE.XHTML_DTD,
        Node = S.Node,
        Event = S.Event,
        Walker = KE.Walker,
        ElementPath = KE.ElementPath;
    if (!KE.enterBlock) {

        (function() {

            function getRange(editor) {
                // Get the selection ranges.
                var ranges = editor.getSelection().getRanges();
                // Delete the contents of all ranges except the first one.
                for (var i = ranges.length - 1; i > 0; i--) {
                    ranges[ i ].deleteContents();
                }
                // Return the first range.
                return ranges[ 0 ];
            }

            function enterBlock(editor) {
                //debugger;
                // Get the range for the current selection.
                var range = getRange(editor);
                var doc = range.document;
                // Exit the list when we're inside an empty list item block. (#5376)
                if (range.checkStartOfBlock() && range.checkEndOfBlock()) {
                    var path = new ElementPath(range.startContainer),
                        block = path.block;
                    //只有两层？
                    if (block &&
                        ( block._4e_name() == 'li' || block.parent()._4e_name() == 'li' )

                        ) {
                        if (editor.hasCommand('outdent')) {
                            editor.fire("save");
                            editor.execCommand('outdent');
                            editor.fire("save");
                            return;
                        } else {
                            return false;
                        }
                    }
                }

                // Determine the block element to be used.
                var blockTag = "p";

                // Split the range.
                var splitInfo = range.splitBlock(blockTag);

                if (!splitInfo)
                    return;

                // Get the current blocks.
                var previousBlock = splitInfo.previousBlock,
                    nextBlock = splitInfo.nextBlock;

                var isStartOfBlock = splitInfo.wasStartOfBlock,
                    isEndOfBlock = splitInfo.wasEndOfBlock;

                var node;

                // If this is a block under a list item, split it as well. (#1647)
                if (nextBlock) {
                    node = nextBlock.parent();
                    if (node._4e_name() == 'li') {
                        nextBlock._4e_breakParent(node);
                        nextBlock._4e_move(nextBlock._4e_next(), true);
                    }
                }
                else if (previousBlock && ( node = previousBlock.parent() ) && node._4e_name() == 'li') {
                    previousBlock._4e_breakParent(node);
                    range.moveToElementEditablePosition(previousBlock._4e_next());
                    previousBlock._4e_move(previousBlock._4e_previous());
                }

                // If we have both the previous and next blocks, it means that the
                // boundaries were on separated blocks, or none of them where on the
                // block limits (start/end).
                if (!isStartOfBlock && !isEndOfBlock) {
                    // If the next block is an <li> with another list tree as the first
                    // child, we'll need to append a filler (<br>/NBSP) or the list item
                    // wouldn't be editable. (#1420)
                    if (nextBlock._4e_name() == 'li'
                        &&
                        ( node = nextBlock._4e_first(Walker.invisible(true)) )
                        && S.inArray(node._4e_name(), ['ul', 'ol']))
                        (UA.ie ? new Node(doc.createTextNode('\xa0')) : new Node(doc.createElement('br'))).insertBefore(node);

                    // Move the selection to the end block.
                    if (nextBlock)
                        range.moveToElementEditablePosition(nextBlock);
                }
                else {
                    var newBlock;

                    if (previousBlock) {
                        // Do not enter this block if it's a header tag, or we are in
                        // a Shift+Enter (#77). Create a new block element instead
                        // (later in the code).
                        if (previousBlock._4e_name() == 'li' || !headerTagRegex.test(previousBlock._4e_name())) {
                            // Otherwise, duplicate the previous block.
                            newBlock = previousBlock._4e_clone();
                        }
                    }
                    else if (nextBlock)
                        newBlock = nextBlock._4e_clone();

                    if (!newBlock)
                        newBlock = new Node("<" + blockTag + ">", null, doc);

                    // Recreate the inline elements tree, which was available
                    // before hitting enter, so the same styles will be available in
                    // the new block.
                    var elementPath = splitInfo.elementPath;
                    if (elementPath) {
                        for (var i = 0, len = elementPath.elements.length; i < len; i++) {
                            var element = elementPath.elements[ i ];

                            if (element._4e_equals(elementPath.block) || element._4e_equals(elementPath.blockLimit))
                                break;
                            //<li><strong>^</strong></li>
                            if (dtd.$removeEmpty[ element._4e_name() ]) {
                                element = element._4e_clone();
                                newBlock._4e_moveChildren(element);
                                newBlock.append(element);
                            }
                        }
                    }

                    if (!UA.ie)
                        newBlock._4e_appendBogus();

                    range.insertNode(newBlock);

                    // This is tricky, but to make the new block visible correctly
                    // we must select it.
                    // The previousBlock check has been included because it may be
                    // empty if we have fixed a block-less space (like ENTER into an
                    // empty table cell).
                    if (UA.ie && isStartOfBlock && ( !isEndOfBlock || !previousBlock[0].childNodes.length )) {
                        // Move the selection to the new block.
                        range.moveToElementEditablePosition(isEndOfBlock ? previousBlock : newBlock);
                        range.select();
                    }

                    // Move the selection to the new block.
                    range.moveToElementEditablePosition(isStartOfBlock && !isEndOfBlock ? nextBlock : newBlock);
                }

                if (!UA.ie) {
                    if (nextBlock) {
                        // If we have split the block, adds a temporary span at the
                        // range position and scroll relatively to it.
                        var tmpNode = new Node(doc.createElement('span'));

                        // We need some content for Safari.
                        tmpNode.html('&nbsp;');

                        range.insertNode(tmpNode);
                        tmpNode._4e_scrollIntoView();
                        range.deleteContents();
                    }
                    else {
                        // We may use the above scroll logic for the new block case
                        // too, but it gives some weird result with Opera.
                        newBlock._4e_scrollIntoView();
                    }
                }
                range.select();
            }

            function EnterKey(editor) {
                var doc = editor.document;
                Event.on(doc, "keydown", function(ev) {
                    var keyCode = ev.keyCode;
                    if (keyCode === 13) {
                        if (ev.shiftKey) {
                        } else {
                            editor.fire("save");
                            var re = editor.execCommand("enterBlock");
                            editor.fire("save");
                            if (re !== false)ev.preventDefault();
                        }

                    }
                });
            }

            EnterKey.enterBlock = enterBlock;
            KE.EnterKey = EnterKey;
        })();
    }
    editor.addPlugin(function() {
        editor.addCommand("enterBlock", {
            exec:KE.EnterKey.enterBlock
        });
        KE.EnterKey(editor);
    });


});
/**
 * fakeobjects for music ,video,flash
 * @author: yiminghe@gmail.com
 */
KISSY.Editor.add("fakeobjects", function(editor) {
    var KE = KISSY.Editor,
        S = KISSY,
        Node = S.Node,
        KEN = KE.NODE,
    SPACER_GIF= KE.Config.base + 'theme/spacer.gif',
        HtmlParser = KE.HtmlParser,
        Editor = S.Editor,
        dataProcessor = editor.htmlDataProcessor,
        htmlFilter = dataProcessor && dataProcessor.htmlFilter;

    var htmlFilterRules = {
        elements : {
            /**
             * 生成最终html时，从编辑器html转化把fake替换为真实，并将style的width,height搞到属性上去
             * @param element
             */
            $ : function(element) {
                var attributes = element.attributes,
                    realHtml = attributes && attributes._ke_realelement,
                    realFragment = realHtml && new HtmlParser.Fragment.FromHtml(decodeURIComponent(realHtml)),
                    realElement = realFragment && realFragment.children[ 0 ];

                // If we have width/height in the element, we must move it into
                // the real element.
                if (realElement && element.attributes._ke_resizable) {
                    var style = element.attributes.style;
                    if (style) {
                        // Get the width from the style.
                        var match = /(?:^|\s)width\s*:\s*(\d+)/i.exec(style),
                            width = match && match[1];
                        // Get the height from the style.
                        match = /(?:^|\s)height\s*:\s*(\d+)/i.exec(style);
                        var height = match && match[1];

                        if (width)
                            realElement.attributes.width = width;

                        if (height)
                            realElement.attributes.height = height;
                    }
                }
                return realElement;
            }
        }
    };


    if (htmlFilter)
        htmlFilter.addRules(htmlFilterRules);


    if (dataProcessor) {
        S.mix(dataProcessor, {

            /**
             * 从外边真实的html，转为为编辑器代码支持的替换元素
             * @param realElement
             * @param className
             * @param realElementType
             * @param isResizable
             */
            createFakeParserElement:function(realElement, className, realElementType, isResizable, attrs) {
                var html;

                var writer = new HtmlParser.BasicWriter();
                realElement.writeHtml(writer);
                html = writer.getHtml();
                var style = realElement.attributes.style;
                if (realElement.attributes.width) {
                    style = "width:" + realElement.attributes.width + "px;" + style;
                }
                if (realElement.attributes.height) {
                    style = "height:" + realElement.attributes.height + "px;" + style;
                }
                var attributes = {
                    'class' : className,
                    src : SPACER_GIF,
                    _ke_realelement : encodeURIComponent(html),
                    _ke_real_node_type : realElement.type,
                    style:style,
                    align : realElement.attributes.align || ''
                };
                attrs && delete attrs.width;
                attrs && delete attrs.height;

                attrs && S.mix(attributes, attrs, false);

                if (realElementType)
                    attributes._ke_real_element_type = realElementType;

                if (isResizable)
                    attributes._ke_resizable = isResizable;

                return new HtmlParser.Element('img', attributes);
            }
        });
    }

    S.augment(Editor, {
        //ie6 ,object outHTML error
        createFakeElement:function(realElement, className, realElementType, isResizable, outerHTML, attrs) {
            var style = realElement.attr("style") || '';
            if (realElement.attr("width")) {
                style = "width:" + realElement.attr("width") + "px;" + style;
            }
            if (realElement.attr("height")) {
                style = "height:" + realElement.attr("height") + "px;" + style;
            }
            var self = this,attributes = {
                'class' : className,
                src : SPACER_GIF,
                _ke_realelement : encodeURIComponent(outerHTML || realElement._4e_outerHtml()),
                _ke_real_node_type : realElement[0].nodeType,
                //align : realElement.attr("align") || '',
                style:style
            };
            attrs && delete attrs.width;
            attrs && delete attrs.height;

            attrs && S.mix(attributes, attrs, false);
            if (realElementType)
                attributes._ke_real_element_type = realElementType;

            if (isResizable)
                attributes._ke_resizable = isResizable;
            return new Node("<img/>", attributes, self.document);
        },

        restoreRealElement:function(fakeElement) {
            if (fakeElement.attr('_ke_real_node_type') != KEN.NODE_ELEMENT)
                return null;
            var html = (decodeURIComponent(fakeElement.attr('_ke_realelement')));

            var temp = new Node('<div>', null, this.document);
            temp.html(html);
            // When returning the node, remove it from its parent to detach it.
            return temp._4e_first(function(n) {
                return n[0].nodeType == KEN.NODE_ELEMENT;
            })._4e_remove();
        }
    });

});
KISSY.Editor.add("flash", function(editor) {
    editor.addPlugin(function() {
        new KISSY.Editor.Flash(editor);
    });
});
/**
 * flash base for all flash-based plugin
 * @author:yiminghe@gmail.com
 */
KISSY.Editor.add("flash/support", function(editor) {
    var KE = KISSY.Editor,
        S = KISSY,
        UA = S.UA,
        Event = S.Event,
        ContextMenu = KE.ContextMenu,
        Node = S.Node,
        BubbleView = KE.BubbleView,
        TripleButton = KE.TripleButton,
        dataProcessor = editor.htmlDataProcessor,
        CLS_FLASH = 'ke_flash',
        TYPE_FLASH = 'flash',
        flashUtils = KE.Utils.flash,
        dataFilter = dataProcessor && dataProcessor.dataFilter;


    if (!KE.Flash) {

        (function() {

            var flashFilenameRegex = /\.swf(?:$|\?)/i;

            /**
             * 所有基于 flash 的插件基类，使用 template 模式抽象
             * @param editor
             */
            function Flash(editor) {
                var self = this;
                self.editor = editor;
                self._init();
            }

            Flash.isFlashEmbed = function (element) {
                var attributes = element.attributes;
                return (
                    attributes.type == 'application/x-shockwave-flash'
                        ||
                        flashFilenameRegex.test(attributes.src || '')
                    );
            };

            S.augment(Flash, {

                /**
                 * 配置信息，用于子类覆盖
                 * @override
                 */
                _config:function() {
                    var self = this;
                    self._cls = CLS_FLASH;
                    self._type = TYPE_FLASH;
                    self._contentCls = "ke-toolbar-flash";
                    self._tip = "插入Flash";
                    self._contextMenu = contextMenu;
                    self._flashRules = ["img." + CLS_FLASH];


                },
                _init:function() {
                    this._config();
                    var self = this,
                        editor = self.editor,
                        myContexts = {},
                        contextMenu = self._contextMenu;

                    //注册属于编辑器的功能实例
                    editor._toolbars = editor._toolbars || {};
                    editor._toolbars[self._type] = self;

                    //生成编辑器工具按钮
                    self.el = new TripleButton({
                        container:editor.toolBarDiv,
                        contentCls:self._contentCls,
                        title:self._tip
                    });
                    self.el.on("offClick", self.show, this);


                    //右键功能关联到编辑器实例
                    if (contextMenu) {
                        for (var f in contextMenu) {
                            (function(f) {
                                myContexts[f] = function() {
                                    contextMenu[f](editor);
                                }
                            })(f);
                        }
                    }
                    //注册右键，contextmenu时检测
                    ContextMenu.register({
                        editor:editor,
                        rules:self._flashRules,
                        width:"120px",
                        funcs:myContexts
                    });


                    //注册泡泡，selectionChange时检测
                    BubbleView.attach({
                        pluginName:self._type,
                        pluginInstance:self
                    });

                    //注册双击，双击时检测
                    Event.on(editor.document, "dblclick", self._dbclick, self);
                    KE.Utils.sourceDisable(editor, self);
                },
                disable:function() {
                    this.el.set("state", TripleButton.DISABLED);
                },
                enable:function() {
                    this.el.set("state", TripleButton.OFF);
                },

                /**
                 * 子类覆盖，如何从flash url得到合适的应用表示地址
                 * @override
                 * @param r flash 元素
                 */
                _getFlashUrl:function(r) {
                    return flashUtils.getUrl(r);
                },
                /**
                 * 更新泡泡弹出的界面，子类覆盖
                 * @override
                 * @param tipurl
                 * @param selectedFlash
                 */
                _updateTip:function(tipurl, selectedFlash) {
                    var self = this,
                        editor = self.editor,
                        r = editor.restoreRealElement(selectedFlash);
                    if (!r) return;
                    var url = self._getFlashUrl(r);
                    tipurl.html(url);
                    tipurl.attr("href", url);
                },

                //根据图片标志触发本插件应用
                _dbclick:function(ev) {
                    var self = this,t = new Node(ev.target);
                    if (t._4e_name() === "img" && t.hasClass(self._cls)) {
                        self.show(null, t);
                        ev.halt();
                    }
                },

                show:function(ev, selected) {
                    var self = this,
                        editor = self.editor;
                    editor.useDialog(self._type + "/dialog", function(dialog) {
                        dialog.show(selected);
                    });
                }
            });

            KE.Flash = Flash;

            /**
             * tip初始化，所有共享一个tip
             */
            var tipHtml = ' <a ' +
                'class="ke-bubbleview-url" ' +
                'target="_blank" ' +
                'href="#"></a> - '
                + ' <span class="ke-bubbleview-link ke-bubbleview-change">编辑</span> - '
                + ' <span class="ke-bubbleview-link ke-bubbleview-remove">删除</span>';

            /**
             * 泡泡判断是否选择元素符合
             * @param node
             */
            function checkFlash(node) {
                return node._4e_name() === 'img' &&
                    (!!node.hasClass(CLS_FLASH)) &&
                    node;
            }

            /**
             * 注册一个泡泡
             * @param pluginName
             * @param label
             * @param checkFlash
             */
            Flash.registerBubble = function(pluginName, label, checkFlash) {

                BubbleView.register({
                    pluginName:pluginName,
                    func:checkFlash,
                    init:function() {
                        var bubble = this,
                            el = bubble.el;
                        el.html(label + tipHtml);
                        var tipurl = el.one(".ke-bubbleview-url"),
                            tipchange = el.one(".ke-bubbleview-change"),
                            tipremove = el.one(".ke-bubbleview-remove");
                        //ie focus not lose
                        tipchange._4e_unselectable();
                        tipurl._4e_unselectable();
                        tipremove._4e_unselectable();

                        tipchange.on("click", function(ev) {
                            //回调show，传入选中元素
                            bubble._plugin.show(null, bubble._selectedEl);
                            ev.halt();
                        });

                        tipremove.on("click", function(ev) {
                            var flash = bubble._plugin;
                            //chrome remove 后会没有焦点
                            if (UA.webkit) {
                                var r = flash.editor.getSelection().getRanges();
                                r && r[0] && (r[0].collapse(true) || true) && r[0].select();
                            }
                            bubble._selectedEl._4e_remove();
                            bubble.hide();
                            flash.editor.notifySelectionChange();
                            ev.halt();
                        });

                        /*
                         位置变化，在显示前就设置内容，防止ie6 iframe遮罩不能正确大小
                         */
                        bubble.on("beforeVisibleChange", function(ev) {
                            var v = ev.newVal,a = bubble._selectedEl,
                                flash = bubble._plugin;
                            if (!v || !a)return;
                            flash._updateTip(tipurl, a);
                        });
                    }
                });
            };


            Flash.registerBubble("flash", "Flash 网址： ", checkFlash);
            Flash.checkFlash = checkFlash;

            //右键功能列表
            var contextMenu = {
                "Flash属性":function(editor) {
                    var selection = editor.getSelection(),
                        startElement = selection && selection.getStartElement(),
                        flash = checkFlash(startElement),
                        flashUI = editor._toolbars[TYPE_FLASH];
                    if (flash) {
                        flashUI.show(null, flash);
                    }
                }
            };

            Flash.CLS_FLASH = CLS_FLASH;
            Flash.TYPE_FLASH = TYPE_FLASH;
        })();
    }

    dataFilter && dataFilter.addRules({
        elements : {
            'object' : function(element) {
                var attributes = element.attributes,i,
                    classId = attributes['classid'] && String(attributes['classid']).toLowerCase();
                if (!classId) {
                    // Look for the inner <embed>
                    for (i = 0; i < element.children.length; i++) {
                        if (element.children[ i ].name == 'embed') {
                            if (!KE.Flash.isFlashEmbed(element.children[ i ]))
                                return null;
                            return dataProcessor.createFakeParserElement(element, CLS_FLASH, TYPE_FLASH, true);
                        }
                    }
                    return null;
                }
                return dataProcessor.createFakeParserElement(element, CLS_FLASH, TYPE_FLASH, true);
            },

            'embed' : function(element) {
                if (!KE.Flash.isFlashEmbed(element))
                    return null;
                return dataProcessor.createFakeParserElement(element, CLS_FLASH, TYPE_FLASH, true);
            }
        }}, 5);
});/**
 * simplified flash bridge for yui swf
 * @author:yiminghe@gmail.com
 */
KISSY.Editor.add("flashbridge", function() {
    var S = KISSY,KE = S.Editor;
    if (KE.FlashBridge) return;

    var instances = {};

    function FlashBridge(cfg) {
        this._init(cfg);
    }

    S.augment(FlashBridge, S.EventTarget, {
        _init:function(cfg) {
            var self = this,
                id = S.guid("flashbridge-"),
                callback = "KISSY.Editor.FlashBridge.EventHandler";
            cfg.flashVars = cfg.flashVars || {};
            cfg.attrs = cfg.attrs || {};
            cfg.params = cfg.params || {};
            var flashVars = cfg.flashVars,
                attrs = cfg.attrs,
                params = cfg.params;
            S.mix(attrs, {
                id:id,
                //http://yiminghe.javaeye.com/blog/764872
                //firefox 必须使创建的flash以及容器可见，才会触发contentReady
                //默认给flash自身很大的宽高，容器小点就可以了，
                width:'100%',
                height:'100%'
            }, false);
            //这几个要放在 param 里面，主要是允许 flash js沟通
            S.mix(params, {
                allowScriptAccess:'always',
                allowNetworking:'all',
                scale:'noScale'
            }, false);
            S.mix(flashVars, {
                shareData: false,
                useCompression:false
            }, false);
            var swfCore = {
                YUISwfId:id,
                YUIBridgeCallback:callback
            };
            if (cfg.ajbridge) {
                swfCore = {
                    swfID:id,
                    jsEntry:callback
                };
            }
            S.mix(flashVars, swfCore);
            instances[id] = self;
            self.id = id;
            self.swf = KE.Utils.flash.createSWFRuntime(cfg.movie, cfg);
            self._expose(cfg.methods);
        },
        _expose:function(methods) {
            var self = this;
            for (var i = 0; i < methods.length; i++) {
                var m = methods[i];
                (function(m) {
                    self[m] = function() {
                        return self._callSWF(m, S.makeArray(arguments));
                    };
                })(m);
            }
        },
        /**
         * Calls a specific function exposed by the SWF's ExternalInterface.
         * @param func {String} the name of the function to call
         * @param args {Array} the set of arguments to pass to the function.
         */
        _callSWF: function (func, args) {
            var self = this;
            args = args || [];
            try {
                if (self.swf[func]) {
                    return self.swf[func].apply(self.swf, args);
                }
            }
                // some version flash function is odd in ie: property or method not supported by object
            catch(e) {
                var params = "";
                if (args.length !== 0) {
                    params = "'" + args.join("', '") + "'";
                }
                //avoid eval for compressiong
                return (new Function('self', 'return self.swf.' + func + '(' + params + ');'))(self);
            }
        },
        _eventHandler:function(event) {
            var self = this,
                type = event.type;
            //console.log(self.id + " : " + type);
            if (type === 'log') {
                S.log(event.message);
            } else if (type) {
                self.fire(type, event);
            }
        },
        _destroy:function() {
            delete instances[this.id];
        }
    });

    FlashBridge.EventHandler = function(id, event) {
        //S.log(id);
        //S.log(event.type);
        var instance = instances[id];
        if (instance) {
            //防止ie同步触发事件，后面还没on呢，另外给 swf 喘息机会
            //否则同步后触发事件，立即调用swf方法会出错
            setTimeout(function() {
                instance._eventHandler.call(instance, event);
            }, 100);
        }
    };

    KE.FlashBridge = FlashBridge;


    /**
     * @module   Flash UA 探测
     * @author   kingfo<oicuicu@gmail.com>
     */

    var UA = S.UA, fpv, fpvF, firstRun = true;

    /**
     * 获取 Flash 版本号
     * 返回数据 [M, S, R] 若未安装，则返回 undefined
     */
    function getFlashVersion() {
        var ver, SF = 'ShockwaveFlash';

        // for NPAPI see: http://en.wikipedia.org/wiki/NPAPI
        if (navigator.plugins && navigator.mimeTypes.length) {
            ver = (navigator.plugins['Shockwave Flash'] || 0).description;
        }
        // for ActiveX see:	http://en.wikipedia.org/wiki/ActiveX
        else if (window.ActiveXObject) {
            try {
                ver = new ActiveXObject(SF + '.' + SF)['GetVariable']('$version');
            } catch(ex) {
                //S.log('getFlashVersion failed via ActiveXObject');
                // nothing to do, just return undefined
            }
        }

        // 插件没安装或有问题时，ver 为 undefined
        if (!ver) return;

        // 插件安装正常时，ver 为 "Shockwave Flash 10.1 r53" or "WIN 10,1,53,64"
        return arrify(ver);
    }

    /**
     * arrify("10.1.r53") => ["10", "1", "53"]
     */
    function arrify(ver) {
        return ver.match(/(\d)+/g);
    }

    /**
     * 格式：主版本号Major.次版本号Minor(小数点后3位，占3位)修正版本号Revision(小数点后第4至第8位，占5位)
     * ver 参数不符合预期时，返回 0
     * numerify("10.1 r53") => 10.00100053
     * numerify(["10", "1", "53"]) => 10.00100053
     * numerify(12.2) => 12.2
     */
    function numerify(ver) {
        var arr = S.isString(ver) ? arrify(ver) : ver, ret = ver;
        if (S.isArray(arr)) {
            ret = parseFloat(arr[0] + '.' + pad(arr[1], 3) + pad(arr[2], 5));
        }
        return ret || 0;
    }

    /**
     * pad(12, 5) => "00012"
     * ref: http://lifesinger.org/blog/2009/08/the-harm-of-tricky-code/
     */
    function pad(num, n) {
        var len = (num + '').length;
        while (len++ < n) {
            num = '0' + num;
        }
        return num;
    }

    /**
     * 返回数据 [M, S, R] 若未安装，则返回 undefined
     * fpv 全称是 flash player version
     */
    UA.fpv = function(force) {
        // 考虑 new ActiveX 和 try catch 的 性能损耗，延迟初始化到第一次调用时
        if (force || firstRun) {
            firstRun = false;
            fpv = getFlashVersion();
            fpvF = numerify(fpv);
        }
        return fpv;
    };

    /**
     * Checks fpv is greater than or equal the specific version.
     * 普通的 flash 版本检测推荐使用该方法
     * @param ver eg. "10.1.53"
     * <code>
     *    if(S.UA.fpvGEQ('9.9.2')) { ... }
     * </code>
     */
    UA.fpvGEQ = function(ver, force) {
        if (firstRun) UA.fpv(force);
        return !!fpvF && (fpvF >= numerify(ver));
    };

    /*
    if (!UA.fpvGEQ("11.0.0")) {

        var alertWin = new KE.SimpleOverlay({
            focusMgr:false,
            mask:true,
            title:"Flash 警告"
        });

        alertWin.body.html("您的Flash插件版本过低，" +
            "可能不能支持上传功能，" +
            "<a href='http://get.adobe.com/cn/flashplayer/' " +
            "target='_blank'>请点击此处更新</a>");

    }
    */

});KISSY.Editor.add("flashutils", function() {
    var S = KISSY,KE = S.Editor,flashUtils = KE.Utils.flash;
    if (flashUtils) return;
    var DOM = S.DOM,Node = S.Node,UA = S.UA;
    flashUtils = {
        getUrl: function (r) {
            var url = "",KEN = KE.NODE;
            if (r._4e_name() == "object") {
                var params = r[0].childNodes;
                for (var i = 0; i < params.length; i++) {
                    if (params[i].nodeType != KEN.NODE_ELEMENT)continue;
                    if ((DOM.attr(params[i], "name") || "").toLowerCase() == "movie") {
                        url = DOM.attr(params[i], "value");
                    } else if (DOM._4e_name(params[i]) == "embed") {
                        url = DOM.attr(params[i], "src");
                    } else if (DOM._4e_name(params[i]) == "object") {
                        url = DOM.attr(params[i], "data");
                    }
                }
            } else if (r._4e_name() == "embed") {
                url = r.attr("src");
            }
            return url;
        },
        createSWF:function(movie, cfg, doc) {
            var attrs = cfg.attrs || {},
                flashVars = cfg.flashVars,
                attrs_str = "",
                params_str = "",
                params = cfg.params || {},
                vars_str = "";
            doc = doc || document;
            S.mix(attrs, {
                wmode:"transparent"
            });
            for (var a in attrs) {
                if (attrs.hasOwnProperty(a))
                    attrs_str += a + "='" + attrs[a] + "' ";
            }

            S.mix(params, {
                quality:"high",
                movie:movie,
                wmode:"transparent"
            });
            for (var p in params) {
                if (params.hasOwnProperty(p))
                    params_str += "<param name='" + p + "' value='" + params[p] + "'/>";
            }


            if (flashVars) {
                for (var f in flashVars) {
                    if (flashVars.hasOwnProperty(f))
                        vars_str += "&" + f + "=" + encodeURIComponent(flashVars[f]);
                }
                vars_str = vars_str.substring(1);
            }

            var outerHTML = '<object ' +
                attrs_str +
                ' classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" >' +
                params_str +
                (vars_str ? '<param name="flashVars" value="' + vars_str + '"/>' : '') +
                /*
                 "<object type='application/x-shockwave-flash'" +
                 " data='" + movie + "'" +
                 " " + attrs_str +
                 ">"
                 +
                 (vars_str ? '<param name="flashVars" value="' + vars_str + '"/>' : '') +
                 */
                '<embed ' +
                attrs_str +
                " " +
                (vars_str ? ( 'FlashVars="' + vars_str + '"') : "") +
                ' pluginspage="http://www.macromedia.com/go/getflashplayer" ' +
                ' quality="high" ' +
                ' src="' + movie + '" ' +
                ' type="application/x-shockwave-flash"/>' +
                // + '</object>' +
                '</object>';
            return {
                el:new Node(outerHTML, null, doc),
                html:outerHTML
            };
        },
        createSWFRuntime2:function(movie, cfg, doc) {
            doc = doc || document;
            var holder = new Node(
                "<div " +
                    "style='" +
                    "width:0;" +
                    "height:0;" +
                    "overflow:hidden;" +
                    "'>", null, doc).appendTo(doc.body)
                , el = flashUtils.createSWF.apply(this, arguments).el.appendTo(holder);
            if (!UA.ie)
                el = el.one("object");
            return el[0];

        },
        createSWFRuntime:function(movie, cfg, doc) {
            var attrs = cfg.attrs || {},
                flashVars = cfg.flashVars || {},
                params = cfg.params || {},
                attrs_str = "",
                params_str = "",
                vars_str = "";
            doc = doc || document;
            attrs.id = attrs.id || S.guid("ke-runtimeflash-");
            for (var a in attrs) {
                if (attrs.hasOwnProperty(a))
                    attrs_str += a + "='" + attrs[a] + "' ";
            }
            for (var p in params) {
                if (params.hasOwnProperty(p))
                    params_str += "<param name='" + p + "' value='" + params[p] + "'/>";
            }
            for (var f in flashVars) {
                if (flashVars.hasOwnProperty(f))
                    vars_str += "&" + f + "=" + encodeURIComponent(flashVars[f]);
            }
            vars_str = vars_str.substring(1);

            if (UA.ie) {
                var outerHTML = '<object ' +
                    attrs_str +
                    ' classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" >' +
                    params_str +
                    '<param name="movie" value="' + movie + '" />' +
                    (vars_str ? '<param name="flashVars" value="' + vars_str + '" />' : '') +
                    '</object>';
            }
            else {
                /*!TODO 截止 firefix3.6 ，会发生 flash 请求两次问题，
                 想改成 embed， 再等等吧
                 */
                outerHTML = "<object " +
                    "type='application/x-shockwave-flash'" +
                    " data='" + movie + "'" +
                    " " + attrs_str +
                    ">" +
                    params_str +
                    (vars_str ? '<param name="flashVars" value="' + vars_str + '"/>' : '')
                    + '</object>';
            }


            var holder = cfg.holder;
            if (!holder) {
                holder = new Node(
                    "<div " +
                        "style='" + (
                        cfg.style ? cfg.style : (
                            //http://yiminghe.javaeye.com/blog/764872
                            //firefox 必须使创建的flash以及容器可见，才会触发contentReady
                            "width:1px;" +
                                "height:1px;" +
                                "position:absolute;" +
                                //"left:" + DOM.scrollLeft() + "px;" +
                                //"top:" + DOM.scrollTop() + "px;"
                                + "overflow:hidden;"
                            ))
                        +
                        "'>", null, doc
                    ).
                    appendTo(doc.body);
                //不能初始化时设置，防止刷新,scrollLeft 一开始为0，等会,wait is virtue
                setTimeout(function() {
                    holder.offset({left:DOM.scrollLeft(),top:DOM.scrollTop()})
                }, 100);
            }
            holder.html(outerHTML);
            return doc.getElementById(attrs.id);
        }

    };
    KE.Utils.flash = flashUtils;


});/**
 * font formatting for kissy editor
 * @author: yiminghe@gmail.com
 */
KISSY.Editor.add("font", function(editor) {

    function wrapFont(vs) {
        var v = [];
        for (var i = 0;
             i < vs.length;
             i++) {
            v.push({
                name:vs[i],
                value:vs[i]
            });
        }
        return v;
    }
    
    var S = KISSY,
        KE = S.Editor,
        KEStyle = KE.Style,
        TripleButton = KE.TripleButton,
        pluginConfig = editor.cfg.pluginConfig,
        Node = S.Node;

    var FONT_SIZES = pluginConfig["font-size"];

    if (FONT_SIZES !== false) {

        FONT_SIZES = FONT_SIZES || {};

        S.mix(FONT_SIZES, {
            items:wrapFont(["8px","10px","12px",
                "14px","18px","24px",
                "36px","48px","60px","72px","84px","96px"]),
            width:"55px"
        }, false);

        var FONT_SIZE_STYLES = {},
            FONT_SIZE_ITEMS = [],
            fontSize_style = {
                element        : 'span',
                styles        : { 'font-size' : '#(size)' },
                overrides    : [
                    { element : 'font', attributes : { 'size' : null } }
                ]
            };

        for (i = 0; i < FONT_SIZES.items.length; i++) {
            var item = FONT_SIZES.items[i],
                name = item.name,
                attrs = item.attrs,
                size = item.value;

            FONT_SIZE_STYLES[size] = new KEStyle(fontSize_style, {
                size:size
            });

            FONT_SIZE_ITEMS.push({
                name:name,
                value:size,
                attrs:attrs
            });
        }

        pluginConfig["font-size"] = FONT_SIZES;
    }

    var FONT_FAMILIES = pluginConfig["font-family"];

    if (FONT_FAMILIES !== false) {

        FONT_FAMILIES = FONT_FAMILIES || {};

        S.mix(FONT_FAMILIES, {
            items:[
                //ie 不认识中文？？？
                {name:"宋体",value:"SimSun"},
                {name:"黑体",value:"SimHei"},
                {name:"隶书",value:"LiSu"},
                {name:"楷体",value:"KaiTi_GB2312"},
                {name:"微软雅黑",value:"Microsoft YaHei"},
                {name:"Georgia",value:"Georgia"},
                {name:"Times New Roman",value:"Times New Roman"},
                {name:"Impact",value:"Impact"},
                {name:"Courier New",value:"Courier New"},
                {name:"Arial",value:"Arial"},
                {name:"Verdana",value:"Verdana"},
                {name:"Tahoma",value:"Tahoma"}
            ],
            width:"130px"
        }, false);


        var FONT_FAMILY_STYLES = {},
            FONT_FAMILY_ITEMS = [],
            fontFamily_style = {
                element        : 'span',
                styles        : { 'font-family' : '#(family)' },
                overrides    : [
                    { element : 'font', attributes : { 'face' : null } }
                ]
            },i;


        pluginConfig["font-family"] = FONT_FAMILIES;


        for (i = 0; i < FONT_FAMILIES.items.length; i++) {
            var item = FONT_FAMILIES.items[i],
                name = item.name,
                attrs = item.attrs || {},
                value = item.value;
            attrs.style = attrs.style || "";
            attrs.style += ";font-family:" + value;
            FONT_FAMILY_STYLES[value] = new KEStyle(fontFamily_style, {
                family:value
            });
            FONT_FAMILY_ITEMS.push({
                name:name,
                value:value,
                attrs:attrs
            });
        }
    }

    if (!KE.Font) {
        (function() {


            function Font(cfg) {
                var self = this;
                Font.superclass.constructor.call(self, cfg);
                self._init();
            }

            Font.ATTRS = {
                title:{},
                html:{},
                styles:{},
                editor:{}
            };
            var Select = KE.Select;
            S.extend(Font, S.Base, {

                _init:function() {
                    var self = this,
                        editor = self.get("editor"),
                        toolBarDiv = editor.toolBarDiv,
                        html = self.get("html");
                    self.el = new Select({
                        container: toolBarDiv,
                        doc:editor.document,
                        width:self.get("width"),
                        popUpWidth:self.get("popUpWidth"),
                        title:self.get("title"),
                        items:self.get("html"),
                        showValue:self.get("showValue"),
                        menuContainer:new Node(document.body)
                    });

                    self.el.on("click", self._vChange, self);
                    editor.on("selectionChange", self._selectionChange, self);
                    KE.Utils.sourceDisable(editor, self);
                },
                disable:function() {
                    this.el.set("state", Select.DISABLED);
                },
                enable:function() {
                    this.el.set("state", Select.ENABLED);
                },

                _vChange:function(ev) {
                    var self = this,
                        editor = self.get("editor"),
                        v = ev.newVal,
                        pre = ev.prevVal,
                        styles = self.get("styles");
                    editor.focus();
                    editor.fire("save");
                    if (v == pre) {
                        styles[v].remove(editor.document);
                        self.el.set("value", "");
                    } else {
                        styles[v].apply(editor.document);
                    }
                    editor.fire("save");
                },

                _selectionChange:function(ev) {
                    var self = this,
                        editor = self.get("editor"),
                        elementPath = ev.path,
                        elements = elementPath.elements,
                        styles = self.get("styles");
                   //S.log(ev);
                    // For each element into the elements path.
                    for (var i = 0, element; i < elements.length; i++) {
                        element = elements[i];
                        // Check if the element is removable by any of
                        // the styles.
                        for (var value in styles) {                            
                            if (styles[ value ].checkElementRemovable(element, true)) {
                                //S.log(value);
                                self.el.set("value", value);
                                return;
                            }
                        }
                    }
                    this.el.reset("value");
                }
            });

            function SingleFont(cfg) {
                var self = this;
                SingleFont.superclass.constructor.call(self, cfg);
                self._init();
            }

            SingleFont.ATTRS = {
                editor:{},
                text:{},
                contentCls:{},
                title:{},
                style:{}
            };

            S.extend(SingleFont, S.Base, {
                _init:function() {
                    var self = this,
                        editor = self.get("editor"),
                        text = self.get("text"),
                        style = self.get("style"),
                        title = self.get("title");
                    self.el = new TripleButton({
                        text:text,
                        title:title,
                        contentCls:self.get("contentCls"),
                        container:editor.toolBarDiv
                    });
                    self.el.on("offClick", self._on, self);
                    self.el.on("onClick", self._off, self);
                    editor.on("selectionChange", self._selectionChange, self);
                    KE.Utils.sourceDisable(editor, self);
                },
                disable:function() {
                    this.el.set("state", TripleButton.DISABLED);
                },
                enable:function() {
                    this.el.set("state", TripleButton.OFF);
                },
                _on:function() {
                    var self = this,
                        editor = self.get("editor"),
                        text = self.get("text"),
                        style = self.get("style"),
                        title = self.get("title");
                    editor.fire("save");
                    style.apply(editor.document);
                    editor.fire("save");
                    editor.notifySelectionChange();
                    editor.focus();
                },
                _off:function() {
                    var self = this,
                        editor = self.get("editor"),
                        text = self.get("text"),
                        style = self.get("style"),
                        title = self.get("title");
                    editor.fire("save");
                    style.remove(editor.document);
                    editor.fire("save");
                    editor.notifySelectionChange();
                    editor.focus();
                },
                _selectionChange:function(ev) {
                    var self = this,
                        editor = self.get("editor"),
                        text = self.get("text"),
                        style = self.get("style"),
                        title = self.get("title"),
                        el = self.el,
                        elementPath = ev.path;
                    if (el.get("state") == TripleButton.DISABLED)
                        return;
                    if (style.checkActive(elementPath)) {
                        el.set("state", TripleButton.ON);
                    } else {
                        el.set("state", TripleButton.OFF);
                    }
                }
            });
            Font.SingleFont = SingleFont;
            KE.Font = Font;
        })();
    }
    editor.addPlugin(function() {


        if (false !== pluginConfig["font-size"]) {
            new KE.Font({
                editor:editor,
                title:"大小",
                width:"30px",
                showValue:true,
                popUpWidth:FONT_SIZES.width,
                styles:FONT_SIZE_STYLES,
                html:FONT_SIZE_ITEMS
            });
        }

        if (false !== pluginConfig["font-family"]) {
            new KE.Font({
                editor:editor,
                title:"字体",
                width:"110px",
                popUpWidth:FONT_FAMILIES.width,
                styles:FONT_FAMILY_STYLES,
                html:FONT_FAMILY_ITEMS
            });
        }

        if (false !== pluginConfig["font-bold"]) {
            new KE.Font.SingleFont({
                contentCls:"ke-toolbar-bold",
                title:"粗体 ",
                editor:editor,
                style:new KEStyle({
                    element        : 'strong',
                    overrides    : [
                        { element : 'b' },
                        {element        : 'span',
                            attributes         : { style:'font-weight: bold;' }}
                    ]
                })
            });
        }

        if (false !== pluginConfig["font-italic"]) {
            new KE.Font.SingleFont({
                contentCls:"ke-toolbar-italic",
                title:"斜体 ",
                editor:editor,
                style:new KEStyle({
                    element        : 'em',
                    overrides    : [
                        { element : 'i' },
                        {element        : 'span',
                            attributes         : { style:'font-style: italic;' }}
                    ]
                })
            });
        }

        if (false !== pluginConfig["font-underline"]) {
            new KE.Font.SingleFont({
                contentCls:"ke-toolbar-underline",
                title:"下划线 ",
                editor:editor,
                style:new KEStyle({
                    element        : 'u',
                    overrides    : [
                        {element        : 'span',
                            attributes         : { style:'text-decoration: underline;' }}
                    ]
                })
            });
        }

        if (false !== pluginConfig["font-strikeThrough"]) {
            new KE.Font.SingleFont({
                contentCls:"ke-toolbar-strikeThrough",
                title:"删除线 ",
                editor:editor,
                style:new KEStyle({
                    element        : 'del',
                    overrides    : [
                        {element        : 'span',
                            attributes         : { style:'text-decoration: line-through;' }},
                        { element : 's' }
                    ]
                })
            });
        }

    });

});
/**
 * forecolor support for kissy editor
 * @author : yiminghe@gmail.com
 */
KISSY.Editor.add("forecolor", function(editor) {
    var S = KISSY,
        KE = S.Editor,
        ColorSupport = KE.ColorSupport;
    var COLOR_STYLES = {
        element        : 'span',
        styles        : { 'color' : '#(color)' },
        overrides    : [
            { element : 'font', attributes : { 'color' : null } }
        ]
    };
    editor.addPlugin(function() {
        new ColorSupport({
            editor:editor,
            styles:COLOR_STYLES,
            title:"文本颜色",
            contentCls:"ke-toolbar-color",
            text:"color"
        });
    });
});
/**
 * format formatting,modified from ckeditor
 * @modifier: yiminghe@gmail.com
 */
KISSY.Editor.add("format", function(editor) {
    var KE = KISSY.Editor,
        S = KISSY,
        Node = S.Node;
    var
        FORMAT_SELECTION_ITEMS = [],
        FORMATS = {
            "普通文本":"p",
            "标题1":"h1",
            "标题2":"h2",
            "标题3":"h3",
            "标题4":"h4",
            "标题5":"h5",
            "标题6":"h6"
        },
        FORMAT_SIZES = {
            p:"1em",
            h1:"2em",
            h2:"1.5em",
            h3:"1.17em",
            h4:"1em",
            h5:"0.83em",
            h6:"0.67em"
        },
        FORMAT_STYLES = {},
        KEStyle = KE.Style;

    for (var p in FORMATS) {
        if (FORMATS[p]) {
            FORMAT_STYLES[FORMATS[p]] = new KEStyle({
                element:FORMATS[p]
            });
            FORMAT_SELECTION_ITEMS.push({
                name:p,
                value:FORMATS[p],
                attrs:{
                    style:"font-size:" + FORMAT_SIZES[FORMATS[p]]
                }
            });

        }
    }

    if (!KE.Format) {
        (function() {

            function Format(cfg) {
                Format.superclass.constructor.call(this, cfg);
                var self = this;
                this._init();
            }

            Format.ATTRS = {
                editor:{}
            };
            var Select = KE.Select;
            S.extend(Format, S.Base, {
                _init:function() {
                    var self = this,
                        editor = this.get("editor"),
                        toolBarDiv = editor.toolBarDiv;
                    self.el = new Select({
                        container: toolBarDiv,
                        value:"",
                        doc:editor.document,
                        width:self.get("width"),
                        popUpWidth:self.get("popUpWidth"),
                        title:self.get("title"),
                        items:self.get("html"),
                        menuContainer:new Node(document.body)
                    });
                    self.el.on("click", self._vChange, self);
                    editor.on("selectionChange", self._selectionChange, self);
                    KE.Utils.sourceDisable(editor, self);
                },
                disable:function() {
                    this.el.set("state", Select.DISABLED);
                },
                enable:function() {
                    this.el.set("state", Select.ENABLED);
                },

                _vChange:function(ev) {
                    var self = this,
                        editor = self.get("editor"),
                        v = ev.newVal,
                        pre = ev.prevVal;
                    editor.fire("save");
                    if (v != pre) {
                        FORMAT_STYLES[v].apply(editor.document);
                    } else {
                        FORMAT_STYLES["p"].apply(editor.document);
                        self.el.set("value", "p");
                    }
                    editor.fire("save");
                },

                _selectionChange:function(ev) {
                    var self = this,
                        editor = self.get("editor"),
                        elementPath = ev.path;
                    // For each element into the elements path.
                    // Check if the element is removable by any of
                    // the styles.
                    for (var value in FORMAT_STYLES) {
                        if (FORMAT_STYLES[ value ].checkActive(elementPath)) {
                            self.el.set("value", value);
                            return;
                        }
                    }

                    self.el.reset("value");
                }
            });
            KE.Format = Format;
        })();
    }

    editor.addPlugin(function() {
        new KE.Format({
            editor:editor,
            html:FORMAT_SELECTION_ITEMS,
            title:"标题",
            width:"100px",
            popUpWidth:"120px"
        });
    });

});
/**
 * modified from ckeditor,process malform html and ms-word copy for kissyeditor
 * @modifier: yiminghe@gmail.com
 */
/*
 Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.html or http://ckeditor.com/license
 */
KISSY.Editor.add("htmldataprocessor", function(editor) {
    var undefined = undefined,
        S = KISSY,
        KE = S.Editor,
        Node = S.Node,
        UA = S.UA,
        KEN = KE.NODE,
        HtmlParser = KE.HtmlParser,
        htmlFilter = new HtmlParser.Filter(),
        dataFilter = new HtmlParser.Filter(),
        dtd = KE.XHTML_DTD;
    //每个编辑器的规则独立
    if (editor.htmlDataProcessor) return;


    /**
     * 给 fragment,Element,Dtd 加一些常用功能
     */
    (function() {

        var fragmentPrototype = KE.HtmlParser.Fragment.prototype,
            elementPrototype = KE.HtmlParser.Element.prototype;

        fragmentPrototype.onlyChild =
            elementPrototype.onlyChild = function() {
                var children = this.children,
                    count = children.length,
                    firstChild = ( count == 1 ) && children[ 0 ];
                return firstChild || null;
            };

        elementPrototype.removeAnyChildWithName = function(tagName) {
            var children = this.children,
                childs = [],
                child;

            for (var i = 0; i < children.length; i++) {
                child = children[ i ];
                if (!child.name)
                    continue;

                if (child.name == tagName) {
                    childs.push(child);
                    children.splice(i--, 1);
                }
                childs = childs.concat(child.removeAnyChildWithName(tagName));
            }
            return childs;
        };

        elementPrototype.getAncestor = function(tagNameRegex) {
            var parent = this.parent;
            while (parent && !( parent.name && parent.name.match(tagNameRegex) ))
                parent = parent.parent;
            return parent;
        };

        fragmentPrototype.firstChild = elementPrototype.firstChild = function(evaluator) {
            var child;

            for (var i = 0; i < this.children.length; i++) {
                child = this.children[ i ];
                if (evaluator(child))
                    return child;
                else if (child.name) {
                    child = child.firstChild(evaluator);
                    if (child)
                        return child;
                }
            }

            return null;
        };

        // Adding a (set) of styles to the element's 'style' attributes.
        elementPrototype.addStyle = function(name, value, isPrepend) {
            var styleText, addingStyleText = '';
            // name/value pair.
            if (typeof value == 'string')
                addingStyleText += name + ':' + value + ';';
            else {
                // style literal.
                if (typeof name == 'object') {
                    for (var style in name) {
                        if (name.hasOwnProperty(style))
                            addingStyleText += style + ':' + name[ style ] + ';';
                    }
                }
                // raw style text form.
                else
                    addingStyleText += name;

                isPrepend = value;
            }

            if (!this.attributes)
                this.attributes = {};

            styleText = this.attributes.style || '';

            styleText = ( isPrepend ?
                [ addingStyleText, styleText ]
                : [ styleText, addingStyleText ] ).join(';');

            this.attributes.style = styleText.replace(/^;|;(?=;)/, '');
        };

        /**
         * Return the DTD-valid parent tag names of the specified one.
         * @param tagName
         */
        dtd.parentOf = function(tagName) {
            var result = {};
            for (var tag in this) {
                if (tag.indexOf('$') == -1 && this[ tag ][ tagName ])
                    result[ tag ] = 1;
            }
            return result;
        };
    })();

    /**
     * 常用的规则：
     * 1。过滤一些常见东西
     * 2。处理 word 复制过来的列表
     */
    (function() {
        var equalsIgnoreCase = KE.Utils.equalsIgnoreCase,
            filterStyle = stylesFilter([
                //word 自有属性名去除
                [/mso/i],
                //ie 自有属性名
                [/^-ms/i],
                //firefox 自有属性名
                [/^-moz/i],
                //webkit 自有属性名
                [/^-webkit/i],
                //qc 3711，只能出现我们规定的字体
                /*
                 [ /font-size/i,'',function(v) {
                 var fontSizes = editor.cfg.pluginConfig["font-size"],
                 fonts = fontSizes.items;
                 for (var i = 0; i < fonts.length; i++) {
                 if (equalsIgnoreCase(v, fonts[i].value)) return v;
                 }
                 return false;
                 },'font-size'],
                 */

                //限制字体
                /*
                 [ /font-family/i,'',function(v) {
                 var fontFamilies = editor.cfg.pluginConfig["font-family"],
                 fams = fontFamilies.items;
                 for (var i = 0; i < fams.length; i++) {
                 var v2 = fams[i].value.toLowerCase();
                 if (equalsIgnoreCase(v, v2)
                 ||
                 equalsIgnoreCase(v, fams[i].name))
                 return v2;
                 }
                 return false;
                 } ,'font-family'],
                 */

                //qc 3701，去除行高，防止乱掉
                [/line-height/i],
                [/display/i,/none/i]
            ], undefined);

        function isListBulletIndicator(element) {
            var styleText = element.attributes && element.attributes.style;
            if (/mso-list\s*:\s*Ignore/i.test(styleText))
                return true;
            return undefined;
        }

        // Create a <ke:listbullet> which indicate an list item type.
        function createListBulletMarker(bulletStyle, bulletText) {
            var marker = new KE.HtmlParser.Element('ke:listbullet'),
                listType;

            // TODO: Support more list style type from MS-Word.
            if (!bulletStyle) {
                bulletStyle = 'decimal';
                listType = 'ol';
            }
            else if (bulletStyle[ 2 ]) {
                if (!isNaN(bulletStyle[ 1 ]))
                    bulletStyle = 'decimal';
                // No way to distinguish between Roman numerals and Alphas,
                // detect them as a whole.
                else if (/^[a-z]+$/.test(bulletStyle[ 1 ]))
                    bulletStyle = 'lower-alpha';
                else if (/^[A-Z]+$/.test(bulletStyle[ 1 ]))
                    bulletStyle = 'upper-alpha';
                // Simply use decimal for the rest forms of unrepresentable
                // numerals, e.g. Chinese...
                else
                    bulletStyle = 'decimal';

                listType = 'ol';
            }
            else {
                if (/[l\u00B7\u2002]/.test(bulletStyle[ 1 ]))
                    bulletStyle = 'disc';
                else if (/[\u006F\u00D8]/.test(bulletStyle[ 1 ]))
                    bulletStyle = 'circle';
                else if (/[\u006E\u25C6]/.test(bulletStyle[ 1 ]))
                    bulletStyle = 'square';
                else
                    bulletStyle = 'disc';

                listType = 'ul';
            }

            // Represent list type as CSS style.
            marker.attributes = {
                'ke:listtype' : listType,
                'style' : 'list-style-type:' + bulletStyle + ';'
            };
            marker.add(new KE.HtmlParser.Text(bulletText));
            return marker;
        }

        function resolveList(element) {
            // <ke:listbullet> indicate a list item.
            var attrs = element.attributes,
                listMarker;

            if (( listMarker = element.removeAnyChildWithName('ke:listbullet') )
                && listMarker.length
                && ( listMarker = listMarker[ 0 ] )) {
                element.name = 'ke:li';

                if (attrs.style) {
                    attrs.style = stylesFilter(
                        [
                            // Text-indent is not representing list item level any more.
                            [ 'text-indent' ],
                            [ 'line-height' ],
                            // Resolve indent level from 'margin-left' value.
                            [ ( /^margin(:?-left)?$/ ), null, function(margin) {
                                // Be able to deal with component/short-hand form style.
                                var values = margin.split(' ');
                                margin = values[ 3 ] || values[ 1 ] || values [ 0 ];
                                margin = parseInt(margin, 10);

                                // Figure out the indent unit by looking at the first increament.
                                if (!listBaseIndent && previousListItemMargin && margin > previousListItemMargin)
                                    listBaseIndent = margin - previousListItemMargin;

                                attrs[ 'ke:margin' ] = previousListItemMargin = margin;
                            } ]
                        ], undefined)(attrs.style, element) || '';
                }

                // Inherit list-type-style from bullet.
                var listBulletAttrs = listMarker.attributes,
                    listBulletStyle = listBulletAttrs.style;
                element.addStyle(listBulletStyle);
                S.mix(attrs, listBulletAttrs);
                return true;
            }

            return false;
        }

        function stylesFilter(styles, whitelist) {
            return function(styleText, element) {
                var rules = [];
                // html-encoded quote might be introduced by 'font-family'
                // from MS-Word which confused the following regexp. e.g.
                //'font-family: &quot;Lucida, Console&quot;'
                styleText
                    .replace(/&quot;/g, '"')
                    .replace(/\s*([^ :;]+)\s*:\s*([^;]+)\s*(?=;|$)/g,
                    function(match, name, value) {
                        name = name.toLowerCase();
                        name == 'font-family' && ( value = value.replace(/["']/g, '') );

                        var namePattern,
                            valuePattern,
                            newValue,
                            newName;
                        for (var i = 0; i < styles.length; i++) {
                            if (styles[ i ]) {
                                namePattern = styles[ i ][ 0 ];
                                valuePattern = styles[ i ][ 1 ];
                                newValue = styles[ i ][ 2 ];
                                newName = styles[ i ][ 3 ];

                                if (name.match(namePattern)
                                    && ( !valuePattern || value.match(valuePattern) )) {
                                    name = newName || name;
                                    whitelist && ( newValue = newValue || value );

                                    if (typeof newValue == 'function')
                                        newValue = newValue(value, element, name);

                                    // Return an couple indicate both name and value
                                    // changed.
                                    if (newValue && newValue.push)
                                        name = newValue[ 0 ],newValue = newValue[ 1 ];

                                    if (typeof newValue == 'string')
                                        rules.push([ name, newValue ]);
                                    return;
                                }
                            }
                        }

                        !whitelist && rules.push([ name, value ]);

                    });

                for (var i = 0; i < rules.length; i++)
                    rules[ i ] = rules[ i ].join(':');

                return rules.length ?
                    ( rules.join(';') + ';' ) : false;
            };
        }

        function assembleList(element) {
            var children = element.children, child,
                listItem,   // The current processing ke:li element.
                listItemAttrs,
                listType,   // Determine the root type of the list.
                listItemIndent, // Indent level of current list item.
                lastListItem, // The previous one just been added to the list.
                list,
                //parentList, // Current staging list and it's parent list if any.
                indent;

            for (var i = 0; i < children.length; i++) {
                child = children[ i ];

                if ('ke:li' == child.name) {
                    child.name = 'li';
                    listItem = child;
                    listItemAttrs = listItem.attributes;
                    listType = listItem.attributes[ 'ke:listtype' ];

                    // List item indent level might come from a real list indentation or
                    // been resolved from a pseudo list item's margin value, even get
                    // no indentation at all.
                    listItemIndent = parseInt(listItemAttrs[ 'ke:indent' ], 10)
                        || listBaseIndent && ( Math.ceil(listItemAttrs[ 'ke:margin' ] / listBaseIndent) )
                        || 1;

                    // Ignore the 'list-style-type' attribute if it's matched with
                    // the list root element's default style type.
                    listItemAttrs.style && ( listItemAttrs.style =
                        stylesFilter([
                            [ 'list-style-type', listType == 'ol' ? 'decimal' : 'disc' ]
                        ], undefined)(listItemAttrs.style)
                            || '' );

                    if (!list) {
                        list = new KE.HtmlParser.Element(listType);
                        list.add(listItem);
                        children[ i ] = list;
                    }
                    else {
                        if (listItemIndent > indent) {
                            list = new KE.HtmlParser.Element(listType);
                            list.add(listItem);
                            lastListItem.add(list);
                        }
                        else if (listItemIndent < indent) {
                            // There might be a negative gap between two list levels. (#4944)
                            var diff = indent - listItemIndent,
                                parent;
                            while (diff-- && ( parent = list.parent ))
                                list = parent.parent;

                            list.add(listItem);
                        }
                        else
                            list.add(listItem);

                        children.splice(i--, 1);
                    }

                    lastListItem = listItem;
                    indent = listItemIndent;
                }
                else
                    list = null;
            }

            listBaseIndent = 0;
        }

        var listBaseIndent,
            previousListItemMargin = 0,
            //protectElementNamesRegex = /(<\/?)((?:object|embed|param|html|body|head|title)[^>]*>)/gi,
            listDtdParents = dtd.parentOf('ol');

        //过滤外边来的 html
        var defaultDataFilterRules = {
            elementNames : [
                // Remove script,iframe style,link,meta
                [  /^script$/i , '' ],
                [  /^iframe$/i , '' ],
                [  /^style$/i , '' ],
                [  /^link$/i , '' ],
                [  /^meta$/i , '' ],
                [/^\?xml.*$/i,''],
                [/^.*namespace.*$/i,'']
            ],
            //根节点伪列表进行处理
            root : function(element) {
                element.filterChildren();
                assembleList(element);
            },
            elements : {
                font:function(el) {
                    delete el.name;
                },
                p:function(element) {
                    element.filterChildren();
                    // Is the paragraph actually a list item?
                    if (resolveList(element))
                        return undefined;
                },
                $:function(el) {
                    var tagName = el.name || "";
                    //ms world <o:p> 保留内容
                    if (tagName.indexOf(':') != -1 && tagName.indexOf("ke") == -1) {
                        //先处理子孙节点，防止delete el.name后，子孙得不到处理?
                        //el.filterChildren();
                        delete el.name;
                    }

                    /*
                     太激进，只做span*/
                    var style = el.attributes.style;
                    //没有属性的inline去掉了
                    if (//tagName in dtd.$inline
                        tagName == "span"
                            && (!style || !filterStyle(style))
                    //&&tagName!=="a"
                        ) {
                        //el.filterChildren();
                        delete el.name;
                    }

                    // Assembling list items into a whole list.
                    if (tagName in listDtdParents) {
                        el.filterChildren();
                        assembleList(el);
                    }
                },
                /*
                 td:function(
                 el
                 ) {
                 if (el.attributes.style) {
                 //去掉td的style，word copy非常讨厌
                 //现在要加padding了
                 delete el.attributes.style;
                 }
                 },*/
                /**
                 * ul,li 从 ms word 重建
                 * @param element
                 */
                span:function(element) {
                    // IE/Safari: remove the span if it comes from list bullet text.
                    if (!UA.gecko &&
                        isListBulletIndicator(element.parent)
                        )
                        return false;

                    // For IE/Safari: List item bullet type is supposed to be indicated by
                    // the text of a span with style 'mso-list : Ignore' or an image.
                    if (!UA.gecko &&
                        isListBulletIndicator(element)) {
                        var listSymbolNode = element.firstChild(function(node) {
                            return node.value || node.name == 'img';
                        });
                        var listSymbol = listSymbolNode && ( listSymbolNode.value || 'l.' ),
                            listType = listSymbol.match(/^([^\s]+?)([.)]?)$/);
                        return createListBulletMarker(listType, listSymbol);
                    }
                },
                a:function(element) {
                    var attribs = element.attributes;
                    if (attribs.href) {
                        attribs._ke_saved_href = attribs.href;
                    }
                }
            },
            comment : !UA.ie ?
                function(value, node) {
                    var imageInfo = value.match(/<img.*?>/),
                        listInfo = value.match(/^\[if !supportLists\]([\s\S]*?)\[endif\]$/);
                    // Seek for list bullet indicator.
                    if (listInfo) {
                        // Bullet symbol could be either text or an image.
                        var listSymbol = listInfo[ 1 ] || ( imageInfo && 'l.' ),
                            listType = listSymbol && listSymbol.match(/>([^\s]+?)([.)]?)</);
                        return createListBulletMarker(listType, listSymbol);
                    }

                    // Reveal the <img> element in conditional comments for Firefox.
                    if (UA.gecko && imageInfo) {
                        var img = KE.HtmlParser.Fragment.FromHtml(imageInfo[0]).children[ 0 ],
                            previousComment = node.previous,
                            // Try to dig the real image link from vml markup from previous comment text.
                            imgSrcInfo = previousComment && previousComment.value.match(/<v:imagedata[^>]*o:href=['"](.*?)['"]/),
                            imgSrc = imgSrcInfo && imgSrcInfo[ 1 ];
                        // Is there a real 'src' url to be used?
                        imgSrc && ( img.attributes.src = imgSrc );
                        return img;
                    }
                    return false;
                }
                :
                function() {
                    return false;
                },
            attributes :  {
                //防止word的垃圾class，全部杀掉算了，除了以ke_开头的编辑器内置class
                'class' : function(value
                    // , element
                    ) {
                    if (/(^|\s+)ke_/.test(value)) return value;
                    return false;
                },
                'style':function(value) {
                    //去除<i style="mso-bidi-font-style: normal">微软垃圾
                    var re = filterStyle(value);
                    if (!re) return false;
                    return re;
                }
            },
            attributeNames :  [
                // Event attributes (onXYZ) must not be directly set. They can become
                // active in the editing area (IE|WebKit).
                [ ( /^on/ ), 'ke_on' ],
                [/^lang$/,'']
            ]
        };
        //将编辑区生成html最终化
        var defaultHtmlFilterRules = {
            elementNames : [
                // Remove the "ke:" namespace prefix.
                [ ( /^ke:/ ), '' ],
                // Ignore <?xml:namespace> tags.
                [ ( /^\?xml:namespace$/ ), '' ]
            ],
            elements : {
                embed : function(element) {
                    var parent = element.parent;
                    // If the <embed> is child of a <object>, copy the width
                    // and height attributes from it.
                    if (parent && parent.name == 'object') {
                        var parentWidth = parent.attributes.width,
                            parentHeight = parent.attributes.height;
                        parentWidth && ( element.attributes.width = parentWidth );
                        parentHeight && ( element.attributes.height = parentHeight );
                    }
                },
                // Restore param elements into self-closing.
                param : function(param) {
                    param.children = [];
                    param.isEmpty = true;
                    return param;
                },
                // Remove empty link but not empty anchor.(#3829)
                a : function(element) {
                    if (!( element.children.length ||
                        element.attributes.name )) {
                        return false;
                    }
                    //防止ie<8 把 #a转换为 window.location#a
                    var attribs = element.attributes;
                    if (attribs._ke_saved_href) {
                        attribs.href = attribs._ke_saved_href;
                    }
                },
                //对应 table plugin , _genTable method
                td:function(element) {
                    var c = element.children;

                    //firefox 添加的 br 去掉
                    for (var i = 0; i < c.length; i++) {
                        if (c[i].name == "br") {
                            c.splice(i, 1);
                            --i;
                        }
                    }
                    //ie预览完美需要 &nbsp;
                    if (!element.children.length) {
                        var t = new KE.HtmlParser.Text("&nbsp;");
                        element.children.push(t);
                    }
                },
                span:function(element) {
                    if (! element.children.length)return false;
                }
            },
            attributes :  {
                //清除空style
                style:function(v) {
                    if (!S.trim(v))
                        return false;
                }
            },
            attributeNames :  [
                [ ( /^ke_on/ ), 'on' ],
                [ ( /^_ke.*/ ), '' ],
                [ ( /^ke:.*$/ ), '' ]
            ]
        };
        if (UA.ie) {
            // IE outputs style attribute in capital letters. We should convert
            // them back to lower case.
            defaultHtmlFilterRules.attributes.style = function(value
                // , element
                ) {
                return value.toLowerCase();
            };
        }

        htmlFilter.addRules(defaultHtmlFilterRules);
        dataFilter.addRules(defaultDataFilterRules);
    })();


    /**
     * 去除firefox代码末尾自动添加的 <br/>
     * 以及ie下自动添加的 &nbsp;
     * 以及其他浏览器段落末尾添加的占位符
     */
    (function() {
        // Regex to scan for &nbsp; at the end of blocks, which are actually placeholders.
        // Safari transforms the &nbsp; to \xa0. (#4172)
        var tailNbspRegex = /^[\t\r\n ]*(?:&nbsp;|\xa0)$/;

        // Return the last non-space child node of the block (#4344).
        function lastNoneSpaceChild(block) {
            var lastIndex = block.children.length,
                last = block.children[ lastIndex - 1 ];
            while (last && last.type == KEN.NODE_TEXT &&
                !S.trim(last.value))
                last = block.children[ --lastIndex ];
            return last;
        }

        function blockNeedsExtension(block) {
            var lastChild = lastNoneSpaceChild(block);

            return !lastChild
                || lastChild.type == KEN.NODE_ELEMENT &&
                lastChild.name == 'br'
                // Some of the controls in form needs extension too,
                // to move cursor at the end of the form. (#4791)
                || block.name == 'form' &&
                lastChild.name == 'input';
        }

        function trimFillers(block, fromSource) {
            // If the current node is a block, and if we're converting from source or
            // we're not in IE then search for and remove any tailing BR node.
            //
            // Also, any &nbsp; at the end of blocks are fillers, remove them as well.
            // (#2886)
            var children = block.children,
                lastChild = lastNoneSpaceChild(block);
            if (lastChild) {
                if (( fromSource || !UA.ie ) &&
                    lastChild.type == KEN.NODE_ELEMENT &&
                    lastChild.name == 'br')
                    children.pop();
                if (lastChild.type == KEN.NODE_TEXT &&
                    tailNbspRegex.test(lastChild.value))
                    children.pop();
            }
        }

        function extendBlockForDisplay(block) {
            trimFillers(block, true);

            if (blockNeedsExtension(block)) {
                if (UA.ie)
                    block.add(new KE.HtmlParser.Text('\xa0'));
                else
                    block.add(new KE.HtmlParser.Element('br', {}));
            }
        }

        function extendBlockForOutput(block) {
            trimFillers(block);
            if (blockNeedsExtension(block))
                block.add(new KE.HtmlParser.Text('\xa0'));
        }

        // Find out the list of block-like tags that can contain <br>.
        var dtd = KE.XHTML_DTD;
        var blockLikeTags = KE.Utils.mix({},
            dtd.$block,
            dtd.$listItem,
            dtd.$tableContent),i;
        for (i in blockLikeTags) {
            if (! ( 'br' in dtd[i] ))
                delete blockLikeTags[i];
        }
        // We just avoid filler in <pre> right now.
        // TODO: Support filler for <pre>, line break is also occupy line height.
        delete blockLikeTags.pre;
        var defaultDataBlockFilterRules = { elements : {} };
        var defaultHtmlBlockFilterRules = { elements : {} };
        for (i in blockLikeTags) {
            defaultDataBlockFilterRules.elements[ i ] = extendBlockForDisplay;
            defaultHtmlBlockFilterRules.elements[ i ] = extendBlockForOutput;
        }
        dataFilter.addRules(defaultDataBlockFilterRules);
        htmlFilter.addRules(defaultHtmlBlockFilterRules);
    })();


    //htmlparser fragment 中的 entities 处理
    //el.innerHTML="&nbsp;"
    //alert(el.innerHTML);
    //http://yiminghe.javaeye.com/blog/788929
    (function() {
        htmlFilter.addRules({
            text : function(text) {
                return text
                    //.replace(/&nbsp;/g, "\xa0")
                    .replace(/\xa0/g, "&nbsp;");
            }
        });
    })();


    editor.htmlDataProcessor = {
        htmlFilter:htmlFilter,
        dataFilter:dataFilter,
        //编辑器 html 到外部 html
        toHtml:function(html, fixForBody) {
            //fixForBody = fixForBody || "p";
            // Now use our parser to make further fixes to the structure, as
            // well as apply the filter.
            //使用htmlwriter界面美观，加入额外文字节点\n,\t空白等

            var writer = new HtmlParser.HtmlWriter(),
                fragment = HtmlParser.Fragment.FromHtml(html, fixForBody);

            fragment.writeHtml(writer, htmlFilter);
            return writer.getHtml(true);
        },
        //外部html进入编辑器
        toDataFormat : function(html, fixForBody) {

            // Firefox will be confused by those downlevel-revealed IE conditional
            // comments, fixing them first( convert it to upperlevel-revealed one ).
            // e.g. <![if !vml]>...<![endif]>
            //<!--[if !supportLists]-->
            // <span style=\"font-family: Wingdings;\" lang=\"EN-US\">
            // <span style=\"\">l<span style=\"font: 7pt &quot;Times New Roman&quot;;\">&nbsp;
            // </span></span></span>
            // <!--[endif]-->

            //变成：

            //<!--[if !supportLists]
            // <span style=\"font-family: Wingdings;\" lang=\"EN-US\">
            // <span style=\"\">l<span style=\"font: 7pt &quot;Times New Roman&quot;;\">&nbsp;
            // </span></span></span>
            // [endif]-->
            if (UA.gecko)
                html = html.replace(/(<!--\[if[^<]*?\])-->([\S\s]*?)<!--(\[endif\]-->)/gi,
                    '$1$2$3');

            //标签不合法可能parser出错，这里先用浏览器帮我们建立棵合法的dom树的html
            // Call the browser to help us fixing a possibly invalid HTML
            // structure.
            var div = new Node("<div>");
            // Add fake character to workaround IE comments bug. (#3801)
            div.html('a' + html);
            html = div.html().substr(1);

            // Certain elements has problem to go through DOM operation, protect
            // them by prefixing 'ke' namespace. (#3591)
            //html = html.replace(protectElementNamesRegex, '$1ke:$2');
            //fixForBody = fixForBody || "p";
            //bug:qc #3710:使用basicwriter，去除无用的文字节点，标签间连续\n空白等

            var writer = new HtmlParser.BasicWriter(),
                fragment = HtmlParser.Fragment.FromHtml(html, fixForBody);

            writer.reset();
            fragment.writeHtml(writer, dataFilter);

            return writer.getHtml(true);
        },
        /*
         最精简html传送到server
         */
        toServer:function(html, fixForBody) {
            var writer = new HtmlParser.BasicWriter(),
                fragment = HtmlParser.Fragment.FromHtml(html, fixForBody);
            fragment.writeHtml(writer, htmlFilter);
            return writer.getHtml(true);
        }
    };
});
/**
 * insert image for kissy editor
 * @author: yiminghe@gmail.com
 */
KISSY.Editor.add("image", function(editor) {
    var KE = KISSY.Editor,
        S = KISSY,
        //DOM = S.DOM,
        UA = S.UA,
        //JSON = S.JSON,
        Node = S.Node,
        Event = S.Event,
        TYPE_IMG = 'image',
        BubbleView = KE.BubbleView;


    //重新采用form提交，不采用flash，国产浏览器很多问题

    if (!KE.ImageInserter) {
        (function() {

            var checkImg = function (node) {
                return node._4e_name() === 'img' &&
                    (!/(^|\s+)ke_/.test(node[0].className)) &&
                    node;
            };

            function ImageInserter(cfg) {
                ImageInserter.superclass.constructor.call(this, cfg);
                this._init();
            }

            var TripleButton = KE.TripleButton;


            ImageInserter.ATTRS = {
                editor:{}
            };

            var contextMenu = {
                "图片属性":function(editor) {
                    var selection = editor.getSelection(),
                        startElement = selection && selection.getStartElement(),
                        flash = checkImg(startElement),
                        flashUI = editor._toolbars[TYPE_IMG];
                    if (flash) {
                        flashUI.show(null, flash);
                    }
                }
            };

            S.extend(ImageInserter, S.Base, {
                _init:function() {
                    var self = this,
                        editor = self.get("editor"),
                        toolBarDiv = editor.toolBarDiv,
                        myContexts = {};
                    self.editor = editor;
                    self.el = new TripleButton({
                        contentCls:"ke-toolbar-image",
                        title:"插入图片",
                        container:toolBarDiv
                    });
                    self.el.on("offClick", self.show, self);
                    Event.on(editor.document, "dblclick", self._dblclick, self);
                    KE.Utils.lazyRun(self, "_prepare", "_real");
                    editor._toolbars = editor._toolbars || {};
                    editor._toolbars[TYPE_IMG] = self;

                    if (contextMenu) {
                        for (var f in contextMenu) {
                            (function(f) {
                                myContexts[f] = function() {
                                    contextMenu[f](editor);
                                }
                            })(f);
                        }
                        KE.ContextMenu.register({
                            editor:editor,
                            rules:[checkImg],
                            width:"120px",
                            funcs:myContexts
                        });
                    }


                    BubbleView.attach({
                        pluginName:TYPE_IMG,
                        pluginInstance:self
                    });

                    KE.Utils.sourceDisable(editor, self);
                },
                disable:function() {
                    this.el.disable();
                },
                enable:function() {
                    this.el.boff();
                },
                _dblclick:function(ev) {
                    var self = this,
                        t = new Node(ev.target);
                    if (checkImg(t)) {
                        self.show(null, t);
                        ev.halt();
                    }
                },

                _updateTip:function(tipurl, img) {
                    var src = img.attr("src");
                    tipurl.html(src);
                    tipurl.attr("href", src);
                },



                show:function(ev, _selectedEl) {
                    var editor = this.get("editor");
                    editor.useDialog("image/dialog", function(dialog) {
                        dialog.show(_selectedEl);
                    });
                }
            });

            KE.ImageInserter = ImageInserter;

            var tipHtml = ' '
                + ' <a class="ke-bubbleview-url" target="_blank" href="#"></a> - '
                + '    <span class="ke-bubbleview-link ke-bubbleview-change">编辑</span> - '
                + '    <span class="ke-bubbleview-link ke-bubbleview-remove">删除</span>'
                + '';

            (function(pluginName, label, checkFlash) {

                BubbleView.register({
                    pluginName:pluginName,
                    func:checkFlash,
                    init:function() {
                        var bubble = this,
                            el = bubble.el;
                        el.html(label + tipHtml);
                        var tipurl = el.one(".ke-bubbleview-url"),
                            tipchange = el.one(".ke-bubbleview-change"),
                            tipremove = el.one(".ke-bubbleview-remove");
                        //ie focus not lose
                        tipchange._4e_unselectable();
                        tipurl._4e_unselectable();
                        tipremove._4e_unselectable();
                        tipchange.on("click", function(ev) {
                            bubble._plugin.show(null, bubble._selectedEl);
                            ev.halt();
                        });
                        tipremove.on("click", function(ev) {
                            var flash = bubble._plugin;
                            if (UA.webkit) {
                                var r = flash.editor.getSelection().getRanges();
                                r && r[0] && (r[0].collapse(true) || true) && r[0].select();
                            }
                            bubble._selectedEl._4e_remove();
                            bubble.hide();
                            flash.editor.notifySelectionChange();
                            ev.halt();
                        });
                        /*
                         位置变化
                         */
                        bubble.on("afterVisibleChange", function(ev) {
                            var v = ev.newVal,
                                a = bubble._selectedEl,
                                flash = bubble._plugin;
                            if (!v || !a)return;
                            flash._updateTip(tipurl, a);
                        });
                    }
                });
            })(TYPE_IMG, "图片网址： ", checkImg);
        })();
    }

    editor.addPlugin(function() {
        new KE.ImageInserter({
            editor:editor
        });
    });
});/**
 * indent formatting,modified from ckeditor
 * @modifier: yiminghe@gmail.com
 */
/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
KISSY.Editor.add("indent", function(editor) {
    var KE = KISSY.Editor,
        listNodeNames = {ol:1,ul:1},
        S = KISSY,
        Walker = KE.Walker,
        DOM = S.DOM,
        Node = S.Node,
        UA = S.UA,
        KEN = KE.NODE;
    if (!KE.Indent) {
        (function() {
            var isNotWhitespaces = Walker.whitespaces(true),
                isNotBookmark = Walker.bookmark(false, true);

            function IndentCommand(type) {
                this.type = type;
                this.indentCssProperty = "margin-left";
                this.indentOffset = 40;
                this.indentUnit = "px";
            }

            function isListItem(node) {
                return node.type = CKEDITOR.NODE_ELEMENT && node.is('li');
            }

            function indentList(editor, range, listNode) {
                // Our starting and ending points of the range might be inside some blocks under a list item...
                // So before playing with the iterator, we need to expand the block to include the list items.
                var startContainer = range.startContainer,
                    endContainer = range.endContainer;
                while (startContainer &&
                    !startContainer.parent()._4e_equals(listNode))
                    startContainer = startContainer.parent();
                while (endContainer &&
                    !endContainer.parent()._4e_equals(listNode))
                    endContainer = endContainer.parent();

                if (!startContainer || !endContainer)
                    return;

                // Now we can iterate over the individual items on the same tree depth.
                var block = startContainer,
                    itemsToMove = [],
                    stopFlag = false;
                while (!stopFlag) {
                    if (block._4e_equals(endContainer))
                        stopFlag = true;
                    itemsToMove.push(block);
                    block = block.next();
                }
                if (itemsToMove.length < 1)
                    return;

                // Do indent or outdent operations on the array model of the list, not the
                // list's DOM tree itself. The array model demands that it knows as much as
                // possible about the surrounding lists, we need to feed it the further
                // ancestor node that is still a list.
                var listParents = listNode._4e_parents(true);
                for (var i = 0; i < listParents.length; i++) {
                    if (listNodeNames[ listParents[i]._4e_name() ]) {
                        listNode = listParents[i];
                        break;
                    }
                }
                var indentOffset = this.type == 'indent' ? 1 : -1,
                    startItem = itemsToMove[0],
                    lastItem = itemsToMove[ itemsToMove.length - 1 ],
                    database = {};

                // Convert the list DOM tree into a one dimensional array.
                var listArray = KE.ListUtils.listToArray(listNode, database);

                // Apply indenting or outdenting on the array.
                var baseIndent = listArray[ lastItem._4e_getData('listarray_index') ].indent;
                for (i = startItem._4e_getData('listarray_index');
                     i <= lastItem._4e_getData('listarray_index'); i++) {
                    listArray[ i ].indent += indentOffset;
                    // Make sure the newly created sublist get a brand-new element of the same type. (#5372)
                    var listRoot = listArray[ i ].parent;
                    listArray[ i ].parent =
                        new Node(listRoot[0].ownerDocument.createElement(listRoot._4e_name()));
                }

                for (i = lastItem._4e_getData('listarray_index') + 1;
                     i < listArray.length && listArray[i].indent > baseIndent; i++)
                    listArray[i].indent += indentOffset;

                // Convert the array back to a DOM forest (yes we might have a few subtrees now).
                // And replace the old list with the new forest.
                var newList = KE.ListUtils.arrayToList(listArray, 
                    database, null,
                    "p",
                    0);

                // Avoid nested <li> after outdent even they're visually same,
                // recording them for later refactoring.(#3982)
                var pendingList = [];
                if (this.type == 'outdent') {
                    var parentLiElement;
                    if (( parentLiElement = listNode.parent() ) &&
                        parentLiElement._4e_name() == ('li')) {
                        var children = newList.listNode.childNodes
                            ,count = children.length,
                            child;

                        for (i = count - 1; i >= 0; i--) {
                            if (( child = new Node(children[i]) ) &&
                                child._4e_name() == 'li')
                                pendingList.push(child);
                        }
                    }
                }

                if (newList) {
                    DOM.insertBefore(newList.listNode, listNode[0]);
                    listNode._4e_remove();
                }
                // Move the nested <li> to be appeared after the parent.
                if (pendingList && pendingList.length) {
                    for (i = 0; i < pendingList.length; i++) {
                        var li = pendingList[ i ],
                            followingList = li;

                        // Nest preceding <ul>/<ol> inside current <li> if any.
                        while (( followingList = followingList.next() ) &&

                            followingList._4e_name() in listNodeNames) {
                            // IE requires a filler NBSP for nested list inside empty list item,
                            // otherwise the list item will be inaccessiable. (#4476)
                            if (UA.ie && !li._4e_first(function(node) {
                                return isNotWhitespaces(node) && isNotBookmark(node);
                            }))
                                li[0].appendChild(range.document.createTextNode('\u00a0'));

                            li[0].appendChild(followingList[0]);
                        }
                        DOM.insertAfter(li[0], parentLiElement[0]);
                    }
                }

                // Clean up the markers.
                for (i in database)
                    database[i]._4e_clearMarkers(database, true);
            }

            function indentBlock(editor, range) {
                var iterator = range.createIterator();
                //  enterMode = "p";
                iterator.enforceRealBlocks = true;
                iterator.enlargeBr = true;
                var block;
                while (( block = iterator.getNextParagraph() ))
                    indentElement.call(this, editor, block);
            }

            function indentElement(editor, element) {

                var currentOffset = parseInt(element._4e_style(this.indentCssProperty), 10);
                if (isNaN(currentOffset))
                    currentOffset = 0;
                currentOffset += ( this.type == 'indent' ? 1 : -1 ) * this.indentOffset;

                if (currentOffset < 0)
                    return false;

                currentOffset = Math.max(currentOffset, 0);
                currentOffset = Math.ceil(currentOffset / this.indentOffset) * this.indentOffset;
                element.css(this.indentCssProperty, currentOffset ? currentOffset + this.indentUnit : '');
                if (element[0].style.cssText === '')
                    element[0].removeAttribute('style');

                return true;
            }

            S.augment(IndentCommand, {
                exec:function(editor) {
                    var selection = editor.getSelection(),
                        range = selection && selection.getRanges()[0];
                    var startContainer = range.startContainer,
                        endContainer = range.endContainer,
                        rangeRoot = range.getCommonAncestor(),
                        nearestListBlock = rangeRoot;

                    while (nearestListBlock && !( nearestListBlock[0].nodeType == KEN.NODE_ELEMENT &&
                        listNodeNames[ nearestListBlock._4e_name() ] ))
                        nearestListBlock = nearestListBlock.parent();

                    // Avoid selection anchors under list root.
                    // <ul>[<li>...</li>]</ul> =>	<ul><li>[...]</li></ul>
                    if (nearestListBlock && startContainer[0].nodeType == KEN.NODE_ELEMENT
                        && startContainer._4e_name() in listNodeNames) {
                        var walker = new Walker(range);
                        walker.evaluator = isListItem;
                        range.startContainer = walker.next();
                    }

                    if (nearestListBlock && endContainer[0].nodeType == KEN.NODE_ELEMENT
                        && endContainer._4e_name() in listNodeNames) {
                        walker = new Walker(range);
                        walker.evaluator = isListItem;
                        range.endContainer = walker.previous();
                    }

                    var bookmarks = selection.createBookmarks(true);

                    if (nearestListBlock) {
                        var firstListItem = nearestListBlock._4e_first();
                        while (firstListItem && firstListItem[0] && firstListItem._4e_name() != "li") {
                            firstListItem = firstListItem.next();
                        }
                        var rangeStart = range.startContainer,
                            indentWholeList = firstListItem[0] == rangeStart[0] || firstListItem._4e_contains(rangeStart);

                        // Indent the entire list if  cursor is inside the first list item. (#3893)
                        if (!( indentWholeList && indentElement.call(this, editor, nearestListBlock) ))
                            indentList.call(this, editor, range, nearestListBlock);
                    }
                    else
                        indentBlock.call(this, editor, range);
                    selection.selectBookmarks(bookmarks);
                }
            });


            var TripleButton = KE.TripleButton;

            /**
             * 用到了按钮三状态的两个状态：off可点击，disabled:不可点击
             * @param cfg
             */
            function Indent(cfg) {
                Indent.superclass.constructor.call(this, cfg);

                var editor = this.get("editor"),
                    toolBarDiv = editor.toolBarDiv;
                // el = this.el;

                var self = this;
                self.el = new TripleButton({
                    container:toolBarDiv,
                    contentCls:this.get("contentCls"),
                    //text:this.get("type"),
                    title:this.get("title")
                });
                this.indentCommand = new IndentCommand(this.get("type"));
                this._init();
            }

            Indent.ATTRS = {
                type:{},
                contentCls:{},
                editor:{}
            };

            S.extend(Indent, S.Base, {

                _init:function() {
                    var editor = this.get("editor"),toolBarDiv = editor.toolBarDiv,
                        el = this.el;
                    var self = this;
                    //off状态下触发捕获，注意没有on状态
                    el.on("offClick", this.exec, this);
                    if (this.get("type") == "outdent")
                        editor.on("selectionChange", this._selectionChange, this);
                    else
                        el.set("state", TripleButton.OFF);
                    KE.Utils.sourceDisable(editor, self);
                },
                disable:function() {
                    this.el.set("state", TripleButton.DISABLED);
                },
                enable:function() {
                    this.el.set("state", TripleButton.OFF);
                },


                exec:function() {
                    var editor = this.get("editor"),
                        el = this.el,
                        self = this;
                    //ie要等会才能获得焦点窗口的选择区域
                    editor.fire("save");
                    setTimeout(function() {
                        self.indentCommand.exec(editor);
                        editor.fire("save");
                        editor.notifySelectionChange();
                    }, 10);
                },

                _selectionChange:function(ev) {
                    var editor = this.get("editor"),type = this.get("type")
                        , elementPath = ev.path,
                        blockLimit = elementPath.blockLimit,
                        el = this.el;

                    if (elementPath.contains(listNodeNames)) {
                        el.set("state", TripleButton.OFF);
                    } else {
                        var block = elementPath.block || blockLimit;
                        if (block && block._4e_style(this.indentCommand.indentCssProperty)) {
                            el.set("state", TripleButton.OFF);
                        } else {
                            el.set("state", TripleButton.DISABLED);
                        }
                    }
                }
            });
            KE.Indent = Indent;
        })();
    }
    editor.addPlugin(function() {
        editor.addCommand("outdent", new KE.Indent({
            editor:editor,
            title:"减少缩进量 ",
            contentCls:"ke-toolbar-outdent",
            type:"outdent"
        }));
        editor.addCommand("indent", new KE.Indent({
            editor:editor,
            title:"增加缩进量 ",
            contentCls:"ke-toolbar-indent",
            type:"indent"
        }));
    });
});
/**
 * align support for kissy editor
 * @author: yiminghe@gmail.com
 */
KISSY.Editor.add("justify", function(editor) {
    var KE = KISSY.Editor,
        S = KISSY,TripleButton = KE.TripleButton;

    if (!KE.Justify) {
        (function() {
            function Justify(editor, v, title, contentCls) {
                var self = this;
                self.editor = editor;
                self.v = v;
                self.contentCls = contentCls;
                self.title = title;
                self._init();
            }

            var alignRemoveRegex = /(-moz-|-webkit-|start|auto)/i,
                default_align = "left";
            S.augment(Justify, {
                _init:function() {
                    var self = this,editor = self.editor,toolBarDiv = editor.toolBarDiv;
                    self.el = new TripleButton({
                        contentCls:self.contentCls,
                        //text:self.v,
                        title:self.title,
                        container:toolBarDiv
                    });
                    editor.on("selectionChange", self._selectionChange, self);
                    self.el.on("offClick", self._effect, self);
                    KE.Utils.sourceDisable(editor, self);
                },
                disable:function() {
                    this.el.set("state", TripleButton.DISABLED);
                },
                enable:function() {
                    this.el.set("state", TripleButton.OFF);
                },
                _effect:function() {
                    var self = this,editor = self.editor,
                        selection = editor.getSelection(),
                        enterMode = "p",state = self.el.get("state");

                    if (!selection)
                        return;

                    var bookmarks = selection.createBookmarks(),
                        ranges = selection.getRanges(),
                        iterator,
                        block;
                    editor.fire("save");
                    for (var i = ranges.length - 1; i >= 0; i--) {
                        iterator = ranges[ i ].createIterator();
                        iterator.enlargeBr = true;
                        while (( block = iterator.getNextParagraph() )) {
                            block.removeAttr('align');
                            if (state == TripleButton.OFF)
                                block.css('text-align', self.v);
                            else
                                block.css('text-align', '');
                        }
                    }
                    editor.notifySelectionChange();
                    selection.selectBookmarks(bookmarks);
                    editor.fire("save");
                },
                _selectionChange:function(ev) {
                    var self = this,
                        el = self.el,
                        path = ev.path,
                        //elements = path.elements,
                        block = path.block || path.blockLimit;
                    //如果block是body，就不要设置，
                    // <body>
                    // <ul>
                    // <li style='text-align:center'>
                    // </li>
                    // </ul>
                    // </body>
                    if (!block || block._4e_name() === "body") {
                        el.set("state", TripleButton.OFF);
                        return;
                    }

                    var align = block.css("text-align").replace(alignRemoveRegex, "");
                    if (align == self.v || (!align && self.v == default_align)) {
                        el.set("state", TripleButton.ON);
                    } else {
                        el.set("state", TripleButton.OFF);
                    }
                }
            });
            KE.Justify = Justify;
        })();
    }
    editor.addPlugin(function() {
        new KE.Justify(editor, "left", "左对齐 ", "ke-toolbar-alignleft");
        new KE.Justify(editor, "center", "居中对齐 ", "ke-toolbar-aligncenter");
        new KE.Justify(editor, "right", "右对齐 ", "ke-toolbar-alignright");
        //new Justify(editor, "justify", "两端对齐 ");
    });
});
/**
 * link editor support for kissy editor ,innovation from google doc and ckeditor
 * @author: yiminghe@gmail.com
 */
KISSY.Editor.add("link", function(editor) {
    var S = KISSY,KE = S.Editor;

    if (!KE.Link) {
        (function() {
            var TripleButton = KE.TripleButton,
                KEStyle = KE.Style,
                Node = S.Node,
                KERange = KE.Range,
                _ke_saved_href = "_ke_saved_href",
                BubbleView = KE.BubbleView,
                link_Style = {
                    element : 'a',
                    attributes:{
                        "href":"#(href)",
                        //ie < 8 会把锚点地址修改
                        "_ke_saved_href":"#(_ke_saved_href)",
                        target:"#(target)"
                    }
                },
                /**
                 * bubbleview/tip 初始化，所有共享一个 tip
                 */
                tipHtml = '前往链接： '
                    + ' <a ' +
                    'href="" '
                    + ' target="_blank" ' +
                    'class="ke-bubbleview-url">' +
                    '</a> - '
                    + ' <span ' +
                    'class="ke-bubbleview-link ke-bubbleview-change">' +
                    '编辑' +
                    '</span> - '
                    + ' <span ' +
                    'class="ke-bubbleview-link ke-bubbleview-remove">' +
                    '去除' +
                    '</span>';


            function Link(editor) {
                var self = this;
                self.editor = editor;
                self._init();
            }

            Link.link_Style = link_Style;
            Link._ke_saved_href = _ke_saved_href;

                function checkLink(lastElement) {
                    return lastElement._4e_ascendant(function(node) {
                        return node._4e_name() === 'a' && (!!node.attr("href"));
                    }, true);
                }

            Link.checkLink = checkLink;

            BubbleView.register({
                pluginName:"link",
                func:checkLink,
                init:function() {
                    var bubble = this,el = bubble.el;
                    el.html(tipHtml);
                    var tipurl = el.one(".ke-bubbleview-url"),
                        tipchange = el.one(".ke-bubbleview-change"),
                        tipremove = el.one(".ke-bubbleview-remove");
                    //ie focus not lose
                    tipchange._4e_unselectable();
                    tipurl._4e_unselectable();
                    tipremove._4e_unselectable();
                    tipchange.on("click", function(ev) {
                        bubble._plugin.show();
                        ev.halt();
                    });
                    tipremove.on("click", function(ev) {
                        var link = bubble._plugin,editor = link.editor;
                        _removeLink(bubble._selectedEl, editor);
                        editor.notifySelectionChange();
                        ev.halt();
                    });

                    bubble.on("afterVisibleChange", function() {
                        var a = bubble._selectedEl;
                        if (!a)return;
                        var href = a.attr(_ke_saved_href) ||
                            a.attr("href");
                        tipurl.html(href);
                        tipurl.attr("href", href);
                    });
                }
            });

            function _removeLink(a, editor) {
                var attr = {
                    href:a.attr("href"),
                    _ke_saved_href:a.attr(_ke_saved_href)
                };
                if (a._4e_hasAttribute("target")) {
                    attr.target = a.attr("target");
                }
                var linkStyle = new KEStyle(link_Style, attr);
                editor.fire("save");
                linkStyle.remove(editor.document);
                editor.fire("save");
            }

            Link._removeLink = _removeLink;

            S.augment(Link, {
                _init:function() {
                    var self = this,editor = self.editor;
                    self.el = new TripleButton({
                        container:editor.toolBarDiv,
                        contentCls:"ke-toolbar-link",
                        title:"插入链接 "
                    });
                    self.el.on("offClick", self.show, self);
                    BubbleView.attach({
                        pluginName:"link",
                        pluginInstance:self
                    });
                    KE.Utils.sourceDisable(editor, self);
                },

                disable:function() {
                    this.el.disable();
                },

                enable:function() {
                    this.el.enable();
                },

                show:function() {
                    this.editor.useDialog("link/dialog", function(dialog) {
                        dialog.show();
                    });
                }
            });

            KE.Link = Link;
        })();
    }
    editor.addPlugin(function() {
        new KE.Link(editor);
    });
});/**
 * list formatting,modified from ckeditor
 * @modifier: yiminghe@gmail.com
 */
/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
KISSY.Editor.add("list", function(editor) {
    var KE = KISSY.Editor,
        listNodeNames = {"ol":1,"ul":1},
        listNodeNames_arr = ["ol","ul"],
        S = KISSY,
        KER = KE.RANGE,
        //KEP = KE.POSITION,
        ElementPath = KE.ElementPath,
        Walker = KE.Walker,
        KEN = KE.NODE,
        UA = S.UA,
        Node = S.Node,
        DOM = S.DOM;
    if (!KE.List) {
        (function() {


            var list = {
                /*
                 * Convert a DOM list tree into a data structure that is easier to
                 * manipulate. This operation should be non-intrusive in the sense that it
                 * does not change the DOM tree, with the exception that it may add some
                 * markers to the list item nodes when database is specified.
                 * 扁平化处理，深度遍历，利用 indent 和顺序来表示一棵树
                 */
                listToArray : function(listNode,
                                       database,
                                       baseArray,
                                       baseIndentLevel,
                                       grandparentNode) {
                    if (!listNodeNames[ listNode._4e_name() ])
                        return [];

                    if (!baseIndentLevel)
                        baseIndentLevel = 0;
                    if (!baseArray)
                        baseArray = [];

                    // Iterate over all list items to and look for inner lists.
                    for (var i = 0, count = listNode[0].childNodes.length;
                         i < count; i++) {
                        var listItem = new Node(listNode[0].childNodes[i]);

                        // It may be a text node or some funny stuff.
                        if (listItem._4e_name() != 'li')
                            continue;

                        var itemObj = { 'parent' : listNode,
                            indent : baseIndentLevel,
                            element : listItem, contents : [] };
                        if (!grandparentNode) {
                            itemObj.grandparent = listNode.parent();
                            if (itemObj.grandparent && itemObj.grandparent._4e_name() == 'li')
                                itemObj.grandparent = itemObj.grandparent.parent();
                        }
                        else
                            itemObj.grandparent = grandparentNode;

                        if (database)
                            listItem._4e_setMarker(database,
                                'listarray_index',
                                baseArray.length);
                        baseArray.push(itemObj);

                        for (var j = 0, itemChildCount = listItem[0].childNodes.length, child;
                             j < itemChildCount; j++) {
                            child = new Node(listItem[0].childNodes[j]);
                            if (child[0].nodeType == KEN.NODE_ELEMENT &&
                                listNodeNames[ child._4e_name() ])
                            // Note the recursion here, it pushes inner list items with
                            // +1 indentation in the correct order.
                                list.listToArray(child, database, baseArray,
                                    baseIndentLevel + 1, itemObj.grandparent);
                            else
                                itemObj.contents.push(child);
                        }
                    }
                    return baseArray;
                },

                // Convert our internal representation of a list back to a DOM forest.
                //根据包含indent属性的元素数组来生成树
                arrayToList : function(listArray, database,
                                       baseIndex, paragraphMode) {
                    if (!baseIndex)
                        baseIndex = 0;
                    if (!listArray || listArray.length < baseIndex + 1)
                        return null;
                    var doc = listArray[ baseIndex ].parent[0].ownerDocument,
                        retval = doc.createDocumentFragment(),
                        rootNode = null,
                        currentIndex = baseIndex,
                        indentLevel = Math.max(listArray[ baseIndex ].indent, 0),
                        currentListItem = null;
                    //,paragraphName = paragraphMode;

                    while (true) {
                        var item = listArray[ currentIndex ];
                        if (item.indent == indentLevel) {
                            if (!rootNode
                                ||
                                //用于替换标签,ul->ol ,ol->ul
                                listArray[ currentIndex ].parent._4e_name() != rootNode._4e_name()) {

                                rootNode = listArray[ currentIndex ].parent._4e_clone(false, true);
                                retval.appendChild(rootNode[0]);
                            }
                            currentListItem = rootNode[0].appendChild(item.element._4e_clone(false, true)[0]);
                            for (var i = 0; i < item.contents.length; i++)
                                currentListItem.appendChild(item.contents[i]._4e_clone(true, true)[0]);
                            currentIndex++;
                        } else if (item.indent == Math.max(indentLevel, 0) + 1) {
                            //进入一个li里面，里面的嵌套li递归构造父亲ul/ol
                            var listData = list.arrayToList(listArray, null,
                                currentIndex, paragraphMode);
                            currentListItem.appendChild(listData.listNode);
                            currentIndex = listData.nextIndex;
                        } else if (item.indent == -1 && !baseIndex &&
                            item.grandparent) {

                            if (listNodeNames[ item.grandparent._4e_name() ])
                                currentListItem = item.element._4e_clone(false, true)[0];
                            else {
                                // Create completely new blocks here, attributes are dropped.
                                //为什么要把属性去掉？？？#3857
                                if (item.grandparent._4e_name() != 'td') {
                                    currentListItem = doc.createElement(paragraphMode);
                                    item.element._4e_copyAttributes(new Node(currentListItem));
                                }
                                else
                                    currentListItem = doc.createDocumentFragment();
                            }

                            for (i = 0; i < item.contents.length; i++) {
                                var ic = item.contents[i]._4e_clone(true, true);
                                //如果是list中，应该只退出ul，保留margin-left
                                if (currentListItem.nodeType == KEN.NODE_DOCUMENT_FRAGMENT) {
                                    item.element._4e_copyAttributes(new Node(ic));
                                }
                                currentListItem.appendChild(ic[0]);
                            }

                            if (currentListItem.nodeType == KEN.NODE_DOCUMENT_FRAGMENT
                                && currentIndex != listArray.length - 1) {
                                if (currentListItem.lastChild
                                    && currentListItem.lastChild.nodeType == KEN.NODE_ELEMENT
                                    && currentListItem.lastChild.getAttribute('type') == '_moz')
                                    DOM._4e_remove(currentListItem.lastChild);
                                DOM._4e_appendBogus(currentListItem);
                            }

                            if (currentListItem.nodeType == KEN.NODE_ELEMENT &&
                                DOM._4e_name(currentListItem) == paragraphMode &&
                                currentListItem.firstChild) {
                                DOM._4e_trim(currentListItem);
                                var firstChild = currentListItem.firstChild;
                                if (firstChild.nodeType == KEN.NODE_ELEMENT &&
                                    DOM._4e_isBlockBoundary(firstChild)
                                    ) {
                                    var tmp = doc.createDocumentFragment();
                                    DOM._4e_moveChildren(currentListItem, tmp);
                                    currentListItem = tmp;
                                }
                            }

                            var currentListItemName = DOM._4e_name(currentListItem);
                            if (!UA.ie && ( currentListItemName == 'div' ||
                                currentListItemName == 'p' ))
                                DOM._4e_appendBogus(currentListItem);
                            retval.appendChild(currentListItem);
                            rootNode = null;
                            currentIndex++;
                        }
                        else
                            return null;

                        if (listArray.length <= currentIndex ||
                            Math.max(listArray[ currentIndex ].indent, 0) < indentLevel)
                            break;
                    }

                    // Clear marker attributes for the new list tree made of cloned nodes, if any.
                    if (database) {
                        var currentNode = new Node(retval.firstChild);
                        while (currentNode && currentNode[0]) {
                            if (currentNode[0].nodeType == KEN.NODE_ELEMENT) {
                                currentNode._4e_clearMarkers(database, true);
                                //add by yiminghe:no need _ke_expando copied!

                            }
                            currentNode = currentNode._4e_nextSourceNode();
                        }
                    }

                    return { listNode : retval, nextIndex : currentIndex };
                }
            };


            var headerTagRegex = /^h[1-6]$/;


            function ListCommand(type) {
                this.type = type;
            }

            ListCommand.prototype = {
                changeListType:function(editor, groupObj, database, listsCreated) {
                    // This case is easy...
                    // 1. Convert the whole list into a one-dimensional array.
                    // 2. Change the list type by modifying the array.
                    // 3. Recreate the whole list by converting the array to a list.
                    // 4. Replace the original list with the recreated list.
                    var listArray = list.listToArray(groupObj.root, database),
                        selectedListItems = [];

                    for (var i = 0; i < groupObj.contents.length; i++) {
                        var itemNode = groupObj.contents[i];
                        itemNode = itemNode._4e_ascendant('li', true);
                        if ((!itemNode || !itemNode[0]) ||
                            itemNode._4e_getData('list_item_processed'))
                            continue;
                        selectedListItems.push(itemNode);
                        itemNode._4e_setMarker(database, 'list_item_processed', true);
                    }

                    var fakeParent = new Node(groupObj.root[0].ownerDocument.createElement(this.type));
                    for (i = 0; i < selectedListItems.length; i++) {
                        var listIndex = selectedListItems[i]._4e_getData('listarray_index');
                        listArray[listIndex].parent = fakeParent;
                    }
                    var newList = list.arrayToList(listArray, database, null, "p");
                    var child, length = newList.listNode.childNodes.length;
                    for (i = 0; i < length &&
                        ( child = new Node(newList.listNode.childNodes[i]) ); i++) {
                        if (child._4e_name() == this.type)
                            listsCreated.push(child);
                    }
                    DOM.insertBefore(newList.listNode, groupObj.root[0]);
                    groupObj.root._4e_remove();
                },
                createList:function(editor, groupObj, listsCreated) {
                    var contents = groupObj.contents,
                        doc = groupObj.root[0].ownerDocument,
                        listContents = [];

                    // It is possible to have the contents returned by DomRangeIterator to be the same as the root.
                    // e.g. when we're running into table cells.
                    // In such a case, enclose the childNodes of contents[0] into a <div>.
                    if (contents.length == 1 && contents[0][0] === groupObj.root[0]) {
                        var divBlock = new Node(doc.createElement('div'));
                        contents[0][0].nodeType != KEN.NODE_TEXT &&
                        contents[0]._4e_moveChildren(divBlock);
                        contents[0][0].appendChild(divBlock[0]);
                        contents[0] = divBlock;
                    }

                    // Calculate the common parent node of all content blocks.
                    var commonParent = groupObj.contents[0].parent();
                    for (var i = 0; i < contents.length; i++)
                        commonParent = commonParent._4e_commonAncestor(contents[i].parent());

                    // We want to insert things that are in the same tree level only,
                    // so calculate the contents again
                    // by expanding the selected blocks to the same tree level.
                    for (i = 0; i < contents.length; i++) {
                        var contentNode = contents[i],
                            parentNode;
                        while (( parentNode = contentNode.parent() )) {
                            if (parentNode[0] === commonParent[0]) {
                                listContents.push(contentNode);
                                break;
                            }
                            contentNode = parentNode;
                        }
                    }

                    if (listContents.length < 1)
                        return;

                    // Insert the list to the DOM tree.
                    var insertAnchor = new Node(listContents[ listContents.length - 1 ][0].nextSibling),
                        listNode = new Node(doc.createElement(this.type));

                    listsCreated.push(listNode);
                    while (listContents.length) {
                        var contentBlock = listContents.shift(),
                            listItem = new Node(doc.createElement('li'));

                        // Preserve heading structure when converting to list item. (#5271)
                        if (headerTagRegex.test(contentBlock._4e_name())) {
                            listItem[0].appendChild(contentBlock[0]);
                        } else {
                            contentBlock._4e_copyAttributes(listItem);
                            contentBlock._4e_moveChildren(listItem);
                            contentBlock._4e_remove();
                        }
                        listNode[0].appendChild(listItem[0]);

                        // Append a bogus BR to force the LI to render at full height
                        if (!UA.ie)
                            listItem._4e_appendBogus();
                    }
                    if (insertAnchor[0])
                        DOM.insertBefore(listNode[0], insertAnchor[0]);
                    else
                        commonParent[0].appendChild(listNode[0]);
                },
                removeList:function(editor, groupObj, database) {
                    // This is very much like the change list type operation.
                    // Except that we're changing the selected items' indent to -1 in the list array.
                    var listArray = list.listToArray(groupObj.root, database),
                        selectedListItems = [];

                    for (var i = 0; i < groupObj.contents.length; i++) {
                        var itemNode = groupObj.contents[i];
                        itemNode = itemNode._4e_ascendant('li', true);
                        if (!itemNode || itemNode._4e_getData('list_item_processed'))
                            continue;
                        selectedListItems.push(itemNode);
                        itemNode._4e_setMarker(database, 'list_item_processed', true);
                    }

                    var lastListIndex = null;
                    for (i = 0; i < selectedListItems.length; i++) {
                        var listIndex = selectedListItems[i]._4e_getData('listarray_index');
                        listArray[listIndex].indent = -1;
                        lastListIndex = listIndex;
                    }

                    // After cutting parts of the list out with indent=-1, we still have to maintain the array list
                    // model's nextItem.indent <= currentItem.indent + 1 invariant. Otherwise the array model of the
                    // list cannot be converted back to a real DOM list.
                    for (i = lastListIndex + 1; i < listArray.length; i++) {
                        //if (listArray[i].indent > listArray[i - 1].indent + 1) {
                        //modified by yiminghe
                        if (listArray[i].indent > Math.max(listArray[i - 1].indent, 0)) {
                            var indentOffset = listArray[i - 1].indent + 1 - listArray[i].indent;
                            var oldIndent = listArray[i].indent;
                            while (listArray[i]
                                && listArray[i].indent >= oldIndent) {
                                listArray[i].indent += indentOffset;
                                i++;
                            }
                            i--;
                        }
                    }

                    var newList = list.arrayToList(listArray, database, null, "p");

                    // Compensate <br> before/after the list node if the surrounds are non-blocks.(#3836)
                    var docFragment = newList.listNode, boundaryNode, siblingNode;

                    function compensateBrs(isStart) {
                        if (( boundaryNode = new Node(docFragment[ isStart ? 'firstChild' : 'lastChild' ]) )
                            && !( boundaryNode[0].nodeType == KEN.NODE_ELEMENT &&
                            boundaryNode._4e_isBlockBoundary() )
                            && ( siblingNode = groupObj.root[ isStart ? '_4e_previous' : '_4e_next' ]
                            (Walker.whitespaces(true)) )
                            && !( boundaryNode[0].nodeType == KEN.NODE_ELEMENT &&
                            siblingNode._4e_isBlockBoundary({ br : 1 }) ))

                            DOM[ isStart ? 'insertBefore' : 'insertAfter' ](editor.document.createElement('br'),
                                boundaryNode[0]);
                    }

                    compensateBrs(true);
                    compensateBrs(undefined);

                    DOM.insertBefore(docFragment, groupObj.root);
                    groupObj.root._4e_remove();
                },

                exec : function(editor) {
                    var //doc = editor.document,
                        selection = editor.getSelection(),
                        ranges = selection && selection.getRanges();

                    // There should be at least one selected range.
                    if (!ranges || ranges.length < 1)
                        return;

                    var bookmarks = selection.createBookmarks(true);

                    // Group the blocks up because there are many cases where multiple lists have to be created,
                    // or multiple lists have to be cancelled.
                    var listGroups = [],
                        database = {};
                    while (ranges.length > 0) {
                        var range = ranges.shift();

                        var boundaryNodes = range.getBoundaryNodes(),
                            startNode = boundaryNodes.startNode,
                            endNode = boundaryNodes.endNode;

                        if (startNode[0].nodeType == KEN.NODE_ELEMENT && startNode._4e_name() == 'td')
                            range.setStartAt(boundaryNodes.startNode, KER.POSITION_AFTER_START);

                        if (endNode[0].nodeType == KEN.NODE_ELEMENT && endNode._4e_name() == 'td')
                            range.setEndAt(boundaryNodes.endNode, KER.POSITION_BEFORE_END);

                        var iterator = range.createIterator(),
                            block;

                        iterator.forceBrBreak = false;

                        while (( block = iterator.getNextParagraph() )) {

                            // Avoid duplicate blocks get processed across ranges.
                            if (block._4e_getData('list_block'))
                                continue;
                            else
                                block._4e_setMarker(database, 'list_block', 1);


                            var path = new ElementPath(block),
                                pathElements = path.elements,
                                pathElementsCount = pathElements.length,
                                listNode = null,
                                processedFlag = false,
                                blockLimit = path.blockLimit,
                                element;

                            // First, try to group by a list ancestor.
                            for (var i = pathElementsCount - 1; i >= 0 &&
                                ( element = pathElements[ i ] ); i--) {
                                if (listNodeNames[ element._4e_name() ]
                                    && blockLimit.contains(element))     // Don't leak outside block limit (#3940).
                                {
                                    // If we've encountered a list inside a block limit
                                    // The last group object of the block limit element should
                                    // no longer be valid. Since paragraphs after the list
                                    // should belong to a different group of paragraphs before
                                    // the list. (Bug #1309)
                                    blockLimit._4e_removeData('list_group_object');

                                    var groupObj = element._4e_getData('list_group_object');
                                    if (groupObj)
                                        groupObj.contents.push(block);
                                    else {
                                        groupObj = { root : element, contents : [ block ] };
                                        listGroups.push(groupObj);
                                        element._4e_setMarker(database, 'list_group_object', groupObj);
                                    }
                                    processedFlag = true;
                                    break;
                                }
                            }

                            if (processedFlag)
                                continue;

                            // No list ancestor? Group by block limit.
                            var root = blockLimit || path.block;
                            if (root._4e_getData('list_group_object'))
                                root._4e_getData('list_group_object').contents.push(block);
                            else {
                                groupObj = { root : root, contents : [ block ] };
                                root._4e_setMarker(database, 'list_group_object', groupObj);
                                listGroups.push(groupObj);
                            }
                        }
                    }

                    // Now we have two kinds of list groups, groups rooted at a list, and groups rooted at a block limit element.
                    // We either have to build lists or remove lists, for removing a list does not makes sense when we are looking
                    // at the group that's not rooted at lists. So we have three cases to handle.
                    var listsCreated = [];
                    while (listGroups.length > 0) {
                        groupObj = listGroups.shift();
                        if (this.state == "off") {
                            if (listNodeNames[ groupObj.root._4e_name() ])
                                this.changeListType(editor, groupObj, database, listsCreated);
                            else
                                this.createList(editor, groupObj, listsCreated);
                        }
                        else if (this.state == "on" && listNodeNames[ groupObj.root._4e_name() ])
                            this.removeList(editor, groupObj, database);
                    }

                    // For all new lists created, merge adjacent, same type lists.
                    for (i = 0; i < listsCreated.length; i++) {
                        listNode = listsCreated[i];
                        //note by yiminghe,why not use merge sibling directly
                        //listNode._4e_mergeSiblings();

                        var mergeSibling, listCommand = this;
                        ( mergeSibling = function(rtl) {

                            var sibling = listNode[ rtl ?
                                '_4e_previous' : '_4e_next' ](Walker.whitespaces(true));
                            if (sibling && sibling[0] &&
                                sibling._4e_name() == listCommand.type) {
                                sibling._4e_remove();
                                // Move children order by merge direction.(#3820)
                                sibling._4e_moveChildren(listNode, rtl ? true : false);
                            }
                        } )();
                        mergeSibling(true);

                    }

                    // Clean up, restore selection and update toolbar button states.
                    KE.Utils.clearAllMarkers(database);
                    selection.selectBookmarks(bookmarks);
                }
            };


            var TripleButton = KE.TripleButton;

            /**
             * 用到了按钮三状态的两个状态：off:点击后格式化，on:点击后清除格式化
             * @param cfg
             */
            function List(cfg) {
                var self = this;
                List.superclass.constructor.call(self, cfg);
                var editor = self.get("editor"),
                    toolBarDiv = editor.toolBarDiv;
                self.el = new TripleButton({
                    //text:this.get("type"),
                    contentCls:self.get("contentCls"),
                    title:self.get("title"),
                    container:toolBarDiv
                });
                self.listCommand = new ListCommand(self['get']("type"));
                self.listCommand.state = self['get']("status");
                //this._selectionChange({path:1});
                self._init();
            }

            List.ATTRS = {
                editor:{},
                type:{},
                contentCls:{}
            };

            S.extend(List, S.Base, {

                _init:function() {
                    var self = this,editor = self.get("editor"),
                        toolBarDiv = editor.toolBarDiv,
                        el = self.el;

                    el.on("offClick onClick", self._change, self);
                    editor.on("selectionChange", self._selectionChange, self);
                    KE.Utils.sourceDisable(editor, self);
                },
                disable:function() {
                    this.el.set("state", TripleButton.DISABLED);
                },
                enable:function() {
                    this.el.set("state", TripleButton.OFF);
                },


                _change:function() {
                    var self = this,editor = self.get("editor"),
                        type = self.get("type"),
                        el = self.el;
                    editor.fire("save");
                    self.listCommand.state = el.get("state");
                    self.listCommand.exec(editor);
                    editor.fire("save");
                    editor.notifySelectionChange();
                },

                _selectionChange:function(ev) {
                    var self = this,editor = self.get("editor"),
                        type = self.get("type"),
                        elementPath = ev.path,
                        element,
                        el = self.el,
                        blockLimit = elementPath.blockLimit,
                        elements = elementPath.elements;
                    if (!blockLimit)return;
                    // Grouping should only happen under blockLimit.(#3940).
                    if (elements)
                        for (var i = 0; i < elements.length && ( element = elements[ i ] )
                            && element[0] !== blockLimit[0]; i++) {
                            var ind = S.indexOf(elements[i]._4e_name(), listNodeNames_arr);
                            //ul,ol一个生效后，另一个就失效
                            if (ind !== -1) {
                                if (listNodeNames_arr[ind] === type) {
                                    el.set("state", TripleButton.ON);
                                    return;
                                } else {
                                    break;
                                }

                            }
                        }
                    el.set("state", TripleButton.OFF);
                }
            });

            KE.ListUtils = list;
            KE.List = List
        })();
    }
    editor.addPlugin(function() {
        new KE.List({
            editor:editor,
            title:"项目列表",
            contentCls:"ke-toolbar-ul",
            type:"ul"
        });
        new KE.List({
            editor:editor,
            title:"编号列表",
            contentCls:"ke-toolbar-ol",
            type:"ol"
        });
    });
});
/**
 * localStorage support for ie<8
 * @author:yiminghe@gmail.com
 */
KISSY.Editor.add("localStorage", function() {
    var S = KISSY,
        KE = S.Editor,STORE;
    STORE = KE.STORE = "localStorage";
    if (!KE.storeReady) {
        KE.storeReady = function(run) {
            KE.on("storeReady", run);
        };
        function rewrite() {
            KE.storeReady = function(run) {
                run();
            };
            KE.detach("storeReady");
        }

        KE.on("storeReady", rewrite);
    }
    function complete() {
        KE.fire("storeReady");
    }

    //原生或者已经定义过立即返回
    if (window[STORE]) {
        //原生的立即可用
        if (!window[STORE]._ke) {
            complete();
        }
        return;
    }

    //国产浏览器用随机数/时间戳试试 ! 是可以的
    var movie = KE.Config.base +
        KE.Utils.debugUrl("plugins/localStorage/swfstore.swf?rand=" +
            (+new Date()));


    window[STORE] = new KE.FlashBridge({
        movie:movie,
        methods:["setItem","removeItem","getValueOf"]
    });

    S.mix(window[STORE], {
        _ke:1,
        getItem:function(key) {
            return this.getValueOf(key);
        }
    });

    //非原生，等待flash通知
    window[STORE].on("contentReady", function() {
        complete();
    });
});/**
 * maximize editor
 * @author:yiminghe@gmail.com
 * @note:firefox 焦点完全完蛋了，这里全是针对firefox
 */
KISSY.Editor.add("maximize", function(editor) {
    var KE = KISSY.Editor,
        S = KISSY,
        UA = S.UA,
        Node = S.Node,
        Event = S.Event,
        TripleButton = KE.TripleButton,
        DOM = S.DOM,
        iframe;
    //firefox 3.5 不支持，有bug
    if (UA.gecko < 1.92) return;
    if (!KE.Maximize) {
        (function() {
            DOM.addStyleSheet(
                ".ke-toolbar-padding {" +
                    "padding:5px;" +
                    "}",
                "ke-maximize"
                );
            var MAXIMIZE_CLASS = "ke-toolbar-maximize",
                RESTORE_CLASS = "ke-toolbar-restore",
                MAXIMIZE_TIP = "全屏",
                MAXIMIZE_TOOLBAR_CLASS = "ke-toolbar-padding",
                RESTORE_TIP = "取消全屏";

            function Maximize(editor) {
                var self = this;
                self.editor = editor;
                self._init();
            }

            Maximize.init = function() {
                iframe = new Node("<" + "iframe " +
                    " class='ke-maximize-shim'" +
                    " style='" +
                    "position:absolute;" +
                    "top:-9999px;" +
                    "left:-9999px;" +
                    "'" +
                    " frameborder='0'>" +
                    "</iframe>").appendTo(document.body);
                Maximize.init = null;
            };
            S.augment(Maximize, {
                _init:function() {
                    var self = this,
                        editor = self.editor,
                        el = new TripleButton({
                            container:editor.toolBarDiv,
                            title:"全屏",
                            contentCls:MAXIMIZE_CLASS
                        });
                    self.el = el;
                    el.on("offClick", self.maximize, self);
                    el.on("onClick", self.restore, self);
                    KE.Utils.lazyRun(self, "_prepare", "_real");
                    self._toolBarDiv = editor.toolBarDiv;
                },

                restore:function() {
                    var self = this,
                        doc = document,
                        editor = self.editor;
                    //body overflow 变化也会引起 resize 变化！！！！先去除
                    self._resize && Event.remove(window, "resize", self._resize);
                    self._saveEditorStatus();
                    self._restoreState();
                    self.el.boff();

                    //firefox 必须timeout
                    setTimeout(function() {
                        self._restoreEditorStatus();
                        editor.notifySelectionChange();
                        editor.fire("restoreWindow");
                    }, 30);
                },

                /**
                 * 从内存恢复最大化前的外围状态信息到编辑器实际动作，
                 * 包括编辑器位置以及周围元素，浏览器窗口
                 */
                _restoreState:function() {
                    var self = this,
                        doc = document,
                        editor = self.editor,
                        //恢复父节点的position原状态 bugfix:最大化被父元素限制
                        _savedParents = self._savedParents;
                    if (_savedParents) {
                        for (var i = 0; i < _savedParents.length; i++) {
                            var po = _savedParents[i];
                            po.el.css("position", po.position);
                        }
                        self._savedParents = null;
                    }
                    //如果没有失去焦点，重新获得当前选取元素
                    //self._saveEditorStatus();
                    editor.wrap.css({
                        height:self.iframeHeight
                    });

                    DOM.css(doc.body, {
                        width:"",
                        height:"",
                        overflow:""
                    });
                    //documentElement 设置宽高，ie崩溃
                    doc.documentElement.style.overflow = "";

                    editor.editorWrap.css({
                        position:"static",
                        width:self.editorWrapWidth
                    });
                    iframe.css({
                        left:"-99999px",
                        top:"-99999px"
                    });
                    window.scrollTo(self.scrollLeft, self.scrollTop);
                    var bel = self.el.el;
                    bel.one("span")
                        .removeClass(RESTORE_CLASS)
                        .addClass(MAXIMIZE_CLASS);
                    bel.attr("title", MAXIMIZE_TIP);

                    UA.ie < 8 && self._toolBarDiv.removeClass(MAXIMIZE_TOOLBAR_CLASS);
                },
                /**
                 * 保存最大化前的外围状态信息到内存，
                 * 包括编辑器位置以及周围元素，浏览器窗口
                 */
                _saveSate:function() {
                    var self = this,
                        editor = self.editor,
                        _savedParents = [],
                        editorWrap = editor.editorWrap;
                    self.iframeHeight = editor.wrap._4e_style("height");
                    self.editorWrapWidth = editorWrap._4e_style("width");
                    //主窗口滚动条也要保存哦
                    self.scrollLeft = DOM.scrollLeft();
                    self.scrollTop = DOM.scrollTop();
                    window.scrollTo(0, 0);

                    //将父节点的position都改成static并保存原状态 bugfix:最大化被父元素限制
                    var p = editorWrap.parent();

                    while (p) {
                        var pre = p.css("position");
                        if (pre != "static") {
                            _savedParents.push({
                                el:p,
                                position:pre
                            });
                            p.css("position", "static");
                        }
                        p = p.parent();
                    }
                    self._savedParents = _savedParents;
                    var bel = self.el.el;
                    self.el.el.one("span")
                        .removeClass(MAXIMIZE_CLASS)
                        .addClass(RESTORE_CLASS);
                    bel.attr("title", RESTORE_TIP);
                    //ie6,7 图标到了窗口边界，不可点击，给个padding
                    UA.ie < 8 && self._toolBarDiv.addClass(MAXIMIZE_TOOLBAR_CLASS);
                },

                /**
                 *  编辑器自身核心状态保存，每次最大化最小化都要save,restore，
                 *  firefox修正，iframe layout变化时，range丢了
                 */
                _saveEditorStatus:function() {
                    var self = this,
                        editor = self.editor;
                    self.savedRanges = null;
                    if (!UA.gecko || !editor.iframeFocus) return;
                    var sel = editor.getSelection();
                    //firefox 光标丢失bug,位置丢失，所以这里保存下
                    self.savedRanges = sel && sel.getRanges();
                },

                /**
                 * 编辑器自身核心状态恢复，每次最大化最小化都要save,restore，
                 * 维持编辑器核心状态不变
                 */
                _restoreEditorStatus:function() {
                    var self = this,
                        editor = self.editor,
                        sel = editor.getSelection(),
                        savedRanges = self.savedRanges;

                    //firefox焦点bug

                    //原来是聚焦，现在刷新designmode
                    //firefox 先失去焦点才行
                    editor.activateGecko();

                    if (savedRanges && sel) {
                        sel.selectRanges(savedRanges);
                    }

                    //firefox 有焦点时才重新聚焦
                    if (editor.iframeFocus && sel) {
                        var element = sel.getStartElement();
                        //使用原生不行的，会使主窗口滚动
                        //element[0] && element[0].scrollIntoView(true);
                        element && element[0] && element._4e_scrollIntoView();
                    }

                    //datauri 清空里面的background-image，使得 expression 重新执行
                    if (UA.ie < 8) {
                        self.el.el.one("span").css("background-image", "");
                    }

                },

                /**
                 * 将编辑器最大化-实际动作
                 * 必须做两次，何解？？
                 */
                _maximize:function(stop) {
                    var self = this,
                        doc = document,
                        editor = self.editor,
                        editorWrap = editor.editorWrap,
                        viewportHeight = DOM.viewportHeight(),
                        viewportWidth = DOM.viewportWidth(),
                        statusHeight = editor.statusDiv ? editor.statusDiv[0].offsetHeight : 0,
                        toolHeight = editor.toolBarDiv[0].offsetHeight;


                    if (!UA.ie) {
                        DOM.css(doc.body, {
                            width:0,
                            height:0,
                            overflow:"hidden"
                        });
                    } else {
                        doc.body.style.overflow = "hidden";
                    }
                    doc.documentElement.style.overflow = "hidden";

                    editorWrap.css({
                        position:"absolute",
                        zIndex:editor.baseZIndex(KE.zIndexManager.MAXIMIZE),
                        width:viewportWidth + "px"
                    });
                    iframe.css({
                        zIndex:editor.baseZIndex(KE.zIndexManager.MAXIMIZE-5),
                        height:viewportHeight + "px",
                        width:viewportWidth + "px"
                    });
                    editorWrap.offset({
                        left:0,
                        top:0
                    });
                    iframe.css({
                        left:0,
                        top:0
                    });

                    editor.wrap.css({
                        height:(viewportHeight - statusHeight - toolHeight ) + "px"
                    });
                    if (stop !== true) {
                        arguments.callee.call(self, true);
                    }
                },
                _real:function() {
                    var self = this,
                        editor = self.editor;

                    self._saveEditorStatus();
                    self._saveSate();
                    self._maximize();
                    //firefox第一次最大化bug，重做一次
                    //if (true
                    //|| UA.gecko
                    //   ) {

                    //}
                    self._resize = self._resize || KE.Utils.buffer(self._maximize, self, 100);
                    Event.on(window, "resize", self._resize);

                    self.el.set("state", TripleButton.ON);
                    setTimeout(function() {
                        self._restoreEditorStatus();
                        editor.notifySelectionChange();
                        editor.fire("maximizeWindow");
                    }, 30);
                },

                _prepare:function() {
                    Maximize.init && Maximize.init();
                },
                maximize:function() {
                    this._prepare();
                }
            });

            KE.Maximize = Maximize;
        })();
    }
    editor.addPlugin(function() {
        new KE.Maximize(editor);
    });
});/**
 * insert music for kissy editor
 * @author: yiminghe@gmail.com
 */
KISSY.Editor.add("music", function(editor) {
    var KE = KISSY.Editor,
        S = KISSY,
        DOM = S.DOM,
        UA = S.UA,
        Event = S.Event,
        Flash = KE.Flash,
        CLS_MUSIC = "ke_music",
        TYPE_MUSIC = 'music',
        MUSIC_PLAYER = "niftyplayer.swf",
        dataProcessor = editor.htmlDataProcessor,
        dataFilter = dataProcessor && dataProcessor.dataFilter,
        TIP = "请输入如 http://xxx.com/xx.mp3";


    function music(src) {
        return src.indexOf(MUSIC_PLAYER) != -1;
    }

    dataFilter && dataFilter.addRules({
        elements : {
            'object' : function(element) {
                var attributes = element.attributes,i,
                    classId = attributes['classid'] &&
                        String(attributes['classid']).toLowerCase();
                if (!classId) {
                    // Look for the inner <embed>
                    for (i = 0; i < element.children.length; i++) {
                        if (element.children[ i ].name == 'embed') {
                            if (!Flash.isFlashEmbed(element.children[ i ]))
                                return null;
                            if (music(element.children[ i ].attributes.src)) {
                                return dataProcessor.createFakeParserElement(element, CLS_MUSIC, TYPE_MUSIC, true);
                            }

                        }
                    }
                    return null;
                }

                for (i = 0; i < element.children.length; i++) {
                    var c = element.children[ i ];
                    if (c.name == 'param' && c.attributes.name == "movie") {
                        if (music(c.attributes.value)) {
                            return dataProcessor.createFakeParserElement(element, CLS_MUSIC, TYPE_MUSIC, true);
                        }
                    }
                }

            },

            'embed' : function(element) {
                if (!Flash.isFlashEmbed(element))
                    return null;
                if (music(element.attributes.src)) {
                    return dataProcessor.createFakeParserElement(element, CLS_MUSIC, TYPE_MUSIC, true);
                }

            }
            //4 比 flash 的优先级 5 高！
        }}, 4);

    //重构，和flash结合起来，抽象
    if (!KE.MusicInserter) {
        (function() {
            var flashRules = ["img." + CLS_MUSIC];

            function MusicInserter(editor) {
                MusicInserter.superclass.constructor.apply(this, arguments);
                //只能ie能用？，目前只有firefox,ie支持图片缩放
                var disableObjectResizing = editor.cfg.disableObjectResizing;
                if (!disableObjectResizing) {
                    Event.on(editor.document.body, UA.ie ? 'resizestart' : 'resize', function(evt) {
                        //console.log(evt.target);
                        if (DOM.hasClass(evt.target, CLS_MUSIC))
                            evt.preventDefault();
                    });
                }
            }

            function checkMusic(node) {
                return node._4e_name() === 'img' && (!!node.hasClass(CLS_MUSIC)) && node;
            }


            S.extend(MusicInserter, Flash, {
                _config:function() {
                    var self = this,
                        editor = self.editor;
                    self._cls = CLS_MUSIC;
                    self._type = TYPE_MUSIC;
                    self._contentCls = "ke-toolbar-music";
                    self._tip = "插入音乐";
                    self._contextMenu = contextMenu;
                    self._flashRules = flashRules;
                }
            });


            Flash.registerBubble("music", "音乐网址： ", checkMusic);
            KE.MusicInserter = MusicInserter;
            var contextMenu = {
                "音乐属性":function(editor) {
                    var selection = editor.getSelection(),
                        startElement = selection && selection.getStartElement(),
                        flash = startElement && checkMusic(startElement),
                        flashUI = editor._toolbars[TYPE_MUSIC];
                    if (flash) {
                        flashUI.show(null, flash);
                    }
                }
            };
        })();
    }


    editor.addPlugin(function() {
        new KE.MusicInserter(editor);
    });

});/**
 * simple overlay for kissy editor using lazyRun
 * @author yiminghe@gmail.com
 * @refer http://yiminghe.javaeye.com/blog/734867
 */
KISSY.Editor.add("overlay", function() {
    // 每次实例都要载入!
    //console.log("overlay loaded!");
    var S = KISSY,
        KE = S.Editor,
        UA = S.UA,
        focusManager = KE.focusManager,
        Node = S.Node,
        Event = S.Event,
        DOM = S.DOM,
        dialogMarkUp = "<div class='ke-dialog' " +
            ">" +
            "<div class='ke-dialog-wrapper'>" +
            "<div class='ke-hd'>" +
            "<span class='ke-hd-title'>" +
            "@title@" +
            "</span>"
            + "<a class='ke-hd-x' href='#'>" +
            "<span class='ke-close'>X</span>" +
            "</a>"
            + "</div>" +
            "<div class='ke-bd'>" +
            "</div>" +
            "<div class='ke-ft'>" +
            "</div>" +
            "</div>" +
            "</div>",
        focusMarkup = "<a " +
            "href='#' " +
            "class='ke-focus' " +
            "style='" +
            "width:0;" +
            "height:0;" +
            "margin:0;" +
            "padding:0;" +
            "overflow:hidden;" +
            "outline:none;" +
            "font-size:0;'" +
            "></a>",
        mask ,
        loadingMask,
        noVisibleStyle = {
            "left":"-9999px",
            top:"-9999px"
        },
        loadingBaseZindex = KE.baseZIndex(KE.zIndexManager.LOADING);

    //全局的不要重写
    if (KE.SimpleOverlay) return;

    function Overlay() {
        var self = this;
        Overlay.superclass.constructor.apply(self, arguments);
        self._init();
    }


    Overlay.mask = function(zIndex) {
        if (!mask) {
            /**
             * 遮罩层
             */
            mask = new Overlay({
                el:new Node("<div>"),
                cls:"ke-mask",
                focusMgr:false,
                draggable:false
            });
            mask.el.css({
                "width":"100%",
                "background-color": "#000000",
                "height": DOM.docHeight(),
                "opacity": 0.15
            });
        }
        zIndex = zIndex || loadingBaseZindex;
        mask.el.css("z-index", zIndex);
        mask.show({left:0,top:0});
    };
    Overlay.unmask = function() {
        mask && mask.hide();
    };


    Overlay.loading = function(el) {
        if (!loadingMask) {
            loadingMask = new Overlay({
                el:new Node("<div>"),
                focusMgr:false,
                cls:"ke-loading",
                shortkey:false,
                draggable:false
            });
            loadingMask.el.css({
                opacity:0.15,
                border:0
            });
        }

        var width,height,offset,zIndex;
        if (el) {
            offset = el.offset();
            width = el[0].offsetWidth;
            height = el[0].offsetHeight;
            zIndex = parseInt(el.css("z-index")) + 1;
            //在元素的中间
            loadingMask.el.css("background-attachment", "scroll");
        } else {
            //DOM.addClass(document.documentElement, "ke-overflow-hidden");
            offset = {
                left:0,
                top:0
            };
            width = "100%";
            height = DOM.docHeight();
            zIndex = loadingBaseZindex;
            //在视窗的中间
            loadingMask.el.css("background-attachment", "fixed");
        }

        loadingMask.el.css({
            width:width,
            height:height,
            "z-index":zIndex
        });
        loadingMask.show(offset);
        return loadingMask;
    };

    Overlay.unloading = function() {
        //DOM.removeClass(document.documentElement, "ke-overflow-hidden");
        loadingMask && loadingMask.hide();
    };


    Overlay.ATTRS = {
        title:{value:""},
        width:{value:"500px"},
        height:{},
        cls:{},
        shortkey:{value:true},
        visible:{value:false},
        "zIndex":{value:KE.baseZIndex(KE.zIndexManager.OVERLAY)},
        //帮你管理焦点
        focusMgr:{value:true},
        mask:{value:false},
        draggable:{value:true}
    };

    S.extend(Overlay, S.Base, {
        _init:function() {
            var self = this;
            self._createEl();
            var el = self.el;
            el.css("z-index", self.get("zIndex"));
            /**
             * 窗口显示与隐藏
             */
            self.on("afterVisibleChange", function(ev) {
                var v = ev.newVal;
                if (v) {
                    if (typeof v == "boolean") {
                        self.center();
                    } else el.offset(v);
                    self.fire("show");
                } else {
                    el.css(noVisibleStyle);
                    self.fire("hide");
                }
            });

            /**
             * 关联编辑器焦点保留与复原
             */
            if (self.get("focusMgr")) {
                self._initFocusNotice();
                self.on("afterVisibleChange", self._editorFocusMg, self);
            }


            /**
             * 键盘快捷键注册
             */
            self.on("afterVisibleChange", function(ev) {
                var v = ev.newVal;
                if (v && self.get("shortkey")) {
                    self._register();
                } else {
                    self._unregister();
                }
            });


            if (self.get("mask")) {
                /**
                 * 遮罩层与ie6遮罩垫片同步
                 */
                self.on("show", function() {
                    Overlay.mask(self.get("zIndex") - 1);
                });
                self.on("hide", function() {
                    Overlay.unmask();
                });
            }

            self.on("afterZIndexChange", function(ev) {
                el.css("z-index", ev.newVal)
            });
            KE.Utils.lazyRun(this, "_prepareShow", "_realShow");

        },
        _register:function() {
            var self = this;
            Event.on(document, "keydown", self._keydown, self);
            //mask click support
            //if (mask) {
            //    mask.on("click", self.hide, self);
            //}
        },
        //esc keydown support
        _keydown:function(ev) {
            //esc
            if (ev.keyCode == 27) {
                this.hide();
                //停止默认行为，例如取消对象选中
                ev.halt();
            }
        },
        _unregister:function() {
            var self = this;
            Event.remove(document, "keydown", self._keydown, self);
            //if (mask) {
            //    mask.detach("click", self.hide, self);
            //}
        },
        _createEl:function() {
            //just manage container
            var self = this,
                el = self.get("el");
            if (!el) {
                //also gen html
                el = new Node(
                    dialogMarkUp.replace(/@title@/,
                        self.get("title"))).appendTo(document.body
                    );
                var head = el.one(".ke-hd"),
                    height = self.get("height");
                self.body = el.one(".ke-bd");
                self.foot = el.one(".ke-ft");
                self._title = head.one("h1");
                el.one(".ke-hd-x").on("click", function(ev) {
                    ev.preventDefault();
                    self.hide();
                });
                if (height) {
                    self.body.css({
                        "height": height,
                        "overflow":"auto"
                    });
                }


                /**
                 *  是否支持标题头拖放
                 */
                var draggable = self.get("draggable");
                if (draggable) {
                    var dragPos = {
                        "all":el ,
                        "foot":self.foot,
                        "body":self.body,
                        "head":head
                    };
                    if (draggable === true)
                        draggable = head;
                    else
                        draggable = dragPos[draggable];
                    if (draggable) {
                        new KE.Drag({
                            node:el,
                            handlers:{
                                id:draggable
                            }
                        });
                    }
                }
            } else {
                //已有元素就用dialog包起来
                self.originalEl = el;
                if (!el[0].parentNode ||
                    //ie新节点 为 fragment 类型
                    el[0].parentNode.nodeType != KE.NODE.NODE_ELEMENT) {
                    el = new Node("<div class='ke-dialog'>")
                        .append(new Node("<div class='ke-dialog-wrapper'>")
                        .append(el))
                        .appendTo(document.body);
                } else {
                    var w = new Node("<div class='ke-dialog'>");
                    w.insertBefore(el);
                    w.append(new Node("<div class='ke-dialog-wrapper'>").append(el));
                    el = w;
                }
            }
            if (self.get("cls")) {
                el.addClass(self.get("cls"));
            }
            if (self.get("width")) {
                el.css("width", self.get("width"));
            }

            self.set("el", el);
            //expose shortcut
            self.el = el;
            //初始状态隐藏
            el.css(noVisibleStyle);
        },

        center :function() {
            var el = this.el,
                bw = el.width(),
                bh = el.height(),
                vw = DOM.viewportWidth(),
                vh = DOM.viewportHeight(),
                bl = (vw - bw) / 2 + DOM.scrollLeft(),
                bt = (vh - bh) / 2 + DOM.scrollTop();
            if ((bt - DOM.scrollTop()) > 200) bt -= 150;

            bl = Math.max(bl, DOM.scrollLeft());
            bt = Math.max(bt, DOM.scrollTop());

            el.css({
                left: bl + "px",
                top: bt + "px"
            });
        },


        _getFocusEl:function() {
            var self = this,fel = self._focusEl;
            if (fel) {
                return fel;
            }
            //焦点管理，显示时用a获得焦点
            fel = new Node(focusMarkup)
                .appendTo(self.el);
            return self._focusEl = fel;
        }        ,

        _initFocusNotice:function() {
            var self = this,
                f = self._getFocusEl();
            f.on("focus", function() {
                self.fire("focus");
            });
            f.on("blur", function() {
                self.fire("blur");
            });
        },

        /**
         * 焦点管理，弹出前记住当前的焦点所在editor
         * 隐藏好重新focus当前的editor
         */
        _editorFocusMg:function(ev) {
            var self = this,
                editor = self._focusEditor,
                v = ev.newVal;
            //console.log(v + " change");
            //将要出现
            if (v) {
                //保存当前焦点editor
                self._focusEditor = focusManager.currentInstance();
                editor = self._focusEditor;
                //聚焦到当前窗口
                if (!UA.webkit) {
                    //webkit 滚动到页面顶部
                    //使得编辑器失去焦点，促使ie保存当前选择区域（位置）
                    self._getFocusEl()[0].focus();
                }
                {
                    /*
                     * IE BUG: If the initial focus went into a non-text element (e.g. button,image),
                     * then IE would still leave the caret inside the editing area.
                     */
                    if (UA.ie && editor) {
                        var $selection = editor.document.selection,
                            $range = $selection.createRange();
                        if ($range) {
                            if (
                            //修改ckeditor，如果单纯选择文字就不用管了
                            //$range.parentElement && $range.parentElement().ownerDocument == editor.document
                            //||
                            //缩放图片那个框在ie下会突出浮动层来
                                $range.item && $range.item(0).ownerDocument == editor.document) {
                                var $myRange = document.body.createTextRange();
                                $myRange.moveToElementText(self.el._4e_first()[0]);
                                $myRange.collapse(true);
                                $myRange.select();
                            }
                        }
                    }
                }

            }
            //将要隐藏
            else {
                editor && editor.focus();
            }
        },
        _prepareShow:function() {
            if (UA.ie == 6) {
                /**
                 * 窗口垫片-shim
                 */
                var self = this,
                    el = self.el,
                    d_iframe = new Node(
                        "<" + "iframe class='ke-dialog-iframe'" +
                            "></iframe>");
                d_iframe.css(S.mix({
                    opacity:0
                }));
                d_iframe.insertBefore(self.el.one(".ke-dialog-wrapper"));
            }
        },

        loading:function() {
            return Overlay.loading(this.el);
        },

        unloading:function() {
            Overlay.unloading();
        },
        _realShow : function(v) {
            this.set("visible", v || true);
        },
        show:function(v) {
            this._prepareShow(v);
        },
        hide:function() {
            this.set("visible", false);
        }
    });
    KE.Utils.lazyRun(Overlay.prototype,
        "_prepareLoading",
        "_realLoading");
    KE.SimpleOverlay = Overlay;
});
KISSY.Editor.add("pagebreak", function(editor) {
    var S = KISSY,KE = S.Editor,
        dataProcessor = editor.htmlDataProcessor,
        dataFilter = dataProcessor && dataProcessor.dataFilter,
        CLS = "ke_pagebreak",
        TYPE = "div";
    if (dataFilter) {
        dataFilter.addRules({
            elements :
            {
                div : function(element) {
                    var attributes = element.attributes,
                        style = attributes && attributes.style,
                        child = style && element.children.length == 1 && element.children[ 0 ],
                        childStyle = child && ( child.name == 'span' ) && child.attributes.style;

                    if (childStyle && ( /page-break-after\s*:\s*always/i ).test(style) && ( /display\s*:\s*none/i ).test(childStyle))
                        return dataProcessor.createFakeParserElement(element, CLS, TYPE);
                }
            }
        });
    }

    if (!KE.PageBreak) {
        (function() {
            var Node = S.Node,
                TripleButton = KE.TripleButton,
                mark_up = '<div' +
                    ' style="page-break-after: always; ">' +
                    '<span style="DISPLAY:none">&nbsp;</span></div>';

            function PageBreak(editor) {
                var el = new TripleButton({
                    container:editor.toolBarDiv,
                    title:"分页",
                    contentCls:"ke-toolbar-pagebreak"
                });
                el.on("offClick", function() {
                    var real = new Node(mark_up, null, editor.document),
                        substitute = editor.createFakeElement ?
                            editor.createFakeElement(real,
                                CLS,
                                TYPE,
                                true,
                                mark_up) :
                            real,

                        insert = new Node("<div>", null, editor.document).append(substitute);
                    editor.insertElement(insert);
                });
                this.el = el;
                KE.Utils.sourceDisable(editor, this);
            }

            S.augment(PageBreak, {
                disable:function() {
                    this.el.set("state", TripleButton.DISABLED);
                },
                enable:function() {
                    this.el.set("state", TripleButton.OFF);
                }
            });

            KE.PageBreak = PageBreak;
        })();
    }

    editor.addPlugin(function() {
        new KE.PageBreak(editor);
    });
});/**
 * preview for kissy editor
 * @author: yiminghe@gmail.com
 */
KISSY.Editor.add("preview", function(editor) {
    var KE = KISSY.Editor,
        S = KISSY,TripleButton = KE.TripleButton;
    if (!KE.Preview) {
        (function() {
            function Preview(editor) {
                this.editor = editor;
                this._init();
            }

            S.augment(Preview, {
                _init:function() {
                    var self = this,editor = self.editor;
                    self.el = new TripleButton({
                        container:editor.toolBarDiv,
                        title:"预览",
                        contentCls:"ke-toolbar-preview"
                        //text:"preview"
                    });
                    self.el.on("offClick", this._show, this);
                },
                _show:function() {
                    var self = this,
                        editor = self.editor;
                    //try {
                    //editor will be unvisible
                    //  editor.focus();
                    //} catch(e) {
                    // }
                    var iWidth = 640,    // 800 * 0.8,
                        iHeight = 420,    // 600 * 0.7,
                        iLeft = 80;	// (800 - 0.8 * 800) /2 = 800 * 0.1.
                    try {
                        var screen = window.screen;
                        iWidth = Math.round(screen.width * 0.8);
                        iHeight = Math.round(screen.height * 0.7);
                        iLeft = Math.round(screen.width * 0.1);
                    } catch (e) {
                    }
                    var sHTML = editor._prepareIFrameHtml()
                        .replace(/<body[^>]+>.+<\/body>/,
                        "<body>\n"
                            + editor.getData(true)
                            + "\n</body>")
                        .replace(/\${title}/, "预览"),
                        sOpenUrl = '',
                        oWindow = window.open(sOpenUrl,
                            //每次都弹出新窗口
                            '',
                            'toolbar=yes,' +
                                'location=no,' +
                                'status=yes,' +
                                'menubar=yes,' +
                                'scrollbars=yes,' +
                                'resizable=yes,' +
                                'width=' +
                                iWidth +
                                ',height='
                                + iHeight
                                + ',left='
                                + iLeft);
                    oWindow.document.open();
                    oWindow.document.write(sHTML);
                    oWindow.document.close();
                    //ie 重新显示
                    oWindow.focus();
                }
            });
            KE.Preview = Preview;
        })();
    }

    editor.addPlugin(function() {
        new KE.Preview(editor);
    });
});
KISSY.Editor.add("progressbar", function() {
    var S = KISSY,
        KE = S.Editor;
    if (KE.ProgressBar) return;


    var DOM = S.DOM,Node = S.Node;
    DOM.addStyleSheet("" +
        "" +
        ".ke-progressbar {" +
        "border:1px solid #D6DEE6;" +
        "position:relative;" +
        "margin-left:auto;margin-right:auto;" +
        "background-color: #EAEFF4;" +
        "background: -webkit-gradient(linear, left top, left bottom, from(#EAEFF4), " +
        ".to(#EBF0F3));" +
        " background: -moz-linear-gradient(top, #EAEFF4, #EBF0F3);" +
        "filter: progid:DXImageTransform.Microsoft.gradient(startColorstr = '#EAEFF4'," +
        " endColorstr = '#EBF0F3');" +
        "}" +
        "" +
        ".ke-progressbar-inner {" +
        "border:1px solid #3571B4;" +
        "background-color:#6FA5DB;" +
        "padding:1px;" +
        "}" +

        ".ke-progressbar-inner-bg {" +
        "height:100%;" +
        "background-color: #73B1E9;" +
        "background: -webkit-gradient(linear, left top, left bottom, from(#73B1E9), " +
        ".to(#3F81C8));" +
        " background: -moz-linear-gradient(top, #73B1E9, #3F81C8);" +
        "filter: progid:DXImageTransform.Microsoft.gradient(startColorstr = '#73B1E9', " +
        "endColorstr = '#3F81C8');" +
        "}" +
        "" +
        "" +
        ".ke-progressbar-title {" +
        "width:30px;" +
        "top:0;" +
        "left:40%;" +
        "line-height:1.2;" +
        "position:absolute;" +
        "}" +
        "", "ke_progressbar");
    function ProgressBar() {
        ProgressBar.superclass.constructor.apply(this, arguments);
        this._init();
    }

    ProgressBar.ATTRS = {
        container:{},
        width:{},
        height:{},
        //0-100
        progress:{value:0}
    };
    S.extend(ProgressBar, S.Base, {
        destroy:function() {
            var self = this;
            self.detach();
            self.el._4e_remove();
        },
        _init:function() {
            var self = this,
                h = self.get("height"),
                el = new Node("<div" +
                    " class='ke-progressbar' " +
                    " style='width:" +
                    self.get("width") +
                    ";" +
                    "height:" +
                    h +
                    ";'" +
                    "></div>"),
                container = self.get("container"),
                p = new Node(
                    "<div style='overflow:hidden;'>" +
                        "<div class='ke-progressbar-inner' style='height:" + (parseInt(h) - 4) + "px'>" +
                        "<div class='ke-progressbar-inner-bg'></div>" +
                        "</div>" +
                        "</div>"
                    ).appendTo(el),
                title = new Node("<span class='ke-progressbar-title'>").appendTo(el);
            if (container)
                el.appendTo(container);
            self.el = el;
            self._title = title;
            self._p = p;
            self.on("afterProgressChange", self._progressChange, self);
            self._progressChange({newVal:self.get("progress")});
        },

        _progressChange:function(ev) {
            var self = this,
                v = ev.newVal;
            self._p.css("width", v + "%");
            self._title.html(v + "%");
        }
    });
    KE.ProgressBar = ProgressBar;

});/**
 * remove inline-style format for kissy editor,modified from ckeditor
 * @author: yiminghe@gmail.com
 */
KISSY.Editor.add("removeformat", function(editor) {
    var KE = KISSY.Editor,
        S = KISSY,
        KER = KE.RANGE,
        ElementPath = KE.ElementPath,
        KEN = KE.NODE,
        TripleButton = KE.TripleButton,
        /**
         * A comma separated list of elements to be removed when executing the "remove
         " format" command. Note that only inline elements are allowed.
         * @type String
         * @default 'b,big,code,del,dfn,em,font,i,ins,kbd,q,samp,small,span,strike,strong,sub,sup,tt,u,var'
         * @example
         */
        removeFormatTags = 'b,big,code,del,dfn,em,font,i,ins,kbd,q,samp,small,span,strike,strong,sub,sup,tt,u,var,s',

        /**
         * A comma separated list of elements attributes to be removed when executing
         * the "remove format" command.
         * @type String
         * @default 'class,style,lang,width,height,align,hspace,valign'
         * @example
         */
        removeFormatAttributes = 'class,style,lang,width,height,align,hspace,valign'.split(',');

    removeFormatTags = new RegExp('^(?:' + removeFormatTags.replace(/,/g, '|') + ')$', 'i');

    function RemoveFormat(editor) {
        this.editor = editor;
        this._init();
    }

    S.augment(RemoveFormat, {
        _init:function() {
            var self = this,editor = self.editor;
            self.el = new TripleButton({
                title:"清除格式",
                contentCls:"ke-toolbar-removeformat",
                container:editor.toolBarDiv
            });
            self.el.on("offClick", self._remove, self);
            KE.Utils.sourceDisable(editor, self);
        },
        disable:function() {
            this.el.set("state", TripleButton.DISABLED);
        },
        enable:function() {
            this.el.set("state", TripleButton.OFF);
        },
        _remove:function() {
            var self = this,
                editor = self.editor,
                tagsRegex = removeFormatTags,
                removeAttributes = removeFormatAttributes;

            tagsRegex.lastIndex = 0;
            var ranges = editor.getSelection().getRanges();
            editor.fire("save");
            for (var i = 0, range; range = ranges[ i ]; i++) {
                if (range.collapsed)
                    continue;

                range.enlarge(KER.ENLARGE_ELEMENT);

                // Bookmark the range so we can re-select it after processing.
                var bookmark = range.createBookmark();

                // The style will be applied within the bookmark boundaries.
                var startNode = bookmark.startNode;
                var endNode = bookmark.endNode;

                // We need to check the selection boundaries (bookmark spans) to break
                // the code in a way that we can properly remove partially selected nodes.
                // For example, removing a <b> style from
                //		<b>This is [some text</b> to show <b>the] problem</b>
                // ... where [ and ] represent the selection, must result:
                //		<b>This is </b>[some text to show the]<b> problem</b>
                // The strategy is simple, we just break the partial nodes before the
                // removal logic, having something that could be represented this way:
                //		<b>This is </b>[<b>some text</b> to show <b>the</b>]<b> problem</b>

                var breakParent = function(node) {
                    // Let's start checking the start boundary.
                    var path = new ElementPath(node);
                    var pathElements = path.elements;

                    for (var i = 1, pathElement; pathElement = pathElements[ i ]; i++) {
                        if (pathElement._4e_equals(path.block) || pathElement._4e_equals(path.blockLimit))
                            break;

                        // If this element can be removed (even partially).
                        if (tagsRegex.test(pathElement._4e_name()))
                            node._4e_breakParent(pathElement);
                    }
                };

                breakParent(startNode);
                breakParent(endNode);

                // Navigate through all nodes between the bookmarks.
                var currentNode = startNode._4e_nextSourceNode(true, KEN.NODE_ELEMENT);

                while (currentNode) {
                    // If we have reached the end of the selection, stop looping.
                    if (currentNode._4e_equals(endNode))
                        break;

                    // Cache the next node to be processed. Do it now, because
                    // currentNode may be removed.
                    var nextNode = currentNode._4e_nextSourceNode(false, KEN.NODE_ELEMENT);

                    // This node must not be a fake element.
                    if (!( currentNode._4e_name() == 'img'
                        && currentNode.attr('_cke_realelement') )
                        ) {
                        // Remove elements nodes that match with this style rules.
                        if (tagsRegex.test(currentNode._4e_name()))
                            currentNode._4e_remove(true);
                        else {
                            removeAttrs(currentNode, removeAttributes);
                        }
                    }

                    currentNode = nextNode;
                }

                range.moveToBookmark(bookmark);
            }

            editor.getSelection().selectRanges(ranges);
            editor.fire("save");
        }

    });
    function removeAttrs(el, attrs) {
        for (var i = 0; i < attrs.length; i++)
            el.removeAttr(attrs[i]);
    }

    editor.addPlugin(function() {
        new RemoveFormat(editor);
    });

});KISSY.Editor.add("resize", function(editor) {
    var S = KISSY,KE = S.Editor,Node = S.Node;
    if (!KE.Resizer) {
        (function() {
            var markup = "<div class='ke-resizer'></div>",
                Draggable = KE.Draggable;

            function Resizer(editor) {
                this.editor = editor;
                this._init();
            }

            S.augment(Resizer, {
                _init:function() {
                    var self = this,
                        editor = self.editor,
                        statusDiv = editor.statusDiv,
                        resizer = new Node(markup),
                        cfg = editor.cfg["pluginConfig"]["resize"] || {};
                    cfg = cfg["direction"] || ["x","y"];
                    resizer.appendTo(statusDiv);
                    //最大化时就不能缩放了
                    editor.on("maximizeWindow", function() {
                        resizer.css("display", "none");
                    });
                    editor.on("restoreWindow", function() {
                        resizer.css("display", "");
                    });
                    var d = new Draggable({
                        node:resizer
                    }),height = 0,width = 0,
                        heightEl = editor.wrap,
                        widthEl = editor.editorWrap;
                    d.on("start", function() {
                        height = heightEl.height();
                        width = widthEl.width();
                    });
                    d.on("move", function(ev) {
                        var diffX = ev.pageX - this.startMousePos.left,
                            diffY = ev.pageY - this.startMousePos.top;
                        if (S.inArray("y", cfg)) heightEl.height(height + diffY);
                        if (S.inArray("x", cfg)) widthEl.width(width + diffX);
                    });
                }
            });

            KE.Resizer = Resizer;
        })();
    }

    editor.addPlugin(function() {
        new KE.Resizer(editor);
    });
});/**
 * select component for kissy editor
 * @author:yiminghe@gmail.com
 */
KISSY.Editor.add("select", function() {
    var S = KISSY,
        UA = S.UA,
        Node = S.Node,
        Event = S.Event,
        DOM = S.DOM,
        KE = S.Editor,
        TITLE = "title",
        ke_select_active = "ke-select-active",
        ke_menu_selected = "ke-menu-selected",
        markup = "<span class='ke-select-wrap'>" +
            "<a onclick='return false;' class='ke-select'>" +
            "<span class='ke-select-text'><span class='ke-select-text-inner'></span></span>" +
            "<span class='ke-select-drop-wrap'>" +
            "<span class='ke-select-drop'></span>" +
            "</span>" +
            "</a></span>",
        menu_markup = "<div onmousedown='return false;'>" +
            "</div>";

    if (KE.Select) return;
    function Select(cfg) {
        var self = this;
        Select.superclass.constructor.call(self, cfg);
        self._init();
    }

    var DISABLED_CLASS = "ke-select-disabled",
        ENABLED = 1,
        DISABLED = 0;
    Select.DISABLED = DISABLED;
    Select.ENABLED = ENABLED;
    var dtd = KE.XHTML_DTD;

    Select.ATTRS = {
        //title标题栏显示值value还是name
        //默认false，显示name
        showValue:{},
        el:{},
        cls:{},
        container:{},
        doc:{},
        value:{},
        width:{},
        title:{},
        items:{},
        //下拉框优先和select左左对齐，上下对齐
        //可以改作右右对齐，下上对齐
        align:{value:["l","b"]},
        menuContainer:{
            valueFn:function() {
                //chrome 需要添加在能够真正包含div的地方
                var c = this.el.parent();
                while (c) {
                    var n = c._4e_name();
                    if (dtd[n] && dtd[n]["div"])
                        return c;
                    c = c.parent();
                }
                return new Node(document.body);
            }
        },
        state:{value:ENABLED}
    };
    Select.decorate = function(el) {
        var width = el.width() ,
            items = [],
            options = el.all("option");
        for (var i = 0; i < options.length; i++) {
            var opt = options[i];
            items.push({
                name:DOM.html(opt),
                value:DOM.attr(opt, "value")
            });
        }
        return new Select({
            width:width + "px",
            el:el,
            items:items,
            cls:"ke-combox",
            value:el.val()
        });

    };

    S.extend(Select, S.Base, {
        _init:function() {
            var self = this,
                container = self.get("container"),
                fakeEl = self.get("el"),
                el = new Node(markup),
                title = self.get(TITLE) || "",
                cls = self.get("cls"),
                text = el.one(".ke-select-text"),
                innerText = el.one(".ke-select-text-inner"),
                drop = el.one(".ke-select-drop");

            if (self.get("value") !== undefined) {
                innerText.html(self._findNameByV(self.get("value")));
            } else {
                innerText.html(title);
            }

            text.css("width", self.get("width"));
            //ie6,7 不失去焦点
            el._4e_unselectable();
            if (title)el.attr(TITLE, title);
            if (cls) {
                el.addClass(cls);
            }
            if (fakeEl) {
                fakeEl[0].parentNode.replaceChild(el[0], fakeEl[0]);
            } else if (container) {
                el.appendTo(container);
            }
            el.on("click", self._click, self);
            self.el = el;
            self.title = innerText;
            self._focusA = el.one("a.ke-select");
            KE.Utils.lazyRun(this, "_prepare", "_real");
            self.on("afterValueChange", self._valueChange, self);
            self.on("afterStateChange", self._stateChange, self);
        },
        _findNameByV:function(v) {
            var self = this,
                name = self.get(TITLE) || "",
                items = self.get("items");
            //显示值，防止下拉描述过多
            if (self.get("showValue")) {
                return v || name;
            }
            for (var i = 0; i < items.length; i++) {
                var item = items[i];
                if (item.value == v) {
                    name = item.name;
                    break;
                }
            }
            return name;
        },

        /**
         * 当逻辑值变化时，更新select的显示值
         * @param ev
         */
        _valueChange:function(ev) {
            var v = ev.newVal,
                self = this,
                name = self._findNameByV(v);
            self.title.html(name);
        },

        _itemsChange:function(ev) {
            var self = this,items = ev.newVal,
                _selectList = self._selectList;
            _selectList.html("");
            if (items) {
                for (var i = 0; i < items.length; i++) {
                    var item = items[i],a = new Node("<a " +
                        "class='ke-select-menu-item' " +
                        "href='#' data-value='" + item.value + "'>"
                        + item.name + "</a>", item.attrs)
                        .appendTo(_selectList)
                        ._4e_unselectable();
                }
            }
            self.as = _selectList.all("a");
        },
        val:function(v) {
            var self = this;
            if (v !== undefined) {
                self.set("value", v);
                return self;
            }
            else return self.get("value");
        },
        _resize:function() {
            var self = this,
                menu = self.menu;
            if (menu.get("visible")) {
                self._real();
            }
        },
        _prepare:function() {
            var self = this,
                el = self.el,
                popUpWidth = self.get("popUpWidth"),
                focusA = self._focusA,
                menuNode = new Node(menu_markup);
            //要在适当位置插入 !!!
            menuNode.appendTo(self.get("menuContainer"));

            var menu = new KE.SimpleOverlay({
                el:menuNode,
                cls:"ke-menu",
                width:popUpWidth ? popUpWidth : el.width(),
                zIndex:KE.baseZIndex(KE.zIndexManager.SELECT),
                focusMgr:false
            }),
                items = self.get("items");
            self.menu = menu;
            //缩放，下拉框跟随
            Event.on(window, "resize", self._resize, self);
            if (self.get(TITLE)) {
                new Node("<div class='ke-menu-title ke-select-menu-item' " +
                    "style='" +
                    "margin-top:-6px;" +
                    "' " +
                    ">" + self.get("title") + "</div>").appendTo(menuNode);
            }
            self._selectList = new Node("<div>").appendTo(menuNode);

            self._itemsChange({newVal:items});


            menu.on("show", function() {
                focusA.addClass(ke_select_active);
            });
            menu.on("hide", function() {
                focusA.removeClass(ke_select_active);
            });
            Event.on([document,self.get("doc")], "click", function(ev) {
                if (el._4e_contains(ev.target)) return;
                menu.hide();
            });
            menuNode.on("click", self._select, self);
            self.as = self._selectList.all("a");

            //mouseenter kissy core bug
            Event.on(menuNode[0], 'mouseenter', function() {
                self.as.removeClass(ke_menu_selected);
            });

            self.on("afterItemsChange", self._itemsChange, self);
        },
        _stateChange:function(ev) {
            var v = ev.newVal,el = this.el;
            if (v == ENABLED) {
                el.removeClass(DISABLED_CLASS);
            } else {
                el.addClass(DISABLED_CLASS);
            }
        },
        enable:function() {
            this.set("state", ENABLED);
        },
        disable:function() {
            this.set("state", DISABLED);
        },
        _select:function(ev) {
            ev.halt();
            var self = this,
                menu = self.menu,
                menuNode = menu.el,
                t = new Node(ev.target),
                a = t._4e_ascendant(function(n) {
                    return menuNode._4e_contains(n) && n._4e_name() == "a";
                }, true);

            if (!a) return;
            var preVal = self.get("value"),newVal = a.attr("data-value");
            //更新逻辑值
            self.set("value", newVal);

            //触发 click 事件，必要时可监听 afterValueChange
            self.fire("click", {
                newVal:newVal,
                prevVal:preVal,
                name:a.html()
            });
            menu.hide();
        },
        _real:function() {
            var self = this,
                el = self.el,
                xy = el.offset(),
                orixy = S.clone(xy),
                menuHeight = self.menu.el.height(),
                menuWidth = self.menu.el.width(),
                wt = DOM.scrollTop(),
                wl = DOM.scrollLeft(),
                wh = DOM.viewportHeight() ,
                ww = DOM.viewportWidth(),
                //右边界坐标,60 is buffer
                wr = wl + ww - 60,
                //下边界坐标
                wb = wt + wh,
                //下拉框向下弹出的y坐标
                sb = xy.top + (el.height() - 2),
                //下拉框右对齐的最右边x坐标
                sr = xy.left + el.width() - 2,
                align = self.get("align"),
                xAlign = align[0],
                yAlign = align[1];


            if (yAlign == "b") {
                //向下弹出优先
                xy.top = sb;
                if (
                    (
                        //不能显示完全
                        (xy.top + menuHeight) > wb
                        )
                        &&
                        (   //向上弹能显示更多
                            (orixy.top - wt) > (wb - sb)
                            )
                    ) {
                    xy.top = orixy.top - menuHeight;
                }
            } else {
                //向上弹出优先
                xy.top = orixy.top - menuHeight;

                if (
                //不能显示完全
                    xy.top < wt
                        &&
                        //向下弹能显示更多
                        (orixy.top - wt) < (wb - sb)
                    ) {
                    xy.top = sb;
                }
            }

            if (xAlign == "l") {
                //左对其优先
                if (
                //左对齐不行
                    (xy.left + menuWidth > wr)
                        &&
                        //右对齐可以弹出更多
                        (
                            (sr - wl) > (wr - orixy.left)
                            )

                    ) {
                    xy.left = sr - menuWidth;
                }
            } else {
                //右对齐优先
                xy.left = sr - menuWidth;
                if (
                //右对齐不行
                    xy.left < wl
                        &&
                        //左对齐可以弹出更多
                        (sr - wl) < (wr - orixy.left)
                    ) {
                    xy.left = orixy.left;
                }
            }
            self.menu.show(xy);
        },
        _click:function(ev) {
            ev.preventDefault();

            var self = this,
                el = self.el,
                v = self.get("value");

            if (el.hasClass(DISABLED_CLASS)) {
                return;
            }

            if (self._focusA.hasClass(ke_select_active)) {
                self.menu.hide();
                return;
            }

            self._prepare();

            //可能的话当显示层时，高亮当前值对应option
            if (v && self.menu) {
                var as = self.as;
                as.each(function(a) {
                    if (a.attr("data-value") == v) {
                        a.addClass(ke_menu_selected);
                    } else {
                        a.removeClass(ke_menu_selected);
                    }
                });
            }
        }
    });

    KE.Select = Select;
});KISSY.Editor.add("separator", function(editor) {
    editor.addPlugin(function() {
        new KISSY.Node('<span class="ke-toolbar-separator">&nbsp;</span>').appendTo(editor.toolBarDiv);
    });
});/**
 * smiley icon from wangwang for kissy editor
 * @author: yiminghe@gmail.com
 */
KISSY.Editor.add("smiley", function(editor) {
    var KE = KISSY.Editor,
        S = KISSY,
        DOM = S.DOM,
        Event = S.Event,
        Node = S.Node,
        Overlay = KE.SimpleOverlay,
        TripleButton = KE.TripleButton;
    if (!KE.Smiley) {
        (function() {

            DOM.addStyleSheet('.ke-smiley-sprite {'
                + ' background: url("http://a.tbcdn.cn/sys/wangwang/smiley/sprite.png") no-repeat scroll -1px 0 transparent;'
                + ' height: 235px;'
                + ' width: 288px;'
                + ' margin: 5px;'
                + 'zoom: 1;'
                + ' overflow: hidden;'
                + '}'
                + '.ke-smiley-sprite a {'
                + '   width: 24px;'
                + 'height: 24px;'
                + ' border: 1px solid white;'
                + ' float: left;'
                + '}'
                + '.ke-smiley-sprite a:hover {'
                + ' border: 1px solid #808080;'
                + '}'
                , "smiley");

            var smiley_markup = "<div class='ke-smiley-sprite'>";

            for (var i = 0; i <= 98; i++) {
                smiley_markup += "<a href='#' data-icon='http://a.tbcdn.cn/sys/wangwang/smiley/48x48/" + i + ".gif'></a>"
            }

            smiley_markup += "</div>";

            function Smiley(editor) {
                this.editor = editor;
                this._init();
            }

            S.augment(Smiley, {
                _init:function() {
                    var self = this,editor = self.editor;
                    self.el = new TripleButton({
                        //text:"smiley",
                        contentCls:"ke-toolbar-smiley",
                        title:"插入表情",
                        container:editor.toolBarDiv
                    });
                    self.el.on("offClick", this._show, this);
                    KE.Utils.lazyRun(this, "_prepare", "_real");
                    KE.Utils.sourceDisable(editor, self);
                },
                disable:function() {
                    this.el.set("state", TripleButton.DISABLED);
                },
                enable:function() {
                    this.el.set("state", TripleButton.OFF);
                },
                _hidePanel:function(ev) {
                    var self = this,
                        el = self.el.el,
                        t = ev.target,
                        smileyWin = self.smileyWin;
                    //当前按钮点击无效
                    if (el._4e_equals(t) || el._4e_contains(t)) {
                        return;
                    }
                    smileyWin.hide();
                },
                _selectSmiley:function(ev) {
                    ev.halt();
                    var self = this,editor = self.editor;
                    var t = ev.target,icon;
                    if (DOM._4e_name(t) == "a" && (icon = DOM.attr(t, "data-icon"))) {
                        var img = new Node("<img " +
                            "class='ke_smiley'" +
                            "alt='' src='" + icon + "'/>", null, editor.document);
                        editor.insertElement(img);
                        this.smileyWin.hide();
                    }
                },
                _prepare:function() {
                    var self = this,editor = self.editor;
                    this.smileyPanel = new Node(smiley_markup);
                    this.smileyWin = new Overlay({
                        el:this.smileyPanel,
                        width:"297px",
                        zIndex:editor.baseZIndex(KE.zIndexManager.POPUP_MENU),
                        focusMgr:false,
                        mask:false
                    });
                    this.smileyPanel.on("click", this._selectSmiley, this);
                    Event.on(document, "click", this._hidePanel, this);
                    Event.on(editor.document, "click", this._hidePanel, this);
                },
                _real:function() {
                    var xy = this.el.el.offset();
                    xy.top += this.el.el.height() + 5;
                    if (xy.left + this.smileyPanel.width() > DOM.viewportWidth() - 60) {
                        xy.left = DOM.viewportWidth() - this.smileyPanel.width() - 60;
                    }
                    this.smileyWin.show(xy);
                },
                _show:function(ev) {
                    var self = this,
                        smileyWin = self.smileyWin;
                    if (smileyWin && smileyWin.get("visible")) {
                        smileyWin.hide();
                    } else {
                        self._prepare(ev);
                    }
                }
            });
            KE.Smiley = Smiley;
        })();
    }
    editor.addPlugin(function() {
        new KE.Smiley(editor);
    });
});
/**
 * source editor for kissy editor
 * @author: yiminghe@gmail.com
 */
KISSY.Editor.add("sourcearea", function(editor) {
    var KE = KISSY.Editor,
        S = KISSY,
        UA = S.UA,
        TripleButton = KE.TripleButton;
    //firefox 3.5 不支持，有bug
    if (UA.gecko < 1.92) return;
    if (!KE.SourceArea) {
        (function() {
            var SOURCE_MODE = KE.SOURCE_MODE ,
                WYSIWYG_MODE = KE.WYSIWYG_MODE;

            function SourceArea(editor) {
                this.editor = editor;
                this._init();
            }

            S.augment(SourceArea, {
                _init:function() {
                    var self = this,editor = self.editor;
                    self.el = new TripleButton({
                        container:editor.toolBarDiv,
                        title:"源码",
                        contentCls:"ke-toolbar-source"
                    });
                    var el = self.el;
                    el.on("offClick", self._show, self);
                    el.on("onClick", self._hide, self);
                    editor.on("sourcemode", function() {
                        el.bon();
                    });
                    editor.on("wysiwygmode", function() {
                        el.boff();
                    });
                },
                _show:function() {
                    var self = this,
                        editor = self.editor;
                    editor.execCommand("sourceAreaSupport", SOURCE_MODE);
                    self.el.bon();
                },


                _hide:function() {
                    var self = this,
                        editor = self.editor,
                        el = self.el;
                    editor.execCommand("sourceAreaSupport", WYSIWYG_MODE);
                    el.boff();
                }
            });
            KE.SourceArea = SourceArea;
        })();
    }

    editor.addPlugin(function() {
        new KE.SourceArea(editor);
    });
});
/**
 * 切换源码与可视化模式的命令对象
 */
KISSY.Editor.add("sourcearea/support", function(editor) {
    var S = KISSY,
        KE = S.Editor,
        UA = S.UA;

    if (!KE.SourceAreaSupport) {
        (function() {
            var SOURCE_MODE = KE.SOURCE_MODE ,
                WYSIWYG_MODE = KE.WYSIWYG_MODE;

            function SourceAreaSupport() {
                var self = this;
                self.mapper = {};
                var m = self.mapper;
                m[SOURCE_MODE] = self._show;
                m[WYSIWYG_MODE] = self._hide;
            }

            S.augment(SourceAreaSupport, {
                exec:function(editor, mode) {
                    var m = this.mapper;
                    m[mode] && m[mode].call(this, editor);
                },

                _show:function(editor) {
                    var textarea = editor.textarea;
                    //还没等 textarea 隐掉就先获取
                    textarea.val(editor.getData(true));
                    this._showSource(editor);
                    editor.fire("sourcemode");
                },
                _showSource:function(editor) {
                    var textarea = editor.textarea,
                        iframe = editor.iframe;
                    textarea.css("display", "");
                    iframe.css("display", "none");
                    //ie textarea height:100%不起作用
                    if (UA.ie < 8) {
                        textarea.css("height", editor.wrap.css("height"));
                    }
                    //ie6 光标透出
                    textarea[0].focus();
                },
                _hideSource:function(editor) {
                    var textarea = editor.textarea,
                        iframe = editor.iframe;
                    iframe.css("display", "");
                    textarea.css("display", "none");
                },
                _hide:function(editor) {
                    var textarea = editor.textarea;
                    this._hideSource(editor);
                    //等 textarea 隐掉了再设置
                    //debugger
                    editor.fire("save");
                    editor.setData(textarea.val());

                    editor.fire("wysiwygmode");
                    //debugger
                    //在切换到可视模式后再进行，否则一旦wysiwygmode在最后，撤销又恢复为原来状态
                    editor.fire("save");

                    //firefox 光标激活，强迫刷新
                    if (UA.gecko) {
                        editor.activateGecko();
                    }
                }
            });
            KE.SourceAreaSupport = new SourceAreaSupport();
        })();
    }
    editor.addPlugin(function() {
        editor.addCommand("sourceAreaSupport", KE.SourceAreaSupport);
    });

});/**
 * table edit plugin for kissy editor
 * @author: yiminghe@gmail.com
 */
KISSY.Editor.add("table", function(editor, undefined) {

    var S = KISSY,
        KE = S.Editor,
        Node = S.Node,
        Walker = KE.Walker,
        UA = S.UA,
        KEN = KE.NODE,
        TripleButton = KE.TripleButton,
        ContextMenu = KE.ContextMenu,
        tableRules = ["tr","th","td","tbody","table"],
        trim = S.trim;

    /**
     * table 编辑模式下显示虚线边框便于编辑
     */
    var showBorderClassName = 'ke_show_border',
        cssStyleText,
        cssTemplate =
            // TODO: For IE6, we don't have child selector support,
            // where nested table cells could be incorrect.
            ( UA.ie === 6 ?
                [
                    'table.%2,',
                    'table.%2 td, table.%2 th,',
                    '{',
                    'border : #d3d3d3 1px dotted',
                    '}'
                ] :
                [
                    ' table.%2,',
                    ' table.%2 > tr > td,  table.%2 > tr > th,',
                    ' table.%2 > tbody > tr > td,  table.%2 > tbody > tr > th,',
                    ' table.%2 > thead > tr > td,  table.%2 > thead > tr > th,',
                    ' table.%2 > tfoot > tr > td,  table.%2 > tfoot > tr > th',
                    '{',
                    'border : #d3d3d3 1px dotted',
                    '}'
                ] ).join('');

    cssStyleText = cssTemplate.replace(/%2/g, showBorderClassName);
    var dataProcessor = editor.htmlDataProcessor,
        dataFilter = dataProcessor && dataProcessor.dataFilter,
        htmlFilter = dataProcessor && dataProcessor.htmlFilter;
    if (dataFilter) {
        dataFilter.addRules({
            elements :  {
                'table' : function(element) {
                    var attributes = element.attributes,
                        cssClass = attributes[ 'class' ],
                        border = parseInt(attributes.border, 10);

                    if (!border || border <= 0)
                        attributes[ 'class' ] = ( cssClass || '' ) + ' ' +
                            showBorderClassName;
                }
            }
        });
    }

    if (htmlFilter) {
        htmlFilter.addRules({
            elements :            {
                'table' : function(table) {
                    var attributes = table.attributes,
                        cssClass = attributes[ 'class' ];

                    if (cssClass) {
                        attributes[ 'class' ] =
                            trim(cssClass.replace(showBorderClassName, "").replace(/\s{2}/, " "));
                    }
                }

            }
        });
    }
    if (!KE.TableUI) {
        (function() {

            function TableUI(editor) {
                var self = this;
                self.editor = editor;
                editor._toolbars = editor._toolbars || {};
                editor._toolbars["table"] = self;
                self._init();
            }

            TableUI.showBorderClassName = showBorderClassName;


            S.augment(TableUI, {
                _init:function() {
                    var self = this,
                        editor = self.editor,
                        toolBarDiv = editor.toolBarDiv,
                        myContexts = {};
                    self.el = new TripleButton({
                        //text:"table",
                        contentCls:"ke-toolbar-table",
                        title:"插入表格",
                        container:toolBarDiv
                    });
                    var el = self.el;
                    el.on("offClick", self._tableShow, self);

                    for (var f in contextMenu) {
                        (function(f) {
                            myContexts[f] = function() {
                                editor.fire("save");
                                contextMenu[f](editor);
                                editor.fire("save");
                            }
                        })(f);
                    }
                    ContextMenu.register({
                        editor:editor,
                        rules:tableRules,
                        width:"120px",
                        funcs:myContexts
                    });

                    KE.Utils.sourceDisable(editor, self);
                },
                disable:function() {
                    this.el.disable();
                },
                enable:function() {
                    this.el.enable();
                },
                _tableShow:function(ev, selectedTable, td) {
                    var editor = this.editor;
                    editor.useDialog("table/dialog", function(dialog) {
                        dialog.show(selectedTable, td);
                    });
                }
            });


            var cellNodeRegex = /^(?:td|th)$/;

            function getSelectedCells(selection) {
                // Walker will try to split text nodes, which will make the current selection
                // invalid. So save bookmarks before doing anything.
                var bookmarks = selection.createBookmarks(),
                    ranges = selection.getRanges(),
                    retval = [],
                    database = {};

                function moveOutOfCellGuard(node) {
                    // Apply to the first cell only.
                    if (retval.length > 0)
                        return;

                    // If we are exiting from the first </td>, then the td should definitely be
                    // included.
                    if (node[0].nodeType == KEN.NODE_ELEMENT &&
                        cellNodeRegex.test(node._4e_name())
                        && !node._4e_getData('selected_cell')) {
                        node._4e_setMarker(database, 'selected_cell', true);
                        retval.push(node);
                    }
                }

                for (var i = 0; i < ranges.length; i++) {
                    var range = ranges[ i ];

                    if (range.collapsed) {
                        // Walker does not handle collapsed ranges yet - fall back to old API.
                        var startNode = range.getCommonAncestor(),
                            nearestCell = startNode._4e_ascendant('td', true) ||
                                startNode._4e_ascendant('th', true);
                        if (nearestCell)
                            retval.push(nearestCell);
                    } else {
                        var walker = new Walker(range),
                            node;
                        walker.guard = moveOutOfCellGuard;

                        while (( node = walker.next() )) {
                            // If may be possible for us to have a range like this:
                            // <td>^1</td><td>^2</td>
                            // The 2nd td shouldn't be included.
                            //
                            // So we have to take care to include a td we've entered only when we've
                            // walked into its children.

                            var parent = node.parent();
                            if (parent && cellNodeRegex.test(parent._4e_name()) &&
                                !parent._4e_getData('selected_cell')) {
                                parent._4e_setMarker(database, 'selected_cell', true);
                                retval.push(parent);
                            }
                        }
                    }
                }

                KE.Utils.clearAllMarkers(database);
                // Restore selection position.
                selection.selectBookmarks(bookmarks);

                return retval;
            }

            function clearRow($tr) {
                // Get the array of row's cells.
                var $cells = $tr.cells;
                // Empty all cells.
                for (var i = 0; i < $cells.length; i++) {
                    $cells[ i ].innerHTML = '';
                    if (!UA.ie)
                        ( new Node($cells[ i ]) )._4e_appendBogus();
                }
            }

            function insertRow(selection, insertBefore) {
                // Get the row where the selection is placed in.
                var row = selection.getStartElement()._4e_ascendant('tr');
                if (!row)
                    return;

                // Create a clone of the row.
                var newRow = row._4e_clone(true);
                // Insert the new row before of it.
                newRow.insertBefore(row);
                // Clean one of the rows to produce the illusion of
                // inserting an empty row
                // before or after.
                clearRow(insertBefore ? newRow[0] : row[0]);
            }

            function deleteRows(selectionOrRow) {
                if (selectionOrRow instanceof KE.Selection) {
                    var cells = getSelectedCells(selectionOrRow),
                        cellsCount = cells.length,
                        rowsToDelete = [],
                        cursorPosition,
                        previousRowIndex,
                        nextRowIndex;

                    // Queue up the rows - it's possible and
                    // likely that we have duplicates.
                    for (var i = 0; i < cellsCount; i++) {
                        var row = cells[ i ].parent(),
                            rowIndex = row[0].rowIndex;

                        !i && ( previousRowIndex = rowIndex - 1 );
                        rowsToDelete[ rowIndex ] = row;
                        i == cellsCount - 1 && ( nextRowIndex = rowIndex + 1 );
                    }

                    var table = row._4e_ascendant('table'),
                        rows = table[0].rows,
                        rowCount = rows.length;

                    // Where to put the cursor after rows been deleted?
                    // 1. Into next sibling row if any;
                    // 2. Into previous sibling row if any;
                    // 3. Into table's parent element if it's the very last row.
                    cursorPosition = new Node(
                        nextRowIndex < rowCount && table[0].rows[ nextRowIndex ] ||
                            previousRowIndex > 0 && table[0].rows[ previousRowIndex ] ||
                            table[0].parentNode);

                    for (i = rowsToDelete.length; i >= 0; i--) {
                        if (rowsToDelete[ i ])
                            deleteRows(rowsToDelete[ i ]);
                    }

                    return cursorPosition;
                }
                else if (selectionOrRow instanceof Node) {
                    table = selectionOrRow._4e_ascendant('table');

                    if (table[0].rows.length == 1)
                        table._4e_remove();
                    else
                        selectionOrRow._4e_remove();
                }

                return 0;
            }

            function insertColumn(selection, insertBefore) {
                // Get the cell where the selection is placed in.
                var startElement = selection.getStartElement(),
                    cell = startElement._4e_ascendant('td', true) ||
                        startElement._4e_ascendant('th', true);
                if (!cell)
                    return;
                // Get the cell's table.
                var table = cell._4e_ascendant('table'),
                    cellIndex = cell[0].cellIndex;
                // Loop through all rows available in the table.
                for (var i = 0; i < table[0].rows.length; i++) {
                    var $row = table[0].rows[ i ];
                    // If the row doesn't have enough cells, ignore it.
                    if ($row.cells.length < ( cellIndex + 1 ))
                        continue;
                    cell = new Node($row.cells[ cellIndex ].cloneNode(false));

                    if (!UA.ie)
                        cell._4e_appendBogus();
                    // Get back the currently selected cell.
                    var baseCell = new Node($row.cells[ cellIndex ]);
                    if (insertBefore)
                        cell.insertBefore(baseCell);
                    else
                        cell.insertAfter(baseCell);
                }
            }

            function getFocusElementAfterDelCols(cells) {
                var cellIndexList = [],
                    table = cells[ 0 ] && cells[ 0 ]._4e_ascendant('table'),
                    i,length,
                    targetIndex,targetCell;

                // get the cellIndex list of delete cells
                for (i = 0,length = cells.length; i < length; i++)
                    cellIndexList.push(cells[i][0].cellIndex);

                // get the focusable column index
                cellIndexList.sort();
                for (i = 1,length = cellIndexList.length;
                     i < length; i++) {
                    if (cellIndexList[ i ] - cellIndexList[ i - 1 ] > 1) {
                        targetIndex = cellIndexList[ i - 1 ] + 1;
                        break;
                    }
                }

                if (!targetIndex)
                    targetIndex = cellIndexList[ 0 ] > 0 ? ( cellIndexList[ 0 ] - 1 )
                        : ( cellIndexList[ cellIndexList.length - 1 ] + 1 );

                // scan row by row to get the target cell
                var rows = table[0].rows;
                for (i = 0,length = rows.length;
                     i < length; i++) {
                    targetCell = rows[ i ].cells[ targetIndex ];
                    if (targetCell)
                        break;
                }

                return targetCell ? new Node(targetCell) : table._4e_previous();
            }

            function deleteColumns(selectionOrCell) {
                if (selectionOrCell instanceof KE.Selection) {
                    var colsToDelete = getSelectedCells(selectionOrCell),
                        elementToFocus = getFocusElementAfterDelCols(colsToDelete);

                    for (var i = colsToDelete.length - 1; i >= 0; i--) {
                        //某一列已经删除？？这一列的cell再做？ !table判断处理
                        if (colsToDelete[ i ])
                            deleteColumns(colsToDelete[i]);
                    }

                    return elementToFocus;
                }
                else if (selectionOrCell instanceof Node) {
                    // Get the cell's table.
                    var table = selectionOrCell._4e_ascendant('table');

                    //该单元格所属的列已经被删除了
                    if (!table)
                        return null;

                    // Get the cell index.
                    var cellIndex = selectionOrCell[0].cellIndex;

                    /*
                     * Loop through all rows from down to up,
                     *  coz it's possible that some rows
                     * will be deleted.
                     */
                    for (i = table[0].rows.length - 1; i >= 0; i--) {
                        // Get the row.
                        var row = new Node(table[0].rows[ i ]);

                        // If the cell to be removed is the first one and
                        //  the row has just one cell.
                        if (!cellIndex && row[0].cells.length == 1) {
                            deleteRows(row);
                            continue;
                        }

                        // Else, just delete the cell.
                        if (row[0].cells[ cellIndex ])
                            row[0].removeChild(row[0].cells[ cellIndex ]);
                    }
                }

                return null;
            }

            function placeCursorInCell(cell, placeAtEnd) {
                var range = new KE.Range(cell[0].ownerDocument);
                if (!range['moveToElementEditablePosition'](cell,
                    placeAtEnd ? true : undefined)) {
                    range.selectNodeContents(cell);
                    range.collapse(placeAtEnd ? false : true);
                }
                range.select(true);
            }

            var contextMenu = {

                "表格属性" : function(editor) {
                    var selection = editor.getSelection(),
                        startElement = selection && selection.getStartElement(),
                        table = startElement && startElement._4e_ascendant('table', true);
                    if (!table)
                        return;
                    var tableUI = editor._toolbars["table"],
                        td = startElement._4e_ascendant(function(n) {
                            var name = n._4e_name();
                            return name == "td" || name == "th";
                        }, true);
                    //!TODO 修改单个 cell 的间距
                    tableUI._tableShow(null, table, td);
                },

                "删除表格" : function(editor) {
                    var selection = editor.getSelection(),
                        startElement = selection &&
                            selection.getStartElement(),
                        table = startElement &&
                            startElement._4e_ascendant('table', true);
                    if (!table)
                        return;
                    // Maintain the selection point at where the table was deleted.
                    selection.selectElement(table);
                    var range = selection.getRanges()[0];
                    range.collapse();
                    selection.selectRanges([ range ]);

                    // If the table's parent has only one child,
                    // remove it,except body,as well.( #5416 )
                    var parent = table.parent();
                    if (parent[0].childNodes.length == 1 &&
                        parent._4e_name() != 'body' &&
                        parent._4e_name() != 'td')
                        parent._4e_remove();
                    else
                        table._4e_remove();
                },

                '删除行 ': function(editor) {
                    var selection = editor.getSelection();
                    placeCursorInCell(deleteRows(selection), undefined);
                },

                '删除列 ' : function(editor) {
                    var selection = editor.getSelection(),
                        element = deleteColumns(selection);
                    element && placeCursorInCell(element, true);
                },

                '在上方插入行': function(editor) {
                    var selection = editor.getSelection();
                    insertRow(selection, true);
                },


                '在下方插入行' : function(editor) {
                    var selection = editor.getSelection();
                    insertRow(selection, undefined);
                },

                '在左侧插入列' : function(editor) {
                    var selection = editor.getSelection();
                    insertColumn(selection, true);
                },


                '在右侧插入列' : function(editor) {
                    var selection = editor.getSelection();
                    insertColumn(selection, undefined);
                }
            };

            KE.TableUI = TableUI;
        })();
    }
    editor.addPlugin(function() {
        new KE.TableUI(editor);
        /**
         * 动态加入显表格border css，便于编辑
         */
        editor.addCustomStyle(cssStyleText);
    });
});
/**
 * simple tabs ui component for kissy editor
 */
KISSY.Editor.add("tabs", function() {
    var S = KISSY,
        KE = S.Editor,
        DOM = S.DOM,
        Node = S.Node,
        LI = "li",
        DIV = "div",
        REL = "rel",
        SELECTED = "ke-tab-selected";
    if (KE.Tabs) return;

    function Tabs(cfg) {
        this.cfg = cfg;
        this._init();
    }

    S.augment(Tabs, S.EventTarget, {
        _init:function() {
            var self = this,
                cfg = self.cfg,
                tabs = cfg.tabs,
                contents = cfg.contents,
                divs = contents.children(DIV),
                lis = tabs.children(LI);

            tabs.on("click", function(ev) {
                var li = new Node(ev.target);
                if (li = li._4e_ascendant(function(n) {
                    return n._4e_name() === LI && tabs._4e_contains(n);
                }, true)) {
                    lis.removeClass(SELECTED);
                    var rel = li.attr(REL);
                    li.addClass(SELECTED);
                    divs.hide();
                    DOM.show(divs[S.indexOf(li[0], lis)]);
                    self.fire(rel);
                }
            });
        },
        getTab:function(n) {
            var self = this,
                cfg = self.cfg,
                tabs = cfg.tabs,
                contents = cfg.contents,
                divs = contents.children(DIV),
                lis = tabs.children(LI);
            for (var i = 0; i < lis.length; i++) {
                var li = new Node(lis[i]),
                    div = new Node(divs[i]);
                if (S.isNumber(n) && n == i
                    ||
                    S.isString(n) && n == li.attr(REL)
                    ) {
                    return {
                        tab:li,
                        content:div
                    };
                }
            }
        },
        remove:function(n) {
            var info = this.getTab(n);
            info.tab.remove();
            info.content.remove();
        },
        _getActivate:function() {
            var self = this,
                cfg = self.cfg,
                tabs = cfg.tabs,
                contents = cfg.contents, divs = contents.children(DIV),
                lis = tabs.children(LI);
            for (var i = 0; i < lis.length; i++) {
                var li = new Node(lis[i]);
                if (li.hasClass(SELECTED)) return li.attr(REL);
            }
        },
        activate:function(n) {
            if (arguments.length == 0) return this._getActivate();
            var self = this,
                cfg = self.cfg,
                tabs = cfg.tabs,
                contents = cfg.contents,
                divs = contents.children(DIV),
                lis = tabs.children(LI);
            lis.removeClass(SELECTED);
            divs.hide();
            var info = this.getTab(n);
            info.tab.addClass(SELECTED);
            info.content.show();
        }
    });

    KE.Tabs = Tabs;
});/**
 * templates support for kissy editor
 * @author: yiminghe@gmail.com
 */
KISSY.Editor.add("templates", function(editor) {
    var KE = KISSY.Editor,
        S = KISSY,
        Node = S.Node,
        //Event = S.Event,
        //KEN = KE.NODE,
        //UA = S.UA,
        DOM = S.DOM,
        TripleButton = KE.TripleButton,
        Overlay = KE.SimpleOverlay;

    if (!KE.TplUI) {

        (function() {
            DOM.addStyleSheet(
                ".ke-tpl {" +
                    "    border: 2px solid #EEEEEE;" +
                    "    width: 95%;" +
                    "    margin: 20px auto 0 auto;" +
                    "}" +

                    ".ke-tpl-list {" +
                    "    border: 1px solid #EEEEEE;" +
                    "    margin: 5px;" +
                    "    padding: 7px;" +
                    "    display: block;" +
                    "    text-decoration: none;" +
                    "    zoom: 1;" +
                    "}" +

                    ".ke-tpl-list:hover, .ke-tpl-selected {" +
                    "    background-color: #FFFACD;" +
                    "    text-decoration: none;" +
                    "    border: 1px solid #FF9933;" +
                    "}"
                , "ke-templates");


            function TplUI(editor) {
                this.editor = editor;
                this._init();
            }

            S.augment(TplUI, {
                _init:function() {
                    var self = this,editor = self.editor,el = new TripleButton({
                        container:editor.toolBarDiv,
                        //text:"template",
                        contentCls:"ke-toolbar-template",
                        title:"模板"
                    });
                    el.on("offClick", self._show, self);
                    KE.Utils.lazyRun(this, "_prepare", "_real");
                    self.el = el;
                    KE.Utils.sourceDisable(editor, self);
                },
                disable:function() {
                    this.el.set("state", TripleButton.DISABLED);
                },
                enable:function() {
                    this.el.set("state", TripleButton.OFF);
                },
                _prepare:function() {
                    var self = this,editor = self.editor,templates = editor.cfg.pluginConfig.templates || [];
                    var HTML = "<div class='ke-tpl'>";

                    for (var i = 0; i < templates.length; i++) {
                        var t = templates[i];
                        HTML += "<a href='javascript:void(0)' class='ke-tpl-list' tabIndex='-1'>" + t.demo + "</a>";
                    }
                    HTML += "</div>";

                    this._initDialogOk = true;
                    var ui = new Overlay({mask:true,title:"内容模板"});
                    ui.body.html(HTML);
                    var list = ui.body.all(".ke-tpl-list");
                    list.on("click", function(ev) {
                        ev.halt();
                        var t = new Node(ev.target);
                        var index = t._4e_index();
                        if (index != -1) {
                            editor.insertHtml(templates[index].html);
                        }
                        ui.hide();
                    });
                    self.ui = ui;
                },
                _real:function() {
                    var self = this;
                    self.ui.show();
                },
                _show:function() {
                    var self = this;
                    self._prepare();
                }
            });
            KE.TplUI = TplUI;
        })();
    }
    editor.addPlugin(function() {
        new KE.TplUI(editor);

    });

});
/**
 * undo,redo manager for kissy editor
 * @author: yiminghe@gmail.com
 */
KISSY.Editor.add("undo", function(editor) {
    var S = KISSY,
        KE = S.Editor,
        arrayCompare = KE.Utils.arrayCompare,
        UA = S.UA,
        Event = S.Event,
        LIMIT = 30;
    if (!KE.UndoManager) {
        (function() {
            /**
             * 当前编辑区域状态，包括 html 与选择区域(光标位置)
             * @param editor
             */
            function Snapshot(editor) {
                var contents = editor._getRawData(),
                    self = this,
                    selection = contents && editor.getSelection();
                //内容html
                self.contents = contents;
                //选择区域书签标志
                self.bookmarks = selection && selection.createBookmarks2(true);
            }


            S.augment(Snapshot, {
                /**
                 * 编辑状态间是否相等
                 * @param otherImage
                 */
                equals:function(otherImage) {
                    var self = this,
                        thisContents = self.contents,
                        otherContents = otherImage.contents;
                    if (thisContents != otherContents)
                        return false;
                    var bookmarksA = self.bookmarks,
                        bookmarksB = otherImage.bookmarks;

                    if (bookmarksA || bookmarksB) {
                        if (!bookmarksA || !bookmarksB || bookmarksA.length != bookmarksB.length)
                            return false;

                        for (var i = 0; i < bookmarksA.length; i++) {
                            var bookmarkA = bookmarksA[ i ],
                                bookmarkB = bookmarksB[ i ];

                            if (
                                bookmarkA.startOffset != bookmarkB.startOffset ||
                                    bookmarkA.endOffset != bookmarkB.endOffset ||
                                    !arrayCompare(bookmarkA.start, bookmarkB.start) ||
                                    !arrayCompare(bookmarkA.end, bookmarkB.end)) {
                                return false;
                            }
                        }
                    }

                    return true;
                }
            });

            /**
             * 通过编辑器的save与restore事件，编辑器实例的历史栈管理，与键盘监控
             * @param editor
             */
            function UndoManager(editor) {
                //redo undo history stack
                /**
                 * 编辑器状态历史保存
                 */
                var self = this;
                self.history = [];
                //当前所处状态对应的历史栈内下标
                self.index = -1;
                self.editor = editor;
                //键盘输入做延迟处理
                self.bufferRunner = KE.Utils.buffer(self.save, self, 500);
                self._init();
            }

            var editingKeyCodes = { /*Backspace*/ 8:1, /*Delete*/ 46:1 },
                modifierKeyCodes = { /*Shift*/ 16:1, /*Ctrl*/ 17:1, /*Alt*/ 18:1 },
                navigationKeyCodes = { 37:1, 38:1, 39:1, 40:1,33:1,34:1 },// Arrows: L, T, R, B
                zKeyCode = 90,
                yKeyCode = 89;


            S.augment(UndoManager, {
                /**
                 * 监控键盘输入，buffer处理
                 */
                _keyMonitor:function() {
                    var self = this,
                        editor = self.editor,
                        doc = editor.document;
                    //也要监控源码下的按键，便于实时统计
                    Event.on([doc,editor.textarea], "keydown", function(ev) {
                        var keycode = ev.keyCode;
                        if (keycode in navigationKeyCodes
                            || keycode in modifierKeyCodes
                            )
                            return;
                        //ctrl+z，撤销
                        if (keycode === zKeyCode && (ev.ctrlKey || ev.metaKey)) {
                            editor.fire("restore", {d:-1});
                            ev.halt();
                            return;
                        }
                        //ctrl+y，重做
                        if (keycode === yKeyCode && (ev.ctrlKey || ev.metaKey)) {
                            editor.fire("restore", {d:1});
                            ev.halt();
                            return;
                        }
                        editor.fire("save", {buffer:1});
                    });
                },

                _init:function() {
                    var self = this,
                        editor = self.editor;
                    //外部通过editor触发save|restore,管理器捕获事件处理
                    editor.on("save", function(ev) {
                        //代码模式下不和可视模式下混在一起
                        if (editor.getMode() != KE.WYSIWYG_MODE) return;
                        if (ev.buffer) {
                            //键盘操作需要缓存
                            self.bufferRunner();
                        } else {
                            //其他立即save
                            self.save();
                        }
                    });
                    editor.on("restore", function(ev) {
                        //代码模式下不和可视模式下混在一起
                        if (editor.getMode() != KE.WYSIWYG_MODE) return;
                        self.restore(ev);
                    });

                    self._keyMonitor();
                    //先save一下,why??
                    //0913:初始状态保存，放在use回调中
                    //self.save();
                },

                /**
                 * 保存历史
                 */
                save:function() {
                    var self = this,
                        history = self.history,
                        index = self.index;
                    //debugger
                    //前面的历史抛弃
                    if (history.length > index + 1)
                        history.splice(index + 1, history.length - index - 1);

                    var editor = self.editor,
                        last = history[history.length - 1],
                        current = new Snapshot(editor);

                    if (!last || !last.equals(current)) {
                        if (history.length === LIMIT) {
                            history.shift();
                        }
                        history.push(current);
                        self.index = index = history.length - 1;
                        editor.fire("afterSave", {history:history,index:index});
                    }
                },

                /**
                 *
                 * @param ev
                 * ev.d ：1.向前撤销 ，-1.向后重做
                 */
                restore:function(ev) {

                    var d = ev.d,
                        self = this,
                        history = self.history,
                        editor = self.editor,
                        snapshot = history[self.index + d];
                    if (snapshot) {
                        editor._setRawData(snapshot.contents);
                        if (snapshot.bookmarks)
                            editor.getSelection().selectBookmarks(snapshot.bookmarks);
                        else if (UA.ie) {
                            // IE BUG: If I don't set the selection to *somewhere* after setting
                            // document contents, then IE would create an empty paragraph at the bottom
                            // the next time the document is modified.
                            var $range = editor.document.body.createTextRange();
                            $range.collapse(true);
                            $range.select();
                        }
                        var selection = editor.getSelection();
                        //将当前光标，选择区域滚动到可视区域
                        if (selection) {
                            selection.scrollIntoView();
                        }
                        self.index += d;
                        editor.fire("afterRestore", {
                            history:history,
                            index:self.index
                        });
                        editor.notifySelectionChange();
                    }
                }
            });


            var TripleButton = KE.TripleButton,RedoMap = {
                "redo":1,
                "undo":-1
            };

            /**
             * 工具栏重做与撤销的ui功能
             * @param editor
             * @param text
             */
            function RestoreUI(editor, text, title, contentCls) {
                var self = this;
                self.editor = editor;
                self.title = title;
                self.text = text;
                self.contentCls = contentCls;
                self._init();
            }

            S.augment(RestoreUI, {
                _init:function() {
                    var self = this,
                        editor = self.editor;

                    self.el = new TripleButton({
                        contentCls:self.contentCls,
                        title:self.title,
                        container:editor.toolBarDiv
                    });
                    var el = self.el;
                    el.set("state", TripleButton.DISABLED);
                    /**
                     * save,restore完，更新工具栏状态
                     */
                    editor.on("afterSave afterRestore", self._respond, self);

                    /**
                     * 触发重做或撤销动作，都是restore，方向不同
                     */
                    el.on("offClick", function() {
                        editor.fire("restore", {
                            d:RedoMap[self.text]
                        });
                    });
                    KE.Utils.sourceDisable(editor, self);
                },
                disable:function() {
                    this._saveState = this.el.get("state");
                    this.el.set("state", TripleButton.DISABLED);
                },
                enable:function() {
                    this.el.set("state", this._saveState);
                },

                _respond:function(ev) {
                    this.updateUI(ev.history, ev.index);
                },

                updateUI:function(history, index) {
                    var self = this,
                        el = self.el,
                        text = self.text;
                    if (text == "undo") {
                        //有状态可退
                        if (index > 0) {
                            el.set("state", TripleButton.OFF);
                        } else {
                            el.set("state", TripleButton.DISABLED);
                        }
                    } else if (text == "redo") {
                        //有状态可前进
                        if (index < history.length - 1) {
                            el.set("state", TripleButton.OFF);
                        } else {
                            el.set("state", TripleButton.DISABLED);
                        }
                    }
                }
            });
            KE.UndoManager = UndoManager;
            KE.RestoreUI = RestoreUI;
        })();
    }

    editor.addPlugin(function() {

        /**
         * 编辑器历史中央管理
         */
        new KE.UndoManager(editor);

        /**
         * 撤销工具栏按钮
         */
        new KE.RestoreUI(editor, "undo", "撤销", "ke-toolbar-undo");
        /**
         * 重做工具栏按钮
         */
        new KE.RestoreUI(editor, "redo", "重做", "ke-toolbar-redo");
    });


});