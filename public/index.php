<?php

declare(strict_types=1);

require(__DIR__ . '/../config/config.php');
require(__DIR__ . '/../src/autoload.php');

use App\Core\{Config, CookieLogin, Data};

$config = new Config();
$cookieLogin = new CookieLogin($config);
$data = new Data();

$_GET['action'] ??= 'main-page';

$options = require(OPTIONS_FILE);
$appSettings = require(SETTINGS_FILE);
$settings = array_key_exists($_GET['action'], $appSettings)
    ? $appSettings[$_GET['action']] : null;

if (!$options['installed']) {
    header('Location: ' . $config->getUrl() . '/install.php');

    exit;
}

$cookieLogin->setCookieLogin();

$settings['role'] ??= 'user';
$settings['option'] ??= 'page';
$settings['name'] ??= 'MainPage';

switch ($settings['role']) {
    case 'user':
        if (($_SESSION['user'] ?? '') === '') {
            header('Location: ' . $config->getUrl() . '/log-in');

            exit;
        }

        break;
    case 'admin':
        if (($_SESSION['admin'] ?? false) === false) {
            header('Location: ' . $config->getUrl() . '/log-in');

            exit;
        }

        break;
    default:
        break;
}

switch ($settings['option']) {
    case 'page':
        $class = 'App\\Controller\\' . $settings['name'] . 'Controller';
        $method = $settings['name'] . 'Action';

        $controller = new $class();

        $array = $controller->$method(
            $data->prepareInput($_REQUEST),
            $_SESSION,
            $options,
            $_FILES
        );

        break;
    case 'ajax':
        $class = 'App\\Controller\\Ajax\\' . $settings['name'] . 'Controller';
        $method = $settings['name'] . 'Action';

        $controller = new $class();

        $array = $controller->$method($data->prepareInput($_REQUEST));

        break;
    case 'api':
        $class = 'App\\Controller\\Api\\' . $settings['name'] . 'Controller';
        $method = $settings['name'] . 'Action';

        $controller = new $class();

        $array = $controller->$method(
            $_SERVER,
            $data->prepareInput(
                json_decode(file_get_contents('php://input'), true) ?? []
            )
        );

        if ($array['redirection'] ?? false) break;

        echo json_encode($array);

        exit;
    default:
        $array = array();

        $array['layout'] = 'layout/main/main.php';
        $array['content'] = 'default/default.php';
        $array['activeMenu'] = '';
        $array['title'] = 'Empty Page';

        break;
}

if ($array['redirection'] ?? false) {
    header('Location: ' . $config->getUrl() . $array['path']);

    exit;
}

$array['url'] = $config->getUrl();

$array = $data->prepareOutput($array);

if ($settings['option'] === 'ajax') {
    include('../templates/' . $array['content']);
} else {
    include('../templates/' . ($array['layout'] ?? 'layout/main/main.php'));
}
