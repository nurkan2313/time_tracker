<div class="page-header">
    <h1>Search time_dimension</h1>
    <p>{{ link_to("time_dimension/new", "Create time_dimension") }}</p>
</div>

{{ content() }}

<form action="time_dimension/search" class="form-horizontal" method="get">
    <div class="form-group">
    <label for="fieldId" class="col-sm-2 control-label">Id</label>
    <div class="col-sm-10">
        {{ text_field("id", "type" : "numeric", "class" : "form-control", "id" : "fieldId") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldDbDate" class="col-sm-2 control-label">Db Of Date</label>
    <div class="col-sm-10">
        {{ text_field("db_date", "type" : "date", "class" : "form-control", "id" : "fieldDbDate") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldYear" class="col-sm-2 control-label">Year</label>
    <div class="col-sm-10">
        {{ text_field("year", "type" : "numeric", "class" : "form-control", "id" : "fieldYear") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldMonth" class="col-sm-2 control-label">Month</label>
    <div class="col-sm-10">
        {{ text_field("month", "type" : "numeric", "class" : "form-control", "id" : "fieldMonth") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldDay" class="col-sm-2 control-label">Day</label>
    <div class="col-sm-10">
        {{ text_field("day", "type" : "numeric", "class" : "form-control", "id" : "fieldDay") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldQuarter" class="col-sm-2 control-label">Quarter</label>
    <div class="col-sm-10">
        {{ text_field("quarter", "type" : "numeric", "class" : "form-control", "id" : "fieldQuarter") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldWeek" class="col-sm-2 control-label">Week</label>
    <div class="col-sm-10">
        {{ text_field("week", "type" : "numeric", "class" : "form-control", "id" : "fieldWeek") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldDayName" class="col-sm-2 control-label">Day Of Name</label>
    <div class="col-sm-10">
        {{ text_field("day_name", "size" : 30, "class" : "form-control", "id" : "fieldDayName") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldMonthName" class="col-sm-2 control-label">Month Of Name</label>
    <div class="col-sm-10">
        {{ text_field("month_name", "size" : 30, "class" : "form-control", "id" : "fieldMonthName") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldHolidayFlag" class="col-sm-2 control-label">Holiday Of Flag</label>
    <div class="col-sm-10">
        {{ text_field("holiday_flag", "class" : "form-control", "id" : "fieldHolidayFlag") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldWeekendFlag" class="col-sm-2 control-label">Weekend Of Flag</label>
    <div class="col-sm-10">
        {{ text_field("weekend_flag", "class" : "form-control", "id" : "fieldWeekendFlag") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldEvent" class="col-sm-2 control-label">Event</label>
    <div class="col-sm-10">
        {{ text_field("event", "size" : 30, "class" : "form-control", "id" : "fieldEvent") }}
    </div>
</div>


    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            {{ submit_button('Search', 'class': 'btn btn-default') }}
        </div>
    </div>
</form>
