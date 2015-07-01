<?php echo validation_errors('<div class="callout callout-danger">', '</div>'); ?>
<?php echo form_open(base_url() . 'word.html', Array('id' => 'form')); ?>

<div class="form-horizontal">
    <div class="box box-primary">

        <div class="box-header"><i class="fa fa-user"></i>

            <h3 class="box-title">关键词</h3></div>

        <div class="box-body">

            <div class="form-group">
                <div class="col-md-12">
                    <label for="uid">禁止词 </label> <small class="text-muted">请用逗号“,”来分割不同的单词。这些单词将禁止出现在任何输入中，如果出现，则不会通过表单验证！</small>
                    <textarea id="word0" name="word0" class="form-control"><?php echo set_value('word0', $word[0]['word']); ?></textarea>

                </div>
            </div>


            <div class="form-group">
                <div class="col-md-12">
                    <input class="btn btn-default" id="save" type="submit" value="保存">
                </div>
            </div>

        </div>
    </div>
</div>

</form>