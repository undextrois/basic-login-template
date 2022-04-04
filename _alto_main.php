<?
#############################################################################
# _alto_login.php  06/23/2004
#
#############################################################################

require_once 'globals/config.inc.php';
//require 'globals/config.inc.php';

$_gn_infL = new _ALTO_Loginfo;
$_gn_ssnL = new _ALTO_Session(__ALTO_SESSION_SUPER__);

if ($_gn_ssnL->_alto_session_auth() == false) {
   $_gn_ssnL->_alto_session_cleanup();
   header("location: index.php");
exit;
}
?>
