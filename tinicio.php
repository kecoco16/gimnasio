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
  'database_name' => 'gym',
  'server' => 'localhost',
  'username' => 'root',
  'password' => 'root',
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
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>GIMNASIO</title> 
  <link href="css/bootstrap.css" rel="stylesheet" type="text/css">
  <link href="css/general.css" rel="stylesheet" type="text/css">
  <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <style>
    
    .box > .icon { text-align: center; position: relative; }
    .box > .icon > .image { position: relative; z-index: 2; margin: auto; width: 88px; height: 88px; border: 8px solid white; line-height: 88px; border-radius: 50%; background: #63B76C; vertical-align: middle; }
    .box > .icon:hover > .image { background: #333; }
    .box > .icon > .image > i { font-size: 36px !important; color: #fff !important; }
    .box > .icon:hover > .image > i { color: white !important; }
    .box > .icon > .info { margin-top: -24px; background: rgba(0, 0, 0, 0.04); border: 1px solid #e0e0e0; padding: 15px 0 10px 0; }
    .box > .icon:hover > .info { background: rgba(0, 0, 0, 0.04); border-color: #e0e0e0; color: white; }
    .box > .icon > .info > h3.title { font-family: "Roboto",sans-serif !important; font-size: 16px; color: #222; font-weight: 500; }
    .box > .icon > .info > p { font-family: "Roboto",sans-serif !important; font-size: 13px; color: #666; line-height: 1.5em; margin: 20px;}
    .box > .icon:hover > .info > h3.title, .box > .icon:hover > .info > p, .box > .icon:hover > .info > .more > a { color: #222; }
    .box > .icon > .info > .more a { font-family: "Roboto",sans-serif !important; font-size: 12px; color: #222; line-height: 12px; text-transform: uppercase; text-decoration: none; }
    .box > .icon:hover > .info > .more > a { color: #fff; padding: 4px 6px; background-color: #63B76C; }
    .box .space { height: 30px; }
  </style>
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
                  <a href="hoy.php" title="Title Link">
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
                  <a href="atrasados.php" title="Title Link">
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
                  <a href="listado.php" title="Title Link">
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
                  <a href="agregar.php" title="Title Link">
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
            <div class="image"><i class="fa fa-shopping-cart"></i></div>
            <div class="info">
              <h3 class="title">Pagos</h3>
                <div class="more">
                  <a href="pagos.php" title="Title Link">
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
              <div class="image"><i class="fa fa-cog fa-spin fa-lg fa-fw"></i></div>
              <div class="info">
                <h3 class="title">Configuracion</h3>
                  <div class="more">
                    <a href="configuracion.php" title="Title Link">
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
                  <a href="salir.php" title="Title Link">
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

