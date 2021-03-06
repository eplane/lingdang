<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('get_file'))
{
    /** 获得图片的实际路径，请确认该图片是经过 efile 类保存的。
     * @param string $file 文件名
     * @return bool|string
     */
    function get_file($file, $default = NULL)
    {
        $CI =& get_instance();
        $CI->load->library('File');

        if (!!$file)
        {
            return $CI->file->get_file($file);
        }
        else
        {
            return $default;

            //$msg = sprintf($CI->lang->line('error_efile_no_file'), $file);
            //trigger_error($msg, E_USER_ERROR);
            //return FALSE;
        }
    }
}

if (!function_exists('get_temp_file'))
{
    /** 获得图片的实际路径，请确认该图片是经过 efile 类保存的。
     * @param string $file 文件名
     * @return bool|string
     */
    function get_temp_file($file, $default = NULL)
    {
        $CI =& get_instance();
        $CI->load->library('File');

        if (!!$file)
        {
            return $CI->file->get_temp_file($file);
        }
        else
        {
            return $default;

            //$msg = sprintf($CI->lang->line('error_efile_no_file'), $file);
            //trigger_error($msg, E_USER_ERROR);
            //return FALSE;
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
        $CI =& get_instance();
        $other_path = $CI->config->item('other_path');

        if (isset($other_path[$path]))
        {
            return base_url() . $other_path[$path];
        }
        else
        {
            $msg = sprintf($CI->lang->line('error_no_path'), $path);
            trigger_error($msg, E_USER_ERROR);
            return FALSE;
        }
    }
}

if (!function_exists('get_file_name'))
{
    /** 获得一个文件的名字，不包括路径和扩展名
     * @param $path
     * @return  bool|string
     */
    function get_file_name($file)
    {
        return pathinfo($file, PATHINFO_FILENAME);
    }
}

if (!function_exists('get_file_fullname'))
{
    /** 获得一个文件的名字，不包括路径，但包括扩展名
     * @param $path
     * @return  bool|string
     */
    function get_file_fullname($file)
    {
        return pathinfo($file, PATHINFO_BASENAME);
    }
}