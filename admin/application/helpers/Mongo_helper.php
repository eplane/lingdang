<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('get_mongo_id'))
{
    /** 获得一个文件的名字，不包括路径，但包括扩展名
     * @param $path
     * @return  bool|string
     */
    function get_mongo_id($doc)
    {
        return $doc['_id']->{'$id'};
    }
}


if (!function_exists('get_mongo_id_stamp'))
{
    /** 获得一个文件的名字，不包括路径，但包括扩展名
     * @param $path
     * @return  bool|string
     */
    function get_mongo_id_stamp($doc, $format = '')
    {
        $mongo_id = $doc['_id'];
        $stamp = $mongo_id->getTimestamp();

        if ($format != '')
        {
            return date($format, $stamp);
        }
        else
        {
            return $stamp;
        }
    }
}