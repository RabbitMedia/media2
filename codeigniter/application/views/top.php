<!DOCTYPE html>
<html lang="ja">
	<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# website: http://ogp.me/ns/website#">
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="copyright" content="&copy;レズ動画セレクト" />
		<meta name="description" content="レズ動画セレクトでは、<?=$total_count?>件のレズ動画からランキングや女優、カテゴリーでおすすめのレズ動画を探すことができます。" />
		<meta name="keywords" content="レズ動画,レズ動画セレクト" />
		<meta property="og:title" content="レズ動画セレクト - ランキングや女優、カテゴリーから探せるレズ動画情報サイト" />
		<meta property="og:type" content="website" />
		<meta property="og:image" content="http://lezselect.com/images/apple-touch-icon.png" />
		<meta property="og:url" content="http://lezselect.com/" />
		<meta property="og:description" content="レズ動画セレクトでは、<?=$total_count?>件のレズ動画からランキングや女優、カテゴリーでおすすめのレズ動画を探すことができます。" />
		<meta property="og:site_name" content="レズ動画セレクト" />
		<link rel="canonical" href="http://lezselect.com/" />
		<title>レズ動画セレクト - ランキングや女優、カテゴリーから探せるレズ動画情報サイト</title>
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
							<a class="active">
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
							<a href="javascript:;">
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
				<section class="wrapper">

					<div class="row">
						<div class="col-lg-12 main-chart">

							<h1>ランキングや女優、カテゴリーから探せるレズ動画情報サイト − レズ動画セレクト</h1>

							<div class="row mt showback">

								<h2><i class="fa fa-chevron-circle-right"></i> 新着動画</h2>

								<?php foreach ($latest_products as $id => $product): ?>
									<?php if($is_mobile): ?>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mb">
											<div class="white-panel pn">
												<div class="row">
													<div class="col-md-4 col-sm-4 col-xs-4">
														<a href="/product/<?=$product['master_id']?>"><img src="<?=$product['main_thumbnail_url']?>" alt="<?=$product['title']?>" class="img-responsive" width="120" height="90"></a>
													</div>
													<div class="col-md-8 col-sm-8 col-xs-8 text-left">
														<h3><a href="/product/<?=$product['master_id']?>"><?=$product['title']?></a></h3>
														<p><small><?=$product['release_date']?></small></p>
													</div>
												</div>
											</div>
										</div>
									<?php else: ?>
										<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 mb">
											<div class="white-panel pn">
												<div class="text-center">
													<a href="/product/<?=$product['master_id']?>"><img src="<?=$product['main_thumbnail_url']?>" alt="<?=$product['title']?>" class="img-responsive" width="240" height="180"></a>
												</div>
												<div class="text-left">
													<h3><a href="/product/<?=$product['master_id']?>"><?=$product['title']?></a></h3>
													<p><small><?=$product['release_date']?></small></p>
												</div>
											</div>
										</div>
									<?php endif; ?>
								<?php endforeach; ?>

								<div class="text-right">
									<a href="/lists" class="btn btn-primary" role="button"><i class="fa fa-caret-right"></i> もっと見る</a>
								</div>

							</div>
							
							<div class="row mt showback">

								<h2><i class="fa fa-chevron-circle-right"></i> 人気ランキング</h2>

								<?php foreach ($ranking_products as $id => $product): ?>
									<?php if($is_mobile): ?>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mb">
											<div class="white-panel pn">
												<div class="white-header">
													<span class="fw"><?=$id+1?>位</span>
												</div>
												<div class="row">
													<div class="col-md-4 col-sm-4 col-xs-4">
														<a href="/product/<?=$product['master_id']?>"><img src="<?=$product['main_thumbnail_url']?>" alt="<?=$product['title']?>" class="img-responsive" width="120" height="90"></a>
													</div>
													<div class="col-md-8 col-sm-8 col-xs-8 text-left">
														<h3><a href="/product/<?=$product['master_id']?>"><?=$product['title']?></a></h3>
														<p><small>前週<?=$product['prev_rank']?>位</small></p>
													</div>
												</div>
											</div>
										</div>
									<?php else: ?>
										<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 mb">
											<div class="white-panel pn">
												<div class="white-header">
													<span class="fw"><?=$id+1?>位</span>
												</div>
												<div class="text-center">
													<a href="/product/<?=$product['master_id']?>"><img src="<?=$product['main_thumbnail_url']?>" alt="<?=$product['title']?>" class="img-responsive" width="240" height="180"></a>
												</div>
												<div class="text-left">
													<h3><a href="/product/<?=$product['master_id']?>"><?=$product['title']?></a></h3>
													<p><small>前週<?=$product['prev_rank']?>位</small></p>
												</div>
											</div>
										</div>
									<?php endif; ?>
								<?php endforeach; ?>

								<div class="text-right">
									<a href="/ranking" class="btn btn-primary" role="button"><i class="fa fa-caret-right"></i> もっと見る</a>
								</div>

							</div>
							
							<div class="row mt showback">

								<h2><i class="fa fa-chevron-circle-right"></i> ピックアップ</h2>

								<?php foreach ($pickup_products as $id => $product): ?>
									<?php if($is_mobile): ?>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mb">
											<div class="white-panel pn">
												<div class="row">
													<div class="col-md-4 col-sm-4 col-xs-4">
														<a href="/product/<?=$product['master_id']?>"><img src="<?=$product['main_thumbnail_url']?>" alt="<?=$product['title']?>" class="img-responsive" width="120" height="90"></a>
													</div>
													<div class="col-md-8 col-sm-8 col-xs-8 text-left">
														<h3><a href="/product/<?=$product['master_id']?>"><?=$product['title']?></a></h3>
														<p><small><?=$product['release_date']?></small></p>
													</div>
												</div>
											</div>
										</div>
									<?php else: ?>
										<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 mb">
											<div class="white-panel pn">
												<div class="text-center">
													<a href="/product/<?=$product['master_id']?>"><img src="<?=$product['main_thumbnail_url']?>" alt="<?=$product['title']?>" class="img-responsive" width="240" height="180"></a>
												</div>
												<div class="text-left">
													<h3><a href="/product/<?=$product['master_id']?>"><?=$product['title']?></a></h3>
													<p><small><?=$product['release_date']?></small></p>
												</div>
											</div>
										</div>
									<?php endif; ?>
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
		<!-- EqualHeight.js -->
		<script src="/js/jquery.equalheight.min.js"></script>
		<!-- EqualHeight.js -->
		<script>
			$(function() {
				var equalHeight = $('.white-panel h3').equalHeight({wait: true});
				// Browser supports matchMedia
				if (window.matchMedia) {
					// MediaQueryList
					var mql = window.matchMedia("(min-width: 500px)");
					// MediaQueryListListener
					var equalHeightCheck = function (mql) {
						if (mql.matches) {
							equalHeight.start();
						} else {
							equalHeight.stop();
						}
					};
					// Add listener
					mql.addListener(equalHeightCheck);
					// Manually call listener
					equalHeightCheck(mql);
				}
				// Browser doesn't support matchMedia
				else {
					equalHeight.start();
				}
			});
		</script>
	</body>
</html>