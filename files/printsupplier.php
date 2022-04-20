<?php 
session_start();
include("./includes/connection.php");
$Employee_Name = $_SESSION['userinfo']['Employee_Name'];
$htm = "<table width ='100%' height = '20px'>
<tr><td><img src='./branchBanner/branchBanner.png' width=100%></td></tr>
<tr><td style='text-align: center;'><b><span style='font-size: x-small;'>LIST OF ALL SUPPLIER.</span></b></td></tr></br>
</table>";

        $htm.= '<table width="100%" border="1">
                    <thead>
                        <tr>
                        <td width="5%">SN</td>
                        <td width="25%">Supplier Name</td>
                        <td width="10%">Supplier Email</td>
                        <td>Mobile Number</td>
                        <td>Physical Address</td>
                        <td width="15%">Postal Address</td>
                        <td>Telephone</td>
                        <td>Contact  Person</td>
                        </tr>
                        </thead> ';

                $qry = mysqli_query($conn,"SELECT * FROM tbl_supplier") or die(mysqli_error($conn));
                $sn = 1;
                if(mysqli_num_rows($qry)>0){
                while ($row = mysqli_fetch_array($qry)) {
                    $color = ($sn % 2 != 0 ? 'white' : '');
                    $htm.= '<tr style="background-color:'.$color.'" >';
                    $htm.= '<td>' . $sn++ . '</td>';
                    $htm.= '<td>' . $row['Supplier_Name'] . '</td>';
                    $htm.= '<td>'.$row['Supplier_Email'].'</td>';
                    $htm.= '<td>'.$row['Mobile_Number'].'</td>';
                    $htm.= '<td>'.$row['Physical_Address'].'</td>';
                    $htm.= '<td>'.$row['Postal_Address'].'</td>';
                    $htm.= '<td>'.$row['Telephone'].'</td>';
                    $htm.= '<td>'.$row['Contact_person'].'</td>';
                    $htm.= '</tr>';
                }
            }else{
                $htm.="<tr><td colspan='5'>No result found</td></tr>";
            }

    $htm.= '</table>';


    $htm = mb_convert_encoding($htm, 'UTF-8', 'UTF-8');
    include("./MPDF/mpdf.php");
    $mpdf=new mPDF('','A4', 0, '', 15,15,20,40,15,35, 'P');
    $mpdf->SetFooter('Printed By '.strtoupper($Employee_Name).'|Page {PAGENO} Of {nb}|{DATE d-m-Y} | Powered By GPITG LTD');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();