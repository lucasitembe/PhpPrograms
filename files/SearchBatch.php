<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Reception_Works'])){
	    if($_SESSION['userinfo']['Reception_Works'] != 'yes'){
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

<a href='EditingBatch.php?Appointments=AppointmentsThisPage' class='art-button-green'>
        BACK
    </a>


<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        
        $Age = $Today - $original_Date; 
    }
?>

<?php

//    select patient information to perform check in process
    if(isset($_GET['Batch_ID'])){
        $Batch_ID = $_GET['Batch_ID']; 
        $select_Batch= mysqli_query($conn,"select
                            Batch_ID,Batch_Name
                          from tbl_blood_batches 
                                              Batch_ID = '$Batch_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select_Batch);
        
        if($no>0){
            while($row = mysqli_fetch_array($select_Batch)){
                $Batch_ID=$row['Batch_ID']; 	
				$Batch_Name =$row['Batch_Name'];
	      
               
            }
       
        }else{
           $Batch_ID=''; 	
		   $Batch_Name ='';
	           
        }
    }else{
            $Batch_ID=''; 	
		   $Batch_Name ='';
        }
?>
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>

<script language="javascript" type="text/javascript">
    function searchBatch(Batch_Name){
        document.getElementById('Search_Iframe').innerHTML ="<iframe width='100%' height=320px src='Search_Batch_Iframe.php?Batch_Name="+Batch_Name+"'></iframe>";
    }
</script>
<br/><br/>
<center>
    <table width=40%>
        <tr>
            <td>
                <input type='text' name='searchBatch' id='searchBatch' onclick='searchBatch(this.value)' onkeypress='searchBatch(this.value)' placeholder='~~~~~~~~~~~~~~~~~~Enter Batch Name~~~~~~~~~~~~~~~~~~~~~~~~~~'>
            </td>
        </tr>
        
    </table>
</center>
<fieldset>  
            <legend align=center><b>BATCH LIST</b></legend>
        <center>
            <table width=100% border=1>
                <tr>
            <td id='Search_Iframe'>
		<iframe width='100%' height=320px src='Search_Batch_Iframe.php?Donor_Name='></iframe>
            </td>
        </tr>
            </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>