
<div class="container">
    <p><?php echo $this->flashSession->output() ?></p>

    <?php echo $this->tag->form("user/login/submit"); ?>

        <div class="form-group">
            <label for="email">E-Mail</label>
            <?php echo $form->render('email'); ?>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <?php echo $form->render('password'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->render('submit'); ?>
        </div>

        <input type='hidden' name='<?= $this->security->getTokenKey() ?>' value='<?= $this->security->getToken() ?>'/>
    </form>

</div>