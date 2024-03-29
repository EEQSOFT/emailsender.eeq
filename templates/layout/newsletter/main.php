<!DOCTYPE html>

<html lang="en">
    <head>
        <?php include('head.php'); ?>
    </head>

    <body>
        <?php include('../templates/' . ($array['content'] ?? 'layout/newsletter/empty.php')); ?>

        <?php include('foot.php'); ?>
    </body>
</html>
