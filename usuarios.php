<?php 
session_start();
require  'medoo.php';

$database = new medoo([
  'database_type' => 'mysql',
  'database_name' => 'bd_gym',
  'server' => 'localhost',
  'username' => 'kecoco',
  'password' => 'Banbastic1',
  'charset' => 'utf8'
  ]);

$user = $database->select("tb_usuarios","*");
$tipo = ""; 
$data = count($user);
for ($i=0; $i <$data ; $i++) { 
  if ($user[$i]["id_usuario"] == $_SESSION['username'] && $user[$i]["tipo"] == "administrador" ) {
    $tipo = $user[$i]["tipo"];
  }
}

if ($tipo == "administrador" ) {

  if (!empty($_POST["usuario"]) && !empty($_POST["pass"]) ) {

    $database->insert("tb_usuarios", [
      "usuario" => $_POST["usuario"],
      "pass" => $_POST["pass"],
      "tipo" => $_POST["tipo"],
      ]);
    header('Location: usuarios.php');
  }
  ?>
  <!DOCTYPE html>
  <html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <title>Configuracion</title>
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
    <h3 class="titulo"><br><br>Usuarios<hr></h3>
    <div class="panel panel-success">
      <div class="panel-heading">
        <h3 class="panel-title">Agregar usuarios</h3>
      </div>
      <div class="panel-body">
        <table class="table table-striped table-hover ">
          <thead>
            <tr>
              <th><i class="fa fa-user "></i> Nombre</th>
              <th><i class="fa fa-key"></i> Contraseña</th>
              <th><i class="fa fa-user-secret"></i> Tipo</th>
            </tr>
          </thead>
          
          <tbody>
            <form class="form-horizontal"  action="" method="POST">
              <td><input class="form-control" name="usuario" type="text"  required></td>
              <td><input  class="form-control" name="pass" type="password"  pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$" title="Es necesario una mayuscula minuscula y un numero" data-toggle='tooltip' data-placement='bottom'  required></td>
              <td>
                <select class="form-control" name="tipo" required>
                  <option></option>
                  <option value="administrador">Administrador</option>
                  <option value="usuario">Usuario</option>
                </select>
              </td>
              <td>
               <button style="padding-left: 0px;" title="Aceptar" data-toggle='tooltip' data-placement='bottom' type="submit" class="btn btn-link">
                <i class="fa fa-check fa-2x"></i> 
              </button>
              
              <button style="padding-left: 0px;" title="Cancelar" data-toggle='tooltip' data-placement='bottom' class="btn btn-link" type="reset">
                <i class="fa fa-times fa-2x"></i> 
              </button>
            </td>
          </form>

        </tbody>
      </table> 
    </div>
  </div>
</div>

<div class="box-principal">
  <div class="panel panel-success">
    <div class="panel-heading">
      <h3 class="panel-title">Editar usuarios</h3>
    </div>
    <div class="panel-body">
      <table class="table table-striped table-hover ">
        <thead>
          <tr>
            <th><i class="fa fa-user "></i> Nombre</th>
            <th><i class="fa fa-user-secret"></i> tipo</th>
          </tr>
        </thead>
        
        <tbody>
          <?php
          $cantidad = count($user);

          if($cantidad > 0){
            for($i=0; $i<$cantidad; $i++){
             echo "<tr>
             <td>".$user[$i]["usuario"]."</td>
             <td>".$user[$i]["tipo"]."</td>
             <td>
              <a title='Editar' data-toggle='tooltip' data-placement='bottom' href='editarUsuario.php? id=".$user[$i]["id_usuario"]."'><i style='padding-left:15px;' class='fa fa-pencil fa-lg'></i></a>";

              if ($user[$i]["tipo"] == "usuario") {
                echo "<a title='Eliminar' data-toggle='tooltip' data-placement='bottom' href='eliminarUsuario.php? id=".$user[$i]["id_usuario"]."'><i style='padding-left:15px;' class='fa fa-trash fa-lg'></i></a>";
              }else{
                echo "<a title='Imposible eliminar a un administrador' data-toggle='tooltip' data-placement='bottom'><i style='padding-left:15px;' class='fa fa-trash fa-lg'></i></a>";
              }
              echo " </td></tr>";
            }    
          }

        }else{
          print("¡Error! No tienes autorizacion para ingresar a esta seccion");
        }
        ?>
      </tbody>
    </table> 
  </div>
</div>
</div>


</body>
</html>