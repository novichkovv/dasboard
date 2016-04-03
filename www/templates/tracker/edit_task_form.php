<style>
    .inp-label {
        width: 100px;
        display: inline-block;
    }
</style>

<h3>Add new worked time entry:</h3> </br>

<style>
    .seiko-time{
        width: 40px;
    }
    table.seiko-edittable td{
        border: 0 !important;
    }
    .seiko-edittable{
        width: 600px;
    }

    .seiko-innertable td{
        padding: 0;
        background-color: white !important;
    }
    .seiko-innertable table td{
        background-color: white !important;
    }
    .seiko-innertable{
        width: 100%;
    }
    .seiko-tr-toggle{
        display: none;
    }
    .seiko-edittable td{
        background-color: white !important;
    }
</style>

<table class="seiko-edittable">
    <tbody>
    <tr>
        <td>
            <table class="seiko-innertable">
                <col style="width: 50%"/>
                <col style="width: 50%"/>
                <tr>
                    <td colspan="2" style="padding-bottom: 15px;">

                        Duration (hours): <input type="text" value="00:00" class="seiko-time work_duration" name="work_duration">
                    </td>
                </tr>
                <tr>
                    <td>
                        Start
                    </td>
                    <td>
                        End
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="text" name="work_begin_time" value="03:11" placeholder="e.g. 19:33" class="seiko-time work_begin_time">
                    </td>
                    <td>
                        <input type="text" name="work_end_time" value="03:11" placeholder="e.g. 19:33" class="seiko-time work_end_time">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="hidden" name="work_begin_pk" value="newentry">
                        <div class="seiko-datepicker"></div>
                        <input type="hidden" name="work_begin_date" class="work_begin_date" value="2016-04-03" placeholder="e.g. 2015-07-28">
                    </td>
                </tr>

            </table>
        </td>
        <td>
        </td>
        <td>
            <button class="btn btn-success saveedit" data-taskid="22952785588248"
                    data-userid="13084161448998">Save
            </button>
            <button data-userid="13084161448998" data-taskid="22952785588248"
                    class="btn btn-danger deledit">Del
            </button>
        </td>

    </tr>
    </tbody>
</table>
<hr/>


<hr/>
<h3>Edit existing worked time entries:</h3>
<hr/>
<table class="seiko-edittable">
    <tbody>
    <tr>
        <td>
            <table class="seiko-innertable" id="seiko-innertable_1">
                <col style="width: 50%"/>
                <col style="width: 50%"/>
                <tr>
                    <td colspan="2" style="padding-bottom: 15px;">

                        Duration (hours): <input type="text" value="02:00" name="work_duration" class="seiko-time work_duration">                                                 <span class="seiko-date-info">Tuesday, December 1 13:33  2015 </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div  class="seiko-tr-toggle">
                            Start
                        </div>
                    </td>
                    <td>
                        <div  class="seiko-tr-toggle">
                            End
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div  class="seiko-tr-toggle">
                            <input type="text" name="work_begin_time" value="13:33"
                                   placeholder="e.g. 19:33" class="seiko-time work_begin_time">
                        </div>
                    </td>
                    <td>
                        <div  class="seiko-tr-toggle">
                            <input type="text" name="work_end_time" value="15:33"
                                   placeholder="e.g. 19:33" class="seiko-time work_end_time">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div  class="seiko-tr-toggle">
                            <input type="hidden" name="work_begin_pk" value="2015-12-01 13:33:00">

                            <div class="seiko-datepicker"></div>
                            <input type="hidden" name="work_begin_date" class="work_begin_date"
                                   value="2015-12-01" placeholder="e.g. 2015-07-28">
                        </div>
                    </td>
                </tr>

            </table>


        </td>
        <td>
        </td>
        <td style="text-align: right">
            <button class="btn btn-success saveedit" data-taskid="22952785588248"
                    data-userid="13084161448998">Save
            </button>
            <button data-userid="13084161448998" data-taskid="22952785588248"
                    class="btn btn-danger deledit">Del
            </button>
        </td>

    </tr>
    <tr>
        <td>
            <table class="seiko-innertable" id="seiko-innertable_2">
                <col style="width: 50%"/>
                <col style="width: 50%"/>
                <tr>
                    <td colspan="2" style="padding-bottom: 15px;">

                        Duration (hours): <input type="text" value="04:00" name="work_duration" class="seiko-time work_duration">                                                 <span class="seiko-date-info">Monday, November 2 13:34  2015 </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div  class="seiko-tr-toggle">
                            Start
                        </div>
                    </td>
                    <td>
                        <div  class="seiko-tr-toggle">
                            End
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div  class="seiko-tr-toggle">
                            <input type="text" name="work_begin_time" value="13:34"
                                   placeholder="e.g. 19:33" class="seiko-time work_begin_time">
                        </div>
                    </td>
                    <td>
                        <div  class="seiko-tr-toggle">
                            <input type="text" name="work_end_time" value="17:34"
                                   placeholder="e.g. 19:33" class="seiko-time work_end_time">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div  class="seiko-tr-toggle">
                            <input type="hidden" name="work_begin_pk" value="2015-11-02 13:34:00">

                            <div class="seiko-datepicker"></div>
                            <input type="hidden" name="work_begin_date" class="work_begin_date"
                                   value="2015-11-02" placeholder="e.g. 2015-07-28">
                        </div>
                    </td>
                </tr>

            </table>


        </td>
        <td>
        </td>
        <td style="text-align: right">
            <button class="btn btn-success saveedit" data-taskid="22952785588248"
                    data-userid="13084161448998">Save
            </button>
            <button data-userid="13084161448998" data-taskid="22952785588248"
                    class="btn btn-danger deledit">Del
            </button>
        </td>

    </tr>
    <tr>
        <td>
            <table class="seiko-innertable" id="seiko-innertable_3">
                <col style="width: 50%"/>
                <col style="width: 50%"/>
                <tr>
                    <td colspan="2" style="padding-bottom: 15px;">

                        Duration (hours): <input type="text" value="00:01" name="work_duration" class="seiko-time work_duration">                                                 <span class="seiko-date-info">Monday, October 5 10:45  2015 </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div  class="seiko-tr-toggle">
                            Start
                        </div>
                    </td>
                    <td>
                        <div  class="seiko-tr-toggle">
                            End
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div  class="seiko-tr-toggle">
                            <input type="text" name="work_begin_time" value="10:45"
                                   placeholder="e.g. 19:33" class="seiko-time work_begin_time">
                        </div>
                    </td>
                    <td>
                        <div  class="seiko-tr-toggle">
                            <input type="text" name="work_end_time" value="10:46"
                                   placeholder="e.g. 19:33" class="seiko-time work_end_time">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div  class="seiko-tr-toggle">
                            <input type="hidden" name="work_begin_pk" value="2015-10-05 10:45:23">

                            <div class="seiko-datepicker"></div>
                            <input type="hidden" name="work_begin_date" class="work_begin_date"
                                   value="2015-10-05" placeholder="e.g. 2015-07-28">
                        </div>
                    </td>
                </tr>

            </table>


        </td>
        <td>
        </td>
        <td style="text-align: right">
            <button class="btn btn-success saveedit" data-taskid="22952785588248"
                    data-userid="13084161448998">Save
            </button>
            <button data-userid="13084161448998" data-taskid="22952785588248"
                    class="btn btn-danger deledit">Del
            </button>
        </td>

    </tr>
    </tbody>
</table>

<script>

    function fixWorkDuration(work_duration) {
        if (work_duration && work_duration.length == 4) {
            return "0" + work_duration;
        } else if (work_duration && work_duration.length == 1) {
            return "0" + work_duration + ":00";
        } else if (work_duration && work_duration.length == 2) {
            return work_duration + ":00";
        }
        return work_duration;
    }

    $(".work_end_time, .work_begin_time, .work_duration").off("change");
    $(".work_end_time, .work_begin_time, .work_duration").on("change", function(){
        var jthis = $(this);
        var jworkbegin = jthis.closest("table").find(".work_begin_time");
        var jworkend = jthis.closest("table").find(".work_end_time");
        var jworkduration = jthis.closest("table").find(".work_duration");


        var isWorkBeginTime = jthis.hasClass("work_begin_time");
        var isWorkEndTime = jthis.hasClass("work_end_time");
        var isWorkDuration = jthis.hasClass("work_duration");

        var timediff = seiko.timediff(jworkbegin.val(), jworkend.val());
        if(timediff && timediff.indexOf("-") > -1){
            timediff = seiko.addTimes("24:00", timediff);
        }



        if(isWorkBeginTime){
            jworkduration.val(timediff);
        }else if(isWorkEndTime){
            jworkduration.val(timediff);
        }else if(isWorkDuration){
            var durationValFixed = fixWorkDuration(jworkduration.val());
            var duration = seiko.addTimes(jworkbegin.val(),durationValFixed);
            if(duration && duration.substring(0,1) == "-"){
                duration = seiko.addTimes("24:00", duration);
            }

            jworkend.val(duration);
        }else{
            console.error("Seiko error: not correct object found");
        }
    });
    $(".saveedit").off("click");
    $(".saveedit").on("click", function () {
        var work_begin_date, work_begin_time, work_end_time, work_begin_pk, work_duration;
        var jthis = $(this);
        var userid = jthis.attr("data-userid");
        var taskid = jthis.attr("data-taskid");
        jthis.closest("tr").find("input").each(function () {
            var j = $(this);
            var name = j.attr("name");
            if (name == 'work_begin_date') {
                work_begin_date = j.val();
            } else if (name == 'work_begin_time') {
                work_begin_time = j.val();
            } else if (name == 'work_end_time') {
                work_end_time = j.val();
            } else if (name == 'work_begin_pk') {
                work_begin_pk = j.val();
            } else if (name == 'work_duration') {
                work_duration = j.val();
            }

        });

        var workfix, workendfix, workbeginfix;

        workfix = fixWorkDuration(work_duration);

        if(work_end_time && work_end_time.length == 4 ){
            workendfix = "0" + work_end_time;
        }
        if(work_begin_time && work_begin_time.length == 4 ){
            workbeginfix = "0" + work_begin_time;
        }


        var pattern = /(00|01|02|03|04|05|06|07|08|09|10|11|12|13|14|15|16|17|18|19|20|21|22|23):(0|1|2|3|4|5)\d/g; //match military time
        var re1 = new RegExp(pattern);
        var re2 = new RegExp(pattern);
        var re3 = new RegExp(pattern);

        var r1 = re1.exec((workbeginfix ? workbeginfix :work_begin_time));
        var r2 = re2.exec((workendfix ? workendfix :work_end_time));
        var r3 = re3.exec((workfix ? workfix : work_duration));

        if(!r1){
            alert("Please provide the _begin time_ in the military time format HH:MM, e.g. 22:15, provided was: " + work_begin_time);
            return;
        }

        if(!r2){
            alert("Please provide the _end time_ in the military time format HH:MM, e.g. 22:15, provided was: "  + work_end_time);
            return;
        }
        if(!r3){
            alert("Please provide the _work duration_ in the military time format HH:MM, e.g. 22:15, provided was: "  + work_duration);
            return;
        }


        var work_begin =  work_begin_date + " " + (workbeginfix ? workbeginfix : work_begin_time) + ":00";
        var work_end = work_begin_date + " " + (workendfix ? workendfix : work_end_time) + ":00";

        var d_begin = new Date(work_begin);
        var d_end = new Date(work_end);
        if(d_begin > d_end){
            //actually means worked past midnight, so increase one day and get string again
            d_end.setDate(d_end.getDate()+1);
            work_end = seiko.formattedDate(d_end);
        }

        $.post('editTaskTime.php', { action: "SAVE", userid: userid, taskid: taskid, work_begin: work_begin, work_end: work_end , work_begin_pk: work_begin_pk
        }, function (data) {
            $(".edittime_modal, .modal-backdrop").hide();
            $(".status").html(data);
            setTimeout(function(){
                $("#workspace-refresh").trigger("click");
            }, 2000);
        });


    });

    $(".deledit").off("click");
    $(".deledit").on("click", function () {
        var jthis = $(this);
        var userid = jthis.attr("data-userid");
        var taskid = jthis.attr("data-taskid");
        var work_begin_pk;
        jthis.closest("tr").find("input").each(function () {
            var j = $(this);
            if (j.attr("name") == 'work_begin_pk') {
                work_begin_pk = j.val();
            }

        });
        $.post('editTaskTime.php', {
            action: "DEL", userid: userid, taskid: taskid, work_begin_pk: work_begin_pk
        }, function (data) {
            $(".edittime_modal, .modal-backdrop").hide();
            $(".status").html(data);
            $("#workspace-refresh").trigger("click");

        });
    });
</script>