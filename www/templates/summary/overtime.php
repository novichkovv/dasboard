<form method="post" action="" style="position: absolute; z-index: 1000; height: 90px; background-color: #fff; width: 100%; padding: 10px;">
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
    <div style="width: 10px; height: 10px; background-color: black; display: inline-block"></div> - Suggested &nbsp;&nbsp;&nbsp;&nbsp;
    <div style="width: 10px; height: 10px; background-color: red; display: inline-block"></div> - Approved &nbsp;&nbsp;&nbsp;&nbsp;
</form>
<div class="grey_stripe"></div>
<div class="corner_cell">Employees</div>
<div class="corner_cell bottom_corner"></div>
<div class="table-scrollable" style="max-height: 500px; overflow: auto; margin-top: 99px;">
    <table class="table table-bordered">
        <thead style="position: absolute; background-color: #fff; z-index: 999">
        <tr>
            <th width="150"><div style="width: 250px;">Employees</div></th>
            <?php foreach ($dates as $date): ?>
                <th><div style="width: 50px;"><?php echo date('d/M', strtotime($date)); ?></div> </th>
            <?php endforeach; ?>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td colspan="1000"><div style="height: 19px;"></div> </td>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td class="user_id" data-id="<?php echo $user['id']; ?>" style=" position: absolute; background-color: #fff; z-index: 999;">
                    <div style="width: 250px; height: 50px; text-overflow: clip;">
                        <?php echo $user['user_name']; ?>
                    </div>
                </td>
                <td><div style="width: 250px;"></div> </td>
                <?php foreach ($dates as $date): ?>
                    <td data-date="<?php echo $date; ?>" data-user="<?php echo $user['id']; ?>">
                        <div style="width: 50px; height: 50px;"
                             <?php if ($overtime[$date][$user['id']]['suggested'] && (!isset($dashboard_user) || $dashboard_user ==$overtime[$date][$user['id']]['dashboard_user_id'])): ?>
                                 data-trigger="hover" data-toggle="popover" data-placement="bottom"
                                 data-content="<?php echo $overtime[$date][$user['id']]['comments'] ? $overtime[$date][$user['id']]['comments'] : ' - '; ?>"
                             <?php endif; ?>
                            >
                            <?php if(!$overtime[$date][$user['id']]): ?>
                                <a data-toggle="modal" href="#overtime_suggest_modal" class="btn btn-xs suggest_overtime" type="button" style="background: none;">
                                    <i class="fa fa-edit"></i>
                                </a>
                            <?php endif; ?>
                            <?php if (!isset($dashboard_user) || $overtime[$date][$user['id']]['dashboard_user_id'] == $dashboard_user): ?>
                                <?php if($overtime[$date][$user['id']]): ?>
                                    <?php echo $overtime[$date][$user['id']]['suggested']; ?>
                                    <?php if(!$overtime[$date][$user['id']]['approved'] && $allowed): ?>
                                        <a data-toggle="modal" data-id="<?php echo $overtime[$date][$user['id']]['id']; ?>" href="#overtime_approve_modal" class="btn btn-xs approve_overtime" type="button" style="background: none;">
                                            <i class="fa fa-edit text-info"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if($overtime[$date][$user['id']]['approved']): ?>
                                        <div style="color: red;">
                                            <?php echo $overtime[$date][$user['id']]['approved']; ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<div id="lower_space"></div>
<div class="modal fade" id="overtime_suggest_modal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form method="post" id="overtime_suggest_form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Add Overtime</h4>
                </div>
                <div class="modal-body with-padding">
                    <div class="form-group">
                        <lable>Overtime (hours)</lable>
                        <input data-require="1" data-validate="numeric" class="form-control" name="overtime[overtime_suggested]">
                        <div class="validate-message error-require">
                            Required Field
                        </div>
                        <div class="validate-message error-validate">
                            Must be a Number
                        </div>
                        <input type="hidden" id="overtime_suggest_user" name="overtime[user_id]">
                        <input type="hidden" id="overtime_suggest_date" name="overtime[work_date]">
                    </div>
                    <div class="form-group">
                        <label>Comment</label>
                        <textarea name="overtime[comments]" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="overtime_approve_modal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form method="post" id="overtime_approve_form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Approve Overtime</h4>
                </div>
                <div class="modal-body with-padding">
                    <div class="form-group">
                        <lable>Overtime (hours)</lable>
                        <input data-require="1" data-validate="numeric" class="form-control" name="overtime[overtime_approved]">
                        <div class="validate-message error-require">
                            Required Field
                        </div>
                        <div class="validate-message error-validate">
                            Must be a Number
                        </div>
                        <input type="hidden" id="overtime_id" name="overtime[id]">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $ = jQuery.noConflict();
    $(document).ready(function () {
        $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd'
        });
        $('[data-toggle="popover"]').popover({trigger: 'hover','placement': 'top', 'html': true});
        var $table = $(".table-scrollable");
        switch (browser()) {
            case "ff":
                $table.scroll(function() {
                    var top = $(this).scrollTop();
                    var left = $(this).scrollLeft();
                    var $thead = $('thead');
                    $thead.css('margin-left', -left/500);
                    var $user_id = $('.user_id');
                    $user_id.css('margin-top', -top/500);
                    $user_id.css('margin-left', left);
                    $thead.css('margin-top', top);
                });
                break;
            case "chrome":
            default :
                $table.scroll(function() {
                    var top = $(this).scrollTop();
                    var left = $(this).scrollLeft();
                    $('thead').css('margin-left', -left);
                    $('.user_id').css('margin-top', -top);
                });

                break;
        }


//        $(document).scroll(function(e) {
//            var offset = $('#under_footer').offset().top;
//            var scroll_top = $(this).scrollTop() - 130;
//            var height = screen.height;
//            console.log(offset);
//            console.log(scroll_top);
//            console.log(height);
//            console.log(offset - scroll_top);
//
//            if(offset - height <= scroll_top) {
//                $(document).scrollTop(scroll_top + 100);
//            }
//        });

        $("body").on("click", ".suggest_overtime", function () {
            var date = $(this).closest('td').attr('data-date');
            var user_id = $(this).closest('tr').find('.user_id').attr('data-id');
            $("#overtime_suggest_user").val(user_id);
            $("#overtime_suggest_date").val(date);
        });

        $("body").on("click", ".approve_overtime", function () {
            var id = $(this).attr('data-id');
            $("#overtime_id").val(id);
        });

        $("#overtime_suggest_form").submit(function() {
            if(validate('overtime_suggest_form')) {
                var params = {
                    'action': 'suggest_overtime',
                    'get_from_form': 'overtime_suggest_form',
                    'callback': function (msg) {
                        ajax_respond(msg, function(respond) {
                            $("td[data-user='" + respond.user_id + "'][data-date='" + respond.date + "']").html(respond.template);
                            $("#overtime_suggest_modal").modal('hide');
                        },
                        function() {
                            Notifier.warning('Something went wrong', 'Fault');
                        })
                    }
                };
                ajax(params);
            }
            return false;
        });

        $("#overtime_approve_form").submit(function() {
            if(validate('overtime_approve_form')) {
                var params = {
                    'action': 'approve_overtime',
                    'get_from_form': 'overtime_approve_form',
                    'callback': function (msg) {
                        ajax_respond(msg, function(respond) {
                                $("td[data-user='" + respond.user_id + "'][data-date='" + respond.date + "']").html(respond.template);
                                $("#overtime_approve_modal").modal('hide');
                            },
                            function() {
                                Notifier.warning('Something went wrong', 'Fault');
                            })
                    }
                };
                ajax(params);
            }
            return false;
        })
    });
</script>
<style>
    .content {
        padding: 0 !important;
    }
    .left-side {
        z-index: 1000;
    }
    .footer-main {
        position: absolute;
        width: 100%;
        z-index: 1004;
        height: 2000px;
    }
    .table {
        border-spacing: 0 !important;
    }
    .corner_cell {
        position: absolute;
        z-index: 1001;
        top: 149px;
        padding: 8px;
        background-color: #fff;
        border: 1px solid #DDD;
        width: 269px;
        height: 39px;
    }
    .bottom_corner {
        top: 633px;
        height: 15px;
        /*background-color: #999;*/
    }
    #lower_space {
        position: absolute;
        z-index: 1003;
        height: 400px;
        width: 100%;
        background-color: #fff;
        border-top: 1px solid#ccc;
    }
    #under_footer {
        background-color: #a6a9a9;
        z-index: 1005;
        position: relative;
        margin-top: 38px;
        border-top: 1px solid grey;
        height: 1000px;
    }
    .grey_stripe {
        position: absolute;
        width: 100%;
        height: 10px;
        background-color: #dfe5d7;
        top: 140px;
        z-index: 1001;
    }
    #total_wrapper {

    }

    /*.wrapper {*/
        /*position: relative;*/
        /*overflow: hidden;*/
    /*}*/
</style>