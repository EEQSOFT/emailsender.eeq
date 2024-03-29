<div class="container">
    <h2>Password Change</h2>

    <div class="alert alert-info">
        <strong>Info!</strong> This option allows you to change the password. Please enter a new password for your account.
    </div>

    <?php if ($array['error'] !== '') { ?>
        <div class="alert alert-danger">
            <?= $array['error'] ?>
        </div>
    <?php } ?>

    <form method="post">
        <div class="form-group">
            <label for="new_password">New Password</label>
            <input type="password" class="form-control" id="new_password" placeholder="Enter new password" name="new_password" value="" />
        </div>

        <div class="form-group">
            <label for="repeat_password">Repeat password</label>
            <input type="password" class="form-control" id="repeat_password" placeholder="Enter new password again" name="repeat_password" value="" />
        </div>

        <button type="submit" class="btn btn-primary" name="submit" value="1">Change Password</button>

        <input type="hidden" name="token" value="<?= $array['token'] ?>" />
    </form>
</div>
