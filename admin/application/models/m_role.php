<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('m_base.php');

class m_role extends m_base
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
        $role = $this->edb->get_one($refresh, 'role', '`id` = ' . $id);

        if ($valid)
        {
            if ($role['status'] == 'stop')
            {
                return FALSE;
            }
        }

        return $role;
    }

    /** 获得一个用户的全部角色信息
     * @roles string | array 要查询的角色列表，可以是字符串，用空格分割id，也可以是一个id数组
     * @valid bool 是否包括无效的角色，例如 status 为 stop 的角色
     * @return bool | array
     */
    public function gets($roles, $valid = TRUE, $refresh = FALSE)
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
     * @param $role
     */
    public function get_access($role, $refresh = FALSE)
    {

    }
}