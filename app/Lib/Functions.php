<?php
class Functions {

	const URL_FORMAT = '/^(https?):\/\/(([a-z0-9$_\.\+!\*\'\(\),;\?&=-]|%[0-9a-f]{2})+(:([a-z0-9$_\.\+!\*\'\(\),;\?&=-]|%[0-9a-f]{2})+)?@)?(?#)((([a-z0-9]\.|[a-z0-9][a-z0-9-]*[a-z0-9]\.)*[a-z][a-z0-9-]*[a-z0-9]|((\d|[1-9]\d|1\d{2}|2[0-4][0-9]|25[0-5])\.){3}(\d|[1-9]\d|1\d{2}|2[0-4][0-9]|25[0-5]))(:\d+)?)(((\/+([a-z0-9$_\.\+!\*\'\(\),;:@&=-]|%[0-9a-f]{2})*)*(\?([a-z0-9$_\.\+!\*\'\(\),;:@&=-]|%[0-9a-f]{2})*)?)?)?(#([a-z0-9$_\.\+!\*\'\(\),;:@&=-]|%[0-9a-f]{2})*)?$/i';

	/** RESPONSES **/
	public static function buildSuccessResponse() {
		$response = array(
			'success' => true
		);
		return $response;
	}

	/** ARRAYS **/
	public static function setNullsToArrayBlanks($array) {
		foreach ($array as &$element) {
			if (trim($element) == '') {
				$element = null;
			}
		}
		return $array;
	}

	public static function createArrayFromSubLevel($array,$subLevel) {
		$subLevelArray = array();
		foreach ($array as $element) {
			array_push($subLevelArray,$element[$subLevel]);
		}
		return $subLevelArray;
	}

	public static function removeElementByValueFromArray($array,$elementValue) {
		if (($key = array_search($elementValue, $array)) !== false) {
			unset($array[$key]);
		}
		return $array;
	}

	public static function setZeroIfNull($element) {
		if ($element==null) {
			$element = 0;
		}
		return $element;
	}

	public static function searchInArray($array,$query,$field) {
		$results = array();
		foreach ($array as $element) {
			if(strpos(strtolower($element[$field]), strtolower($query)) !== FALSE) {
				array_push($results,$element);
			}
		}

		return $results;
	}

	public static function extractArrayIdsFromArrayObjects($arrayObjects,$index) {
		$arrayIds = array();
		foreach ($arrayObjects as $object) {
			array_push($arrayIds,$object[$index]['id']);
		}
		return $arrayIds;
	}

	public static function removeClassFromQueryResults($results,$className) {
		$resultsParsed = array();
		foreach ($results as $result) {
			$parsedResult = $result[$className];
			$parsedResult['class'] = $className;
			$resultsParsed[] = $parsedResult;
		}
		return $resultsParsed;
	}

	/** STRINGS **/
	public static function explode($delimiter, $string) {
		if (trim($string)=='') {
			return array();
		} elseif (!strpos($string,$delimiter)) {
			return array($string);
		} else {
			return explode($delimiter, $string);
		}
	}

	public static function containsSubstring($string,$substring) {
		$pos = strpos($string,$substring);

		if($pos === false) {
			return false;
		}
		else {
			return true;
		}
	}

	public static function shortString($string,$length) {
		if (strlen($string)>$length) {
			return substr($string,0,$length-3) . '...';
		}
		return $string;
	}

	public static function removeQuotes($string) {
		$string = str_replace("'","",$string);
		$string = str_replace('"','',$string);
		return $string;
	}

	public static function escapeSingleQuotes($string) {
		$string = str_replace("'","\'",$string);
		return $string;
	}

	public static function generateSlug($string) {
		$string = preg_replace("`\[.*\]`U","",$string);
		$string = preg_replace('`&(amp;)?#?[a-z0-9]+;`i','-',$string);
		$string = htmlentities($string, ENT_COMPAT, 'utf-8');
		$string = preg_replace( "`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);`i","\\1", $string );
		$string = preg_replace( array("`[^a-z0-9]`i","`[-]+`") , "-", $string);

		$string = str_replace("-amp-","--amp--",$string);
		$string = str_replace("-amp-","-",$string);
		$string = preg_replace('/[-]+/', '-', $string);
		$string = strtolower(trim($string, '-'));

		return $string;
	}

	/** BOOLS **/
	public static function parseBoolToString($bool) {
		if ($bool) {
			return 'true';
		}
		return 'false';
	}

	/** DATETIMES **/
	public static function getNowDatetimeISO8601() {
		$time = time();
		$ISODateTime = date(DATE_ISO8601,$time);
		return $ISODateTime;
	}

	public static function ago($date) {

		$str = strtotime(date($date));
		$today = strtotime(date('Y-m-d H:i:s'));

		// It returns the time difference in Seconds...
		$time_differnce = $today - $str;

		// To Calculate the time difference in Years...
		$years = 60 * 60 * 24 * 365;

		// To Calculate the time difference in Months...
		$months = 60 * 60 * 24 * 30;

		// To Calculate the time difference in Days...
		$days = 60 * 60 * 24;

		// To Calculate the time difference in Hours...
		$hours = 60 * 60;

		// To Calculate the time difference in Minutes...
		$minutes = 60;

		if (intval($time_differnce / $years) > 1) {
			$datediff = __('%s years ago',intval($time_differnce / $years));
		} else if (intval($time_differnce / $years) > 0) {
			$datediff = __('%s year ago',intval($time_differnce / $years));
		} else if (intval($time_differnce / $months) > 1) {
			$datediff = __('%s months ago',intval($time_differnce / $months));
		} else if (intval(($time_differnce / $months)) > 0) {
			$datediff = __('%s month ago',intval($time_differnce / $months));
		} else if (intval(($time_differnce / $days)) > 1) {
			$datediff = __('%s days ago',intval($time_differnce / $days));
		} else if (intval(($time_differnce / $days)) > 0) {
			$datediff = __('%s day ago',intval($time_differnce / $days));
		} else if (intval(($time_differnce / $hours)) > 1) {
			$datediff = __('%s hours ago',intval($time_differnce / $hours));
		} else if (intval(($time_differnce / $hours)) > 0) {
			$datediff = __('%s hour ago',intval($time_differnce / $hours));
		} else if (intval(($time_differnce / $minutes)) > 1) {
			$datediff = __('%s minutes ago',intval($time_differnce / $minutes));
		} else if (intval(($time_differnce / $minutes)) > 0) {
			$datediff = __('%s minute ago',intval($time_differnce / $minutes));
		} else if (intval(($time_differnce)) > 1) {
			$datediff = __('%s seconds ago',intval($time_differnce));
		} else {
			$datediff = ' ' . __('few seconds ago');
		}

		return $datediff;
	}

	/** CURLS **/
	public static function getUrl($url) {

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($curl);
		curl_close($curl);

		return $data;

	}

	public static function fileGetContents($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_REFERER, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}

	public static function getImageSizeFast($url) {
		$headers = array(
				"Range: bytes=0-32768"
		);

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($curl);
		curl_close($curl);

		try {
			if (APP_ENV=="development") {
				xdebug_disable();
				Configure::write('debug', 0);
			}
			error_reporting(E_ERROR | E_PARSE);
			$im = imagecreatefromstring($data);
		} catch (Exception $e) {
			// do nothing
			return false;
		}

		$params['width'] = imagesx($im);
		$params['height'] = imagesy($im);
		return $params;
	}

	public static function urlExists($url) {

		$h = get_headers($url);
		$status = array();
		preg_match('/HTTP\/.* ([0-9]+) .*/', $h[0] , $status);
		return ($status[1] == 200);
	}

	public static function isValidUrl($url) {
		if (self::startsWith(strtolower($url), 'http://localhost')) {
			return true;
		}
		return preg_match(self::URL_FORMAT, $url);
	}

	public static function startsWith($haystack, $needle) {
		return $needle === "" || strpos($haystack, $needle) === 0;
	}

	/** FILES **/
	public static function removeFilesFromDirOlderThanTimeInMinutes($dir,$timeInMinutes) {

		$objects = scandir($dir);
		foreach ($objects as $object) {
			$object = $dir . $object;
			if (filemtime($object) < time() - ($timeInMinutes*60)) {
				if (!is_dir($object))
					unlink($object);
			}
		}

	}

	/** IPS **/
	public static function getRealIP() {
		$ipaddress = '';
		if(isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
			$ipaddress =  $_SERVER['HTTP_CF_CONNECTING_IP'];
		} else if (isset($_SERVER['HTTP_X_REAL_IP'])) {
			$ipaddress = $_SERVER['HTTP_X_REAL_IP'];
		}
		else if (isset($_SERVER['HTTP_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_X_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if(isset($_SERVER['REMOTE_ADDR']))
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'UNKNOWN';

		return $ipaddress;
	}

}

?>