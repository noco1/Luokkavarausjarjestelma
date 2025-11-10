<?php
    require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Luokkavaraus</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <div id="app">
    <div id="login-view" class="card">
      <h2>Kirjaudu</h2>
      <input id="email" placeholder="Sähköposti">
      <input id="password" type="password" placeholder="Salasana">
      <button id="loginBtn">Kirjaudu</button>
      <p id="loginMsg" class="msg"></p>
    </div>

    <div id="main-view" style="display:none;">
      <header>
        <h1>Luokkavarausjärjestelmä</h1>
        <div id="userInfo">Tervetuloa, <span id="userName"></span> (<span id="userRole"></span>) <button id="logoutBtn">Kirjaudu ulos</button></div>
      </header>

      <section class="card">
        <h3>Uusi varaus</h3>
        <form id="reserveForm">
          <label>Luokka</label>
          <select id="classroomSelect"></select>
          <label>Aloitus</label>
          <input type="datetime-local" id="startTime">
          <label>Loppu</label>
          <input type="datetime-local" id="endTime">
          <label>Tarkoitus</label>
          <input id="purpose">
          <button type="submit">Varaa</button>
        </form>
        <p id="reserveMsg" class="msg"></p>
      </section>

      <section class="card">
        <h3>Varaukset</h3>
        <table id="reservationsTable">
          <thead><tr><th>Luokka</th><th>Käyttäjä</th><th>Aloitus</th><th>Loppu</th><th>Status</th><th>Toiminnot</th></tr></thead>
          <tbody></tbody>
        </table>
      </section>
    </div>
  </div>

  <script src="assets/main.js"></script>
</body>
</html>