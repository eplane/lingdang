<link href="<?php echo css_path();?>iCheck/all.css" rel="stylesheet" type="text/css" />

<?php echo validation_errors('<span class="text-red">','</span>'); ?>

<?php echo form_open(base_url().'admin/user/me.html',Array('enctype'=>'multipart/form-data'));?>
<div class="form-horizontal">
<div class="box box-primary">
	<div class="box-header"><i class="fa fa-user"></i><h3 class="box-title">个人信息</h3></div>
	<div class="box-body">	

		<div  class="form-group">
			<div class="col-md-4">
				<label for="uid">用户ID </label>
				<input type="text" id="uid" name="uid" class="form-control" value="<?php echo $user['uid']; ?>" disabled="">
			
				<label for="nickname" style="margin-top:15px;">昵称 </label>
				<input type="text" id="nickname" name="nickname" class="form-control" placeholder="昵称" value="<?php echo set_value('nickname', $user['nickname']); ?>" >
			</div>

			<div class="col-md-4">
				<label>头像 </label><br>
				<div style="width:108px;height:108px;border:1px solid #ccc; cursor:pointer;" id="avatar" src="<?php echo efile_get_img($user['avatar']); ?>">

				</div>
			</div>

		</div>



		<div  class="form-group">
			<div class="col-md-4">
				<label for="email">Email </label>
				<div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                    <input id="email" name="email" class="form-control" placeholder="Email" type="text" value="<?php echo set_value('email', $user['email']); ?>">
                </div>
			</div>

			<div class="col-md-4">
				<label for="mobile">手机 </label>
				<div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                    <input class="form-control" placeholder="手机" type="text" id="mobile" name="mobile" value="<?php echo set_value('mobile', $user['mobile']); ?>">
                </div>				
			</div>
		</div>
	

		<div  class="form-group">
			<div class="col-md-4">
				<label for="name">真实姓名 </label>
				<input type="text" id="name" name="name" class="form-control" placeholder="真实姓名" value="<?php echo set_value('name', $user['name']);?>">
			</div>

			<div class="col-md-4">
	            <label for="birthday">生日</label>
	            <div class="input-group">
	                <div class="input-group-addon">
	                    <i class="fa fa-calendar"></i>
	                </div>
	                <input id="birthday" name="birthday" class="form-control" data-inputmask="'alias': 'yyyy/mm/dd'" data-mask="" type="text" value="<?php echo set_value('birthday', $user['birthday']); ?>">
	            </div><!-- /.input group -->
            </div>
		</div>
		<div  class="form-group">
            <div class="col-md-4">
            	<label>性别 </label><br>
				
				<input type="radio" id="male" name="sex" value="male" <?php echo set_radio('sex', 'male', $user['sex']=='male');?>>
				<label for="male" style="margin:0 15px 0 5px;">男 </label>

				<input type="radio" id="famale" name="sex" value="famale"   <?php echo set_radio('sex', 'famale', $user['sex']=='famale');?>>
				<label for="famale" style="margin:0 15px 0 5px;">女 </label>

				<input type="radio" id="null" name="sex" value="null" <?php echo set_radio('sex', 'null', $user['sex']=='null');?>>
				<label for="null">保密 </label>	

			</div>
        </div>
			
		<br>
        <div  class="form-group">
			<div class="col-md-12">
				<button class="btn btn-default" id="save">保存 <i class="fa fa-arrow-circle-right"></i></button>
				<button class="btn btn-default" id="change" data-toggle="modal" data-target="#change-psw">修改密码 <i class="fa fa-arrow-circle-right"></i></button>
			</div>
		</div>


	</div>
</div>

</div>

</form>

<!-- InputMask -->
<script src="<?php echo js_path(); ?>plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
<script src="<?php echo js_path(); ?>plugins/input-mask/jquery.inputmask.date.extensions.js" type="text/javascript"></script>
<script src="<?php echo js_path(); ?>plugins/input-mask/jquery.inputmask.extensions.js" type="text/javascript"></script>

<script type="text/javascript">

$(document).ready(function (){

	$("#birthday").inputmask();

	$('input[type="radio"]').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue'
	});

	$("#avatar").easyimagefile();

});

function change()
{
	
}

 </script>

 <script src="<?php echo js_path(); ?>plugins/iCheck/icheck.min.js" type="text/javascript"></script>
 <script src="<?php echo js_path(); ?>easyform.js" type="text/javascript"></script>

 <!-- 修改密码 -->
<div class="modal fade" id="change-psw" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">修改密码</h4>
      </div>
      <div class="modal-body">
        	
        	<div class="form-horizontal">
			<form>
				
				<div  class="form-group">
					<div class="col-md-8">
					<label for="opsw">旧密码 </label>
					<input type="password" id="opsw" name="opsw" class="form-control" value="">
					</div>
				</div>

				<div  class="form-group">
					<div class="col-md-8">
					<label for="opsw">新密码 </label>
					<input type="password" id="opsw" name="opsw" class="form-control" value="">
					</div>
				</div>

				<div  class="form-group">
					<div class="col-md-8">
					<label for="opsw">确认新密码 </label>
					<input type="password" id="opsw" name="opsw" class="form-control" value="">
					</div>
				</div>

			</form>
			</div>
			

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
        <button type="button" class="btn btn-danger" onclick="change();">修改密码</button>
      </div>
    </div>
  </div>
</div>