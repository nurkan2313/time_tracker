<div class="container">
    <h2>удаление пользователя</h2>

    <p><?php echo $this->flashSession->output() ?></p>

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
</div>