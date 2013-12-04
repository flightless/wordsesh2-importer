<?php

/**
 * Class Wordsesh2_UserImporter_PendingUser_Test
 */
class Wordsesh2_UserImporter_PendingUser_Test extends Wordsesh2_UserImporter_UnitTestCase {
	public function test_create_pending_user() {
		$user = new Wordsesh2_UserImporter_PendingUser('test_username', 'test@example.com', 'test_password');
		$this->assertInstanceOf( 'Wordsesh2_UserImporter_PendingUser', $user );
		$this->assertEquals( 'test_username', $user->get_username() );
		$this->assertEquals( 'test@example.com', $user->get_email_address() );
		$this->assertEquals( 'test_password', $user->get_password() );
	}

	public function test_autogenerated_password() {
		$user = new Wordsesh2_UserImporter_PendingUser('test_username', 'test@example.com', '');
		$this->assertNotEquals( '', $user->get_password() );
	}

	public function test_existing_user_validation() {
		$user = new Wordsesh2_UserImporter_PendingUser('test_username', 'test@example.com', 'test_password');
		$this->assertTrue( $user->is_valid() );

		$conflicting_username_user_id = wp_create_user( 'test_username', wp_generate_password(), 'test2@example.com' );
		$this->assertFalse( $user->is_valid() );

		wp_delete_user( $conflicting_username_user_id );
		$this->assertTrue( $user->is_valid() );

		$conflicting_email_user_id = wp_create_user( 'different_username', wp_generate_password(), 'test@example.com' );
		$this->assertFalse( $user->is_valid() );

		wp_delete_user( $conflicting_email_user_id );
		$this->assertTrue( $user->is_valid() );
	}

	public function test_save_user() {
		$user = new Wordsesh2_UserImporter_PendingUser('test_username', 'test@example.com', 'test_password');
		$user_id = $user->save();
		$wp_user = new WP_User($user_id);
		$this->assertEquals( 'test_username', $wp_user->user_login );
		$this->assertEquals( 'test@example.com', $wp_user->user_email );
	}

	public function test_failed_to_save_user() {
		$user = new Wordsesh2_UserImporter_PendingUser('test_username', 'test@example.com', 'test_password');
		wp_create_user( 'test_username', wp_generate_password(), 'test2@example.com' );
		$error = $user->save();
		$this->assertTrue(is_wp_error($error));
		$this->assertEquals('import_skipped', $error->get_error_code());
	}

	public function test_wordpress_error() {
		$user = new Wordsesh2_UserImporter_PendingUser('', 'test@example.com', 'test_password');
		$error = $user->save();
		$this->assertTrue(is_wp_error($error));
		$this->assertEquals('empty_user_login', $error->get_error_code());
	}
}
 