<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('m_base.php');

class M_doc extends m_base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get($id, $refresh = FALSE)
    {
        return $this->edb->mget($refresh, 'doc', $id);
    }

    public function add($data)
    {
        $data['update'] = time();
        $r = $this->mongo->insert('doc', $data);

        if ($r == TRUE)
        {
            $id = $this->mongo->insert_id();

            //日志
            $log['action'] = 'doc/add';
            $log['me'] = $_SESSION['me']['id'];
            $log['id'] = $id;
            $this->log->write(2, $log, $this->mongo);
        }

        return $r;
    }

    public function set($id, $data)
    {
        $data['update'] = time();

        $r = $this->mongo->where(array('_id' => new MongoId($id)))->update('doc', $data);

        if ($r == TRUE)
        {
            //日志
            $log['action'] = 'doc/set';
            $log['me'] = $_SESSION['me']['id'];
            $log['id'] = $id;
            $this->log->write(2, $log, $this->mongo);

            $this->cache->delete('doc.' . $id);
        }

        return $r;
    }

    public function all()
    {
        return $this->mongo->get('doc')->result_array();
    }

    public function delete($id)
    {
        $this->cache->delete('doc.' . $id);
        return $this->mongo->where(array('_id' => new MongoId($id)))->delete('doc');
    }
}