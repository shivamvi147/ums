<?php
session_start();
error_reporting(0);
include('functions.php');
if (strlen($_SESSION['token']=='')) {
  header('location:logout.php');
} else{
    $data = [];
    $response = callAPI('GET', 'profile.php');
    if($response['httpcode'] == 200 && $response['data']->status == 200){
        $data = $response['data'];
    } else {
        echo "<script>alert('API Call failed');</script>";
    }  
   
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>UMS</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        table tbody tr td, table tbody tr th {
            padding:10px;
        } 
    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
         <?php include_once('includes/sidebar.php ');?>
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
               <?php include_once('includes/topbar.php');?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Welcome  <b><?= isset($data->name) ? $data->name : '';?></b></h1>
                    <table border="1px">
                        <tbody>
                            <tr>
                                <th>Name</th>
                                <td><?= isset($data->name) ? $data->name : '';?></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td><?= isset($data->email) ? $data->email : '';?></td>
                            </tr>
                            <tr>
                                <th>Username</th>
                                <td><?= isset($data->username) ? $data->username : '';?></td>
                            </tr>
                        </tbosy>
                    </table>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
         <?php include_once('includes/footer.php');?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

      <?php include_once('includes/logout-modal.php');?>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>
</html>
<?php } ?>