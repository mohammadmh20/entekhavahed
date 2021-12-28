<?php require_once('../Connections/register.php'); ?>
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

mysql_select_db($database_register, $register);
$query_rec_news = "SELECT id, title FROM news ORDER BY id DESC";
$rec_news = mysql_query($query_rec_news, $register) or die(mysql_error());
$row_rec_news = mysql_fetch_assoc($rec_news);
$totalRows_rec_news = mysql_num_rows($rec_news);
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['login'])) {
  $loginUsername=$_POST['login'];
  $password=$_POST['password'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "dashboard.php";
  $MM_redirectLoginFailed = "failed_login.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_register, $register);
  
  $LoginRS__query=sprintf("SELECT `user`, password FROM login WHERE `user`=%s AND password=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $register) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
mysql_select_db($database_register, $register);
$query_rec_login = "SELECT * FROM login";
$rec_login = mysql_query($query_rec_login, $register) or die(mysql_error());
$row_rec_login = mysql_fetch_assoc($rec_login);
$totalRows_rec_login = mysql_num_rows($rec_login);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html dir="rtl">
<!-- DW6 -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>صحفه اصلی</title>
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
        <form id="search-code" name="search-code" method="post" action="Search.php">
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
      <div class="text">خبر</div></div>
        <div class="middle">
        <div class="text">
<table border="0" cellspacing="0" cellpadding="0">
  <?php do { ?>
    <tr>
      <td scope="row"><img src="../img/ballblue.gif"> <a href="../Templates/archive-news.php?id=<?php echo $row_rec_news['id']; ?>"><?php echo $row_rec_news['title']; ?></a></td>
    </tr>
    <?php } while ($row_rec_news = mysql_fetch_assoc($rec_news)); ?>
</table>

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
          <div class="bigtext">ورود به بخش مدیریت</div>
        </div>
          <div class="bigmiddle" align="center">
                <div class="text">
                  <div align="right">
                      <div class="padd1">
                        <form action="<?php echo $loginFormAction; ?>" method="POST" name="frm_login" id="frm_login">
                        <br/>
                          <table width="396" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                              <td width="89" scope="row"><label for="login">&nbsp;&nbsp;نام کاریری :</label></td>
                              <td width="301">
                              <input type="text" name="login" id="login"></td>
                            </tr>
                            <tr>
                              <td scope="row"><br/><label for="password">&nbsp;&nbsp;رمز عبور :</label></td>
                              <td><br/>
                              <input type="password" name="password" id="password"></td>
                            </tr>
                            <tr>
                              <td colspan="2" scope="row"><div align="center"><br/>
                                <input name="btn_submit" type="submit" value="&#1608;&#1585;&#1608;&#1583;">
                              </div></td>
                            </tr>
                          </table>
                        </form> 
                        <br/>
                        
                      </div>
                  </div>
                </div>
          </div>
          
<div class="bigbottom"></div>
<br/>
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
mysql_free_result($rec_news);

mysql_free_result($Recordset1);

mysql_free_result($Recordset2);
?>
