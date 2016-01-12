<?php
	/**
	 * Checks if needle is string is a prefix of the other string.
	 * @param string &$haystack 
	 * @param string $needle 
	 * @return string
	 */
	function starts_with($haystack, $needle) {
		if (strlen($haystack) < strlen($needle))
			return false;
		return substr_compare($haystack, $needle, 0, strlen($needle)) === 0;
	}

	/**
	 * Checks if needle is string is a suffix of the other string.
	 * @param string &$haystack 
	 * @param string $needle 
	 * @return string
	 */
	function ends_with($haystack, $needle) {
		$hlen = strlen($haystack);
		$nlen = strlen($needle);
		if ($hlen < $nlen) return false;
		return substr_compare($haystack, $needle, $hlen - $nlen) === 0;
	}

	/**
	 * Saves or reads JSON file in the /json directory.
	 * @param string $filename the JSON file name that will be searched in the /json directory. will automatically append the .json extension.
	 * @param string &$data data to put to the JSON file. if not specified, this function will return the contents of the json file.
	 * @return string the encoded JSON string if data is not null
		 * @return type the JSON object if the data is null
	 */
	function json($filename, $data = null) {
		$prefix = $_SERVER['DOCUMENT_ROOT'];
		if ($prefix[strlen($prefix) - 1] != '/')
			$prefix .= '/';
		$filename = $prefix."json/$filename.json";
		if (!is_null($data)) {
			$encoded = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
			file_put_contents($filename, $encoded);
			return $encoded;
		}
		else if (file_exists($filename))
			return json_decode(file_get_contents($filename), true);
		else
			return array();
	}

	/**
	 * Forcefully redirects current page to the designated url using javascript.
	 * @param string $link
	 * 		The URL of the page to be redirected to.
	 * @return void
	 */
	function redirect($link) {
		if ($link != '' && strpos($link, 'http') === false)
			while (!file_exists($link))
				$link = '../'.$link;
		die ('<script> window.location.href = "'.$link.'"</script>');
	}

	/**
	 * References to a source code.
	 * @param string $link 
	 * @return void
	 * Possible extensions: *.(php|css|js).
	 * Automatically prepends the address with the extension (e.g. "file.php" => "php/file.php").
	 * Iteratively searches for the nearest uncle in the file directory.
	 */
	function source($link) {
		$ext = pathinfo($link, PATHINFO_EXTENSION);
		if (defined('MIN_JS') && MIN_JS && $ext == 'js' && strpos($link, '.min') === false)
			$link = str_replace('.js', '.min.js', $link);
		if (defined('MIN_CSS') && MIN_CSS && $ext == 'css' && strpos($link, '.min') === false)
			$link = str_replace('.css', '.min.css', $link);
		if (strrpos($link, 'http', -strlen($link)) === false) {
			$link = "$ext/$link";
			for ($limit = 100, $prefix = ''; $limit > 0; --$limit, $prefix = "../$prefix") {
				if (file_exists($prefix.$link)) {
					$link = $prefix.$link;
					break;
				}
			}
			if ($limit == 0) $link = '';
		}
		if ($ext === 'css') { ?>
			<link rel='stylesheet' type='text/css' href='<?php echo $link; ?>'>
		<?php } else if ($ext === 'js') { ?>
			<script src='<?php echo $link; ?>'></script>
		<?php } else if ($ext === 'php') {
			require_once $link;
		}
	}

	/**
	 * Convenient method for array key-value acquisition.
	 * @param array &$array input array
	 * @param type $key
	 * @return type
	 */
	function get(&$array, $key) {
		return isset($array[$key]) ? $array[$key] : NULL;
	}

	/**
	 * Convenient method for normalizing HTML attributes. replaces ' ' with '-' and removes all single quotes.
	 * @param string $text text you want to normalize 
	 * @return string
	 */
	function propertize($text) {
		return str_replace(array(' ', "'"), array('-', ''), strtolower($text));
	}

	/**
	 * Wraps and replaces line breaks with HTML paragraph tags.
	 * @param type $text text you want to transform into an html paragraph
	 * @return type
	 */
	function paragraph($text) {
		if ($text === null || $text === '') return '';
		return '<p>'.str_replace('\n', '</p><p>', $text).'</p>';
	}
?>