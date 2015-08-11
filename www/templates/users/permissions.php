<div class="row">
    <div class="col-xs-2 col-md-1">
        <div class="stat-icon" style="color:#4BAAB7;">
            <i class="fa fa-user-times fa-3x stat-elem"></i>
        </div>
    </div>
    <div class="col-xs-9 col-md-10">
        <h1>Group Permissions</h1>
    </div>
</div>

<br>
<div class="row transparent permissions-list">
    <section class="panel general transparent-tabs" style="background: none;">
        <header class="panel-heading tab-bg-dark-navy-blue">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a data-toggle="tab" href="#system_routes">
                        <i class="fa fa-gear"></i>
                        System
                    </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#charts">
                        <i class="fa fa-bar-chart"></i>
                        Dashboard
                    </a>
                </li>
            </ul>
        </header>

        <div class="text-right" style="background-color: #fff; height: 60px; box-shadow: 0 5px 1px #B4D9E5; padding: 20px;">
            <a href="<?php echo SITE_DIR; ?>users/">USERS</a> | PERMISSIONS
        </div>
        <div style="background: #B4D9E5; height: 5px;"></div>
        <div class="panel-body">
            <div class="tab-content" style="background: none;">
                <div id="system_routes" class="tab-pane  active">
                    <form class="permissions_form" id="permissions_form_1">
                        <div class="row">
                            <div class="col-xs-12">
                                <?php foreach($result as $user_group_id => $v): ?>
                                    <div class="col-md-3 col-sm-6">
                                        <h3><?php echo $v['group_name']; ?></h3>
                                        <ul style="">
                                            <?php foreach($v['routes'] as $route): ?>
                                                <li>
                                                    <div class="checkbox">
                                                        <div class="squaredFour">
                                                            <input type="checkbox" id="squaredFour_1_<?php echo $user_group_id; ?>_<?php echo $route['id']; ?>"  name="permission[1][<?php echo $user_group_id; ?>][<?php echo $route['id']; ?>]" value="<?php echo $route['id']; ?>" <?php if($route['checked']) echo 'checked'; ?> class="parent_perm styled-checkbox">
                                                            <label for="squaredFour_1_<?php echo $user_group_id; ?>_<?php echo $route['id']; ?>"></label>
                                                        </div>
                                                        <span class="styled-checkbox-label"><?php echo $route['title']; ?></span>
                                                    </div>
                                                    <?php if($route['children']): ?>
                                                        <ul>
                                                            <?php foreach($route['children'] as $child): ?>
                                                                <li>
                                                                    <div class="checkbox">
                                                                        <div class="squaredFour">
                                                                            <input type="checkbox" id="squaredFour_1_<?php echo $user_group_id; ?>_<?php echo $child['id']; ?>"  name="permission[1][<?php echo $user_group_id; ?>][<?php echo $child['id']; ?>]" value="<?php echo $child['id']; ?>" <?php if($child['checked']) echo 'checked'; ?> class="child_perm styled-checkbox">
                                                                            <label for="squaredFour_1_<?php echo $user_group_id; ?>_<?php echo $child['id']; ?>"></label>
                                                                        </div>
                                                                        <span class="styled-checkbox-label"><?php echo $child['title']; ?></span>
                                                                    </div>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    <?php endif; ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <hr class="hr-dark-blue">
                        <div class="row">
                            <div class="col-md-5">
                                <input class="btn btn-info btn-lg btn-big" type="submit" name="save_permissions_btn" value="Save">
                            </div>
                        </div>
                    </form>

                </div>
                <div id="charts" class="tab-pane">

                </div>
            </div>
        </div>
    </section>
    <div class="row transparent">

    </div>
</div>
<script type="text/javascript">
    $ = jQuery.noConflict();
    $(document).ready(function(){
        $(".parent_perm").change(function(){
            if(!$(this).prop('checked')) {
                $(this).closest('li').find('.child_perm').each(function()
                {
                    $(this).prop('checked', false);
                })
            } else {
                $(this).closest('li').find('.child_perm').each(function()
                {
                    $(this).prop('checked', true);
                })
            }
        });
        $('.child_perm').change(function() {
            if($(this).prop('checked')) {
//                if($(this).closest('li').closest('li').find('.parent_perm').prop('checked', false)) {
                $(this).closest('ul').closest('li').find('.parent_perm').prop('checked', true);
//                }
            }
        });
        $('[data-toggle="tab"]').click(function(){
            var part = $(this).attr('href').substr(1);
            var params = {
                'action': 'get_' + part + '_permissions',
                callback: function(msg) {
                    $("#" + part).html(msg);
                }
            };
            if(!$("#" + part).children().length) {
                ajax(params);
            }
        });
        $("body").on("submit", ".permissions_form", function(e)
        {
            e.preventDefault();
            var form_id = $(this).attr('id');
            var params = {
                action: 'save_permission',
                get_from_form: form_id,
                callback: function(msg) {
                    try {
                        var respond = JSON.parse(msg);
                    }
                    catch (e) {
                        Notifier.error('Save failed', 'Unpredicted Error!');
                        return false;
                    }
                    if (respond.status == 1) {
                        Notifier.success('The data has been saved!', 'Success');
                    } else {
                        Notifier.error('Save failed', 'Unpredicted Error!');
                    }
                }
            };
            ajax(params);
        });
    });
</script>