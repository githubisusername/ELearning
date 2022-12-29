<?php
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");
include('../dbConnection.php');
session_start();
require_once("./lib/config_paytm.php");
require_once("./lib/encdec_paytm.php");
$paytmChecksum = "";
$paramList = array();
$isValidChecksum = "FALSE";
$paramList = $_POST;
$paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : "";
$isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum);
if($isValidChecksum == "TRUE") {
	if ($_POST["STATUS"] == "TXN_SUCCESS") {
		echo "<b>Transaction status is success</b>" . "<br/>";
		if(isset($_POST['ORDERID']) && isset($_POST['TXNAMOUNT'])){
			$order_id = $_POST['ORDERID'];
			$stu_email = $_SESSION['stuLogEmail'];
			$course_id = $_SESSION['course_id'];
			$status = $_POST['STATUS'];
			$respmsg = $_POST['RESPMSG'];
			$amount = $_POST['TXNAMOUNT'];
			$date = $_POST['TXNDATE'];
			$sql = "INSERT INTO courseorder(order_id, stu_email, course_id, status, respmsg, amount, order_date) VALUES ('$order_id', '$stu_email', '$course_id', '$status', '$respmsg', '$amount', '$date')";
				if($conn->query($sql) == TRUE){
					echo "Redirecting to My Profile....";
					echo "<script> setTimeout(() => {
						window.location.href = '../Student/myCourse.php';
				}, 1500); </script>";
				};
		}
			
	}
	else {
		echo "<b>Transaction status is failure</b>" . "<br/>";
	}

	if (isset($_POST) && count($_POST)>0 )
	{ 		
	}	
}
else {
	echo "<b>Checksum mismatched.</b>";
}

?>