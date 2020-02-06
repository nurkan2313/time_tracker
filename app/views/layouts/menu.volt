<nav class="mb-4 navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand font-bold" href="{{ url('/') }}">Главная</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-4" aria-controls="navbarSupportedContent-4" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent-4">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    {% if session.has('IS_LOGIN') %}
                        <a class="nav-link" href="{{ url('/user/logout') }}">Выйти</a>
                    {% else %}
                        <a class="nav-link" href="{{ url('/user/login') }}">Логин</a>
                    {% endif %}
                </li>

                {% if session.get('AUTH') == 'admin' %}
                <li class="nav-item dropdown">

                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       Welcome admin: {{ session.get('AUTH_NAME') }}
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ url('/user/register') }}">Зарегать пользователя</a>
                        <a class="dropdown-item" href="{{ url('/admin/disableUser') }}">Забанить пользователя</a>
                        <a class="dropdown-item" href="{{ url('/admin/manageUsers') }}">Редактировать время пользователей</a>
                        <a class="dropdown-item" href="{{ url('/admin/setHour') }}">Назначить время начала рабочего дня</a>
                        <a class="dropdown-item" href="{{ url('/admin/holiday') }}">Создать выходные дни</a>
                    </div>

                </li>
                {% elseif session.get('AUTH') == 'users' %}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Welcome user: {{ session.get('AUTH_NAME') }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ url('/user/worktable') }}">Рабочая таблица</a>
                        </div>

                    </li>
                {% endif %}

            </ul>
        </div>
    </div>
</nav>
