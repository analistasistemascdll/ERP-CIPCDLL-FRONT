<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recursos Humanos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            display: flex;
            background: linear-gradient(135deg, #cfd9df, #e2ebf0);
            height: 100vh;
            overflow: hidden;
        }

        .sidebar {
            width: 250px;
            background: rgba(40, 116, 166, 0.95); /* Azul elegante con transparencia */
            color: white;
            height: 100vh;
            padding: 20px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.15);
            position: fixed;
            left: 0;
            top: 0;
            z-index: 999;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
            cursor: pointer;
            padding: 12px;
            background-color: transparent;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .sidebar h2:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
            overflow: hidden;
            max-height: 500px;
            transition: max-height 1s ease;
        }

        .sidebar ul.collapsed {
            max-height: 0;
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
            background-color: transparent;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .sidebar ul li a:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .main-content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            margin-left: 250px;
            position: relative;
        }

        .container {
            text-align: center;
            background: rgba(255, 255, 255, 0.95);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 380px;
        }

        h2 {
            color: #2c3e50;
        }

        .logout-btn {
            position: absolute;
            top: 25px;
            right: 30px;
            background-color: #2874A6;
            color: white;
            border: none;
            padding: 12px 15px;
            font-size: 16px;
            border-radius: 50%;
            cursor: pointer;
            transition: background 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logout-btn:hover {
            background-color: #1A5276;
        }

        .logout-btn i {
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2 onclick="toggleMenu()">Recursos Humanos</h2>
        <ul id="sidebarMenu">
            <li><a href="{{ route('recursosHumanos.home') }}">Inicio</a></li>
        </ul>
    </div>

    <div class="main-content">
        <button class="logout-btn" onclick="logout()">
            <i class="fas fa-power-off"></i>
        </button>

        <div class="container">
            <h2>Bienvenido</h2>
            <p>Has ingresado al módulo de Recursos Humanos.</p>
        </div>
    </div>

    <script>
        function toggleMenu() {
            const sidebarMenu = document.getElementById('sidebarMenu');
            sidebarMenu.classList.toggle('collapsed');
        }

        document.addEventListener('DOMContentLoaded', () => {
            const token = localStorage.getItem('token');
            if (!token) {
                window.location.href = '{{ route("contabilidad.login") }}';
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
                window.location.href = '{{ route("contabilidad.login") }}';
                return;
            }

            try {
                const url = "<?php echo config('app.url'); ?>/api/logout";
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    credentials: 'include'
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
