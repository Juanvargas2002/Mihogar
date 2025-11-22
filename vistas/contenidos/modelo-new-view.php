<!-- Registro de Modelos -->
<div class="container-fluid">
    <div class="card shadow mb-4 mx-auto" style="max-width: 600px;">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Registrar Modelo</h6>
        </div>

        <div class="card-body">

            <!-- Formulario de registro -->
            <form class="FormularioAjax" action="<?php echo SERVER_URL ?>ajax/modeloAjax.php" method="POST" data-form="save">
                
                <!-- Nombre -->
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" id="nombre" class="form-control" required>
                </div>

                <!-- Corriente Máxima (opcional) -->
                <div class="mb-3">
                    <label for="corriente_max" class="form-label">Corriente Máxima (A)</label>
                    <input type="number" min="0" step="0.01" name="corriente_max" id="corriente_max" class="form-control">
                </div>

                <!-- Temperatura Mínima -->
                <div class="mb-3">
                    <label for="temperatura_min" class="form-label">Temperatura Mínima (°C)</label>
                    <input type="number" min="-50" step="0.01" name="temperatura_min" id="temperatura_min" class="form-control">
                </div>

                <!-- Temperatura Máxima -->
                <div class="mb-3">
                    <label for="temperatura_max" class="form-label">Temperatura Máxima (°C)</label>
                    <input type="number" max="85" step="0.01" name="temperatura_max" id="temperatura_max" class="form-control">
                </div>

                <!-- Vibración Máxima -->
                <div class="mb-3">
                    <label for="vibracion_max" class="form-label">Vibración Máxima (mm/s)</label>
                    <input type="number" step="0.01" name="vibracion_max" id="vibracion_max" class="form-control">
                </div>

                <p class="text-muted"><span class="text-danger">*</span> Campos obligatorios</p>

                <!-- Botón añadir Modelo -->
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
