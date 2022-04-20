<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	    if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>
<br/><br/>

<?php
	if(isset($_POST['submittedAddNewDistrictForm'])){
		$District_Name = mysqli_real_escape_string($conn,$_POST['District_Name']);
                $Region_Name = mysqli_real_escape_string($conn,$_POST['Region_Name']);
                
                
		$sql = "insert into tbl_district(District_Name,Region_ID)
                    values('$District_Name',(select region_id from tbl_regions where region_name = '$Region_Name'))";
		
		if(!mysqli_query($conn,$sql)){ 
			$error = '1062yes';
			    if(mysql_errno()."yes" == $error){ 
                            ?>
                            
                            <script type='text/javascript'>
                                alert('DISTRICT NAME ALREADY EXISTS! \nTRY ANOTHER NAME');
                                </script>
                            
                        <?php
			}
		}
		else { 
                    echo "<script type='text/javascript'>
			    alert('DISTRICT ADDED SUCCESSFUL');
			    </script>"; 
		}
	}
?>
<br/><br/><br/><br/><br/>

<center>
    <table width=40%><tr><td>
        <center>
            <fieldset>
                    <legend align="center" ><b>ADD NEW DISTRICT</b></legend>
                    <table>
                        <form action='#' method='post' name='myForm' id='myForm'>
                                <td style='text-align: right;'><b>SELECT REGION</b></td>
                                <td>
                                    <select name='Region_Name' id='Region_Name'>
                                    <?php
                                        $data = mysqli_query($conn,"select * from tbl_regions");
                                            while($row = mysqli_fetch_array($data)){
                                            echo '<option>'.$row['Region_Name'].'</option>';
                                        }
                                    ?>
                                </select>
                                </td>
                                <tr>
                                    <td width=40% style='text-align: right;'><b>DISTRICT NAME</b></td>
                                    <td width=60%><input type='text' name='District_Name' required='required' size=70 id='District_Name' placeholder='Enter District Name'></td>
                                </tr>                                  
                                <tr>
                                    <td colspan=2 style='text-align: right;'>
                                        <input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>
                                        <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                                        <input type='hidden' name='submittedAddNewDistrictForm' value='true'/> 
                                    </td>
                                </tr>
                        </form></table>
            </fieldset>
        </center></td></tr></table>
</center>


<?php
    include("./includes/footer.php");
?>