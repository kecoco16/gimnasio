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

$mensua = $database->select("tb_mensualidades","*");
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

  if (!empty($_POST["usuario"]) && !empty($_POST["pass"]) ) {

    $database->insert("tb_usuarios", [
      "usuario" => $_POST["usuario"],
      "pass" => $_POST["pass"],
      "tipo" => $_POST["tipo"],
      ]);
    header('Location: configuracion.php');
  }else{

    if (!empty($_POST["tipo"]) && !empty($_POST["mensualidad"]) ) {

      $database->insert("tb_mensualidades", [
        "tipo" => $_POST["tipo"],
        "mensualidad" => $_POST["mensualidad"],
        ]);
      header('Location: configuracion.php');
    }
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
  </head>
  <body>
   <?php 
   include("html.php");
   ?>
   <div class="box-principal">
    <h3 class="titulo"><br><br>Configuracion<hr></h3>
    <div class="panel panel-success">
      <div class="panel-heading">
        <h3 class="panel-title">Agregar usuario</h3>
      </div>
      <div class="panel-body">
        <table class="table table-striped table-hover ">
          <thead>
            <tr>
              <th><i class="fa fa-user "></i> Nombre usuario</th>
              <th><i class="fa fa-key"></i> Contraseña</th>
              <th><i class="fa fa-user-secret"></i> Tipo</th>
            </tr>
          </thead>
          
          <tbody>
            <form class="form-horizontal"  action="" method="POST">
              <td><input class="form-control" name="usuario" type="text" pattern="[a-z]+" title="No pueden llevar mayusculas" placeholder="Ejemplo: kevin" required></td>
              <td><input  class="form-control" name="pass" type="password" placeholder="Ejemplo: Kev1"  pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$" title="Es necesario una mayuscula minuscula y un numero"  required></td>
              <td>
                <select class="form-control" name="tipo" required>
                  <option></option>
                  <option value="administrador">Administrador</option>
                  <option value="usuario">Usuario</option>
                </select>
              </td>
              <td>
               <button style="padding-left: 0px;" title="Aceptar" type="submit" class="btn btn-link">
                <i class="fa fa-check fa-2x"></i> 
              </button>
              
              <button style="padding-left: 0px;" title="Cancelar" class="btn btn-link" type="reset">
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
      <h3 class="panel-title">Agregar una mensualidad</h3>
    </div>
    <div class="panel-body">
      <table class="table table-striped table-hover ">
        <thead>
          <tr>
            <th><i class="fa fa-user "></i> Nombre</th>
            <th><i class="fa fa-money"></i> Precio</th>
          </tr>
        </thead>
        
        <tbody>
          <form class="form-horizontal"  action="" method="POST">
            <td><input class="form-control" name="tipo" type="text" placeholder="Ejemplo: Jovenes" required></td>
            <td><input  class="form-control" name="mensualidad" type="int" placeholder="Ejemplo: 10000"  required></td>
            <td>
             <button style="padding-left: 0px;" title="Aceptar" type="submit" class="btn btn-link">
              <i class="fa fa-check fa-2x"></i> 
            </button>
            
            <button style="padding-left: 0px;" title="Cancelar" class="btn btn-link" type="reset">
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
      <h3 class="panel-title">Editar una mensualidad</h3>
    </div>
    <div class="panel-body">
      <table class="table table-striped table-hover ">
        <thead>
          <tr>
            <th><i class="fa fa-user "></i> Nombre</th>
            <th><i class="fa fa-money"></i> Precio</th>
          </tr>
        </thead>
        
        <tbody>
          <?php
          $cantidad = count($mensua);

          if($cantidad > 0){
            for($i=0; $i<$cantidad; $i++){
              echo "<tr>
              <td>".$mensua[$i]["tipo"]."</td>
              <td> ₡".$mensua[$i]["mensualidad"]."</td>
              <td>
                <a title='Editar' href='editarMensualidad.php? id=".$mensua[$i]["id_mensualidad"]."'><i style='padding-left:15px;' class='fa fa-pencil fa-lg'></i></a>
                <a title='Eliminar' href='eliminar.php? id=".$mensua[$i]["id_mensualidad"]."'><i style='padding-left:15px;' class='fa fa-trash fa-lg'></i></a>
              </td>
            </tr>";
          }    
        }

        ?>
      </tbody>
    </table> 
  </div>
</div>
</div>
<div class="box-principal">
  <div class="panel panel-success">
    <div class="panel-heading">
      <h3 class="panel-title">Editar una usuarios</h3>
    </div>
    <div class="panel-body">
      <table class="table table-striped table-hover ">
        <thead>
          <tr>
            <th><i class="fa fa-user "></i> Nombre</th>
          </tr>
        </thead>
        
        <tbody>
          <?php
          $cantidad = count($user);

          if($cantidad > 0){
            for($i=0; $i<$cantidad; $i++){
              echo "<tr>
              <td>".$user[$i]["usuario"]."</td>
              <td>
                <a title='Editar' href='editarUsuario.php? id=".$user[$i]["id_usuario"]."'><i style='padding-left:15px;' class='fa fa-pencil fa-lg'></i></a>
                <a title='Eliminar' href='eliminar.php? id=".$user[$i]["id_usuario"]."'><i style='padding-left:15px;' class='fa fa-trash fa-lg'></i></a>
              </td>
            </tr>";
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