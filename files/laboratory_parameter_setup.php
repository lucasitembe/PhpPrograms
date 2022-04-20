

<?php
        $itemID=$_GET['Item_ID'];
	include("./includes/header.php");
	include("./includes/connection.php");
    	
        if(!isset($_SESSION['userinfo'])){ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");}
                     if(isset($_SESSION['userinfo'])){
    		              if(isset($_SESSION['userinfo']['Laboratory_Works'])){
    			                     if($_SESSION['userinfo']['Laboratory_Works'] != 'yes'){
                                                header("Location: ./index.php?InvalidPrivilege=yes");} 
    		                                          }else{
    				                                        header("Location: ./index.php?InvalidPrivilege=yes");
    			                                                 }
                                                            }else{
                                                                 @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes"); }

                                                    if(isset($_SESSION['userinfo'])){
                                                             if($_SESSION['userinfo']['Laboratory_Works'] == 'yes'){
                                                                         echo "<a href='searchlabtestlistparameter.php' class='art-button-green'>BACK</a>";
                                                                                        }
                                                                                            }
               
//difine the qualitative template
                      $qualitative_table ='<table width="100%">';
                      $qualitative_table .='<tr><th>Paramenter Name</th><th>Normal Value</th></tr>';
                      $qualitative_table .='<tr><td style="width:300px">';
                      $qualitative_table .='<select name="Laboratory_Parameter_ID" id="Laboratory_Parameter_ID"  required="required"  style="width:100%">';
                       $qualitative_table .='<option selected="selected"></option>';
                                                                         
                                                                             $data = mysqli_query($conn,"SELECT * FROM tbl_laboratory_parameters");
                                                                                    while($row = mysqli_fetch_array($data)){
                                                                               $qualitative_table .='<option  value="'.$row["Laboratory_Parameter_ID"].'">'.$row["Laboratory_Parameter_Name"].'</option>';
                                                                                                         }
                                             
                                    $data = mysqli_query($conn,"SELECT * FROM tbl_parameters");
                                              while($row = mysqli_fetch_array($data)){
                                            }
?>
<?php


if(!isset($_GET['addLaboratorySpecimen']) & !isset($_GET['removeParameter'])){
                    ?>
                <center>               
                <table style="width:90%;margin_top:20px;" class="hiv_table">
                    <tr><td colspan="4" style="width:100%"><hr></td></tr>
                    <tr><td colspan="4" style="width:100%"><center><b>LAB PARAMETERS SETTINGS</b></center></td></tr>
                    <tr><td colspan="4" style="width:100%"><hr></td></tr>
                    <tr>
                        <td>
                            <fieldset>   
                                    <center>
                                            <table width=100%>
                                                  <tr>
                                                        <td style="width:70px;text-align:right;"><b>Test Name</b></td>
                                                        <td style="width:400px" colspan="2">
                                                             <?php 
                                                                if(!isset($_GET['Item_ID'])){
                                                                                 echo "<select name='Item_ID' id='Item_ID' required='required'  style='width:100%' >
                                                                                        <option selected='selected'></option>";
                                                                                              $data = mysqli_query($conn,"SELECT * FROM tbl_items where Consultation_Type='Laboratory'");
                                                                                                      while($row = mysqli_fetch_array($data)){
                                                                                                              echo "<option class='itemId' id='".$row['Item_ID']."'>".$row['Product_Name']."</option>";
                                                                                                                            }
                                                                                                                                 echo "</select>";
                                                                }else{
                                                                        $data = mysqli_query($conn,"SELECT * FROM tbl_items where Item_ID='".$_GET['Item_ID']."'");
                                                                        $row = mysqli_fetch_array($data);
                                                                                echo "<input name='' class='itemId' id='".$_GET['Item_ID']."' type='text' value='".$row['Product_Name']."'>";
                                                                                                 }
                                                                                                     ?>
                                                        </td>
                                
                                                 </tr>
                                                 <tr><td colspan="7" style="width:100%"><hr></td></tr>
                                                 </table>

                                                 <div  id="temp_result">
                                                 <table width="100%">
                                                 <tr>
                                                     <th>Paramenter Name</th>
                                                        
                                                  </tr>
                                                 <tr>      
                                                     <td style="width:300px">
                                                <select name='Laboratory_Parameter_ID' id='Laboratory_Parameter_ID'  required='required'  style="width:100%" >
                                                        <option selected='selected'></option>
                                                            <?php
                                                                $data = mysqli_query($conn,"SELECT * FROM tbl_parameters");
                                                                   while($row = mysqli_fetch_array($data)){
                                                                    echo "<option class='parameterName' value='".$row['parameter_ID']."'>".$row['Parameter_Name']."</option>";
                                                                    }
                                                            ?> 
                                                </select>
                                                    </td>

                                                    <td style='text-align: center;'>
                                                        <input type='button' name='submit' id='assignsubmit' value='ADD' class='art-button-green'>
                                                         
                                                    </td>
                                                     </tr>
                                                      <tr>
                                                          <td colspan="7" style="width:100%"><hr></td>
                                                      </tr>
                                                      </table>
                                                    </div>
                                                    <table  width="100%">
                                                        <tr><td colspan="7" style="width:100%">
                                                                <div id="relodParameter" style="width:100%;height:350px;background-color:white;overflow:scroll;">
                                                       
                                                                            <center>
                                                                                <table style="width:99%" class="hiv_table">

                                                                                            <tr>
                                                                                            <th>S/N</th>
                                                                                                <th>Paramenter Name</th>
                                                                                                <th>Action</th>
                                                                                                <th></th>
                                                                                            </tr>


                        <?php
                        $i=1;
                        $select_sample =mysqli_query($conn,"SELECT * FROM tbl_tests_parameters inner join tbl_parameters on ref_parameter_ID=parameter_ID 
                         where ref_item_ID='".filter_input(INPUT_GET, 'Item_ID')."'");
                        while($disp = mysqli_fetch_array($select_sample)){                 
                        echo "<tr>";
                        echo "<td style='color:blue;border:1px solid #ccc;width:4px;'>".$i."</td>";
                        echo "<td style='color:blue;border:1px solid #ccc;width:500px;'>".$disp['Parameter_Name']."</td>
                                <td style='color:blue;border:1px solid #ccc;width:4px;'><button class='removeParameter' name='".$itemID."' id='".$disp['ref_parameter_ID']."'>Remove</button></td>";
                        echo "</tr>";
                        $i++;
                       }
                        

                        }
                            ?>
                </table>
                </center>
                </div>
                </td>
                            </tr>
                            </table>  
                                        
                                        
                                        
                                        
                        </center>
                </fieldset>
                        </td>
                    </tr>
                </table>      
                        </center>
                <?php
                 include("./includes/footer.php");
                 ?>
<script src="css/jquery.js"></script>
<script src="css/jquery-ui.js"></script>
<script src="css/scripts.js"></script>