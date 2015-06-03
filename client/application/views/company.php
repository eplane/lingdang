<?php echo validation_errors('<span class="text-red">','</span>'); ?>
<?php echo form_open(base_url().'admin/company.html', Array('enctype'=>'multipart/form-data'));?>
<form>
	<div class="form-horizontal">
	<div class="box box-primary">
		<div class="box-header"><i class="fa fa-user"></i><h3 class="box-title">公司信息</h3></div>
		<div class="box-body">

			<div  class="form-group">
			<div class="col-md-2">
				<label>Logo </label><br>
				<div style="width:116px;height:116px;border:1px solid #ccc;" id="logo" name="logo" src=""></div>
			</div>

			<div class="col-md-6">
				<label for="name">公司名称 </label>
				<input type="text" id="name" name="name" class="form-control" placeholder="公司名称">
			
				<label for="address" style="margin-top:15px;">公司地址 </label>
				<input type="text" id="address" name="address" class="form-control" placeholder="公司地址">				
			</div>			
			</div>

			<div  class="form-group">
			<div class="col-md-4">
				<label for="email">email </label>
				<div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                    <input id="email" name="email" class="form-control" placeholder="Email" type="text">
                </div>
			</div>

			<div class="col-md-4">
				<label for="alias">显示名称 </label>
				<input type="text" id="alias" name="alias" class="form-control" placeholder="显示名称">
			</div>
			</div>

			<div  class="form-group">
			<div class="col-md-4">
				<label for="email">联系电话1 </label>
				<div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                    <input id="tel1" name="tel1" class="form-control" placeholder="联系电话" type="text">
                </div>
			</div>

			<div class="col-md-4">
				<label for="mobile">联系电话2 </label>
				<div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                    <input id="tel2" name="tel2" class="form-control" placeholder="联系电话" type="text">
                </div>				
			</div>
			</div>		


			<div class="form-group">
			<div class="col-md-8">
				<label for="summary">公司简介</label>
				<textarea id="summary" name="summary" class="form-control" placeholder="公司简介"></textarea>
			</div>
			</div>

			<div class="form-group">

			<label style="margin-left:15px;">公司位置</label><br>
			<div class="col-md-2">
				<select class="form-control">
					<option>辽宁省</option>
				</select>
			</div>
			<div class="col-md-3">
				<select class="form-control">
					<option>沈阳市</option>
				</select>
			</div>
			<div class="col-md-3">
				<select class="form-control">
					<option>大东区</option>
				</select>
			</div>

			</div>

			<div class="form-group">
			<div class="col-md-8">
				<label>公司详细位置</label>
				<input type="hidden" id="longitude" name="longitude" value="">
				<input type="hidden" id="latitude" name="latitude" value="">
				
				<div id="map" style="width:100%;height:400px;border:1px solid #ccc;"></div>				
			</div>
			</div>

			<div  class="form-group">
			<div class="col-md-8">
				<button class="btn btn-default">保存 <i class="fa fa-arrow-circle-right"></i></button>
			</div>
			</div>

		</div>
	</div>
	</div>
</form>
<script type="text/javascript" src="http://webapi.amap.com/maps?v=1.3&key=ea421ffdd24384e1d12898abaad605da"></script>
<script type="text/javascript">

		var position = new AMap.LngLat(116.397262, 39.913207);//默认 北京天安门

		//初始化地图对象，加载地图
		var map = new AMap.Map('map',{
			resizeEnable: true,
	        rotateEnable:true,
	        dragEnable:true,
	        zoomEnable:true,
	        //二维地图显示视口
	        //设置地图中心点
	        //设置地图显示的缩放级别
	        view: new AMap.View2D({
	            center: position,
	            zoom: 16 
	        })
	    });

		//地标
	    var customMarker = new AMap.Marker({
				map:map,				
				icon:new AMap.Icon({  //复杂图标
					size:new AMap.Size(20,32),//图标大小
					image:"<?php echo img_path();?>admin/location.png", //大图地址
					imageOffset:new AMap.Pixel(0,0)//相对于大图的取图位置
				})
		});

		customMarker.hide();

	    var clickEventListener=AMap.event.addListener(map,'click',function(e){
			customMarker.setPosition(e.lnglat);
			customMarker.show();

			$("#longitude").val(e.lnglat.lng);
			$("#latitude").val(e.lnglat.lat);
		});
		
</script>

 <script src="<?php echo js_path(); ?>easyform.js" type="text/javascript"></script>

 <script type="text/javascript">

$(document).ready(function (){

	$("#logo").easyimagefile();

});

 </script>