<div class="col-md-12">
    <section class="panel">
        <header class="panel-heading">
            Project Detail
        </header>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <select class="form-control select2" id="project" data-placeholder="Choose roject..">
                            <option value=""></option>
                            <?php foreach($projects as $project): ?>
                                <option value="<?php echo $project['project']; ?>" <?php if($active_project == $project['project']) echo 'selected'; ?>>
                                    <?php echo $project['project']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div id="task" style="width: 100%; height: 400px"></div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript">
    $ = jQuery.noConflict();
    $(document).ready(function () {
        $(".select2").select2();
        $("#project").change(function()
        {
            var value = $(this).val();
            var params = {
                'action': 'get_graph_data',
                'values': {
                    'date_start': $("[name='date_start']").val(),
                    'date_end': $("[name='date_end']").val(),
                    'project': value
                },
                callback: function (msg) {
                    ajax_respond(msg,
                        function(respond)
                        {
                            var count = respond.data.length;
                            $("#task").height(30 * count + 60);
                            $("#task").html('');
                            $.plot($("#task"), [ {
                                    data: respond.data,
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
                                        ticks: respond.ticks
                                    },
                                    xasis: {
                                        color: 'white'
                                    }
                                }
                            );
                            var xaxisLabel = $("<div class='axisLabel xaxisLabel'></div>")
                                .text("Hours")
                                .appendTo($("#task"));
                            xaxisLabel.css("top", $("#task").height() - 40);
                        }
                    );
                }
            };
            ajax(params);
        });
        var count = <?php echo count($stats['data']); ?>;
        $("#task").height(30 * count + 60);
        $.plot($("#task"), [ {
                data: [
                    <?php foreach($stats['data'] as $k => $v): ?>
                    [<?php echo $v; ?>,<?php echo $k; ?>]<?php if($k != count($stats['data']) - 1) echo ','; ?>
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
                        <?php foreach($stats['ticks'] as $k => $v): ?>
                        [<?php echo $k; ?>, '<?php echo $v; ?>']<?php if($k != count($stats['ticks']) - 1) echo ','; ?>
                        <?php endforeach; ?>
                    ]
                },
                xasis: {
                    color: 'white',
                }
            }
        );
        var xaxisLabel = $("<div class='axisLabel xaxisLabel'></div>")
            .text("Hours")
            .appendTo($("#task"));
        xaxisLabel.css("top", $("#task").height() - 40);



});
</script>