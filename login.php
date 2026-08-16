<?php
// ==========================================================
// CONTROL DE SESIÓN (PHP) — LÓGICA INVERSA A LAS DEMÁS PÁGINAS
// Este es el archivo de LOGIN, así que la lógica es al revés
// del resto del sistema: si YA existe una sesión activa, no
// tiene sentido mostrar el formulario de login, así que
// redirige directo al panel principal (index.php).
// ==========================================================
session_start();
// Si ya hay una sesión activa de PHP, redirigir al panel principal
if (isset($_SESSION['usuario_actual'])) {
    header("Location: index.php");
    exit();
}

// ==========================================================
// PROCESAR EL LOGIN (backend, vía POST)
// El formulario de login NO se envía de forma tradicional;
// el JavaScript de abajo primero valida el usuario/contraseña
// contra Firestore, y SOLO si son correctos, hace un fetch()
// POST a este mismo archivo (login.php) para que PHP cree
// la variable de sesión $_SESSION['usuario_actual'].
// Este bloque es el que atiende esa petición POST.
// ==========================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true); // Lee el JSON enviado por fetch()
    if (isset($data['usuario'])) {
        $_SESSION['usuario_actual'] = $data['usuario']; // Crea la sesión del lado del servidor
        echo json_encode(["status" => "success"]);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Iniciar Sesión — Sistema Iglesia</title>
<link rel="icon" type="image/png" href="Logo.png">
<!-- ==========================================================
     ESTILOS (CSS)
     Tarjeta de login centrada en la pantalla, con logo, campos
     de usuario/contraseña y mensaje de error. Incluye un ajuste
     especial para pantallas pequeñas (@media).
     ========================================================== -->
<style>
  /* Variables globales de color y radio de bordes (mismas del resto del sistema) */
  :root {
    --bg: #EEF1EC;
    --bg-card: #FBFAF7;
    --ink: #232B3A;
    --ink-soft: #5B6472;
    --wine: #000000;
    --wine-dark: #111111;
    --gold: #B8923F;
    --rule: #D9D4C8;
    --radius: 10px;
  }
  * { box-sizing: border-box; }

  /* El body centra la tarjeta de login tanto vertical como horizontalmente */
  body {
    margin: 0;
    background: var(--bg);
    color: var(--ink);
    font-family: 'Karla', -apple-system, sans-serif;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    padding: 16px;
  }

  /* Tarjeta blanca que contiene el logo, título y formulario */
  .login-card {
    background: var(--bg-card);
    border: 1px solid var(--rule);
    border-radius: var(--radius);
    padding: clamp(24px, 5vw, 36px) clamp(20px, 4vw, 28px);
    width: 100%;
    max-width: 400px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    display: flex;
    flex-direction: column;
    align-items: center;
  }
  .login-logo {
    max-width: clamp(70px, 15vw, 95px);
    height: auto;
    margin-bottom: 14px;
    object-fit: contain;
  }
  .login-card h2 {
    font-family: 'Fraunces', serif;
    font-size: clamp(1.2rem, 3.5vw, 1.45rem);
    color: var(--wine-dark);
    margin: 0 0 6px 0;
    text-align: center;
    line-height: 1.3;
  }
  .login-card p {
    font-size: 0.85rem;
    color: var(--ink-soft);
    margin: 0 0 22px 0;
    text-align: center;
  }

  /* --- Formulario de usuario y contraseña --- */
  .login-form {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 14px;
  }
  .login-form label {
    display: flex;
    flex-direction: column;
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--ink-soft);
    gap: 6px;
    text-transform: uppercase;
    letter-spacing: 0.03em;
  }
  input[type="text"], input[type="password"] {
    font-family: 'Karla', sans-serif;
    font-size: 1rem;
    border: 1px solid var(--rule);
    border-radius: 6px;
    padding: 12px;
    background: #fff;
    color: var(--ink);
    width: 100%;
  }
  input:focus {
    outline: 2px solid var(--gold);
    outline-offset: 1px;
  }

  /* --- Botón de "Iniciar sesión" --- */
  .btn {
    font-family: 'Karla', sans-serif;
    font-weight: 700;
    font-size: 0.95rem;
    border: none;
    border-radius: 6px;
    padding: 12px;
    background: var(--wine);
    color: #fff;
    cursor: pointer;
    transition: background 0.15s ease, transform 0.08s ease;
    margin-top: 6px;
    width: 100%;
  }
  .btn:hover { background: var(--wine-dark); }
  .btn:active { transform: scale(0.98); } /* Pequeño efecto de "presionado" */

  /* Mensaje de error (credenciales incorrectas o fallo de conexión); oculto por defecto */
  .error-msg {
    color: #b30000;
    font-size: 0.82rem;
    text-align: center;
    display: none;
    margin-top: 4px;
  }

  /* En pantallas muy angostas (celulares), se quita el "efecto tarjeta"
     (sombra, borde, fondo) para que se vea más simple e integrado con el fondo */
  @media (max-width: 480px) {
    body {
      padding: 12px;
      align-items: flex-start;
      padding-top: 40px;
    }
    .login-card {
      box-shadow: none;
      border: none;
      background: transparent;
      padding: 10px;
    }
  }
</style>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@600&family=Karla:wght@400;700&display=swap" rel="stylesheet">

<!-- SDKs de Firebase (versión "compat", con sintaxis de espacio de nombres firebase.xxx
     en vez de los imports modulares que usan las demás páginas del sistema) -->
<script src="https://www.gstatic.com/firebasejs/10.8.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.8.0/firebase-firestore-compat.js"></script>
</head>
<body>

  <!-- Tarjeta de login: logo, título, formulario y mensaje de error -->
  <div class="login-card">
    <img src="Logo.png" alt="Logo Iglesia" class="login-logo">
    <h2>Ministerio Internacional Movimiento de Gloria</h2>
    <p>Ingrese su usuario y contraseña</p>

    <form class="login-form" id="formLogin">
      <label>
        Usuario
        <input type="text" id="loginUsuario" placeholder="Usuario" required autocomplete="username">
      </label>
      <label>
        Contraseña
        <input type="password" id="loginPassword" placeholder="••••••••" required autocomplete="current-password">
      </label>
      <button type="submit" class="btn">Iniciar sesión</button>
      <div class="error-msg" id="loginError">Usuario o contraseña incorrectos</div>
    </form>
  </div>

<!-- ==========================================================
     LÓGICA (JavaScript, script normal, NO type="module")
     Valida las credenciales directamente contra Firestore desde
     el navegador, y si son correctas, avisa a login.php (PHP)
     para que registre la sesión del servidor.

     ⚠️ IMPORTANTE (seguridad): aquí las contraseñas se guardan y
     comparan en TEXTO PLANO dentro de Firestore (data.password
     === pass), y la comparación ocurre en el navegador del
     usuario. Cualquiera que abra las herramientas de desarrollador
     puede ver las contraseñas de la colección "usuarios" tal como
     las devuelve la consulta. Lo recomendable sería: (1) nunca
     guardar contraseñas en texto plano (usar un hash, por ejemplo
     con password_hash() en PHP), y (2) hacer esta validación desde
     el servidor (PHP) y no desde el cliente, para no exponer datos
     de otros usuarios ni la lógica de autenticación.
     ========================================================== -->
<script>
  // --- Configuración y conexión al proyecto de Firebase ---
  const firebaseConfig = {
    apiKey: "AIzaSyD-lfkA-khlPes6zcjGmfAACWK9SK6Uhxc",
    authDomain: "mdgweb-b7ab7.firebaseapp.com",
    projectId: "mdgweb-b7ab7",
    storageBucket: "mdgweb-b7ab7.appspot.com",
    messagingSenderId: "571662431119",
    appId: "1:571662431119:web:bf0d2b4ca2164d70c9"
  };

  firebase.initializeApp(firebaseConfig);
  const db = firebase.firestore();

  // ----------------------------------------------------------
  // ENVÍO DEL FORMULARIO DE LOGIN
  // ----------------------------------------------------------
  document.getElementById('formLogin').addEventListener('submit', async (e) => {
    e.preventDefault(); // Evita que el formulario recargue la página
    const user = document.getElementById('loginUsuario').value.trim();
    const pass = document.getElementById('loginPassword').value.trim();
    const errorEl = document.getElementById('loginError');
    errorEl.style.display = 'none'; // Oculta cualquier mensaje de error previo

    try {
      // Paso 1: busca en Firestore, dentro de la colección "usuarios",
      // todos los documentos cuyo campo "username" coincida con lo escrito
      const querySnapshot = await db.collection("usuarios").where("username", "==", user).get();

      // Paso 2: entre los resultados, revisa si alguno tiene la
      // contraseña exacta (comparación en texto plano, ver nota de seguridad arriba)
      let usuarioEncontrado = null;
      querySnapshot.forEach((doc) => {
        const data = doc.data();
        if (data.password === pass) {
          usuarioEncontrado = data;
        }
      });

      if (usuarioEncontrado) {
        // Paso 3: si las credenciales son válidas, se le avisa a
        // login.php (bloque PHP de arriba) para que cree la sesión
        // del lado del servidor mediante $_SESSION
        const response = await fetch('login.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ usuario: user })
        });

        if (response.ok) {
          window.location.href = 'index.php'; // Redirige al panel principal
        }
      } else {
        // Usuario no encontrado o contraseña incorrecta
        errorEl.style.display = 'block';
      }
    } catch (error) {
      // Error de red o de conexión con Firestore
      console.error("Error al iniciar sesión:", error);
      errorEl.textContent = "Error de conexión con la base de datos";
      errorEl.style.display = 'block';
    }
  });
</script>
</body>
</html>
