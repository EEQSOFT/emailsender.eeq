<div class="container">
    <h2>Information</h2>

    <?php if ($array['passwordChangeEmailSent']) { ?>
        <div class="alert alert-success">
            <strong>Success!</strong> Check your email for further instructions.
        </div>
    <?php } else { ?>
        <div class="alert alert-danger">
            <strong>Danger!</strong> Sending the email with further instructions has failed.
        </div>
    <?php } ?>
</div>
