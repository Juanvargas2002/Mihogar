<!-- Equipos -->
<div class="container-fluid">

    <!-- Cabecera de página principal -->
    <div class="d-flex align-items-center mb-4">
        <h1 class="h3 text-gray-800 mr-3">Equipos</h1>
    </div>

    <!-- Filtro de Equipos -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Búsqueda de Equipos</h6>
        </div>

        <div class="card-body">
            <form id="filtroEquipos" method="POST" action="<?php echo SERVER_URL; ?>equipos/">
                <div class="row">

                    <!-- Número de Serie -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_num_serie">Num. Serie</label>
                            <input type="text" name="num_serie" id="filtro_num_serie" class="form-control" value="<?php echo isset($_POST['num_serie']) ? $_POST['num_serie'] : ''; ?>">
                        </div>
                    </div>

                    <!-- Marca -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_marca">Marca</label>
                            <select name="id_marca" id="filtro_marca" class="form-control">
                                <?php
                                require_once "./controladores/marcaControlador.php";
                                $ins_marca = new marcaControlador();
                                $marcas = $ins_marca->obtener_marcas_controlador();

                                echo '<option value="">Todas</option>';
                                if ($marcas) {
                                    foreach ($marcas as $marca) {
                                        $selected = (isset($_POST['id_marca']) && $marca->id == $_POST['id_marca']) ? 'selected' : '';
                                        echo '<option value="' . $marca->id . '" ' . $selected . '>' . $marca->nombre . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Tipo -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_tipo">Linea</label>
                            <select name="id_tipo" id="filtro_tipo" class="form-control">
                                <?php
                                require_once "./controladores/tipoControlador.php";
                                $ins_tipo = new tipoControlador();
                                $tipos = $ins_tipo->obtener_tipos_controlador();

                                echo '<option value="">Todos</option>';
                                if ($tipos) {
                                    foreach ($tipos as $tipo) {
                                        $selected = (isset($_POST['id_tipo']) && $tipo->id == $_POST['id_tipo']) ? 'selected' : '';
                                        echo '<option value="' . $tipo->id . '" ' . $selected . '>' . $tipo->nombre . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Modelo -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_modelo">Modelo</label>
                            <select name="id_modelo" id="filtro_modelo" class="form-control">
                                <?php
                                require_once "./controladores/modeloControlador.php";
                                $ins_modelo = new modeloControlador();
                                $modelos = $ins_modelo->obtener_modelos_controlador();

                                echo '<option value="">Todos</option>';
                                if ($modelos) {
                                    foreach ($modelos as $modelo) {
                                        $selected = (isset($_POST['id_modelo']) && $modelo->id == $_POST['id_modelo']) ? 'selected' : '';
                                        echo '<option value="' . $modelo->id . '" ' . $selected . '>' . $modelo->nombre . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Cliente -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_cliente">Cliente</label>
                            <select name="id_cliente" id="filtro_cliente" class="form-control">
                                <?php
                                require_once "./controladores/clienteControlador.php";
                                $ins_cliente = new clienteControlador();
                                $clientes = $ins_cliente->obtener_clientes_controlador();

                                echo '<option value="">Todos</option>';
                                if ($clientes) {
                                    foreach ($clientes as $cliente) {
                                        $selected = (isset($_POST['id_cliente']) && $cliente->id == $_POST['id_cliente']) ? 'selected' : '';
                                        echo '<option value="' . $cliente->id . '" ' . $selected . '>' . $cliente->nombre . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Ordenar por -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_orden">Ordenar por serie:</label>
                            <select class="form-control" id="filtro_orden" name="orden">
                                <option value="">Seleccione</option>
                                <option value="ASC" <?php echo (isset($_POST['orden']) && $_POST['orden'] == 'ASC') ? 'selected' : ''; ?> >1-9</option>
                                <option value="DESC" <?php echo (isset($_POST['orden']) && $_POST['orden'] == 'DESC') ? 'selected' : ''; ?> >9-1</option>
                            </select>
                        </div>
                    </div>

                    <!-- Botones -> Buscar | Reestablecer -->
                    <div class="col-md-2 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary mr-2">Buscar</button>
                        <a href="<?php echo SERVER_URL; ?>equipos/" class="btn btn-secondary">Restablecer</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Equipos -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Listado de Equipos</h6>
        </div>
        
        <div class="card-body">
            <?php
                require_once "./controladores/equipoControlador.php";
                $ins_equipo = new equipoControlador();

                $filtros = [
                    'num_serie' => isset($_POST['num_serie']) ? trim($_POST['num_serie']) : '',
                    'lote' => isset($_POST['lote']) ? trim($_POST['lote']) : '',
                    'id_marca' => isset($_POST['id_marca']) ? trim($_POST['id_marca']) : '',
                    'id_tipo' => isset($_POST['id_tipo']) ? trim($_POST['id_tipo']) : '',
                    'id_modelo' => isset($_POST['id_modelo']) ? trim($_POST['id_modelo']) : '',
                    'id_cliente' => isset($_POST['id_cliente']) ? trim($_POST['id_cliente']) : '',
                    'orden' => isset($_POST['orden']) ? trim($_POST['orden']) : ''
                ];

                echo $ins_equipo->paginar_equipo_controlador($pagina[1], 10, $pagina[0], $filtros);
            ?>
        </div>
    </div>
</div>
