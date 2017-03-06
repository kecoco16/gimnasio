<?php 

require 'medoo.php';
require('fpdf.php');

$pdf = new FPDF();


	function trimString($str, $len, $sub){
		if (strlen($str) >= $len) return substr($str, 0, $sub). "...";
		else return $str;
	}

	function createTable($header, $obj){
		$w = [10,50,27,27,27,27,27];
		
		$database = new medoo([
		    'database_type' => 'mysql',
		    'database_name' => 'gym',
		    'server' => 'localhost',
		    'username' => 'root',
		    'password' => 'root'
		]);
		$desde = $_POST["desde"];
		$hasta = $_POST["hasta"];

		$data = $database->select("tb_pagos", "*",[
			"fecha[<>]" => ["$desde", "$hasta"],
			"ORDER" => "fecha"
			]);	

		$totaal = $database->sum("tb_pagos", "pago",[
          "fecha[<>]" => ["$desde", "$hasta"]
        ]);
        
          
		$i=0;
		date_default_timezone_set('America/Costa_Rica');
		$dia = Date("Y-m-d");
		$dia=date("d-m-Y",strtotime($dia));
        $obj->Cell('C',10,'Historial de pagos',0,1,'C');
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
		$totaal=0;
			foreach ($data as $row) {
			$row["fecha_mensualidad"]=date("d-m-Y",strtotime($row["fecha_mensualidad"]));
			$row["fecha"]=date("d-m-Y",strtotime($row["fecha"]));
			$obj->SetFillColor(255,255,255);
            $color++;
            $obj->Cell(10,8,trimString($total, 43, 37), 1,0,'C',true);
            $total++;
            $totaal= $totaal+$row["pago"];
			$obj->Cell(50,8,trimString($row["nombre"], 43, 20), 1,0,'C',true);
			$obj->Cell(27,8,$row["telefono"], 1,0, 'C',true);
			$obj->Cell(27,8,$row["fecha"], 1,0, 'C',true);
			$obj->Cell(27,8,$row["fecha_mensualidad"], 1,0, 'C',true);
			$obj->Cell(27,8,$row["usuario"], 1,0, 'C',true);
			$obj->Cell(27,8,$row["pago"], 1,0, 'C',true);
			$obj->Ln();
		}
      	$obj->Cell('C',10,'Total: '.$totaal,0,1,'C');
      	$obj->Output();
    }

  $pdf->SetTitle('Historial de pagos');
	$pdf->SetFont('Arial','B',10);
	$header = ['#','Nombre','Telefono','Fecha del pago','Membresia','Usuario','Mensualidad'];
	$pdf->AddPage();
	createTable($header, $pdf);
?>