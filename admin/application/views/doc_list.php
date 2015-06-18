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
                <th width="120">更新日期</th>
                <th width="70">大小</th>
                <th width="30">操作</th>
            </tr>
            </thead>

            <tbody>

            <?php foreach ($data as $row): ?>
                <tr>
                    <td><?php echo get_mongo_id($row); ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['type']; ?></td>
                    <td><?php echo get_mongo_id_stamp($row, 'Y-m-d H:i:s'); ?></td>
                    <td><?php echo (strlen($row['content'])/1000).'kb'; ?></td>
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
                    return "<a href=\"<?php echo base_url();?>doc/edit/" + row[0] + ".html\" title=\"编辑角色\">" + data + "</a>";
                }
            },
            {
                "name": "tool",
                "targets": [5],
                "searchable": false,
                "orderable": false,
                "render": function (data, type, row)
                {
                    return '<button class="btn btn-danger btn-flat mini"  data-toggle="modal" data-target="#delete-confirm" data-id="' + row[0] + '" data-name="' + row[2] + '" title="删除文档"><i class="fa fa-fw fa-times"></i></button>';
                }
            }
        ]
    }).api();

    $(document).ready(function ()
    {
        $('#delete-confirm').on('show.bs.modal', function (event)
        {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var name = button.data('name');
            var modal = $(this);
            modal.find('.del-item-name').html(name);
            modal.find('#del-item-id').html(id);
            modal.find(".btn.btn-danger").attr("href", "<?php echo base_url();?>doc/delete/" + id + ".html");
        });
    });

</script>

<!-- 删除确认 -->
<div class="modal fade" id="delete-confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">删除文档</h4>
                <span style="display:none;" id="del-item-id"></span>
            </div>
            <div class="modal-body">
                您确定你要删除文档<span class="del-item-name text-red"></span>吗？
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <a class="btn btn-danger" href="">删除文档</a>
            </div>
        </div>
    </div>
</div>