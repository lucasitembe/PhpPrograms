<?php
   
    if(isset($_GET['Patient_Name'])){
        $Patient_Name = $_GET['Patient_Name'];   
    }else{
        $Patient_Name = '';
    } 

  
?>
<center><table width =100% border=1 id="table">

 </table></center>

<script>
    function getPatientLaboratoryInfo(patientID = '') {
  

      if(window.XMLHttpRequest) {
    mm = new XMLHttpRequest();
      }
      else if(window.ActiveXObject){ 
    mm = new ActiveXObject('Micrsoft.XMLHTTP');
    mm.overrideMimeType('text/xml');
      }

document.location ='getPatientfromspeciemenlist.php?patientID='+patientID;
  // mm.onreadystatechange= function () {
  //       var xmlResponse =mm.responseXML.documentElement;


  //       var table =xmlResponse.getElementsByTagName('ItemInfo')[0].childNodes[0].nodeValue;


  //       document.getElementById('table').innerHTML =table;

  //   } 

  //     mm.open('GET','getLaboratory_patient.php?patientID='+patientID,true);
  //     mm.send();


   }
  window.onload =getPatientLaboratoryInfo('<?php echo $Patient_Name; ?>');
  </script>