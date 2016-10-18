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
 * Update Database File
 */

define('APACHE_MIME_TYPES_URL','http://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types');

define('BASE_DATABASE', dirname(__FILE__) . "/base.json");


function generateUpToDateMimeArray($url = APACHE_MIME_TYPES_URL){

    $result = [];

    foreach(@explode("\n", @file_get_contents($url)) as $item) {

        if(isset($item[0]) && $item[0] !== "#") {

            $parts = explode(" ", str_replace("\t", " ", $item), 2);

            if(count($parts) == 2) {

                $mime = trim(strtolower($parts[0]));

                $extensions = [];
                foreach(explode(" ", trim(strtolower($parts[1]))) as $extension) {
                    $extensions[] = strtolower(trim(str_replace(".", "", $extension)));
                }

                $result[$mime] = [
                    "extensions" => $extensions,
                    "mime" => $mime,
                    "alternatives" => false,
                    "description" => false
                ];
            }
        }
    }

    // updated base
    $base = json_decode(file_get_contents(BASE_DATABASE), true);

    foreach($base as $mime => $item) {

        if(!isset($result[$mime])) {
            $result[$mime] = [
                "extensions" => [],
                "mime" => $mime,
                "alternatives" => false,
                "description" => false
            ];
        }

        // set description from base file
        $result[$mime]["description"] = $item["description"];

        // set extensions
        if(isset($item["extension"])) {
            $extensions = explode(",", $item["extension"]);

            foreach($extensions as $extension) {

                $extension = strtolower(trim(str_replace(".", "", $extension)));

                if(!in_array($extension, $result[$mime]["extensions"])) {

                    $result[$mime]["extensions"][] = $extension;
                }
            }
        }

        // set alternative mimes
        if(isset($item["alternatives"])) $result[$mime]["alternatives"] = $item["alternatives"];

    }


    return $result;
}

file_put_contents("../src/mime.json", json_encode(generateUpToDateMimeArray(APACHE_MIME_TYPES_URL)));
