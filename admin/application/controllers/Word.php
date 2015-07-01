<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('Controller_base.php');

class Word extends Controller_base
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('m_word', 'mword');
        $this->load->helper('Mongo');
    }

    public function index()
    {
        $this->is_permit('关键词修改', TRUE);

        $this->load->helper(array('form'));
        $this->load->library('form_validation');

        //路径导航条数据
        $data['nav'] = ['主页' => 'main.html', '关键词' => ''];

        $data['word'][] = $this->mword->get('禁止词');

        if ($this->form_validation->run('word')==FALSE)
        {
            $this->view('word_list', $data);
        }
        else
        {
            $word0 = $this->input->post('word0', TRUE);

            $this->mword->set('禁止词', $word0);

            $this->view('word_list', $data);
        }
    }


}