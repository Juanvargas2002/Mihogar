# 5 Diseño Arquitectónico

## 5.1 Diseño arquitectónico

### 5.1.1 Requerimientos funcionales

- RF01: El sistema debe permitir registrar usuarios (técnicos y administradores).
- RF02: El sistema debe permitir iniciar sesión con credenciales válidas.
- RF03: El sistema debe permitir registrar, listar, editar y eliminar clientes.
- RF04: El sistema debe permitir registrar, listar, editar y eliminar marcas.
- RF05: El sistema debe permitir registrar, listar, editar y eliminar modelos de equipo.
- RF06: El sistema debe permitir registrar, listar, editar y eliminar tipos de equipo.
- RF07: El sistema debe permitir registrar, listar, editar y eliminar equipos (asociados a marca, modelo, tipo y cliente).
- RF08: El sistema debe permitir registrar monitoreos para un equipo (datos: fecha_hora, duración, corriente, temperatura, vibración, gráficos, comentario, técnico, equipo).
- RF09: El sistema debe permitir consultar los monitoreos de un equipo y ver detalles ampliados (incluye datos del cliente, marca, modelo, técnico).
- RF10: El sistema debe mostrar un panel con métricas y contadores (cantidad de usuarios, equipos, clientes, monitoreos).
- RF11: El sistema debe permitir generación de reportes y exportación (al menos visualización de gráficas con Chart.js).
- RF12: El sistema debe permitir cerrar sesión.

> Nota: Los RF se han inferido a partir de los controladores y modelos presentes en el proyecto (`modelos/` y `controladores/`).

### 5.1.2 Requerimientos no funcionales

- RNF01 (Seguridad): Las contraseñas deben almacenarse de forma segura (hash) y las sesiones deben validarse antes de acceder a módulos restringidos.
- RNF02 (Usabilidad): La interfaz debe ser navegable y coherente; las vistas usan `sb-admin-2` y Bootstrap para facilitar usabilidad.
- RNF03 (Rendimiento): Las páginas de listas deben paginarse; las vistas principales deben cargarse en menos de 2-3 segundos en condiciones normales.
- RNF04 (Compatibilidad): El sistema debe funcionar en navegadores modernos como Chrome y Firefox y ser usable en resoluciones de escritorio.
- RNF05 (Mantenibilidad): El código está organizado en MVC (modelos, controladores y vistas) para facilitar mantenimiento y extensibilidad.
- RNF06 (Escalabilidad): La separación por capas permite migrar a APIs o desacoplar componentes si la carga crece.

### 5.1.3 Diagramas de Caso de Uso

Actores principales:
- Administrador: gestiona usuarios, configura el sistema.
- Técnico (Usuario autenticado): registra monitoreos, consulta equipos y clientes.
- Usuario autenticado: consulta panel y reportes.

Casos de uso principales (lista):
- Iniciar sesión (actor: Usuario)
- Registrar usuario (actor: Administrador)
- Registrar cliente (actor: Administrador / Técnico)
- Registrar equipo (actor: Técnico)
- Registrar monitoreo (actor: Técnico)
- Consultar monitoreos (actor: Técnico / Administrador)
- Generar reporte (actor: Usuario autenticado)

Ejemplo — Descripción de caso de uso:

Caso de uso: Registrar monitoreo
Actor: Técnico
Precondición: El técnico ha iniciado sesión y existe el equipo en la base de datos.
Flujo básico:
1. El técnico abre el formulario de nuevo monitoreo.
2. Ingresa `fecha_hora`, `duracion`, `corriente_max`, `temperatura_min`, `temperatura_max`, `vibracion_max`, gráficos y `comentario`.
3. Selecciona el equipo y confirma.
4. El sistema guarda el registro en la tabla `monitoreo` y muestra confirmación.
Postcondición: El monitoreo queda registrado asociado al equipo y al técnico.

> Diagrama sugerido: usar draw.io o StarUML para representar actores y casos anteriores; incluir relaciones de inclusión o extensión si aplica.

### 5.1.4 Diagramas de Clase

Descripción textual de las clases principales (basadas en `modelos/` y `controladores/`):

- Clase: `mainModel`
  - Responsabilidad: conexión a base de datos (PDO), utilidades (limpiar cadenas, paginador).
  - Métodos relevantes: `conectar()`, `ejecutar_consulta_simple()`, `limpiar_cadena()`, `paginador_tablas()`.

- Clase: `usuarioModelo` / `usuarioControlador`
  - Atributos (tabla `usuario`): `id`, `identificacion`, `nombre`, `usuario`, `contrasena`, `rol` (implícito en código de acceso).
  - Métodos ejemplo: `agregar_usuario_modelo()`, `eliminar_usuario_modelo()`, `obtener_usuarios_modelo()`, `actualizar_usuario_modelo()`.

- Clase: `clienteModelo` / `clienteControlador`
  - Tabla `cliente`: `id`, `cedula`, `nombre`, `contacto`, `correo`, `direccion`.
  - Métodos: CRUD (`agregar_cliente_modelo`, `obtener_clientes_modelo`, etc.).

- Clase: `marcaModelo`, `modeloModelo`, `tipoModelo`
  - Tablas simples: `marca(id,nombre)`, `modelo(id,nombre,corriente_max,temperatura_min,temperatura_max,vibracion_max)`, `tipo(id,nombre)`.

- Clase: `equipoModelo` / `equipoControlador`
  - Tabla `equipo`: `id`, `num_serie`, `lote`, `id_marca`, `id_tipo`, `id_modelo`, `id_cliente`.
  - Métodos: `agregar_equipo_modelo`, `actualizar_equipo_modelo`, `obtener_equipos_modelo`, `eliminar_equipo_modelo`.

- Clase: `monitoreoModelo` / `monitoreoControlador`
  - Tabla `monitoreo`: `id`, `fecha_hora`, `duracion`, `corriente_max`, `temperatura_min`, `temperatura_max`, `vibracion_max`, `grafico_corr`, `grafico_temp`, `comentario`, `id_usuario`, `id_equipo`.
  - Métodos: `agregar_monitoreo_modelo`, `obtener_monitoreo_modelo`, `eliminar_monitoreo_modelo`, `obtener_cantidad_monitoreos_modelo`.

Relaciones (resumen):
- `cliente` 1:N `equipo` (un cliente puede tener muchos equipos).
- `marca`, `modelo`, `tipo` 1:N `equipo` (cada equipo referencia una marca, modelo y tipo).
- `equipo` 1:N `monitoreo` (cada monitoreo pertenece a un equipo).
- `usuario` 1:N `monitoreo` (técnico que realiza el monitoreo).

Recomendación: generar un diagrama UML de clases con herramientas como draw.io o StarUML con las clases anteriores, atributos y relaciones.

### 5.1.5 Diagramas de Secuencia (descriptivo)

Secuencia 1 — Iniciar sesión
1. Actor: Usuario abre `login-view`.
2. Vista envía credenciales a `loginAjax.php` / `loginControlador`.
3. Controlador valida con `loginModelo` consultando `usuario` en BD (vía `mainModel`).
4. Si las credenciales son válidas, el sistema crea la sesión y redirige al `panel-view`.

Secuencia 2 — Registrar monitoreo
1. Actor: Técnico abre `monitoreo-new-view`.
2. Vista envía los datos por AJAX a `monitoreoAjax.php`.
3. El `monitoreoControlador` valida y limpia datos, llama a `monitoreoModelo::agregar_monitoreo_modelo()`.
4. El modelo ejecuta INSERT en tabla `monitoreo` y retorna resultado.
5. Controlador devuelve respuesta a la vista; la vista muestra confirmación.

Secuencia 3 — Consultar detalles de monitoreo
1. Actor solicita ver monitoreo.
2. Vista solicita al servidor el `id` (AJAX a `monitoreoAjax.php`).
3. Controlador llama `monitoreoModelo::obtener_monitoreo_modelo($id)` que hace JOIN con `equipo`, `marca`, `modelo`, `cliente`, `usuario`.
4. Resultado devuelto y mostrado en la vista.

> Puede representarse con un diagrama de secuencia UML usando las entidades: Actor, Vista, Controlador, Modelo, Base de datos.

### 5.1.6 Diagramas de Actividades (descripción)

Actividad: Registrar equipo
1. Inicio -> Abrir formulario de equipos -> Ingresar datos (num_serie, lote, marca, tipo, modelo, cliente) -> Validar datos -> Guardar (INSERT en `equipo`) -> Confirmación -> Fin.

Actividad: Registrar usuario
1. Inicio -> Formulario nuevo usuario -> Ingresar datos -> Validar (unicidad usuario/identificación) -> Guardar (INSERT en `usuario`) -> Notificar -> Fin.

Actividad: Realizar monitoreo
1. Inicio -> Seleccionar equipo -> Ingresar métricas y gráficos -> Validar rangos (opcional) -> Guardar en `monitoreo` -> Actualizar panel/indicadores -> Fin.

### 5.1.7 Diseño de la base de datos

Diagrama Entidad-Relación (texto resumen):
- Entidades: `usuario`, `cliente`, `marca`, `modelo`, `tipo`, `equipo`, `monitoreo`.
- Relaciones:
  - `cliente` 1 --- N `equipo`
  - `marca` 1 --- N `equipo`
  - `modelo` 1 --- N `equipo`
  - `tipo` 1 --- N `equipo`
  - `equipo` 1 --- N `monitoreo`
  - `usuario` 1 --- N `monitoreo`

Modelo físico (tablas y campos clave inferidos):

- Tabla: `usuario`
  - `id` INT PK AI
  - `identificacion` VARCHAR(50)
  - `nombre` VARCHAR(150)
  - `usuario` VARCHAR(50)
  - `contrasena` VARCHAR(255)
  - `rol` VARCHAR(50) -- ejemplo: 'Administrador', 'Técnico'

- Tabla: `cliente`
  - `id` INT PK AI
  - `cedula` VARCHAR(50)
  - `nombre` VARCHAR(150)
  - `contacto` VARCHAR(100)
  - `correo` VARCHAR(150)
  - `direccion` VARCHAR(255)

- Tabla: `marca`
  - `id` INT PK AI
  - `nombre` VARCHAR(100)

- Tabla: `modelo`
  - `id` INT PK AI
  - `nombre` VARCHAR(100)
  - `corriente_max` DECIMAL(10,2)
  - `temperatura_min` DECIMAL(10,2)
  - `temperatura_max` DECIMAL(10,2)
  - `vibracion_max` DECIMAL(10,2)

- Tabla: `tipo`
  - `id` INT PK AI
  - `nombre` VARCHAR(100)

- Tabla: `equipo`
  - `id` INT PK AI
  - `num_serie` VARCHAR(100)
  - `lote` VARCHAR(100)
  - `id_marca` INT FK -> `marca(id)`
  - `id_tipo` INT FK -> `tipo(id)`
  - `id_modelo` INT FK -> `modelo(id)`
  - `id_cliente` INT FK -> `cliente(id)`

- Tabla: `monitoreo`
  - `id` INT PK AI
  - `fecha_hora` DATETIME
  - `duracion` VARCHAR(50) -- o INT (segundos) según diseño
  - `corriente_max` DECIMAL(10,2)
  - `temperatura_min` DECIMAL(10,2)
  - `temperatura_max` DECIMAL(10,2)
  - `vibracion_max` DECIMAL(10,2)
  - `grafico_corr` TEXT (JSON/imagen base64/URL)
  - `grafico_temp` TEXT
  - `comentario` TEXT
  - `id_usuario` INT FK -> `usuario(id)`
  - `id_equipo` INT FK -> `equipo(id)`

Indices y constraints recomendados:
- Índices en `equipo(id_cliente)`, `monitoreo(id_equipo)`, `monitoreo(id_usuario)` para consultas rápidas.
- FK con ON DELETE CASCADE/RESTRICT según reglas de negocio (recomiendo RESTRICT para evitar borrados accidentales).

## 5.2 Desarrollo

- **Arquitectura:** MVC (Modelos en `modelos/`, Controladores en `controladores/`, Vistas en `vistas/`), comunicación parcial por AJAX a través de `ajax/*.php`.
- **Frontend:** HTML, Bootstrap (`vendor/bootstrap` y `vistas/css/sb-admin-2.min.css`), jQuery (`vendor/jquery`), Chart.js (`vendor/chart.js`) para gráficas.
- **Backend:** PHP nativo (POO), `mainModel.php` usa PDO para conectar a MySQL (constantes en `config/SERVER.php`).
- **Bases de datos:** MySQL (tablas descritas arriba).
- **Dependencias/librerías:** Bootstrap, jQuery, Chart.js, FontAwesome.
- **Conexión a BD:** `mainModel::conectar()` crea un objeto `PDO(SGBD, USER_NAME, PASSWORD)` y aplica `SET CHARACTER SET utf8`.

Estructura de carpetas (resumen):
- `ajax/` -> endpoints AJAX por entidad
- `config/` -> `APP.php`, `SERVER.php` (configuración y constantes)
- `controladores/` -> lógica recepcionadora
- `modelos/` -> acceso a datos (PDO)
- `vistas/` -> plantillas y contenidos, `include/` con `sideBar.php`, `topBar.php`, `script.php`
- `vendor/` -> librerías de frontend

Descripción de módulos (resumen):
- Módulo Usuarios: gestión de técnicos y administradores (alta, baja, edición). Interacciones en `usuarioControlador.php` y `usuarioModelo.php`.
- Módulo Clientes: CRUD de clientes (`clienteControlador.php`, `clienteModelo.php`).
- Módulo Equipos: CRUD equipos y asociarlos a cliente/marca/modelo/tipo.
- Módulo Monitoreos: crear registros de monitoreo, consultar detalles y visualización mediante gráficas.
- Módulo Login/Seguridad: `loginControlador.php` y `loginModelo.php` manejan autenticación.

Capturas: incluir capturas de `vistas/contenidos/panel-view.php`, `monitoreo-new-view.php`, `monitoreos-view.php` y formularios CRUD. (En este documento dejar espacio y referencias a las rutas `vistas/contenidos/` para insertar imágenes).

## 5.3 Pruebas del sistema

Plan de pruebas sugerido:

- Pruebas funcionales:
  - Caso: Iniciar sesión con credenciales válidas -> resultado esperado: acceso al panel.
  - Caso: Crear usuario técnico -> usuario listado en gestión de usuarios.
  - Caso: Crear cliente y asignar equipo -> equipo aparece en lista de equipos con cliente asociado.
  - Caso: Registrar monitoreo -> el monitoreo queda en la base de datos y se puede visualizar detalle.
  - Caso: Eliminar registro (cliente/equipo/monitoreo) -> consecuencias en integridad referencial (verificar si existen restricciones). 

- Pruebas no funcionales:
  - Rendimiento: medir tiempos de carga en listado de equipos/monitoreos con N registros (p. ej. 1000).
  - Seguridad: comprobar almacenamiento de contraseñas (hash), probar inyección SQL en campos de búsqueda (debe estar protegido por prepared statements, como se observa en modelos).
  - Compatibilidad: revisar visualización en Chrome y Firefox en escritorio.

Casos de prueba ejemplo (tabla resumida):
- ID: PT-01 | Descripción: Login válido | Entrada: usuario+contraseña válidos | Salida esperada: Redirección a `panel-view`.
- ID: PT-02 | Descripción: Crear monitoreo | Entrada: formulario completo | Salida esperada: Registro en `monitoreo`, respuesta OK.

Herramientas de pruebas recomendadas:
- Para pruebas manuales: Postman (AJAX), navegador con devtools.
- Para pruebas automatizadas: PHPUnit para lógicas PHP (si se adapta el proyecto), o scripts Selenium para pruebas E2E de UI.

---

Anexos y siguientes pasos recomendados:
- Generar diagramas gráficos (UML) con draw.io o StarUML y exportarlos a PNG/PNG para incluir en la memoria.
- Extraer capturas de las vistas reales (`vistas/contenidos/*.php`) y pegarlas en la sección de Desarrollo.
- Revisar `config/SERVER.php` para documentar datos de conexión y ajustar seguridad (no incluir credenciales en la documentación pública).
- Si quieres, genero los diagramas UML básicos en formato XML de draw.io o archivos PNG listos para insertar.

Fin del documento — Punto 5 (borrador) generado automáticamente a partir del código fuente del proyecto.
