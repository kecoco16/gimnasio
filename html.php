<?php
$nombres=$_SESSION['name'];
$id = $_SESSION['username'];
?>
<!DOCTYPE html>
<html>
<head>
 <meta name="viewport" content="width=device-width, user-scalable=no">
 <link href="css/bootstrap.css" rel="stylesheet" type="text/css">
 <link href="css/general.css" rel="stylesheet" type="text/css">
 <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
</head>
<body>
	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container-fluid">
      <div class="navbar-header">
        <button class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="inicio.php"><i class="fa fa-home fa-fw"></i>&nbsp; Inicio</a>
      </div>
      <div style="text-align: center;" class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul id="centrar" style="float: none; display: inline-block;" class="nav navbar-nav">
          <form id="searchG" class="navbar-form navbar-center" name="buscador" method="get" action="buscar.php">
           <div class="form-group">
            <input id="buscar" name="buscar" type="search" placeholder="Buscar aquí..."  class="form-control" autofocus>
          </div>
          <button title="Buscar" data-toggle='tooltip' data-placement='bottom' type="submit" name="buscador" class="btn btn-default"><i class="fa fa-search fa-lg"></i> </button>
        </form>
      </ul>

      <form id="searchP" class="navbar-form navbar-left" name="buscador" method="get" action="buscar.php">
        <div class="form-group">
          <input id="buscarP" name="buscar" type="search" class="form-control" placeholder="Buscar aqui">
        </div>
        <button class="btn btn-default" title="Buscar" type="submit" name="buscador"><i class="fa fa-search fa-lg"></i></button>
      </form>

      <ul id="name" class="nav navbar-nav navbar-right">
        <li><a>Hola <?php echo $nombres; ?></a></li>
      </div>
    </ul>
  </div>
</div>
</nav>
</body>

</html>