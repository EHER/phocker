<?php

require 'vendor/autoload.php';

$twig = new Twig_Environment(new Twig_Loader_Filesystem('views'));

if (!empty($_POST)) {
    $zipFile = tempnam(sys_get_temp_dir(), "phansible_bundle_");

    $files['docker-machine.sh'] = $twig->render('docker-machine.sh.twig', $_POST['vagrant_local']);
    $files['docker/web/Dockerfiles'] = $twig->render('docker/web.twig', $_POST);
    $files['docker/php/Dockerfiles'] = $twig->render('docker/php.twig', $_POST);
    $files['docker-compose.yml'] = $twig->render('docker-compose.yml.twig', $_POST);

    $zip = new \ZipArchive();
    $zip->open($zipFile, \ZipArchive::CREATE);
    foreach ($files as $fileName => $content) {
        $zip->addFromString($fileName, $content);
    }
    $zip->close();

    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=\"phocker\.zip\"");
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: ".filesize($zipFile));
    ob_end_flush();
    @readfile($zipFile);
    unlink($zipFile);

    //var_dump($_POST, $files);
    exit;
}

echo $twig->render('index.html.twig');
