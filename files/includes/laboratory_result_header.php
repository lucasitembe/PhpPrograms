<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Laboratory_Works'])){
	    if($_SESSION['userinfo']['Laboratory_Works'] != 'yes'){
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

            <!-- link menu -->
            <script type="text/javascript">
                
            </script>

<!--            <label style='border: 1px ;padding: 8px;margin-right: 7px;' class='art-button-green'>
                    <select id='patientlist' name='patientlist'>
                            <option selected='selected'>
                                     Chagua Orodha Ya Wagonjwa
                            </option>
                            <option>
                                    Outpatient Credit
                            </option>
                            <option>
                                     Outpatient Cash
                            </option>
                            <option>
                                    Inpatient Credit
                            </option>
                            <option>
                                     Inpatient Cash
                            </option>
                            <option>
                                     Patient From Revenue Center
                            </option>
                            <option>
                                     All Patients List
                            </option>
                    </select>
                    <input type='button' value='VIEW' onclick='gotolink()'>
            </label>-->


<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
		$age = $Today - $original_Date; 
    }
    
        if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Laboratory_Works'] == 'yes')
            { 
            echo "<a href='seachpatientfromspeciemenlist.php' class='art-button-green'>UNCONSULTED PATIENTS</a>";
            }
            
         }  
    
    
        if(isset($_SESSION['userinfo'])){
        if(($_SESSION['userinfo']['Laboratory_Works'] == 'yes') && ($_SESSION['userinfo']['Laboratory_consulted_patients'] == 'yes'))
            { 
            echo "<a href='consultedlabpatientlist.php' id='resultsProvidedList'  class='art-button-green'>CONSULTED PATIENTS</a>";
            } else {
               // echo 'Not allowed';
             echo "<button class='art-button-green' onclick='return consulted_access_Denied();'>CONSULTED PATIENTS</button>";   
            }
            
            
            
             
         }   
        
      if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Laboratory_Works'] == 'yes')
            { 
            echo "<a href='laboratory.php?section=Laboratory&LaboratoryWorks=LaboratoryWorksThisPage' class='art-button-green'>BACK</a>";
            }
    }  
?>


<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>

<script type='text/javascript'>
    function consulted_access_Denied(){ 
   alert("Access denied, please contact your system administrator!");
   //document.location = "./index.php";
    }
</script>