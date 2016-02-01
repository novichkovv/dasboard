<form method="post" action="">
    <div class="row">
        <div class="col-xs-5 col-md-2">
            <input placeholder="Date From" class="form-control datepicker" name="date_from" value="<?php echo $date_from; ?>">
        </div>
        <div class="col-xs-5 col-md-2">
            <input placeholder="Date To" class="form-control datepicker" name="date_to" value="<?php echo $date_to; ?>">
        </div>
        <div class="col-xs-8 col-md-3">
            <select name="user" class="form-control">
                <option value="">
                    All
                </option>
                <?php foreach ($users_list as $user): ?>
                    <option value="<?php echo $user['id']; ?>"
                        <?php if (!empty($_POST['user']) && $user['id'] == $_POST['user']): ?>
                            selected
                        <?php endif; ?>>
                        <?php echo $user['user_name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-xs-2 col-md-2">
            <input type="submit" class="btn btn-info" value="Submit">
        </div>
    </div>
    <br>
</form>

<div class="panel panel-default">
    <div class="panel panel-heading">
        <h3 class="panel-title">Deductions</h3>
    </div>
    <div class="panel-body">
        
    </div>
</div>
<script type="text/javascript">
    $ = jQuery.noConflict();
    $(document).ready(function () {
        $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd'
        });
    });
</script>