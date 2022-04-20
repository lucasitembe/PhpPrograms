<?php
include("./includes/connection.php");
@session_start();
$Item_ID = 0;
$Consultation_Type = $_GET['Consultation_Type'];
?>
<!--Global Item_ID-->

<!--filtering services against categories-->
<script type="text/javascript" language="javascript">
    function getItemList(Item_Category_Name) {
        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }
        //getPrice();
        var ItemListType = document.getElementById('Type').value;
        getItemListType(ItemListType);
        document.getElementById('Price').value = '';
        mm.onreadystatechange = AJAXP; //specify name of function that will handle server response....
        mm.open('GET', 'GetItemList.php?Item_Category_Name=' + Item_Category_Name, true);
        mm.send();
    }
    function AJAXP() {
        var data1 = mm.responseText;
        document.getElementById('Item_Name').innerHTML = data1;
    }
</script>





<!--filtering services against categories-->
<script type="text/javascript" language="javascript">
    function getSubcategory(Item_Category_ID) {
        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }

        mm.onreadystatechange = AJAXP2; //specify name of function that will handle server response....
        mm.open('GET', 'GetItemSubcategory.php?Item_Category_ID=' + Item_Category_ID, true);
        mm.send();
    }
    function AJAXP2() {
        var data1 = mm.responseText;
        document.getElementById('Item_Subcategory_ID').innerHTML = data1;
    }

    //function to search items
    function searchItem() {
        Item_Category_ID = document.getElementById('Item_Category_ID').value;
        Item_Subcategory_ID = document.getElementById('Item_Subcategory_ID').value;
        test_name = document.getElementById('test_name').value;
        $.ajax({
            type: 'GET',
            url: 'doctordtl_frame.php',
            data: "test_name=" + test_name + "&Item_Subcategory_ID=" + Item_Subcategory_ID + "&Item_Category_ID=" + Item_Category_ID + "&Consultation_Type=<?php echo $Consultation_Type; ?>",
            cache: false,
            success: function (html) {
                document.getElementById('frmaesearch').innerHTML = html;
            }
        });
    }

</script>
<form action='' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
    <!--<br/>-->

    <fieldset>
        <center>
            <form method='post' action=''>
                        <table style='width: 100%;'>
                          

                            <tr>
                                <td style="text-align: right;">Category </td>
                                <td>
                                    <select id='Item_Category_ID' name='Item_Category_ID' onchange='getSubcategory(this.value)' required='required' style='width: 100%'>
                                        <option>All</option>
                                        <?php
                                        $qr = '';

                                        if ($Consultation_Type == 'Pharmacy') {
                                            $qr = "SELECT * FROM tbl_item_category as ic
					    join tbl_item_subcategory as iss on iss.Item_Category_ID = ic.Item_Category_ID
					    join tbl_items  as i on iss.Item_Subcategory_ID = i.Item_Subcategory_ID
					    WHERE i.Item_Type='Pharmacy' group by ic.Item_Category_ID LIMIT 200";
                                        }
                                        elseif ($Consultation_Type == 'Laboratory') {
                                            $qr = "SELECT * FROM tbl_item_category as ic
					    join tbl_item_subcategory as iss on iss.Item_Category_ID = ic.Item_Category_ID
					    join tbl_items  as i on iss.Item_Subcategory_ID = i.Item_Subcategory_ID
					    WHERE i.Consultation_Type='Laboratory' group by ic.Item_Category_ID";
                                        }
                                        elseif ($Consultation_Type == 'Surgery') {
                                            $qr = "SELECT * FROM tbl_item_category as ic
					    join tbl_item_subcategory as iss on iss.Item_Category_ID = ic.Item_Category_ID
					    join tbl_items  as i on iss.Item_Subcategory_ID = i.Item_Subcategory_ID
					    WHERE i.Consultation_Type='Surgery' group by ic.Item_Category_ID";
                                        }
                                        elseif ($Consultation_Type == 'Procedure') {
                                            $qr = "SELECT * FROM tbl_item_category as ic
					    join tbl_item_subcategory as iss on iss.Item_Category_ID = ic.Item_Category_ID
					    join tbl_items  as i on iss.Item_Subcategory_ID = i.Item_Subcategory_ID
					    WHERE i.Consultation_Type='Procedure' group by ic.Item_Category_ID";
                                        } else {
                                            $qr = "SELECT * FROM tbl_item_category as ic
					    join tbl_item_subcategory as iss on iss.Item_Category_ID = ic.Item_Category_ID
					    WHERE iss.Item_Subcategory_ID IN
					    (SELECT Item_Subcategory_ID FROM tbl_items where $Consultation_Condition2)
					    group by ic.Item_Category_ID
					    ";

                                            //echo $qr;exit;
                                        }
                                        
                                        $result = mysqli_query($conn,$qr);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                            <?php if (strtolower($row['Item_Category_Name']) == 'laboratory') { ?>
                                                <option value="<?php echo $row['Item_Category_ID'] ?>" selected='selected'>
                                                    <?php echo $row['Item_Category_Name'] ?>
                                                </option>
                                                <?php
                                            }
                                            if (strtolower($row['Item_Category_Name']) == 'imaging') {
                                                ?>
                                                <option value="<?php echo $row['Item_Category_ID'] ?>" >
                                                    <?php echo $row['Item_Category_Name'] ?>
                                                </option>
                                            <?php }if (strtolower($row['Item_Category_Name']) == 'MINOR SURGERY SERVICES') { ?>
                                                <option value="<?php echo $row['Item_Category_ID'] ?>" >
                                                    <?php echo $row['Item_Category_Name'] ?>
                                                </option>
                                            <?php }if (strtolower($row['Item_Category_Name']) == 'procedures') { ?>
                                                <option value="<?php echo $row['Item_Category_ID'] ?>" >
                                                    <?php echo $row['Item_Category_Name'] ?>
                                                </option>
                                            <?php } else { ?>
                                                <option value="<?php echo $row['Item_Category_ID'] ?>" >
                                                    <?php echo $row['Item_Category_Name'] ?>
                                                </option>
                                            <?php } ?>
                                        <?php }
                                        ?>
                                    </select>
                                </td>
                                <td style="text-align: right;">Subcategory</td>
                                <td>
                                    <select id='Item_Subcategory_ID' name='Item_Subcategory_ID' onchange='searchItem(this.value)'  required='required' style='width: 100%'>
                                        <option>All</option>
                                        <?php
                                        if ($Consultation_Type == 'Pharmacy') {
                                            $qr = "SELECT * FROM tbl_item_subcategory as iss
					WHERE iss.Item_Subcategory_ID IN
					(SELECT Item_Subcategory_ID FROM tbl_items where Item_Type='Pharmacy')
					group by iss.Item_Subcategory_ID";
                                        } else {
                                            $qr = "SELECT * FROM tbl_item_subcategory as iss
					WHERE iss.Item_Subcategory_ID IN
					(SELECT Item_Subcategory_ID FROM tbl_items where $Consultation_Condition2)
					group by iss.Item_Subcategory_ID";
                                        }



                                        $result = mysqli_query($conn,$qr) or die(mysqli_error($conn));
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                            <option value='<?php echo $row['Item_Subcategory_ID'] ?>'>
                                                <?php echo $row['Item_Subcategory_Name'] ?>
                                            </option>
                                            <?php
                                        }
                                        ?>
                                    </select>			    
                                </td>
                                <td style='text-align: right'>Item Name</td>
                                <td>
                                    <input type='text' oninput='searchItem()'  placeholder='-----Search an item-----' id='test_name' name='test_name'>

                                </td>
                            </tr>
                        </table>
                </table>
        </center>
        <center>
            <table width='100%' class="dataItem">
                <tr>
                    <td width='35%'  >

                        <script type='text/javascript'>
                            function getItemsList(Item_Category_ID) {
                                document.getElementById("Search_Value").value = '';
                                document.getElementById("Price").value = '';
                                document.getElementById("Item_Name").value = '';
                                document.getElementById("Quantity").value = '';
                                if (window.XMLHttpRequest) {
                                    myObject = new XMLHttpRequest();
                                } else if (window.ActiveXObject) {
                                    myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                                    myObject.overrideMimeType('text/xml');
                                }
                                //alert(data);

                                myObject.onreadystatechange = function () {
                                    data = myObject.responseText;
                                    if (myObject.readyState == 4) {
                                        //document.getElementById('Approval').disabled = 'disabled';
                                        document.getElementById('Items_Fieldset').innerHTML = data;
                                    }
                                }; //specify name of function that will handle server response........
                                myObject.open('GET', 'Get_List_Of_Items.php?Item_Category_ID=' + Item_Category_ID, true);
                                myObject.send();
                            }

                            function getItemsListFiltered(Item_Name) {
                                document.getElementById("Price").value = '';
                                document.getElementById("Item_Name").value = '';
                                document.getElementById("Quantity").value = '';
                                var Item_Category_ID = document.getElementById("Item_Category_ID").value;
                                if (Item_Category_ID == '' || Item_Category_ID == null) {
                                    Item_Category_ID = 'All';
                                }

                                if (window.XMLHttpRequest) {
                                    myObject = new XMLHttpRequest();
                                } else if (window.ActiveXObject) {
                                    myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                                    myObject.overrideMimeType('text/xml');
                                }
                                //alert(data);

                                myObject.onreadystatechange = function () {
                                    data = myObject.responseText;
                                    if (myObject.readyState == 4) {
                                        //document.getElementById('Approval').disabled = 'disabled';
                                        document.getElementById('Items_Fieldset').innerHTML = data;
                                    }
                                }; //specify name of function that will handle server response........
                                myObject.open('GET', 'Get_List_Of_Items_Filtered.php?Item_Category_ID=' + Item_Category_ID + '&Item_Name=' + Item_Name, true);
                                myObject.send();
                            }


                        </script>
                        

                        <!--HHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHH-->
                        <table width = 100%>
                            <tr>
                                <td style="border:1px  solid #ccc;">
                                    <fieldset>
                                        <table width="100%">
                                            <tr>
                                                <td style="border:1px  solid #ccc;">
                                                    <div  style="width:100%; height:320px; overflow-y:scroll;overflow-x:hidden" id='frmaesearch'>
                                                        <?php
                                                        include "doctordtl_frame.php";
                                                        ?>

                                                    </div>

                                                </td>
                                            </tr>
                                        </table>
                                    </fieldset>	
                                </td>
                            </tr>
                        </table> 
                    </td>
            </tr> 
            </table>
        </center>
    </fieldset>
</form>

