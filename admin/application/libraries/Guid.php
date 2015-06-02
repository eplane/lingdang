<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
 * 唯一标识生成类
 *
 *
 *
 * ---------------------------------[更新列表]---------------------------------
 */

class Guid
{
    /**
     * @return string 32位数字字母混合，并带有 -
     */
    public static function standard()
    {
        $id     = md5(str_replace('.', '', uniqid('', TRUE)));
        $hyphen = chr(45); // "-"

        $id = substr($id, 0, 8) . $hyphen
            . substr($id, 8, 4) . $hyphen
            . substr($id, 12, 4) . $hyphen
            . substr($id, 16, 4) . $hyphen
            . substr($id, 20, 12);

        return $id;
    }

    /**
     * @return string 32位数字字母混合
     */
    public static function long()
    {
        $id = str_replace('.', '', uniqid('', TRUE));

        return (md5($id));
    }

    /**
     * @return string 22位数字字母混合
     */
    public static function short()
    {
        $id = str_replace('.', '', uniqid('', TRUE));

        return $id;
    }

    /**
     * @return string 14位数字
     */
    public static function simple()
    {
        $temp = Array();

        for ($i = 0; $i < 2; $i++)
        {
            $temp[] = mt_rand(1000000, 9999999);
        }

        return implode('', $temp);
    }
}