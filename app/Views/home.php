<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login</title>

    <link rel="stylesheet" href="assets/vendors/feather/feather.css">
    <link rel="stylesheet" href="assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
    <link rel="stylesheet" type="text/css" href="assets/js/select.dataTables.min.css">
    <link rel="stylesheet" href="assets/css/vertical-layout-light/style.css">
    <link rel="shortcut icon" href="assets/images/favicon.png">
  <style>
    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
    }

    <?php if (!isset($_SESSION['user'])): ?>
    body {
      background: url('bussets/img/kampus.jpeg') no-repeat center center fixed;
      background-size: cover;
      position: relative;
    }

    body::before {
      content: "";
      position: absolute;
      top: 0; left: 0;
      width: 100%;
      height: 100%;
      backdrop-filter: blur(0px);
      background-color: rgba(255, 255, 255, 0.1);
      z-index: 0;
    }
    <?php endif; ?>

    .modal-content {
      z-index: 2;
      animation: fadeInDown 0.7s ease-out;
      background-color: rgba(255, 255, 255, 0.85);
      backdrop-filter: blur(5px);
    }

    @keyframes fadeInDown {
      from { opacity: 0; transform: translateY(-30px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .bg-image1 {
      background: url('bussets/img/login1.png') no-repeat center center;
      background-size: cover;
    }

    .custom-modal-width {
      max-width: 600px;
    }
  </style>
</head>
<body>

<?php if (!isset($_SESSION['user'])): ?>
 
                  <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                      <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                  <?php endif; ?>


					<div class="container d-flex align-items-center justify-content-center vh-100">
						<div class="card shadow p-3 login-card">
							<div class="text-center">
								<img src="assets/images/logo.png" alt="Logo" class="mb-3" style="width: 100px;">
								<h3 class="font-weight-bold">Login</h3>
							</div>

							
							<form action="/login" method="post" id="formlogin">
								<input type="hidden" name="csrf_token" value="<?= \App\Controllers\AuthController::generateCsrf(); ?>">
								<div class="mb-3 input-group">
									<span class="input-group-text"><i class="ti-user"></i></span>
									<input type="text" class="form-control" name="username" placeholder="Enter username">
								</div>
								<div class="mb-3 input-group">
									<span class="input-group-text"><i class="ti-lock"></i></span>
									<input type="password" class="form-control" name="password_hash" placeholder="Enter password">
								</div>
								<button type="submit" class="btn btn-primary w-100 d-flex align-items-center justify-content-center">
									<i class="ti-arrow-right me-2"></i> Login
								</button>
							</form>
						</div>
					</div>

 
<?php endif; ?>

<style>
body {
  background: url('assets/images/background.jpg') no-repeat center center fixed;
  background-size: cover;
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
}
body::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(13, 110, 253, 0.3);
  z-index: 0;
}
.login-card {
  position: absolute;
  top: 10%;
  right: 7%;
  width: 400px;
  background: rgba(255, 255, 255, 0.6);
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
  z-index: 1;
}
</style>


    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="assets/vendors/chart.js/Chart.min.js"></script>
    <script src="assets/vendors/datatables.net/jquery.dataTables.js"></script>
    <script src="assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
    <script src="assets/js/dataTables.select.min.js"></script>
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/hoverable-collapse.js"></script>
    <script src="assets/js/template.js"></script>
    <script src="assets/js/settings.js"></script>
    <script src="assets/js/todolist.js"></script>
    <script src="assets/js/dashboard.js"></script>
    <script src="assets/js/Chart.roundedBarCharts.js"></script>
</body>
</html>
