<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
 * 唯一标识生成类
 *
 *
 *
 * ---------------------------------[更新列表]---------------------------------
 */
class Content
{
    private $ci;

    public function __construct()
    {
        $this->ci =& get_instance();
    }

    public function get_img($html)
    {
        preg_match_all('/<img.*?src="(.*?)".*?>/is', $html, $results);

        $len = count($results[0]);

        $temp = [];

        for ($i = 0; $i < $len; $i++)
        {
            $temp[$i]['img'] = $results[0][$i];
            $temp[$i]['file'] = $results[1][$i];
        }

        return $temp;
    }

    public function save_img($html)
    {
        //获得文章中的临时图片信息
        $imgs = $this->get_img($html);

        $temp = [];

        foreach ($imgs as $img)
        {
            $this->ci->load->library('File');

            $fullname = get_file_fullname($img['file']);

            //如果是网站内的临时文件
            if ($this->ci->file->is_temp_file($fullname))
            {
                $md5 = md5_file($img['file']);

                //如果是一张新图片
                if (FALSE == isset($temp[$md5]))
                {
                    $name = get_file_name($img['file']);

                    $file = $this->ci->file->save($name);

                    $temp[$md5]['file'] = get_file($file);
                }
                else    //如果是一张重复的图片
                {
                    $name = get_file_fullname($img['file']);

                    $this->ci->file->delete_temp($name);
                }

                //替换文件
                $html = str_replace($img['file'], $temp[$md5]['file'], $html);
            }
        }

        return $html;
    }

    /** 判断是否包含敏感关键词
     * TODO 需要支持中文分词
     * @param $html string
     * @param $words array 敏感词组
     * @return bool
     */
    public function illegal_words($html, $words)
    {
        if (count($words) == 0)
            return TRUE;

        foreach ($words as $w)
        {
            if (FALSE !== strpos($html, $w))
            {
                return FALSE;
            }
        }

        return TRUE;
    }
}