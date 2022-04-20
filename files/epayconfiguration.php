
<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    ?>
<a href="general_ledger_buttons.php" class="art-button-green">BACK</a>
<?php 
    if(isset($_POST['save_changes_btn'])){
        $collection=mysqli_real_escape_string($conn,$_POST['collection']);
        $code=mysqli_real_escape_string($conn,$_POST['code']);
        $client_id=mysqli_real_escape_string($conn,$_POST['client_id']);
        $password=mysqli_real_escape_string($conn,$_POST['password']);
        $timestamp=mysqli_real_escape_string($conn,$_POST['timestamp']);
        $url=mysqli_real_escape_string($conn,$_POST['url']);
        $id=mysqli_real_escape_string($conn,$_POST['id']);
        
        $sql_update_approval_level_tittle_result=mysqli_query($conn,
                "UPDATE tbl_collection_point_confg SET "
                . "collection='$collection',code='$code',"
                . "client_id='$client_id',password='$password',"
                . "timestamp='$timestamp',url='$url' WHERE id='$id'") or die(mysqli_error($conn));
    
        if($sql_update_approval_level_tittle_result){
            ?>
                <script>
                    alert("UPDATED SUCCESSFULLY");
                </script>    
            <?php
            $approval_level_title="";
        }else{
            ?>
                <script>
                    alert("UPDATING FAIL...TRY AGAIN");
                </script>    
            <?php
        }
    }
    if(isset($_POST['save_btn'])){
        $collection=mysqli_real_escape_string($conn,$_POST['collection']);
        $code=mysqli_real_escape_string($conn,$_POST['code']);
        $client_id=mysqli_real_escape_string($conn,$_POST['client_id']);
        $password=mysqli_real_escape_string($conn,$_POST['password']);
        $timestamp=mysqli_real_escape_string($conn,$_POST['timestamp']);
        $url=mysqli_real_escape_string($conn,$_POST['url']);
        
        $sql_add_approval_level_tittle_result=mysqli_query($conn,
                "INSERT INTO `tbl_collection_point_confg`(`collection`, `code`, `client_id`, `password`, `timestamp`,`url`)  VALUES"
                . "('$collection','$code','$client_id','$password','$timestamp','$url')") or die(mysqli_error($conn));
    
        if($sql_add_approval_level_tittle_result){
            ?>
                <script>
                    alert("ADDED SUCCESSFULLY");
                </script>    
            <?php
        }else{
            ?>
                <script>
                    alert("ADDING FAIL...TRY AGAIN");
                </script>    
            <?php
        }
    }
if(isset($_POST['edit_btn'])){
        $collection=mysqli_real_escape_string($conn,$_POST['collection']);
        $code=mysqli_real_escape_string($conn,$_POST['code']);
        $client_id=mysqli_real_escape_string($conn,$_POST['client_id']);
        $password=mysqli_real_escape_string($conn,$_POST['password']);
        $timestamp=mysqli_real_escape_string($conn,$_POST['timestamp']);
        $url=mysqli_real_escape_string($conn,$_POST['url']);
        $id=mysqli_real_escape_string($conn,$_POST['id']);
}
?>
<br/><br/><br/><br/>
<fieldset>  
            <legend align=center><b>EPAY CONFIGURATION</b></legend>
            <center>
                <form action="" method="POST">
                    <table style="width: 100%;" class="table">
                        <tr>
                            <td style="width: 5%; text-align:right;"><h4>Name</h4></td>
                            <td style="width: 15%">
                                <input type="text" name="collection" placeholder="Enter selection name" value="<?= $collection ?>"class="form-control"/>
                            </td>
                             <td style="width: 5%; text-align:right;"><h4>Code</h4></td>
                            <td style="width: 6%">
                                <input type="text" name="code" placeholder="Enter selection Code" value="<?= $code ?>"class="form-control"/>
                            </td>
                             <td style="width: 5%; text-align:right;"><h4>Client ID</h4></td>
                            <td style="width: 6%">
                                <input type="text" name="client_id" placeholder="Enter selection Client ID" value="<?= $client_id ?>"class="form-control"/>
                            </td>
                             <td style="width: 5%; text-align:right;"><h4>Pass</h4></td>
                            <td style="width: 6%">
                                <input type="text" name="password" placeholder="Enter selection Pass" value="<?= $password ?>"class="form-control"/>
                            </td>
                             <td style="width: 5%; text-align:right;"><h4>Timestamp</h4></td>
                            <td style="width: 6%">
                                <input type="text" name="timestamp" placeholder="Enter selection Timestamp" value="<?= $timestamp ?>"class="form-control"/>
                                <input type='text' value='<?= $id ?>' name='id' hidden='hidden'/>
                            </td>
                            <td style="width: 5%; text-align:right;"><h4>Url</h4></td>
                            <td style="width: 15%">
                                <input type="text" name="url" placeholder="Enter selection Timestamp" value="<?= $url ?>"class="form-control"/>
                                <input type='text' value='<?= $id ?>' name='id' hidden='hidden'/>
                            </td>
                            <td style="width: 10%">
                                <?php 
                                    if(isset($_POST['edit_btn'])){
                                      ?>
                                        <input type="submit" name="save_changes_btn" value="SAVE CHANGES" class="art-button-green"/>
                                <?php
                                    }else{
                                ?>
                                <input type="submit" name="save_btn" value="ADD" class="art-button-green"/>
                                <?php
                                    }
                                ?>
                            </td>
                        </tr>
                    </table>
                </form>
                <div style="height:350px;width: 100%;overflow-y: scroll;overflow-x: hidden">
                <table style="width: 100%;background: #FFFFFF;" class="table table-bordered">
                    <tr>
                        <th style="width:5%">S/No.</th>
                        <th style="width:10%">NAME</th>
                        <th style="width:10%">CODE</th>
                        <th style="width:10%">CLIENT ID</th>
                        <th style="width:10%">PASS</th>
                        <th style="width:10%">TIMESTAMP</th>
                        <th style="width:400%">URLs</th>
                        <th style="width:5%">EDIT</th>
                    </tr>
                    <tbody>
                    <?php 
                        $sql_select_added_title_result=mysqli_query($conn,"SELECT * FROM `tbl_collection_point_confg`") or die(mysqli_error($conn));
                        if(mysqli_num_rows($sql_select_added_title_result)>0){
                             $count=1;
                            while($added_level_rows=mysqli_fetch_assoc($sql_select_added_title_result)){
                                $collection=$added_level_rows['collection'];
                                $code=$added_level_rows['code'];
                                $client_id=$added_level_rows['client_id'];
                                $password=$added_level_rows['password'];
                                $timestamp=$added_level_rows['timestamp'];
                                $url=$added_level_rows['url'];
                                $id=$added_level_rows['id'];
                                echo "<tr>
                                        <td style='text-align: center;'>$count</td>
                                        <td style='text-align: center;'>$collection</td>
                                        <td style='text-align: center;'>$code</td>
                                        <td style='text-align: center;'>$client_id</td>
                                        <td style='text-align: center;'>$password</td>
                                        <td style='text-align: center;'>$timestamp</td>
                                        <td style='text-align: center;'>$url</td>
                                        <td>
                                            <form action='' method='POST'> 
                                                <input type='text' value='$collection' name='collection' hidden='hidden'/>
                                                <input type='text' value='$code' name='code' hidden='hidden'/>
                                                <input type='text' value='$client_id' name='client_id' hidden='hidden'/>
                                                <input type='text' value='$password' name='password' hidden='hidden'/>
                                                <input type='text' value='$timestamp' name='timestamp' hidden='hidden'/>
                                                <input type='text' value='$url' name='url' hidden='hidden'/>
                                                <input type='text' value='$id' name='id' hidden='hidden'/>
                                                <input type='submit' name='edit_btn' class='art-button-green' value='EDIT'/>
                                            </form>
                                        </td>
                                      </tr>";
                                $count++;
                            }
                        }
                    ?>
                    </tbody>
                </table>
                </div>
            </center>
</fieldset>