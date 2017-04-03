<?php 

require 'medoo.php';
require('fpdf.php');

$pdf = new FPDF();


function trimString($str, $len, $sub){
  if (strlen($str) >= $len) return substr($str, 0, $sub). "...";
  else return $str;
}

function createTable($header, $obj){
  $w = [10,45,27,50,27,15,15];
  
  $database = new medoo([
    'database_type' => 'mysql',
    'database_name' => 'bd_gym',
    'server' => 'localhost',
    'username' => 'kecoco',
    'password' => 'Banbastic1',
    'charset' => 'utf8'
    ]);

  date_default_timezone_set('America/Costa_Rica');
  $fechaactual = Date("Y-m-d");

  $data = $database->select(
    "tb_clientes",[
    "[><]tb_mensualidades" => ["id_mensualidad" => "id_mensualidad"],
    ],
    [   
    "tb_clientes.nombre",
    "tb_clientes.id_clientes",
    "tb_clientes.ruta_imagen",
    "tb_clientes.correo",
    "tb_clientes.telefono",
    "tb_clientes.fecha_pago",
    "tb_mensualidades.mensualidad"
    ],[
    "fecha_pago" => "$fechaactual",
    "ORDER" => "nombre",
    ]);
  
  $i=0;
  $dia = Date("Y-m-d");
  $dia=date("d-m-Y",strtotime($dia));
  $obj->Cell('C',10,'Clientes que pagan hoy: '.$dia,0,1,'C');
  $obj->Ln();

  foreach($header as $col){
    $obj->SetFillColor(33,150,243);
    $obj->SetTextColor(0,0,0);
    $obj->Cell($w[$i],10,$col,1,0,'C',true);
    $i++;
  }

  $obj->Ln();
  $color=0;
  $total=1;
  foreach ($data as $row) {
   date_default_timezone_set('America/Costa_Rica');
   $estado ="";
   $fechaactual = Date("Y-m-d");
   if ( $row["fecha_pago"] >= $fechaactual ) 
    $estado = "A";
  else
    $estado = "P";
  $row["fecha_pago"]=date("d-m-Y",strtotime($row["fecha_pago"]));
  $obj->SetFillColor(255,255,255);
  $color++;
  $obj->Cell(10,8,trimString($total, 43, 37), 1,0,'C',true);
  $total++;
  $obj->Cell(45,8,trimString($row["nombre"], 43, 37), 1,0,'L',true);
  $obj->Cell(27,8,trimString($row["telefono"], 43, 20), 1,0,'C',true);
  $obj->Cell(50,8,$row["correo"], 1,0, 'C',true);
  $obj->Cell(27,8,$row["fecha_pago"], 1,0, 'C',true);
  $obj->Cell(15,8,$estado, 1,0, 'C',true);
  $obj->Cell(15,8,$row["mensualidad"], 1,0, 'C',true);
  $obj->Ln();
}
$obj->Output();
}

$pdf->SetTitle('Clientes para hoy');
$pdf->SetFont('Arial','B',10);
$header = ['#','Nombre','Telefono','Correo','Fecha de pago','Estado','Pago'];
$pdf->AddPage();
createTable($header, $pdf);

?>