<?php
    // Fonction pour ajouter une ligne dans le fichier log.txt
    function addLineLog($title, $description, $type, $date) {
        $line =  "\n" . $date . ',' . $title . ',' . $description . ',' . $type. ',*';
        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/log.txt', $line, FILE_APPEND);
    }

    // Fonction pour supprimer une ligne dans le fichier log.txt
    function deleteLineLog($date) {
        $log_file = file($_SERVER['DOCUMENT_ROOT'].'/log.txt');
        $keys = explode(',', array_shift($log_file));
        $log_data = array();

        foreach ($log_file as $line) {
            $line_data = explode(',', $line);
            $log_data[] = array_combine($keys, $line_data);
        }

        foreach ($log_data as $key => $data) {
            if ($data['date'] == $date) {
                unset($log_data[$key]);
            }
        }

        $new_log = implode(',', $keys) . "\n";
        foreach ($log_data as $data) {
            $new_log .= implode(',', $data) . "\n";
        }

        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/log.txt', $new_log);
    }

    // Fonction pour récupérer les données du fichier log.txt
    function recupDataLog($limit = null) {
        $log_file = file($_SERVER['DOCUMENT_ROOT'].'/log.txt');
        $keys = explode(',', array_shift($log_file));
        $log_data = array();
    
        if ($limit !== null && $limit <= count($log_file)) {
            $log_file = array_slice($log_file, 0, $limit);
        }

        foreach ($log_file as $line) {
            $line_data = explode(',', $line);
            $line_data = array_map(function($value) {
                return rtrim($value);
            }, $line_data);
            $log_data[] = array_combine($keys, $line_data);
        }
    
        return $log_data;
    }

    //Permet de mettre à jour l'icon des notif
    function iconNotif($type) {
        if($type == "connexion") {
            $icon = "mdi mdi-lan-connect";
            $color = "primary";
        } 
        if($type == "deconnexion") {
            $icon = "mdi mdi-lan-disconnect";
            $color = "danger";
        } 
        if($type == "addOU") {
            $icon = "mdi mdi-codepen";
            $color = "primary";
        } 
        if($type == "deleteOU") {
            $icon = "mdi mdi-codepen";
            $color = "danger";
        } 
        if($type == "addUser") {
            $icon = "mdi mdi-account-plus";
            $color = "primary";
        } 
        if($type == "editUser") {
            $icon = "mdi mdi-account-star";
            $color = "warning";
        } 
        if($type == "deleteUser") {
            $icon = "mdi mdi-account-minus";
            $color = "danger";
        } 

        $array = [];
        array_push($array, $icon);
        array_push($array, $color);
        return $array;
    }