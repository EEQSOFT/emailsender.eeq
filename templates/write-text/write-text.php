<div class="container">
    <h2>Text Writing</h2>

    <?php if ($array['error'] !== '') { ?>
        <div class="alert alert-danger">
            <?= $array['error'] ?>
        </div>
    <?php } ?>

    <form action="<?= $array['url'] ?>/write" method="post">
        <div class="form-group">
            <label for="subject">Subject</label>
            <input type="text" class="form-control" id="subject" placeholder="Enter subject" name="subject" value="<?= $array['subject'] ?>" />
        </div>

        <div class="form-group">
            <label for="message">Message</label>
            <textarea class="form-control" rows="10" id="message" placeholder="Enter message" name="message"><?= $array['message'] ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary" name="submit" value="1">Save</button>

        <input type="hidden" name="token" value="<?= $array['token'] ?>" />
    </form>

    <br />

    <h2>Texts List</h2>

    <table class="table table-striped table-text">
        <thead>
            <tr>
                <th class="table-text-id">Id</th>
                <th class="table-text-subject">Subject</th>
                <th class="table-text-edit">Edit</th>
                <th class="table-text-delete">Delete</th>
            </tr>
        </thead>

        <?php if (empty($array['textList'])) { ?>
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
                <?php foreach ($array['textList'] as $key => $value) { ?>
                    <tr>
                        <td><?= $key ?></td>
                        <td><?= $value['text_subject'] ?></td>
                        <td><a href="<?= $array['url'] ?>/write,<?= $key ?>,edit">Edit</a></td>
                        <td><a href="<?= $array['url'] ?>/write,<?= $key ?>,delete">X</a></td>
                    </tr>
                <?php } ?>
            </tbody>
        <?php } ?>
    </table>

    <?= $array['pageNavigator'] ?>
</div>
