<?php
    include("./MPDF/mpdf.php");
    //$mpdf=new mPDF('','A4',0,'',12.7,12.7,14,12.7,8,8);
    /*$mpdf = new mPDF('',    // mode - default ''
                    '',    // format - A4, for example, default ''
                    0,     // font size - default 0
                    '',    // default font family
                    15,    // margin_left
                    15,    // margin right
                    16,     // margin top
                    16,    // margin bottom
                    9,     // margin header
                    9,     // margin footer
                    'P');  // L - landscape, P - portrait*/
    // Define a Landscape page size/format by name
    //$mpdf=new mPDF('utf-8', 'A4');

    // Define a page using all default values except "L" for Landscape orientation
    //$mpdf=new mPDF('','', 0, '', 15, 15, 16, 16, 9, 9, 'P');
    $mpdf=new mPDF('utf-8','A4', 0, '', 15,15,20,40,15,35, 'P');

    $mpdf->SetFooter('|Page {PAGENO} of {nb}|{DATE d-m-Y} Powered by GPITG');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
?>