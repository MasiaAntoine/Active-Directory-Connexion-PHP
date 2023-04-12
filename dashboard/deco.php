<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/assets/config/logFunctions.inc.php';

    // Fonction de sérialisation de tableau personnalisée
    function json_encode_no_escape_slash($array) {
        return str_replace('\\/', '/', json_encode($array, JSON_PRETTY_PRINT));
    }

    if(isset($_COOKIE['serveur'])) {
        $serveurLog = $_COOKIE['serveur'];
        $idLog = $_COOKIE['identifiant'];
    }

    //supprimer tout les cookies
    // Parcourt le tableau des cookies
    foreach($_COOKIE as $cookie_name => $cookie_value){

        // Commence par supprimer la valeur du cookie
        unset($_COOKIE[$cookie_name]);
        // Puis désactive le cookie en lui fixant 
        // une date d'expiration dans le passé
        setcookie($cookie_name, '', time() - 4200, '/');
    }

    //Met à jour le fichier JSON sur false pour la déconnexion
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
            //pour les logs
            $serveurLog = $config['serveur'];
            $idLog = $config['identifiant'];

            // Modifier la valeur de la clé actived
            $config['actived'] = false;

            // Convertir le tableau en chaîne JSON personnalisée
            $json = json_encode_no_escape_slash($config);

            // Écrire les données modifiées dans le fichier JSON
            file_put_contents($_SERVER['DOCUMENT_ROOT'].'/config.json', $json);
        }
      }
    
    addLineLog("Active Directory", "Déconnexion de l'active directory <b>$serveurLog</b> avec l'identifiant <b>$idLog</b>", 'deconnexion', date("Y-m-j H:i:s"));
    header('Location: /');
    exit;
?>