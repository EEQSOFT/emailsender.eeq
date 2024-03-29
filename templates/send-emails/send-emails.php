<div class="container">
    <h2>List Selection</h2>

    <?php if ($array['error'] !== '') { ?>
        <div class="alert alert-danger">
            <?= $array['error'] ?>
        </div>
    <?php } ?>

    <form method="post">
        <div class="form-group">
            <label for="list">Emails Lists</label>

            <select class="form-control" id="list" name="list"<?php if ($crontabFileExists) { ?> disabled="disabled"<?php } ?>>
                <option value="0">None</option>

                <?php foreach ($array['listList'] as $key => $value) { ?>
                    <option value="<?= $key ?>"<?php if ($key === $array['list']) { ?> selected="selected"<?php } ?>><?= $value['list_name'] ?></option>
                <?php } ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary" name="submit" value="1"<?php if ($crontabFileExists) { ?> disabled="disabled"<?php } ?>>Select</button>

        <input type="hidden" name="text" value="<?= $array['text'] ?>" />
        <input type="hidden" name="token" value="<?= $array['token'] ?>" />
    </form>

    <br />

    <h2>Text Selection</h2>

    <?php if ($array['error2'] !== '') { ?>
        <div class="alert alert-danger">
            <?= $array['error2'] ?>
        </div>
    <?php } ?>

    <form method="post">
        <div class="form-group">
            <label for="text">Texts Subjects</label>

            <select class="form-control" id="text" name="text"<?php if ($crontabFileExists) { ?> disabled="disabled"<?php } ?>>
                <option value="0">None</option>

                <?php foreach ($array['textList'] as $key => $value) { ?>
                    <option value="<?= $key ?>"<?php if ($key === $array['text']) { ?> selected="selected"<?php } ?>><?= $value['text_subject'] ?> [id: <?= $key ?>]</option>
                <?php } ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary" name="submit2" value="1"<?php if ($crontabFileExists) { ?> disabled="disabled"<?php } ?>>Select</button>

        <input type="hidden" name="list" value="<?= $array['list'] ?>" />
        <input type="hidden" name="token" value="<?= $array['token'] ?>" />
    </form>

    <br />

    <h2>Emails Sending</h2>

    <?php if ($array['error3'] !== '') { ?>
        <div class="alert alert-danger">
            <?= $array['error3'] ?>
        </div>
    <?php } ?>

    <form method="post">
        <?php if ($crontabFileExists) { ?>
            <div class="alert alert-success">
                <strong>Success!</strong> Your emails are being sent... [<?= $array['progress'] ?>%]
            </div>
        <?php } else { ?>
            <div class="alert alert-info">
                <strong>Info!</strong> Press "Send" to start sending your emails.
            </div>
        <?php } ?>

        <button type="submit" class="btn btn-success" name="submit3" value="1"<?php if ($crontabFileExists) { ?> disabled="disabled"<?php } ?>>Send</button>
        <button type="submit" class="btn btn-danger" name="submit4" value="1">Stop</button>

        <input type="hidden" name="list" value="<?= $array['list'] ?>" />
        <input type="hidden" name="text" value="<?= $array['text'] ?>" />
        <input type="hidden" name="token" value="<?= $array['token'] ?>" />
    </form>
</div>
