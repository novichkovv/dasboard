<form method="post" action="" style="position: absolute; z-index: 1000; height: 100px; background-color: #fff; width: 100%; padding: 10px;">
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
<div class="table-scrollable" style="max-height: 500px; overflow: auto; margin-top: 100px;">
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
                        <?php $deductions = 0; ?>
                        <?php $over = 0; ?>
                        <?php $l = $late[$date][$user['id']]['value'] ? $late[$date][$user['id']]['value'] : 0; ?>
                        <?php $e = $early[$date][$user['id']]['value'] ? $early[$date][$user['id']]['value'] : 0; ?>
                        <?php $w = $less_worked[$date][$user['id']]['value'] ? $less_worked[$date][$user['id']]['value'] : 0; ?>
                        <?php $o = $overtime[$date][$user['id']]['value'] ? $overtime[$date][$user['id']]['value'] : 0; ?>
                        <div
                            <?php if ($absent['present'][$date][$user['id']]['work_end'] && $absent['present'][$date][$user['id']]['work_begin']): ?>
                            data-trigger="hover" data-toggle="popover" data-placement="bottom"
                            data-content="<?php echo date('H:i', strtotime($absent['present'][$date][$user['id']]['work_begin'])); ?> -
                                    <?php echo date('H:i', strtotime($absent['present'][$date][$user['id']]['work_end'])); ?><br>"
                                <?php endif; ?>style="width: 50px; height: 50px;">
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
    </table>
</div>
<div id="lower_space" style="position: absolute; height: 400px; background-color: #fff;"></div>
<?php /*
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
<!--
                                <?php endif; ?>  style="width: 50px; height: 50px;">
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
 */ ?>
<script type="text/javascript">
    $ = jQuery.noConflict();
    $(document).ready(function () {
        $('[data-toggle="popover"]').popover({trigger: 'hover','placement': 'top', 'html': true});
        $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd'
        });
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
        top: 150px;
        padding: 8px;
        background-color: #fff;
        border: 1px solid #DDD;
        width: 269px;
    }
    .bottom_corner {
        top: 632px;
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
        top: 141px;
        z-index: 1001;
    }
</style>