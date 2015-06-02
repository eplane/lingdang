<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/** Model 基类
 *
 */
class m_base extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Edb');
    }
}