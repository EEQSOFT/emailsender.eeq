<div class="container">
    <h2>Information</h2>

    <?php if ($array['activationEmailSent']) { ?>
        <div class="alert alert-warning">
            <strong>Warning!</strong> The account is not active.<br />
            Check your email to activate your account.
        </div>
    <?php } else { ?>
        <div class="alert alert-warning">
            <strong>Warning!</strong> The account is not active.<br />
            Sending the activation email has failed.
        </div>
    <?php } ?>
</div>
