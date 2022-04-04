<?
#############################################################################
# _alto_login.php  06/23/2004
#
#############################################################################

require_once '../globals/config.inc.php';

$_gn_infL = new _ALTO_Loginfo;
$_gn_ssnL = new _ALTO_Session(__ALTO_SESSION_SUPER__);

$_gn_ssnL->_alto_session_cleanup();
header("location: ".$rootdir."index.php");
?>
