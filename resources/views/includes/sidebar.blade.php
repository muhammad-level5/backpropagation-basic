<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
      <div class="sidebar-brand-text mx-3">Backpropagation Basic</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
      <a class="nav-link" href="{{ url('') }}">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Dashboard</span></a>
    </li>

    
    <!-- Nav Item - Tables -->
    <li class="nav-item">
      <a class="nav-link" href="{{ route('data.index') }}">
        <i class="fas fa-fw fa-table"></i>
        <span>Data</span></a>
    </li>

    <!-- Nav Item - Charts -->
    <li class="nav-item">
      <a class="nav-link" href="{{ route('backpropagation') }}">
        <i class="fas fa-chart-line"></i>
        <span>Training Data</span></a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('prediction') }}">
        <i class="fas fa-atlas"></i>
        <span>Prediction</span></a>
    </li>


    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
      <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

  </ul>