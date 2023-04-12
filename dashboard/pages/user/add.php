<?php 
  include_once $_SERVER['DOCUMENT_ROOT'].'/assets/config/functions.inc.php';
  session_start();

  $ouList = selectAllOU($connection);

  //Permet d'ajouter un utilisateur dans une unité d'organisation
  $message = "";

  if(isset($_POST['valid'])) {
    $prenom = htmlspecialchars(ucfirst(strtolower($_POST['name'])));
    $nom = htmlspecialchars(ucfirst(strtolower($_POST['surname'])));
    $mail = htmlspecialchars($_POST['mail']);
    $mobile = htmlspecialchars($_POST['mobile']);
    $description = htmlspecialchars($_POST['description']);
    $password = $_POST['password'];
    $ou = htmlspecialchars($_POST['ou']);

    $error = false;

    //Permet de vérifier les propriétés du prénom
    if(!$error) {
      if(strlen($prenom) < 3 || strlen($prenom) > 50) {
        $error = true;
        $message = "Le prénom doit faire seulement 50 caractères max.";
      }
    }
    //Permet de vérifier les propriétés du nom
    if(!$error) {
      if(strlen($nom) < 3 || strlen($nom) > 50) {
        $error = true;
        $message = "Le nom doit faire seulement 50 caractères max.";
      }
    }
    //Permet de vérifier les propriétés de l'adresse mail
    if(!$error) {
      if(strlen($mail) <= 3 || strlen($mail) > 100) {
        $error = true;
        $message = "L'adresse mail doit faire seulement 100 caractères max.";
      }
    }
    if(!$error) {
      if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $error = true;
        $message = "Adresse email non valide.";
      }      
    }
    //Permet de vérifier les propriétés du numéro mobile
    if(!$error) {
      if(strlen($mobile) != 10) {
        $error = true;
        $message = "Il y a une erreur dans le numéro de téléphone";
      }
    }
    if(!$error) {
      if (!preg_match("/^[0-9]{10}$/", $mobile)) {
        $error = true;
        $message = "Le numéro de téléphone n'est pas valide.";
      }
    }
    //Permet de vérifier les propriétés de la description
    if(!$error) {
      if(strlen($description) <= 3 || strlen($description) > 250) {
        $error = true;
        $message = "La description doit faire seulement 250 caractères max.";
      }
    }
    //Permet de vérifier les propriétés du mot de passe
    if(!$error) {
      if(strlen($password) < 8) {
        $error = true;
        $message = "Le mot de passe doit comporter au moins 8 caractères.";
      }
    }
    if(!$error) {
      if(!preg_match("#[0-9]+#", $password)) {
        $error = true;
        $message = "Le mot de passe doit comporter au moins 1 chiffre.";
      }
    }
    if(!$error) {
      if(!preg_match("#[a-z]+#", $password)) {
        $error = true;
        $message = "Le mot de passe doit comporter au moins 1 lettre minuscule.";
      }
    }
    if(!$error) {
      if(!preg_match("#[A-Z]+#", $password)) {
        $error = true;
        $message = "Le mot de passe doit comporter au moins 1 lettre majuscule.";
      }
    }
    if(!$error) {
      if(!preg_match("#\W+#", $password)) {
        $error = true;
        $message = "Le mot de passe doit comporter au moins 1 caractère spécial.";
      }
    }
    //Permet de vérifier si l'ou existe bien
    if(!$error) {
      if(!in_array($ou, $ouList)) {
        $error = true;
        $message = "La valeur de $ou n'existe pas.";
      }
    }

    //Si il n'y a aucune erreur
    if(!$error) {
      $dcId = $GLOBALS['dcId'];
      $dcDomaine = $GLOBALS['dcDomaine'];

      $dn = "ou=$ou,dc=$dcId,dc=$dcDomaine";
      $user = array();
      $user["cn"] = "$prenom $nom";
      $user["sn"] = "$nom";
      $user["givenName"] = "$prenom";
      $user["mail"] = "$mail";
      $user["mobile"] = "$mobile";
      $user["description"] = "$description"; 
      $user["userPassword"] = "{MD5}$password";

      if($_POST['activAccount'] == "on") {
        $user["UserAccountControl"] = "544";
      }

      $user["objectclass"][0] = "top";
      $user["objectclass"][1] = "person";
      $user["objectclass"][2] = "organizationalPerson";
      $user["objectclass"][3] = "user";
    
      $result = ldap_add($connection, "cn=".$user["cn"].",".$dn, $user);
      if (!$result) {
        $error = ldap_error($connection);
        exit("Adding new user failed: " . $error);
      } else {
        echo 'New user added successfully';
      }
    
      ldap_close($connection);

      addLineLog("Utilisateur", "Ajout de l'utilisateur <b>$nom $prenom</b> à l'unité d'organisation $ou", 'addUser', date("Y-m-j H:i:s"));
      redirectUrl("/dashboard/pages/user/list.php?ou=$ou");
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
                  <h4 class="card-title">Ajoute un utilisateur</h4>
                  <p class="card-description"> Permet d'ajouter un utilisateur dans l'active directory </p>

                  <div class="text-danger mb-3"><?= $message; ?></div>

                  <form method="post" class="forms-sample">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="surname">Nom</label>
                          <input type="text" class="form-control" name="surname" placeholder="Charse">
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="name">Prénom</label>
                          <input type="text" class="form-control" name="name" placeholder="Pascal">
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="email">Adresse Mail</label>
                          <input type="email" class="form-control" name="mail" placeholder="pascal@gmail.com">
                        </div>
                      </div>
                      
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="email">Numéro de téléphone</label>
                          <input type="mobile" class="form-control" name="mobile" placeholder="0615273854">
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="ou">Unité d'Organisation</label>
                      <select class="form-control" name="ou">
                        <?php foreach($ouList as $ouName): ?>
                        <option value="<?= $ouName ?>"><?= $ouName ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>

                    <div class="form-group">
                      <label for="password">Mot de passe</label>
                      <input type="password" class="form-control" name="password" placeholder="XdHp=zo4Da5m">
                    </div>
                    
                    <div class="form-group">
                      <label for="description">Textarea</label>
                      <textarea class="form-control" name="description" placeholder="Compte utilisateur" rows="4"></textarea>
                    </div>

                    <div class="form-check form-check-flat form-check-primary">
                      <label class="form-check-label" for="activAccount">
                      <input type="checkbox" class="form-check-input" name="activAccount"> Activer le compte <i class="input-helper"></i></label>
                    </div>

                    <button type="submit" name="valid" class="btn btn-primary mr-2">Ajouter</button>
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
    <!-- End custom js for this page -->
  </body>
</html>