<?php

    if ($peticionAjax) {
        require_once "../modelos/modeloModelo.php";
    } else {
        require_once "./modelos/modeloModelo.php";
    }

    class modeloControlador extends modeloModelo {

        //---------- Agregar un modelo nuevo -------------//
        public function agregar_modelo_controlador() {
            session_start(['name' => 'STMH']);

            $nombre = $_POST['nombre'];
            $corriente_max = isset($_POST['corriente_max']) && $_POST['corriente_max'] !== "" ? $_POST['corriente_max'] : null;
            $temperatura_min = isset($_POST['temperatura_min']) && $_POST['temperatura_min'] != "" ? $_POST['temperatura_min'] : null;
            $temperatura_max = isset($_POST['temperatura_max']) && $_POST['temperatura_max'] != "" ? $_POST['temperatura_max'] : null;
            $vibracion_max = isset($_POST['vibracion_max']) && $_POST['vibracion_max'] != "" ? $_POST['vibracion_max'] : null;

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

            //---------- Validar que corriente_max sea mayor a 0 si fue proporcionada ------------//
            if ($corriente_max !== null && $corriente_max !== '' && $corriente_max <= 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "La corriente máxima debe ser mayor a 0",
                    "Tipo" => "error",
                ];
                echo json_encode($alerta);
                exit();
            }

            //---------- Validar que vibracion_max sea mayor o igual a 0 ------------//
            if ($vibracion_max !== null && $vibracion_max < 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "La vibración máxima debe ser mayor o igual a 0",
                    "Tipo" => "error",
                ];
                echo json_encode($alerta);
                exit();
            }

            //------------ Comprobando nombre del modelo -------------//
            $check_modelo = mainModel::ejecutar_consulta_simple("SELECT nombre FROM modelo WHERE nombre = '$nombre';");
            if ($check_modelo->rowCount() > 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El modelo ingresado ya se encuentra registrado en el sistema",
                    "Tipo" => "error",
                ];
                echo json_encode($alerta);
                exit();
            }

            $datos = [
                "Nombre" => $nombre,
                "CorrienteMax" => $corriente_max,
                "TemperaturaMin" => $temperatura_min,
                "TemperaturaMax" => $temperatura_max,
                "VibracionMax" => $vibracion_max
            ];

            $agregar_modelo = modeloModelo::agregar_modelo_modelo($datos);

            if ($agregar_modelo->rowCount() == 1) {
                $alerta = [
                    "Alerta" => "limpiar",
                    "Titulo" => "Modelo registrado",
                    "Texto" => "Los datos del modelo han sido registrados con éxito",
                    "Tipo" => "success",
                ];
                echo json_encode($alerta);
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No hemos podido registrar el modelo",
                    "Tipo" => "error",
                ];
                echo json_encode($alerta);
            }
        }

        //---------- Paginación de modelos -------------//
        public function paginar_modelo_controlador($pagina, $registros, $url, $filtros) {

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
        
            $consulta = "SELECT * FROM modelo $where $orderBy LIMIT $inicio, $registros;";
            $consulta_total = "SELECT COUNT(*) FROM modelo $where;";

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
                            <th>Corriente Máx.</th>
                            <th>Temp. Mín.</th>
                            <th>Temp. Máx.</th>
                            <th>Vibración Máx.</th>
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
                        <td>' . ($rows['corriente_max'] ?? 'N/A') . '</td>
                        <td>' . ($rows['temperatura_min'] ?? 'N/A') . '</td>
                        <td>' . ($rows['temperatura_max'] ?? 'N/A') . '</td>
                        <td>' . ($rows['vibracion_max'] ?? 'N/A') . '</td>
                        <td>
                            <a href="' . SERVER_URL . 'modelo-update/' . $rows['id'] . '/" class="btn btn-success btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form class="FormularioAjax d-inline" action="' . SERVER_URL . 'ajax/modeloAjax.php" method="POST" data-form="delete">
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
                        <td colspan="7">
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
                        <td colspan="7">No hay registros en el sistema</td>
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
                $tabla .= '<p class="text-left">Mostrando modelo ' . $reg_inicio . ' al ' . $reg_final . ' de un total de ' . $total . '</p>';
                $tabla .= mainModel::paginador_tablas($pagina, $n_paginas, $url, 10);
            }

            return $tabla;
        }

        //---------- Eliminar un modelo -----------------//
        public function eliminar_modelo_controlador() {

            // Recibiendo id del modelo
            $id = $_POST['id_eliminar'];

            //------------ Comprobando el modelo en BD ------------//
            $check_modelo = mainModel::ejecutar_consulta_simple("SELECT id FROM modelo WHERE id = '$id';");
            if ($check_modelo->rowCount() == 0) {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'El modelo que intenta eliminar no existe en el sistema',
                    "Tipo" => 'error'
                ];
                echo json_encode($alerta);
                exit();
            }

            //------------ Comprobando los equipos asociados ------------//
            $check_equipos = mainModel::ejecutar_consulta_simple("SELECT id_modelo FROM equipo WHERE id_modelo = '$id' LIMIT 1;");
            if ($check_equipos->rowCount() > 0) {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No podemos eliminar este modelo debido a que tiene equipos asociados',
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

            $eliminar_modelo = modeloModelo::eliminar_modelo_modelo($id);

            if ($eliminar_modelo->rowCount() == 1) {
                $alerta = [
                    "Alerta" => 'recargar',
                    "Titulo" => 'Modelo eliminado',
                    "Texto" => 'El modelo ha sido eliminado del sistema exitosamente',
                    "Tipo" => 'success'
                ];
            } else {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No hemos podido eliminar el modelo, intentelo nuevamente',
                    "Tipo" => 'error'
                ];
            }
            echo json_encode($alerta);
        }

        //---------- Obtener un modelo específico -------------//
        public function obtener_modelo_controlador($id) {
            return modeloModelo::obtener_modelo_modelo($id);
        }

        //---------- Obtener la cantidad de modelos -------------//
        public function obtener_cantidad_modelos_controlador() {
            return modeloModelo::obtener_cantidad_modelos_modelo();
        }

        //---------- Obtener todos los modelos -------------//
        public function obtener_modelos_controlador() {
            return modeloModelo::obtener_modelos_modelo();
        }

        //---------- Buscar modelos para autocompletado -------------//
        public function buscar_modelos_controlador($q) {
            $q = mainModel::limpiar_cadena($q);
            $consulta = "SELECT id, nombre FROM modelo WHERE nombre LIKE '%" . $q . "%' ORDER BY nombre LIMIT 7;";
            $resultado = mainModel::ejecutar_consulta_simple($consulta);
            return $resultado->fetchAll(PDO::FETCH_ASSOC);
        }

        //---------- Actualizar los datos del modelo -------------//
        public function actualizar_modelo_controlador() {
            
            $id = $_POST['id_actualizar'];

            // Comprobar el modelo en la BD
            $check_modelo = mainModel::ejecutar_consulta_simple("SELECT * FROM modelo WHERE id = '$id'");
            if ($check_modelo->rowCount() == 0) {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No hemos encontrado el modelo en el sistema',
                    "Tipo" => 'error'
                ];
                echo json_encode($alerta);
                exit();
            } else {
                $datos = $check_modelo->fetch();
            }

            $nombre = $_POST['nombre_actualizar'];
            $corriente_max = isset($_POST['corriente_max_actualizar']) && $_POST['corriente_max_actualizar'] !== "" ? $_POST['corriente_max_actualizar'] : null;
            $temperatura_min = isset($_POST['temperatura_min_actualizar']) && $_POST['temperatura_min_actualizar'] != "" ? $_POST['temperatura_min_actualizar'] : null;
            $temperatura_max = isset($_POST['temperatura_max_actualizar']) && $_POST['temperatura_max_actualizar'] != "" ? $_POST['temperatura_max_actualizar'] : null;
            $vibracion_max = isset($_POST['vibracion_max_actualizar']) && $_POST['vibracion_max_actualizar'] != "" ? $_POST['vibracion_max_actualizar'] : null;

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

            //---------- Validar que corriente_max sea mayor a 0 si fue proporcionada ------------//
            if ($corriente_max !== null && $corriente_max !== '' && $corriente_max <= 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "La corriente máxima debe ser mayor a 0",
                    "Tipo" => "error",
                ];
                echo json_encode($alerta);
                exit();
            }

            //---------- Validar que vibracion_max sea mayor o igual a 0 ------------//
            if ($vibracion_max !== null && $vibracion_max < 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "La vibración máxima debe ser mayor o igual a 0",
                    "Tipo" => "error",
                ];
                echo json_encode($alerta);
                exit();
            }

            $datos = [
                "Id" => $id,
                "Nombre" => $nombre,
                "CorrienteMax" => $corriente_max,
                "TemperaturaMin" => $temperatura_min,
                "TemperaturaMax" => $temperatura_max,
                "VibracionMax" => $vibracion_max
            ];

            $actualizar_modelo = modeloModelo::actualizar_modelo_modelo($datos);

            if ($actualizar_modelo->rowCount() == 1) {
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
