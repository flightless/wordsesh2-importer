<?php

/**
 * Class Wordsesh2_UserImporter_UserListImporter_Test
 */
class Wordsesh2_UserImporter_UserListImporter_Test extends Wordsesh2_UserImporter_UnitTestCase {
	public function test_import_list() {
		$users = new ArrayObject(array(
			array( 'test_user1', 'test1@example.com', '' ),
			array( 'test_user2', 'test2@example.com', '' ),
		));
		$importer = new Wordsesh2_UserImporter_UserListImporter($users);
		$importer->import_list();

		$user1 = get_user_by('login', 'test_user1');
		$user2 = get_user_by('login', 'test_user2');

		$this->assertInstanceOf('WP_User', $user1);
		$this->assertInstanceOf('WP_User', $user2);

		$this->assertNotEquals(0, $user1->ID);
		$this->assertNotEquals(0, $user2->ID);
	}

	public function test_import_file() {
		$file = new SplFileObject($this->get_test_csv_file_path());
		$file->setFlags(SplFileObject::SKIP_EMPTY|SplFileObject::READ_CSV|SplFileObject::READ_AHEAD|SplFileObject::DROP_NEW_LINE);

		$importer = new Wordsesh2_UserImporter_UserListImporter($file);
		$importer->import_list();

		$first = get_user_by('login', 'gentoo');
		$middle = get_user_by('login', 'emperor');
		$last = get_user_by('login', 'galapagos');

		$this->assertInstanceOf('WP_User', $first);
		$this->assertInstanceOf('WP_User', $middle);
		$this->assertInstanceOf('WP_User', $last);

		$this->assertNotEquals(0, $first->ID);
		$this->assertNotEquals(0, $middle->ID);
		$this->assertNotEquals(0, $last->ID);
	}

	public function test_response() {
		$users = new ArrayObject(array(
			array( 'test_user1', 'test1@example.com', '' ),
			array( 'test_user2', 'test2@example.com', '' ),
		));
		$importer = new Wordsesh2_UserImporter_UserListImporter($users);
		$response = $importer->import_list();

		$this->assertInstanceOf( 'Wordsesh2_UserImporter_MessageCollection', $response );

		$this->assertCount(2, $response->get('update'));
		$this->assertCount(0, $response->get('error'));

		$users = new ArrayObject(array(
			array( '', 'test3@example.com', '' ),
			array( 'test_user4', 'test4@example.com', '' ),
		));
		$importer = new Wordsesh2_UserImporter_UserListImporter($users);
		$response = $importer->import_list();

		$this->assertCount(1, $response->get('update'));
		$this->assertCount(1, $response->get('error'));
	}
}
 