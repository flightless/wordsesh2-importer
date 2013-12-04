<?php

/**
 * Class Wordsesh2_UserImporter_MessageCollection
 */
class Wordsesh2_UserImporter_MessageCollection {
	private $update = array();
	private $error = array();
	public function __construct() {

	}

	public function add( $message, $status = 'update' ) {
		switch( $status ) {
			case 'error':
				$this->error[] = $message;
				break;
			case 'update':
				$this->update[] = $message;
				break;
		}
	}

	public function get( $status ) {
		switch( $status ) {
			case 'error':
				return $this->error;
			case 'update':
				return $this->update;
			default:
				return array();
		}
	}

	public function to_html( $status ) {
		$messages = $this->get($status);
		if ( empty($messages) ) {
			return '';
		}
		$html = '<ul>';
		foreach ( $messages as $m ) {
			$html .= '<li>'.$m.'</li>';
		}
		$html .= '</ul>';
		return $html;
	}

	public function save() {
		$errors = $this->to_html('error');
		if ( !empty($errors) ) {
			add_settings_error('general', 'import_error', __('Import Errors', 'wordsesh') . $errors, 'error');
		}

		$updates = $this->to_html('update');
		if ( !empty($updates) ) {
			add_settings_error('general', 'import_success', __('Import Complete', 'wordsesh') . $updates, 'updated');
		}

		if ( empty($updates) && empty($errors) ) {
			add_settings_error('general', 'import_error', __('Nothing imported', 'wordsesh'), 'error');
		}

		set_transient('settings_errors', get_settings_errors(), 30);
	}
}
 