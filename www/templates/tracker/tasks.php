<h2 style="display: inline; vertical-align: middle;"><?php echo $workspace_name; ?></h2> <button type="button" class="btn btn-sm btn-default"><i class="fa fa-refresh"></i> Refresh</button>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel panel-heading">
                <h3 class="panel-title"></h3>
            </div>
            <div class="panel-body">
                <table class="table" id="table">
                    <thead>
                    <tr>
                        <th>Project</th>
                        <th>Tasks (assigned to you)</th>
                        <th>Subtasks</th>
                        <th>Worked Time</th>
                        <th>Timer</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($tasks): ?>
                        <?php foreach ($tasks['data'] as $task): ?>
                            <tr>
                                <td><?php echo $task['projects'][0]['name']; ?></td>
                                <td><?php echo $task['name']; ?></td>
                                <td>
                                    <?php if ($task['subtasks']): ?>
                                        <ul>
                                            <?php foreach ($task['subtasks'] as $subtask): ?>
                                                <li>
                                                    <?php echo $subtask['name']; ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="worked_time"><?php echo $task['worked_time']; ?></span>
                                    <button class="btn btn-default" data-toggle="modal" data-target="#edit_task_modal"><i class="fa fa-edit"> Edit</i> </button>
                                </td>
                                <td>
                                    <button type="button" data-id="<?php echo $task['id']; ?>" class="btn btn-success start_btn"> Start </button>
                                    <span class="hours">00</span>:<span class="minutes">00</span>:<span class="seconds">00</span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $ = jQuery.noConflict();
    $(document).ready(function () {
        var interval;
        var $body = $("body");
        $body.on("click", ".start_btn", function () {
            var first = true;
            var $btn = $(this);
            var id = $btn.attr('data-id');
            $btn.removeClass('start_btn');
            $(".start_btn").prop('disabled', true);
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
                            if(null === get_cookie('asana_cached_time')) {
                                cached_time = 1;
                            } else {
                                cached_time = parseInt(get_cookie('asana_cached_time')) + 1;
                            }
                            setcookie('asana_cached_time', cached_time, 3600*24*90);
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
            $(".start_btn").prop('disabled', false);
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
    });
</script>