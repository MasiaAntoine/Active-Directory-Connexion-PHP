<?php 
  include_once $_SERVER['DOCUMENT_ROOT'].'/assets/config/functions.inc.php';
  session_start();

  // Récupération de la liste des utilisateurs
  $data = recupAllUser($connection);
  
  //Ferme la connexion avec la ldap
  ldap_close($connection);
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
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Liste des utilisateurs</h4>
                    <p class="card-description"> Vous permet de voir l'ensemble des utilisateurs et leurs données de contact<?php if(!isset($_GET["ou"])) {echo(".");} ?>  
                    <?php if(isset($_GET["ou"])) {echo("de l'unité d'organisation ". $_GET["ou"]) . ".";} ?>
                    </p>
                    <div class="table-responsive">
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <?php if(isset($_GET["ou"])): ?>
                              <th> </th>
                            <?php endif; ?>
                            <th>#</th>
                            <th>Nom</th>
                            <?php if(!isset($_GET["ou"])): ?>
                              <th>Unité d'organisation</th>
                            <?php endif; ?>
                            <th>Description</th>
                            <th>Mail</th>
                            <th>Telephone</th>
                          </tr>
                        </thead>
                        <tbody>
                          
                        <?php foreach($data as $d): ?>
                          <?php if(isset($d["dn"])): ?>
                            <?php $ou = explode("=", explode(",", $d["dn"])[1])[1]; ?>
                              <?php if($ou != "Users"): ?>
                                <?php if(isset($_GET["ou"])): ?>
                                  <?php if($_GET["ou"] == $ou): ?>
                                    <tr>
                                      <td>
                                        <?php 
                                          $nameComposed = explode("=", explode(",", $d["dn"])[0])[1];
                                          $name = explode(" ", $nameComposed)[1];
                                          $surname = explode(" ", $nameComposed)[0];
                                        ?>

                                        <i class="mdi mdi-pencil-box icon-md text-primary" onclick="location.href = '/dashboard/pages/user/edit.php?ou=<?= $ou ?>&prenom=<?= $surname ?>&nom=<?= $name ?>';"></i>
                                        <i class="mdi mdi-delete-forever icon-md text-danger" onclick="location.href = '/dashboard/pages/user/delete.php?ou=<?= $ou ?>&prenom=<?= $name ?>&nom=<?= $surname ?>';"></i>
                                      </td>
                                      <td>
                                        <?php if(isset($d["useraccountcontrol"][0])) {echo $d["useraccountcontrol"][0];} else {echo "null";} ?>
                                      </td>
                                      <td>
                                          <?php 
                                              if(isset($d["dn"])) {
                                                  echo explode("=", explode(",", $d["dn"])[0])[1];
                                              } else {
                                                  echo "null";
                                              } 
                                          ?>
                                      </td>
                                      <td><?php if(isset($d["description"][0])) {echo $d["description"][0];} else {echo "null";} ?></td>
                                      <td><?php if(isset($d["mail"][0])) {echo $d["mail"][0];} else {echo "null";} ?></td>
                                      <td><?php if(isset($d["mobile"][0])) {echo wordwrap($d["mobile"][0],2," ",1);} else {echo "null";} ?></td>
                                    </tr>
                                  <?php endif; ?>
                                <?php elseif(!isset($_GET["ou"])): ?>
                                    <tr>
                                      <td>
                                        <?php if(isset($d["useraccountcontrol"][0])) {echo $d["useraccountcontrol"][0];} else {echo "null";} ?>
                                      </td>
                                      <td>
                                          <?php 
                                              if(isset($d["dn"])) {
                                                  echo explode("=", explode(",", $d["dn"])[0])[1];
                                              } else {
                                                  echo "null";
                                              } 
                                          ?>
                                      </td>
                                      <td>
                                          <?php 
                                              if(isset($d["dn"])) {
                                                  echo "<a href='/dashboard/pages/user/list.php?ou=$ou'>$ou</a>";
                                              } else {
                                                  echo "null";
                                              } 
                                          ?>
                                      </td>
                                      <td><?php if(isset($d["description"][0])) {echo $d["description"][0];} else {echo "null";} ?></td>
                                      <td><?php if(isset($d["mail"][0])) {echo $d["mail"][0];} else {echo "null";} ?></td>
                                      <td><?php if(isset($d["mobile"][0])) {echo wordwrap($d["mobile"][0],2," ",1);} else {echo "null";} ?></td>
                                    </tr>
                                  <?php endif; ?>
                                <?php endif; ?>
                              <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>

                    <div id="bouton" class="mt-4">
                      <?php if(isset($_GET["ou"])): ?>
                        <button class="btn btn-primary" onclick="location.href = '/dashboard/pages/user/list.php';">Reset filtre</button>
                      <?php endif; ?>
                    </div>

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