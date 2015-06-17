<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
 * 唯一标识生成类
 *
 *
 *
 * ---------------------------------[更新列表]---------------------------------
 */
class Text
{
    public function get_img($html)
    {
        //$reg = '/<img +src=[\'"](http.*?)[\'"]/i';

        var_dump($html);

        preg_match_all('/<img.*?src="(.*?)".*?>/is', $html, $results);

        //preg_match_all('/<img[^>]*>/i', $html, $results);

        //preg_match_all($reg, $html, $results);

        return $results;
    }
}