<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/* 文件维护类
 * 上传的全部文件都会放到一个临时文件夹，该文件夹由
 *
 *
 */


class File
{
    private $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        //$this->CI->load->database();

        $this->CI->load->library('Guid');
    }

    //保存文件
    public function upload($file, $types = NULL, $size = NULL)
    {
        //删除连续上传的重复文件
        $last_file = $this->CI->session->$file;

        if (NULL != $last_file && file_exists($last_file['fullpath'] . $last_file['file']))
        {
            unlink($last_file['fullpath'] . $last_file['file']);
        }

        //保存文件到 temp
        $config = $this->CI->config->item('upload');        //获取默认配置
        $config['upload_path'] = $config['temp_path'];     //将临时路径设置成目标路径

        if (NULL != $size)
            $config['max_size'] = $size;                    //设置允许文件类型

        if (NULL != $types)
            $config['allowed_types'] = $types;           //设置允许文件类型

        $this->CI->load->library('upload', $config);

        $result = array();

        if (!$this->CI->upload->do_upload($file))
        {
            $result['error'] = 'TRUE';
            $result['method'] = 'save';
            $result['message'] = $this->CI->upload->display_errors();
            return $result;
        }
        else
        {
            $data = array('upload_data' => $this->CI->upload->data());

            //保存文件信息，供后面处理数据使用
            $cache['file'] = $data['upload_data']['file_name'];
            $cache['fullpath'] = $data['upload_data']['file_path'];
            //var_dump($cache);

            $this->CI->session->$file = $cache;

            $result['error'] = 'FALSE';
            $result['data'] = $data['upload_data'];
        }

        return $result;
    }

    /** 裁剪图片
     * @param Array $filedata 文件上传成功后的返回数据
     * @param string $filesession 文件控件的名称
     * @param int $x 坐标起点x
     * @param int $y 坐标起点y
     * @param int $w 目标宽
     * @param int $h 目标高
     * @param int $iw 文件宽
     * @param int $ih 文件高
     * @return array
     */
    public function img_crop($filedata, $filesession, $x, $y, $w, $h, $iw, $ih)
    {
        $this->CI->load->library('image_lib');  //加载图像处理库

        $sx = $filedata['image_width'] / $iw;
        $sy = $filedata['image_height'] / $ih;

        $filename = Guid::long();

        $config['image_library'] = 'gd2';
        $config['quality'] = 90;
        $config['source_image'] = $filedata['full_path'];
        $config['new_image'] = $filedata['file_path'] . $filename . $filedata['file_ext'];
        $config['maintain_ratio'] = FALSE;          //保证设置的长宽有效
        $config['x_axis'] = $x * $sx;
        $config['y_axis'] = $y * $sy;
        $config['width'] = $w * $sx;
        $config['height'] = $h * $sy;

        $this->CI->image_lib->initialize($config);

        $result = array();

        if (!$this->CI->image_lib->crop())
        {
            $result['error'] = 'TRUE';
            $result['method'] = 'resize';
            $result['message'] = $this->CI->image_lib->display_errors();
        }
        else
        {
            //保存文件信息，供后面处理数据使用
            $cache['file'] = $filename . $filedata['file_ext'];
            $cache['fullpath'] = $filedata['file_path'];
            //var_dump($cache);
            $this->CI->session->$filesession = $cache;

            $filedata['file_name'] = $filename . $filedata['file_ext'];
            $filedata['raw_name'] = $filename;

            $result['error'] = 'FALSE';
            $result['data'] = $filedata;
        }

        //删除上传的源文件
        unlink($filedata['full_path']);

        return $result;
    }

    /** 删除旧文件，使用新文件
     * @param string $old_file 旧文件
     * @param string $new_file 新文件
     * @param string $path 子目录
     */
    public function replace($old_file, $new_file)
    {
        $this->delete($old_file);
        return $this->save($new_file);
    }

    /** 将一个刚上传的临时文件保存到正式文件夹
     * @param $file
     * @return bool|string
     */
    public function save($file)
    {
        if (isset($_SESSION[$file]))
        {
            $filename = $_SESSION[$file]['file'];

            unset($_SESSION[$file]);

            if ($filename != '')
            {
                $file_name = $this->create_file_name();

                $ext = substr($filename, strrpos($filename, '.'));

                $path = $this->get_path($file_name);

                if (FALSE == is_dir($path))
                {
                    mkdir($path);
                }

                $c = $this->CI->config->item('upload');     //获取文件上传根目录

                if (rename($c['temp_path'] . $filename, $path . $file_name . $ext))
                {
                    return $file_name . $ext;
                }

                return FALSE;
            }
        }

        return FALSE;
    }

    /**
     * 清除临时文件
     */
    public function clear()
    {

    }

    public function delete($file)
    {
        if (!!$file)
        {
            $path = $this->get_path($file);

            if (FALSE != is_dir($path))
            {
                if (file_exists($path . $file))
                {
                    return unlink($path . $file);
                }
            }

            return FALSE;
        }
        else
        {
            return FALSE;
        }
    }

    private function create_file_name()
    {
        return date('Ymd', time()) . Guid::short();
    }

    private function get_path($file)
    {
        $c = $this->CI->config->item('upload');     //获取文件上传根目录

        $path = $c['upload_path'] . substr($file, 0, 8) . '/';

        return $path;
    }

    public function get_file($filename)
    {
        $c = $this->CI->config->item('upload');     //获取文件上传根目录

        $path = $c['web_root'] . substr($filename, 0, 8) . '/';

        return $path . $filename;
    }
}