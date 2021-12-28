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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "frm_admin")) {


	move_uploaded_file($_FILES["file"]["tmp_name"],
	"upload/favicon.ico");
	$file_name = "upload/favicon.ico";
	  
  $updateSQL = sprintf("UPDATE login SET `user`=%s, password=%s, title_site=%s, `Description`=%s, keyword=%s ,int_news=%s , icon='$file_name' , enabled=%s , title_en=%s , time_site=%s WHERE id=1 ",
                       GetSQLValueString($_POST['adm_user'], "text"),
                       GetSQLValueString($_POST['adm_pass'], "text"),
                       GetSQLValueString($_POST['adm_title'], "text"),
                       GetSQLValueString($_POST['Description'], "text"),
                       GetSQLValueString($_POST['keyword'], "text"),
                       GetSQLValueString($_POST['int_news'], "int"),
					   GetSQLValueString($_POST['adm_enabled'], "text"),
					   GetSQLValueString($_POST['adm_title_en'], "text"),
					   GetSQLValueString($_POST['adm_time_site'], "text"));

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
                  <a href="../Templates/advance-search.php">جستجوی پیشرفته                  </a></div></td>
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
          <div class="bigtext">تنظیمات کاربری</div>
        </div>
          <div class="bigmiddle" align="center">
                <div class="text">
                  <div align="right">
                      <div class="padd1">
                        <form action="<?php echo $editFormAction; ?>" method="POST" name="frm_admin" id="frm_admin" enctype='multipart/form-data'>
                        <br/>
                          <table width="493" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                              <td scope="row" width="203"><label for="adm_enabled">&nbsp;&nbsp;وضعیت سایت :</label>
                            </td>
                          <td width="219"><select name="adm_enabled">
                            <?php if($row_rec_login['enabled'] == "en") {?>
                              <option value="en" selected>فعال</option>
                              <option value="des">غیر فعال</option>
                              <?php }else{ ?>
                              <option value="des">غیر فعال</option>
                               <option value="en">فعال</option>
                              <?php } ?>
                                  </select></td>
                            </tr>
                            <tr>
                              <td scope="row" width="203"><br/>
                                <label for="adm_title_en">&nbsp;&nbsp;متن غیرفعال :</label>
                            </td>
                            <td width="219"><br/>
                            <textarea name="adm_title_en" cols="50" rows="3" id="adm_title_en"><?php echo $row_rec_login['title_en']; ?></textarea>
                           </td>
                            </tr>
                          <tr>
                            <td align="right"><br/><label for="file">&nbsp;&nbsp;آیکون سایت :</label></td>
                
                 <td colspan="2">
                  <br/><input class="textbox" type="file" name="file" id="file" />&nbsp;&nbsp;&nbsp;&nbsp;<img src="../admin/<?php echo $row_rec_login['icon']; ?>" /></td>
                  <td width="17"> </td>
                  </tr>
                          <tr>
                              <td scope="row" width="203"><br/>
                                <label for="adm_title">&nbsp;&nbsp;عنوان سایت :</label>
                            </td>
                            <td width="219"><br/>
                            <input type="text" name="adm_title" id="adm_title" value="<?php echo $row_rec_login['title_site']; ?>" size="50"></td>
                            </tr>
                             <tr>
                              <td scope="row" width="203"><br/>
                                <label for="int_news">&nbsp;&nbsp;تعداد اخبار در هر صفحه :</label>
                              </td>
                              <td width="219"><br/>
                               <input type="text" name="int_news" id="int_news" value="<?php echo $row_rec_login['int_news']; ?>" size="3"></td>
                            </tr>
                            <tr>
                              <td scope="row" width="203"><br/>
                                <label for="Description">&nbsp;&nbsp;توضیحات - Description :</label>
                              </td>
                              <td width="219"><br/>
                                <textarea name="Description" cols="50" rows="3" id="Description"><?php echo $row_rec_login['Description']; ?></textarea></td>
                            </tr>
                                <tr>
                                  <td scope="row" width="203"><br/>
                                    <label for="keyword">&nbsp;&nbsp;کلمات کلیدی - Keyword :</label>
                                  </td>
                                  <td width="219"><br/>
                                    <textarea name="keyword" cols="50" rows="3" id="keyword"><?php echo $row_rec_login['keyword']; ?></textarea></td>
                            </tr>
                                <tr>
                              <td scope="row" width="203"><br/>
                                <label for="adm_time_site">&nbsp;&nbsp;زمان محلی :</label>
                            </td>
                            <td width="219"><br/>
                              <select name="adm_time_site">
                        <option value="Asia/Tehran">Asia/Tehran</option>
                        <option value="Kwajalein">Kwajalein</option>
                        <option value="Pacific/Midway">Pacific/Midway</option>
                        <option value="Pacific/Honolulu">Pacific/Honolulu</option>
                        <option value="America/Anchorage">America/Anchorage</option>
                        <option value="America/Los_Angeles">America/Los_Angeles</option>
                        <option value=" America/Denver">America/Denver</option>
                        <option value="America/Tegucigalpa">America/Tegucigalpa</option>
                        <option value="America/New_York">America/New_York</option>
                        <option value="America/Caracas ">America/Caracas</option>
                        <option value="America/Halifax">America/Halifax</option>
                        <option value="America/St_Johns">America/St_Johns</option>
                        <option value=" America/Argentina/Buenos_Aires">America/Argentina/Buenos_Aires</option>
                        <option value="America/Sao_Paulo">America/Sao_Paulo</option>
                        <option value="Atlantic/South_Georgia">Atlantic/South_Georgia</option>
                        <option value="Atlantic/Azores">Atlantic/Azores</option>
                        <option value="Europe/Dublin">Europe/Dublin</option>
                        <option value="Europe/Belgrade ">Europe/Belgrade</option>
                        <option value="Europe/Minsk">Europe/Minsk</option>
                        <option value="Asia/Kuwait ">Asia/Kuwait</option> 
                        <option value="Asia/Muscat ">Asia/Muscat</option>
                        <option value="Asia/Yekaterinburg ">Asia/Yekaterinburg</option>
                        <option value="Asia/Kolkata">Asia/Kolkata</option> 
                        <option value="Asia/Katmandu">Asia/Katmandu</option> 
                        <option value="Asia/Dhaka">Asia/Dhaka</option> 
                        <option value="Asia/Rangoon">Asia/Rangoon</option> 
                        <option value="Asia/Krasnoyarsk">Asia/Krasnoyarsk</option>  
                        <option value="Asia/Brunei">Asia/Brunei</option>
                        <option value="Asia/Seoul">Asia/Seoul</option>
                        <option value="Australia/Darwin">Australia/Darwin</option>
                        <option value="Australia/Canberra">Australia/Canberra</option>
                        <option value="Asia/Magadan">Asia/Magadan</option>
                        <option value="Pacific/Fiji">Pacific/Fiji</option>
                        <option value="Pacific/Tongatapu">Pacific/Tongatapu</option>
                              </select></td>
                            </tr>
                            <tr>
                              <td scope="row" width="203"><br/>
                                <label for="adm_user">&nbsp;&nbsp;نام کاربری :</label>
                              </td>
                              <td width="219"><br/>
                              <input type="text" name="adm_user" id="adm_user" value="<?php echo $row_rec_login['user']; ?>"></td>
                            </tr>
                            <tr>
                              <td scope="row"><br/><label for="adm_pass">&nbsp;&nbsp;رمزعبور :</label>
                              </td>
                              <td><br/><input type="password" name="adm_pass" id="adm_pass" value="<?php echo $row_rec_login['password']; ?>"></td>
                            </tr>
                            <tr>
                              <td scope="row" colspan="2"><div align="center"><br/><input name="btn_submit" type="submit" value="ثبت">
                              </div></td>
                              
                            </tr>
                          </table>
                          <input type="hidden" name="MM_update" value="frm_admin">
                        </form>
                        <br/>
                        <center> <a href="dashboard.php">بازگشت</a>   </center>
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

mysql_free_result($Recordset2);
?>

