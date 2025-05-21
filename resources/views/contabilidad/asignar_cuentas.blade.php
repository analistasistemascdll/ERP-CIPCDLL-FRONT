@extends('layouts.base')

@section('title', 'Asignacion de Cuentas - Sistema Contable')

@section('content')
    <div class="main-container">
        <div class="page-header">
            <h1>Asignación de Cuentas a Áreas</h1>
        </div>
        
        <div class="content-grid">
            <!-- Formulario de asignación (izquierda) -->
            <div class="form-container">
                <h2>Seleccionar Área</h2>
                <form id="asignacionForm">
                    @csrf
                    <div class="form-group">
                        <label for="area">Seleccione Área:</label>
                        <select id="area" class="form-control" required>
                            <option value="">-- Seleccione un área --</option>
                            <option value="2">Finanzas</option>
                            <option value="3">Recursos Humanos</option>
                            <option value="4">Logística</option>
                        </select>
                    </div>
                    
                    <div class="selected-accounts-container">
                        <h3>Cuentas seleccionadas:</h3>
                        <ul id="selectedAccountsList" class="selected-accounts-list">
                            <!-- Las cuentas seleccionadas aparecerán aquí -->
                        </ul>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Guardar Asignación</button>
                </form>
            </div>
            
            <!-- Tabla de cuentas disponibles (derecha) -->
            <div class="table-container accounts-table">
                <h2>Sub Cuentas Disponibles</h2>
                
                <!-- Filtros para la tabla de SubCuentas -->
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
                
                <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                    <table id="tablaCuentas" class="data-table">
                        <thead>
                            <tr>
                                <th>Cuenta</th>
                                <th>Sub Cuenta</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Agregar</th>
                            </tr>
                        </thead>
                        <tbody id="cuentasTableBody">
                            <!-- Los datos se cargarán dinámicamente aquí -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Tabla de asignaciones realizadas (abajo) -->
        <div class="table-container assignments-table">
            <h2>Asignaciones Realizadas</h2>
            <div class="table-responsive">
                <table id="tablaAsignaciones" class="data-table">
                    <thead>
                        <tr>
                            <th>Área</th>
                            <th>Código Cuenta</th>
                            <th>Nombre Cuenta</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="asignacionesTableBody">
                        <!-- Los datos se cargarán dinámicamente aquí -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        .selected-accounts-container {
    margin: 20px 0;
    padding: 15px;
    border: 1px dashed #ccc;
    border-radius: 5px;
    background-color: #f9f9f9;
    max-height: 400px; /* Altura máxima antes de aparecer scroll */
    min-height: 500px; /* Altura mínima */
    overflow-y: auto; /* Scroll vertical cuando el contenido exceda la altura */
    display: flex;
    flex-direction: column;
}

.selected-accounts-list {
    list-style-type: none;
    padding: 0;
    margin: 0;
    overflow-y: auto; /* Scroll interno */
    flex-grow: 1; /* Ocupa todo el espacio disponible */
    min-height: 810px; /* Altura mínima para la lista */
}

/* Estilos para el scroll (opcional pero recomendado) */
.selected-accounts-container::-webkit-scrollbar {
    width: 8px;
}

.selected-accounts-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.selected-accounts-container::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

.selected-accounts-container::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Ajustes para pantallas pequeñas */
@media (max-width: 992px) {
    .selected-accounts-container {
        max-height: 200px; /* Altura menor en móviles */
    }
}
        .main-container {
            padding: 20px;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .page-header {
            margin-bottom: 25px;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }
        
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        @media (max-width: 992px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }
        
        .form-container, .table-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .form-container {
            height: fit-content;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        .selected-accounts-container {
            margin: 20px 0;
            padding: 15px;
            border: 1px dashed #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            min-height: 100px;
        }
        
        .selected-accounts-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        
        .selected-accounts-list li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px;
            margin-bottom: 5px;
            background-color: #fff;
            border-radius: 4px;
            border-left: 4px solid #4285f4;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .data-table th, .data-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        .data-table th {
            background-color: #f5f7fa;
            color: #333;
            font-weight: 600;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .data-table tbody tr:hover {
            background-color: rgba(66, 133, 244, 0.05);
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            transition: background-color 0.2s;
        }
        
        .btn-primary {
            background-color: #4285f4;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #3b78e7;
        }
        
        .btn-add {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .btn-add:hover {
            background-color: #218838;
        }
        
        .btn-remove {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 4px 8px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
        }
        
        .btn-remove:hover {
            background-color: #c82333;
        }
        
        .btn-action {
            background-color: #17a2b8;
            color: white;
            border: none;
            padding: 4px 8px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 15px;
            margin-right: 4px;
        }
        
        .btn-action:hover {
            background-color: #138496;
        }
        
        h2 {
            color: #333;
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 1.5rem;
        }
        
        h3 {
            color: #555;
            margin-top: 0;
            font-size: 1.2rem;
        }
        
        .assignments-table {
            margin-top: 20px;
        }
        
        /* Spinner para cargas */
        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            border-top-color: #4285f4;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Notificaciones */
        .notification {
            position: fixed;
            top: 50px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 4px;
            color: white;
            font-weight: 500;
            opacity: 0;
            transform: translateY(-20px);
            transition: all 0.3s;
            z-index: 1000;
        }
        
        .notification.show {
            opacity: 1;
            transform: translateY(0);
        }
        
        .notification.success {
            background-color: #28a745;
        }
        
        .notification.error {
            background-color: #dc3545;
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
            border-radius: 4px;
            font-size: 14px;
            min-width: 150px;
        }
        
        .btn-search, .btn-clear-search {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
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
    </style>

    <script>
// Variables globales
let cuentasOriginales = []; // Para almacenar todas las cuentas antes de filtrar
let cuentasPadre = []; // Para almacenar las cuentas padre disponibles

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar la aplicación
    cargarCuentasPadreSelects();
    cargarCuentas();
    cargarAsignaciones();
    
    // Manejar el envío del formulario
    document.getElementById('asignacionForm').addEventListener('submit', async function(event) {
        event.preventDefault();
        
        // Obtener el área seleccionada
        const areaId = document.getElementById('area').value;
        // Validar que se haya seleccionado un área
        if (!areaId) {
            mostrarNotificacion('Por favor, seleccione un área', 'error');
            return;
        }
        
        // Obtener todas las cuentas seleccionadas
        const cuentasSeleccionadas = document.querySelectorAll('#selectedAccountsList li');
        
        // Validar que haya al menos una cuenta seleccionada
        if (cuentasSeleccionadas.length === 0) {
            mostrarNotificacion('Por favor, seleccione al menos una cuenta', 'error');
            return;
        }
        
        // Mostrar indicador de proceso
        mostrarNotificacion('Guardando asignaciones...', 'info');
        
        // Contador para el seguimiento de asignaciones exitosas
        let exitosas = 0;
        
        // Iterar por cada cuenta seleccionada y guardar su asignación
        for (let i = 0; i < cuentasSeleccionadas.length; i++) {
            const cuentaId = cuentasSeleccionadas[i].dataset.id;
            
            try {
                // Llamar a la función de guardar asignación con estado 1 (activo)
                await guardarAsignacion(areaId, cuentaId, 1);
                exitosas++;
            } catch (error) {
                console.error('Error al guardar asignación:', error);
                // El mensaje de error ya es manejado por la función guardarAsignacion
            }
        }
        
        // Mostrar resultado final
        if (exitosas === cuentasSeleccionadas.length) {
            mostrarNotificacion(`Se han guardado todas las asignaciones (${exitosas})`, 'success');
            // Limpiar el formulario
            document.getElementById('area').value = '';
            document.getElementById('selectedAccountsList').innerHTML = '';
            // Recargar la tabla de asignaciones
            cargarAsignaciones();
        } else if (exitosas > 0) {
            mostrarNotificacion(`Se guardaron ${exitosas} de ${cuentasSeleccionadas.length} asignaciones`, 'warning');
            // Recargar la tabla de asignaciones
            cargarAsignaciones();
        } else {
            mostrarNotificacion('No se pudo guardar ninguna asignación', 'error');
        }
    });
    
    // Evento para el botón de búsqueda
    document.getElementById('btnSearch').addEventListener('click', function() {
        filtrarTabla();
    });
    
    // Evento para limpiar búsqueda
    document.getElementById('btnClearSearch').addEventListener('click', function() {
        document.getElementById('searchInput').value = '';
        document.getElementById('filterCuentaPadre').value = '';
        cargarCuentas();
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
});

function confirmarEliminacion(id) {
    if (window.Swal) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'La asignación será eliminada del sistema.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f44336',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                eliminarAsignacion(id);
            }
        });
    } else {
        if (confirm('¿Está seguro de eliminar esta asignación?')) {
            eliminarAsignacion(id);
        }
    }
}

async function guardarAsignacion(areaId, cuentaId, estado) {
    if (!areaId || !cuentaId) {
        mostrarNotificacion('Faltan datos: área o cuenta no especificada', 'error');
        return;
    }

    try {
        const data = {
            IdArea: areaId,
            IdCuenta_Hija: cuentaId,
            estado: estado
        };

        const response = await fetch('<?php echo config('app.url'); ?>/api/area-cuenta/agregar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (response.ok) {
            mostrarNotificacion(`Asignación de cuenta ${cuentaId} guardada correctamente`, 'success');
        } else {
            mostrarNotificacion(`Error al asignar cuenta ${cuentaId}: ${result.message || 'No se pudo guardar la asignación'}`, 'error');
        }
    } catch (error) {
        console.error(`Error al guardar asignación de cuenta ${cuentaId}:`, error);
        mostrarNotificacion(`Error de conexión al asignar cuenta ${cuentaId}`, 'error');
    }
}

// Función para mostrar notificaciones
function mostrarNotificacion(mensaje, tipo) {
    // Eliminar cualquier notificación existente
    const notificacionExistente = document.querySelector('.notification');
    if (notificacionExistente) {
        notificacionExistente.remove();
    }
    
    // Crear nueva notificación
    const notificacion = document.createElement('div');
    notificacion.className = `notification ${tipo}`;
    notificacion.textContent = mensaje;
    document.body.appendChild(notificacion);
    
    // Mostrar notificación
    setTimeout(() => {
        notificacion.classList.add('show');
    }, 10);
    
    // Ocultar notificación después de 3 segundos
    setTimeout(() => {
        notificacion.classList.remove('show');
        setTimeout(() => {
            notificacion.remove();
        }, 300);
    }, 3000);
}
// Función para cargar las cuentas disponibles con filtro opcional
async function cargarCuentas(idCuentaPadre = '') {
    let url = "<?php echo config('app.url'); ?>/api/hijas";
    
    // Si se especifica un ID de cuenta padre, usamos la ruta optimizada
    if (idCuentaPadre) {
        url = `<?php echo config('app.url'); ?>/api/hijas2/${idCuentaPadre}`;
    }
    
    const tbody = document.getElementById('cuentasTableBody');
    tbody.innerHTML = '<tr><td colspan="5" class="text-center"><div class="spinner"></div> Cargando cuentas...</td></tr>';

    try {
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
        });

        const data = await response.json();
        tbody.innerHTML = '';

        if (response.ok) {
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center">No hay cuentas disponibles</td></tr>';
                return;
            }
            
            // Primero obtener todos los códigos de cuenta padre únicos
            const idsPadresUnicos = [...new Set(data.map(cuenta => cuenta.Idcuenta_Padre))];
            
            // Crear un mapa de códigos padre para evitar múltiples llamadas
            const mapaCodigosPadre = new Map();
            
            // Obtener todos los códigos padre en paralelo
            await Promise.all(idsPadresUnicos.map(async idPadre => {
                try {
                    const codigo = await obtenerCodigoCuentaPadre(idPadre);
                    mapaCodigosPadre.set(idPadre, codigo);
                } catch (error) {
                    console.error(`Error al obtener código padre para ${idPadre}:`, error);
                    mapaCodigosPadre.set(idPadre, 'N/A');
                }
            }));
            
            // Mapear los datos con los códigos padre ya obtenidos
            const cuentasCompletas = data.map(cuenta => ({
                ...cuenta,
                codigoPadre: cuenta.Codigo_Padre || mapaCodigosPadre.get(cuenta.Idcuenta_Padre) || 'N/A'
            }));
            
            // Guardar los datos originales para búsquedas locales
            cuentasOriginales = cuentasCompletas;
            
            // Renderizar la tabla
            renderizarTablaCuentas(cuentasCompletas);
            
        } else {
            console.error("Error al obtener cuentas hijas:", data.message);
            tbody.innerHTML = '<tr><td colspan="5" class="text-center">Error al cargar las cuentas</td></tr>';
        }

    } catch (error) {
        console.error("Error de red al cargar cuentas:", error);
        tbody.innerHTML = '<tr><td colspan="5" class="text-center">Error de conexión al cargar las cuentas</td></tr>';
    }
}

// Función para renderizar la tabla de cuentas
function renderizarTablaCuentas(cuentas) {
    const tbody = document.getElementById('cuentasTableBody');
    tbody.innerHTML = '';
    
    if (cuentas.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center">No se encontraron registros</td></tr>';
        return;
    }
    
    for (const cuenta of cuentas) {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${cuenta.codigoPadre}</td>
            <td>${cuenta.Codigo_Hija}</td>
            <td>${cuenta.Nombre}</td>
            <td>${cuenta.Descripcion || 'N/A'}</td>
            <td>
                <button class="btn-add" data-id="${cuenta.IdCuenta_Hija}" 
                        data-codigo="${cuenta.Codigo_Hija}" 
                        data-name="${cuenta.Nombre}" 
                        data-padre="${cuenta.codigoPadre}">
                    Agregar
                </button>
            </td>
        `;
        tbody.appendChild(row);
    }
    
    // Agregar eventos a los botones de agregar
    document.querySelectorAll('.btn-add').forEach(button => {
        button.addEventListener('click', function() {
            agregarCuentaALista(
                this.dataset.id, 
                this.dataset.name,
                this.dataset.codigo,
                this.dataset.padre
            );
        });
    });
}

// Función para filtrar la tabla localmente
function filtrarTabla() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const idCuentaPadre = document.getElementById('filterCuentaPadre').value;
    
    // Si no hay término de búsqueda ni cuenta padre seleccionada, cargar todas las cuentas
    if (!searchTerm.trim() && !idCuentaPadre) {
        renderizarTablaCuentas(cuentasOriginales);
        return;
    }
    
    // Filtrar los datos locales
    const filteredData = cuentasOriginales.filter(cuenta => {
        const cumpleTerminoBusqueda = 
            cuenta.Nombre.toLowerCase().includes(searchTerm) ||
            cuenta.Codigo_Hija.toString().includes(searchTerm);
        
        const cumpleFiltroPadre = 
            !idCuentaPadre || 
            cuenta.Idcuenta_Padre.toString() === idCuentaPadre;
        
        return cumpleTerminoBusqueda && cumpleFiltroPadre;
    });
    
    renderizarTablaCuentas(filteredData);
}

// Función para cargar las cuentas padre en los selects
async function cargarCuentasPadreSelects() {
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

// Función para obtener el código de la cuenta hija
async function obtenerCodigoHija(idCodigoHija) {
    const url = `<?php echo config('app.url'); ?>/api/verhija/${idCodigoHija}`;
    
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
            const padre = await obtenerCodigoCuentaPadre(data.Idcuenta_Padre); 
            return padre + "" + data.Codigo_Hija; // Devuelve el código completo
        } else {
            console.error("Error al obtener código de cuenta hija:", data.message);
            return 'N/A'; // Si no hay datos, devuelve 'N/A'
        }

    } catch (error) {
        console.error("Error de red al obtener código de cuenta hija:", error);
        return 'N/A'; // Si hay un error de red, devuelve 'N/A'
    }
}

// Función para obtener el código de la cuenta padre
async function obtenerCodigoCuentaPadre(idCuentaPadre) {
    const url = `<?php echo config('app.url'); ?>/api/cuentaspadre/${idCuentaPadre}`;
    
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
            return data.Codigo; // Devuelve el código de la cuenta padre
        } else {
            console.error("Error al obtener código de cuenta padre:", data.message);
            return 'N/A'; // Si no hay datos, devuelve 'N/A'
        }

    } catch (error) {
        console.error("Error de red al obtener código de cuenta padre:", error);
        return 'N/A'; // Si hay un error de red, devuelve 'N/A'
    }
}

// Función para obtener el nombre de la cuenta hija
async function obtenerNombreHIja(idCuentahija) {
    const url = `<?php echo config('app.url'); ?>/api/cuenta-hija/${idCuentahija}/nombre`;
    
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
            return data.Nombre; // Devuelve el nombre de la cuenta hija
        } else {
            console.error("Error al obtener nombre de cuenta hija:", data.message);
            return 'N/A'; // Si no hay datos, devuelve 'N/A'
        }

    } catch (error) {
        console.error("Error de red al obtener nombre de cuenta hija:", error);
        return 'N/A'; // Si hay un error de red, devuelve 'N/A'
    }
}

// Función para agregar cuenta a la lista de seleccionadas
function agregarCuentaALista(id, nombre, codigo, padre) {
    const lista = document.getElementById('selectedAccountsList');
    // Verificar si la cuenta ya está en la lista
    if (document.querySelector(`#selectedAccountsList li[data-id="${id}"]`)) {
        mostrarNotificacion('Esta cuenta ya ha sido agregada', 'error');
        return;
    }
    const item = document.createElement('li');
    item.dataset.id = id;
    item.dataset.codigo = codigo;
    item.dataset.nombre = nombre;
    item.innerHTML = `
        <span>${nombre} (${padre}${codigo})</span>
        <button class="btn-remove">Quitar</button>
    `;
    
    // Agregar evento al botón de quitar
    item.querySelector('.btn-remove').addEventListener('click', function() {
        item.remove();
    });
    
    lista.appendChild(item);
}

// Función para cargar las asignaciones existentes
async function cargarAsignaciones() {
    const url = "<?php echo config('app.url'); ?>/api/area-cuenta/listar";
    const tbody = document.getElementById('asignacionesTableBody');
    tbody.innerHTML = '<tr><td colspan="4" class="text-center"><div class="spinner"></div> Cargando asignaciones...</td></tr>';

    try {
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
        });

        const data = await response.json();
        tbody.innerHTML = '';

        if (response.ok) {
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center">No hay asignaciones registradas</td></tr>';
                return;
            }
            
            for (const asignacion of data) {
                const row = document.createElement('tr');
                
                // Convertir nombre de área según el ID
                let nombreArea = 'Desconocido';
                if (asignacion.IdArea === 2) nombreArea = 'Finanzas';
                else if (asignacion.IdArea === 3) nombreArea = 'Recursos Humanos';
                else if (asignacion.IdArea === 4) nombreArea = 'Logística';
                
                // Obtener código y nombre de la cuenta
                const codigo = await obtenerCodigoHija(asignacion.IdCuenta_Hija);
                const nombre = await obtenerNombreHIja(asignacion.IdCuenta_Hija);
                
                row.innerHTML = `
                    <td>${nombreArea}</td>
                    <td>${codigo}</td>
                    <td>${nombre}</td>
                    <td>
                        <button class="btn-action btn-delete" data-id="${asignacion.IdArea_cuenta}">Eliminar</button>
                    </td>
                `;
                tbody.appendChild(row);
            }
            
            // Agregar eventos a los botones de eliminar
            document.querySelectorAll('.btn-delete').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    confirmarEliminacion(id);
                });
            });
            
        } else {
            console.error("Error al obtener asignaciones:", data.message);
            tbody.innerHTML = '<tr><td colspan="4" class="text-center">Error al cargar las asignaciones</td></tr>';
        }

    } catch (error) {
        console.error("Error de red al cargar asignaciones:", error);
        tbody.innerHTML = '<tr><td colspan="4" class="text-center">Error de conexión al cargar las asignaciones</td></tr>';
    }
}

// Función para eliminar una asignación
async function eliminarAsignacion(id) {
    try {
        const response = await fetch(`<?php echo config('app.url'); ?>/api/area-cuenta/eliminar/${id}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
        });
        
        const data = await response.json();
        
        if (response.ok) {
            mostrarNotificacion('Asignación eliminada correctamente', 'success');
            cargarAsignaciones(); // Recargar la tabla
        } else {
            mostrarNotificacion(`Error: ${data.message || 'No se pudo eliminar la asignación'}`, 'error');
        }
    } catch (error) {
        console.error('Error al eliminar asignación:', error);
        mostrarNotificacion('Error de conexión al eliminar la asignación', 'error');
    }
}
    </script>
@endsection