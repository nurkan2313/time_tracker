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
            </ul>
        </div>
    </div>
</nav>
