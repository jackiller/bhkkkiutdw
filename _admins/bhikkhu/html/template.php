<?php

	if (!isset($_SESSION)) {
		session_start();
	}

	$request_url = pathinfo($_SERVER['REQUEST_URI']);
	$request_url_parts = explode('/', rtrim($request_url['dirname'], '/'));
	$url = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $request_url_parts[1] . '/_admins/';

	if (!isset($_SESSION['jigowatt']['user_level'])) {
		//echo 'Only admins or special users can view this content.';
		header('Location: ' . $url);
		exit;
	}

	// 1 = admin, 2 = special, 3 = user, 4 = view
	if ( in_array( 2, $_SESSION['jigowatt']['user_level'] ) ) {
		$login_fail = FALSE;
	} elseif ( in_array( 3, $_SESSION['jigowatt']['user_level'] ) ) {
		$login_fail = FALSE;
	} elseif ( in_array( 4, $_SESSION['jigowatt']['user_level'] ) ) {
		$login_fail = FALSE;
	} else {
		$login_fail = TRUE;
	}

	if ($login_fail == TRUE) {
		//echo 'Only admins or special users can view this content.';
		header('Location: ' . $url);
		exit();
	}

?>
<!DOCTYPE HTML>
<html>
    <head>
    	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
    	<title><?php echo $title_1 ?> - <?php echo $title_2 ?> - eXtended CRUD &amp; Data Management System</title>
        <link href="assets/shCore.css" rel="stylesheet" type="text/css" />
        <link href="assets/shThemeDjango.css" rel="stylesheet" type="text/css" />
        <link href="assets/style.css" rel="stylesheet" type="text/css" />
        <?php if($theme == 'bootstrap'){ ?>
        <link href="../../xcrud/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <?php } ?>
		<link rel="stylesheet" href="../../vendors/smooth-scroll-to-top-bottom/css/totop.css">
    </head>

    <body>
        <div id="page">
		<?php if (substr($_SERVER["HTTP_HOST"], 0, 9) == "localhost") : ?>
			<div id="menu_dev"><?php include(dirname(__FILE__).'/menu.php') ?></div>
		<?php else: ?>
			<div id="menu"><?php include(dirname(__FILE__).'/menu.php') ?></div>
		<?php endif; ?>
            <div id="content">
                <div class="clr">&nbsp;</div>
                <?php include($file) ?>
                <div class="clr">&nbsp;</div>
            </div>
        </div>
        <?php if($theme == 'bootstrap'){ ?>
        <script src="../../xcrud/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <?php } ?>
		<div id="totopscroller"></div>
		<script>
		$(function(){
			$('#totopscroller').totopscroller();
		});
		</script>
    </body>
	<script src="../../vendors/smooth-scroll-to-top-bottom/js/jquery.totop.js"></script>
</html>