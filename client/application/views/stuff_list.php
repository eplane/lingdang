<link href="<?php echo css_path(); ?>datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />

<div class="box box-primary">
<div class="box-header"><i class="fa fa-user"></i><h3 class="box-title">员工列表</h3></div>
	<div class="box-body">

	<div style="margin-bottom:-30px;">
	<button class="btn btn-default" data-toggle="modal" data-target="#add-user"><i class="fa fa-user"></i> 添加员工</a></button>
	</div>
	
		<table id="rolelist" class="table table-bordered table-hover table-striped" style="width:100%;">
	        <thead>
	            <tr>
	            	<th>id</th>
	                <th width="80">登录id</th>
	                <th width="80">真实姓名</th>
	                <th>职务</th>
	                <th>email</th>
	                <th width="60">电话</th>
	                <th width="60">状态</th>
	                <th width="40">操作</th>
	            </tr>
	        </thead>
	        <tbody>
	        	<?php foreach($stuffs as $row):?>
		            <tr>
		                <td><?php echo $row['id'];?></td>
		                <td><?php echo $row['uid'];?></td>
		                <td><?php echo $row['name'];?></td>
		                <td><?php 
		                	if(!!$row['role'])
		                	{
		                		foreach ($row['role'] as $role) {
		                			echo $role['name'].' ';
		                		}
		                	}		                	
		                ?></td>		                
		                <td><?php echo $row['email'];?></td>
		                <td><?php echo $row['mobile'];?></td>
		                <td><?php echo $row['status'];?></td>
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
<script src="<?php echo js_path(); ?>common.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function() {
        $('#rolelist').dataTable({
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
	                "targets": [ 2 ],
	                "render": function ( data, type, row ) {
	                	var a = '<a href="<?php echo base_url();?>admin/stuff/'+row[0]+'.html">'+data+"</a>";
	                	return a;
	                }
	            },
	            {
        			"name":"role",
	                "targets": [ 3 ],
	                "orderable":false
	            },
	            {
        			"name":"status",
	                "targets": [ 6 ],
	                "render": function ( data, type, row ) {

	                	var value = "未知";

	                	switch(data)
	                	{
	                		case "normal":value = "正常";
	                			break;
	                		case "stop":value = "停用";
	                			break;
	                	}

                    	return value;
                	}
	            },
	            {
	            	"name":"tool",
	                "targets": [ 7 ],
	                "searchable": false,
	                "orderable":false,
	                "render": function ( data, type, row ) {
	                	
	                	var html = "<a href=\"javascript:;\" title=\"删除\" data-toggle=\"modal\" data-target=\"#delete-stuff\" data-id=\""+row[0]+"\" data-name=\""+row[2]+"\"><i class=\"fa fa-fw fa-trash-o\"></i></a>";

                    	return html;
                	}
	            }            
        	]
        });

		$('#delete-stuff').on('show.bs.modal', function (event){

			var button = $(event.relatedTarget)
			var id = button.data('id')
			var name = button.data('name')
			var modal = $(this)
			modal.find('#del-stuff-name').html(name)
			modal.find('#del-stuff-id').html(id)

		});
    });

function search()
{
	var text = $("#search").val();	
	$.ajax({
	  type: "POST",
	  url: "<?php echo base_url();?>admin/stuff/search.html",
	  data: "search="+text,
	  dataType:"json",
	  success: function(json){

		  if( null !=  json)
		  {
			  $("#user #content #id").html(json.id);
			  $("#user #content #uid").html(json.uid);
			  $("#user #content #name").html(json.name);
			  $("#user #content #mobile").html(json.mobile);
			  $("#user #content #email").html(json.email);
			  
			  $("#user #content #uid-c").fadeIn();
			  $("#user #content #name-c").fadeIn();
			  $("#user #content #mobile-c").fadeIn();
			  $("#user #content #email-c").fadeIn();
			  
			  $("#user #content #no-user").hide();

			  $("#add-stuff").removeClass("disabled");
		  }
		  else
		  {
			  $("#user #content #id").html(0);
			  $("#user #content #uid-c").hide();
			  $("#user #content #name-c").hide();
			  $("#user #content #mobile-c").hide();
			  $("#user #content #email-c").hide();
			  
			  $("#user #content #no-user").fadeIn();
			  
			  $("#add-stuff").addClass("disabled");
		  }
		
		  $("#user").fadeIn();
	   }
	});
}

function add()
{
	var id = $("#user #content #id").html();
	window.location.href = "<?php echo base_url();?>admin/stuff/add/"+id+".html"; 
}

function del()
{
	var id = $("#del-stuff-id").html();
	window.location.href = "<?php echo base_url();?>admin/stuff/delete/"+id+".html";
}

</script>

<!-- 添加员工 -->
<div class="modal fade" id="add-user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">添加员工</h4>
      </div>
      <div class="modal-body">
      
      <div class="callout callout-info">
        	<h4>您可以这样添加一个员工：</h4>
            <ol>
            	<li>通过用户id、手机号码或者用户的Email来搜索一个用户。</li>
                <li>十分抱歉，因为直接搜索名字可能带来大量的重名问题，所以我们没有支持通过名字直接搜索。</li>
                <li>你只能搜索到在平台中已注册的用户，所以如果一个员工没有注册账号的话，十分抱歉，他必须注册后才能成为您的员工。</li>
            </ol>
      </div>
      
        
        <form class="form-horizontal" id="form-add-user" method="post" action="<?php echo base_url(); ?>admin/role/stuff/add/<?php echo $role['id']; ?>.html">	
			<div class="form-group">
				<div class="col-md-12">
					<div class="input-group">	                    
	                    <input id="search" name="search" class="form-control" placeholder="请输入要查找的用户id" type="text">
	                    <span class="input-group-addon btn btn-default" onClick="search();"><i class="fa fa-search"></i>搜索</span>
	                </div>
				</div>
			</div>
		</form>
        
        
        <div class="box box-solid" id="user" style="display:none;">        
        <div class="box-header"><h4 class="box-title">您搜索到的员工</h4></div>
        <div class="box-body" id="content">
        	<div id="id" style="display:none;"></div>
        	<div id="uid-c">ID： <span id="uid"></span></div>
            <div id="name-c">姓名： <span id="name"></span></div>
            <div id="mobile-c">电话： <span id="mobile"></span></div>
            <div id="email-c">email： <span id="email"></div>
            <div id="no-user">很抱歉，没有找到符合条件的员工!</div>
         </div>
         </div>
        
        

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
        <button id="add-stuff" type="button" class="btn btn-success disabled" onclick="add();">添加员工</button>
      </div>
    </div>
  </div>
</div>


<!-- 删除确认 -->
<div class="modal fade" id="delete-stuff" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">删除员工</h4>
        <span style="display:none;" id="del-stuff-id"></span>
      </div>
      <div class="modal-body">
        您确定你要删除员工<span id="del-stuff-name" class="text-red"></span>吗？
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
        <button type="button" class="btn btn-danger" onclick="del();">删除员工</button>
      </div>
    </div>
  </div>
</div>