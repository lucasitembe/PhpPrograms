<?php
 include("./includes/connection.php");

 if(isset($_POST['ward_search_value'])){
    $ward_search_value = str_replace(" ", "%", $_POST['ward_search_value']);
}else{
    $ward_search_value = '';
}

// echo $ward_search_value; exit;

$html = '
        <div id="depertments_list">
                        <table class="table">';

                        $sql_result=mysqli_query($conn,"SELECT Hospital_Ward_ID,Hospital_Ward_Name FROM tbl_hospital_ward WHERE Hospital_Ward_Name like '%$ward_search_value%'") or die(mysqli_error($conn));
                        if(mysqli_num_rows($sql_result)>0){
                            while($Ward_Row=mysqli_fetch_assoc($sql_result)){
                                $Ward_ID=$Ward_Row['Hospital_Ward_ID'];
                                    $Hospital_Ward_Name=$Ward_Row['Hospital_Ward_Name'];
                                    $html .= "<tr>
                                                    <td>
                                                        <label style='font-weight:normal'>
                                                            <input type='checkbox' class='Ward_ID' name='Ward_ID' value='$Ward_ID'> $Hospital_Ward_Name
                                                        </label>
                                                    </td>
                                                </tr>";
                                }
                            }else{
                                $html .=  "<tr>
                                        <td>
                                            <label style='color:red;'>
                                                SORRY, NO RESULT FOUND!
                                            </label>
                                        </td>  
                                    </tr>";
                            }
                       $html .=  '</table>
                    </div>';

                    echo $html;
?>