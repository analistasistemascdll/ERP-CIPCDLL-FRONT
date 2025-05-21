@extends('layouts.base')

@section('title', 'Registrar Cuenta Hija - Sistema Contable')

@section('content')
<div class="main-container">
        <!-- Formulario de registro -->
        <div class="form-container">
            <h2 id="formularioTitulo">Registrar Nueva Sub Cuenta</h2>
            <form id="registroCuentaForm">
                <input type="hidden" id="cuentaId" value="">
                <div class="input-group">
                <label for="nombreCuenta">Nombre de la Sub Cuenta</label>
                <input type="text" id="nombreCuenta" name="nombreCuenta" placeholder="Ingresa el nombre de la cuenta" pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" title="Solo se permiten letras y espacios" required>

                </div>
                <div class="input-group">
                    <label for="codigoCuenta">Código de la Sub Cuenta</label>
                    <input type="number" id="codigoCuenta" placeholder="Ingresa el código de la cuenta" required>
                </div>
                <div class="input-group">
                    <label for="Cuentapadre">Cuenta</label>
                    <select id="Cuentapadre" name="IdCuenta_Padre" required>
                        <option value="">Seleccione una cuenta padre</option>
                    </select>
                </div>

                <div class="input-group">
                    <label for="descripcionCuenta">Descripción</label>
                    <textarea id="descripcionCuenta" name="Descripcion" placeholder="Descripción de la cuenta" required></textarea>
                    </div>
                <div class="input-group">
                    <input type="submit" id="btnRegistrar" value="Registrar Cuenta">
                    <button type="button" id="btnActualizar" style="display:none;">Actualizar Cuenta</button>
                    <button type="button" id="btnCancelar" style="display:none;">Cancelar</button>
                </div>
            </form>
        </div>
        
        <!-- Tabla de cuentas -->
        <div class="table-container">
            <h2>Sub Cuentas Registradas</h2>
            
            <!-- Buscador mejorado -->
            <div class="search-container">
                <div class="search-input-group">
                    <select id="filterCuentaPadre">
                        <option value="">Todas las cuentas</option>
                    </select>
                    <input type="text" id="searchInput" placeholder="Buscar por nombre o código...">
                    <button id="btnSearch" class="btn-search">Buscar</button>
                    <button id="btnClearSearch" class="btn-clear-search">Limpiar</button>
                </div>
            </div>
            
            <div class="table-responsive">
                <table id="tablaCuentas" class="data-table">
                    <thead>
                        <tr>
                           <th>Cuenta</th>
                            <th>Sub Cuenta</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="cuentasTableBody">
                        <!-- Los datos se cargarán dinámicamente aquí -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

 <script>
// Variables globales
let modoEdicion = false;
let cuentasOriginales = []; // Para guardar todas las cuentas antes de filtrar
let cuentasPadre = []; // Para almacenar las cuentas padre disponibles

// Función para activar el modo edición
function activarModoEdicion() {
    modoEdicion = true;
    document.getElementById('formularioTitulo').textContent = 'Editar Cuenta';
    document.getElementById('btnRegistrar').style.display = 'none';
    document.getElementById('btnActualizar').style.display = 'inline-block';
    document.getElementById('btnCancelar').style.display = 'inline-block';
}

// Función para desactivar el modo edición
function desactivarModoEdicion() {
    modoEdicion = false;
    document.getElementById('formularioTitulo').textContent = 'Registrar Nueva Cuenta';
    document.getElementById('btnRegistrar').style.display = 'inline-block';
    document.getElementById('btnActualizar').style.display = 'none';
    document.getElementById('btnCancelar').style.display = 'none';
    document.getElementById('cuentaId').value = '';
    document.getElementById('registroCuentaForm').reset();
}

document.getElementById("nombreCuenta").addEventListener("input", function (e) {
    this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '');        
});

// Evento para registrar cuenta nueva
document.getElementById('registroCuentaForm').addEventListener('submit', async function(event) {
    event.preventDefault();
    
    // No hacer nada si estamos en modo edición (se usará el botón actualizar)
    if (modoEdicion) return;

    await registrarCuenta();
});

// Asignar eventos a los botones de actualizar y cancelar
document.getElementById('btnActualizar').addEventListener('click', async function() {
    await actualizarCuenta();
});

document.getElementById('btnCancelar').addEventListener('click', function() {
    desactivarModoEdicion();
});

// Evento para el botón de búsqueda
document.getElementById('btnSearch').addEventListener('click', function() {
    filtrarTabla();
});

// Evento para limpiar búsqueda
document.getElementById('btnClearSearch').addEventListener('click', function() {
    document.getElementById('searchInput').value = '';
    document.getElementById('filterCuentaPadre').value = '';
    cargarCuentas(document.getElementById('filterCuentaPadre').value);
});

// Evento para buscar al presionar Enter en el campo de búsqueda
document.getElementById('searchInput').addEventListener('keyup', function(event) {
    if (event.key === 'Enter') {
        filtrarTabla();
    }
});

// Evento para el filtro de cuenta padre
document.getElementById('filterCuentaPadre').addEventListener('change', function() {
    const idCuentaPadre = this.value;
    cargarCuentas(idCuentaPadre);
});


function filtrarTabla() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const idCuentaPadre = document.getElementById('filterCuentaPadre').value;
    
    // Si no hay término de búsqueda ni cuenta padre seleccionada, cargar todas las cuentas
    if (!searchTerm.trim() && !idCuentaPadre) {
        cargarCuentas();
        return;
    }
    
    // Si hay cuenta padre seleccionada pero no término de búsqueda, cargar cuentas de ese padre
    if (idCuentaPadre && !searchTerm.trim()) {
        cargarCuentas(idCuentaPadre);
        return;
    }
    
    // Si hay término de búsqueda (con o sin cuenta padre seleccionada), filtrar localmente
    const filteredData = cuentasOriginales.filter(cuenta => {
        const cumpleTerminoBusqueda = 
            cuenta.Nombre.toLowerCase().includes(searchTerm) ||
            cuenta.Codigo_Hija.toString().includes(searchTerm);
        
        const cumpleFiltroPadre = 
            !idCuentaPadre || 
            cuenta.Idcuenta_Padre.toString() === idCuentaPadre;
        
        return cumpleTerminoBusqueda && cumpleFiltroPadre;
    });
    
    renderizarTabla(filteredData);
}

// Función para cargar cuentas hijas según el padre seleccionado
async function cargarCuentas(idCuentaPadre = '') {
    let url = "<?php echo config('app.url'); ?>/api/hijas";
    
    // Si se especifica un ID de cuenta padre, usamos la ruta optimizada
    if (idCuentaPadre) {
        url = `<?php echo config('app.url'); ?>/api/hijas2/${idCuentaPadre}`;
    }
    
    const tbody = document.getElementById('cuentasTableBody');
    tbody.innerHTML = '<tr><td colspan="5" style="text-align: center;">Cargando datos...</td></tr>';

    try {
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
        });

        const data = await response.json();

        if (response.ok) {
            // Mapeamos los datos según la estructura esperada
            const cuentasCompletas = data.map(cuenta => ({
                ...cuenta,
                codigoPadre: cuenta.Codigo_Padre || 'N/A'
            }));
            
            // Guardar los datos completos para usarlos en la búsqueda
            cuentasOriginales = cuentasCompletas;
            
            // Renderizar la tabla con los datos
            renderizarTabla(cuentasCompletas);
        } else {
            console.error("Error al obtener cuentas hijas:", data.message);
            tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; color: red;">Error al cargar los datos</td></tr>';
        }

    } catch (error) {
        console.error("Error de red al cargar cuentas:", error);
        tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; color: red;">Error de conexión</td></tr>';
    }
}

// Función para renderizar la tabla con los datos filtrados o completos
function renderizarTabla(cuentas) {
    const tbody = document.getElementById('cuentasTableBody');
    
    if (cuentas.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" style="text-align: center;">No se encontraron registros</td></tr>';
        return;
    }
    
    tbody.innerHTML = '';
    
    for (const cuenta of cuentas) {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${cuenta.codigoPadre}</td>
            <td>${cuenta.Codigo_Hija}</td>
            <td>${cuenta.Nombre}</td>
            <td>${cuenta.Descripcion}</td>
            <td>
                <div class="acciones">
                    <button class="btn-action btn-edit" data-id="${cuenta.IdCuenta_Hija}">Editar</button> 
                    <button class="btn-action btn-delete" data-id="${cuenta.IdCuenta_Hija}">Eliminar</button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    }
    
    // Agregar eventos a los botones después de crear la tabla
    agregarEventosTabla();
}

// Función para cargar las cuentas padre en los selects
async function cargarCuentasPadre() {
    const urlBase = "<?php echo config('app.url'); ?>";
    const selectForm = document.getElementById("Cuentapadre");
    const selectFilter = document.getElementById("filterCuentaPadre");
    
    try {
        const response = await fetch(`${urlBase}/api/ids`, {
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
        });
        
        const ids = await response.json();
        
        // Limpiar selects
        selectForm.innerHTML = '<option value="">Seleccione una cuenta</option>';
        selectFilter.innerHTML = '<option value="">Todas las cuentas</option>';
        
        // Para almacenar temporalmente las cuentas padre con sus códigos
        const cuentasPadreTemp = [];
        
        // Obtener los códigos de cada cuenta padre
        for (const id of ids) {
            const res = await fetch(`${urlBase}/api/cuentaspadre/${id}`, {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                }
            });
            
            const data = await res.json();
            
            if (data && data.Codigo) {
                cuentasPadreTemp.push({
                    id: id,
                    codigo: data.Codigo
                });
                
                // Agregar opción al select del formulario
                const optionForm = document.createElement("option");
                optionForm.value = id;
                optionForm.textContent = data.Codigo;
                selectForm.appendChild(optionForm);
                
                // Agregar opción al select del filtro
                const optionFilter = document.createElement("option");
                optionFilter.value = id;
                optionFilter.textContent = data.Codigo;
                selectFilter.appendChild(optionFilter);
            }
        }
        
        // Guardar las cuentas padre para uso futuro
        cuentasPadre = cuentasPadreTemp;
        
    } catch (error) {
        console.error("Error al cargar cuentas padre:", error);
    }
}

document.addEventListener("DOMContentLoaded", function () {
    // Cargar las cuentas padre en los selects
    cargarCuentasPadre();
    
    // Cargar todas las cuentas hijas al inicio
    cargarCuentas();
});

// Función para registrar una nueva cuenta
async function registrarCuenta() {
    const nombreCuenta = document.getElementById('nombreCuenta').value;
    const codigoCuenta = document.getElementById('codigoCuenta').value;
    const cuentaPadre = document.getElementById('Cuentapadre').value;
    const descripcionCuenta = document.getElementById('descripcionCuenta').value;
    const estadoCuenta = 1;
    
    try {
        const url = "<?php echo config('app.url'); ?>/api/hija/crear";
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            body: JSON.stringify({
                Codigo_Hija: parseInt(codigoCuenta),
                Descripcion: descripcionCuenta,
                Estado: estadoCuenta,
                Idcuenta_Padre: parseInt(cuentaPadre),
                Nombre: nombreCuenta
            })
        });

        const data = await response.json();

        if (response.ok) {
            Swal.fire({
                icon: 'success',
                title: 'Cuenta registrada exitosamente',
                text: 'La cuenta ha sido agregada al sistema.',
                showConfirmButton: false,
                timer: 1500
            });

            // Limpiar el formulario
            document.getElementById('registroCuentaForm').reset();
            
            // Recargar la tabla después de registrar
            await cargarCuentas(document.getElementById('filterCuentaPadre').value);
        } else {
            // Si el código de respuesta es 400, mostramos el mensaje de error del backend
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.mensaje || 'Ocurrió un error al registrar la cuenta.'
            });
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error de conexión',
            text: 'No se pudo conectar con el servidor. Intenta nuevamente.'
        });
    }
}

// Función para agregar eventos a los botones de la tabla
function agregarEventosTabla() {
    // Eventos para botones de editar
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', async function() {
            const id = this.getAttribute('data-id');
            await cargarDatosCuenta(id);
        });
    });
    
    // Eventos para botones de eliminar
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            confirmarEliminacion(id);
        });
    });
}

async function cargarDatosCuenta(id) {
    try {
        const url = `<?php echo config('app.url'); ?>/api/verhija/${id}`;
        
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
        });
        
        if (!response.ok) {
            throw new Error('Error al cargar los datos de la cuenta');
        }
        
        const cuenta = await response.json();

        // Cambiar a modo edición
        activarModoEdicion();

        // Rellenar los campos del formulario
        document.getElementById('cuentaId').value = id; // ID oculto para actualizar
        document.getElementById('nombreCuenta').value = cuenta.Nombre || '';
        document.getElementById('codigoCuenta').value = cuenta.Codigo_Hija || '';
        document.getElementById('Cuentapadre').value = cuenta.IdCuenta_Padre || '';
        document.getElementById('descripcionCuenta').value = cuenta.Descripcion || '';

        // Desplazar al formulario
        document.querySelector('.form-container').scrollIntoView({ behavior: 'smooth' });

    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudieron cargar los datos de la cuenta.'
        });
    }
}

// Función para confirmar eliminación (cambio de estado)
function confirmarEliminacion(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'La cuenta será eliminada del sistema.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f44336',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            eliminarCuenta(id);
        }
    });
}

// Función para eliminar cuenta (cambiar estado a 0)
async function eliminarCuenta(id) {
    try {
        const url = `<?php echo config('app.url'); ?>/api/cuentas-hijas/eliminar/${id}`;
        
        const response = await fetch(url, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
        });
        
        if (!response.ok) {
            throw new Error('Error al eliminar la cuenta');
        }
        
        const data = await response.json();
        
        Swal.fire({
            icon: 'success',
            title: 'Cuenta eliminada',
            text: data.message || 'La cuenta ha sido eliminada exitosamente.',
            showConfirmButton: false,
            timer: 1500
        });
        
        // Recargar la tabla para reflejar los cambios
        await cargarCuentas(document.getElementById('filterCuentaPadre').value);
        
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo eliminar la cuenta. Intenta nuevamente.'
        });
    }
}

async function actualizarCuenta() {
    const id = document.getElementById('cuentaId').value;
    const nombreCuenta = document.getElementById('nombreCuenta').value;
    const codigoCuenta = document.getElementById('codigoCuenta').value;
    const descripcionCuenta = document.getElementById('descripcionCuenta').value;
    const cuentaPadre = document.getElementById('Cuentapadre').value;

    try {
        const url = `<?php echo config('app.url'); ?>/api/cuentas-hijas/editar/${id}`;

        const response = await fetch(url, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            body: JSON.stringify({
                Codigo_Hija: parseInt(codigoCuenta),
                Nombre: nombreCuenta,
                Descripcion: descripcionCuenta,
                Idcuenta_Padre: parseInt(cuentaPadre)
            })
        });

        const data = await response.json();

        if (response.ok) {
            Swal.fire({
                icon: 'success',
                title: 'Cuenta actualizada',
                text: 'La cuenta hija ha sido actualizada exitosamente.',
                showConfirmButton: false,
                timer: 1500
            });

            // Desactivar modo edición
            desactivarModoEdicion();

            // Recargar la tabla
            await cargarCuentas(document.getElementById('filterCuentaPadre').value);

        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Ocurrió un error al actualizar la cuenta hija.'
            });
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error de conexión',
            text: 'No se pudo conectar con el servidor. Intenta nuevamente.'
        });
    }
}
</script>

    <style>
       /* Contenedor principal */
       
    .acciones {
        display: flex;
        gap: 5px;
        justify-content: center;
        align-items: center;
    }
    
    td:last-child {
        padding: 10px; /* o el padding que usas para los demás td */
        vertical-align: middle;
    }
    
    table td {
        vertical-align: middle; 
        word-wrap: break-word;
        word-break: break-word;
        white-space: normal;
        min-width: 80px;   /* Tamaño mínimo */
        width: 220px;       /* Tamaño inicial/preferido */
        max-width: 800px;   /* Tamaño máximo permitido */
    }

    .main-container {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 20px;
        gap: 20px;
        min-height: calc(95vh - 100px); /* Ajustar según header/footer */
        background-color: transparent;
        min-height: 960px;
        
    }

    /* Estilos para el formulario */
    .form-container {
        flex: 0 0 50%; /* Reduce el ancho del formulario para dar más espacio a la tabla */
        background-color: rgba(255, 255, 255, 0.9);
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        height: auto;
        margin-top:25px;
        
    }

    /* Estilos para la tabla */
    .table-container {
        flex: 0 0 48%;
        max-width: 100%; /* Evita que se pase del ancho del contenedor padre */
        background-color: rgba(255, 255, 255, 0.9);
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        margin-top: 25px;
    }

    /* Límite de altura para la tabla con scroll interno */
    .table-responsive {
        overflow-x: auto;
        width: 100%;
        max-height: 650px; /* Altura máxima para la tabla */
        overflow-y: auto; /* Scroll vertical cuando sea necesario */
        margin-top: 15px;
    }

    /* Estilos para el buscador */
    .search-container {
        margin-bottom: 15px;
        width: 100%;
    }

    .search-input-group {
        display: flex;
        gap: 10px;
        width: 100%;
        flex-wrap: wrap;
    }

    .search-input-group input,
    .search-input-group select {
        flex: 1;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
        min-width: 150px;
    }

    .btn-search, .btn-clear-search {
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.3s;
    }

    .btn-search {
        background-color: #D7B56D;
        color: white;
    }

    .btn-search:hover {
        background-color: #c9a456;
    }

    .btn-clear-search {
        background-color: #aaa;
        color: white;
    }

    .btn-clear-search:hover {
        background-color: #999;
    }

    /* Estilos del formulario */
    .input-group {
        margin-bottom: 15px;
    }

    .input-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        color: #333;
    }

    .input-group input, .input-group select, .input-group textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 16px;
    }

    .input-group input[type="submit"], .input-group button {
        background-color: #D7B56D !important;
        color: white;
        border: none;
        cursor: pointer;
        padding: 10px;
        border-radius: 5px;
        font-size: 16px;
        transition: background-color 0.3s ease;
        width: auto;
        margin-right: 10px;
    }

    .input-group input[type="submit"]:hover, .input-group button:hover {
        background-color: #c9a456 !important;
    }

    .input-group input[type="submit"]:disabled {
        background-color: #cccccc !important;
        cursor: not-allowed;
    }

    #btnActualizar {
        background-color: #D7B56D !important;
    }

    #btnActualizar:hover {
        background-color: #c9a456 !important;
    }

    #btnCancelar {
        background-color: #aaa !important;
    }

    #btnCancelar:hover {
        background-color: #999 !important;
    }

    textarea {
        resize: vertical;
        height: 120px;
    }

    /* Estilos para la tabla */
    .data-table {
        width: 100%;
        max-width: 100%; /* O un valor como 1000px si quieres limitarlo más */
        border-collapse: collapse;
        margin-top: 20px;
        min-width: 650px;
    }

    .data-table th, .data-table td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
    }

    .data-table th {
        background-color: #f2f2f2;
        color: #333;
        font-weight: bold;
        position: sticky; /* Mantener los encabezados visibles al hacer scroll */
        top: 0; /* Pegado a la parte superior */
        z-index: 10; /* Asegurar que esté por encima del contenido */
    }

    .data-table tr:nth-child(even) {
        background-color: rgba(242, 242, 242, 0.5);
    }

    .data-table tr:hover {
        background-color: rgba(221, 221, 221, 0.7);
    }

    /* Estilos para los botones de acción */
    .btn-action {
        padding: 6px 12px;
        margin: 0 5px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
        transition: background-color 0.3s;
    }

    .btn-edit {
        background-color: #c9a456;
        color: white;
    }

    .btn-edit:hover {
        background-color: #b8934d;
    }

    .btn-delete {
        background-color: #f44336;
        color: white;
    }

    .btn-delete:hover {
        background-color: #e53935;
    }

    /* Estilos responsivos mejorados */
    @media (max-width: 1200px) {
        .form-container {
            flex: 0 0 35%;
        }
        
        .table-container {
            flex: 0 0 60%;
        }
    }

    @media (max-width: 992px) {
        .main-container {
            flex-direction: column;
        }

        .form-container, .table-container {
            flex: 0 0 100%;
            max-width: 100%;
            width: 100%;
            margin-bottom: 20px;
        }
    }

    @media (max-width: 768px) {
        .input-group input[type="submit"], .input-group button {
            width: 100%;
            margin-right: 0;
            margin-bottom: 10px;
        }
        
        .main-container {
            padding: 10px;
        }
        
        .form-container, .table-container {
            padding: 15px;
        }

        .search-input-group {
            flex-direction: column;
        }

        .search-input-group input, 
        .search-input-group select,
        .btn-search,
        .btn-clear-search {
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        .data-table th, .data-table td {
            padding: 8px 5px;
            font-size: 14px;
        }
        
        .btn-action {
            padding: 5px 8px;
            font-size: 12px;
        }
    } 
    </style>
@endsection