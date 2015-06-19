<link href="<?php echo get_path('common-js'); ?>easyform/easyform.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css">
<link href="<?php echo get_path('common-js'); ?>froala/css/froala_editor_l.css" rel="stylesheet" type="text/css">
<link href="<?php echo get_path('common-js'); ?>froala/css/froala_style_l.css" rel="stylesheet" type="text/css">


<div class="box box-primary">
    <div class="box-header"><i class="fa fa-user"></i>

        <h3 class="box-title">添加文档</h3></div>

    <div class="box-body">

        <?php echo validation_errors('<div class="callout callout-danger">', '</div>'); ?>

        <div class="form-horizontal">

            <?php
            if (isset($doc['_id']))
            {
                echo form_open(base_url() . 'doc/edit/' . get_mongo_id($doc) . '.html', array('id' => 'form'));
            }
            else
            {
                echo form_open(base_url() . 'doc/add.html', array('id' => 'form'));
            }
            ?>

            <div class="form-group">

                <div class="col-md-3">
                    <label for="type">文档分类</label>
                    <select id="type" name="type" class="form-control">
                        <option value="用户协议" <?php echo set_select('type', '用户协议', $doc['type']); ?>>用户协议</option>
                    </select>
                </div>

                <div class="col-md-9">
                    <label for="content">文档名称</label><input type="text" id="name" name="name" class="form-control"
                                                            placeholder="文档名称" data-easyform="length:6 40;"
                                                            data-easytip="position:bottom;"
                                                            value="<?php echo set_value('name', $doc['name']); ?>">
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-12">
                    <label for="content">文档内容</label>
                    <textarea id="content" placeholder="这里输入内容"
                              name="content"><?php echo set_value('content', $doc['content']); ?></textarea>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-3">
                </div>
            </div>


            <div class="form-group">
                <div class="col-md-12">
                    <input type="submit" id="sss" value="保存" class="btn btn-default">
                </div>
            </div>

            </form>

        </div>

    </div>


</div>


<script src="<?php echo get_path('common-js'); ?>easyform/easyform.js" type="text/javascript"></script>
<script src="<?php echo get_path('common-js'); ?>froala/js/froala_editor.min.js"></script>
<!--[if lt IE 9]>
<script src="<?php echo get_path('common-js'); ?>froala/js/froala_editor_ie8.min.js"></script>
<![endif]-->
<script src="<?php echo get_path('common-js'); ?>froala/js/plugins/tables.min.js"></script>
<script src="<?php echo get_path('common-js'); ?>froala/js/plugins/lists.min.js"></script>
<script src="<?php echo get_path('common-js'); ?>froala/js/plugins/colors.min.js"></script>
<script src="<?php echo get_path('common-js'); ?>froala/js/plugins/media_manager.min.js"></script>
<script src="<?php echo get_path('common-js'); ?>froala/js/plugins/font_family.min.js"></script>
<script src="<?php echo get_path('common-js'); ?>froala/js/plugins/font_size.min.js"></script>
<script src="<?php echo get_path('common-js'); ?>froala/js/plugins/block_styles.min.js"></script>
<script src="<?php echo get_path('common-js'); ?>froala/js/plugins/video.min.js"></script>
<script src="<?php echo get_path('common-js'); ?>froala/js/plugins/fullscreen.min.js"></script>
<script src="<?php echo get_path('common-js'); ?>froala/js/langs/zh_cn.js"></script>

<script>

    $(document).ready(function ()
    {
        $("#form").easyform();

        $('#content')
            .editable({
                inlineMode: false,
                allowedImageTypes: ['jpeg', 'jpg', 'png', 'gif'],
                language: 'zh_cn',
                imageUploadURL: '<?php echo base_url();?>ajax/upload_1.html',
                imageUploadParams: {m: 1, id: "file"},
                imageDeleteURL: '<?php echo base_url();?>ajax/delete_1.html',
            })

            .on('editable.afterRemoveImage', function (e, editor, $img)
            {
                // Set the image source to the image delete params.
                editor.options.imageDeleteParams = {file: $img.attr('src')};

                // Make the delete request.
                editor.deleteImage($img);
            });

    });

</script>