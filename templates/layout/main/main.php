<!DOCTYPE html>

<html lang="en">
    <head>
        <?php include('head.php'); ?>
    </head>

    <body>
        <?php include('menu.php'); ?>

        <?php include('../templates/' . ($array['content'] ?? 'layout/main/empty.php')); ?>

        <?php include('foot.php'); ?>
    </body>
</html>
