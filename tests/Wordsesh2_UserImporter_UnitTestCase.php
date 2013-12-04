<?php

/**
 * Class Wordsesh2_UserImporter_UnitTestCase
 */
abstract class Wordsesh2_UserImporter_UnitTestCase extends WP_UnitTestCase {
	protected function get_test_csv_file_path() {
		return dirname( __FILE__ ) . '/data/user_list.csv';
	}

	protected function clear_settings_errors() {
		global $wp_settings_errors;
		$wp_settings_errors = array();
		delete_transient( 'settings_errors' );
	}
}
