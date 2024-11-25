<?php

function dd($data)
{
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
    die();
}


function format_date($date, $formatter)
{
    $d = new DateTime($date);
    return $d->format($formatter);
}
