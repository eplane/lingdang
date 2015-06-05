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
        $data['nav'] = ['主页' => 'main.html', '用户管理' => 'user/admins.html', '管理员列表' => ''];

        $data['data'] = $this->mrole->gets();

        $this->view('roles', $data);
    }

}