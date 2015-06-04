<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/* Easy DB
 * 数据库类
 *
 *
 */

class Edb
{
    protected $last_query = NULL;

    protected $db;

    protected $ci;

    public function __construct()
    {
        $this->ci =& get_instance();
        $this->ci->load->database();
        $this->ci->load->library('Cache');
        $this->db = $this->ci->db;
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

    /** 从缓存中获得数据，需要注意同步问题。
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
        $key = md5($table . $where);

        if (TRUE === $refresh)
        {
            $this->ci->cache->delete($key);
        }

        //如果缓存中没有数据
        $data = $this->ci->cache->get($key, function ($key) use ($table, $where, $column, $order, $count, $start)
        {
            $data = $this->select($table, $where, $column, $order, $count, $start);

            $this->ci->cache->save($key, $data, $this->ci->config->item('data_timeout'));
        });

        return $data;
    }


    /** 在一个表中查询一行数据，注意，如果有多行返回值，只返回第一行
     * @param bool $refresh
     * @param string $table
     * @param int $id
     * @return array|bool
     */
    public function get_row_id($refresh, $table, $id)
    {
        $key = ($table . $id);

        if (TRUE === $refresh)
        {
            $this->ci->cache->delete($key);
        }

        //如果缓存中没有数据
        $data = $this->ci->cache->get($key, function ($key) use ($table, $id)
        {
            $data = $this->select_row($table, '`id` = ' . $id);

            $this->ci->cache->save($key, $data, $this->ci->config->item('data_timeout'));
        });

        return $data;
    }

    public function set_row_id($table, $data, $id)
    {
        $this->update($table, $data, '`id` = ' . $id);

        $key = $table . $id;

        return $this->ci->cache->delete($key);
    }

    /** 查询并缓存一个值，需要注意同步问题。
     * @param bool $refresh
     * @param string $table
     * @param string $where
     * @param string $column
     * @param string $order
     * @param int $start
     * @return mixed
     */
    public function get_value($refresh, $table, $where, $column, $order = '', $start = 0)
    {
        $key = md5($table . $where);

        if (TRUE === $refresh)
        {
            $this->ci->cache->delete($key);
        }

        //如果缓存中没有数据
        $data = $this->ci->cache->get($key, function ($key) use ($table, $where, $column, $order, $start)
        {
            $data = $this->select_one($table, $where, $column, $order, $start);

            $this->ci->cache->save($key, $data, $this->ci->config->item('data_timeout'));
        });

        return $data;
    }

    public function recycle($table, $where)
    {
        if (!!$this->ci->cache)
        {
            $key = md5($table . $where);
            return $this->ci->cache->delete($key);
        }
        else
            return FALSE;
    }

    /** 获取枚举类型的值列表
     * @param string $table 表名
     * @param string $column 列名
     * @return array
     */
    public function enum($table, $column)
    {
        $sql = 'SHOW COLUMNS FROM `' . $table . '` LIKE \'' . $column . '\'';

        $query = $this->db->query($sql);

        $enum = $query->result_array();

        $data = $enum[0]['Type'];

        $data = substr($data, 5, strlen($data) - 6);
        $data = str_replace('\'', '', $data);
        $data = explode(',', $data);

        return $data;
    }
}