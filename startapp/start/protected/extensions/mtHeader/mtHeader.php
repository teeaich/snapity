<?php
/**
 * mtHeader is a bunch of static functions to set headers for common mime types
 *
 * The parsed mime.types file is in standard unix format. So if missing
 * a mime type, simply overwrite the delivered mime.types file - no
 * additional modifications needed.
 *
 * Mime types are cached. So don't forget to clean the cache after
 * prividing a new mime.types file!
 *
 * Here you can find fresh versions:
 * http://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types
 *
 * Inculded scopes:
 * - nocache
 * - length    [size in bytes]
 * - download  [file name and optional file size in bytes]
 * - public
 * - private
 *
 * Setting a plain text header:
 * mtHeader::txt();
 *
 * Setting this png to private:
 * mtHeader::png('private');
 *
 * Telling the browser, to cache it 1 hour
 * mtHeader::png(array('expires'=>3600));
 *
 * Telling the browser not to cache
 * mtHeader::png('nocache');
 *
 * Setting individual headers
 * mtHeader::png(array('GreetingsTo' => 'my mom'));
 *
 * Force download with given file name "setup.exe" 1kb size
 * mtHeader::exe(array('download' => array('setup.exe', 1024)));
 *
 *
 * PHP >= 5.3
 *
 * @category  Helper
 * @package   HTTP
 * @author    Florian Fackler <florian.fackler@mintao.com>
 * @copyright 2010 mintao GmbH & Co. KG
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link      http://mintao.com
 */
class mtHeader
{
    private static $_mimeTypes;
    private static $_header;
    private static $_mimeTypesUrl;

    /**
     * Generic function to output the right mime header for a given
     * file extension
     *
     * @param string $ext       The file exntion (e.g. exe, js, xml)
     * @param array  $arguments The arguments for scopes or self defined headers
     *
     * @return void Sets headers directly
     */
    public static function __callStatic($ext=null, $arguments=array())
    {
        if (count($arguments)>0) {
            // Only one argument possible  (string or array)
            $arguments = array_shift($arguments);

            // Prepare for loop
            if (!is_array($arguments)) {
                $arguments = array($arguments);
            }

            // Parse all arguments
            foreach ($arguments as $scope=>$param) {
                if (is_numeric($scope)) {
                    $scope = $param;
                    $param = null;
                }

                $method = 'scope' . ucfirst(strtolower($scope));

                if (method_exists(__CLASS__, $method)) {
                    if (!is_array($param)) {
                        if(is_null($param)) {
                            forward_static_call(array(__CLASS__, $method));
                        } else {
                            forward_static_call(
                                array(__CLASS__, $method),
                                $param
                            );
                        }
                    } else {
                        forward_static_call_array(
                            array(__CLASS__, $method),
                            $param
                        );
                    }
                } else if (is_scalar($param)) {
                    self::addHeader(array($scope=>$param));
                }
            }
        }
        self::addHeader(self::_getMime(strtolower($ext)));
        self::_output();
    }


    /**
     * SCOPE: Tells the browser not to cache the following content
     *
     * @return void
     */
    public static function scopeNocache()
    {
        self::scopeExpires(-604800);
        self::addHeader(
            array(
                'Pragma' => 'no-cache',
                'Cache-Control' => 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0',
            )
        );
    }


    /**
     * SCOPE: Tells the browser not to request this content again
     * the next $sec seconds but use the browser cached content
     *
     * @param integer $sec Time in secons to hold in browser cache
     *
     * @return void
     */
    public static function scopeExpires($sec = 300)
    {
        self::addHeader(
            array(
                'Expires' => gmdate('D, d M Y H:i:s', time() + $sec) . ' GMT',
                'Cache-Control' => "max-age={$sec}, public, s-maxage={$sec}",
            )
        );
    }


    /**
     * SCOPE: Tells the browser that the following content is private
     *
     * @return void
     */
    public static function scopePrivate()
    {
        self::addHeader(
            array(
                'Pragma' => 'private',
                'Cache-control' => 'private, must-revalidate',
            )
        );
    }


    /**
     * SCOPE:  Tells the browser that the following content is public
     *
     * @return void
     */
    public static function scopePublic()
    {
        self::addHeader(
            array(
                'Pragma' => 'public',
            )
        );
    }


    /**
     * SCOPE: Forces a file dpwnload. Be sure to give the right extension
     *
     * @param string  $fileName The name of the file when it's downloaded
     * @param integer $fileSize The size in bytes. [Optional]
     *
     * @return void
     */
    public static function scopeDownload($fileName, $fileSize=null)
    {
        self::addHeader(
            array(
                'Content-Description' => 'File Transfer',
                'Content-disposition' => 'attachment; filename="'
                    . addslashes($fileName) . '"',
            )
        );
        // Add file size if provided
        if ((int) $fileSize > 0) {
            self::scopeLength($fileSize);
        }
        // For IE7
        self::scopePrivate();
    }


    /**
     * SCOPE: Tells the browser the length of the following content.
     * This mostly makes sense when using the download function
     * so the browser can calculate how many bytes are left
     * during the process
     *
     * @param integer $sizeInBytes The content size in bytes
     *
     * @return void
     */
    public static function scopeLength($sizeInBytes)
    {
        self::addHeader(
            array(
                'Content-Length' => (int) $sizeInBytes
            )
        );
    }


    /**
     * Called internally to prepare headers
     *
     * @param array $headerArray Use formar: Key=>Value
     *
     * @return void
     */
    public static function addHeader($headerArray)
    {
        if (!is_array(self::$_header)) {
            self::$_header = array();
        }

        foreach ($headerArray as $k => $v) {
            self::$_header[$k] = $v;
        }
    }


    /**
     * Removes a already defined header
     *
     * @param string $key
     * @return void
     * @author Florian Fackler
     */
    public function removeHeader($key)
    {
        if (is_array(self::$_header) && isset(self::$_header[$key])) {
            unset (self::$_header[$key]);
        }
    }


    /**
     * Returns the right mime type for an extenion
     *
     * @param string $ext Extension
     *
     * @return string The mime type
     */
    private static function _getMime($ext=null)
    {
        // First call of function
        if (!is_array(self::$_mimeTypes)) {
            // Try to get from cache
            self::$_mimeTypes = Yii::app()->cache->get('mtHeaderMimeTypes');
            // Not in cache? Read file
            if (!is_array(self::$_mimeTypes)) {
                self::$_mimeTypes = self::_readMimeTypes();
            }
            // Cache mime types for 28 days
            if (is_array(self::$_mimeTypes)) {
                Yii::app()->cache->set(
                    'mtHeaderMimeTypes',
                    self::$_mimeTypes,
                    (60 * 60 * 24 * 28)
                );
            }
        }

        if (!array_key_exists($ext, self::$_mimeTypes)) {
            throw new Exception('Unable to find this mime type ' . $ext);
        }
        return array('Content-Type' => self::$_mimeTypes[$ext]);
    }


    /**
     * The final output of the prepared headers
     *
     * @return void Sets headers, no return
     */
    private static function _output()
    {
        // Don't try to set headers when it's already too late
        if (true === headers_sent()) {
            return false;
        }

        foreach (self::$_header as $k => $v) {
            header("$k: $v");
        }
        self::$_header = null;
    }


    /**
     * Returns an array where the keys are the file extension and
     * the values are the associated mime types
     *
     * @return array Mime Types
     */
    private static function _readMimeTypes()
    {
        $mimeTypesFile = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'mime.types';
        if (!file_exists($mimeTypesFile)) {
            throw new Exception('Unable to find mime types file.');
        }

        $back = array();
        $fh = fopen($mimeTypesFile, 'r');
        while (feof($fh) === false) {
            $line = trim(fgets($fh));
            if (empty($line) || substr($line, 0, 1) === '#') {
                continue;
            }
            preg_match('@^(\S+)\s*(\S.*)?$@', $line, $match);
            if (!isset($match[2])) {
                continue;
            }
            $k = $match[1];
            $vs = preg_split('@\s+@', $match[2]);
            if (!is_array($vs)) {
                $vs = array($vs);
            }
            foreach ($vs as $v) {
                $back[$v] = $k;
            }
        }
        fclose($fh);
        return $back;
    }
}
