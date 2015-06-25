<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('Controller_base.php');

class Ajax extends Controller_base
{
    public function __construct()
    {
        parent::__construct();
    }

    /** 切换管理员账号权限
     * @param int $id
     */
    public function admin_toggle()
    {
        //请求地址过滤
        $urls[] = 'user/users.html';

        $this->url($urls, TRUE);

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
        $urls[] = 'role/roles.html';

        $this->url($urls, TRUE);

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

    /**
     * 单文件上传
     */
    public function upload()
    {
        //请求地址过滤
        $urls[] = 'doc/add.html';
        $urls[] = 'doc/edit';
        $this->url($urls, TRUE);

        $id = $this->input->post('id');
        $replace = $this->input->post('r');
        $temp = $this->input->post('t');

        $this->load->library('File');

        $r = $this->file->upload($id, $temp, $replace);

        echo json_encode(array('link' => get_file($r['data']['file_name'])));
    }

    public function delete()
    {
        // 在base的构造里已经判断了是否登录
        $urls[] = 'doc/add.html';
        $urls[] = 'doc/edit';

        $this->url($urls, TRUE);

        $this->load->library('File');

        $file = $this->input->post('file');

        $file = get_file_fullname($file);

        if (FALSE == $this->file->delete($file))
        {
            $this->file->delete_temp($file);
        }
    }
}