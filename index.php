<?php

require 'vendor/autoload.php';

if (!empty($_POST)) {
    var_dump($_POST);
}

$twig = new Twig_Environment(new Twig_Loader_Filesystem('.'));
echo $twig->render('index.html.twig', [
]);
