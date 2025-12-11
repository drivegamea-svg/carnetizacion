# Cambios Realizados - Afiliados Module

**Fecha:** 10 Diciembre 2025  
**Cambios:** 3 Mejoras principales

---

## 1. ✅ VALIDACIÓN DE CI CON SIGNO Y COMPLEMENTO

**Archivo:** `app/Http/Requests/StoreAfiliadoRequest.php`

### Cambio:
- **Anterior:** `'regex:/^[0-9]+$/'` - Solo números
- **Nuevo:** `'regex:/^[0-9]+(-[A-Z]{2})?$/'` - Números con opción de signo + 2 letras

### Formatos aceptados:
- ✅ `10020292` (sin complemento)
- ✅ `10020292-HG` (con complemento)
- ❌ `10020292-AB-CD` (múltiples complementos - no permitido)
- ❌ `10020292-hg` (letras minúsculas - no permitido)

### Mensaje de validación actualizado:
```
"El CI debe tener formato válido (ej: 10020292 o 10020292-HG)."
```

---

## 2. ✅ PERSISTENCIA DE DOCUMENTOS ESCANEADOS EN LOCALSTORAGE

**Archivo:** `resources/views/afiliados/create.blade.php`

### Problema:
Cuando el formulario tenía errores de validación y se recargaba, los documentos escaneados se perdían.

### Solución:
Se implementó localStorage para persistir documentos, similar a las fotos:

#### Nuevas funciones JavaScript:

**1. `guardarDocumentosEnLocalStorage()`**
- Se ejecuta antes de enviar el formulario
- Guarda cada documento con:
  - Tipo de documento
  - Descripción
  - Nombre del archivo
  - Datos base64 del archivo
- LocalStorage keys: `carnetizacion_documentos_0`, `carnetizacion_documentos_1`, etc.
- También guarda: `carnetizacion_documentos_count`

**2. `restaurarDocumentosDesdeLocalStorage()`**
- Se ejecuta al cargar la página (en `document.ready`)
- Restaura todos los documentos guardados
- Recrea filas de documentos si es necesario
- Muestra previsualizaciones de imágenes

**3. `limpiarLocalStorageFotos()` - MEJORADA**
- Ahora también limpia documentos
- Se ejecuta después de guardar exitosamente

### Integración en el formulario:
- **Submit del formulario:** Llama `guardarDocumentosEnLocalStorage()` antes de enviar
- **Page load:** Llama `restaurarDocumentosDesdeLocalStorage()` en `document.ready`
- **Después de guardar:** `limpiarLocalStorageFotos()` limpia todo

---

## 3. ✅ CAMBIO DE BÚSQUEDA DE CI A MODAL DE ACCIONES

**Archivo:** `resources/views/afiliados/create.blade.php`

### Cambio de comportamiento:

#### Antes:
- Al buscar un CI existente, se auto-completaban TODOS los datos del formulario
- El usuario solo veía un mensaje de éxito/error
- Funciones: `buscarPersona()`, `llenarDatosPersona()`, `limpiarDatosPersona()`

#### Ahora:
- Al buscar un CI existente, aparece un **MODAL** con opciones contextuales
- El usuario decide qué acción realizar
- Se eliminó el auto-completado de datos

### Modal de opciones según estado:

**Si ACTIVO:**
```
┌─ AFILIADO ENCONTRADO ─────┐
│ Juan Perez García         │
│ CI: 10020292 LP           │
│ Estado: ACTIVO ✓          │
│                           │
│ Este afiliado ya está     │
│ activo. ¿Desea reimprimir │
│ su carnet?                │
│                           │
│ [🖨️ Reimprimir Carnet]    │
└───────────────────────────┘
```

**Si PENDIENTE:**
```
┌─ AFILIADO ENCONTRADO ─────┐
│ Juan Perez García         │
│ CI: 10020292 LP           │
│ Estado: PENDIENTE ⚠️      │
│                           │
│ Este afiliado está        │
│ pendiente de activación.  │
│ ¿Desea activarlo ahora?   │
│                           │
│ [✓ Activar Afiliado]      │
└───────────────────────────┘
```

**Si INACTIVO:**
```
┌─ AFILIADO ENCONTRADO ─────┐
│ Juan Perez García         │
│ CI: 10020292 LP           │
│ Estado: INACTIVO ✗        │
│                           │
│ Este afiliado está        │
│ inactivo. Contacte al     │
│ administrador para        │
│ reactivarlo.              │
└───────────────────────────┘
```

### Nuevas funciones:

**1. `buscarPersonaParaModal(ci)`**
- Reemplaza `buscarPersona()`
- Muestra spinner de carga
- Llama a `/buscar-persona/{ci}` por AJAX
- Si encuentra, muestra modal
- Si no encuentra, muestra mensaje informativo

**2. `mostrarModalOpcionesAfiliado(afiliado)`**
- Crea dinámicamente el modal con opciones
- Adapta el contenido según estado (ACTIVO/PENDIENTE/INACTIVO)
- Maneja múltiples aperturas reutilizando el modal

**3. `reimprimir(afiliadoId)`**
- Redirige a: `/afiliados/{id}/carnet/pdf`
- Genera nuevo PDF del carnet

**4. `activarAfiliado(afiliadoId)`**
- Solicita confirmación
- Realiza AJAX POST a `/afiliados/{id}/activar`
- Usa CSRF token de meta tag
- Redirige a listado de afiliados

### Funciones eliminadas:
- ❌ `buscarPersona()` 
- ❌ `llenarDatosPersona()`
- ❌ `limpiarDatosPersona()`

---

## Resumen de cambios por archivo:

| Archivo | Cambios |
|---------|---------|
| `app/Http/Requests/StoreAfiliadoRequest.php` | Actualizar regex CI para aceptar complemento |
| `resources/views/afiliados/create.blade.php` | Agregar localStorage documentos + Modal de búsqueda CI |

---

## Testing recomendado:

### 1. Validación de CI:
```
✅ 10020292 (sin complemento)
✅ 10020292-HG (con complemento)
❌ 10020292hg (minúsculas)
❌ 10020292-HG-XX (múltiples)
```

### 2. Persistencia de documentos:
1. Rellenar formulario con documentos
2. Dejar un campo vacío para generar error
3. Verificar que documentos se restauren

### 3. Modal de búsqueda:
1. Buscar CI de afiliado ACTIVO → Debe mostrar opción "Reimprimir Carnet"
2. Buscar CI de afiliado PENDIENTE → Debe mostrar opción "Activar"
3. Buscar CI inexistente → Debe mostrar mensaje "completa los datos nuevos"

---

## Notas importantes:

⚠️ **localStorage tiene límite:** ~5-10MB por dominio. Si se guardan muchos documentos con bases64 grandes, considerar chunking o server-side session.

🔐 **CSRF Token:** El modal de activación usa `meta[name="csrf-token"]` que debe existir en el layout (ya está incluido).

📱 **Responsividad:** El modal se adapta a dispositivos móviles con Bootstrap modal.

---

## Archivos modificados:
- ✏️ `app/Http/Requests/StoreAfiliadoRequest.php` (líneas 19-25, 87-89)
- ✏️ `resources/views/afiliados/create.blade.php` (múltiples secciones)

## Archivos sin cambios necesarios:
- ✅ `app/Http/Controllers/AfiliadoController.php` (ya soporta documentos)
- ✅ `routes/web.php` (ya tiene rutas /activar)
- ✅ `resources/views/layouts/app.blade.php` (ya tiene csrf-token)
