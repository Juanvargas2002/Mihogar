<!-- Detalle del Monitoreo -->
<?php
    $id_monitoreo = (isset($pagina[1])) ? $pagina[1] : 0;
    
    require_once "./controladores/monitoreoControlador.php";
    $ins_monitoreo = new monitoreoControlador();
    
    $datos_monitoreo = $ins_monitoreo->obtener_monitoreo_controlador($id_monitoreo);
    
    if ($datos_monitoreo->rowCount() == 1) {
        $datos = $datos_monitoreo->fetch();
?>

<div class="container-fluid">

    <!-- Cabecera -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Detalle del monitoreo numero <?php echo $datos['id']; ?></h1>
        <div>
            <a href="<?php echo SERVER_URL; ?>monitoreos/" class="btn btn-secondary no-print">
                <i class="fas fa-arrow-left"></i> Volver al Listado
            </a>
            <button onclick="imprimirPDF()" class="btn btn-danger no-print">
                <i class="fas fa-file-pdf"></i> Descargar PDF
            </button>
        </div>
    </div>

    <!-- Información del Monitoreo -->
    <!-- Datos del Local -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-secondary text-white">
            <h6 class="m-0 font-weight-bold">Datos del Local</h6>
        </div>
        <div class="card-body">
            <p class="mb-1"><strong>Centro de servicio autorizado:</strong> Servicios Técnicos Mi Hogar</p>
            <p class="mb-1"><strong>Dirección:</strong> Cra 12 N|31-04 Centro</p>
            <p class="mb-1"><strong>Email:</strong> <a href="mailto:jservicios@mihogar.com.co">jservicios@mihogar.com.co</a></p>
            <p class="mb-0"><strong>Celulares:</strong> 319 232 4891 - 310 465 0693</p>
        </div>
    </div>

    <div class="row">
        <!-- Datos del Equipo -->
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">Información del Equipo</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tbody>
                            <tr>
                                <th width="40%">Número de Serie:</th>
                                <td><?php echo $datos['num_serie']; ?></td>
                            </tr>
                            <tr>
                                <th>Falla:</th>
                                <td><?php echo !empty($datos['lote']) ? $datos['lote'] : 'N/A'; ?></td>
                            </tr>
                            <tr>
                                <th>Marca:</th>
                                <td><?php echo $datos['marca_nombre']; ?></td>
                            </tr>
                            <tr>
                                <th>Linea:</th>
                                <td><?php echo $datos['tipo_nombre']; ?></td>
                            </tr>
                            <tr>
                                <th>Modelo:</th>
                                <td><?php echo $datos['modelo_nombre']; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Datos del Cliente y Monitoreo -->
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-success text-white">
                    <h6 class="m-0 font-weight-bold">Datos del Cliente y Monitoreo</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tbody>
                            <tr>
                                <th width="40%">Cliente:</th>
                                <td><?php echo $datos['cliente_nombre']; ?></td>
                            </tr>
                            <tr>
                                <th>Cédula:</th>
                                <td><?php echo $datos['cliente_cedula']; ?></td>
                            </tr>
                            <tr>
                                <th>Contacto:</th>
                                <td><?php echo $datos['cliente_contacto']; ?></td>
                            </tr>
                            <tr>
                                <th>Técnico:</th>
                                <td><?php echo $datos['tecnico_nombre']; ?></td>
                            </tr>
                            <tr>
                                <th>Fecha y Hora:</th>
                                <td><?php echo date('d/m/Y H:i:s', strtotime($datos['fecha_hora'])); ?></td>
                            </tr>
                            <tr>
                                <th>Duración:</th>
                                <td><?php echo $datos['duracion']; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Mediciones -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-info text-white">
            <h6 class="m-0 font-weight-bold">Diagnóstico</h6>
        </div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-3">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Corriente promedio</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $datos['corriente_max']; ?> A</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Temperatura Mín.</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo !empty($datos['temperatura_min']) ? $datos['temperatura_min'] . ' °C' : 'N/A'; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Temperatura Máx.</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo !empty($datos['temperatura_max']) ? $datos['temperatura_max'] . ' °C' : 'N/A'; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Vibración Máx.</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo !empty($datos['vibracion_max']) ? $datos['vibracion_max'] . ' m/s²' : 'N/A'; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficas -->
    <div class="row">
        <?php if (!empty($datos['grafico_corr'])): ?>
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Gráfica de Corriente</h6>
                </div>
                <div class="card-body text-center">
                    <img src="<?php echo SERVER_URL . $datos['grafico_corr']; ?>" alt="Gráfica de Corriente" class="img-fluid" style="max-height: 400px;">
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($datos['grafico_temp'])): ?>
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-danger">Gráfica de Temperatura</h6>
                </div>
                <div class="card-body text-center">
                    <img src="<?php echo SERVER_URL . $datos['grafico_temp']; ?>" alt="Gráfica de Temperatura" class="img-fluid" style="max-height: 400px;">
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Comentarios -->
    <?php if (!empty($datos['comentario'])): ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-warning text-white">
            <h6 class="m-0 font-weight-bold">Comentarios y Observaciones</h6>
        </div>
        <div class="card-body">
            <p class="mb-0"><?php echo nl2br($datos['comentario']); ?></p>
        </div>
    </div>
    <?php endif; ?>

    <!-- Términos y Condiciones -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-dark text-white">
            <h6 class="m-0 font-weight-bold">Términos y Condiciones</h6>
        </div>
        <div class="card-body">
            <p class="small mb-2">Este informe se entrega con fines informativos y de diagnóstico técnico. Los datos mostrados (corriente, temperatura, vibración, gráficas, y demás mediciones) provienen de equipos de captura y pueden presentar variaciones debidas a condiciones ambientales, calibración del equipo o limitaciones del sensor.</p>

            <p class="small mb-2">Servicios Técnicos Mi Hogar no asume responsabilidad por decisiones tomadas exclusivamente con base en la información contenida en este documento. Se recomienda complementar este informe con pruebas adicionales y la evaluación de un técnico calificado antes de realizar reparaciones o cambios en el equipo.</p>

            <p class="small mb-2">El cliente autoriza el almacenamiento y tratamiento de los datos asociados a este monitoreo para fines de soporte, historial y mejora del servicio. Los datos serán conservados por el tiempo necesario para dichos fines y conforme a la normativa aplicable.</p>

            <p class="small mb-2">Queda prohibida la reproducción o distribución total o parcial de este informe sin el consentimiento expreso de Servicios Técnicos Mi Hogar. Para consultas, aclaraciones o reclamaciones relacionadas con este reporte, contacte a: <a href="mailto:jservicios@mihogar.com.co">jservicios@mihogar.com.co</a> o a los teléfonos 319 232 4891 / 310 465 0693.</p>

            <p class="small mb-0">Si requiere acciones correctivas o recomendaciones formales, solicite la visita técnica correspondiente. Las intervenciones sobre el equipo deben ser realizadas por personal autorizado y siguiendo las medidas de seguridad pertinentes.</p>

            <div class="mt-3">
                <small class="text-muted">Fecha de emisión: <?php echo date('d/m/Y H:i:s', strtotime($datos['fecha_hora'])); ?> &nbsp;|&nbsp; Técnico responsable: <?php echo $datos['tecnico_nombre']; ?></small>
            </div>
        </div>
    </div>

</div>

<script>
function imprimirPDF() {
    // Abrir en nueva ventana para imprimir/guardar como PDF
    // Clonar el contenedor y eliminar elementos marcados como .no-print
    const original = document.querySelector('.container-fluid');
    const clone = original.cloneNode(true);
    clone.querySelectorAll('.no-print').forEach(el => el.remove());
    const contenido = clone.innerHTML;
    const ventana = window.open('', '', 'width=800,height=600');
    
    ventana.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Monitoreo Número<?php echo $datos['id']; ?> - <?php echo $datos['num_serie']; ?></title>
            <link href="<?php echo SERVER_URL; ?>vistas/css/sb-admin-2.min.css" rel="stylesheet">
            <link href="<?php echo SERVER_URL; ?>vistas/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
            <style>
                /* Ensure 1 inch (APA) page margins when printing */
                @page { margin: 1in; }
                @media print {
                    .no-print { display: none !important; }
                    /* remove extra browser body margin (we rely on @page) */
                    body { margin: 0; }
                    /* ensure printed container fits within page margins */
                    .container-print { box-sizing: border-box; }

                    /* Force Bootstrap grid columns to stay side-by-side when printing */
                    .row { display: flex; flex-wrap: wrap; }
                    .col-md-6 { flex: 0 0 50%; max-width: 50%; }
                    .col-md-3 { flex: 0 0 25%; max-width: 25%; }

                    /* Tables: allow them to use available width but avoid forcing full width stack */
                    .table { width: auto !important; }
                }
                .card { page-break-inside: avoid; margin-bottom: 20px; }
                img { max-width: 100%; height: auto; }
                /* also apply consistent box-sizing for layout */
                .container-print { box-sizing: border-box; padding: 0; }
            </style>
        </head>
        <body>
                <div class="text-center mb-4">
                    <img src="<?php echo SERVER_URL; ?>vistas/img/logo.png" alt="<?= COMPANY; ?>" style="height:60px;margin-bottom:10px;">
                    <h2>REPORTE DE MONITOREO</h2>
                    <h4>Monitoreo Número <?php echo $datos['id']; ?></h4>
                    <p><strong>Fecha de Generación:</strong> ${new Date().toLocaleString()}</p>
                </div>
            <div class="container-print">${contenido}</div>
            <div class="text-center mt-4 no-print">
                <button onclick="window.print()" class="btn btn-primary">Imprimir / Guardar PDF</button>
                <button onclick="window.close()" class="btn btn-secondary">Cerrar</button>
            </div>
        </body>
        </html>
    `);
    
    ventana.document.close();
    ventana.focus();
}
</script>

<?php
    } else {
?>
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-body text-center py-5">
                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                <h4>Monitoreo no encontrado</h4>
                <p class="text-muted">El monitoreo que buscas no existe o ha sido eliminado.</p>
                <a href="<?php echo SERVER_URL; ?>monitoreos/" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Volver al Listado
                </a>
            </div>
        </div>
    </div>
<?php
    }
?>
