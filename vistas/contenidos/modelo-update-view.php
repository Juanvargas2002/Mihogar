<!-- Actualizar Modelo -->
<div class="container-fluid">
    <div class="card shadow mb-4 mx-auto" style="max-width: 600px;">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Editar Modelo</h6>

            <!-- Boton para regresar -->
            <a href="<?php echo SERVER_URL; ?>modelos/" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-right"></i>
                </span>
                <span class="text">Regresar</span>
            </a>
        </div>
        
        <div class="card-body">
            <?php
                require_once "./controladores/modeloControlador.php";
                $ins_modelo = new modeloControlador();
                $datos_modelo = $ins_modelo->obtener_modelo_controlador($pagina[1]);

                if($datos_modelo->rowCount() == 1) {
                    $campos = $datos_modelo->fetch();
            ?>
            <!-- Formulario para actualizar modelo -->
            <form class="FormularioAjax" action="<?php echo SERVER_URL ?>ajax/modeloAjax.php" method="POST" data-form="update">
                
                <!-- Id -->
                <div class="mb-3">
                    <input type="number" name="id_actualizar" id="id_actualizar" class="form-control" value="<?php echo $pagina[1]; ?>" hidden readonly>
                </div>

                <!-- Nombre -->
                <div class="mb-3">
                    <label for="nombre_actualizar" class="form-label">Nombre <span class="text-danger">*</span></label>
                    <input type="text" name="nombre_actualizar" id="nombre_actualizar" class="form-control" value="<?php echo $campos['nombre']; ?>" required>
                </div>

                <!-- Corriente Máxima (opcional) -->
                <div class="mb-3">
                    <label for="corriente_max_actualizar" class="form-label">Corriente Máxima (A)</label>
                    <input type="number" step="0.01" name="corriente_max_actualizar" id="corriente_max_actualizar" class="form-control" value="<?php echo $campos['corriente_max']; ?>">
                </div>

                <!-- Temperatura Mínima -->
                <div class="mb-3">
                    <label for="temperatura_min_actualizar" class="form-label">Temperatura Mínima (°C)</label>
                    <input type="number" step="0.01" name="temperatura_min_actualizar" id="temperatura_min_actualizar" class="form-control" value="<?php echo $campos['temperatura_min']; ?>">
                </div>

                <!-- Temperatura Máxima -->
                <div class="mb-3">
                    <label for="temperatura_max_actualizar" class="form-label">Temperatura Máxima (°C)</label>
                    <input type="number" step="0.01" name="temperatura_max_actualizar" id="temperatura_max_actualizar" class="form-control" value="<?php echo $campos['temperatura_max']; ?>">
                </div>

                <!-- Vibración Máxima -->
                <div class="mb-3">
                    <label for="vibracion_max_actualizar" class="form-label">Vibración Máxima (mm/s)</label>
                    <input type="number" step="0.01" name="vibracion_max_actualizar" id="vibracion_max_actualizar" class="form-control" value="<?php echo $campos['vibracion_max']; ?>">
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
