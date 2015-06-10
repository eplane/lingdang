<link href="<?php echo get_path('common-js'); ?>easyform/easyform.css" rel="stylesheet" type="text/css"/>

<?php echo validation_errors('<div class="callout callout-danger">', '</div>'); ?>
<?php echo form_open(base_url() . 'user/me.html', Array('enctype' => 'multipart/form-data', 'id' => 'form')); ?>

<div class="form-horizontal">
    <div class="box box-primary">
        <div class="box-header"><i class="fa fa-user"></i>

            <h3 class="box-title">个人信息</h3></div>
        <div class="box-body">

            <div class="form-group">
                <div class="col-md-4">
                    <label for="uid">用户ID </label>
                    <input type="text" id="uid" name="uid" class="form-control" value="<?php echo $user['uid']; ?>"
                           disabled="">

                    <label for="nick" style="margin-top:15px;">昵称 </label>
                    <input type="text" id="nick" name="nick" class="form-control" placeholder="昵称"
                           value="<?php echo set_value('nick', $user['nick']); ?>">
                </div>

                <div class="col-md-4">
                    <label>头像 </label><br>
                    <img style="width:108px;height:108px;border:1px solid #ccc; cursor:pointer;" id="avatar"
                         src="<?php echo get_file($user['avatar']); ?>" title="头像">
                </div>

            </div>


            <div class="form-group">
                <div class="col-md-4">
                    <label for="email">Email </label>

                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                        <input id="email" name="email" class="form-control" placeholder="Email" type="text"
                               value="<?php echo set_value('email', $user['email']); ?>">
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="mobile">手机 </label>

                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                        <input class="form-control" placeholder="手机" type="text" id="mobile" name="mobile"
                               value="<?php echo set_value('mobile', $user['mobile']); ?>">
                    </div>
                </div>
            </div>


            <div class="form-group">
                <div class="col-md-4">
                    <label for="name">真实姓名 </label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="真实姓名"
                           value="<?php echo set_value('name', $user['name']); ?>">
                </div>
            </div>

            <br>

            <div class="form-group">
                <div class="col-md-12">
                    <input class="btn btn-default" id="save" type="submit" value="保存">
                    <button class="btn btn-default" id="change" data-toggle="modal" data-target="#change-psw">修改密码</button>
                </div>
            </div>


        </div>
    </div>

</div>

</form>

<!-- InputMask -->
<script src="<?php echo get_path('js'); ?>plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
<script src="<?php echo get_path('js'); ?>plugins/input-mask/jquery.inputmask.date.extensions.js"
        type="text/javascript"></script>
<script src="<?php echo get_path('js'); ?>plugins/input-mask/jquery.inputmask.extensions.js"
        type="text/javascript"></script>

<script type="text/javascript">

    $(document).ready(function ()
    {
        $("#form").easyform();
        $("#avatar").easyimagefile();
        $("#psw-form").easyform();
    });

</script>

<script src="<?php echo get_path('js'); ?>plugins/iCheck/icheck.min.js" type="text/javascript"></script>
<script src="<?php echo get_path('common-js'); ?>easyform/easyform.js" type="text/javascript"></script>
<script src="<?php echo get_path('common-js'); ?>easyform/easyimagefile.js" type="text/javascript"></script>

<!-- 修改密码 -->
<div class="modal fade" id="change-psw" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form action="<?php echo base_url();?>user/psw.html" id="psw-form" method="post">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">修改密码</h4>
            </div>
            <div class="modal-body">

                <div class="form-horizontal">

                        <div class="form-group">
                            <div class="col-md-8">
                                <label for="opsw">旧密码 </label>
                                <input type="password" id="opsw" name="opsw" class="form-control" value="" data-easyform="length:4 20;" data-message="请填写正确的密码">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8">
                                <label for="npsw1">新密码 </label>
                                <input type="password" id="npsw1" name="npsw1" class="form-control" value="" data-easyform="length:4 20;" data-message="请填写正确的密码">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8">
                                <label for="npsw2">确认新密码 </label>
                                <input type="password" id="npsw2" name="npsw2" class="form-control" value="" data-easyform="equal:#npsw1;" data-message="和上一次输入保持一致">
                            </div>
                        </div>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <input type="submit" value="修改密码" class="btn btn-danger" >
            </div>
        </div>
    </div>
    </form>
</div>

<script src="<?php echo get_path('common-js'); ?>easyform/easyform.js" type="text/javascript"></script>