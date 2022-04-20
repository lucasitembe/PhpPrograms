<?php
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];   
    }else{
        $Registration_ID = '';
    }
    if(isset($_GET['Patient_Payment_Item_List_ID'])){
		$Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
	}else{
		$Patient_Payment_Item_List_ID = '';
	}

	if(isset($_GET['Patient_Payment_ID'])){
		$Patient_Payment_ID = $_GET['Patient_Payment_ID'];
	}else{
		$Patient_Payment_ID = '';
	}
if(isset($_GET['Status_From'])){
	$Status_From = $_GET['Status_From'];
}else{
	$Status_From = '';
}
if(isset($_GET['Item_ID'])){
	$Item_ID = $_GET['Item_ID'];
}else{
	$Item_ID = '';
}
 ?>

 <?php
	if(isset($_POST['submitradilogyform'])){       
        if(isset($_SESSION['userinfo'])){ 
           if(isset($_SESSION['userinfo']['Employee_ID'])){
                $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
           }else{
            $Employee_ID = 0;
           }
    }
	}

	//parameter,comments
	
echo '<center><table width ="100%"  class="vital"  style="border-left:1px solid black;border-right:1px solid black;border-top:1px solid black;border-bottom:1px solid black;"">';
echo '<tr bgcolor="#D3D3D3"><td width = "5%"><b>SN</b></td>
      <td width = "15%"style="color:black;"><b>Parameter</b></td>
      <td style="text-align:center;color:black;" width = "30%"><b>Results/Comments</b></td>
      </tr>';
	
    $select_radiology = mysqli_query($conn,"SELECT * FROM tbl_radiology_discription rd,tbl_radiology_parameter rp
										where rd.Parameter_ID = rp.Parameter_ID and
											rd.Registration_ID='$Registration_ID' ") or die(mysqli_error($conn));   
    while($row = mysqli_fetch_array($select_radiology)){
		$Radiology_Description_ID = $row['Radiology_Description_ID'];
        echo "<tr><td>".$temp."</td>";
		echo "<td><a href='editParameterRadiology.php?Radiology_Description_ID=".$Radiology_Description_ID."' style='text-decoration:none;color:black;' target='_Parent'>" .$row['Parameter_Name']. "</a></td>";
		echo "<td><textarea rows='4' cols='80'bgcolor='black'  style='border-radius:1px;box-shadow:0 0 1px 1px #123456;'>".$row['comments']."</textarea></td>";
	 $temp++;
     }  echo "</tr>";
 
?>
