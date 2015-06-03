<!DOCTYPE html>
<html>
<head>

    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="cleartype" content="on">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <title><?php echo $tab_title; ?></title>

    <!-- bootstrap 3.0.2 -->
    <link href="<?php echo get_path('common-css'); ?>bootstrap.css" rel="stylesheet" type="text/css"/>
    <!-- font Awesome -->
    <link href="<?php echo get_path('css'); ?>font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <!-- Theme style -->
    <link href="<?php echo get_path('css'); ?>AdminLTE.css" rel="stylesheet" type="text/css"/>

    <link href="<?php echo get_path('js'); ?>plugins/easyform/easyform.css" rel="stylesheet"
          type="text/css"/>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<div class="form-box" id="login-box">
    <div class="header">登录</div>
    <?php echo validation_errors('<div class="callout callout-danger" style="margin: 0;">', '</div>'); ?>
    <?php echo form_open(base_url() . 'login.html', Array('id' => 'form')); ?>
    <div class="body bg-gray">
        <div class="form-group">
            <input type="text" id="uid" name="uid" class="form-control"
                   placeholder="请输入用户名" message="必须为4-20位英文字母"
                   value="<?php echo set_value('uid'); ?>" easyform="char-normal;length:4 20;"/>
        </div>
        <div class="form-group">
            <input type="password" id="password" name="password" class="form-control"
                   placeholder="请输入密码"
                   easyform="length:6 20;" message="必须为6-20位英文字母"/>
        </div>
    </div>
    <div class="footer bg-gray">
        <input type="submit" id="bt-submit" class="btn bg-olive btn-block" value="登录">

        <p><a href="javascript:void(0);" id="forget">忘记密码</a></p>

        <p><a href="javascript:void(0);" class="text-center" id="regist">注册</a></p>
    </div>
    </form>
</div>

<!-- jQuery 2.1.3 -->
<script src="<?php echo get_path('common-js'); ?>jquery-2.1.3.min.js"></script>
<!-- Bootstrap -->
<script src="<?php echo get_path('common-js'); ?>bootstrap.min.js" type="text/javascript"></script>

<!--<script src="<?php /*echo get_path('common-js'); */?>jquery.language/jquery.language.js"
        type="text/javascript"></script>-->

<script src="<?php echo get_path('js'); ?>plugins/easyform/easyform.js" type="text/javascript"></script>

<script>

    $(document).ready(function () {

        $("#form").easyform();

        //初始化语言
        /*var lang = $.prototype.easylanguage({
            language: "ch",
            page: "login",
            url: "<?php echo get_path('common-js'); ?>jquery.language/",

            success: function (data) {
                $uid = $("#uid");
                $password = $("#password");

                $(".header").html(data["login"]);
                $uid.attr("placeholder", data["uid-placeholder"]);
                $uid.attr("message", data["uid-message"]);
                $password.attr("placeholder", data["password-placeholder"]);
                $password.attr("message", data["password-message"]);
                $("#bt-submit").val(data["login"]);
                $("a#forget").html(data["forget"]);
                $("a#regist").html(data["regist"]);
            }
        });

        lang.load("en");*/

    });

</script>

</body>
</html>