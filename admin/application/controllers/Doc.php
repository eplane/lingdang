<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('Controller_base.php');

class Doc extends Controller_base
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('m_doc', 'mdoc');
    }

    public function index()
    {
        $this->is_permit('文档浏览', TRUE);

        //路径导航条数据
        $data['nav'] = ['主页' => 'main.html', '文档管理' => 'doc.html', '文档列表' => ''];

        $data['data'] = $this->mdoc->all(TRUE);

        $this->view('doc_list', $data);
    }

    private  function _doc($data, $id = 0, $cmd = 'add')
    {
        $this->load->library('form_validation');
        $this->load->helper('form');

        //执行server端验证
        if ($this->form_validation->run() == FALSE)
        {
            $this->view('doc_edit', $data);
        }
        else    //表单验证成功
        {
            $doc['name']    = $this->input->post('name');
            $doc['type']  = $this->input->post('type');
            $doc['content'] = $this->input->post('content');

           // var_dump($doc);

            $this->load->library('text');

            $r = $this->text->get_img($doc['content']);

            var_dump($r);

            $this->view('doc_edit', $data);

            /*if ($cmd == 'add')
            {
                $this->mdoc->add($doc);
            }
            elseif ($cmd == 'edit')
            {
                $this->mdoc->set($id, $doc);
            }

            redirect(base_url() . 'role/roles.html');*/
        }
    }

    public function add()
    {
        $this->is_permit('文档添加', TRUE);

        //路径导航条数据
        $data['nav'] = ['主页' => 'main.html', '文档管理' => 'doc.html', '添加文档' => ''];

        $data['role'] = Array('id' => '', 'name' => '', 'explain' => '', 'status' => '正常', 'access' => Array());

        $this->_doc($data);
    }
    public function edit()
    {
        $this->is_permit('文档修改', TRUE);
    }

}