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
<title>Casas de Paz - Ministerio Internacional Movimiento de Gloria</title>
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

  .toolbar{display:flex;gap:12px;margin-bottom:20px;}
  input[type="text"],select,input[type="tel"],input[type="number"]{font-family:'Karla',sans-serif;font-size:0.92rem;border:1px solid var(--rule);border-radius:6px;padding:10px 14px;background:var(--bg-card);color:var(--ink);width:100%;}
  input:focus,select:focus{outline:2px solid var(--gold);outline-offset:1px;}
  #buscar{flex:1;min-width:100%;}

  .btn{font-family:'Karla',sans-serif;font-weight:700;font-size:0.82rem;border:none;border-radius:6px;padding:8px 14px;cursor:pointer;}
  .btn-ghost{background:transparent;color:var(--ink-soft);border:1px solid var(--rule);text-decoration:none;display:inline-block;}
  .btn-ghost:hover{border-color:var(--wine);color:var(--wine);}
  .btn-danger{background:transparent;color:var(--wine);text-decoration:underline;padding:4px 6px;}
  .btn-primary{background:var(--wine);color:#fff;}
  .btn-primary:hover{background:var(--wine-dark);}

  /* Estilo de Tarjetas Casas de Paz */
  .card-casa{background:var(--bg-card);border:1px solid var(--rule);border-radius:var(--radius);padding:20px;margin-bottom:16px;box-shadow:0 2px 6px rgba(0,0,0,0.02);position:relative;}
  .card-casa-header{display:flex;justify-content:space-between;align-items:flex-start;gap:16px;margin-bottom:6px;}
  .card-casa-title{font-family:'Fraunces',serif;font-size:1.25rem;font-weight:600;color:var(--wine-dark);margin:0;}
  .card-casa-meta{font-size:0.85rem;color:var(--ink-soft);display:flex;gap:16px;flex-wrap:wrap;align-items:center;margin-bottom:12px;}
  .card-actions{display:flex;gap:8px;align-items:center;}
  
  .badge-codigo{display:inline-block;font-size:0.68rem;text-transform:uppercase;letter-spacing:0.04em;color:var(--gold);border:1px solid var(--gold);border-radius:4px;padding:2px 8px;font-family:'IBM Plex Mono',monospace;font-weight:600;}
  
  .integrantes-section{border-top:1px dashed var(--rule);padding-top:12px;margin-top:10px;}
  .integrantes-title{font-size:0.78rem;text-transform:uppercase;letter-spacing:0.05em;color:var(--ink-soft);font-weight:700;margin-bottom:8px;}
  .integrantes-list{display:flex;gap:8px;flex-wrap:wrap;}
  .integrante-chip{background:rgba(217,212,200,0.3);border:1px solid var(--rule);border-radius:20px;padding:4px 12px;font-size:0.82rem;display:inline-flex;align-items:center;gap:6px;color:var(--ink);}

  .vacio{text-align:center;padding:40px 16px;color:var(--ink-soft);font-family:'Fraunces',serif;font-style:italic;font-size:0.95rem;}

  /* Modal y sección de Integrantes Dinámicos */
  .overlay{position:fixed;inset:0;background:rgba(35,43,58,0.45);display:flex;align-items:center;justify-content:center;padding:16px;z-index:10;}
  .modal{background:var(--bg-card);border-radius:var(--radius);padding:20px;max-width:640px;width:100%;box-shadow:0 12px 40px rgba(0,0,0,0.25);max-height:90vh;overflow-y:auto;}
  .modal h2{font-family:'Fraunces',serif;color:var(--wine-dark);margin-top:0;font-size:1.3rem;}
  .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
  .form-grid .full{grid-column:1/-1;}
  .form-grid label{display:flex;flex-direction:column;font-size:0.75rem;font-weight:700;color:var(--ink-soft);gap:5px;text-transform:uppercase;}
  
  .integrantes-builder{background:rgba(217,212,200,0.15);border:1px solid var(--rule);border-radius:6px;padding:12px;margin-top:4px;}
  .integrante-input-row{display:flex;gap:8px;margin-bottom:8px;}
  .integrantes-added-list{display:flex;flex-direction:column;gap:6px;max-height:160px;overflow-y:auto;margin-top:8px;}
  .integrante-row-item{display:flex;align-items:center;justify-content:between;background:var(--bg-card);border:1px solid var(--rule);padding:6px 10px;border-radius:6px;font-size:0.85rem;}
  .integrante-row-item span{flex:1;}

  .modal-actions{display:flex;justify-content:flex-end;gap:10px;margin-top:20px;}
  
  .fab{position:fixed;bottom:20px;right:20px;background:var(--wine);color:#fff;border:none;border-radius:50%;width:50px;height:50px;font-size:1.5rem;cursor:pointer;box-shadow:0 6px 18px rgba(0,0,0,0.3);z-index:5;}
  .fab:hover{background:var(--wine-dark);}
  .loading{text-align:center;padding:40px;color:var(--ink-soft);font-style:italic;}
  .aviso{font-size:0.75rem;color:var(--ink-soft);text-align:center;margin-top:30px;}
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
      <h1>Casas de Paz</h1>
      <div class="subtitle">Gestión y control de Casas de Paz</div>
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
      <a href="escuela_ministerial.php" class="menu-item">Escuela Ministerial</a>
      <a href="visitantes.php" class="menu-item">Visitantes</a>
      <a href="casas_de_paz.php" class="menu-item actual">Casas de Paz</a>
    </div>
  </div>

  <div id="loading" class="loading">Conectando con Firebase…</div>
  <div id="contenido" style="display:none;">
    <div class="toolbar"><input type="text" id="buscar" placeholder="Buscar Casa de Paz por anfitrión, líder, dirección..."></div>
    <div id="listaCasas"></div>
  </div>
  
  <div class="aviso">Los datos se sincronizan directamente con Firebase Firestore.</div>
  <button class="fab" id="btnNuevo" title="Registrar Casa de Paz">+</button>
</div>

<!-- Modal Registro / Edición -->
<div class="overlay" id="overlay" style="display:none;">
  <div class="modal">
    <h2 id="modalTitulo">Registrar Casa de Paz</h2>
    <form id="form">
      <div class="form-grid">
        <label class="full">Líder<input type="text" id="f_anfitriones" required></label>
        <label>Ubicación<input type="text" id="f_direccion" required></label>
        <label>Día y Hora<input type="text" id="f_dia_hora" placeholder="Ej: Sábados, 5:00 pm" required></label>
        <label>Barrio / Colonia <input type="text" id="f_codigo" placeholder="Ej: C. STANDARD" required></label>
        <label>Número de Integrantes<input type="number" id="f_num_integrantes" required></label>
        
        <div class="full">
          <label style="margin-bottom:4px;">Gestión de Integrantes</label>
          <div class="integrantes-builder">
            <div class="integrante-input-row">
              <input type="text" id="nuevo_integrante_nombre" placeholder="Nombre del integrante..." style="flex:2;">
              <input type="text" id="nuevo_integrante_tel" placeholder="Teléfono..." style="flex:1;">
              <button type="button" class="btn btn-primary" id="btnAddIntegrante">+ Agregar</button>
            </div>
            <div class="integrantes-added-list" id="listaIntegrantesForm">
              <!-- Aquí se listan dinámicamente con botones de eliminar -->
            </div>
          </div>
        </div>
      </div>

      <div class="modal-actions">
        <button type="button" class="btn btn-ghost" id="btnCancelar">Cancelar</button>
        <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar</button>
      </div>
    </form>
  </div>
</div>

<script type="module">
  import { initializeApp } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-app.js";
  import { getFirestore, collection, getDocs, addDoc, doc, updateDoc, deleteDoc } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-firestore.js";
  
  const firebaseConfig = { apiKey: "AIzaSyD-lfkA-khlPes6zcjGmfAACWK9SK6Uhxc", authDomain: "mdgweb-b7ab7.firebaseapp.com", projectId: "mdgweb-b7ab7", storageBucket: "mdgweb-b7ab7.appspot.com", messagingSenderId: "571662431119", appId: "1:571662431119:web:bf0d2b4ca2164d70c9" };
  const db = getFirestore(initializeApp(firebaseConfig));
  const COLECCION = 'casas_de_paz';
  
  let registros = [], editandoId = null;
  let integrantesTemp = [];

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
    
    const q = document.getElementById('buscar').value.trim().toLowerCase();
    const items = registros.filter(r => !q || Object.values(r).some(v => String(v || '').toLowerCase().includes(q)));
    
    const contenedor = document.getElementById('listaCasas');
    if(items.length === 0){ 
      contenedor.innerHTML = `<div class="vacio">No hay registros de Casas de Paz.</div>`; 
      return; 
    }
    
    contenedor.innerHTML = items.map(r => {
      let listaIntHTML = '';
      let arrInt = r.integrantes || [];
      if(arrInt.length === 0 && r.integrantesTexto){
        arrInt = r.integrantesTexto.split(',').map(s => {
          const parts = s.trim().split('(');
          return { nombre: parts[0].trim(), telefono: parts[1] ? parts[1].replace(')', '').trim() : '' };
        }).filter(x => x.nombre);
      }

      if(arrInt.length > 0){
        const chips = arrInt.map(i => `<span class="integrante-chip">👤 ${escapeHtml(i.nombre)} ${i.telefono ? '('+escapeHtml(i.telefono)+')' : ''}</span>`).join('');
        listaIntHTML = `
          <div class="integrantes-section">
            <div class="integrantes-title">Integrantes registrados (${arrInt.length}):</div>
            <div class="integrantes-list">${chips}</div>
          </div>`;
      }

      return `
        <div class="card-casa">
          <div class="card-casa-header">
            <div>
              <h3 class="card-casa-title">${escapeHtml(r.anfitriones || '')}</h3>
              <div class="card-casa-meta">
                <span>${escapeHtml(r.direccion || '')}</span>
                <span>${escapeHtml(r.dia_hora || '')}</span>
                <span>${escapeHtml(String(r.num_integrantes || arrInt.length))}</span>
              </div>
            </div>
            <div class="card-actions">
              <button class="btn btn-ghost" data-editar="${r.id}">Editar</button>
              <button class="btn btn-danger" data-borrar="${r.id}">Eliminar</button>
            </div>
          </div>
          <div><span class="badge-codigo">${escapeHtml(r.codigo || 'C. ESTÁNDAR')}</span></div>
          ${listaIntHTML}
        </div>`;
    }).join('');

    contenedor.querySelectorAll('[data-editar]').forEach(b => b.addEventListener('click', () => abrirEdicion(b.dataset.editar)));
    contenedor.querySelectorAll('[data-borrar]').forEach(b => b.addEventListener('click', () => borrar(b.dataset.borrar)));
  }

  function renderIntegrantesForm(){
    const contenedor = document.getElementById('listaIntegrantesForm');
    if(integrantesTemp.length === 0){
      contenedor.innerHTML = `<div style="font-size:0.8rem; color:var(--ink-soft); font-style:italic; padding:4px;">No hay integrantes agregados todavía.</div>`;
      return;
    }
    contenedor.innerHTML = integrantesTemp.map((item, idx) => `
      <div class="integrante-row-item">
        <span>👤 <strong>${escapeHtml(item.nombre)}</strong> ${item.telefono ? '('+escapeHtml(item.telefono)+')' : ''}</span>
        <button type="button" class="btn btn-danger" data-quitar-int="${idx}" style="font-size:0.75rem; padding:2px 6px;">Quitar</button>
      </div>
    `).join('');

    contenedor.querySelectorAll('[data-quitar-int]').forEach(btn => {
      btn.addEventListener('click', () => {
        const index = parseInt(btn.dataset.quitarInt);
        integrantesTemp.splice(index, 1);
        renderIntegrantesForm();
      });
    });
  }

  document.getElementById('btnAddIntegrante').addEventListener('click', () => {
    const nombreInput = document.getElementById('nuevo_integrante_nombre');
    const telInput = document.getElementById('nuevo_integrante_tel');
    const nombre = nombreInput.value.trim();
    const telefono = telInput.value.trim();

    if(!nombre) return alert('Ingrese el nombre del integrante');

    integrantesTemp.push({ nombre, telefono });
    nombreInput.value = '';
    telInput.value = '';
    renderIntegrantesForm();
  });

  function escapeHtml(str){ const d = document.createElement('div'); d.textContent = str; return d.innerHTML; }
  function abrirModal(){ document.getElementById('overlay').style.display = 'flex'; }
  function cerrarModal(){ document.getElementById('overlay').style.display = 'none'; editandoId = null; integrantesTemp = []; document.getElementById('form').reset(); }
  
  document.getElementById('btnNuevo').addEventListener('click', () => { 
    editandoId = null; 
    document.getElementById('modalTitulo').textContent = 'Registrar Casa de Paz'; 
    document.getElementById('form').reset(); 
    integrantesTemp = [];
    renderIntegrantesForm();
    abrirModal(); 
  });
  
  document.getElementById('btnCancelar').addEventListener('click', cerrarModal);
  document.getElementById('buscar').addEventListener('input', render);

  function abrirEdicion(id){
    const r = registros.find(x => x.id === id); if(!r) return;
    editandoId = id;
    document.getElementById('modalTitulo').textContent = 'Editar Casa de Paz';
    document.getElementById('f_anfitriones').value = r.anfitriones || '';
    document.getElementById('f_direccion').value = r.direccion || '';
    document.getElementById('f_dia_hora').value = r.dia_hora || '';
    document.getElementById('f_codigo').value = r.codigo || '';
    document.getElementById('f_num_integrantes').value = r.num_integrantes || '';
    
    integrantesTemp = r.integrantes ? [...r.integrantes] : [];
    if(integrantesTemp.length === 0 && r.integrantesTexto){
      integrantesTemp = r.integrantesTexto.split(',').map(s => {
        const parts = s.trim().split('(');
        return { nombre: parts[0].trim(), telefono: parts[1] ? parts[1].replace(')', '').trim() : '' };
      }).filter(x => x.nombre);
    }
    renderIntegrantesForm();
    abrirModal();
  }

  async function borrar(id){
    if(!confirm('¿Eliminar esta Casa de Paz?')) return;
    await deleteDoc(doc(db, COLECCION, id));
    registros = registros.filter(r => r.id !== id); 
    render();
  }

  document.getElementById('form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const datos = { 
      anfitriones: document.getElementById('f_anfitriones').value.trim(), 
      direccion: document.getElementById('f_direccion').value.trim(), 
      dia_hora: document.getElementById('f_dia_hora').value.trim(), 
      codigo: document.getElementById('f_codigo').value.trim(), 
      num_integrantes: document.getElementById('f_num_integrantes').value.trim(), 
      integrantes: integrantesTemp 
    };
    
    if(!datos.anfitriones) return;

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
  });

  cargarDatos();
</script>
</body>
</html>