<link href="<?php echo get_path('css'); ?>datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css"/>

<div class="box box-primary">
    <div class="box-header"><i class="fa fa-files-o"></i>

        <h3 class="box-title">文档列表</h3></div>
    <div class="box-body">

        <div style="margin-bottom:-30px;">
            <a href="<?php echo base_url(); ?>doc/add.html" class="btn btn-default"><i class="fa fa-file-text-o"></i>
                添加文档</a>
        </div>

        <table id="list" class="table table-bordered table-hover table-striped" style="width:100%;">
            <thead>
            <tr>
                <th>id</th>
                <th>名称</th>
                <th width="80">分类</th>
                <th width="100">更新日期</th>
                <th width="70">大小</th>
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

</script>