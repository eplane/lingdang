<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('m_base.php');

class M_channel extends m_base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function all()
    {
        return $this->mongo->get('channel')->result_array();
    }

    public function get($id, $refresh = FALSE)
    {
        return $this->edb->mget($refresh, 'channel', $id);
    }
}