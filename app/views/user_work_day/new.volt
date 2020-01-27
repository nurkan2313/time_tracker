<div class="row">
    <nav>
        <ul class="pager">
            <li class="previous">{{ link_to("user_work_day", "Go Back") }}</li>
        </ul>
    </nav>
</div>

<div class="page-header">
    <h1>Create user_work_day</h1>
</div>

{{ content() }}

<form action="user_work_day/create" class="form-horizontal" method="post">
    <div class="form-group">
    <label for="fieldId" class="col-sm-2 control-label">Id</label>
    <div class="col-sm-10">
        {{ text_field("id", "type" : "numeric", "class" : "form-control", "id" : "fieldId") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldTotalWorkHour" class="col-sm-2 control-label">Total Of Work Of Hour</label>
    <div class="col-sm-10">
        {{ text_field("total_work_hour", "size" : 30, "class" : "form-control", "id" : "fieldTotalWorkHour") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldRemain" class="col-sm-2 control-label">Remain</label>
    <div class="col-sm-10">
        {{ text_field("remain", "size" : 30, "class" : "form-control", "id" : "fieldRemain") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldDay" class="col-sm-2 control-label">Day</label>
    <div class="col-sm-10">
        {{ text_field("day", "size" : 30, "class" : "form-control", "id" : "fieldDay") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldStartTime" class="col-sm-2 control-label">Start Of Time</label>
    <div class="col-sm-10">
        {{ text_field("start_time", "size" : 30, "class" : "form-control", "id" : "fieldStartTime") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldEndTime" class="col-sm-2 control-label">End Of Time</label>
    <div class="col-sm-10">
        {{ text_field("end_time", "size" : 30, "class" : "form-control", "id" : "fieldEndTime") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldUserId" class="col-sm-2 control-label">User</label>
    <div class="col-sm-10">
        {{ text_field("user_id", "type" : "numeric", "class" : "form-control", "id" : "fieldUserId") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldTimeDimensionId" class="col-sm-2 control-label">Time Of Dimension</label>
    <div class="col-sm-10">
        {{ text_field("time_dimension_id", "type" : "numeric", "class" : "form-control", "id" : "fieldTimeDimensionId") }}
    </div>
</div>


    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            {{ submit_button('Save', 'class': 'btn btn-default') }}
        </div>
    </div>
</form>
