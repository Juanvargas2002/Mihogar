<!-- Registro de Clientes -->
<div class="container-fluid">
    <div class="card shadow mb-4 mx-auto" style="max-width: 600px;">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Registrar Cliente</h6>
        </div>

        <div class="card-body">

            <!-- Formulario de registro -->
            <form class="FormularioAjax" action="<?php echo SERVER_URL ?>ajax/clienteAjax.php" method="POST" data-form="save">
                
                <!-- Cédula -->
                <div class="mb-3">
                    <label for="cedula" class="form-label">Cédula <span class="text-danger">*</span></label>
                    <input type="number" name="cedula" id="cedula" class="form-control" maxlength="20" required>
                </div>

                <!-- Nombre -->
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" id="nombre" class="form-control" maxlength="100" required>
                </div>

                <!-- Contacto -->
                <div class="mb-3">
                    <label for="contacto" class="form-label">Número de Teléfono <span class="text-danger">*</span></label>
                    <input type="number" name="contacto" id="contacto" class="form-control" maxlength="50" required>
                </div>

                <!-- Correo electrónico -->
                <div class="mb-3">
                    <label for="correo" class="form-label">Correo Electrónico</label>
                    <input type="email" name="correo" id="correo" class="form-control" maxlength="50">
                </div>

                <!-- Dirección -->
                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <input type="text" name="direccion" id="direccion" class="form-control" maxlength="150">
                </div>

                <p class="text-muted"><span class="text-danger">*</span> Campos obligatorios</p>

                <!-- Botón añadir Cliente -->
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
