<link href="<?php echo get_path('css'); ?>datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css"/>

<div class="box box-primary">
    <div class="box-header"><i class="fa fa-users"></i>

        <h3 class="box-title">角色列表</h3></div>
    <div class="box-body">

        <div style="width:70%">

            <div style="margin-bottom:-30px;">
                <a href="<?php echo base_url(); ?>role/add.html" class="btn btn-default"><i
                        class="fa fa-users"></i> 添加角色</a>
            </div>

            <table id="rolelist" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                    <th>id</th>
                    <th>名称</th>
                    <th width="40">状态</th>
                    <th width="30">操作</th>
                </tr>
                </thead>
                <tbody>

                <?php foreach ($data as $row): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td></td>
                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>

        </div>

    </div>
</div>


<!-- DATA TABES SCRIPT -->
<script src="<?php echo get_path('js'); ?>plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?php echo get_path('js'); ?>plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
<script src="<?php echo get_path('js'); ?>plugins/datatables/cn.js" type="text/javascript"></script>
<script src="<?php echo get_path('js'); ?>common.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function ()
    {
        $('#rolelist').dataTable({
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
                    "name": "name",
                    "targets": [1],
                    "render": function (data, type, row)
                    {
                        var str = "<a href=\"<?php echo base_url();?>role/edit/" + row[0] + ".html\" title=\"编辑角色\">" + data + "</a>";
                        return str;
                    }
                },

                {
                    "name": "tool",
                    "targets": [3],
                    "searchable": false,
                    "orderable": false,
                    "render": function (data, type, row)
                    {
                        if (row[2] == "正常")
                            html = '<button class="btn btn-danger btn-flat mini" onclick="$(this).addClass(\'_click\');admin_toggle(' + row[0] + ');" data-toggle="tooltip" title="停用角色"><i class="fa fa-fw fa-ban"></i></button>';
                        else
                            html = '<button class="btn btn-success btn-flat mini" onclick="$(this).addClass(\'_click\');admin_toggle(' + row[0] + ');" data-toggle="tooltip" title="启用角色"><i class="fa fa-fw fa-check"></i></button>';

                        return html;
                    }
                }
            ]
        });
    });
</script>

<!-- 删除确认 -->
<div class="modal fade" id="delete-role" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">删除职务</h4>
                <span style="display:none;" id="del-role-id"></span>
            </div>
            <div class="modal-body">
                您确定你要删除职务<span class="del-role-name text-red"></span>吗？
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-danger" onclick="del();">删除职务</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function ($)
    {

        $('#delete-role').on('show.bs.modal', function (event)
        {

            var button = $(event.relatedTarget)
            var id = button.data('id')
            var name = button.data('name')
            var modal = $(this)
            modal.find('.del-role-name').html(name)
            modal.find('#del-role-id').html(id)

        });
    });

    function del()
    {
        var id = $("#del-role-id").html();
        window.location.href = "<?php echo base_url();?>admin/role/delete/" + id + ".html";
    }

</script>