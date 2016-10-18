<?php

/**
 * flyandi:php-mime
 *
 * A quick and easy to use MimeType library that actually support descriptions
 *
 * @version: v1.0.0
 * @author: Andy Schwarz
 *
 * Created by Andy Schwarz. Please report any bug at http://github.com/flyandi/php-mime
 *
 * Copyright (c) 2016 Andy Schwarz http://github.com/flyandi
 *
 * The MIT License (http://www.opensource.org/licenses/mit-license.php)
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without
 * restriction, including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following
 * conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 */

/**
 * (Constants)
 */
define("MIMETYPE_DATABASE", dirname(__FILE__) . "/mime.json");
define("MIMETYPE_SEARCH_MIME", "mime");
define("MIMETYPE_SEARCH_EXTENSION", "extension");

/**
 * [MimeTypeByFilename description]
 * @param [type] $filename [description]
 */
function MimeTypeByFilename($filename) {

    return MimeTypeSearch(mime_content_type($filename));
}

/**
 * [MimeTypeByExtension description]
 * @param [type] $extension [description]
 */
function MimeTypeByExtension($extension) {

    $extension = str_replace(".", "", $extension);

    return MimeTypeSearch($extension, MIMETYPE_SEARCH_EXTENSION);
}

/**
 * [MimeTypeByFileExtension description]
 * @param [type] $filename [description]
 */
function MimeTypeByFileExtension($filename) {

    return MimeTypeByExtension(pathinfo($filename, PATHINFO_EXTENSION));
}

/**
 * [MimeTypeSearch description]
 * @param [type] $search [description]
 * @param [type] $match  [description]
 */
function MimeTypeSearch($search, $match = MIMETYPE_SEARCH_MIME, $asObject = true) {

    $search = trim(strtolower($search));

    $database = json_decode(file_get_contents(MIMETYPE_DATABASE));

    foreach($database as $mime => $item) {

        switch($match) {

            case MIMETYPE_SEARCH_MIME:

                if($mime == $search || (is_array(@$item->alternatives) && in_array($search, $item->alternatives))) return $item;

                break;

            case MIMETYPE_SEARCH_EXTENSION:

                if(in_array($search, $item->extensions)) return $item;

                break;
        }
    }

    return false;
}
