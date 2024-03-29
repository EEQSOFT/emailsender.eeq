<div class="container">
    <h2>Password Reset</h2>

    <div class="alert alert-info">
        <strong>Info!</strong> This option allows you to reset the password. Please enter a login to your account.
    </div>

    <?php if ($array['error'] !== '') { ?>
        <div class="alert alert-danger">
            <?= $array['error'] ?>
        </div>
    <?php } ?>

    <form method="post">
        <div class="form-group">
            <label for="login">Login / Email</label>
            <input type="text" class="form-control" id="login" placeholder="Enter login / email" name="login" value="<?= $array['login'] ?>" />
        </div>

        <button type="submit" class="btn btn-primary" name="submit" value="1">Reset Password</button>

        <input type="hidden" name="token" value="<?= $array['token'] ?>" />
    </form>
</div>
