<?php 
session_start();
if (isset($_SESSION['username'])) {
}else{
  header("Location: index.php");
}

date_default_timezone_set('America/Costa_Rica');
$fechaactual = Date("Y-m-d");
require 'medoo.php';

$database = new medoo([
  'database_type' => 'mysql',
  'database_name' => 'bd_gym',
  'server' => 'localhost',
  'username' => 'kecoco',
  'password' => 'Banbastic1',
  'charset' => 'utf8'
  ]);

$data_atrasados = $database->select("tb_clientes", "*",[
  "fecha_pago[<]" => "$fechaactual",
  "ORDER" => "fecha_pago"
  ]);

$data_hoy = $database->select("tb_clientes", "*",[
  "fecha_pago" => "$fechaactual",
  "ORDER" => "nombre"
  ]);

$data_todos = $database->select("tb_clientes", "*",[
  "ORDER" => "nombre"
  ]);


$usuario = $database->select("tb_usuarios", "*");
$tipo = "";
$data = count($usuario);
for ($i=0; $i <$data ; $i++) { 
  if (strcasecmp($usuario[$i]["id_usuario"],$_SESSION['username']) == 0 && $usuario[$i]["tipo"] == "administrador" ) {
    $tipo = $usuario[$i]["tipo"];
  }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, user-scalable=no">
  <title>GIMNASIO</title> 
  <link href="css/bootstrap.css" rel="stylesheet" type="text/css">
  <link href="css/general.css" rel="stylesheet" type="text/css">
  <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <script src="js/jquery-1.12.4.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/main.js"></script>
</head>
<body>
  <?php 
  include("html.php");
  ?>

  <div class="container" style="padding-top: 100px">
    <div class="row">
      
      <div class="col-xs-12 col-sm-6 col-lg-4">
        <div  class="box">              
          <div class="icon">
            <div class="image"><i class="fa fa-thumbs-o-up"></i></div>
            <div class="info">
              <h3 class="title">Clientes para hoy <?php echo count($data_hoy);?></h3>
              <div class="more">
                <a href="hoy.php">
                  Ver todo <i class="fa fa-angle-double-right"></i>
                </a>
              </div>
            </div>
          </div>
          <div class="space"></div>
        </div> 
      </div>
      
      <div class="col-xs-12 col-sm-6 col-lg-4">
        <div  class="box">              
          <div class="icon">
            <div class="image"><i class="fa fa-exclamation-triangle"></i></div>
            <div class="info">
              <h3 class="title">Clientes pendientes <?php echo count($data_atrasados);?></h3>
              <div class="more">
                <a href="atrasados.php">
                  Ver todo <i class="fa fa-angle-double-right"></i>
                </a>
              </div>
            </div>
          </div>
          <div class="space"></div>
        </div> 
      </div>
      
      <div class="col-xs-12 col-sm-6 col-lg-4">
        <div   class="box">             
          <div class="icon">
            <div class="image"><i class="fa fa-book fa-fw"></i></div>
            <div class="info">
              <h3 class="title">Listado de clientes <?php echo count($data_todos); ?></h3>  
              <div class="more">
                <a href="listado.php">
                  Ver todo <i class="fa fa-angle-double-right"></i>
                </a>
              </div>
            </div>
          </div>
          <div class="space"></div>
        </div> 
      </div>    

      <div class="col-xs-12 col-sm-6 col-lg-4">
        <div class="box">             
          <div class="icon">
            <div class="image"><i class="fa fa-address-card"></i></div>
            <div class="info">
              <h3 class="title">Agregar un cliente</h3>
              <div class="more">
                <a href="agregar.php">
                  Ver todo <i class="fa fa-angle-double-right"></i>
                </a>
              </div>
            </div>
          </div>
          <div class="space"></div>
        </div> 
      </div>

      <div class="col-xs-12 col-sm-6 col-lg-4">
        <div class="box">             
          <div class="icon">
            <div class="image"><i class="fa fa-money fa-lg"></i></div>
            <div class="info">
              <h3 class="title">Pagos</h3>
              <div class="more">
                <a href="pagos.php">
                  Ver todo <i class="fa fa-angle-double-right"></i>
                </a>  
              </div>
            </div>
          </div>
          <div class="space"></div>
        </div> 
      </div>

      <?php 
      if ($tipo == "administrador"){
        ?>
        <div class="col-xs-12 col-sm-6 col-lg-4">
          <div class="box">             
            <div class="icon">
              <div class="image"><i class="fa fa-cog"></i></div>
              <div class="info">
                <h3 class="title">Configuracion</h3>
                <div class="more">
                  <a href="configuracion.php">
                    Ver todo <i class="fa fa-angle-double-right"></i>
                  </a>
                </div>
              </div>
            </div>
            <div class="space"></div>
          </div> 
        </div>
        <?php 
      }
      ?>
      
      <div class="col-xs-12 col-sm-6 col-lg-4">
        <div class="box">             
          <div class="icon">
            <div class="image"><i class="fa fa-sign-out"></i></div>
            <div class="info">
              <h3 class="title">Salir</h3>
              <div class="more">
                <a href="salir.php">
                  Salir <i class="fa fa-angle-double-right"></i>
                </a>
              </div>
            </div>
          </div>
          <div class="space"></div>
        </div> 
      </div>

      
      
    </div>
  </div>
  
</body>
</html>

