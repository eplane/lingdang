<link href="<?php echo get_path('css'); ?>datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css"/>


<div class="box box-primary">
    <div class="box-header"><i class="fa fa-users"></i>

        <h3 class="box-title">管理员列表</h3></div>
    <div class="box-body">

        <div style="margin-bottom:-30px;">
            <a href="<?php echo base_url(); ?>admin/role/add.html" class="btn btn-default"><i class="fa fa-users"></i>
                添加管理员</a>
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

            <?php foreach ($data as $row): ?>
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
<script src="<?php echo get_path('js'); ?>plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
<script src="<?php echo get_path('js'); ?>plugins/datatables/cn.js" type="text/javascript"></script>
<script src="<?php echo get_path('js'); ?>common.js" type="text/javascript"></script>
<script type="text/javascript">

    var table;

    table = $('#list').dataTable({
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
                "render": function (data, type, row) {

                    if (row[5] == "正常")
                        html = '<button class="btn btn-danger btn-flat mini" onclick="$(this).addClass(\'_click\');admin_toggle(' + row[0] + ');"><i class="fa fa-fw fa-ban"></i></button>';
                    else
                        html = '<button class="btn btn-success btn-flat mini" onclick="$(this).addClass(\'_click\');admin_toggle(' + row[0] + ');"><i class="fa fa-fw fa-check"></i></button>';

                    return html;
                }
            }
        ]
    }).api();

    function admin_toggle(id) {

        var bt = $("._click");
        $(bt).attr("disabled", true);

        $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>ajax/admin_toggle.html",
            data: "id=" + id,
            dataType: "json",
            success: function (data) {

                if (data.result == 0) {
                    var icon = $("._click i");
                    var bt = $("._click");
                    var row = table.row(bt.parent().parent()).index();

                    if (data.data == 1) {
                        icon.removeClass("fa-check").addClass("fa-ban");
                        bt.removeClass("btn-success").addClass("btn-danger");
                        table.cell(row, 5).data("正常");
                    }
                    else if (data.data == 2) {
                        icon.removeClass("fa-ban").addClass("fa-check");
                        bt.removeClass("btn-danger").addClass("btn-success");
                        table.cell(row, 5).data("停用");
                    }
                }
            },
            error: function (msg) {

            },
            complete: function () {
                $("._click").removeAttr("disabled");
                $("._click").removeClass("_click");
            }
        })
    }

</script>
