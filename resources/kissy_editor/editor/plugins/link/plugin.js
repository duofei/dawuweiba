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
});