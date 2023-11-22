<?php
/**
 * 
 *    Copyright (C) 2002-2024 NekoYuzu (MlgmXyysd) All Rights Reserved.
 *    Copyright (C) 2013-2024 MeowCat Studio All Rights Reserved.
 *    Copyright (C) 2020-2024 Meow Mobile All Rights Reserved.
 * 
 */

/**
 * 
 * Xiaomi HyperOS BootLoader Bypass
 * 
 * https://github.com/MlgmXyysd/Xiaomi-BootLoader-Bypass
 * 
 * Bypass Xiaomi HyperOS community restrictions of BootLodaer unlock account bind.
 * 
 * Environment requirement:
 *   - PHP 8.0+
 *   - OpenSSL Extension
 *   - ADB
 * 
 * @author MlgmXyysd
 * @version 1.0
 * 
 * All copyright in the software is not allowed to be deleted
 * or changed without permission.
 * 
 */

/***********************
 *    Configs Start    *
 ***********************/

// Global flag
// If you are running a Global ROM (Non-China Mainland), set it to true
$useGlobal = false;

/*********************
 *    Configs End    *
 *********************/


/***************************************
 *               WARNING               *
 *    Do NOT modify the codes below    *
 *               WARNING               *
 ***************************************/

// Include php-adb library
// https://github.com/MlgmXyysd/php-adb

require_once __DIR__ . DIRECTORY_SEPARATOR . "adb.php";

use MeowMobile\ADB;

/*************************
 *    Constants Start    *
 *************************/

global $api;
global $sign_key;
global $data_pass;
global $data_iv;

$api = $useGlobal ? "https://unlock.update.intl.miui.com/v1/" : "https://unlock.update.miui.com/v1/";
$sign_key = "10f29ff413c89c8de02349cb3eb9a5f510f29ff413c89c8de02349cb3eb9a5f5";
$data_pass = "20nr1aobv2xi8ax4";
$data_iv = "0102030405060708";

$version = "1.0";

/***********************
 *    Constants End    *
 ***********************/

/*************************
 *    Functions Start    *
 *************************/

/**
 * Formatted Log
 * @param  $a  ADB    required  ADB instance
 * @return     array            List of connected adb devices
 * @author NekoYuzu (MlgmXyysd)
 * @date   2022/03/24 14:01:03
 */

function parseDeviceList(ADB $a): array
{
    $s = $a -> refreshDeviceList();
    $t = array();
    foreach ($s as $d) {
        if ($d["status"] === $a::CONNECT_TYPE_DEVICE) {
            $t[] = array($d["serial"], $d["transport"]);
        }
    }
    return $t;
}

/**
 * Formatted Log
 * @param  $m  string  optional  Message
 * @param  $c  string  optional  Color
 * @param  $p  string  optional  Indicator
 * @param  $t  string  optional  Type (Level)
 * @author NekoYuzu (MlgmXyysd)
 * @date   2022/03/24 14:50:01
 */

function logf(string $m = "", string $c = "", string $p = "-", string $t = "I"): void
{
	switch (strtoupper($c)) {
		case "G":
			$c = "\033[32m";
			break;
		case "R":
			$c = "\033[31m";
			break;
		case "Y":
			$c = "\033[33m";
			break;
		default:
			$c = "";
	}
	switch (strtoupper($t)) {
		case "W":
			$t = "WARN";
			break;
		case "E":
			$t = "ERROR";
			break;
		case "I":
		default:
			$t = "INFO";
	}
	print(date("[Y-m-d] [H:i:s]") . " [" . $t . "] " . $p . " " . $c . $m . "\033[0m" . PHP_EOL);
}

/**
 * Curl HTTP wrapper function
 * @param  $url      string  required  Target url
 * @param  $method   string  required  Request method
 * @param  $fields   array   optional  Request body
 * @param  $header   array   optional  Request header
 * @param  $useForm  bool    optional  Treat request body as urlencoded form
 * @return           array             Curl response
 * @author NekoYuzu (MlgmXyysd)
 * @date   2023/11/20 23:50:39
 */

function http(string $url, string $method, array $fields = array(), array $header = array(), bool $useForm = false): array
{
	if ($useForm) {
		$fields = http_build_query($fields);
	}
    $curl = curl_init();
    curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSL_VERIFYHOST => false,
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_CONNECTTIMEOUT => 2,
		CURLOPT_TIMEOUT => 6,
		CURLOPT_CUSTOMREQUEST => $method,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_POST => $method == "POST",
		CURLOPT_POSTFIELDS => $fields,
		CURLOPT_HTTPHEADER => $header
    ));

    $response = curl_exec($curl);
    $info = curl_getinfo($curl);
    $info["errno"] = curl_errno($curl);
    $info["error"] = curl_error($curl);
    $info["request"] = json_encode($fields);
    $info["response"] = $response;
    curl_close($curl);
    return $info;
}

/**
 * HTTP POST wrapper
 * @param  $_api     string  required  Target endpoint
 * @param  $data     array   optional  Request body
 * @param  $header   array   optional  Request header
 * @param  $useForm  bool    optional  Treat request body as urlencoded form
 * @return           array             Curl response
 * @return           false             Response code is not HTTP 200 OK
 * @author NekoYuzu (MlgmXyysd)
 * @date   2023/11/20 23:55:41
 */

function postApi(string $_api, array $data = array(), array $header = array(), bool $useForm = false): array|false
{
    $response = http($GLOBALS["api"] . $_api, "POST", $data, $header, $useForm);
    if ($response["http_code"] != 200) {
        return false;
    }
    return json_decode($response["response"], true);
}

/**
 * Sign data using HMAC SHA-1
 * @param  $data  string  required  Data to sign
 * @return        string            Signed hash
 * @author NekoYuzu (MlgmXyysd)
 * @date   2023/11/21 00:20:56
 */

function signData(string $data): string
{
    return strtolower(bin2hex(hash_hmac("sha1", "POST\n/v1/unlock/applyBind\ndata=" . $data . "&sid=miui_sec_android", $GLOBALS["sign_key"], true)));
}

/**
 * Decrypt data using AES/CBC/PKCS5Padding
 * @param  $data  string  required  Data to decrypt
 * @return        string            Decrypted data
 * @return        false             Failed to decrypt
 * @author NekoYuzu (MlgmXyysd)
 * @date   2023/11/21 00:15:30
 */

function decryptData(string $data): string|false
{
	return openssl_decrypt(base64_decode($data), "AES-128-CBC", $GLOBALS["data_pass"], OPENSSL_RAW_DATA, $GLOBALS["data_iv"]);
}

/***********************
 *    Functions End    *
 ***********************/

/**********************
 *    Banner Start    *
 **********************/

logf("************************************", "g");
logf("* Xiaomi HyperOS BootLoader Bypass *", "g");
logf("* By NekoYuzu          Version " . $version . " *", "g");
logf("************************************", "g");
logf("GitHub: https://github.com/MlgmXyysd");
logf("XDA: https://xdaforums.com/m/mlgmxyysd.8430637");
logf("X (Twitter): https://x.com/realMlgmXyysd");
logf("PayPal: https://paypal.me/MlgmXyysd");
logf("My Blog: https://www.neko.ink/");
logf("************************************", "g");

/********************
 *    Banner End    *
 ********************/

/********************
 *    Main Logic    *
 ********************/

logf("Starting ADB server...");

$adb = new ADB(__DIR__ . DIRECTORY_SEPARATOR . "libraries");

$devices = parseDeviceList($adb);
$devices_count = count($devices);

while ($devices_count != 1) {
	if ($devices_count == 0) {
		logf("Waiting for device connection...");
	} else {
		logf("Only one device is allowed to connect, disconnect others to continue. Current number of devices: " . $devices_count);
	}
	sleep(1);
	$devices = parseDeviceList($adb);
	$devices_count = count($devices);
}

$device = $devices[0];
$id = $adb -> getDeviceId($device[1], true);
logf("Processing device " . $device[0] . "(" . $device[1] . ")...");

$adb -> clearLogcat($id);
$adb -> runAdb($id . "shell svc data enable");

logf("Finding BootLoader unlock bind request...");

$focus = $adb -> getCurrentActivity();
if ($focus[0] != "com.android.settings") {
	if ($focus[0] != "NotificationShade") {
		$adb -> runAdb($id . "shell am start -a android.settings.APPLICATION_DEVELOPMENT_SETTINGS");
	}
} else {
	if ($focus[1] != "com.android.settings.bootloader.BootloaderStatusActivity") {
		$adb -> runAdb($id . "shell am start -a android.settings.APPLICATION_DEVELOPMENT_SETTINGS");
	}
}
logf("Now you can bind account in the developer options.", "y", "*");

$args = $headers = null;

$process = proc_open($adb -> bin . " " . $id . "logcat *:S CloudDeviceStatus:V", array(
	1 => ["pipe", "w"]
), $pipes);

if (is_resource($process)) {
    while (!feof($pipes[1])) {
        $output = fgets($pipes[1]);
		
        if (str_contains($output, "CloudDeviceStatus: args:")) {
            if (preg_match("/args:(.*)/", $output, $matches)) {
                $args = trim($matches[1]);
            }
			$adb -> runAdb($id . "shell svc data disable");
        }
		
        if (str_contains($output, "CloudDeviceStatus: headers:")) {
            if (preg_match("/headers:(.*)/", $output, $matches)) {
                $headers = trim($matches[1]);
            }
			logf("Account bind request found! Let's block it.");
            break;
        }
    }
	
    fclose($pipes[1]);
}

logf("Refactoring parameters...");

$data = json_decode(decryptData($args), true);

$data["rom_version"] = str_replace("V816", "V14", $data["rom_version"]);

$data = json_encode($data);
$sign = signData($data);

$headers = decryptData($headers);
$cookies = null;
if (preg_match("/Cookie=\[(.*)\]/", $headers, $matches)) {
	$cookies = trim($matches[1]);
}

logf("Sending POST request...");
$res = postApi("unlock/applyBind", array(
	"data" => $data,
	"sid" => "miui_sec_android",
	"sign" => $sign
), array(
	"Cookie: " . $cookies,
	"Content-Type: application/x-www-form-urlencoded"
), true);

$adb -> runAdb($id . "shell svc data enable");

if (!$res) {
	logf("Fail to send request, check your internet connection.", "r", "!");
	exit();
}

switch ($res["code"]) {
	case 0:
		logf("Target account: " . $res["data"]["userId"], "g");
		logf("Account bound successfully, wait time can be viewed in the unlock tool.", "g");
		break;
	case 401:
		logf("Account credentials have expired, re-login to your account in your phone. (401)", "y");
		break;
	case 20086:
		logf("Device credentials expired. (20086)", "y");
		break;
	case 30001:
		logf("Binding failed, this device has been forced to verify the account qualification by Xiaomi. (30001)", "y");
		break;
	case 86015:
		logf("Fail to bind account, invalid device signature. (86015)", "y");
		break;
	default:
		logf($res["descEN"] . " (" . $res["code"] . ")", "y");
}
