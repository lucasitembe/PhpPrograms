<?php
 include("./includes/connection.php");
 if(isset($_GET['normal'])){
    $search_value=$_GET['search_value'];

    $filter='';
    $filter .=" AND disease_code LIKE '%$search_value%' OR disease_name LIKE '%$search_value%'";
    
    $get_icd_9_or_10_result=mysqli_query($conn,"SELECT configvalue FROM tbl_config WHERE configname='Icd_10OrIcd_9'") or die(mysqli_error($conn));
    if(mysqli_num_rows($get_icd_9_or_10_result)>0){
        $configvalue_icd10_9=mysqli_fetch_assoc($get_icd_9_or_10_result)['configvalue'];
    }
    
    $deceased_diseases=mysqli_query($conn,"SELECT * FROM tbl_disease WHERE disease_version='$configvalue_icd10_9' $filter  order by disease_name,disease_code ASC LIMIT 5");
    while($row=mysqli_fetch_assoc($deceased_diseases)){
        extract($row);
                            $disease_id="{$disease_ID}";
    echo "<tr><td><label style='font-weight:normal'><input type='checkbox' onclick='add_normal_reason(\"$disease_id\")' value='{$disease_name}'>{$disease_name} ~~<b>{$disease_code}</b></label></td></tr>";
    }
 }else if(isset($_GET['search_value'])){
    $search_value=$_GET['search_value'];

    $filter='';
    if(!empty($search_value)){
    $filter=" AND (disease_code LIKE '%$search_value%' OR disease_name LIKE '%$search_value%')";
    }else{
        $filter="  ";
    }
    $get_icd_9_or_10_result=mysqli_query($conn,"SELECT configvalue FROM tbl_config WHERE configname='Icd_10OrIcd_9'") or die(mysqli_error($conn));
    if(mysqli_num_rows($get_icd_9_or_10_result)>0){
        $configvalue_icd10_9=mysqli_fetch_assoc($get_icd_9_or_10_result)['configvalue'];
    }
    $deceased_diseases=mysqli_query($conn,"SELECT * FROM tbl_disease WHERE disease_version='$configvalue_icd10_9' $filter  order by disease_name ASC LIMIT 5");
    while($row=mysqli_fetch_assoc($deceased_diseases)){
        extract($row);
                            $disease_id="{$disease_ID}";
    echo "<tr><td><label style='font-weight:normal'><input type='checkbox' onclick='add_death_reason(\"$disease_id\")' value='{$disease_name}'>{$disease_name} ~~<b>{$disease_code}</b></label></td></tr>";
    }
 }
 
if(isset($_POST['disease_ID']) && isset($_POST['selected_disease']) && $_POST['selected_disease'] == 'added_disease'){
    $disease_ID=$_POST['disease_ID'];
    $select_disease=mysqli_query($conn,"SELECT disease_ID, disease_name, disease_code FROM tbl_disease WHERE disease_ID=$disease_ID");
    $disease_info=mysqli_fetch_assoc($select_disease);
    $disease_ID = $disease_info['disease_ID'];
    $disease_name = $disease_info['disease_name'];
    $disease_code = $disease_info['disease_code'];
    echo '<td >
    <input type="hidden" value="'.$disease_ID.'" class="disease_list">
    <input type="hidden" value="'.$disease_code.'" class="disease_name_list">
    '.$disease_name.'</td><td>'.$disease_code.'</td>';
}

if(isset($_GET['search_valuedisease'])){
    $disease_code = $_GET['disease_code'];
    $disease_name = $_GET['disease_name'];
    $filter = '';
    if (!empty($disease_code)) {
        $filter = " and disease_code LIKE '%$disease_code%'";
    } else {
        $filter = " and disease_name LIKE '%$disease_name%'";
    }
    $configvalue_icd10_9 = $_GET['disease_version'];
    $deceased_diseases = mysqli_query($conn, "SELECT * FROM tbl_disease WHERE disease_version='$configvalue_icd10_9' $filter  order by disease_name,disease_code ASC LIMIT 5");
    while ($row = mysqli_fetch_assoc($deceased_diseases)) {
        extract($row);
        $disease_id = "{$disease_ID}";
        echo "<tr><td><label style='font-weight:normal'><input type='checkbox' onclick='add_death_reason(\"$disease_id\")' value='{$disease_name}'>{$disease_name} ~~<b>{$disease_code}</b></label></td></tr>";
    }
    if (isset($_POST['disease_ID']) && isset($_POST['selected_disease']) && $_POST['selected_disease'] == 'added_disease') {
        $disease_ID = $_POST['disease_ID'];
        $select_disease = mysqli_query($conn, "SELECT disease_ID, disease_name, disease_code FROM tbl_disease WHERE disease_ID=$disease_ID");
        $disease_info = mysqli_fetch_assoc($select_disease);
        $disease_ID = $disease_info['disease_ID'];
        $disease_name = $disease_info['disease_name'];
        $disease_code = $disease_info['disease_code'];
        if (isset($_POST['from_referal'])) {
            echo $disease_code;
        } else {
            echo '<td >
        <input type="hidden" value="' . $disease_ID . '" class="disease_list">
        <input type="hidden" value="' . $disease_code . '" class="disease_name_list">
        ' . $disease_name . '</td><td>' . $disease_code . '</td>';
        }
    };
}