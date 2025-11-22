<!-- Nuevo Monitoreo en Tiempo Real con ESP32 -->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary"> Monitoreo en Tiempo Real con ESP32</h6>
            <h5 class="mb-0"><span class="badge badge-dark" id="cronometro">00:00:00</span></h5>
        </div>

        <div class="card-body">
            <form class="FormularioAjax" action="<?php echo SERVER_URL ?>ajax/monitoreoAjax.php" method="POST" data-form="save" id="formMonitoreo">
                
                <input type="hidden" name="id_equipo" id="id_equipo_hidden">
                <input type="hidden" name="duracion" id="duracion">
                <input type="hidden" name="grafico_corr" id="grafico_corr">
                <input type="hidden" name="grafico_temp" id="grafico_temp">
                <input type="hidden" name="grafico_vibr" id="grafico_vibr">

                <div class="card border-left-primary mb-4">
                    <div class="card-body">
                        <h6 class="font-weight-bold text-primary mb-3">
                            <i class="fas fa-box"></i> 1. Información del Equipo
                        </h6>
                        <div class="mb-3" style="position:relative;">
                            <label for="equipo_autocomplete" class="form-label">Equipo (escribe el número de serie) <span class="text-danger">*</span></label>
                            <input type="text" id="equipo_autocomplete" class="form-control" placeholder="Escribe el número de serie..." autocomplete="off">
                            <div id="equipo_suggestions" class="list-group" style="position: absolute; z-index: 1050; width: 100%;"></div>
                        </div>
                        <div id="info_equipo" style="display: none;" class="alert alert-info">
                            <h6 class="font-weight-bold mb-3">Datos del Equipo Seleccionado:</h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <small class="text-muted">Serie:</small>
                                    <p class="mb-0 font-weight-bold" id="info_serie">-</p>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">Marca:</small>
                                    <p class="mb-0 font-weight-bold" id="info_marca">-</p>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">Tipo:</small>
                                    <p class="mb-0 font-weight-bold" id="info_tipo">-</p>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">Modelo:</small>
                                    <p class="mb-0 font-weight-bold" id="info_modelo">-</p>
                                </div>
                            </div>
                            <hr>
                            <h6 class="font-weight-bold mb-2">Datos del Cliente dueño del equipo:</h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <small class="text-muted">Cédula:</small>
                                    <p class="mb-0 font-weight-bold" id="info_cliente_cedula">-</p>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">Nombre:</small>
                                    <p class="mb-0 font-weight-bold" id="info_cliente_nombre">-</p>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">Contacto:</small>
                                    <p class="mb-0 font-weight-bold" id="info_cliente_contacto">-</p>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">Correo:</small>
                                    <p class="mb-0 font-weight-bold" id="info_cliente_correo">-</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-left-info mb-4" id="seccion_bluetooth" style="display: none;">
                    <div class="card-body">
                        <h6 class="font-weight-bold text-info mb-3">
                            <i class="fas fa-bluetooth"></i> 2. Conexión con ESP32 por Bluetooth
                        </h6>
                        <div class="text-center">
                            <button type="button" id="btnConectarBluetooth" class="btn btn-info btn-lg mb-3">
                                <i class="fas fa-bluetooth"></i> Conectar al ESP32_MONITOREO
                            </button>
                            <div id="estado_conexion" class="alert alert-secondary">
                                <i class="fas fa-info-circle"></i> Esperando conexión...
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-left-warning mb-4" id="seccion_parametros" style="display: none;">
                    <div class="card-body">
                        <h6 class="font-weight-bold text-warning mb-3">
                            <i class="fas fa-sliders-h"></i> 3. Configuración de Parámetros del Monitoreo
                        </h6>
                        <p class="text-muted small">Configure los valores de referencia esperados (opcional)</p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="corriente_max_param" class="form-label">Corriente Máxima Esperada (A)</label>
                                    <input type="number" id="corriente_max_param" class="form-control" step="0.01" min="0" placeholder="Ej: 15.00">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="vibracion_max_param" class="form-label">Vibración Máxima Esperada (mV)</label>
                                    <input type="number" id="vibracion_max_param" class="form-control" step="0.01" min="0" placeholder="Ej: 1000">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="temperatura_min_param" class="form-label">Temperatura Mínima Esperada (°C)</label>
                                    <input type="number" id="temperatura_min_param" class="form-control" step="0.01" placeholder="Ej: 15.0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="temperatura_max_param" class="form-label">Temperatura Máxima Esperada (°C)</label>
                                    <input type="number" id="temperatura_max_param" class="form-control" step="0.01" placeholder="Ej: 80.0">
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="button" id="btnIniciarMonitoreo" class="btn btn-success btn-lg">
                                <i class="fas fa-play"></i> Iniciar Monitoreo en Tiempo Real
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card border-left-success mb-4" id="seccion_monitoreo" style="display: none;">
                    <div class="card-body">
                        <h6 class="font-weight-bold text-success mb-3">
                            <i class="fas fa-chart-line"></i> 4. Monitoreo en Tiempo Real
                        </h6>
                        <div class="row text-center mb-4">
                            <div class="col-md-4">
                                <div class="card border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Corriente Actual</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><span id="valor_corriente">0.00</span> A</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-left-danger shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Temperatura Actual</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><span id="valor_temperatura">0.0</span> °C</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-left-warning shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Vibración Actual</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><span id="valor_vibracion">0</span> mV</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 mb-4">
                                <div class="card shadow">
                                    <div class="card-header bg-primary text-white py-2">
                                        <h6 class="m-0"> Corriente (A)</h6>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="grafica_corriente" height="200"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 mb-4">
                                <div class="card shadow">
                                    <div class="card-header bg-danger text-white py-2">
                                        <h6 class="m-0"> Temperatura (°C)</h6>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="grafica_temperatura" height="200"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 mb-4">
                                <div class="card shadow">
                                    <div class="card-header bg-warning text-white py-2">
                                        <h6 class="m-0"> Vibración (mV)</h6>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="grafica_vibracion" height="150"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="button" id="btnDetenerMonitoreo" class="btn btn-danger btn-lg">
                                <i class="fas fa-stop"></i> Detener y Finalizar Monitoreo
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card border-left-dark mb-4" id="seccion_guardar" style="display: none;">
                    <div class="card-body">
                        <h6 class="font-weight-bold text-dark mb-3">
                            <i class="fas fa-save"></i> 5. Resumen y Guardado del Monitoreo
                        </h6>
                        <div class="alert alert-success">
                            <h6 class="font-weight-bold"> Monitoreo Finalizado</h6>
                            <p class="mb-0">Revise los valores registrados y agregue observaciones antes de guardar.</p>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Duración Total</label>
                                    <input type="text" id="duracion_display" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Corriente Promedio (A)</label>
                                    <input type="number" name="corriente_max" id="corriente_max" class="form-control" step="0.01" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Temperatura Mínima (°C)</label>
                                    <input type="number" name="temperatura_min" id="temperatura_min" class="form-control" step="0.01" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Temperatura Máxima (°C)</label>
                                    <input type="number" name="temperatura_max" id="temperatura_max" class="form-control" step="0.01" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Vibración Máxima (mV)</label>
                                    <input type="number" name="vibracion_max" id="vibracion_max" class="form-control" step="0.01" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="comentario" class="form-label">Comentarios y Observaciones</label>
                            <textarea name="comentario" id="comentario" class="form-control" rows="3" maxlength="500"></textarea>
                            <small class="text-muted">Máximo 500 caracteres</small>
                        </div>
                        <p class="text-muted"><span class="text-danger">*</span> Los valores se registraron automáticamente durante el monitoreo</p>
                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            <i class="fas fa-save"></i> Guardar Monitoreo Completo
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
let bluetoothDevice = null;
let characteristic = null;
let monitoreoActivo = false;
let tiempoInicio = null;
let intervalo = null;
let chartCorriente = null;
let chartTemperatura = null;
let chartVibracion = null;
// Full history arrays (para guardar imágenes completas y calcular promedios)
let fullLabels = [];
let fullCorriente = [];
let fullTemperatura = [];
let fullVibracion = [];
let fullTimestamps = []; // epoch ms for each sample

let maxCorriente = 0; // ya no se usa como valor guardado, se conserva para compatibilidad
let minTemperatura = 999;
let maxTemperatura = -999;
let maxVibracion = 0;
// Ventana de muestra que se muestra en el chart (puntos recientes). Aumenta esto para ver más historia en pantalla.
const displayWindow = 300; // muestra hasta 300 puntos en pantalla (ajustable)
const ONE_MINUTE_MS = 60000;
const SERVICE_UUID = "6e400001-b5a3-f393-e0a9-e50e24dcca9e";
const CHARACTERISTIC_UUID = "6e400002-b5a3-f393-e0a9-e50e24dcca9e";

// --- Nuevo: control del buzzer ---
let latestData = null;                    // almacena última lectura recibida vía BLE
let buzzerControllerTimer = null;         // intervalo de control del buzzer
let lastBuzzerCommand = null;             // "ON" o "OFF" (último comando enviado)
let buzzerBlocked = false;                // si true, no ejecutar control del buzzer (se envió OFF una vez)
// ------------------------------------------------

// Autocomplete para equipos por número de serie
(function(){
    const input = document.getElementById('equipo_autocomplete');
    const suggestions = document.getElementById('equipo_suggestions');
    const hidden = document.getElementById('id_equipo_hidden');
    let timer = null;

    function clear(){ suggestions.innerHTML = ''; }

    input.addEventListener('input', function(){
        hidden.value = '';
        // clear displayed info when editing
        document.getElementById('info_serie').textContent = '-';
        document.getElementById('info_marca').textContent = '-';
        document.getElementById('info_tipo').textContent = '-';
        document.getElementById('info_modelo').textContent = '-';
        document.getElementById('info_cliente_cedula').textContent = '-';
        document.getElementById('info_cliente_nombre').textContent = '-';
        document.getElementById('info_cliente_contacto').textContent = '-';
        document.getElementById('info_cliente_correo').textContent = '-';
        document.getElementById('info_equipo').style.display = 'none';
        document.getElementById('seccion_bluetooth').style.display = 'none';

        const q = this.value.trim();
        if (timer) clearTimeout(timer);
        if (q.length === 0) { clear(); return; }
        timer = setTimeout(function(){
            const fd = new FormData();
            fd.append('q', q);
            fetch('<?php echo SERVER_URL ?>ajax/equipoAjax.php', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                clear();
                if (!Array.isArray(data) || data.length === 0) return;
                data.forEach(item => {
                    const a = document.createElement('a');
                    a.href = '#';
                    a.className = 'list-group-item list-group-item-action';
                    a.textContent = item.num_serie + ' — ' + item.marca_nombre + ' ' + item.tipo_nombre + ' ' + item.modelo_nombre;
                    a.dataset.payload = JSON.stringify(item);
                    a.addEventListener('click', function(e){
                        e.preventDefault();
                        const payload = JSON.parse(this.dataset.payload);
                        hidden.value = payload.id;
                        input.value = payload.num_serie;
                        document.getElementById('info_serie').textContent = payload.num_serie || 'N/A';
                        document.getElementById('info_marca').textContent = payload.marca_nombre || 'N/A';
                        document.getElementById('info_tipo').textContent = payload.tipo_nombre || 'N/A';
                        document.getElementById('info_modelo').textContent = payload.modelo_nombre || 'N/A';
                        // cliente
                        document.getElementById('info_cliente_cedula').textContent = payload.cliente_cedula || 'N/A';
                        document.getElementById('info_cliente_nombre').textContent = payload.cliente_nombre || 'N/A';
                        document.getElementById('info_cliente_contacto').textContent = payload.cliente_contacto || 'N/A';
                        document.getElementById('info_cliente_correo').textContent = payload.cliente_correo || 'N/A';
                        // parámetros desde el modelo
                        if (payload.corriente_max !== null && payload.corriente_max !== undefined) document.getElementById('corriente_max_param').value = payload.corriente_max;
                        if (payload.temperatura_min !== null && payload.temperatura_min !== undefined) document.getElementById('temperatura_min_param').value = payload.temperatura_min;
                        if (payload.temperatura_max !== null && payload.temperatura_max !== undefined) document.getElementById('temperatura_max_param').value = payload.temperatura_max;
                        if (payload.vibracion_max !== null && payload.vibracion_max !== undefined) document.getElementById('vibracion_max_param').value = payload.vibracion_max;

                        document.getElementById('info_equipo').style.display = 'block';
                        document.getElementById('seccion_bluetooth').style.display = 'block';
                        clear();
                    });
                    suggestions.appendChild(a);
                });
            }).catch(err => { console.error(err); clear(); });
        }, 230);
    });

    document.addEventListener('click', function(e){ if (!input.contains(e.target) && !suggestions.contains(e.target)) suggestions.innerHTML=''; });
})();

document.getElementById('btnConectarBluetooth').addEventListener('click', async function() {
    try {
        const estadoDiv = document.getElementById('estado_conexion');
        estadoDiv.className = 'alert alert-info';
        estadoDiv.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Buscando dispositivo ESP32_MONITOREO...';
        console.log("Buscando dispositivo BLE...");
        bluetoothDevice = await navigator.bluetooth.requestDevice({
            filters: [{ name: "ESP32_MONITOREO" }],
            optionalServices: [SERVICE_UUID]
        });
        console.log("Conectando al dispositivo...");
        estadoDiv.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Conectando al ESP32...';
        const server = await bluetoothDevice.gatt.connect();
        const service = await server.getPrimaryService(SERVICE_UUID);
        characteristic = await service.getCharacteristic(CHARACTERISTIC_UUID);
        await characteristic.startNotifications();
        characteristic.addEventListener('characteristicvaluechanged', handleBluetoothData);
        estadoDiv.className = 'alert alert-success';
        estadoDiv.innerHTML = '<i class="fas fa-check-circle"></i> Conectado a ESP32_MONITOREO correctamente';
        console.log("Conectado a ESP32_MONITOREO");
        this.disabled = true;
        this.textContent = 'Conectado';
        document.getElementById('seccion_parametros').style.display = 'block';
        Swal.fire({icon: 'success', title: 'Conexión Exitosa', text: 'ESP32 conectado correctamente. Puede configurar los parámetros.', timer: 2000, showConfirmButton: false});

        // Manejar desconexión para limpiar intervalos y estados
        bluetoothDevice.addEventListener('gattserverdisconnected', () => {
            console.log('📴 Dispositivo BLE desconectado');
            document.getElementById('estado_conexion').className = 'alert alert-secondary';
            document.getElementById('estado_conexion').innerHTML = '<i class="fas fa-info-circle"></i> Esperando conexión...';
            this.disabled = false;
            this.textContent = 'Conectar al ESP32_MONITOREO';
            stopBuzzerController(); // detener control si estaba activo
        });

    } catch (error) {
        console.error('Error al conectar:', error);
        const estadoDiv = document.getElementById('estado_conexion');
        estadoDiv.className = 'alert alert-danger';
        estadoDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Error: ' + error.message;
        Swal.fire({icon: 'error', title: 'Error de Conexión', text: 'No se pudo conectar al ESP32. Asegúrese de que esté encendido y cerca.', confirmButtonText: 'Reintentar'});
    }
});

function handleBluetoothData(event) {
    const value = new TextDecoder().decode(event.target.value);
    try {
        const data = JSON.parse(value);
        latestData = data; // guardo la última lectura para el controlador del buzzer
        // console.log('Datos recibidos:', data);
        document.getElementById('valor_corriente').textContent = data.corriente.toFixed(2);
        document.getElementById('valor_temperatura').textContent = data.temp.toFixed(1);
        document.getElementById('valor_vibracion').textContent = data.vibracion.toFixed(0);
        if (monitoreoActivo) {
            // Actualizar min/max locales
            if (data.corriente > maxCorriente) maxCorriente = data.corriente;
            if (data.temp < minTemperatura) minTemperatura = data.temp;
            if (data.temp > maxTemperatura) maxTemperatura = data.temp;
            if (data.vibracion > maxVibracion) maxVibracion = data.vibracion;

            // Guardar en historial completo
            const tiempo = new Date().toLocaleTimeString();
            fullLabels.push(tiempo);
            fullCorriente.push(data.corriente);
            fullTemperatura.push(data.temp);
            fullVibracion.push(data.vibracion);
            fullTimestamps.push(Date.now());
            // Actualizar charts en tiempo real mostrando solo la última ventana de 60s
            updateDisplayedCharts();
        }
    } catch (err) {
        console.error("Error parseando JSON:", err, value);
    }
}

// Actualiza los charts on-screen para mostrar únicamente la última ventana de ONE_MINUTE_MS
function updateDisplayedCharts() {
    if (!fullTimestamps.length) return;
    const cutoff = Date.now() - ONE_MINUTE_MS;
    let startIdx = fullTimestamps.findIndex(ts => ts >= cutoff);
    if (startIdx === -1) {
        // If all samples are older, show last available minute relative to last sample
        const lastTs = fullTimestamps[fullTimestamps.length - 1];
        const cutoff2 = lastTs - ONE_MINUTE_MS;
        startIdx = fullTimestamps.findIndex(ts => ts >= cutoff2);
        if (startIdx === -1) startIdx = 0;
    }

    const labelsSlice = fullLabels.slice(startIdx);
    const corrSlice = fullCorriente.slice(startIdx);
    const tempSlice = fullTemperatura.slice(startIdx);
    const vibSlice = fullVibracion.slice(startIdx);

    // Corriente
    chartCorriente.data.labels = labelsSlice.slice();
    chartCorriente.data.datasets[0].data = corrSlice.slice();
    chartCorriente.update('none');

    // Temperatura
    chartTemperatura.data.labels = labelsSlice.slice();
    chartTemperatura.data.datasets[0].data = tempSlice.slice();
    chartTemperatura.update('none');

    // Vibración
    chartVibracion.data.labels = labelsSlice.slice();
    chartVibracion.data.datasets[0].data = vibSlice.slice();
    chartVibracion.update('none');
}

// --- Funciones para control automático del buzzer ---

// Evalúa condiciones y devuelve true si debe activarse el buzzer
function shouldActivateBuzzer(data) {
    if (!data) return false;

    // Leer umbrales del formulario
    const corrRaw = document.getElementById('corriente_max_param').value;
    const vibrRaw = document.getElementById('vibracion_max_param').value;
    const tempMinInputRaw = document.getElementById('temperatura_min_param').value;
    const tempMaxInputRaw = document.getElementById('temperatura_max_param').value;

    const currentThreshold = corrRaw !== "" ? parseFloat(corrRaw) : null;
    const vibrThreshold = vibrRaw !== "" ? parseFloat(vibrRaw) : null;
    const tempMin = tempMinInputRaw !== "" ? parseFloat(tempMinInputRaw) : null;
    const tempMax = tempMaxInputRaw !== "" ? parseFloat(tempMaxInputRaw) : null;

    // Si no hay ningún umbral definido, no activamos el buzzer desde aquí
    const anyThreshold = (currentThreshold !== null) || (vibrThreshold !== null) || (tempMin !== null) || (tempMax !== null);
    if (!anyThreshold) return false;

    // Evaluaciones:
    // - Corriente: solo se valida si se definió un umbral de corriente
    if (currentThreshold !== null && data.corriente > currentThreshold) {
        return true;
    }

    // - Temperatura: si se definió min o max se valida
    if (tempMax !== null && data.temp > tempMax) return true;
    if (tempMin !== null && data.temp < tempMin) return true;

    // - Vibración: si se definió umbral se valida
    if (vibrThreshold !== null && data.vibracion > vibrThreshold) return true;

    return false;
}

// Envía "ON" o "OFF" al ESP32 (solo si hay cambio de estado por defecto)
async function checkAndSendBuzzer() {
    if (!characteristic || !latestData || buzzerBlocked) return;

    const activate = shouldActivateBuzzer(latestData);
    const desired = activate ? "ON" : "OFF";

    // Evitar enviar si no cambia (para reducir tráfico BLE). 
    // Si quieres forzar envío cada segundo aunque no cambie, elimina esta condición.
    if (desired === lastBuzzerCommand) return;

    const encoder = new TextEncoder();
    try {
        await characteristic.writeValue(encoder.encode(desired));
        lastBuzzerCommand = desired;
        console.log(`🔵 Enviado comando buzzer: ${desired}`);
    } catch (err) {
        console.error('Error enviando comando buzzer:', err);
    }
}

function startBuzzerController() {
    // Si ya está corriendo, no iniciar otro intervalo
    if (buzzerControllerTimer) return;
    // Si no hay umbrales definidos en el formulario, enviar OFF una vez y bloquear el control
    const corrRaw = document.getElementById('corriente_max_param').value;
    const vibrRaw = document.getElementById('vibracion_max_param').value;
    const tempMinInputRaw = document.getElementById('temperatura_min_param').value;
    const tempMaxInputRaw = document.getElementById('temperatura_max_param').value;
    const anyThreshold = (corrRaw !== "") || (vibrRaw !== "") || (tempMinInputRaw !== "") || (tempMaxInputRaw !== "");
    if (!anyThreshold) {
        // enviar OFF una vez y bloquear comportamiento futuro
        if (characteristic) {
            const encoder = new TextEncoder();
            characteristic.writeValue(encoder.encode("OFF")).then(() => {
                lastBuzzerCommand = "OFF";
                buzzerBlocked = true;
                console.log('🔕 No hay umbrales definidos: enviado OFF una vez y bloqueado control del buzzer');
            }).catch(err => console.warn('No se pudo enviar OFF al buzzer:', err));
        } else {
            buzzerBlocked = true;
            console.log('🔕 No hay umbrales y no hay characteristic: bloqueo local del control del buzzer');
        }
        return;
    }

    // Clear any previous block and start controller
    buzzerBlocked = false;
    checkAndSendBuzzer();
    buzzerControllerTimer = setInterval(checkAndSendBuzzer, 1000);
    console.log('▶️ Control automático del buzzer iniciado');
}

function stopBuzzerController() {
    if (buzzerControllerTimer) {
        clearInterval(buzzerControllerTimer);
        buzzerControllerTimer = null;
    }
    // Intentar apagar buzzer en el dispositivo
    if (characteristic) {
        const encoder = new TextEncoder();
        characteristic.writeValue(encoder.encode("OFF"))
            .then(() => {
                lastBuzzerCommand = "OFF";
                console.log('🔔 Buzzer apagado por seguridad');
            })
            .catch(err => console.warn('No se pudo enviar OFF al buzzer:', err));
    }
    console.log('⏸️ Control automático del buzzer detenido');
}
// ----------------------------------------------------


document.getElementById('btnIniciarMonitoreo').addEventListener('click', function() {
    monitoreoActivo = true;
    tiempoInicio = Date.now();
    maxCorriente = 0;
    minTemperatura = 999;
    maxTemperatura = -999;
    maxVibracion = 0;
    // reset full history arrays (start fresh for this session)
    fullLabels = [];
    fullCorriente = [];
    fullTemperatura = [];
    fullVibracion = [];
    fullTimestamps = [];
    intervalo = setInterval(actualizarCronometro, 1000);
    document.getElementById('seccion_monitoreo').style.display = 'block';
    this.disabled = true;
    inicializarGraficas();
    document.getElementById('seccion_monitoreo').scrollIntoView({ behavior: 'smooth' });
    Swal.fire({icon: 'success', title: 'Monitoreo Iniciado', text: 'Registrando datos en tiempo real desde el ESP32...', timer: 2000, showConfirmButton: false});
    console.log('Monitoreo iniciado');

    // Iniciar controlador automático del buzzer si hay conexión BLE y characteristic disponible
    if (characteristic) {
        startBuzzerController();
    }
});

function inicializarGraficas() {
    const ctxCorriente = document.getElementById('grafica_corriente').getContext('2d');
    chartCorriente = new Chart(ctxCorriente, {
        type: 'line',
            data: {
            labels: [],
            datasets: [{
                label: 'Corriente (A)',
                data: [],
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: false,
            scales: {
                y: {beginAtZero: true, title: { display: true, text: 'Amperios (A)' }},
                x: {title: { display: true, text: 'Tiempo' }}
            }
        }
    });
    const ctxTemperatura = document.getElementById('grafica_temperatura').getContext('2d');
    chartTemperatura = new Chart(ctxTemperatura, {
        type: 'line',
            data: {
            labels: [],
            datasets: [{
                label: 'Temperatura (°C)',
                data: [],
                borderColor: '#e74a3b',
                backgroundColor: 'rgba(231, 74, 59, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: false,
            scales: {
                y: {title: { display: true, text: 'Grados Celsius (°C)' }},
                x: {title: { display: true, text: 'Tiempo' }}
            }
        }
    });
    const ctxVibracion = document.getElementById('grafica_vibracion').getContext('2d');
    chartVibracion = new Chart(ctxVibracion, {
        type: 'line',
            data: {
            labels: [],
            datasets: [{
                label: 'Vibración (mV)',
                data: [],
                borderColor: '#f6c23e',
                backgroundColor: 'rgba(246, 194, 62, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: false,
            scales: {
                y: {beginAtZero: true, title: { display: true, text: 'Milivoltios (mV)' }},
                x: {title: { display: true, text: 'Tiempo' }}
            }
        }
    });
}

// Máximo de puntos en la vista del chart (ventana deslizante)
const maxPuntos = displayWindow;

// pushToChart: agrega un punto al chart manteniendo la ventana deslizante
function pushToChart(chart, valor, tiempo) {
    chart.data.labels.push(tiempo);
    chart.data.datasets[0].data.push(valor);
    if (chart.data.labels.length > maxPuntos) {
        chart.data.labels.shift();
        chart.data.datasets[0].data.shift();
    }
    chart.update('none');
}

// Crear un chart temporal (off-screen) con todos los datos y devolver su Base64
function exportFullChartBase64(type, labels, datasetLabel, dataArray, borderColor, backgroundColor, yTitle, width = 1600, height = 600) {
    return new Promise((resolve) => {
        const DPR = window.devicePixelRatio || 1;
        const canvas = document.createElement('canvas');
        // Set canvas physical size for high-res export
        canvas.width = Math.round(width * DPR);
        canvas.height = Math.round(height * DPR);
        canvas.style.width = width + 'px';
        canvas.style.height = height + 'px';
        canvas.style.display = 'none';
        document.body.appendChild(canvas);
        const ctx = canvas.getContext('2d');
        // Scale the context so chart elements render crisp at DPR
        ctx.setTransform(DPR, 0, 0, DPR, 0, 0);
        const tempChart = new Chart(ctx, {
            type: type,
            data: {
                labels: labels.slice(),
                datasets: [{
                    label: datasetLabel,
                    data: dataArray.slice(),
                    borderColor: borderColor,
                    backgroundColor: backgroundColor,
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: false,
                animation: false,
                plugins: { legend: { display: true } },
                scales: { y: { title: { display: true, text: yTitle } }, x: { title: { display: true, text: 'Tiempo' } } }
            }
        });
        // Forcing a tiny timeout ensures Chart has synced internal state before export in some browsers
        setTimeout(() => {
            try {
                const img = tempChart.toBase64Image();
                tempChart.destroy();
                canvas.remove();
                resolve(img);
            } catch (err) {
                console.error('Error exporting full chart:', err);
                try { tempChart.destroy(); } catch(e){}
                canvas.remove();
                resolve('');
            }
        }, 80);
    });
}

function actualizarGrafico(chart, valor) {
    const tiempo = new Date().toLocaleTimeString();
    chart.data.labels.push(tiempo);
    chart.data.datasets[0].data.push(valor);
    if (chart.data.labels.length > maxPuntos) {
        chart.data.labels.shift();
        chart.data.datasets[0].data.shift();
    }
    chart.update('none');
}

function actualizarCronometro() {
    const ahora = Date.now();
    const transcurrido = Math.floor((ahora - tiempoInicio) / 1000);
    const horas = Math.floor(transcurrido / 3600);
    const minutos = Math.floor((transcurrido % 3600) / 60);
    const segundos = transcurrido % 60;
    const tiempoFormateado = String(horas).padStart(2, '0') + ':' + String(minutos).padStart(2, '0') + ':' + String(segundos).padStart(2, '0');
    document.getElementById('cronometro').textContent = tiempoFormateado;
}

document.getElementById('btnDetenerMonitoreo').addEventListener('click', async function() {
    monitoreoActivo = false;
    clearInterval(intervalo);
    const duracion = document.getElementById('cronometro').textContent;
    document.getElementById('duracion').value = duracion;
    document.getElementById('duracion_display').value = duracion;

    // Limitar guardado a la última minuto (60.000 ms)
    const oneMinuteMs = 60000;
    let startIdx = 0;
    if (fullTimestamps.length > 0) {
        const cutoff = Date.now() - oneMinuteMs;
        startIdx = fullTimestamps.findIndex(ts => ts >= cutoff);
        if (startIdx === -1) {
            // If no samples are newer than cutoff, try to take the last-minute window relative to the last sample
            const lastTs = fullTimestamps[fullTimestamps.length - 1];
            const cutoff2 = lastTs - oneMinuteMs;
            startIdx = fullTimestamps.findIndex(ts => ts >= cutoff2);
            if (startIdx === -1) startIdx = 0;
        }
    }

    const labelsSlice = fullLabels.slice(startIdx);
    const corrSlice = fullCorriente.slice(startIdx);
    const tempSlice = fullTemperatura.slice(startIdx);
    const vibSlice = fullVibracion.slice(startIdx);

    // Calcular corriente promedio a partir del slice (último minuto)
    let avgCorr = 0;
    if (corrSlice.length > 0) {
        const sum = corrSlice.reduce((a, b) => a + b, 0);
        avgCorr = sum / corrSlice.length;
    } else if (fullCorriente.length > 0) {
        avgCorr = fullCorriente.reduce((a, b) => a + b, 0) / fullCorriente.length;
    } else {
        avgCorr = maxCorriente; // fallback
    }
    document.getElementById('corriente_max').value = avgCorr.toFixed(2);

    // Mantener min/max temperatura y vibración como antes
    document.getElementById('temperatura_min').value = minTemperatura !== 999 ? minTemperatura.toFixed(2) : '';
    document.getElementById('temperatura_max').value = maxTemperatura !== -999 ? maxTemperatura.toFixed(2) : '';
    document.getElementById('vibracion_max').value = maxVibracion.toFixed(2);

    // Exportar imágenes pero usando solo el último minuto (labelsSlice, corrSlice, ...)
    try {
        // Export high-resolution images (width x height). Adjust sizes as needed.
        const imgCorr = await exportFullChartBase64('line', labelsSlice.length ? labelsSlice : [''], 'Corriente (A)', corrSlice.length ? corrSlice : [0], '#4e73df', 'rgba(78, 115, 223, 0.1)', 'Amperios (A)', 1920, 600);
        const imgTemp = await exportFullChartBase64('line', labelsSlice.length ? labelsSlice : [''], 'Temperatura (°C)', tempSlice.length ? tempSlice : [0], '#e74a3b', 'rgba(231, 74, 59, 0.1)', 'Grados Celsius (°C)', 1920, 600);
        const imgVib = await exportFullChartBase64('line', labelsSlice.length ? labelsSlice : [''], 'Vibración (mV)', vibSlice.length ? vibSlice : [0], '#f6c23e', 'rgba(246, 194, 62, 0.1)', 'Milivoltios (mV)', 1920, 400);
        document.getElementById('grafico_corr').value = imgCorr;
        document.getElementById('grafico_temp').value = imgTemp;
        document.getElementById('grafico_vibr').value = imgVib;
    } catch (err) {
        console.error('Error exporting full images:', err);
        // Fallback to on-screen images if off-screen export fails
        try {
            document.getElementById('grafico_corr').value = chartCorriente.toBase64Image();
            document.getElementById('grafico_temp').value = chartTemperatura.toBase64Image();
            document.getElementById('grafico_vibr').value = chartVibracion.toBase64Image();
        } catch (e) { console.warn('Fallback export failed', e); }
    }

    document.getElementById('seccion_guardar').style.display = 'block';
    this.disabled = true;
    document.getElementById('seccion_guardar').scrollIntoView({ behavior: 'smooth' });
    Swal.fire({
        icon: 'info',
        title: 'Monitoreo Finalizado',
        html: '<div class="text-left"><p><strong>Duración:</strong> ' + duracion + '</p><p><strong>Corriente Promedio:</strong> ' + avgCorr.toFixed(2) + ' A</p><p><strong>Temperatura Min/Max:</strong> ' + minTemperatura.toFixed(1) + '°C / ' + maxTemperatura.toFixed(1) + '°C</p><p><strong>Vibración Máxima:</strong> ' + maxVibracion.toFixed(0) + ' mV</p></div>',
        confirmButtonText: 'Revisar y Guardar'
    });
    console.log('Monitoreo detenido');

    // Detener controlador y apagar buzzer
    stopBuzzerController();
});
</script>