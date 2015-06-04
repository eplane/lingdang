<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller
{
    public function qr()
    {
        $this->load->add_package_path(APPPATH . 'third_party/PHPQRCode/');
        $this->load->library('PHPQRCode', NULL, 'qr');

        echo $this->qr->encode('a你好a!！');
    }

    public function en()
    {
        $str = '三翻四复是否{}dfasfdsafasdfdasfa{}{}{}{}';

        $zip = gzdeflate($str, 1);

        $this->load->library('EEncrypt', NULL, 'ee');

        $r1 = $this->ee->encode($str, 'aaaaa');

        $r2 = $this->ee->encode($zip, 'aaaaa');

        var_dump($str);
        var_dump($zip);
    }

    public function mongo()
    {
        $this->load->library('CI_Mongo', 'mongo');

        var_dump($this->mongo);

        $posts = $this->mongo->db->posts->find();

        foreach ($posts as $id => $post)
        {
            var_dump($id);
            var_dump($post);
        }
    }

    public function log()
    {
        $this->log->write(1, '测试');
        $this->log->write(2, '测试');
        $this->log->write(3, '测试');
        $this->log->write(4, '测试');
        $this->log->write(5, '测试');

        $str = $this->log->read('2015-06-04');

        $str = str_replace("\r\n", '<br>', $str);
        echo $str;
    }
}
