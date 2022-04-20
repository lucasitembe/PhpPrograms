<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ./index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Admission_Works'])){
	    if($_SESSION['userinfo']['Admission_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if(isset($_GET['section'])){
            $section = $_GET['section'];
        }else{
            $section = "Admission";
        }
        if($section=='Admission'){
            echo "<a href='admissionworkspage.php?section=".$section."&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
            ADMISSION MAIN WORKPAGE
            </a>
            <a href='admissionreports.php?section=".$section."&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
            ADMISSION REPORTS
            </a>
            ";
    }
?>

<!--    Datepicker script-->
    <link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.1.custom.min.css" />
    <script src="js/jquery-1.9.1.js"></script>
    <script src="js/jquery-ui-1.10.1.custom.min.js"></script>
    <script>
        $(function () { 
            $("#date_From").datepicker({ 
                changeMonth: true,
                changeYear: true,
                showWeek: true,
                showOtherMonths: true,  
                //buttonImageOnly: true, 
                //showOn: "both",
                dateFormat: "yy-mm-dd",
                //showAnim: "bounce"
            });
            
        });
        
        $(function () { 
            $("#date_To").datepicker({ 
                changeMonth: true,
                changeYear: true,
                showWeek: true,
                showOtherMonths: true,  
                //buttonImageOnly: true, 
                //showOn: "both",
                dateFormat: "yy-mm-dd",
                //showAnim: "bounce"
            });
            
        });
    </script>
    
<!--    end of datepicker script-->


<?php
    /* if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
    <a href='#?SearchListPatientBilling=SearchListPatientBillingThisPage' class='art-button-green'>
        INPATIENT
    </a>
<?php  } } */ ?>

<?php
    /*if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
    <a href='../DirectCashsearchlistofoutpatientbilling.php?SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
        DIRECT CASH
    </a>
<?php  } } */ ?>
 



<br/><br/>
<center>
<?php 
    if(isset($_POST['Filter'])){
        $Branch_ID = $_POST['Branch_ID'];
        $Gender = $_POST['Gender'];
        $Region = $_POST['Region'];
        $Hospital_Ward_ID = $_POST['Hospital_Ward_ID'];
        $start_age = $_POST['start_age'];
        $end_age = $_POST['end_age'];
    }else{
        $Branch_ID = '';
        $Gender = '';
        $Region = '';
        $Hospital_Ward_ID = '';
        $start_age = '';
        $end_age = '';
    }
?>
<form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
    <table width=80%>
        <tr>
            <td style='text-align: center;'><b>Branch</b></td>
            <td style='text-align: center;'><b>Gender</b></td>
            <td style='text-align: center;'><b>Region</b></td>
            <td style='text-align: center;'><b>Ward</b></td>
            <td style='text-align: center;'><b>Age</b></td>
            <td></td>
        </tr>
        <tr>
            <td>
                <center>
                    <select id='Branch_ID' name='Branch_ID'>
                    <option>ALL</option>
                    <?php
                        $select_branch = "SELECT * FROM tbl_branches";
                        $result = mysqli_query($conn,$select_branch);
                        while($row = mysqli_fetch_assoc($result)){
                            ?>
                            <option value='<?php echo $row['Branch_ID'];?>'><?php echo strtoupper($row['Branch_Name']);?></option>
                            <?php
                        }
                    ?>
                    </select>
                </center>
            </td>
            <td>
                <center>
                    <select id='Gender' name='Gender'>
                        <option>ALL</option>
                        <option>Male</option>
                        <option>Female</option>
                    </select>
                </center>
            </td>
            <td>
                <center>
                    <select id='Region' name='Region'>
                    <option>ALL</option>
                    <?php
                        $select_branch = "SELECT * FROM tbl_regions";
                        $result = mysqli_query($conn,$select_branch);
                        while($row = mysqli_fetch_assoc($result)){
                            ?>
                            <option value='<?php echo $row['Region_Name'];?>'><?php echo strtoupper($row['Region_Name']);?></option>
                            <?php
                        }
                    ?>
                    </select>
                </center>
            </td>
            <td>
                <center>
                    <select id='Hospital_Ward_ID' name='Hospital_Ward_ID'>
                    <option>ALL</option>
                    <?php
                        $select_branch = "SELECT * FROM tbl_hospital_ward";
                        $result = mysqli_query($conn,$select_branch);
                        while($row = mysqli_fetch_assoc($result)){
                            ?>
                            <option value='<?php echo $row['Hospital_Ward_ID'];?>'><?php echo strtoupper($row['Hospital_Ward_Name']);?></option>
                            <?php
                        }
                    ?>
                    </select>
                </center>
            </td>
            <td style='text-align: center;'>
                <input style="width: 25px;" type='text' id='start_age' name='start_age'> To <input style="width: 25px;" type='text' id='end_age' name='end_age'>
            </td>
            <td style='text-align: center;'>
                <input type='submit' name='Filter' id='Filter' class='art-button-green' value='FILTER'>
                <a href="dischargepatientreportprint.php?Branch_ID=<?php echo $Branch_ID;?>&Gender=<?php echo $Gender;?>&Region=<?php echo $Region;?>&Hospital_Ward_ID=<?php echo $Hospital_Ward_ID;?>&start_age=<?php echo $start_age;?>&end_age=<?php echo $end_age;?>" class='art-button-green' target='_blank'>PREVIEW DETAILS</a>
            </td>
        </tr>
    </table>
    <table width=80%>
        <tr>
            <td colspan=7 style='text-align: center;'><?php //echo $status; ?></td>
        </tr>
        
    </table>
</center>
        <fieldset>
        <center>
            <table width=100% border=1>
                <tr>
                    <td>
                        <iframe width='100%' height=275px src="dischargepatientreport_iframe.php?Branch_ID=<?php echo $Branch_ID;?>&Gender=<?php echo $Gender;?>&Region=<?php echo $Region;?>&Hospital_Ward_ID=<?php echo $Hospital_Ward_ID;?>&start_age=<?php echo $start_age;?>&end_age=<?php echo $end_age;?>"></iframe>
                    </td>
                </tr>
            </table>
        </center>
</fieldset><br/>
</form>
<?php
    include("./includes/footer.php");
?>