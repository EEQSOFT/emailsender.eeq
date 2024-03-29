<div class="container">
    <h2>Text Editing</h2>

    <?php if ($array['error'] !== '') { ?>
        <div class="alert alert-danger">
            <?= $array['error'] ?>
        </div>
    <?php } ?>

    <form method="post">
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
</div>
