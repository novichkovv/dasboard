<div class="col-md-12">
    <section class="panel">
        <header class="panel-heading">
            Week Performance
        </header>
        <div class="panel-body">
            <div id="week" style="width:100% ;height:400px; background-color: rgba(115, 193, 255, 0.11)"></div>
        </div>
    </section>
</div>
<script type="text/javascript">
    $ = jQuery.noConflict();
    $(document).ready(function () {
        $.plot($("#week"), [ {
                data: [
                    <?php foreach($stats['data'] as $k => $v): ?>
                    [<?php echo $v; ?>,<?php echo $k; ?>]<?php if($k != count($stats['data']) - 1) echo ','; ?>
                    <?php endforeach; ?>,

                ],
                color: '#65C651',
                bars: {
                    show: true,
                    barWidth: 0.8,
                    fillColor: '#9CD690',
                    highlightColor: '#9CD690',
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
//                    max: 20

                }
            });
    });
</script>