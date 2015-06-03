<?php

//TODO : 添加缓冲支持，对相同内容的二维码，直接返回缓冲
class PHPQRCode
{
    private $config;

    public function __construct()
    {
        require_once(APPPATH . 'third_party/PHPQRCode/lib/PHPQRCode.php');

        $CI                   = &get_instance();
        $this->config         = $CI->config->item('qrcode');
        $this->config['root'] = $CI->config->item('root_dir');
    }

    private function image($content)
    {
        $path = $this->config['root'] . $this->config['path'];
        $name = microtime(TRUE) . '.png';
        $img  = $path . $name;

        //生成二维码图片
        PHPQRCode\QRcode::png($content, $img, $this->config['level'], $this->config['size'], $this->config['margin']);

        $logo = $this->config['logo'];
        //$QR   = 'qrcode.png';//已经生成的原始二维码图

        if ($logo != FALSE)
        {
            $QR             = imagecreatefromstring(file_get_contents($img));
            $logo           = imagecreatefromstring(file_get_contents($logo));
            $QR_width       = imagesx($QR);//二维码图片宽度
            $QR_height      = imagesy($QR);//二维码图片高度
            $logo_width     = imagesx($logo);//logo图片宽度
            $logo_height    = imagesy($logo);//logo图片高度
            $logo_qr_width  = ($QR_width * $this->config['logo_size']);
            $scale          = $logo_width / $logo_qr_width;
            $logo_qr_height = ($logo_height / $scale);
            $from_width     = (($QR_width - $logo_qr_width) / 2);

            $r['x1'] = ($from_width - $this->config['size'] / 2);
            $r['y1'] = ($from_width - $this->config['size'] / 2);
            $r['x2'] = ($r['x1'] + $logo_qr_width + $this->config['size']) - 1;
            $r['y2'] = ($r['y1'] + $logo_qr_width + $this->config['size']) - 1;

            imagefilledrectangle($QR, $r['x1'], $r['y1'], $r['x2'], $r['y2'], 0);

            //重新组合图片并调整大小
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,
                               $logo_qr_height, $logo_width, $logo_height);

            //输出图片
            $old  = $name;
            $name = microtime(TRUE) . '.png';
            imagepng($QR, $path . $name);
            unlink($path . $old);
        }

        return '<img src="' . base_url() . $this->config['path'] . $name . '">';
    }

    public function encode($content)
    {
        $CI = &get_instance();

        $CI->load->library('ECache');

        $r = $CI->ecache->get(md5($content), function ($key) use ($content, $CI)
        {
            $r = $this->image($content);

            $CI->ecache->set($key, $r);
        });

        return $r;
    }
}