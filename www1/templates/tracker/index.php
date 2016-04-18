<div class="row">
    <div class="col-xs-2 col-md-1">
        <div class="stat-icon" style="color:#4BAAB7;">
            <i class="fa fa-check-circle-o fa-3x stat-elem"></i>
        </div>
    </div>
    <div class="col-xs-9 col-md-10">
        <h1>Time Tracker <img src="<?php echo SITE_DIR; ?>images/main/715.gif" id="preloader"> </h1>
    </div>
</div>
<div class="row">
    <div class="col-md-12" id="workspaces_container">
        <?php require(TEMPLATE_DIR . 'tracker' . DS . 'workspaces.php'); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12" id="tasks_container">
        <?php echo $tasks_template; ?>
    </div>
</div>
<div class="modal fade" id="edit_task_modal">
    <div class="modal-dialog modal-lg" style="width: 90%; max-width: 900px;">
<!--        <form method="post" autocomplete="off" id="edit_task_form" class="form-horizontal">-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Edit Task Time</h4>
                </div>
                <div class="modal-body with-padding" id="edit_task_form_container">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
<!--        </form>-->
    </div>
</div>
<script type="text/javascript">
    $ = jQuery.noConflict();
    var $preloader = $("#preloader");
    $(document).ready(function () {
        $("body").on("click", ".workspace", function () {
            $preloader.show();
            var workspace_id = $(this).attr('data-id');
            var workspace_name = $(this).html();
            var params = {
                'action': 'set_workspace',
                'values': {workspace_id: workspace_id, workspace_name: workspace_name},
                'callback': function (msg) {
                    $("#tasks_container").html(msg);
                    $("#workspaces_container").html('');
                    $preloader.hide();
                }
            };
            ajax(params);
        });

        $("body").on("click", "#update_workspace", function () {
            $preloader.show();
            var params = {
                'action': 'update_workspace',
                'callback': function (msg) {
                    $("#tasks_container").html(msg);
                    $preloader.hide();
                }
            };
            ajax(params);
        });

        $("body").on("click", "#change_workspace", function () {
            $preloader.show();
            var params = {
                'action': 'change_workspace',
                'callback': function (msg) {
                    $("#tasks_container").html('');
                    $("#workspaces_container").html(msg);
                    $preloader.hide();
                }
            };
            ajax(params);
        });
    });
</script>
