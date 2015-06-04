<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
 * Mongo
 *
 *
 *
 * ---------------------------------[更新列表]---------------------------------
 */
class CI_Mongo extends Mongo
{
    protected $config;
    protected $ci;

    protected $db;

    function __construct()
    {
        // Fetch CodeIgniter instance
        $this->ci = get_instance();

        // Fetch Mongo server and database configuration
        $this->config = $this->ci->config->item('mongo');

        // Initialise Mongo
        if ($this->config['server'])
        {
            parent::__construct($this->config['server']);
        }
        else
        {
            parent::__construct();
        }

        $name = $this->config['db'];

        $this->db = $this->$name;
    }
}