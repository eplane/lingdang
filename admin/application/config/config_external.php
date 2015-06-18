<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| 附加路径
|--------------------------------------------------------------------------
|
| 除base_url外其他的重要路径
|
*/
$config['other_path']['js'] = 'resource/js/';
$config['other_path']['img'] = 'resource/image/';
$config['other_path']['css'] = 'resource/css/';
$config['other_path']['less'] = 'resource/less/';

$config['other_path']['common-js'] = '../resource/js/';
$config['other_path']['common-css'] = '../resource/css/';
$config['other_path']['common-img'] = '../resource/image/';
$config['other_path']['doc'] = '../resource/doc/';

$config['captcha_path'] = '../resource/temp/';

//Cache Driver
$config['cache_driver'] = 'file';   //缓冲驱动

//网站 title
$config['title'] = '测试';   //系统默认标题

//单位 秒
$config['expiration'] = 300;                //验证码超时
$config['data_timeout'] = 3600;           //数据缓存过期 86400一天    604800一周

//Upload 设置
$config['upload']['upload_path'] = APPPATH . '../../resource/upload/';          //上传文件的根目录
$config['upload']['temp_path'] = APPPATH . '../../resource/temp/';              //上传文件的临时目录
$config['upload']['web_temp'] = 'http://localhost/dog/resource/temp/';       //文件的外部访问根目录
$config['upload']['web_root'] = 'http://localhost/dog/resource/upload/';       //文件的外部访问根目录
$config['upload']['allowed_types'] = 'gif|jpg|png';
$config['upload']['max_size'] = '1000';     //单位 K
$config['upload']['encrypt_name'] = TRUE;  //不允许自动改名





/*
|--------------------------------------------------------------------------
| Email 配置
|--------------------------------------------------------------------------
| protocol：邮件发送协议，mail, sendmail, 或  smtp
| smtp_host：发送服务器
| smtp_user：用户名
| smtp_pass：密码
| smtp_port：端口
| smtp_timeout：超时时长
| newline：换行符
| crlf：换行符
| mailtype：邮件类型。发送 HTML 邮件比如是完整的网页。请确认网页中是否有相对路径的链接和图片地址，它们在邮件中不能正确显示。
| charset：字符集(utf-8, iso-8859-1 等)。
| wordwrap：是否自动换行。
| wrapchars：自动换行时每行的最大字符数。
| service-email：发送者
| service-name：发送者名称
*/
$config['email']['protocol'] = 'smtp';
$config['email']['smtp_host'] = 'ssl://smtp.qq.com';
$config['email']['smtp_user'] = '9475127@qq.com';
$config['email']['smtp_pass'] = 'yrgtqh39db';
$config['email']['smtp_port'] = '465';
$config['email']['smtp_timeout'] = '5';
$config['email']['newline'] = "\r\n";
$config['email']['crlf'] = "\r\n";
$config['email']['mailtype'] = 'html';
$config['email']['charset'] = 'utf-8';
$config['email']['wordwrap'] = TRUE;
$config['email']['wrapchars'] = 76;
$config['email']['service-email'] = 'li-lf@qq.com';         //发送者
$config['email']['service-name'] = '系统服务';                //发送者名称

/*
|--------------------------------------------------------------------------
| PHPQRCode 二维码配置
|--------------------------------------------------------------------------
| logo：如果添加了logo，容错等级就要提高。如果不需要添加 logo 设置成 FALSE。
| logo_size：logo占整个图片的宽度比例
| level：容错级别 L（QR_ECLEVEL_L，7%），M（QR_ECLEVEL_M，15%），Q（QR_ECLEVEL_Q，25%），H（QR_ECLEVEL_H，30%）
| size：每格的像素宽度
| margin：边缘的格子数
| path：生成的图片路径
| back：背景色
| fore：前景色
*/
$config['qrcode']['logo'] = 'http://localhost/wz/admin/resource/temp/96C710AC48FAFD1C11CCE7A17E6DADD1.jpg';
$config['qrcode']['level'] = 'H';
$config['qrcode']['size'] = 6;
$config['qrcode']['margin'] = 1;
$config['qrcode']['path'] = 'resource/temp/';
$config['qrcode']['back'] = '255,255,255';
$config['qrcode']['fore'] = '0,0,0';
$config['qrcode']['logo_size'] = 1 / 5;


/*
|--------------------------------------------------------------------------
| CURL 配置
|--------------------------------------------------------------------------
| url：连接地址
| cert：证书地址，需要服务器物理路径，不是URL路径
*/
$config['curl']['url'] = '';
$config['curl']['cert'] = '';


/*
|--------------------------------------------------------------------------
| Cimongo 配置
|--------------------------------------------------------------------------
| url：连接地址
| cert：证书地址，需要服务器物理路径，不是URL路径
*/
$config['mongo']['host'] = "localhost";
$config['mongo']['port'] = 27017;        // Generally 27017
$config['mongo']['db'] = "bell";
// Required if Mongo is running in auth mode
$config['mongo']['user'] = "";
$config['mongo']['password'] = "";

//  默认FALSE .
//  FALSE ： 程序执行中不会等待数据库请求返回（异步）
// TRUE ： 程序将等待数据库请求返回，如果update请求没有返回succeed ，则跑出MongoCursorException异常
$config['mongo']['query_safety'] = TRUE;

//如果运行于身份验证模式，并且用户不具有全局读写权限，请设置为TRUE
$config['mongo']['db_flag'] = FALSE;