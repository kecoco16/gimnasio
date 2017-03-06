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
$data = count($user);
for ($i=0; $i <$data ; $i++) { 
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
  $database->update("tb_usuarios", [
    "usuario" => $_POST["usuario"],
    "pass" => $_POST["pass"],
    "tipo" => $_POST["tipo"],
  ],
  
  ["id_usuario" => $_POST["id"]]);

  header('Location: usuarios.php');
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Editar pass</title>
  <link href="css/bootstrap.css" rel="stylesheet" type="text/css">
  <link href="css/general.css" rel="stylesheet" type="text/css">
  <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
</head>
 <?php 
  include("html.php");
  ?>
  <div class="box-principal">
    <h3 class="titulo"><br><br>Editar Usuario<hr></h3>
      <div class="panel panel-success">
        <div class="panel-heading">
          <h3 class="panel-title">Perfil de  <?php echo $data[0]["usuario"];?></h3>
        </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-md-1"></div>
                  <div class="col-md-9">
                    <ul class="list-group">
                        <div class="form-group">
                         <form class="form-horizontal" action="editarUsuario.php" method="POST">
                          <label class="control-label">Nombre</label>
                          <input class="form-control" name="usuario" type="text" required value="<?php echo $data[0]["usuario"]; ?>">
                          <label class="control-label">Contraseña</label>
                          <input class="form-control" type="password" name="pass" required  pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$" title="Es necesario una mayuscula minuscula y un numero" value="<?php echo $data[0]["pass"]; ?>">
                          <td>
                          <label class="control-label">Tipo</label>
                              <select class="form-control" name="tipo" required>
                              <option></option>
                              <option value="administrador">Administrador</option>
                              <option value="usuario">Usuario</option>
                              </select>
                          </td>
                          <input type="hidden" name="id" value="<?php echo $data[0]["id_usuario"]; ?>">
                        </div>
                              <td >
                                <button style="padding-left: 0px;" title="Aceptar" type="submit" class="btn btn-link">
                                  <i class="fa fa-check fa-2x"></i> 
                                </button>
                                
                                <button style="padding-left: 0px;" title="Cancelar" class="btn btn-link" onclick="history.back();">
                                  <i class="fa fa-times fa-2x"></i> 
                                </button>
                              </td>
                      </form>               
                  </div>
              </div>   
            </div>
      </div>
    </div>
  </div>  
   <?php 
    }else{
      print("¡Error! No tienes autorizacion para ingresar a esta seccion");
    }
  ?>
<body>

</body>
</html>