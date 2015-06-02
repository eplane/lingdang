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
     * @return bool | array
     *  即使角色被停用了，也会正常返回数据。
     */
    public function get($id, $valid = FALSE, $refresh = FALSE)
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
     * @param string | array $roles 要查询的角色列表，可以是字符串，用空格分割id，也可以是一个id数组
     * @param bool $valid 是否包括无效的角色，例如 status 为 stop 的角色
     * @return bool | array
     * 即使角色被停用了，也会正常返回数据。
     */
    public function gets($roles, $valid = FALSE, $refresh = FALSE)
    {
        $data = [];

        if (is_string($roles))
        {
            $roles = explode(',', $roles);
        }

        foreach ($roles as $row)
        {
            $role = $this->get($row, $valid, $refresh);

            if (!!$role)
                $data[] = $role;
        }

        return $data;
    }
}