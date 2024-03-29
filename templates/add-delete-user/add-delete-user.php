<div class="container">
    <h2>User Addition</h2>

    <?php if ($array['error'] !== '') { ?>
        <div class="alert alert-danger">
            <?= $array['error'] ?>
        </div>
    <?php } ?>

    <form action="<?= $array['url'] ?>/users" method="post">
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

        <div class="form-group">
            <label for="admin">Privileges</label>
            <select class="form-control" id="admin" name="admin">
                <option value="0"<?php if (!$array['admin']) { ?> selected="selected"<?php } ?>>User</option>
                <option value="1"<?php if ($array['admin']) { ?> selected="selected"<?php } ?>>Admin</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary" name="submit" value="1">Add</button>

        <input type="hidden" name="token" value="<?= $array['token'] ?>" />
    </form>

    <br />

    <h2>User Deletion</h2>

    <table class="table table-striped table-user">
        <thead>
            <tr>
                <th class="table-user-login">Login</th>
                <th class="table-user-email">Email</th>
                <th class="table-user-privileges">Privileges</th>
                <th class="table-user-delete">Delete</th>
            </tr>
        </thead>

        <?php if (empty($array['userList'])) { ?>
            <tbody>
                <tr>
                    <td>None</td>
                    <td>None</td>
                    <td>None</td>
                    <td>None</td>
                </tr>
            </tbody>
        <?php } else { ?>
            <tbody>
                <?php foreach ($array['userList'] as $key => $value) { ?>
                    <tr>
                        <td><?= $value['user_login'] ?></td>
                        <td><?= $value['user_email'] ?></td>
                        <td><?php if ($value['user_admin']) { echo 'Admin'; } else { echo 'User'; } ?></td>
                        <td><a href="<?= $array['url'] ?>/users,<?= $key ?>,delete">X</a></td>
                    </tr>
                <?php } ?>
            </tbody>
        <?php } ?>
    </table>

    <?= $array['pageNavigator'] ?>
</div>
