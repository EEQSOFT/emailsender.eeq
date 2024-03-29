<div class="container">
    <h2>Information</h2>

    <?php if ($array['userActiveSet']) { ?>
        <div class="alert alert-success">
            <strong>Success!</strong> The user account has been activated.
        </div>
    <?php } else { ?>
        <div class="alert alert-danger">
            <strong>Danger!</strong> The user account has not been activated.
        </div>
    <?php } ?>
</div>
