<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
 * 日志类
 *
 *
 *
 * ---------------------------------[更新列表]---------------------------------
 */
class  MY_Log extends CI_Log
{
    protected $option;

    private $file = NULL;

    public function __construct()
    {
        parent::__construct();

        $config =& get_config();

        $this->option = $config['log'];


        $date = date('Y-m-d', time());
        $this->file = fopen($this->option['file'] . $date . '.txt', 'a');
    }

    public function write($level, $data)
    {
        if ($level >= $this->option['level'])
        {
            $str = $this->bulld($data, $level);
            fwrite($this->file, $str);
        }
    }

    public function read($date)
    {
        if (FALSE != strtotime($date))
        {
            $file_name = $this->option['file'] . $date . '.txt';

            if (file_exists($file_name))
            {
                return file_get_contents($file_name);
            }
        }

        return FALSE;
    }

    private function bulld($data, $level)
    {
        $str = '';

        switch ($level)
        {
            case 1:
                $str .= '[INFO:';
                break;

            case 2:
                $str .= '[NOTICE:';
                break;

            case 3:
                $str .= '[WARNING:';
                break;

            case 4:
                $str .= '[ERROR:';
                break;

            case 5:
                $str .= '[CRITICAL ERROR:';
                break;

            default:
                $str .= '[UNKNOWN:';
        }

        $str .= date('Y-m-d H:i:s]', time());

        $str .= $this->bulld_data($data);

        return $str . "\r\n";
    }

    private function bulld_data($data)
    {
        $str = '';

        if (is_array($data))
        {
            foreach ($data as $k => $v)
            {
                $str .= $k . ':' . $v . '; ';
            }
        }
        else if (is_string($data))
        {
            $str = $data;
        }

        return $str;
    }
}