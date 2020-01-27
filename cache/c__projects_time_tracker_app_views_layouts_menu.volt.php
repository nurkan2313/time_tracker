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
            </ul>
        </div>
    </div>
</nav>
