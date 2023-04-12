<?php 
  include_once $_SERVER['DOCUMENT_ROOT'].'/assets/config/functions.inc.php';
  session_start();

  $ouList = selectAllOU($connection);

  //Permet d'ajouter un utilisateur dans une unité d'organisation
  $message = "";

  if(isset($_POST['valid'])) {
    $error = false;
    $ou = htmlspecialchars($_POST['ou']);

    //Récupère le fichier CSV
    $file = $_FILES['csv'];
    $file_tmp = $file['tmp_name'][0];
    $file_size = $file['size'][0];
    $file_type = $file['type'][0];
    $file_error = $file['error'][0];
    
    // Vérification de l'erreur
    if(!$error) {
        if ($file_error !== 0) {
            $error = true;
            $message = "Erreur lors du téléchargement du fichier.";
        }
    }
    
    // Vérification de la taille
    if(!$error) {
        if ($file_size > 1000000) {
            $error = true;
            $message = "La taille du fichier ne doit pas dépasser 1 MB.";
        }
    }
    
    // Vérification du type de fichier
    if(!$error) {
        $allowed_types = ['text/csv'];
        if (!in_array($file_type, $allowed_types)) {
            $error = true;
            $message = "Le type de fichier n'est pas autorisé.";
        }
    }
    
    //Si tout se passe bien pour le fichier passer à la suite des vérifications
    if(!$error) {
        // Envoi du fichier csv
        move_uploaded_file($file_tmp, $_SERVER['DOCUMENT_ROOT'].'/listUser.csv');
        //Récupère les donnée du fichier CSV
        $listCSVUser = convertCSVToArray('/listUser.csv');
        // Supprime le fichier CSV
        unlink($_SERVER['DOCUMENT_ROOT'].'/listUser.csv');

        //vérifier la liste des utilisateurs à ajouter pour vérifier si chaque utilisateur va bien
        foreach($listCSVUser as $user) {
            $prenom = htmlspecialchars(ucfirst(strtolower($user['name'])));
            $nom = htmlspecialchars(ucfirst(strtolower($user['firstName'])));
            $mail = htmlspecialchars($user['mail']);
            $mobile = htmlspecialchars($user['mobile']);
            $description = htmlspecialchars($user['description']);
            $password = $user['password'];
        
            //Permet de vérifier les propriétés du prénom
            if(!$error) {
            if(strlen($prenom) < 3 || strlen($prenom) > 50) {
                $error = true;
                $message = "Le prénom $prenom doit faire seulement 50 caractères max.";
            }
            }
            //Permet de vérifier les propriétés du nom
            if(!$error) {
            if(strlen($nom) < 3 || strlen($nom) > 50) {
                $error = true;
                $message = "Le nom $nom doit faire seulement 50 caractères max.";
            }
            }
            //Permet de vérifier les propriétés de l'adresse mail
            if(!$error) {
            if(strlen($mail) <= 3 || strlen($mail) > 100) {
                $error = true;
                $message = "L'adresse email $mail doit faire seulement 100 caractères max.";
            }
            }
            if(!$error) {
            if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                $error = true;
                $message = "Adresse email $mail non valide.";
            }      
            }
            //Permet de vérifier les propriétés du numéro mobile
            if(!$error) {
            if(strlen($mobile) != 10) {
                $error = true;
                $message = "Il y a une erreur dans le numéro de téléphone $mobile";
            }
            }
            if(!$error) {
            if (!preg_match("/^[0-9]{10}$/", $mobile)) {
                $error = true;
                $message = "Le numéro de téléphone $mobile n'est pas valide.";
            }
            }
            //Permet de vérifier les propriétés de la description
            if(!$error) {
            if(strlen($description) <= 3 || strlen($description) > 250) {
                $error = true;
                $message = "La description de $nom $prenom doit faire seulement 250 caractères max.";
            }
            }
            //Permet de vérifier les propriétés du mot de passe
            if(!$error) {
            if(strlen($password) < 8) {
                $error = true;
                $message = "Le mot de passe de $nom $prenom doit comporter au moins 8 caractères.";
            }
            }
            if(!$error) {
            if(!preg_match("#[0-9]+#", $password)) {
                $error = true;
                $message = "Le mot de passe de $nom $prenom doit comporter au moins 1 chiffre.";
            }
            }
            if(!$error) {
            if(!preg_match("#[a-z]+#", $password)) {
                $error = true;
                $message = "Le mot de passe de $nom $prenom doit comporter au moins 1 lettre minuscule.";
            }
            }
            if(!$error) {
            if(!preg_match("#[A-Z]+#", $password)) {
                $error = true;
                $message = "Le mot de passe de $nom $prenom doit comporter au moins 1 lettre majuscule.";
            }
            }
            if(!$error) {
            if(!preg_match("#\W+#", $password)) {
                $error = true;
                $message = "Le mot de passe de $nom $prenom doit comporter au moins 1 caractère spécial.";
            }
            }
            //Permet de vérifier si l'ou existe bien
            if(!$error) {
            if(!in_array($ou, $ouList)) {
                $error = true;
                $message = "La valeur de $ou n'existe pas.";
            }
            }
        }

        //Si il n'y a aucune erreur
        if(!$error) {
            $dcId = $GLOBALS['dcId'];
            $dcDomaine = $GLOBALS['dcDomaine'];

            $dn = "ou=$ou,dc=$dcId,dc=$dcDomaine";

            //Permet d'ajouter les utilisateurs
            foreach($listCSVUser as $user) {
                $prenom = htmlspecialchars(ucfirst(strtolower($user['name'])));
                $nom = htmlspecialchars(ucfirst(strtolower($user['firstName'])));
                $mail = htmlspecialchars($user['mail']);
                $mobile = htmlspecialchars($user['mobile']);
                $description = htmlspecialchars($user['description']);
                $password = $user['password'];
                $activAccount = filter_var($user['activate'], FILTER_VALIDATE_BOOLEAN);

                $user = array();
                $user["cn"] = "$prenom $nom";
                $user["sn"] = "$nom";
                $user["givenName"] = "$prenom";
                $user["mail"] = "$mail";
                $user["mobile"] = "$mobile";
                $user["description"] = "$description"; 
                $user["userPassword"] = "{MD5}$password";

                if($activAccount) {
                    $user["UserAccountControl"] = "544";
                }

                $user["objectclass"][0] = "top";
                $user["objectclass"][1] = "person";
                $user["objectclass"][2] = "organizationalPerson";
                $user["objectclass"][3] = "user";
                
                $result = ldap_add($connection, "cn=".$user["cn"].",".$dn, $user);

                addLineLog("Utilisateur", "Ajout de l'utilisateur <b>$nom $prenom</b> à l'unité d'organisation $ou", 'addUser', date("Y-m-j H:i:s"));
            }

            ldap_close($connection);
            redirectUrl("/dashboard/pages/user/list.php?ou=$ou");
        }
    }
  }
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Corona Admin</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="/dashboard/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="/dashboard/assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="/dashboard/assets/vendors/jvectormap/jquery-jvectormap.css">
    <link rel="stylesheet" href="/dashboard/assets/vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="/dashboard/assets/vendors/owl-carousel-2/owl.carousel.min.css">
    <link rel="stylesheet" href="/dashboard/assets/vendors/owl-carousel-2/owl.theme.default.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="/dashboard/assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="/dashboard/assets/images/favicon.png" />
  </head>
  <body>
    <div class="container-scroller">
      <!-- partial:partials/_sidebar.html -->
      <?php include_once $_SERVER['DOCUMENT_ROOT'].'/dashboard/partials/_sidebar.php'; ?>

      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_navbar.html -->
        <?php include_once $_SERVER['DOCUMENT_ROOT'].'/dashboard/partials/_navbar.php'; ?>

        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Ajoute une liste d'utilisateurs</h4>
                  <p class="card-description"> Permet d'ajouter une liste d'utilisateurs dans l'active directory </p>

                  <div class="text-danger mb-3"><?= $message; ?></div>

                  <form method="post" class="forms-sample" enctype="multipart/form-data">
                   
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                        <label for="ou">Unité d'Organisation</label>
                        <select class="form-control" name="ou">
                            <?php foreach($ouList as $ouName): ?>
                            <option value="<?= $ouName ?>"><?= $ouName ?></option>
                            <?php endforeach; ?>
                        </select>
                        </div>
                      </div>

                      <div class="col-md-6">

                        <div class="form-group">
                            <label>Liste d'utilisateur</label>
                            <input type="file" name="csv[]" class="file-upload-default" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
                            <div class="input-group col-xs-12">
                            <input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload CSV">
                            <span class="input-group-append">
                                <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                            </span>
                            </div>
                        </div>
                      </div>
                    </div>

                    <button type="submit" name="valid" class="btn btn-primary mr-2">Ajouter</button>
                    <button class="btn btn-success mr-2" id="downloadButton">Télécharger un exemple</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
            <?php include_once $_SERVER['DOCUMENT_ROOT'].'/dashboard/partials/_footer.php'; ?>
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="/dashboard/assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="/dashboard/assets/vendors/chart.js/Chart.min.js"></script>
    <script src="/dashboard/assets/vendors/progressbar.js/progressbar.min.js"></script>
    <script src="/dashboard/assets/vendors/jvectormap/jquery-jvectormap.min.js"></script>
    <script src="/dashboard/assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <script src="/dashboard/assets/vendors/owl-carousel-2/owl.carousel.min.js"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="/dashboard/assets/js/off-canvas.js"></script>
    <script src="/dashboard/assets/js/hoverable-collapse.js"></script>
    <script src="/dashboard/assets/js/misc.js"></script>
    <script src="/dashboard/assets/js/settings.js"></script>
    <script src="/dashboard/assets/js/todolist.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="/dashboard/assets/js/dashboard.js"></script>
    <script src="/dashboard/assets/js/file-upload.js"></script>
    <!-- End custom js for this page -->
    <script>
        document.getElementById("downloadButton").addEventListener("click", function(){
            var link = document.createElement("a");
            link.download = "exempleListCSV.csv";
            link.href = "/assets/exempleListCSV.csv";
            link.click();
        });
    </script>
  </body>
</html>