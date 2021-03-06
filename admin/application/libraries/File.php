<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/* 文件维护类
 *
 *
 *
 */

class File
{
    private $ci;

    public function __construct()
    {
        $this->ci =& get_instance();
        $this->ci->load->library('Guid');
    }

    /** 上传文件
     * @param $file string 上传文件的数据索引
     * @param $is_temp bool 是否上传到temp目录
     * @param $replace bool
     * @param $types string 允许的类型，类似jpg|png|gif
     * @param $size int 允许的大小，单位k
     */
    public function upload($file, $is_temp = TRUE, $replace = TRUE, $types = NULL, $size = NULL)
    {
        $config = $this->ci->config->item('upload');        //获取默认配置

        //是否将文件上传到temp文件夹
        if ($is_temp)
        {
            $config['upload_path'] = $config['temp_path'];     //将临时路径设置成目标路径
        }
        else
        {
            $config['encrypt_name'] = FALSE;
            $config['file_name'] = $this->create_file_name();
            $config['upload_path'] = $this->get_path($config['file_name']);
        }

        if (FALSE == is_dir($config['upload_path']))
        {
            mkdir($config['upload_path'], 0777, TRUE);
        }

        if (NULL != $size)
        {
            $config['max_size'] = $size;                    //设置允许文件大小
        }

        if (NULL != $types)
        {
            $config['allowed_types'] = $types;           //设置允许文件类型
        }

        //加载上传类
        $this->ci->load->library('upload', $config);

        $result = array();

        //上传文件
        if (!$this->ci->upload->do_upload($file))
        {
            $result['error'] = 'TRUE';
            $result['success'] = 'FALSE';
            $result['method'] = 'upload';
            $result['message'] = $this->ci->upload->display_errors();
            return $result;
        }
        else
        {
            $data = array('upload_data' => $this->ci->upload->data());

            $result['error'] = 'FALSE';
            $result['success'] = 'TRUE';
            $result['method'] = 'upload';
            $result['data'] = $data['upload_data'];
            $result['message'] = '';
        }

        //保存文件信息，供后面处理数据使用
        $cache['file'] = $result['data']['file_name'];
        $cache['path'] = $result['data']['file_path'];

        //如果上传同id文件不覆盖
        if (FALSE == $replace)
        {
            //保存临时文件的信息
            $_SESSION[$file][] = $cache;
        }
        else
        {
            if (isset($_SESSION[$file]) && isset($_SESSION[$file]['file']))
            {
                if ($is_temp)
                {
                    $this->delete_temp($_SESSION[$file]['file']);
                }
                else
                {
                    $this->delete($_SESSION[$file]['file']);
                }
            }

            $_SESSION[$file] = $cache;
        }

        return $result;
    }

    public function upload_base64($file, $type, $types = NULL, $size = NULL)
    {
        $config = $this->ci->config->item('upload');        //获取默认配置

        if ($size == NULL)
        {
            $size = $config['max_size'];
        }
        if ($types == NULL)
        {
            $types = $config['allowed_types'];
        }

        $name = $this->create_file_name();

        $path = $this->get_path($name);

        if (FALSE == is_dir($path))
        {
            mkdir($path, 0777, TRUE);
        }

        if (strpos($types, $type) === FALSE)
        {
            $result = array(
                'error' => 1,
                'msg' => '该类型文件不允许上传'
            );

            return $result;
        }

        $file_str = base64_decode($file);

        if ((strlen($file_str) / 1000) > $size)
        {
            $result = array(
                'error' => 1,
                'msg' => '文件超过限制大小'
            );

            return $result;
        }

        $r = file_put_contents($path . $name . '.' . $type, $file_str);



        $result = FALSE;

        if ($r != FALSE)
        {
            $isize = getimagesize($path . $name . '.' . $type);

            $result = array(
                'error' => 0,
                'file_name' => $name . '.' . $type,
                'file_path' => $path,
                'file_type' => $isize['mime'],
                'full_path' => $path . $name . '.' . $type,
                'raw_name' => $name,
                'file_ext' => '.' . $type,
                'file_size' => $r / 1000,
                'image_width' => $isize[0],
                'image_height' => $isize[1],
            );
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
        $this->ci->load->library('image_lib');  //加载图像处理库

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

        $this->ci->image_lib->initialize($config);

        $result = array();

        if (!$this->ci->image_lib->crop())
        {
            $result['error'] = 'TRUE';
            $result['success'] = 'FALSE';
            $result['method'] = 'resize';
            $result['message'] = $this->ci->image_lib->display_errors();
        }
        else
        {
            //保存文件信息，供后面处理数据使用
            $cache['file'] = $filename . $filedata['file_ext'];
            $cache['fullpath'] = $filedata['file_path'];
            //var_dump($cache);
            $this->ci->session->$filesession = $cache;

            $filedata['file_name'] = $filename . $filedata['file_ext'];
            $filedata['raw_name'] = $filename;

            $result['error'] = 'FALSE';
            $result['success'] = 'TRUE';
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
                    mkdir($path, 0777, TRUE);
                }

                $c = $this->ci->config->item('upload');     //获取文件上传根目录

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

    public function delete_temp($file)
    {
        if (!!$file)
        {
            $path = $this->get_temp_path();

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

    //30位数字字母混合，字母小写
    public function create_file_name()
    {
        //支持2038年以后的时间
        $date = new DateTime();
        $date = $date->format('Ymd');

        return $date . Guid::short();
    }

    public function get_path($file)
    {
        $c = $this->ci->config->item('upload');     //获取文件上传根目录

        $file = get_file_name($file);

        $path = $c['upload_path'] . substr($file, 0, 8) . '/' . substr($file, strlen($file) - 1, 1) . '/';

        return $path;
    }

    public function get_web_path($file)
    {
        $c = $this->ci->config->item('upload');     //获取文件上传根目录

        $file = get_file_name($file);

        $path = $c['web_root'] . substr($file, 0, 8) . '/' . substr($file, strlen($file) - 1, 1) . '/';

        return $path;
    }

    private function get_temp_path()
    {
        $c = $this->ci->config->item('upload');     //获取文件上传根目录

        $path = $c['temp_path'];

        return $path;
    }

    public function get_file($filename)
    {
        //$c = $this->ci->config->item('upload');     //获取文件上传根目录

        $path = $this->get_web_path($filename);

        return $path . $filename;
    }

    public function get_temp_file($filename)
    {
        $c = $this->ci->config->item('upload');     //获取文件上传根目录

        $path = $c['web_temp'];

        return $path . $filename;
    }

    public function is_temp_file($file)
    {
        if (!!$file)
        {
            $path = $this->get_temp_path();

            if (FALSE != is_dir($path))
            {
                if (file_exists($path . $file))
                {
                    return TRUE;
                }
            }

            return FALSE;
        }
        else
        {
            return FALSE;
        }
    }
}