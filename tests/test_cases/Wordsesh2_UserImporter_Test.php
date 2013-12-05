<?php

/**
 * Class Wordsesh2_UserImporter_Test
 */
class Wordsesh2_UserImporter_Test extends Wordsesh2_UserImporter_UnitTestCase {
	public function test_plugin_loaded() {
		$instance = Wordsesh2_UserImporter::instance();
		$this->assertInstanceOf('Wordsesh2_UserImporter', $instance);
	}
}
 