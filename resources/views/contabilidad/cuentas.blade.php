@extends('layouts.base')

@section('title', 'Registrar Cuenta - Sistema Contable')

@section('content')
<div class="main-container">
        <!-- Formulario de registro -->
        <div class="form-container">
            <h2 id="formularioTitulo">Registrar Nueva Cuenta</h2>
            <form id="registroCuentaForm">
                <input type="hidden" id="cuentaId" value="">
                <div class="input-group">
                <label for="nombreCuenta">Nombre de la Cuenta</label>
                <input type="text" id="nombreCuenta" name="nombreCuenta" placeholder="Ingresa el nombre de la cuenta" pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" title="Solo se permiten letras y espacios" required>

                </div>
                <div class="input-group">
                    <label for="codigoCuenta">Código de la Cuenta</label>
                    <input type="number" id="codigoCuenta" placeholder="Ingresa el código de la cuenta" required>
                </div>
                <div class="input-group">
                    <label for="tipoCuenta">Tipo de Cuenta</label>
                    <select id="tipoCuenta" required>
                        <option value="Activos">Activos</option>
                        <option value="Pasivos">Pasivos</option>
                        <option value="Patrimonio">Patrimonio</option>
                        <option value="Gastos">Gastos</option>
                        <option value="Ingresos">Ingresos</option>
                    </select>
                </div>
                <div class="input-group">
                    <label for="descripcionCuenta">Descripción</label>
                    <textarea id="descripcionCuenta" placeholder="Descripción de la cuenta" required></textarea>
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
            <h2>Cuentas Registradas</h2>
            
            <!-- Filtro de búsqueda -->
            <div class="filtro-container">
                <div class="input-group filtro-grupo">
                    <label for="filtroCodigo">Filtrar por Código:</label>
                    <input type="number" id="filtroCodigo" placeholder="Ingrese código para filtrar">
                </div>
                <button id="btnLimpiarFiltro" class="btn-limpiar">Limpiar Filtro</button>
            </div>
            
            <div class="table-scroll">
                <table id="tablaCuentas" class="data-table">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
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
        document.getElementById("nombreCuenta").addEventListener("input", function (e) {
    this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '');
});
        // Variables globales
        let modoEdicion = false;
        let todasLasCuentas = []; // Array para guardar todas las cuentas sin filtrar
        
        // Evento para registrar cuenta nueva
        document.getElementById('registroCuentaForm').addEventListener('submit', async function(event) {
            event.preventDefault();
            
            // No hacer nada si estamos en modo edición (se usará el botón actualizar)
            if (modoEdicion) return;

            await registrarCuenta();
        });
        
// Función para registrar una nueva cuenta
async function registrarCuenta() {
    const nombreCuenta = document.getElementById('nombreCuenta').value;
    const codigoCuenta = document.getElementById('codigoCuenta').value;
    const tipoCuenta = document.getElementById('tipoCuenta').value;
    const descripcionCuenta = document.getElementById('descripcionCuenta').value;
    const estadoCuenta = 1;
    
    try {
        const url = "<?php echo config('app.url'); ?>/api/cuenta-padre/crear";
        
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            body: JSON.stringify({
                Codigo: parseInt(codigoCuenta),
                Nombre: nombreCuenta,
                Tipo: tipoCuenta,
                Descripcion: descripcionCuenta,
                Estado: estadoCuenta
            })
        });

        const data = await response.json();

        if (response.ok) {
            // Si la respuesta es exitosa
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
            await cargarCuentas();
        } else {
            // Si no es exitosa, mostrar el mensaje de error
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

        
        // Función para cargar los datos de las cuentas
        async function cargarCuentas() {
            try {
                const url = "<?php echo config('app.url'); ?>/api/cuenta-padre";
                
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${localStorage.getItem('token')}`
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Error al cargar las cuentas');
                }
                
                const cuentas = await response.json();
                todasLasCuentas = cuentas; // Guardar todas las cuentas sin filtrar
                
                // Aplicar filtro actual si existe
                const filtroCodigo = document.getElementById('filtroCodigo').value;
                if (filtroCodigo) {
                    mostrarCuentasFiltradas(filtroCodigo);
                } else {
                    renderizarTabla(cuentas);
                }
                
            } catch (error) {
                console.error('Error al cargar las cuentas:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudieron cargar las cuentas. Por favor, intenta nuevamente.'
                });
            }
        }
        
        // Función para renderizar la tabla con las cuentas
        function renderizarTabla(cuentas) {
            const tableBody = document.getElementById('cuentasTableBody');
            tableBody.innerHTML = '';
            
            if (cuentas.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="5" class="text-center">No hay cuentas registradas</td></tr>';
                return;
            }
            
            cuentas.forEach(cuenta => {
                const row = document.createElement('tr');
                row.innerHTML = `
                <td>${cuenta.Codigo}</td>
                <td>${cuenta.Nombre}</td>
                <td>${cuenta.Tipo || ''}</td>
                <td>${cuenta.Descripcion || ''}</td>
                <td>
                    <div class="acciones">
                    <button class="btn-action btn-edit" data-id="${cuenta.Idcuenta_Padre}">Editar</button> 
                    <button class="btn-action btn-delete" data-id="${cuenta.Idcuenta_Padre}">Eliminar</button>
                    </div>
                </td>
                `;
                tableBody.appendChild(row);
            });
            
            // Agregamos event listeners a los botones
            agregarEventosTabla();
        }
        
        // Función para filtrar cuentas por código
        function mostrarCuentasFiltradas(codigo) {
            const cuentasFiltradas = todasLasCuentas.filter(cuenta => 
                cuenta.Codigo.toString().includes(codigo)
            );
            renderizarTabla(cuentasFiltradas);
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
        
        // Función para cargar los datos de una cuenta en el formulario
        async function cargarDatosCuenta(id) {
            try {
                const url = `<?php echo config('app.url'); ?>/api/cuenta-padre/${id}`;
                
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
                document.getElementById('cuentaId').value = cuenta.Idcuenta_Padre;
                document.getElementById('nombreCuenta').value = cuenta.Nombre;
                document.getElementById('codigoCuenta').value = cuenta.Codigo;
                document.getElementById('tipoCuenta').value = cuenta.Tipo || '';
                document.getElementById('descripcionCuenta').value = cuenta.Descripcion || '';
                
                // Desplazarse al formulario
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
        
        // Función para activar el modo edición
        function activarModoEdicion() {
            modoEdicion = true;
            document.getElementById('formularioTitulo').textContent = 'Editar Cuenta';
            document.getElementById('btnRegistrar').disabled = true;
            document.getElementById('btnActualizar').style.display = 'inline-block';
            document.getElementById('btnCancelar').style.display = 'inline-block';
        }
        
        // Función para desactivar el modo edición
        function desactivarModoEdicion() {
            modoEdicion = false;
            document.getElementById('formularioTitulo').textContent = 'Registrar Nueva Cuenta';
            document.getElementById('btnRegistrar').disabled = false;
            document.getElementById('btnActualizar').style.display = 'none';
            document.getElementById('btnCancelar').style.display = 'none';
            document.getElementById('cuentaId').value = '';
            document.getElementById('registroCuentaForm').reset();
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
                const url = `<?php echo config('app.url'); ?>/api/cuenta-padre/eliminar/${id}`;
                
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
                await cargarCuentas();
                
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo eliminar la cuenta. Intenta nuevamente.'
                });
            }
        }
        
        // Función para actualizar una cuenta
        async function actualizarCuenta() {
            const id = document.getElementById('cuentaId').value;
            const nombreCuenta = document.getElementById('nombreCuenta').value;
            const codigoCuenta = document.getElementById('codigoCuenta').value;
            const tipoCuenta = document.getElementById('tipoCuenta').value;
            const descripcionCuenta = document.getElementById('descripcionCuenta').value;
            
            try {
                const url = `<?php echo config('app.url'); ?>/api/cuenta-padre/editar/${id}`;
                
                const response = await fetch(url, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${localStorage.getItem('token')}`
                    },
                    body: JSON.stringify({
                        Codigo: parseInt(codigoCuenta),
                        Nombre: nombreCuenta,
                        Tipo: tipoCuenta,
                        Descripcion: descripcionCuenta
                    })
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Cuenta actualizada',
                        text: 'La cuenta ha sido actualizada exitosamente.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    
                    // Desactivar modo edición
                    desactivarModoEdicion();
                    
                    // Recargar la tabla
                    await cargarCuentas();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Ocurrió un error al actualizar la cuenta.'
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
        
        // Configurar botones de acción y eventos de filtrado
        document.addEventListener('DOMContentLoaded', function() {
            // Cargar las cuentas al inicio
            cargarCuentas();
            
            // Botón Actualizar
            document.getElementById('btnActualizar').addEventListener('click', function() {
                actualizarCuenta();
            });
            
            // Botón Cancelar
            document.getElementById('btnCancelar').addEventListener('click', function() {
                desactivarModoEdicion();
            });
            
            // Evento para filtrar por código
            document.getElementById('filtroCodigo').addEventListener('input', function() {
                const codigo = this.value.trim();
                if (codigo) {
                    mostrarCuentasFiltradas(codigo);
                } else {
                    renderizarTabla(todasLasCuentas);
                }
            });
            
            // Botón para limpiar filtro
            document.getElementById('btnLimpiarFiltro').addEventListener('click', function() {
                document.getElementById('filtroCodigo').value = '';
                renderizarTabla(todasLasCuentas);
            });
        });
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
        padding: 10px;
        vertical-align: middle;
    }
       
    table td {
        vertical-align: middle; 
        word-wrap: break-word;
        word-break: break-word;
        white-space: normal;
        min-width: 80px;
        width: 220px;
        max-width: 800px;
    }

    .main-container {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 20px;
        gap: 20px;
        min-height: calc(95vh - 100px);
        background-color: transparent;
         /* borar para cambiar el tamaño del frmn */
    }

    /* Estilos para el formulario */
    .form-container {
        flex: 0 0 50%;
        background-color: rgba(255, 255, 255, 0.9);
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        height: auto;
        margin-top: 25px;
    }

    /* Estilos para la tabla */
    .table-container {
        min-height: 700px;
        flex: 0 0 49%;
        max-width: 100%;
        background-color: rgba(255, 255, 255, 0.9);
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        margin-top: 25px;
        display: flex;
        flex-direction: column;
    }

    /* Filtro de búsqueda */
    .filtro-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding: 10px;
        background-color: #f8f8f8;
        border-radius: 5px;
    }

    .filtro-grupo {
        display: flex;
        align-items: center;
        margin-bottom: 0;
        width: 70%;
    }

    .filtro-grupo label {
        margin-right: 10px;
        margin-bottom: 0;
        white-space: nowrap;
    }

    .btn-limpiar {
        background-color: #D7B56D;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .btn-limpiar:hover {
        background-color: #c9a456;
    }

    /* Scroll para la tabla */
    .table-scroll {
        overflow-y: auto;
        max-height: 570px; /* Aumentada para aprovechar el nuevo tamaño del contenedor */
        width: 100%;
        border: 1px solid #eee;
        border-radius: 5px;
        flex-grow: 1; /* Permite que el scroll ocupe todo el espacio disponible */
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
    .table-responsive {
        overflow-x: auto;
        width: 100%;
    }

    .data-table {
        width: 100%;
        max-width: 100%;
        border-collapse: collapse;
        margin-top: 0;
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
        position: sticky;
        top: 0;
        z-index: 10;
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
        font-size: 13px;
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
        .table-responsive {
            overflow-x: scroll;
        }
        
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
        
        .filtro-container {
            flex-direction: column;
        }
        
        .filtro-grupo {
            width: 100%;
            margin-bottom: 10px;
        }
        
        .btn-limpiar {
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