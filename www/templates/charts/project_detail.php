<div class="col-md-12">
    <section class="panel">
        <header class="panel-heading">
            Project Detail
        </header>
        <div class="panel-body">
            <div class="col-md-12">
                <section class="panel general">
                    <header class="panel-heading tab-bg-dark-navy-blue">
                        <ul class="nav nav-tabs">
                            <?php $i = 0; ?>
                            <?php foreach($stats as $project => $v): ?>
                                <li<?php if($i == 0) echo ' class="active"'; ?>>
                                    <a data-toggle="tab" href="#task_tab_<?php echo $i; ?>">
                                        <?php echo $project; ?>
                                    </a>
                                </li>
                                <?php $i ++; ?>
                            <?php endforeach; ?>
                        </ul>
                    </header>
                    <div class="panel-body">
                        <div class="tab-content">
                            <?php $i = 0; ?>
                            <?php foreach($stats as $project => $v): ?>
                                <div class="tab-pane<?php if($i == 0) echo ' active'; ?>" id="task_tab_<?php echo $i; ?>">
                                    <div id="task_<?php echo $i; ?>" style="width: 100%; height: 400px"></div>
                                </div>
                                <?php $i ++; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript">
    $ = jQuery.noConflict();
    $(document).ready(function () {
        <?php $stat = $stats[array_keys($stats)[0]]; ?>
        $.plot($("#task_0"), [ {
                data: [
                    <?php foreach($stat['data'] as $k => $v): ?>
                    [<?php echo $v; ?>,<?php echo $k; ?>]<?php if($k != count($stat['data']) - 1) echo ','; ?>
                    <?php endforeach; ?>,

                ],
                color: '#866AA7',
                bars: {
                    show: true,
                    barWidth: 0.8,
                    fillColor: '#8E79A8',
                    highlightColor: '#8E79A8D',
                    horizontal: true
                }
            }],
            {
                yaxis: {
                    color: 'white',
                    ticks: [
                        <?php foreach($stat['ticks'] as $k => $v): ?>
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
        <?php $i = 1; ?>
        <?php foreach($stats as $k => $stat): ?>
        <?php if(array_keys($stats)[0] == $k) continue; ?>
        $("[href='#task_tab_<?php echo $i; ?>']").click(function()
        {
            setTimeout(function()
            {
                $.plot($("#task_<?php echo $i; ?>"), [ {
                        data: [
                            <?php foreach($stat['data'] as $k => $v): ?>
                            [<?php echo $v; ?>,<?php echo $k; ?>]<?php if($k != count($stat['data']) - 1) echo ','; ?>
                            <?php endforeach; ?>,

                        ],
                        color: '#866AA7',
                        bars: {
                            show: true,
                            barWidth: 0.8,
                            fillColor: '#8E79A8',
                            highlightColor: '#8E79A8',
                            horizontal: true
                        }
                    }],
                    {
                        yaxis: {
                            color: 'white',
                            ticks: [
                                <?php foreach($stat['ticks'] as $k => $v): ?>
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
            }, 200);
        });

        <?php $i++; ?>
        <?php endforeach; ?>

});
</script>