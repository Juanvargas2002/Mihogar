<?php
if ($_SESSION['rol_usuario'] != "Administrador") {
    echo $lc->forzar_cierre_sesion_controlador();
    exit();
}
?>
<!-- Registro de usuarios -->
<div class="container-fluid">
    <div class="card shadow mb-4 mx-auto" style="max-width: 600px;">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Registrar Usuario</h6>
        </div>

        <div class="card-body">

            <!-- Formulario de registro -->
            <form class="FormularioAjax" action="<?php echo SERVER_URL ?>ajax/usuarioAjax.php" method="POST" data-form="save">

                <!-- Identificación del empleado -->
                <div class="mb-3">
                    <label for="identificacion" class="form-label">Identificación <span class="text-danger">*</span></label>
                    <input type="text" name="identificacion" id="identificacion" class="form-control" maxlength="20" required>
                </div>

                <!-- Nombre de empleado -->
                <div class="mb-3">
                    <label for="nombre_empleado" class="form-label">Nombre del empleado <span class="text-danger">*</span></label>
                    <input type="text" name="nombre_empleado" id="nombre_empleado" class="form-control" maxlength="50" required>
                </div>

                <!-- Nombre de usuario -->
                <div class="mb-3">
                    <label for="usuario" class="form-label">Nombre de usuario <span class="text-danger">*</span></label>
                    <input type="text" name="usuario" id="usuario" class="form-control" maxlength="20" required>
                </div>

                <!-- Contraseña -->
                <div class="mb-3">
                    <label for="contrasena" class="form-label">Contraseña <span class="text-danger">*</span></label>
                    <input type="password" name="contrasena" id="contrasena" class="form-control" maxlength="20" required>
                </div>

                <!-- Rol -->
                <div class="mb-3">
                    <label for="rol" class="form-label">Rol</label>
                    <input type="text" name="rol" id="rol" class="form-control" value="Técnico" readonly>
                </div>

                <p class="text-muted"><span class="text-danger">*</span> Campos obligatorios</p>

                <!-- Boton añadir -->
                <button type="submit" class="btn btn-primary btn-icon-split">
                    <span class="icon text-white-50">
                        <i class="fas fa-check"></i>
                    </span>
                    <span class="text">Añadir</span>
                </button>
            </form>
        </div>
    </div>
</div>