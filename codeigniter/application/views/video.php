<!DOCTYPE html>
<html lang="ja">
	<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# website: http://ogp.me/ns/website#">
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="copyright" content="&copy;セックル速報" />
		<meta name="robots" content="INDEX,FOLLOW" />
		<meta name="description" content="【動画あり】<?=$video['title']?>これはエロすぎるｗｗｗセックル速報にはこの他にも無料セックス動画が満載ｗｗｗ" />
		<meta name="keywords" content="セックス動画,セックル速報" />
		<meta property="og:title" content="<?=$video['title']?> | セックル速報" />
		<meta property="og:type" content="article" />
		<meta property="og:image" content="<?=$video['thumbnail_url']?>" />
		<meta property="og:url" content="http://sekusoku.com/video/<?=$video['master_id']?>" />
		<meta property="og:description" content="【動画あり】<?=$video['title']?>これはエロすぎるｗｗｗ" />
		<meta property="og:site_name" content="セックル速報 - 無料セックス動画まとめ" />
		<meta name="twitter:card" content="summary">
		<meta name="twitter:site" content="@sekusoku">
		<link rel="canonical" href="http://sekusoku.com/video/<?=$video['master_id']?>" />
		<title><?=$video['title']?> | セックル速報 - 無料セックス動画まとめ</title>
		<link rel="shortcut icon" type="image/x-icon" href="/images/favicon.ico" />
		<link rel="icon" type="image/png" href="/images/favicon.png" />
		<link rel="apple-touch-icon" href="/images/apple-touch-icon.png" />

		<!-- Bootstrap -->
		<link rel="stylesheet" href="/css/bootstrap.min.css">
		<!-- Custom styles for Sticky footer with fixed navbar -->
		<link rel="stylesheet" href="/css/sticky-footer-navbar.css">
		<!-- bxSlider -->
		<link rel="stylesheet" href="/css/bxslider.css">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>

		<!-- Google Tag Manager -->
		<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-PQJKB4" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','GTM-PQJKB4');</script>

		<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="btn btn-default navbar-btn" data-toggle="modal" data-target=".openmodal"><span class="glyphicon glyphicon-th-list"></span></button>
					<a class="navbar-brand" href="/"><img src="/images/logo.png" alt="セックル速報" width="160" height="25"></a>
				</div>
				<div class="collapse navbar-collapse">
					<p class="navbar-text navbar-right">無料セックス動画を毎日更新！</p>
				</div>
			</div>
		</nav>

		<div class="container">

			<div class="row hidden-xs">

				<div class="col-xs-12">
					<ol class="breadcrumb">
						<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="/" itemprop="url"><span itemprop="title">ホーム</span></a></li>
						<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
							<?php if ($referer_flag): ?>
								<a href="/category/<?=$referer_category_id?>" itemprop="url"><span itemprop="title"><?=$referer_category_name?></span></a>
							<?php else: ?>
								<a href="/category/<?=$video['category'][0]['id']?>" itemprop="url"><span itemprop="title"><?=$video['category'][0]['name']?></span></a>
							<?php endif; ?>
						</li>
						<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" class="active"><span itemprop="title"><?=$video['title']?></span></li>
					</ol>
				</div>

			</div>

			<div class="row">

				<div class="col-xs-12">
					<h1><?=$video['title']?></h1>
				</div>

			</div>

			<div class="row">

				<div class="col-xs-12 col-sm-12 col-md-7">
					<div class="panel panel-default">
						<div class="panel-body">
							<ul class="bxslider">
								<?php foreach ($video['embed_tag'] as $embed_tag): ?>
									<li>
										<div class="embed-responsive embed-responsive-4by3">
											<?=$embed_tag?>
										</div>
									</li>
								<?php endforeach; ?>
							</ul>
							<div class="outside text-center">
								<p><span id="slider-prev" class="slider-prev-next"></span><span id="slider-next" class="slider-prev-next"></span></p>
							</div>
							<p>
								<?php foreach ($video['category'] as $key => $category): ?>
									<a href="/category/<?=$category['id']?>"><span class="label label-default"><?=$category['name']?></span></a>&nbsp;
								<?php endforeach; ?>
							</p>
							<p><?=$video['create_time']?></p>
						</div>
					</div>
				</div>

				<!-- <div class="col-xs-12 col-sm-12 col-md-5 visible-md visible-lg">
					<div class="panel panel-default text-center">
						<div class="panel-body">
							<p>AD</p>
						</div>
					</div>
					<div class="panel panel-default text-center">
						<div class="panel-body">
							<p>AD</p>
						</div>
					</div>
				</div> -->

			</div>

			<div class="row">

				<!-- <div class="col-xs-12 col-sm-6 col-md-3">
					<div class="thumbnail">
						<a href=""><img src="" alt="" class="img-rounded img-responsive"></a>
						<div class="caption">
							<h2><a href="">動画タイトル</a></h2>
							<p><a href=""><span class="label label-default">カテゴリー</span></a> <a href=""><span class="label label-default">カテゴリー</span></a> <a href=""><span class="label label-default">カテゴリー</span></a></p>
							<p>2014年12月31日</p>
						</div>
					</div>
				</div> -->

			</div>

		</div>

		<div class="footer">
			<div class="container text-center">
				<p class="text-muted">Copyright &copy; <?php echo date("Y"); ?> セックル速報 - 無料セックス動画まとめ All Rights Reserved.</p>
			</div>
		</div>

		<div class="modal fade openmodal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">

					<nav class="navbar navbar-default" role="navigation">
						<div class="container">
							<div class="navbar-header">
								<button type="button" class="btn btn-default navbar-btn navbar-left" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span></button>
							</div>
						</div>
					</nav>

					<div class="container">

						<div class="row">

							<!-- <div class="col-xs-12">
								<h3>キーワード検索</h3>
								<div class="col-xs-12">
									<form class="form-horizontal" role="form">
										<div class="form-group">
											<div class="input-group">
												<input type="text" class="form-control" placeholder="無料セックス動画の検索はこちらから">
												<span class="input-group-btn">
													<button class="btn btn-default" type="button"><span class="glyphicon glyphicon-search"></span></button>
												</span>
											</div>
										</div>
									</form>
								</div>
							</div> -->

							<div class="col-xs-12">
								<h3>カテゴリー検索</h3>
								<div class="well">
									<ul class="pager">
										<?php foreach ($categories as $category): ?>
											<li><a href="/category/<?=$category['id']?>"><?=$category['name']?></a></li>
										<?php endforeach; ?>
									</ul>
								</div>
							</div>

							<div class="col-xs-12">
								<ul class="nav nav-pills">
									<li><a href="/">ホーム</a></li>
									<li><a href="/about">セックル速報について</a></li>
									<li><a href="/ad">広告掲載について</a></li>
									<li><a href="/contact">お問い合わせ</a></li>
								</ul>
							</div>

						</div>

					</div>

				</div>
			</div>
		</div>

		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="/js/jquery.min.js"><\/script>')</script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
		<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
		<script src="/js/ie10-viewport-bug-workaround.js"></script>
		<!-- EqualHeight.js -->
		<script src="/js/jquery.equalheight.min.js"></script>
		<!-- bxSlider -->
		<script src="/js/jquery.fitvids.js"></script>
		<script src="/js/jquery.bxslider.min.js"></script>
		<!-- EqualHeight.js -->
		<script>
			$(function() {
				var equalHeight = $('.caption h2').equalHeight({wait: true});
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
		<script>
			$(function() {
				$('.bxslider').bxSlider({
					speed: 300,
					pagerType: 'short',
					infiniteLoop: false,
					nextSelector: '#slider-next',
					prevSelector: '#slider-prev',
					nextText: '<button type="button" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-chevron-right"></span></button>',
					prevText: '<button type="button" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-chevron-left"></span></button>',
					video: true,
					useCSS: false
				});
			});
		</script>
	</body>
</html>