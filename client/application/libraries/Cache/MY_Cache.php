<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/* Easy Cache
 * 缓存类
 *
 * 需要注意的是，使用redis、memcached的时候，需要额外的配置相关的环境
 *
 */

class MY_Cache extends CI_Cache
{
    public function __construct($config = array())
    {
        $CI =& get_instance();
        $config['adapter'] = $CI->config->item('cache_driver');

        parent::__construct($config);
    }

    public function driver_name()
    {
        return $this->_adapter;
    }

    public function get($id, Closure $missing = NULL)
    {
        $data = parent::get($id);

        if( $data === FALSE && !!$missing )
        {
           $missing($id);

            return parent::get($id);
        }

        return $data;
    }
}