<?php

    if ($peticionAjax) {
        require_once "../modelos/marcaModelo.php";
    } else {
        require_once "./modelos/marcaModelo.php";
    }

    class marcaControlador extends marcaModelo {

        //---------- Agregar una marca nueva -------------//
        public function agregar_marca_controlador() {
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

            //------------ Comprobando nombre de la marca -------------//
            $check_marca = mainModel::ejecutar_consulta_simple("SELECT nombre FROM marca WHERE nombre = '$nombre';");
            if ($check_marca->rowCount() > 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "La marca ingresada ya se encuentra registrada en el sistema",
                    "Tipo" => "error",
                ];
                echo json_encode($alerta);
                exit();
            }

            $datos = [
                "Nombre" => $nombre
            ];

            $agregar_marca = marcaModelo::agregar_marca_modelo($datos);

            if ($agregar_marca->rowCount() == 1) {
                $alerta = [
                    "Alerta" => "limpiar",
                    "Titulo" => "marca registrada",
                    "Texto" => "Los datos de la marca han sido registrados con éxito",
                    "Tipo" => "success",
                ];
                echo json_encode($alerta);
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No hemos podido registrar la marca",
                    "Tipo" => "error",
                ];
                echo json_encode($alerta);
            }
        }

        //---------- Paginación de marcas -------------//
        public function paginar_marca_controlador($pagina, $registros, $url, $filtros) {

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
        
            $consulta = "SELECT * FROM marca $where $orderBy LIMIT $inicio, $registros;";
            $consulta_total = "SELECT COUNT(*) FROM marca $where;";

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
                            <th scope="col">Id</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Acciones</th>
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
                            <a href="' . SERVER_URL . 'marca-update/' . $rows['id'] . '/" class="btn btn-success btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form class="FormularioAjax d-inline" action="' . SERVER_URL . 'ajax/marcaAjax.php" method="POST" data-form="delete">
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
                        <td colspan="4">
                            <a href="' . $url . '1/" class="btn btn-primary">
                                Haga clic acá para recargar el listado
                            </a>
                        </td>
                    </tr>
                    ';
                } else {
                    $tabla .= '
                    <tr class="text-center">
                        <td colspan="4">No hay registros en el sistema</td>
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
                $tabla .= '<p class="text-left">Mostrando marca ' . $reg_inicio . ' al ' . $reg_final . ' de un total de ' . $total . '</p>';
                $tabla .= mainModel::paginador_tablas($pagina, $n_paginas, $url, 10);
            }

            return $tabla;
        }

        //---------- Eliminar una marca -----------------//
        public function eliminar_marca_controlador() {

            // Recibiendo id de la marca
            $id = $_POST['id_eliminar'];

            //------------ Comprobando la marca en BD ------------//
            $check_marca = mainModel::ejecutar_consulta_simple("SELECT id FROM marca WHERE id = '$id';");
            if ($check_marca->rowCount() == 0) {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'La marca que intenta eliminar no existe en el sistema',
                    "Tipo" => 'error'
                ];
                echo json_encode($alerta);
                exit();
            }

            //------------ Comprobando los equipos asociados ------------//
            $check_equipos = mainModel::ejecutar_consulta_simple("SELECT id_marca FROM equipo WHERE id_marca = '$id' LIMIT 1;");
            if ($check_equipos->rowCount() > 0) {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No podemos eliminar esta marca debido a que tiene equipos asociados',
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

            $eliminar_marca = marcaModelo::eliminar_marca_modelo($id);

            if ($eliminar_marca->rowCount() == 1) {
                $alerta = [
                    "Alerta" => 'recargar',
                    "Titulo" => 'marca eliminada',
                    "Texto" => 'La marca ha sido eliminada del sistema exitosamente',
                    "Tipo" => 'success'
                ];
            } else {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No hemos podido eliminar la marca, intentelo nuevamente',
                    "Tipo" => 'error'
                ];
            }
            echo json_encode($alerta);
        }

        //---------- Obtener una marca específica -------------//
        public function obtener_marca_controlador($id) {
            return marcaModelo::obtener_marca_modelo($id);
        }

        //---------- Obtener la cantidad de marcas -------------//
        public function obtener_cantidad_marcas_controlador() {
            return marcaModelo::obtener_cantidad_marcas_modelo();
        }

        //---------- Obtener todas las marcas -------------//
        public function obtener_marcas_controlador() {
            return marcaModelo::obtener_marcas_modelo();
        }

        //---------- Buscar marcas para autocompletado -------------//
        public function buscar_marcas_controlador($q) {
            $q = mainModel::limpiar_cadena($q);
            $consulta = "SELECT id, nombre FROM marca WHERE nombre LIKE '%" . $q . "%' ORDER BY nombre LIMIT 3;";
            $resultado = mainModel::ejecutar_consulta_simple($consulta);
            return $resultado->fetchAll(PDO::FETCH_ASSOC);
        }

        //---------- Actualizar los datos de la marca -------------//
        public function actualizar_marca_controlador() {
            
            $id = $_POST['id_actualizar'];

            // Comprobar la marca en la BD
            $check_marca = mainModel::ejecutar_consulta_simple("SELECT * FROM marca WHERE id = '$id'");
            if ($check_marca->rowCount() == 0) {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No hemos encontrado la marca en el sistema',
                    "Tipo" => 'error'
                ];
                echo json_encode($alerta);
                exit();
            } else {
                $datos = $check_marca->fetch();
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

            //------------ Comprobando nombre de la marca -------------//
            if ($nombre != $datos['nombre']) {
                $check_marca = mainModel::ejecutar_consulta_simple("SELECT nombre FROM marca WHERE nombre = '$nombre';");
                if ($check_marca->rowCount() > 0) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "La marca ingresada ya se encuentra registrada en el sistema",
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

            $actualizar_marca = marcaModelo::actualizar_marca_modelo($datos);

            if ($actualizar_marca->rowCount() == 1) {
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