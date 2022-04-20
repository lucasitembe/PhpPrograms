<?php

include("./includes/connection.php");
 
if(isset($_POST['selected_items'])&&isset($_POST['selected_sponsor'])){
    $selected_items=$_POST['selected_items'];$selected_sponsors=$_POST['selected_sponsor'];

    foreach ($selected_sponsors as $sponsor){
        foreach ($selected_items as $item){
            #kuondoa data duplication
            $check_if_exist = mysqli_query($conn,"SELECT free_item_id FROM tbl_free_items WHERE sponsor_id='$sponsor' AND item_id='$item'");
            if (mysqli_num_rows($check_if_exist)==0) {
                mysqli_query($conn,"INSERT INTO tbl_free_items(item_id,sponsor_id) VALUES ('$item','$sponsor')");
            } 
        }
    }
}