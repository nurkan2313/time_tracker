

{#<table class="table table-striped">#}
    {#<thead>#}
    {#<tr>#}
        {#<th scope="col">День</th>#}
    {#{% for user in users %}#}
        {#<th scope="col">{{ user.name }}</th>#}
    {#{% endfor %}#}
    {#</tr>#}
    {#</thead>#}
    {#<tbody>#}
        {#<tr>#}
            {#<td>#</td>#}
        {#</tr>#}
    {#{% for date in calendar %}#}
        {#<tr>#}
            {#<td>{{ date.day }} {{ date.day_name }}</td>#}
        {#</tr>#}
    {#{% endfor %}#}

    {#</tbody>#}
{#</table>#}

<div class="limiter">
    <div class="container-table100">
        <div class="wrap-table100">
            <div class="table100">

                <table>
                    <thead>
                    <tr class="table100-head">
                        <th class="column1">День</th>
                        {% for fills in data %}
                            {% for userName in fills %}

                            {% endfor %}
                        {% endfor %}
                    </tr>
                    </thead>
                    <tbody>
                        {#{% for data in calendar %}#}
                            {#{% for cal in data['cal'] %}#}
                                {#{%for user in calendar['workDay'] %}#}
                                    {#<tr>#}
                                        {#<td class="column1">{{ cal.day }} </td>#}
                                    {#</tr>#}
                                {#{% endfor %}#}
                            {#{% endfor %}#}
                        {#{% endfor %}#}
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>