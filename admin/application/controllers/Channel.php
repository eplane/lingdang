<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('Controller_base.php');

class Channel extends Controller_base
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('m_word', 'mword');
        $this->load->helper('Mongo');
    }

    public function index()
    {
        $this->is_permit('频道浏览', TRUE);

        $this->load->helper(array('form'));
        $this->load->library('form_validation');

        //路径导航条数据
        $data['nav'] = ['主页' => 'main.html', '频道管理' => ''];



        $this->view('channel_list', $data);
    }
}