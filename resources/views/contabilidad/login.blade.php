<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('img/2.png') }}">
    <title>Login</title>
    <style>
        /* Fuentes personalizadas */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');
        
        /* Estilos generales */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', Arial, sans-serif;
            background: linear-gradient(135deg, #D7B56D, rgb(153, 119, 46)); 
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-attachment: fixed; /* Fondo fijo */
            opacity: 0;
            animation: fadeIn 2s ease-in-out forwards; /* FadeIn más lento */
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }

        /* Contenedor transparente con efecto de vidrio */
        .login-container {
            background-color: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px); /* Efecto de vidrio */
            padding: 3rem; 
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 450px;
            min-height: 550px; 
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: flex-start; 
            opacity: 0;
            transform: translateY(20px);
            animation: slideIn 1s ease-in-out 0.5s forwards; /* SlideIn más lento */
        }

        @keyframes slideIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Cabecera con imagen */
        .login-header {
            margin-bottom: 2.5rem; /* Más espacio debajo del logo */
            position: relative;
            opacity: 0;
            animation: fadeIn 1s ease-in-out 1s forwards; /* FadeIn más lento */
        }

        .login-header img {
            width: 120px; 
            height: 120px; 
            border-radius: 50%; 
            border: 4px solid #B02E2D; 
            object-fit: cover;
            transition: transform 0.5s ease; /* Transición más lenta */
        }

        .login-header img:hover {
            transform: scale(1.05);
        }

        /* Textos */
        .login-container h2 {
            margin: 0; 
            color: #333;
            font-weight: 600;
            opacity: 0;
            animation: fadeIn 1s ease-in-out 1.5s forwards; /* FadeIn más lento */
        }

        .login-container h2:first-child {
            margin-bottom: 1rem; /* Más espacio debajo del título principal */
            color: #B02E2D;
            font-size: 1.8rem;
        }

        .login-container h2:nth-child(2) {
            color: #666;
            font-size: 1.2rem;
            margin-bottom: 2rem; /* Más espacio debajo del subtítulo */
        }

        /* Grupo de inputs con diseño mejorado */
        .input-group {
            position: relative;
            margin-bottom: 2rem; /* Más espacio entre los inputs */
            text-align: left; 
            opacity: 0;
            animation: fadeIn 1s ease-in-out 2s forwards; /* FadeIn más lento */
        }

        .input-group label {
            display: block;
            margin-bottom: 0.75rem; /* Más espacio debajo del label */
            color: #333; 
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.5s ease; /* Transición más lenta */
        }

        .input-group input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            padding-right: 2.5rem; 
            box-sizing: border-box;
            background-color: #f9f9f9;
            color: #000; 
            transition: all 0.5s ease; /* Transición más lenta */
        }

        .input-group input:focus {
            outline: none;
            border-color: #B02E2D;
            box-shadow: 0 0 10px rgba(160, 44, 42, 0.2);
        }

        .input-group .toggle-password {
            position: absolute;
            right: 0.75rem;
            top: 60%; 
            transform: translateY(-50%);
            cursor: pointer;
            color: #666; 
            background: none;
            border: none;
            font-size: 1.2rem;
            padding: 0;
            transition: color 0.5s ease; /* Transición más lenta */
        }

        .input-group .toggle-password:hover {
            color: #B02E2D; 
        }

        /* Botón de login con efecto */
        .login-container input[type="submit"] {
            width: 100%;
            padding: 0.75rem;
            background-color: #B02E2D; 
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 1rem; /* Más espacio encima del botón */
            transition: all 0.5s ease; /* Transición más lenta */
            font-weight: 600;
            letter-spacing: 1px;
            opacity: 0;
            animation: fadeIn 1s ease-in-out 2.5s forwards; /* FadeIn más lento */
        }

        .login-container input[type="submit"]:hover {
            background-color: #8B0000; 
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        /* Estilos responsive */
        @media (max-width: 480px) {
            .login-container {
                padding: 2rem 1.5rem; 
                min-height: 500px; 
                width: 95%;
                margin: 0 10px;
            }

            .login-header img {
                width: 100px;
                height: 100px;
            }

            .login-container h2:first-child {
                font-size: 1.5rem;
            }

            .login-container h2:nth-child(2) {
                font-size: 1rem;
            }

            .input-group input {
                padding: 0.5rem;
                padding-right: 2rem;
            }

            .input-group .toggle-password {
                right: 0.5rem;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Cabecera con imagen -->
        <div class="login-header">
            <img src="{{ asset('img/2.png') }}" alt="Logo">
        </div>
        <h2>Login</h2>
        <h2>Contabilidad</h2>
        <form id="loginForm">
            <div class="input-group">
                <label for="username">Correo</label>
                <input type="text" id="username" placeholder="Ingresa tu usuario" required>
            </div>
            <div class="input-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" placeholder="Ingresa tu contraseña" required>
                <button type="button" class="toggle-password" onclick="togglePassword()">👁️‍🗨️</button>
            </div>
            <input type="submit" value="Iniciar Sesión">
        </form>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.querySelector('.toggle-password');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.textContent = '👁️'; // Cambia a ojo abierto
            } else {
                passwordInput.type = 'password';
                toggleIcon.textContent = '👁️‍🗨️'; // Cambia a ojo cerrado
            }
        }

        // Asegurar que el campo de contraseña esté cerrado por defecto
        document.addEventListener('DOMContentLoaded', () => {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.querySelector('.toggle-password');
            passwordInput.type = 'password'; // Campo cerrado por defecto
            toggleIcon.textContent = '👁️‍🗨️'; // Ícono cerrado por defecto
        });
    </script>
   <script>
    document.getElementById('loginForm').addEventListener('submit', async function(event) {
        event.preventDefault(); // Evita envío tradicional del formulario
        
        const url = "<?php echo config('app.url'); ?>/api/login";
        const email = document.getElementById('username').value;
        const password = document.getElementById('password').value;

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ email, password }),
                credentials: 'include'
            });

            const data = await response.json();

            if (response.ok) {
                const token = data.token;
                const userId = data.user.id;

                localStorage.setItem('token', token);

                const rolUrl = `{{ config('app.url') }}/api/user_rol/${userId}`;
                
                fetch(rolUrl, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                })
                .then(response => response.json())
                .then(rolData => {
                    const idRol = rolData.idRol;
                    localStorage.setItem('rol', idRol); // opcional, por si lo necesitas después

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Inicio de sesión exitoso',
                        showConfirmButton: false,
                        timer: 1000
                    }).then(() => {
                        if (idRol == 1) {
                            window.location.href = "{{ route('contabilidad.home') }}";
                        } else if (idRol == 2) {
                            window.location.href = "{{ route('recursosHumanos.home') }}";
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Rol no autorizado',
                                text: 'Este rol no tiene una vista asignada.'
                            });
                        }
                    });
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al obtener el rol',
                        text: error.toString()
                    });
                });
            } else {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: data.message || 'Error al iniciar sesión',
                    showConfirmButton: false,
                    timer: 1000
                });
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: 'Error al conectar con el servidor',
                showConfirmButton: false,
                timer: 1000
            });
        }
    });
</script>



    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>