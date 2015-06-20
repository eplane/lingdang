<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Controller_base extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->load->model('m_role', 'mrole');
        $this->load->library('CI_Mongo', NULL, 'mongo');


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
        $count        = 0;
        $count_access = count($access);

        //获得权限验证列表
        if (is_string($access))
        {
            $access = explode(',', $access);

            $user_access = $this->mrole->get_access($_SESSION['me']['roles'], TRUE);

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

        if ($jump)
        {
            redirect(base_url() . 'main/error.html');
        }

        return FALSE;
    }

    protected function url($list, $exit)
    {
        $referer = $this->input->server('HTTP_REFERER');

        foreach ($list as $item)
        {
            if (strpos($referer, $item))
            {
                return TRUE;
            }
        }

        if ($exit)
        {
            exit(json_encode(array('success' => 'FALSE', 'msg' => 'ajax请求非法')));
        }

        return FALSE;
    }

    protected function send($array)
    {
        echo json_encode($array);
    }

    public function error()
    {
        echo '没有权限';
    }

    //敏感词验证
    //TODO 敏感词数据源
    public function _illegal_words($str)
    {
        $this->load->library('Content');

        $words = ['测试'];

        return $this->content->illegal_words($str, $words);
    }
}