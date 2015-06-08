<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
 *
 *
 *
 *
 * ---------------------------------[更新列表]---------------------------------
 */
class MY_Form_validation extends CI_Form_validation
{

    public function __construct($rules = array())
    {
        parent::__construct($rules);
    }

    public function is_date($str)
    {
        return !(FALSE === strtotime($str));
    }

    //在数据库中查询是否存在某个值，但排除某个值
    //用于在修改email等情景时判断值的合法性
    //Demo : $this->form_validation->set_rules('name', '角色名称', 'is_unique_except[admin_role.name.id.' . $id . ']');
    public function is_unique_except($str, $param)
    {
        $table    = '';
        $column   = '';
        $except_c = '';
        $except_v = '';

        sscanf($param, '%[^.].%[^.].%[^.].%[^.]', $table, $column, $except_c, $except_v);

        return isset($this->CI->db)

            ? ($this->CI->db->limit(1)->get_where($table, array($column => $str, $except_c . '!=' => $except_v))->num_rows() === 0)
            : FALSE;
    }
}