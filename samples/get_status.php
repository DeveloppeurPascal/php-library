<?php
/* Example of use for EnvOrderStatus class  
 * Get the status of a passed order
 */
$folder = '../';
require_once('../utils/header.php');
require_once('../utils/config.php');
require_once('../env/WebService.php');
require_once('../env/OrderStatus.php');
 
// Prepare and execute the request
$env = 'test';
$locale = 'en-US'; // you can change this to 'fr-FR' or 'es-ES' for instance
$lib = new EnvOrderStatus($credentials[$env]);
$lib->setEnv($env);
$lib->setLocale($locale);
$lib->getOrderInformations("1306261940MONR01PHFR");

if(!$lib->curl_error && !$lib->resp_error)
{
	// If you want to recieve order's status changes or documents, check for "url_push" param in samples/make_order.php
	echo '<pre>'.print_r($lib->order_info,true).'</pre>';
}

handle_errors($lib);
require_once('../utils/footer.php');
?>
 
