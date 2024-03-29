<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\{Database, Manager};

class Controller
{
    protected Database $database;
    protected Manager $manager;

    public function setManager(): void
    {
        if (!isset($this->manager)) {
            $this->database = new Database();
            $this->manager = new Manager($this->database);

            $this->database->dbConnect();
        }
    }

    public function getManager(): Manager
    {
        $this->setManager();

        return $this->manager;
    }

    public function redirectToRoute(string $route): array
    {
        switch ($route) {
            case 'main_page':
                $path = '/';

                break;
            case 'login_page':
                $path = '/log-in';

                break;
            case 'logout_page':
                $path = '/log-out';

                break;
            default:
                $path = '/';

                break;
        }

        return array('redirection' => true, 'path' => $path);
    }
}
