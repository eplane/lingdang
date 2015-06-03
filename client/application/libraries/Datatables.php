<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
 *
 *
 *
 *
 * ---------------------------------[更新列表]---------------------------------
 */
class Datatables
{
    public function get($refresh, $table, $columns, $search_column, $where_extra)
    {
        $CI =& get_instance();

        $CI->load->library('Edb');
        $this->db = $CI->edb;

        $draw = $CI->input->post('draw');
        $start = $CI->input->post('start');
        $length = $CI->input->post('length');
        $post_columns = $CI->input->post('columns');
        $order = $CI->input->post('order');
        $search = $CI->input->post('search');

        $where = !!$search['value'] ? $search_column . ' LIKE "%' . $search['value'] . '%"' : '';

        if (!!$where && !!$where_extra)
            $where .= ' AND ';

        $where .= $where_extra;

        $order = $this->order($order, $post_columns);

        $temp = $this->db->get($refresh, $table, $where, $columns, $order, $length, $start);

        $data = Array();
        $data['draw'] = $draw;

        $data['recordsTotal'] = $this->db->count_all($table, $where_extra);
        $data['recordsFiltered'] = count($temp);

        $data['data'] = $this->data($temp, $columns);

        return $data;
    }

    private function data($temp, $columns)
    {
        $i = 0;

        $data = NULL;

        foreach ($temp as $row)
        {
            $i++;
            $row_temp = Array();
            foreach ($columns as $c)
            {
                $row_temp[] = $row[$c];
            }

            $data[] = $row_temp;
        }

        return $data;
    }

    private function order($orders, $columns)
    {
        $str = '';

        foreach ($orders as $o)
        {
            $str .= $columns[$o['column']]['name'] . ' ' . $o['dir'] . ',';
        }

        return trim($str, ',');
    }

}