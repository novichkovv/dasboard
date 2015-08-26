<div class="col-md-12">
    <section class="panel">
        <header class="panel-heading">
            Utilization In Office
        </header>
        <div class="panel-body">
            <div id="utilization" style="width: 100%; height: 400px"></div>
        </div>
    </section>
</div>
<script type="text/javascript">
    $ = jQuery.noConflict();
    $(document).ready(function () {
        var plot = $.plot($("#utilization"), [ {
                data: [
                    <?php foreach($stats['data'] as $k => $v): ?>
                    [<?php echo $v; ?>,<?php echo $k; ?>]<?php if($k != count($stats['data']) - 1) echo ','; ?>
                    <?php endforeach; ?>,

                ],
                color: '#EBEB56',
                bars: {
                    show: true,
                    barWidth: 0.8,
                    fillColor: '#FFFF98',
                    highlightColor: '#FFFF98',
                    horizontal: true
                }
            }],
            {
                yaxis: {
                    color: 'white',
                    ticks: [
                        <?php foreach($stats['ticks'] as $k => $v): ?>
                        [<?php echo $k; ?>, '<?php echo $v; ?>']<?php if($k != count($stats['ticks']) - 1) echo ','; ?>
                        <?php endforeach; ?>
                    ]

                },
                xasis: {
                    color: 'white',
                    max: 200,
                    min: 200
                }
        });

        var opts = plot.getOptions();
        opts.xaxes[0].min = 0;
        opts.xaxes[0].max = 200;
        opts.xaxes[0].minTickSize = 25;
        opts.xaxes[0].ticks = [[0, 0], [25, '25%'],[50,'50%'],[75,'75%'],[100,'100%'],[125,'125%'],[150,'150%'],[175,'175%'],[200,'200%']];
        plot.setupGrid();
        plot.draw();

        var xaxisLabel = $("<div class='axisLabel xaxisLabel'></div>")
            .text("%")
            .appendTo($("#utilization"));
        xaxisLabel.css("top", $("#utilization").height() - 40);
    });
</script>