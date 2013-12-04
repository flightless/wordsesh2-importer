<?php

/**
 * Class Wordsesh2_UserImporter_SubmissionHandler
 */
class Wordsesh2_UserImporter_SubmissionHandler {
	private $_POST = array();
	private $_FILES = array();

	public function __construct( $post, $files ) {
		$this->_POST = $post;
		$this->_FILES = $files;
	}

	public function submission_detected() {
		return ( isset($this->_POST['wordsesh2nonce']) && isset($this->_FILES['import_file']['tmp_name']) );
	}

	public function handle_import_request() {
		if ( !$this->valid_submission() ) {
			return;
		}
		$this->do_import();
		$this->redirect();
	}

	public function valid_submission() {
		return ( $this->valid_nonce() && $this->valid_file() );
	}

	private function valid_nonce() {
		return wp_verify_nonce($this->_POST['wordsesh2nonce'], 'userimport');
	}

	private function valid_file() {
		$file_path = $this->get_submitted_file_path();
		return ( !empty($file_path) && file_exists($file_path) );
	}

	private function get_submitted_file_path() {
		if ( !isset($this->_FILES['import_file']['tmp_name']) ) {
			return FALSE;
		}
		return $this->_FILES['import_file']['tmp_name'];
	}

	public function do_import() {
		$file = $this->get_submitted_file();
		$importer = new Wordsesh2_UserImporter_UserListImporter($file);
		$messages = $importer->import_list();
		$messages->save();
	}

	private function get_submitted_file() {
		$path = $this->get_submitted_file_path();

		$file = new SplFileObject($path);
		$file->setFlags(SplFileObject::SKIP_EMPTY|SplFileObject::READ_CSV|SplFileObject::READ_AHEAD|SplFileObject::DROP_NEW_LINE);
		return $file;
	}

	private function redirect() {
		wp_redirect( add_query_arg( array('settings-updated' => 1 ) ) );
		exit();
	}

}
 