<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/assets/config/logFunctions.inc.php';
    session_start();

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
        $serveurLog = $config['serveur'];
        $idLog = $config['identifiant'];
        addLineLog("Active Directory", "Connexion à l'active directory depuis un fichier JSON à <b>$serveurLog</b> avec l'identifiant <b>$idLog</b>", 'connexion', date("Y-m-j H:i:s"));

        header('Location: /');
        exit;
      }
    }

    //Si l'utilisateur se connecte
    if(isset($_POST['valid'])) {
        setcookie('serveur', $_POST['serveur'], time()+3600*24, '/', '', true, true);
        setcookie('domaine', $_POST['domaine'], time()+3600*24, '/', '', true, true);
        setcookie('port', $_POST['port'], time()+3600*24, '/', '', true, true);
        setcookie('identifiant', $_POST['identifiant'], time()+3600*24, '/', '', true, true);
        setcookie('motDePasse', $_POST['motDePasse'], time()+3600*24, '/', '', true, true);
        
        $serveurLog = $_POST['serveur'];
        $idLog = $_POST['identifiant'];
        addLineLog("Active Directory", "Connexion à l'active directory <b>$serveurLog</b> avec l'identifiant <b>$idLog</b>", 'connexion', date("Y-m-j H:i:s"));
        header('Location: /');
        exit;
    }

    if(isset($_COOKIE['serveur'])) {
        header('Location: /');
        exit;
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
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="row w-100 m-0">
          <div class="content-wrapper full-page-wrapper d-flex align-items-center auth login-bg">
            <div class="card col-lg-4 mx-auto">
              <div class="card-body px-5 py-5">
                <h3 class="card-title text-left mb-3">Connexion</h3>
                <form method="post">
                    <div class="form-group">
                        <label>Serveur</label>
                        <input type="text" name="serveur" class="form-control p_input">
                    </div>

                    <div class="form-group">
                        <label>Domaine</label>
                        <input type="text" name="domaine" class="form-control p_input">
                    </div>

                    <div class="form-group">
                        <label>Port</label>
                        <input type="text" name="port" class="form-control p_input">
                    </div>

                    <div class="form-group">
                        <label>Identifiant</label>
                        <input type="text" name="identifiant" class="form-control p_input">
                    </div>

                    <div class="form-group">
                        <label>Mot de passe</label>
                        <input type="password" name="motDePasse" class="form-control p_input">
                    </div>

                    <div class="text-center">
                        <button type="submit" name="valid" class="btn btn-primary btn-block enter-btn">Connexion</button>
                    </div>
                </form>
              </div>
            </div>
          </div>
          <!-- content-wrapper ends -->
        </div>
        <!-- row ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="/dashboard/assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="/dashboard/assets/js/off-canvas.js"></script>
    <script src="/dashboard/assets/js/hoverable-collapse.js"></script>
    <script src="/dashboard/assets/js/misc.js"></script>
    <script src="/dashboard/assets/js/settings.js"></script>
    <script src="/dashboard/assets/js/todolist.js"></script>
    <!-- endinject -->
    <script>
      setInterval(function() {
          fetch('/config.json')
              .then(response => response.json())
              .then(config => {
                  if (config.actived === true) {
                      window.location.href = "/connexion.php";
                  }
              })
              .catch(error => console.error(error));
      }, 1000);
    </script>
  </body>
</html>