<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (!isset($_SESSION['Procedure_Supervisor'])) {
    header("Location: ./deptsupervisorauthentication.php?SessionCategory=Procedure&InvalidSupervisorAuthentication=yes");
}

if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Procedure_Works'])) {
        if ($_SESSION['userinfo']['Procedure_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
?>
<a href="Procedure.php?PatientsBillingWorks=PatientsBillingWorks" class="art-button-green">BACK</a>
<br/>
<br/>

<fieldset>
    <legend align="center">
        PROCEDURE ITEMS CONFIGURATION
    </legend>

  <div class="" style="overflow-y: scroll;">
    <center>
      <div class="" style="background-color:#fff; min-height:400px;">
        <table width="80%;">
          <tr>
            <th width="30%">PROCEDURE</th>
            <th width="30%">ITEMS</th>
            <th width="40%">LIST</th>
          </tr>
          <tr>
            <td>
              <input type="text" name="" value="" placeholder="Enter Procedure Name" id="Procedure_Name_Search" oninput="Search_Procedure();">
              <br>&emsp; <hr>
              <span  id="procedure_list">
                <?php
                  $query = mysqli_query($conn,"SELECT * FROM `tbl_items` WHERE Consultation_Type='Procedure' LIMIT 5");
                  $count_procedure =1;
                  while ($row = mysqli_fetch_assoc($query)) {
                    extract($row);
                    echo $count_procedure."- <input type='radio' name='procedure' onclick='Select_Procedure(\"{$Product_Name}\",\"{$Item_ID}\");'>".$Product_Name."<br><hr>";
                    $count_procedure++;
                  }
                 ?>
             </span>
            </td>
            <td>
              <input type="text" name="" value="" id="item_name" placeholder="Enter Item Name" oninput="Search_Item();">
              <br>&emsp; <hr>
              <span id="item_list">
              <?php
                $item_count = 1;
                $item_query = mysqli_query($conn,"SELECT * FROM `tbl_items` WHERE Consultation_Type='Pharmacy' LIMIT 5");
                while ($row = mysqli_fetch_assoc($item_query)) {
                  extract($row);
                  echo $item_count."- <input type='checkbox' name='procedure' id='item_".$Item_ID."' onclick='Select_Item(\"{$Product_Name}\",\"{$Item_ID}\");'>".$Product_Name."<br><hr>";
                  $item_count++;
                }
              ?>
              </span>
            </td>
            <td>
              <div class="" style="border-radius: 5px;">
                <label for="" style="background-color:#f2f3f4; width:98%;text-align:center;" id="Procedure_Selected">Procedure name</label>
                <input type="hidden" name="" id="Procedure_to_Map" value="">
                <table id="map_list" width="100%;">

                </table>
                <br>
                <input type="button" name="" value="Save" class="art-button-green" style="display:none;float:right" id="btn_save" onclick="Save_Procedure_Mapped_List();" >
                <input type="button" name="" value="Clear" class="art-button-green" style="display:none;float:right" id="btn_clear" onclick="Clear_List();" >
              </div>
            </td>
          </tr>
        </table>
      </div>
    </center>
    </div>
</fieldset>

<?php
include("./includes/footer.php");
?>
<script type="text/javascript">
  function Select_Procedure(Product_Name,Item_ID){
    if(Item_ID != $("#Procedure_to_Map").val()){
      count = 1;
      for(var i = 0;i< added_items.length; i++){
        $("#item_"+added_items[i]).prop('checked',false);
      }
      added_items = [];
      $("#map_list").html('');
      $("#btn_save,#btn_clear").hide();
    }
    $("#Procedure_Selected").text(Product_Name);
    $("#Procedure_to_Map").val(Item_ID);
    $.ajax({
      url:'fetch_procedure_list.php',
      type:'post',
      data:{search_for:'procedure_list',Item_ID:Item_ID},
      dataType:'json',
      success:function(result){
        $("#map_list").html(result.data);
        count=result.count;
      }
    });
  }
  var count =1;
  var added_items = [];
  function Select_Item(Product_Name,Item_ID){
    var Procedure = $("#Procedure_to_Map").val();
    if(Procedure.trim() === ''){
      $("#item_"+Item_ID).prop('checked',false);
      alert("SELECT PROCEDURE FIRST");
      return false;
    }
    if(!added_items.includes(Item_ID)){
      var selected_item='selected_item';
      added_items.push(Item_ID);
      $("#map_list").append('<tr><td width="10%">'+count+'</td><td>'+Product_Name+'</td><td width="8%"><input type="button" value="X"  onclick="Remove_Procedure_Item('+Item_ID+');"></td></tr>');
      $("#btn_save,#btn_clear").show();
      count++;
    }else{
      $("#item_"+Item_ID).prop('checked',true);
      alert("THIS ITEM IS ALREADY SELECTED");
    }
  }
  function Clear_List(){
    count = 1;
    for(var i = 0;i< added_items.length; i++){
      $("#item_"+added_items[i]).prop('checked',false);
    }
    added_items = [];
    $("#map_list").html('');
    $("#btn_save,#btn_clear").hide();
  }
  function Save_Procedure_Mapped_List(){
    var clean_map=[];
    var Procedure = $("#Procedure_to_Map").val();
    var Employee_ID = "<?=$_SESSION['userinfo']['Employee_ID'];?>";
    for(var i=0; i<added_items.length; i++){
      if(added_items[i] !=''){
        clean_map.push(added_items[i]);
      }
    }
    //var Items = JSON.stringify(clean_map);
    $.ajax({
      url:'fetch_procedure_list.php',
      type:'post',
      data:{search_for:'map_items',Items:clean_map,Employee_ID:Employee_ID,Procedure:Procedure},
      success:function(result){
        if(result ='ok'){
          alert("Mapping Successed");
        }
      }
    });
  }

  /*
  search the procedure list
  */
  function Search_Procedure(){
    var Procedure_Name = $("#Procedure_Name_Search").val();
    $.ajax({
      url:'fetch_procedure_list.php',
      type:'post',
      data:{search_for:'Procedure',Procedure_Name:Procedure_Name},
      success:function(result){
        $("#procedure_list").html(result);
      }
    });
  }
  function Search_Item(){
    var Item_Name = $("#item_name").val();
    $.ajax({
      url:'fetch_procedure_list.php',
      type:'post',
      data:{search_for:'Items',Item_Name:Item_Name},
      success:function(result){
        $("#item_list").html(result);
      }
    })
  }
function Remove_Procedure_Item(item){
  $("#"+item).remove();
  var Procedure_ID = $("#Procedure_to_Map").val();
  $.ajax({
    url:'fetch_procedure_list.php',
    type:'post',
    data:{search_for:'remove_item',Procedure_ID:Procedure_ID,Item_ID:item},
    success:function(result){

    }
  });
}
</script>
