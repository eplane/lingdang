<link href="<?php echo get_path('css'); ?>iCheck/all.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo get_path('common-js'); ?>easyform/easyform.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo get_path('css'); ?>datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css"/>


<script>

    $(document).ready(function ()
    {
        <?php if(isset($user)):?>
        var table = $('#list').dataTable({
            "bLengthChange": false,
            "language": ch,
            "columnDefs": [
                {
                    "name": "id",
                    "targets": [0],
                    "visible": false,
                    "searchable": false
                },
                {
                    "name": "tool",
                    "targets": [6],
                    "searchable": false,
                    "orderable": false,
                    "render": function (data, type, row)
                    {
                        var html = '';

                        html += '<button class="btn btn-danger btn-flat mini"  data-toggle=\"modal\" data-target=\"#delete-user\" data-id=\"' + row[0] + '\" data-name=\"' + row[2] + '\" title="删除用户"><i class="fa fa-fw fa-times"></i></button>';

                        return html;
                    }
                }
            ]
        }).api();
        <?php endif;?>

        $('input[type="checkbox"]').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue'
        });

        $("#form").easyform();

        $('#delete-user').on('show.bs.modal', function (event)
        {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var name = button.data('name');
            var modal = $(this);
            modal.find('#del-user-name').html(name);
            modal.find('#del-user-id').html(id);
            modal.find(".btn.btn-danger").attr("href", "<?php echo base_url();?>role/delete_user/<?php echo $role['id']?>/" + id + ".html");
        });
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


<?php if (isset($user)): ?>
    <!--角色成员-->
    <div class="box box-primary">

        <div class="box-header"><i class="fa fa-users"></i>

            <h3 class="box-title">角色成员</h3></div>

        <div class="box-body">

            <div style="margin-bottom:-30px;">
                <a href="<?php echo base_url(); ?>user/add.html" class="btn btn-default"><i class="fa fa-users"></i>
                    添加用户</a>
            </div>

            <table id="list" class="table table-bordered table-hover table-striped" style="width:100%;">
                <thead>
                <tr>
                    <th>id</th>
                    <th>昵称</th>
                    <th>姓名</th>
                    <th>电话</th>
                    <th>email</th>
                    <th width="40">状态</th>
                    <th width="30">操作</th>
                </tr>
                </thead>

                <tbody>

                <?php foreach ($user as $row): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['nick']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['mobile']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td></td>
                    </tr>
                <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </div>
    <!-- DATA TABES SCRIPT -->
    <script src="<?php echo get_path('js'); ?>plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
    <script src="<?php echo get_path('js'); ?>plugins/datatables/dataTables.bootstrap.js"
            type="text/javascript"></script>
    <script src="<?php echo get_path('js'); ?>plugins/datatables/cn.js" type="text/javascript"></script>
<?php endif; ?>

<script src="<?php echo get_path('js'); ?>plugins/iCheck/icheck.min.js" type="text/javascript"></script>
<script src="<?php echo get_path('common-js'); ?>easyform/easyform.js" type="text/javascript"></script>


<!-- 删除确认 -->
<div class="modal fade" id="delete-user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">从角色中删除用户</h4>
                <span style="display:none;" id="del-user-id"></span>
            </div>
            <div class="modal-body">
                您确定你要从角色<span class="text-red"><?php echo $role['name']; ?></span>中删除用户<span id="del-user-name"
                                                                                             class="text-red"></span>吗？
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <a class="btn btn-danger" href="">删除用户</a>
            </div>
        </div>
    </div>
</div>
