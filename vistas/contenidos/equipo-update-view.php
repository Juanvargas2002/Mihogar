<!-- Actualizar Equipo -->
<div class="container-fluid">
    <div class="card shadow mb-4 mx-auto" style="max-width: 800px;">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Editar Equipo</h6>

            <!-- Boton para regresar -->
            <a href="<?php echo SERVER_URL; ?>equipos/" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-right"></i>
                </span>
                <span class="text">Regresar</span>
            </a>
        </div>
        
        <div class="card-body">
            <?php
                require_once "./controladores/equipoControlador.php";
                $ins_equipo = new equipoControlador();
                $datos_equipo = $ins_equipo->obtener_equipo_controlador($pagina[1]);

                if($datos_equipo->rowCount() == 1) {
                    $campos = $datos_equipo->fetch();

                    // Obtener nombres actuales para mostrar en los inputs de autocompletado
                    require_once "./controladores/marcaControlador.php";
                    $ins_marca = new marcaControlador();
                    $marca_actual = $ins_marca->obtener_marca_controlador($campos['id_marca']);
                    $marca_nombre = '';
                    if ($marca_actual && $marca_actual->rowCount() == 1) {
                        $tmp = $marca_actual->fetch();
                        $marca_nombre = $tmp['nombre'];
                    }

                    require_once "./controladores/tipoControlador.php";
                    $ins_tipo = new tipoControlador();
                    $tipo_actual = $ins_tipo->obtener_tipo_controlador($campos['id_tipo']);
                    $tipo_nombre = '';
                    if ($tipo_actual && $tipo_actual->rowCount() == 1) {
                        $tmp = $tipo_actual->fetch();
                        $tipo_nombre = $tmp['nombre'];
                    }

                    require_once "./controladores/modeloControlador.php";
                    $ins_modelo = new modeloControlador();
                    $modelo_actual = $ins_modelo->obtener_modelo_controlador($campos['id_modelo']);
                    $modelo_nombre = '';
                    if ($modelo_actual && $modelo_actual->rowCount() == 1) {
                        $tmp = $modelo_actual->fetch();
                        $modelo_nombre = $tmp['nombre'];
                    }

                    require_once "./controladores/clienteControlador.php";
                    $ins_cliente = new clienteControlador();
                    $cliente_actual = $ins_cliente->obtener_cliente_controlador($campos['id_cliente']);
                    $cliente_display = '';
                    if ($cliente_actual && $cliente_actual->rowCount() == 1) {
                        $tmp = $cliente_actual->fetch();
                        $cliente_display = $tmp['cedula'] . ' - ' . $tmp['nombre'];
                    }
            ?>
            <!-- Formulario para actualizar equipo -->
            <form class="FormularioAjax" action="<?php echo SERVER_URL ?>ajax/equipoAjax.php" method="POST" data-form="update">
                
                <!-- Id -->
                <div class="mb-3">
                    <input type="number" name="id_actualizar" id="id_actualizar" class="form-control" value="<?php echo $pagina[1]; ?>" hidden readonly>
                </div>

                <div class="row">
                    <!-- Número de Serie -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="num_serie_actualizar" class="form-label">Número de Serie <span class="text-danger">*</span></label>
                            <input type="text" name="num_serie_actualizar" id="num_serie_actualizar" class="form-control" maxlength="100" value="<?php echo $campos['num_serie']; ?>" required>
                        </div>
                    </div>

                    <!-- falla -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="lote_actualizar" class="form-label">Falla</label>
                            <input type="text" name="lote_actualizar" id="lote_actualizar" class="form-control" maxlength="255" value="<?php echo $campos['lote']; ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Marca -->
                    <div class="col-md-6" style="position:relative;">
                        <div class="mb-3">
                            <label for="marca_autocomplete_actualizar" class="form-label">Marca <span class="text-danger">*</span></label>
                            <input type="text" id="marca_autocomplete_actualizar" class="form-control" placeholder="Escribe para buscar..." autocomplete="off" value="<?php echo htmlspecialchars($marca_nombre); ?>">
                            <input type="hidden" name="id_marca_actualizar" id="id_marca_actualizar" value="<?php echo $campos['id_marca']; ?>">
                            <div id="marca_suggestions_actualizar" class="list-group" style="position: absolute; z-index: 1050; width: 100%;"></div>
                        </div>
                    </div>

                    <!-- Tipo -->
                    <div class="col-md-6" style="position:relative;">
                        <div class="mb-3">
                            <label for="tipo_autocomplete_actualizar" class="form-label">Tipo <span class="text-danger">*</span></label>
                            <input type="text" id="tipo_autocomplete_actualizar" class="form-control" placeholder="Escribe para buscar..." autocomplete="off" value="<?php echo htmlspecialchars($tipo_nombre); ?>">
                            <input type="hidden" name="id_tipo_actualizar" id="id_tipo_actualizar" value="<?php echo $campos['id_tipo']; ?>">
                            <div id="tipo_suggestions_actualizar" class="list-group" style="position: absolute; z-index: 1050; width: 100%;"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Modelo -->
                    <div class="col-md-6" style="position:relative;">
                        <div class="mb-3">
                            <label for="modelo_autocomplete_actualizar" class="form-label">Modelo <span class="text-danger">*</span></label>
                            <input type="text" id="modelo_autocomplete_actualizar" class="form-control" placeholder="Escribe para buscar..." autocomplete="off" value="<?php echo htmlspecialchars($modelo_nombre); ?>">
                            <input type="hidden" name="id_modelo_actualizar" id="id_modelo_actualizar" value="<?php echo $campos['id_modelo']; ?>">
                            <div id="modelo_suggestions_actualizar" class="list-group" style="position: absolute; z-index: 1050; width: 100%;"></div>
                        </div>
                    </div>

                    <!-- Cliente -->
                    <div class="col-md-6" style="position:relative;">
                        <div class="mb-3">
                            <label for="cliente_autocomplete_actualizar" class="form-label">Cliente <span class="text-danger">*</span></label>
                            <input type="text" id="cliente_autocomplete_actualizar" class="form-control" placeholder="Busca por cédula..." autocomplete="off" value="<?php echo htmlspecialchars($cliente_display); ?>">
                            <input type="hidden" name="id_cliente_actualizar" id="id_cliente_actualizar" value="<?php echo $campos['id_cliente']; ?>">
                            <div id="cliente_suggestions_actualizar" class="list-group" style="position: absolute; z-index: 1050; width: 100%;"></div>
                        </div>
                    </div>
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
<script>
// Autocomplete genérico para actualización (mantiene valores actuales)
(function(){
    const config = [
        { inputId: 'marca_autocomplete_actualizar', hiddenId: 'id_marca_actualizar', listId: 'marca_suggestions_actualizar', ajax: 'marcaAjax.php', render: function(item){ return item.nombre; } },
        { inputId: 'tipo_autocomplete_actualizar', hiddenId: 'id_tipo_actualizar', listId: 'tipo_suggestions_actualizar', ajax: 'tipoAjax.php', render: function(item){ return item.nombre; } },
        { inputId: 'modelo_autocomplete_actualizar', hiddenId: 'id_modelo_actualizar', listId: 'modelo_suggestions_actualizar', ajax: 'modeloAjax.php', render: function(item){ return item.nombre; } },
        { inputId: 'cliente_autocomplete_actualizar', hiddenId: 'id_cliente_actualizar', listId: 'cliente_suggestions_actualizar', ajax: 'clienteAjax.php', render: function(item){ return (item.cedula + ' - ' + item.nombre); } }
    ];

    function createAutocomplete(cfg){
        const input = document.getElementById(cfg.inputId);
        const hidden = document.getElementById(cfg.hiddenId);
        const list = document.getElementById(cfg.listId);
        if (!input || !hidden || !list) return;

        let timer = null;
        function clearList(){ list.innerHTML = ''; }

        input.addEventListener('input', function(){
            // Si el usuario edita el texto, limpiar el hidden para forzar re-selección
            hidden.value = '';
            const q = this.value.trim();
            if (timer) clearTimeout(timer);
            if (q.length === 0) { clearList(); return; }
            timer = setTimeout(function(){
                const fd = new FormData();
                fd.append('q', q);
                fetch('<?php echo SERVER_URL ?>ajax/' + cfg.ajax, { method: 'POST', body: fd })
                .then(function(r){ return r.json(); })
                .then(function(data){
                    clearList();
                    if (!Array.isArray(data) || data.length === 0) return;
                    data.forEach(function(item){
                        const a = document.createElement('a');
                        a.href = '#';
                        a.className = 'list-group-item list-group-item-action';
                        a.textContent = cfg.render(item);
                        a.dataset.id = item.id;
                        a.addEventListener('click', function(e){
                            e.preventDefault();
                            input.value = this.textContent;
                            hidden.value = this.dataset.id;
                            clearList();
                        });
                        list.appendChild(a);
                    });
                }).catch(function(){ clearList(); });
            }, 230);
        });

        document.addEventListener('click', function(e){
            if (!input.contains(e.target) && !list.contains(e.target)) {
                clearList();
            }
        });
    }

    config.forEach(createAutocomplete);

    // Validación antes de enviar: asegurar que los hidden requeridos estén completos (form update)
    const form = document.querySelector('form.FormularioAjax[data-form="update"]');
    if (form) {
        form.addEventListener('submit', function(e){
            const required = [ 'id_marca_actualizar', 'id_tipo_actualizar', 'id_modelo_actualizar', 'id_cliente_actualizar' ];
            for (let i = 0; i < required.length; i++) {
                const id = required[i];
                const el = document.getElementById(id);
                if (el && !el.value) {
                    e.preventDefault();
                    const visible = document.getElementById(id.replace('_actualizar','') + '_autocomplete_actualizar') || document.getElementById(id);
                    alert('Por favor selecciona una opción válida para ' + (visible ? visible.previousElementSibling.textContent.replace('*','') : id));
                    if (visible) visible.focus();
                    return false;
                }
            }
        });
    }

})();
</script>
