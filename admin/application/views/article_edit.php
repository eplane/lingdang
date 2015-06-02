
<link rel="stylesheet" type="text/css" href="<?php echo js_path();?>plugins/simditor-2.1.2/styles/simditor.css" />

<script type="text/javascript" src="<?php echo js_path();?>plugins/simditor-2.1.2/scripts/module.js"></script>
<script type="text/javascript" src="<?php echo js_path();?>plugins/simditor-2.1.2/scripts/uploader.js"></script>
<script type="text/javascript" src="<?php echo js_path();?>plugins/simditor-2.1.2/scripts/hotkeys.min.js"></script>
<script type="text/javascript" src="<?php echo js_path();?>plugins/simditor-2.1.2/scripts/simditor.js"></script>



<script type="text/javascript">        

    $(document).ready(function () {
	    

	    var editor = new Simditor({
			  textarea: $('#editor'),
			  defaultImage:'<?php echo img_path(); ?>/1.png',
			   upload : {  
		            url : 'ImgUpload.action', //文件上传的接口地址  
		            params: null, //键值对,指定文件上传接口的额外参数,上传的时候随文件一起提交  
		            fileKey: 'fileDataFileName', //服务器端获取文件数据的参数名  
		            connectionCount: 3,  
		            leaveConfirm: '正在上传文件'  
		        }   
			});	    

	});

</script>


<div class="box box-primary">


<div class="box-header"><i class="fa fa-envelope"></i><h3 class="box-title">添加内容</h3></div>

<div class="box-body">

	<form class="form-horizontal">

	<div  class="form-group">

		<div class="col-md-2">
			<select id="channel" name="channel" class="form-control">
				<option>原创</option>
				<option>翻译</option>
				<option>转载</option>
			</select>
		</div>

		<div class="col-md-7"><input type="text" id="title" name="title" class="form-control" placeholder="填写标题"></div>
		
		<div class="col-md-3">
			<select id="channel" name="channel" class="form-control">
				<?php foreach ($channels as $value):?>
					<option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option>
				<?php endforeach;?>
			</select>
		</div>
	</div>


	<div class="form-group">
		<div class="col-md-12">
		<textarea id="editor" placeholder="这里输入内容" autofocus class="form-control"></textarea>
		</div>
	</div>

	<div  class="form-group">
		<div class="col-md-12">
		<button class="pull-right btn btn-default" id="sendEmail">保存 <i class="fa fa-arrow-circle-right"></i></button>
		<button class="pull-right btn btn-default" id="sendEmail" style="margin-right: 5px;">发布 <i class="fa fa-arrow-circle-right"></i></button>
		</div>
	</div>

	</form>

</div>

</div>