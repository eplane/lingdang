<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller
{
    public function qr()
    {
        /*$this->load->add_package_path(APPPATH . 'third_party/PHPQRCode/');
        $this->load->library('PHPQRCode', NULL, 'qr');

        echo $this->qr->encode('a你好a!！');*/

        echo $this->uri->ruri_string();

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
        $this->load->library('CI_Mongo', NULL, 'mongo');

        $this->location->ip();

        $r = $this->mongo->get('log');

        $a = $r->result_array();


        $this->mongo->command();

        //var_dump(json_decode($a[0]['msg'], TRUE));
        var_dump($a);

        /*$r = $this->mongo->get_where('log', array("_id"=>new MongoId('557116c208a9e95812000032')));

        var_dump($r->result_array());*/
    }

    public function log()
    {
        $this->load->library('CI_Mongo', NULL, 'mongo');

        $data['user'] = '000001';
        $data['option'] = '测试';
        $data['active'] = '执行了某个命令';

        $this->log->write(3, $data, $this->mongo);

        $str = $this->log->read('2015-06-05', $this->mongo);


        /* $str = str_replace("\r\n", '<br>', $str);*/
        var_dump($str);
    }

    public function file()
    {
        $this->load->library('File');

        $n = $this->file->create_file_name();

        var_dump($n);

        $d= $this->file->get_path($n);

        var_dump($d);

        $w = $this->file->get_web_path($n);

        var_dump($w);

    }
}
