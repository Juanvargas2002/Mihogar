
<style>
    /* When the sidebar is collapsed/toggled, hide the full brand text to prevent overlap */
    .sidebar.toggled .brand-full,
    #accordionSidebar.toggled .brand-full,
    body.sidebar-toggled .brand-full {
        display: none !important;
    }
</style>

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center my-1" href="<?= SERVER_URL; ?>panel/">
        <div style="background:#ffffff;padding:5px 10px;border-radius:8px;display:flex;align-items:center;gap:.5rem;box-shadow:0 1px 2px rgba(0,0,0,0.05);">
            <img src="<?php echo SERVER_URL ?>vistas/img/logo.png" alt="<?= COMPANY;?>" style="width:3.75rem;height:3.75rem;object-fit:contain;">
            <span class="brand-full d-none d-md-inline" style="color:#b71c1c;font-weight:800;font-size:1rem;line-height:1;">Servicios Tecnicos Mi Hogar</span>
        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Panel -->
    <li class="nav-item active">
        <a class="nav-link" href="<?= SERVER_URL; ?>panel/">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Panel</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <?php if ($_SESSION['rol_usuario'] == "Administrador") { ?>
        
    <!-- Nav Item - Usuarios collapse Menu-->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUsuarios"
            aria-expanded="true" aria-controls="collapseUsuarios">
            <i class="fas fa-fw fa-users"></i>
            <span>Usuarios</span>
        </a>
        <div id="collapseUsuarios" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Acciones:</h6>
                <a class="collapse-item" href="<?= SERVER_URL; ?>usuario-new/">Añadir Usuario</a>
                <a class="collapse-item" href="<?= SERVER_URL; ?>usuarios/">Listado de Usuarios</a>
            </div>
        </div>
    </li>
    <?php } ?>

    <!-- Nav Item - Marcas collapse Menu-->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMarcas"
            aria-expanded="true" aria-controls="collapseMarcas">
            <i class="fas fa-fw fa-tags"></i>
            <span>Marcas</span>
        </a>
        <div id="collapseMarcas" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Acciones:</h6>
                <a class="collapse-item" href="<?= SERVER_URL; ?>marca-new/">Añadir Marca</a>
                <a class="collapse-item" href="<?= SERVER_URL; ?>marcas/">Listado de Marcas</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Tipos collapse Menu-->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTipos"
            aria-expanded="true" aria-controls="collapseTipos">
            <i class="fas fa-fw fa-th-large"></i>
            <span>Lineas</span>
        </a>
        <div id="collapseTipos" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Acciones:</h6>
                <a class="collapse-item" href="<?= SERVER_URL; ?>tipo-new/">Añadir Linea</a>
                <a class="collapse-item" href="<?= SERVER_URL; ?>tipos/">Listado de Lineas</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Modelos collapse Menu-->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseModelos"
            aria-expanded="true" aria-controls="collapseModelos">
            <i class="fas fa-fw fa-cubes"></i>
            <span>Modelos</span>
        </a>
        <div id="collapseModelos" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Acciones:</h6>
                <a class="collapse-item" href="<?= SERVER_URL; ?>modelo-new/">Añadir Modelo</a>
                <a class="collapse-item" href="<?= SERVER_URL; ?>modelos/">Listado de Modelos</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Clientes collapse Menu-->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseClientes"
            aria-expanded="true" aria-controls="collapseClientes">
            <i class="fas fa-fw fa-handshake"></i>
            <span>Clientes</span>
        </a>
        <div id="collapseClientes" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Acciones:</h6>
                <a class="collapse-item" href="<?= SERVER_URL; ?>cliente-new/">Añadir Cliente</a>
                <a class="collapse-item" href="<?= SERVER_URL; ?>clientes/">Listado de Clientes</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Equipos collapse Menu-->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseEquipos"
            aria-expanded="true" aria-controls="collapseEquipos">
            <i class="fas fa-fw fa-desktop"></i>
            <span>Equipos</span>
        </a>
        <div id="collapseEquipos" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Acciones:</h6>
                <a class="collapse-item" href="<?= SERVER_URL; ?>equipo-new/">Añadir Equipo</a>
                <a class="collapse-item" href="<?= SERVER_URL; ?>equipos/">Listado de Equipos</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Monitoreos collapse Menu-->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMonitoreos"
            aria-expanded="true" aria-controls="collapseMonitoreos">
            <i class="fas fa-fw fa-chart-line"></i>
            <span>Monitoreos</span>
        </a>
        <div id="collapseMonitoreos" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Acciones:</h6>
                <a class="collapse-item" href="<?= SERVER_URL; ?>monitoreo-new/">Añadir Monitoreo</a>
                <a class="collapse-item" href="<?= SERVER_URL; ?>monitoreos/">Listado de Monitoreos</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>