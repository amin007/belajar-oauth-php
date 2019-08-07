<?php
#-------------------------------------------------------------------------------------------------------------
/*
$fileList = glob('/*.*');
foreach($fileList as $filename)
{
	# Use the is_file function to make sure that it is not a directory.
	if(is_file($filename))
	{
		echo $filename . '<br>';
	}
}
*/
function getFileList($dir)
{
	# array to hold return value
	$retval = [];
	# add trailing slash if missing
	if(substr($dir, -1) != "/") { $dir .= "/"; }
	# open pointer to directory and read list of files
	$d = @dir($dir) or die("getFileList: Failed opening directory {$dir} for reading");
	while(FALSE !== ($entry = $d->read()))
	{
		# skip hidden files
		if($entry{0} == ".") continue;
		if(is_dir("{$dir}{$entry}"))
		{
			$retval[] = [
			'name' => "{$dir}{$entry}/",
			'type' => filetype("{$dir}{$entry}"),
			'size' => 0,
			'lastmod' => filemtime("{$dir}{$entry}")
			];
		}
		elseif(is_readable("{$dir}{$entry}"))
		{
			$retval[] = [
			'name' => "{$dir}{$entry}",
			'type' => mime_content_type("{$dir}{$entry}"),
			'size' => filesize("{$dir}{$entry}"),
			'lastmod' => filemtime("{$dir}{$entry}")
			];
		}
	}

	$d->close();
	return $retval;
}
#-------------------------------------------------------------------------------------------------------------
function pautan($name,$web)
{
	$icon1 = '<i class="fas fa-globe-asia fa-spin"></i>';
	$icon2 = '<i class="far fa-folder fa-spin"></i>';
	$icon = ($name != $web) ? $icon1 : $icon2;
	return '' . $icon
	. '<a target="_blank" href="' . $web . '">'
	. $name . '</a><hr>';
}
#-------------------------------------------------------------------------------------------------------------
function list_files()
{
	$dirlist = getFileList("./");
	//echo "<pre>",print_r($dirlist),"</pre>";
	//echo '<tr><td> name</td><td> type</td><td> size</td><td> lastmod</td></tr>';
	$failIni = basename($_SERVER['PHP_SELF']);
	diatas();
	echo "\n$failIni<hr>";
	foreach(getWebsite() as $name => $web):
		echo "\n" . pautan($name,$web);
	endforeach;
	foreach($dirlist as $key02 => $value):
		if ($value['type'] == 'dir'):
			echo "\n" . pautan($value['name'],$value['name']);
		else:echo '';endif;
	endforeach;
	dibawah();
}
#-------------------------------------------------------------------------------------------------------------
function diatas($tajuk='***')
{
print <<<END
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<title>$tajuk</title>
<link href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" rel="stylesheet" type="text/css">
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" type="text/css">

<style type="text/css">
html, body {
	height: 100%;
}
body {
	margin: 0;
	padding: 0;
	width: 100%;
	display: table;
	font-weight: 100;
}
.kotakAtas {
	text-align: center;
	display: table-cell;
	vertical-align: middle;
}
.kotakTengah {
	text-align: center;
	display: inline-block;
}
.kotakLabel {
	margin: .4rem;
}
/*label { margin: .4rem; }*/
</style>
<style type="text/css">
table.excel {
	border-style:ridge;
	border-width:1;
	border-collapse:collapse;
	font-family:sans-serif;
	font-size:11px;
}
table.excel thead th, table.excel tbody th {
	background:#CCCCCC;
	border-style:ridge;
	border-width:1;
	text-align: center;
	vertical-align: top;
}
table.excel tbody th { text-align:center; vertical-align: top; }
table.excel tbody td { vertical-align:bottom; }
table.excel tbody td
{
	padding: 0 3px; border: 1px solid #aaaaaa;
	background:#ffffff;
}
</style>
</head>
<body>

<div class="kotakAtas">
<div class="kotakTengah">
<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

END;
}
#-------------------------------------------------------------------------------------------------------------
function dibawah()
{
	print <<<END
<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
</div><!-- / class="kotakTengah" -->
</div><!-- / class="kotakAtas" -->

<!-- Footer
================================================== -->
<!-- footer class="footer">
	<div class="container">
		<span class="label label-info">
		&copy; Hak Cipta Terperihara 2019. Theme Asal Bootstrap Twitter
		</span>
	</div>
</footer -->

<!-- khas untuk jquery dan js2 lain
================================================== -->
<script type="text/javascript" src="https://code.jquery.com/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
END;
}
#-------------------------------------------------------------------------------------------------------------
?>