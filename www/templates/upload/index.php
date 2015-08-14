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
                            for (var i in respond.result) {
                                $("#table_body").append('' +
                                '<tr>' +
                                '   <td>' + respond.result[i][1] + '</td>' +
                                '   <td>' + respond.result[i][0] + '</td>' +
                                '</tr>');
                                $("#warning_modal").modal('show');
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
                        } else {
                            Notifier.error(respond.error, 'Fail');
                        }

                    });
                });
            },
            uploadMultiple: false,
            addRemoveLinks: false
        };
    });
</script>
