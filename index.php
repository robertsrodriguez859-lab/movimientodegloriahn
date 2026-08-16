<?php
// ==========================================================
// CONTROL DE SESIÓN Y PERMISOS (PHP)
// ==========================================================
session_start();
if (!isset($_SESSION['usuario_actual'])) {
    header("Location: login.php");
    exit();
}

// PROTECCIÓN ESPECIAL: Si el usuario es 'admin', forzamos acceso total de inmediato
if (strcasecmp($_SESSION['usuario_actual'], 'admin') === 0) {
    $_SESSION['rol'] = 'admin';
    $_SESSION['permisos'] = ['todos'];
}

$rolUsuario = $_SESSION['rol'] ?? 'usuario';
$esAdmin = (strcasecmp($rolUsuario, 'admin') === 0 || strcasecmp($rolUsuario, 'administrador') === 0);
$permisosUsuario = $_SESSION['permisos'] ?? [];

// FUNCIÓN BLINDADA DE PERMISOS: Normaliza tildes, espacios y mayúsculas
function tieneAccesoModulo($nombreModulo, $esAdmin, $permisosUsuario) {
    if ($esAdmin) return true;
    if (!is_array($permisosUsuario)) return false;

    foreach ($permisosUsuario as $permiso) {
        $pClean = mb_strtolower(trim($permiso), 'UTF-8');
        // Reemplazar tildes y caracteres especiales
        $pClean = str_replace(
            ['á', 'é', 'í', 'ó', 'ú', 'ä', 'ë', 'ï', 'ö', 'ü', 'ñ'],
            ['a', 'e', 'i', 'o', 'u', 'a', 'e', 'i', 'o', 'u', 'n'],
            $pClean
        );
        $pSlug = str_replace(' ', '_', $pClean);

        // Si tiene permiso total
        if ($pSlug === 'todos' || $pSlug === 'todo' || $pSlug === 'admin') return true;

        // Coincidencia exacta con el identificador del módulo
        if ($pSlug === $nombreModulo) return true;

        // Coincidencias flexibles para nombres largos comunes
        if ($nombreModulo === 'jovenes_cc' && ($pSlug === 'jovenes_contra_cultura' || $pSlug === 'contra_cultura')) {
            return true;
        }
    }
    return false;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sistema - Ministerio Internacional Movimiento de Gloria</title>
<link rel="icon" type="image/png" href="Logo.png">
<style>
  :root{
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
  *{box-sizing:border-box;}

  body{
    margin:0;
    background:var(--bg);
    color:var(--ink);
    font-family:'Karla', -apple-system, sans-serif;
    min-height:100vh;
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    padding:20px;
  }

  .app{
    max-width:800px;
    width:100%;
    background:var(--bg-card);
    border:1px solid var(--rule);
    border-radius:var(--radius);
    padding:30px;
    box-shadow:0 10px 30px rgba(0,0,0,0.05);
    text-align:center;
  }

  .ministerio-container {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    margin-bottom: 15px;
    flex-wrap: wrap;
  }
  .login-logo {
    height: 40px;
    width: auto;
    object-fit: contain;
  }
  .ministerio-banner{
    font-family:'Fraunces', serif;
    font-size: clamp(1.1rem, 3vw, 1.4rem);
    font-weight:700;
    text-transform:uppercase;
    letter-spacing:0.15em;
    color:var(--gold);
    margin: 0;
  }

  h1{
    font-family:'Fraunces', serif;
    font-weight:600;
    font-size:1.8rem;
    color:var(--wine-dark);
    margin-bottom: 5px;
  }
  .subtitle{
    font-size:0.9rem;
    color:var(--ink-soft);
    font-style:italic;
    margin-bottom:25px;
  }

  .nav-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));
    gap:12px;
    margin-bottom:25px;
  }
  .nav-btn{
    font-family:'Karla', sans-serif;
    font-size:0.95rem;
    font-weight:700;
    padding:14px;
    border-radius:8px;
    border:1px solid var(--rule);
    background:var(--bg);
    color:var(--ink);
    text-decoration:none;
    cursor:pointer;
    transition:all 0.15s ease;
    display:block;
  }
  .nav-btn:hover{border-color:var(--wine); color:var(--wine); background:#fff;}
  .nav-btn.active{background:var(--wine); color:#fff; border-color:var(--wine);}

  .top-actions{
    display:flex;
    justify-content:center;
    gap:15px;
    border-top:1px solid var(--rule);
    padding-top:20px;
    flex-wrap: wrap;
  }
  .btn{
    font-family:'Karla', sans-serif;
    font-weight:700;
    font-size:0.85rem;
    border-radius:6px;
    padding:8px 16px;
    text-decoration:none;
    cursor:pointer;
    border:1px solid var(--rule);
    background:transparent;
    color:var(--ink-soft);
  }
  .btn:hover{border-color:var(--wine); color:var(--wine);}
  .btn-danger{color:var(--wine); border-color:var(--wine);}
</style>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@500;600;700&family=Karla:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="app">

  <div class="ministerio-container">
    <img src="Logo.png" alt="Logo Iglesia" class="login-logo">
    <div class="ministerio-banner">Ministerio Internacional Movimiento de Gloria</div>
  </div>

  <h1>Panel Principal</h1>
  <div class="subtitle">Seleccione una sección para administrar los registros</div>

  <div class="nav-grid">
    <?php if (tieneAccesoModulo('miembros', $esAdmin, $permisosUsuario)): ?>
      <a href="miembros.php" class="nav-btn">Miembros</a>
    <?php endif; ?>

    <?php if (tieneAccesoModulo('jovenes_cc', $esAdmin, $permisosUsuario)): ?>
      <a href="jovenes_contra_cultura.php" class="nav-btn">Jóvenes Contra Cultura</a>
    <?php endif; ?>

    <?php if (tieneAccesoModulo('jovenes_invitados', $esAdmin, $permisosUsuario)): ?>
      <a href="jovenes_invitados.php" class="nav-btn">Jóvenes Invitados</a>
    <?php endif; ?>

    <?php if (tieneAccesoModulo('equipo_ministerial', $esAdmin, $permisosUsuario)): ?>
      <a href="equipo_ministerial.php" class="nav-btn">Equipo Ministerial</a>
    <?php endif; ?>

    <?php if (tieneAccesoModulo('escuela_ministerial', $esAdmin, $permisosUsuario)): ?>
      <a href="escuela_ministerial.php" class="nav-btn">Escuela Ministerial</a>
    <?php endif; ?>

    <?php if (tieneAccesoModulo('visitantes', $esAdmin, $permisosUsuario)): ?>
      <a href="visitantes.php" class="nav-btn">Visitantes</a>
    <?php endif; ?>

    <?php if (tieneAccesoModulo('casas_de_paz', $esAdmin, $permisosUsuario)): ?>
      <a href="casas_de_paz.php" class="nav-btn">Casas de Paz</a>
    <?php endif; ?>
  </div>

  <div class="top-actions">
    <?php if (tieneAccesoModulo('reportes', $esAdmin, $permisosUsuario)): ?>
      <a href="reportes.php" class="btn">Reportes</a>
    <?php endif; ?>

    <?php if (tieneAccesoModulo('usuarios', $esAdmin, $permisosUsuario)): ?>
      <a href="usuarios.php" class="btn">Usuarios</a>
    <?php endif; ?>

    <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
  </div>
</div>

</body>
</html>
