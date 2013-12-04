<?php

/**
 * Class Wordsesh2_UserImporter
 */
class Wordsesh2_UserImporter {
	/** @var Wordsesh2_UserImporter  */
	private static $instance = NULL;

	/** @var Wordsesh2_UserImporter_AdminPage */
	private $admin_page = NULL;

	/** @var Wordsesh2_UserImporter_SubmissionHandler */
	private $submission_handler = NULL;


	private function __construct() {
		$this->setup_admin_page();
		$this->setup_submission_handler();
	}

	private function setup_admin_page() {
		$this->admin_page = new Wordsesh2_UserImporter_AdminPage();
		add_action( 'admin_menu', array( $this->admin_page, 'register' ), 10, 0 );
	}

	private function setup_submission_handler() {
		$this->submission_handler = new Wordsesh2_UserImporter_SubmissionHandler($_POST, $_FILES);

		if ( $this->submission_handler->submission_detected() ) {
			add_action( 'load-tools_page_'.Wordsesh2_UserImporter_AdminPage::MENU_SLUG, array( $this->submission_handler, 'handle_import_request' ), 10, 0 );
		}
	}

	public function admin() {
		return $this->admin_page;
	}

	public function handler() {
		return $this->submission_handler;
	}

	public static function instance() {
		if ( !isset(self::$instance) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
 