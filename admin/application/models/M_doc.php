<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('m_base.php');

class M_doc extends m_base
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library('email');

        $config = $this->config->item('email');
        $this->email->initialize($config);
        $this->email->from($config['service-email'], $config['service-name']);
    }

    public function get($id)
    {

    }

    public function set($id, $data)
    {

    }

    public function all($refresh = FALSE)
    {
        return [];
    }
}