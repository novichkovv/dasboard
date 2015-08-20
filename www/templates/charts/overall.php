<div class="col-md-12">
    <section class="panel">
        <header class="panel-heading">
            Overall Costs
        </header>
        <div class="panel-body">
            <div id="overall" style="width: 100%; height: 400px"></div>
        </div>
    </section>
</div>
<script type="text/javascript">
    $ = jQuery.noConflict();
    $(document).ready(function () {
        $.plot($("#overall"), [ {
                data: [
                    <?php foreach($stats['data'] as $k => $v): ?>
                    [<?php echo $v; ?>,<?php echo $k; ?>]<?php if($k != count($stat['data']) - 1) echo ','; ?>
                    <?php endforeach; ?>,

                ],
                color: '#F06262',
                bars: {
                    show: true,
                    barWidth: 0.8,
                    fillColor: '#F28D8D',
                    highlightColor: '#F28D8D',
                    horizontal: true
                }
            }],
            {
                yaxis: {
                    color: 'white',
                    ticks: [
                        <?php foreach($stats['ticks'] as $k => $v): ?>
                        [<?php echo $k; ?>, '<?php echo $v; ?>']<?php if($k != count($stat['ticks']) - 1) echo ','; ?>
                        <?php endforeach; ?>
                    ]

                },
                xasis: {
                    color: 'white',
//                    max: 20

                }
            }
        );
    });
</script>