<?php
session_start();
if (isset($_SESSION['username'])) {
}else{
  header("Location: index.php");
}

require 'medoo.php';

$database = new medoo([
  'database_type' => 'mysql',
  'database_name' => 'bd_gym',
  'server' => 'localhost',
  'username' => 'kecoco',
  'password' => 'Banbastic1',
  'charset' => 'utf8'
  ]);

date_default_timezone_set('America/Costa_Rica');
$hoy = date('Y-m-d');
$fechaactual = Date("Y-m-d");
$cantidad_resultados_por_pagina = 4;

  if (isset($_GET["pagina"])) {//Comprueba si está seteado el GET de HTTP
    if (is_string($_GET["pagina"])) {//Si el GET de HTTP SÍ es una string / cadena, procede    
      if (is_numeric($_GET["pagina"])) { //Si la string es numérica, define la variable 'pagina'
        if ($_GET["pagina"] == 1) {//Si es 1 en lugar de ir a 'index.php?pagina=1' se iría directamente a 'index.php'
        header("Location: pagos.php");
        die();
        }else{ //Si la petición desde la paginación no es para ir a la pagina 1, va a la que sea
          $pagina = $_GET["pagina"];
        };
      }else{ //Si la string no es numérica, redirige al index (por ejemplo: index.php?pagina=AAA)
        header("Location: pagos.php");
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
  <script src="js/jquery-1.12.4.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/main.js"></script>
</head>
<body>
  <?php 
  include("html.php");
  $data = $database->select("tb_pagos", "*",[
    "fecha" => "$fechaactual",
    "LIMIT" => ["$empezar_desde", "$cantidad_resultados_por_pagina"]
    ]);

  $datatotal = $database->select("tb_pagos", "*",[
    "fecha" => "$fechaactual",
    ]);

  $total = $database->sum("tb_pagos", "pago",[
    "fecha" => "$fechaactual",
    ]);
  
  $cantidadtotal = count($datatotal);
  $total_paginas = ceil($cantidadtotal / $cantidad_resultados_por_pagina); 
  ?>
  
  <div class="box-principal">
    <h3 class="titulo"><br><br>Listado de Clientes<hr></h3>
    <div class="panel panel-success">
      <div class="panel-heading">
        <h3 class="panel-title">Pagos de hoy <?php echo count($datatotal); ?>
          <a title='Generar PDF' data-toggle='tooltip' data-placement='bottom' style="margin-right: 15px;" class="navbar-right" href="pdf/pdfpagos.php" target="_blank"><i class="fa fa-file-pdf-o"></i></a>   
        </h3>
      </div>
      <div class="panel-body">
        <table class="table table-striped table-hover ">
          <thead>
            <tr>
              <th>Foto</th>
              <th>Nombre</th>
              <th>Membresia</th>
              <th>Mensualidad</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $cantidad = count($data);
            if($cantidad > 0){
              for($i=0; $i<$cantidad; $i++){
                $data[$i]["fecha_mensualidad"]=date("d-m-Y",strtotime($data[$i]["fecha_mensualidad"]));
                echo "<tr>
                <td><img class='imagen-avatar' src='imagenes/".$data[$i]["ruta_imagen"]."'></td>
                <td style='padding-top: 2.5%; padding-bottom: 0px;'><a title='Ver perfil' data-toggle='tooltip' data-placement='bottom' href='perfil.php? id=".$data[$i]["id_clientes"]."'>".$data[$i]["nombre"]."</td>
                <td style='padding-top: 2.5%; padding-bottom: 0px;'>".$data[$i]["fecha_mensualidad"]."</td>
                <td style='padding-top: 2.5%; padding-bottom: 0px;'>₡".$data[$i]["pago"]."</td>
              </tr>";
            }
          }
          
          echo "<tr>
          <td></td>
          <td></td>
          <td></td>
          <td style='padding-top: 2.5%; padding-bottom: 0px;'>Total = ₡".$total."</td>
        </tr>";
        ?>
      </tbody>
    </table> 
  </div>
  <div>
    <?php 
    if ($cantidadtotal > $cantidad_resultados_por_pagina ) {
      ?>
      <ul class="pagination navbar-right">
        <li><a href="pagos.php">&laquo;</a></li>
        <?php 
        for ($i=1; $i <=$total_paginas ; $i++) { 
          echo "<li><a href='?pagina=".$i."'>" .$i."</a></li>  ";
        }
        ?>
        <li><a href=<?php echo "pagos.php?pagina=".$total_paginas;  ?>>&raquo;</a></li>
      </ul> 
      <?php 
    }
    ?>
  </div>
</div>
</div> <br><br>

<div class="box-principal">
  <div class="panel panel-success">
    <div class="panel-heading">
      <h3 class="panel-title">Registro de pagos</h3>
    </div>
    <div class="panel-body">
      <table class="table table-striped table-hover ">
        <thead>
          <tr>
            <th>Fecha Inicial</th>
            <th>Fecha final</th>
          </tr>
        </thead>
        
        <tbody>
          <tr>
           <form class="form-horizontal" target="_blank" action="pdf/pdfpagostotal.php" method="POST">
            <td><input type="date" name="desde" required></td>
            <td><input type="date" name="hasta" required max=<?php echo $hoy;?> ></td>
            <td>
             <button title="Aceptar" data-toggle='tooltip' data-placement='bottom' type="submit" class="btn btn-link">
              <i class="fa fa-check fa-2x"></i> 
            </button>
            
            <button title="Cancelar" data-toggle='tooltip' data-placement='bottom' class="btn btn-link" type="reset">
              <i class="fa fa-times fa-2x"></i> 
            </button>
          </td>
        </form>
      </tr>
    </tbody>
  </table> 
</div>
</div>
</body>