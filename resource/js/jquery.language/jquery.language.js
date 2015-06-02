/*
 * jq 多语言支持库
 * */
(function ($, window, document, undefined) {

    $.prototype.easylanguage = function (options) {

        this.defaults = {
            language: "ch",
            page: "",
            url: "",
            success: null
        };

        this.options = $.extend({}, this.defaults, options);

        var $this = this;

        this.load = function (lang) {
            $.getJSON($this.options.url + "languages/" + lang + "/" + $this.options.page + ".json",
                function (data) {
                    if (!!$this.options.success) {
                        $this.options.success(data);
                    }
                });
        };

        this.load(this.options.language);

        return this;
    };

})(jQuery, window, document);