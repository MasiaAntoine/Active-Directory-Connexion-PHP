<?php 
  include_once $_SERVER['DOCUMENT_ROOT'].'/assets/config/functions.inc.php';
  session_start();

  $ouList = selectAllOU($connection);

  //Créer une OU
  $messageAddOu = "";
  if(isset($_POST['validAddOu'])) {
    $ou = htmlspecialchars(ucfirst(strtolower($_POST['ouAdd'])));
    $error = false;

    if(!$error) {
      if(strlen($ou) <= 3 || strlen($ou) > 50) {
        $error = true;
        $messageAddOu = "L'unité d'organisation doit avoir une longueur de 3 à 50 caractères.";
      }
    }

    if(!$error) {
      $messageAddOu = addOu($connection, $ou);
    }
  }

  //Supprimer une OU
  $messageDeleteOu = "";
  if(isset($_POST['validDeleteOu'])) {
    $ou = htmlspecialchars(ucfirst(strtolower($_POST['ouDelete'])));
    $error = false;

    //Permet de vérifier si l'ou est choisi
    if(!$error) {
      if(strlen($ou) <= 0) {
        $error = true;
      }
    }

    //Permet de vérifier si l'ou existe bien
    if(!$error) {
      if(!in_array($ou, $ouList)) {
        $error = true;
        $messageDeleteOu = "L'unité d'organisation $ou n'existe pas pour être supprimé.";
      }
    }

    if(!$error) {
      $messageDeleteOu = deleteOu($connection, $ou);
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

            <div class="row">
              <div class="col-sm-4 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h5 class="mb-3">Ajouter une OU</h5>
                    <div class="row">
                      <div class="col-8 col-sm-12 col-xl-8 my-auto">
                        <h6 class="text-muted font-weight-normal">
                          <form method="post" class="forms-sample">
                            <div class="form-group">
                              <input type="text" class="form-control" name="ouAdd" placeholder="Nom de l'OU" autocomplete="off">
                            </div>
                            <button type="submit" name="validAddOu" class="btn btn-primary mr-2">Ajouter</button>
                          </form>
                        </h6>
                        <p class="text-danger"><?= $messageAddOu; ?></p>
                      </div>
                      <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
                        <i class="icon-lg mdi mdi-plus-box text-primary ml-auto"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-sm-4 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h5 class="mb-3">Supprimer une OU</h5>
                    <div class="row">
                      <div class="col-8 col-sm-12 col-xl-8 my-auto">
                        <h6 class="text-muted font-weight-normal">
                          <form method="post" class="forms-sample">
                            <div class="form-group">
                              <select class="form-control" name="ouDelete">
                                <option></option>
                                <?php foreach($ouList as $ouName): ?>
                                <option value="<?= $ouName ?>"><?= $ouName ?></option>
                                <?php endforeach; ?>
                              </select>
                            </div>

                            <button type="submit" name="validDeleteOu" class="btn btn-danger mr-2">Supprimer</button>
                          </form>
                        </h6>
                        <p class="text-danger"><?= $messageDeleteOu; ?></p>
                      </div>
                      <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
                        <i class="icon-lg mdi mdi-minus-box text-danger ml-auto"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              
            <?php foreach($ouList as $ou): ?>
              <div class="col-sm-4 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h5>Unité d'Organisation</h5>
                    <div class="row">
                      <div class="col-8 col-sm-12 col-xl-8 my-auto">
                        <div class="d-flex d-sm-block d-md-flex align-items-center">
                          <h2 class="mb-0"><?= $ou ?></h2>
                        </div>
                        <h6 class="text-muted font-weight-normal">
                          <a href="/dashboard/pages/user/list.php?ou=<?= $ou; ?>">
                            <?= recupNumberUserFromOU($connection, $ou); ?> utilisateurs
                          </a>
                        </h6>
                      </div>
                      <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
                        <i class="icon-lg mdi mdi-codepen text-primary ml-auto"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>

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