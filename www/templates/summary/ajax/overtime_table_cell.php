<?php echo $overtime['overtime_suggested']; ?>
<?php if(!$overtime['overtime_approved'] && $allowed): ?>
    <a data-toggle="modal" data-id="<?php echo $overtime['id']; ?>" href="#overtime_approve_modal" class="btn btn-xs approve_overtime" type="button" style="background: none;">
        <i class="fa fa-edit text-info"></i>
    </a>
<?php endif; ?>
<?php if($overtime['overtime_approved']): ?>
    <div style="color: red;">
        <?php echo $overtime['overtime_approved']; ?>
    </div>
<?php endif; ?>
