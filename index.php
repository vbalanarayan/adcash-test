<?php session_start();

if(!isset($_SESSION['loginid'])){
    header( 'Location: login.php' );
    exit();
}
require("dbconfig.php");
?>
<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Order Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="assets/images/icon/favicon.ico">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/themify-icons.css">
    <link rel="stylesheet" href="assets/css/metisMenu.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/slicknav.min.css">
    <!-- amchart css -->
    <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
    <!-- others css -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/css/typography.css">
    <link rel="stylesheet" href="assets/css/default-css.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <!-- modernizr css -->
    <script src="assets/js/vendor/modernizr-2.8.3.min.js"></script>
</head>

<body>
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <!-- preloader area start -->
    <div id="preloader">
        <div class="loader"></div>
    </div>
    <!-- preloader area end -->
    <!-- page container area start -->
    <div class="page-container">
        <!-- sidebar menu area start -->
        <div class="sidebar-menu">
            <div class="sidebar-header">
                <div class="logo">
                    <a href="index.php">ORDER MANAGEMENT</a>
                </div>
            </div>
            <div class="main-menu">
                <div class="menu-inner">
                    <nav>
                        <ul class="metismenu" id="menu">
                            <li class="active">
                                <a href="javascript:void(0)" aria-expanded="true"><i class="ti-dashboard"></i><span>dashboard</span></a>
                                <ul class="collapse">
                                    <li class="active"><a href="#">Manage Orders</a></li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <!-- sidebar menu area end -->
        <!-- main content area start -->
        <div class="main-content">
            <!-- header area start -->
            <div class="header-area">
                <div class="row align-items-center">
                    <!-- nav and search button -->
                    <div class="col-md-6 col-sm-8 clearfix">
                        <div class="nav-btn pull-left">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                    <!-- profile info & task notification -->
                    <div class="col-md-6 col-sm-4 clearfix">
                        <ul class="notification-area pull-right">
                            <li class="settings-btn">
                                <div class="user-profile-new pull-right">
                                    <!--img class="avatar user-thumb" src="assets/images/author/avatar.png" alt="avatar"-->
                                    <h4 class="user-name dropdown-toggle" data-toggle="dropdown"><?php echo $_SESSION['fullname']; ?> <i class="fa fa-angle-down"></i></h4>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="logout.php">Log Out</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- header area end -->
            <!-- page title area start -->
            <div class="page-title-area">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <div class="breadcrumbs-area clearfix">
                            <h4 class="page-title pull-left">Dashboard</h4>
                            <ul class="breadcrumbs pull-left">
                                <li><a href="index.html">Home</a></li>
                                <li><span>Dashboard</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- page title area end -->
            <div class="main-content-inner">
                <!-- order list area start -->
                <div class="col-12 mt-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="header-title"><button class="btn btn-success" id="btn_show_add_order" onclick="showAddOrder();">Add New Order</button></div>
                        </div>
                    </div>
                </div>
                <div class="card mt-5">
                    <div class="card">
                        <form id="search_form" method="POST" onsubmit="return false;">
                            <div class="card-body">
                                <div class="header-title">Search Order</div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <select class="form-control" name="order_period" id="order_period">
                                            <option value="today" selected="selected">Today</option>
                                            <option value="7days">Last 7 days</option>
                                            <option value="alltime">All time</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" name="search_param" id="search_param" class="form-control" placeholder="Enter Search Term" />
                                    </div>
                                    <div class="col-sm-4">
                                        <button class="btn btn-primary" id="btn_search" onclick="listOrders();">Search</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <h4 class="header-title">Order List</h4>
                        <div class="single-table">
                        <div class="table-responsive">
                            <table class="table text-center" id="order_table_dt">
                                <thead class="bg-primary">
                                    <tr class="heading-td">
                                        <td>User</td>
                                        <td>Product</td>
                                        <td>Price</td>
                                        <td>Quantity</td>
                                        <td>Total</td>
                                        <td>Date</td>
                                        <td>Actions</td>
                                    </tr>
                                </thead>
                                <tbody id="order_table_tbody">
                                    <tr>
                                        <td colspan="7">No orders matching the search criteria</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                </div>
                <!-- Add/Edit Modal -->
                <div class="modal fade" id="add_edit_modal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="add_edit_form" method="POST" onsubmit="return false;">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modal_header_h5">Add New Order</h5>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="select_user">User</label>
                                        <select name="select_user" id="select_user" class="form-control">
                                        <?php 
                                        $query = "SELECT user_id, full_name FROM user ORDER BY full_name ASC";
                                        $result = mysqli_query($dbc, $query);
                                        if(mysqli_num_rows($result)>0){
                                            while($row = mysqli_fetch_assoc($result)){
                                                echo '<option value="'.$row['user_id'].'">'.$row['full_name'].'</option>';
                                            }
                                        }else{
                                            echo '<option value="">No users added</option>';
                                        }
                                        ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="select_product">User</label>
                                        <select name="select_product" id="select_product" class="form-control">
                                        <?php 
                                        $query = "SELECT product_id, product_name FROM product ORDER BY product_name ASC";
                                        $result = mysqli_query($dbc, $query);
                                        if(mysqli_num_rows($result)>0){
                                            while($row = mysqli_fetch_assoc($result)){
                                                echo '<option value="'.$row['product_id'].'">'.$row['product_name'].'</option>';
                                            }
                                        }else{
                                            echo '<option value="">No products added</option>';
                                        }
                                        ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="order_quantity">Quantity</label>
                                        <input type="number" class="form-control" name="order_quantity" id="order_quantity" required="" placeholder="Enter Quantity">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="alert alert-success" role="alert" id="alert_div">
                                        You successfully read this important alert message.
                                    </div>
                                    <button type="button" class="btn btn-primary" id="btn_add_order" onclick="addOrder()">Add Order</button>
                                    <button type="button" class="btn btn-info" id="btn_edit_order" onclick="editOrder()">Edit Order</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>                                    
                                    <input type="hidden" name="action" id="action" value="add_order">
                                    <input type="hidden" name="hidden_order_id" id="hidden_order_id" value="0">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- main content area end -->
        <!-- footer area start-->
        <footer>
            <div class="footer-area">
                <p>© Copyright 2018. All right reserved. Template by <a href="https://colorlib.com/wp/">Colorlib</a>.</p>
            </div>
        </footer>
        <!-- footer area end-->
    </div>
    <!-- page container area end -->
    <!-- offset area end -->
    <!-- jquery latest version -->
    <script src="assets/js/vendor/jquery-2.2.4.min.js"></script>
    <!-- bootstrap 4 js -->
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/owl.carousel.min.js"></script>
    <script src="assets/js/metisMenu.min.js"></script>
    <script src="assets/js/jquery.slimscroll.min.js"></script>
    <script src="assets/js/jquery.slicknav.min.js"></script>
    <!-- Start datatable js -->
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
    <!-- Bootbox -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
    <!-- others plugins -->
    <script src="assets/js/plugins.js"></script>
    <script src="assets/js/scripts.js"></script>
    <script src="modulejs/orders.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        $('#btn_edit_order').hide();
        $('#alert_div').hide();
        listOrders();
    });
    </script>
</body>
</html>