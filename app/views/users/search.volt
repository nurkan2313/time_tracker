<div class="row">
    <nav>
        <ul class="pager">
            <li class="previous">{{ link_to("Users/index", "Go Back") }}</li>
            <li class="next">{{ link_to("Users/new", "Create ") }}</li>
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
            <th>Name</th>
            <th>Login</th>
            <th>Password</th>
            <th>Email</th>
            <th>Active</th>

                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        {% if page.items is defined %}
        {% for user in page.items %}
            <tr>
                <td>{{ user.getId() }}</td>
            <td>{{ user.getName() }}</td>
            <td>{{ user.getLogin() }}</td>
            <td>{{ user.getPassword() }}</td>
            <td>{{ user.getEmail() }}</td>
            <td>{{ user.getActive() }}</td>

                <td>{{ link_to("Users/edit/"~user.getId(), "Edit") }}</td>
                <td>{{ link_to("Users/delete/"~user.getId(), "Delete") }}</td>
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
                <li>{{ link_to("Users/search", "First", false, "class": "page-link") }}</li>
                <li>{{ link_to("Users/search?page="~page.before, "Previous", false, "class": "page-link") }}</li>
                <li>{{ link_to("Users/search?page="~page.next, "Next", false, "class": "page-link") }}</li>
                <li>{{ link_to("Users/search?page="~page.last, "Last", false, "class": "page-link") }}</li>
            </ul>
        </nav>
    </div>
</div>
