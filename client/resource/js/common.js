/*
*
* 本文件定义了一组实用的方法。
* 用于简化开发中的js编写。
*/


/**
* 时间戳转日期时间
*
* @data : 时间戳
*
* return : 返回类似 2011-10-05 10:51:32 的时间
*/
function unix2time(data) {
	var idata = parseInt(data);
	
	if( idata <= 0) return '无效时间';
	
	var time = new Date(idata * 1000);

	return time.getFullYear() + '-' + (time.getMonth() + 1) + '-' + time.getDate() + ' ' 
				+ time.getHours() + ':' + time.getMinutes() + ':' + time.getSeconds();
}

/**
* 时间戳转日期
*
* @data : 时间戳
*
* return : 返回类似 2011-10-05 的时间
*/
function unix2date(data) {
	var idata = parseInt(data);
	
	if( idata <= 0) return '无效时间';
	
	var time = new Date(idata * 1000);

	return time.getFullYear() + '-' + (time.getMonth() + 1) + '-' + time.getDate();
}

/**
* 获得js文件绝对路径
*
* @jsFileName : js文件名称
*
* return : js文件的路径，不含文件名。
*
* remark : 目前未发现问题，由于算法较复杂，其可靠性有待验证。
*/
function get_js_path(jsFileName)
{
	var e = {};
	var htmlPath = "";
	var jsPath = "";
	if ( document.location.protocol == 'file:')
	{
		e.BasePath = unescape(document.location.pathname.substr(1) ) ;
		e.BasePath = e.BasePath.replace( /\\/gi, '/' ) ;
		e.BasePath = 'file://' + e.BasePath.substring(0,e.BasePath.lastIndexOf('/')+1) ;
		e.FullBasePath = e.BasePath ;
	}
	else
	{
		e.BasePath = document.location.pathname.substring(0,document.location.pathname.lastIndexOf('/')+1) ;
		e.FullBasePath = document.location.protocol + '//' + document.location.host + e.BasePath ;
	}
	
	htmlPath = e.FullBasePath; 
	var scriptTag = document.getElementsByTagName("script");
	for(var i=0;i<scriptTag.length;i++)
	{
		if(scriptTag[i].src.lastIndexOf(jsFileName) >= 0)
		{   
			var src = scriptTag[i].src.replace( /\\/gi, '/') ;//把\转换为/
			if(src.toLowerCase().indexOf("file://")==0)
			{//http全路径形式 file://
				var _temp = src.substring(0,src.lastIndexOf('/')+1);
				jsPath = _temp;
				//alert("file://")
			}
			else if(src.toLowerCase().indexOf("http://")==0)
			{//http全路径形式 http://
				var _temp = src.substring(0,src.lastIndexOf('/')+1);
				jsPath = _temp;    
			//alert("http://")
			}
			else if(src.toLowerCase().indexOf("https://")==0)
			{//http全路径形式 https://
				var _temp = src.substring(0,src.lastIndexOf('/')+1);
				jsPath = _temp;    
			//alert("https://")
			}
			else if(src.toLowerCase().indexOf("../")==0)
			{//相对路径形式 ../
				jsPath = htmlPath + src.substring(0,src.lastIndexOf('/')+1);
			//alert("../")
			}
			else if(src.toLowerCase().indexOf("./")==0)
			{//相对路径形式 ./
				jsPath = htmlPath + src.substring(0,src.lastIndexOf('/')+1);
			//alert("./")
			}else if(src.toLowerCase().indexOf("/")==0)
			{//相对路径形式 /,只有采用http访问时有效
				if(document.location.protocol == 'http:' || document.location.protocol == 'https:')
				{
					var _temp = document.location.protocol + "//" + document.location.host + src.substring(0,src.lastIndexOf('/')+1);
					jsPath = _temp;
				}
			//alert("/")
			}
			else if(src.toLowerCase().search(/^([a-z]{1}):/) >=0)
			{//盘符形式 c:
				var _temp = src.substring(0,src.lastIndexOf('/')+1);
				jsPath = _temp;
			//alert("^([a-z]+):")
			}
			else
			{//同级形式
				jsPath = htmlPath;
			}
		}
	}
	
	return jsPath;
}