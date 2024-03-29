<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="<?= $array['url'] ?>/">EmailSender with Newsletter</a>
        </div>

        <?php if ($settings['role'] === 'user' || $settings['role'] === 'admin') { ?>
            <ul class="nav navbar-nav">
                <li<?php if ($array['activeMenu'] === 'main-page') { echo ' class="active"'; } ?>><a href="<?= $array['url'] ?>/">Home</a></li>
                <li<?php if ($array['activeMenu'] === 'app-options') { echo ' class="active"'; } ?>><a href="<?= $array['url'] ?>/options">Options</a></li>
                <li<?php if ($array['activeMenu'] === 'email-lists') { echo ' class="active"'; } ?>><a href="<?= $array['url'] ?>/lists">Lists</a></li>
                <li<?php if ($array['activeMenu'] === 'emails-list') { echo ' class="active"'; } ?>><a href="<?= $array['url'] ?>/emails">Emails</a></li>
                <li<?php if ($array['activeMenu'] === 'insert-email') { echo ' class="active"'; } ?>><a href="<?= $array['url'] ?>/insert">Insert</a></li>
                <li<?php if ($array['activeMenu'] === 'export-emails') { echo ' class="active"'; } ?>><a href="<?= $array['url'] ?>/export">Export</a></li>
                <li<?php if ($array['activeMenu'] === 'import-emails') { echo ' class="active"'; } ?>><a href="<?= $array['url'] ?>/import">Import</a></li>
                <li<?php if ($array['activeMenu'] === 'write-text') { echo ' class="active"'; } ?>><a href="<?= $array['url'] ?>/write">Write</a></li>
            </ul>

            <button class="btn <?php if ($crontabFileExists = file_exists(CRONTAB_FILE)) { echo 'btn-success'; } else { echo 'btn-danger'; } ?> navbar-btn" onclick="location.href = '<?= $array['url'] ?>/send';">Send</button>
        <?php } ?>

        <ul class="nav navbar-nav navbar-right">
            <?php if ($settings['role'] === 'user' || $settings['role'] === 'admin') { ?>
                <?php if ($_SESSION['admin'] ?? false) { ?>
                    <li<?php if ($array['activeMenu'] === 'add-delete-user') { echo ' class="active"'; } ?>><a href="<?= $array['url'] ?>/users"><span class="glyphicon glyphicon-user"></span> Users</a></li>
                <?php } ?>

                <li<?php if ($array['activeMenu'] === 'log-out-user') { echo ' class="active"'; } ?>><a href="<?= $array['url'] ?>/log-out"><span class="glyphicon glyphicon-log-out"></span> Log Out</a></li>
            <?php } else { ?>
                <?php if (!$options['registered']) { ?>
                    <li<?php if ($array['activeMenu'] === 'register-user') { echo ' class="active"'; } ?>><a href="<?= $array['url'] ?>/register"><span class="glyphicon glyphicon-user"></span> Register</a></li>
                <?php } ?>

                <li<?php if ($array['activeMenu'] === 'log-in-user') { echo ' class="active"'; } ?>><a href="<?= $array['url'] ?>/log-in"><span class="glyphicon glyphicon-log-in"></span> Log In</a></li>
            <?php } ?>
        </ul>
    </div>
</nav>
