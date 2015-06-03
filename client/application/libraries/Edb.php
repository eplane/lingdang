<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/* Easy DB
 * 数据库类
 *
 *
 */

class Edb
{
    private $last_query = NULL;

    private $db;

    public function __construct()
    {
        $CI =& get_instance();
        $CI->load->database();
        $this->db = $CI->db;
    }

    /*****************************[select]******************************/

    /**
     * @param $table
     * @param string $where
     * @param string $column
     * @param string $order
     * @param int $count
     * @param int $start
     */
    protected function select_query($table, $where = '', $column = '*', $order = '', $count = -1, $start = 0)
    {
        $this->db->select($column);
        $this->db->from($table);

        if ($where != '')
            $this->db->where($where);

        if ($order != '')
            $this->db->order_by($order);

        if ($count > 0)
            $this->db->limit($count, $start);

        $this->last_query = $this->db->get();
    }

    public function select($table, $where = '', $column = '*', $order = '', $count = -1, $start = 0)
    {
        $this->select_query($table, $where, $column, $order, $count, $start);

        if ($this->count() > 0)
        {
            return $this->last_query->result_array();
        }
        else
            return NULL;
    }

    public function select_one($table, $where, $column, $order = '', $start = 0)
    {
        $this->select_query($table, $where, $column, $order, 1, $start);

        if ($this->count() > 0)
        {
            $result = $this->last_query->result_array();

            return $result[0][$column];
        }
        else
            return NULL;
    }

    public function select_row($table, $where, $column = '*', $order = '', $start = 0)
    {
        $this->select($table, $where, $column, $order, 1, $start);

        if ($this->count() > 0)
        {
            $result = $this->last_query->result_array();

            return $result[0];
        }
        else
            return NULL;
    }

    /*****************************[insert]******************************/

    public function insert($table, $data)
    {
        //插入数据 $date必须是正确格式的数组
        foreach ($data as $row)
        {
            $this->db->insert($table, $row);
        }

        $count = $this->db->affected_rows();

        return $count;
    }

    public function insert_row($table, $data)
    {
        $temp[0] = $data;

        return $this->insert($table, $temp);
    }

    public function insert_id()
    {
        return $this->db->insert_id();
    }


    /*****************************[update]******************************/

    public function update($table, $data, $where)
    {
        $this->db->where($where);
        $this->db->update($table, $data);

        $count = $this->db->affected_rows();

        $this->recycle($table, $where);

        return $count;
    }

    /*****************************[delete]******************************/

    public function delete($table, $where)
    {
        $this->db->delete($table, $where);

        $count = $this->db->affected_rows();

        return $count;
    }


    /*****************************[other]******************************/

    //上一条操作影响行数
    public function count()
    {
        if (!!$this->last_query)
            return $this->last_query->num_rows();

        return NULL;
    }

    public function count_all($table, $where = '')
    {
        $sql = 'SELECT COUNT(*) as `length` FROM `' . $table . '`';

        if (!!$where)
        {
            $sql .= ' WHERE ' . $where;
        }

        $result = $this->db->query($sql);

        $rows = $result->result_array();

        return $rows[0]['length'];
    }

    public function affected()
    {
        return $this->db->affected_rows();
    }

    public function query($sql, $param = NULL)
    {
        return $this->db->query($sql, $param);
    }

    public function last_query()
    {
        return $this->db->last_query();
    }
    /*****************************[cache]******************************/

    /** 从缓存中获得数据
     * @param bool $refresh 是否强制刷新数据
     * @param string $table 表
     * @param string $where 条件
     * @param string $column 列
     * @param string $order 排序
     * @param int $count 条目数
     * @param int $start 起点
     * @return mixed 返回数据，失败返回NULL
     */
    public function get($refresh, $table, $where = '', $column = '*', $order = '', $count = -1, $start = 0)
    {
        $CI =& get_instance();
        $CI->load->library('Cache');

        //如果加载缓存类失败
        if (!$CI->cache)
        {
            return $this->select($table, $where, $column, $order, $count, $start);
        }

        $key = md5($table . $where);

        if (TRUE === $refresh)
        {
            $CI->cache->delete($key);
        }

        //如果缓存中没有数据
        $data = $CI->cache->get($key, function ($key) use ($CI, $table, $where, $column, $order, $count, $start)
        {
            $data = $this->select($table, $where, $column, $order, $count, $start);

            $CI->cache->save($key, $data, $CI->config->item('data_timeout'));
        });

        return $data;
    }


    /** 在一个表中查询一行数据，注意，如果有多行返回值，只返回第一行
     * @param $refresh
     * @param $table
     * @param $id
     * @return mixed
     */
    public function get_one($refresh, $table, $where, $column = '*')
    {
        $CI =& get_instance();
        $CI->load->library('Cache');

        $data = $this->get($refresh, $table, $where, $column);

        return (!!$data[0]) ? ($data[0]) : (NULL);
    }

    public function recycle($table, $where)
    {
        $CI =& get_instance();
        $CI->load->library('Cache');

        if (!!$CI->cache)
        {
            $key = md5($table . $where);
            return $CI->cache->delete($key);
        }
        else
            return FALSE;
    }

    public function recycle_one($table, $id)
    {
        return $this->recycle($table, '`id` = ' . $id);
    }
}