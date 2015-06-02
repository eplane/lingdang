<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('Controller_base.php');

class Main extends Controller_base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

        //echo password_hash('111111', PASSWORD_DEFAULT );

        //路径导航条数据
        $data['nav'] = ['主页'=>'main.html', '主页1'=>''];

        $this->view('main', $data);
    }
}