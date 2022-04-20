<?php
    include("./includes/connection.php");
    if(isset($_GET['Patient_Name'])){
        $Patient_Name = $_GET['Patient_Name'];
    }else{
        $Patient_Name = '';
    }
    $pdf = '<center><table width =100% border=1>';
    $pdf.= '<tr><td><b>PATIENT NAME</b></td>
                <td><b>REG NUMBER</b></td>
                <td><b>SPONSOR</b></td>
                    <td><b>AGE</b></td>
                        <td><b>GENDER</b></td>
                            <td><b>PHONE NUMBER</b></td>
                                <td><b>RESIDENCE</b></td>
                                <td><b>EMERGENCY CONTACT</b></td>
                                </tr>';
    $select_Patients = mysqli_query($conn,"select Old_Registration_Number,Title,First_Name,
                                Second_Name,Last_Name,Date_Of_Birth,
                                    Gender,pr.Region,pr.District,pr.Ward,
                                        Member_Number,Member_Card_Expire_Date,
                                            pr.Phone_Number,Email_Address,Occupation,
                                                Employee_Vote_Number,Emergence_Contact_Name,
                                                    Emergence_Contact_Number,Company,Registration_ID
                                                        Employee_ID,Registration_Date_And_Time,Guarantor_Name,
                                                        Registration_ID
                                      from tbl_patient_registration pr, tbl_sponsor sp WHERE pr.Sponsor_ID = sp.Sponsor_ID") or die(mysqli_error($conn));
    while($row = mysqli_fetch_array($select_Patients)){
        $pdf.= "<tr><td>".$row['First_Name']." ".$row['Second_Name']." ".$row['Last_Name']."</a></td>";
        $pdf.= "<td>".$row['Registration_ID']."</td>";
        $pdf.= "<td>".$row['Guarantor_Name']."</td>";
        $pdf.= "<td>".$row['Date_Of_Birth']."</td>";
        $pdf.= "<td>&nbsp;".$row['Gender']."</td>";
        $pdf.= "<td>&nbsp;".$row['Phone_Number']."</td>";
        $pdf.= "<td>&nbsp;".$row['Ward']."-".$row['Region']."</td>";
        $pdf.= "<td>&nbsp;".$row['Emergence_Contact_Number']."</td>";
    }
    $pdf.= "</tr></table></center>";
    include("MPDF/mpdf.php");

    $mpdf=new mPDF('Registered_Patients'); 

    $mpdf->WriteHTML($pdf);
    $mpdf->Output();
    exit;
?>