<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

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

    'role/add' => Array(
        array(
            'field' => 'name',
            'label' => '角色名称',
            'rules' => 'trim|required|max_length[20]|is_unique[admin_role.name]',
            'errors' => array(
                'required' => '%s 不能为空',
                'max_length' => '%s 只能是10个汉字以下的长度',
                'is_unique' => '%s 不能重复，已经存在这个名字的角色了'
            )
        ),
        array(
            'field' => 'explain',
            'label' => '角色说明',
            'rules' => 'trim|required|max_length[128]',
            'errors' => array(
                'required' => '%s 不能为空',
                'max_length' => '%s 只能是64个汉字以下的长度'
            )
        )
    ),

    'role/edit' => Array(
        array(
            'field' => 'name',
            'label' => '角色名称',
            'rules' => 'trim|required|max_length[20]',
            'errors' => array(
                'required' => '%s 不能为空',
                'max_length' => '%s 只能是10个汉字以下的长度'
            )
        ),
        array(
            'field' => 'explain',
            'label' => '角色说明',
            'rules' => 'trim|required|max_length[128]',
            'errors' => array(
                'required' => '%s 不能为空',
                'max_length' => '%s 只能是64个汉字以下的长度'
            )
        )
    )
);