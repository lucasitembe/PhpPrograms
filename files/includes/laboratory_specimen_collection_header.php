
<?php
error_reporting(0);
    include("./includes/header.php");
   // echo($_SESSION['OTHER_DEPART_ITEM_ADD']);
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

         <input type='button' name='patient_outpatient' id='patient_outpatient' value='DIRECT DEPT - OUTPATIENT' onclick='outpatient()' class='art-button hide' />
         <input type='button' name='patient_inpatient' id='patient_inpatient' value='DIRECT DEPT - INPATIENT' onclick='inpatient()' class='art-button hide' />
       
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
                echo "<a href='seachpatientfromspeciemenlist.php?PatientLaboratoryResultsThisPage=ThisPage' class='art-button filterByDate'>
                            PATIENT LAB RESULTS
                     </a>";
                }
                
                 if($_SESSION['userinfo']['Laboratory_Works'] == 'yes')
                { 
                  //echo '<a href="searchpatientlaboratorylist.php" class="art-button-green">UNCONSULTED PATIENTS</a>';
                }
                
              if($_SESSION['userinfo']['Laboratory_Works'] == 'yes')
                { 
                  //echo '<button id="attendedlist" class="art-button-green">CONSULTED PATIENTS</button>';
                }
                
                 if($_SESSION['userinfo']['Laboratory_Works'] == 'yes')
                { 
                  //echo '<a href="removedspecimenCollectionPatients.php" class="art-button-green">REMOVED PATIENTS</a>';
                }
                
                
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
       
