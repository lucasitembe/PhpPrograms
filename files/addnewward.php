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
	if(isset($_POST['submittedAddNewWardForm'])){
		$District_Name = mysqli_real_escape_string($conn,$_POST['District_Name']);
                $Ward_Name = mysqli_real_escape_string($conn,$_POST['Ward_Name']);
                
                
		$sql = "insert into tbl_ward(Ward_Name,District_ID)
                    values('$Ward_Name',(select district_id from tbl_district where district_name = '$District_Name'))";
		
		if(!mysqli_query($conn,$sql)){ 
			$error = '1062yes';
			    if(mysql_errno()."yes" == $error){ 
                            ?>
                            
                            <script type='text/javascript'>
                                alert('WARD NAME ALREADY EXISTS! \nTRY ANOTHER NAME');
                                </script>
                            
                        <?php
			}
		}
		else { 
                    echo "<script type='text/javascript'>
			    alert('WARD ADDED SUCCESSFUL');
			    </script>"; 
		}
	}
?>
<br/><br/><br/><br/><br/>

<center>
    <table width=40%><tr><td>
        <center>
            <fieldset>
                    <legend align="center" ><b>ADD NEW WARD</b></legend>
                    <table>
                        <form action='#' method='post' name='myForm' id='myForm'>
                                <td style='text-align: right;'><b>SELECT DISTRICT</b></td>
                                <td>
                                    <select name='District_Name' id='District_Name'>
                                    <?php
                                        $data = mysqli_query($conn,"select * from tbl_district");
                                            while($row = mysqli_fetch_array($data)){
                                            echo '<option>'.$row['District_Name'].'</option>';
                                        }
                                    ?>
                                </select>
                                </td>
                                <tr>
                                    <td width=40% style='text-align: right;'><b>WARD NAME</b></td>
                                    <td width=60%><input type='text' name='Ward_Name' required='required' size=70 id='Ward_Name' placeholder='Enter Ward Name'></td>
                                </tr>
                                <tr>
                                    <td colspan=2 style='text-align: right;'>
                                        <input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>
                                        <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                                        <input type='hidden' name='submittedAddNewWardForm' value='true'/> 
                                    </td>
                                </tr>
                        </form></table>
            </fieldset>
        </center></td></tr></table>
</center>


<?php
    include("./includes/footer.php");
?>