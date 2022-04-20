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
     
?>
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
    <a href='systemconfiguration.php?SystemConfiguration=SystemConfigurationThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>


<?php
    //select systemconfiguration based on branch
    if(isset($_SESSION['userinfo']['Branch_ID'])){
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    }else{
        $Branch_ID = 0;
    }

    $select_system_configuration = mysqli_query($conn,"SELECT Store_Order_Add_Items_By_Pop_Up
                                                FROM tbl_system_configuration
                                                WHERE Branch_ID = '$Branch_ID'");

    while($row = mysqli_fetch_array($select_system_configuration)){
        $Store_Order_Add_Items_By_Pop_Up = $row['Store_Order_Add_Items_By_Pop_Up'];
    }

    if (!empty($_POST)) {
        if (isset($_POST['Store_Order_Add_Items_By_Pop_Up'])) {
            $Store_Order_Add_Items_By_Pop_Up = 'yes';
        } else {
            $Store_Order_Add_Items_By_Pop_Up = 'no';
        }
    }

    $update_system_configuration = mysqli_query($conn,"UPDATE tbl_system_configuration
                                                SET Store_Order_Add_Items_By_Pop_Up = '$Store_Order_Add_Items_By_Pop_Up'
                                                WHERE Branch_ID = '$Branch_ID'");
    if ($update_system_configuration) {
        echo "<script>alert('You have update Store & Supply Configuration');</script>";
    }
?>

<br/><br/><br/><br/><br/><br/>

<fieldset>
    <form action="#" method="post">
        <input type="hidden" name="yes" value="yes"/>

        <legend align=center><b>STORAGE & SUPPLY CONFIGURATION</b></legend>
        <center><br/>
            <table width = "100%">
                <tr>
                    <td style='text-align: center; color:black; border:2px solid #ccc;text-align:center;'>
                        <input type='checkbox' name='Store_Order_Add_Items_By_Pop_Up' id='Store_Order_Add_Items_By_Pop_Up'
                            <?php if(strtolower($Store_Order_Add_Items_By_Pop_Up) =='yes'){ echo 'checked="checked"'; }?>>
                        Store Order : Add Items By Pop Up Window
                    </td>
                    <td style="text-align: center;">
                        <input type='submit' class='art-button-green' value='Change Settings'/>
                    </td>
                </tr>
            </table>
        </center>
    </form>
</fieldset>

<?php
    include("./includes/footer.php");
?>