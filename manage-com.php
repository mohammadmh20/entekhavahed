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
mysql_select_db($database_register, $register);
$query_rec_login = "SELECT * FROM login";
$rec_login = mysql_query($query_rec_login, $register) or die(mysql_error());
$row_rec_login = mysql_fetch_assoc($rec_login);
$totalRows_rec_login = mysql_num_rows($rec_login);


mysql_select_db($database_register, $register);
$query_Recordset1 = "SELECT * FROM setup";
$Recordset1 = mysql_query($query_Recordset1, $register) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);


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


$txt_total_setup = $totalRows_Recordset2;

mysql_select_db($database_register, $register);
$query_rec_login = "SELECT * FROM login";
$rec_login = mysql_query($query_rec_login, $register) or die(mysql_error());
$row_rec_login = mysql_fetch_assoc($rec_login);
$totalRows_rec_login = mysql_num_rows($rec_login);

mysql_select_db($database_register, $register);
$query_rec_com = "SELECT * FROM `comment`";
$rec_com = mysql_query($query_rec_com, $register) or die(mysql_error());
$row_rec_com = mysql_fetch_assoc($rec_com);
$totalRows_rec_com = mysql_num_rows($rec_com);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html dir="rtl">
<!-- DW6 -->
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
          <div class="bigtext">مدیریت نظرات سایت</div>
        </div>
          <div class="bigmiddle" align="center">
                <div class="text">
                  <div align="right">
                      <div class="padd1"> <br/>
                      <form action="" method="post" name="frm_manage-com" id="frm_manage-com">
                          <table width="450" height="65" border="1" align="center" cellpadding="0" cellspacing="0">
                            <tr>
                              <th width="15" align="center" scope="row">ردیف</th>
                              <th width="238" align="center">نظرات</th>
                              <th width="28" align="center">حذف</th>
                            </tr>
                            <?php $counter=1;?>
                            <?php do { ?>
                            <tr>
                              <td height="15" scope="row" align="center">&nbsp;<?php echo $counter++; ?></td>
                              <td>
                              <center><?php echo $row_rec_com['title_news']; ?></center><br/>
                              &nbsp;<b>نام :</b> <?php echo $row_rec_com['name_c']; ?>&nbsp;&nbsp;<b>تاریخ :</b> <?php echo $row_rec_com['date_c']; ?>&nbsp;&nbsp;<b>ساعت :</b> <?php echo $row_rec_com['time_c']; ?><br/><br/>
                              &nbsp;<b>نظر :</b> <?php echo $row_rec_com['comment_c']; ?><br/><br/>
                              &nbsp;<b>ایمیل :</b> <?php echo $row_rec_com['email_c']; ?>
                              <br/>
                              <br/>
                              </td>
                              <td align="center"><a href="delete-com.php?id=<?php echo $row_rec_com['id_c']; ?>"><img src="../img/delete.png" height="20" width="20" /></a></td>
                             
                            </tr>
                              <?php } while ($row_rec_com = mysql_fetch_assoc($rec_com)); ?>
                          </table>
                                                    <br/>
                          <br/>
                          <center>
                            <a href="dashboard.php">برگشت</a>
                          </center>
                        </form>                       <br/>
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

mysql_free_result($rec_com);

mysql_free_result($Recordset2);
?>

