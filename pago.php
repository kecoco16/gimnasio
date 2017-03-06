<?php
session_start();
if (isset($_SESSION['username'])) {
}else{
header("Location: index.php");
}
$user=$_SESSION['username']; 

require  'medoo.php';

$database = new medoo([
  'database_type' => 'mysql',
  'database_name' => 'gym',
  'server' => 'localhost',
  'username' => 'root',
  'password' => 'root',
  'charset' => 'utf8'
]);

if($_GET){  
  
  $data = $database->select(
                "tb_clientes",[
                "[><]tb_mensualidades" => ["id_mensualidad" => "id_mensualidad"],
            ],
            [   
                "tb_clientes.nombre",
                "tb_clientes.telefono",
                "tb_clientes.id_clientes",
                "tb_clientes.ruta_imagen",
                "tb_clientes.fecha_pago",
                "tb_mensualidades.mensualidad"
            ],[
            "id_clientes" => $_GET["id"]
            ]);
}

if($_POST){
  date_default_timezone_set('America/Costa_Rica');
  $fechaactual = Date("Y-m-d");

  $database->update("tb_clientes",[
    "fecha_pago" => $_POST["fecha_pago"]],
    ["id_clientes" => $_POST["id"]]);

    $database->insert("tb_pagos", [
      "usuario" => "$user",
      "id_clientes" => $_POST["id"],
      "telefono" => $_POST["telefono"],
      "fecha" => "$fechaactual",
      "fecha_mensualidad"=>$_POST["fecha_mensualidad"],
      "pago"=>$_POST["mensualidad"],
      "ruta_imagen"=>$_POST["ruta_imagen"],
      "nombre"=>$_POST["nombre"],
    ]);
    
    header('Location: inicio.php');
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
    <h3 class="titulo"><br><br>Confirmar pago<hr></h3>
      <div class="panel panel-success">
        <div class="panel-heading">
          <h3 class="panel-title">Pago de <?php echo $data[0]["nombre"]; ?> </h3>
        </div>
          <div class="panel-body">
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th>Foto</th>
                  <th>Nombre</th>
                  <th>Fecha pago</th>
                  <th>Proximo pago</th>
                  <th>Mensualidad</th>
                </tr>
              </thead>
                <tbody>
                  <?php 
                    date_default_timezone_set('America/Costa_Rica');
                    $actual = $data[0]["fecha_pago"];
                    $nuevafecha = strtotime ( '+1 month' , strtotime ( $actual ) ) ;
                    $nuevafecha = date ( 'Y-m-d' , $nuevafecha );
                    $fecha_muestra = date("d-m-Y",strtotime($nuevafecha));
                    $fecha=date("d-m-Y",strtotime($data[0]["fecha_pago"]));
                  ?>
                    <form action="pago.php" method="post">
                      <?php 
                        echo "<td><img class='imagen-avatar' src='imagenes/".$data[0]["ruta_imagen"]."'></td>"; 
                      ?>
                        <td style="padding-top: 2.5%; padding-bottom: 0px;" ><?php echo $data[0]["nombre"]?></td>
                        <td style="padding-top: 2.5%; padding-bottom: 0px;" ><?php echo $fecha?></td>
                        <td style="padding-top: 2.5%; padding-bottom: 0px;" ><?php echo $fecha_muestra?></td>
                        <td style="padding-top: 2.5%; padding-bottom: 0px;" > <?php echo "₡".$data[0]['mensualidad']?></td>
                        <input type="hidden" name="ruta_imagen" value="<?php echo $data[0]["ruta_imagen"]; ?>">
                        <input type="hidden" name="telefono" value="<?php echo $data[0]["telefono"]; ?>">
                        <input type="hidden" name="nombre" value="<?php echo $data[0]["nombre"]; ?>">
                        <input type="hidden" name="fecha_mensualidad" value="<?php echo $data[0]["fecha_pago"]; ?>">
                        <input type="hidden" name="mensualidad" value="<?php echo $data[0]["mensualidad"]; ?>">
                        <input type="hidden" name="id" value="<?php echo $data[0]["id_clientes"]; ?>">
                        <td><input type="hidden" name="fecha_pago" value="<?php echo $nuevafecha; ?>"></td>
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
            </table>    
          </div>      
      </div>
  </div>
</body>
</html>