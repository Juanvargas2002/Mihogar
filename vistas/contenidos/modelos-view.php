<!-- Modelos -->
<div class="container-fluid">

    <!-- Cabecera de página principal -->
    <div class="d-flex align-items-center mb-4">
        <h1 class="h3 text-gray-800 mr-3">Modelos</h1>
    </div>

    <!-- Filtro de Modelos -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Búsqueda de Modelos</h6>
        </div>

        <div class="card-body">
            <form id="filtroModelos" method="POST" action="<?php echo SERVER_URL; ?>modelos/">
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
                            <label for="filtro_orden">Ordenar modelos de:</label>
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
                        <a href="<?php echo SERVER_URL; ?>modelos/" class="btn btn-secondary">Restablecer</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Modelos -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Listado de Modelos</h6>
        </div>
        
        <div class="card-body">
            <?php
                require_once "./controladores/modeloControlador.php";
                $ins_modelo = new modeloControlador();

                $filtros = [
                    'nombre' => isset($_POST['nombre']) ? trim($_POST['nombre']) : '',
                    'orden' => isset($_POST['orden']) ? trim($_POST['orden']) : ''
                ];

                echo $ins_modelo->paginar_modelo_controlador($pagina[1], 10, $pagina[0], $filtros);
            ?>
        </div>
    </div>
</div>
