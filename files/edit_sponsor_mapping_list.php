<?php
    include("./includes/connection.php");
    include("./includes/header.php");

    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){
?>

    <a href='sponsor_mapping_list.php' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>

<br/><br/><br/><br/><br/><br/>
<fieldset>

  <legend ><b>EDIT SPONSOR</b></legend>

  <!-- Attach sponsors to their specific ledgers -->

  <div class="" style="background-color:#fff; width:100%;border-radius: 2px; float:left; position:relative; margin-right:5px; padding: 2px 2px 2px 2px;">
    <?php
  $list = array();
  $sponsor_list=mysqli_query($conn,"SELECT sp.Sponsor_ID, sp.Guarantor_Name FROM tbl_sponsor sp",$connection) or die(mysqli_error($conn));
  while ($row=mysqli_fetch_assoc($sponsor_list)) {
    extract($row);
    $list[$Sponsor_ID] = $Guarantor_Name;

  }

      include("gaccountModules/ehms_gaccounting_connection.php");
    ?>
    <table width="100%" style="font-size:15px;">
      <tr>
        <th>SN</th>
        <th>Sponsor Name</th>
        <th>CR Ledger</th>
        <th>CR Ledger</th>
        <th>Edit</th>
      </tr>
      <?php

        function getSponsorName(){
            return mysqli_fetch_assoc(mysqli_query($conn,"SELECT  sp.Guarantor_Name FROM tbl_sponsor sp WHERE sp.Sponsor_ID = 1138 ",$connection))['Guarantor_Name'];
        }

        $map_list=mysqli_query($conn,"SELECT slm.Sponsor_ID, crl.ledger_id as crl_id, drl.ledger_id as drl_id, crl.ledger_name as crl_ledger, drl.ledger_name as drl_ledger FROM tbl_sponsor_ledger_map slm, tbl_ledgers crl, tbl_ledgers drl WHERE drl.ledger_id = slm.DR_ledger_id AND crl.ledger_id = slm.CR_ledger_id",$gaccountConn);
        $count=1;
        while ($row = mysqli_fetch_assoc($map_list)) {
          echo "<tr>
                  <td>".$count."</td>
                  <td>".$list[$row['Sponsor_ID']]."</td>
                  <td>".$row['crl_ledger']."</td>
                  <td>".$row['drl_ledger']."</td>
                  <td style='text-align:center;'><a href='edit_sponsor_mapping_list.php?Sponsor_ID=".$row['Sponsor_ID']."&crl_id=".$row['crl_id']."&drl_id=".$row['drl_id']."' class='art-button-green'>edit</a></td>
                </tr>";
                $count++;
        }
       ?>
    </table>
  </div>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>
