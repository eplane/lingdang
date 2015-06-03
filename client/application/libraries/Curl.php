<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
 * Curl
 *
 *
 *
 * ---------------------------------[更新列表]---------------------------------
 */
class Curl
{
    public function get($url, $data)
    {
        //整理参数格式
        $temp = '?';
        foreach ($data as $k => $v)
        {
            $temp .= $k . '=' . $v . '&';
        }
        $p = substr($temp, 0, strlen($temp) - 1);

        //将参数链接到url上
        $uri = $url . $p;

        //初始化
        $ch = curl_init($uri);

        //配置
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    //忽略SSL协议证书

        //执行
        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }

    public function http_post_json($url, $data)
    {
        $ch = curl_init();

        //var_dump($data);

        $json = json_encode($data, JSON_UNESCAPED_UNICODE);

        //var_dump($json);

        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    //是否要求返回数据
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($json))
        );

        $output = curl_exec($ch);

        //var_dump(curl_error($ch));
	//echo '<br>';

        curl_close($ch);

        return $output;
    }

    /** 证书必须是pem格式
     * @param $url
     * @param $data
     * @param $cert
     * @param string $psw
     * @return mixed
     */
    public function https_post_json($url, $data, $cert, $psw = '')
    {
        $ch = curl_init();

        $json = json_encode($data);
        // $json = urldecode(json_encode($data));

        //var_dump($json);

        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
        curl_setopt($ch, CURLOPT_POST, 1);
        //curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    //是否要求返回数据
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($json))
        );

        //证书配置
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); //信任任何证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // 检查证书中是否设置域名,0不验证
        curl_setopt($ch, CURLOPT_SSLCERT, $cert); //证书文件路径
        curl_setopt($ch, CURLOPT_SSLCERTPASSWD, $psw); //client证书密码


        $output = curl_exec($ch);

        //var_dump(curl_error($ch));

        curl_close($ch);

        return $output;
    }
}