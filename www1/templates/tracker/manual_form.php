<div id="manual_form_<?php echo $week_offset; ?>">
    <div class="pull-right">
        <input type="hidden" class="week_offset" name="week_offset" value="<?php echo isset($week_offset) ? $week_offset : 0; ?>">
        <button type="button" class="btn btn-default previous_week"><i class="fa fa-chevron-left"></i></button>
        &nbsp;&nbsp;Week&nbsp;&nbsp;
        <button type="button" class="btn btn-default next_week"><i class="fa fa-chevron-right"></i></button>
    </div>
    <table class="table">
        <thead>
        <tr>
            <td></td>
            <?php foreach ($tasks['daily'][0] as $date => $task): ?>
                <td
                    <?php if (date('w', strtotime($date)) == 6 || date('w', strtotime($date)) == 0): ?>
                        class="text-warning"
                    <?php endif; ?>><?php echo date('D<b\r>d M', strtotime($date)); ?></td>
            <?php endforeach; ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($tasks['daily'] as $k => $task): ?>
            <tr>
                <td><?php echo $tasks['data'][$k]['name']; ?></td>
                <?php foreach ($task as $date => $v): ?>
                    <td>
                        <input data-validate="time" type="text" class="form-control time-mask" value="<?php echo $v['work_duration']; ?>" name="manual[<?php echo $tasks['data'][$k]['id']; ?>][<?php echo $date; ?>][new]">
                        <div class="error-validate validate-message">Time must have format HH:MM</div>
                        <input type="hidden" value="<?php echo $v['work_duration']; ?>" name="manual[<?php echo $tasks['data'][$k]['id']; ?>][<?php echo $date; ?>][current]">
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <input class="btn btn-success" type="submit" value="Save">
</div>