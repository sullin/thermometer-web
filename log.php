<?php

include "lib.php";

$headers = apache_request_headers();
if (!isset($headers['Authorization']) || $headers['Authorization'] != $GLOBALS['auth_token']) {
	http_response_code(403);
	echo "Unauthorized";
} else {
	if (store_last($_POST) && store_data($_POST)) {
		echo "OK";
	} else {
		http_response_code(500);
		echo "Failed";
	}
}

?>
