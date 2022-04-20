<?php 
include("./includes/connection.php");
session_start();
    if(isset($_POST['addtribe'])){?>
        <div id="adddiv">
            <input type="text"  id="trabe_name" class="form-control" style="display:inline;" value=""><br>
            <input type='button' value="SAVE" class='art-button-green' style="display:inline;" onclick='savetribe()' >
        </div>
        
        <?php
    }
    if(isset($_POST['savename'])){
        $tribe_name = $_POST['tribe_name'];
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        $select_name = mysqli_query($conn, "SELECT tribe_name FROM tbl_tribe where tribe_name LIKE '%$tribe_name%'") or die(mysqli_error($conn));
        if(mysqli_num_rows($select_name)>0){
            echo "Tribe already exist";
        }else{
            $insert_name = mysqli_query($conn, "INSERT INTO tbl_tribe (tribe_name, saved_by)VALUES('$tribe_name', '$Employee_ID')") or die(mysqli_error($conn));
            if($insert_name){
                echo "Saved successful"; 
            }else{
                echo "Failed to save";
            }
        }
    }

    if(isset($_POST['tribesearchdiv'])){
        $Search_tribe = $_POST['Search_tribe'];
        $count = 0;
        $select_name = mysqli_query($conn, "SELECT tribe_name, tribe_id FROM tbl_tribe where tribe_name LIKE '%$Search_tribe%'") or die(mysqli_error($conn));
        if(mysqli_num_rows($select_name)>0){
            while($triberows=mysqli_fetch_assoc($select_name)){
            $tribe_name= $triberows['tribe_name'];
            $tribe_id=$triberows['tribe_id'];  
            $count++;                                  
            echo "
                <tr>
                    <td>$count</td>
                    <td>$tribe_name</td>
                    <td>
                        <form action='' method='POST'>
                            <input type='text' value='$tribe_id'hidden='hidden' name='tribe_id'/>
                            <input type='submit' value='EDIT' class='art-button-green' name='edit_btn'/>
                        </form>
                    </td>                                            
                </tr>
                    ";
            $count++;
            }
        }
    }
?>
