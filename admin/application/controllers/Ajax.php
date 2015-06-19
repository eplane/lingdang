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
        $urls[] = 'role/roles.html';

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

    /**
     * 单文件上传
     */
    public function upload_1()
    {
        // 在base的构造里已经判断了是否登录

        //请求地址过滤
        $urls[] = 'doc/add.html';
        $urls[] = 'doc/edit';

        if (FALSE == $this->url($urls))
        {
            $this->send(array('success' => 'FALSE', 'result' => 1, 'msg' => 'ajax请求非法'));
            return;
        }

        $id = $this->input->post('id');
        $multi = $this->input->post('m');

        $this->load->library('File');

        //上传文件
        $r = $this->file->upload($id);

        //是否要上传多个文件
        if ($multi)
        {
            //保存临时文件的信息
            $temp = $_SESSION[$id];

            //清除临时文件缓存，以便相同名字能上传多个文件
            unset($_SESSION[$id]);

            $name = get_file_name($temp['file']);

            $_SESSION[$name] = $temp;
        }

        //echo '{"success":"' . $r['success'] . '" ,"link":"' . get_temp_file($r['data']['file_name']) . '", "msg":"' . $r['message'] . '"}';

        echo json_encode(array('link' => get_temp_file($r['data']['file_name'])));
    }


    public function delete_1()
    {
        // 在base的构造里已经判断了是否登录

        $urls[] = 'doc/add.html';
        $urls[] = 'doc/edit';

        if (FALSE == $this->url($urls))
        {
            $this->send(array('success' => 'FALSE', 'result' => 1, 'msg' => 'ajax请求非法'));
            return;
        }

        $this->load->library('File');

        $file = $this->input->post('file');

        $file = get_file_fullname($file);

        if (FALSE == $this->file->delete($file))
        {
            $this->file->delete_temp($file);
        }
    }
}