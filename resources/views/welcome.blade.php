<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EDP - CIPCDLL</title>
  <link rel="icon" href="{{ asset('img/2.png') }}">
  <style>
    body, html {
      margin: 0;
      padding: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
    }
    
    .container {
      position: relative;
      width: 100%;
      height: 100vh;
      background-color: white; /* Fondo inicial blanco */
      animation: changeBackground 3.3s ease-in-out 1.5s forwards; /* Transición de 3.5 segundos */
    }

    @keyframes changeBackground {
      to {
        background-color: #D7B56D; /* Cambia a crema */
      }
    }
    
    .left-side {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: #B02E2D;
      clip-path: polygon(0 0, 100% 0, 0 100%);
      transition: clip-path 1.5s ease-in-out;
    }
    
    .right-side {
      position: absolute;
      top: 0;
      right: 0;
      width: 100%;
      height: 100%;
      background-color: #C13835;
      clip-path: polygon(100% 0, 0 100%, 100% 100%);
      transition: clip-path 1.5s ease-in-out;
    }
    
    .image-container {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      z-index: 10;
      cursor: pointer;
      transition: opacity 1s ease-in-out;
    }
    
    .image-container img {
      max-width: 100%;
      height: auto;
      display: block;
    }
    
    .left-side.animate {
      clip-path: polygon(0 0, 0 0, 0 100%);
    }
    
    .right-side.animate {
      clip-path: polygon(100% 0, 100% 100%, 100% 100%);
    }
    
    .image-container.animate {
      opacity: 0;
    }
    
    /* Ajuste del contenedor de divs transparentes */
    .transparent-container {
      display: flex;
      flex-direction: row;
      justify-content: center;
      align-items: center;
      width: 100%;
      position: absolute;
      top: 40px;
      left: 50%;
      transform: translateX(-50%);
      opacity: 0;
      transition: opacity 1s ease-in-out;
    }

    .transparent-div {
      width: 20vw; /* Tamaño más pequeño */
      height: 20vw; /* Tamaño más pequeño */
      max-width: 360px; /* Tamaño máximo más pequeño */
      max-height: 360px; /* Tamaño máximo más pequeño */
      aspect-ratio: 1/1;
      margin: 0 20px;
      border: 2px solid rgba(255, 255, 255, 0.7);
      border-radius: 10px;
      backdrop-filter: blur(5px);
      background-color: rgba(255, 255, 255, 0.2);
      opacity: 0;
      transform: translateY(20px) scale(1); /* Escala inicial */
      transition: 
          opacity 0.5s ease-in-out, 
          transform 0.5s ease-in-out, 
          box-shadow 0.3s ease-in-out,
          background-color 0.3s ease-in-out;
      cursor: pointer;
      pointer-events: auto; /* Asegura que todo el div sea interactivo */

    }

    .transparent-div.visible {
      opacity: 1;
      transform: translateY(0) scale(1); /* Escala normal al aparecer */
    }

    .transparent-div:hover {
      transform: scale(1.1); /* Aumenta un 10% el tamaño */
      box-shadow: 0px 0px 20px rgba(255, 255, 255, 0.7); /* Sombra más suave */
      background-color: rgba(255, 255, 255, 0.4); /* Fondo más visible */
      opacity: 1;
    }

    .transparent-container.visible {
      opacity: 1;
    }

    .transparent-div:hover img {
      filter: brightness(1.1); /* Hace la imagen un poco más brillante */
    }

    .transparent-div img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 4px;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="left-side" id="leftSide"></div>
    <div class="right-side" id="rightSide"></div>
    <div class="image-container" id="imageContainer">
      <img src="{{ asset('img/2.png') }}" alt="Imagen central">
    </div>
    
    <!-- Divs transparentes en vertical -->
    <div class="transparent-container" id="transparentContainer">
      <div class="transparent-div" id="div1">
        <img src="{{ asset('img/aviso.jpeg') }}" alt="Imagen financiero">
      </div>
      <div class="transparent-div" id="div2" onclick="window.location.href='{{ route('contabilidad.login') }}'">
        <img src="{{ asset('img/contabilidad.jpg') }}" alt="Imagen contabilidad">
      </div>
      <div class="transparent-div" id="div3">
        <img src="{{ asset('img/construccion.jpeg') }}" alt="Imagen colegiatura">
      </div>
    </div>
  </div>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const imageContainer = document.getElementById('imageContainer');
      const leftSide = document.getElementById('leftSide');
      const rightSide = document.getElementById('rightSide');
      const transparentContainer = document.getElementById('transparentContainer');
      const div1 = document.getElementById('div1');
      const div2 = document.getElementById('div2');
      const div3 = document.getElementById('div3');
      
      imageContainer.addEventListener('click', function() {
        // Aplicar clases para animar
        imageContainer.classList.add('animate');
        leftSide.classList.add('animate');
        rightSide.classList.add('animate');
        
        // Mostrar los divs transparentes después de la animación
        setTimeout(function() {
          transparentContainer.classList.add('visible');
          
          // Mostrar los divs uno por uno con un pequeño retraso entre ellos
          setTimeout(function() { div1.classList.add('visible'); }, 200);
          setTimeout(function() { div2.classList.add('visible'); }, 400);
          setTimeout(function() { div3.classList.add('visible'); }, 600);
        }, 1700); // Un poco después de que terminen las animaciones anteriores
      });
    });
  </script>
</body>
</html>