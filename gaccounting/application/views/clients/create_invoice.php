<div class="row">
    <div class="col-lg-12">
       
                <div class="row">
                	<div class="col-md-12">
                		<table class="table table-bordered ">
                			<thead>
	                			<tr style="background:#999;color:#fff;">
	                				<th colspan="5">Client details</th>
	                			</tr>
	                			<tr>
	                                <th>Client name</th> 
	                                <th>Address</th> 
	                                <th>Contact Name</th>
	                                <th>Contact Phone</th>
	                                <th>Contact Email</th>
	                            </tr>
                			</thead>
                			<tbody>
                				<tr>
                					<td><?= $client->client_name ?></td>
                					<td><?= $client->address ?></td>
                					<td><?= $client->contactname ?></td>
                					<td><?= $client->contact_phone ?></td>
                					<td><?= $client->email ?></td>
                				</tr>
                			</tbody>
                		</table>
                	</div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered table-condensed">
                            <thead>
                                <tr style="background:#999;color:#fff;">
                                    <th>Narration</th>
                                    <th width="5%">Taxable</th>
                                    <th >Amount</th>
                                    <th width="5%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="background:#999;color:#fff;">
                                <form method="GET" id="addNewInvoiceEntryForm">
                                    <td><input type="text" name="narration" id="narration" class="form-control" maxlength="250"></td>
                                    <td ><input type="checkbox" name="taxable" id="taxable" class="form-control" style="width:33px;" value="true">
                                        <input type="hidden" name="_taxable" id="_taxable" value="false">
                                        <input type="hidden" name="client_id" id="client_id" value="<?= $client->client_id ?>">
                                    </td>
                                    <td><input type="text" name="amount" id="amount" class="form-control"></td>
                                    <td><input type="submit" value="Add" id="addInvoiceDetailsCache" class="btn btn-primary btn-sm"></td>
                                </form>
                                </tr>
                            </tbody>
                            <tfoot id="invoice_details_cache" style="height:30px;">
                                
                            </tfoot>
                        </table>
                    </div>
                </div>
    </div>
</div>
<script type="text/javascript">
var baseUrl = '<?= base_url() ?>';
    $("#addInvoiceDetailsCache").click(function(){
        var client_id = $("#client_id").val();
        var narration = $("#narration").val();
        var amount = $("#amount").val();
        var taxable = '';
        
        if(narration=='' || amount==''){
            alert("Narration and Amount can not be blank");
            return;
        }

        if($("#taxable").prop('checked')==true){
            taxable = 1;
        } else {
            taxable = 0;
        }

    var url=baseUrl + "/Clients/addInvoiceDetailsCache/"+client_id;
    var datastring = "client_id="+client_id+"&narration="+narration+"&amount="+amount+"&taxable="+taxable;
    
    $.ajax({
        method: "GET",
        url: url,
        data: datastring,
        dataType: 'json' ,
        beforeSend: function () {
            spinner('show');
        },
        success: function (data, textStatus, jqXHR) {
            //refresh the details list
            if(data.status=='500'){
                alert(data.message);
            } else {
                getinvoiceCache(client_id);
                $("#narration").val('');
                $("#amount").val('');
                $("#taxable").prop('checked',false);
            }
            

        }, complete: function (jqXHR, textStatus) {
            spinner('hide');
        }, error: function (jqXHR, textStatus, errorThrown) {
            spinner('hide');
        }
    }); 
    });
    getinvoiceCache($("#client_id").val());
    function getinvoiceCache(client_id){
        //alert("client id : "+client_id);
            var url=baseUrl + "/Clients/getInvoiceDetailsCache/"+client_id;
            var datastring = '';
            $.ajax({
            method: "GET",
            url: url,
            data: datastring,
            beforeSend: function () {
                spinner('show');
            },
            success: function (data, textStatus, jqXHR) {
                //refresh the details list
                $("#invoice_details_cache").html(data);
            }, complete: function (jqXHR, textStatus) {
                spinner('hide');
            }, error: function (jqXHR, textStatus, errorThrown) {
                spinner('hide');
            }
        });
    }

    function submitInvoice(client_id){
        r = confirm('Are you sure you want to submit this invoice?');
        if(!r){
            return;
        }
        var url=baseUrl + "Clients/submitInvoice/"+client_id;
            var datastring = '';
            $.ajax({
            method: "GET",
            url: url,
            data: datastring,
            dataType: 'json' ,
            beforeSend: function () {
                spinner('show');
            },
            success: function (data, textStatus, jqXHR) {
                //refresh the details list
                getinvoiceCache($("#client_id").val());
               getClientInvoiceList($("#client_id").val());
                alert(data.message);
                console.log(data);
                //$("#invoice_details_cache").html(data);
            }, complete: function (jqXHR, textStatus) {
                spinner('hide');
            }, error: function (jqXHR, textStatus, errorThrown) {
                spinner('hide');
            }
        });

    }
     
    function removeInvoiceEntry(entry_id){
        r = confirm('Are you sure you want to remove this entry?');
        if(!r){
            return;
        }
        var url=baseUrl + "Clients/removeInvoiceEntryFromCache/"+entry_id;
            var datastring = '';
            $.ajax({
            method: "GET",
            url: url,
            data: datastring,
            dataType: 'json' ,
            beforeSend: function () {
                spinner('show');
            },
            success: function (data, textStatus, jqXHR) {
                //refresh the details list
                getinvoiceCache($("#client_id").val());
                if(data.status!='200'){
                    alert(data.message);
                }
                
                console.log(data);
                //$("#invoice_details_cache").html(data);
            }, complete: function (jqXHR, textStatus) {
                spinner('hide');
            }, error: function (jqXHR, textStatus, errorThrown) {
                spinner('hide');
            }
        });
    }
</script>

