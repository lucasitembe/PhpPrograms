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
    <table width=100% border=1 style=''>
        <tr>
            <td><b>BRANCH NAME</b></td>
            <td><B>ACTION</B></td>
        </tr>
<?php
    
    
    $Select_Assigned_Branch = mysqli_query($conn,"select * from tbl_employee emp, tbl_branch_employee be,tbl_branches b
                                                        where emp.employee_id = be.employee_id
														and b.Branch_ID = be.Branch_ID and
                                                        emp.employee_id = '$Employee_ID'");
    $one_branch = mysqli_num_rows($Select_Assigned_Branch);
    while($row = mysqli_fetch_array($Select_Assigned_Branch)){
        echo '<tr><td>'.ucfirst($row['Branch_Name']).'</td>';
                    if($one_branch>1){
					echo '<td style="text-align: center;" width=10%><a href="removebranch.php?Employee_ID='.$row['Employee_ID'].'&Branch_ID='.$row['Branch_ID'].'" target="_parent" class="art-button-green"><b>REMOVE</b></td></tr>';
					}else{   
					?><td style="text-align: center;" width=10%><input type="button" class="art-button-green" value="REMOVE" onclick="alert('Unable To Complete Action,\nEmployee Must Have Atleast One Branch !')"></td></tr><?php
					}
            }

?>
       
    </table>
</center>