'use strict'; 

// ================== IMPORTS (ajustá rutas si cambia estructura) ==================
import {
  registrarUsuario,
  iniciarSesion,
  subirFoto as usr_subirFoto,
  subirComprobante as usr_subirComprobante,
  subirAntecedentes as usr_subirAntecedentes,
  getInteresado,
  getUsuario,
  getIdSesion as usr_getIdSesion,
} from '../../BackEnd/APIFetchs/APIUsuario.js';

import {
  aprobarEstado,
  rechazarEstado,
  getInteresados,
  rechazarInteresado,
  aprobarInteresado,
  asignarEntrevista,
  asignarPagoInicial,
  getAdmin,
  getIdSesion as bo_getIdSesion,
  contarInteresados,
  subirFoto as bo_subirFoto,
  asignarPagoMensual,
  asignarPagoPersonalizado,
  getPagosPendientes,
  aprobarPago,
  rechazarPago,
} from '../../BackEnd/APIFetchs/APIBackOffice.js';

// ====== APICooperativa (usamos sólo los que están OK con tu PHP actual) ======
import {
  // getComprobantes,  // <- NO lo usamos porque tu PHP no tiene 'accion=getComprobantes' y esa función usa 'id' no definido
  subirComprobante as coop_subirComprobante,
  subirHoras,
  getCooperativa,
  getPagos as coop_getPagos,
  getHorasTrabajadas,
  editarHoras,
  borrarHoras,
  // getIdSesion  // <- si lo tenés exportado, podrías importarlo y loguearlo; no es necesario para las pruebas
} from '../../BackEnd/APIFetchs/APICooperativa.js';

// ================== CONST: base para endpoints directos (no expuestos) ==================
const BO_BASE = '/Proyecto/BackEnd/APIBackOffice/ApiBackOffice.php';

// ================== Helper de red directo (tolerante a no-JSON) ==================
async function directJson(url, method, data) {
  const opts = { method: method || 'GET', credentials: 'include', headers: {} };
  if (data instanceof FormData) {
    opts.body = data;
  } else if (data !== undefined && data !== null) {
    opts.headers['Content-Type'] = 'application/json';
    opts.body = JSON.stringify(data);
  }
  const res = await fetch(url, opts);
  const text = await res.text(); // leer body una única vez
  try { return { ok: true, http: res.status, json: JSON.parse(text) }; }
  catch { return { ok: false, http: res.status, text: text || '' }; }
}

// ================== UI / LOG helpers ==================
const out = document.getElementById('output');
function fmt(x) {
  try { if (typeof x === 'string') return x; return JSON.stringify(x, null, 2); }
  catch (e) { try { return String(x); } catch (_) { return '[unserializable]'; } }
}
function log(line, cls) {
  const div = document.createElement('div');
  div.className = 'card';
  div.innerHTML = '<div class="' + (cls || '') + '">' + line + '</div>';
  out.appendChild(div);
  if (cls === 'fail') console.error(line); else console.log(line);
}
function pass(name, payload) { log('✅ PASS: ' + name + '\n' + fmt(payload), 'ok'); }
function fail(name, err)     { log('❌ FAIL: ' + name + '\n' + fmt(err), 'fail'); }
function info(name, msg)     { log('ℹ️ ' + name + '\n' + fmt(msg || ''), 'info'); }
function isExito(res)        { return res && (res.status === 'exito' || res.status === 'Éxito' || res.status === 'EXITO'); }

// ================== Datos fake & helpers ==================
function rnd() { return Math.floor(100000 + Math.random() * 900000); }
const stamp = Date.now();

function fakePersona(prefix) {
  // CI alto + timestamp para minimizar colisiones
  return {
    ci: String(90000000 + (Date.now() % 8999999)),
    email: `${prefix}.${stamp}.${rnd()}@example.com`,
    telefono: '099' + String(rnd()).slice(0, 6),
    nombre: `${prefix}_NOMBRE_${rnd()}`,
    apellido: `${prefix}_APELLIDO_${rnd()}`,
    'contraseña': 'Test1234.',
    'confirmarContraseña': 'Test1234.'
  };
}

function fakeFile(filename, mime) {
  const blob = new Blob(['TEST FILE ' + filename + ' @ ' + new Date().toISOString()], { type: mime });
  try { return new File([blob], filename, { type: mime }); }
  catch (e) { blob.name = filename; return blob; }
}
function formDataKV(key, file) { const fd = new FormData(); fd.append(key, file); return fd; }

// ================== Nombres de columna exactos ==================
const CAMPO_ENTREVISTA    = 'Estado_entrevista';
const CAMPO_ANTECEDENTES  = 'Estado_antecedentes';
const CAMPO_PAGO_INICIAL  = 'Estado_pago_inicial';

// ================== Helpers IDs en respuestas (Cooperativa) ==================
function pickIdHoras(item) {
  if (!item || typeof item !== 'object') return null;
  return item.idHoras || item.ID_Horas || item.id || null;
}
function pickFecha(item) {
  if (!item || typeof item !== 'object') return null;
  return item.fecha || item.Fecha || null;
}
function pickIdComprobante(item) {
  if (!item || typeof item !== 'object') return null;
  return item.idComprobante || item.ID_Comprobante || item.id || null;
}

// ================== RUNNER ==================
(async function run() {
  log('API E2E TESTS — APIUsuarios + APIBackOffice\n(Flujos: Interesado → Usuario, y pruebas adicionales de BackOffice + Cooperativa)');

  const userA = fakePersona('TESTA'); // se aprueba → Usuario
  const userB = fakePersona('TESTB'); // usado para rechazos básicos
  const userC = fakePersona('TESTC'); // aprobar/rechazar estados + rechazarInteresado
  const userE = fakePersona('TESTE'); // dedicado a pruebas APICooperativa
  const adminSeed = {
    ci: String(80000000 + (Date.now() % 8999999)),
    email: 'admin.seed.' + stamp + '@example.com',
    telefono: '098' + String(rnd()).slice(0, 6),
    nombre: 'ADMIN_TEST_' + rnd(),
    apellido: 'ADMIN_TEST_' + rnd(),
    contraseña: 'Test1234.',
    nivelPermisos: 'Operador'
  };



  // ====== (1) Registrar A y login ======
  try {
    const r = await registrarUsuario(userA);
    if (isExito(r)) pass('A: APIUsuario.registrarUsuario', r);
    else fail('A: APIUsuario.registrarUsuario', r);
  } catch (e) { fail('A: APIUsuario.registrarUsuario', e); }

  try {
    const r = await iniciarSesion({ ci: userA.ci, 'contraseña': userA['contraseña'] });
    if (isExito(r)) pass('A: APIUsuario.iniciarSesion', r);
    else fail('A: APIUsuario.iniciarSesion', r);
  } catch (e) { fail('A: APIUsuario.iniciarSesion', e); }

  let idA = null;
  try {
    const r = await usr_getIdSesion();
    if (isExito(r)) { idA = Number(r.message); pass('A: APIUsuario.getIdSesion', r); }
    else fail('A: APIUsuario.getIdSesion', r);
  } catch (e) { fail('A: APIUsuario.getIdSesion', e); }

  if (!idA) { fail('Runner', 'Sin id de sesión de A; aborto.'); return; }

  try {
    const r = await getInteresado(idA);
    if (isExito(r)) pass('A: APIUsuario.getInteresado', r);
    else fail('A: APIUsuario.getInteresado', r);
  } catch (e) { fail('A: APIUsuario.getInteresado', e); }

  // uploads A
  try {
    const r = await usr_subirFoto(formDataKV('foto', fakeFile('TESTA_foto.jpg', 'image/jpeg')));
    if (isExito(r)) pass('A: APIUsuario.subirFoto', r);
    else fail('A: APIUsuario.subirFoto', r);
  } catch (e) { fail('A: APIUsuario.subirFoto', e); }

  try {
    const r = await usr_subirComprobante(formDataKV('comprobante', fakeFile('TESTA_comprobante.pdf', 'application/pdf')));
    if (isExito(r)) pass('A: APIUsuario.subirComprobante', r);
    else fail('A: APIUsuario.subirComprobante', r);
  } catch (e) { fail('A: APIUsuario.subirComprobante', e); }

  try {
    const r = await usr_subirAntecedentes(formDataKV('antecedentes', fakeFile('TESTA_antecedentes.pdf', 'application/pdf')));
    if (isExito(r)) pass('A: APIUsuario.subirAntecedentes', r);
    else fail('A: APIUsuario.subirAntecedentes', r);
  } catch (e) { fail('A: APIUsuario.subirAntecedentes', e); }

  // ====== (2) BackOffice para A (aprobar todo → usuario) ======
  try {
    const d = new Date();
    const yyyy = d.getFullYear(), mm = ('0' + (d.getMonth() + 1)).slice(-2), dd = ('0' + d.getDate()).slice(-2);
    const HH = ('0' + d.getHours()).slice(-2), MM = ('0' + d.getMinutes()).slice(-2);
    const r = await asignarEntrevista({ idPersona: idA, fecha: (yyyy + '-' + mm + '-' + dd), hora: (HH + ':' + MM) });
    if (isExito(r)) pass('A: APIBackOffice.asignarEntrevista', r);
    else fail('A: APIBackOffice.asignarEntrevista', r);
  } catch (e) { fail('A: APIBackOffice.asignarEntrevista', e); }

  try {
    const r = await aprobarEstado({ idPersona: idA, campo: CAMPO_ENTREVISTA });
    if (isExito(r)) pass('A: APIBackOffice.aprobarEstado(' + CAMPO_ENTREVISTA + ')', r);
    else fail('A: APIBackOffice.aprobarEstado(' + CAMPO_ENTREVISTA + ')', r);
  } catch (e) { fail('A: APIBackOffice.aprobarEstado(' + CAMPO_ENTREVISTA + ')', e); }

  try {
    const r = await asignarPagoInicial({ idPersona: idA, montoPagoInicial: 12345 });
    if (isExito(r)) pass('A: APIBackOffice.asignarPagoInicial', r);
    else fail('A: APIBackOffice.asignarPagoInicial', r);
  } catch (e) { fail('A: APIBackOffice.asignarPagoInicial', e); }

  try {
    const r = await aprobarEstado({ idPersona: idA, campo: CAMPO_PAGO_INICIAL });
    if (isExito(r)) pass('A: APIBackOffice.aprobarEstado(' + CAMPO_PAGO_INICIAL + ')', r);
    else fail('A: APIBackOffice.aprobarEstado(' + CAMPO_PAGO_INICIAL + ')', r);
  } catch (e) { fail('A: APIBackOffice.aprobarEstado(' + CAMPO_PAGO_INICIAL + ')', e); }

  try {
    const r = await aprobarEstado({ idPersona: idA, campo: CAMPO_ANTECEDENTES });
    if (isExito(r)) pass('A: APIBackOffice.aprobarEstado(' + CAMPO_ANTECEDENTES + ')', r);
    else fail('A: APIBackOffice.aprobarEstado(' + CAMPO_ANTECEDENTES + ')', r);
  } catch (e) { fail('A: APIBackOffice.aprobarEstado(' + CAMPO_ANTECEDENTES + ')', e); }

  try {
    const r = await aprobarInteresado({ idPersona: idA });
    if (isExito(r)) pass('A: APIBackOffice.aprobarInteresado', r);
    else fail('A: APIBackOffice.aprobarInteresado', r);
  } catch (e) { fail('A: APIBackOffice.aprobarInteresado', e); }

  try {
    const r = await getUsuario(idA);
    if (isExito(r)) pass('A: APIUsuario.getUsuario(after-approve)', r);
    else fail('A: APIUsuario.getUsuario(after-approve)', r);
  } catch (e) { fail('A: APIUsuario.getUsuario(after-approve)', e); }

  // ====== (3) BO listados/contador ======
  try {
    const r = await getInteresados();
    if (isExito(r)) pass('APIBackOffice.getInteresados', r);
    else fail('APIBackOffice.getInteresados', r);
  } catch (e) { fail('APIBackOffice.getInteresados', e); }

  try {
    const r = await contarInteresados();
    if (isExito(r)) pass('APIBackOffice.contarInteresados', r);
    else fail('APIBackOffice.contarInteresados', r);
  } catch (e) { fail('APIBackOffice.contarInteresados', e); }


  try {
    const r = await iniciarSesion({ ci: userB.ci, 'contraseña': userB['contraseña'] });
    if (isExito(r)) pass('B: APIUsuario.iniciarSesion', r);
    else fail('B: APIUsuario.iniciarSesion', r);
  } catch (e) { fail('B: APIUsuario.iniciarSesion', e); }

  let idB = null;
  try {
    const r = await usr_getIdSesion();
    if (isExito(r)) { idB = Number(r.message); pass('B: APIUsuario.getIdSesion', r); }
    else fail('B: APIUsuario.getIdSesion', r);
  } catch (e) { fail('B: APIUsuario.getIdSesion', e); }

  if (idB) {
    try {
      const r = await rechazarEstado({ idPersona: idB, campo: CAMPO_ENTREVISTA });
      if (isExito(r)) pass('B: APIBackOffice.rechazarEstado(' + CAMPO_ENTREVISTA + ')', r);
      else info('B: APIBackOffice.rechazarEstado(' + CAMPO_ENTREVISTA + ')', r);
    } catch (e) { info('B: APIBackOffice.rechazarEstado(' + CAMPO_ENTREVISTA + ') no JSON', e); }


  }

  // ====== (5) Crear C para aprobar/rechazar estados y rechazarInteresado ======
  let idC = null;

  try {
    const r = await iniciarSesion({ ci: userC.ci, 'contraseña': userC['contraseña'] });
    if (isExito(r)) pass('C: APIUsuario.iniciarSesion', r);
    else fail('C: APIUsuario.iniciarSesion', r);
  } catch (e) { fail('C: APIUsuario.iniciarSesion', e); }

  try {
    const r = await usr_getIdSesion();
    if (isExito(r)) { idC = Number(r.message); pass('C: APIUsuario.getIdSesion', r); }
    else fail('C: APIUsuario.getIdSesion', r);
  } catch (e) { fail('C: APIUsuario.getIdSesion', e); }

  if (idC) {
    try {
      const d = new Date();
      const yyyy = d.getFullYear(), mm = ('0' + (d.getMonth() + 1)).slice(-2), dd = ('0' + d.getDate()).slice(-2);
      const HH = ('0' + d.getHours()).slice(-2), MM = ('0' + d.getMinutes()).slice(-2);
      const r = await asignarEntrevista({ idPersona: idC, fecha: (yyyy + '-' + mm + '-' + dd), hora: (HH + ':' + MM) });
      if (isExito(r)) pass('C: APIBackOffice.asignarEntrevista', r);
      else fail('C: APIBackOffice.asignarEntrevista', r);
    } catch (e) { fail('C: APIBackOffice.asignarEntrevista', e); }

    try {
      const r = await aprobarEstado({ idPersona: idC, campo: CAMPO_ENTREVISTA });
      if (isExito(r)) pass('C: APIBackOffice.aprobarEstado(' + CAMPO_ENTREVISTA + ')', r);
      else fail('C: APIBackOffice.aprobarEstado(' + CAMPO_ENTREVISTA + ')', r);
    } catch (e) { fail('C: APIBackOffice.aprobarEstado(' + CAMPO_ENTREVISTA + ')', e); }

    try {
      const r = await rechazarEstado({ idPersona: idC, campo: CAMPO_ANTECEDENTES });
      if (isExito(r)) pass('C: APIBackOffice.rechazarEstado(' + CAMPO_ANTECEDENTES + ')', r);
      else fail('C: APIBackOffice.rechazarEstado(' + CAMPO_ANTECEDENTES + ')', r);
    } catch (e) { fail('C: APIBackOffice.rechazarEstado(' + CAMPO_ANTECEDENTES + ')', e); }

    try {
      const r = await asignarPagoInicial({ idPersona: idC, montoPagoInicial: 111 });
      if (isExito(r)) pass('C: APIBackOffice.asignarPagoInicial', r);
      else fail('C: APIBackOffice.asignarPagoInicial', r);
    } catch (e) { fail('C: APIBackOffice.asignarPagoInicial', e); }

    try {
      const r = await rechazarEstado({ idPersona: idC, campo: CAMPO_PAGO_INICIAL });
      if (isExito(r)) pass('C: APIBackOffice.rechazarEstado(' + CAMPO_PAGO_INICIAL + ')', r);
      else fail('C: APIBackOffice.rechazarEstado(' + CAMPO_PAGO_INICIAL + ')', r);
    } catch (e) { fail('C: APIBackOffice.rechazarEstado(' + CAMPO_PAGO_INICIAL + ')', e); }

    // Rechazar interesado C (si tiene dependencias puede fallar por FK → INFO)

  }

  // ====== (6) BO pagos (globales) ======
  try {
    const r = await asignarPagoMensual({ montoPagoMensual: 999 });
    if (isExito(r)) pass('APIBackOffice.asignarPagoMensual', r);
    else fail('APIBackOffice.asignarPagoMensual', r);
  } catch (e) { fail('APIBackOffice.asignarPagoMensual', e); }

  try {
    const r = await asignarPagoPersonalizado({ montoPagoPersonalizado: 321, ci: userA.ci, motivoPagoPersonalizado: 'TEST_Personalizado' });
    if (isExito(r)) pass('APIBackOffice.asignarPagoPersonalizado', r);
    else fail('APIBackOffice.asignarPagoPersonalizado', r);
  } catch (e) { fail('APIBackOffice.asignarPagoPersonalizado', e); }

  let pendientes = null;
  try {
    const r = await getPagosPendientes();
    if (isExito(r)) { pass('APIBackOffice.getPagosPendientes', r); pendientes = r.message; }
    else fail('APIBackOffice.getPagosPendientes', r);
  } catch (e) { fail('APIBackOffice.getPagosPendientes', e); }

  if (pendientes && typeof pendientes === 'object' && !Array.isArray(pendientes) && ('comprobantesPendientes' in pendientes)) {
    info('APIBackOffice.aprobarPago / rechazarPago', 'No hay ítems concretos (solo contador).');
  } else {
    let idc = null;
    if (Array.isArray(pendientes) && pendientes.length) {
      const p0 = pendientes[0];
      idc = p0.idComprobante || p0.ID_Comprobante || p0.id || null;
    } else if (pendientes && typeof pendientes === 'object') {
      const ks = Object.keys(pendientes);
      if (ks.length) {
        const v = pendientes[ks[0]];
        idc = (v && (v.idComprobante || v.ID_Comprobante || v.id)) || null;
      }
    }
    if (!idc) {
      info('APIBackOffice.aprobarPago / rechazarPago', 'No se encontró idComprobante para probar.');
    } else {
      try {
        const r = await aprobarPago({ idComprobante: idc });
        if (isExito(r)) pass('APIBackOffice.aprobarPago(id=' + idc + ')', r);
        else fail('APIBackOffice.aprobarPago(id=' + idc + ')', r);
      } catch (e) { fail('APIBackOffice.aprobarPago', e); }

      try {
        const r = await rechazarPago({ idComprobante: idc });
        if (isExito(r)) pass('APIBackOffice.rechazarPago(id=' + idc + ')', r);
        else info('APIBackOffice.rechazarPago(id=' + idc + ')', r);
      } catch (e) { info('APIBackOffice.rechazarPago no JSON', e); }
    }
  }

  // ====== (7) BO subirFoto ======
  try {
    const r = await bo_subirFoto(formDataKV('foto', fakeFile('BO_banner.png', 'image/png')));
    if (isExito(r)) pass('APIBackOffice.subirFoto', r);
    else fail('APIBackOffice.subirFoto', r);
  } catch (e) { fail('APIBackOffice.subirFoto', e); }

  // ====== (8) BO getIdSesion ======
  try {
    const r = await bo_getIdSesion();
    if (isExito(r)) pass('APIBackOffice.getIdSesion', r);
    else info('APIBackOffice.getIdSesion', r);
  } catch (e) { info('APIBackOffice.getIdSesion no JSON', e); }

  // ====== (9) BO getAdmin ======
  try {
    const r = await getAdmin(1); // cambia si conocés otro ID válido
    if (isExito(r)) pass('APIBackOffice.getAdmin(id=1)', r);
    else info('APIBackOffice.getAdmin(id=1)', r);
  } catch (e) { info('APIBackOffice.getAdmin no JSON', e); }

  // ====== (10) BO crearAdmin (direct) ======
  try {
    const r = await directJson(BO_BASE + '?accion=crearAdmin', 'POST', adminSeed);
    if (r.ok && r.json && isExito(r.json)) pass('APIBackOffice.crearAdmin (direct)', r.json);
    else info('APIBackOffice.crearAdmin (direct)', r.ok ? r.json : ('HTTP ' + r.http + ' ' + r.text));
  } catch (e) {
    info('APIBackOffice.crearAdmin (direct) error', e);
  }

  // ====== (11) BO asignarUnidadHabitacional (direct) — si no está implementado, es normal que devuelva error ======
  try {
    const r = await directJson(BO_BASE + '?accion=asignarUnidadHabitacional', 'POST', { ci: userA.ci, idUnidadHabitacional: 123 });
    if (r.ok && r.json && isExito(r.json)) pass('APIBackOffice.asignarUnidadHabitacional (direct)', r.json);
    else info('APIBackOffice.asignarUnidadHabitacional (direct)', r.ok ? r.json : ('HTTP ' + r.http + ' ' + r.text));
  } catch (e) {
    info('APIBackOffice.asignarUnidadHabitacional (direct) error', e);
  }

  // ================== (12) === APICOOPERATIVA (usuario E) ==================


  try {
    const r = await iniciarSesion({ ci: userE.ci, 'contraseña': userE['contraseña'] });
    if (isExito(r)) pass('E: APIUsuario.iniciarSesion', r);
    else fail('E: APIUsuario.iniciarSesion', r);
  } catch (e) { fail('E: APIUsuario.iniciarSesion', e); }

  let idE = null;
  try {
    const r = await usr_getIdSesion();
    if (isExito(r)) { idE = Number(r.message); pass('E: APIUsuario.getIdSesion', r); }
    else fail('E: APIUsuario.getIdSesion', r);
  } catch (e) { fail('E: APIUsuario.getIdSesion', e); }

  // Lecturas básicas de Cooperativa
  try {
    const r = await getCooperativa(idE);
    if (isExito(r)) pass('Coop.getCooperativa', r);
    else fail('Coop.getCooperativa', r);
  } catch (e) { fail('Coop.getCooperativa', e); }

  try {
    const r = await coop_getPagos(idE);
    if (isExito(r)) pass('Coop.getPagos', r);
    else fail('Coop.getPagos', r);
  } catch (e) { fail('Coop.getPagos', e); }

  let horasHist = null;
  try {
    const r = await getHorasTrabajadas(idE);
    if (isExito(r)) { pass('Coop.getHorasTrabajadas(pre)', r); horasHist = r.message && r.message.horas; }
    else fail('Coop.getHorasTrabajadas(pre)', r);
  } catch (e) { fail('Coop.getHorasTrabajadas(pre)', e); }

  // subirHoras — caso inválido y válido
  try {
    const r = await subirHoras({ horas: 0 });
    isExito(r) ? info('Coop.subirHoras(0) — esperaba error', r) : pass('Coop.subirHoras(0) — validación OK', r);
  } catch (e) { info('Coop.subirHoras(0) no JSON', e); }

  try {
    const r = await subirHoras({ horas: 3 });
    if (isExito(r)) pass('Coop.subirHoras(3)', r);
    else fail('Coop.subirHoras(3)', r);
  } catch (e) { fail('Coop.subirHoras(3)', e); }

  // capturar idHoras para editar/borrar
  let idHoras = null, fechaHoras = null;
  try {
    const r = await getHorasTrabajadas(idE);
    if (isExito(r)) {
      pass('Coop.getHorasTrabajadas(post)', r);
      const arr = (r.message && r.message.horas) || [];
      const last = Array.isArray(arr) && arr.length ? arr[arr.length - 1] : null;
      idHoras = pickIdHoras(last);
      fechaHoras = pickFecha(last) || new Date().toISOString().slice(0,10);
    } else {
      fail('Coop.getHorasTrabajadas(post)', r);
    }
  } catch (e) { fail('Coop.getHorasTrabajadas(post)', e); }

  if (idHoras) {
    try {
      const r = await editarHoras({ idHoras, horas: 4, fecha: fechaHoras });
      if (isExito(r)) pass('Coop.editarHoras(id=' + idHoras + ', horas=4)', r);
      else fail('Coop.editarHoras', r);
    } catch (e) { fail('Coop.editarHoras', e); }

    try {
      const r = await borrarHoras({ idHoras });
      if (isExito(r)) pass('Coop.borrarHoras(id=' + idHoras + ')', r);
      else fail('Coop.borrarHoras', r);
    } catch (e) { fail('Coop.borrarHoras', e); }
  } else {
    info('Coop.editarHoras/borrarHoras', 'No se encontró idHoras para operar.');
  }

  // intentar subirComprobante si hay alguno pendiente (lo obtengo desde getCooperativa)
  let idComprobante = null;
  try {
    const r = await getCooperativa(idE);
    if (isExito(r)) {
      const pend = r.message && r.message.comprobantesPendientes;
      if (Array.isArray(pend) && pend.length) {
        idComprobante = pickIdComprobante(pend[0]);
      } else if (pend && typeof pend === 'object') {
        const k = Object.keys(pend)[0];
        idComprobante = pickIdComprobante(pend[k]);
      }
    }
  } catch (e) {
    // nada; sólo es para obtener id
  }

  if (idComprobante) {
    try {
      const fd = formDataKV('comprobante', fakeFile('TESTE_comprobante.pdf', 'application/pdf'));
      const r = await coop_subirComprobante(fd, idComprobante);
      if (isExito(r)) pass('Coop.subirComprobante(id=' + idComprobante + ')', r);
      else fail('Coop.subirComprobante', r);
    } catch (e) { fail('Coop.subirComprobante', e); }
  } else {
    info('Coop.subirComprobante', 'No hay comprobantes pendientes con idComprobante detectable.');
  }

  // ====== FIN ======
  log('Tests terminados');
})();
