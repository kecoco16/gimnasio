<?php
session_start();
if (isset($_SESSION['username'])) {
}else{
  header("Location: index.php");
}
require  'medoo.php';

$database = new medoo([
  'database_type' => 'mysql',
  'database_name' => 'bd_gym',
  'server' => 'localhost',
  'username' => 'kecoco',
  'password' => 'Banbastic1',
  'charset' => 'utf8'
  ]);

if($_GET){
  $data = $database->select(
    "tb_clientes",[
    "[><]tb_mensualidades" => ["id_mensualidad" => "id_mensualidad"],
    ],
    [   
    "tb_clientes.nombre",
    "tb_clientes.id_mensualidad",
    "tb_clientes.telefono",
    "tb_clientes.correo",
    "tb_clientes.fecha_inicio",
    "tb_clientes.id_clientes",
    "tb_clientes.ruta_imagen",
    "tb_clientes.fecha_pago",
    "tb_mensualidades.mensualidad",
    "tb_mensualidades.tipo"
    ],[
    "id_clientes" => $_GET["id"]
    ]);
}

if(!empty($_FILES['imagen']['name'])){

  $aleatorio  = rand(1,99);
  $nombre_img = $aleatorio.$_POST["nombre"].$_FILES['imagen']['name'];
  $tipo = $_FILES['imagen']['type'];
  $tamano = $_FILES['imagen']['size'];

  $database->update("tb_clientes", [
    "nombre" => $_POST["nombre"],
    "telefono" => $_POST["telefono"],
    "correo" => $_POST["correo"],
    "id_mensualidad" => $_POST["mensualidad"],
    "ruta_imagen" => "$nombre_img",
    ],
    
    ["id_clientes" => $_POST["id"]]);
  
  if ($nombre_img == !NULL) { //Si existe imagen 
    if (($_FILES["imagen"]["type"] == "image/gif")
      || ($_FILES["imagen"]["type"] == "image/jpeg")
      || ($_FILES["imagen"]["type"] == "image/jpg")
      || ($_FILES["imagen"]["type"] == "image/png"))
    {//indicamos los formatos que permitimos subir a nuestro servidor
      $directorio = $_SERVER['DOCUMENT_ROOT'].'/imagenes/';// Ruta donde se guardarán las imágenes que subamos
      move_uploaded_file($_FILES['imagen']['tmp_name'],$directorio.$nombre_img);// Muevo la imagen desde el directorio temporal a nuestra ruta indicada anteriormente
    }else{//si no cumple con el formato
     echo "No se puede subir una imagen con ese formato ";
   }
 }

 header('Location: listado.php');  
}else{
  
  if($_POST){
    $database->update("tb_clientes", [
      "nombre" => $_POST["nombre"],
      "telefono" => $_POST["telefono"],
      "id_mensualidad" => $_POST["mensualidad"],
      "correo" => $_POST["correo"],
      ],
      ["id_clientes" => $_POST["id"]]);
    header('Location: inicio.php');
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
  <script src="js/jquery-1.12.4.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/main.js"></script>
</head>
<body>
  <?php 
  include("html.php");
  ?>
  <div class="box-principal">
    <h3 class="titulo"><br><br>Editar registro<hr></h3>
    <div class="panel panel-success">
      <div class="panel-heading">
        <h3 class="panel-title">Perfil de <?php echo $data[0]["nombre"];?></h3>
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-md-3">
            <?php
            $cantidad = count($data);
            for($i=0; $i<$cantidad; $i++){
              echo "<img class='img-responsive' src='imagenes/".$data[$i]["ruta_imagen"]."'>";
            }
            ?>
            <form class="form-horizontal" action="editar.php" method="POST" enctype="multipart/form-data">
              <div class="fileUpload btn btn-default">
                <span><i class="fa fa-camera-retro fa-2x"></i></span>
                <input type="file" name="imagen" id="imagen" class="upload" />
              </div>
            </div>
            <div class="col-md-9">
              <ul class="list-group">
                
                <div class="form-group">
                  <label class="control-label">Nombre</label>
                  <input class="form-control" name="nombre" type="text" required value="<?php echo $data[0]["nombre"]; ?>">
                  <label class="control-label">Telefono</label>
                  <input class="form-control" type="tel" name="telefono" minlength="8" maxlength="8" required  value="<?php echo $data[0]["telefono"]; ?>">
                  <label class="control-label"><i class="fa fa-money fa-lg"></i> Mensualidad </label>
                  <select class="form-control" name="mensualidad" required>
                    <option  value="<?php echo $data[0]["id_mensualidad"]; ?>"></option>
                    <?php 
                    $mensualidad = $database->select("tb_mensualidades","*");
                    $cantidad = count($mensualidad);
                    for ($i=0; $i<$cantidad ; $i++) { 
                      if ($mensualidad[$i]["id_mensualidad"] == $data[0]["id_mensualidad"]){
                        echo "<option selected='selected' value=".$mensualidad[$i]["id_mensualidad"].">".$mensualidad[$i]["tipo"]." (₡".$mensualidad[$i]["mensualidad"].")</option>";  
                      }else{
                        echo "<option value=".$mensualidad[$i]["id_mensualidad"].">".$mensualidad[$i]["tipo"]." (₡".$mensualidad[$i]["mensualidad"].")</option>";
                      }
                    }
                    ?>
                  </select>
                  <label class="control-label" >Correo</label>
                  <input class="form-control" type="email" name="correo" value="<?php echo $data[0]["correo"]; ?>">
                  <input type="hidden" name="id" value="<?php echo $data[0]["id_clientes"]; ?>">
                </div>

                <td style="padding-top: 1.5%; padding-bottom: 0px;">
                  <a style='padding-top:16px; padding-left:35%;'></a>
                  <button title="Aceptar" type="submit" data-toggle='tooltip' data-placement='bottom' class="btn btn-link">
                    <i class="fa fa-check fa-2x"></i> 
                  </button>
                  
                  <button style="padding-left: 0px;" type="reset" title="Cancelar" data-toggle='tooltip' data-placement='bottom' class="btn btn-link" onclick="history.back();">
                    <i class="fa fa-times fa-2x"></i> 
                  </button>
                </td>
              </form>               
            </div>
          </div>   
          <div class="col-md-1"></div>
        </div>
      </div>
    </div>
  </div>  
</body>
</html>