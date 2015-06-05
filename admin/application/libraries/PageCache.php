<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');


class PageCache
{
    protected $option;

    protected $ci;

    public function __construct()
    {
        $this->ci =& get_instance();
    }
}