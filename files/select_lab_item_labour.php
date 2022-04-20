<?php
include("includes/connection.php");

if (isset($_POST['key_search'])) {
    $key_search = $_POST['key_search'];

    $sql = "SELECT * FROM `tbl_items` WHERE `Consultation_Type`='Laboratory'  AND Product_Name LIKE '%$key_search%'             LIMIT 50";
} else {
    $sql = "SELECT * FROM `tbl_items` WHERE `Consultation_Type`='Laboratory' LIMIT 30";
}

$result = mysqli_query($conn,$sql);
#get sub department
function getLabSubDepartment()
{
    $sql = "SELECT sd.Sub_Department_ID,sd.Sub_Department_Name FROM tbl_sub_department sd JOIN tbl_department d ON sd.Department_ID = d.Department_ID WHERE d.Department_Name='Laboratory'";
    $result = mysqli_query($conn,$sql);
    $response = array();
    while ($row = mysqli_fetch_assoc($result)) {
        extract($row);
        array_push($response, array("sub_name" => $Sub_Department_Name, "sub_id" => $Sub_Department_ID));
    }

    return $response;
}

?>

<style>
    #setup{
        width:30%;
    }
    #search{
        width:60%;
    }
    .side{
        float:left;
    }
    #content{
        clear:both;
        
    }

    .section{
        margin-top:15px;
        float:left;
        height:500px;
    }

    #sec1{
        width:40%;
        background:#F0F0F0;
        overflow-y:scroll;
    }

    #sec2{
        width:57%;
        background:#f2f3f4;
    }
    #relevent-notes{
        width:70%;
        margin-left:10px;
        padding-left:10px;
    }
    #label{
        margin-left:9px;
    }

    #notes{
        margin-top:15px;
    }
    #priority{
        margin-left:15px;
    }

    #selection{
        margin-top:15px;
        margin-left:15px;
    }
    #titles{
        width:90%;
    }

    table,tr,td{
        border-collapse:collapse;
        border:none !important;
        box-sizing:border-box;
    }
    td{
        text-align:center;
    }

    #add{
        height:35px;
    }

    #add{
        height:22px !important;
        color:#fff !important;
        width:20px;
    }
    #selected-item{
        margin-top:15px;
        background:#fff;
        width:95%;
        margin-left:10px; 
        height:300px;
        padding-top:5px;
    }

    #done{
        text-align:center;
        color:#fff !important;
    }

    #btn-done{
        width:70px;
        color:#fff !important;
        height:35px !important;
    }
    #error_msg{
        clear:both;
        color:red;
        text-align:center;
    }
</style>
<div class="side" id="setup">
    <div>
    <span>Bill Type</span>
    <select name="" id="">
    <option value="">Select Bill Type</option>
    </select>
    </div>
</div>
<div class="side" id="search">
    <div>
    <span>Search item</span><span><input type="text"  style="padding-left:10px;width:80%" name="search_item" placeholder="Search item" id="search_item"></span>
    </div>

</div>

    <div id="error_msg"></div>
    <div id="content">
        <div class="section" id="sec1">
        <?php 
        $sn = 0;
        if (!empty($key_search)) {
            ?>
             <div>
             <label><span></span><input class="item" type="radio" id="<?= $Item_ID ?>" item-name="<?= $Product_Name ?>" name="item_name"></label>
             </div>
         <?php 
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            extract($row);
            ?>
             <div>
             <label><span></span><input type="radio" id="<?= $Item_ID ?>" item-name="<?= $Product_Name ?>" name="item_name" class="item"><?= $Product_Name ?></label>
             </div>
         <?php 
    }
}
?>
        </div>
        <div class="section" id="sec2" style="color:grey">
            <div id="notes">
            <span id="label">Relevent Notes</span><span><textarea name="relevent_notes" id="relevent-notes"  cols="40" rows="2"></textarea></span><span><select name="priority" id="priority"><option value="normal">Normal</option>
            <option value="urgent">Urgent</option>
            </select></span>
            </div>

            <div id="selection">
            <table id="titles">
            <tr>
            <td style="width:25%">Item Name</td>
            <td style="width:19%">Location</td>
            <td style="width:19%">Price</td>
            <td style="width:19%">Status</td>
            <td style="width:19%">Qty</td>
            <td style="width:19%">Queue</td>
            <td style="width:19%">Amount</td>
            <td style="width:10%">
                
            </td>
            </tr>
            <tr>
            <td style="width:25%"><input name="item_name"  id="item_name" placeholder="item name"></td>
            <td style="width:19%"><select name="location" id="location">
            <?php foreach (getLabSubDepartment() as $dep) {
                ?>
                <option value="<?= $dep['sub_id'] ?>"><?= $dep['sub_name'] ?></option>
            <?php 
        } ?>
                
            </select></td>
            <td style="width:19%"><input name="price" id="price" style="width:100%" placeholder="price"></td>
            <td style="width:19%"><input name="status" id="status" style="width:100%" placeholder="Status"></td>
            <td style="width:19%"><input name="quantity" id="quantity" value="1" style="width:100%" placeholder="0"></td>
            <td style="width:19%"><input name="queue" id="queue" style="width:100%" placeholder="Queue"></td>
            <td style="width:19%"><input name="amount" id="amount" style="width:100%" placeholder="Amount"></td>
            <td style="width:10%">
                <button class="art-button-green" id="add">Add</button>
            </td>
            <input type="hidden" id="item-id" value="">
            </tr>
            </table>


            <div id="selected-item">
            
            </div>

            <div id="done">
                <button class="art-button-green" id="btn-done">Done</button>
            </div>
            </div>
        </div>
    </div>