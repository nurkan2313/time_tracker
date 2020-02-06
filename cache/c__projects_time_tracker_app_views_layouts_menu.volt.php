<nav class="mb-4 navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand font-bold" href="<?= $this->url->get('/') ?>">Главная</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-4" aria-controls="navbarSupportedContent-4" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent-4">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <?php if ($this->session->has('IS_LOGIN')) { ?>
                        <a class="nav-link" href="<?= $this->url->get('/user/logout') ?>">Выйти</a>
                    <?php } else { ?>
                        <a class="nav-link" href="<?= $this->url->get('/user/login') ?>">Логин</a>
                    <?php } ?>
                </li>

                <?php if ($this->session->get('AUTH') == 'admin') { ?>
                <li class="nav-item dropdown">

                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       Welcome admin: <?= $this->session->get('AUTH_NAME') ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="<?= $this->url->get('/user/register') ?>">Зарегать пользователя</a>
                        <a class="dropdown-item" href="<?= $this->url->get('/admin/disableUser') ?>">Забанить пользователя</a>
                        <a class="dropdown-item" href="<?= $this->url->get('/admin/manageUsers') ?>">Редактировать время пользователей</a>
                        <a class="dropdown-item" href="<?= $this->url->get('/admin/setHour') ?>">Назначить время начала рабочего дня</a>
                        <a class="dropdown-item" href="<?= $this->url->get('/admin/holiday') ?>">Создать выходные дни</a>
                    </div>

                </li>
                <?php } elseif ($this->session->get('AUTH') == 'users') { ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Welcome user: <?= $this->session->get('AUTH_NAME') ?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="<?= $this->url->get('/user/worktable') ?>">Рабочая таблица</a>
                        </div>

                    </li>
                <?php } ?>

            </ul>
        </div>
    </div>
</nav>
