<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('efile_get_file'))
{
    /** 获得图片的实际路径，请确认该图片是经过 efile 类保存的。
     * @param string $file 文件名
     * @return bool|string
     */
    function efile_get_file($file)
    {
        $CI =& get_instance();

        if (!!$file)
        {
            $CI->load->library('Efile');
            return $CI->efile->get_file($file);
        }
        else
        {
            $msg = sprintf($CI->lang->line('error_efile_no_file'), $file);
            trigger_error($msg, E_USER_ERROR);
            return FALSE;
        }
    }
}

if (!function_exists('get_path'))
{
    /** 获得配置文件中的扩展路径配置
     * @param $path
     * @return  bool|string
     */
    function get_path($path)
    {
        $CI         =& get_instance();
        $other_path = $CI->config->item('other_path');

        if (isset($other_path[$path]))
        {
            return $other_path[$path];
        }
        else
        {
            $msg = sprintf($CI->lang->line('error_no_path'), $path);
            trigger_error($msg, E_USER_ERROR);
            return FALSE;
        }
    }
}