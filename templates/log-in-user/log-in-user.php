<div class="container">
    <?php if (isset($_SESSION['user'])) { ?>
        <div class="alert alert-info">
            You are logged in as <?= $_SESSION['user'] ?>,
            <a href="<?= $array['url'] ?>/log-out">Log Out</a>
        </div>
    <?php } ?>

    <h2>User Login</h2>

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

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" placeholder="Enter password" name="password" value="" />
        </div>

        <div class="checkbox">
            <label>
                <input type="checkbox" name="remember" value="1"<?php if ($array['remember']) { ?> checked="checked"<?php } ?> /> Remember me,
                <a href="<?= $array['url'] ?>/reset-password">Reset Password</a>
            </label>
        </div>

        <button type="submit" class="btn btn-primary" name="submit" value="1">Log In</button>

        <input type="hidden" name="token" value="<?= $array['token'] ?>" />
    </form>
</div>
