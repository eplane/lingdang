<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('m_base.php');

class m_user extends m_base
{
    public function __construct()
    {
        parent::__construct();
    }

    /** 用户登录
     * @param $uid
     * @param $password
     * @return bool
     */
    public function login($uid, $password)
    {
        //获得用户数据
        $id = $this->get_id($uid);
        $user = $this->get($id, TRUE);

        if (password_verify($password, $user['psw']))
        {
            //判断是否拥有合法的角色
            if ($user['status'] == 'normal' && count($user['role']) > 0)
            {
                //建立session
                $_SESSION['me'] = $user;
                return TRUE;
            }
            else
            {
                return 2;//账户被停用;
            }
        }

        return 1; // 用户名或密码错误;
    }

    public function logout()
    {
        $this->session->sess_destroy();
    }

    public function get($id, $refresh = FALSE)
    {
        if (!!$id)
        {
            $login = $this->edb->get_one($refresh, 'user', '`id` = ' . $id);

            $info = $this->edb->get_one($refresh, 'user_info', '`id` = ' . $id);

            $roles['role'] = [];

            //获得角色
            if (isset($info['role']))
            {
                $this->load->model('m_role', 'mrole');
                $roles['role'] = $this->mrole->gets($info['role'], TRUE, $refresh);
            }

            $data = array_merge($login, $info, $roles);

            return $data;
        }
        else
            return NULL;
    }

    public function get_id($uid, $refresh = FALSE)
    {
        if (!!$uid)
        {
            $id = $this->edb->get_one($refresh, 'user', '`uid`="' . $uid . '"', '`id`');

            if ($id != FALSE)
                $id = $id['id'];

            return $id;
        }
        else
            return NULL;
    }
}