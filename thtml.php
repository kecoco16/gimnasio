<?php
$nombre=$_SESSION['username'];

$usuario = $database->select("tb_usuarios", "*");
$user = "";
$tipo = "";
$data = count($usuario);
for ($i=0; $i <$data ; $i++) { 
  if ($usuario[$i]["usuario"] == $_SESSION['username'] && $usuario[$i]["tipo"] == "administrador" ) {
    $user = $usuario[$i]["usuario"];
    $tipo = $usuario[$i]["tipo"];
  }
}

?>
<!DOCTYPE html>
<html>
<head>
   <link href="css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="css/general.css" rel="stylesheet" type="text/css">
  <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
</head>
<body>
  <nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="inicio.php"><i class="fa fa-home fa-fw"></i>&nbsp; Inicio</a>
        </div>
          <div style="text-align: center;" class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
              <ul style="float: none; display: inline-block;" class="nav navbar-nav">
                <form class="navbar-form navbar-center" name="buscador" method="get" action="buscar.php">
                  <div class="form-group">
                      <input id="buscar" name="buscar" type="search" placeholder="Buscar aquí..."  class="form-control" autofocus>
                  </div>
                  <button title="Buscar" type="submit" name="buscador" class="btn btn-default"><i class="fa fa-search fa-lg"></i> </button>
                </form>
              </ul>
          <ul class="nav navbar-nav navbar-right">
              <?php
                  $cantidad = count($usuario);

                    for($i=0; $i<$cantidad; $i++){
                      if ($nombre == $usuario[$i]["usuario"] ) {
                        echo "<li><a title='Editar' href='editarUsuario.php? id=".$usuario[$i]["id_usuario"]."'>Hola ".$usuario[$i]["usuario"]."</a></li>";
                      }
                      
                    }   

               if ($tipo == "administrador"){
              ?>
              <li><a href="configuracion.php"> <i class="fa fa-cog fa-spin fa-lg fa-fw" ></i></a></li>
              <?php 
              }
              ?>
              
          </div>
          </ul>
        </div>
    </div>
  </nav>
</body>
</html>