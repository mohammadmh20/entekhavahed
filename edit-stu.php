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
if(isset($_GET['id']) && $_GET['id'] != ''){
	$pid = $_GET['id'];
	}
	
	
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

$txt_total_setup = $totalRows_Recordset1;

mysql_select_db($database_register, $register);
$query_rec_login = "SELECT * FROM login";
$rec_login = mysql_query($query_rec_login, $register) or die(mysql_error());
$row_rec_login = mysql_fetch_assoc($rec_login);
$totalRows_rec_login = mysql_num_rows($rec_login);

mysql_select_db($database_register, $register);
$query_rec_search = "SELECT * FROM setup WHERE code = $pid;";
$rec_search = mysql_query($query_rec_search, $register) or die(mysql_error());
$row_rec_search = mysql_fetch_assoc($rec_search);
$totalRows_rec_search = mysql_num_rows($rec_search);

mysql_select_db($database_register, $register);
$query_rec_news = "SELECT id, title FROM news ORDER BY id DESC";
$rec_news = mysql_query($query_rec_news, $register) or die(mysql_error());
$row_rec_news = mysql_fetch_assoc($rec_news);
$totalRows_rec_news = mysql_num_rows($rec_news);


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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "frm_register")) {
  $updateSQL = sprintf("UPDATE setup SET name=%s, family=%s, shenasnameh=%s, moadel=%s, tavalod=%s, mahaltavalod=%s, reshteh=%s, mobile=%s, f_name=%s, sen=%s, tahsilat=%s, shoghl=%s, f_mobile=%s, f_mahalkar=%s, noemaskan=%s, viziat=%s, farzand=%s, address=%s, tel=%s WHERE code=%s",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['family'], "text"),
                       GetSQLValueString($_POST['shenasnameh'], "text"),
                       GetSQLValueString($_POST['moadel'], "int"),
                       GetSQLValueString($_POST['tavalod'], "text"),
                       GetSQLValueString($_POST['mahaltavalod'], "text"),
                       GetSQLValueString($_POST['reshteh'], "text"),
                       GetSQLValueString($_POST['mobile'], "text"),
                       GetSQLValueString($_POST['f_name'], "text"),
                       GetSQLValueString($_POST['sen'], "int"),
                       GetSQLValueString($_POST['tahsilat'], "text"),
                       GetSQLValueString($_POST['f_shoghl'], "text"),
                       GetSQLValueString($_POST['f_mobile'], "text"),
                       GetSQLValueString($_POST['f_mahalkar'], "text"),
                       GetSQLValueString($_POST['noemaskan'], "text"),
                       GetSQLValueString($_POST['vaziat'], "text"),
                       GetSQLValueString($_POST['farzand'], "int"),
                       GetSQLValueString($_POST['address'], "text"),
                       GetSQLValueString($_POST['tel'], "text"),
                       GetSQLValueString($_POST['name1'], "text"));

  mysql_select_db($database_register, $register);
  $Result1 = mysql_query($updateSQL, $register) or die(mysql_error());

  $updateGoTo = "manage-stu.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
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
    	<li><a href="../Templates/index.php">???????? ????????</a></li>
    	<li><a href="../Templatesregister.php">?????? ??????</a></li>
    	<li><a href="../Templatesadvance-search.php">??????????</a></li>
    	<li style="border-left:1px solid #FFF"><a href="../Templates/que-ans.php">???????? ?? ????????</a></li>
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
        <div class="text">??????????</div></div>
        <div class="middle">
        <div class="text">
        <form id="search-code" name="search-code" method="post" action="search.php">
          <table width="64" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td width="64" valign="middle"><p>???? ???????????? :
                </p>
                <p>
                  <input type="text" name="txtsearch" id="txtsearch" size="20">
                  </p></td>
            </tr>
            <tr>
              <td><div align="center">
                <input type="submit" name="btnsearch" id="btnsearch" value="??????????">
              </div>
                <div align="center">
                  <a href="../Templates/advance-search.php">???????????? ??????????????                  </a></div></td>
            </tr>
          </table>
          </form>
        </div>
    </div>
	<div class="bottom"></div>
    </div>
	<div class="menu">
      <div class="top">
      <div class="text">???????? ?? ??????????</div></div>
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
        ???? ???? ?????? : <?php echo getRealIpAddr() ?><br/>
        ?????????? ???????????? : <?php include('user_online.php');?><br/>
        ?????? ?????? ?????? : <?php echo $totalRows_Recordset1 ?> ??????<br/>
    </div>
    </div>
    <div class="bottom"></div>
    </div>
    <div class="menu">
    <div class="top">
      <div class="text">????????</div></div>
        <div class="middle">
        <div class="text">
        <?php date_default_timezone_set($row_rec_login['time_site']); ?>

        ???????? :  <?php echo date('H:i:s'); ?> <br/>
        ?????????? : <?php echo date('l F d, Y');?> <br/>
    </div>
    </div>
    <div class="bottom"></div>
    </div>
    <div class="menu">
      <div class="top">
      <div class="text">5 ?????? ?????? ?????? ?????? ??????</div></div>
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
          
                <center> ?????? ?????? ?????? ???????? ??????</center>
               
                <?php } ?>

            <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?></div>        
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
          <div class="bigtext">???????????? ?????? ??????</div>
        </div>
          <div class="bigmiddle" align="center">
                <div class="text">
                  <div align="right">
                      <div class="padd1"> <br/>
                      <div class="text"><div align="right"><strong>&nbsp;&nbsp;???????????? ????????  </strong><br/>
        </div>
        <div align="right">
          <form action="<?php echo $editFormAction; ?>" method="POST" name="frm_register" id="frm_register">
            <table width="516" border="0" cellpadding="0" cellspacing="0">
            <tr>
                 <td align="left" colspan="4"><div align="right">&nbsp;&nbsp;&nbsp;<img src="../Templates/<?php echo $row_rec_search['aks']; ?>"  title="<?php echo $row_rec_search['name']; ?> <?php echo $row_rec_search['family']; ?>" width="100" height="100"></div><br/></td>
                 </tr>
              <tr>
                 <td width="128" align="right" scope="row"><label for="name">&nbsp;&nbsp;?????? :</label></td>
                <th width="142" align="right">
                <input name="name1" type="hidden" value="<?php echo $row_rec_search['code']; ?>">
                <input name="name" type="text" value="<?php echo $row_rec_search['name']; ?>">
                  </th>
                  <td width="109" align="right" scope="row"><label for="family">&nbsp;&nbsp;?????? ???????????????? :</label></td>
                <th width="142" align="right">
                <input name="family" type="text" value="<?php echo $row_rec_search['family']; ?>">
                  </th>
              </tr>
                            
              <tr><br/>
                 <td scope="row" align="right"><br/><label for="shenasnameh">&nbsp;&nbsp;?????????? ???????????????? :</label></td>
                <th align="right"><br/>
                <input name="shenasnameh" type="text" value="<?php echo $row_rec_search['shenasnameh']; ?>"></th>
                                  <td scope="row" align="right"><br/><label for="tavalod">&nbsp;&nbsp;?????????? ???????? :</label></td>
                <th align="right"><br/>
                <input name="tavalod" type="text" value="<?php echo $row_rec_search['tavalod']; ?>"></th>
              </tr>
<tr>
                                  <td scope="row" align="right"><br/><label for="mahaltavalod">&nbsp;&nbsp;?????? ???????? :</label></td>
                <th align="right"><br/>
                <input name="mahaltavalod" type="text" value="<?php echo $row_rec_search['mahaltavalod']; ?>"></th>
                  <td scope="row" align="right"><br/><label for="reshteh">&nbsp;&nbsp;???????? ???????????? :</label></td>
                <th><div align="right"><br/>
				<label>
                  <select name="reshteh" id="reshteh">
                  <?php if($row_rec_search['reshteh'] == "????????????????"){ ?>
                    <option  value="????????????????" selected>????????????????</option>
                    <?php }else{ ?>
                    <option  value="????????????????">????????????????</option>
                    <?php } ?>
                    <?php if($row_rec_search['reshteh'] == "????????????????"){ ?>
                    <option value="????????????????" selected>????????????????</option>
                    <?php }else{ ?>
                    <option value="????????????????">????????????????</option>
                    <?php } ?>
                    <?php if($row_rec_search['reshteh'] == "?????? ????????"){ ?>
                    <option value="?????? ????????" selected>?????? ????????</option>
                    <?php }else{ ?>
                    <option value="?????? ????????">?????? ????????</option>
                    <?php } ?>
                    <?php if($row_rec_search['reshteh'] == "??????????????????"){ ?>
                    <option value="??????????????????" selected>??????????????????</option>
                    <?php }else{ ?>
                    <option value="??????????????????">??????????????????</option>
                    <?php } ?>
                    <?php if($row_rec_search['reshteh'] == "????????????"){ ?>
                    <option value="????????????" selected>????????????</option>
                    <?php }else{ ?>
                    <option value="????????????">????????????</option>
                    <?php } ?>
                    <?php if($row_rec_search['reshteh'] == "??????????????"){ ?>
                    <option value="??????????????" selected>??????????????</option>
                    <?php }else{ ?>
                    <option value="??????????????">??????????????</option>
                    <?php } ?>
                    <?php if($row_rec_search['reshteh'] == "??????????"){ ?>
                    <option value="??????????" selected>??????????</option>
                    <?php }else{ ?>
                    <option value="??????????">??????????</option>
                    <?php } ?>
                  </select>
                  </label>
				</div></th>
              </tr>
                            
              <tr>
                   <td scope="row" align="right"><br/><label for="moadel">&nbsp;&nbsp;???????? :</label></td>
                <th align="right"><br/>
                <input name="moadel" type="text" value="<?php echo $row_rec_search['moadel']; ?>">
</th>
                              <td scope="row" align="right"><br/><label for="mobile">&nbsp;&nbsp;???????? ?????????? :</label></td>
                <th align="right"><br/>
                <input name="mobile" type="text" value="<?php echo $row_rec_search['mobile']; ?>"></th>
              </tr>
  
            </table>
            <center><div style="border:1px solid #f1f1f1; width:530px; margin-top:5px;"></div></center>
            <b>&nbsp;&nbsp;???????????? ?????? ??????????????</b><br/>
            <table width="516" border="0" cellpadding="0" cellspacing="0" >
                          
              <tr>
                 <td width="128" align="right" scope="row"><br/><label for="f_name">&nbsp;&nbsp;?????? :</label></td>
                <th width="147" align="right"><br/>
                <input name="f_name" type="text" value="<?php echo $row_rec_search['f_name']; ?>"></th>
                  <td width="99" align="right" scope="row"><br/><label for="sen">&nbsp;&nbsp;???? :</label></td>
                <th width="142" align="right"><br/>
                <input name="sen" type="text" value="<?php echo $row_rec_search['sen']; ?>" size="3">
 ??????</th>
              </tr>
                           
                 <td scope="row" align="right"><br/><label for="tahsilat">&nbsp;&nbsp;?????????????? :</label></td>
                <th><div align="right"><br/>
                <select name="tahsilat" id="tahsilat">
                <?php if($row_rec_search['tahsilat'] == "???????? ??????????????"){ ?>
				    <option value="???????? ??????????????" selected>???????? ??????????????</option>
                    <?php }else{ ?>
                    <option value="???????? ??????????????" selected>???????? ??????????????</option>
                    <?php } ?>
                    <?php if($row_rec_search['tahsilat'] == "??????????"){ ?>
                    <option value="??????????" selected>??????????</option>
                    <?php }else{ ?>
                    <option value="??????????">??????????</option>
                    <?php } ?>
                    <?php if($row_rec_search['tahsilat'] == "?????? ??????????"){ ?>
                    <option value="?????? ??????????" selected>?????? ??????????</option>
                    <?php }else{ ?>
                    <option value="?????? ??????????">?????? ??????????</option>
                    <?php } ?>
                    <?php if($row_rec_search['tahsilat'] == "????????????"){ ?>
                    <option value="????????????" selected>????????????</option>
                    <?php }else{ ?>
                    <option value="????????????">????????????</option>
                    <?php } ?>
                    <?php if($row_rec_search['tahsilat'] == "?????? ????????????"){ ?>
                    <option value="?????? ????????????" selected>?????? ????????????</option>
                    <?php }else{ ?>
                    <option value="?????? ????????????">?????? ????????????</option>
                    <?php } ?>
                    <?php if($row_rec_search['tahsilat'] == "??????????"){ ?>
                    <option value="??????????" selected>??????????</option>
                    <?php }else{ ?>
                    <option value="??????????">??????????</option>
                    <?php } ?>
                    </select>
                    </div></th>
                                  <td scope="row" align="right"><br/><label for="f_shoghl">&nbsp;&nbsp;?????? :</label></td>
                <th align="right"><br/>
                <input name="f_shoghl" type="text" value="<?php echo $row_rec_search['shoghl']; ?>"></th>
              </tr>
                            
<tr>
                                  <td scope="row" align="right"><br/><label for="f_mobile">&nbsp;&nbsp;???????? ?????????? :</label></td>
                <th align="right"><br/>
                <input name="f_mobile" type="text" value="<?php echo $row_rec_search['f_mobile']; ?>"></th>
                  <td scope="row" align="right"><br/><label for="f_mahalkar">&nbsp;&nbsp;?????? ?????? :</label></td>
                <th align="right"><br/>
               <textarea name="f_mahalkar" id="f_mahalkar" cols="20" rows="3"><?php echo $row_rec_search['f_mahalkar']; ?></textarea></th>
              </tr>
  
            </table>
            <center><div style="border:1px solid #f1f1f1; width:530px; margin-top:5px;"></div></center>
            <b>&nbsp;&nbsp;?????????? ??????????????</b><br/>
            <table width="516" border="0" cellpadding="0" cellspacing="0">
              <tr>
                 <td width="128" align="right" scope="row"><br/><label for="noemaskan">&nbsp;&nbsp;?????? ???????? :</label></td>
                 <th width="144"><div align="right"><br/>
                 <select name="noemaskan" id="noemaskan">
                 <?php if($row_rec_search['noemaskan'] == "??????????????"){ ?>
                    <option value="??????????????" selected>??????????????</option>
                    <?php }else{ ?>
                    <option value="??????????????">??????????????</option>
                    <?php } ?>
                    <?php if($row_rec_search['noemaskan'] == "??????????"){ ?>
                    <option value="??????????" selected>??????????</option>
                    <?php }else{ ?>
                    <option value="??????????">??????????</option>
                    <?php } ?>
                    <?php if($row_rec_search['noemaskan'] == "??????"){ ?>
                    <option value="??????" selected>??????</option>
                    <?php }else{ ?>
                    <option value="??????">??????</option>
                    <?php } ?>
                    <?php if($row_rec_search['noemaskan'] == "????????"){ ?>
                    <option value="????????" selected>????????</option>
                    <?php }else{ ?>
                    <option value="??????">??????</option>
                    <?php } ?>
                    </select></div></th>
                  <td width="97" align="right" scope="row"><br/><label for="vaziat">&nbsp;&nbsp;?????????? ?????????????? :</label></td>
                <th width="140"><div align="right"><br/>
				<select name="vaziat" id="vaziat">
                <?php if($row_rec_search['viziat'] == "?????? ????????"){ ?>
                    <option value="?????? ????????" selected>?????? ????????</option>
                    <?php }else{ ?>
                    <option value="?????? ????????">?????? ????????</option>
                     <?php } ?>
                    <?php if($row_rec_search['viziat'] == "????????"){ ?>
                    <option value="????????" selected>????????</option> 
                    <?php }else{ ?>
                    <option value="????????">????????</option> 
                     <?php } ?>
                    <?php if($row_rec_search['viziat'] == "????????????"){ ?>
                    <option value="????????????" selected>????????????</option>
                    <?php }else{ ?>
                    <option value="????????????">????????????</option>
                     <?php } ?>
                    <?php if($row_rec_search['viziat'] == "??????????"){ ?>
                    <option value="??????????" selected>??????????</option>
                    <?php }else{ ?>
                    <option value="??????????">??????????</option>
                     <?php } ?>
                  </select></div></th>
              </tr>
                           
              <tr>
                 <td scope="row" align="right"><br/><label for="farzand">&nbsp;&nbsp;?????????? ?????????? :</label></td>
                <th align="right"><br/>
                <input name="farzand" type="text" value="<?php echo $row_rec_search['farzand']; ?> " size="2">
                ??????</th>
                                  <td scope="row" align="right"><br/><label for="tel">&nbsp;&nbsp;???????? ???????? :</label></td>
                <th align="right"><br/>
                <input name="tel" type="text" value="<?php echo $row_rec_search['tel']; ?>"></th>
              </tr>
                            
<tr>
                                  <td scope="row" align="right"><br/><label for="address">&nbsp;&nbsp;&nbsp;???????? :</label></td>
                
                 <th align="right"><br/>
                 <textarea name="address" id="address" cols="20" rows="3"><?php echo $row_rec_search['address']; ?></textarea><br/></th>
              </tr>
              <tr>
            		<td colspan="2" align="center"><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="btn_setup" type="submit" value="???????????? ????????????">  </td>
                    </tr>
                  </div>        
            </table>

            <br/>
            <input type="hidden" name="MM_update" value="frm_register">
          </form>
          <br/>
          <center>
            <a href="manage-stu.php">??????????</a>
          </center>
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
	?????????? ???????? ???????? ???????? ?????????? ???? ????????<br /><br />
    </center>    </td>
  </tr>
</table>
</div>
</center>
</body>
</html>
