<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('m_base.php');

class m_email extends m_base
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library('email');

        $config = $this->config->item('email');
        $this->email->initialize($config);
        $this->email->from($config['service-email'], $config['service-name']);
    }

    public function forget_password()
    {
        $user = $this->session->user;

        $this->email->to('Lanfei.Li@Bluemobi.cn');
        $this->email->subject('密码修改');
        $this->email->message('<h1>您修改了密码</h1><br>如果这不是您本人的操作，请立刻联系本站。');

        return $this->email->send();
    }
}