<?php

    //Cette fonction permet la connexion via les cookies.
    function connexionToAddWithCookie() {
        //Utilise les cookie pour la connexion
        if(!isset($_COOKIE['serveur'])) {
            redirectUrl("/connexion.php");
        } else {
            $server = $_COOKIE['serveur'];
            $domain = $_COOKIE['domaine'];
            $port = $_COOKIE['port'];
            $username = $_COOKIE['identifiant'];
            $password = $_COOKIE['motDePasse'];

            $dcId = substr(explode('.', $_COOKIE['domaine'])[0], 1);
            $dcDomaine = explode('.', $_COOKIE['domaine'])[1];

            $returnArrayData = [];
            array_push($returnArrayData, $server);
            array_push($returnArrayData, $domain);
            array_push($returnArrayData, $port);
            array_push($returnArrayData, $username);
            array_push($returnArrayData, $password);
            array_push($returnArrayData, $dcId);
            array_push($returnArrayData, $dcDomaine);
            
            return($returnArrayData);
        }
        
    }

    //Si le fichier est détecté est activé l'utiliser pour se connecter
    //Vérifier si le fichier de config json existe
    if (file_exists($_SERVER['DOCUMENT_ROOT'].'/config.json')) {
        // Le fichier existe
        // Lire les données du fichier JSON
        $json = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/config.json');
  
        // Convertir les données JSON en tableau PHP
        $config = json_decode($json, true);
  
        // Accéder aux valeurs dans le tableau
        $active = $config['actived'];
        
        //Vérifier si la variable est activé pour utiliser le fichier json
        if($active) {
            $server = $config['serveur'];
            $domain = $config['domaine'];
            $port = $config['port'];
            $username = $config['identifiant'];
            $password = $config['motDePasse'];

            $dcId = substr(explode('.', $config['domaine'])[0], 1);
            $dcDomaine = explode('.', $config['domaine'])[1];
        } else {
            //utilise les cookies pour se connecter
            $config = connexionToAddWithCookie();

            $server = $config[0];
            $domain = $config[1];
            $port = $config[2];
            $username = $config[3];
            $password = $config[4];

            $dcId = substr(explode('.', $config[1])[0], 1);
            $dcDomaine = explode('.', $config[1])[1];
        }
    } else {
        //utilise les cookies pour se connecter
        $config = connexionToAddWithCookie();

        $server = $config[0];
        $domain = $config[1];
        $port = $config[2];
        $username = $config[3];
        $password = $config[4];

        $dcId = substr(explode('.', $config[1])[0], 1);
        $dcDomaine = explode('.', $config[1])[1];
    }