<?php

require_once dirname(__DIR__) . '/init.php';

call_user_func(function (Base $f3) {
    if (PHP_SAPI != 'cli') {
        $f3->config([
            ROOT . '/cfg/map.ini',
            ROOT . '/cfg/route.ini',
        ]);

        if ($f3->get('AJAX')) {
            $f3->set('ONERROR', function (Base $f3) {
                echo json_encode($f3->get('ERROR'), JSON_UNESCAPED_UNICODE);
            });
        } else {
            if (!$f3->get('DEBUG')) {
                $f3->set('ONERROR', function () {
                    echo Template::instance()->render('error.html');
                });
            }
        }
        $f3->run();
    }
}, Base::instance());
