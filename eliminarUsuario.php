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
        $data = $database->select("tb_usuarios", "*", 
              ["id_usuario" => $_GET["id"]

        ]);

    }

    if($_POST){
          $database->delete("tb_usuarios",
          ["id_usuario" => $_POST["id"]
          ]);
          header("Location: usuarios.php");     
    }

    if ($data[0]["tipo"] == "usuario") {
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
          <h3 class="panel-title">Desea eliminar al usuario <?php echo $data[0]["usuario"]." del sistema";?></h3>
        </div>
          <div class="panel-body">
            <table class="table table-striped table-hover ">
              <thead>
                <tr>
                  <th>Nombre</th>
                  <th>tipo</th>
                </tr>
              </thead>
                <tbody> 
                  <form action="eliminarUsuario.php" method="post">
                  <?php 
                    echo "<td style='padding-top: 2%;'>".$data[0]["usuario"]."</td>
                          <td style='padding-top: 2%;'>".$data[0]["tipo"]."</td>"; 
                  ?>
                  <input type="hidden" name="id" value="<?php echo $data[0]["id_usuario"]; ?>">
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
                print("¡Error! El usuario con este id es administrador y no se puede borrar");
              }
    }else{
      print("¡Error! No tienes autorizacion para ingresar a esta seccion");
    }
  ?>
</body>
</html>