<?php
include("../includes/connection.php");


if(isset($_POST['action'])){
    if($_POST['action']=='parameterList'){
       $item=$_POST['id'];  
    }elseif($_POST['action']=='AddparameterList'){
       $item=$_POST['item'];
    }elseif ($_POST['action']=='deleteparameterList'){
       $item=$_POST['item'];
    }
    
echo '<div id="viewAllParameters">
   <select id="Laboratory_Parameter_ID">';
    echo '<option selected="selected"></option>';
    $data = mysqli_query($conn,"SELECT * FROM tbl_parameters");
    while($row = mysqli_fetch_array($data)){
     echo "<option class='parameterName' value='".$row['parameter_ID']."'>".$row['Parameter_Name']."</option>";
    }
  echo '</select>
    <button id="assignsubmit" name="'.$item.'" class="art-button-green">ADD PARAMETER</button> <button id="Newparameter" class="art-button-green">ADD NEW PARAMETER</button>
    </div><br />';  
}


if(isset($_POST['action'])){
  if($_POST['action']=='parameterList'){
                $item=$_POST['id'];  
                echo '<table style="width:100%" class="hiv_table parameterListings">';
                echo '<tr>
                    <th>S/N</th>
                    <th>Paramenter Name</th>
                    <th>Action</th>
                </tr>';    
                $i=1;
                $select_sample =mysqli_query($conn,"SELECT * FROM tbl_tests_parameters inner join tbl_parameters on ref_parameter_ID=parameter_ID where ref_item_ID='".$item."'");
                while($disp = mysqli_fetch_array($select_sample)){                 
                echo "<tr>";
                echo "<td style='color:blue;border:1px solid #ccc;width:4px;'>".$i."</td>";
                echo "<td style='color:blue;border:1px solid #ccc;width:500px;'>".$disp['Parameter_Name']."</td>
                     <td style='color:blue;border:1px solid #ccc;width:4px;'><button class='removeParameter' name='".$disp['ref_item_ID']."' id='".$disp['ref_parameter_ID']."'>Remove</button></td>";
                echo "</tr>";
                $i++;
               }
               
               echo '</table>';
        } elseif ($_POST['action']=='AddparameterList'){
        $item=$_POST['item'];
        $parameter=$_POST['itemVal'];
      // echo '<div class="itemId" id="'.$item.'" style="display:none"></div>';
        $getparameter="SELECT * FROM tbl_tests_parameters WHERE ref_parameter_ID='".$parameter."' AND ref_item_ID='".$item."'";
        $getNumber=  mysqli_query($conn,$getparameter);
        $number_rows=  mysqli_num_rows($getNumber);
        if($number_rows>0){
           // echo 'This parameter is already assigned';  
        } else {
            $save="INSERT INTO tbl_tests_parameters(ref_parameter_ID,ref_item_ID) VALUES ('$parameter','$item')";
            $result=  mysqli_query($conn,$save);
            if($result){
                 
            } else {
               // echo 'Failed to assign parameter';
            }
        }
        
      // echo "SELECT * FROM tbl_tests_parameters inner join tbl_parameters on ref_parameter_ID=parameter_ID where ref_item_ID='".$item."'";
       //exit();
//        
                echo '<table style="width:100%" class="hiv_table parameterListings">';
                echo '<tr>
                    <th>S/N</th>
                    <th>Paramenter Name</th>
                    <th>Action</th>
                </tr>';
                $i=1;
                $select_sample =mysqli_query($conn,"SELECT * FROM tbl_tests_parameters inner join tbl_parameters on ref_parameter_ID=parameter_ID where ref_item_ID='".$item."'");
                while($disp = mysqli_fetch_array($select_sample)){                 
                echo "<tr>";
                echo "<td style='color:blue;border:1px solid #ccc;width:4px;'>".$i."</td>";
                echo "<td style='color:blue;border:1px solid #ccc;width:500px;'>".$disp['Parameter_Name']."</td>
                        <td style='color:blue;border:1px solid #ccc;width:4px;'><button class='removeParameter' name='".$disp['ref_item_ID']."' id='".$disp['ref_parameter_ID']."'>Remove</button></td>";
                echo "</tr>";
                $i++;
               }
               
               
            }elseif ($_POST['action']=='deleteparameterList') {
             //Remove parameter here
                $item=$_POST['item'];
                $parameter=$_POST['itemVal'];
              //  echo '<div class="itemsId" id="'.$item.'" style="display:none"></div>';
                $delete="DELETE FROM tbl_tests_parameters WHERE ref_parameter_ID='".$parameter."' AND ref_item_ID='".$item."'";
                $query=  mysqli_query($conn,$delete);
                if($query){

                } else {
                    echo '<script>alert("Parameter unset failed")</script>';
                }

                echo '<table style="width:100%" class="hiv_table parameterListings">';
                echo '<tr>
                    <th>S/N</th>
                    <th>Paramenter Name</th>
                    <th>Action</th>
                </tr>';

                 $i=1;
//                 echo "SELECT * FROM tbl_tests_parameters inner join tbl_parameters on ref_parameter_ID=parameter_ID where ref_item_ID='".$item."'";
//                 
//                 
                $select_sample =mysqli_query($conn,"SELECT * FROM tbl_tests_parameters inner join tbl_parameters on ref_parameter_ID=parameter_ID where ref_item_ID='".$item."'");
                while($disp = mysqli_fetch_array($select_sample)){                 
                echo "<tr>";
                echo "<td style='color:blue;border:1px solid #ccc;width:4px;'>".$i++."</td>";
                echo "<td style='color:blue;border:1px solid #ccc;width:500px;'>".$disp['Parameter_Name']."</td>
                        <td style='color:blue;border:1px solid #ccc;width:4px;'><button class='removeParameter' name='".$disp['ref_item_ID']."' id='".$disp['ref_parameter_ID']."'>Remove</button></td>";
                echo "</tr>";
               }
               echo '</table>';
        
    }elseif ($_POST['action']=='AddNewparameterDetails'){
        $ParameterName=$_POST['ParameterName'];
        $unitofmeasure=$_POST['unitofmeasure'];
        $lowervalue=$_POST['lowervalue'];
        $highervalue=$_POST['highervalue'];
        $Operator=$_POST['Operator'];
        $results=$_POST['results'];
        $insert=sprintf("INSERT INTO tbl_parameters (Parameter_Name,unit_of_measure,lower_value,operator,higher_value,result_type) VALUES ('%s','%s','%s','%s','%s','%s')",
         mysqli_real_escape_string($conn,$ParameterName),  mysqli_real_escape_string($conn,$unitofmeasure),  mysqli_real_escape_string($conn,$lowervalue),  mysqli_real_escape_string($conn,$Operator),  mysqli_real_escape_string($conn,$highervalue),  mysqli_real_escape_string($conn,$results));
        $query=  mysqli_query($conn,$insert);
        if($query){
            echo 'Parameter registered successfully';
        }  else {
            echo 'Parameter not successfully saved';
        }
    }
}if(isset($_GET['action']) && $_GET['action']=='getParameters'){
    echo '<option selected="selected"></option>';
    $data = mysqli_query($conn,"SELECT * FROM tbl_parameters");
    while($row = mysqli_fetch_array($data)){
     echo "<option class='parameterName' value='".$row['parameter_ID']."'>".$row['Parameter_Name']."</option>";
    } 
}
?>

<style>
    
    select #Laboratory_Parameter_ID {
        width: 500px;
    }
</style>