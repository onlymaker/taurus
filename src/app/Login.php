<?php

namespace app;

use app\common\AppHelper;
use db\SqlMapper;

class Login
{
    use AppHelper;

    private $administrator = ['debug'];

    function get()
    {
        echo \Template::instance()->render('login.html');
    }

    function post(\Base $f3)
    {
        $logger = $f3->get('LOGGER');
        $username = $_POST['username'];
        $password = $_POST['password'];

        $logger->write("Receive login request: $username");

        if ($this->validate($username, $password)) {
            $logger->write("User $username login success");
            $f3->set('SESSION.AUTHENTICATION', $username);
            $f3->set('SESSION.AUTHORIZATION', in_array($username, $this->administrator) ? 'administrator' : 'user');
            echo json_encode([
                'error' => ['code' => 0]
            ]);
        } else {
            $logger->write("User $username login failure");
            echo json_encode([
                'error' => ['code' => -1, 'text' => 'login error']
            ]);
        }
    }

    function validate($username, $password)
    {
        $user = new SqlMapper('user');
        $auth = new \Auth($user, [
            'id' => 'username',
            'pw' => 'password',
        ]);
        return $auth->login($username, $password);
    }
}
