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
if(isset($_POST['txtsearch']) && $_POST['txtsearch'] != ''){
	$pid = $_POST['txtsearch'];
	mysql_select_db($database_register, $register);
$sql_search = "SELECT * FROM setup WHERE codep LIKE '$pid'";
$rec_search = mysql_query($sql_search, $register) or die(mysql_error());
$row_rec_search = mysql_fetch_assoc($rec_search);
$totalRows_rec_search = mysql_num_rows($rec_search);
	}
	if((isset($_POST['txt_name']) && $_POST['txt_name'] != '') &&((isset($_POST['txt_family']) && $_POST['txt_family'] != '')) && ((isset($_POST['txt_idcard']) && $_POST['txt_idcard'] != ''))){
	$pname = $_POST['txt_name'];
	$pfamily = $_POST['txt_family'];
	$psh = $_POST['txt_idcard'];
		mysql_select_db($database_register, $register);
	$sql_search = "SELECT * FROM setup WHERE name LIKE '$pname' AND family LIKE '$pfamily' AND 			shenasnameh LIKE '$psh'";
	$rec_search = mysql_query($sql_search, $register) or die(mysql_error());
	$row_rec_search = mysql_fetch_assoc($rec_search);
	$totalRows_rec_search = mysql_num_rows($rec_search);
}


mysql_select_db($database_register, $register);
$query_rec_setup1 = "SELECT MAX(code) FROM setup";
$rec_setup1 = mysql_query($query_rec_setup1, $register) or die(mysql_error());
$row_rec_setup1 = mysql_fetch_assoc($rec_setup1);
$totalRows_rec_setup1 = mysql_num_rows($rec_setup1);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}


mysql_select_db($database_register, $register);
$query_rec_news = "SELECT id, title FROM news ORDER BY id DESC";
$rec_news = mysql_query($query_rec_news, $register) or die(mysql_error());
$row_rec_news = mysql_fetch_assoc($rec_news);
$totalRows_rec_news = mysql_num_rows($rec_news);

mysql_select_db($database_register, $register);
$query_rec_setup = "SELECT * FROM setup";
$rec_setup = mysql_query($query_rec_setup, $register) or die(mysql_error());
$row_rec_setup = mysql_fetch_assoc($rec_setup);
$totalRows_rec_setup = mysql_num_rows($rec_setup);

$maxRows_Recordset1 = 5;
$pageNum_Recordset1 = 0;
if (isset($_GET['pageNum_Recordset1'])) {
  $pageNum_Recordset1 = $_GET['pageNum_Recordset1'];
}
$startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;

mysql_select_db($database_register, $register);
$query_Recordset1 = "SELECT name, family FROM setup ORDER BY code DESC";
$query_limit_Recordset1 = sprintf("%s LIMIT %d, %d", $query_Recordset1, $startRow_Recordset1, $maxRows_Recordset1);
$Recordset1 = mysql_query($query_limit_Recordset1, $register) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);

if (isset($_GET['totalRows_Recordset1'])) {
  $totalRows_Recordset1 = $_GET['totalRows_Recordset1'];
} else {
  $all_Recordset1 = mysql_query($query_Recordset1);
  $totalRows_Recordset1 = mysql_num_rows($all_Recordset1);
}
$totalPages_Recordset1 = ceil($totalRows_Recordset1/$maxRows_Recordset1)-1;

$txt_total_setup = $totalRows_rec_setup;
mysql_select_db($database_register, $register);
$query_rec_login = "SELECT * FROM login";
$rec_login = mysql_query($query_rec_login, $register) or die(mysql_error());
$row_rec_login = mysql_fetch_assoc($rec_login);
$totalRows_rec_login = mysql_num_rows($rec_login);
?>

<head>
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
body {
	background-repeat: no-repeat;
}
.style1 {color: #FF0000}
-->
</style>

</head>

<body>
<center>
<div class="tblmain">
<table width="788" height="484" border="0" align="center" cellpadding="0" cellspacing="0">
  
  <tr>
    <td colspan="2" valign="top" scope="row">

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
    <td width="216" rowspan="2" valign="top">
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
        افراد آنلاین : <?php include('user_online.php');?><?php echo $row_rec_setup['aks']; ?><br/>
        ثبت نام شده : <?php echo $totalRows_rec_setup ?> نفر<br/>
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
                <td scope="row"><img src="../img/user_set.png">  <?php echo $row_Recordset1['name']; ?> <?php echo $row_Recordset1['family']; ?></td>

        </tr>

		
                </table>
                          <?php }else{?>
          
                <center> کسی ثبت نام نشده است</center>
               
                <?php } ?>

            <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?></div>
    </div>
    <div class="bottom"></div>
    </div>
    <div class="bottom"></div>
    </div>    </td>
  </tr>
  <?php if($totalRows_rec_search != 0) { ?>
  <tr>
  
    <td width="574" valign="top" scope="row"><div align="center" class="c">
       <div class="menu">
    <div class="bigtop">
      <div class="bigtext">مشخصات ثبت نام شده</div>
    </div>
        <div class="bigmiddle">
        <div class="text"><div align="right"><strong>&nbsp;&nbsp;مشخصات فردی  </strong><br/>
        </div>
        <div align="right">
          <form action="" method="POST" name="frm_register" id="frm_register">
            <table width="516" border="0" cellpadding="0" cellspacing="0">
            <tr>
                 <td align="left" colspan="4"><div align="right">&nbsp;&nbsp;&nbsp;<img src="../Templates/<?php echo $row_rec_search['aks']; ?>"  title="<?php echo $row_rec_search['name']; ?> <?php echo $row_rec_search['family']; ?>" width="100" height="100"></div><br/></td>
                 </tr>
              <tr>
                 <td width="128" align="right" scope="row"><label for="name">&nbsp;&nbsp;نام :</label></td>
                <th width="142" align="right">
                  <?php echo $row_rec_search['name']; ?></th>
                  <td width="109" align="right" scope="row"><label for="family">&nbsp;&nbsp;نام خانوادگی :</label></td>
                <th width="142" align="right">
                  <?php echo $row_rec_search['family']; ?></th>
              </tr>
                            
              <tr><br/>
                 <td scope="row" align="right"><br/><label for="shenasnameh">&nbsp;&nbsp;شماره شناسنامه :</label></td>
                <th align="right"><br/>
                  <?php echo $row_rec_search['shenasnameh']; ?></th>
                                  <td scope="row" align="right"><br/><label for="tavalod">&nbsp;&nbsp;تاریخ تولد :</label></td>
                <th align="right"><br/>
                  <?php echo $row_rec_search['tavalod']; ?></th>
              </tr>
<tr>
                                  <td scope="row" align="right"><br/><label for="mahaltavalod">&nbsp;&nbsp;محل تولد :</label></td>
                <th align="right"><br/>
                  <?php echo $row_rec_search['mahaltavalod']; ?></th>
                  <td scope="row" align="right"><br/><label for="reshteh">&nbsp;&nbsp;رشته تحصیلی :</label></td>
                <th><div align="right"><br/><?php echo $row_rec_search['reshteh']; ?></div></th>
              </tr>
                            
              <tr>
                   <td scope="row" align="right"><br/><label for="moadel">&nbsp;&nbsp;معدل :</label></td>
                <th align="right"><br/>
                  <?php echo $row_rec_search['moadel']; ?></th>
                              <td scope="row" align="right"><br/><label for="mobile">&nbsp;&nbsp;تلفن همراه :</label></td>
                <th align="right"><br/>
                  <?php echo $row_rec_search['mobile']; ?></th>
              </tr>
  
            </table>
            <center><div style="border:1px solid #f1f1f1; width:530px; margin-top:5px;"></div></center>
            <b>&nbsp;&nbsp;مشخصات پدر خانواده</b><br/>
            <table width="516" border="0" cellpadding="0" cellspacing="0" >
                          
              <tr>
                 <td width="128" align="right" scope="row"><br/><label for="f_name">&nbsp;&nbsp;نام :</label></td>
                <th width="147" align="right"><br/>
                  <?php echo $row_rec_search['f_name']; ?></th>
                  <td width="99" align="right" scope="row"><br/><label for="sen">&nbsp;&nbsp;سن :</label></td>
                <th width="142" align="right"><br/>
                  <?php echo $row_rec_search['sen']; ?> سال</th>
              </tr>
                           
                 <td scope="row" align="right"><br/><label for="tahsilat">&nbsp;&nbsp;تحصیلات :</label></td>
                <th><div align="right"><br/><?php echo $row_rec_search['tahsilat']; ?></div></th>
                                  <td scope="row" align="right"><br/><label for="f_shoghl">&nbsp;&nbsp;شغل :</label></td>
                <th align="right"><br/>
                  <?php echo $row_rec_search['shoghl']; ?></th>
              </tr>
                            
<tr>
                                  <td scope="row" align="right"><br/><label for="f_mobile">&nbsp;&nbsp;تلفن همراه :</label></td>
                <th align="right"><br/>
                  <?php echo $row_rec_search['f_mobile']; ?></th>
                  <td scope="row" align="right"><br/><label for="f_mahalkar">&nbsp;&nbsp;محل کار :</label></td>
                <th align="right"><br/>
                  <?php echo $row_rec_search['f_mahalkar']; ?></th>
              </tr>
  
            </table>
            <center><div style="border:1px solid #f1f1f1; width:530px; margin-top:5px;"></div></center>
            <b>&nbsp;&nbsp;وضعیت خانواده</b><br/>
            <table width="516" border="0" cellpadding="0" cellspacing="0">
              <tr>
                 <td width="128" align="right" scope="row"><br/><label for="noemaskan">&nbsp;&nbsp;نوع مسکن :</label></td>
                 <th width="144"><div align="right"><br/><?php echo $row_rec_search['noemaskan']; ?></div></th>
                  <td width="97" align="right" scope="row"><br/><label for="vaziat">&nbsp;&nbsp;وضعیت خانواده :</label></td>
                <th width="140"><div align="right"><br/><?php echo $row_rec_search['viziat']; ?></div></th>
              </tr>
                           
              <tr>
                 <td scope="row" align="right"><br/><label for="farzand">&nbsp;&nbsp;تعداد فرزند :</label></td>
                <th align="right"><br/>
                  <?php echo $row_rec_search['farzand']; ?> نفر</th>
                                  <td scope="row" align="right"><br/><label for="tel">&nbsp;&nbsp;تلفن منزل :</label></td>
                <th align="right"><br/>
                  <?php echo $row_rec_search['tel']; ?></th>
              </tr>
                            
<tr>
                                  <td scope="row" align="right"><br/><label for="address">&nbsp;&nbsp;&nbsp;آدرس :</label></td>
                
                 <th align="right"><br/>
                  <?php echo $row_rec_search['address']; ?><br/></th>
              </tr>
              
                  </div>        
            </table>

            <br/>
          </form>
                            </div>
</div>
    </div>
    <div class="bigbottom"></div>
    </div>

    </div>
          
        </td>
  </tr>


          <?php }else{ ?>
          <td width="574" valign="top" scope="row"><div align="center" class="c">
       <div class="menu">
    <div class="bigtop">
      <div class="bigtext">ناموفقیت در جستجو</div>
    </div>
        <div class="bigmiddle">
        <div class="text"><br/>مشخصات شما وجود ندارد یا احتمالا از سیستم حذف شده اید
        <br/>
        <br/>
        <a href="advance-search.php">برگشت به صفحه جستجو</a>        </div>
        </div>

             <div class="bigbottom"></div>
    </div> 
            </div>
        
            </table>

               
            <?php } ?>


                     
    <tr>
    <td colspan="3">
    <center><div style="border:1px solid #f1f1f1; width:788px; margin-top:20px;"></div></center>
    <div class="footer">
    Copy right &copy; 2011 . All right reserved<br />
	تمامی حقوق برای احمد نعمتی می باشد<br />
    </div>    </td>
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

mysql_free_result($rec_setup);

mysql_free_result($Recordset1);

mysql_free_result($rec_setup1);
?>
