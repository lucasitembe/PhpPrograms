<?php
    include("./includes/header.php");
    include("./includes/connection.php");
	$temp = 1;
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

	<a href='bloodworkpage.php?BloodDisposed=BloodDisposedThisPage' class='art-button-green'>
        BACK
	</a>


<?php
    if(isset($_POST['submittedBloodDispose'])){
        
        if(isset($_SESSION['userinfo'])){ 
           if(isset($_SESSION['userinfo']['Employee_ID'])){
                $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
           }else{
            $Employee_ID = 0;
           }
        }
        $Blood_Batch = mysqli_real_escape_string($conn,$_POST['Batch_Name']);
        $Blood_Group = mysqli_real_escape_string($conn,$_POST['Blood_Group']);
        $Blood_Disposed = mysqli_real_escape_string($conn,$_POST['Blood_Disposed']);
        $Reason = mysqli_real_escape_string($conn,$_POST['Reason']);
        $Date_Taken = mysqli_real_escape_string($conn,$_POST['Date_Taken']);
	$Blood_ID = mysqli_real_escape_string($conn,$_POST['Blood_ID']);
	$Blood_Status = 'DISPOSED';
        
        
        if(isset($_SESSION['userinfo'])){
            if(isset($_SESSION['userinfo']['Employee_ID'])){
                $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
            }else{
                $Employee_ID = 0;
            }
        }
       
       
	//select patient registration date and time
	$data = mysqli_query($conn,"select now() as Registered_Date_And_Time");
            while($row = mysqli_fetch_array($data)){
                $Registered_Date_And_Time = $row['Registered_Date_And_Time'];
            }
	    
	    
        $Insert_Sql = "insert into tbl_blood_checked(
                    Blood_Group,Blood_Batch,BloodRecorded,Blood_Status,Employee_ID,Blood_ID,
					Reason,Date_Taken,Registered_Date_And_Time)
	
                    values('$Blood_Group', '$Blood_Batch',' $Blood_Disposed','$Blood_Status','$Employee_ID','$Blood_ID','$Reason',
                    ' $Date_Taken','$Registered_Date_And_Time')";
        
		
		if(!mysqli_query($conn,$Insert_Sql)){
            die(mysqli_error($conn));
	   }else{
		mysqli_query($conn,"DELETE FROM tbl_patient_blood_data WHERE Blood_ID = '$Blood_ID'") or die(mysqli_error($conn));
		 echo "<script type='text/javascript'>
			    alert('RECORDED');
			    document.location = './blooddisposal.php';
			</script>";
		
      }                          
    }
	   
?>
<script type="text/javascript" language="javascript">
function BloodVolume() {
	
	     if(window.XMLHttpRequest) {
		 mm4 = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
		 mm4 = new ActiveXObject('Microsoft.XMLHTTP');
		 mm4.overrideMimeType('text/xml');
	    }
		
		var Blood_ID=document.getElementById('Blood_ID').value;
		//var Blood_Group=document.getElementById('Blood_Group').value;
		
	    var url = 'BloodVolumeAjax.php?Blood_ID='+Blood_ID;
	    mm4.onreadystatechange= AJAXP4; //specify name of function that will handle server response....
	     mm4.open('GET',url,true);
	    mm4.send(); 
	}
    function AJAXP4() {
	var data4 = mm4.responseText;
	
   document.getElementById('sumblood').value = data4;
    		
    }
</script>

	
	
<script type="text/javascript" language="javascript">
function BloodGroup() {
	
	     if(window.XMLHttpRequest) {
		mm3 = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
		mm3 = new ActiveXObject('Microsoft.XMLHTTP');
		mm3.overrideMimeType('text/xml');
	    }
		
		var Blood_ID=document.getElementById('Blood_ID').value;
		
		
	    var url = 'BloodGroupAjax.php?Blood_ID='+Blood_ID;
	   mm3.onreadystatechange= AJAXP3; //specify name of function that will handle server response....
	    mm3.open('GET',url,true);
	    mm3.send(); 
	}
    function AJAXP3() {
	var data3 = mm3.responseText;
	document.getElementById('Blood_Group').value = data3;

    		
    }
	
</script>

<script type="text/javascript" language="javascript">
    function getBatches(Batch_Name) {
	    if(window.XMLHttpRequest) {
		mm2 = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
		mm2 = new ActiveXObject('Micrsoft.XMLHTTP');
		mm2.overrideMimeType('text/xml');
	    }
	    
	    mm2.onreadystatechange= AJAXP2; //specify name of function that will handle server response....
	    mm2.open('GET','GetBloodId.php?Batch_Name='+Batch_Name,false);
	    mm2.send();
	}
    function AJAXP2() {
	var data2 = mm2.responseText;
	document.getElementById('Blood_ID').innerHTML = data2;	
	document.getElementById("Blood_Group").value = '';
	document.getElementById("sumblood").value = '';
    }

</script>


   
    
<script>
function Bloodcompare() {
	
var a = parseInt(document.getElementById('Blood_Disposed').value);
var b = parseInt(document.getElementById('sumblood').value);
    if (a>b) {
        alert ("The amount your disposing exceeds amount available in stock!!");
		document.getElementById('Blood_Disposed').value='';
    }
 }  
</script>


<br>
<br>
<br>

<center>
<fieldset style=' height:320px;width:95%;'>
    <legend align='right'><b>BLOOD DISPOSED</b></legend><br/>
        <center>
	    <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">           
            <table width=80%>
                <tr> 
                    <td>
                        <table width=90%>
		<tr>
		    <td style="text-align:right;"><b>Blood Batch</b></td>
		    <td>
			<select name='Batch_Name' id='Batch_Name' onchange='getBatches(this.value)'>
			    <option selected='selected' value=''> </option>
			    <?php
				$data = mysqli_query($conn,"select * from tbl_blood_batches");
				    while($row = mysqli_fetch_array($data)){
				?>
				<option value='<?php echo $row['Batch_Name'];?>'>
				    <?php echo $row['Batch_Name']; ?>
				</option>
			    <?php
				}
			    ?>
			</select>
		    </td>
						
		    <td style="text-align:right;"><b>Blood Checked ID</b></td>
		    <td>
			<select name='Blood_ID' id='Blood_ID' required='required'  onchange='BloodGroup();BloodVolume()'>
			<option selected='selected'></option> 
				 
			</select>
		    </td>
		</tr> 
		<tr>
		    <td style="text-align: right;"><b>Blood Group</b></td>
		    <td ><input type='text' name='Blood_Group' id='Blood_Group' ></td>
						      
		    <td style="text-align: right;width:20%;"><b>Total Volume</b></td>
		    <td><input type="text" id="sumblood" name="sumblood" value="" placeholder="In milliliter"></td>
                </tr>
		<tr>
		    <td  style="text-align: right;width:40%"><b>Blood Volume Disposed</b></td>
	            <td colspan='3'><input type='text' name='Blood_Disposed' id='Blood_Disposed'  placeholder='In milliliter' onkeyup='Bloodcompare()'></td>
	        </tr>
		<tr>
		    <td style='text-align:right;'><b>Reason for Disposing</b></td>
		    <td colspan='3'><textarea  name='Reason' id='Reason'></textarea></td>
                </tr>
		<tr>
							    
		    <td style="text-align: right;width:40%"><b>Date Disposed</b></td>
		    <td colspan='3'><input type='text' name='Date_Taken' required='required' id='Date_Taken'  ></td>
		</tr>
		<tr>
		    <td colspan='5' style='text-align: right;'>
		       <input style="width: 25%;" type='submit' name='Save' id='Save' class='art-button-green' value='SAVE'>
		       <input type='hidden' name='submittedBloodDispose' value='true'/>
		    </td>
		</tr>
                               
                            
	    </table>
                    </td>
                    
                </tr>
	

 <link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.1.custom.min.css" />
    <script src="js/jquery-1.9.1.js"></script>
    <script src="js/jquery-ui-1.10.1.custom.min.js"></script>
    <script>
	    $(function () { 
		    $("#Date_Taken").datepicker({ 
			    changeMonth: true,
			    changeYear: true,
			    showWeek: true,
			    showOtherMonths: true,  
			    
			    dateFormat: "yy-mm-dd",
			    
		    });
		    
		    
	    });
    </script> 
				
				
</center>

	    </table>
	    </form>	
        
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>