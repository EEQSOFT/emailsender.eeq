<div class="container">
    <h2>List Selection</h2>

    <?php if ($array['error'] !== '') { ?>
        <div class="alert alert-danger">
            <?= $array['error'] ?>
        </div>
    <?php } ?>

    <form action="<?= $array['url'] ?>/emails" method="post">
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

        <input type="hidden" name="email" value="<?= $array['email'] ?>" />
        <input type="hidden" name="token" value="<?= $array['token'] ?>" />
    </form>

    <br />

    <h2>Email Search</h2>

    <?php if ($array['error2'] !== '') { ?>
        <div class="alert alert-danger">
            <?= $array['error2'] ?>
        </div>
    <?php } ?>

    <form action="<?= $array['url'] ?>/emails" method="post">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" value="<?= $array['email'] ?>" />
        </div>

        <button type="submit" class="btn btn-primary" name="submit2" value="1">Search</button>

        <input type="hidden" name="list" value="<?= $array['list'] ?>" />
        <input type="hidden" name="token" value="<?= $array['token'] ?>" />
    </form>

    <br />

    <h2>Emails List</h2>

    <table class="table table-striped table-email">
        <thead>
            <tr>
                <th class="table-email-number">Number</th>
                <th class="table-email-name">Name</th>
                <th class="table-email-email">Email</th>
                <th class="table-email-delete">Delete</th>
            </tr>
        </thead>

        <?php if (empty($array['emailList'])) { ?>
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
                <?php foreach ($array['emailList'] as $key => $value) { ?>
                    <tr>
                        <td><?= $array['number']-- ?></td>
                        <td><?= $value['email_name'] ?></td>
                        <td><?= $value['email_email'] ?></td>
                        <td><a href="<?= $array['url'] ?>/emails,<?= $key ?>,delete">X</a></td>
                    </tr>
                <?php } ?>
            </tbody>
        <?php } ?>
    </table>

    <?= $array['pageNavigator'] ?>
</div>
