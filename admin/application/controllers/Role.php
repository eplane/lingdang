<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('Controller_base.php');

class Role extends Controller_base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function roles()
    {
        $this->is_permit('角色浏览', TRUE);

        //路径导航条数据
        $data['nav'] = ['主页' => 'main.html', '用户管理' => 'user/users.html', '角色列表' => ''];

        $data['data'] = $this->mrole->gets(TRUE);

        $this->view('role_list', $data);
    }

    private function _role($data, $id = 0, $cmd = 'add')
    {
        $this->load->library('form_validation');
        $this->load->helper('form');

        $data['access'] = $this->mrole->access(TRUE);

        if ($cmd == 'edit')
        {
            $this->form_validation->set_rules('name', '角色名称', 'is_unique_except[admin_role.name.id.' . $id . ']');
        }

        //执行server端验证
        if ($this->form_validation->run() == FALSE)
        {
            $this->view('role_edit', $data);
        }
        else    //表单验证成功
        {
            $role['name'] = $this->input->post('name');
            $role['status'] = $this->input->post('status');
            $role['explain'] = $this->input->post('explain');
            $role['access'] = implode(',', $this->input->post('access'));

            if ($cmd == 'add')
            {
                $this->mrole->add($role);
            }
            elseif ($cmd == 'edit')
            {
                $this->mrole->set($id, $role);
            }

            redirect(base_url() . 'role/roles.html');
        }
    }

    public function add()
    {
        $this->is_permit('角色添加', TRUE);

        //路径导航条数据
        $data['nav'] = ['主页' => 'main.html', '用户管理' => 'user/users.html', '添加角色' => ''];

        $data['role'] = Array('id' => '', 'name' => '', 'explain' => '', 'status' => '正常', 'access' => Array());

        $this->_role($data);
    }

    public function edit($id)
    {
        $this->is_permit('角色修改', TRUE);

        $this->load->model('m_user', 'muser');

        //路径导航条数据
        $data['nav'] = ['主页' => 'main.html', '用户管理' => 'user/users.html', '修改角色' => ''];

        $data['role'] = $this->mrole->get($id, FALSE, TRUE);

        $data['role']['access'] = explode(',', $data['role']['access']);

        $data['user'] = $this->mrole->user($id);

        $data['user2'] = $this->muser->gets();

        foreach($data['user'] as $row)
        {
            foreach($data['user2'] as $k=>$v)
            {
                if( $v['id'] ==  $row['id'] )
                {
                    unset($data['user2'][$k]);
                }
            }
        }

        //var_dump($data['user2']);

        $this->_role($data, $id, 'edit');
    }

    public function delete($id)
    {
        $this->is_permit('角色删除', TRUE);

        $this->mrole->delete($id);

        redirect(base_url() . 'role/roles.html');
    }

    public function delete_user($role, $user)
    {
        $this->is_permit('角色修改,用户修改', TRUE);

        $this->load->model('m_user', 'muser');

        $user = $this->muser->get($user);

        $roles = explode(',', $user['roles']);

        $i = array_search($role, $roles);

        if ($i <= FALSE)
        {
            unset($roles[$i]);
        }

        $roles = implode(',', $roles);

        $data['role'] = $roles;

        $this->muser->set($user['id'], $data);

        redirect(base_url() . 'role/edit/' . $role . '.html');
    }
}