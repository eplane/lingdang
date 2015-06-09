<link href="<?php echo get_path('css'); ?>iCheck/all.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo get_path('common-js'); ?>easyform/easyform.css" rel="stylesheet" type="text/css"/>

<script>

    $(document).ready(function ()
    {
        $('input[type="checkbox"]').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue'
        });

        $("#form").easyform();
    });

</script>

<div class="box box-primary">
    <div class="box-header"><i class="fa fa-user"></i>

        <h3 class="box-title">添加用户</h3></div>

    <div class="box-body">

        <?php echo validation_errors('<div class="callout callout-danger">', '</div>'); ?>

        <div class="form-horizontal">

            <?php echo form_open(base_url() . 'user/add.html', array('id' => 'form')); ?>

            <div class="form-group">
                <div class="col-md-4">
                    <label for="uid">用户id</label><input type="text" id="uid" name="uid" class="form-control"
                                                        placeholder="用户id" data-easyform="length:4 20;"
                                                        value="<?php echo set_value('uid'); ?>">
                </div>
                <div class="col-md-4">
                    <label for="psw">用户密码</label><input type="text" id="psw" name="psw" class="form-control"
                                                        placeholder="用户密码" data-easyform="length:6 20;"
                                                        value="<?php echo set_value('psw'); ?>">
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-4">
                    <label for="mobile">用户手机</label><input type="text" id="mobile" name="mobile" class="form-control"
                                                           placeholder="用户手机" data-easyform="length:11;number;"
                                                           value="<?php echo set_value('mobile'); ?>">
                </div>
                <div class="col-md-4">
                    <label for="email">用户mail</label><input type="text" id="email" name="email" class="form-control"
                                                            placeholder="用户mail" data-easyform="length:6 60;email;"
                                                            value="<?php echo set_value('email'); ?>">
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-12">
                    <section><h5>用户角色</h5>
                    <?php foreach($role as $row):?>

                        <input id="role-<?php echo $row['id'] ?>" type="checkbox" name="role[]"
                               value="<?php echo $row['id'] ?>"
                            <?php echo set_checkbox('role[]', $row['id']); ?>>
                        <label style="margin:0 15px 0 5px;" data-toggle="tooltip" title="<?php echo $row['explain'] ?>"
                               for="role-<?php echo $row['id'] ?>">
                            <?php echo $row['name'] ?></label>

                    <?php endforeach;?>

                    </section></div>
            </div>



            <div class="form-group">
                <div class="col-md-12">
                    <input type="submit" id="sss" value="保存" class="btn btn-default">
                </div>
            </div>

            </form>

        </div>

    </div>


</div>


<script src="<?php echo get_path('common-js'); ?>easyform/easyform.js" type="text/javascript"></script>