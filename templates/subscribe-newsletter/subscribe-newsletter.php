<div class="container">
    <h2>Newsletter Subscribing</h2>

    <?php if ($array['error'] !== '') { ?>
        <div class="alert alert-danger">
            <?= $array['error'] ?>
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

        <button type="submit" class="btn btn-primary" name="submit" value="1">Subscribe</button>

        <input type="hidden" name="token" value="<?= $array['token'] ?>" />
    </form>
</div>
