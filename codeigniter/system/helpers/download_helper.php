<?php

/**
 * CodeIgniter.
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2019 - 2022, CodeIgniter Foundation
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2019, British Columbia Institute of Technology (https://bcit.ca/)
 * @copyright	Copyright (c) 2019 - 2022, CodeIgniter Foundation (https://codeigniter.com/)
 * @license	https://opensource.org/licenses/MIT	MIT License
 *
 * @see	https://codeigniter.com
 * @since	Version 1.0.0
 *
 * @filesource
 */
defined('BASEPATH') || exit('No direct script access allowed');

/*
 * CodeIgniter Download Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/userguide3/helpers/download_helper.html
 */

// ------------------------------------------------------------------------

if (!function_exists('force_download')) {
    /**
     * Force Download.
     *
     * Generates headers that force a download to happen
     *
     * @param	string	filename
     * @param	mixed	the data to be downloaded
     * @param	bool	whether to try and send the actual file MIME type
     *
     * @return void
     */
    function force_download($filename = '', $data = '', $set_mime = false)
    {
        if ('' === $filename or '' === $data) {
            return;
        } elseif (null === $data) {
            if (!@is_file($filename) or ($filesize = @filesize($filename)) === false) {
                return;
            }

            $filepath = $filename;
            $filename = explode('/', str_replace(DIRECTORY_SEPARATOR, '/', $filename));
            $filename = end($filename);
        } else {
            $filesize = strlen($data);
        }

        // Set the default MIME type to send
        $mime = 'application/octet-stream';

        $x = explode('.', $filename);
        $extension = end($x);

        if (true === $set_mime) {
            if (1 === count($x) or '' === $extension) {
                /* If we're going to detect the MIME type,
                 * we'll need a file extension.
                 */
                return;
            }

            // Load the mime types
            $mimes = &get_mimes();

            // Only change the default MIME if we can find one
            if (isset($mimes[$extension])) {
                $mime = is_array($mimes[$extension]) ? $mimes[$extension][0] : $mimes[$extension];
            }
        }

        /* It was reported that browsers on Android 2.1 (and possibly older as well)
         * need to have the filename extension upper-cased in order to be able to
         * download it.
         *
         * Reference: http://digiblog.de/2011/04/19/android-and-the-download-file-headers/
         */
        if (1 !== count($x) && isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/Android\s(1|2\.[01])/', $_SERVER['HTTP_USER_AGENT'])) {
            $x[count($x) - 1] = strtoupper($extension);
            $filename = implode('.', $x);
        }

        if (null === $data && ($fp = @fopen($filepath, 'rb')) === false) {
            return;
        }

        // Clean output buffer
        if (0 !== ob_get_level() && false === @ob_end_clean()) {
            @ob_clean();
        }

        // Generate the server headers
        header('Content-Type: ' . $mime);
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Expires: 0');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . $filesize);
        header('Cache-Control: private, no-transform, no-store, must-revalidate');

        // If we have raw data - just dump it
        if (null !== $data) {
            exit($data);
        }

        // Flush 1MB chunks of data
        while (!feof($fp) && ($data = fread($fp, 1048576)) !== false) {
            echo $data;
        }

        fclose($fp);
        exit;
    }
}
