<?php
session_start();
if (isset($_SESSION['username'])) {
}else{
header("Location: index.php");
}

require 'medoo.php';
date_default_timezone_set('America/Costa_Rica');
$fechaactual = Date("Y-m-d");
    
  $database = new medoo([
    'database_type' => 'mysql',
    'database_name' => 'gym',
    'server' => 'localhost',
    'username' => 'root',
    'password' => 'root',
    'charset' => 'utf8'
  ]);
        
  $cantidad_resultados_por_pagina = 7;

  if (isset($_GET["pagina"])) {//Comprueba si está seteado el GET de HTTP
    if (is_string($_GET["pagina"])) {//Si el GET de HTTP SÍ es una string / cadena, procede    
      if (is_numeric($_GET["pagina"])) { //Si la string es numérica, define la variable 'pagina'
        if ($_GET["pagina"] == 1) {//Si es 1 en lugar de ir a 'index.php?pagina=1' se iría directamente a 'index.php'
          header("Location: atrasados.php");
          die();
        }else{ //Si la petición desde la paginación no es para ir a la pagina 1, va a la que sea
          $pagina = $_GET["pagina"];
        };
      }else{ //Si la string no es numérica, redirige al index (por ejemplo: index.php?pagina=AAA)
        header("Location: atrasados.php");
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
  <title>GIMNASIO</title>
  <link href="css/bootstrap.css" rel="stylesheet" type="text/css">
  <link href="css/general.css" rel="stylesheet" type="text/css">
  <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
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
                "fecha_pago[<]" => "$fechaactual",
                "ORDER" => "fecha_pago",
                "LIMIT" => ["$empezar_desde", "$cantidad_resultados_por_pagina"]
            ]);
  
  $datatotal = $database->select("tb_clientes", "*",[
                "fecha_pago[<]" => "$fechaactual",
                "ORDER" => "fecha_pago"
              ]);

  $cantidadtotal = count($datatotal);
  $total_paginas = ceil($cantidadtotal / $cantidad_resultados_por_pagina); 

  ?>

  <div class="box-principal">
    <h3 class="titulo"><br><br>Clientes pendientes<hr></h3>
      <div class="panel panel-success">
        <div class="panel-heading">
          <h3 class="panel-title">Total de clientes <?php echo count($datatotal); ?>
          <a style="margin-right: 15px;" class="navbar-right" href="pdf/pdfatrasados.php" target="_blank"><i class="fa fa-file-pdf-o"></i></a>   
          </h3>
        </div>
          <div class="panel-body">
            <table class="table table-striped table-hover ">
              <thead>
                <tr>
                <th>Foto</th>
                <th>Nombre</th>
                <th>Fecha de pago</th>
                <th>Mensualidad</th>
                </tr>
              </thead>
                <tbody>
                  <?php
                    $cantidad = count($data);
                    if($cantidad > 0){  
                      for($i=0; $i<$cantidad; $i++){
                        $data[$i]["fecha_pago"]=date("d-m-Y",strtotime($data[$i]["fecha_pago"]));
                        echo "<tr>
                                <td ><img class='imagen-avatar' src='imagenes/".$data[$i]["ruta_imagen"]."'></td>
                                <td style='padding-top: 2.5%; padding-bottom: 0px;'><a href='perfil.php? id=".$data[$i]["id_clientes"]."'>".$data[$i]["nombre"]."</td>
                                <td style='padding-top: 2.5%; padding-bottom: 0px;'>".$data[$i]["fecha_pago"]."</td>
                                <td style='padding-top: 2.5%;' padding-bottom: 0px;> ₡".$data[$i]["mensualidad"]."</td>
                                <td>
                                <a title='Realizar pago' href='pago.php? id=".$data[$i]["id_clientes"]."'><i style='padding-top:9%; padding-left:15px;' class='fa fa-credit-card-alt fa-lg'></i></a>
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
        
          <?php 
          if ($cantidadtotal > $cantidad_resultados_por_pagina ) {
        ?>
          <ul class="pagination navbar-right">
            <li><a href="atrasados.php">&laquo;</a></li>
        <?php 
          for ($i=1; $i <=$total_paginas ; $i++) { 
            echo "<li><a href='?pagina=".$i."'>" .$i."</a></li>";
          }
        ?>
            <li><a href=<?php echo "atrasados.php?pagina=".$total_paginas;  ?>>&raquo;</a></li>
          </ul> 
        <?php 
          }
        ?>  
      </div>
    </div>
    
</body>