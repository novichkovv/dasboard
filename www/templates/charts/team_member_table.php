<div class="col-md-12">
    <section class="panel">
        <header class="panel-heading">
            Team Member Table
        </header>
        <div class="panel-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <td>Team Member</td>
                    <?php foreach($dates as $date): ?>
                        <td><?php echo date('d/m', strtotime($date)); ?></td>
                    <?php endforeach; ?>
                </tr>
                </thead>
                <tbody>
                <?php foreach($stats as $username => $stat): ?>
                    <tr>
                        <td>
                            <?php echo $username; ?>
                            <?php foreach($stat as $time): ?>
                                <td>
                                    <?php echo $time; ?>
                                </td>
                            <?php endforeach; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
<script type="text/javascript">
    $ = jQuery.noConflict();
    $(document).ready(function () {

    });
</script>