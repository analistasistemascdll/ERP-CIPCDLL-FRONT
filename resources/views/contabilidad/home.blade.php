<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Contable</title>
    <!-- Incluye FontAwesome para íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            display: flex;
            background: linear-gradient(135deg, #D7B56D, rgb(153, 119, 46));
            height: 100vh;
            overflow: hidden; /* Evita el desplazamiento horizontal */
        }

        /* Barra lateral */
        .sidebar {
            width: 250px;
            background: rgba(176, 46, 45, 0.9); /* Color #B02E2D con transparencia */
            color: white;
            height: 100vh;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            position: fixed;
            left: 0;
            top: 0;
            z-index: 999; /* Asegura que esté por encima del contenido */
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
            cursor: pointer; /* Cambia el cursor al pasar sobre "Recursos Humanos" */
            padding: 10px;
            background-color: transparent; /* Fondo transparente */
            border-radius: 8px; /* Bordes redondeados */
            transition: background-color 0.3s ease; /* Transición suave */
        }

        .sidebar h2:hover {
            background-color: rgba(139, 0, 0, 0.2); /* Hover más oscuro */
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
            overflow: hidden; /* Oculta el contenido que se desborda */
            max-height: 500px; /* Altura máxima para el menú */
            transition: max-height 1s ease; /* Animación suave */
        }

        .sidebar ul.collapsed {
            max-height: 0; /* Oculta los elementos al colapsar */
        }

        .sidebar ul li {
            margin: 15px 0;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            display: block;
            padding: 10px;
            background-color: transparent; /* Fondo transparente */
            border-radius: 8px; /* Bordes redondeados */
            transition: background-color 0.3s ease; /* Transición suave */
        }

        .sidebar ul li a:hover {
            background-color: rgba(139, 0, 0, 0.2); /* Hover más oscuro */
        }

        /* Contenido principal */
        .main-content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            position: relative; /* Para posicionar el botón de cerrar sesión */
            margin-left: 250px; /* Ajuste para la barra lateral */
        }

        .container {
            text-align: center;
            background: rgba(255, 255, 255, 0.85);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            width: 350px;
        }

        h2 {
            color: #333;
        }

        /* Botón de cerrar sesión */
        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #B02E2D;
            color: white;
            border: none;
            padding: 10px 15px;
            font-size: 16px;
            border-radius: 50%; /* Forma circular */
            cursor: pointer;
            transition: background 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logout-btn:hover {
            background-color: #8B0000;
        }

        .logout-btn i {
            margin-right: 0; /* Ajuste para el ícono */
        }
    </style>
</head>
<body>
    <!-- Barra lateral -->
    <div class="sidebar">
        <h2 onclick="toggleMenu()">Contabilidad</h2>
        <ul id="sidebarMenu">
            <li><a href="{{route('contabilidad.home')}}">Inicio</a></li>
            <li><a href="{{route('contabilidad.libro_contable')}}">Libro contable</a></li>
            <li><a href="#"></a></li>
            <li><a href="#"></a></li>
            <li><a href="#"></a></li>
        </ul>
    </div>

    <!-- Contenido principal -->
    <div class="main-content">
        <!-- Botón de cerrar sesión -->
        <button class="logout-btn" onclick="logout()">
            <i class="fas fa-power-off"></i> <!-- Ícono de apagado -->
        </button>

        <div class="container">
            <h2>Bienvenido</h2>
            <p>Has iniciado sesión correctamente.</p>
        </div>
    </div>

    <script>
        // Función para desplegar/ocultar el menú de la barra lateral
        function toggleMenu() {
            const sidebarMenu = document.getElementById('sidebarMenu');
            sidebarMenu.classList.toggle('collapsed');
        }

        // Verificar si el usuario tiene un token almacenado
        document.addEventListener('DOMContentLoaded', () => {
            const token = localStorage.getItem('token');
            if (!token) {
                window.location.href = '{{ route('contabilidad.login') }}'; // Redirige al login si no hay token
            }
        });
        async function logout() {
    const token = localStorage.getItem('token');
    if (!token) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: 'No hay sesión activa',
            showConfirmButton: false,
            timer: 2000
        });
        window.location.href = '{{ route('contabilidad.login') }}';
        return;
    }

    try {
        const url = "<?php echo config('app.url'); ?>/api/logout"; // <- CORRECTO
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            credentials: 'include' // si estás usando Laravel Sanctum
        });

        if (response.ok) {
            localStorage.removeItem('token');
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Sesión cerrada con éxito',
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                window.location.href = "{{ route('contabilidad.login') }}";
            });
        } else {
            const errorData = await response.json();
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: errorData.message || 'Error al cerrar sesión',
                showConfirmButton: false,
                timer: 2000
            });
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: 'No se pudo conectar con el servidor',
            showConfirmButton: false,
            timer: 2000
        });
    }
}

      
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>