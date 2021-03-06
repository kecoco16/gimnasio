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

$cantidad_resultados_por_pagina = 7;

  if (isset($_GET["pagina"])) {//Comprueba si está seteado el GET de HTTP
    if (is_string($_GET["pagina"])) {//Si el GET de HTTP SÍ es una string / cadena, procede    
      if (is_numeric($_GET["pagina"])) { //Si la string es numérica, define la variable 'pagina'
        if ($_GET["pagina"] == 1) {//Si es 1 en lugar de ir a 'index.php?pagina=1' se iría directamente a 'index.php'
        header("Location: listado.php");
        die();
        }else{ //Si la petición desde la paginación no es para ir a la pagina 1, va a la que sea
          $pagina = $_GET["pagina"];
        };
      }else{ //Si la string no es numérica, redirige al index (por ejemplo: index.php?pagina=AAA)
        header("Location: listado.php");
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
  <script type="text/javascript">
    $(window).load(function () {
  // Una vez se cargue al completo la página desaparecerá el div "cargando"
  $('#cargando').hide();
});
</script>
</head>
<body>
 <script src="js/jquery-1.12.4.min.js"></script>
 <script src="js/bootstrap.min.js"></script>
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
  "ORDER" => "nombre",
  "LIMIT" => ["$empezar_desde", "$cantidad_resultados_por_pagina"]
  ]);

 $datatotal = $database->select("tb_clientes", "*");

 $cantidadtotal = count($datatotal);
 $total_paginas = ceil($cantidadtotal / $cantidad_resultados_por_pagina); 
 ?>
 <div id="cargando">Cargando...</div>
 <div class="box-principal">
  <h3 class="titulo"><br><br>Listado de Clientes<hr></h3>
  <div class="panel panel-success">
    <div class="panel-heading">
      <h3 class="panel-title">Total de clientes <?php echo count($datatotal); ?>
        <a style="margin-right: 15px;" class="navbar-right" href="pdf/pdflistado.php" target="_blank"><i class="fa fa-file-pdf-o"></i></a>   
      </h3>
    </div>
    <div class="panel-body">
      <table class="table table-striped table-hover ">
        <thead>
          <tr>
            <th>Foto</th>
            <th>Nombre</th>
            <th id="fecha_pago">Fecha Pago</th>
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
                <a data-toggle='modal' data-target='#miModal' title='Realizar pago' id=".$data[$i]["id_clientes"]."'><i style='padding-top:9%; padding-left:10px;' class='fa fa-credit-card-alt fa-lg'></i></a>
                <a title='Editar' href='editar.php? id=".$data[$i]["id_clientes"]."'><i style='padding-left:10px;' class='fa fa-pencil fa-lg'></i></a>
                <a title='Eliminar' href='eliminar.php? id=".$data[$i]["id_clientes"]."'><i style='padding-left:10px;' class='fa fa-trash fa-lg'></i></a>
              </td>
            </tr>";
            ?>
            <div class="modal fade" id="miModal">
             <div class="modal-dialog">
               <div class="modal-content">
                <div class="modal-header">
                  <button class="close" aria-hidden="true" data-dismiss="modal">&times;</button>
                  <h4>Confirmar Pago</h4>
                </div>
                <div class="modal-body">
                  <?php 
                  echo "";
                  ?>
                </div>
                <div class="modal-footer">
                  <button class="btn btn-primary">Aceptar</button>
                  <button class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php 
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
    <li><a href="listado.php">&laquo;</a></li>
    <?php 
    for ($i=1; $i <=$total_paginas ; $i++) { 
      echo "<li><a href='?pagina=".$i."'>" .$i."</a></li>  ";
    }
    ?>
    <li><a href=<?php echo "listado.php?pagina=".$total_paginas;  ?>>&raquo;</a></li>
  </ul> 
  <?php 
}
?>
</div>
</div>
</body>