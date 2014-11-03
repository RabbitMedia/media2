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

		<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#mobile-menu">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="/dashboard">Dashboard</a>
				</div>

				<div class="collapse navbar-collapse" id="mobile-menu">
					<ul class="nav navbar-nav">
						<li class="active"><a href="/dashboard/crawled_videos">アップ待ち <span class="badge"><?=$total_count?></span></a></li>
						<li><a href="/dashboard">アップ済み</a></li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li><a href="/dashboard/logout">ログアウト</a></li>
					</ul>
				</div>
			</div>
		</nav>

		<div class="container">

			<?php foreach ($videos as $id => $video): ?>

				<div class="row">

					<div class="col-xs-12">
						<h1><?=$video['create_time']?>にクロールされた動画</h1>
					</div>

				</div>

				<?php echo form_open('dashboard/upload'); ?>

				<div class="row">

					<div class="col-xs-12 col-sm-12 col-md-6">
						<div class="panel panel-default">
							<div class="panel-body">
								<h2>動画（<?=$video['duration']?>）</h2>
								<div id="videoToggle">
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
								</div>
								<!-- <button id="videoTogglePush" type="button" class="btn btn-default btn-sm">表示切替</button> -->
							</div>
						</div>
					</div>

				</div>

				<div class="row">

					<div class="col-xs-12 col-sm-12 col-md-12">
						<div class="panel panel-default">
							<div class="panel-body">
								<h2>サムネイル選択</h2>
								<?php if ($video['thumbnail']): ?>
									<?php foreach ($video['thumbnail'] as $thumbnail): ?>
										<?php if ($thumbnail): ?>
											<?php foreach ($thumbnail as $value): ?>
												<label><img src="<?=$value?>" class="img-thumbnail" width="136" height="102"><input type="radio" name="thumbnail" value="<?=$value?>" required="true"></label>
											<?php endforeach; ?>
										<?php else: ?>
											<h2 class="text-danger">サムネイルの取得に失敗しました</h2>
										<?php endif; ?>
									<?php endforeach; ?>
								<?php endif; ?>
							</div>
						</div>
					</div>

				</div>

				<div class="row">

					<div class="col-xs-12 col-sm-12 col-md-12">
						<div class="panel panel-default">
							<div class="panel-body">
								<h2>タイトル</h2>
								<div class="form-group">
									<input type="text" class="form-control" name="title" value="" placeholder="タイトルを入力" required>
								</div>
								<p class="text-danger">meta description "【動画あり】**タイトル**これはエロすぎるｗｗｗ" を意識してネーミングすること</p>
								<h2>参考タイトル</h2>
								<?php foreach ($video['title'] as $key => $value): ?>
									<div class="well well-sm">
										<?=$value?>(<?=$video['media'][$key]?>)
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>

				</div>

				<div class="row">

					<div class="col-xs-12 col-sm-12 col-md-6">
						<div class="panel panel-default">
							<div class="panel-body">
								<h2>メインカテゴリー選択</h2>
								<div class="well well-sm">
									<?php foreach ($categories as $category): ?>
										<label><h3><span class="label label-primary"><?=$category['name']?> <input type="checkbox" name="main_category[]" value="<?=$category['id']?>"></span></h3></label>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					</div>

					<div class="col-xs-12 col-sm-12 col-md-6">
						<div class="panel panel-default">
							<div class="panel-body">
								<h2>サブカテゴリー選択</h2>
								<div class="well well-sm">
									<?php foreach ($categories as $category): ?>
										<label><h3><span class="label label-primary"><?=$category['name']?> <input type="checkbox" name="sub_category[]" value="<?=$category['id']?>"></span></h3></label>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					</div>

				</div>

				<div class="row">

					<div class="col-xs-12 col-sm-12 col-md-12">
						<div class="panel panel-default">
							<div class="panel-body">
								<div class="col-xs-6 col-sm-6 col-md-6 text-center">
									<div class="form-group">
										<h2><label>アップ確認 <input type="checkbox" name="confirm" value="" required></h2></label>
									</div>
									<div class="form-group">
										<input type="hidden" name="crawler_master_id" value="<?=$video['crawler_master_id']?>">
										<input type="hidden" name="duration" value="<?=$video['duration']?>">
										<input type="hidden" name="current_page" value="<?=$current_page?>">
										<?php foreach ($video['type'] as $key => $type): ?>
											<input type="hidden" name="type[]" value="<?=$type?>">
											<input type="hidden" name="video_url_id[]" value="<?=$video['video_url_id'][$key]?>">
										<?php endforeach; ?>
										<button type="submit" class="btn btn-lg btn-default btn-block">アップする</button>
									</div>
									<?php echo form_close(); ?>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6 text-center">
									<?php echo form_open('dashboard/delete_crawled_videos'); ?>
										<div class="form-group">
											<h2><label>削除確認 <input type="checkbox" name="confirm" value="" required></h2></label>
										</div>
										<div class="form-group">
											<input type="hidden" name="crawler_master_id" value="<?=$video['crawler_master_id']?>">
											<input type="hidden" name="current_page" value="<?=$current_page?>">
											<button type="submit" class="btn btn-lg btn-primary btn-block">削除する</button>
										</div>
									<?php echo form_close(); ?>
								</div>
							</div>
						</div>
					</div>

				</div>

			<?php endforeach; ?>

			<div class="row">

				<div class="col-xs-12 hidden-xs text-center">
					<ul class="pagination pagination-lg">
						<?=$pagination?>
					</ul>
				</div>
				<div class="col-xs-12 visible-xs text-center">
					<ul class="pagination">
						<?=$pagination?>
					</ul>
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
		<!-- bxSlider -->
		<script src="/js/jquery.fitvids.js"></script>
		<script src="/js/jquery.bxslider.min.js"></script>
		<!-- bxSlider -->
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
		<script>
			// $(function(){
			// 	$("#videoTogglePush").click(function(){
			// 		$("#videoToggle").slideToggle();
			// 	});
			// });
		</script>
	</body>
</html>