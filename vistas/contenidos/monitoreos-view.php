<!-- Listado de Monitoreos -->
<div class="container-fluid">

    <!-- Cabecera de página principal -->
    <div class="d-flex align-items-center mb-4">
        <h1 class="h3 text-gray-800 mr-3">Monitoreos</h1>
    </div>

    <!-- Filtro de Monitoreos -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Búsqueda de Monitoreos</h6>
        </div>

        <div class="card-body">
            <form id="filtroMonitoreos" method="POST" action="<?php echo SERVER_URL; ?>monitoreos/">
                <div class="row">

                    <!-- ID Monitoreo -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_id_monitoreo">Numero de monitoreo</label>
                            <input type="number" name="id_monitoreo" id="filtro_id_monitoreo" class="form-control" value="<?php echo isset($_POST['id_monitoreo']) ? $_POST['id_monitoreo'] : ''; ?>" min="1">
                        </div>
                    </div>

                    <!-- Número de Serie -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filtro_num_serie">Núm. Serie Equipo</label>
                            <input type="text" name="num_serie" id="filtro_num_serie" class="form-control" value="<?php echo isset($_POST['num_serie']) ? $_POST['num_serie'] : ''; ?>">
                        </div>
                    </div>

                    <!-- Cliente -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filtro_cliente">Cliente</label>
                            <input type="text" name="cliente" id="filtro_cliente" class="form-control" value="<?php echo isset($_POST['cliente']) ? $_POST['cliente'] : ''; ?>">
                        </div>
                    </div>

                    <!-- Técnico -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filtro_tecnico">Técnico</label>
                            <input type="text" name="tecnico" id="filtro_tecnico" class="form-control" value="<?php echo isset($_POST['tecnico']) ? $_POST['tecnico'] : ''; ?>">
                        </div>
                    </div>

                    <!-- Fecha Inicio -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filtro_fecha_inicio">Desde</label>
                            <input type="date" name="fecha_inicio" id="filtro_fecha_inicio" class="form-control" value="<?php echo isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : ''; ?>">
                        </div>
                    </div>

                    <!-- Fecha Fin -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filtro_fecha_fin">Hasta</label>
                            <input type="date" name="fecha_fin" id="filtro_fecha_fin" class="form-control" value="<?php echo isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : ''; ?>">
                        </div>
                    </div>

                    <!-- Botones -> Buscar | Reestablecer -->
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary mr-2">Buscar</button>
                        <a href="<?php echo SERVER_URL; ?>monitoreos/" class="btn btn-secondary">Restablecer</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Monitoreos -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Listado de Monitoreos Realizados</h6>
        </div>
        
        <div class="card-body">
            <?php
                require_once "./controladores/monitoreoControlador.php";
                $ins_monitoreo = new monitoreoControlador();

                $filtros = [
                    'id_monitoreo' => isset($_POST['id_monitoreo']) ? trim($_POST['id_monitoreo']) : '',
                    'num_serie' => isset($_POST['num_serie']) ? trim($_POST['num_serie']) : '',
                    'cliente' => isset($_POST['cliente']) ? trim($_POST['cliente']) : '',
                    'tecnico' => isset($_POST['tecnico']) ? trim($_POST['tecnico']) : '',
                    'fecha_inicio' => isset($_POST['fecha_inicio']) ? trim($_POST['fecha_inicio']) : '',
                    'fecha_fin' => isset($_POST['fecha_fin']) ? trim($_POST['fecha_fin']) : ''
                ];

                echo $ins_monitoreo->paginar_monitoreo_controlador($pagina[1], 10, $pagina[0], $filtros);
            ?>
        </div>
    </div>
</div>
