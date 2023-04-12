<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
    <a class="sidebar-brand brand-logo" href="/"><img src="/dashboard/assets/images/logo.svg" alt="logo" /></a>
    <a class="sidebar-brand brand-logo-mini" href="/"><img src="/dashboard/assets/images/logo-mini.svg" alt="logo" /></a>
  </div>
  <ul class="nav">
    <li class="nav-item nav-category">
      <span class="nav-link">Navigation</span>
    </li>

    <li class="nav-item menu-items">
      <a class="nav-link" href="/">
        <span class="menu-icon">
          <i class="mdi mdi-speedometer"></i>
        </span>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>

    <li class="nav-item menu-items">
      <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
        <span class="menu-icon">
          <i class="mdi mdi-laptop"></i>
        </span>
        <span class="menu-title">Utilisateurs</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="ui-basic">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="/dashboard/pages/user/list.php">Liste</a></li>
          <li class="nav-item"> <a class="nav-link" href="/dashboard/pages/user/add.php">Ajouter</a></li>
          <li class="nav-item"> <a class="nav-link" href="/dashboard/pages/user/addList.php">Ajouter une Liste</a></li>
        </ul>
      </div>
    </li>

    <li class="nav-item menu-items">
      <a class="nav-link" href="/dashboard/pages/notifs/notification.php">
        <span class="menu-icon">
          <i class="mdi mdi-bell"></i>
        </span>
        <span class="menu-title">Notifications</span>
      </a>
    </li>

    <li class="nav-item menu-items">
      <a class="nav-link" href="/dashboard/pages/icons/mdi.html">
        <span class="menu-icon">
          <i class="mdi mdi-file-document"></i>
        </span>
        <span class="menu-title">Demo</span>
      </a>
    </li>

  </ul>
</nav>