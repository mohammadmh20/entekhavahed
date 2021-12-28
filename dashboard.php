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
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "frm_memo")) {


	  
  $updateSQL = sprintf("UPDATE login SET memo=%s WHERE id=1 ",
                       GetSQLValueString($_POST['txta_memo'], "text"));

  mysql_select_db($database_register, $register);
  $Result1 = mysql_query($updateSQL, $register) or die(mysql_error());

  $updateGoTo = "dashboard.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_register, $register);
$query_Recordset1 = "SELECT * FROM setup";
$Recordset1 = mysql_query($query_Recordset1, $register) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_register, $register);
$query_rec_login = "SELECT * FROM login";
$rec_login = mysql_query($query_rec_login, $register) or die(mysql_error());
$row_rec_login = mysql_fetch_assoc($rec_login);
$totalRows_rec_login = mysql_num_rows($rec_login);

mysql_select_db($database_register, $register);
$query_rec_news = "SELECT id, title FROM news";
$rec_news = mysql_query($query_rec_news, $register) or die(mysql_error());
$row_rec_news = mysql_fetch_assoc($rec_news);
$totalRows_rec_news = mysql_num_rows($rec_news);

mysql_select_db($database_register, $register);
$query_rec_static = "SELECT id, title FROM staticpage";
$rec_static = mysql_query($query_rec_static, $register) or die(mysql_error());
$row_rec_static = mysql_fetch_assoc($rec_static);
$totalRows_rec_static = mysql_num_rows($rec_static);

mysql_select_db($database_register, $register);
$query_rec_que = "SELECT name, id FROM question";
$rec_que = mysql_query($query_rec_que, $register) or die(mysql_error());
$row_rec_que = mysql_fetch_assoc($rec_que);
$totalRows_rec_que = mysql_num_rows($rec_que);

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

$txt_total_setup = $totalRows_Recordset2;

mysql_select_db($database_register, $register);

$query=mysql_query('select * from counter');
$field=mysql_fetch_array($query);

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
.style1{
	color:#FF0000;
}
.style2{
	color:#009900;
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
                  <a href="../Templates/advance-search.php">جستجوی پیشرفته  </a></div></td>
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
          <div class="bigtext">داشبورد</div>
        </div>
          <div class="bigmiddle" align="center">
                <div class="text">
                  <div align="right">
                      <div class="padd1">
                      <form action="<?php echo $editFormAction; ?>" method="POST" name="frm_memo" id="frm_memo">
                      <table width="516" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                      <td align="center"><br/><a href="manage-stu.php"><img src="../img/stu.png" title="مدیریت دانشجویان" /></a>&nbsp;&nbsp;&nbsp;<a href="manage-news.php" ><img src="../img/news.png" title="مدیریت نظزات سایت" /></a>&nbsp;&nbsp;&nbsp;<a href="manage-que.php"><img src="../img/que.png" title="مدیریت پرسش و پاسخ"/></a>&nbsp;&nbsp;&nbsp;<a href="manage-static.php" title="مدیریت صفحات استاتیک"><img src="../img/static_page.png" /></a>&nbsp;&nbsp;&nbsp;<a href="admin-manage.php" title="تنظیمات کاربری"><img src="../img/users.png" /></a>&nbsp;&nbsp;&nbsp;<a href="manage-com.php" title="تهیه نسخه پشتیبان"><img src="../img/1114.png" width="64" height="64" /></a></td>
                      </tr>
                      <tr>
                      <td><br/>یادداشت :</td>
                      </tr>
                      <tr>
                      <td><textarea name="txta_memo" cols="80" rows="6"><?php echo $row_rec_login['memo']; ?></textarea></td>
                      </tr>
                       <tr>
                      <td><br/><input name="btn_submit" type="submit" value="&#1584;&#1582;&#1740;&#1585;&#1607;"></td>
                      </tr>
                      </table>
                      <input type="hidden" name="MM_update" value="frm_memo" />
                      </form>
                      <table width="516" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                      <td>وضعیت سایت : <?php if($row_rec_login['enabled'] == "en"){ echo "<span class='style2'><b>فعال</b> </span>" ;}else{ echo "<span class='style1'><b>غیر فعال</b> </span>"; }?></td>
                      </tr>
                      <tr><br/>
                      <td><br/>تعداد خبرهای سایت : <?php echo $totalRows_rec_news ?> </td>
                      <td><br/>تعداد صفحات استاتیک : <?php echo $totalRows_rec_static ?> </td>
                      </tr>
                       <tr>
                      <td><br/>تعداد افراد ثبت نام شده : <?php echo $totalRows_Recordset1 ?> نفر</td>
                      <td><br/>تعداد سوالات : <?php echo $totalRows_rec_que ?> </td>
                      </tr>
                      <tr>
                      <td><br/>کل بازدیدها : <?php echo $field['total']; ?></td>
                      <td><br/>بازدید دیروز : <?php echo $field['yesterday']; ?> </td>
                      <td><br/>بازدید امروز : <?php echo $field['today']; ?> </td>
                      </tr>
                      </table>
                        <br/>
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

</div>
</center>
</body>
</html>

<?php

mysql_free_result($Recordset1);

mysql_free_result($rec_login);

mysql_free_result($rec_news);

mysql_free_result($rec_static);

mysql_free_result($rec_que);

mysql_free_result($Recordset2);
?>
