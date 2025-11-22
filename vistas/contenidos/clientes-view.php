<!-- Clientes -->
<div class="container-fluid">

    <!-- Cabecera de página principal -->
    <div class="d-flex align-items-center mb-4">
        <h1 class="h3 text-gray-800 mr-3">Clientes</h1>
    </div>

    <!-- Filtro de Clientes -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Búsqueda de Clientes</h6>
        </div>

        <div class="card-body">
            <form id="filtroClientes" method="POST" action="<?php echo SERVER_URL; ?>clientes/">
                <div class="row">

                    <!-- Cédula -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_cedula">Cédula</label>
                            <input type="text" name="cedula" id="filtro_cedula" class="form-control" value="<?php echo isset($_POST['cedula']) ? $_POST['cedula'] : ''; ?>">
                        </div>
                    </div>

                    <!-- Nombre -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_nombre">Nombre</label>
                            <input type="text" name="nombre" id="filtro_nombre" class="form-control" value="<?php echo isset($_POST['nombre']) ? $_POST['nombre'] : ''; ?>">
                        </div>
                    </div>

                    <!-- Ordenar por:(A-Z, Z-A) -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_orden">Ordenar clientes de:</label>
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
                        <a href="<?php echo SERVER_URL; ?>clientes/" class="btn btn-secondary">Restablecer</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Clientes -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Listado de Clientes</h6>
        </div>
        
        <div class="card-body">
            <?php
                require_once "./controladores/clienteControlador.php";
                $ins_cliente = new clienteControlador();

                $filtros = [
                    'cedula' => isset($_POST['cedula']) ? trim($_POST['cedula']) : '',
                    'nombre' => isset($_POST['nombre']) ? trim($_POST['nombre']) : '',
                    'orden' => isset($_POST['orden']) ? trim($_POST['orden']) : '',
                    'correo' => isset($_POST['correo']) ? trim($_POST['correo']) : ''
                ];

                echo $ins_cliente->paginar_cliente_controlador($pagina[1], 10, $pagina[0], $filtros);
            ?>
        </div>
    </div>
</div>
