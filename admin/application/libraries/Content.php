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
     * @param $html
     * @return mixed
     */
    public function save($html)
    {
        $content = $html;

        //删除不安全的标签
        $content = preg_replace('/<script>.*?<\/script>/is', '', $content);
        $content = preg_replace('/<link.*>/is', '', $content);
        $content = preg_replace('/<style>.*?<\/style>/is', '', $content);

        return $content;
    }

    public function load($html)
    {
        $content = htmlspecialchars_decode($html);

        return $content;
    }

    private function get_img($html)
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