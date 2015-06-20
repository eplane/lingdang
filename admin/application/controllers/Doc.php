<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('Controller_base.php');

class Doc extends Controller_base
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('m_doc', 'mdoc');
        $this->load->helper('Mongo');
    }

    public function index()
    {
        $this->is_permit('文档浏览', TRUE);

        //路径导航条数据
        $data['nav'] = ['主页' => 'main.html', '文档管理' => 'doc.html', '文档列表' => ''];

        $data['data'] = $this->mdoc->all(TRUE);

        //var_dump($data['data']);

        $this->view('doc_list', $data);
    }

    //TODO 修改文档时，图片的删除和上传是实时的，即使最后没有按保存，图片也会被修改
    // 这会造成文档内容和图片的不同步。
    private function _doc($data, $id = 0, $cmd = 'add')
    {
        $this->load->library('form_validation');
        $this->load->helper('form');

        // 敏感词验证
        $this->form_validation->set_message('_illegal_words', '%s含有非法词汇!');
        $this->form_validation->set_rules('content', '内容', 'callback__illegal_words');
        $this->form_validation->set_rules('name', '标题', 'callback__illegal_words');

        //执行server端验证
        if ($this->form_validation->run() == FALSE)
        {
            $this->view('doc_edit2', $data);
        }
        else    //表单验证成功
        {
            $doc['name'] = $this->input->post('name');
            $doc['type'] = $this->input->post('type');
            $doc['content'] = $this->input->post('content');
            // var_dump($doc);

            $this->load->library('Content');

            //对文章的内容进行处理
            $doc['content'] = $this->content->save($doc['content']);

            if ($cmd == 'add')
            {
                $this->mdoc->add($doc);
            }
            elseif ($cmd == 'edit')
            {
                $this->mdoc->set($id, $doc);
            }

            //$this->view('doc_edit2', $data);

            redirect(base_url() . 'doc.html');
        }
    }

    public function add()
    {
        $this->is_permit('文档添加', TRUE);

        //路径导航条数据
        $data['nav'] = ['主页' => 'main.html', '文档管理' => 'doc.html', '添加文档' => ''];

        $data['doc'] = Array('type' => '用户协议', 'name' => '', 'content' => '');

        $this->_doc($data);
    }

    public function edit($id)
    {
        $this->is_permit('文档修改', TRUE);

        //路径导航条数据
        $data['nav'] = ['主页' => 'main.html', '文档管理' => 'doc.html', '修改文档' => ''];

        $data['doc'] = $this->mdoc->get($id);

        //var_dump($data['doc']);

        $this->_doc($data, $id, 'edit');
    }

    public function delete($id)
    {
        $this->is_permit('文档删除', TRUE);

        $this->mdoc->delete($id);

        redirect(base_url() . 'doc.html');
    }
}