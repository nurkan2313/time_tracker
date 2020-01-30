    <h2>удаление пользователя</h2>

    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <strong><?php echo $this->flashSession->output() ?></strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <ul class="list-group">
        {% for user in users %}
        <li class="list-group-item d-flex justify-content-between align-items-center">
            {{ user.name }}
            <span class="badge badge-primary badge-pill"> ID: {{ user.id }}</span>
        </li>
        {% endfor %}
    </ul>

    <?php echo $this->tag->form("/admin/disableUser"); ?>

    <div class="form-group">
        <label for="name">ID: </label>
        <?php echo $form->render('user_id'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->render('submit'); ?>
    </div>

    </form>
