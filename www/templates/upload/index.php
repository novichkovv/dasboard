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
                        Notifier.error(respond.error, 'Fail');
                    });
                });
            },
            uploadMultiple: false,
            addRemoveLinks: false
        };
    });
</script>
