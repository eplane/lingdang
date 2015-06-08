﻿<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('m_user', 'muser');
    }

    /**
     * 登录页面
     */
    public function index()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('uid', '登录', 'callback__login[' . $this->input->post('password') . ']');

        if ($this->form_validation->run() == FALSE)
        {
            $data['tab_title'] = $this->config->item('title');
            $this->load->view('login', $data);
        }
        else
        {
            redirect(base_url() . 'main.html');
        }
    }

    /**
     * 执行登录，因为form验证的原因，写成了这样
     * @param $user
     * @param $password
     * @return bool
     */
    public function _login($user, $password)
    {
        $r = $this->muser->login($user, $password);

        if ($r === TRUE)
        {
            return TRUE;
        }
        else
        {
            if ($r === 1)
                $this->form_validation->set_message('_login', '用户名或密码错误!');
            else
                $this->form_validation->set_message('_login', '账户被停用!');

            return FALSE;
        }
    }


    /**
     * 登出
     */
    public function logout()
    {
        $this->muser->logout();

        redirect(base_url() . 'login.html');
    }
}