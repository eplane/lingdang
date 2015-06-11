<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('m_base.php');

class M_role extends m_base
{
    public function __construct()
    {
        parent::__construct();
    }

    /** 获得一个角色的信息
     * @param int $id
     * @valid bool 是否判断角色状态
     * @return bool | array
     */
    public function get($id, $valid = TRUE, $refresh = FALSE)
    {
        $role = $this->edb->get_row_id($refresh, 'admin_role', $id);

        if ($valid)
        {
            if ($role['status'] == '停用')
            {
                return FALSE;
            }
        }

        return $role;
    }

    public function gets($refresh = FALSE)
    {
        $this->db->select('*')->from('admin_role')->where('`status` != 3');
        $query = $this->db->get();

        return $query->result_array();
    }

    /** 获得一个用户的全部角色信息
     * @roles string 要查询的角色列表，可以是字符串，用空格分割id
     * @valid bool 是否包括无效的角色，例如 status 为 stop 的角色
     * @return bool | array
     */
    public function get_user_roles($roles, $valid = TRUE, $refresh = FALSE)
    {
        $data = [];

        if (is_string($roles))
        {
            $roles = explode(',', $roles);
        }
        else
        {
            return $data;
        }

        foreach ($roles as $row)
        {
            $role = $this->get($row, $valid, $refresh);

            if (!!$role)
                $data[] = $role;
        }

        return $data;
    }

    /** 获得一个角色的全部权限
     * @param int $role
     */
    public function get_access($roles, $refresh = FALSE)
    {
        $data = [];

        if (is_string($roles))
        {
            $roles = explode(',', $roles);
        }
        else
        {
            return $data;
        }

        foreach ($roles as $role)
        {
            $role = $this->get($role, TRUE, $refresh);

            $access = explode(',', $role['access']);

            foreach ($access as $row)
            {
                $data[] = $this->edb->get_row_id($refresh, 'admin_access', $row);
            }
        }

        return $data;
    }

    public function access($refresh = FALSE)
    {
        $this->db->select('*')->from('admin_access')->order_by('group');
        $query = $this->db->get();

        return $query->result_array();
    }

    public function user($role)
    {
        $this->load->model('m_user', 'muser');
        $users = $this->muser->gets();

        $data = [];

        foreach ($users as $row)
        {
            $roles = explode(',', $row['role']);

            if (in_array($role, $roles))
            {
                $data[] = $row;
            }
        }

        return $data;
    }

    public function add($data)
    {
        $r = $this->edb->insert_row('admin_role', $data);

        $id = $this->edb->insert_id();

        //日志
        $log['action'] = 'role/add';
        $log['me'] = $_SESSION['me']['id'];
        $log['id'] = $id;
        $this->log->write(2, $log, $this->mongo);

        return $r;
    }

    public function set($id, $data)
    {
        if (isset($data['id']))
            unset($data['id']);

        $r = $this->edb->set_row_id('admin_role', $data, $id);

        //日志
        $log['action'] = 'role/set';
        $log['me'] = $_SESSION['me']['id'];
        $log['id'] = $id;
        $log['data'] = $data;
        $this->log->write(2, $log, $this->mongo);

        return $r;
    }

    public function toggle($id)
    {
        $role = $this->get($id);

        if ($role['status'] == '删除')
            return 3;

        if ($role['status'] == '正常')
        {
            $data['status'] = 2;
        }
        else
        {
            $data['status'] = 1;
        }

        $this->edb->set_row_id('admin_role', $data, $id);

        //日志
        $log['action'] = 'role/toggle';
        $log['me'] = $_SESSION['me']['id'];
        $log['id'] = $id;
        $log['status'] = $data['status'];
        $this->log->write(2, $log, $this->mongo);

        return $data['status'];
    }

    public function delete($id)
    {
        $role['status'] = 3;

        $r = $this->edb->set_row_id('admin_role', $role, $id);

        if ($r == 1)
        {
            //日志
            $log['action'] = 'role/delete';
            $log['me'] = $_SESSION['me']['id'];
            $log['id'] = $id;
            $log['status'] = $role['status'];
            $this->log->write(2, $log, $this->mongo);
        }

        return $r == 1;
    }
}