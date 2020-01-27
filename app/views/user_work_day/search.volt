<div class="row">
    <nav>
        <ul class="pager">
            <li class="previous">{{ link_to("user_work_day/index", "Go Back") }}</li>
            <li class="next">{{ link_to("user_work_day/new", "Create ") }}</li>
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
            <th>Total Of Work Of Hour</th>
            <th>Remain</th>
            <th>Day</th>
            <th>Start Of Time</th>
            <th>End Of Time</th>
            <th>User</th>
            <th>Time Of Dimension</th>

                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        {% if page.items is defined %}
        {% for user_work_day in page.items %}
            <tr>
                <td>{{ user_work_day.getId() }}</td>
            <td>{{ user_work_day.getTotalWorkHour() }}</td>
            <td>{{ user_work_day.getRemain() }}</td>
            <td>{{ user_work_day.getDay() }}</td>
            <td>{{ user_work_day.getStartTime() }}</td>
            <td>{{ user_work_day.getEndTime() }}</td>
            <td>{{ user_work_day.getUserId() }}</td>
            <td>{{ user_work_day.getTimeDimensionId() }}</td>

                <td>{{ link_to("user_work_day/edit/"~user_work_day.getId(), "Edit") }}</td>
                <td>{{ link_to("user_work_day/delete/"~user_work_day.getId(), "Delete") }}</td>
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
                <li>{{ link_to("user_work_day/search", "First", false, "class": "page-link") }}</li>
                <li>{{ link_to("user_work_day/search?page="~page.before, "Previous", false, "class": "page-link") }}</li>
                <li>{{ link_to("user_work_day/search?page="~page.next, "Next", false, "class": "page-link") }}</li>
                <li>{{ link_to("user_work_day/search?page="~page.last, "Last", false, "class": "page-link") }}</li>
            </ul>
        </nav>
    </div>
</div>
