<?php

    if ($peticionAjax) {
        require_once "../modelos/usuarioModelo.php";
    } else {
        require_once "./modelos/usuarioModelo.php";
    }

    class usuarioControlador extends usuarioModelo {

        //---------- Agregar un usuario -------------//
        public function agregar_usuario_controlador() {
            session_start(['name' => 'STMH']);

            $identificacion = $_POST['identificacion'];
            $nombre = $_POST['nombre_empleado'];
            $usuario = $_POST['usuario'];
            $contrasena = $_POST['contrasena'];

            //---------- Comprobar campos vacios ------------//
            if ($identificacion == "" || $nombre == "" || $usuario == "" || $contrasena == "") {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No haz llenado todos los campos que son obligatorios",
                    "Tipo"=>"error",
                ];
                echo json_encode($alerta);
                exit();
            }

            //------------ Comprobar la identificación -------------//
            $check_identificacion = mainModel::ejecutar_consulta_simple("SELECT identificacion FROM usuario WHERE identificacion = '$identificacion' LIMIT 1;");
            if ($check_identificacion->rowCount() > 0) {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"La identificación ingresada ya se encuentra registrada en el sistema",
                    "Tipo"=>"error",
                ];
                echo json_encode($alerta);
                exit();
            }

            //------------ Comprobar el nombre del usuario -------------//
            $check_usuario = mainModel::ejecutar_consulta_simple("SELECT usuario.usuario FROM usuario WHERE usuario.usuario = '$usuario' LIMIT 1;");
            if ($check_usuario->rowCount() > 0) {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El nombre de usuario ingresado ya se encuentra registrado en el sistema",
                    "Tipo"=>"error",
                ];
                echo json_encode($alerta);
                exit();
            }

            $datos_usuario = [
                "Identificacion"=>$identificacion,
                "Nombre"=>$nombre,
                "Usuario"=>$usuario,
                "Contrasena"=>$contrasena
            ];

            $agregar_usuario = usuarioModelo::agregar_usuario_modelo($datos_usuario);

            if ($agregar_usuario->rowCount() == 1) {
                $alerta = [
                    "Alerta"=>"limpiar",
                    "Titulo"=>"Usuario registrado",
                    "Texto"=>"Los datos del usuario han sido registrados con éxito",
                    "Tipo"=>"success",
                ];
                echo json_encode($alerta);
            } else {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No hemos podido registrar al usuario",
                    "Tipo"=>"error",
                ];
                echo json_encode($alerta);
            }
            
        }

        //---------- Controlador para paginar usuarios -------------//
        public function paginar_usuario_controlador($pagina, $registros, $url, $filtros) {

            $url = SERVER_URL . $url . "/";
            $tabla = "";

            $pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1;
            $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;

            $where = "WHERE rol = 'Técnico'";

            // Procesar filtros
            if (isset($filtros['identificacion']) && $filtros['identificacion'] != "") {
                $where .= " AND identificacion LIKE '%" . $filtros['identificacion'] . "%'";
            }
            if (isset($filtros['nombre_empleado']) && $filtros['nombre_empleado'] != "") {
                $where .= " AND nombre LIKE '%" . $filtros['nombre_empleado'] . "%'";
            }

            $consulta = "SELECT * FROM usuario $where LIMIT $inicio, $registros;";
            $consulta_total = "SELECT COUNT(*) FROM usuario $where;";
        

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
                            <th scope="col">Identificación</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Usuario</th>
                            <th scope="col">Contraseña</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
            ';

            if ($total >= 1 && $pagina <= $n_paginas) {
                $contador = $inicio + 1;
                $reg_inicio = $inicio + 1;
                foreach ($datos as $rows) {
                    if ($_SESSION['id_usuario'] != $rows['id']) {
                        $tabla .= '
                        <tr>
                            <td>' . $rows['identificacion'] . '</td>
                            <td>' . $rows['nombre'] . '</td>
                            <td>' . $rows['usuario'] . '</td>
                            <td>' . $rows['contrasena'] . '</td>
                            <td>
                                <a href="' . SERVER_URL . 'usuario-update/' . $rows['id'] . '/" class="btn btn-success btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form class="FormularioAjax d-inline" action="' . SERVER_URL . 'ajax/usuarioAjax.php" method="POST" data-form="delete">
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
                }
                $reg_final = $contador - 1;
            } else {
                if ($total >= 1) {
                    $tabla .= '
                    <tr class="text-center">
                        <td colspan="5">
                            <a href="' . $url . '1/" class="btn btn-primary">
                                Haga clic acá para recargar el listado
                            </a>
                        </td>
                    </tr>
                    ';
                } else {
                    $tabla .= '
                    <tr class="text-center">
                        <td colspan="5">No hay registros en el sistema</td>
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
                $tabla .= '<p class="text-left">Mostrando usuario ' . $reg_inicio . ' al ' . $reg_final . ' de un total de ' . $total . '</p>';
                $tabla .= mainModel::paginador_tablas($pagina, $n_paginas, $url, 10);
            }

            return $tabla;
        }

        //---------- Eliminar usuario -----------------//
        public function eliminar_usuario_controlador() {
            
            // Recibiendo id del usuario
            $id = $_POST['id_eliminar'];

            // Comprobar campos vacios
            if ($id == "") {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No se encontró el id del usuario',
                    "Tipo" => 'error'
                ];
                echo json_encode($alerta);
                exit();
            }

            //------------ Comprobando el usuario en BD ------------//
            $check_usuario = mainModel::ejecutar_consulta_simple("SELECT id FROM usuario WHERE id = '$id';");
            if ($check_usuario->rowCount() == 0) {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'El usuario que intenta eliminar no existe en el sistema',
                    "Tipo" => 'error'
                ];
                echo json_encode($alerta);
                exit();
            }

            //------------ Comprobando los monitoreos asociados ------------//
            $check_ventas = mainModel::ejecutar_consulta_simple("SELECT id_usuario FROM monitoreo WHERE id_usuario = '$id' LIMIT 1;");
            if ($check_ventas->rowCount() > 0) {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No podemos eliminar este usuario debido a que tiene monitoreos asociados',
                    "Tipo" => 'error'
                ];
                echo json_encode($alerta);
                exit();
            }

            $eliminar_usuario = usuarioModelo::eliminar_usuario_modelo($id);

            if ($eliminar_usuario->rowCount() == 1) {
                $alerta = [
                    "Alerta" => 'recargar',
                    "Titulo" => 'usuario eliminado',
                    "Texto" => 'El usuario ha sido eliminado del sistema exitosamente',
                    "Tipo" => 'success'
                ];
            } else {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No hemos podido eliminar al usuario, intentelo nuevamente',
                    "Tipo" => 'error'
                ];
            }
            echo json_encode($alerta);
        }

        //---------- Obtener usuario -------------//
        public function obtener_usuario_controlador($id) {
            return usuarioModelo::obtener_usuario_modelo($id);
        }

        //---------- Obtener cantidad de usuarios -------------//
        public function obtener_cantidad_usuarios_controlador() {//el nit negocio esta directamente en el modelo (^.^)
            return usuarioModelo::obtener_cantidad_usuarios_modelo();
        }

        //---------- Obtener todos los usuarios -------------//
        public function obtener_usuarios_controlador() {//el nit negocio esta directamente en el modelo (^.^)
            return usuarioModelo::obtener_usuarios_modelo();
        }

        //---------- Actualizar los datos del usuario -------------//
        public function actualizar_usuario_controlador() {

            // Iniciar sesión
            session_start(['name' => 'STMH']);

            // Recibiendo id del usuario
            $id = $_POST['id_actualizar'];

            // Comprobar campos vacios
            if ($id == "") {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No se encontró el id del usuario',
                    "Tipo" => 'error'
                ];
                echo json_encode($alerta);
                exit();
            }

            // Comprobar al usuario en la BD
            $check_usuario = mainModel::ejecutar_consulta_simple("SELECT * FROM usuario WHERE id = $id");
            if ($check_usuario->rowCount() <= 0) {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No hemos encontrado al usuario en el sistema',
                    "Tipo" => 'error'
                ];
                echo json_encode($alerta);
                exit();
            } else {
                $datos = $check_usuario->fetch();
            }

            $identificacion = $_POST['identificacion_actualizar'];
            $nombre = $_POST['nombre_empleado_actualizar'];
            $usuario = $_POST['usuario_actualizar'];
            $contrasena = $_POST['contrasena_actualizar'];

            //---------- Comprobar campos vacios ------------//
            if ($identificacion == "" || $nombre == "" || $usuario == "" || $contrasena == "") {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No haz llenado todos los campos que son obligatorios",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }

            $datos = [
                "Id" => $id,
                "Identificacion" => $identificacion,
                "Nombre" => $nombre,
                "Usuario" => $usuario,
                "Contrasena" => $contrasena,
            ];

            $actualizar_usuario = usuarioModelo::actualizar_usuario_modelo($datos);

            if ($actualizar_usuario->rowCount() == 1) {
                $alerta = [
                    "Alerta" => "recargar",
                    "Titulo" => "Datos actualizados",
                    "Texto" => "Los datos han sido actualizados con éxito",
                    "Tipo" => "success"
                ];
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No hemos podido actualizar los datos",
                    "Tipo" => "error"
                ];
            }
            echo json_encode($alerta);
        }
    }