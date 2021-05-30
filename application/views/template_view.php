<? session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<title>ToDo</title>
		<link rel="stylesheet" type="text/css" href="/css/style.css" />
		<script src="/js/jquery-1.6.2.js" type="text/javascript"></script>
        <script src="/js/script.js" type="text/javascript"></script>
	</head>
	<body>
		<div id="wrapper">
		<header>
        <div class="menu">
            <div class="left">
				<? 
					if(empty($_SESSION['login'])){
						echo "<a href=\"/\">ToDo</a>";
					}else{
						echo "<a href=\"/kanban/desks\">ToDo</a>";
					}
				?>
				
			</div>
			<div style="display: inline-block;" class="name">
				<?
					echo $title;
				?>
			</div>
            <!-- <div class="right"><a href="/user/login">Войти</a></div> -->
			<?
				if(empty($_SESSION['login'])){
					echo "<div class=\"right\"><a href=\"/user/login\">Вход</a></div>";
				}else{
				echo "<div class=\"right\">
				<span href=\"/user/login\" style=\"margin-right: 30px\">" .$_SESSION['login']. "</span>
				<a href=\"/user/logout\">Выйти</a></div>";
				}
			?>
        </div>
        <div class="clearfix"></div>
    </header>
				<div id="content">
					<div class="box">
						<?php include 'application/views/'.$content_view; ?>
					</div>
					<br class="clearfix" />
				</div>
				<br class="clearfix" />
			</div>
			
		</div>
	</body>
</html>