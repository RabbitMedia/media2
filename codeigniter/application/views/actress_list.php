<!DOCTYPE html>
<html lang="ja">
	<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# website: http://ogp.me/ns/website#">
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="copyright" content="&copy;レズ動画セレクト" />
		<meta name="description" content="このページではレズ動画を出演女優で探すことができます。レズ動画セレクトでは、おすすめのレズ動画をランキングや女優、カテゴリーで探すことができます。" />
		<meta name="keywords" content="レズ動画,レズ動画セレクト" />
		<meta property="og:title" content="女優で探す | レズ動画セレクト" />
		<meta property="og:type" content="website" />
		<meta property="og:image" content="http://lezselect.com/images/apple-touch-icon.png" />
		<meta property="og:url" content="http://lezselect.com/actress/order" />
		<meta property="og:description" content="このページではレズ動画を出演女優で探すことができます。レズ動画セレクトでは、おすすめのレズ動画をランキングや女優、カテゴリーで探すことができます。" />
		<meta property="og:site_name" content="レズ動画セレクト" />
		<link rel="canonical" href="http://lezselect.com/actress/order" />
		<title>女優で探す | レズ動画セレクト</title>
		<link rel="shortcut icon" type="image/x-icon" href="/images/favicon.ico" />
		<link rel="icon" type="image/png" href="/images/favicon.png" />
		<link rel="apple-touch-icon" href="/images/apple-touch-icon.png" />

		<!-- Bootstrap -->
		<link rel="stylesheet" href="/css/bootstrap.min.css">
		<!-- Custom styles -->
		<link rel="stylesheet" href="/css/style.css">
		<link rel="stylesheet" href="/css/style-responsive.css">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="/css/font-awesome.min.css">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>

		<!-- Google Tag Manager -->
		<!-- <noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-PQJKB4" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','GTM-PQJKB4');</script> -->

		<section id="container">

			<header class="header black-bg">

				<div class="sidebar-toggle-box">
					<div class="fa fa-bars"></div>
				</div>

				<h1><a href="/" class="logo"><img src="/images/logo.png" alt="レズ動画セレクト" width="160" height="25"></a><h1>

			</header>

			<aside>

				<div id="sidebar" class="nav-collapse ">
					<ul class="sidebar-menu" id="nav-accordion">

						<li class="mt">
							<a href="/">
								<i class="fa fa-home"></i>
								<span>ホーム</span>
							</a>
						</li>
						<li class="sub-menu">
							<a href="/lists">
								<i class="fa fa-youtube-play"></i>
								<span>すべてのレズ動画</span>
							</a>
						</li>
						<li class="sub-menu">
							<a href="/ranking">
								<i class="fa fa-sort-amount-asc"></i>
								<span>人気ランキング</span>
							</a>
						</li>
						<li class="sub-menu">
							<a class="active" href="javascript:;">
								<i class="fa fa-search"></i>
								<span>動画を探す</span>
							</a>
							<ul class="sub">
								<li><a href="/category">カテゴリーで探す</a></li>
								<li><a href="/actress/order">女優で探す</a></li>
								<li><a href="/label/order">レーベルで探す</a></li>
							</ul>
						</li>
						<li class="sub-menu">
							<a href="javascript:;" >
								<i class="fa fa-info-circle"></i>
								<span>サイトについて</span>
							</a>
							<ul class="sub">
								<li><a href="/faq">よくある質問</a></li>
								<li><a href="/contact">お問い合わせ</a></li>
							</ul>
						</li>

					</ul>
				</div>

			</aside>

			<section id="main-content">
				<section class="wrapper site-min-height">

					<div class="row">
						<div class="col-lg-12 main-chart">

							<div class="row">

								<div class="col-xs-12">
									<ol class="breadcrumb">
										<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
											<a href="/" itemprop="url"><span itemprop="title">ホーム</span></a>
										</li>
										<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" class="active">
											<span itemprop="title">女優で探す</span>
										</li>
									</ol>
								</div>

							</div>

							<h1><i class="fa fa-chevron-circle-right"></i> 女優で探す</h1>

							<div class="row mt">

								<?php foreach ($order_group_btn as $order_group => $btn_text): ?>
									<div class="col-lg-1 col-md-1 col-sm-2 col-xs-3 text-center">
										<?php if ($order_group + 1 == $current_order_group): ?>
											<a class="btn btn-primary active fw" role="button"><?=$btn_text?></a>
										<?php else: ?>
											<a href="/actress/order/<?=$order_group + 1?>" class="btn btn-primary" role="button"><?=$btn_text?></a>
										<?php endif; ?>
									</div>
								<?php endforeach; ?>

							</div>
							
							<div class="row mt">

								<?php foreach ($actresses as $actress): ?>
									<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 mb text-center">
										<a href="/actress/<?=$actress['id']?>"><span class="fw"><?=$actress['name']?></span></a>
									</div>
								<?php endforeach; ?>

							</div>

						</div>
					</div>

				</section>
			</section>

			<footer class="site-footer text-center">
				Copyright &copy; <?php echo date("Y"); ?> レズ動画セレクト All Rights Reserved.
			</div>

		</section>

		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="/js/jquery.min.js"><\/script>')</script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
		<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
		<script src="/js/ie10-viewport-bug-workaround.js"></script>
		<!--common script for all pages -->
		<script src="/js/common-scripts.js"></script>
		<!-- scroll -->
		<script src="/js/jquery.scrollTo.min.js"></script>
		<script src="/js/jquery.nicescroll.js"></script>
		<!-- Vertical Accordion Menu -->
		<script class="include" src="/js/jquery.dcjqaccordion.2.7.js"></script>
	</body>
</html>