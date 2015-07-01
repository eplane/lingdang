<link href="<?php echo get_path('common-js'); ?>easyform/easyform.css" rel="stylesheet" type="text/css"/>

<?php echo validation_errors('<div class="callout callout-danger">', '</div>'); ?>
<?php echo form_open(base_url() . 'user/me.html', Array('enctype' => 'multipart/form-data', 'id' => 'form')); ?>


<div class="form-horizontal">
    <div class="box box-primary">
        <div class="box-header"><i class="fa fa-list"></i>

            <h3 class="box-title">频道列表</h3></div>

        <div class="box-body">


            <div class="form-group">
            <div class="col-md-1">
                a
            </div>
            <div class="col-md-11">
                <div class="input-group input-group-sm">

                    <input class="form-control" type="text">
                <span class="input-group-btn">
                    <button class="btn btn-info btn-flat" type="button"><i class="fa fa-arrow-left"></i></button>
                    <button class="btn btn-info btn-flat" type="button"><i class="fa fa-arrow-up"></i></button>
                    <button class="btn btn-info btn-flat" type="button"><i class="fa fa-arrow-down"></i></button>
                    <button class="btn btn-info btn-flat" type="button"><i class="fa fa-arrow-right"></i></button>
                </span>

                </div>
            </div>
                </div>


        </div>
    </div>
</div>


</form>