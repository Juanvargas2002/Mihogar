<?php
if ($_SESSION['rol_usuario'] != "Administrador") {
    echo $lc->forzar_cierre_sesion_controlador();
    exit();
}
?>

<!-- Usuarios -->
<div class="container-fluid">

    <!-- Cabecera de página -->
    <div class="d-flex align-items-center mb-4">
        <h1 class="h3 text-gray-800 mr-3">Usuarios</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Búsqueda de usuarios</h6>
        </div>

        <div class="card-body">

            <!-- Formulario de filtros -->
            <form id="filtroUsuarios" method="POST" action="<?php echo SERVER_URL; ?>usuarios/">
                <div class="row">

                    <!-- Id -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_id">Identificación</label>
                            <input type="text" name="identificacion" id="filtro_id" class="form-control" value="<?php echo isset($_POST['identificacion']) ? htmlspecialchars($_POST['identificacion'], ENT_QUOTES, 'UTF-8') : ''; ?>">
                        </div>
                    </div>

                    <!-- Nombre -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_nombre">Nombre</label>
                            <input type="text" name="nombre_empleado" id="filtro_nombre" class="form-control" value="<?php echo isset($_POST['nombre_empleado']) ? htmlspecialchars($_POST['nombre_empleado'], ENT_QUOTES, 'UTF-8') : ''; ?>">
                        </div>
                    </div>

                    <!-- Botones (Buscar, Reestablecer) -->
                    <div class="col-md-3 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary mr-2">Buscar</button>
                        <a href="<?php echo SERVER_URL; ?>usuarios/" class="btn btn-secondary">Restablecer</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla Usuarios -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Listado de Usuarios</h6>
        </div>

        <div class="card-body">
            <?php
            require_once "./controladores/usuarioControlador.php";
            $ins_usuario = new usuarioControlador();
            $filtros = [
                'identificacion' => isset($_POST['identificacion']) ? trim($_POST['identificacion']) : '',
                'nombre_empleado' => isset($_POST['nombre_empleado']) ? trim($_POST['nombre_empleado']) : ''
            ];
            
            echo $ins_usuario->paginar_usuario_controlador($pagina[1], 10, $pagina[0], $filtros);
            ?>
        </div>
    </div>
</div>