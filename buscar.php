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

$nombre = trim($_GET['buscar']);
$cantidad_resultados_por_pagina = 7 ;

  if (isset($_GET["pagina"])) {//Comprueba si está seteado el GET de HTTP
    if (is_string($_GET["pagina"])) {//Si el GET de HTTP SÍ es una string / cadena, procede    
      if (is_numeric($_GET["pagina"])) { //Si la string es numérica, define la variable 'pagina'
        if ($_GET["pagina"] == 1) {//Si es 1 en lugar de ir a 'index.php?pagina=1' se iría directamente a 'index.php'
        header("Location: buscar.php");
        die();
        }else{ //Si la petición desde la paginación no es para ir a la pagina 1, va a la que sea
          $pagina = $_GET["pagina"];
        };
      }else{ //Si la string no es numérica, redirige al index (por ejemplo: index.php?pagina=AAA)
        header("Location: buscar.php");
        die();
      };
    };
  }else{ //Si el GET de HTTP no está seteado, lleva a la primera página (puede ser cambiado al index.php o lo que sea)
    $pagina = 1;
  };

$empezar_desde = ($pagina-1) * $cantidad_resultados_por_pagina;//Define el número 0 para empezar a paginar multiplicado por la cantidad de resultados por página
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
  <script type="text/javascript" src="js/jquery-1.12.4.min.js"></script>
  <script type="text/javascript">
    $(window).load(function () {
  // Una vez se cargue al completo la página desaparecerá el div "cargando"
  $('#cargando').hide();
});
</script>
</head>
<body>
  <?php 
  include("html.php");
  
  $data = $database->select(
    "tb_clientes",[
    "[><]tb_mensualidades" => ["id_mensualidad" => "id_mensualidad"],
    ],
    [   
    "tb_clientes.nombre",
    "tb_clientes.id_clientes",
    "tb_clientes.ruta_imagen",
    "tb_clientes.fecha_pago",
    "tb_mensualidades.mensualidad"
    ],[
    "nombre[~]" => "$nombre",
    "ORDER" => "nombre",
    "LIMIT" => ["$empezar_desde", "$cantidad_resultados_por_pagina"]
    ]);

  $datatotal =  $database->select("tb_clientes", "*", [
    "nombre[~]" => "$nombre"
    ]);

  $cantidadtotal = count($datatotal);
  $total_paginas = ceil($cantidadtotal / $cantidad_resultados_por_pagina); 
  ?>

  <div class="box-principal">
    <h3 class="titulo"><br><br>Listado de Clientes<hr></h3>
    <div class="panel panel-success">
      <div id="cargando"  class="panel panel-success">
        <div class="panel-heading">
          <h3 style="text-align: center;" class="panel-title">Cargando...<i class="fa fa-spinner fa-spin fa-lg fa-fw"></i></h3>
        </div>
      </div>
      <div class="panel-heading">
        <h3 class="panel-title"><?php if (count($datatotal) == 1) {
          echo "Un resultado con ".$nombre;
        }else
        echo count($datatotal). " resultados con " .$nombre; ?>
      </h3>
    </div>
    <div class="panel-body">
      <table class="table table-striped table-hover ">
        <thead>
          <tr>
            <th>Foto</th>
            <th>Nombre</th>
            <th id='fecha_pago'>Fecha de pago</th>
            <th>Mensualidad</th>
          </tr>
        </thead>
        
        <tbody>
          <?php
          $cantidad = count($data);
          if($cantidad > 0){
            for($i=0; $i<$cantidad; $i++){
              date_default_timezone_set('America/Costa_Rica');
              $data[$i]["fecha_pago"]=date("d-m-Y",strtotime($data[$i]["fecha_pago"]));
              echo "<tr>
              <td><img class='imagen-avatar' src='imagenes/".$data[$i]["ruta_imagen"]."'></td>
              <td style='padding-top: 2.5%; padding-bottom: 0px;'><a href='perfil.php? id=".$data[$i]["id_clientes"]."'>".$data[$i]["nombre"]."</td>
              <td id='fecha_pago' style='padding-top: 2.5%; padding-bottom: 0px;'>".$data[$i]["fecha_pago"]."</td>
              <td style='padding-top: 2.5%; padding-bottom: 0px;'> ₡".$data[$i]["mensualidad"]."</td>
              <td>
                <a title='Realizar pago' href='pago.php? id=".$data[$i]["id_clientes"]."'><i style='padding-top:16px; padding-left:15px;' class='fa fa-credit-card-alt fa-lg'></i></a>
                <a title='Editar' href='editar.php? id=".$data[$i]["id_clientes"]."'><i style='padding-left:15px;' class='fa fa-pencil fa-lg'></i></a>
                <a title='Eliminar' href='eliminar.php? id=".$data[$i]["id_clientes"]."'><i style='padding-left:15px;' class='fa fa-trash fa-lg'></i></a>
              </td>
            </tr>";
          }
        }   
        ?>
      </tbody>
    </table> 
  </div>
  <div>
  </div>

  <?php 
  if ($cantidadtotal > $cantidad_resultados_por_pagina ) {
    ?>
    <ul class="pagination navbar-right">
      <li><a href=<?php echo "buscar.php?&buscar=" .$nombre;  ?>>&laquo;</a></li>
      <li><a href=<?php echo "buscar.php?&buscar=" .$nombre;  ?>>1</a></li>
      <?php 
      for ($i=2; $i <=$total_paginas ; $i++) {       
        echo "<li><a href='buscar.php?pagina=".$i."&buscar=" .$nombre."'>" .$i."</a></li>";
      }
      ?>
      <li><a href=<?php echo "buscar.php?pagina=".$total_paginas."&buscar=" .$nombre;  ?>>&raquo;</a></li>
    </ul> 
    <?php 
  }
  ?>
</body>