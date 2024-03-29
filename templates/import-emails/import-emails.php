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

            <select class="form-control" id="list" name="list">
                <option value="0">None</option>

                <?php foreach ($array['listList'] as $key => $value) { ?>
                    <option value="<?= $key ?>"<?php if ($key === $array['list']) { ?> selected="selected"<?php } ?>><?= $value['list_name'] ?></option>
                <?php } ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary" name="submit" value="1">Select</button>

        <input type="hidden" name="token" value="<?= $array['token'] ?>" />
    </form>

    <br />

    <h2>Emails Import</h2>

    <?php if ($array['error2'] !== '') { ?>
        <div class="alert alert-danger">
            <?= $array['error2'] ?>
        </div>
    <?php } ?>

    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="file">Emails File</label>
            <input type="file" class="form-control" id="file" name="file" />
        </div>

        <button type="submit" class="btn btn-primary" name="submit2" value="1">Import</button>

        <input type="hidden" name="list" value="<?= $array['list'] ?>" />
        <input type="hidden" name="token" value="<?= $array['token'] ?>" />
    </form>
</div>
