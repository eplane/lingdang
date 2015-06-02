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

        if (password_verify($password, $user['password']))
        {
            //判断是否拥有合法的角色
            if (count($user['role']) > 0)
            {
                //建立session
                $_SESSION['me'] = $user;
                return TRUE;
            }
        }

        return FALSE;
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

            //获得角色
            $this->load->model('m_role', 'mrole');
            $roles['role'] = $this->mrole->gets($info['role'], TRUE, $refresh);

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

            if( $id != FALSE )
                $id = $id['id'];

            return $id;
        }
        else
            return NULL;
    }
}