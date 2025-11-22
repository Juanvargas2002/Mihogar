<!-- Actualizar Cliente -->
<div class="container-fluid">
    <div class="card shadow mb-4 mx-auto" style="max-width: 600px;">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Editar Cliente</h6>

            <!-- Boton para regresar -->
            <a href="<?php echo SERVER_URL; ?>clientes/" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-right"></i>
                </span>
                <span class="text">Regresar</span>
            </a>
        </div>
        
        <div class="card-body">
            <?php
                require_once "./controladores/clienteControlador.php";
                $ins_cliente = new clienteControlador();
                $datos_cliente = $ins_cliente->obtener_cliente_controlador($pagina[1]);

                if($datos_cliente->rowCount() == 1) {
                    $campos = $datos_cliente->fetch();
            ?>
            <!-- Formulario para actualizar cliente -->
            <form class="FormularioAjax" action="<?php echo SERVER_URL ?>ajax/clienteAjax.php" method="POST" data-form="update">
                
                <!-- Id -->
                <div class="mb-3">
                    <input type="number" name="id_actualizar" id="id_actualizar" class="form-control" value="<?php echo $pagina[1]; ?>" hidden readonly>
                </div>

                <!-- Cédula -->
                <div class="mb-3">
                    <label for="cedula_actualizar" class="form-label">Cédula <span class="text-danger">*</span></label>
                    <input type="number" name="cedula_actualizar" id="cedula_actualizar" class="form-control" maxlength="20" value="<?php echo $campos['cedula']; ?>" required>
                </div>

                <!-- Nombre -->
                <div class="mb-3">
                    <label for="nombre_actualizar" class="form-label">Nombre <span class="text-danger">*</span></label>
                    <input type="text" name="nombre_actualizar" id="nombre_actualizar" class="form-control" maxlength="100" value="<?php echo $campos['nombre']; ?>" required>
                </div>

                <!-- Contacto -->
                <div class="mb-3">
                    <label for="contacto_actualizar" class="form-label">Número de Teléfono <span class="text-danger">*</span></label>
                    <input type="number" name="contacto_actualizar" id="contacto_actualizar" class="form-control" maxlength="50" value="<?php echo $campos['contacto']; ?>" required>
                </div>

                <!-- Correo electrónico -->
                <div class="mb-3">
                    <label for="correo_actualizar" class="form-label">Correo Electrónico</label>
                    <input type="email" name="correo_actualizar" id="correo_actualizar" class="form-control" maxlength="50" value="<?php echo $campos['correo']; ?>">
                </div>

                <!-- Dirección -->
                <div class="mb-3">
                    <label for="direccion_actualizar" class="form-label">Dirección</label>
                    <input type="text" name="direccion_actualizar" id="direccion_actualizar" class="form-control" maxlength="150" value="<?php echo isset($campos['direccion']) ? $campos['direccion'] : ''; ?>">
                </div>

                <p class="text-muted"><span class="text-danger">*</span> Campos obligatorios</p>

                <!-- Actualizar -->
                <button type="submit" class="btn btn-primary btn-icon-split">
                    <span class="icon text-white-50">
                        <i class="fas fa-check"></i>
                    </span>
                    <span class="text">Actualizar</span>
                </button>
            </form>
            <?php }?>
        </div>
    </div>
</div>
