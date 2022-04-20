 
 <!--[if lt IE 9]><script src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <link rel="stylesheet" href="style.css" media="screen">
    <!--[if lte IE 7]><link rel="stylesheet" href="style.ie7.css" media="screen" /><![endif]-->
    <link rel="stylesheet" href="style.responsive.css" media="all">
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Ubuntu&amp;subset=latin">
 
 <script src="jquery.js"></script>
    <script src="script.js"></script>
    <script src="script.responsive.js"></script>
    
<?php include("./includes/connection.php"); ?>


<?php
    if(isset($_GET['Employee_ID'])){
        $Employee_ID = $_GET['Employee_ID'];
    } 
    $is_hr="";
    if(isset($_GET['HRWork']) && $_GET['HRWork']=='true'){
        $is_hr = "&HRWork=true";   
    }
?>


<br/>
<center>
    <table width=30% border=1 style='border: inherit;'>
        <tr>
            <td width=70%><b>BRANCH NAME</b></td>
           <!-- <td><b>ACTION</b></td>-->
        </tr>
<?php
    
    
    $Select_Assigned_Branches = mysqli_query($conn,"select * from tbl_employee e, tbl_branches b, tbl_branch_employee be
                    where e.employee_id = be.employee_id and
                        b.branch_id = be.branch_id and e.employee_id = '$Employee_ID'");
    
    while($row = mysqli_fetch_array($Select_Assigned_Branches)){
                echo '<tr><td>'.strtoupper($row['Branch_Name']).'</td>';
                echo '<td style="text-align: center;"><a href="removeaccessbranch.php?Employee_ID='.$row['Employee_ID'].'&Branch_ID='.$row['Branch_ID'].$is_hr.'" target="_parent" class="art-button-green"><b>REMOVE</b></td></tr>';   
            }

?>
       
    </table>
</center>