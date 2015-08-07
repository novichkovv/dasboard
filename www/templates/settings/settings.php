<div class="row">
    <div class="col-xs-2 col-md-1">
        <div class="stat-icon" style="color:#56F5A0;">
            <i class="fa fa-gear fa-3x stat-elem"></i>
        </div>
    </div>
    <div class="col-xs-9 col-md-10">
        <h1>Settings</h1>
    </div>
</div>
<div class="row transparent">
    <section class="panel general transparent-tabs" style="background: none;">
        <header class="panel-heading tab-bg-dark-navy-blue">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a data-toggle="tab" href="#settings_tab_1">
                        <i class="fa fa-gear"></i>
                        Main
                    </a>
                </li>
            </ul>
        </header>

        <div class="text-right" style="background-color: #fff; height: 60px; box-shadow: 0 5px 1px #B4D9E5; padding: 20px;">
            <a href="">Settings</a> | Main
        </div>
        <div style="background: #B4D9E5; height: 5px;"></div>
        <div class="panel-body">
            <div class="tab-content" style="background: none;">
                <div id="settings_tab_1" class="tab-pane  active">
                    <form action="" method="post" id="settings_form_1" class="settings_form">
                        <div class="row">
                            <div class="col-md-offset-1 col-md-4">
                                <div class="form-group">
                                    <label for="language">
                                        Language
                                    </label>
                                    <select id="language" class="form-control" name="settings[1][language]">
                                        <option value="rus" <?php if (registry::get('language') == 'rus') echo 'selected'; ?>>
                                            Russian
                                        </option>
                                        <option value="eng" <?php if (registry::get('language') == 'eng') echo 'selected'; ?>>
                                            English
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-offset-1 col-md-4">
                                <input type="submit" class="btn btn-lg btn-info" value="Save">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript">
    $ = jQuery.noConflict();
    $(document).ready(function () {
        $(".settings_form").submit(function(e)
        {
            e.preventDefault();
            var id = $(this).attr('id');
            var params = {
                'action': 'save_settings',
                'get_from_form': id,
                'callback': function(msg) {
                    try {
                        var respond = JSON.parse(msg);
                    }
                    catch (e) {
                        Notifier.error('Save failed', 'Unpredicted Error!');
                        return false;
                    }
                    if (respond.status == 1) {
                        Notifier.success('The data has been saved!', 'Success');
                    } else {
                        Notifier.error('Save failed', 'Unpredicted Error!');
                    }
                }
            };
            ajax(params);
        })
    });
</script>