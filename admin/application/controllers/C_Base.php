<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** 菜单接口 **/
interface Imenu
{
    /** 返回该模块的后台菜单
     * @return array
     *
     * 返回的菜单格式:
     * array{
     *      'title' => '',      //模块名称
     *      'icon' => '',     //模块图标，图标的索引基于相应的模板图标集，并非实际url地址
     *      'items' => array{
     *                          '菜单项1' => 'url',
     *                          '菜单项2' => 'url'
     *                      }
     * }
     * 其中url为绝对路径，以支持外部链接
     */
    public static function admin_menu();
}

/** 模块依赖关系判断 **/
interface Irelationship
{
    /** 返回该模块依赖的所有其他模块
     * @return array
     */
    public static function need();

    /** 返回该模块的名称，该名称应该包括名称和版本号
     * @return string
     */
    public static function name();

    /** 判断当前的运行环境，是否满足该模块需要。
     * @return bool
     */
    public static function is_lack();
}

abstract class C_Base extends CI_Controller
{
    public function __construct()
    {
        $this->load->model('m_entity', 'mentity');
        $this->load->model('m_module', 'mmodule');
    }

    /** 加载视图
     * @param string $page
     * @param array $data
     */
    public function view($page, $data)
    {
        $dir = '';

        if (FALSE == isset($data['html_title']))
        {
            $data['html_title'] = $this->config->item('title');
        }

        $data['menu'] = $this->load_menu();

        $data['_user'] = $this->session->user;

        $data['page'] = $this->load->view($dir . $page, $data, TRUE);

        $this->load->view($dir . 'template', $data);
    }



    /**
     * 菜单加载
     */

    /** 加载模块的菜单
     * @return array
     */
    private function load_menu()
    {
        //获得登录用户的所属entity（实体）的全部有效模块
        $modules = $this->mentity->get_modules(TRUE);

        $menu = Array();

        if (!!$modules)
        {
            foreach ($modules as $m)
            {
                $module = $this->mmodule->get_module(TRUE, $m);

                require_once($module['key'] . '.php');

                $menu[] = $module['key']::admin_menu();
            }
        }

        return $menu;
    }



    /**
     * 权限检查
     * 所有页面调用的第一行都应该执行该方法。
     * 一共有两个方法，只使用其中一个即可
     */

    /** 权限检查
     * @param string $access_name
     * @return bool
     */
    protected function check_access($access_name)
    {
        $user = $this->session->user['id'];

        return $this->mrole->check_access($user, $access_name);
    }

    /** 权限检查，失败后直接跳转登陆页
     * @param string $access_key
     * @return null
     */
    protected function check_access_login($access_key)
    {
        $user = $this->session->user['id'];

        if (FALSE === $this->mrole->check_access($user, $access_key))
        {
            redirect(base_url() . 'login.html');
        }
    }



}
