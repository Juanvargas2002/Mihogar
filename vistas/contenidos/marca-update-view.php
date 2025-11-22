<!-- Actualizar Marca -->
<div class="container-fluid">
    <div class="card shadow mb-4 mx-auto" style="max-width: 600px;">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Editar Marca</h6>

            <!-- Boton para regresar -->
            <a href="<?php echo SERVER_URL; ?>marcas/" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-right"></i>
                </span>
                <span class="text">Regresar</span>
            </a>
        </div>
        
        <div class="card-body">
            <?php
                require_once "./controladores/marcaControlador.php";
                $ins_marca = new marcaControlador();
                $datos_marca = $ins_marca->obtener_marca_controlador($pagina[1]);

                if($datos_marca->rowCount() == 1) {
                    $campos = $datos_marca->fetch();
            ?>
            <!-- Formulario para actualizar marca -->
            <form class="FormularioAjax" action="<?php echo SERVER_URL ?>ajax/marcaAjax.php" method="POST" data-form="update">
                
                <!-- Id -->
                <div class="mb-3">
                    <input type="number" name="id_actualizar" id="id_actualizar" class="form-control" value="<?php echo $pagina[1]; ?>" hidden readonly>
                </div>

                <!-- Nombre -->
                <div class="mb-3">
                    <label for="nombre_actualizar" class="form-label">Nombre</label>
                    <input type="text" name="nombre_actualizar" id="nombre_actualizar" class="form-control" value="<?php echo $campos['nombre']; ?>" required>
                </div>

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