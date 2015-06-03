<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Captcha
{
    private $time_out = 0;
    private $CI = NULL;

    public function __construct()
    {
        $this->CI = &get_instance();

        //$this->CI->load->library('Edb');
        $this->CI->load->library('Session');

        $this->time_out = (int)$this->CI->config->item('expiration');
    }

    public function create($w = 123, $h = 41)
    {
        $this->CI->load->helper('captcha');

        $vals = array(
            'img_path' => APPPATH. $this->CI->config->item('captcha_path'),
            'img_url' => base_url() . $this->CI->config->item('captcha_path'),
            'img_width' => $w,
            'img_height' => $h,
            'expiration' => $this->CI->config->item('expiration'),
            'word_length' => 4,
            'pool' => '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ',
            //'word'       => rand(1000, 9999)
        );

        if (FALSE == is_dir($vals['img_path']))
        {
            mkdir($vals['img_path']);
        }

        $img = create_captcha($vals);

        $data['time'] = time();
        $data['ip'] = $this->CI->input->ip_address();
        $data['word'] = strtolower($img['word']);
        $data['img'] = $img['filename'];

        $old = $this->CI->session->captcha;

        if (!!$old)
        {
            if (file_exists($vals['img_path'] . $old['img']))
                unlink($vals['img_path'] . $old['img']);
        }

        $this->CI->session->captcha = $data;

        return $img;
    }

    public function validation($word)
    {
        $this->clear();

        $ip = $this->CI->input->ip_address();

        $time = time() - $this->time_out;

        $data = $this->CI->session->captcha;

        if (!$data || $data['ip'] != $ip || $data['time'] < $time || $data['word'] != strtolower($word))
        {
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }


    /*
     * 删除过期数据
     * */
    private function clear()
    {
        //每隔一段时间，删除一次旧文件
        $this->CI->load->library('ECache');
        $last = $this->CI->ecache->get('captcha.clear');
        $now = time();
        if ($now - $last < 600)
            return;

        //删除文件
        $captcha_dir = APPPATH . '../' . $this->CI->config->item('captcha_path');

        $captcha_list = scandir($captcha_dir);

        $time = time() - $this->time_out;

        foreach ($captcha_list as $file)
        {
            $file_location = $captcha_dir . "/" . $file;

            if (is_dir($file_location) && $file != "/." && $file != "/..")
            {
                continue;
            }

            if (filemtime($file_location) < $time)
            {
                unlink($file_location);
            }
        }

        $this->CI->ecache->set('captcha.clear', time());
    }
}