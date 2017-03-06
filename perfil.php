  <?php
session_start();
if (isset($_SESSION['username'])) {
}else{
header("Location: index.php");
}
date_default_timezone_set('America/Costa_Rica');

require 'medoo.php';
    
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
                "tb_clientes.correo",
                "tb_clientes.fecha_inicio",
                "tb_clientes.id_clientes",
                "tb_clientes.ruta_imagen",
                "tb_clientes.fecha_pago",
                "tb_mensualidades.mensualidad"
            ],[
            "id_clientes" => $_GET["id"]
            ]);
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
</head>
<body>
  <?php 
  include("html.php");
  ?>
  
  <div class="box-principal">
    <h3 class="titulo"><br><br>Perfil del cliente<hr></h3>
      <div class="panel panel-success">
        <div class="panel-heading">
          <h3 class="panel-title">Perfil de <?php
                                              $cantidad = count($data);
                                              for($i=0; $i<$cantidad; $i++){
                                                echo $data[$i]["nombre"];
                                              }
                                            ?>
          </h3>
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
              </div>
                <div class="col-md-9">
          	      <ul class="list-group">
          	        <?php
                      $cantidad = count($data);
                      if($cantidad > 0){
                        for($i=0; $i<$cantidad; $i++){
                          $estado ="";
                          $fechaactual = Date("Y-m-d");
                            if ( $data[$i]["fecha_pago"] >= $fechaactual ) 
                              $estado = "Activo";
                            else
                              $estado = "Pendiente";
                          date_default_timezone_set('America/Costa_Rica');
                          $data[$i]["fecha_inicio"]=date("d-m-Y",strtotime($data[$i]["fecha_inicio"]));
                          $data[$i]["fecha_pago"]=date("d-m-Y",strtotime($data[$i]["fecha_pago"]));
                          
                          echo "
                                  <li class='list-group-item'>Nombre: ".$data[$i]["nombre"]."</li>
                                  <li class='list-group-item'>Fecha de inicio: ".$data[$i]["fecha_inicio"]."</li>
                                  <li class='list-group-item'>Fecha de pago: ".$data[$i]["fecha_pago"]."</li>
                                  <li class='list-group-item'>Telefono: ".$data[$i]["telefono"]."</li>
                                  <li class='list-group-item'>Estado: ".$estado."</li>
                                  <li class='list-group-item'>Mensualidad: ₡".$data[$i]["mensualidad"]."</li>
                                  <li class='list-group-item'>Correo: ".$data[$i]["correo"]."</li>
                                  <tr>
                                    <td style= 'text-aling:center;'>
                                    <a style='padding-top:16px; padding-left:30%;'></a>
                                      <a title='Realizar pago' href='pago.php? id=".$data[$i]["id_clientes"]."'><i style='padding-top:16px; ' class='fa fa-credit-card-alt fa-3x'></i></a>
                                      <a title='Editar' href='editar.php? id=".$data[$i]["id_clientes"]."'><i style='padding-left:15px;' class='fa fa-pencil fa-3x'></i></a>
                                      <a title='Eliminar' href='eliminar.php? id=".$data[$i]["id_clientes"]."'><i style='padding-left:15px;' class='fa fa-trash fa-3x'></i></a>
                                    </td>
                                  </tr>
                                ";
                        }
                      }
                    ?>
                  </ul>
                </div>
            </div>
          </div>
      </div>
  </div>		 
</body>