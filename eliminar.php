<?php
session_start();
if (isset($_SESSION['username'])) {
}else{
header("Location: index.php");
}
require  'medoo.php';
date_default_timezone_set('America/Costa_Rica');

$database = new medoo([
  'database_type' => 'mysql',
  'database_name' => 'gym',
  'server' => 'localhost',
  'username' => 'root',
  'password' => 'root',
  'charset' => 'utf8'
]);

if($_GET){
  $data = $database->select("tb_clientes", "*", 
        ["id_clientes" => $_GET["id"]
  ]);
}

if($_POST){
  $database->delete("tb_clientes",
    ["id_clientes" => $_POST["id"]
  ]);

  $database->update("tb_mensualidades", [
    "total_clientes[-]" => 1
  ], [
  "id_mensualidad" => $_POST["mensualidad"]
  ]);
  header("Location: inicio.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>GIMNASIO</title>
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="css/general.css" rel="stylesheet" type="text/css">
  <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php 
  include("html.php");
?>
  <div class="box-principal">
    <h3 class="titulo"><br><br>Eliminar registro<hr></h3>
      <div class="panel panel-success">
        <div class="panel-heading">
          <h3 class="panel-title">Desea eliminar a <?php echo $data[0]["nombre"]." del registro";?></h3>
        </div>
          <div class="panel-body">
            <table class="table table-striped table-hover ">
              <thead>
                <tr>
                  <th>Foto</th>
                  <th>Nombre</th>
                  <th>Fecha de pago</th>
                </tr>
              </thead>
                <tbody> 
                  <form action="eliminar.php" method="post">
                  <?php 

                      $data[0]["fecha_pago"]=date("d-m-Y",strtotime($data[0]["fecha_pago"]));
                    echo "<td><img class='imagen-avatar' src='imagenes/".$data[0]["ruta_imagen"]."'></td>
                          <td style='padding-top: 2.5%; padding-bottom: 0px;' ><a href='perfil.php? id=".$data[0]["id_clientes"]."'></>".$data[0]["nombre"]."</td>
                          <td style='padding-top: 2.5%; padding-bottom: 0px;'>".$data[0]["fecha_pago"]."</td>"; 
                  ?>
                  <input type="hidden" name="mensualidad" value="<?php echo $data[0]["id_mensualidad"]; ?>">
                  <input type="hidden" name="id" value="<?php echo $data[0]["id_clientes"]; ?>">
                    <td style="padding-top: 1.5%; padding-bottom: 0px;">
                                <button title="Aceptar" type="submit" class="btn btn-link">
                                  <i class="fa fa-check fa-2x"></i> 
                                </button>
                                
                                <button style="padding-left: 0px;" title="Cancelar" class="btn btn-link" onclick="history.back();">
                                  <i class="fa fa-times fa-2x"></i> 
                                </button>
                              </td>
                  </form>
                </tbody>
</body>
</html>