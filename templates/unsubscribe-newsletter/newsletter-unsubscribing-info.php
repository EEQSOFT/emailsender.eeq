<div class="container">
    <h2>Information</h2>

    <?php if ($array['emailDataDeleted']) { ?>
        <div class="alert alert-success">
            <strong>Success!</strong> The newsletter has been unsubscribed.
        </div>
    <?php } else { ?>
        <div class="alert alert-danger">
            <strong>Danger!</strong> The newsletter has not been unsubscribed.
        </div>
    <?php } ?>
</div>
