<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema Contable')</title>
    <!-- SweetAlert2 -->
    <link rel="icon" href="{{ asset('img/2.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        html, body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #D7B56D, rgb(153, 119, 46));
            min-height: 100%;
            overflow-y: auto;
        }

        .logout-btn:hover {
            background-color: #8B0000;
        }

        .logout-btn i {
            margin-right: 0;
        }
        
        .main-content {
            padding-top: 40px; /* Ajusta según la altura de tu navbar */
        }
        
        /* Ocultar el navbar en pantallas grandes */
        @media (min-width: 992px) {
            .navbar-collapse {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div>
        <!-- Navbar con offcanvas -->
        <nav class="navbar bg-body-tertiary fixed-top">
            <div class="container-fluid">
                <!-- Mostrar siempre el botón toggler -->
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <!-- Mostrar siempre el título -->
                <a class="navbar-brand mx-auto" href="#">Sistema Contable</a>
                
                <!-- Offcanvas -->
                <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title">Operaciones Actuales</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link active" href="{{ route('contabilidad.home') }}">Home</a>
                            </li>
                            
                            <!-- Dropdown dentro del offcanvas -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    Libro Contable
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('contabilidad.cuentas') }}">Asignación de cuentas</a></li>
                                    <li><a class="dropdown-item" href="{{ route('contabilidad.cuentas_hija') }}">Asignación de sub cuentas</a></li>
                                    <li><a class="dropdown-item" href="{{ route('contabilidad.asignar_cuentas') }}">Asignar cuentas a Áreas</a></li>
                                </ul>
                            </li>
                            
                            <li class="nav-item mt-3">
                                <button class="btn btn-danger w-100" onclick="logout()">
                                    <i class="fas fa-power-off"></i> Cerrar sesión
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </div>
    
    <div class="main-content">
        @yield('content')
    </div>

    <script>
        // Inicializar dropdowns
        document.querySelectorAll('.dropdown-toggle').forEach((dropdownToggleEl) => {
            new bootstrap.Dropdown(dropdownToggleEl);
        });

        // Cerrar offcanvas al hacer clic fuera
        document.addEventListener('click', function(event) {
            const offcanvas = document.getElementById('offcanvasNavbar');
            const isOffcanvasOpen = offcanvas && offcanvas.classList.contains('show');
            const isClickInsideOffcanvas = event.target.closest('#offcanvasNavbar');
            const isClickOnToggler = event.target.closest('.navbar-toggler');

            if (isOffcanvasOpen && !isClickInsideOffcanvas && !isClickOnToggler) {
                const closeButton = offcanvas.querySelector('.btn-close');
                if (closeButton) {
                    closeButton.click();
                }
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            const token = localStorage.getItem('token');
            if (!token) {
                window.location.href = '{{ route('contabilidad.login') }}';
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
                const url = "{{ config('app.url') }}/api/logout";
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
                        window.location.href = "{{ route('welcome') }}";
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
</body>
</html>