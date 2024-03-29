<div class="container">
    <h2>App Options</h2>

    <?php if ($array['error'] !== '') { ?>
        <div class="alert alert-danger">
            <?= $array['error'] ?>
        </div>
    <?php } ?>

    <form method="post">
        <div class="form-group">
            <label for="version">App Version</label>
            <input type="text" class="form-control" id="version" placeholder="Enter version" name="version" value="<?= $array['version'] ?>" readonly="readonly" />
        </div>

        <div class="form-group">
            <label for="admin_email" data-toggle="tooltip" data-placement="right" title="The email used to send">Admin Email</label>
            <input type="email" class="form-control" id="admin_email" placeholder="Enter admin email" name="admin_email" value="<?= $array['adminEmail'] ?>" />
        </div>

        <div class="form-group">
            <label for="emails_number" data-toggle="tooltip" data-placement="right" title="The number of emails sent in the package every period of time">Emails Number</label>
            <input type="text" class="form-control" id="emails_number" placeholder="Enter emails number" name="emails_number" value="<?= $array['emailsNumber'] ?>" />
        </div>

        <div class="form-group">
            <label for="time_period" data-toggle="tooltip" data-placement="right" title="The time in minutes between sending each package of emails">Time Period</label>
            <input type="text" class="form-control" id="time_period" placeholder="Enter time period" name="time_period" value="<?= $array['timePeriod'] ?>" />
        </div>

        <div class="form-group">
            <label data-toggle="tooltip" data-placement="right" title="Whether to add an unsubscribe link to your emails">Unsubscribe Active</label>

            <div>
                <label class="radio-inline"><input type="radio" name="unsubscribe_active" value="1"<?php if ($array['unsubscribeActive']) { ?> checked="checked"<?php } ?> />Yes</label>
                <label class="radio-inline"><input type="radio" name="unsubscribe_active" value="0"<?php if (!$array['unsubscribeActive']) { ?> checked="checked"<?php } ?> />No</label>
            </div>
        </div>

        <div class="form-group">
            <label for="unsubscribe_url" data-toggle="tooltip" data-placement="right" title="The url with the domain for this administrative application">Unsubscribe Url</label>
            <input type="text" class="form-control" id="unsubscribe_url" placeholder="Enter unsubscribe url" name="unsubscribe_url" value="<?= $array['unsubscribeUrl'] ?>" />
        </div>

        <div class="form-group">
            <label for="newsletter_name" data-toggle="tooltip" data-placement="right" title="The name of the newsletter list">Newsletter Name</label>
            <input type="text" class="form-control" id="newsletter_name" placeholder="Enter newsletter name" name="newsletter_name" value="<?= $array['newsletterName'] ?>" />
        </div>

        <button type="submit" class="btn btn-primary" name="submit" value="1">Save</button>

        <input type="hidden" name="token" value="<?= $array['token'] ?>" />
    </form>
</div>
