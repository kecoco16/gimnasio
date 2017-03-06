<?php 
session_start();

require  'medoo.php';

$database = new medoo([
    // required
    'database_type' => 'mysql',
    'database_name' => 'gym',
    'server' => 'localhost',
    'username' => 'root',
    'password' => 'root',
    'charset' => 'utf8'
]);



  $user = $database->select("tb_usuarios","*");
  $usuario = "";
  $tipo = ""; 
  $datas = count($user);

  for ($i=0; $i <$datas ; $i++) { 
    if ($user[$i]["usuario"] == $_SESSION['username'] && $user[$i]["tipo"] == "administrador" ) {
      $usuario = $user[$i]["usuario"];
      $tipo = $user[$i]["tipo"];
    }
  }

  if ($usuario == $_SESSION['username'] && $tipo == "administrador" ) {

    if($_GET){
        $data = $database->select("tb_mensualidades", "*", 
              ["id_mensualidad" => $_GET["id"]

        ]);

    }

    if($_POST){
          $database->delete("tb_mensualidades",
          ["id_mensualidad" => $_POST["id"]
          ]);
          header("Location: mensualidades.php");     
    }

    if ($data[0]["total_clientes"] == 0) {
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
          <h3 class="panel-title">Desea eliminar la mensualidad <?php echo $data[0]["tipo"]." del sistema";?></h3>
        </div>
          <div class="panel-body">
            <table class="table table-striped table-hover ">
              <thead>
                <tr>
                  <th>Tipo</th>
                  <th>Monto</th>
                </tr>
              </thead>
                <tbody> 
                  <form action="eliminarMensualidad.php" method="post">
                  <?php 
                    echo "<td style='padding-top: 2%;'>".$data[0]["tipo"]."</td>
                          <td style='padding-top: 2%;'>".$data[0]["mensualidad"]."</td>"; 
                  ?>
                  <input type="hidden" name="id" value="<?php echo $data[0]["id_mensualidad"]; ?>">
                    <td>
                                <button title="Aceptar" type="submit" class="btn btn-link">
                                  <i class="fa fa-check fa-lg"></i> 
                                </button>
                                
                                <button title="Cancelar" class="btn btn-link" onclick="history.back();">
                                  <i class="fa fa-times fa-lg"></i> 
                                </button>
                              </td>
                  </form>
                </tbody>
                <?php 
              }else{
                print("¡Error! La mensualidad con este id no se puede eliminar debido a que esta en uso");
              }
    }else{
      print("¡Error! No tienes autorizacion para ingresar a esta seccion");
    }
  ?>
</body>
</html>