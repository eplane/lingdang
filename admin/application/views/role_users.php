<!-- DATA TABLES -->
<link href="<?php echo css_path(); ?>datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
<link href="<?php echo css_path();?>iCheck/all.css" rel="stylesheet" type="text/css" />

<div class="box box-primary">
<div class="box-header"><i class="fa fa-users"></i><h3 class="box-title"><?php echo $role['name'];?></h3></div>
	<div class="box-body">

	<div style="margin-bottom:-30px;">
	<button class="btn btn-default" data-toggle="modal" data-target="#add-user"><i class="fa fa-users"></i> 添加员工</button>
	</div>
	
		<table id="role-users" class="table table-bordered table-hover table-striped" style="width:100%;">
	        <thead>
	            <tr>
	            	<th>id</th>
	                <th width="100">id</th>	                
	                <th>真实姓名</th>
	                <th>昵称</th>
	                <th width="40">操作</th>
	            </tr>
	        </thead>
	        <tbody>
	        	<?php foreach($users as $user):?>
		            <tr>
		                <td><?php echo $user['id'];?></td>
		                <td><?php echo $user['uid'];?></td>		                
		                <td><?php echo $user['name'];?></td>
		                <td><?php echo $user['nickname'];?></td>
		                <td></td>
		            </tr>
	        	<?php endforeach;?>
            </tbody>
		</table>

	</div>
</div>


<!-- DATA TABES SCRIPT -->
<script src="<?php echo js_path(); ?>plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?php echo js_path(); ?>plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
<script src="<?php echo js_path(); ?>plugins/datatables/cn.js" type="text/javascript"></script>
<script src="<?php echo js_path(); ?>plugins/iCheck/icheck.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function() {
        $('#role-users').dataTable({
        	"bLengthChange": false,
        	"language" : ch,
        	"columnDefs": [
        		{
        			"name":"id",
	                "targets": [ 0 ],
	                "visible": false,
	                "searchable": false
	            },
	            {
        			"name":"name",
	                "targets": [ 2 ]
	            },
	            {
	            	"name":"tool",
	                "targets": [ 4 ],
	                "searchable": false,
	                "orderable":false,
	                "render": function ( data, type, row ) {

	                	var html = "<a href=\"javascript:;\" data-toggle=\"modal\" data-target=\"#delete-role\" data-user=\""+row[0]+"\" data-name=\""+row[2]+"\"><i class=\"fa fa-fw fa-times\"></i></a>";

                    	return html;
                	}
	            }
        	]
        });

        $('#userlist').dataTable({
        	"bLengthChange": false,
        	"language" : ch,
        	"columnDefs": [
        		{"targets": [ 0 ],"name":"id","visible": false,"searchable": false},
	            {"targets": [ 1 ],"name":"name","searchable": false, "orderable":false,"width":"1px"},
        	]
        });

        $('input[type="checkbox"].selected-user').iCheck({
	        checkboxClass: 'icheckbox_flat-blue',
	        radioClass: 'iradio_flat-blue'
	    });
    });
</script>

<!-- 删除确认 -->
<div class="modal fade" id="delete-role" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">终止职务</h4>
        <span style="display:none;" id="del-user-id"></span>
      </div>
      <div class="modal-body">
        您确定要终止<span class="text-red" id="del-user-name"></span>的<span class="text-red"><?php echo $role['name'];?></span>职务吗？
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
        <button type="button" class="btn btn-danger" onclick="del();">终止职务</button>
      </div>
    </div>
  </div>
</div>

<!-- 添加员工 -->
<div class="modal fade" id="add-user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">添加员工</h4>
      </div>
      <div class="modal-body">
        
        <form id="form-add-user" method="post" action="<?php echo base_url(); ?>admin/role/user/add/<?php echo $role['id']; ?>.html">
		<table id="userlist" class="table table-bordered table-hover table-striped" style="width:100%;">
	        <thead>
	            <tr>
	            	<th>id</th>
	            	<th></th>
	                <th>id</th>	                
	                <th>真实姓名</th>
	            </tr>
	        </thead>
	        <tbody>
	        	<?php foreach($other_users as $user):?>
		            <tr>
		                <td><?php echo $user['id'];?></td>
		                <td><input type="checkbox" class="selected-user" name="user[]" value="<?php echo $user['id']; ?>"></td>
		                <td><?php echo $user['uid'];?></td>		                
		                <td><?php echo $user['name'];?></td>
		            </tr>
	        	<?php endforeach;?>
            </tbody>
		</table>
		</form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
        <button type="button" class="btn btn-success" onclick="add();">添加员工</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

$(document).ready(function($) {

	$('#delete-role').on('show.bs.modal', function (event){

		var button = $(event.relatedTarget)

		var id = button.data('user')
		var name = button.data('name')

		var modal = $(this)
		modal.find('#del-user-name').html(name)
		modal.find('#del-user-id').html(id)

	});
});

function del()
{
	var id = $("#del-user-id").html();
	window.location.href = "<?php echo base_url();?>admin/role/user/delete/<?php echo $role['id'];?>/"+id+".html";
}

function add()
{
	$("#form-add-user").submit();
}

</script>