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
<title>Escuela Ministerial - Ministerio Internacional Movimiento de Gloria</title>
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

  .stats-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:10px;margin-bottom:20px;}
  .stat-card{background:var(--bg-card);border:1px solid var(--rule);border-radius:var(--radius);padding:12px;border-left:4px solid var(--gold);}
  .stat-card .titulo-stat{font-size:0.65rem;text-transform:uppercase;letter-spacing:0.06em;color:var(--ink-soft);font-weight:700;margin-bottom:4px;}
  .stat-card .valor-stat{font-family:'IBM Plex Mono',monospace;font-size:1.3rem;font-weight:600;color:var(--wine-dark);}
  .toolbar{display:flex;gap:12px;margin-bottom:20px;}
  input[type="text"],select,input[type="date"],input[type="tel"],input[type="number"]{font-family:'Karla',sans-serif;font-size:0.92rem;border:1px solid var(--rule);border-radius:6px;padding:10px 12px;background:var(--bg-card);color:var(--ink);width:100%;}
  input:focus,select:focus{outline:2px solid var(--gold);outline-offset:1px;}
  #buscar{flex:1;min-width:100%;}
  .btn{font-family:'Karla',sans-serif;font-weight:700;font-size:0.85rem;border:none;border-radius:6px;padding:8px 14px;cursor:pointer;}
  .btn-primary{background:var(--wine);color:#fff;}
  .btn-primary:hover{background:var(--wine-dark);}
  .btn-ghost{background:transparent;color:var(--ink-soft);border:1px solid var(--rule);text-decoration:none;display:inline-block;}
  .btn-ghost:hover{border-color:var(--wine);color:var(--wine);}
  .btn-danger{background:transparent;color:var(--wine);text-decoration:underline;padding:4px 6px;}
  .bloque-seccion{margin-bottom:28px;}
  .bloque-titulo{font-family:'Fraunces',serif;font-size:1.15rem;font-weight:600;color:var(--wine-dark);margin-bottom:10px;display:flex;align-items:center;gap:8px;border-left:3px solid var(--gold);padding-left:8px;}
  .tabla-container{background:var(--bg-card);border:1px solid var(--rule);border-radius:var(--radius);overflow-x:auto;margin-bottom:10px;}
  table{width:100%;border-collapse:collapse;text-align:left;font-size:0.9rem;}
  th,td{padding:12px 16px;border-bottom:1px solid var(--rule);}
  th{font-family:'Fraunces',serif;font-weight:600;color:var(--wine-dark);background:rgba(217,212,200,0.2);font-size:0.85rem;text-transform:uppercase;letter-spacing:0.05em;}
  tr:last-child td{border-bottom:none;}
  .badge{display:inline-block;font-size:0.68rem;text-transform:uppercase;letter-spacing:0.04em;color:var(--gold);border:1px solid var(--gold);border-radius:20px;padding:2px 9px;}
  .badge-graduado{color:#2e7d32;border-color:#2e7d32;}
  .badge-inactivo{color:#c62828;border-color:#c62828;}
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
      <h1>Escuela Ministerial</h1>
      <div class="subtitle">Gestión y control de estudiantes por bloques de formación</div>
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
      <a href="equipo_ministerial.php" class="menu-item">Equipo Ministerial</a>
      <a href="escuela_ministerial.php" class="menu-item actual">Escuela Ministerial</a>
      <a href="visitantes.php" class="menu-item">Visitantes</a>
      <a href="casas_de_paz.php" class="menu-item">Casas de Paz</a>
    </div>
  </div>

  <div class="stats-row">
    <div class="stat-card">
      <div class="titulo-stat">Fundamentos</div>
      <div class="valor-stat" id="countFundamentos">0</div>
    </div>
    <div class="stat-card">
      <div class="titulo-stat">LBS</div>
      <div class="valor-stat" id="countLbs">0</div>
    </div>
    <div class="stat-card">
      <div class="titulo-stat">La Visión</div>
      <div class="valor-stat" id="countVision">0</div>
    </div>
    <div class="stat-card">
      <div class="titulo-stat">Líderes Casa de Paz</div>
      <div class="valor-stat" id="countLideresCasa">0</div>
    </div>
    <div class="stat-card">
      <div class="titulo-stat">Poder del Servicio</div>
      <div class="valor-stat" id="countServicio">0</div>
    </div>
    <div class="stat-card">
      <div class="titulo-stat">Poder de la Honra</div>
      <div class="valor-stat" id="countHonra">0</div>
    </div>
    <div class="stat-card">
      <div class="titulo-stat">Profético</div>
      <div class="valor-stat" id="countProfetico">0</div>
    </div>
  </div>

  <div id="loading" class="loading">Conectando con Firebase…</div>
  <div id="contenido" style="display:none;">
    <div class="toolbar"><input type="text" id="buscar" placeholder="Buscar alumno por nombre, teléfono o residencia..."></div>
    
    <!-- Bloque Fundamentos -->
    <div class="bloque-seccion">
      <div class="bloque-titulo">Fundamentos</div>
      <div class="tabla-container">
        <table>
          <thead><tr><th style="width:50px;">N°</th><th>Nombre Completo</th><th>Edad</th><th>Teléfono</th><th>Residencia</th><th>Fecha de Inicio</th><th>Estado</th><th style="text-align:right;">Acciones</th></tr></thead>
          <tbody id="tablaFundamentos"></tbody>
        </table>
      </div>
    </div>

    <!-- Bloque LBS -->
    <div class="bloque-seccion">
      <div class="bloque-titulo">LBS</div>
      <div class="tabla-container">
        <table>
          <thead><tr><th style="width:50px;">N°</th><th>Nombre Completo</th><th>Edad</th><th>Teléfono</th><th>Residencia</th><th>Fecha de Inicio</th><th>Estado</th><th style="text-align:right;">Acciones</th></tr></thead>
          <tbody id="tablaLbs"></tbody>
        </table>
      </div>
    </div>

    <!-- Bloque La Visión -->
    <div class="bloque-seccion">
      <div class="bloque-titulo">La Visión</div>
      <div class="tabla-container">
        <table>
          <thead><tr><th style="width:50px;">N°</th><th>Nombre Completo</th><th>Edad</th><th>Teléfono</th><th>Residencia</th><th>Fecha de Inicio</th><th>Estado</th><th style="text-align:right;">Acciones</th></tr></thead>
          <tbody id="tablaVision"></tbody>
        </table>
      </div>
    </div>

    <!-- Bloque Líderes de Casa de Paz -->
    <div class="bloque-seccion">
      <div class="bloque-titulo">Líderes de Casa de Paz</div>
      <div class="tabla-container">
        <table>
          <thead><tr><th style="width:50px;">N°</th><th>Nombre Completo</th><th>Edad</th><th>Teléfono</th><th>Residencia</th><th>Fecha de Inicio</th><th>Estado</th><th style="text-align:right;">Acciones</th></tr></thead>
          <tbody id="tablaLideresCasa"></tbody>
        </table>
      </div>
    </div>

    <!-- Bloque El poder del servicio -->
    <div class="bloque-seccion">
      <div class="bloque-titulo">El poder del servicio</div>
      <div class="tabla-container">
        <table>
          <thead><tr><th style="width:50px;">N°</th><th>Nombre Completo</th><th>Edad</th><th>Teléfono</th><th>Residencia</th><th>Fecha de Inicio</th><th>Estado</th><th style="text-align:right;">Acciones</th></tr></thead>
          <tbody id="tablaServicio"></tbody>
        </table>
      </div>
    </div>

    <!-- Bloque El poder de la honra -->
    <div class="bloque-seccion">
      <div class="bloque-titulo">El poder de la honra</div>
      <div class="tabla-container">
        <table>
          <thead><tr><th style="width:50px;">N°</th><th>Nombre Completo</th><th>Edad</th><th>Teléfono</th><th>Residencia</th><th>Fecha de Inicio</th><th>Estado</th><th style="text-align:right;">Acciones</th></tr></thead>
          <tbody id="tablaHonra"></tbody>
        </table>
      </div>
    </div>

    <!-- Bloque Profético -->
    <div class="bloque-seccion">
      <div class="bloque-titulo">Profético</div>
      <div class="tabla-container">
        <table>
          <thead><tr><th style="width:50px;">N°</th><th>Nombre Completo</th><th>Edad</th><th>Teléfono</th><th>Residencia</th><th>Fecha de Inicio</th><th>Estado</th><th style="text-align:right;">Acciones</th></tr></thead>
          <tbody id="tablaProfetico"></tbody>
        </table>
      </div>
    </div>

  </div>
  
  <div class="aviso">Los datos se sincronizan directamente con Firebase Firestore.</div>
  <button class="fab" id="btnNuevo" title="Registrar alumno">+</button>
</div>

<!-- Modal -->
<div class="overlay" id="overlay" style="display:none;">
  <div class="modal">
    <h2 id="modalTitulo">Registrar Alumno</h2>
    <form id="form">
      <div class="form-grid">
        <label class="full">Nombre Completo<input type="text" id="f_nombre" required></label>
        <label>Edad<input type="number" id="f_edad" required></label>
        <label>Teléfono<input type="tel" id="f_telefono" required></label>
        <label class="full">Lugar de Residencia<input type="text" id="f_residencia" required></label>
        <label>Nivel / Módulo
          <select id="f_nivel" required>
            <option value="">Seleccione el nivel...</option>
            <option value="Fundamentos">Fundamentos</option>
            <option value="LBS">LBS</option>
            <option value="La Visión">La Visión</option>
            <option value="Líderes de Casa de Paz">Líderes de Casa de Paz</option>
            <option value="El poder del servicio">El poder del servicio</option>
            <option value="El poder de la honra">El poder de la honra</option>
            <option value="Profético">Profético</option>
          </select>
        </label>
        <label>Estado
          <select id="f_estado" required>
            <option value="Cursando">Cursando</option>
            <option value="Graduado">Graduado</option>
            <option value="Inactivo">Inactivo</option>
          </select>
        </label>
        <label class="full">Fecha de Inicio<input type="date" id="f_fecha" required></label>
      </div>
      <div class="modal-actions">
        <button type="button" class="btn btn-ghost" id="btnCancelar">Cancelar</button>
        <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar Alumno</button>
      </div>
    </form>
  </div>
</div>

<script type="module">
  import { initializeApp } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-app.js";
  import { getFirestore, collection, getDocs, addDoc, doc, updateDoc, deleteDoc } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-firestore.js";
  
  const firebaseConfig = { apiKey: "AIzaSyD-lfkA-khlPes6zcjGmfAACWK9SK6Uhxc", authDomain: "mdgweb-b7ab7.firebaseapp.com", projectId: "mdgweb-b7ab7", storageBucket: "mdgweb-b7ab7.appspot.com", messagingSenderId: "571662431119", appId: "1:571662431119:web:bf0d2b4ca2164d70c9" };
  const db = getFirestore(initializeApp(firebaseConfig));
  const COLECCION = 'escuela_ministerial';
  
  let registros = [], editandoId = null;

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
    
    // Contadores por nivel exacto
    const countF = registros.filter(r => (r.nivel || '').trim().toLowerCase() === 'fundamentos').length;
    const countLbs = registros.filter(r => (r.nivel || '').trim().toLowerCase() === 'lbs').length;
    const countV = registros.filter(r => (r.nivel || '').trim().toLowerCase() === 'la visión').length;
    const countLC = registros.filter(r => (r.nivel || '').trim().toLowerCase() === 'líderes de casa de paz').length;
    const countS = registros.filter(r => (r.nivel || '').trim().toLowerCase() === 'el poder del servicio').length;
    const countH = registros.filter(r => (r.nivel || '').trim().toLowerCase() === 'el poder de la honra').length;
    const countP = registros.filter(r => (r.nivel || '').trim().toLowerCase() === 'profético').length;
    
    document.getElementById('countFundamentos').textContent = countF;
    document.getElementById('countLbs').textContent = countLbs;
    document.getElementById('countVision').textContent = countV;
    document.getElementById('countLideresCasa').textContent = countLC;
    document.getElementById('countServicio').textContent = countS;
    document.getElementById('countHonra').textContent = countH;
    document.getElementById('countProfetico').textContent = countP;

    const q = document.getElementById('buscar').value.trim().toLowerCase();
    const items = registros.filter(r => !q || Object.values(r).some(v => String(v || '').toLowerCase().includes(q)));
    
    // Filtrar y renderizar por cada nivel con su respectiva numeración 1, 2, 3...
    renderTabla('tablaFundamentos', items.filter(r => (r.nivel || '').trim().toLowerCase() === 'fundamentos'));
    renderTabla('tablaLbs', items.filter(r => (r.nivel || '').trim().toLowerCase() === 'lbs'));
    renderTabla('tablaVision', items.filter(r => (r.nivel || '').trim().toLowerCase() === 'la visión'));
    renderTabla('tablaLideresCasa', items.filter(r => (r.nivel || '').trim().toLowerCase() === 'líderes de casa de paz'));
    renderTabla('tablaServicio', items.filter(r => (r.nivel || '').trim().toLowerCase() === 'el poder del servicio'));
    renderTabla('tablaHonra', items.filter(r => (r.nivel || '').trim().toLowerCase() === 'el poder de la honra'));
    renderTabla('tablaProfetico', items.filter(r => (r.nivel || '').trim().toLowerCase() === 'profético'));
  }

  function renderTabla(elementId, items){
    const cuerpo = document.getElementById(elementId);
    if(items.length === 0){ 
      cuerpo.innerHTML = `<tr><td colspan="8" class="vacio">No hay registros en este nivel.</td></tr>`; 
      return; 
    }
    
    cuerpo.innerHTML = items.map((r, index) => {
      let badgeClass = 'badge';
      if((r.estado || '').toLowerCase() === 'graduado') badgeClass += ' badge-graduado';
      if((r.estado || '').toLowerCase() === 'inactivo') badgeClass += ' badge-inactivo';

      return `
        <tr>
          <td><strong style="color:var(--ink-soft);">${index + 1}</strong></td>
          <td><strong>${escapeHtml(r.nombre || '')}</strong></td>
          <td>${escapeHtml(String(r.edad || ''))}</td>
          <td>${escapeHtml(r.telefono || '')}</td>
          <td>${escapeHtml(r.residencia || '')}</td>
          <td>${escapeHtml(r.fecha || '')}</td>
          <td><span class="${badgeClass}">${escapeHtml(r.estado || 'Cursando')}</span></td>
          <td style="text-align:right; white-space:nowrap;">
            <button class="btn btn-ghost" data-editar="${r.id}" style="font-size:0.75rem; padding:4px 8px;">Editar</button>
            <button class="btn btn-danger" data-borrar="${r.id}">Eliminar</button>
          </td>
        </tr>`;
    }).join('');

    cuerpo.querySelectorAll('[data-editar]').forEach(b => b.addEventListener('click', () => abrirEdicion(b.dataset.editar)));
    cuerpo.querySelectorAll('[data-borrar]').forEach(b => b.addEventListener('click', () => borrar(b.dataset.borrar)));
  }

  function escapeHtml(str){ const d = document.createElement('div'); d.textContent = str; return d.innerHTML; }
  function abrirModal(){ document.getElementById('overlay').style.display = 'flex'; }
  function cerrarModal(){ document.getElementById('overlay').style.display = 'none'; editandoId = null; document.getElementById('form').reset(); }
  
  document.getElementById('btnNuevo').addEventListener('click', () => { 
    editandoId = null; 
    document.getElementById('modalTitulo').textContent = 'Registrar Alumno'; 
    document.getElementById('form').reset(); 
    abrirModal(); 
  });

  document.getElementById('btnCancelar').addEventListener('click', cerrarModal);
  document.getElementById('buscar').addEventListener('input', render);

  function abrirEdicion(id){
    const r = registros.find(x => x.id === id); if(!r) return;
    editandoId = id;
    document.getElementById('modalTitulo').textContent = 'Editar Alumno';
    document.getElementById('f_nombre').value = r.nombre || '';
    document.getElementById('f_edad').value = r.edad || '';
    document.getElementById('f_telefono').value = r.telefono || '';
    document.getElementById('f_residencia').value = r.residencia || '';
    document.getElementById('f_nivel').value = r.nivel || '';
    document.getElementById('f_estado').value = r.estado || 'Cursando';
    document.getElementById('f_fecha').value = r.fecha || '';
    abrirModal();
  }

  async function borrar(id){
    if(!confirm('¿Eliminar este registro de alumno?')) return;
    try {
      await deleteDoc(doc(db, COLECCION, id));
      registros = registros.filter(r => r.id !== id); 
      render();
    } catch(e) {
      console.error(e);
      alert('Error al eliminar el registro.');
    }
  }

  document.getElementById('form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const datos = { 
      nombre: document.getElementById('f_nombre').value.trim(), 
      edad: document.getElementById('f_edad').value.trim(), 
      telefono: document.getElementById('f_telefono').value.trim(), 
      residencia: document.getElementById('f_residencia').value.trim(), 
      nivel: document.getElementById('f_nivel').value.trim(), 
      estado: document.getElementById('f_estado').value.trim(), 
      fecha: document.getElementById('f_fecha').value.trim() 
    };
    
    if(!datos.nombre) return;

    try {
      if(editandoId){
        await updateDoc(doc(db, COLECCION, editandoId), datos);
        const idx = registros.findIndex(r => r.id === editandoId); 
        if(idx > -1) registros[idx] = { id: editandoId, ...datos };
      }else{
        const ref = await addDoc(collection(db, COLECCION), datos);
        registros.push({ id: ref.id, ...datos });
      }
      cerrarModal(); 
      render();
    } catch(e) {
      console.error(e);
      alert('Error al guardar el alumno.');
    }
  });

  cargarDatos();
</script>
</body>
</html>