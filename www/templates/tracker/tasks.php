<h2 style="display: inline; vertical-align: middle;"><?php echo $workspace_name; ?></h2>
<button type="button" class="btn btn-sm btn-default" id="update_workspace"><i class="fa fa-refresh"></i> Refresh</button>
<button type="button" class="btn btn-sm btn-default" id="change_workspace"><i class="fa fa-exchange"></i> Change Workspace</button>
<br><br>
<ul class="nav nav-tabs">
    <li class="active"><a href="#tracker" data-toggle="tab"><i class="fa fa-clock-o"></i> Tracker</a></li>
    <li><a href="#manual" data-toggle="tab"><i class="fa fa-edit"></i> Manual Entry</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
    <div class="tab-pane active" id="tracker">
        <div class="row">
            <div class="col-md-12">
                <div style="background-color: #fff; padding: 30px;">
                    <table class="table" id="table">
                        <thead>
                        <tr>
                            <th>Project</th>
                            <th>Tasks (assigned to you)</th>
                            <th>Subtasks</th>
                            <th>Worked Time</th>
                            <th>Timer</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody id="tasks_table_body">
                        <?php require_once(TEMPLATE_DIR . 'tracker' . DS . 'tasks_table_body.php'); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane" id="manual">
        <div style="background-color: #fff; padding: 30px;">
            <form method="post" id="manual_time_form">
                <div style="overflow-x: auto" id="manual_form_container">
                    <?php require_once(TEMPLATE_DIR . 'tracker' . DS . 'manual_form.php'); ?>
                </div>
                <div class="hidden" id="forms_container"></div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $ = jQuery.noConflict();
    $(document).ready(function () {
        $(".pop").popover();
        var interval;
        var $body = $("body");

        $body.on("submit", "#manual_time_form", function () {
            if(validate('manual_time_form')) {
                var params = {
                    'action': 'save_manual_time',
                    'get_from_form': 'manual_time_form',
                    'callback': function (msg) {
                        ajax_respond(msg,
                            function (respond) { //success
                                for(var tid in respond.worked_time) {
                                    $("tr[data-id='" + tid + "']").find('.worked_time').html(respond.worked_time[tid]);
                                }
                                Notifier.success('Time has been saved');
                            },
                            function (respond) { //fail
                                Notifier.error('Something went wrong');
                            }
                        );
                    }
                };
                ajax(params);
            }
            return false;
        });

        $body.on("click", ".previous_week", function () {
            var number = parseInt($(this).parent().find(".week_offset").val());
            var offset = number - 1;
            var $current_form = $("#manual_form_" + number);
            var $new_form = $("#manual_form_" + offset);
            var $container = $("#manual_form_container");
            $current_form.hide();
            if($new_form.length) {
                $new_form.show();
            } else {
                var params = {
                    'action': 'get_manual_form',
                    'values': {week_offset: offset},
                    'callback': function (msg) {
                        ajax_respond(msg,
                            function (respond) { //success
                                $container.append(respond.template);
                            },
                            function (respond) { //fail
                            }
                        );
                    }
                };
                ajax(params);
            }
        });

        $body.on("click", ".next_week", function () {
            var number = parseInt($(this).parent().find(".week_offset").val());
            var offset = number + 1;
            var $current_form = $("#manual_form_" + number);
            var $new_form = $("#manual_form_" + offset);
            var $container = $("#manual_form_container");
            $current_form.hide();
            if($new_form.length) {
                $new_form.show();
            } else {
                var params = {
                    'action': 'get_manual_form',
                    'values': {week_offset: offset},
                    'callback': function (msg) {
                        ajax_respond(msg,
                            function (respond) { //success
                                $container.append(respond.template);
                            },
                            function (respond) { //fail
                            }
                        );
                    }
                };
                ajax(params);
            }
        });

        $(".time-mask").mask('99:99');

        $body.on("click", ".start_btn", function () {
            $("a[href='#manual']").prop('disabled', true);
            var first = true;
            var $btn = $(this);
            var id = $btn.attr('data-id');
            $btn.removeClass('start_btn');
            $(".start_btn").prop('disabled', true);
            $(".edit_task").prop('disabled', true);
            $btn.removeClass('btn-success');
            $btn.addClass('btn-danger');
            $btn.addClass('stop_btn');
            $btn.html('Stop');
            var $td = $(this).closest('td');
            var $tr = $td.parent('tr');
            interval = setInterval(function() {
                var $seconds = $td.find('.seconds');
                var sec = parseInt($seconds.html());
                var $minutes = $td.find('.minutes');
                var min = parseInt($minutes.html());
                var $hours = $td.find('.hours');
                var hr = parseInt($hours.html());
                if(sec%<?php echo TRACKING_FREQUENCY; ?> === 0) {
                    var params = {
                        'action': 'register_time',
                        'values': {time: hr + ':' + 'min' + ':', id: id},
                        'callback': function (msg) {
                            ajax_respond(msg,
                                function (respond) { //success
                                    $tr.find('.worked_time').html(respond.time);
                                    $tr.find('.pop').hide();
                                    if(respond.work_begin !== undefined) {
                                        $btn.attr('data-work_begin', respond.work_begin);
                                    }
                                },
                                function (respond) { //fail

                                }
                            );
                        },
                        error: function() {
                            var cached_time;
                            if(null === get_cookie('asana_cached_time_' + id)) {
                                cached_time = 1;
                            } else {
                                cached_time = parseInt(get_cookie('asana_cached_time_' + id)) + 1;
                            }
                            setcookie('asana_cached_time_' + id, cached_time, 3600*24*90, '/', '.<?php echo str_replace('http://', '', SITE_DIR); ?>');
                            $tr.find('.pop').show();
                        }
                    };
                    if(first) {
                        params.values.first = true;
                        first = false;
                    } else {
                        params.values.work_begin = $btn.attr('data-work_begin');
                    }
                    ajax(params);
                }
                sec = sec == 59 ? 0 : sec + 1;
                sec = sec < 10 ? "0" + sec : sec;
                $seconds.html(sec);
                if(sec == "00") {
                    min = min == 59 ? 0 : min + 1;
                    min = min < 10 ? "0" + min : min;
                    $minutes.html(min);
                    if(min == "00") {
                        hr = hr < 10 ? "0" + hr : hr;
                        $hours.html(hr);
                    }
                }
            }, 1000);
            $btn.attr('data-interval', interval);
        });

        $body.on("click", ".stop_btn", function () {
            $("a[href='#manual']").prop('disabled', false);
            $(".start_btn").prop('disabled', false);
            $(".edit_task").prop('disabled', false);
            var $btn = $(this);
            interval = $btn.attr('data-interval');
            clearInterval(interval);
            $btn.addClass('start_btn');
            $btn.addClass('btn-success');
            $btn.removeClass('btn-danger');
            $btn.removeClass('stop_btn');
            $btn.html('Start');
            $btn.removeAttr('data-work_begin');
        });

        $body.on("click", ".edit_task", function () {
            var id = $(this).attr('data-id');
            var params = {
                'action': 'get_edit_task_form',
                'values': {tid: id},
                'callback': function (msg) {
                    ajax_respond(msg,
                        function (respond) { //success
                            $("#edit_task_form_container").html(respond.template);
                        },
                        function (respond) { //fail
                            Notifier.error('Something went wrong!');
                        }
                    );
                }
            };
            ajax(params);
        });

        $body.on("submit", ".edit_existing_task_form", function (e) {
            e.preventDefault();
            var $form = $(this);
            var form_id = $form.attr('id');
            if(validate(form_id)) {
                var params = {
                    'action': 'edit_task',
                    'get_from_form': form_id,
                    'callback': function (msg) {
                        ajax_respond(msg,
                            function (respond) { //success
                                $("tr[data-id='" + respond.tid + "']").find('.worked_time').html(respond.worked_time);
                                Notifier.success('The time has been changed');
                            },
                            function (respond) { //fail
                                Notifier.error('The time has not been changed', 'ERROR!');
                            }
                        );
                    }
                };
                ajax(params);
            }
            return false;
        });

        $body.on("submit", "#edit_task_form", function () {
            if(validate('edit_task_form')) {
                var params = {
                    'action': 'add_new_task',
                    'get_from_form': 'edit_task_form',
                    'callback': function (msg) {
                        ajax_respond(msg,
                            function (respond) { //success
                                $("tr[data-id='" + respond.tid + "']").find('.worked_time').html(respond.worked_time);
                                Notifier.success('The time has been added');
                                $("#edit_task_modal").modal('hide');
                            },
                            function (respond) { //fail
                                Notifier.error('The time has not been added', 'ERROR!');
                            }
                        );
                    }
                };
                ajax(params);
            }
            return false;
        });

        $("body").on("click", ".delete_task", function () {
            var tid = $(this).attr('data-tid');
            var work_begin = $(this).attr('data-work_begin');
            var $button = $(this);
            var params = {
                'action': 'delete_task',
                'values': {tid: tid, work_begin: work_begin},
                'callback': function (msg) {
                    $button.closest('.row').remove();
                    ajax_respond(msg,
                        function (respond) { //success
                            $("tr[data-id='" + respond.tid + "']").find('.worked_time').html(respond.worked_time);
                            Notifier.success('The time has been deleted');
                        },
                        function (respond) { //fail
                            Notifier.error('The time has not been deleted', 'ERROR!');
                        }
                    );
                }
            };
            ajax(params);
        });

//        $("body").on("change", "#new_time", function () {
//            alert(1);
//            var time = $(this).val();
//            var start = $("#new_time_start").val();
//            var date = $("#new_time_date").val();
//            var start_arr = start.split(":");
//            var date_arr = date.split('-');
//            var d = new Date(parseInt(date_arr[0]), parseInt(date_arr[1]) - 1, parseInt(date_arr[2]), parseInt(start_arr[0]), parseInt(start_arr[1]));
//            console.log(d);
//        });
    });
</script>