<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Controller_base extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->load->model('m_role', 'mrole');

        if (FALSE == $this->is_login())
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
        if (FALSE == isset($data['html_title']))
        {
            $data['html_title'] = $this->config->item('title');
        }

        if (FALSE == isset($data['nav']))
        {
            $data['nav'] = ['主页' => 'main.html'];
        }

        if (!!$page)
            $data['page'] = $this->load->view($page, $data, TRUE);

        $this->load->view('template', $data);
    }

    protected function is_permit($access = NULL, $jump = FALSE)
    {
        //获得请求地址
        //$uri = uri_string();

        $count = 0;
        $count_access = count($access);

        //获得权限验证列表
        if (is_string($access))
        {
            $access = explode(',', $access);

            $user_access = $this->mrole->get_access($_SESSION['me']['roles']);

            foreach ($user_access as $row)
            {
                if (in_array($row['name'], $access))
                {
                    $count++;
                }

                if ($count == $count_access)
                {
                    return TRUE;
                }
            }
        }

        return FALSE;
    }

    public function error()
    {
        echo '错误';
    }
}