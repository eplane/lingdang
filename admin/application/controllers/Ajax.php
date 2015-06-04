<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('Controller_base.php');

class Ajax extends Controller_base
{
    public function __construct()
    {
        parent::__construct();
    }

    private function send($array)
    {
        echo json_encode($array);
    }

    /** 切换管理员账号权限
     * @param int $id
     */
    public function admin_toggle()
    {
        $id = $this->input->post('id');

        $this->load->model('m_user', 'muser');

        $status = $this->muser->toggle($id);

        $this->send(array('result' => 0, 'data' => $status));
    }
}