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
});