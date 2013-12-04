<?php

/**
 * Class Wordsesh2_UserImporter_UserListImporter
 */
class Wordsesh2_UserImporter_UserListImporter {
	/** @var Traversable */
	private $list = NULL;

	/** @var Wordsesh2_UserImporter_MessageCollection */
	private $messages = NULL;

	public function __construct( Traversable $list ) {
		$this->list = $list;
		$this->messages = new Wordsesh2_UserImporter_MessageCollection();
	}

	public function import_list() {
		foreach ( $this->list as $row ) {
			$user = new Wordsesh2_UserImporter_PendingUser($row[0], $row[1], $row[2]);
			$user_id = $user->save();
			$this->log_message($user_id, $user);
		}
		return $this->messages;
	}

	/**
	 * @param int|WP_Error $response
	 * @param Wordsesh2_UserImporter_PendingUser $user
	 *
	 * @return void
	 */
	private function log_message( $response, $user ) {

		if ( is_wp_error($response) ) {
			$this->log_error_message( $response, $user );
		} else {
			$this->log_success_message( $response, $user );
		}
	}

	/**
	 * @param WP_Error $error
	 * @param Wordsesh2_UserImporter_PendingUser $user
	 *
	 * @return void
	 */
	private function log_error_message( $error, $user ) {
		if ( $error->get_error_code() == 'import_skipped' ) {
			$this->messages->add($error->get_error_message());
		} else {
			$this->messages->add( sprintf( __( 'Error creating user %s (%s). "%s"', 'wordsesh' ), $user->get_username(), $user->get_email_address(), $error->get_error_message() ), 'error' );
		}
	}

	/**
	 * @param int $user_id
	 * @param Wordsesh2_UserImporter_PendingUser $user
	 *
	 * @return void
	 */
	private function log_success_message( $user_id, $user ) {
		$this->messages->add(sprintf( __( 'Created new user %s (%s) with user ID %d', 'wordsesh' ), $user->get_username(), $user->get_email_address(), $user_id ));
	}
}
 