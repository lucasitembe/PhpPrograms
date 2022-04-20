<?php
  include("./includes/header.php");
  include("./includes/connection.php");
  if(!isset($_SESSION['userinfo'])){ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");}
  if(isset($_SESSION['userinfo'])){
    if(isset($_SESSION['userinfo']['Storage_And_Supply_Work'])){
      if($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes'){
        header("Location: ./index.php?InvalidPrivilege=yes");
      }else{
        @session_start();
        if(!isset($_SESSION['Storage_Supervisor'])){ 
        header("Location: ./storagesupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
      }
    } 
    }else{
      header("Location: ./index.php?InvalidPrivilege=yes");
    }
  }else{
      @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");
  }
?>
    <style>
        table,tr,td{
            border-collapse:collapse !important;
            border:none !important;
        }
        #sss:hover{
        background-color:#eeeeee;
        cursor:pointer;
        }
    </style>

    <?php
        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){
            if (isset($_GET['from'])) {
                $From = $_GET['from'];
                if ($From == 'StorageAndSupply') {
                    echo "<a href='storageandsupply.php?StorageAndSupply=StorageAndSupplyThisPage' class='art-button-green'>BACK</a>";
                }
            } else {
                echo "<a href='generalstockbalancereport.php?GeneralStockBalanceReport=GeneralStockBalanceReportThisPage' class='art-button-green'>BACK</a>";
            }
        }
    ?>

 
<br/><br/>
<table width="100%">
  <tr>
    <td width="50%">
      <fieldset style='overflow-y: scroll; height: 400px; background-color: white;' id='List_Area'>
        <legend style="padding:5px;color:white;background-color: #006400;text-align:left;"><b>SUB DEPARTMENTS LIST</b></legend>
        <table width="100%">
          <tr><td width="10%"><b>SN</b></td><td width="75%"><b>SUB DEPARTMENT NAME</b></td><td style="text-align: center;"><b>ACTION</b></td></tr>
          <tr><td colspan="3"><hr></td></tr>
          <?php
            $temp = 0;
            $select = mysqli_query($conn,"select sd.Sub_Department_ID, sd.Sub_Department_Name from 
                                    tbl_department dep, tbl_sub_department sd where
                                    dep.Department_ID = sd.Department_ID and
                                    dep.Department_Location in('Pharmacy','Storage And Supply')
                                    order by Sub_Department_Name") or die(mysqli_error($conn));
            $num = mysqli_num_rows($select);
            if($num > 0){
              while ($data = mysqli_fetch_array($select)) {
                $Sub_Department_ID = $data['Sub_Department_ID'];
                $check = mysqli_query($conn,"select Sub_Department_ID from tbl_stock_balance_sub_departments where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
                $check_num = mysqli_num_rows($check);
                if($check_num == 0){
                  echo '<tr id="sss">
                        <td>'.++$temp.'</td><td>'.ucwords(strtolower($data['Sub_Department_Name'])).'</td>
                        <td style="text-align: center;"><input type="button" value="ADD" class="art-button-green" onclick="Check_Sub_Department_Number('.$data['Sub_Department_ID'].')"></td>
                      </tr>';
                }
              }
            }else{
              echo '<tr><td colspan="3"><b>NO DEPARTMENT SELECTED</b></td></tr>';
            }
          ?>
        </table>
      </fieldset>
    </td>
    <td width="50%">
      <fieldset style='overflow-y: scroll; height: 400px; background-color: white;' id='Selected_Area'>
        <legend style="padding:5px;color:white;background-color: #006400;text-align:left;"><b>SELECTED SUB DEPARTMENTS</b></legend>
        <table width="100%">
          <tr><td width="10%"><b>SN</b></td><td width="75%"><b>SUB DEPARTMENT NAME</b></td><td style="text-align: center;"><b>ACTION</b></td></tr>
          <tr><td colspan="3"><hr></td></tr>
          <?php
            $temp = 0;
            $select = mysqli_query($conn,"select sd.Sub_Department_ID, sd.Sub_Department_Name from tbl_stock_balance_sub_departments sb, tbl_sub_department sd where
                                    sd.Sub_Department_ID = sb.Sub_Department_ID order by Sub_Department_Name") or die(mysqli_error($conn));
            $num = mysqli_num_rows($select);
            if($num > 0){
              while ($data = mysqli_fetch_array($select)) {
                echo '<tr id="sss">
                        <td>'.++$temp.'</td><td>'.ucwords(strtolower($data['Sub_Department_Name'])).'</td>
                        <td style="text-align: center;"><input type="button" value="REMOVE" class="art-button-green" onclick="Remove_Sub_Department('.$data['Sub_Department_ID'].')"></td>
                      </tr>';
              }
            }else{
              echo '<tr><td colspan="3"><b>NO DEPARTMENT SELECTED</b></td></tr>';
            }
          ?>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td colspan="2" style="text-align: center;">
      <b style="color: #037CB0;">Both lists involve only sub departments that manage stocks (e.g Pharmacies, Stores, e.t.c)</b>
    </td>
  </tr>
</table>

<div id="numberAlert">
    <center>Process fail!! Selected sub departments must be four(4) or less. <br/>Please remove selected sub department first</center>
</div>


<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script src="script.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="script.responsive.js"></script>
<script>
   $(document).ready(function(){
      $("#numberAlert").dialog({ autoOpen: false, width:600,height:110, title:'eHMS 2.0 ~ Alert Message',modal: true});
   });
</script>


<script type="text/javascript">
  function Check_Sub_Department_Number(Sub_Department_ID){
    if(window.XMLHttpRequest){
      myObjectCheckNumber = new XMLHttpRequest();
    }else if(window.ActiveXObject){
      myObjectCheckNumber = new ActiveXObject('Micrsoft.XMLHTTP');
      myObjectCheckNumber.overrideMimeType('text/xml');
    }
    myObjectCheckNumber.onreadystatechange = function (){
      data1abc = myObjectCheckNumber.responseText;
      if (myObjectCheckNumber.readyState == 4) {
        var feedback = data1abc;
        if(feedback == 'yes'){
          Add_Sub_Department(Sub_Department_ID);
        }else{
          $("#numberAlert").dialog("open");
        }
      }
    }; //specify name of function that will handle server response........
    
    myObjectCheckNumber.open('GET','Check_Sub_Department_Number.php',true);
    myObjectCheckNumber.send();
  }
</script>

<script type="text/javascript">
  function Remove_Sub_Department(Sub_Department_ID){
    if(window.XMLHttpRequest){
      myObjectRemove = new XMLHttpRequest();
    }else if(window.ActiveXObject){
      myObjectRemove = new ActiveXObject('Micrsoft.XMLHTTP');
      myObjectRemove.overrideMimeType('text/xml');
    }
    myObjectRemove.onreadystatechange = function (){
      data1 = myObjectRemove.responseText;
      if (myObjectRemove.readyState == 4) {
        document.getElementById('Selected_Area').innerHTML = data1;
        Refresh_List();
        Refresh_Selected();
      }
    }; //specify name of function that will handle server response........
    
    myObjectRemove.open('GET','Remove_Sub_Department.php?Sub_Department_ID='+Sub_Department_ID,true);
    myObjectRemove.send();
  }
</script>


<script type="text/javascript">
  function Add_Sub_Department(Sub_Department_ID){
    if(window.XMLHttpRequest){
      myObjectAdd = new XMLHttpRequest();
    }else if(window.ActiveXObject){
      myObjectAdd = new ActiveXObject('Micrsoft.XMLHTTP');
      myObjectAdd.overrideMimeType('text/xml');
    }
    myObjectAdd.onreadystatechange = function (){
      data12 = myObjectAdd.responseText;
      if (myObjectAdd.readyState == 4) {
        document.getElementById('List_Area').innerHTML = data12;
        Refresh_List();
        Refresh_Selected();
      }
    }; //specify name of function that will handle server response........
    
    myObjectAdd.open('GET','Add_Sub_Department.php?Sub_Department_ID='+Sub_Department_ID,true);
    myObjectAdd.send();
  }
</script>

<script type="text/javascript">
  function Refresh_List(){
    if(window.XMLHttpRequest){
      myObjectRefreshList = new XMLHttpRequest();
    }else if(window.ActiveXObject){
      myObjectRefreshList = new ActiveXObject('Micrsoft.XMLHTTP');
      myObjectRefreshList.overrideMimeType('text/xml');
    }
    myObjectRefreshList.onreadystatechange = function (){
      data1112 = myObjectRefreshList.responseText;
      if (myObjectRefreshList.readyState == 4) {
        document.getElementById('List_Area').innerHTML = data1112;
      }
    }; //specify name of function that will handle server response........
    
    myObjectRefreshList.open('GET','Refresh_Sub_Department_List.php',true);
    myObjectRefreshList.send();
  }
</script>

<script type="text/javascript">
  function Refresh_Selected(){
    if(window.XMLHttpRequest){
      myObjectRefreshSelected = new XMLHttpRequest();
    }else if(window.ActiveXObject){
      myObjectRefreshSelected = new ActiveXObject('Micrsoft.XMLHTTP');
      myObjectRefreshSelected.overrideMimeType('text/xml');
    }
    myObjectRefreshSelected.onreadystatechange = function (){
      data1222 = myObjectRefreshSelected.responseText;
      if (myObjectRefreshSelected.readyState == 4) {
        document.getElementById('Selected_Area').innerHTML = data1222;
      }
    }; //specify name of function that will handle server response........
    
    myObjectRefreshSelected.open('GET','Refresh_Sub_Department_List_Selected.php',true);
    myObjectRefreshSelected.send();
  }
</script>

<?php
    include("./includes/footer.php");
?>