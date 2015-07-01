<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('m_base.php');

class M_word extends m_base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get($type, $refresh = FALSE)
    {
        //type是汉字，为了防止操作系统的默认编码不一致造成key不同，所以将汉字进行统一编码
        $key = 'word.' . md5($type);

        if ($refresh)
            $this->cache->delete($key);

        $data = $this->cache->get($key);

        if ($data == FALSE)
        {
            $data = $this->mongo->get_where('word', array("type" => $type))->result_array();

            if ($data != FALSE)
            {
                $this->cache->save($key, $data[0]);
                return $data[0];
            }
            else
            {
                return FALSE;
            }
        }
        else
        {
            return $data;
        }
    }

    public function set($type, $words)
    {
        $data['update'] = time();
        $data['word'] = $words;

        $r = $this->mongo->where(array('type' => $type))->update('word', $data, array('upsert' => TRUE));

        if ($r == TRUE)
        {
            //日志
            $log['action'] = 'word/set';
            $log['me'] = $_SESSION['me']['id'];
            $log['type'] = $type;
            $log['word'] = $words;
            $this->log->write(2, $log, $this->mongo);

            $key = 'word.' . md5($type);
            $this->cache->delete($key);
        }

        return $r;
    }

    /** 判断是否包含敏感关键词
     * TODO 需要支持中文分词
     * @param $word array 敏感词组
     * @param $str string
     * @return bool
     */
    public function have($type, $str)
    {
        $word = $this->mword->get($type);
        $word = str_replace('，', ',', $word['word']);
        $words = explode(',', $word);

        if (count($words) == 0)
            return TRUE;

        foreach ($words as $w)
        {
            if (FALSE !== strpos($str, $w))
            {
                return FALSE;
            }
        }

        return TRUE;
    }
}