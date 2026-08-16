<?php
session_start();
if (!isset($_SESSION['usuario_actual'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Equipo Ministerial - Ministerio Internacional Movimiento de Gloria</title>
    <link rel="icon" type="image/png" href="Logo.png">
<style>
  :root{--bg:#EEF1EC;--bg-card:#FBFAF7;--ink:#232B3A;--ink-soft:#5B6472;--wine:#000000;--wine-dark:#111111;--gold:#B8923F;--rule:#D9D4C8;--radius:10px;}
  *{box-sizing:border-box;}
  body{margin:0;background:var(--bg);color:var(--ink);font-family:'Karla',-apple-system,sans-serif;min-height:100vh;}
  .app{max-width:1180px;margin:0 auto;padding:20px 16px 80px;width:100%;}
  .ministerio-container{display:flex;align-items:center;justify-content:center;gap:12px;margin-bottom:8px;text-align:center;flex-wrap:wrap;}
  .login-logo{height:36px;width:auto;object-fit:contain;}
  .ministerio-banner{font-family:'Fraunces',serif;font-size:clamp(1.1rem,3vw,1.5rem);font-weight:700;text-transform:uppercase;letter-spacing:0.15em;color:var(--gold);margin:0;}
  header.top{display:flex;align-items:center;justify-content:space-between;border-bottom:2px solid var(--wine);padding-bottom:14px;margin-bottom:20px;flex-wrap:wrap;gap:16px;}
  header.top .title-block{display:flex;flex-direction:column;}
  header.top h1{font-family:'Fraunces',serif;font-weight:600;font-size:clamp(1.4rem,4vw,2rem);margin:0;color:var(--wine-dark);}
  header.top .subtitle{font-size:0.85rem;color:var(--ink-soft);font-style:italic;margin-top:2px;}
  .header-actions{display:flex;align-items:center;gap:12px;flex-wrap:wrap;}
  .stat-n{font-family:'IBM Plex Mono',monospace;font-size:1.3rem;font-weight:600;color:var(--wine);display:block;line-height:1;text-align:center;}
  .stat-l{font-size:0.68rem;text-transform:uppercase;letter-spacing:0.06em;color:var(--ink-soft);text-align:center;}
  
  /* Menú de Hamburguesa (3 rayitas) */
  .menu-hamburguesa-container{position:relative;margin-bottom:20px;border-bottom:1px solid var(--rule);padding-bottom:12px;}
  .btn-hamburguesa{background:var(--bg-card);border:1px solid var(--rule);border-radius:6px;padding:10px 16px;cursor:pointer;display:inline-flex;align-items:center;gap:10px;font-family:'Karla',sans-serif;font-size:0.95rem;font-weight:700;color:var(--wine-dark);transition:all 0.15s ease;}
  .btn-hamburguesa:hover{border-color:var(--wine);background:#fff;}
  .icono-rayitas{display:flex;flex-direction:column;justify-content:space-between;width:18px;height:14px;}
  .icono-rayitas span{display:block;height:2px;width:100%;background:var(--wine-dark);border-radius:2px;}
  
  /* Desplegable oculto por defecto */
  .menu-desplegable{display:none;position:absolute;top:calc(100% + 4px);left:0;background:var(--bg-card);border:1px solid var(--rule);border-radius:var(--radius);box-shadow:0 8px 24px rgba(0,0,0,0.15);width:280px;z-index:20;overflow:hidden;padding:6px 0;}
  .menu-desplegable.activo{display:block;}
  .menu-item{display:block;padding:10px 16px;color:var(--ink);text-decoration:none;font-size:0.9rem;font-weight:500;transition:background 0.1s;}
  .menu-item:hover{background:rgba(217,212,200,0.3);color:var(--wine);}
  .menu-item.actual{font-weight:700;color:var(--wine);background:rgba(217,212,200,0.5);border-left:3px solid var(--gold);}

  .stats-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(130px,1fr));gap:10px;margin-bottom:20px;}
  .stat-card{background:var(--bg-card);border:1px solid var(--rule);border-radius:var(--radius);padding:12px;border-left:4px solid var(--gold);}
  .stat-card .titulo-stat{font-size:0.62rem;text-transform:uppercase;letter-spacing:0.06em;color:var(--ink-soft);font-weight:700;margin-bottom:4px;}
  .stat-card .valor-stat{font-family:'IBM Plex Mono',monospace;font-size:1.3rem;font-weight:600;color:var(--wine-dark);}
  .toolbar{display:flex;gap:12px;margin-bottom:20px;}
  input[type="text"],select,input[type="date"],input[type="tel"],input[type="number"]{font-family:'Karla',sans-serif;font-size:0.92rem;border:1px solid var(--rule);border-radius:6px;padding:10px 12px;background:var(--bg-card);color:var(--ink);width:100%;}
  input:focus,select:focus{outline:2px solid var(--gold);outline-offset:1px;}
  #buscar{flex:1;min-width:100%;}
  .btn{font-family:'Karla',sans-serif;font-weight:700;font-size:0.85rem;border:none;border-radius:6px;padding:10px 16px;cursor:pointer;}
  .btn-primary{background:var(--wine);color:#fff;}
  .btn-primary:hover{background:var(--wine-dark);}
  .btn-ghost{background:transparent;color:var(--ink-soft);border:1px solid var(--rule);text-decoration:none;display:inline-block;}
  .btn-ghost:hover{border-color:var(--wine);color:var(--wine);}
  .bloque-seccion{margin-bottom:28px;}
  .bloque-titulo{font-family:'Fraunces',serif;font-size:1.15rem;font-weight:600;color:var(--wine-dark);margin-bottom:10px;display:flex;align-items:center;gap:8px;border-left:3px solid var(--gold);padding-left:8px;}
  .tabla-container{background:var(--bg-card);border:1px solid var(--rule);border-radius:var(--radius);overflow-x:auto;margin-bottom:10px;}
  table{width:100%;border-collapse:collapse;text-align:left;font-size:0.9rem;}
  th,td{padding:12px 16px;border-bottom:1px solid var(--rule);}
  th{font-family:'Fraunces',serif;font-weight:600;color:var(--wine-dark);background:rgba(217,212,200,0.2);font-size:0.85rem;text-transform:uppercase;letter-spacing:0.05em;}
  tr:last-child td{border-bottom:none;}
  .vacio{text-align:center;padding:24px 16px;color:var(--ink-soft);font-family:'Fraunces',serif;font-style:italic;font-size:0.95rem;}
  .overlay{position:fixed;inset:0;background:rgba(35,43,58,0.45);display:flex;align-items:center;justify-content:center;padding:16px;z-index:10;}
  .modal{background:var(--bg-card);border-radius:var(--radius);padding:20px;max-width:640px;width:100%;box-shadow:0 12px 40px rgba(0,0,0,0.25);max-height:90vh;overflow-y:auto;}
  .modal h2{font-family:'Fraunces',serif;color:var(--wine-dark);margin-top:0;font-size:1.3rem;}
  .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
  .form-grid .full{grid-column:1/-1;}
  .form-grid label{display:flex;flex-direction:column;font-size:0.75rem;font-weight:700;color:var(--ink-soft);gap:5px;text-transform:uppercase;}
  .modal-actions{display:flex;justify-content:flex-end;gap:10px;margin-top:20px;}
  .fab{position:fixed;bottom:20px;right:20px;background:var(--wine);color:#fff;border:none;border-radius:50%;width:50px;height:50px;font-size:1.5rem;cursor:pointer;box-shadow:0 6px 18px rgba(0,0,0,0.3);z-index:5;}
  .fab:hover{background:var(--wine-dark);}
  .loading{text-align:center;padding:40px;color:var(--ink-soft);font-style:italic;}
  .aviso{font-size:0.75rem;color:var(--ink-soft);text-align:center;margin-top:24px;}
</style>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@500;600;700&family=Karla:wght@400;700&family=IBM+Plex+Mono:wght@500&display=swap" rel="stylesheet">
</head>
<body>
<div class="app">
  <div class="ministerio-container">
    <img src="Logo.png" alt="Logo Iglesia" class="login-logo">
    <div class="ministerio-banner">Ministerio Internacional Movimiento de Gloria</div>
  </div>
  <header class="top">
    <div class="title-block">
      <h1>Equipo Ministerial</h1>
      <div class="subtitle">Gestión y control de líderes, pastores y oficiales</div>
    </div>
    <div class="header-actions">
      <div>
        <span class="stat-n" id="statTotal">0</span>
        <span class="stat-l">Total</span>
      </div>
      <a href="index.php" class="btn btn-ghost" style="font-size:0.78rem; padding:6px 12px;">Inicio</a>
      <a href="reportes.php" class="btn btn-ghost" style="font-size:0.78rem; padding:6px 12px;">Reportes</a>
      <a href="logout.php" class="btn btn-ghost" style="font-size:0.78rem; padding:6px 12px; color:var(--wine); border-color:var(--wine);">Cerrar Sesión</a>
    </div>
  </header>
  
  <!-- Menú Hamburguesa (3 rayitas) -->
  <div class="menu-hamburguesa-container">
    <button type="button" class="btn-hamburguesa" id="btnMenuToggle">
      <div class="icono-rayitas">
        <span></span>
        <span></span>
        <span></span>
      </div>
      <span>Menú</span>
    </button>
    <div class="menu-desplegable" id="menuDesplegable">
      <a href="miembros.php" class="menu-item">Miembros</a>
      <a href="jovenes_contra_cultura.php" class="menu-item">Jóvenes Contra Cultura</a>
      <a href="jovenes_invitados.php" class="menu-item">Jóvenes Invitados</a>
      <a href="equipo_ministerial.php" class="menu-item actual">Equipo Ministerial</a>
      <a href="escuela_ministerial.php" class="menu-item">Escuela Ministerial</a>
      <a href="visitantes.php" class="menu-item">Visitantes</a>
      <a href="casas_de_paz.php" class="menu-item">Casas de Paz</a>
    </div>
  </div>

  <div class="stats-row">
    <div class="stat-card">
      <div class="titulo-stat">Miembros</div>
      <div class="valor-stat" id="countMiembros">0</div>
    </div>
    <div class="stat-card">
      <div class="titulo-stat">Líderes</div>
      <div class="valor-stat" id="countLideres">0</div>
    </div>
    <div class="stat-card">
      <div class="titulo-stat">Mentores</div>
      <div class="valor-stat" id="countMentores">0</div>
    </div>
    <div class="stat-card">
      <div class="titulo-stat">Ancianos</div>
      <div class="valor-stat" id="countAncianos">0</div>
    </div>
    <div class="stat-card">
      <div class="titulo-stat">Evangelistas</div>
      <div class="valor-stat" id="countEvangelistas">0</div>
    </div>
    <div class="stat-card">
      <div class="titulo-stat">Profetas</div>
      <div class="valor-stat" id="countProfetas">0</div>
    </div>
    <div class="stat-card">
      <div class="titulo-stat">Pastores</div>
      <div class="valor-stat" id="countPastores">0</div>
    </div>
  </div>

  <div id="loading" class="loading">Conectando con Firebase…</div>
  <div id="contenido" style="display:none;">
    <div class="toolbar"><input type="text" id="buscar" placeholder="Buscar integrante por nombre, teléfono o residencia..."></div>
    
    <!-- Bloque Miembros -->
    <div class="bloque-seccion">
      <div class="bloque-titulo">Miembros</div>
      <div class="tabla-container">
        <table>
          <thead><tr><th style="width:50px;">N°</th><th>Nombre Completo</th><th>Edad</th><th>Teléfono</th><th>Residencia</th><th>Fecha de Ingreso</th></tr></thead>
          <tbody id="tablaMiembros"></tbody>
        </table>
      </div>
    </div>

    <!-- Bloque Líderes -->
    <div class="bloque-seccion">
      <div class="bloque-titulo">Líderes</div>
      <div class="tabla-container">
        <table>
          <thead><tr><th style="width:50px;">N°</th><th>Nombre Completo</th><th>Edad</th><th>Teléfono</th><th>Residencia</th><th>Fecha de Ingreso</th></tr></thead>
          <tbody id="tablaLideres"></tbody>
        </table>
      </div>
    </div>

    <!-- Bloque Mentores -->
    <div class="bloque-seccion">
      <div class="bloque-titulo">Mentores</div>
      <div class="tabla-container">
        <table>
          <thead><tr><th style="width:50px;">N°</th><th>Nombre Completo</th><th>Edad</th><th>Teléfono</th><th>Residencia</th><th>Fecha de Ingreso</th></tr></thead>
          <tbody id="tablaMentores"></tbody>
        </table>
      </div>
    </div>

    <!-- Bloque Ancianos -->
    <div class="bloque-seccion">
      <div class="bloque-titulo">Ancianos</div>
      <div class="tabla-container">
        <table>
          <thead><tr><th style="width:50px;">N°</th><th>Nombre Completo</th><th>Edad</th><th>Teléfono</th><th>Residencia</th><th>Fecha de Ingreso</th></tr></thead>
          <tbody id="tablaAncianos"></tbody>
        </table>
      </div>
    </div>

    <!-- Bloque Evangelistas -->
    <div class="bloque-seccion">
      <div class="bloque-titulo">Evangelistas</div>
      <div class="tabla-container">
        <table>
          <thead><tr><th style="width:50px;">N°</th><th>Nombre Completo</th><th>Edad</th><th>Teléfono</th><th>Residencia</th><th>Fecha de Ingreso</th></tr></thead>
          <tbody id="tablaEvangelistas"></tbody>
        </table>
      </div>
    </div>

    <!-- Bloque Profetas -->
    <div class="bloque-seccion">
      <div class="bloque-titulo">Profetas</div>
      <div class="tabla-container">
        <table>
          <thead><tr><th style="width:50px;">N°</th><th>Nombre Completo</th><th>Edad</th><th>Teléfono</th><th>Residencia</th><th>Fecha de Ingreso</th></tr></thead>
          <tbody id="tablaProfetas"></tbody>
        </table>
      </div>
    </div>

    <!-- Bloque Pastores -->
    <div class="bloque-seccion">
      <div class="bloque-titulo">Pastores</div>
      <div class="tabla-container">
        <table>
          <thead><tr><th style="width:50px;">N°</th><th>Nombre Completo</th><th>Edad</th><th>Teléfono</th><th>Residencia</th><th>Fecha de Ingreso</th></tr></thead>
          <tbody id="tablaPastores"></tbody>
        </table>
      </div>
    </div>

  </div>
  
  <div class="aviso">Los datos se sincronizan directamente con Firebase Firestore.</div>
  <button class="fab" id="btnNuevo" title="Registrar integrante">+</button>
</div>

<!-- Modal -->
<div class="overlay" id="overlay" style="display:none;">
  <div class="modal">
    <h2 id="modalTitulo">Registrar Integrante</h2>
    <form id="form">
      <div class="form-grid">
        <label class="full">Nombre Completo<input type="text" id="f_nombre" required></label>
        <label>Edad<input type="number" id="f_edad" required></label>
        <label>Teléfono<input type="tel" id="f_telefono" required></label>
        <label class="full">Lugar de Residencia<input type="text" id="f_residencia" required></label>
        <label class="full">Cargo / Rol
          <select id="f_nivel" required>
            <option value="">Seleccione el cargo...</option>
            <option value="Miembros">Miembros</option>
            <option value="Líderes">Líderes</option>
            <option value="Mentores">Mentores</option>
            <option value="Ancianos">Ancianos</option>
            <option value="Evangelistas">Evangelistas</option>
            <option value="Profetas">Profetas</option>
            <option value="Pastores">Pastores</option>
          </select>
        </label>
        <label class="full">Fecha de Ingreso<input type="date" id="f_fecha" required></label>
      </div>
      <div class="modal-actions">
        <button type="button" class="btn btn-ghost" id="btnCancelar">Cancelar</button>
        <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar Integrante</button>
      </div>
    </form>
  </div>
</div>

<script type="module">
  import { initializeApp } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-app.js";
  import { getFirestore, collection, getDocs, addDoc } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-firestore.js";
  
  const firebaseConfig = { apiKey: "AIzaSyD-lfkA-khlPes6zcjGmfAACWK9SK6Uhxc", authDomain: "mdgweb-b7ab7.firebaseapp.com", projectId: "mdgweb-b7ab7", storageBucket: "mdgweb-b7ab7.appspot.com", messagingSenderId: "571662431119", appId: "1:571662431119:web:bf0d2b4ca2164d70c9" };
  const db = getFirestore(initializeApp(firebaseConfig));
  const COLECCION = 'equipo_ministerial';
  
  let registros = [];

  // Script para el menú desplegable de las 3 rayitas
  const btnMenuToggle = document.getElementById('btnMenuToggle');
  const menuDesplegable = document.getElementById('menuDesplegable');

  btnMenuToggle.addEventListener('click', (e) => {
    e.stopPropagation();
    menuDesplegable.classList.toggle('activo');
  });

  document.addEventListener('click', () => {
    menuDesplegable.classList.remove('activo');
  });

  async function cargarDatos(){
    try{
      const snap = await getDocs(collection(db, COLECCION));
      registros = []; 
      snap.forEach(d => registros.push({ id: d.id, ...d.data() }));
    }catch(e){console.error(e);}
    document.getElementById('loading').style.display = 'none';
    document.getElementById('contenido').style.display = 'block';
    render();
  }

  function render(){
    document.getElementById('statTotal').textContent = registros.length;
    
    // Contadores por cargo exacto
    const countM = registros.filter(r => (r.nivel || '').trim().toLowerCase() === 'miembros').length;
    const countL = registros.filter(r => (r.nivel || '').trim().toLowerCase() === 'líderes').length;
    const countMe = registros.filter(r => (r.nivel || '').trim().toLowerCase() === 'mentores').length;
    const countA = registros.filter(r => (r.nivel || '').trim().toLowerCase() === 'ancianos').length;
    const countE = registros.filter(r => (r.nivel || '').trim().toLowerCase() === 'evangelistas').length;
    const countPr = registros.filter(r => (r.nivel || '').trim().toLowerCase() === 'profetas').length;
    const countP = registros.filter(r => (r.nivel || '').trim().toLowerCase() === 'pastores').length;
    
    document.getElementById('countMiembros').textContent = countM;
    document.getElementById('countLideres').textContent = countL;
    document.getElementById('countMentores').textContent = countMe;
    document.getElementById('countAncianos').textContent = countA;
    document.getElementById('countEvangelistas').textContent = countE;
    document.getElementById('countProfetas').textContent = countPr;
    document.getElementById('countPastores').textContent = countP;

    const q = document.getElementById('buscar').value.trim().toLowerCase();
    const items = registros.filter(r => !q || Object.values(r).some(v => String(v || '').toLowerCase().includes(q)));
    
    // Filtrar y renderizar por cada cargo con su respectiva numeración 1, 2, 3...
    renderTabla('tablaMiembros', items.filter(r => (r.nivel || '').trim().toLowerCase() === 'miembros'));
    renderTabla('tablaLideres', items.filter(r => (r.nivel || '').trim().toLowerCase() === 'líderes'));
    renderTabla('tablaMentores', items.filter(r => (r.nivel || '').trim().toLowerCase() === 'mentores'));
    renderTabla('tablaAncianos', items.filter(r => (r.nivel || '').trim().toLowerCase() === 'ancianos'));
    renderTabla('tablaEvangelistas', items.filter(r => (r.nivel || '').trim().toLowerCase() === 'evangelistas'));
    renderTabla('tablaProfetas', items.filter(r => (r.nivel || '').trim().toLowerCase() === 'profetas'));
    renderTabla('tablaPastores', items.filter(r => (r.nivel || '').trim().toLowerCase() === 'pastores'));
  }

  function renderTabla(elementId, items){
    const cuerpo = document.getElementById(elementId);
    if(items.length === 0){ 
      cuerpo.innerHTML = `<tr><td colspan="6" class="vacio">No hay registros en este cargo.</td></tr>`; 
      return; 
    }
    
    cuerpo.innerHTML = items.map((r, index) => {
      return `
        <tr>
          <td><strong style="color:var(--ink-soft);">${index + 1}</strong></td>
          <td><strong>${escapeHtml(r.nombre || '')}</strong></td>
          <td>${escapeHtml(String(r.edad || ''))}</td>
          <td>${escapeHtml(r.telefono || '')}</td>
          <td>${escapeHtml(r.residencia || '')}</td>
          <td>${escapeHtml(r.fecha || '')}</td>
        </tr>`;
    }).join('');
  }

  function escapeHtml(str){ const d = document.createElement('div'); d.textContent = str; return d.innerHTML; }
  function abrirModal(){ document.getElementById('overlay').style.display = 'flex'; }
  function cerrarModal(){ document.getElementById('overlay').style.display = 'none'; document.getElementById('form').reset(); }
  
  document.getElementById('btnNuevo').addEventListener('click', () => { document.getElementById('modalTitulo').textContent = 'Registrar Integrante'; document.getElementById('form').reset(); abrirModal(); });
  document.getElementById('btnCancelar').addEventListener('click', cerrarModal);
  document.getElementById('buscar').addEventListener('input', render);

  document.getElementById('form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const datos = { 
      nombre: document.getElementById('f_nombre').value.trim(), 
      edad: document.getElementById('f_edad').value.trim(), 
      telefono: document.getElementById('f_telefono').value.trim(), 
      residencia: document.getElementById('f_residencia').value.trim(), 
      nivel: document.getElementById('f_nivel').value.trim(), 
      fecha: document.getElementById('f_fecha').value.trim() 
    };
    
    if(!datos.nombre) return;

    const ref = await addDoc(collection(db, COLECCION), datos);
    registros.push({ id: ref.id, ...datos });
    cerrarModal(); 
    render();
  });

  cargarDatos();
</script>
</body>
</html>
