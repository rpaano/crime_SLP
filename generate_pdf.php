<?php 
    require "fpdf181/fpdf.php";
    $db = new PDO('mysql:host=localhost;dbname=case_db','root','');

    $id = $_GET['id'];
    /**
     * 
     */
    class myPDF extends FPDF
    {
        
        function header()
        {
            $this->Image('logo.png',10,6);
            $this->SetFont('Arial','B',14);
            $this->Cell(276,5,'ADMIN DATA',0,0,'C');
            $this->Ln();
            $this->SetFont('Times','',12);
            $this->Cell(276,10,'Canitoan',0,0,'C');
            $this->Ln();
            $this->Ln();
            $this->Ln();
            $this->Ln();
            $this->headerTable();
        }

        function footer(){
            $this->SetY(-15);
            $this->SetFont('Arial','',8);
            $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');


        }

        function headerTable(){
            
            $this->SetFont('Times','',12);
            $this->Cell(45,10,'Date&Time',1,0,'C');
            $this->Cell(40,10,'Brgy. Case Number',1,0,'C');
            $this->Cell(45,10,'Complainant',1,0,'C');
            $this->Cell(45,10,'Respondent',1,0,'C');
            $this->Cell(33,10,'Nature of Case',1,0,'C');
            $this->Cell(38,10,'Disposition/Remarks',1,0,'C');
            $this->Cell(30,10,'Status',1,0,'C');
            $this->Ln();
               
        }

        function viewTable($db,$id){
            $this->SetFont('Times','',10);
            $stmt = $db->query("SELECT * FROM admin INNER JOIN pending_cases on admin.admin_id=pending_cases.admin_id WHERE CONCAT( `date_time`, `brgycasenumber`, `complainant`, `respondent`, `natureofcase`, `disposition_remarks`,`status`) LIKE '%".$id."%'");

            while ($data = $stmt->fetch(PDO::FETCH_OBJ)) {
                $this->Cell(45,10,$data->date_time,1,0,'C');
                $this->Cell(40,10,$data->brgycasenumber,1,0,'C');
                $this->Cell(45,10,$data->complainant,1,0,'L');
                $this->Cell(45,10,$data->respondent,1,0,'L');
                $this->Cell(33,10,$data->natureofcase,1,0,'N');
                $this->Cell(38,10,$data->disposition_remarks,1,0,'L');
                $this->Cell(30,10,$data->status,1,0,'C');
                $this->Ln();
            }
        }
    }

    $pdf = new myPDF();
    $pdf->AliasNbPages();
    $pdf->AddPage('L','A4',0);
    //$pdf->headerTable();
    $pdf->viewTable($db,$id);
    $pdf->Output();
 ?>