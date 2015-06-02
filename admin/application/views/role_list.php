<link href="<?php echo css_path(); ?>datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />

<div class="box box-primary">
<div class="box-header"><i class="fa fa-users"></i><h3 class="box-title">职务列表</h3></div>
	<div class="box-body">

	<div style="margin-bottom:-30px;">
	<a href="<?php echo base_url(); ?>admin/role/add.html" class="btn btn-default"><i class="fa fa-users"></i> 添加职务</a>
	</div>
	
		<table id="rolelist" class="table table-bordered table-hover table-striped" style="width:100%;">
	        <thead>
	            <tr>
	            	<th>id</th>
	                <th>名称</th>
	                <th width="80">状态</th>
	                <th width="120">创建日期</th>
	                <th width="120">修改日期</th>
	                <th width="40">操作</th>
	            </tr>
	        </thead>
		</table>

	</div>
</div>


<!-- DATA TABES SCRIPT -->
<script src="<?php echo js_path(); ?>plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?php echo js_path(); ?>plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
<script src="<?php echo js_path(); ?>plugins/datatables/cn.js" type="text/javascript"></script>
<script src="<?php echo js_path(); ?>common.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function() {
        $('#rolelist').dataTable({
            "processing": true,
        	"serverSide": true,
        	"bLengthChange": false,
        	"language" : ch,
        	"ajax": {"url":"<?php echo base_url();?>admin/role/get_roles.html","type": "POST"},
        	"columnDefs": [
        		{
        			"name":"id",
	                "targets": [ 0 ],
	                "visible": false,
	                "searchable": false
	            },
	            {
	            	"name":"name",
	                "targets": [ 1 ],
	                "render": function ( data, type, row ) {
						var str = "<a href=\"<?php echo base_url();?>admin/role/stuff/"+row[0]+".html\" title=\"点击职务名称可以向该职务添加员工\">"+data+"</a>";
                    	return str;
                	}
	            },
	            {
	            	"name":"status",
	                "targets": [ 2 ],
	                "render": function ( data, type, row ) {
						var str = '未知';
                		switch(data)
                		{
                			case "normal": str = "正常"; break;
                			case "stop": str = "停用"; break;
                			case "delete": str = "删除"; break;
                		}
                    	return str;
                	}
	            },
	            {
	            	"name":"create",
	                "targets": [ 3 ],
	                "render": function ( data, type, row ) {
                    	return unix2time(data);
                	}
	            },
	            {
	            	"name":"update",
	                "targets": [ 4 ],
	                "render": function ( data, type, row ) {
                    	return unix2time(data);
                	}
	            },
	            {
	            	"name":"tool",
	                "targets": [ 5 ],
	                "searchable": false,
	                "orderable":false,
	                "render": function ( data, type, row ) {

	                	var html = '<a title="编辑" href="<?php echo base_url();?>admin/role/edit/'+row[0]+'.html"><i class="fa fa-fw fa-edit"></i></a>';
	                	 html += '<a title="员工列表" href="<?php echo base_url();?>admin/role/stuff/'+row[0]+'.html"><i class="fa fa-fw fa-list"></i></a>';
	                	 html += '<a title="删除" href="javascript:;" data-toggle="modal" data-target="#delete-role" data-id="'+row[0]+'" data-name="'+row[1]+'"><i class="fa fa-fw fa-trash-o"></i></a>';
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
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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

$(document).ready(function($) {

	$('#delete-role').on('show.bs.modal', function (event){

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
	window.location.href = "<?php echo base_url();?>admin/role/delete/"+id+".html";
}

</script>