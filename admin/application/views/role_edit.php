<link href="<?php echo get_path('css'); ?>iCheck/all.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo get_path('common-js'); ?>easyform/easyform.css" rel="stylesheet" type="text/css"/>

<script>

    $(document).ready(function () {
        $('input[type="checkbox"]').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue'
        });

        $("#form").easyform();
    });

</script>

<div class="box box-primary">
    <div class="box-header"><i class="fa fa-users"></i>

        <h3 class="box-title">角色</h3></div>
    <div class="box-body">

        <?php echo validation_errors('<div class="callout callout-danger">', '</div>'); ?>
        <div class="form-horizontal">

            <?php
            if (!!$role['id'])
            {
                echo form_open(base_url() . 'role/edit/' . $role['id'] . '.html', array('id' => 'form'));
            }
            else
            {
                echo form_open(base_url() . 'role/add.html', array('id' => 'form'));
            }

            ?>

            <div class="form-group">

                <div class="col-md-2">
                    <select class="form-control" id="status" name="status">
                        <option
                            value="1" <?php echo set_select('status', '正常', $role['status'] == '正常'); ?>>
                            正常
                        </option>
                        <option value="2" <?php echo set_select('status', '停用', $role['status'] == '停用'); ?>>
                            停用
                        </option>
                    </select>
                </div>

                <div class="col-md-6">
                    <input type="text" id="name" name="name" class="form-control" placeholder="角色名称"
                           data-easyform="length:2 20"
                           value="<?php echo set_value('name', $role['name']); ?>">
                </div>

            </div>

            <div class="form-group">
                <div class="col-md-8">
                    <textarea id="explain" name="explain" class="form-control" data-easyform="length:4 128;"
                              data-message="长度必须是4到128个字符之间"
                              placeholder="角色说明"><?php echo set_value('explain', $role['explain']); ?></textarea>
                </div>
            </div>


            <h4 class="page-header">角色权限</h4>
            <?php $last_group = '';
            $first = TRUE;
            foreach ($access as $v): ?>

                <?php
                if ($v['group'] != $last_group)
                {
                    if (FALSE == $first)
                    {
                        echo '</section></div></div>';
                    }

                    echo '<div class="form-group">';
                    echo '<div class="col-md-12">';
                    echo '<section><h5>' . $v['group'] . '</h5>';

                    $last_group = $v['group'];
                    $first = FALSE;
                }?>


                <input id="access-<?php echo $v['id'] ?>" type="checkbox" name="access[]"
                       value="<?php echo $v['id'] ?>"
                    <?php echo set_checkbox('access[]', $v['id'], in_array($v['id'], $role['access'])); ?>>
                <label style="margin:0 15px 0 5px;" data-toggle="tooltip" title="<?php echo $v['explain'] ?>"
                       for="access-<?php echo $v['id'] ?>">
                    <?php echo $v['name'] ?></label>


            <?php endforeach; ?>

            </section></div>


    </div>

    <br>

    <div class="form-group">
        <div class="col-md-12">
            <input type="submit" id="sss" value="保存" class="btn btn-default">
        </div>
    </div>
    </form>
</div>


</div>
</div>

<script src="<?php echo get_path('js'); ?>plugins/iCheck/icheck.min.js" type="text/javascript"></script>
<script src="<?php echo get_path('common-js'); ?>easyform/easyform.js" type="text/javascript"></script>
