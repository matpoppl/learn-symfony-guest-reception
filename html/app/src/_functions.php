<?php

function poka()
{
    echo '<pre style="font:400 16px/1.4 monospace;background:#fff;color:#000;border:1px solid #f00;margin:1px;padding:0.5em 1em;text-align:left;">';

    foreach (func_get_args() as $i => $arg) {
        echo "{$i} ";

        if (is_array($arg) || is_object($arg)) {
            print_r($arg);
        } else {
            var_dump($arg);
        }
    }

    echo '<pre>';
}
