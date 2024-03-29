<div class="container">
    <h2>Information</h2>

    <?php if ($array['activationEmailSent']) { ?>
        <div class="alert alert-success">
            <strong>Success!</strong> Your user account has been created.<br />
            Check the email to activate your account.
        </div>
    <?php } else { ?>
        <div class="alert alert-danger">
            <strong>Danger!</strong> Your user account has been created.<br />
            Sending the activation email has failed.
        </div>
    <?php } ?>
</div>
