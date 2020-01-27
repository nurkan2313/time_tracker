<div class="row">
    <nav>
        <ul class="pager">
            <li class="previous">{{ link_to("time_dimension/index", "Go Back") }}</li>
            <li class="next">{{ link_to("time_dimension/new", "Create ") }}</li>
        </ul>
    </nav>
</div>

<div class="page-header">
    <h1>Search result</h1>
</div>

{{ content() }}

<div class="row">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Id</th>
            <th>Db Of Date</th>
            <th>Year</th>
            <th>Month</th>
            <th>Day</th>
            <th>Quarter</th>
            <th>Week</th>
            <th>Day Of Name</th>
            <th>Month Of Name</th>
            <th>Holiday Of Flag</th>
            <th>Weekend Of Flag</th>
            <th>Event</th>

                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        {% if page.items is defined %}
        {% for time_dimension in page.items %}
            <tr>
                <td>{{ time_dimension.getId() }}</td>
            <td>{{ time_dimension.getDbDate() }}</td>
            <td>{{ time_dimension.getYear() }}</td>
            <td>{{ time_dimension.getMonth() }}</td>
            <td>{{ time_dimension.getDay() }}</td>
            <td>{{ time_dimension.getQuarter() }}</td>
            <td>{{ time_dimension.getWeek() }}</td>
            <td>{{ time_dimension.getDayName() }}</td>
            <td>{{ time_dimension.getMonthName() }}</td>
            <td>{{ time_dimension.getHolidayFlag() }}</td>
            <td>{{ time_dimension.getWeekendFlag() }}</td>
            <td>{{ time_dimension.getEvent() }}</td>

                <td>{{ link_to("time_dimension/edit/"~time_dimension.getId(), "Edit") }}</td>
                <td>{{ link_to("time_dimension/delete/"~time_dimension.getId(), "Delete") }}</td>
            </tr>
        {% endfor %}
        {% endif %}
        </tbody>
    </table>
</div>

<div class="row">
    <div class="col-sm-1">
        <p class="pagination" style="line-height: 1.42857;padding: 6px 12px;">
            {{ page.current~"/"~page.total_pages }}
        </p>
    </div>
    <div class="col-sm-11">
        <nav>
            <ul class="pagination">
                <li>{{ link_to("time_dimension/search", "First", false, "class": "page-link") }}</li>
                <li>{{ link_to("time_dimension/search?page="~page.before, "Previous", false, "class": "page-link") }}</li>
                <li>{{ link_to("time_dimension/search?page="~page.next, "Next", false, "class": "page-link") }}</li>
                <li>{{ link_to("time_dimension/search?page="~page.last, "Last", false, "class": "page-link") }}</li>
            </ul>
        </nav>
    </div>
</div>
