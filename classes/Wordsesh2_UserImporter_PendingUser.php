<?php

/**
 * Class Wordsesh2_UserImporter_PendingUser
 */
class Wordsesh2_UserImporter_PendingUser {
	private $username = '';
	private $email_address = '';
	private $password = '';

	public function __construct( $username, $email_address, $password = '' ) {
		$this->username = $username;
		$this->email_address = $email_address;
		$this->password = $password ? $password : wp_generate_password();
	}

	/**
	 * @return string
	 */
	public function get_email_address() {
		return $this->email_address;
	}

	/**
	 * @return string
	 */
	public function get_password() {
		return $this->password;
	}

	/**
	 * @return string
	 */
	public function get_username() {
		return $this->username;
	}

	public function is_valid() {
		if ( $this->email_conflict() || $this->username_conflict() ) {
			return FALSE;
		}
		return TRUE;
	}

	private function email_conflict() {
		$exists = get_user_by('email', $this->email_address);
		return $exists !== FALSE;
	}

	private function username_conflict() {
		$exists = username_exists($this->username);
		return $exists !== null;
	}

	public function save() {
		if ( !$this->is_valid() ) {
			return new WP_Error('import_skipped', sprintf( __( 'Skipped user %s (%s). Username or email address is already taken.', 'wordsesh' ), $this->username, $this->email_address ) );
		}
		return wp_create_user( $this->username, $this->password, $this->email_address );
	}

}
 