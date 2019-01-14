<?php

namespace app;

class Callback
{
    function process(\Base $f3)
    {
        ob_start();
        var_dump($_REQUEST);
        $f3->get('LOGGER')->write(ob_get_clean());
        echo 'SUCCESS';
    }
}
