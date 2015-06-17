<link href="<?php echo get_path('common-js'); ?>easyform/easyform.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="<?php echo get_path('common-js'); ?>simditor/styles/simditor.css"/>


<div class="box box-primary">
    <div class="box-header"><i class="fa fa-user"></i>

        <h3 class="box-title">添加文档</h3></div>

    <div class="box-body">

        <?php echo validation_errors('<div class="callout callout-danger">', '</div>'); ?>

        <div class="form-horizontal">

            <?php echo form_open(base_url() . 'doc/add.html', array('id' => 'form')); ?>

            <div class="form-group">

                <div class="col-md-3">
                    <label for="type">文档分类</label>
                    <select id="type" name="type" class="form-control">
                        <option>用户协议</option>
                    </select>
                </div>

                <div class="col-md-9">
                    <label for="content">文档名称</label><input type="text" id="name" name="name" class="form-control"
                                                            placeholder="文档名称" data-easyform="length:6 40;"
                                                            data-easytip="position:bottom;"
                                                            value="<?php echo set_value('name'); ?>">
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-12">
                    <label for="content">文档内容</label>
                    <textarea id="content" placeholder="这里输入内容"
                              name="content"><?php echo set_value('content'); ?></textarea>
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
<script type="text/javascript" src="<?php echo get_path('common-js'); ?>simditor/scripts/module.js"></script>
<script type="text/javascript" src="<?php echo get_path('common-js'); ?>simditor/scripts/uploader.js"></script>
<script type="text/javascript" src="<?php echo get_path('common-js'); ?>simditor/scripts/hotkeys.js"></script>
<script type="text/javascript" src="<?php echo get_path('common-js'); ?>simditor/scripts/simditor.js"></script>
<script type="text/javascript" src="<?php echo get_path('common-js'); ?>common.js"></script>
<script>

    $(document).ready(function ()
    {
        $("#form").easyform();

        var id = uuid(8, 16);

        var editor = new Simditor({
            textarea: $('#content'),
            defaultImage: '<?php echo get_path('img'); ?>ajax-loader.gif',
            upload: {
                url: '<?php echo base_url();?>ajax/upload_1.html',
                params: {id: id}, //键值对,指定文件上传接口的额外参数,上传的时候随文件一起提交
                fileKey: id, //服务器端获取文件数据的参数名
                connectionCount: 3,
                leaveConfirm: '正在上传文件'
            }
        });

        editor.on('valuechanged', function ()
        {
            id = uuid(8, 16);

            this.opts.upload.fileKey = id;
            this.opts.upload.params = {id: id};
        });
    });

</script>