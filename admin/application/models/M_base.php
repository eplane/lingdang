<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/** Model 基类
 *
 */
class M_base extends CI_Model
{
    public function __construct()
    {
        parent::__construct();

        //加载数据库，这一步骤会将框架的数据库类一起加载
        //这里也会加载mongo库
        $this->load->library('Edb');
    }
}