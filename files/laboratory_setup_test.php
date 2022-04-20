<?php
	include("./includes/header.php");
	include("./includes/connection.php");
	$requisit_officer=$_SESSION['userinfo']['Employee_Name'];
	
    if(!isset($_SESSION['userinfo'])){ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");}
    if(isset($_SESSION['userinfo']))
	{
		if(isset($_SESSION['userinfo']['Laboratory_Works']))
		{
			if($_SESSION['userinfo']['Laboratory_Works'] != 'yes'){ header("Location: ./index.php?InvalidPrivilege=yes");} 
		}else
			{
				header("Location: ./index.php?InvalidPrivilege=yes");
			}
    }else
		{ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes"); }

    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Laboratory_Works'] == 'yes')
            { 
            //echo "<a onclick='goBack()' class='art-button-green'>BACK</a>";
            }
    }
               
?>
<?php
if(isset($_GET['Status_From'])){?>
	<a href="laboratory_sample_collection_details.php?Status_From=<?php 
            echo $_GET['Status_From']; ?>&patient_id=<?php 
            echo $_GET['patient_id']; ?>&Patient_Payment_Item_List_ID=<?php 
            echo $_GET['Patient_Payment_Item_List_ID']; ?>&item_id=<?php echo $_GET['Item_ID']; ?>&payment_id=<?php 
            echo $_GET['payment_id']; ?>&Required_Date=<?php echo filter_input(INPUT_GET, 'Required_Date'); ?> " class='art-button-green'>BACK</a>
<?php }
//if isset using only Item_ID
else{ ?>
	<a href="searchlabtestlist.php?LaboratorySetupTestThisPage=ThisPage" class='art-button-green'>BACK</a>
<?php }


?>
<?php 
if(!isset($_GET['addlaboratorytest'])){
    ?>

<center>               
<table style="width:90%;margin_top:10px;" class="hiv_table">
                    <tr><td colspan="4" style="width:100%"><hr></td></tr>
                    <tr><td colspan="4" style="width:100%"><center><b>LAB SPECIMEN SETTINGS</b></center></td></tr>
                    <tr><td colspan="4" style="width:100%"><hr></td></tr>
    <tr>
        <td>
            <fieldset>   
        <center>
        <form name="" action="laboratory_setup_test.php?addlaboratorytest=addlaboratorytest" method="POST">
            <table width=100%>
                <tr>
                    <td width="10%">Test Name</td>

                    <td style="width:1000px">
                     <?php 
                     $select_all_item_ID =mysqli_query($conn,"SELECT ref_specimen_ID FROM tbl_tests_specimen WHERE tests_item_ID='".$_GET['Item_ID']."'" );

                        $lenght =mysqli_num_rows($select_all_item_ID);
                        $in_data='';
                        $i=1;

                            while($row = mysqli_fetch_array($select_all_item_ID))
                            {
                                $in_data .=$row['ref_specimen_ID']; if($lenght > $i){$in_data .=",";}
                                $i++;
                            }

                            if(isset($_GET['Item_ID'])){
                                $data = mysqli_query($conn,"SELECT * FROM tbl_items where Item_ID='".$_GET['Item_ID']."'");
                                $row = mysqli_fetch_array($data);
                                echo "<input name='' type='text' class='specimenItemID' id='".$row['Item_ID']."' value='".$row['Product_Name']."'>";
                                echo "<input name='Item_ID' type='hidden' value='".$row['Item_ID']."'>";
                        }

                        ?>
                    </td>
                    <td colspan="2"></td>
                </tr>

            <tr>
            <td colspan="5" style="width:100%">

            <center>
                <table style="width:99%">
                    <tr>
                        <td style="width:48%;text-align:center;font-weight:bold;" colspan="2">Specimen Available</td>
                        <td style="width:48%;text-align:center;font-weight:bold;" colspan="2">Specimen Selected</td>
                    </tr>


                </table>
            </center>

            </td>
                </tr>
                 <tr>
                    <td colspan="5" style="width:100%">
                        
                        <div id="refreshSpecimen" style="width:100%;height:340px;background-color:white;">


                    <?php
                     $select_sample =mysqli_query($conn,"select * from tbl_tests_specimen where tests_item_ID='".$_GET['Item_ID']."'");
                    ?>
                    <center>
                    <table style="width:99%">

                                <tr>
                                <td style="width:48%;border-right:1px solid black;height:100%;" colspan="2">
                                <div style="width:100%;overflow-y:scroll;overflow-x:hidden;height:340px;">
                                    <table style="width:100%;padding-button:0px;" id="assignParameter">
                                  
                                        <?php

                                        $select_specimen ="SELECT * FROM tbl_laboratory_specimen";
                                        if($lenght > 0){
                                                $select_specimen .=" where Specimen_ID NOT IN(";
                                                $select_specimen .=$in_data.")";
                                        }
                                        $data = mysqli_query($conn,$select_specimen);
                                        while($row = mysqli_fetch_array($data)){
                                        echo "<tr>
                                        <td width='10px' class='hidetd' id='hidetd".$row['Specimen_ID']."'><input name='Specimen_ID' class='checkSpecimen' type='checkbox' value=".$row['Specimen_ID'].">".$row['Specimen_Name']."</td></tr>";
                                        }
                                        ?> 
                                    
                                    </table>
                                </div>
                                </td>
                                <td style="width:48%;border-right:1px solid black;height:100%;" colspan="2">
                                <div style="width:100%;overflow-y:scroll;overflow-x:hidden;height:340px;">
                                <table width="100%" id="addedParameter">
                                    <!--<div >-->
                                    <?php
                                    if($lenght > 0){
                                                $select_specimen1 ="SELECT * FROM tbl_laboratory_specimen";
                                                $select_specimen1 .=" where Specimen_ID  IN(".$in_data.")";

                                                $data1 = mysqli_query($conn,$select_specimen1);
                                                while($row = mysqli_fetch_array($data1)){
                                                echo "<tr id='remove".$row['Specimen_ID']."'><td width='10px'><input name='Specimen_ID' checked='true' class='Specimen_ID'  type='checkbox' id='".$row['Specimen_ID']."' value=".$_GET['Item_ID']."></td><td>".$row['Specimen_Name']."</td></tr>";
                                                }
                                         }
                                    ?> 
                                    <!--</div>-->
                                </table>
                                </div>
                                </td>
                                </tr>


                                </table>
                             </center>
                            
                            
                        </div>  

                    </td>
                </tr>
            </table>
            
	    <table align='right'>
		<tr>
			<td style="text-align: right">
				<?php
					if(isset($_GET['Status_From'])){?>
						<a href="laboratory_sample_collection_details.php?Status_From=<?php 
						    echo $_GET['Status_From']; ?>&patient_id=<?php 
						    echo $_GET['patient_id']; ?>&Patient_Payment_Item_List_ID=<?php 
						    echo $_GET['Patient_Payment_Item_List_ID']; ?>&item_id=<?php echo $_GET['Item_ID']; ?>&payment_id=<?php 
						    echo $_GET['payment_id']; ?>&Required_Date=<?php echo filter_input(INPUT_GET, 'Required_Date'); ?> " class='art-button-green'>DONE</a>
					<?php }
					//if isset using only Item_ID
					else{ ?>
						<a href="searchlabtestlist.php?LaboratorySetupTestThisPage=ThisPage" class='art-button-green'>DONE</a>
					<?php }
					
					
					?>
			</td>
		 </tr>
	    </table>
        </center>
	
</fieldset>
        </td>
    </tr>
</table>      
        </center>
<?php
    }else
     if(isset($_GET['addlaboratorytest'])) {

        $item_id = $_POST['Item_ID'];
        $lab_test_name = $_POST['lab_test_name'];
        $Specimen_ID = $_POST['Specimen_ID'];
        $cpoe_name = $_POST['cpoe_name'];
        $general_information = $_POST['general_information'];

        $sql = mysqli_query($conn,"INSERT INTO tbl_laboratory_test_specimen ( Lab_Test_Name,Item_ID,CPOE_Name,Specimen_ID) 
                                VALUES ( '$lab_test_name','$item_id', '$cpoe_name', '$Specimen_ID');");

        if($sql){

                echo "<script>
                        document.location = 'laboratory_setup_test.php?Item_ID={$item_id}';
                        </script>";
        // header('Location: laboratory_setup_test.php?Item_ID ={$item_id}');
}else{
        echo "Fail To Add Item Specimen";   
}

    }             
                

    include("./includes/footer.php");
?>

<script src="css/jquery.js"></script>
<script src="css/jquery-ui.js"></script>
<script src="css/scripts.js"></script>