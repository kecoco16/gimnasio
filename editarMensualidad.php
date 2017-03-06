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
if($_GET){
 $data = $database->select("tb_mensualidades", "*", 
        ["id_mensualidad" => $_GET["id"]
  ]);
}
if($_POST){

  $database->update("tb_mensualidades", [
    "tipo" => $_POST["tipo"],
    "mensualidad" => $_POST["mensualidad"],
  ],
  
  ["id_mensualidad" => $_POST["id"]]);

  header('Location: mensualidades.php');
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Editar Mensualidad</title>
  <link href="css/bootstrap.css" rel="stylesheet" type="text/css">
  <link href="css/general.css" rel="stylesheet" type="text/css">
  <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
</head>
 <?php 
  include("html.php");
  ?>
  <div class="box-principal">
    <h3 class="titulo"><br><br>Editar Mensualidad<hr></h3>
      <div class="panel panel-success">
        <div class="panel-heading">
          <h3 class="panel-title">Mensualidad  <?php                                                 echo $data[0]["tipo"];
                                              
                                            ?>
          </h3>
        </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-md-1"></div>
                  <div class="col-md-9">
                    <ul class="list-group">
                      
                        <div class="form-group">
                         <form class="form-horizontal" action="editarMensualidad.php" method="POST">
                          <label class="control-label">Nombre</label>
                          <input class="form-control" name="tipo" type="text" required value="<?php echo $data[0]["tipo"]; ?>">
                          <label class="control-label">Precio</label>
                          <input class="form-control" type="int" name="mensualidad" required  value="<?php echo $data[0]["mensualidad"]; ?>">
                          <input type="hidden" name="id" value="<?php echo $data[0]["id_mensualidad"]; ?>">
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