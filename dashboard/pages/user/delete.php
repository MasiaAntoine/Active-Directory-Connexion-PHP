<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/assets/config/functions.inc.php';
    session_start();

    deleteUserFROMOU($connection, $_GET['nom'], $_GET['prenom'], $_GET['ou']);
    redirectUrl('/dashboard/pages/user/list.php?ou='.$_GET['ou']);
?>