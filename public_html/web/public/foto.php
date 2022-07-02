<?PHP

/**
 * TimThumb script created by Ben Gillbanks, originally created by Tim McDaniels and Darren Hoyt
 * http://code.google.com/p/timthumb/
 * 
 * GNU General Public License, version 2
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * Examples and documentation available on the project homepage
 * http://www.binarymoon.co.uk/projects/timthumb/
 */
 
error_reporting(-1);
ini_set("display_errors", 1);

ini_set('date.timezone', 'Europe/Amsterdam');
setlocale(LC_ALL, "nl-NL");

define ('CACHE_SIZE', 2000);				// number of files to store before clearing cache
define ('CACHE_CLEAR', 20);					// maximum number of files to delete on each cache clear
define ('CACHE_USE', TRUE);					// use the cache files? (mostly for testing)
define ('VERSION', '1.24');					// version number (to force a cache refresh)
define ('DIRECTORY_CACHE', './cache');		// cache directory
define ('MAX_WIDTH', 2000);					// maximum image width
define ('MAX_HEIGHT', 2000);				// maximum image height
define ('ALLOW_EXTERNAL', FALSE);			// allow external website (override security precaution - not advised!)
define ('MEMORY_LIMIT', '150M');			// set PHP memory limit
define ('MAX_FILE_SIZE', 4500000);			// file size limit to prevent possible DOS attacks (roughly 1.5 megabytes)

// external domains that are allowed to be displayed on your website
$allowedSites = array ();

// STOP MODIFYING HERE!
// --------------------

/* Security fix Mafiasource */
// sort out image source
$src = get_request ('src', '');
if ($src == '' || strlen ($src) <= 3 || strpos($src, "://") !== false || stripos(strtoupper((string) $src), "%3A%2F%2F") !== false) {
    display_error ('no image specified');
    $src = null;
}


// clean params before use
$src = isset($src) ? clean_source ($src) : "";

// get mime type of src
$mime_type = $src !== "" ? mime_type ($src) : "";

// used for external websites only
$external_data_string = '';

// generic file handle for reading and writing to files
$fh = '';

// check to see if this image is in the cache already
// if already cached then display the image and die
// But only when dealing with a valid mime_type
if(isset($mime_type)) check_cache ($mime_type);

// cache doesn't exist and then process everything
// check to see if GD function exist
if (!function_exists ('imagecreatetruecolor')) {
    display_error ('GD Library Error: imagecreatetruecolor does not exist - please contact your webhost and ask them to install the GD library');
}

// get standard input properties
$new_width =  (int) abs ((int)get_request ('w', 0));
$new_height = (int) abs ((int)get_request ('h', 0));
$zoom_crop = (int) get_request ('zc', 1);
$quality = 100; //(int) abs (get_request ('q', 100));
$align = "c"; //get_request ('a', 'c');
$filters = ""; //get_request ('f', '');
$sharpen = 0; //(bool) get_request ('s', 0);
$new_width = $new_width >= 0 ? $new_width : 0;
$new_height = $new_height >= 0 ? $new_height : 0;

// set default width and height if neither are set already
if ($new_width == 0 && $new_height == 0) {

	if(file_exists($src) && is_file($src)) {
		list($width, $height, $type, $attr) = getimagesize($src);

		if($width > 400) {
			$ratio = $width/$height;
			$width = 400;
			$height = $width/$ratio;
		}

	    $new_width = $width;
	    $new_height = $height;
	} else {
	    $new_width = 340;
	    $new_height = 90;
	}
}


// ensure size limits can not be abused
$sizes = array();
$new_width = $sizes["height"] = (int) min($new_width, MAX_WIDTH);
$new_height = $sizes["width"] = (int) min($new_height, MAX_HEIGHT);
/* //End security fix Mafiasource */

// set memory limit to be able to have enough space to resize larger images
ini_set ('memory_limit', MEMORY_LIMIT);

if (file_exists ($src)) {
    
    // open the existing image
    $image = open_image ($mime_type, $src);
	
    if ($image === false) {
		showPlaceholder();		
        display_error ('Unable to load image.');
    }

    // Get original width and height
    $width = is_object($image) ? imagesx ($image) : $new_width;
    $height = is_object($image) ? imagesy ($image) : $new_height;
	$origin_x = 0;
	$origin_y = 0;

    // generate new w/h if not provided
    if ($new_width && !$new_height) {
        $new_height = floor ($height * ($new_width / $width));
    } else if ($new_height && !$new_width) {
        $new_width = floor ($width * ($new_height / $height));
    }

	// create a new true color image
	$canvas = imagecreatetruecolor ((int) $new_width, (int) $new_height);
	imagealphablending ($canvas, false);

	// Create a new transparent color for image
	$color = imagecolorallocatealpha ($canvas, 255,255,255,0);

	// Completely fill the background of the new image with allocated color.
	imagefill ($canvas, 0, 0, $color);

	// scale down and add borders
	if ($zoom_crop == 2) {


		if($new_width > $width) {
		
			$origin_y = round(($new_height/2) - ($height/2));
			$origin_x = round(($new_width/2) - ($width/2));

			$new_width = $width;
			$new_height = $height;

		} else {
			$final_height = $height * ($new_width / $width);
	
			if ($final_height > $new_height) {
	
				$origin_x = $new_width / 2;
				$new_width = $width * ($new_height / $height);
				$origin_x = round ($origin_x - ($new_width / 2));
	
			} else {
	
				$origin_y = $new_height / 2;
				$new_height = $final_height;
				$origin_y = round ($origin_y - ($new_height / 2));
	
			}
		}

	}

	// Restore transparency blending
	imagesavealpha ($canvas, true);

	if ($zoom_crop > 0) {

		$src_x = $src_y = 0;
		$src_w = $width;
		$src_h = $height;

		$cmp_x = $width / $new_width;
		$cmp_y = $height / $new_height;

		// calculate x or y coordinate and width or height of source
		if ($cmp_x > $cmp_y) {

			$src_w = round ($width / $cmp_x * $cmp_y);
			$src_x = round (($width - ($width / $cmp_x * $cmp_y)) / 2);

		} else if ($cmp_y > $cmp_x) {

			$src_h = round ($height / $cmp_y * $cmp_x);
			$src_y = round (($height - ($height / $cmp_y * $cmp_x)) / 2);

		}

		// positional cropping!
		switch ($align) {
			case 't':
			case 'tl':
			case 'lt':
			case 'tr':
			case 'rt':
				$src_y = 0;
				break;

			case 'b':
			case 'bl':
			case 'lb':
			case 'br':
			case 'rb':
				$src_y = $height - $src_h;
				break;

			case 'l':
			case 'tl':
			case 'lt':
			case 'bl':
			case 'lb':
				$src_x = 0;
				break;

			case 'r':
			case 'tr':
			case 'rt':
			case 'br':
			case 'rb':
				$src_x = $width - $new_width;
				$src_x = $width - $src_w;
				break;

			default:
				break;
		}

		imagecopyresampled ($canvas, $image, $origin_x, $origin_y, $src_x, $src_y, (int) $new_width, (int) $new_height, $src_w, $src_h);

    } else {

        // copy and resize part of an image with resampling
        imagecopyresampled ($canvas, $image, 0, 0, 0, 0, (int) $new_width, (int) $new_height, $width, $height);

    }
    
    // output image to browser based on mime type
    show_image ($mime_type, $canvas);

    // remove image from memory
    imagedestroy ($canvas);

	// if not in cache then clear some space and generate a new file
	clean_cache ();

	die ();

} else {

    if (strlen ($src)) {
        display_error ('image ' . $src . ' not found');
    } else {
        display_error ('no source specified');
    }

}


/**
 *
 * @global <type> $quality
 * @param <type> $mime_type
 * @param <type> $image_resized
 */
function show_image ($mime_type, $image_resized) {

    global $quality;

    // check to see if we can write to the cache directory
    $cache_file = get_cache_file ($mime_type);

	if (stripos ($mime_type, 'jpeg') > 1) {
		imagejpeg ($image_resized, $cache_file, $quality);
	} else {
		imagepng ($image_resized, $cache_file, floor ($quality * 0.09));
	}

	show_cache_file ($mime_type);

}


/**
 *
 * @param <type> $property
 * @param <type> $default
 * @return <type>
 */
function get_request ($property, $default = 0) {

    if (isset ($_GET[$property])) {
        return $_GET[$property];
    } else {
        return $default;
    }

}


/**
 *
 * @param <type> $mime_type
 * @param <type> $src
 * @return <type>
 */
function open_image ($mime_type, $src) {

	$mime_type = strtolower ((string) $mime_type);

	if (stripos ($mime_type, 'gif') !== false) {
        $image = imagecreatefromgif ($src);
    } elseif (stripos ($mime_type, 'jpeg') !== false) {
        $image = imagecreatefromjpeg ($src);
    } elseif (stripos ($mime_type, 'png') !== false) {
        $image = @imagecreatefrompng ($src);
    }

    return isset($image) ? $image : null;

}

/**
 * clean out old files from the cache
 * you can change the number of files to store and to delete per loop in the defines at the top of the code
 *
 * @return <type>
 */
function clean_cache () {

	// add an escape
	// Reduces the amount of cache clearing to save some processor speed
	if (rand (1, 50) > 10) {
		return true;
	}

	flush ();

    $files = glob (DIRECTORY_CACHE . '/*', GLOB_BRACE);

	if (count ($files) > CACHE_SIZE) {

        $yesterday = time () - (24 * 60 * 60);

        usort ($files, 'filemtime_compare');
        $i = 0;

		foreach ($files as $file) {

			$i ++;

			if ($i >= CACHE_CLEAR) {
				return;
			}

			if (@filemtime ($file) > $yesterday) {
				return;
			}

			if (file_exists ($file)) {
				unlink ($file);
			}

		}

    }

}


/**
 * compare the file time of two files
 *
 * @param <type> $a
 * @param <type> $b
 * @return <type>
 */
function filemtime_compare ($a, $b) {

	$break = explode ('/', $_SERVER['SCRIPT_FILENAME']);
	$filename = $break[count ($break) - 1];
	$filepath = str_replace ($filename, '', $_SERVER['SCRIPT_FILENAME']);

	$file_a = realpath ($filepath . $a);
	$file_b = realpath ($filepath . $b);

    return filemtime ($file_a) - filemtime ($file_b);

}


/**
 * determine the file mime type
 *
 * @param <type> $file
 * @return <type>
 */
function mime_type ($file) {
    if(is_file($file))
    {
    	$file_infos = getimagesize ($file);
    	$mime_type = isset($file_infos['mime']) ? $file_infos['mime'] : "";
    
    	// no mime type
    	if (empty ($mime_type)) {
    		display_error ('no mime type specified');
    	}
    
        // use mime_type to determine mime type
        if (!preg_match ("/jpg|jpeg|gif|png/i", (string)$mime_type)) {
    		display_error ('Invalid src mime type: ' . $mime_type);
        }
    
        return $mime_type;
    }
}


/**
 *
 * @param <type> $mime_type
 */
function check_cache ($mime_type) {

	if (CACHE_USE) {

		if (!show_cache_file ($mime_type)) {
			// make sure cache dir exists
			if (!file_exists (DIRECTORY_CACHE)) {
				// give 777 permissions so that developer can overwrite
				// files created by web server user
				mkdir (DIRECTORY_CACHE);
				chmod (DIRECTORY_CACHE, 0777);
			}
		}

	}

}


/**
 *
 * @param <type> $mime_type
 * @return <type>
 */
function show_cache_file ($mime_type) {

	// use browser cache if available to speed up page load
	if (isset ($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
		if (strtotime ($_SERVER['HTTP_IF_MODIFIED_SINCE']) < strtotime('now')) {
			header ('HTTP/2 304 Not Modified');
			die ();
		}
	}

	$cache_file = get_cache_file ($mime_type);

	if (file_exists ($cache_file)) {

		// change the modified headers
		$gmdate_expires = gmdate ('D, d M Y H:i:s', strtotime ('now +10 days')) . ' GMT';
		$gmdate_modified = gmdate ('D, d M Y H:i:s') . ' GMT';

		// send content headers then display image
		header ('Content-Type: ' . $mime_type);
		header ('Accept-Ranges: bytes');
		header ('Last-Modified: ' . $gmdate_modified);
		header ('Content-Length: ' . filesize ($cache_file));
		header ('Cache-Control: max-age=864000, must-revalidate');
		header ('Expires: ' . $gmdate_expires);

		if (!@readfile ($cache_file)) {
			$content = file_get_contents ($cache_file);
			if ($content != FALSE) {
				echo htmlspecialchars($content);
			} else {
				display_error ('cache file could not be loaded');
			}
		}

		die ();

    }

	return FALSE;

}


/**
 *
 * @staticvar string $cache_file
 * @param <type> $mime_type
 * @return string
 */
function get_cache_file ($mime_type) {

    static $cache_file;
	global $src;

	$file_type = '.png';

	if (stripos ($mime_type, 'jpeg') > 1) {
		$file_type = '.jpg';
    }

    if (!$cache_file) {
		// filemtime is used to make sure updated files get recached
        $cache_file = DIRECTORY_CACHE . '/' . md5 ($_SERVER ['QUERY_STRING'] . VERSION . filemtime ($src)) . $file_type;
    }

    return $cache_file;

}


/**
 *
 * @param <type> $url
 * @return <type>
 */
function validate_url ($url) {
	$pattern = '/^(([\w]+:)?\/\/)?(([\d\w]|%[a-fA-f\d]{2,2})+(:([\d\w]|%[a-fA-f\d]{2,2})+)?@)?([\d\w][-\d\w]{0,253}[\d\w]\.)+[\w]{2,4}(:[\d]+)?(\/([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)*(\?(&amp;?([-+_~.\d\w]|%[a-fA-f\d]{2,2})=?)*)?(#([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)?$/';
	return preg_match ($pattern, $url);
}


/**
 *
 * @global array $allowedSites
 * @param string $src
 * @return string
 */
function check_external ($src) {

	global $allowedSites;

	// work out file details
	$fileDetails = pathinfo ($src);
	$filename = 'external_' . md5 ($src);
	$local_filepath = DIRECTORY_CACHE . '/' . $filename . '.' . array_key_exists("extension", $fileDetails) && isset($fileDetails['extension']) ? strtolower ((string) $fileDetails['extension']) : "";

	// only do this stuff the file doesn't already exist
	if (!file_exists ($local_filepath)) {

		if (stripos ($src, 'http://') !== false || stripos ($src, 'https://') !== false) {

			if (!validate_url ($src)) {
				display_error ('invalid url');
			}

			$url_info = parse_url ($src);

			// convert youtube video urls
			// need to tidy up the code
            /*
			if ($url_info['host'] == 'www.youtube.com' || $url_info['host'] == 'youtube.com') {
				parse_str ($url_info['query']);

				if (isset ($v)) {
					$src = 'http://img.youtube.com/vi/' . $v . '/0.jpg';
					$url_info['host'] = 'img.youtube.com';
				}
			}
            */
			// check allowed sites (if required)
			if (ALLOW_EXTERNAL) {

				$isAllowedSite = true;

			} else {

				$isAllowedSite = false;
				foreach ($allowedSites as $site) {
					if (stripos ($url_info['host'], $site) !== false) {
						$isAllowedSite = true;
					}
				}

			}

			// if allowed
			if ($isAllowedSite) {

				if (function_exists ('curl_init')) {

					global $fh;

					$fh = fopen ($local_filepath, 'w');
					$ch = curl_init ($src);

					curl_setopt ($ch, CURLOPT_TIMEOUT, 5);
					curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0');
					curl_setopt ($ch, CURLOPT_URL, $src);
					curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
					curl_setopt ($ch, CURLOPT_HEADER, 0);
					curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
					curl_setopt ($ch, CURLOPT_FILE, $fh);
					curl_setopt ($ch, CURLOPT_WRITEFUNCTION, 'curl_write');

					// error so die
					if (curl_exec ($ch) === FALSE) {
						display_error ('error reading file ' . $src . ' from remote host: ' . curl_error ($ch));
					}

					curl_close ($ch);
					fclose ($fh);

                } else {

					if (!$img = file_get_contents (basename($src))) {
						display_error ('remote file for ' . $src . ' can not be accessed. It is likely that the file permissions are restricted');
					}

					if (file_put_contents ($local_filepath, $img) == FALSE) {
						display_error ('error writing temporary file');
					}

				}

				if (!file_exists ($local_filepath)) {
					display_error ('local file for ' . $src . ' can not be created');
				}

				$src = $local_filepath;

			} else {

				display_error ('remote host "' . $url_info['host'] . '" not allowed');

			}

		}

    } else {

		$src = $local_filepath;

	}

    return $src;

}


/**
 * callback for curl command to receive external images
 * limit the amount of data downloaded from external servers
 *
 * @global <type> $data_string
 * @param <type> $handle
 * @param <type> $data
 * @return <type>
 */
function curl_write ($handle, $data) {

	global $external_data_string, $fh;

	fwrite ($fh, $data);
	$external_data_string .= $data;

	if (strlen ($external_data_string) > MAX_FILE_SIZE) {
		return 0;
	} else {
		return strlen ($data);
	}

}


/**
 * tidy up the image source url
 *
 * @param <type> $src
 * @return string
 */
function clean_source ($src) {
    if(isset($src))
    {
    	$host = str_replace ('www.', '', htmlspecialchars($_SERVER['HTTP_HOST']));
    	$regex = preg_quote("/^(http(s|):\/\/)(www\.|)" . $host . "\//i", '#');
    
    	$src = ltrim( preg_replace ('#' . $regex . '#', '', $src), '/');
    	$src = strip_tags ($src);
    	$src = str_replace (' ', '%20', $src);
        $src = check_external ($src);
    
        // remove slash from start of string
        if (strpos ($src, '/') === 0) {
            $src = substr ($src, -(strlen ($src) - 1));
        }
    
        // don't allow users the ability to use '../'
        // in order to gain access to files below document root
        $src = preg_replace ("/\.\.+\//", "", $src);
    
        // get path to image on file system
        $src = get_document_root ($src) . '/' . $src;
    
    	if (!is_file ($src)) {
    		display_error ('source is not a valid file');
    	}
        else
        {
        	if (filesize ($src) > MAX_FILE_SIZE) {
        		display_error ('source file is too big (filesize > MAX_FILE_SIZE)');
        	}
        }
        return realpath ($src);
    }
}


/**
 *
 * @param <type> $src
 * @return string
 */
function get_document_root ($src) {

    // check for unix servers
    if (file_exists ($_SERVER['DOCUMENT_ROOT'] . '/' . $src)) {
        return $_SERVER['DOCUMENT_ROOT'];
    }

    // check from script filename (to get all directories to timthumb location)
    $parts = array_diff (explode ('/', $_SERVER['SCRIPT_FILENAME']), explode ('/', $_SERVER['DOCUMENT_ROOT']));

	if (!substr ($_SERVER['SCRIPT_FILENAME'], 0, 1) == '/') {
		$path = $_SERVER['DOCUMENT_ROOT'];
	}
    
    if(!isset($path))
        $path = "";
    
    foreach ($parts as $part) {
        $path .= '/' . $part;
        if (file_exists ($path . '/' . $src)) {
            return $path;
        }
    }

    // special check for microsoft servers
    if (!isset ($_SERVER['DOCUMENT_ROOT'])) {
        $path = str_replace ("/", "\\", $_SERVER['ORIG_PATH_INFO']);
        $path = str_replace ($path, '', $_SERVER['SCRIPT_FILENAME']);

        if (file_exists ($path . '/' . $src)) {
            return $path;
        }
    }

    display_error ('file not found');

}


/**
 * generic error message
 * @global <type> $sizes
 * @param <type> $errorString
 */
function display_error ($errorString = '') {
    
        global $sizes;
        if(!is_array($sizes) || !array_key_exists("width", $sizes) || !array_key_exists("height", $sizes))
        {
            $sizes["width"] = 340;
            $sizes["height"] = 90;
        }

		$my_img = imagecreate( $sizes["width"], $sizes["height"]);
		$background = imagecolorallocate( $my_img, 200, 200, 200);
		$text_colour = imagecolorallocate( $my_img, 0, 0, 0 );
		
		$center = ($sizes["width"]/2)-42;
		$centerv = ($sizes["height"]/2)-10;
		
		imagestring($my_img, 4, $center, $centerv, "PLACEHOLDER",$text_colour );
		header( "Content-type: image/png" );
		imagepng( $my_img );
		imagecolordeallocate( $my_img, $text_colour );
		imagecolordeallocate( $my_img, $text_colour );
		imagecolordeallocate( $my_img, $background );
		imagedestroy( $my_img );
}
