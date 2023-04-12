<?php
  $notifs = recupDataLog(5);
  $columns = array_column($notifs, 'date');
  array_multisort($columns, SORT_DESC, $notifs);
?>

<nav class="navbar p-0 fixed-top d-flex flex-row">
  <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
    <a class="navbar-brand brand-logo-mini" href="/"><img src="/dashboard/assets/images/logo-mini.svg" alt="logo" /></a>
  </div>
  <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
      <span class="mdi mdi-menu"></span>
    </button>
    <ul class="navbar-nav navbar-nav-right">
      <li class="nav-item dropdown d-none d-lg-block">
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="createbuttonDropdown">
          <h6 class="p-3 mb-0">Projects</h6>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item preview-item">
            <div class="preview-thumbnail">
              <div class="preview-icon bg-dark rounded-circle">
                <i class="mdi mdi-file-outline text-primary"></i>
              </div>
            </div>
            <div class="preview-item-content">
              <p class="preview-subject ellipsis mb-1">Software Development</p>
            </div>
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item preview-item">
            <div class="preview-thumbnail">
              <div class="preview-icon bg-dark rounded-circle">
                <i class="mdi mdi-web text-info"></i>
              </div>
            </div>
            <div class="preview-item-content">
              <p class="preview-subject ellipsis mb-1">UI Development</p>
            </div>
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item preview-item">
            <div class="preview-thumbnail">
              <div class="preview-icon bg-dark rounded-circle">
                <i class="mdi mdi-layers text-danger"></i>
              </div>
            </div>
            <div class="preview-item-content">
              <p class="preview-subject ellipsis mb-1">Software Testing</p>
            </div>
          </a>
          <div class="dropdown-divider"></div>
          <p class="p-3 mb-0 text-center">See all projects</p>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
          <i class="mdi mdi-bell"></i>
          <span class="count bg-danger"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
          <h6 class="p-3 mb-0">Notifications</h6>

          <?php foreach($notifs as $notif): ?>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item preview-item">
            <?php 
              $iconValue = iconNotif($notif['type']);
            ?>
            <div class="preview-thumbnail">
              <div class="preview-icon bg-dark rounded-circle">
                <i class="<?= $iconValue[0] ?> text-<?= $iconValue[1] ?>"></i>
              </div>
            </div>

            <div class="preview-item-content">
              <p class="preview-subject mb-1"><?= $notif['title'] ?></p>
              <p class="text-muted ellipsis mb-0"><?= $notif['description'] ?></p>
            </div>
          </a>
          <?php endforeach; ?>

          <div class="dropdown-divider"></div>
          <p class="p-3 mb-0 text-center">
            <a href="/dashboard/pages/notifs/notification.php">
            Voir toutes les notifications
            </a>
          </p>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link" id="profileDropdown" href="#" data-toggle="dropdown">
          <div class="navbar-profile">
            <!-- <img class="img-xs rounded-circle" src="/images/profil/"> -->
            <p class="mb-0 d-none text-capitalize d-sm-block navbar-profile-name">Menu</p>
            <i class="mdi mdi-menu-down d-none d-sm-block"></i>
          </div>
        </a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="profileDropdown">
          <h6 class="p-3 mb-0">Profil</h6>
          <!-- <div class="dropdown-divider"></div>
          <a class="dropdown-item preview-item">
            <div class="preview-thumbnail">
              <div class="preview-icon bg-dark rounded-circle">
                <i class="mdi mdi-settings text-success"></i>
              </div>
            </div>
            <div class="preview-item-content">
              <p class="preview-subject mb-1">Paramètres</p>
            </div>
          </a> -->
          <div class="dropdown-divider"></div>
          <a href="/dashboard/deco.php" name="deco" class="dropdown-item preview-item">
            <div class="preview-thumbnail">
              <div class="preview-icon bg-dark rounded-circle">
                <i class="mdi mdi-logout text-danger"></i>
              </div>
            </div>
            <div class="preview-item-content">
              <p class="preview-subject mb-1" onclick="location.href = '/deco.php';">Déconnexion</p>
            </div>
          </a>
          <!-- <div class="dropdown-divider"></div>
          <p class="p-3 mb-0 text-center">Advanced settings</p> -->
        </div>
      </li>
    </ul>
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
      <span class="mdi mdi-format-line-spacing"></span>
    </button>
  </div>
</nav>