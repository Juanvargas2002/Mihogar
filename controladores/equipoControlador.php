<?php

    if ($peticionAjax) {
        require_once "../modelos/equipoModelo.php";
    } else {
        require_once "./modelos/equipoModelo.php";
    }

    class equipoControlador extends equipoModelo {

        //---------- Agregar un equipo nuevo -------------//
        public function agregar_equipo_controlador() {
            session_start(['name' => 'STMH']);

            $num_serie = mainModel::limpiar_cadena($_POST['num_serie']);
            $lote = isset($_POST['lote']) && $_POST['lote'] != "" ? mainModel::limpiar_cadena($_POST['lote']) : null;
            $id_marca = $_POST['id_marca'];
            $id_tipo = $_POST['id_tipo'];
            $id_modelo = $_POST['id_modelo'];
            $id_cliente = $_POST['id_cliente'];

            //---------- Comprobar campos vacios ------------//
            if ($num_serie == "" || $id_marca == "" || $id_tipo == "" || $id_modelo == "" || $id_cliente == "") {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No haz llenado todos los campos que son obligatorios",
                    "Tipo" => "error",
                ];
                echo json_encode($alerta);
                exit();
            }

            //------------ Comprobando número de serie del equipo -------------//
            $check_equipo = mainModel::ejecutar_consulta_simple("SELECT num_serie FROM equipo WHERE num_serie = '$num_serie';");
            if ($check_equipo->rowCount() > 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El número de serie ingresado ya se encuentra registrado en el sistema",
                    "Tipo" => "error",
                ];
                echo json_encode($alerta);
                exit();
            }

            //------------ Verificar que la marca existe -------------//
            $check_marca = mainModel::ejecutar_consulta_simple("SELECT id FROM marca WHERE id = '$id_marca';");
            if ($check_marca->rowCount() == 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "La marca seleccionada no existe en el sistema",
                    "Tipo" => "error",
                ];
                echo json_encode($alerta);
                exit();
            }

            //------------ Verificar que el tipo existe -------------//
            $check_tipo = mainModel::ejecutar_consulta_simple("SELECT id FROM tipo WHERE id = '$id_tipo';");
            if ($check_tipo->rowCount() == 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El tipo seleccionado no existe en el sistema",
                    "Tipo" => "error",
                ];
                echo json_encode($alerta);
                exit();
            }

            //------------ Verificar que el modelo existe -------------//
            $check_modelo = mainModel::ejecutar_consulta_simple("SELECT id FROM modelo WHERE id = '$id_modelo';");
            if ($check_modelo->rowCount() == 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El modelo seleccionado no existe en el sistema",
                    "Tipo" => "error",
                ];
                echo json_encode($alerta);
                exit();
            }

            //------------ Verificar que el cliente existe -------------//
            $check_cliente = mainModel::ejecutar_consulta_simple("SELECT id FROM cliente WHERE id = '$id_cliente';");
            if ($check_cliente->rowCount() == 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El cliente seleccionado no existe en el sistema",
                    "Tipo" => "error",
                ];
                echo json_encode($alerta);
                exit();
            }

            $datos = [
                "NumSerie" => $num_serie,
                "Lote" => $lote,
                "IdMarca" => $id_marca,
                "IdTipo" => $id_tipo,
                "IdModelo" => $id_modelo,
                "IdCliente" => $id_cliente
            ];

            $agregar_equipo = equipoModelo::agregar_equipo_modelo($datos);

            if ($agregar_equipo->rowCount() == 1) {
                $alerta = [
                    "Alerta" => "limpiar",
                    "Titulo" => "Equipo registrado",
                    "Texto" => "Los datos del equipo han sido registrados con éxito",
                    "Tipo" => "success",
                ];
                echo json_encode($alerta);
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No hemos podido registrar el equipo",
                    "Tipo" => "error",
                ];
                echo json_encode($alerta);
            }
        }

        //---------- Paginación de equipos -------------//
        public function paginar_equipo_controlador($pagina, $registros, $url, $filtros) {

            $url = SERVER_URL . $url . "/";
            $tabla = "";

            $pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1;
            $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;

            $where = "WHERE 1=1 ";

            if (!empty($filtros['num_serie'])) {
                $where .= " AND e.num_serie LIKE '%" . $filtros['num_serie'] . "%'";
            }

            if (!empty($filtros['lote'])) {
                $where .= " AND e.lote LIKE '%" . $filtros['lote'] . "%'";
            }

            if (!empty($filtros['id_marca'])) {
                $where .= " AND e.id_marca = '" . $filtros['id_marca'] . "'";
            }

            if (!empty($filtros['id_tipo'])) {
                $where .= " AND e.id_tipo = '" . $filtros['id_tipo'] . "'";
            }

            if (!empty($filtros['id_modelo'])) {
                $where .= " AND e.id_modelo = '" . $filtros['id_modelo'] . "'";
            }

            if (!empty($filtros['id_cliente'])) {
                $where .= " AND e.id_cliente = '" . $filtros['id_cliente'] . "'";
            }
        
            $orderBy = " ORDER BY e.id DESC";
            if (!empty($filtros['orden'])) {
                $orderBy = " ORDER BY e.num_serie " . $filtros['orden'];
            }
        
            $consulta = "SELECT e.*, m.nombre AS marca_nombre, t.nombre AS tipo_nombre, 
                        mo.nombre AS modelo_nombre, c.nombre AS cliente_nombre 
                        FROM equipo e
                        INNER JOIN marca m ON e.id_marca = m.id
                        INNER JOIN tipo t ON e.id_tipo = t.id
                        INNER JOIN modelo mo ON e.id_modelo = mo.id
                        INNER JOIN cliente c ON e.id_cliente = c.id
                        $where $orderBy LIMIT $inicio, $registros;";
            
            $consulta_total = "SELECT COUNT(*) FROM equipo e $where;";

            $conexion = mainModel::conectar();

            $datos = $conexion->query($consulta);
            $datos = $datos->fetchAll();

            $total = $conexion->query($consulta_total);
            $total = (int) $total->fetchColumn();

            $n_paginas = ceil($total / $registros);

            $tabla .= '
            <div class="table-responsive mb-2">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Num. Serie</th>
                            <th>Marca</th>
                            <th>Linea</th>
                            <th>Modelo</th>
                            <th>Cliente</th>
                            <th style="max-width:160px; white-space:normal; overflow-wrap:anywhere; word-break:break-word;">Falla</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
            ';

            if ($total >= 1 && $pagina <= $n_paginas) {
                $contador = $inicio + 1;
                $reg_inicio = $inicio + 1;
                foreach ($datos as $rows) {
                    $tabla .= '
                    <tr>
                        <td>' . $rows['id'] . '</td>
                        <td>' . $rows['num_serie'] . '</td>
                        <td>' . $rows['marca_nombre'] . '</td>
                        <td>' . $rows['tipo_nombre'] . '</td>
                        <td>' . $rows['modelo_nombre'] . '</td>
                        <td>' . $rows['cliente_nombre'] . '</td>
                        <td style="max-width:160px; white-space:normal; overflow-wrap:anywhere; word-break:break-word;">' . ($rows['lote'] ?? 'N/A') . '</td>
                        <td>
                            <a href="' . SERVER_URL . 'equipo-update/' . $rows['id'] . '/" class="btn btn-success btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form class="FormularioAjax d-inline" action="' . SERVER_URL . 'ajax/equipoAjax.php" method="POST" data-form="delete">
                                <input type="hidden" name="id_eliminar" value="' . $rows['id'] . '">
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    ';
                    $contador++;
                }
                $reg_final = $contador - 1;
            } else {
                if ($total >= 1) {
                    $tabla .= '
                    <tr class="text-center">
                        <td colspan="8">
                            <a href="' . $url . '1/">
                                <button class="btn btn-primary btn-raised btn-sm">
                                    Haga clic aquí para recargar el listado
                                </button>
                            </a>
                        </td>
                    </tr>
                    ';
                } else {
                    $tabla .= '
                    <tr class="text-center">
                        <td colspan="8">No hay registros en el sistema</td>
                    </tr>
                    ';
                }
            }

            $tabla .= '
                    </tbody>
                </table>
            </div>
            ';

            if ($total >= 1 && $pagina <= $n_paginas) {
                $tabla .= '<p class="text-left">Mostrando equipo ' . $reg_inicio . ' al ' . $reg_final . ' de un total de ' . $total . '</p>';
                $tabla .= mainModel::paginador_tablas($pagina, $n_paginas, $url, 10);
            }

            return $tabla;
        }

        //---------- Eliminar un equipo -----------------//
        public function eliminar_equipo_controlador() {

            // Recibiendo id del equipo
            $id = $_POST['id_eliminar'];

            //------------ Comprobando el equipo en BD ------------//
            $check_equipo = mainModel::ejecutar_consulta_simple("SELECT id FROM equipo WHERE id = '$id';");
            if ($check_equipo->rowCount() == 0) {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'El equipo que intenta eliminar no existe en el sistema',
                    "Tipo" => 'error'
                ];
                echo json_encode($alerta);
                exit();
            }

            //------------ Comprobando si el equipo tiene monitoreos ------------//
            $check_monitoreos = mainModel::ejecutar_consulta_simple("SELECT id FROM monitoreo WHERE id_equipo = '$id' LIMIT 1;");
            if ($check_monitoreos->rowCount() > 0) {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No se puede eliminar el equipo porque tiene monitoreos asociados',
                    "Tipo" => 'error'
                ];
                echo json_encode($alerta);
                exit();
            }

            //------------ Comprobando rol -----------------//
            session_start(['name' => 'STMH']);
            if ($_SESSION['rol_usuario'] != "Administrador") {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No tienes los permisos necesarios para realizar esta acción',
                    "Tipo" => 'error'
                ];
                echo json_encode($alerta);
                exit();
            }

            $eliminar_equipo = equipoModelo::eliminar_equipo_modelo($id);

            if ($eliminar_equipo->rowCount() == 1) {
                $alerta = [
                    "Alerta" => 'recargar',
                    "Titulo" => 'Equipo eliminado',
                    "Texto" => 'El equipo ha sido eliminado del sistema exitosamente',
                    "Tipo" => 'success'
                ];
            } else {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No hemos podido eliminar el equipo, intentelo nuevamente',
                    "Tipo" => 'error'
                ];
            }
            echo json_encode($alerta);
        }

        //---------- Obtener un equipo específico -------------//
        public function obtener_equipo_controlador($id) {
            return equipoModelo::obtener_equipo_modelo($id);
        }

        //---------- Obtener la cantidad de equipos -------------//
        public function obtener_cantidad_equipos_controlador() {
            return equipoModelo::obtener_cantidad_equipos_modelo();
        }

        //---------- Obtener todos los equipos -------------//
        public function obtener_equipos_controlador() {
            return equipoModelo::obtener_equipos_modelo();
        }

        //---------- Actualizar los datos del equipo -------------//
        public function actualizar_equipo_controlador() {
            
            $id = $_POST['id_actualizar'];

            // Comprobar el equipo en la BD
            $check_equipo = mainModel::ejecutar_consulta_simple("SELECT * FROM equipo WHERE id = '$id'");
            if ($check_equipo->rowCount() == 0) {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No hemos encontrado el equipo en el sistema',
                    "Tipo" => 'error'
                ];
                echo json_encode($alerta);
                exit();
            } else {
                $datos = $check_equipo->fetch();
            }

            $num_serie = mainModel::limpiar_cadena($_POST['num_serie_actualizar']);
            $lote = isset($_POST['lote_actualizar']) && $_POST['lote_actualizar'] != "" ? mainModel::limpiar_cadena($_POST['lote_actualizar']) : null;
            $id_marca = $_POST['id_marca_actualizar'];
            $id_tipo = $_POST['id_tipo_actualizar'];
            $id_modelo = $_POST['id_modelo_actualizar'];
            $id_cliente = $_POST['id_cliente_actualizar'];

            //---------- Comprobar campos vacios ------------//
            if ($num_serie == "" || $id_marca == "" || $id_tipo == "" || $id_modelo == "" || $id_cliente == "") {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No haz llenado todos los campos que son obligatorios",
                    "Tipo" => "error",
                ];
                echo json_encode($alerta);
                exit();
            }

            //------------ Comprobando número de serie del equipo si es diferente ------------//
            if ($datos['num_serie'] != $num_serie) {
                $check_num_serie = mainModel::ejecutar_consulta_simple("SELECT num_serie FROM equipo WHERE num_serie = '$num_serie';");
                if ($check_num_serie->rowCount() > 0) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "El número de serie ingresado ya se encuentra registrado en el sistema",
                        "Tipo" => "error",
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            }

            //------------ Verificar que la marca existe -------------//
            $check_marca = mainModel::ejecutar_consulta_simple("SELECT id FROM marca WHERE id = '$id_marca';");
            if ($check_marca->rowCount() == 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "La marca seleccionada no existe en el sistema",
                    "Tipo" => "error",
                ];
                echo json_encode($alerta);
                exit();
            }

            //------------ Verificar que el tipo existe -------------//
            $check_tipo = mainModel::ejecutar_consulta_simple("SELECT id FROM tipo WHERE id = '$id_tipo';");
            if ($check_tipo->rowCount() == 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El tipo seleccionado no existe en el sistema",
                    "Tipo" => "error",
                ];
                echo json_encode($alerta);
                exit();
            }

            //------------ Verificar que el modelo existe -------------//
            $check_modelo = mainModel::ejecutar_consulta_simple("SELECT id FROM modelo WHERE id = '$id_modelo';");
            if ($check_modelo->rowCount() == 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El modelo seleccionado no existe en el sistema",
                    "Tipo" => "error",
                ];
                echo json_encode($alerta);
                exit();
            }

            //------------ Verificar que el cliente existe -------------//
            $check_cliente = mainModel::ejecutar_consulta_simple("SELECT id FROM cliente WHERE id = '$id_cliente';");
            if ($check_cliente->rowCount() == 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El cliente seleccionado no existe en el sistema",
                    "Tipo" => "error",
                ];
                echo json_encode($alerta);
                exit();
            }

            $datos = [
                "Id" => $id,
                "NumSerie" => $num_serie,
                "Lote" => $lote,
                "IdMarca" => $id_marca,
                "IdTipo" => $id_tipo,
                "IdModelo" => $id_modelo,
                "IdCliente" => $id_cliente
            ];

            $actualizar_equipo = equipoModelo::actualizar_equipo_modelo($datos);

            if ($actualizar_equipo->rowCount() == 1) {
                $alerta = [
                    "Alerta" => "recargar",
                    "Titulo" => "Datos actualizados",
                    "Texto" => "Los datos han sido actualizados con éxito",
                    "Tipo" => "success",
                ];
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No hemos podido actualizar los datos",
                    "Tipo" => "error",
                ];
            }
            echo json_encode($alerta);
        }

        //---------- Listar equipos para select -------------//
        public function listar_equipos_select_controlador() {
            $consulta = "SELECT 
                e.id,
                e.num_serie,
                e.lote,
                ma.nombre AS marca_nombre,
                t.nombre AS tipo_nombre,
                mo.nombre AS modelo_nombre,
                c.nombre AS cliente_nombre
            FROM equipo e
            INNER JOIN marca ma ON e.id_marca = ma.id
            INNER JOIN tipo t ON e.id_tipo = t.id
            INNER JOIN modelo mo ON e.id_modelo = mo.id
            INNER JOIN cliente c ON e.id_cliente = c.id
            ORDER BY e.num_serie ASC";

            $datos = mainModel::ejecutar_consulta_simple($consulta);
            
            $opciones = '';
            if ($datos->rowCount() > 0) {
                $datos = $datos->fetchAll();
                foreach ($datos as $row) {
                    $opciones .= '<option value="' . $row['id'] . '" 
                        data-serie="' . $row['num_serie'] . '" 
                        data-lote="' . ($row['lote'] ?? 'N/A') . '" 
                        data-marca="' . $row['marca_nombre'] . '" 
                        data-tipo="' . $row['tipo_nombre'] . '" 
                        data-modelo="' . $row['modelo_nombre'] . '" 
                        data-cliente="' . $row['cliente_nombre'] . '">' 
                        . $row['num_serie'] . ' - ' . $row['marca_nombre'] . ' ' . $row['tipo_nombre'] . 
                    '</option>';
                }
            }
            return $opciones;
        }

        //---------- Buscar equipos por número de serie para autocompletado -------------//
        public function buscar_equipos_controlador($q) {
            $q = mainModel::limpiar_cadena($q);
            $consulta = "SELECT 
                e.id,
                e.num_serie,
                ma.nombre AS marca_nombre,
                t.nombre AS tipo_nombre,
                mo.nombre AS modelo_nombre,
                mo.corriente_max,
                mo.temperatura_min,
                mo.temperatura_max,
                mo.vibracion_max,
                c.id AS cliente_id,
                c.nombre AS cliente_nombre,
                c.cedula AS cliente_cedula,
                c.contacto AS cliente_contacto,
                c.correo AS cliente_correo
                FROM equipo e
                INNER JOIN marca ma ON e.id_marca = ma.id
                INNER JOIN tipo t ON e.id_tipo = t.id
                INNER JOIN modelo mo ON e.id_modelo = mo.id
                INNER JOIN cliente c ON e.id_cliente = c.id
                WHERE e.num_serie LIKE '%" . $q . "%' 
                ORDER BY e.num_serie ASC LIMIT 7;";

            $resultado = mainModel::ejecutar_consulta_simple($consulta);
            return $resultado->fetchAll(PDO::FETCH_ASSOC);
        }
    }
