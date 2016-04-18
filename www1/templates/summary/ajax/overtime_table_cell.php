<div style="width: 50px; height: 50px;">
<?php if (!$overtime['id']): ?>
    <a data-toggle="modal" href="#overtime_suggest_modal" class="btn btn-xs suggest_overtime" type="button" style="background: none;">
        <i class="fa fa-edit"></i>
    </a>
<?php endif; ?>
<?php if ($overtime['id']): ?>
    <a data-toggle="modal" data-id="<?php echo $overtime['id']; ?>" href="#overtime_suggest_modal" class="btn btn-xs edit_suggested_overtime" type="button" style="background: none;">
        <?php echo $overtime['overtime_suggested']; ?>
    </a>
<?php endif; ?>
<?php if(!$overtime['overtime_approved'] && $allowed && $overtime['id']): ?>
    <a data-toggle="modal" data-id="<?php echo $overtime['id']; ?>" href="#overtime_approve_modal" class="btn btn-xs approve_overtime" type="button" style="background: none;">
        <i class="fa fa-edit text-info"></i>
    </a>
<?php endif; ?>
<?php if($overtime['overtime_approved']): ?>
    <a data-toggle="modal" data-value="<?php echo $overtime['overtime_approved']; ?>" data-id="<?php echo $overtime['id']; ?>" href="#overtime_approve_modal" class="btn btn-xs approve_overtime" type="button" style="background: none;">
       <span style="color: red;">
        <?php echo $overtime['overtime_approved']; ?>
    </span>
    </a>
<?php endif; ?>
</div>