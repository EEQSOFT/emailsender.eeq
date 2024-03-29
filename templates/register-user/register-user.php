<div class="container">
    <h2>User Registration</h2>

    <?php if ($array['error'] !== '') { ?>
        <div class="alert alert-danger">
            <?= $array['error'] ?>
        </div>
    <?php } ?>

    <form method="post">
        <div class="form-group">
            <label for="login">Login</label>
            <input type="text" class="form-control" id="login" placeholder="Enter login" name="login" value="<?= $array['login'] ?>" />
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" value="<?= $array['email'] ?>" />
        </div>

        <div class="form-group">
            <label for="repeat_email">Repeat email</label>
            <input type="email" class="form-control" id="repeat_email" placeholder="Enter email again" name="repeat_email" value="" />
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" placeholder="Enter password" name="password" value="" />
        </div>

        <div class="form-group">
            <label for="repeat_password">Repeat password</label>
            <input type="password" class="form-control" id="repeat_password" placeholder="Enter password again" name="repeat_password" value="" />
        </div>

        <button type="submit" class="btn btn-primary" name="submit" value="1">Register</button>

        <input type="hidden" name="token" value="<?= $array['token'] ?>" />
    </form>
</div>
