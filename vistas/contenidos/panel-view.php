<!-- Panel administrativo -->
<div class="container-fluid">

    <!-- Cabecera de página -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Panel administrativo</h1>
    </div>

    <!-- Contenido en fila -->
    <div class="row">

        <?php 
            if ($_SESSION['rol_usuario'] == "Administrador") {
                require_once "./controladores/usuarioControlador.php";
                $ins_usuario = new usuarioControlador();
                $total_usuarios = $ins_usuario->obtener_cantidad_usuarios_controlador();
        ?>
        <!-- Tarjeta Usuarios -->
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="<?php echo SERVER_URL; ?>usuarios/" style="text-decoration: none;">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Usuarios</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_usuarios->Cantidad ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <?php } ?>

        <!-- Tarjeta Marcas -->
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="<?php echo SERVER_URL; ?>marcas/" style="text-decoration: none;">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Marcas</div>
                                <?php
                                    require_once "./controladores/marcaControlador.php";
                                    $ins_marca = new marcaControlador();
                                    $total_marcas = $ins_marca->obtener_cantidad_marcas_controlador();
                                ?>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_marcas->Cantidad ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-tags fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Tarjeta Tipos -->
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="<?php echo SERVER_URL; ?>tipos/" style="text-decoration: none;">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Lineas</div>
                                <?php
                                    require_once "./controladores/tipoControlador.php";
                                    $ins_tipo = new tipoControlador();
                                    $total_tipos = $ins_tipo->obtener_cantidad_tipos_controlador();
                                ?>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_tipos->Cantidad ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-th-large fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Tarjeta Modelos -->
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="<?php echo SERVER_URL; ?>modelos/" style="text-decoration: none;">
                <div class="card border-left-dark shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                    Modelos</div>
                                <?php
                                    require_once "./controladores/modeloControlador.php";
                                    $ins_modelo = new modeloControlador();
                                    $total_modelos = $ins_modelo->obtener_cantidad_modelos_controlador();
                                ?>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_modelos->Cantidad ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-cubes fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Tarjeta Clientes -->
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="<?php echo SERVER_URL; ?>clientes/" style="text-decoration: none;">
                <div class="card border-left-secondary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                    Clientes</div>
                                <?php
                                    require_once "./controladores/clienteControlador.php";
                                    $ins_cliente = new clienteControlador();
                                    $total_clientes = $ins_cliente->obtener_cantidad_clientes_controlador();
                                ?>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_clientes->Cantidad ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-handshake fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Tarjeta Equipos -->
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="<?php echo SERVER_URL; ?>equipos/" style="text-decoration: none;">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Equipos</div>
                                <?php
                                    require_once "./controladores/equipoControlador.php";
                                    $ins_equipo = new equipoControlador();
                                    $total_equipos = $ins_equipo->obtener_cantidad_equipos_controlador();
                                ?>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_equipos->Cantidad ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-desktop fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Tarjeta Monitoreos -->
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="<?php echo SERVER_URL; ?>monitoreos/" style="text-decoration: none;">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Monitoreos</div>
                                <?php
                                    require_once "./controladores/monitoreoControlador.php";
                                    $ins_monitoreo = new monitoreoControlador();
                                    $total_monitoreos = $ins_monitoreo->obtener_cantidad_monitoreos_controlador();
                                ?>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_monitoreos->Cantidad ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>