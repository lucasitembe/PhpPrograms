<?php
include("./includes/functions.php");

include("./includes/header.php");

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if(isset($_GET['dhis2_dataset_name'])){
  $dhis2_dataset_name=$_GET['dhis2_dataset_name'];
}else{
  $dhis2_dataset_name="";
}
if(isset($_GET['dataset_id'])){
   $dataset_id=$_GET['dataset_id'];
}else{
    $dataset_id="";
}
if(isset($_GET['dhis2_auto_dataset_id'])){
    $dhis2_auto_dataset_id=$_GET['dhis2_auto_dataset_id'];
}else{
    $dhis2_auto_dataset_id="";
}


?>
<a href="dhis2_add_data_elements.php?dhis2_auto_dataset_id=<?= $dhis2_auto_dataset_id ?>&&dhis2_dataset_name=<?= $dhis2_dataset_name ?>&&dataset_id=<?= $dataset_id ?>" class='art-button-green'>ADD DATA ELEMENT</a>
<a href="data_element_values_source.php?dhis2_auto_dataset_id=<?= $dhis2_auto_dataset_id ?>&&dhis2_dataset_name=<?= $dhis2_dataset_name ?>&&dataset_id=<?= $dataset_id ?>" class='art-button-green'>DATA ELEMENT VALUES SOURCE SETUP</a>
<a href='dhis2_hmis_dataelements.php?dhis2_auto_dataset_id=<?= $dhis2_auto_dataset_id ?>&&dhis2_dataset_name=<?= $dhis2_dataset_name ?>&&dataset_id=<?= $dataset_id ?>' class='art-button-green'>
        BACK
</a>
<br/><br/>
<style>
    table,tr,td{
        border:none!important;
    }
</style>
<fieldset>
    <legend align="right" style="text-align:right;background-color:#006400;color:white;padding:5px;"><b><?= $dhis2_dataset_name ?></b></legend>
    <div class="row">
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header">
                    <h4>DHIS2 Data Element Groups</h4>
                </div>
                <div class="box-body" style="height: 500px;overflow-y: auto;overflow-x: auto">
                    <table class="table">
                        <tr>
                            <td width="5%">S/No.</td>
                            <td width='10%'>DataElement Group</td>
                            <td width="75%">DataElement Name</td>
                            <td width='10%' >Assign To Value Source</td>
                        </tr>
                        <tbody>
                            <?php 
                            $sql_select_data_element_id_result=mysqli_query($conn,"SELECT dhis2_dataelement_id,displayname FROM tbl_dhis2_dataelements WHERE dataset_id='$dataset_id' GROUP BY dhis2_dataelement_id") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_data_element_id_result)>0){
                                $count=1;
                                while($data_elemetn_id_rows=mysqli_fetch_assoc($sql_select_data_element_id_result)){
                                    $dhis2_dataelement_id=$data_elemetn_id_rows['dhis2_dataelement_id'];
                                    $displayname=$data_elemetn_id_rows['displayname'];
                                    echo "<tr>
                                            <td>$count.</td>
                                            <td>$dhis2_dataelement_id</td>
                                            <td>$displayname</td>
                                            <td>
                                                <input type='button' value='>>' onclick='open_data_element_item_from_group(\"$dhis2_dataelement_id\")'/>
                                            </td>
                                         </tr>";
                                    $count++;
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header">
                    <h4>Merging Area</h4>
                    
                </div>
                <div class="box-body" style="height: 500px">
                    <div class='row' style='height:250px;overflow-y: auto;overflow-x: hidden'>
                        <div class="col-md-12">
                            <table class="table">
                                <tr>
                                <caption><b>LIST OF ASSIGNED DATA ELEMENT TO MERGE TO VALUES SOURCE</b></caption>
                                </tr>
                                <tr>
                                    <td>S/No.</td>
                                    <td>Data Element Name</td>
                                </tr>
                                <tbody id='data_element_opening_area'>
                                    <tr><td><input type="text" value=""class='hide' id="selected_dhis2_dataelement_id"/></tr></td>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <hr/>
                        </div>
                    </div>
                    <div class='row' style='height:230px;overflow-y: auto;overflow-x: hidden'>
                        <div class="col-md-12">
                            <table class="table">
                                <tr>
                                <caption><b>LIST OF VALUES SOURCE ITEMS</b></caption>
                                </tr>
                                <tr>
                                    <td>S/No.</td>
                                    <td>ITEM NAME</td>
                                    <td>ACTION</td>
                                </tr>
                                <tbody id="list_of_merged_data_source_items">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header">
                    <div class="col-md-6"><b>Select Data Element Value Source</b></div>
                    <div class="col-md-6">
                        <select class="form-control" id="data_element_value_source" onchange="fill_value_source_area()">
                            <option>Select Data Element Value Source</option>
                            <option>Diseases</option>
                            <option>Pharmaceutical</option>
                            <option>Laboratory</option>
                            <option>Procedure</option>
                            <option>Surgery</option>
                            <option>Date Range</option>
                            <option>Other Item</option>
                        </select>
                    </div>
                </div>
                <div class="box-body" style="height: 490px;overflow-y: auto;overflow-x: hidden">
                    <table class="table">
                        <tr>
                        <caption><b class="col-md-6">LIST OF ITEM VALUE SOURCE</b><span class="col-md-6"><input type="text" id='search_value' onkeyup="search_item()"placeholder="Search Item" class="form-control"/></span></caption>
                        </tr>
                        <tr>
                            <td>ACTION</td>
                            <td>ITEM NAME</td>
                            <td>S/No.</td>
                        </tr>
                        <tbody id="list_of_values_source_items">
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</fieldset>
<script>
    function fill_value_source_area(){
      var data_element_value_source=$("#data_element_value_source").val();
      $.ajax({
          type:'POST',
          url:'ajax_get_value_source_items.php',
          data:{data_element_value_source:data_element_value_source},
          success:function(data){
             $("#list_of_values_source_items").html(data); 
          },
          error:function(x,y,z){
              console.log(z);
          }
      });
    }
    function search_item(){
        var search_value=$("#search_value").val();
        var data_element_value_source=$("#data_element_value_source").val();
          $.ajax({
          type:'POST',
          url:'ajax_get_value_source_items.php',
          data:{data_element_value_source:data_element_value_source,search_value:search_value},
          success:function(data){
             $("#list_of_values_source_items").html(data); 
          },
          error:function(x,y,z){
              console.log(z);
          }
      });
    }
    function open_data_element_item_from_group(dhis2_dataelement_id){
       var dataset_id='<?= $dataset_id ?>';
       $.ajax({
          type:'POST',
          url:'ajax_get_data_element_group_items.php',
          data:{dhis2_dataelement_id:dhis2_dataelement_id,dataset_id:dataset_id},
          success:function(data){
             $("#data_element_opening_area").html(data); 
             get_list_of_data_element_value_source(dhis2_dataelement_id,dataset_id)
          },
          error:function(x,y,z){
              console.log(z);
          }
      }); 
    }
    function merge_value_source_to_data_element(Item_ID_disease_ID){
       var data_element_value_source=$("#data_element_value_source").val();
       var selected_dhis2_dataelement_id=$("#selected_dhis2_dataelement_id").val();
       var dataset_id='<?= $dataset_id ?>';
       if(selected_dhis2_dataelement_id==""){
           alert("Select dataelement goup to merge")
       }else{
            $.ajax({
               type:'POST',
               url:'ajax_merge_value_source_to_data_element.php',
               data:{selected_dhis2_dataelement_id:selected_dhis2_dataelement_id,Item_ID_disease_ID:Item_ID_disease_ID,dataset_id:dataset_id,data_element_value_source:data_element_value_source},
               success:function(data){
                 // $("#data_element_opening_area").html(data); 
                 if(data!="fail"){
                    // alert("Merged Successfully");
                     get_list_of_data_element_value_source(selected_dhis2_dataelement_id,dataset_id)
                 }
               },
               error:function(x,y,z){
                   console.log(z);
               }
           });  
        }
 }
 function get_list_of_data_element_value_source(dhis2_dataelement_id,dataset_id){
      var data_element_value_source=$("#data_element_value_source").val();
      $.ajax({
               type:'POST',
               url:'ajax_get_list_of_data_element_value_source.php',
               data:{dhis2_dataelement_id:dhis2_dataelement_id,dataset_id:dataset_id,data_element_value_source:data_element_value_source},
               success:function(data){
                 $("#list_of_merged_data_source_items").html(data);
                 console.log(data)
               },
               error:function(x,y,z){
                   console.log(z);
               }
           });
 }
 function remove_all_items_merged_to_this_value_source(Item_ID_disease_ID,dhis2_dataelement_id,dataset_id){
      $.ajax({
               type:'POST',
               url:'ajax_remove_all_items_merged_to_this_value_source.php',
               data:{dhis2_dataelement_id:dhis2_dataelement_id,dataset_id:dataset_id,Item_ID_disease_ID:Item_ID_disease_ID},
               success:function(data){
                 get_list_of_data_element_value_source(dhis2_dataelement_id,dataset_id)
               },
               error:function(x,y,z){
                   console.log(z);
               }
           });
 }
</script>
<?php
include("./includes/footer.php");
?>
