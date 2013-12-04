<?php
/*
Plugin Name: WordSesh2 Demo - User importer
Plugin URI: https://github.com/flightless/wordsesh2-importer
Description: Import users from a CSV
Author: Flightless
Author URI: http://flightless.us/
Version: 0.1
*/
/*
Copyright (c) 2013 Flightless, Inc. http://flightless.us/

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be included
in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

function wordsesh2_userimporter_initialize() {
	spl_autoload_register('wordsesh2_userimporter_autoload');
	Wordsesh2_UserImporter::instance();
}

function wordsesh2_userimporter_autoload( $class ) {

	if ( strpos( $class, 'Wordsesh2_UserImporter' ) !== 0 ) {
		return;
	}
	$dir = dirname(__FILE__).DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR;

	if ( file_exists( $dir.$class.'.php' ) ) {
		include( $dir.$class.'.php' );
		return;
	}
}
add_action( 'plugins_loaded', 'wordsesh2_userimporter_initialize', 10, 0 );