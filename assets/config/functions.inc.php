<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/assets/config/essentialFunctions.inc.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/assets/config/logFunctions.inc.php';

    //Permet de récupérer la liste des unités d'organisation
    function selectAllOU($connection) {
        $dcId = $GLOBALS['dcId'];
        $dcDomaine = $GLOBALS['dcDomaine'];

        $ldap_ou_search = ldap_search($connection, "dc=$dcId,dc=$dcDomaine", "(objectClass=organizationalUnit)");
        $ldap_ous = ldap_get_entries($connection, $ldap_ou_search);
        
        $ouList = [];
        for ($i=0; $i<$ldap_ous["count"]; $i++) {
            if($ldap_ous[$i]["ou"][0] != "Domain Controllers") {
                array_push($ouList, $ldap_ous[$i]["ou"][0]);
            }
        }

        return $ouList;
    }

    //Permet de récupérer le nomrbe d'utilisateur dans une ou
    function recupNumberUserFromOU($connection, $ou) {
        $dcId = $GLOBALS['dcId'];
        $dcDomaine = $GLOBALS['dcDomaine'];

        $base_dn = "OU=$ou,DC=$dcId,DC=$dcDomaine";
        $filter = "(&(objectCategory=person)(objectClass=user))";
        $attributes = array("displayname");
    
        $result = ldap_search($connection, $base_dn, $filter, $attributes);
        $entries = ldap_get_entries($connection, $result);
        return $entries['count'];
    }

    //Permet de supprimer un utilisateur
    function deleteUserFROMOU($connection, $nom, $prenom, $ou) {
        $dcId = $GLOBALS['dcId'];
        $dcDomaine = $GLOBALS['dcDomaine'];

        $dn = "cn=$nom $prenom,ou=$ou,dc=$dcId,dc=$dcDomaine";
        $result = ldap_delete($connection, $dn);
        if (!$result) {
            $error = ldap_error($connection);
            exit("Deleting user failed: " . $error);
        } else {
            addLineLog("Utilisateur", "Suppression de l'utilisateur <b>$nom $prenom</b> de l'unité d'organisation $ou", 'deleteUser', date("Y-m-j H:i:s"));
            echo 'User deleted successfully';
        }
    }

    //Permet de récupérer les données d'un utilisateur
    function recupDataUser($connection, $nom, $prenom, $ou) {
        $dcId = $GLOBALS['dcId'];
        $dcDomaine = $GLOBALS['dcDomaine'];
  
        $dn = "cn=$prenom $nom,ou=$ou,dc=$dcId,dc=$dcDomaine";
        $search_filter = "(cn=$prenom $nom)";
        $attributes = array("givenname", "sn", "mail", "mobile", "description", "useraccountcontrol");
        $result = ldap_search($connection, $dn, $search_filter, $attributes);
        if (!$result) {
            $error = ldap_error($connection);
            exit("Search failed: " . $error);
        }
        $entries = ldap_get_entries($connection, $result);
        if ($entries['count'] > 0) {
            return $entries;
        } else {
            return false;
        }
    }

    //Permet d'ajouter une OU
    function addOu($connection, $ou) {
        $dcId = $GLOBALS['dcId'];
        $dcDomaine = $GLOBALS['dcDomaine'];
  
        $dn = "ou=$ou,dc=$dcId,dc=$dcDomaine";
        $entry = array();
        $entry["objectclass"][0] = "organizationalUnit";
        $entry["ou"] = "$ou";
        $result = ldap_add($connection, $dn, $entry);
        if (!$result) {
            $error = ldap_error($connection);
            return "L'ajout de l'unité d'organisation a échoué : " . $error;
        } else {
            addLineLog("Unité d'Organisation", "Ajout de l'unité d'organisation <b>$ou</b>", 'addOU', date("Y-m-j H:i:s"));
            redirectUrl('/dashboard/index.php#ou');
        }
    }

    //Permet de supprimer une OU
    function deleteOu($connection, $ou) {
        $dcId = $GLOBALS['dcId'];
        $dcDomaine = $GLOBALS['dcDomaine'];
  
        $dn = "ou=$ou,dc=$dcId,dc=$dcDomaine";
        $result = ldap_delete($connection, $dn);
        if (!$result) {
            $error = ldap_error($connection);
            return "La suppression de l'unité d'organisation a échoué : " . $error;
        } else {
            addLineLog("Unité d'Organisation", "Suppression de l'unité d'organisation <b>$ou</b>", 'deleteOU', date("Y-m-j H:i:s"));
            redirectUrl('/dashboard/index.php#ou');
        }
    }

    //Permet de récupérer la liste des utilisateurs
    function recupAllUser($connection) {
        $dcId = $GLOBALS['dcId'];
        $dcDomaine = $GLOBALS['dcDomaine'];
            
        $base_dn = "DC=$dcId,DC=$dcDomaine";
        $filter = "(&(objectCategory=person)(objectClass=user))";
        $attributes = array("displayname", "mail", "mobile", "description", "useraccountcontrol");

        $result = ldap_search($connection, $base_dn, $filter, $attributes);
        return ldap_get_entries($connection, $result);
    }
