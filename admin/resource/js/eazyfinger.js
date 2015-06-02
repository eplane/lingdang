/**
 * Created by LiLanfei on 2014/8/27.
 *
 * version 0.1.1
 *
 * EasyPoint is a point object class.
 *
 * EasyFinger is object for touch.
 *
 */

function AngelToRad(angle) {
    return (Math.PI) * (angle / 180);
}

function RadToAngel(rad) {
    return (180 / Math.PI) * rad;
}

function Bind(el, e, fn) {
    if (el.addEventListener) {
        el.addEventListener(e, fn, false);
    }
    else if (el.attachEvent) {
        el.attachEvent("on" + e, fn, false);
    }
    else {
        alert("Bind error!");
    }
}

function Unbind(el, e, fn) {
    if (el.removeEventListener) {
        el.removeEventListener(e, fn, false);
    }
    else if (el.detachEvent) {
        el.detachEvent("on" + e, fn, false);
    }
    else {
        alert("Unbind error!");
    }
}

function EasyGuid() {
    var S4 = function () {
        return (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
    };

    return (S4() + S4() + "-" + S4() + "-" + S4() + "-" + S4() + "-" + S4() + S4() + S4());
}

function EasyPoint(x, y) {

    this.x = x;
    this.y = y;

    this.FromString = function (str) {
        var p = str.split(" ");
        p[0] = p[0].substr(0, p[0].length - 2);
        p[1] = p[1].substr(0, p[1].length - 2);

        this.x = parseFloat(p[0]);
        this.y = parseFloat(p[1]);

        return this;
    };

    this.ToString = function (unit) {
        if (undefined == unit)
            unit = "";

        return this.x + unit + " " + this.y + unit;
    };

    this.Distance = function (easyPoint) {
        if (easyPoint == undefined || easyPoint == null)
            return undefined;

        var a = this.x - easyPoint.x;
        var b = this.y - easyPoint.y;

        return Math.sqrt(a * a + b * b);
    };

    this.Direction = function (easyPoint) {
        if (easyPoint == undefined || easyPoint == null)
            return undefined;

        var a = easyPoint.x - this.x;
        var b = this.y - easyPoint.y;

        var angle = 0;

        if (a >= 0 && b >= 0) angle = 0;
        if (a < 0 && b >= 0) angle = -180;
        if (a < 0 && b < 0) angle = 180;
        if (a >= 0 && b < 0) angle = -360;

        var c = Math.sqrt(a * a + b * b);

        b = Math.abs(b);
        a = Math.abs(a);

        var Rad = Math.asin(b / c);

        angle += RadToAngel(Rad);

        angle = Math.abs(angle);

        angle = AngelToRad(angle);

        return angle;
    };

    this.Clone = function () {
        return new EasyPoint(this.x, this.y);
    };
}

function EasyRect(l, t, w, h) {
    this.left = l;
    this.top = t;
    this.width = w;
    this.height = h;

    this.IsIn = function (point) {
        if (point.x >= this.left && point.x <= (this.left + this.width)
            && point.y >= this.top && point.y <= (this.top + this.height)) {
            return true;
        }
        else {
            return false;
        }
    };

    //获得一个对象的位置和宽高
    this.FromElement = function (e) {
        this.top = e.offsetTop;
        this.left = e.offsetLeft;
        this.width = e.offsetWidth;
        this.height = e.offsetHeight;

        while (e = e.offsetParent) {
            this.top += e.offsetTop;
            this.left += e.offsetLeft;
        }
    }

    this.Clone = function () {
        return new EasyRect(this.left, this.top, this.width, this.height);
    };
}

function CompareElementDepth(el1, el2) {
    if (el1 == el2)
        return 0;

    var elp1 = [], elp2 = [], i = 0;
    var loop1 = true, loop2 = true;

    //获得两个元素的全部父节点，一直到document
    while (loop1 || loop2) {
        var p1 = null, p2 = null;

        if (i == 0) {
            p1 = el1.parentNode;
            p2 = el2.parentNode;
        }
        else {
            if (loop1)
                p1 = elp1[0].parentNode;
            if (loop2)
                p2 = elp2[0].parentNode;
        }

        p1 != null ? elp1.unshift(p1) : loop1 = false;
        p2 != null ? elp2.unshift(p2) : loop2 = false;

        i++;
    }

    //找到两列父节点中最初不同的地方。
    i = 0;
    var length = Math.max(elp1.length, elp2.length);
    while (elp1[i] == elp2[i] && i < length) {
        i++;
    }

    //从最后一个相同的父节点上找到两个元素的顺序差异，序号大的靠前
    var parent = elp1[i - 1];
    var p1 = elp1[i] == undefined ? el1 : elp1[i];
    var p2 = elp2[i] == undefined ? el2 : elp2[i];

    for (var n = 0; n < parent.childNodes.length; n++) {
        if (parent.childNodes[n] == p2) {
            return 1;
        }
        else if (parent.childNodes[n] == p1) {
            return -1;
        }
    }
}

//EasyFinger
(function () {
    var EasyFinger = {};

    //是否忽略浏览器的默认动作
    var is_ignore_default = false;

    EasyFinger.elements = [];

    var moment_threshold = 300;  //瞬间动作时间阀值，小于该时间差的动作都被认为是瞬间动作。
    //var long_threshold = 300;    //长时间动作阀值。
    var stop_threshold = 30;     //移动判断，小于该阀值的距离被认为是原地停止。

    var mouse_down_time = 0;
    var is_mouse_down = false;
    var mouse_down_position = null;
    var is_mouse_drag = false;

    /*
     * Add a callback function to handle events
     * el: the element to receive events
     * e: type of event
     * callback: callback function
     * */
    EasyFinger.Add = function (el, e, callback) {
        if (el != null && el != undefined) {
            var handle = {"element": el, "event": e.toLowerCase(), "callback": callback};

            EasyFinger.elements.push(handle);
        }

        for (var i = 0; i < EasyFinger.elements.length; i++) {
            for (var j = i + 1; j < EasyFinger.elements.length; j++) {
                if (CompareElementDepth(EasyFinger.elements[i].element, EasyFinger.elements[j].element) < 0) {
                    var temp = EasyFinger.elements[i];
                    EasyFinger.elements[i] = EasyFinger.elements[j];
                    EasyFinger.elements[j] = temp;
                }
            }
        }
    };

    EasyFinger.Delete = function (el, e, callback) {
        for (var i; i < EasyFinger.elements.length; i++) {
            var event = EasyFinger.elements[i];

            if (event.el == el && event.e == e && event.callback == callback) {
                EasyFinger.elements.splice(i, 1);

                return true;
            }
        }

        return false;
    };

    /*
     * Convert screen coordinates to object coordinates.
     * */
    EasyFinger.GetPointOnElement = function (e, p) {
        var b = e.getBoundingClientRect();

        var xp = p.pageX - b.left * (e.offsetWidth / b.width);
        var yp = p.pageY - b.top * (e.offsetHeight / b.height);

        return new EasyPoint(xp, yp);
    };

    var send_lock = 0; // 阻塞消息计时
    var drag_start = false;
    EasyFinger.Send = function (e, type, device) {
        var time = new Date().getTime();

        if (send_lock > 0 && time < send_lock) {
            return;
        }

        if(type == "swipe")
        {
            var iddd = "ddddddddddd";
        }

        send_lock = 0;

        for (var i = 0; i < EasyFinger.elements.length; i++) {
            if (type == EasyFinger.elements[i].event) {
                var param = {};

                param.type = type;
                param.device = device;

                if ("click" == type || "down" == type || "moving" == type || "drag_start" == type) {
                    var el = EasyFinger.elements[i].element;
                    var rect = new EasyRect();
                    rect.FromElement(el);
                    var pos = new EasyPoint(e.pageX, e.pageY);

                    if (rect.IsIn(pos)) {
                        var pp = EasyFinger.GetPointOnElement(EasyFinger.elements[i].element, e); //获得对象内的坐标位置。
                        param.position = pp;
                    }
                    else {
                        continue;
                    }
                }

                if ("up" == type || "drag" == type || "drag_end" == type
                    || "pinch_in" == type || "pinch_out" == type) {
                    var pp = EasyFinger.GetPointOnElement(EasyFinger.elements[i].element, e); //获得对象内的坐标位置。

                    param.position = pp;
                }

                if ("swipe" == type || "drag" == type || "pinch_in" == type || "pinch_out" == type) {
                    param.speed = e.distance / e.dtime;

                    if ("pinch_in" == type && "touch" == device)
                        param.speed = -param.speed;
                }

                if ("swipe" == type || "drag" == type) {
                    param.direction = e.direction;
                    param.angle = RadToAngel(param.direction);
                }

                param.timestamp = new Date().getTime();

                EasyFinger.elements[i].callback(param);

                if ("drag_end" == type || "pinch_in" == type || "pinch_out" == type) {
                    send_lock = time + 100;
                }

                break;
            }
        }
    };

    EasyFinger.Down = function (e) {
        if (is_ignore_default)
            e.preventDefault();

        is_mouse_down = true;
        mouse_down_time = new Date().getTime();
        mouse_down_position = new EasyPoint(e.pageX, e.pageY);

        EasyFinger.Send(e, "down", "mouse");
    };

    EasyFinger.Up = function (e) {
        if (is_ignore_default)
            e.preventDefault();

        EasyFinger.Send(e, "up", "mouse");

        is_mouse_down = false;
        var pos = new EasyPoint(e.pageX, e.pageY);
        e.distance = pos.Distance(mouse_down_position);
        e.dtime = new Date().getTime() - mouse_down_time;

        if (is_mouse_drag) {
            is_mouse_drag = false;
            EasyFinger.Send(e, "drag_end", "mouse");
        }
        else if (e.dtime <= moment_threshold && e.distance <= stop_threshold) {
            EasyFinger.Send(e, "click", "mouse");
        }
        else if (e.dtime <= moment_threshold && e.distance > stop_threshold) {
            e.direction = mouse_down_position.Direction(pos);
            EasyFinger.Send(e, "swipe", "mouse");
        }

        mouse_down_time = 0;
    };

    EasyFinger.Moving = function (e) {
        if (is_ignore_default)
            e.preventDefault();

        EasyFinger.Send(e, "moving", "mouse");

        if (is_mouse_down) {
            var pos = new EasyPoint(e.pageX, e.pageY);

            e.dtime = new Date().getTime() - mouse_down_time;
            e.distance = pos.Distance(mouse_down_position);

            if (false == is_mouse_drag && e.dtime >= moment_threshold && e.distance > stop_threshold) {
                is_mouse_drag = true;
                EasyFinger.Send(e, "drag_start", "mouse");
            }
            else if (is_mouse_drag) {
                e.direction = pos.Direction(mouse_down_position);
                EasyFinger.Send(e, "drag", "mouse");
            }
        }
    };

    EasyFinger.Mousewheel = function (e) {
        if (is_ignore_default)
            e.preventDefault();

        var pos = new EasyPoint(e.pageX, e.pageY);

        e.dtime = 500;
        e.distance = e.wheelDelta;

        if (e.distance == undefined)
            e.distance = e.detail * 40;


        if (e.distance > 0) {
            EasyFinger.Send(e, "pinch_out", "mouse");
        }
        else {
            EasyFinger.Send(e, "pinch_in", "mouse");
        }
    };

    var touch_down_time = 0;
    var touch_down_position = null;
    var touch_move_position = null;
    var is_touch_drag = false;
    var is_touch_moving = false;

    var is_touch_pinch = false;
    var touch_pinch_start_distance = 0;
    var touch_pinch_pos1 = null;
    var touch_pinch_pos2 = null;

    EasyFinger.TouchStart = function (ee) {
        if (is_ignore_default)
            ee.preventDefault();

        try {
            var e = {};
            e.pageX = ee.touches[0].pageX;
            e.pageY = ee.touches[0].pageY;

            touch_down_time = new Date().getTime();
            touch_down_position = new EasyPoint(e.pageX, e.pageY);

            EasyFinger.Send(e, "down", "touch");

            if (ee.touches.length == 2 && false == is_touch_pinch) {
                is_touch_pinch = true;
                var pos1 = new EasyPoint(ee.touches[0].pageX, ee.touches[0].pageY);
                var pos2 = new EasyPoint(ee.touches[1].pageX, ee.touches[1].pageY);

                touch_pinch_pos1 = new EasyPoint(ee.touches[0].pageX, ee.touches[0].pageY);
                touch_pinch_pos2 = new EasyPoint(ee.touches[1].pageX, ee.touches[1].pageY);

                touch_pinch_start_distance = pos2.Distance(pos1);
            }
        }
        catch (e) {
            alert("Touchstart :" + e);
        }

    };

    EasyFinger.TouchEnd = function (ee) {
        if (is_ignore_default)
            ee.preventDefault();

        //因为touchend事件没有坐标，只好手动模拟一个事件参数了
        var e = {};
        try {
            if (false == is_touch_pinch && touch_down_position != null) {
                if (is_touch_moving) {
                    e.pageX = touch_move_position.x;
                    e.pageY = touch_move_position.y;
                    e.distance = touch_down_position.Distance(touch_move_position);
                }
                else {
                    e.pageX = touch_down_position.x;
                    e.pageY = touch_down_position.y;
                    e.distance = 0;
                }

                EasyFinger.Send(e, "up", "touch");

                e.dtime = new Date().getTime() - touch_down_time;

                if (is_touch_drag) {
                    is_touch_drag = false;
                    EasyFinger.Send(e, "drag_end", "touch");
                }
                else if (e.dtime <= moment_threshold && e.distance <= stop_threshold) {
                    EasyFinger.Send(e, "click", "touch");
                }
                else if (e.dtime <= moment_threshold && e.distance > stop_threshold) {
                    e.direction = touch_down_position.Direction(touch_move_position);
                    EasyFinger.Send(e, "swipe", "touch");
                }
            }
            else if (true == is_touch_pinch) {
                is_touch_pinch = false;

                if (touch_pinch_pos2 != null) {
                    e.distance = touch_pinch_pos2.Distance(touch_pinch_pos1);
                    e.dtime = new Date().getTime() - touch_down_time;

                    e.pageX = touch_pinch_pos1.x + (touch_pinch_pos2.x - touch_pinch_pos1.x) / 2;
                    e.pageY = touch_pinch_pos1.y + (touch_pinch_pos2.y - touch_pinch_pos1.y) / 2;

                    if (e.distance > touch_pinch_start_distance) {
                        EasyFinger.Send(e, "pinch_out", "touch");
                    }
                    else {
                        EasyFinger.Send(e, "pinch_in", "touch");
                    }
                }

                touch_move_position = null;
                touch_down_position = null;
            }

            is_touch_moving = false;
        }
        catch (e) {
            alert("Touchend :" + e);
        }
    };

    EasyFinger.TouchMove = function (ee) {
        if (is_ignore_default)
            ee.preventDefault();
        try {
            is_touch_moving = true;

            if (ee.touches.length == 1) {
                var e = {};
                e.pageX = ee.touches[0].pageX;
                e.pageY = ee.touches[0].pageY;

                var pos = new EasyPoint(ee.touches[0].pageX, ee.touches[0].pageY);
                touch_move_position = new EasyPoint(ee.touches[0].pageX, ee.touches[0].pageY);

                e.dtime = new Date().getTime() - touch_down_time;
                e.distance = pos.Distance(touch_down_position);

                EasyFinger.Send(e, "moving", "touch");

                if (false == is_touch_drag && e.dtime >= moment_threshold && e.distance > stop_threshold) {
                    is_touch_drag = true;
                    EasyFinger.Send(e, "drag_start", "touch");
                }
                else if (is_touch_drag) {
                    e.direction = touch_down_position.Direction(touch_move_position);
                    EasyFinger.Send(e, "drag", "touch");
                }
            }
            else if (ee.touches.length == 2) {
                touch_pinch_pos1 = new EasyPoint(ee.touches[0].pageX, ee.touches[0].pageY);
                touch_pinch_pos2 = new EasyPoint(ee.touches[1].pageX, ee.touches[1].pageY);
            }

        }
        catch (e) {
            alert("Touchmove :" + e);
        }
    };


    window.EasyFinger = EasyFinger;

    //所有事件是统一接收并识别的
    if (document.hasOwnProperty("ontouchstart")) {
        Bind(document, 'touchstart', EasyFinger.TouchStart);
        Bind(document, 'touchend', EasyFinger.TouchEnd);
        Bind(document, 'touchmove', EasyFinger.TouchMove);
    }
    else {
        document.onmouseup = EasyFinger.Up;
        document.onmousedown = EasyFinger.Down;
        document.onmousemove = EasyFinger.Moving;
        document.onmousewheel = EasyFinger.Mousewheel;
        Bind(document, 'DOMMouseScroll', EasyFinger.Mousewheel);
    }

}());

//EasyCss
(function () {
    var EasyCss = {};

    // 倒序查询，以符合CSS执行顺序。
    EasyCss.Find = function (rule, attr, is_delete) {
        rule = rule.toLowerCase();

        if (document.styleSheets) {
            for (var i = document.styleSheets.length - 1; i >= 0; i--) {
                var styleSheet = document.styleSheets[i];

                var styleSheetRules = styleSheet.cssRules ? styleSheet.cssRules : styleSheet.rules;

                for (var ii = styleSheetRules.length - 1; ii >= 0; ii--) {
                    var cssRule = styleSheetRules[ii];

                    if (cssRule) {
                        var rule_name = "";

                        if (cssRule.selectorText) {
                            rule_name = cssRule.selectorText;
                        }
                        else if (cssRule.name) {
                            rule_name = cssRule.name;
                        }

                        if (rule_name.toLowerCase() == rule) {
                            if (attr) {
                                if (is_delete === true) {
                                    cssRule.style.removeProperty(attr);

                                    return true;
                                }
                                else {
                                    return cssRule.style.getPropertyValue(attr);
                                }
                            }
                            else if (is_delete === true) {
                                if (styleSheet.cssRules) {
                                    styleSheet.deleteRule(ii);
                                }
                                else {
                                    styleSheet.removeRule(ii);
                                }
                                return true;
                            }
                            else {
                                return cssRule;
                            }
                        }
                    }
                }
            }
        }
        return false;
    };

    EasyCss.Set = function (rule, attr, value) {
        var s = EasyCss.Find(rule);
        if (false != s) {
            s.style.setProperty(attr, value);
        }
        else {
            return false;
        }
    };

    EasyCss.Get = function (rule, attr) {
        rule = rule.toLowerCase();

        if (attr) {
            return EasyCss.Find(rule, attr);
        }
        else {
            return EasyCss.Find(rule);
        }
    };

    // Delete a CSS rule
    EasyCss.Delete = function (ruleName) {
        return EasyCss.Find(ruleName, undefined, true);
    };

    // Create a new css rule
    EasyCss.Add = function (ruleName, css) {
        // Can browser do styleSheets?
        if (document.styleSheets) {
            // if rule doesn't exist...
            if (!EasyCss.Get(ruleName)) {
                // Browser is IE?
                if (document.styleSheets[0].addRule) {
                    // IE
                    document.styleSheets[0].addRule(ruleName, css, 0);
                }
                else {   // is not IE
                    document.styleSheets[0].insertRule(ruleName + " {" + css + "}", 0);
                }
            }
        }

        return EasyCss.Find(ruleName);
    };

    window.EasyCss = EasyCss;

}());

//EasyAnimation static
(function () {
    EasyAnimation.Animations = [];

    EasyAnimation.Animations.Delete = function (css_name) {
        for (var i = 0; i < EasyAnimation.Animations.length; i++) {
            if (css_name.indexOf(EasyAnimation.Animations[i].css_name) != -1) {
                EasyAnimation.Animations.splice(i, 1);
                return true;
            }
        }

        return false;
    };

    EasyAnimation.Get = function (css_name) {
        for (var i = 0; i < EasyAnimation.Animations.length; i++) {
            if (css_name.indexOf(EasyAnimation.Animations[i].css_name) != -1) {
                return EasyAnimation.Animations[i];
            }
        }

        return null;
    };

    EasyAnimation.AnimationEnd = function (e) {
        var ani = EasyAnimation.Get(e.currentTarget.className);

        if (ani != null) {
            if (ani.AnimationEnd != null)
                ani.AnimationEnd(e);
        }
    };

    EasyAnimation.AnimationStart = function (e) {
        var ani = EasyAnimation.Get(e.currentTarget.className);

        if (ani != null) {
            if (ani.AnimationStart != null)
                ani.AnimationStart(e);
        }
    };

    EasyAnimation.AnimationIteration = function (e) {
        var ani = EasyAnimation.Get(e.currentTarget.className);

        if (ani != null) {
            if (ani.AnimationIteration != null)
                ani.AnimationIteration(e);
        }
    };
}());

function EasyTimer(funcName, time) {
    var args = [];
    for (var i = 2; i < arguments.length; i++) {
        args.push(arguments[i]);
    }

    return window.setInterval(function () {
        funcName.apply(this, args);
    }, time);
};

//EasyAnimation function
function EasyAnimation(element, name, duration) {
    var new_css_name = "a-" + EasyGuid();

    this.element = element;
    this.css_name = new_css_name;
    this.name = name;
    this.duration = duration;
    this.timing_function = "ease";
    this.delay = 0;
    this.iteration_count = "infinite";
    this.direction = "normal";
    this.play_state = "paused";
    this.fill_mode = "both";

    this.AnimationEnd = undefined;
    this.AnimationStart = undefined;
    this.AnimationIteration = undefined;
    this.AnimationPaused = undefined;

    this.Css = function () {
        var animation = "animation: " + this.name + " " + this.duration + "s " + this.timing_function + " " + this.delay + "s " + this.iteration_count + " " + this.direction + ";";

        var css = animation;
        css += "-moz-" + animation;
        css += "-webkit-" + animation;
        css += "-o-" + animation;

        css += this.Property("animation-play-state", this.play_state);
        css += this.Property("animation-fill-mode", this.fill_mode);

        return css;
    };

    this.Property = function (name, value) {
        var css = name + ":" + value + ";";
        css += "-moz-" + name + ":" + value + ";";
        css += "-webkit-" + name + ":" + value + ";";
        css += "-o-" + name + ":" + value + ";";

        return css;
    };

    this.Play = function (times) {
        //this.Pause();
        //this.Drop();

        var class_name = this.element.className;

        if (-1 == class_name.indexOf(this.css_name)) {
            this.play_state = "running";

            this.iteration_count = (times == undefined ? "infinite" : times);

            this.Style = EasyCss.Add("." + this.css_name, this.Css());

            var animation_play_state = this.Property("animation-play-state", this.play_state);

            this.element.className += " " + this.css_name;
        }

        EasyAnimation.Animations.push(this);

        Bind(this.element, "animationstart", EasyAnimation.AnimationStart);
        Bind(this.element, "animationend", EasyAnimation.AnimationEnd);
        Bind(this.element, "animationiteration", EasyAnimation.AnimationIteration);

        //for chorme
        Bind(this.element, "webkitAnimationStart", EasyAnimation.AnimationStart);
        Bind(this.element, "webkitAnimationEnd", EasyAnimation.AnimationEnd);
        Bind(this.element, "webkitAnimationIteration", EasyAnimation.AnimationIteration);

        this.element.style.cssText = animation_play_state;
    };

    this.Pause = function () {
        this.play_state = "paused";

        if (this.AnimationPaused) {
            this.AnimationPaused();
        }

        this.element.style.cssText = this.Property("animation-play-state", this.play_state);
    };

    this.Drop = function () {
        var class_name = this.element.className;

        if (-1 != class_name.indexOf(this.css_name)) {
            this.element.className = this.element.className.replace(this.css_name, "");
            this.element.className = this.element.className.trim();
        }

        EasyCss.Delete("." + this.css_name);

        this.Style = null;

        EasyAnimation.Animations.Delete(this.css_name);

        Unbind(this.element, "animationstart", EasyAnimation.AnimationStart);
        Unbind(this.element, "animationend", EasyAnimation.AnimationEnd);
        Unbind(this.element, "animationiteration", EasyAnimation.AnimationIteration);

        //for chorme
        Unbind(this.element, "webkitAnimationStart", EasyAnimation.AnimationStart);
        Unbind(this.element, "webkitAnimationEnd", EasyAnimation.AnimationEnd);
        Unbind(this.element, "webkitAnimationIteration", EasyAnimation.AnimationIteration);
    };

    return this;
}

function EasyKeyframe(name) {
    if (name == undefined || name == "") {
        alert("new a EasyKeyframe must by a Existing @Keyframe, so you must give a name.");
    }

    this.Keyframe = EasyCss.Get(name);

    this.Frames = [];

    this.IsEmpty = function () {
        if (this.Keyframe == false) {
            alert("This Keyframe is empty. Please write one by css. Then new a EasyKeyframe object by the name.");
            return true;
        }
        else {
            return false;
        }
    };

    this.ToString = function () {
        if (this.IsEmpty()) {
            return null;
        }
        else {
            return this.Keyframe.cssText;
        }
    };

    this.Empty = function () {
        if (this.IsEmpty()) {
            return null;
        }

        var cssRules = this.Keyframe.cssRules ? this.Keyframe.cssRules : this.Keyframe.Rules;

        for (; cssRules.length > 0;) {
            if ("/v" == "v") {
                //IE
                this.Keyframe.deleteRule(0);
            }
            else {
                this.Keyframe.deleteRule(cssRules[0].keyText);
            }
        }

        this.Frames = [];
    };

    this.Get = function (framekey, attr) {
        if (this.IsEmpty())
            return null;

        for (var i = 0; i < this.Frames.length; i++) {
            if (framekey == this.Frames[i].key) {
                if (attr == undefined || attr == null || attr == "") {
                    return this.Frames[i];
                }
                else {
                    for (var j = 0; j < this.Frames[i].value.length; j++) {
                        if (attr && attr == this.Frames[i].value[j].key) {
                            return this.Frames[i].value[j].value;
                        }
                    }
                }
            }
        }

        return undefined;
    };

    this.Set = function (framekey, attr, value, no_refresh) {
        if (this.IsEmpty())
            return false;

        for (var i = 0; i < this.Frames.length; i++) {
            if (this.Frames[i].key == framekey) {
                for (var j = 0; j < this.Frames[i].value.length; j++) {
                    if (this.Frames[i].value[j].key == attr) {
                        this.Frames[i].value[j].value = value;

                        if (!no_refresh)
                            this.Refresh();

                        return true;
                    }
                }

                this.Frames[i].value.push({"key": attr, "value": value});

                if (!no_refresh)
                    this.Refresh();

                return true;
            }
        }

        var v = [{"key": attr, "value": value}];

        this.Frames.push({"key": framekey, "value": v});

        if (!no_refresh)
            this.Refresh();

        return true;
    };

    this.Delete = function (framekey, attr, no_refresh) {
        if (this.IsEmpty())
            return false;

        for (var i = 0; i < this.Frames.length; i++) {
            if (this.Frames[i].key == framekey) {
                if (attr) {
                    for (var j = 0; j < this.Frames[i].length; j++) {
                        if (this.Frames[i].value[j].key == attr) {
                            this.Frames[i].value.splice(i, 1);

                            if (!no_refresh)
                                this.Refresh();

                            return true;
                        }
                    }
                }
                else {
                    this.Frames.splice(i, 1);

                    if (!no_refresh)
                        this.Refresh();

                    return true;
                }
            }
        }
    };

    this.Refresh = function () {
        if (this.IsEmpty())
            return null;

        var cssRules = this.Keyframe.cssRules ? this.Keyframe.cssRules : this.Keyframe.Rules;

        for (; cssRules.length > 0;) {
            if ("/v" == "v") {
                //IE
                this.Keyframe.deleteRule(0);
            }
            else {
                this.Keyframe.deleteRule(cssRules[0].keyText);
            }
        }

        for (var j = 0; j < this.Frames.length; j++) {
            var name = this.Frames[j].key;
            var attrs = this.Frames[j].value;
            var str = "";

            for (var n = 0; n < this.Frames[j].value.length; n++) {
                var attr = this.Frames[j].value[n];
                str += attr.key + ":" + attr.value + ";";
            }

            var rule = this.Frames[j].key + "{" + str + "}";

            if (this.Keyframe.appendRule) {// FF
                this.Keyframe.appendRule(rule, 0);
            }
            else {
                this.Keyframe.insertRule(rule, 0);
            }
        }

    };

    if (this.Keyframe == false) {
        return null;
    }
    else {
        var Rules = this.Keyframe.cssRules ? this.Keyframe.cssRules : this.Keyframe.rules;

        for (var i = 0; i < Rules.length; i++) {
            var frame = Rules[i];

            var css = frame.cssText;

            var strs = css.split("}"); //所有的帧

            var values = [];

            for (var j = 0; j < strs.length; j++) {
                if (strs[j].trim() != "") {
                    strs[j] = strs[j].substr(strs[j].indexOf("{") + 1);

                    var temp = strs[j].split(";"); //一帧里的所有属性

                    for (var n = 0; n < temp.length; n++) {
                        if (temp[n].trim() != "") {
                            var attr_temp = temp[n].split(":");

                            //属性值对
                            var value_pair = {key: attr_temp[0].trim(), value: attr_temp[1].trim().replace(";", "")};

                            values.push(value_pair);
                        }
                    }
                }
            }

            this.Frames.push({key: frame.keyText, value: values});
        }

        return this;
    }
}
