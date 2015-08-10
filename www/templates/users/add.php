<div class="row">
    <div class="col-xs-2 col-md-1">
        <div class="stat-icon" style="color:#4BAAB7;">
            <i class="fa fa-user-plus fa-3x stat-elem"></i>
        </div>
    </div>
    <div class="col-xs-9 col-md-10">
        <h1><?php echo ($_GET['id'] ? 'Edit' : 'Add'); ?> User</h1>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-offset-1 col-md-4 col-sm-6">
        <form action="" method="post" id="user_form">
            <div class="form-group">
                <label>Email</label>
                <input type="text" class="form-control" name="email" value="<?php echo $user['email']; ?>">
            </div>
            <div class="form-group">
                <label>Group</label>
                <select name="user_group_id" class="form-control">
                    <?php foreach($user_groups as $group): ?>
                        <option value="<?php echo $group['id']; ?>" <?php if($user['user_group_id'] == $group['id'])echo 'selected'; ?>>
                            <?php echo $group['group_name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>First Name</label>
                <input type="text" class="form-control" name="user_name" value="<?php echo $user['user_name']; ?>">
            </div>
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" class="form-control" name="user_surname" value="<?php echo $user['user_surname']; ?>">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" name="user_password" value=""<?php if($_GET['id']) echo ' placeholder="Leave empty if don\'t change"' ?>>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" class="form-control" name="confirm" value=""<?php if($_GET['id']) echo ' placeholder="Leave empty if don\'t change"' ?>>
            </div>
            <div class="form-group">
                <input class="btn btn-primary btn-lg" type="submit" name="save_user_btn" value="Save">
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    $ = jQuery.noConflict();
    $(document).ready(function()
    {
        $("#user_form").submit(function()
        {
            if(!$("input[name='email']").val() || $("input[name='email']").val() == '') {
                alert('Enter Email!');
                return false;
            }
            if($("input[name='user_password']").val() != $("input[name='confirm']").val()) {
                alert('Passwords don\'t match!');
                return false;
            }
            return true;
        });
    });
</script>