<?php

    if ($peticionAjax) {
        require_once "../modelos/tipoModelo.php";
    } else {
        require_once "./modelos/tipoModelo.php";
    }

    class tipoControlador extends tipoModelo {

        //---------- Agregar un tipo nuevo -------------//
        public function agregar_tipo_controlador() {
            session_start(['name' => 'STMH']);

            $nombre = $_POST['nombre'];

            //---------- Comprobar campos vacios ------------//
            if ($nombre == "") {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No haz llenado todos los campos que son obligatorios",
                    "Tipo" => "error",
                ];
                echo json_encode($alerta);
                exit();
            }

            //------------ Comprobando nombre del tipo -------------//
            $check_tipo = mainModel::ejecutar_consulta_simple("SELECT nombre FROM tipo WHERE nombre = '$nombre';");
            if ($check_tipo->rowCount() > 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El tipo ingresado ya se encuentra registrado en el sistema",
                    "Tipo" => "error",
                ];
                echo json_encode($alerta);
                exit();
            }

            $datos = [
                "Nombre" => $nombre
            ];

            $agregar_tipo = tipoModelo::agregar_tipo_modelo($datos);

            if ($agregar_tipo->rowCount() == 1) {
                $alerta = [
                    "Alerta" => "limpiar",
                    "Titulo" => "Tipo registrado",
                    "Texto" => "Los datos del tipo han sido registrados con éxito",
                    "Tipo" => "success",
                ];
                echo json_encode($alerta);
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No hemos podido registrar el tipo",
                    "Tipo" => "error",
                ];
                echo json_encode($alerta);
            }
        }

        //---------- Paginación de tipos -------------//
        public function paginar_tipo_controlador($pagina, $registros, $url, $filtros) {

            $url = SERVER_URL . $url . "/";
            $tabla = "";

            $pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1;
            $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;

            $where = "WHERE 1=1 ";

            if (!empty($filtros['nombre'])) {
                $where .= " AND nombre LIKE '%" . $filtros['nombre'] . "%'";
            }
        
            $orderBy = "";
            if (!empty($filtros['orden'])) {
                $orderBy = " ORDER BY nombre " . $filtros['orden'];
            }
        
            $consulta = "SELECT * FROM tipo $where $orderBy LIMIT $inicio, $registros;";
            $consulta_total = "SELECT COUNT(*) FROM tipo $where;";

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
                            <th>Nombre</th>
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
                        <td>' . $rows['nombre'] . '</td>
                        <td>
                            <a href="' . SERVER_URL . 'tipo-update/' . $rows['id'] . '/" class="btn btn-success btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form class="FormularioAjax d-inline" action="' . SERVER_URL . 'ajax/tipoAjax.php" method="POST" data-form="delete">
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
                        <td colspan="3">
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
                        <td colspan="3">No hay registros en el sistema</td>
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
                $tabla .= '<p class="text-left">Mostrando tipo ' . $reg_inicio . ' al ' . $reg_final . ' de un total de ' . $total . '</p>';
                $tabla .= mainModel::paginador_tablas($pagina, $n_paginas, $url, 10);
            }

            return $tabla;
        }

        //---------- Eliminar un tipo -----------------//
        public function eliminar_tipo_controlador() {

            // Recibiendo id del tipo
            $id = $_POST['id_eliminar'];

            //------------ Comprobando el tipo en BD ------------//
            $check_tipo = mainModel::ejecutar_consulta_simple("SELECT id FROM tipo WHERE id = '$id';");
            if ($check_tipo->rowCount() == 0) {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'El tipo que intenta eliminar no existe en el sistema',
                    "Tipo" => 'error'
                ];
                echo json_encode($alerta);
                exit();
            }

            //------------ Comprobando los equipos asociados ------------//
            $check_equipos = mainModel::ejecutar_consulta_simple("SELECT id_tipo FROM equipo WHERE id_tipo = '$id' LIMIT 1;");
            if ($check_equipos->rowCount() > 0) {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No podemos eliminar este tipo debido a que tiene equipos asociados',
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

            $eliminar_tipo = tipoModelo::eliminar_tipo_modelo($id);

            if ($eliminar_tipo->rowCount() == 1) {
                $alerta = [
                    "Alerta" => 'recargar',
                    "Titulo" => 'Tipo eliminado',
                    "Texto" => 'El tipo ha sido eliminado del sistema exitosamente',
                    "Tipo" => 'success'
                ];
            } else {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No hemos podido eliminar el tipo, intentelo nuevamente',
                    "Tipo" => 'error'
                ];
            }
            echo json_encode($alerta);
        }

        //---------- Obtener un tipo específico -------------//
        public function obtener_tipo_controlador($id) {
            return tipoModelo::obtener_tipo_modelo($id);
        }

        //---------- Obtener la cantidad de tipos -------------//
        public function obtener_cantidad_tipos_controlador() {
            return tipoModelo::obtener_cantidad_tipos_modelo();
        }

        //---------- Obtener todos los tipos -------------//
        public function obtener_tipos_controlador() {
            return tipoModelo::obtener_tipos_modelo();
        }

        //---------- Buscar tipos para autocompletado -------------//
        public function buscar_tipos_controlador($q) {
            $q = mainModel::limpiar_cadena($q);
            $consulta = "SELECT id, nombre FROM tipo WHERE nombre LIKE '%" . $q . "%' ORDER BY nombre LIMIT 7;";
            $resultado = mainModel::ejecutar_consulta_simple($consulta);
            return $resultado->fetchAll(PDO::FETCH_ASSOC);
        }

        //---------- Actualizar los datos del tipo -------------//
        public function actualizar_tipo_controlador() {
            
            $id = $_POST['id_actualizar'];

            // Comprobar el tipo en la BD
            $check_tipo = mainModel::ejecutar_consulta_simple("SELECT * FROM tipo WHERE id = '$id'");
            if ($check_tipo->rowCount() == 0) {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No hemos encontrado el tipo en el sistema',
                    "Tipo" => 'error'
                ];
                echo json_encode($alerta);
                exit();
            } else {
                $datos = $check_tipo->fetch();
            }

            $nombre = $_POST['nombre_actualizar'];

            //---------- Comprobar campos vacios ------------//
            if ($nombre == "") {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No haz llenado todos los campos que son obligatorios",
                    "Tipo" => "error",
                ];
                echo json_encode($alerta);
                exit();
            }

            //------------ Comprobando nombre del tipo -------------//
            if ($nombre != $datos['nombre']) {
                $check_tipo = mainModel::ejecutar_consulta_simple("SELECT nombre FROM tipo WHERE nombre = '$nombre';");
                if ($check_tipo->rowCount() > 0) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "El tipo ingresado ya se encuentra registrado en el sistema",
                        "Tipo" => "error",
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            }
            
            $datos = [
                "Id" => $id,
                "Nombre" => $nombre
            ];

            $actualizar_tipo = tipoModelo::actualizar_tipo_modelo($datos);

            if ($actualizar_tipo->rowCount() == 1) {
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
