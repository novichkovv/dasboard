<div class="row">
    <div class="col-xs-5 col-md-2">
        <input placeholder="Date From" class="form-control datepicker" name="common[date_from]" value="<?php echo $_POST['common']['date_from']; ?>">
    </div>
    <div class="col-xs-5 col-md-2">
        <input placeholder="Date From" class="form-control datepicker" name="common[date_from]" value="<?php echo $_POST['common']['date_from']; ?>">
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
<script type="text/javascript">
    $ = jQuery.noConflict();
    $(document).ready(function () {
        $(".datepicker").datepicker();
    });
</script>