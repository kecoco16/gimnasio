<?php
session_start();
if (isset($_SESSION['username'])) {
}else{
  header("Location: index.php");
}

date_default_timezone_set('America/Costa_Rica');
$hoy = date('ymd');

require  'medoo.php';

$database = new medoo([
  'database_type' => 'mysql',
  'database_name' => 'bd_gym',
  'server' => 'localhost',
  'username' => 'kecoco',
  'password' => 'Banbastic1',
  'charset' => 'utf8'
  ]);

if(!empty($_FILES['imagen']['name'])){

  $aleatorio  = rand(1,99);
  $nombre_img = $aleatorio.$_POST["nombre"].$_FILES['imagen']['name'];
  $tipo = $_FILES['imagen']['type'];
  $tamano = $_FILES['imagen']['size'];
  $actual = $_POST["fecha_inicio"];
  $nuevafecha = strtotime ( '+1 month' , strtotime ( $actual ) ) ;
  $nuevafecha = date ( 'Y-m-d' , $nuevafecha );
  
  $database->insert("tb_clientes", [
    "nombre" => $_POST["nombre"],
    "sexo" => $_POST["sexo"],
    "fecha_inicio" => $_POST["fecha_inicio"],
    "fecha_pago" => "$nuevafecha",
    "id_mensualidad" => $_POST["mensualidad"],
    "telefono" => $_POST["telefono"],
    "correo" => $_POST["correo"],
    "ruta_imagen" => "$nombre_img",
    ]);

  $database->update("tb_mensualidades", [
    "total_clientes[+]" => 1
    ], [
    "id_mensualidad" => $_POST["mensualidad"]
    ]);
  
  
  if ($nombre_img == !NULL){
    if (($_FILES["imagen"]["type"] == "image/gif")
      ||($_FILES["imagen"]["type"] == "image/jpeg")
      ||($_FILES["imagen"]["type"] == "image/jpg")
      ||($_FILES["imagen"]["type"] == "image/png")){
      
      $directorio = $_SERVER['DOCUMENT_ROOT'].'/imagenes/';
    move_uploaded_file($_FILES['imagen']['tmp_name'],$directorio.$nombre_img);
    
  }else{
    echo "No se puede subir una imagen con ese formato ";
  }

}

header('Location: listado.php');

}else{
  if (!empty($_POST["sexo"])) {
    if ($_POST["sexo"] == "m") {
      $nombre_img ="default_mujer.jpg";
    }else{
      $nombre_img ="default_hombre.jpg";
    }
    $actual = $_POST["fecha_inicio"];
    $nuevafecha = strtotime ( '+1 month' , strtotime ( $actual ) ) ;
    $nuevafecha = date ( 'Y-m-d' , $nuevafecha );
    
    $database->insert("tb_clientes", [
      "nombre" => $_POST["nombre"],
      "sexo" => $_POST["sexo"],
      "fecha_inicio" => $_POST["fecha_inicio"],
      "id_mensualidad" => $_POST["mensualidad"],
      "fecha_pago" => "$nuevafecha",
      "telefono" => $_POST["telefono"],
      "correo" => $_POST["correo"],
      "ruta_imagen" => "$nombre_img",
      ]);

    $database->update("tb_mensualidades", [
      "total_clientes[+]" => 1
      ], [
      "id_mensualidad" => $_POST["mensualidad"]
      ]);

    header('Location: listado.php');    
  }   
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, user-scalable=no">
  <title>GIMNASIO</title>
  <link href="css/bootstrap.css" rel="stylesheet" type="text/css">
  <link href="css/general.css" rel="stylesheet" type="text/css">
  <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <script src="js/main.js"></script>
  <script src="js/jquery-1.12.4.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/main.js"></script>
</head>

<body>
  <?php 
  include("html.php");

  ?>
  <div class="box-principal">
    <h3 class="titulo"><br><br>Agregar registro<hr></h3>
    <div class="panel panel-success">
     <div class="panel-heading">
      <h3 class="panel-title">Nuevo registro</h3>
    </div>
    <div class="panel-body">
      <table class="table table-striped table-hover ">
        <div class="row">
          <div class="col-md-1"></div>
          <div class="col-md-10">
            <form class="form-horizontal" action="" method="POST" enctype="multipart/form-data">
              <div class="form-group">
                <label class="control-label"><i class="fa fa-user fa-lg"></i> Nombre</label>
                <input class="form-control" name="nombre" type="text" required>
                <label class="control-label"><i class="fa fa-transgender-alt fa-lg"></i> Sexo </label>
                <select class="form-control" name="sexo" required>
                  <option></option>
                  <option value="h">Masculino</option>
                  <option value="m">Femenino</option>
                </select>
                <label class="control-label"><i class="fa fa-calendar fa-lg"></i> Fecha inicio</label>
                <input class="form-control" type="date" name="fecha_inicio" max=<?php echo $hoy;?> required>
                <label class="control-label"><i class="fa fa-mobile fa-lg"></i> Telefono</label>
                <input class="form-control" type="tel" name="telefono" minlength="8" maxlength="8" pattern="[0-9]{8}" required>
                <label class="control-label"><i class="fa fa-money fa-lg"></i> Mensualidad </label>
                <select class="form-control" name="mensualidad" required>
                  <option></option>
                  <?php 
                  $mensualidad = $database->select("tb_mensualidades","*");
                  $cantidad = COUNT($mensualidad);
                  for ($i=0; $i<$cantidad ; $i++) { 
                    echo "<option value=".$mensualidad[$i]["id_mensualidad"]."'>".$mensualidad[$i]["tipo"]." (₡".$mensualidad[$i]["mensualidad"].")</option>";  
                  }
                  ?>
                </select>
                <label class="control-label"><i class="fa fa-envelope-o fa-lg"></i> Correo </label>
                <input class="form-control" type="email" name="correo">
                <label class="control-label">Imagen</label>
                <div class="fileUpload btn btn-default">
                  <span><i class="fa fa-camera-retro fa-2x"></i></span>
                  <input type="file" name="imagen" id="imagen" class="upload" />
                </div>
              </div>	       
              <a style='padding-left:40%;' >
              </a> 
              <button title="Aceptar" data-toggle='tooltip' data-placement='top' type="submit" class="btn btn-link">
                <i class="fa fa-check fa-2x"></i> 
              </button>           
              <button style="padding-left: 0px;" type="reset" title="Cancelar" data-toggle='tooltip' data-placement='top' class="btn btn-link" onclick="window.location.href='http://ccdrorotina.com/inicio.php'" />
              <i class="fa fa-times fa-2x"></i></a> 
            </button>
          </form>
        </div>
      </div>
    </table>
  </div>
</div>
</div>
</body>
</html>

