var backendURL = "backend/";
var notFirstTime = false;
var ordersTemplate = '<tr><td>[user]</td><td>[product]</td><td>[price]</td><td>[quantity]</td><td>[total]</td><td>[date]</td>\
        <td><button class="btn btn-warning" onclick="showEditOrder([orderid])">Edit</button>&nbsp;<button class="btn btn-danger" onclick="deleteOrder([orderid])">Delete</button></td></tr>';
var currency = " EUR";

function listOrders() {
    var order_period = $('#order_period').val().trim();
    var search_param = $('#search_param').val().trim();
    var data = "action=fetch_order&order_period="+order_period;
    if (search_param != '') {
        data += "&search_param="+search_param;
    }
    $.ajax({
        url: backendURL+"service.php",
        type: "POST",
        data:  data,
        dataType: 'json',
        success: function(result) {
            if (result.infocode == "ORDERSFETCHED") {
                ordersData = result.ordersData;
                displayOrderDetails(ordersData);
            } else {
                if (notFirstTime) {
                    $('#order_table_dt').DataTable().destroy();
                    //Flag is unset
                    notFirstTime = false;
                }                
                var display = '<tr><td colspan="7">'+result.message+'</td></tr>';
                $('#order_table_tbody').html(display);

            }
        },
        error: function() {
            bootbox.alert("Network Error, please try again"); 
        }             
    });
}

function displayOrderDetails(ordersData) {
    var display = '';
    for(i = 0; i < ordersData.length; i++){
        display += ordersTemplate.replace('[user]', ordersData[i].full_name)
                                .replace('[product]', ordersData[i].product_name)
                                .replace('[price]', parseFloat(ordersData[i].unit_price).toFixed(2) + currency)
                                .replace('[quantity]', ordersData[i].quantity)
                                .replace('[total]', parseFloat(ordersData[i].total_price).toFixed(2) + currency)
                                .replace('[date]', ordersData[i].created_time)
                                .replace('[orderid]', ordersData[i].order_id)
                                .replace('[orderid]', ordersData[i].order_id);
    }
    if (notFirstTime) {
        $('#order_table_dt').DataTable().destroy();
    }
    $('#order_table_tbody').html(display);
    //Flag is set
    notFirstTime = true;
    $('#order_table_dt').DataTable({"order": [[ 5, "desc" ]]});

}

function showAddOrder() {
    //Reset form values to Add Order module
    $('#add_edit_form').trigger("reset");
    $('#action').val('add_order');
    $('#hidden_order_id').val('0');
    $('#btn_edit_order').hide();
    $('#btn_add_order').show();
    $('#alert_div').html('').hide();
    $('#modal_header_h5').html('Add New Order');
    $('#add_edit_modal').modal('show');
}

function showEditOrder(orderId) {
    var data = "action=fetch_order_detail&order_id="+orderId;
    $.ajax({
        url: backendURL+"service.php",
        type: "POST",
        data:  data,
        dataType: 'json',
        success: function(result) {
            if (result.infocode == "ORDERDETAILFETCHED") {
                orderData = result.orderData;
                //Edit form values to Edit Order module
                $('#select_user').val(orderData.user_id);
                $('#select_product').val(orderData.product_id);
                $('#order_quantity').val(orderData.quantity);
                $('#action').val('edit_order');
                $('#hidden_order_id').val(orderData.order_id);
                $('#btn_add_order').hide();
                $('#btn_edit_order').show();
                $('#alert_div').html('').hide();
                $('#modal_header_h5').html('Edit Order');
                $('#add_edit_modal').modal('show');
            } else {
                bootbox.alert(result.message);                
            }
        },
        error: function() {
            bootbox.alert("Network Error, please try again"); 
        }             
    });    
}

function addOrder() {
    var order_quantity = $('#order_quantity').val().trim();
    
    if (order_quantity == '' || isNaN(order_quantity)) {
        $('#alert_div').removeClass('alert-success').addClass('alert-danger').html('Quantity should be a valid number').show();
    } else {
        $.ajax({
            url: backendURL+"service.php",
            type: "POST",
            data:  $('#add_edit_form').serialize(),
            dataType: 'json',
            success: function(result) {
                if (result.infocode == "ORDERADDED") {
                    $('#alert_div').removeClass('alert-danger').addClass('alert-success').html(result.message).show();
                    setTimeout(modalClose, 5000);
                    listOrders();
                } else {
                    $('#alert_div').removeClass('alert-success').addClass('alert-danger').html(result.message).show();
                }
            },
            error: function() {
                bootbox.alert("Network Error, please try again"); 
            }             
        });
     }
}

function editOrder() {
    var order_quantity = $('#order_quantity').val().trim();
    
    if (order_quantity == '' || isNaN(order_quantity)) {
        $('#alert_div').removeClass('alert-success').addClass('alert-danger').html('Quantity should be a valid number').show();
    } else {
        $.ajax({
            url: backendURL+"service.php",
            type: "POST",
            data:  $('#add_edit_form').serialize(),
            dataType: 'json',
            success: function(result) {
                if (result.infocode == "ORDERUPDATED") {
                    $('#alert_div').removeClass('alert-danger').addClass('alert-success').html(result.message).show();
                    setTimeout(modalClose, 5000);
                    listOrders();
                } else {
                    $('#alert_div').removeClass('alert-success').addClass('alert-danger').html(result.message).show();
                }
            },
            error: function() {
                bootbox.alert("Network Error, please try again"); 
            }             
        });
     }
}

function deleteOrder(orderId) {
    
    bootbox.confirm("You sure you want to delete this order?", function(result) {
        if (result) {
            var data = "action=delete_order&order_id="+orderId;
            $.ajax({
                url: backendURL+"service.php",
                type: "POST",
                data:  data,
                dataType: 'json',
                success: function(result) {
                    if (result.infocode == "ORDERDELETED") {
                        bootbox.alert(result.message);
                        listOrders();
                    } else {
                        bootbox.alert(result.message); 
                    }
                },
                error: function() {
                    bootbox.alert("Network Error, please try again"); 
                }             
            });
        }
    });
}

function modalClose() {
    $('#add_edit_modal').modal('hide');
}