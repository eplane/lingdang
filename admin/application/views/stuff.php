<link href="<?php echo css_path();?>iCheck/all.css" rel="stylesheet" type="text/css" />

<div class="form-horizontal">
<div class="box box-primary">
	<div class="box-header"><i class="fa fa-user"></i><h3 class="box-title">个人信息</h3></div>
	<div class="box-body">	

		<div class="form-group">
			<div class="col-md-4">
				<label for="uid">用户ID </label>
				<!-- <div><?php echo $stuff['uid']; ?></div> -->
				<input type="text" id="uid" name="uid" class="form-control" value="<?php echo $stuff['uid']; ?>" disabled="">
			
				<label for="nickname" style="margin-top:15px;">昵称 </label>
				<input type="text" id="nickname" name="nickname" class="form-control" placeholder="昵称" value="<?php echo $stuff['nickname']; ?>" disabled="">
			</div>

			<div class="col-md-4">
				<label>头像 </label><br>
				<img src="<?php echo efile_get_img($stuff['avatar']);?>" style="width:108px;height:108px;border:1px solid #ccc;cursor:not-allowed;" id="avatar">
			</div>

		</div>

		<div  class="form-group">
			<div class="col-md-4">
				<label for="email">Email </label>
				<div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                    <input id="email" name="email" class="form-control" placeholder="Email" type="text" value="<?php echo $stuff['email']; ?>" disabled="">
                </div>
			</div>

			<div class="col-md-4">
				<label for="mobile">手机 </label>
				<div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                    <input class="form-control" placeholder="手机" type="text" id="mobile" name="mobile" value="<?php echo $stuff['mobile']; ?>" disabled="">
                </div>				
			</div>
		</div>

		<div  class="form-group">
			<div class="col-md-4">
				<label for="name">真实姓名 </label>
				<input type="text" id="name" name="name" class="form-control" placeholder="真实姓名" value="<?php echo $stuff['name']; ?>" disabled="">
			</div>

			<div class="col-md-4">
	            <label for="birthday">生日</label>
	            <div class="input-group">
	                <div class="input-group-addon">
	                    <i class="fa fa-calendar"></i>
	                </div>
	                <input id="birthday" name="birthday" class="form-control" data-inputmask="'alias': 'yyyy/mm/dd'" data-mask="" type="text" value="<?php echo $stuff['birthday'];?>" disabled="">
	            </div><!-- /.input group -->
            </div>
		</div>
		<div  class="form-group">
            <div class="col-md-4">
            	<label>性别 </label><br>			
				
				<input type="radio" id="male" name="sex" value="male"  <?php echo $stuff['sex']=='male'?'checked="checked"':'';?> disabled="">
				<label for="male" style="margin:0 15px 0 5px;">男 </label>				
				
				<input type="radio" id="famale" name="sex" value="famale"   <?php echo $stuff['sex']=='famale'?'checked="checked"':'';?> disabled="">
				<label for="famale" style="margin:0 15px 0 5px;">女 </label>				
				
				<input type="radio" id="null" name="sex" value="null" <?php echo $stuff['sex']=='null'?'checked="checked"':'';?> disabled="">
				<label for="null">保密 </label>
				
			</div>
        </div>
	</div>
</div>

</div>

<div class="form-horizontal">
<div class="box box-primary">
	<div class="box-header"><i class="fa fa-user"></i><h3 class="box-title">职务信息</h3></div>
	<div class="box-body">
    	<form action="<?php echo base_url();?>admin/stuff/change_role.html" method="post">
        <input type="hidden" value="<?php echo $stuff['id'];?>" name="user">
        <div  class="form-group">
			<div class="col-md-12">
            
            	<label>职务 </label><br>
                
                <?php foreach($roles as $r):?>
				
				<input type="checkbox" id="role-<?php echo $r['id'];?>" name="role[]" value="<?php echo $r['id'];?>"
					<?php if(is_array($stuff['role'])) foreach ($stuff['role'] as $sr) {
						if( $r['id'] == $sr['id'] )
						{
							echo 'checked="checked"';
						}
					}?>
				>
				<label for="male" data-toggle="tooltip" title="<?php echo $r['explain'];?>" style="margin:0 15px 0 5px;"><?php echo $r['name'];?></label>
                
                <?php endforeach;?>	
						
			</div>
		</div>
    
    	<br>
        <div  class="form-group">
			<div class="col-md-12">
				<button class="btn btn-default" id="sendEmail">保存 <i class="fa fa-arrow-circle-right"></i></button>
			</div>
		</div>
        </form>    	
    </div>
</div>
</div>



<!-- InputMask -->
<script src="<?php echo js_path(); ?>plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
<script src="<?php echo js_path(); ?>plugins/input-mask/jquery.inputmask.date.extensions.js" type="text/javascript"></script>
<script src="<?php echo js_path(); ?>plugins/input-mask/jquery.inputmask.extensions.js" type="text/javascript"></script>
<script src="<?php echo js_path(); ?>plugins/iCheck/icheck.min.js" type="text/javascript"></script>

<script type="text/javascript">

$(document).ready(function (){

	$('input[type="checkbox"]').iCheck({
	        checkboxClass: 'icheckbox_square-blue',
	        radioClass: 'iradio_square-blue'
	});

});

 </script>