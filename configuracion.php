<?php 
session_start();
require  'medoo.php';

$database = new medoo([
    'database_type' => 'mysql',
    'database_name' => 'gym',
    'server' => 'localhost',
    'username' => 'root',
    'password' => 'root',
    'charset' => 'utf8'
]);

$mensua = $database->select("tb_mensualidades","*");
$user = $database->select("tb_usuarios","*");
$usuario = "";
$tipo = ""; 
$data = count($user);
for ($i=0; $i <$data ; $i++) { 
  if ($user[$i]["usuario"] == $_SESSION['username'] && $user[$i]["tipo"] == "administrador" ) {
    $usuario = $user[$i]["usuario"];
    $tipo = $user[$i]["tipo"];
  }
}

  if ($usuario == $_SESSION['username'] && $tipo == "administrador" ) {

?>
<!DOCTYPE html>
<html>
<head>
  <title>Configuracion</title>
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
        <div class="box-principal">
            <h3 class="titulo"><br><br>Configuracion<hr></h3>
              <div class="col-xs-12 col-sm-6 col-lg-6">
                <div  class="box">              
                  <div class="icon">
                    <div class="image"><i class="fa fa-user-secret"></i></div>
                    <div class="info">
                      <h3 class="title">Usuarios </h3>
                        <div class="more">
                          <a href="usuarios.php" title="Title Link">
                            Ver todo <i class="fa fa-angle-double-right"></i>
                          </a>
                        </div>
                    </div>
                  </div>
                  <div class="space"></div>
                </div> 
              </div>
                
              <div class="col-xs-12 col-sm-6 col-lg-6">
                <div   class="box">             
                  <div class="icon">
                    <div class="image"><i class="fa fa-money fa-lg"></i></div>
                    <div class="info">
                      <h3 class="title">Mensualidades </h3>  
                        <div class="more">
                          <a href="mensualidades.php" title="Title Link">
                            Ver todo <i class="fa fa-angle-double-right"></i>
                          </a>
                        </div>
                    </div>
                  </div>
                <div class="space"></div>
                </div> 
              </div>    
          </div>
              <?php 
                }else{
                  print("¡Error! No tienes autorizacion para ingresar a esta seccion");
                }
                ?>
              
 
</body>
</html>