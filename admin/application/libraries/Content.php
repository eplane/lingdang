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

    /** 对提交的文章内容进行处理
     * 1. 转存文章中的图片
     * 2. 对html标签进行过滤，例如<script>
     * @param $html
     * @return mixed
     */
    public function save($html)
    {
        return $this->save_img($html);
    }

    private function get_img($html)
    {
        preg_match_all('/<img.*?src="(.*?)".*?>/is', $html, $results);

        $len = count($results[0]);

        $temp = [];

        for ($i = 0; $i < $len; $i++)
        {
            $temp[$i]['img']  = $results[0][$i];
            $temp[$i]['file'] = $results[1][$i];
        }

        return $temp;
    }

    private function save_img($html)
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
                $name = get_file_name($img['file']);

                $file = $this->ci->file->save($name);

                $temp[$name]['file'] = get_file($file);

                //替换文件
                $html = str_replace($img['file'], $temp[$name]['file'], $html);
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