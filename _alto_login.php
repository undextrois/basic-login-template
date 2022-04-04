<?
#############################################################################
# _alto_login.php  06/23/2004
#
#############################################################################

require_once 'globals/config.inc.php';

#############################################################################
#
# ukpa_session
#
# ssn_idPK int
# ssn_user char 16
# ssn_ipaddr int
# ssn_key char 32
# ssn_login timestamp
# ssn_time int
#
#############################################################################

function _gn_header() {
  $now = gmdate('D, d M Y H:i:s') . ' GMT';
  header('Expires: ' . $now);
  header('Last-Modified: ' . $now);
  header('Cache-Control: no-store, no-cache, must-revalidate, pre-check=0, post-check=0, max-age=0');
  header('Pragma: no-cache');
}

$_gn_infL = new _ALTO_Loginfo;
$_gn_ssnL = new _ALTO_Session(__ALTO_SESSION_SUPER__);
$_error_out = false;

if ((isset($_SERVER['REQUEST_METHOD'])) && ($_SERVER['REQUEST_METHOD']=='POST')) {
  # get referer, if necesary
  # just to check calling script

  $_gn_accept = true;

  if (isset($_POST['username'])) $_gn_infL->username = trim($_POST['username']);
  else $_gn_infL->username = '';
  if (isset($_POST['password'])) $_gn_infL->password = trim($_POST['password']);
  else $_gn_infL->password = '';

  if (!$_gn_infL->_alto_is_user($_gn_infL->username)) { $_gn_accept = false; }
  if (!$_gn_infL->_alto_is_passwd($_gn_infL->password)) { $_gn_accept = false; }

  $_DB->dbconnect();

  if (($_gn_rs=mysql_query('SELECT * FROM loginfo WHERE username="'.mysql_escape_string($_gn_infL->username).'"'))==false) {
     $_DB->closedb();
     die ('query error');
  }
  if (mysql_num_rows($_gn_rs)) {
     $_gn_rw=mysql_fetch_object($_gn_rs);
     if ($_gn_infL->password != decrypt($_gn_rw->password,'ukpa'))
        $_gn_accept = false;
     mysql_free_result($_gn_rs);
  }
  else {
    $_gn_accept = false;
  }
  /*
  if (($_gn_rs=mysql_query('SELECT u.admin FROM userlist AS u, loginfo AS l WHERE l.username="'.mysql_escape_string($_gn_infL->username).'" AND u.useridPK = l.useridFK'))==false)
  {
     $_DB->closedb();
     die ('query error');
  }
  $m_type = __ALTO_SESSION_USER__;
  if (mysql_num_rows($_gn_rs)) {
     $_gn_rw=mysql_fetch_object($_gn_rs);
     if ($_gn_rw->admin == 'Y') $m_type=__ALTO_SESSION_ADMIN__;
     mysql_free_result($_gn_rs);
  }
  */

  $_DB->closedb();

  $m_type = __ALTO_SESSION_USER__;

  if ($_gn_accept) {
     # set-up session here
     $_gn_ssnL->_alto_session_create($_gn_infL->username,$m_type);
     header("location: indexinside.php");
  exit;
  }
  else
  {
    $_error_out = true;
  }
}

$_gn_tpl = new Template(".","keep");
$_gn_tpl->set_file(array("_gn_tpl"=>"_login/html/am_login.htm"));
$_gn_tpl->set_var("phpscript",$_SERVER['PHP_SELF']);
//if (!$_gn_accept) {
//     $_gn_tpl->set_var("something"," onload=\"javascript:alert('Password or Username Error');\"");
//} else {
//     $_gn_tpl->set_var("phpscript","");
//}
if (!$_error_out)
   $_gn_tpl->set_var("error_out","");
else
   $_gn_tpl->set_var("error_out","<font color=\"#800000\"><strong>WRONG USERNAME/PASSWORD</strong><br>(hint: Check if \"<b>Caps Lock</b>\" is on)</font>");
$_gn_tpl->parse("_gn_tpl",array("_gn_tpl"));
$_gn_tpl->finish("_gn_tpl");
$_gn_tpl->p("_gn_tpl");
?>
