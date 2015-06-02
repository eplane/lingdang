<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('admin_base.php');

class user extends admin_base
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('m_user', 'muser');
    }

    /**
     * 个人信息配置页
     */
    public function me()
    {
        $user = $this->session->user;

        $this->load->helper(array('form'));
        $this->load->library('form_validation');

        $data['title']     = '我的信息';
        $data['sub_title'] = '管理自己的平台信息';

        $data['user']             = $user;
        $data['user']['birthday'] = date('Y/m/d', $data['user']['birthday']);

        if ($this->form_validation->run() == FALSE)
        {
            $this->view('me', $data);
        }
        else
        {
            //保存角色信息
            $submit['name']     = $this->input->post('name');
            $submit['nickname'] = $this->input->post('nickname');
            $submit['mobile']   = $this->input->post('mobile');
            $submit['email']    = $this->input->post('email');
            $submit['birthday'] = strtotime($this->input->post('birthday'));
            $submit['sex']      = $this->input->post('sex');

            $this->load->library('Efile');

            $result = $this->efile->upload('avatar-file', NULL, 500);    //接收客户端文件

            //如果修改了头像
            if ($result['error'] === 'FALSE')
            {
                $submit['avatar'] = $this->efile->save('avatar-file');     //保存文件
            }

            $this->muser->save($user['id'], $submit);

            $data['user'] = $this->session->user;

             $this->view('me', $data);
        }
    }
}