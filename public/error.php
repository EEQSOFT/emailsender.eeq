<?php

declare(strict_types=1);

require(__DIR__ . '/../config/config.php');

$code = (int) ($_GET['code'] ?? 404);
?>
<!DOCTYPE html>

<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta charset="utf-8" />

        <title>Error <?= $code ?></title>

        <link rel="stylesheet" href="/css/bootstrap.min.css" />
    </head>

    <body>
        <div class="container">
            <h2>Error <?= $code ?></h2>

            <div class="alert alert-danger">
                <strong>Danger!</strong> Back to the main page <a href="/">here</a>.
            </div>
        </div>
    </body>
</html>
