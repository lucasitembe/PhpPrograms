<?php
 include("./includes/connection.php");

 if(isset($_POST['sponsor_search_value'])){
    $Guarantor_Name = str_replace(" ", "%", $_POST['sponsor_search_value']);
}else{
    $Guarantor_Name = '';
}


 $html = '<div id="sponsors_list"><table class="table">';

$sql_result=mysqli_query($conn,"SELECT * FROM tbl_sponsor  WHERE  Guarantor_Name like '%$Guarantor_Name%' ") or die(mysqli_error($conn));
           if(mysqli_num_rows($sql_result)>0){
            while($sponsor_rows=mysqli_fetch_assoc($sql_result)){
                $Sponsor_ID=$sponsor_rows['Sponsor_ID'];
                $Guarantor_Name =$sponsor_rows['Guarantor_Name'];
                        $html.= "<tr>
                                    <td>
                                        <label style='font-weight:normal'>
                                            <input type='checkbox' class='Sponsor_ID' name='Sponsor_ID' value='$Sponsor_ID'> $Guarantor_Name
                                        </label>
                                    </td>
                                </tr>";
                }
            }else{
                $html.= "<tr>
                            <td>
                                <label style='color:red;'>
                                    SORRY, NO RESULT FOUND!
                                </label>
                            </td>
                            
                    </tr>";
            }

$html.= "</table></div>";

echo $html;

?>