<link href="<?php echo get_path('css'); ?>datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css"/>

<div class="box box-primary">
    <div class="box-header"><i class="fa fa-users"></i>

        <h3 class="box-title">角色列表</h3></div>
    <div class="box-body">
        <div style="width:70%">

            <div style="margin-bottom:-30px;">
                <a href="<?php echo base_url(); ?>role/add.html" class="btn btn-default"><i class="fa fa-users"></i> 添加角色</a>
            </div>

            <table id="rolelist" class="table table-bordered table-hover table-striped" style="width:100%;">
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
    var table = $('#rolelist').dataTable({
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
                        html = '<button class="btn btn-danger btn-flat mini" onclick="$(this).addClass(\'_click\');role_toggle(' + row[0] + ');" title="停用角色"><i class="fa fa-fw fa-ban"></i></button>';
                    else
                        html = '<button class="btn btn-success btn-flat mini" onclick="$(this).addClass(\'_click\');role_toggle(' + row[0] + ');" title="启用角色"><i class="fa fa-fw fa-check"></i></button>';

                    html += '<button class="btn btn-danger btn-flat mini"  data-toggle="modal" data-target="#delete-role" data-id="' + row[0] + '" data-name="' + row[2] + '" title="删除角色"><i class="fa fa-fw fa-times"></i></button>';

                    return html;
                }
            }
        ]
    }).api();

    $(document).ready(function ()
    {
        $('#delete-role').on('show.bs.modal', function (event)
        {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var name = button.data('name');
            var modal = $(this);
            modal.find('#del-role-name').html(name);
            modal.find('#del-role-id').html(id);
            modal.find(".btn.btn-danger").attr("href", "<?php echo base_url();?>role/delete/" + id + ".html");
        });
    });

    function role_toggle(id)
    {

        var bt = $("._click");
        $(bt).attr("disabled", true);

        $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>ajax/role_toggle.html",
            data: "id=" + id,
            dataType: "json",
            success: function (data)
            {

                if (data.result == 0)
                {
                    var icon = $("._click i");
                    var bt = $("._click");
                    var row = table.row(bt.parent().parent()).index();

                    if (data.data == 1)
                    {
                        icon.removeClass("fa-check").addClass("fa-ban");
                        bt.removeClass("btn-success").addClass("btn-danger");
                        table.cell(row, 2).data("正常");
                    }
                    else if (data.data == 2)
                    {
                        icon.removeClass("fa-ban").addClass("fa-check");
                        bt.removeClass("btn-danger").addClass("btn-success");
                        table.cell(row, 2).data("停用");
                    }
                }
            },
            error: function (msg)
            {

            },
            complete: function ()
            {
                $("._click").removeAttr("disabled");
                $("._click").removeClass("_click");
            }
        })
    }
</script>

<!-- 删除确认 -->
<div class="modal fade" id="delete-role" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">删除角色</h4>
                <span style="display:none;" id="del-role-id"></span>
            </div>
            <div class="modal-body">
                您确定你要删除角色<span class="del-role-name text-red"></span>吗？
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <a class="btn btn-danger" href="">删除角色</a>
            </div>
        </div>
    </div>
</div>