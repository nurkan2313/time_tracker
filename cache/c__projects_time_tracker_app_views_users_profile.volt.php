<div class="container">
    <h2>Профиль</h2>
    <hr>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Привет, <?= $this->session->get('AUTH_NAME') ?></h5>
            <p class="card-text"><?= $this->session->get('AUTH_EMAIL') ?></p>
            <p class="card-text"><small class="text-muted">кол - во отработанных часовв</small></p>
            <?= $this->tag->linkTo(["user/logout", '<i class="fa fa-sign-out" aria-hidden="true"></i> Выйти', "class" => "btn btn-danger"]); ?>
        </div>
    </div>
</div>