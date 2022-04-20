      <link rel="stylesheet" href="style.css" media="screen">
    <!--[if lte IE 7]><link rel="stylesheet" href="style.ie7.css" media="screen" /><![endif]-->
    <link rel="stylesheet" href="style.responsive.css" media="all"> 
 
 <script src="jquery.js"></script>
    <script src="script.js"></script>
    <script src="script.responsive.js"></script>
    
<?php include("./includes/connection.php"); ?>


<?php
    if(isset($_GET['Employee_ID'])){
        $Employee_ID = $_GET['Employee_ID'];
    } 
?>


<br/>
<center>
    <table width=30% border=1 style='border: inherit;'>
        <!--<tr>
            <td width=70%><b>CLINIC NAME</b></td> 
        </tr>-->
<?php
    
    $is_hr=((isset($_GET['HRWork']) && $_GET['HRWork']=='true')?'&HRWork=true':'');
    $Select_Assigned_Clinic = mysqli_query($conn,"select * from tbl_clinic c, tbl_clinic_employee ce,tbl_employee e
                                where c.clinic_id = ce.clinic_id and
                                ce.employee_id = e.employee_id and e.employee_id = '$Employee_ID'");
    
    while($row = mysqli_fetch_array($Select_Assigned_Clinic)){
                    echo '<tr><td>'.strtoupper($row['Clinic_Name']).'</td>';
                    echo '<td style="text-align: center;"><a href="removeaccessclinic.php?Employee_ID='.$row['Employee_ID'].'&Clinic_ID='.$row['Clinic_ID'].$is_hr.'" target="_parent" class="art-button-green"><b>REMOVE</b></td></tr>';   
            }

?>
       
    </table>
</center>