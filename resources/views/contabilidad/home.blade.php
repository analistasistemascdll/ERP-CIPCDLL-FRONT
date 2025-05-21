@extends('layouts.base')

@section('title', 'Inicio - Sistema Contable')

@section('content')
    <div class="welcome-wrapper">
        <div class="welcome-box">
            <h2>¡Bienvenido al Sistema Contable!</h2>
            <p>Has iniciado sesión correctamente. Desde aquí puedes gestionar tus cuentas, revisar libros contables y más.</p>
        </div>
    </div>

    <style>
        .welcome-wrapper {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            padding: 20px;
            box-sizing: border-box;
        }

        .welcome-box {
            background-color: rgba(255, 255, 255, 0.4); /* Más transparente */
            padding: 40px 30px;
            border-left: 8px solid #B40404;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
        }

        .welcome-box h2 {
            color: #B40404;
            font-size: 28px;
            margin-bottom: 15px;
        }

        .welcome-box p {
            color: #333;
            font-size: 18px;
        }

        @media (max-width: 768px) {
            .welcome-box {
                padding: 25px 20px;
            }

            .welcome-box h2 {
                font-size: 22px;
            }

            .welcome-box p {
                font-size: 16px;
            }
        }
    </style>
@endsection
