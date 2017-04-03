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
    "tb_clientes.telefono",
    "tb_clientes.id_clientes",
    "tb_clientes.ruta_imagen",
    "tb_clientes.fecha_pago",
    "tb_clientes.correo",
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


  $f_pago=date("d-m-Y",strtotime($fechaactual));
  $para  = $_POST["correo"]; 
  $nombre = $_POST["nombre"];
  $total = $_POST["mensualidad"];

        // título
  $título = 'Comprobante de pago';
      // mensaje
  $mensaje = '
  <html>
  <head>
    <title></title>
  </head>
  <body>
    <tr>
      <td bgcolor="#fcfcfc" colspan="3">
        <div style="padding:10px">
          <div style="max-width:400px;margin:0 auto"><span class="im">
            <h1 style="color:#2c3e50">
              Gracias! Es un placer contar con su apoyo.
            </h1>
            <p>
              Este es un comprobante de pago por un mes en nuestras instalaciones.
            </p>
            <p>
              <strong>Fecha de pago:</strong>
              <span>'.$f_pago.'</span>
            </p>
            <table>
              <thead>
                <tr>
                  <td style="padding:5px;background-color:#eee;color:#009acf">Nombre</td>
                  <td style="padding:5px;background-color:#eee;color:#009acf;width:100px">Precio</td>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td style="padding:5px;border:solid #eee">'.$nombre.'</td>
                  <td style="padding:5px;border:solid #eee;width:100px">'.$total.'</td>
                </tr>
              </tbody>
            </table>
          </span><div style="margin-top:3em">
        </div>
      </div>

    </div>
  </td>
</tr>
</body>
</html>
';

      // Para enviar un correo HTML, debe establecerse la cabecera Content-type
$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

      // Cabeceras adicionales
$cabeceras .= 'To: '.$nombre.' <'.$correo.'>' . "\r\n";
$cabeceras .= 'From: CcdrOrotina <info@ccdrorotina.com>' . "\r\n";

      // Enviarlo
mail($para, $título, $mensaje, $cabeceras);

header('Location: inicio.php');
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
              <th id="fecha_pago" >Fecha pago</th>
              <th id="fecha_pago">Proximo pago</th>
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
              <td id="fecha_pago" style="padding-top: 2.5%; padding-bottom: 0px;" ><?php echo $fecha?></td>
              <td id="fecha_pago" style="padding-top: 2.5%; padding-bottom: 0px;" ><?php echo $fecha_muestra?></td>
              <td style="padding-top: 2.5%; padding-bottom: 0px;" > <?php echo "₡".$data[0]['mensualidad']?></td>
              <input type="hidden" name="ruta_imagen" value="<?php echo $data[0]["ruta_imagen"]; ?>">
              <input type="hidden" name="correo" value="<?php echo $data[0]["correo"]; ?>">
              <input type="hidden" name="telefono" value="<?php echo $data[0]["telefono"]; ?>">
              <input type="hidden" name="nombre" value="<?php echo $data[0]["nombre"]; ?>">
              <input type="hidden" name="fecha_mensualidad" value="<?php echo $data[0]["fecha_pago"]; ?>">
              <input type="hidden" name="mensualidad" value="<?php echo $data[0]["mensualidad"]; ?>">
              <input type="hidden" name="id" value="<?php echo $data[0]["id_clientes"]; ?>">
              <td><input type="hidden" name="fecha_pago" value="<?php echo $nuevafecha; ?>"></td>
              <td style="padding-top: 1.5%; padding-bottom: 0px; ">
                <button style="padding-left: 0px;" title="Aceptar" data-toggle='tooltip' data-placement='bottom' type="submit" class="btn btn-link">
                  <i class="fa fa-check fa-2x"></i> 
                </button>
                <button  style="padding-left: 0px;" title="Cancelar" data-toggle='tooltip' data-placement='bottom' type="reset" class="btn btn-link" onclick="history.back();">
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