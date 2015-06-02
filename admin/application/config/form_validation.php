<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');


/*
|--------------------------------------------------------------------------
| platform-register 平台用户注册表单规则
|--------------------------------------------------------------------------
|
*/

$config = Array(
    'login/index' => Array(
        array(
            'field' => 'uid',
            'label' => '用户名',
            'rules' => 'trim|required|min_length[4]|max_length[20]',
            'errors' => array(
                'required' => '%s 不能为空',
                'min_length' => '%s 必须为4位以上的英文字母',
                'max_length' => '%s 必须为20位以下的英文字母'
            )
        ),
        array(
            'field' => 'password',
            'label' => '密码',
            'rules' => 'trim|required|min_length[4]|max_length[20]',
            'errors' => array(
                'required' => '%s 不能为空',
                'min_length' => '%s 必须为4位以上的英文字母',
                'max_length' => '%s 必须为20位以下的英文字母'
            )
        )
    ),

    'admin_role_add' => Array(
        array(
            'field' => 'name',
            'label' => '职务名称',
            'rules' => 'trim|required|max_length[20]',
            'errors' => array(
                'required' => '%s 不能为空',
                'max_length' => '%s 只能是20个字以下的长度'
            )
        )
    ),

    'admin/user/me' => Array(
        array(
            'field' => 'nickname',
            'label' => '昵称',
            'rules' => 'trim|required|max_length[20]',
            'errors' => array(
                'required' => '%s 不能为空',
                'max_length' => '%s 只能是10个汉字以下的长度'
            )
        ),
        array(
            'field' => 'email',
            'rules' => 'trim|strtolower|is_unique[user.email]|required|max_length[64]|valid_email',
            'errors' => array(
                'required' => '%s 不能为空',
                'max_length' => '%s 只能是64个英文字符以下的长度',
                'valid_email' => '请输入正确的Email地址',
                'is_unique' => 'email不能重复，该email已经被注册使用过'
            )
        ),
        array(
            'field' => 'mobile',
            'label' => '手机',
            'rules' => 'trim|required|is_unique[user.mobile]|exact_length[11]|numeric',
            'errors' => array(
                'required' => '%s 不能为空',
                'exact_length' => '手机的号码位数错误',
                'numeric' => '请输入正确的手机号码',
                'is_unique' => '手机号码不能重复，该手机号码已经被注册使用过'
            )
        ),
        array(
            'field' => 'name',
            'label' => '真实姓名',
            'rules' => 'trim|max_length[10]',
            'errors' => array(
                'exact_length' => '%s 只能是10个汉字以下的长度'
            )
        ),
        array(
            'field' => 'birthday',
            'label' => '生日',
            'rules' => 'trim|is_date',
            'errors' => array(
                'is_date' => '%s 的时间格式错误'
            )
        )
    ),

    'admin_company' => Array(
        array(
            'field' => 'name',
            'label' => '职务名称',
            'rules' => 'trim|required|max_length[20]',
            'errors' => array(
                'required' => '%s 不能为空',
                'max_length' => '%s 只能是20个字以下的长度'
            )
        )
    )
);





/* End of file config.php */
/* Location: ./application/config/config.php */