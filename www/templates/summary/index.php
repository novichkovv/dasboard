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
    <div class="panel-body" style="max-height: 500px; overflow: auto;">
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
                        <?php $deductions = 0; ?>
                        <?php $over = 0; ?>
                        <?php $l = $late[$date][$user['id']]['value'] ? $late[$date][$user['id']]['value'] : 0; ?>
                        <?php $e = $early[$date][$user['id']]['value'] ? $early[$date][$user['id']]['value'] : 0; ?>
                        <?php $w = $less_worked[$date][$user['id']]['value'] ? $less_worked[$date][$user['id']]['value'] : 0; ?>
                        <?php $o = $overtime[$date][$user['id']]['value'] ? $overtime[$date][$user['id']]['value'] : 0; ?>
                        <td data-date="<?php echo $date; ?>" data-user="<?php echo $user['id']; ?>">
                            <div
                                <?php if ($absent['present'][$date][$user['id']]['work_end'] && $absent['present'][$date][$user['id']]['work_begin']): ?>
                                data-trigger="hover" data-toggle="popover" data-placement="bottom"
                                    data-content="<?php echo date('H:i', strtotime($absent['present'][$date][$user['id']]['work_begin'])); ?> -
                                    <?php echo date('H:i', strtotime($absent['present'][$date][$user['id']]['work_end'])); ?><br>
<!--                                    --><?php //echo $l; ?><!-- - late <br>-->
<!--                                    --><?php //echo $e; ?><!-- - early<br>-->
<!--                                    --><?php //echo $w; ?><!-- - less<br>-->
<!--                                    --><?php //echo $o; ?><!-- - overtime"
                                <?php endif; ?>>
                            <?php if ($absent['absent'][$date][$user['id']]): ?>
                                <span style="color: red;">Absent</span><br>
                            <?php endif; ?>
                            <?php if ($absent['present'][$date][$user['id']]): ?>
                            <?php endif; ?>
                            <?php $deductions = $l + $e + $w; ?>
                            <?php if ($o): ?>
                                <?php if ($o > $deductions): ?>
                                    <?php $over = $o - $deductions; ?>
                                    <?php $deductions = 0; ?>
                                <?php endif; ?>
                                <?php if ($o <= $deductions): ?>
                                    <?php $over = 0; ?>
                                    <?php $deductions = $deductions - $o; ?>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if ($deductions): ?>
                                <span style="color: red;"><?php echo round($deductions,2); ?>h</span>
                            <?php endif; ?>
                            <?php if ($over): ?>
                                <span style="color: green;"><?php echo round($over,2); ?>h</span>
                            <?php endif; ?>
                            </div>
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
<script type="text/javascript">
    $ = jQuery.noConflict();
    $(document).ready(function () {
        $('[data-toggle="popover"]').popover({trigger: 'hover','placement': 'top', 'html': true});
        $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd'
        });
    });
</script>