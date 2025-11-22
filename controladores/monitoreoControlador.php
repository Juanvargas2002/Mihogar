<?php

    if ($peticionAjax) {
        require_once "../modelos/monitoreoModelo.php";
    } else {
        require_once "./modelos/monitoreoModelo.php";
    }

    class monitoreoControlador extends monitoreoModelo {

        //---------- Agregar un monitoreo nuevo -------------//
        public function agregar_monitoreo_controlador() {
            session_start(['name' => 'STMH']);

            $id_equipo = $_POST['id_equipo'];
            $duracion = mainModel::limpiar_cadena($_POST['duracion']);
            $corriente_max = mainModel::limpiar_cadena($_POST['corriente_max']);
            $temperatura_min = isset($_POST['temperatura_min']) && $_POST['temperatura_min'] != "" ? mainModel::limpiar_cadena($_POST['temperatura_min']) : null;
            $temperatura_max = isset($_POST['temperatura_max']) && $_POST['temperatura_max'] != "" ? mainModel::limpiar_cadena($_POST['temperatura_max']) : null;
            $vibracion_max = isset($_POST['vibracion_max']) && $_POST['vibracion_max'] != "" ? mainModel::limpiar_cadena($_POST['vibracion_max']) : null;
            $comentario = isset($_POST['comentario']) && $_POST['comentario'] != "" ? mainModel::limpiar_cadena($_POST['comentario']) : null;
            $grafico_corr = isset($_POST['grafico_corr']) ? $_POST['grafico_corr'] : '';
            $grafico_temp = isset($_POST['grafico_temp']) ? $_POST['grafico_temp'] : '';

            //---------- Comprobar campos vacíos ------------//
            if ($id_equipo == "" || $duracion == "" || $corriente_max == "") {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error",
                    "Texto" => "No has llenado todos los campos obligatorios",
                    "Tipo" => "error",
                ];
                echo json_encode($alerta);
                exit();
            }

            //------------ Verificar que el equipo existe -------------//
            $check_equipo = mainModel::ejecutar_consulta_simple("SELECT id FROM equipo WHERE id = '$id_equipo'");
            if ($check_equipo->rowCount() == 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error",
                    "Texto" => "El equipo seleccionado no existe en el sistema",
                    "Tipo" => "error",
                ];
                echo json_encode($alerta);
                exit();
            }

            //---------- Validar corriente máxima ------------//
            if ($corriente_max <= 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error",
                    "Texto" => "La corriente máxima debe ser mayor a 0",
                    "Tipo" => "error",
                ];
                echo json_encode($alerta);
                exit();
            }

            // Guardar gráficas como imágenes
            $ruta_grafico_corr = $this->guardar_grafica($grafico_corr, 'corriente');
            $ruta_grafico_temp = $this->guardar_grafica($grafico_temp, 'temperatura');

            //---------- Ajustando Zona horaria------------//
            date_default_timezone_set('America/Bogota');

            $datos = [
                "FechaHora" => date('Y-m-d H:i:s'),
                "Duracion" => $duracion,
                "CorrienteMax" => $corriente_max,
                "TemperaturaMin" => $temperatura_min,
                "TemperaturaMax" => $temperatura_max,
                "VibracionMax" => $vibracion_max,
                "GraficoCorr" => $ruta_grafico_corr,
                "GraficoTemp" => $ruta_grafico_temp,
                "Comentario" => $comentario,
                "IdUsuario" => $_SESSION['id_usuario'],
                "IdEquipo" => $id_equipo
            ];

            $agregar_monitoreo = monitoreoModelo::agregar_monitoreo_modelo($datos);

            if ($agregar_monitoreo->rowCount() == 1) {
                $alerta = [
                    "Alerta" => "limpiar",
                    "Titulo" => "Monitoreo registrado",
                    "Texto" => "El monitoreo se registró correctamente",
                    "Tipo" => "success",
                ];
                echo json_encode($alerta);
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error",
                    "Texto" => "No se pudo registrar el monitoreo",
                    "Tipo" => "error",
                ];
                echo json_encode($alerta);
            }
        }

        //---------- Función para guardar gráficas -------------//
        private function guardar_grafica($base64Data, $tipo) {
            if (empty($base64Data)) {
                return null;
            }

            // Crear directorio si no existe
            $directorio = '../vistas/img/graficas/';
            if (!file_exists($directorio)) {
                mkdir($directorio, 0777, true);
            }

            // Extraer datos de la imagen base64
            $imagenData = str_replace('data:image/png;base64,', '', $base64Data);
            $imagenData = str_replace(' ', '+', $imagenData);
            $imagenBinaria = base64_decode($imagenData);

            // Generar nombre único
            $nombreArchivo = $tipo . '_' . time() . '_' . rand(1000, 9999) . '.png';
            $rutaCompleta = $directorio . $nombreArchivo;

            // Guardar archivo
            if (file_put_contents($rutaCompleta, $imagenBinaria)) {
                return 'vistas/img/graficas/' . $nombreArchivo;
            }

            return null;
        }

        //---------- Paginación de monitoreos -------------//
        public function paginar_monitoreo_controlador($pagina, $registros, $url, $filtros) {

            $url = SERVER_URL . $url . "/";
            $tabla = "";

            $pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1;
            $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;

            $where = "WHERE 1=1 ";

            if (!empty($filtros['num_serie'])) {
                $where .= " AND e.num_serie LIKE '%" . $filtros['num_serie'] . "%'";
            }

            if (!empty($filtros['id_monitoreo'])) {
                $where .= " AND m.id = '" . intval($filtros['id_monitoreo']) . "'";
            }

            if (!empty($filtros['cliente'])) {
                $where .= " AND c.nombre LIKE '%" . $filtros['cliente'] . "%'";
            }

            if (!empty($filtros['tecnico'])) {
                $where .= " AND u.nombre LIKE '%" . $filtros['tecnico'] . "%'";
            }

            if (!empty($filtros['fecha_inicio'])) {
                $where .= " AND DATE(m.fecha_hora) >= '" . $filtros['fecha_inicio'] . "'";
            }

            if (!empty($filtros['fecha_fin'])) {
                $where .= " AND DATE(m.fecha_hora) <= '" . $filtros['fecha_fin'] . "'";
            }

            $orderBy = " ORDER BY m.fecha_hora DESC";

            $consulta = "SELECT 
                m.*,
                e.num_serie,
                ma.nombre AS marca_nombre,
                t.nombre AS tipo_nombre,
                mo.nombre AS modelo_nombre,
                c.nombre AS cliente_nombre,
                u.nombre AS tecnico_nombre
                FROM monitoreo m
                INNER JOIN equipo e ON m.id_equipo = e.id
                INNER JOIN marca ma ON e.id_marca = ma.id
                INNER JOIN tipo t ON e.id_tipo = t.id
                INNER JOIN modelo mo ON e.id_modelo = mo.id
                INNER JOIN cliente c ON e.id_cliente = c.id
                INNER JOIN usuario u ON m.id_usuario = u.id
                $where $orderBy LIMIT $inicio, $registros";
            
            $consulta_total = "SELECT COUNT(*) FROM monitoreo m 
                INNER JOIN equipo e ON m.id_equipo = e.id
                INNER JOIN cliente c ON e.id_cliente = c.id
                INNER JOIN usuario u ON m.id_usuario = u.id
                $where";

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
                        <tr class="text-center">
                            <th>Numero de prueba</th>
                            <th>Fecha y hora</th>
                            <th>Equipo</th>
                            <th>Cliente</th>
                            <th>Técnico</th>
                            <th>Duración</th>
                            <th>Corriente Promedio</th>
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
                        <tr class="text-center">
                            <td>' . $rows['id'] . '</td>
                            <td>' . date('d/m/Y H:i', strtotime($rows['fecha_hora'])) . '</td>
                            <td>' . $rows['num_serie'] . '</td>
                            <td>' . $rows['cliente_nombre'] . '</td>
                            <td>' . $rows['tecnico_nombre'] . '</td>
                            <td>' . $rows['duracion'] . '</td>
                            <td>' . $rows['corriente_max'] . ' A</td>
                            <td>
                                <a href="' . SERVER_URL . 'consulta-monitoreos/' . $rows['id'] . '/" class="btn btn-info btn-sm" title="Ver Detalles">
                                    <i class="fas fa-eye"></i>
                                </a>';

                    if ($_SESSION['rol_usuario'] == "Administrador") {
                        $tabla .= '
                                <form class="FormularioAjax" action="' . SERVER_URL . 'ajax/monitoreoAjax.php" method="POST" data-form="delete" style="display: inline-block;">
                                    <input type="hidden" name="id_eliminar" value="' . $rows['id'] . '">
                                    <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>';
                    }

                    $tabla .= '
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
                                <a href="' . $url . '1/" class="btn btn-primary btn-sm">
                                    Haga clic aquí para recargar el listado
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
                $tabla .= '<p class="text-left">Mostrando monitoreo ' . $reg_inicio . ' al ' . $reg_final . ' de un total de ' . $total . '</p>';
                $tabla .= mainModel::paginador_tablas($pagina, $n_paginas, $url, 10);
            }

            return $tabla;
        }

        //---------- Eliminar un monitoreo -----------------//
        public function eliminar_monitoreo_controlador() {

            // Recibiendo id del monitoreo
            $id = mainModel::limpiar_cadena($_POST['id_eliminar']);

            //------------ Comprobando el monitoreo en BD ------------//
            $check_monitoreo = mainModel::ejecutar_consulta_simple("SELECT * FROM monitoreo WHERE id = '$id'");
            if ($check_monitoreo->rowCount() == 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error",
                    "Texto" => "El monitoreo no existe en el sistema",
                    "Tipo" => "error",
                ];
                echo json_encode($alerta);
                exit();
            }

            $datos_monitoreo = $check_monitoreo->fetch();

            //------------ Comprobando rol -----------------//
            session_start(['name' => 'STMH']);
            if ($_SESSION['rol_usuario'] != "Administrador") {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error",
                    "Texto" => "No tienes permisos para realizar esta acción",
                    "Tipo" => "error",
                ];
                echo json_encode($alerta);
                exit();
            }

            // Eliminar archivos de gráficas si existen
            if (!empty($datos_monitoreo['grafico_corr']) && file_exists('../' . $datos_monitoreo['grafico_corr'])) {
                unlink('../' . $datos_monitoreo['grafico_corr']);
            }
            if (!empty($datos_monitoreo['grafico_temp']) && file_exists('../' . $datos_monitoreo['grafico_temp'])) {
                unlink('../' . $datos_monitoreo['grafico_temp']);
            }

            $eliminar_monitoreo = monitoreoModelo::eliminar_monitoreo_modelo($id);

            if ($eliminar_monitoreo->rowCount() == 1) {
                $alerta = [
                    "Alerta" => "recargar",
                    "Titulo" => "Monitoreo eliminado",
                    "Texto" => "El monitoreo fue eliminado del sistema correctamente",
                    "Tipo" => "success",
                ];
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error",
                    "Texto" => "No se pudo eliminar el monitoreo",
                    "Tipo" => "error",
                ];
            }
            echo json_encode($alerta);
        }

        //---------- Obtener un monitoreo específico -------------//
        public function obtener_monitoreo_controlador($id) {
            $id = mainModel::limpiar_cadena($id);
            return monitoreoModelo::obtener_monitoreo_modelo($id);
        }

        //---------- Obtener la cantidad de monitoreos -------------//
        public function obtener_cantidad_monitoreos_controlador() {
            return monitoreoModelo::obtener_cantidad_monitoreos_modelo();
        }
    }
