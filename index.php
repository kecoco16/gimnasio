<?php  
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
session_start();
unset($_SESSION["username"]);

if($_POST){
  $usuario = $_POST["usuario"];
  $pass = $_POST["pass"];
  $data = $database->select("tb_usuarios", "*", [
	"usuario" => "$usuario"
  ]);

  $user = count($data);

  for($i=0; $i<$user; $i++){              
    if($data[$i]["pass"] == $pass){
     	$_SESSION["username"] = $usuario;
       	header("Location: inicio.php");
    }else{
    	header("location:index.php");
    }
                    
  }
}

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
	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container-fluid">
	    	<div class="navbar-header">
	      		<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">
	        		<span class="sr-only"></span>
	        		<span class="icon-bar"></span>
	        		<span class="icon-bar"></span>
	        		<span class="icon-bar"></span>
	      		</button>
	      		<a class="navbar-brand" href="index.php"><i class="fa fa-home fa-fw"></i>&nbsp; Inicio</a>
	    	</div>
	    	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
     	    	<ul class="nav navbar-nav"></ul>
			</div>
		</div>
	</nav>
	<div class="box-login">
		<h3 class="titulo"><br><br>Iniciar sesion<hr></h3>
			<div class="panel panel-success">
	  			<div class="panel-heading">
	    			<h3 class="panel-title">Usuario</h3>
	  			</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-1"></div>
								<div class="col-md-10">
									<form class="form-horizontal" action="index.php" method="POST">
										<div class="form-group">
											<label for="inputEmail" class="control-label" ><i class="fa fa-user "></i> Nombre</label>
    									<input class="form-control" name="usuario" type="text" pattern="[a-z]+" title="No pueden llevar mayusculas" required autofocus>
							        <label for="inputEmail" class="control-label"><i class="fa fa-key"></i> Contraseña</label>
							        <input  class="form-control" name="pass" type="password" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$" title="Es necesario una mayuscula minuscula y un numero" required>
										</div>
											<button type="submit" class="btn btn-success">Registrar</button>
											<button type="reset" class="btn btn-warning">Borrar</button>
									</form>
								</div>
							</div>
						</div>
					</div>
                        
			</div>
	</div>
</body>
</html>
