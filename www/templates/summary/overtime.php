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
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-info">
            <div class="panel panel-heading">
                <h3 class="panel-title">Overtime</h3>
            </div>
            <div class="panel-body table-scrollable" style="max-height: 500px; overflow: auto;">
                <div style="width: 10px; height: 10px; background-color: black; display: inline-block"></div> - Suggested &nbsp;&nbsp;&nbsp;&nbsp;
                <div style="width: 10px; height: 10px; background-color: red; display: inline-block"></div> - Approved
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th width="150" style="width: 120px;">Employees</th>
                        <?php foreach ($dates as $date): ?>
                            <th><?php echo date('d/M', strtotime($date)); ?></th>
                        <?php endforeach; ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <th class="user_id" data-id="<?php echo $user['id']; ?>" width="150" style="width: 120px;"><?php echo $user['user_name']; ?></th>
                            <?php foreach ($dates as $date): ?>
                                <td data-date="<?php echo $date; ?>" data-user="<?php echo $user['id']; ?>">
                                    <?php if(!$overtime[$date][$user['id']]): ?>
                                        <a data-toggle="modal" href="#overtime_suggest_modal" class="btn btn-xs suggest_overtime" type="button" style="background: none;">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    <?php endif; ?>
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
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <?php if (empty($_POST['user'])): ?>
                        <tfoot>
                        <tr>
                            <th width="150" style="width: 120px;">Employees</th>
                            <?php foreach ($dates as $date): ?>
                                <th><?php echo date('d/M', strtotime($date)); ?></th>
                            <?php endforeach; ?>
                        </tr>
                        </tfoot>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</div>
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

        $("body").on("click", ".suggest_overtime", function () {
            var date = $(this).closest('td').attr('data-date');
            var user_id = $(this).closest('tr').find('th.user_id').attr('data-id');
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