<?php
$nombres=$_SESSION['username'];
?>
<!DOCTYPE html>
<html>
<head>
   <link href="css/bootstrap.css" rel="stylesheet" type="text/css">
  	<link href="css/general.css" rel="stylesheet" type="text/css">
  <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
</head>
<body>
	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container-fluid">
	    	<div class="navbar-header">
	      		<a class="navbar-brand" href="inicio.php"><i class="fa fa-home fa-fw"></i>&nbsp; Inicio</a>
	    	</div>
	    		<div style="text-align: center;" class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
     	    		<ul style="float: none; display: inline-block;" class="nav navbar-nav">
								<form class="navbar-form navbar-center" name="buscador" method="get" action="buscar.php">
	        				<div class="form-group">
	          					<input id="buscar" name="buscar" type="search" placeholder="Buscar aquí..."  class="form-control" autofocus>
	        				</div>
	        				<button title="Buscar" type="submit" name="buscador" class="btn btn-default"><i class="fa fa-search fa-lg"></i> </button>
	      				</form>
		    			</ul>
					<ul class="nav navbar-nav navbar-right">
		      <li><a>Hola <?php echo $nombres; ?></a></li>
					</div>
		      </ul>
				</div>
		</div>
	</nav>
</body>
</html>