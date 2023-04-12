<?php 
  include_once $_SERVER['DOCUMENT_ROOT'].'/assets/config/functions.inc.php';
  session_start();

  $allNotifPageNotif = recupDataLog();
  
  $columns = array_column($allNotifPageNotif, 'date');
  array_multisort($columns, SORT_DESC, $allNotifPageNotif);
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
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex flex-row justify-content-between">
                      <h4 class="card-title mb-1">Notifications</h4>
                      <!-- <p class="text-muted mb-1">Your data status</p> -->
                    </div>
                    <div class="row">
                      <div class="col-12">
                        <div class="preview-list">

                        <?php foreach($allNotifPageNotif as $notif): ?>
                            <?php 
                              $iconValue = iconNotif($notif['type']);
                            ?>
                          <div class="preview-item border-bottom">
                            <div class="preview-thumbnail">
                              <div class="preview-icon bg-<?= $iconValue[1] ?>">
                                <i class="<?= $iconValue[0] ?>"></i>
                              </div>
                            </div>
                            <div class="preview-item-content d-sm-flex flex-grow">
                              <div class="flex-grow">
                                <h6 class="preview-subject"><?= $notif['title'] ?></h6>
                                <p class="text-muted mb-0"><?= $notif['description'] ?></p>
                              </div>
                              <div class="mr-auto text-sm-right pt-2 pt-sm-0">
                                <p class="text-muted"><?= $notif['date'] ?></p>
                              </div>
                            </div>
                          </div>
                        <?php endforeach; ?>

                        </div>
                      </div>
                    </div>
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