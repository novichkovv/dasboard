<?php if ($tasks): ?>
    <?php foreach ($tasks['data'] as $task): ?>
        <tr data-id="<?php echo $task['id']; ?>">
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
                <span class="worked_time"><?php echo $task['worked_time']; ?></span>&nbsp;&nbsp;&nbsp;
<!--                <button  data-id="--><?php //echo $task['id']; ?><!--" class="btn btn-default btn-sm edit_task" data-toggle="modal" data-target="#edit_task_modal"><i class="fa fa-edit"> Edit</i> </button>-->
            </td>
            <td>
                <button type="button" data-id="<?php echo $task['id']; ?>" class="btn btn-success btn-sm start_btn"> Start </button>&nbsp;&nbsp;&nbsp;
                <span class="hours">00</span>:<span class="minutes">00</span>:<span class="seconds">00</span>
            </td>
            <td>
                <button class="btn btn-icon btn-default pop" data-container="body" data-toggle="popover" data-placement="left" data-content="No connection to server, tracked time is being cached.">!</button>
            </td>
        </tr>
    <?php endforeach; ?>
<?php endif; ?>
<?php if (!$tasks['data']): ?>
    <tr>
        <td colspan="20">
            <h1 class="text-center">No Tasks Assigned to You</h1>
        </td>
    </tr>
<?php endif; ?>