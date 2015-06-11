<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('Controller_base.php');

class Article extends Controller_base
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('m_article', 'marticle');
    }


}