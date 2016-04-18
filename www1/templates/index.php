<div class="row">
    <?php if ($links): ?>
        <?php foreach ($links as $link): ?>
            <div class="col-md-3">
                <a href="<?php echo SITE_DIR; ?>dashboard/<?php echo $link['url']; ?>" class="sm-st clearfix" style="display: block">
                    <span class="sm-st-icon <?php echo $link['color']; ?>"><i class="<?php echo $link['icon']; ?>"></i></span>
                    <div class="sm-st-info">
                        <?php echo $link['chart_name']; ?>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<form method="post" action="">
    <div class="row">
        <div class="col-xs-5 col-md-2">
            <input placeholder="Date From" class="form-control datepicker" name="date_start" value="<?php echo $date_start ? date('Y-m-d', strtotime($date_start)) : date('Y-m-d', strtotime(date('Y-m-d') . ' - 15 day')); ?>">
        </div>
        <div class="col-xs-5 col-md-2">
            <input placeholder="Date To" class="form-control datepicker" name="date_end" value="<?php echo $date_end ? date('Y-m-d', strtotime($date_end)) : date('Y-m-d'); ?>">
        </div>
        <div class="col-xs-2 col-md-2">
            <input type="submit" class="btn btn-info" name="choose_date_btn" value="Submit">
        </div>
    </div>
<br>
<div class="row">
    <div class="col-md-12">
        <?php echo $template; ?>
    </div>
</div>
</form>
<script type="text/javascript">
    $ = jQuery.noConflict();
    $(document).ready(function () {
        $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd'
        });
    });
</script>