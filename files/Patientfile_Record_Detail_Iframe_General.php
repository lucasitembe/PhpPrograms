<link rel="stylesheet" href="tablew.css" media="screen">
<?php
    //include("./includes/connection.php");
    @session_start();
	 if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	die( "<style>.art-content{background-color: #FFFFFF;}</style><p style='color:red;text-align:center;font-family:widen latin;font-size:40px;margin-bottom:200px'>You don't have access to this page yet.Try login again or contact administrator for support!<p>");

    }
    if(isset($_SESSION['userinfo'])){
       if(isset($_SESSION['userinfo']['Patient_Record_Works'])){
	    if($_SESSION['userinfo']['Patient_Record_Works'] != 'yes'){
		die( "<style>.art-content{background-color: #FFFFFF;}</style><p style='color:red;text-align:center;font-family:widen latin;font-size:40px;margin-bottom:200px'>You don't have access to this resource.Please contact administrator for support!<p>");
	    }
	}else{
	    	die( "<style>.art-content{background-color: #FFFFFF;}</style><p style='color:red;text-align:center;font-family:widen latin;font-size:40px;margin-bottom:200px'>You don't have access to this page yet.Try login again or contact administrator for support!<p>");

	}
      
    }else{
	@session_destroy();
	   	die( "<style>.art-content{background-color: #FFFFFF;}</style><p style='color:red;text-align:center;font-family:widen latin;font-size:40px;margin-bottom:200px'>You don't have access to this page yet.Try login again or contact administrator for support!<p>");

    }
    
	
    if(isset($_GET['name'])){
        $Patient_Name = $_GET['name'];
    }else{
        $Patient_Name = "";
    }
    
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }
    
    //Find the current date to filter check in list     
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date; 
    }
    //$Patient_Payment_ID=$row['Patient_Payment_ID'];
    //$Patient_Payment_Item_List_ID=$_GET['Patient_Payment_Item_List_ID'];
?>
    <center>
        <table width = '100%'>
            <tr id='thead'>
			    <tr><td colspan="9"><hr></td></tr>
                <td style="width:5%;"><center><b>SN</b></center></td>
                <td><center><b>DATE</b></center></td>
                <td><center><b>DOCTORS REVIEW</b></center></td>
                <td><center><b>ATTACHMENT</b></center></td>
                <td><center><b>RESULTS/ VITALS</b></center></td>
	    <tr><td colspan="9"><hr></td></tr>
            </tr>
<?php
    $select_patients = "SELECT ppl.Patient_Payment_Item_List_ID, ppl.Patient_Payment_ID, c.consultation_ID, c.Consultation_Date_And_Time, c.Registration_ID, ppl.Patient_Payment_ID
			    FROM tbl_consultation c, tbl_patient_payment_item_list ppl WHERE
			    c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID and
			    c.Registration_ID = '$Registration_ID'";
    $result = mysqli_query($conn,$select_patients) or die(mysqli_error($conn));
    $num = mysqli_num_rows($result);
	
	$resultPat = mysqli_query($conn,"SELECT Patient_Name FROM tbl_patient_registration WHERE Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    $pat = mysqli_fetch_array($resultPat);
	$patientName= $pat['Patient_Name'];
    
    $i=1;
    while($row = mysqli_fetch_array($result)){
	  $href="consultation_ID=".$row['consultation_ID'];
	        if(isset($_GET['doctorsworkpage'])){ 
	          $href.='doctorsworkpage=yes';
		    }
      $vital="<a target='_blank' class='art-button-green' href='nurseform.php?Registration_ID=".$row['Registration_ID']."&Nurse_DateTime=".$row['Consultation_Date_And_Time']."&NurseWorks=NurseWorksThisPage' target='_parent' style='text-decoration: none;'>VITAL SIGNS</a>";			
	  $href .="&Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."";
	  
	  $Review=$ViewAttachments=$Attach='';

		//echo $row['Patient_Payment_ID']; exit;
		    $Review = "<a href='Patient_Record_Review.php?consultation_ID=".$row['consultation_ID']."&Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."' target='_parent' style='text-decoration: none'>
		<button class='art-button-green' style='width: 40%; height: 100%'>Review</button>
		    </a>";
		    
		    $vital="<a target='' class='art-button-green' href='nurseform_General.php?Section=DoctorRad&consultation_ID=".$row['consultation_ID']."&Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&Nurse_DateTime=".$row['Consultation_Date_And_Time']."&NurseWorks=NurseWorksThisPage' target='_parent' style='text-decoration: none;'>VITAL SIGNS</a>";
		    
		    $ViewAttachments="<a href='Patient_Attachment_Detail_General.php?consultation_ID=".$row['consultation_ID']."&Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."' target='_parent' style='text-decoration: none'>
		<button class='art-button-green' style='width: auto; height: 100%;display:inline'>View Attachments</button>
		    </a>";
		    
		    $Attach="<a href='File_Attach_General.php?consultation_ID=".$row['consultation_ID']."&Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."' target='_parent' style='text-decoration: none;'>
		<button class='art-button-green' style='width: auto; height: 100%;display:inline'>Attach</button>
		    </a>";
	    
    ?>
        <tr>
            <td id="thead"><center><?php echo $i.". "; ?></center></td>
            <td><center><?php echo $row['Consultation_Date_And_Time']; ?></center></td>
            <td>
		<center>
		 <?php
      		 echo $Review;
		 ?>
		</a>
		</center>
	    </td>
            <td>
		<center>
		 <?php 

			echo $ViewAttachments;
			
			echo $Attach;
		 ?>

		</center>
	    </td>
            <td>
		   <center>
		   <?php echo ' <button class="art-button-green" onclick="parentResult(\''.$href.'\',\''.$patientName.'\',\''.$row['Consultation_Date_And_Time'].'\',\''.$Registration_ID.'\')">RESULTS</button>'; echo $vital;?>
		   </center>
		 </td>
	</tr>
        <?php
	$i++;
    }
?>
    </table>
</center>
