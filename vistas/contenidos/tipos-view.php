<!-- Tipos -->
<div class="container-fluid">

    <!-- Cabecera de página principal -->
    <div class="d-flex align-items-center mb-4">
        <h1 class="h3 text-gray-800 mr-3">Lineas</h1>
    </div>

    <!-- Filtro de Lineas -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Búsqueda de Lineas</h6>
        </div>

        <div class="card-body">
            <form id="filtroTipos" method="POST" action="<?php echo SERVER_URL; ?>tipos/">
                <div class="row">

                    <!-- Nombre -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_nombre">Nombre</label>
                            <input type="text" class="form-control" id="filtro_nombre" name="nombre" value="<?php echo (isset($_POST['nombre']) ? $_POST['nombre'] : ''); ?>">
                        </div>
                    </div>

                    <!-- Ordenar por:(A-Z, Z-A) -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_orden">Ordenar lineas de:</label>
                            <select class="form-control" id="filtro_orden" name="orden">
                                <option value="">Seleccione</option>
                                <option value="ASC" <?php echo (isset($_POST['orden']) && $_POST['orden'] == 'ASC') ? 'selected' : ''; ?> >A-Z</option>
                                <option value="DESC" <?php echo (isset($_POST['orden']) && $_POST['orden'] == 'DESC') ? 'selected' : ''; ?> >Z-A</option>
                            </select>
                        </div>
                    </div>

                    <!-- Botones -> Buscar | Reestablecer -->
                    <div class="col-md-2 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary mr-2">Buscar</button>
                        <a href="<?php echo SERVER_URL; ?>tipos/" class="btn btn-secondary">Restablecer</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Tipos -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Listado de Tipos</h6>
        </div>
        
        <div class="card-body">
            <?php
                require_once "./controladores/tipoControlador.php";
                $ins_tipo = new tipoControlador();

                $filtros = [
                    'nombre' => isset($_POST['nombre']) ? trim($_POST['nombre']) : '',
                    'orden' => isset($_POST['orden']) ? trim($_POST['orden']) : ''
                ];

                echo $ins_tipo->paginar_tipo_controlador($pagina[1], 10, $pagina[0], $filtros);
            ?>
        </div>
    </div>
</div>
