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
        $id = $this->get_id($uid, TRUE);
        $user = $this->get($id, TRUE);

        if (password_verify($password, $user['psw']))
        {
            //判断是否拥有合法的角色
            if ($user['status'] == '正常' && count($user['role']) > 0)
            {
                //建立session
                $user['ip'] = $this->location->ip();
                $_SESSION['me'] = $user;

                //操作日志
                $log['action'] = 'login';
                $log['id'] = $id;
                $log['ip'] = $user['ip'];
                $this->log->write(2, $log, $this->mongo);

                return TRUE;
            }
            else
            {
                //操作日志
                $log['action'] = 'login';
                $log['id'] = $id;
                $log['ip'] = $this->location->ip();
                $log['msg'] = '停用的账户尝试登陆';
                $this->log->write(4, $log, $this->mongo);

                return 2;//账户被停用;
            }
        }

        //操作日志
        $log['action'] = 'login';
        $log['uid'] = $uid;
        $log['password'] = $password;
        $log['msg'] = '用户名或密码错误';
        $log['ip'] = $this->location->ip();
        $this->log->write(4, $log, $this->mongo);

        return 1; // 用户名或密码错误;
    }

    public function logout()
    {
        //操作日志
        $log['action'] = 'logout';
        $log['id'] = $_SESSION['me']['id'];
        $log['ip'] = $_SESSION['me']['ip'];
        $this->log->write(1, $log, $this->mongo);

        $this->session->sess_destroy();
    }

    /**
     * @param int $id
     * @param bool $refresh
     * @return array|null
     */
    public function get($id, $refresh = FALSE)
    {
        if (!!$id)
        {
            $login = $this->edb->get_row_id($refresh, 'admin', $id);

            $info = $this->edb->get_row_id($refresh, 'admin_info', $id);

            $roles['roles'] = $info ['role'];
            $roles['role'] = [];

            //获得角色
            if (isset($info['role']))
            {
                $this->load->model('m_role', 'mrole');
                $roles['role'] = $this->mrole->get_user_roles($info['role'], TRUE, $refresh);
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
            $id = $this->edb->get_value($refresh, 'admin', '`uid`="' . $uid . '"', 'id');

            return $id;
        }
        else
            return NULL;
    }

    public function gets()
    {
        $this->db->select('*')->from('admin')->where('`status` != 3');
        $this->db->join('admin_info', 'admin_info.id = admin.id');

        $query = $this->db->get();

        return $query->result_array();
    }

    /** 切换用户账户状态
     * @param $id
     * @return int
     */
    public function toggle($id)
    {
        $user = $this->get($id);

        if ($user['status'] == '删除')
            return 3;

        if ($user['status'] == '正常')
        {
            $data['status'] = 2;
        }
        else
        {
            $data['status'] = 1;
        }

        $this->edb->set_row_id('admin', $data, $id);

        //日志
        $log['action'] = 'user/toggle';
        $log['me'] = $_SESSION['me']['id'];
        $log['id'] = $id;
        $log['status'] = $data['status'];
        $this->log->write(2, $log, $this->mongo);

        return $data['status'];
    }

    public function add($data)
    {
        $info['role'] = $data['role'];
        unset($data['role']);

        $data['psw'] = password_hash($data['psw'], PASSWORD_DEFAULT);

        $r = $this->edb->insert_row('admin', $data);

        if ($r > 0)
        {
            $id = $this->edb->insert_id();

            $info['id'] = $id;
            $info['name'] = '未填写';
            $info['nick'] = '一个神秘的陌生人';
            $this->edb->insert_row('admin_info', $info);

            //日志
            $log['action'] = 'user/add';
            $log['me'] = $_SESSION['me']['id'];
            $log['id'] = $id;
            $log['data'] = array_merge($data, $info);
            $this->log->write(2, $log, $this->mongo);

            return TRUE;
        }
        else
            return FALSE;
    }

    public function delete($id)
    {
        $user['status'] = 3;

        $r = $this->edb->set_row_id('admin', $user, $id);

        if ($r == 1)
        {
            //日志
            $log['action'] = 'user/delete';
            $log['me'] = $_SESSION['me']['id'];
            $log['id'] = $id;
            $log['status'] = $user['status'];
            $this->log->write(2, $log, $this->mongo);
        }

        return $r == 1;
    }
}