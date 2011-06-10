KISSY.Editor.add("table/dialog", function(editor) {
    var S = KISSY,
        KE = S.Editor,
        Node = S.Node,
        DOM = S.DOM,
        UA = S.UA,
        trim = S.trim,
        TableUI = KE.TableUI,
        showBorderClassName = TableUI.showBorderClassName,
        Overlay = KE.SimpleOverlay,
        IN_SIZE = 6,
        alignStyle = 'margin-left:2px;',
        MIDDLE = "vertical-align:middle;",
        TABLE_HTML = "<div style='padding:20px 20px 10px 20px;'>" +
            "<table class='ke-table-config' style='width:100%'>" +
            "<tr>" +
            "<td>" +
            "<label>行数： " +
            "<input " +
            " data-verify='^(?!0$)\\d+$' " +
            " data-warning='行数请输入正整数' " +
            " value='2' " +
            " class='ke-table-rows ke-table-create-only ke-input' " +
            "style='" + alignStyle + MIDDLE + "'" +
            " size='" +
            IN_SIZE +
            "'" +
            " />" +
            "</label>" +
            "</td>" +
            "<td>" +
            "<label>宽&nbsp;&nbsp;&nbsp;度： " +
            "<input " +
            " data-verify='^(?!0$)\\d+$' " +
            " data-warning='宽度请输入正整数' " +
            "value='200' " +
            "style='" +
            alignStyle + MIDDLE + "' " +
            "class='ke-table-width ke-input' " +
            "size='" + IN_SIZE + "'/>" +
            "</label> " +
            "<select class='ke-table-width-unit'>" +
            "<option value='px'>像素</option>" +
            "<option value='%'>百分比</option>" +
            "</select>" +
            "</td>" +
            "</tr>" +
            "<tr>" +
            "<td>" +
            "<label>列数： " +
            "<input " +
            " data-verify='^(?!0$)\\d+$' " +
            " data-warning='列数请输入正整数' " +
            "class='ke-table-cols ke-table-create-only ke-input' " +
            "style='" + alignStyle + MIDDLE + "'" +
            "value='3' " +
            "size='" +
            IN_SIZE + "'/>" +
            "</label>" +
            "</td>" +
            "<td>" +
            "<label>高&nbsp;&nbsp;&nbsp;度： " +
            "<input " +
            " data-verify='^((?!0$)\\d+)?$' " +
            " data-warning='高度请输入正整数' " +
            "value='' " +
            "style='" +
            alignStyle + MIDDLE + "'" +
            "class='ke-table-height ke-input' " +
            "size='" + IN_SIZE + "'/>" +
            "</label> &nbsp;像素" +
            "</td>" +
            "</tr>" +
            "<tr>" +
            "<td>" +
            "<label>对齐： " +
            "<select class='ke-table-align'>" +
            "<option value=''>无</option>" +
            "<option value='left'>左对齐</option>" +
            "<option value='right'>右对齐</option>" +
            "<option value='center'>中间对齐</option>" +
            "</select>" +
            "</label>" +
            "</td>" +
            "<td>" +
            "<label>标题格： " +
            "<select class='ke-table-head ke-table-create-only'>" +
            "<option value=''>无</option>" +
            "<option value='1'>有</option>" +
            "</select>" +
            "</td>" +
            "</tr>" +
            "<tr>" +
            "<td>" +
            "<label>边框： " +
            "<input " +
            " data-verify='^\\d+$' " +
            " data-warning='边框请输入非负整数' " +
            "value='1' " +
            "style='" +
            alignStyle + MIDDLE + "'" +
            "class='ke-table-border ke-input' " +
            "size='" + IN_SIZE + "'/>" +
            "</label> &nbsp;像素" +
            "</td>" +
            "<td>" +
            "<label " +
            "class='ke-table-cellpadding-holder'" +
            ">边&nbsp;&nbsp;&nbsp;距： " +
            "<input " +
            " data-verify='^(\\d+)?$' " +
            " data-warning='边框请输入非负整数' " +
            "value='0' " +
            "style='" +
            alignStyle + MIDDLE + "'" +
            "class='ke-table-cellpadding ke-input' " +
            "size='" + IN_SIZE + "'/>" +
            " &nbsp;像素</label>" +
            "</td>" +
            "</tr>" +
            "<tr>" +
            "<td colspan='2'>" +
            "<label>" +
            "标题： " +
            "<input " +
            "class='ke-table-caption ke-input' " +
            "style='width:320px;" +
            alignStyle + MIDDLE + "'>" +
            "</label>" +
            "</td>" +
            "</tr>" +
            "</table>" +
            "</div>",
        footHtml = "<a " +
            "class='ke-table-ok ke-button' " +
            "style='margin-right:20px;'>确定</a> " +
            "<a " +
            "class='ke-table-cancel ke-button'>取消</a>";

    if (!TableUI.Dialog) {
        (function() {

            function valid(str) {
                return trim(str).length != 0;
            }

            function TableUIDialog(editor) {
                var self = this;
                self.editor = editor;
                KE.Utils.lazyRun(self, "_prepareTableShow", "_realTableShow");
            }

            TableUI.Dialog = TableUIDialog;

            S.augment(TableUIDialog, {
                _tableInit:function() {
                    var self = this,
                        editor = self.editor,
                        d = new Overlay({
                            width:"430px",
                            mask:true,
                            title:"表格"//属性"
                        }),
                        body = d.body;
                    d.body.html(TABLE_HTML);
                    d.foot.html(footHtml);
                    var dbody = d.body;
                    d.twidth = dbody.one(".ke-table-width");
                    d.theight = dbody.one(".ke-table-height");
                    d.tborder = dbody.one(".ke-table-border");
                    d.tcaption = dbody.one(".ke-table-caption");
                    d.talign = KE.Select.decorate(dbody.one(".ke-table-align"));
                    d.trows = dbody.one(".ke-table-rows");
                    d.tcols = dbody.one(".ke-table-cols");
                    d.thead = KE.Select.decorate(dbody.one(".ke-table-head"));
                    d.cellpaddingHolder = dbody.one(".ke-table-cellpadding-holder");
                    d.cellpadding = dbody.one(".ke-table-cellpadding");
                    var tok = d.foot.one(".ke-table-ok"),
                        tclose = d.foot.one(".ke-table-cancel");
                    d.twidthunit = KE.Select.decorate(dbody.one(".ke-table-width-unit"));
                    self.tableDialog = d;
                    tok.on("click", self._tableOk, self);
                    d.on("hide", function() {
                        //清空
                        self.selectedTable = null;
                    });
                    tclose.on("click", function() {
                        d.hide();
                    });
                },
                _tableOk:function() {
                    var self = this,
                        tableDialog = self.tableDialog,
                        inputs = tableDialog.el.all("input");

                    if (tableDialog.twidthunit.val() == "%") {
                        var tw = parseInt(tableDialog.twidth.val());
                        if (
                            !tw || (
                                tw > 100 ||
                                    tw < 0
                                )
                            ) {
                            alert("宽度百分比：" + "请输入1-100之间");
                            return;
                        }
                    }
                    var re = KE.Utils.verifyInputs(inputs);
                    if (!re) return;


                    if (!self.selectedTable) {
                        self._genTable();
                    } else {
                        self._modifyTable();
                    }
                },
                _modifyTable:function() {
                    var self = this,
                        d = self.tableDialog,
                        selectedTable = self.selectedTable,
                        caption = selectedTable.one("caption"),
                        talignVal = d.talign.val(),
                        tborderVal = d.tborder.val();

                    if (valid(talignVal))
                        selectedTable.attr("align", trim(talignVal));
                    else
                        selectedTable.removeAttr("align");

                    if (valid(tborderVal)) {
                        selectedTable.attr("border", trim(tborderVal));
                    } else {
                        selectedTable.removeAttr("border");
                    }
                    if (!valid(tborderVal) || tborderVal == "0") {
                        selectedTable.addClass(showBorderClassName);
                    } else {
                        selectedTable.removeClass(showBorderClassName);
                    }

                    if (valid(d.twidth.val()))
                        selectedTable.css("width",
                            trim(d.twidth.val()) + d.twidthunit.val());
                    else
                        selectedTable.css("width", "");
                    if (valid(d.theight.val()))
                        selectedTable.css("height",
                            trim(d.theight.val()));
                    else
                        selectedTable.css("height", "");

                    d.cellpadding.val(parseInt(d.cellpadding.val()) || 0);
                    if (self.selectedTd)self.selectedTd.css("padding", d.cellpadding.val());
                    if (valid(d.tcaption.val())) {
                        var tcv = KE.Utils.htmlEncode(trim(d.tcaption.val()));
                        if (caption && caption[0])
                            caption.html(tcv);
                        else {
                            //不能使用dom操作了, ie6 table 报错
                            //http://msdn.microsoft.com/en-us/library/ms532998(VS.85).aspx
                            var c = selectedTable[0].createCaption();
                            DOM.html(c, "<span>"
                                + tcv
                                + "</span>");
                            // new Node("<caption><span>" + tcv + "</span></caption>");
                            // .insertBefore(selectedTable[0].firstChild);
                        }
                    } else if (caption) {
                        caption._4e_remove();
                    }
                    d.hide();
                },
                _genTable:function() {
                    var self = this,
                        d = self.tableDialog,
                        html = "<table ",
                        i,
                        cols = parseInt(d.tcols.val()) || 1,
                        rows = parseInt(d.trows.val()) || 1,
                        //firefox 需要 br 才能得以放置焦点
                        cellpad = UA.ie ? "" : "<br/>",
                        editor = self.editor;

                    if (valid(d.talign.val()))
                        html += "align='" + trim(d.talign.val()) + "' ";
                    //if (S.trim(d.tcellspacing.val()).length != 0)
                    //    html += "cellspacing='" + S.trim(d.tcellspacing.val()) + "' ";
                    //if (S.trim(d.tcellpadding.val()).length != 0)
                    //    html += "cellpadding='" + S.trim(d.tcellpadding.val()) + "' ";
                    if (valid(d.tborder.val()))
                        html += "border='" + trim(d.tborder.val()) + "' ";
                    if (valid(d.twidth.val()) || valid(d.theight.val())) {
                        html += "style='";
                        if (valid(d.twidth.val())) {
                            html += "width:" + trim(d.twidth.val())
                                + d.twidthunit.val() + ";"
                        }
                        if (valid(d.theight.val())) {
                            html += "height:" + trim(d.theight.val()) + "px;"
                        }
                        html += "' "
                    }
                    if (!valid(d.tborder.val()) || trim(d.tborder.val()) == "0") {
                        html += "class='" + showBorderClassName + "' "
                    }

                    html += ">";
                    if (valid(d.tcaption.val())) {
                        html += "<caption><span>" + KE.Utils.htmlEncode(trim(d.tcaption.val()))
                            + "</span></caption>";
                    }
                    if (d.thead.val()) {
                        html += "<thead>";
                        html += "<tr>";
                        for (i = 0; i < cols; i++)
                            html += "<th>" + cellpad + "</th>";
                        html += "</tr>";
                        html += "</thead>";
                        rows -= 1;
                    }

                    html += "<tbody>";
                    for (var r = 0; r < rows; r++) {
                        html += "<tr>";
                        for (i = 0; i < cols; i++) {
                            html += "<td>" + cellpad + "</td>";
                        }
                        html += "</tr>";
                    }
                    html += "</tbody>";
                    html += "</table>";

                    var table = new Node(html, null, editor.document);
                    editor.insertElement(table);
                    d.hide();

                },
                _fillTableDialog:function() {
                    var self = this,
                        d = self.tableDialog,
                        selectedTable = self.selectedTable,
                        caption = selectedTable.one("caption");
                    if (self.selectedTd) {
                        d.cellpadding.val(
                            parseInt(self.selectedTd.css("padding"))
                                || "0"
                            );
                    }

                    d.talign.val(selectedTable.attr("align") ||
                        "");


                    d.tborder.val(selectedTable.attr("border") ||
                        "0");
                    var w = selectedTable._4e_style("width") ||
                        ("" + selectedTable.width());

                    //忽略pt单位
                    d.twidth.val(w.replace(/px|%|(.*pt)/i, ""));
                    if (w.indexOf("%") != -1) d.twidthunit.val("%");
                    else d.twidthunit.val("px");

                    d.theight.val((selectedTable._4e_style("height") || "")
                        .replace(/px|%/i, ""));
                    var c = "";
                    if (caption) {
                        c = caption.text();
                    }
                    d.tcaption.val(c);
                    var head = selectedTable._4e_first(function(n) {
                        return n._4e_name() == "thead";
                    });
                    d.trows.val(selectedTable.one("tbody").children().length +
                        (head ? head.children("tr").length : 0));
                    d.tcols.val(selectedTable.one("tr").children().length);
                    d.thead.val(head ? '1' : '');
                },
                _realTableShow:function() {
                    var self = this,
                        d = self.tableDialog;

                    if (self.selectedTable) {
                        self._fillTableDialog();
                        d.body
                            .all(".ke-table-create-only")
                            .attr("disabled", "disabled");
                        d.thead.disable();
                    } else {
                        d.body.all(".ke-table-create-only")
                            .removeAttr("disabled");
                        d.thead.enable();
                    }
                    if (self.selectedTd) {
                        d.cellpaddingHolder.show();
                    } else {
                        d.cellpaddingHolder.hide();
                    }
                    self.tableDialog.show();
                },
                _prepareTableShow:function() {
                    var self = this;
                    self._tableInit();
                },
                show:    function(selectedTable, td) {
                    var self = this;
                    self.selectedTable = selectedTable;
                    self.selectedTd = td;
                    self._prepareTableShow();
                }
            });

        })();
    }


    editor.addDialog("table/dialog", new TableUI.Dialog(editor));
});