<?php $stripedUrl=str_replace(array('http:/','http://','https://','https:/'),'',$_GET['url']);$url=(substr($_GET['url'],0,5)=='https')?'https://'.$stripedUrl:'http://'.$stripedUrl;?>
<!DOCTYPE html>
<html lang="en"> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
<meta name="robots" content="noindex,nofollow">	
<meta http-equiv="refresh" content="1; URL=<?=$url?>" />
<title>Redirecting to..</title> 
<style type="text/css">body {font:Arial,sans-serif;background-color:#f6f6f6;color:#222}#main {background:#fff;text-align:center;margin:15% auto;width:730px;border:1px solid #cbcbcb;border-radius:10px}h1{font:35px}h2{font:20px}</style> 
</head> 
<body>
<div id="main">
<h1>Redirecting to..</h1>
<h2><?=$url?></h2>
</div>	
</body> 
</html>