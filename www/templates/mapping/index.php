<div class="row">
    <div class="col-xs-2 col-md-1">
        <div class="stat-icon" style="color:#4BAAB7;">
            <i class="fa fa-exchange fa-3x stat-elem"></i>
        </div>
    </div>
    <div class="col-xs-9 col-md-10">
        <h1>User Mapping</h1>
    </div>
</div>
<br>
<form class="form-horizontal" id="mapping_form">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label col-md-2">Add Mapping</label>
                <div class="col-md-3">
                    <input type="text" name="mapping[user_name]" placeholder="User Name" class="form-control" data-require="1">
                    <div class="error-require validate-message">
                        Required Field
                    </div>
                </div>
                <div class="col-md-3">
                    <input type="text" name="mapping[user_id]" placeholder="User Id" class="form-control" data-require="1">
                    <div class="error-require validate-message">
                        Required Field
                    </div>
                </div>
                <div class="col-md-3">
                    <input type="submit" class="btn btn-primary" value="Add">
                </div>
            </div>
        </div>
    </div>
</form>
<div class="row">
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-body with-padding">
                <div class="custom-datatable">
                    <table class="table table-bordered" id="mapping_table">
                        <thead>
                        <tr>
                            <th>
                                <input type="text" name="user_email" data-sign="like" placeholder="search" class="form-control filter-field">
                            </th>
                            <th>
                                <input type="text" name="user_name" data-sign="like" placeholder="search" class="form-control filter-field">
                            </th>
                            <th>
<!--                                <input type="text" name="user_id" data-sign="like" placeholder="search" class="form-control filter-field">-->
                            </th>
                            <th></th>
                        </tr>
                        <tr>
                            <th>
                                User Email
                            </th>
                            <th>
                                User Name
                            </th>
                            <th>
                                User Rate
                            </th>
                            <th>
                                Actions
                            </th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="edit_modal">
    <div class="modal-dialog modal-sm">
        <form id="edit_mapping_form">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Edit Data</h4>
                </div>
                <div class="modal-body with-padding">
                    <div class="form-group">
                        <label>User Email</label>
                        <input name="mapping[user_email]" id="edit_name" class="form-control" data-require="1">
                        <div class="error-require validate-message">
                            Required Field
                        </div>
                    </div>
                    <div class="form-group">
                        <label>User Name</label>
                        <select name="mapping[user_name]" class="form-control">
                            <?php foreach ($names as $name): ?>
                                <option value="<?php echo $name['username']; ?>">
                                    <?php echo $name['username']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
<!--                        <input id="edit_name" class="form-control" data-require="1">-->
                        <div class="error-require validate-message">
                            Required Field
                        </div>
                    </div>
                    <div class="form-group">
                        <label>User Rate</label>
                        <input name="mapping[user_rate]" id="edit_name" class="form-control">
                        <div class="error-require validate-message">
                            Required Field
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input name="mapping[id]" id="edit_id" type="hidden">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="delete_modal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Delete Record</h4>
            </div>
            <div class="modal-body with-padding">
                <p>Are You Sure?</p>
            </div>
            <div class="modal-footer">
                <button type="button" id="delete_btn" class="btn btn-primary">Yes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $ = jQuery.noConflict();
    $(document).ready(function () {
        ajax_datatable('mapping_table');
        $("#mapping_form").submit(function()
        {
            if(validate('mapping_form')) {
                var params = {
                    action: 'save_mapping',
                    get_from_form: 'mapping_form',
                    callback: function(msg) {
                        ajax_respond(msg,
                            function(respond) {
                                Notifier.success('A Record has been Successfully Added', 'Success');
                                ajax_datatable('mapping_table');
                                document.getElementById('mapping_form').reset();
                            },
                            function(respond) {
                                Notifier.warning(respond.error, 'Fail');
                            }
                        )
                    }
                };
                ajax(params);
            }
            return false;
        });

        $("#edit_mapping_form").submit(function()
        {
            if(validate('edit_mapping_form')) {
                var params = {
                    action: 'save_mapping',
                    get_from_form: 'edit_mapping_form',
                    callback: function(msg) {
                        ajax_respond(msg,
                            function(respond) {
                                Notifier.success('The Record has been Successfully Edited', 'Success');
                                ajax_datatable('mapping_table');
                                $("#edit_modal").modal('hide');
                            },
                            function(respond) {
                                Notifier.warning(respond.error, 'Fail');
                            }
                        )
                    }
                };
                ajax(params);
            }
            return false;
        });

        $('body').on('click', '.edit_mapping', function()
        {
            var id = $(this).attr('data-id');
            var params = {
                action: 'get_mapping',
                values: {id: id},
                callback: function(msg) {
                    ajax_respond(
                        msg,
                        function(respond) {
                            for(var i in respond.result) {
                                $('#edit_mapping_form [name="mapping[' + i + ']"]').val(respond.result[i]);
                            }
                        },
                        function(respond) {
                            Notifier.warning(respond.error, 'Fail');
                        }
                    );
                }
            };
            ajax(params);
        });

        $('body').on('click', '.delete_mapping', function() {
            var id = $(this).attr('data-id');
            $("#delete_btn").val(id);
        });

        $("#delete_btn").click(function()
        {
            var id = $(this).val();
            var params = {
                action: 'delete_mapping',
                values: {id: id},
                callback: function(msg) {
                    ajax_respond(
                        msg,
                        function(respond) {
                            Notifier.success('The Record has been Successfully Deleted', 'Success');
                            ajax_datatable('mapping_table');
                            $("#delete_modal").modal('hide');
                        },
                        function(respond) {
                            Notifier.warning(respond.error, 'Fail');
                        }
                    );
                }
            };
            ajax(params);
        })
    });
</script>
