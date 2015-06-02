<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('client_base.php');

class Portal extends client_base
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_article', 'marticle');
    }

    public function index()
    {
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');

        $this->view($this->get_template_path(), 'login');
    }

    /** 页面载入
     * @param int $id 文章id
     */
    public function p($id)
    {
        //参数合法性判断

        //获取数据
        $data['title'] = '测试文章';
        $data['subtitle'] = '测试文章';
        $data['content'] = '测试文章';
        $data['update'] = 0;

        //载入视图，传递参数
        $this->view($this->get_template_path(), 'read', $data);
    }

    public function t()
    {
        $dir = 'portal/default/';

        $data['title'] = '测试文章';
        $data['subtitle'] = '测试文章';
        $data['content'] = '测试文章';
        $data['update'] = 0;

        $this->view($this->get_template_path(), 'main', $data);
    }
}