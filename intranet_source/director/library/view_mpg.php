<?
$file = $HTTP_GET_VARS[file];
?>

<html>
<head>
<title><? echo $file ?></title>
</head>
<body>
<embed src="/director/library/<? echo $file?>" autostart = "true" width = "100%" height = "100%" >
</body>
</html>
