<form id="edit_task_form">
    <h2>Add new worked time entry:</h2>
    <div class="form-group">
        <div class="col-md-3">
            <label class="control-label">
                Duration (hours):
            </label>
            <input type="text" id="new_time" class="form-control" name="new_time" value="00:00">
        </div>
        <div class="col-md-4 col-md-offset-5">
            <input type="submit" class="btn btn-success" value="Save">
            <input type="button" data-dismiss="modal" class="btn btn-default" value="Cancel">
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="form-group">
        <div class="col-md-3">
            <label class="control-label">
                Start:
            </label>
            <input type="text" id="new_time_start" class="form-control" name="new_time_start" value="<?php echo date('H:i'); ?>">
        </div>
        <div class="col-md-3">
            <label class="control-label">
                End:
            </label>
            <input type="text" id="new_time_end" class="form-control" name="new_time_end" value="<?php echo date('H:i'); ?>">
        </div>
    </div>
    <div class="clearfix"></div>
    <br>
    <div class="form-group">
        <div class="col-md-12">
            <input type="hidden" id="new_time_date" name="new_time_date" value="<?php echo date('Y-m-d'); ?>">
            <div class="seiko-datepicker"></div>
        </div>
    </div>
    <input type="hidden" name="tid" value="<?php echo $tid; ?>">
</form>
<br>
<hr>
<h2>Edit existing worked time entries:</h2>
<?php if ($tasks): ?>
    <?php foreach ($tasks as $k => $task): ?>
        <div class="row">
            <form method="post" class="edit_existing_task_form" id="<?php echo $k; ?>">
                <div class="col-md-3">
                    Duration (hours):
                </div>
                <div class="col-md-3">
                    <input class="form-control" name="time" value="<?php echo $task['time']; ?>">
                </div>
                <div class="col-md-3">
                    <?php echo date('L, F d, H:i Y', strtotime($task['work_begin'])); ?>
                    <input type="hidden" name="work_begin" value="<?php echo $task['work_begin']; ?>">
                    <input type="hidden" name="tid" value="<?php echo $task['tid']; ?>">
                </div>
                <div class="col-md-3">
                    <button class="btn btn-info" type="submit">Save</button>
                    <button type="button" class="btn btn-danger delete_task" data-work_begin="<?php echo $task['work_begin']; ?>" data-tid="<?php echo $task['tid']; ?>">Delete</button>
                </div>
            </form>
        </div>
        <br>
    <?php endforeach; ?>
<?php endif; ?>
<script type="text/javascript">
    $ = jQuery.noConflict();
    $(document).ready(function () {
        $(".seiko-datepicker").datepicker({
            dateFormat: 'yy-mm-dd',
            onSelect: function (dateText) {
                var jthis = $(this);
                $("#new_time_date").val(dateText); //work end date is calculated
            }
        });
//        $(".seiko-datepicker").each(function () {
//            var jthis = $(this);
//            var workbegindate = jthis.parent().find("#work_begin_date");
//            var initial_date = workbegindate.val();
//            jthis.datepicker({
//                dateFormat: 'yy-mm-dd',
//                onSelect: function (dateText) {
//                    var jthis = $(this);
//                    workbegindate.val(dateText); //work end date is calculated
//                }
//            });
//            jthis.datepicker("setDate", $.datepicker.parseDate("yy-mm-dd", initial_date));
//        });
    });
</script>