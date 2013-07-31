<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title>sourceMakeup - File <?php echo $file; ?></title>

	<meta name="viewport" content="width=1280, initial-scale=1, maximum-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge" />

	<link rel="shortcut icon" type="image/x-icon" href="http://jquery-jkit.com/favicon.ico" />

	<script src="js/jquery-1.9.1.min.js"></script>
	<script src="js/jquery.easing.1.3.js"></script>
	<script src="js/jquery.jkit.1.1.29.min.js"></script>
	<script src="js/sourcemakeup.js"></script>

	<script type="text/javascript" src="libs/google-code-prettify/prettify.js"></script>
	<link href="libs/google-code-prettify/prettify.css" type="text/css" rel="stylesheet" />

	<link href="css/sourcemakeup.css" type="text/css" rel="stylesheet" />
	<style>
		p.selected {
			font-weight: bold;
		}

		#filelist {
			left:  0px;
		    position: fixed;
		    width: 300px;
		    bottom: 0px;
		    top: 0px;
		    overflow-y: scroll;
		}

		#filelist div {
			padding: 20px;
		}

		#filelist p {
			margin-bottom: 10px;
		}

		body > table {
			margin-left: 300px;
		}

		hr {
			display: none;
		}
	</style>

</head>
<body>
	
	<div id="options" data-jkit="[tooltip:text=Show compiler instructions?]"><input type="checkbox" id="showinstructions" value="1"></div>
	<div id="search" data-jkit="[filter:global=yes;by=text;affected=tr.docu;loader=#spinner]"><input class="jkit-filter" placeholder="search ..." type="text" value=""></div>
	<div id="overview" data-jkit="[summary:what=headers;linked=yes;from=table.container;scope=all;style=select;indent=yes]"></div>
	<div id="filelist"><div>
		
		<?php foreach($files as $f):

				$selected 	= ($_GET['file']==$f)
							?'selected'
							:'';

				$url = $_SERVER['PHP_SELF'].'?file='.$f;
		?>

			<p class="<?php echo $selected;?>">
				<a href="<?php echo $url;?>"><?php echo $f;?></a></p>

		<?php endforeach; ?>

	</div></div>
	
	<table class="container" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<th class="comment">
					
				</th>
				<th class="code">
					Code
				</th>
			</tr>
		</thead>
		<tbody>
			<?=$output?>
		</tbody>
	</table>
	
	<div id="spinner"><img src="imgs/spinner.gif"></div>
	
</body>
</html>