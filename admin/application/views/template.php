<!DOCTYPE html>
<html>
<head>

    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="cleartype" content="on">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <title><?php echo $html_title; ?></title>

    <!-- bootstrap 3.0.2 -->
    <link href="<?php echo get_path('css'); ?>bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <!-- font Awesome -->
    <link href="<?php echo get_path('css'); ?>font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <!-- Ionicons -->
    <link href="<?php echo get_path('css'); ?>ionicons.min.css" rel="stylesheet" type="text/css"/>
    <!-- Theme style -->
    <link href="<?php echo get_path('css'); ?>AdminLTE.css" rel="stylesheet" type="text/css"/>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <!-- jQuery 2.1.3 -->
    <script src="<?php echo get_path('js'); ?>jquery-2.1.3.min.js"></script>
    <!-- jQuery UI 1.10.3 -->
    <script src="<?php echo get_path('js'); ?>jquery-ui-1.10.3.min.js" type="text/javascript"></script>
    <!-- Bootstrap -->
    <script src="<?php echo get_path('js'); ?>bootstrap.min.js" type="text/javascript"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo get_path('js'); ?>AdminLTE/app.js" type="text/javascript"></script>

</head>
<body class="skin-blue">

<!-- header logo: style can be found in header.less -->
<header class="header">
    <a href="javascript:;" class="logo">
        <!-- Add the class icon to your logo image or logo icon to add the margining -->
        Welcome
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="javascript:;" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-right">
            <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="glyphicon glyphicon-user"></i>
                        <span><?php echo $_SESSION['me']['nick']; ?><i class="caret"></i></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header bg-light-blue">
                            <img
                                src="<?php echo get_file($_SESSION['me']['avatar'], get_path('common-img') . 'avatar.jpg'); ?>"
                                class="img-circle" alt="User Image"
                                onclick="javascript:window.location.href='<?php echo base_url(); ?>admin/user/me.html';"
                                style="cursor:pointer;"/>

                            <p>
                                <?php echo $_SESSION['me']['name']; ?>
                            </p>
                        </li>

                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="<?php echo base_url(); ?>user/me.html" class="btn btn-default btn-flat">我的账户</a>
                            </div>
                            <div class="pull-right">
                                <a href="<?php echo base_url(); ?>login/logout.html" class="btn btn-default btn-flat">退出登录</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>



<div class="wrapper row-offcanvas row-offcanvas-left">
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="left-side sidebar-offcanvas" style="/*display:none;*/">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">

                <li class="active" style="line-height:25px;">
                    <a href="<?php echo base_url(); ?>admin.html">
                        <i class="fa fa-home"></i> <span>主页</span>
                    </a>
                </li>

                <li class="treeview active">
                    <a href="#">
                        <i class="fa fa-user"></i>
                        <span>员工管理</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="<?php echo base_url(); ?>admin/stuff.html"><i class="fa fa-angle-double-right"></i>员工列表</a></li>
                        <li><a href="<?php echo base_url(); ?>admin/role.html"><i class="fa fa-angle-double-right"></i>职务列表</a></li>
                        <li><a href="<?php echo base_url(); ?>admin/role/add.html"><i class="fa fa-angle-double-right"></i>添加职务</a></li>
                    </ul>
                </li>


                <li class="treeview active">
                    <a href="#">
                        <i class="fa fa-user"></i>
                        <span>系统</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="<?php echo base_url(); ?>company/edit.html"><i class="fa fa-angle-double-right"></i>公司资料</a></li>
                    </ul>
                </li>


                <li class="treeview active">
                    <a href="#">
                        <i class="fa fa-edit"></i>
                        <span>内容管理</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="javascript:;"><i class="fa fa-angle-double-right"></i>发布文章</a></li>
                        <li><a href="javascript:;"><i class="fa fa-angle-double-right"></i>草稿箱</a></li>
                        <li><a href="javascript:;"><i class="fa fa-angle-double-right"></i>频道列表</a></li>
                    </ul>
                </li>

                <li class="treeview active">
                    <a href="#">
                        <i class="fa fa-star"></i>
                        <span>会员管理</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="javascript:;"><i class="fa fa-angle-double-right"></i>会员列表</a></li>
                        <li><a href="javascript:;"><i class="fa fa-angle-double-right"></i>会员等级列表</a></li>
                    </ul>
                </li>


            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side">
        <!-- 顶部路径导航 -->
        <ol class="breadcrumb">
            <?php foreach ($nav as $text => $url): ?>

                <?php if (!!$url): ?>
                    <li><a href="<?php echo base_url().$url; ?>"><h4><?php echo $text; ?></h4></a></li>
                <?php else: ?>
                    <li class="active"><?php echo $text; ?></li>
                <?php endif; ?>

            <?php endforeach; ?>

        </ol>

        <!-- Main content -->
        <section class="content">

            <?php echo $page; ?>

        </section>
        <!-- /.content -->
    </aside>
    <!-- /.right-side -->
</div>
<!-- ./wrapper -->
</body>
</html>