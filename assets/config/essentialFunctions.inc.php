<?php
    include $_SERVER['DOCUMENT_ROOT'].'/assets/config/loginDB.inc.php';
    include $_SERVER['DOCUMENT_ROOT'].'/assets/config/global.inc.php';
    
    //Connexion en base de donnée
    $connection = ldap_connect($server, $port);
    if (!$connection) {
        exit('Connection failed');
    }
    ldap_set_option($connection , LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($connection , LDAP_OPT_REFERRALS, 0);
    $bind = @ldap_bind($connection, $username.$domain, $password);
    if (!$bind) {
        if (file_exists('config.json')) {
            // Le fichier existe
            // Lire les données du fichier JSON
            $json = file_get_contents('config.json');
    
            // Convertir les données JSON en tableau PHP
            $config = json_decode($json, true);
    
            // Accéder aux valeurs dans le tableau
            $active = $config['actived'];
            
            //Vérifier si la variable est activé pour utiliser le fichier json
            if($active) {
                redirectUrl('/error.php');
            } else {
                redirectUrl('/dashboard/deco.php');
            }
        } else {
            redirectUrl('/dashboard/deco.php');
        }
    }

    //Verifier si l'utilisateur est connectée
    function isConnect() {
        if(!isset($_SESSION['idUser'])) {
            redirectUrl("/login");
        }
    }

    //Redirection d'url
    function redirectUrl($url)
    {
        header('Location: ' . $url);
        exit;
    }

    //Modifier la date de la database
    function MyDate($date) { 
        $jour = substr($date, 8, 2);
        $mois = substr($date, 5, 2);
        $annee = substr($date, 0, 4);

        if($mois == "01") {
            $mois = "janv";
        }
        if($mois == "02") {
            $mois = "fev";
        }
        if($mois == "03") {
            $mois = "mars";
        }
        if($mois == "04") {
            $mois = "avril";
        }
        if($mois == "05") {
            $mois = "mai";
        }
        if($mois == "06") {
            $mois = "juin";
        }
        if($mois == "07") {
            $mois = "juil";
        }
        if($mois == "08") {
            $mois = "août";
        }
        if($mois == "09") {
            $mois = "sept";
        }
        if($mois == "10") {
            $mois = "oct";
        }
        if($mois == "11") {
            $mois = "nov";
        }
        if($mois == "12") {
            $mois = "dec";
        }

        return $jour . " " . $mois . " " . $annee;
    }

    //compte le nombre de chiffre dans une chaine.
    function verifInt($chain) {
        $chiffre = 0;
        for($i=0;$i<strlen($chain);$i++) {
            for($c=0;$c<=9;$c++) {
                if($chain[$i] === (string)$c) {
                    $chiffre++;
                    break;
                }
            }
        }
        return $chiffre;
    }

    //couper une phrase de mot en mot sans couper au milieu
    function cutPhrase($string, $limit = 5, $fin = ' ...')
    {
        preg_match('/^\s*+(?:\S++\s*+){1,' .$limit. '}/u', $string, $matches);
        if (!isset($matches[0]) || strlen($string) === strlen($matches[0])) {
            return $string.$fin;
        }
        return rtrim($matches[0]).$fin;
    }

    //vérifier l'adresse ip de l'utilisateur correctement
    function getIp() {
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
          $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
          $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
          $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
      }

    //Voir l'url de la page
    function getUrl() {
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
        {
            $url = "https";
        }
        else
        {
            $url = "http"; 
        }  
        $url .= "://"; 
        $url .= $_SERVER['HTTP_HOST']; 
        $url .= $_SERVER['REQUEST_URI']; 
        return $url; 
    }

    //Retire des jours à uen date
    function date_outil($date,$nombre_jour) {
 
        $year = substr($date, 0, -6);   
        $month = substr($date, -5, -3);   
        $day = substr($date, -2);   
     
        // récupère la date du jour
        $date_string = mktime(0,0,0,$month,$day,$year);
     
        // Supprime les jours
        $timestamp = $date_string - ($nombre_jour * 86400);
        $nouvelle_date = date("Y-m-d", $timestamp); 
     
        // pour afficher
       return $nouvelle_date;
     
        }

    //Permet de récupérer les données d'un fichier CSV
    function convertCSVToArray($linkFile) {
        $file = fopen($_SERVER['DOCUMENT_ROOT'].$linkFile, 'r');
        $data = array();
        $headers = array();

        // Récupérer les en-têtes à partir de la première ligne
        if (($headers = fgetcsv($file)) !== FALSE) {
        // Ignorer la première ligne
        }

        while (($line = fgetcsv($file)) !== FALSE) {
        // Construire un tableau associatif pour chaque ligne
        $data[] = array_combine($headers, $line);
        }

        fclose($file);
        
        return $data;
    }