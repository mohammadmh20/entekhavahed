<?php require_once('../Connections/register.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "index.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
mysql_select_db($database_register, $register);
$query_rec_login = "SELECT * FROM login";
$rec_login = mysql_query($query_rec_login, $register) or die(mysql_error());
$row_rec_login = mysql_fetch_assoc($rec_login);
$totalRows_rec_login = mysql_num_rows($rec_login);

$maxRows_rec_news = 1;
$pageNum_rec_news = 0;
if (isset($_GET['page'])) {
  $pageNum_rec_news = $_GET['page'];
}
$startRow_rec_news = $pageNum_rec_news * $maxRows_rec_news;

$maxRows_rec_news = 1;
$pageNum_rec_news = 0;
if (isset($_GET['pageNum_rec_news'])) {
  $pageNum_rec_news = $_GET['pageNum_rec_news'];
}
$startRow_rec_news = $pageNum_rec_news * $maxRows_rec_news;

mysql_select_db($database_register, $register);
if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; }; 
$start_from = ($page-1) * 1; 
$query_rec_news = "SELECT id, title, little_news FROM news ORDER BY id DESC LIMIT $start_from, 1";
$rec_news = mysql_query($query_rec_news, $register) or die(mysql_error());
$row_rec_news = mysql_fetch_assoc($rec_news);

if (isset($_GET['totalRows_rec_news'])) {
  $totalRows_rec_news = $_GET['totalRows_rec_news'];
} else {
  $all_rec_news = mysql_query($query_rec_news);
  $totalRows_rec_news = mysql_num_rows($all_rec_news);
}
$totalPages_rec_news = ceil($totalRows_rec_news/$maxRows_rec_news)-1;

mysql_select_db($database_register, $register);
$query_Recordset1 = "SELECT * FROM setup";
$Recordset1 = mysql_query($query_Recordset1, $register) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

$queryString_rec_news = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "page") == false && 
        stristr($param, "totalRows_rec_news") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rec_news = "&" . htmlentities(implode("&", $newParams));
  }
}

$maxRows_Recordset2 = 5;
$pageNum_Recordset2 = 0;
if (isset($_GET['pageNum_Recordset2'])) {
  $pageNum_Recordset2 = $_GET['pageNum_Recordset2'];
}
$startRow_Recordset2 = $pageNum_Recordset2 * $maxRows_Recordset2;

mysql_select_db($database_register, $register);
$query_Recordset2 = "SELECT name, family FROM setup ORDER BY code DESC";
$query_limit_Recordset2 = sprintf("%s LIMIT %d, %d", $query_Recordset2, $startRow_Recordset2, $maxRows_Recordset2);
$Recordset2 = mysql_query($query_limit_Recordset2, $register) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);

if (isset($_GET['totalRows_Recordset2'])) {
  $totalRows_Recordset2 = $_GET['totalRows_Recordset2'];
} else {
  $all_Recordset2 = mysql_query($query_Recordset2);
  $totalRows_Recordset2 = mysql_num_rows($all_Recordset2);
}
$to = ceil($totalRows_Recordset2/$maxRows_Recordset2)-1;

$queryString_rec_news = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "page") == false && 
        stristr($param, "totalRows_rec_news") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rec_news = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rec_news = sprintf("&to=%d%s", $totalRows_rec_news, $queryString_rec_news);
$txt_total_setup = $totalRows_Recordset2;
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html dir="rtl">
<head>
<link rel="shortcut icon" href="../admin/<?php echo $row_rec_login['icon']; ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $row_rec_login['title_site'];?></title>
<meta name="keywords" content="<?php echo $row_rec_login['keyword']; ?>">
<meta name="description" content="<?php echo $row_rec_login['Description']; ?>">
<link href="../style/style.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
body,td,th {
	font-family: tahoma;
	font-size: 12px;
}
a {
	font-family: tahoma;
	font-size: 12px;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}

-->
</style>
</head>

<body>
<center>
<div class="tblmain">
<table width="788" height="484" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2" scope="row" valign="top" class="backg" width="600">
	<br/>
    <br/>
    <br/>
    <br/>
      
                    <div class="navbar" align="center">
                        <div style="margin-right:180px;" align="center">
    <ul>
    <center>
    	<li><a href="../Templates/index.php">صفحه اصلی</a></li>
    	<li><a href="../Templatesregister.php">ثبت نام</a></li>
    	<li><a href="../Templatesadvance-search.php">جستجو</a></li>
    	<li style="border-left:1px solid #FFF"><a href="../Templates/que-ans.php">پرسش و پاسخ</a></li>
        </center>
    </ul>
    </div>
    </div>    </td>
  </tr>
  <tr>
    <td width="216" rowspan="2" valign="top" align="center">
      <div align="right" class="c">
      <div class="menu">
      <div class="top">
        <div class="text">جستجو</div></div>
        <div class="middle">
        <div class="text">
        <form id="search-code" name="search-code" method="post" action="search.php">
          <table width="64" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td width="64" valign="middle"><p>کد پیگیری :
                </p>
                <p>
                  <input type="text" name="txtsearch" id="txtsearch" size="20">
                  </p></td>
            </tr>
            <tr>
              <td><div align="center">
                <input type="submit" name="btnsearch" id="btnsearch" value="جستجو">
              </div>
                <div align="center">
                  <a href="advance-search.php">جستجوی پیشرفته                  </a></div></td>
            </tr>
          </table>
          </form>
        </div>
    </div>
	<div class="bottom"></div>
    </div>
	<div class="menu">
      <div class="top">
      <div class="text">آمار و ارقام</div></div>
        <div class="middle">
        <div class="text">
        <?php function getRealIpAddr() 
{ 
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet 
    { 
      $ip=$_SERVER['HTTP_CLIENT_IP']; 
    } 
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy 
    { 
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR']; 
    } 
    else 
    { 
      $ip=$_SERVER['REMOTE_ADDR']; 
    } 
    return $ip; 
}  
?>
        آی پی شما : <?php echo getRealIpAddr() ?><br/>
        افراد آنلاین : <?php include('user_online.php');?><br/>
        ثبت نام شده : <?php echo $totalRows_Recordset1 ?> نفر<br/>
    </div>
    </div>
    <div class="bottom"></div>
    </div>
    <div class="menu">
    <div class="top">
      <div class="text">زمان</div></div>
        <div class="middle">
        <div class="text">
        <?php date_default_timezone_set($row_rec_login['time_site']); ?>
        ساعت :  <?php echo date('H:i:s'); ?> <br/>
        تاریخ : <?php echo date('l F d, Y');?> <br/>
    </div>
    </div>
    <div class="bottom"></div>
    </div>
    <div class="menu">
      <div class="top">
      <div class="text">5 نفر آخر ثبت نام شده</div></div>
        <div class="middle">
        <div class="text">                
          <?php do { ?>
          <?php if($txt_total_setup != 0){ ?>
            <table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td scope="row"><img src="../img/user_set.png">  <?php echo $row_Recordset2['name']; ?> <?php echo $row_Recordset2['family']; ?></td>
        </tr>
                </table>
                          <?php }else{?>
          
                <center> کسی ثبت نام نشده است</center>
               
                <?php } ?>

            <?php } while ($row_Recordset2 = mysql_fetch_assoc($Recordset2)); ?></div>        
    </div>
    <div class="bottom"></div>
    </div>
    <div class="bottom"></div>
    </div>    </td>
  </tr>
  <tr>
    <td width="574" align="center" valign="top" scope="row"><div align="right" class="c">
      <div class="menu">
        <div class="bigtop" align="center">
          <div class="bigtext">خطا در ورود</div>
        </div>
          <div class="bigmiddle" align="center">
                <div class="text">
                  <div align="center">
                      <div class="padd1">
                      <br/>
                      نام کاربری و رمز عبور اشتباه می باشد<br/>
                      <br/>
                      <a href="index.php">برگشت به صفحه ورود</a>  
                      <br/><br/>
                      </div>
                  </div>
                </div>
          </div>
          
<div class="bigbottom"></div>
<br/>
<center>
</center>
      </div>
    <tr>
    <td colspan="3" style="color:#999; margin-top:5px; padding-bottom:10px;">
    <br />
    <img align="absmiddle" src="../img/divide.png" />
    <center>
        Copy right &copy; 2011 . All right reserved<br />
	تمامی حقوق برای احمد نعمتی می باشد<br /><br />
    </center>    </td>
  </tr>
</table>

<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/jquery.nivo.slider.pack.js"></script>
<script type="text/javascript" src="../js/jquery.fancybox.pack.js"></script>
<script type="text/javascript" src="../js/jquery.easing.pack.js"></script>
<script type="text/javascript" src="../js/DD_belatedPNG.js"></script>
<script type="text/javascript" src="../js/filter.js"></script>
<script type="text/javascript" src="../js/custom.js"></script>
</div>
</center>
</body>
</html>
<?php
mysql_free_result($rec_news);

mysql_free_result($Recordset1);

mysql_free_result($Recordset2);
?>

