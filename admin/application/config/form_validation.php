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
    ),

    'user/add' => Array(
        array(
            'field' => 'uid',
            'label' => '用户名',
            'rules' => 'trim|required|max_length[20]|is_unique[admin.uid]',
            'errors' => array(
                'required' => '%s 不能为空',
                'max_length' => '%s 只能是10个汉字以下的长度',
                'is_unique' => '%s 不能重复，已经存在这个id的用户了'
            )
        ),
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'trim|required|max_length[60]',
            'errors' => array(
                'required' => '%s 不能为空',
                'max_length' => '%s 只能是60个字符以内'
            )
        ),
        array(
            'field' => 'mobile',
            'label' => '手机',
            'rules' => 'trim|required|exact_length[11]|numeric',
            'errors' => array(
                'required' => '%s 不能为空',
                'exact_length' => '%s 只能是11位数字',
                'numeric' => '%s 只能是数字'
            )
        )
    ),

    'user/me' => Array(
        array(
            'field' => 'nick',
            'label' => '昵称',
            'rules' => 'trim|required|max_length[40]|is_unique[admin.uid]',
            'errors' => array(
                'required' => '%s 不能为空',
                'max_length' => '%s 只能是10个汉字以下的长度',
                'is_unique' => '%s 不能重复，已经存在这个id的用户了'
            )
        ),
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'trim|required|max_length[60]|valid_email',
            'errors' => array(
                'required' => '%s 不能为空',
                'max_length' => '%s 只能是60个字符以内',
                'valid_email' => '%s 必须是个email地址'
            )
        ),
        array(
            'field' => 'mobile',
            'label' => '手机',
            'rules' => 'trim|required|exact_length[11]|numeric',
            'errors' => array(
                'required' => '%s 不能为空',
                'exact_length' => '%s 只能是11位数字',
                'numeric' => '%s 只能是数字'
            )
        )
    ),

    'doc/add' => Array(
        array(
            'field' => 'name',
            'label' => '文档名称',
            'rules' => 'trim|required|max_length[40]|min_length[6]',
            'errors' => array(
                'required' => '%s 不能为空',
                'max_length' => '%s 必须是20个汉字以下的长度',
                'min_length' => '%s 必须是3个汉字以上的长度'
            )
        ),
        array(
            'field' => 'content',
            'label' => '文档内容',
            'rules' => 'trim|required|max_length[1000000]',
            'errors' => array(
                'required' => '%s 不能为空',
                'max_length' => '%s 必须在1M长度以下'
            )
        )
    ),
    'doc/edit' => Array(
        array(
            'field' => 'name',
            'label' => '文档名称',
            'rules' => 'trim|required|max_length[40]|min_length[6]',
            'errors' => array(
                'required' => '%s 不能为空',
                'max_length' => '%s 必须是20个汉字以下的长度',
                'min_length' => '%s 必须是3个汉字以上的长度'
            )
        ),
        array(
            'field' => 'content',
            'label' => '文档内容',
            'rules' => 'trim|required|max_length[1000000]',
            'errors' => array(
                'required' => '%s 不能为空',
                'max_length' => '%s 必须在1M长度以下'
            )
        )
    )

);