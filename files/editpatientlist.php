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

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
   <!-- <a href='#?SearchListPatientBilling=SearchListPatientBillingThisPage' class='art-button-green'>
        INPATIENT LIST
    </a>-->
<?php  } } ?>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
    <a href='receptionworkspage.php?ReceptionworkspageForm=ReceptionworkspageFormThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>


<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        
        $age = $Today - $original_Date; 
    }
?>

<?php

//    select patient information to perform check in process
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID']; 
        $select_Patient = mysqli_query($conn,"select
                            Old_Registration_Number,Title,Patient_Name,
                                Date_Of_Birth,
                                    Gender,pr.Region,pr.District,pr.Ward,
                                        Member_Number,Member_Card_Expire_Date,
                                            pr.Phone_Number,Email_Address,Occupation,
                                                Employee_Vote_Number,Emergence_Contact_Name,
                                                    Emergence_Contact_Number,Company,Registration_ID
                                                        Employee_ID,Registration_Date_And_Time,Guarantor_Name,
                                                        Registration_ID
                                      
                                      
                                      
                                      
                                      from tbl_patient_registration pr, tbl_sponsor sp 
                                        where pr.Sponsor_ID = sp.Sponsor_ID and 
                                              Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select_Patient);
        
        if($no>0){
            while($row = mysqli_fetch_array($select_Patient)){
                $Registration_ID = $row['Registration_ID'];
                $Old_Registration_Number = $row['Old_Registration_Number'];
                $Title = $row['Title'];
                $Patient_Name = $row['First_Name'];
                $Date_Of_Birth = $row['Date_Of_Birth'];
                $Gender = $row['Gender'];
                $Region = $row['Region'];
                $District = $row['District'];
                $Ward = $row['Ward'];
                $Guarantor_Name = $row['Guarantor_Name'];
                $Member_Number = $row['Member_Number'];
                $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
                $Phone_Number = $row['Phone_Number'];
                $Email_Address = $row['Email_Address'];
                $Occupation = $row['Occupation'];
                $Employee_Vote_Number = $row['Employee_Vote_Number'];
                $Emergence_Contact_Name = $row['Emergence_Contact_Name'];
                $Emergence_Contact_Number = $row['Emergence_Contact_Number'];
                $Company = $row['Company'];
                $Employee_ID = $row['Employee_ID'];
                $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
               // echo $Ward."  ".$District."  ".$Ward; exit;
            }
            $age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";
        }else{
            $Registration_ID = '';
            $Old_Registration_Number = '';
            $Title = '';
            $Patient_Name = '';
            $Date_Of_Birth = '';
            $Gender = '';
            $Region = '';
            $District = '';
            $Ward = '';
            $Guarantor_Name = '';
            $Member_Number = '';
            $Member_Card_Expire_Date = '';
            $Phone_Number = '';
            $Email_Address = '';
            $Occupation = '';
            $Employee_Vote_Number = '';
            $Emergence_Contact_Name = '';
            $Emergence_Contact_Number = '';
            $Company = '';
            $Employee_ID = '';
            $Registration_Date_And_Time = '';            
        }
    }else{
            $Registration_ID = '';
            $Old_Registration_Number = '';
            $Title = '';
            $Patient_Name = '';
            $Date_Of_Birth = '';
            $Gender = '';
            $Region = '';
            $District = '';
            $Ward = '';
            $Guarantor_Name = '';
            $Member_Number = '';
            $Member_Card_Expire_Date = '';
            $Phone_Number = '';
            $Email_Address = '';
            $Occupation = '';
            $Employee_Vote_Number = '';
            $Emergence_Contact_Name = '';
            $Emergence_Contact_Number = '';
            $Company = '';
            $Employee_ID = '';
            $Registration_Date_And_Time = '';
        }
?>
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>

<script language="javascript" type="text/javascript">
    function searchPatient(Patient_Name){
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=400px src='Edit_Patient_List_Iframe.php?Patient_Name="+Patient_Name+"'></iframe>";
    }
</script>
<script language="javascript" type="text/javascript">
    function Search_Patient_Using_Number(Patient_Number){
	var Patient_Name = document.getElementById("Search_Patient").value;
	
	if (Patient_Name != '' && Patient_Name != null) {
	    document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=400px src='Edit_Patient_List_Iframe.php?Patient_Number="+Patient_Number+"&Patient_Name="+Patient_Name+"'></iframe>";
	}else{
	    document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=400px src='Edit_Patient_List_Iframe.php?Patient_Number="+Patient_Number+"'></iframe>";
	}
    }
</script>
<script>
    function Search_Patient_By_Phone_Number(Phone_Number) {
	
	var Patient_Name = document.getElementById("Search_Patient").value;
	var Patient_Number = document.getElementById("Patient_Number").value;
	
	if ((Patient_Name != '' && Patient_Name != null) && (Patient_Number != '' && Patient_Number != null)) {
	    document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=400px src='Edit_Patient_List_Iframe.php?Patient_Name="+Patient_Name+"&Patient_Number="+Patient_Number+"&Phone_Number="+Phone_Number+"'></iframe>";
	}else if ((Patient_Name != '' && Patient_Name !=null) && (Patient_Number == '' && Patient_Number == null)) {
	    document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=400px src='Edit_Patient_List_Iframe.php?Patient_Name="+Patient_Name+"&Phone_Number="+Phone_Number+"'></iframe>";
	}else if ((Patient_Name == '' && Patient_Name ==null) && (Patient_Number != '' && Patient_Number != null)) {
	    document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=400px src='Edit_Patient_List_Iframe.php?Patient_Number="+Patient_Number+"&Phone_Number="+Phone_Number+"'></iframe>";
	}else{
	    document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=400px src='Edit_Patient_List_Iframe.php?Phone_Number="+Phone_Number+"'></iframe>";
	}
    }
</script>
<br/><br/>
<center>
    <table width=100%>
        <tr>
            <td>
                <input style="text-align: center" type='text' name='Search_Patient' id='Search_Patient' onkeypress='searchPatient(this.value)' placeholder='~~~~~~~~~~~~~~  Search Patient Name  ~~~~~~~~~~~~~'>
            </td>
	    <td>
                <input style="text-align: center" type='text' name='Patient_Number' id='Patient_Number' onkeyup='Search_Patient_Using_Number(this.value)' onkeypress='Search_Patient_Using_Number(this.value)' placeholder='~~~~~~~~~~~~~~  Search Patient Number  ~~~~~~~~~~~~~'>
            </td>
	    <td>
                <input style="text-align: center" type='text' name='Phone_Number' id='Phone_Number' onkeypress='Search_Patient_By_Phone_Number(this.value)' onkeyup='Search_Patient_By_Phone_Number(this.value)' placeholder='~~~~~~~~~~~~~~  Search Phone Number  ~~~~~~~~~~~~~'>
            </td>
        </tr>
        
    </table>
</center>
<br/>
<fieldset>  
            <legend align=center><b>REGISTERED PATIENTS LIST</b></legend>
        <center>
            <table width=100% border=1>
                <tr>
            <td id='Search_Iframe'>
		<iframe width='100%' height=380px src='Edit_Patient_List_Iframe.php'></iframe>
            </td>
        </tr>
            </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>