<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('m_base.php');

//TODO:忘记角色有停用状态了
class m_role extends m_base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function create_db()
    {
    }

    /** 验证一个用户是否有指定权限
     * @param string $access
     * @return BOOL
     */
    public function check_access($user, $access_key)
    {
        $access = $this->get_access_by_key(TRUE, $access_key);

        if (NULL != $access)
        {
            $accesses = $this->get_user_access(TRUE, $user);

            $accesses = explode(',', $accesses);

            foreach ($accesses as $a)
            {
                if ($a === $access['id'])
                {
                    return TRUE;
                }
            }
        }

        return FALSE;
    }

    /** 获得一个用户的所有角色
     * @param int $user
     * @return string
     */
    public function get_user_roles($refresh, $user)
    {
        $entity = $this->session->stuff['entity'];

        $data = $this->edb->get_one($refresh, 'stuff', '`user` = ' . $user . ' AND `entity` = ' . $entity, 'roles');

        return $data['roles'];
    }

    /** 获得指定角色的全部权限
     * @param Array $roles
     * @return string
     */
    public function get_roles_access($refresh, $roles)
    {
        $access = '';

        if (-1 != strpos(',', $roles))
        {
            $data = $this->edb->get($refresh, 'role', '`id` in (' . $roles . ')', 'accesses');

            foreach ($data as $row)
            {
                $access .= $row['accesses'] . ',';
            }

            $access = trim($access, ',');
        }
        else
        {
            $access = $this->edb->get($refresh, 'role', '`id` = ' . $roles, 'accesses');
        }

        return $access;
    }

    /** 获得一个用户的全部权限
     * @param int $user
     */
    public function get_user_access($refresh, $user)
    {
        $roles = $this->get_user_roles($refresh, $user);

        $access = $this->get_roles_access($refresh, $roles);

        return $access;
    }

    public function get_role($refresh, $id = NULL)
    {
        if (!!$id)
            return $this->edb->get_one($refresh, 'role', '`id` = ' . $id . ' AND `entity` = ' . $this->session->stuff['entity']);
        else
            return $this->edb->get($refresh, 'role', '`entity` = ' . $this->session->stuff['entity']);
    }

    public function get_role_user($refresh, $id)
    {
        $entity = $this->session->stuff['entity'];

        $stuff = $this->edb->get($refresh, 'stuff', '`entity` = ' . $entity, 'roles, user');

        $users = Array();

        foreach ($stuff as $s)
        {
            $roles = explode(',', $s['roles']);

            if (in_array($id, $roles))
            {
                $users [] = $s['user'];
            }
        }

        return $users;
    }

    public function get_access($refresh, $id = NULL)
    {
        if (!!$id)
            return $this->edb->get_one($refresh, 'access', '`id` = ' . $id);
        else
            return $this->edb->get($refresh, 'access', '', '', 'group');
    }

    public function get_access_by_key($refresh, $key)
    {
        return $this->edb->get_one($refresh, 'access', '`key` = "' . $key . '"');
    }

    public function add_access($name, $explain)
    {
        $data['name'] = $name;
        $data['explain'] = $explain;

        $this->edb->insert_row('access', $data);
    }

    public function add_role($name, $explain, $access, $status)
    {
        $data['name'] = $name;
        $data['explain'] = $explain;
        $data['accesses'] = implode(',', $access);
        $data['status'] = $status;

        $data['create'] = time();
        $data['update'] = time();

        $data['entity'] = $this->session->stuff['entity'];

        $this->edb->insert_row('role', $data);
    }

    public function save_role($id, $name, $explain, $access, $status)
    {
        $data['name'] = $name;
        $data['explain'] = $explain;
        $data['accesses'] = implode(',', $access);
        $data['status'] = $status;

        $data['update'] = time();

        $this->edb->update('role', $data, '`id` = ' . $id);

        $this->get_role(TRUE, $id);
    }

    public function delete_role($id)
    {
        $this->edb->delete('role', '`id` = ' . $id);
    }

    /** 为一个角色添加一个用户，反过来说也行
     * @param int $role
     * @param int $users
     */
    public function add_user($role, $users)
    {
        foreach ($users as $user)
        {
            $stuff = $this->edb->get_one(TRUE, 'stuff', '`user` = ' . $user . ' AND `entity` = ' . $this->session->stuff['entity']);

            $data = Array();

            if (!!$stuff['roles'])
            {
                $data['roles'] = $this->wash_roles($stuff['roles']);
                $data['roles'] = $data['roles'] != '' ? $data['roles'] . ',' . $role : $role;
            }
            else
            {
                $data['roles'] = $role;
            }

            $this->edb->update('stuff', $data, '`id` = ' . $stuff['id']);
        }
    }

    /** 为一个角色删除一个用户，反过来说也行
     * @param int $role
     * @param int $users
     */
    public function delete_user($role, $user)
    {
        $stuff = $this->edb->get_one(TRUE, 'stuff', '`user` = ' . $user . ' AND `entity` = ' . $this->session->stuff['entity']);

        $roles = explode(',', $stuff['roles']);

        $temp = Array();

        foreach ($roles as $r)
        {
            if ($r != $role)
            {
                $temp[] = $r;
            }
        }

        $data = Array();
        $data['roles'] = implode(',', $temp);

        $this->edb->update('stuff', $data, '`id` = ' . $stuff['id']);
    }

    /** 删除stuff表roles列中，已经没有用的role id
     *  修正删除role时产生的数据不同步问题
     * @param string $str
     */
    private function wash_roles($str)
    {
        $roles = explode(',', $str);

        $result = '';

        foreach ($roles as $r)
        {
            $t = $this->get_role($r, TRUE);

            if (NULL != $t)
            {
                $result .= $r . ',';
            }
        }

        $result = trim($result, ',');

        return $result;
    }
}