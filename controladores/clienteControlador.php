<?php

    if ($peticionAjax) {
        require_once "../modelos/clienteModelo.php";
    } else {
        require_once "./modelos/clienteModelo.php";
    }

    class clienteControlador extends clienteModelo {

        //---------- Agregar un cliente nuevo -------------//
        public function agregar_cliente_controlador() {
            session_start(['name' => 'STMH']);

            $cedula = mainModel::limpiar_cadena($_POST['cedula']);
            $nombre = mainModel::limpiar_cadena($_POST['nombre']);
            $contacto = mainModel::limpiar_cadena($_POST['contacto']);
            $correo = mainModel::limpiar_cadena($_POST['correo']);
            $direccion = isset($_POST['direccion']) ? mainModel::limpiar_cadena($_POST['direccion']) : null;

            //---------- Comprobar campos vacios ------------//
            if ($cedula == "" || $nombre == "" || $contacto == "") {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No haz llenado todos los campos que son obligatorios",
                    "Tipo" => "error",
                ];
                echo json_encode($alerta);
                exit();
            }

            //------------ Comprobando cédula del cliente -------------//
            $check_cliente = mainModel::ejecutar_consulta_simple("SELECT cedula FROM cliente WHERE cedula = '$cedula';");
            if ($check_cliente->rowCount() > 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "La cédula ingresada ya se encuentra registrada en el sistema",
                    "Tipo" => "error",
                ];
                echo json_encode($alerta);
                exit();
            }

            $datos = [
                "Cedula" => $cedula,
                "Nombre" => $nombre,
                "Contacto" => $contacto,
                "Correo" => $correo,
                "Direccion" => $direccion
            ];

            $agregar_cliente = clienteModelo::agregar_cliente_modelo($datos);

            if ($agregar_cliente->rowCount() == 1) {
                $alerta = [
                    "Alerta" => "limpiar",
                    "Titulo" => "Cliente registrado",
                    "Texto" => "Los datos del cliente han sido registrados con éxito",
                    "Tipo" => "success",
                ];
                echo json_encode($alerta);
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No hemos podido registrar el cliente",
                    "Tipo" => "error",
                ];
                echo json_encode($alerta);
            }
        }

        //---------- Paginación de clientes -------------//
        public function paginar_cliente_controlador($pagina, $registros, $url, $filtros) {

            $url = SERVER_URL . $url . "/";
            $tabla = "";

            $pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1;
            $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;

            $where = "WHERE 1=1 ";

            if (!empty($filtros['cedula'])) {
                $where .= " AND cedula LIKE '%" . $filtros['cedula'] . "%'";
            }

            if (!empty($filtros['nombre'])) {
                $where .= " AND nombre LIKE '%" . $filtros['nombre'] . "%'";
            }
        
            $orderBy = "";
            if (!empty($filtros['orden'])) {
                $orderBy = " ORDER BY nombre " . $filtros['orden'];
            }
        
            $consulta = "SELECT * FROM cliente $where $orderBy LIMIT $inicio, $registros;";
            $consulta_total = "SELECT COUNT(*) FROM cliente $where;";

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
                            <th>Cédula</th>
                            <th>Nombre</th>
                            <th>Número de teléfono</th>
                            <th>Correo Electrónico</th>
                            <th>Dirección</th>
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
                        <td>' . $rows['cedula'] . '</td>
                        <td>' . $rows['nombre'] . '</td>
                        <td>' . $rows['contacto'] . '</td>
                        <td>' . ($rows['correo'] ?: 'N/A') . '</td>
                        <td>' . (!empty($rows['direccion']) ? $rows['direccion'] : 'N/A') . '</td>
                        <td>
                            <a href="' . SERVER_URL . 'cliente-update/' . $rows['id'] . '/" class="btn btn-success btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form class="FormularioAjax d-inline" action="' . SERVER_URL . 'ajax/clienteAjax.php" method="POST" data-form="delete">
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
                        <td colspan="6">
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
                        <td colspan="6">No hay registros en el sistema</td>
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
                $tabla .= '<p class="text-left">Mostrando cliente ' . $reg_inicio . ' al ' . $reg_final . ' de un total de ' . $total . '</p>';
                $tabla .= mainModel::paginador_tablas($pagina, $n_paginas, $url, 10);
            }

            return $tabla;
        }

        //---------- Eliminar un cliente -----------------//
        public function eliminar_cliente_controlador() {

            // Recibiendo id del cliente
            $id = $_POST['id_eliminar'];

            //------------ Comprobando el cliente en BD ------------//
            $check_cliente = mainModel::ejecutar_consulta_simple("SELECT id FROM cliente WHERE id = '$id';");
            if ($check_cliente->rowCount() == 0) {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'El cliente que intenta eliminar no existe en el sistema',
                    "Tipo" => 'error'
                ];
                echo json_encode($alerta);
                exit();
            }

            //------------ Comprobando los equipos asociados ------------//
            $check_equipos = mainModel::ejecutar_consulta_simple("SELECT id_cliente FROM equipo WHERE id_cliente = '$id' LIMIT 1;");
            if ($check_equipos->rowCount() > 0) {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No podemos eliminar este cliente debido a que tiene equipos asociados',
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

            $eliminar_cliente = clienteModelo::eliminar_cliente_modelo($id);

            if ($eliminar_cliente->rowCount() == 1) {
                $alerta = [
                    "Alerta" => 'recargar',
                    "Titulo" => 'Cliente eliminado',
                    "Texto" => 'El cliente ha sido eliminado del sistema exitosamente',
                    "Tipo" => 'success'
                ];
            } else {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No hemos podido eliminar el cliente, intentelo nuevamente',
                    "Tipo" => 'error'
                ];
            }
            echo json_encode($alerta);
        }

        //---------- Obtener un cliente específico -------------//
        public function obtener_cliente_controlador($id) {
            return clienteModelo::obtener_cliente_modelo($id);
        }

        //---------- Obtener la cantidad de clientes -------------//
        public function obtener_cantidad_clientes_controlador() {
            return clienteModelo::obtener_cantidad_clientes_modelo();
        }

        //---------- Obtener todos los clientes -------------//
        public function obtener_clientes_controlador() {
            return clienteModelo::obtener_clientes_modelo();
        }

        //---------- Buscar clientes por cédula para autocompletado -------------//
        public function buscar_clientes_controlador($q) {
            $q = mainModel::limpiar_cadena($q);
            $consulta = "SELECT id, cedula, nombre FROM cliente WHERE cedula LIKE '%" . $q . "%' ORDER BY cedula LIMIT 7;";
            $resultado = mainModel::ejecutar_consulta_simple($consulta);
            return $resultado->fetchAll(PDO::FETCH_ASSOC);
        }

        //---------- Actualizar los datos del cliente -------------//
        public function actualizar_cliente_controlador() {
            
            $id = $_POST['id_actualizar'];

            // Comprobar el cliente en la BD
            $check_cliente = mainModel::ejecutar_consulta_simple("SELECT * FROM cliente WHERE id = '$id'");
            if ($check_cliente->rowCount() == 0) {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No hemos encontrado el cliente en el sistema',
                    "Tipo" => 'error'
                ];
                echo json_encode($alerta);
                exit();
            } else {
                $datos = $check_cliente->fetch();
            }

            $cedula = mainModel::limpiar_cadena($_POST['cedula_actualizar']);
            $nombre = mainModel::limpiar_cadena($_POST['nombre_actualizar']);
            $contacto = mainModel::limpiar_cadena($_POST['contacto_actualizar']);
            $correo = mainModel::limpiar_cadena($_POST['correo_actualizar']);
            $direccion = isset($_POST['direccion_actualizar']) ? mainModel::limpiar_cadena($_POST['direccion_actualizar']) : null;

            //---------- Comprobar campos vacios ------------//
            if ($cedula == "" || $nombre == "" || $contacto == "") {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No haz llenado todos los campos que son obligatorios",
                    "Tipo" => "error",
                ];
                echo json_encode($alerta);
                exit();
            }

            //------------ Comprobando cédula del cliente si es diferente ------------//
            if ($datos['cedula'] != $cedula) {
                $check_cedula = mainModel::ejecutar_consulta_simple("SELECT cedula FROM cliente WHERE cedula = '$cedula';");
                if ($check_cedula->rowCount() > 0) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "La cédula ingresada ya se encuentra registrada en el sistema",
                        "Tipo" => "error",
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            }

            //--------- Comprobando si cliente esta en equipos ------------//
            $check_equipos = mainModel::ejecutar_consulta_simple("SELECT id_cliente FROM equipo WHERE id_cliente = '$id' LIMIT 1;");
            if ($check_equipos->rowCount() > 0 && $datos['cedula'] != $cedula) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No podemos actualizar los datos de este cliente debido a que tiene equipos asociados",
                    "Tipo" => "error",
                ];
                echo json_encode($alerta);
                exit();
            }

            $datos = [
                "Id" => $id,
                "Cedula" => $cedula,
                "Nombre" => $nombre,
                "Contacto" => $contacto,
                "Correo" => $correo,
                "Direccion" => $direccion
            ];

            $actualizar_cliente = clienteModelo::actualizar_cliente_modelo($datos);

            if ($actualizar_cliente->rowCount() == 1) {
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
    }
