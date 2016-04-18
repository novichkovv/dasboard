<div class="row">
    <div class="col-md-6">
        <div class="panel panel-success">
            <div class="panel panel-heading">
                <h3 class="panel-title">Settings</h3>
            </div>
            <div class="panel-body">
                <form method="post" id="settings_form">
                    <div class="form-group">
                        <label>Work Day Length *</label>
                        <input class="form-control" data-require="1" data-validate="numeric" type="text" name="config[day_length]" value="<?php echo $config['day_length']; ?>">
                        <div class="validate-message error-require">
                            Required field
                        </div>
                        <div class="validate-message error-validate">
                            Must be a Number
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Cut off times *</label>
                        <div class="row">
                            <div class="col-md-4">
                                Clock-in between
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" name="config[clock_in_start]">
                                    <?php for($i = 0; $i < 24; $i ++): ?>
                                        <option value="<?php echo $i >= 10 ? $i : '0' . $i; ?>" <?php if($config['clock_in_start'] == $i) echo 'selected'; ?>>
                                            <?php echo $i >= 10 ? $i : '0' . $i; ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-md-1">
                                and
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" name="config[clock_in_end]">
                                    <?php for($i = 0; $i < 24; $i ++): ?>
                                        <option value="<?php echo $i >= 10 ? $i : '0' . $i; ?>" <?php if($config['clock_in_end'] == $i) echo 'selected'; ?>>
                                            <?php echo $i >= 10 ? $i : '0' . $i; ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-4">
                                Clock-out between
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" name="config[clock_out_start]">
                                    <?php for($i = 0; $i < 24; $i ++): ?>
                                        <option value="<?php echo $i >= 10 ? $i : '0' . $i; ?>" <?php if($config['clock_out_start'] == $i) echo 'selected'; ?>>
                                            <?php echo $i >= 10 ? $i : '0' . $i; ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-md-1">
                                and
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" name="config[clock_out_end]">
                                    <?php for($i = 0; $i < 24; $i ++): ?>
                                        <option value="<?php echo $i >= 10 ? $i : '0' . $i; ?>" <?php if($config['clock_out_end'] == $i) echo 'selected'; ?>>
                                            <?php echo $i >= 10 ? $i : '0' . $i; ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="form-group">
                            <button class="btn btn-info btn-lg" type="submit">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $ = jQuery.noConflict();
    $(document).ready(function () {
        $("#settings_form").submit(function() {
            if(validate('settings_form')) {
                var params = {
                    'action': 'save_settings',
                    'get_from_form': 'settings_form',
                    'callback': function (msg) {
                        ajax_respond(msg, function(respond) {
                            Notifier.success('Settings have been saved', 'Success');
                        })
                    }
                };
                ajax(params);
            }
            return false;
        });
    });
</script>