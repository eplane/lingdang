<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
 * Curl
 *
 *
 *
 * ---------------------------------[更新列表]---------------------------------
 */
class Token
{
    const OK = 0;
    const INVALID_IP = 1;
    const INVALID_APPID = 2;
    const INVALID_TIME = 3;
    const INVALID_OPTION = 4;
    const INVALID_TOKEN = 5;

    //token 字符串打乱规则
    private $config;
    private $cache;

    public function __construct($config = NULL)
    {
        $this->config = $config;

        $ci =& get_instance();
        $ci->load->library('Cache');
        $this->cache = $ci->cache;
    }

    /**
     * 生成一个token
     * @param string $appid 分配给应用的固定id，16位
     * @param string $option 要进行的操作，便于权限验证
     * @param string $ip ip地址
     */
    public function get($appid, $option, $ip)
    {
        if (FALSE == in_array($appid, $this->config['appid']))
        {
            return Token::INVALID_APPID;
        }
        else
        {
            $appid = substr(md5($appid), 2, 8);
        }

        $time = time();
        //截取4位秒
        $time = substr($time, strlen($time) - 4);

        //操作的4位编码
        if (isset($this->config['option'][$option]))
        {
            $option = $this->config['option'][$option];
        }
        else
        {
            return Token::INVALID_OPTION;
        }

        //ip白名单过滤
        if (0 < count($this->config['ip']['white']) && FALSE == in_array($ip, $this->config['ip']['white']))
        {
            return Token::INVALID_IP;
        }

        //ip黑名单过滤
        if (0 < count($this->config['ip']['white']) && in_array($ip, $this->config['ip']['white']))
        {
            return Token::INVALID_IP;
        }

        //整理ip地址，例如 127.0.0.1 => 127,000,000,001
        $ip = explode('.', $ip);

        for ($i = 0; $i < 4; $i++)
        {
            while (strlen($ip[$i]) < 3)
            {
                $ip[$i] = '0' . $ip[$i];
            }
        }

        //12位脏字符
        list($usec, $sec) = explode(" ", microtime());
        $dirty = $usec;
        $dirty = substr($dirty, strlen($dirty) - 8, 5) . substr(md5($dirty), 2, 7);

        //8位appid . 4位时间 . 4位option id . 12位ip . 12位脏数据
        $temp = $appid . $time . $option . $ip[0] . $ip[1] . $ip[2] . $ip[3] . $dirty;

        $s = explode(',', $this->config['sort']);

        $token = '';

        foreach ($s as $n)
        {
            $token .= $temp[trim($n)];
        }

        //$_SESSION['TOKEN'][] = $token;
        $this->cache->save($token, $token, $this->config['timeout']);

        return $token;
    }

    private function valid($appid, $token, $option, $ip)
    {
        /*$tokens = $_SESSION['TOKEN'];
        $i = array_search($token, $tokens);*/

        $token = $this->cache->get($token);

        if (FALSE === $token)
        {
            return Token::INVALID_TOKEN;
        }

        $time = time();

        $ttime = $this->time($token);
        $tappid = $this->appid($token);
        $toption = $this->option($token);
        $tip = $this->ip($token);

        if (($time - $ttime) >= $this->config['timeout'])
        {
            return Token::INVALID_TIME;
        }
        elseif (substr(md5($appid), 2, 8) != $tappid)
        {
            return Token::INVALID_APPID;
        }
        elseif ($toption != $option)
        {
            return Token::INVALID_OPTION;
        }
        elseif ($tip == $ip)
        {
            return Token::INVALID_IP;
        }

        return Token::OK;
    }

    public function used($appid, $token, $option, $ip)
    {
        $r = $this->valid($appid, $token, $option, $ip);

        if (Token::OK !== $r)
        {
            $this->cache->delete($token);

            return FALSE;
        }

        $this->cache->delete($token);

        return TRUE;
    }

    //获得一个token的原型，即没有打乱的token
    private function prototype($token)
    {
        $temp = '';

        $s = explode(',', $this->config['sort']);

        for ($i = 0; $i < 40; $i++)
        {
            $temp .= $token[array_search($i, $s)];
        }

        return $temp;
    }


    public function time($token)
    {
        $o = $this->prototype($token);

        $time = time();

        return substr($time, 0, strlen($time) - 4) . substr($o, 8, 4);
    }

    public function appid($token)
    {
        $o = $this->prototype($token);

        return substr($o, 0, 8);
    }

    public function option($token)
    {
        $o = $this->prototype($token);

        $option_id = substr($o, 12, 4);

        foreach ($this->config['option'] as $k => $v)
        {
            if ($v == $option_id)
                return $k;
        }

        return FALSE;
    }

    public function ip($token)
    {
        $o = $this->prototype($token);

        $ip = (int)substr($o, 16, 3) . '.' . (int)substr($o, 19, 3) . '.' . (int)substr($o, 22, 3) . '.' . (int)substr($o, 25, 3);

        return $ip;
    }
}