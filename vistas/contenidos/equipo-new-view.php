<!-- Registro de Equipos -->
<div class="container-fluid">
    <div class="card shadow mb-4 mx-auto" style="max-width: 800px;">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Registrar Equipo</h6>
        </div>

        <div class="card-body">

            <!-- Formulario de registro -->
            <form class="FormularioAjax" action="<?php echo SERVER_URL ?>ajax/equipoAjax.php" method="POST" data-form="save">
                
                <div class="row">
                    <!-- Número de Serie -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="num_serie" class="form-label">Número de Serie <span class="text-danger">*</span></label>
                            <input type="number" name="num_serie" id="num_serie"  class="form-control" maxlength="100" required>
                        </div>
                    </div>

                    <!-- falla -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="lote" class="form-label">Falla</label>
                            <input type="text" name="lote" id="lote" class="form-control" maxlength="255">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Marca -->
                    <div class="col-md-6" style="position:relative;">
                        <div class="mb-3">
                            <label for="marca_autocomplete" class="form-label">Marca <span class="text-danger">*</span></label>
                            <input type="text" id="marca_autocomplete" class="form-control" placeholder="Escribe para buscar..." autocomplete="off">
                            <input type="hidden" name="id_marca" id="id_marca">
                            <div id="marca_suggestions" class="list-group" style="position: absolute; z-index: 1050; width: 100%;"></div>
                        </div>
                    </div>


                    <!-- Tipo -->
                    <div class="col-md-6" style="position:relative;">
                        <div class="mb-3">
                            <label for="tipo_autocomplete" class="form-label">Linea <span class="text-danger">*</span></label>
                            <input type="text" id="tipo_autocomplete" class="form-control" placeholder="Escribe para buscar..." autocomplete="off">
                            <input type="hidden" name="id_tipo" id="id_tipo">
                            <div id="tipo_suggestions" class="list-group" style="position: absolute; z-index: 1050; width: 100%;"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Modelo -->
                    <div class="col-md-6" style="position:relative;">
                        <div class="mb-3">
                            <label for="modelo_autocomplete" class="form-label">Modelo <span class="text-danger">*</span></label>
                            <input type="text" id="modelo_autocomplete" class="form-control" placeholder="Escribe para buscar..." autocomplete="off">
                            <input type="hidden" name="id_modelo" id="id_modelo">
                            <div id="modelo_suggestions" class="list-group" style="position: absolute; z-index: 1050; width: 100%;"></div>
                        </div>
                    </div>

                    <!-- Cliente -->
                    <div class="col-md-6" style="position:relative;">
                        <div class="mb-3">
                            <label for="cliente_autocomplete" class="form-label">Cliente <span class="text-danger">*</span></label>
                            <input type="text" id="cliente_autocomplete" class="form-control" placeholder="Busca por cédula..." autocomplete="off">
                            <input type="hidden" name="id_cliente" id="id_cliente">
                            <div id="cliente_suggestions" class="list-group" style="position: absolute; z-index: 1050; width: 100%;"></div>
                        </div>
                    </div>
                </div>

                <p class="text-muted"><span class="text-danger">*</span> Campos obligatorios</p>

                <!-- Botón añadir Equipo -->
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
<script>
            // Autocomplete genérico para varios campos (marca, tipo, modelo, cliente)
            (function(){
                const config = [
                    { inputId: 'marca_autocomplete', hiddenId: 'id_marca', listId: 'marca_suggestions', ajax: 'marcaAjax.php', render: function(item){ return item.nombre; } },
                    { inputId: 'tipo_autocomplete', hiddenId: 'id_tipo', listId: 'tipo_suggestions', ajax: 'tipoAjax.php', render: function(item){ return item.nombre; } },
                    { inputId: 'modelo_autocomplete', hiddenId: 'id_modelo', listId: 'modelo_suggestions', ajax: 'modeloAjax.php', render: function(item){ return item.nombre; } },
                    { inputId: 'cliente_autocomplete', hiddenId: 'id_cliente', listId: 'cliente_suggestions', ajax: 'clienteAjax.php', render: function(item){ return (item.cedula + ' - ' + item.nombre); } }
                ];

                function createAutocomplete(cfg){
                    const input = document.getElementById(cfg.inputId);
                    const hidden = document.getElementById(cfg.hiddenId);
                    const list = document.getElementById(cfg.listId);
                    if (!input || !hidden || !list) return;

                    let timer = null;
                    function clearList(){ list.innerHTML = ''; }

                    input.addEventListener('input', function(){
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

                // Validación antes de enviar: asegurar que los hidden requeridos estén completos
                const form = document.querySelector('form.FormularioAjax[data-form="save"]');
                if (form) {
                    form.addEventListener('submit', function(e){
                        const required = [ 'id_marca', 'id_tipo', 'id_modelo', 'id_cliente' ];
                        for (let i = 0; i < required.length; i++) {
                            const id = required[i];
                            const el = document.getElementById(id);
                            if (el && !el.value) {
                                e.preventDefault();
                                const visible = document.getElementById(id.replace('id_','') + '_autocomplete') || document.getElementById(id);
                                alert('Por favor selecciona una opción válida para ' + (visible ? visible.previousElementSibling.textContent.replace('*','') : id));
                                if (visible) visible.focus();
                                return false;
                            }
                        }
                    });
                }

            })();
</script>