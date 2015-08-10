<div class="col-md-6">
    <section class="panel">
        <header class="panel-heading">
            Overall Costs
        </header>
        <div class="panel-body">
            <div id="overall" style="width:600px;height:300px"></div>
        </div>
    </section>
</div>
<script type="text/javascript">
    $ = jQuery.noConflict();
    $(document).ready(function () {
        var ticks = ['a','b','c','d'];
        $.plot($("#overall"), [ {
                data: [[0, 4], [1, 6], [2,3], [3,1], [4,5], [5,2], [6,4], [7,0], [8,4]],
                bars: {
//                    show: true,
                    barWidth: 0.8,
                    fillColor: '#CCE8E2',
                    highlightColor: '#CCE8E2',
                    horizontal: true
                }
            }],
            {
                yaxis: {
                    max: 7,
                    ticks: [[0,'Time Tracker Fix'],[1,'Software'],[2,'Cadworx'],[3,'CRM bug fixes'], [4,'invoice hcc'], [5,'Rolex Time'], [6, 'Shoring takeoff']]
                },
                xasis: {
                    max: 20
                }
            });
    });
</script>