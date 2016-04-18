<div class="row">
    <div class="col-md-5">
        <section class="panel">
            <header class="panel-heading">
                Uploader
            </header>
            <div class="panel-body" style="height: 300px;">
                <form action="<?php echo SITE_DIR; ?>upload/" class="dropzone" id="source">
                </form>
            </div>
        </section>
    </div>
</div>

<div class="modal fade" id="warning_modal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header modal-warning">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">
                    <i class="text-danger fa fa-warning"></i>
                    Following Users Are Not Mapped
                </h4>
            </div>
            <div class="modal-body with-padding">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody id="table_body">

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="warning_modal_1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header modal-warning">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">
                    <i class="text-danger fa fa-warning"></i>
                    Following Records have Incorrect Type
                </h4>
            </div>
            <div class="modal-body with-padding">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Type</th>
                    </tr>
                    </thead>
                    <tbody id="table_body_1">

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="warning_modal_2">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header modal-warning">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">
                    <i class="text-danger fa fa-warning"></i>
                    Following Records have Incorrect Dates
                </h4>
            </div>
            <div class="modal-body with-padding">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                    </tr>
                    </thead>
                    <tbody id="table_body_2">

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
                    <h4 class="modal-title">Map User</h4>
                </div>
                <div class="modal-body with-padding">
                    <div class="form-group">
                        <label>User Name</label>
                        <input class="form-control" name="mapping[user_name]" id="edit_name" data-require="1">
                        <div class="error-require validate-message">
                            Required Field
                        </div>
                    </div>
                    <div class="form-group">
                        <label>User Email</label>
                        <input name="mapping[user_email]" id="edit_email" class="form-control" data-require="1">
                        <div class="error-require validate-message">
                            Required Field
                        </div>
                    </div>
                    <div class="form-group">
                        <label>User Rate</label>
                        <input name="mapping[user_rate]" id="edit_rate" class="form-control">
                        <div class="error-require validate-message">
                            Required Field
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input id="edit_id" type="hidden">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    $ = jQuery.noConflict();
    $(document).ready(function () {
        Dropzone.options['source'] = {
            init: function() {
                this.on("success", function(file, msg) {
                    console.log(msg);
                    ajax_respond(msg, function(respond)
                    {
                        Notifier.success('The File was successfully uploaded', 'Success');
                    }, function(respond) {
                        if(respond.status == 3) {
                            $("#table_body").html('');
                            var j = 1;
                            for (var i in respond.result) {
                                $("#table_body").append('' +
                                '<tr id="tr_' + j + '">' +
                                '   <td>' + respond.result[i][1] + '</td>' +
                                '   <td>' + respond.result[i][0] + '</td>' +
                                '   <td>' +
                                '       <a class="btn btn-xs btn-default edit_mapping" href="#edit_modal" data-name="' + respond.result[i][0] + '" data-toggle="modal"><span class="fa fa-pencil"></span> </a>' +
                                '   </td>' +
                                '</tr>');
                                $("#warning_modal").modal('show');
                                j ++;
                            }
                        } else if(respond.status == 4) {
                            $("#table_body_1").html('');
                            for(var i in respond.result) {
                                $("#table_body_1").append('' +
                                '<tr>' +
                                '   <td>' + respond.result[i][0] + '</td>' +
                                '   <td>' + respond.result[i][1] + '</td>' +
                                '   <td>' + respond.result[i][2] + '</td>' +
                                '</tr>');
                                $("#warning_modal_1").modal('show');
                            }
                        } else if(respond.status == 5) {
                            $("#table_body_2").html('');
                            for(var i in respond.result) {
                                $("#table_body_2").append('' +
                                '<tr>' +
                                '   <td>' + respond.result[i][0] + '</td>' +
                                '   <td>' + respond.result[i][1] + '</td>' +
//                                '   <td>' + respond.result[i][2] + '</td>' +
                                '</tr>');
                                $("#warning_modal_2").modal('show');
                            }
                        } else {
                            Notifier.error(respond.error, 'Fail');
                        }

                    });
                });
            },
            uploadMultiple: false,
            addRemoveLinks: false
        };

        $("body").on('click', '.edit_mapping', function () {
            var name = $(this).attr('data-name');
            var id = $(this).closest('tr').attr('id').substr(3);
            $("#edit_name").val(name);
            $("#edit_id").val(id);
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

                                ajax_datatable('mapping_table');
                                $("#edit_modal").modal('hide');
                                var id = $("#edit_id").val();
                                $("#tr_" + id).remove();
                                $("#edit_email").val('');
                                $("#edit_rate").val('');
                                if(!$("#table_body tr").length) {
                                    Notifier.info('Please, Upload the file again to finish', 'Info');
                                    $("#warning_modal").modal('hide');
                                } else {
                                    Notifier.success('The Record has been Successfully Edited', 'Success');
                                }
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
    });
</script>
