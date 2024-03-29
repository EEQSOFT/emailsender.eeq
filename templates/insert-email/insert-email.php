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

        <input type="hidden" name="name" value="<?= $array['name'] ?>" />
        <input type="hidden" name="email" value="<?= $array['email'] ?>" />
        <input type="hidden" name="token" value="<?= $array['token'] ?>" />
    </form>

    <br />

    <h2>Email Addition</h2>

    <?php if ($array['error2'] !== '') { ?>
        <div class="alert alert-danger">
            <?= $array['error2'] ?>
        </div>
    <?php } ?>

    <form method="post">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" placeholder="Enter name" name="name" value="<?= $array['name'] ?>" />
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" value="<?= $array['email'] ?>" />
        </div>

        <button type="submit" class="btn btn-primary" name="submit2" value="1">Add</button>

        <input type="hidden" name="list" value="<?= $array['list'] ?>" />
        <input type="hidden" name="token" value="<?= $array['token'] ?>" />
    </form>
</div>
