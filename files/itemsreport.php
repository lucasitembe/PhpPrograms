<?php
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Pharmacy'])){
	    if($_SESSION['userinfo']['Pharmacy'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }else{
		@session_start();
		if(!isset($_SESSION['Pharmacy_Supervisor'])){ 
		    header("Location: ./pharmacysupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
		}
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>
 
 
 <?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Pharmacy'] == 'yes'){ 
?>
    <a href='pharmacyreportspage.php?PhrmacyReports=PharmacyReportsThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
<br/><br/>

<?php
    //get current date and time
    $sql_date_time = mysqli_query($conn,"select now() as Date_Time ") or die(mysqli_error($conn));
    while($date = mysqli_fetch_array($sql_date_time)){
        $Current_Date_Time = $date['Date_Time'];
    }
    $Filter_Value = substr($Current_Date_Time,0,11);
    
    if(isset($_SESSION['Storage'])){
                $Sub_Department_Name = $_SESSION['Storage'];
            }else{
                $Sub_Department_Name = '';
            }
?>
<fieldset>
    
     <table width=100%>
        <tr>
            <td style="text-align: center">
                <input type='text' id='Search_Value' style="width: 50%;text-align: center" name='Search_Value' style='text-align: center;' autocomplete='off' onkeyup='Filter_Dispense_List()' onkeypress='Filter_Dispense_List()' placeholder='~~~~~ Search Item Name ~~~~~~'>
            </td>
        </tr>
      </table>
        
</fieldset>
 <fieldset>
     <legend align='center' style='background-color:#2F8BAF;padding:10px;color:white;'><b>iTEM BALANCE ~ <?php if(isset($_SESSION['Pharmacy'])){ echo strtoupper($_SESSION['Pharmacy']);  }?></b></legend>      
        <center>
           	    
                   
                              <fieldset style="background-color:white; height:380px; overflow-y: scroll;" id='Items_Fieldset'>
			          <?php include './Get_Filtered_Items_stock.php'; ?>
		              </fieldset>
			</td>
                    </tr>
            </table>
</fieldset>


<script>
	function Filter_Dispense_List() {
	   var Search_Value = document.getElementById("Search_Value").value;
	    
	    	if(window.XMLHttpRequest) {
		    myObjectFilterDispensed = new XMLHttpRequest();
		}else if(window.ActiveXObject){ 
		    myObjectFilterDispensed = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObjectFilterDispensed.overrideMimeType('text/xml');
		}
		
		myObjectFilterDispensed.onreadystatechange = function (){
			data20 = myObjectFilterDispensed.responseText;
			if (myObjectFilterDispensed.readyState == 4) {
			    document.getElementById('Items_Fieldset').innerHTML = data20;
			}
		}; //specify name of function that will handle server response........
		if (Search_Value != '' && Search_Value != null) {
		    myObjectFilterDispensed.open('GET','Get_Filtered_Items_stock.php?Search_Value='+Search_Value,true);
		}
		myObjectFilterDispensed.send();
	    
	}
</script>


</center>
<br/>
<?php
    include("./includes/footer.php");
?>