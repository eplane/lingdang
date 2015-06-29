<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('Controller_base.php');

class User extends Controller_base
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
        //路径导航条数据
        $data['nav'] = ['主页' => 'main.html', '用户管理' => 'user/users.html', '个人资料' => ''];

        $data['user'] = $this->session->me;

        $this->load->helper(array('form'));
        $this->load->library('form_validation');

        $data['title'] = '我的信息';
        $data['sub_title'] = '管理自己的平台信息';

        if ($this->form_validation->run() == FALSE)
        {
            $this->view('user_me', $data);
        }
        else
        {
            //保存角色信息
            $submit['name'] = $this->input->post('name', TRUE);
            $submit['nick'] = $this->input->post('nick', TRUE);
            $submit['mobile'] = $this->input->post('mobile', TRUE);
            $submit['email'] = $this->input->post('email', TRUE);

            $this->load->library('file');

            $result = $this->file->upload('avatar', TRUE, TRUE, NULL, 500);    //接收客户端文件

            //如果修改了头像
            if ($result['error'] === 'FALSE')
            {
                $submit['avatar'] = $this->file->save('avatar');     //保存文件
            }

            $this->muser->set($data['user']['id'], $submit);

            $_SESSION['me'] = $this->muser->get($data['user']['id'], TRUE);

            $data['user'] = $this->session->me;

            $this->view('user_me', $data);
        }
    }

    public function psw()
    {
        $opsw = $this->input->post('opsw');
        $npsw1 = $this->input->post('npsw1');

        if (password_verify($opsw, $_SESSION['me']['psw']))
        {
            $data['psw'] = password_hash($npsw1, PASSWORD_DEFAULT);

            $this->muser->set($_SESSION['me']['id'], $data);
        }

        redirect(base_url() . 'user/me.html');
    }

    public function users()
    {
        $this->is_permit('用户浏览', TRUE);

        //路径导航条数据
        $data['nav'] = ['主页' => 'main.html', '用户管理' => 'user/users.html', '管理员列表' => ''];

        $data['data'] = $this->muser->gets();

        $this->view('user_list', $data);
    }

    public function add()
    {
        $this->is_permit('用户添加', TRUE);

        $this->load->library('form_validation');
        $this->load->helper('form');

        //路径导航条数据
        $data['nav'] = ['主页' => 'main.html', '用户管理' => 'user/users.html', '添加用户' => ''];

        $data['role'] = $this->mrole->gets();

        if ($this->form_validation->run() == FALSE)
        {
            $this->view('user_add', $data);
        }
        else
        {
            $user['uid'] = $this->input->post('uid', TRUE);
            $user['psw'] = $this->input->post('psw', TRUE);
            $user['mobile'] = $this->input->post('mobile', TRUE);
            $user['email'] = $this->input->post('email', TRUE);
            $user['role'] = implode(',', $this->input->post('role', TRUE));
            //var_dump($user);

            $this->muser->add($user);

            redirect(base_url() . 'user/users.html');
        }
    }

    public function delete($id)
    {
        $this->is_permit('用户删除', TRUE);

        $this->muser->delete($id);

        redirect(base_url() . 'user/users.html');
    }
}