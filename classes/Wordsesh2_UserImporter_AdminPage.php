<?php

/**
 * Class Wordsesh2_UserImporter_AdminPage
 */
class Wordsesh2_UserImporter_AdminPage {
	const MENU_SLUG = 'wordsesh2_userimporter';

	public function register() {
		add_management_page(
			__('Import Users', 'wordsesh'),
			__('Import Users', 'wordsesh'),
			'manage_users',
			self::MENU_SLUG,
			array( $this, 'display' )
		);
	}

	public function display() {
		$title = __('Import users', 'wordsesh');
		$icon = get_screen_icon('tools');
		$description = '<p class="description">'.__('Import users from a CSV.', 'wordsesh').'</p>';

		$nonce = wp_nonce_field('userimport', 'wordsesh2nonce', true, false);
		$file_input = '<input type="file" name="import_file" id="wordsesh-user-import-csv-file"/>';
		$button = get_submit_button(__('Import Users', 'wordsesh'));
		$form = sprintf('<form method="post" action="%s" enctype="multipart/form-data">%s%s%s</form>', $_SERVER['REQUEST_URI'], $nonce, $file_input, $button);

		$content = $description.$form;

		ob_start();
		settings_errors();
		$messages = ob_get_clean();

		printf( '<div class="wrap">%s<h2>%s</h2>%s%s</div>', $icon, $title, $messages, $content );
	}
}
 