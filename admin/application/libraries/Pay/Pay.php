<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
 *
 *
 *
 *
 * ---------------------------------[更新列表]---------------------------------
 */

interface ipay
{
    /**
     * @return array
     */
    public function pay();

    /** 返回当前的配置是否支持该adapter
     * @return bool
     */
    public function is_supported();
}

class Pay extends CI_Driver_Library
{
    protected $valid_drivers = array(
        'ali',
        'yl'
    );


    public function __construct($config = array())
    {
    }

//    public function pay()
//    {
//        return $this->{$this->_adapter}->pay();
//    }

    public function is_supported()
    {
        return FALSE;
    }

    private function create_no()
    {

    }
}