/**
 * Created by lilf on 2015/4/7.
 */


//TODO 尝试返回选择堆栈
(function ($, window, document, undefined) {

    var id = "listselect-1-0";

    var div = "<div  class='listselect-mask' style='text-align:center;position: absolute;top: 0; left: 0; width: 100%; height: 100%; z-index:1001;'></div>" +
        "<div id='" + id + "' class='listselect' style='position: absolute;z-index:1002;top: 0; left: 0; width: 100%; height: 100%; '>" +
        "<div class='listselect-container' style='margin-left: auto; margin-right: auto; '>" +
        "<div class='listselect-close' style='float: right;cursor:pointer;'></div>" +
        "<div class='listselect-info' style='float: left;'></div>" +
        "<br style='clear: both;'>" +
        "</div>" +
        "</div>";

    /*
     构造函数
     **/
    var _listselect = function (ele, opt) {

        if (ele.length == 0) {
            throw new Error("Can't init with null object!")
        }

        this.input = ele;
        this.value = null;

        this.stack = [];

        this.defaults = {
            sort: true,            //是否对数据排序
            hyphen: '',          //连接符
            all: true,               //是否显示“全部”按钮
            selected: null       //选择事件，选定值后触发
        };

        this.options = $.extend({}, this.defaults, opt);
    };

    //方法
    _listselect.prototype = {

        init: function () {
            var pl = this;

            //对数据进行排序
            if (this.options.sort && this.options.data.length > 0) {
                this.options.data.sort(function (a, b) {
                    return a.text.localeCompare(b.text);
                });
            }

            //显示控件
            this.input.click(function () {
                pl.show(1);
            });

            //控件id
            var input_id = this.input[0].id;
            //控件name
            var input_name = this.input[0].name;

            if (input_name != undefined) {
                this.input[0].id = input_id + "_text";
                this.input[0].name = input_name + "_text";
                $(this.input[0].parentNode).append("<input name='" + input_name + "' id='" + input_id + "' type='hidden'>");
            }

            this.value = $("#" + input_name);

            return this;
        },

        show: function (level, parent) {

            var data = this.options.data;
            var pl = this;

            pl.hide();

            $("body").append(div);

            var select = $("#" + id);

            select.hide();
            select.fadeIn();

            var top = $(document).scrollTop();

            var mask_height = Math.max(window.screen.height, $(document.body).height());


            $(".listselect-mask").height(mask_height);

            //显示当前选中的信息
            var str = "";
            for (o in pl.stack) {
                str = pl.stack[o].text + pl.options.hyphen + str;
            }
            str = str.substring(0, str.length - this.options.hyphen.length);
            $(".listselect-info").html(str);


            select.click(function () {
                pl.cancel();
            });

            $("#" + id + " .listselect-container").click(function (e) {
                //终止冒泡的方法
                e.stopPropagation();
            });

            select.css("top", top);

            if (!!data) {
                for (var i = 0; i < data.length; i++) {

                    if (data[i].level == level && ( !parent || data[i].parent == parent || data[i].parent == "0")) {
                        $("#" + id + " .listselect-container").append("<div class='listselect-item' style='float:left;cursor:pointer;' id='" + data[i].id + "' level='" + data[i].level + "' parent='" + data[i].parent + "'>" + data[i].text + "</div>");
                    }
                }

                $("#" + id + " .listselect-container").append("<div style='clear:both; height:20px; width: 100%;'></div>");

                var html = "<div class='listselect-button cancel' style='cursor:pointer;'>取消</div>";

                if (this.options.all)
                    html = html + "<div class='listselect-button apply' style='cursor:pointer;'>全部</div>";

                $("#" + id + " .listselect-container").append(html);

                if (level > 1)
                    $("#" + id + " .listselect-container").append("<div class='listselect-button last' style='cursor:pointer;'>上一步</div>");


                $("#" + id + " .listselect-container").append("<br style='clear:both;'>");

                if ($(".listselect-item").length == 0) {
                    pl.apply();
                }
            }

            $(".listselect-item").click(function () {

                var id = $(this).attr("id");
                var text = $(this).html();
                var level = $(this).attr("level");
                var parent = $(this).attr("parent");

                var item = {id: id, text: text, level: level, parent: parent};

                pl.stack.unshift(item);

                pl.show(parseInt(level) + 1, $(this).attr("id"));
            });

            $(".listselect-container .cancel").click(function () {
                pl.cancel();
            });

            $(".listselect-container .apply").click(function () {
                pl.apply();
            });

            $(".listselect-container .last").click(function () {

                var o = pl.stack[0];
                pl.stack.splice(0, 1);
                pl.show(o.level, o.parent);
            });

            $(".listselect-close").click(function () {
                pl.cancel();
            });
        },

        //隐藏图层，只隐藏，不返回数据
        hide: function () {

            //删除界面
            var select = $("#" + id);
            select.remove();

            //删除遮罩
            $(".listselect-mask").fadeOut(100, function () {
                $(this).remove();
            });
        },

        //关闭，返回数据
        apply: function () {
            var text = "";
            var o;
            var v;

            this.value.val("");
            this.input.val("");

            //遍历堆栈 获得选择的值和文字
            if (this.stack.length > 0) {
                while (o = this.stack.pop()) {
                    text += o.text + this.options.hyphen;       //多级文字连接，hyphen是连接符
                    //设置hidden值
                    this.value.val(o.id);
                    v = o;
                }

                //删除最后一个多余的连接符，如果没有连接符，则什么都不做
                text = text.substring(0, text.length - this.options.hyphen.length);

                this.input.val(text);

                this.stack = [];
            }

            this.hide();

            //如果有selecetd事件，执行事件
            if (typeof this.options.selected == "function") {
                var e = {
                    text: text,
                    value: v.id
                };
                this.options.selected(e);
            }
        },

        cancel: function () {
            this.stack = []; //清空堆栈
            this.hide();        //隐藏界面
        }
    };

    //添加到jquery
    $.fn.listselect = function (options) {
        var listselect = new _listselect(this, options);

        return listselect.init();
    };


})(jQuery, window, document);
