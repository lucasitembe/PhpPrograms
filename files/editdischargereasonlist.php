<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
     

    if(isset($_GET['frompage']) && $_GET['frompage'] == "addmission") {
?>
<a href='admissionconfiguration.php?AdmisionWorks=AdmisionWorksThisPage&frompage=addmission' class='art-button-green'>
    <b>BACK</b>
</a>

<?php
    } else {
?>
<a href='admissionconfiguration.php?AdmisionWorks=AdmisionWorksThisPage' class='art-button-green'>
    <b>BACK</b>
</a>

<?php
    }
?>

<br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>

<?php
	
        
//        $Discharge_Reason = mysqli_real_escape_string($conn,$_GET['Discharge_Reason']);
	    $sql = "SELECT * FROM tbl_discharge_reason ";
	
	$result=  mysqli_query($conn,$sql) or die(mysqli_error($conn));
        
        
?>
<center>
    
            <fieldset>
                <form action='#' method='post'>
                    <legend align="center" ><b>EDIT DISCHARGE REASON</b></legend>
                    <table width="60%" >
                        <tr>
                            <td style="width:3% ">S/N</td><td>Reason</td>
                        </tr>
                   <?php
                       $i=1;
                      while($row=  mysqli_fetch_array($result)){
                          echo '<tr><td>'.(++$i).'</td><td><a href="editdischargereason.php?Discharge_Reason_ID='.$row['Discharge_Reason_ID'].'&AdmisionWorks=AdmisionWorksThisPage">'.$row['Discharge_Reason'].'</td></tr>';
                      }
                   ?>
                    </table>
            </fieldset>
        </center>
</center>
<?php
    include("./includes/footer.php");
?>