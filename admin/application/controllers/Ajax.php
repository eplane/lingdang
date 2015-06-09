<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('Controller_base.php');

class Ajax extends Controller_base
{
    public function __construct()
    {
        parent::__construct();
    }

    private function url($list)
    {
        $referer = $this->input->server('HTTP_REFERER');

        if (in_array($referer, $list))
        {
            return TRUE;
        }

        return FALSE;
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
        //请求地址过滤
        $urls[] = base_url() . 'user/users.html';

        if (FALSE == $this->url($urls))
        {
            $this->send(array('result' => 1, 'msg' => 'ajax请求非法'));
            return;
        }

        //权限过滤
        if (TRUE === $this->is_permit('用户状态'))
        {
            $id = $this->input->post('id');

            $this->load->model('m_user', 'muser');

            $status = $this->muser->toggle($id);

            $this->send(array('result' => 0, 'data' => $status));
            return;
        }
        else
        {
            $this->send(array('result' => 2, 'msg' => '没有操作权限'));
            return;
        }
    }

    public function role_toggle()
    {
        //请求地址过滤
        $urls[] = base_url() . 'role/roles.html';

        if (FALSE == $this->url($urls))
        {
            $this->send(array('result' => 1, 'msg' => 'ajax请求非法'));
            return;
        }

        //权限过滤
        if (TRUE === $this->is_permit('角色修改'))
        {
            $id = $this->input->post('id');

            $this->load->model('m_user', 'muser');

            $status = $this->mrole->toggle($id);

            $this->send(array('result' => 0, 'data' => $status));
            return;
        }
        else
        {
            $this->send(array('result' => 2, 'msg' => '没有操作权限'));
            return;
        }
    }
}