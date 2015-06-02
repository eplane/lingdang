<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Controller_base extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        if(FALSE == $this->is_login())
        {
            redirect(base_url() . 'login.html');
        }
    }

    protected function is_login()
    {
        return isset($_SESSION['me']) && !!$_SESSION['me'];
    }

    protected function view($page, $data)
    {
        if(FALSE == isset($data['html_title']))
        {
            $data['html_title'] = $this->config->item('title');
        }

        if (!!$page)
            $data['page'] = $this->load->view($page, $data, TRUE);

        $this->load->view('template', $data);
    }
}