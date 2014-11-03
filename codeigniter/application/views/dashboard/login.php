<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="copyright" content="&copy;セックル速報" />
		<meta name="robots" content="NOINDEX,NOFOLLOW" />
		<title>Dashboard</title>
		<link rel="shortcut icon" type="image/x-icon" href="/images/favicon.ico" />
		<link rel="icon" type="image/png" href="/images/favicon.png" />
		<link rel="apple-touch-icon" href="/images/apple-touch-icon.png" />

		<!-- Bootstrap -->
		<link rel="stylesheet" href="/css/bootstrap.min.css">
		<!-- Custom styles for Sticky footer with fixed navbar -->
		<link rel="stylesheet" href="/css/sticky-footer-navbar.css">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>

		<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<a class="navbar-brand" href="/dashboard/login">Dashboard</a>
				</div>
			</div>
		</nav>

		<div class="container">

			<div class="row">

				<div class="col-xs-12 col-sm-12 col-md-8 col-md-offset-2">
					<div class="panel panel-default">
						<div class="panel-body text-center">
							<h1>Login</h1>
							<?php echo form_open('dashboard/login', array('role' => 'form')); ?>
								<div class="form-group">
									<input type="text" class="form-control" name="username" value="<?php echo set_value('username'); ?>" placeholder="Username" required autofocus>
								</div>
								<div class="form-group">
									<input type="password" class="form-control" name="password" value="<?php echo set_value('password'); ?>" placeholder="Password" required>
								</div>
								<p class="text-danger"><?php echo validation_errors(); ?></p>
								<?php if ($error_flag == true): ?>
									<p class="text-danger">Login Failed</p>
								<?php endif; ?>
								<div class="form-group">
									<button type="submit" class="btn btn-lg btn-default btn-block">Login</button>
								</div>
							<?php echo form_close(); ?>
						</div>
					</div>
				</div>

			</div>

		</div>

		<div class="footer">
			<div class="container text-center">
				<p class="text-muted">Copyright &copy; <?php echo date("Y"); ?> セックル速報 - 無料セックス動画まとめ All Rights Reserved.</p>
			</div>
		</div>

		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="/js/jquery.min.js"><\/script>')</script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
		<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
		<script src="/js/ie10-viewport-bug-workaround.js"></script>
	</body>
</html>