<?php

declare(strict_types=1);

namespace App\Controller;

class MainPageController
{
    public function mainPageAction(): array
    {
        return array(
            'content' => 'main-page/main-page.php',
            'activeMenu' => 'main-page',
            'title' => 'EmailSender with Newsletter'
        );
    }
}
