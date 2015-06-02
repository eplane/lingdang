<?php 
isset($role)?$role:$role=Array('id'=>'','name'=>'','explain'=>'','status'=>'normal','access'=>Array()); 

?>
<link href="<?php echo css_path();?>iCheck/all.css" rel="stylesheet" type="text/css" />

<script>

	$(document).ready(function (){

		$('input[type="checkbox"]').iCheck({
	        checkboxClass: 'icheckbox_square-blue',
	        radioClass: 'iradio_square-blue'
	    });
    });

</script>

<div class="box box-primary">
	<div class="box-header"><i class="fa fa-users"></i><h3 class="box-title">职务</h3></div>
	<div class="box-body">

	<?php echo validation_errors('<span class="text-red">','</span>'); ?>
	<div class="form-horizontal">

	<?php
		if(!!$role)
			echo form_open(base_url().'admin/role/edit/'.$role['id'].'.html');
		else
			echo form_open(base_url().'admin/role/add.html');
	?>

	<!-- <form class="form-horizontal"> -->

		<div  class="form-group">

			<div class="col-md-2">
				<select class="form-control" id="status" name="status">
					<option value="normal" <?php echo set_select('status', 'normal', $role['status']=='normal'); ?>>职务正常</option>
					<option value="stop" <?php echo set_select('status', 'stop', $role['status']=='stop'); ?>>职务停用</option>
				</select>
			</div>

			<div class="col-md-6">
				<input type="text" id="name" name="name" class="form-control" placeholder="职务名称" value="<?php echo set_value('name', $role['name']); ?>">
			</div>

		</div>

		<div class="form-group">
			<div class="col-md-8">
				<textarea id="explain" name="explain" class="form-control" placeholder="职务说明"><?php echo set_value('explain',$role['explain']); ?></textarea>
			</div>
		</div>
		<h4 class="page-header">职务权限</h4>
		<?php $last_group = ''; $first = TRUE; foreach ($accesses as $v):?>

			<?php
			if($v['group'] != $last_group)
			{
				if(FALSE == $first)
				{
					echo '</section></div></div>';
				}

				echo '<div class="form-group">';
				echo '<div class="col-md-12">';
				echo '<section><h5>'.$v['group'].'</h5>';
	
				$last_group = $v['group'];
				$first = FALSE;
			}?>
	
			
			<input class="check-access" id="access-<?php echo $v['id']?>" type="checkbox" name="access[]" value="<?php echo $v['id']?>" 
			<?php echo set_checkbox('access[]', $v['id'], in_array($v['id'], $role['access']) ); ?>>
			<label style="margin:0 15px 0 5px;" data-toggle="tooltip" title="<?php echo $v['explain']?>" for="access-<?php echo $v['id']?>">
			<?php echo $v['name']?></label>
	

		<?php endforeach;?>

        </section></div></div>

		<br>
        <div  class="form-group">
			<div class="col-md-12">
				<button class="btn btn-default">保存 <i class="fa fa-arrow-circle-right"></i></button>
			</div>
		</div>
	</form>
	</div>


	</div>
</div>

<script src="<?php echo js_path(); ?>plugins/iCheck/icheck.min.js" type="text/javascript"></script>
