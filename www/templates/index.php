<form method="post" action="">

    <div class="row">
        <div class="col-xs-5 col-md-2">
            <input placeholder="Date From" class="form-control datepicker" name="date_start" value="<?php echo $_POST['date_start'] ? $_POST['date_start'] : date('Y-m-d', strtotime(date('Y-m-d') . ' - 15 day')); ?>">
        </div>
        <div class="col-xs-5 col-md-2">
            <input placeholder="Date To" class="form-control datepicker" name="date_end" value="<?php echo $_POST['date_end'] ? $_POST['date_end'] : date('Y-m-d'); ?>">
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