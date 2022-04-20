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
    if(isset($_POST['Employee_ID'])){
        $Employee_ID = $_POST['Employee_ID'];
    } 
?>


<br/>
<center>
    <table width=100%  style='' class="table">
        <tr>
            <td>S/No.</td>
            <td><b>DEPARTMENT NAME</b></td>
            <td><b>SUB DEPARTMENT NAME</b></td>
            <td><B>ACTION</B></td>
        </tr>
<?php
    
    
    $Select_Assigned_Sub_Department = mysqli_query($conn,"select * from tbl_employee emp, tbl_department dept, tbl_sub_department sdept, tbl_employee_sub_department ed
                                                        where emp.employee_id = ed.employee_id and
                                                            sdept.department_id = dept.department_id and
                                                                sdept.sub_department_id = ed.sub_department_id and
                                                                    emp.employee_id = '$Employee_ID'");
    $count_sn=1;
    while($row = mysqli_fetch_array($Select_Assigned_Sub_Department)){
        echo '<tr><td>'.$count_sn.'.</td><td>'.ucfirst($row['Department_Name']).'</td>';
                    echo '<td>'.strtoupper($row['Sub_Department_Name']).'</td>';
                    echo '<td style="text-align: center;" width=10%><a href="removeaccesssubdepartment.php?Employee_ID='.$row['Employee_ID'].'&Sub_Department_ID='.$row['Sub_Department_ID'].'" target="_parent" class="art-button-green"><b>REMOVE</b></td></tr>';   
           $count_sn++;
                    }

?>
       
    </table>
</center>