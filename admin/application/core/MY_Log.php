<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
 * 日志类
 *
 *  支持文件和mongo
 *
 * TODO mongo将所有的数据都写在一个集合里了，应该每年换一个
 *
 * ---------------------------------[更新列表]---------------------------------
 */
class  MY_Log extends CI_Log
{
    protected $option;

    protected $file = NULL;

    protected $ci;

    public function __construct()
    {
        parent::__construct();

        $config =& get_config();

        $this->option = $config['log'];

        if ($this->option['drive'] == 'file')
        {
            $date = date('Y-m-d', time());
            $this->file = fopen($this->option['file'] . $date . '.txt', 'a');
        }
    }

    public function write($level, $data, $mongo = NULL)
    {
        if ($level >= $this->option['level'])
        {
            $log = $this->build($data, $level);

            if ($this->option['drive'] == 'file')
            {
                if (!!$this->file)
                {
                    if (is_array($log['data']))
                    {
                        $log['data'] = json_encode($log['data'], JSON_UNESCAPED_UNICODE);
                    }

                    fwrite($this->file, '[' . $log['level'] . ' : ' . $log['date'] . ' ' . $log['time'] . '] ' . $log['data'] . "\r\n");
                }
                else
                {
                    throw new Exception('Log file can not open !');
                }
            }
            else if ($this->option['drive'] == 'mongo')
            {
                $mongo->insert($this->option['name'], $log);
            }

            return $log;
        }
        else
        {
            return FALSE;
        }
    }

    public function read($date, $mongo = NULL)
    {
        if (FALSE != strtotime($date))
        {
            if ($this->option['drive'] == 'file')
            {
                $file_name = $this->option['file'] . $date . '.txt';

                if (file_exists($file_name))
                {
                    return file_get_contents($file_name);
                }
            }
            else if ($this->option['drive'] == 'mongo')
            {
                return $mongo->get_where('log', array('date' => $date))->result_array();
            }
        }

        return FALSE;
    }

    private function build($data, $level)
    {
        $log['stamp'] = time();
        $log['time'] = date('H:i:s', $log['stamp']);
        $log['date'] = date('Y-m-d', $log['stamp']);
        $log['data'] = $data;

        switch ($level)
        {
            case 1:
                $log['level'] = 'INFO';
                break;

            case 2:
                $log['level'] = 'NOTICE';
                break;

            case 3:
                $log['level'] = 'WARNING';
                break;

            case 4:
                $log['level'] = 'ERROR';
                break;

            case 5:
                $log['level'] = 'CRITICAL ERROR';
                break;

            default:
                $log['level'] = 'UNKNOWN';
        }

        return $log;
    }
}