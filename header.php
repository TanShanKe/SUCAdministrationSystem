<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <title>SUC Administration Sytem</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  </head>
  <body>
    <nav class="navbar navbar-light" style="background-color: #B7C6EE;">
      <div class="container-fluid">
        <a class="navbar-brand" style="font-size: 150%";>SUC Administration System</a>
        <form class="d-flex" role="logout">
          <button class="btn btn-outline-primary" type="button" onclick="confirmLogout()">Logout</button>
        </form>
      </div>
    </nav>

<script>
  function confirmLogout() {
    if (confirm('Are you sure you want to logout?')) {
      location.href = baseUrl + 'index.php';
    }
  }
</script>