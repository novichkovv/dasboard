<div class="col-md-12">
    <section class="panel">
        <header class="panel-heading">
            Team Member Hours
        </header>
        <div class="panel-body">
            <div id="team_member_hours" style="width:100% ;height:400px"></div>
        </div>
    </section>
</div>
<script type="text/javascript">
    $ = jQuery.noConflict();
    $(document).ready(function () {
        var ticks = ['a','b','c','d'];
        $.plot($("#team_member_hours"), [ {
                data: [
                    <?php foreach($stats['data'] as $k => $v): ?>
                    [<?php echo $v; ?>,<?php echo $k; ?>]<?php if($k != count($stats['data']) - 1) echo ','; ?>
                    <?php endforeach; ?>
                ],
                bars: {
                    show: true,
                    barWidth: 0.8,
                    fillColor: '#CCE8E2',
                    highlightColor: '#CCE8E2',
                    horizontal: true
                }
            }],
            {
                yaxis: {
                    ticks: [
                    <?php foreach($stats['ticks'] as $k => $v): ?>
                    [<?php echo $k; ?>, '<?php echo $v; ?>']<?php if($k != count($stats['ticks']) - 1) echo ','; ?>
                    <?php endforeach; ?>
            ]

                },
                xasis: {
//                    max: 20

                }
            });
    });
</script>