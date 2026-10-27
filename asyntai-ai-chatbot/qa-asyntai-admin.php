<?php
/*
	Asyntai AI Chatbot for Question2Answer
	Settings form and the shared helper that builds the widget script tag.
*/

if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
	header('Location: ../../');
	exit;
}

class qa_asyntai_ai_chatbot_admin
{
	const SCRIPT_URL = 'https://widget.asyntai.com/static/js/chat-widget.js';

	public function option_default($option)
	{
		switch ($option) {
			case 'asyntai_ai_chatbot_enabled':
				return 1;
			case 'asyntai_ai_chatbot_widget_id':
			case 'asyntai_ai_chatbot_excluded_pages':
				return '';
		}

		return null;
	}

	/**
	 * Returns a valid widget ID, or an empty string. The owner may paste the
	 * whole embed code from the Asyntai dashboard, so the ID is taken from
	 * the data-asyntai-id attribute when one is present.
	 */
	public static function clean_widget_id($value)
	{
		$value = trim((string) $value);

		if (preg_match('/data-asyntai-id\s*=\s*["\']?([A-Za-z0-9_-]+)/i', $value, $match)) {
			$value = $match[1];
		}

		$value = trim($value, "=;\"' \t\r\n");

		return preg_match('/^[A-Za-z0-9_-]{4,64}$/', $value) ? $value : '';
	}

	/**
	 * Returns the list of page templates and request paths that must not
	 * show the chatbot, from the comma separated option.
	 */
	public static function excluded_pages()
	{
		$pages = array();

		foreach (explode(',', (string) qa_opt('asyntai_ai_chatbot_excluded_pages')) as $page) {
			$page = strtolower(trim($page, " \t\r\n/"));

			if ($page !== '') {
				$pages[] = $page;
			}
		}

		return $pages;
	}

	/**
	 * Returns the script tag for a page, or an empty string when nothing must load.
	 */
	public static function script_tag($template, $request)
	{
		if ((int) qa_opt('asyntai_ai_chatbot_enabled') !== 1) {
			return '';
		}

		$widget_id = self::clean_widget_id(qa_opt('asyntai_ai_chatbot_widget_id'));

		if ($widget_id === '') {
			return '';
		}

		$template = strtolower((string) $template);
		$request = strtolower(trim((string) $request, '/'));

		// Never on the admin pages of the site.
		if ($template === 'admin' || strpos($request, 'admin') === 0) {
			return '';
		}

		// The owner already pasted the widget into the custom HTML of the site.
		foreach (array('custom_in_head', 'custom_header', 'custom_footer', 'custom_sidebar') as $option) {
			if (strpos((string) qa_opt($option), 'widget.asyntai.com/static/js/chat-widget.js') !== false) {
				return '';
			}
		}

		$first = $request === '' ? '' : current(explode('/', $request));

		foreach (self::excluded_pages() as $page) {
			if ($page === $template || $page === $request || $page === $first) {
				return '';
			}
		}

		return '<script src="' . self::SCRIPT_URL . '" data-asyntai-id="' . qa_html($widget_id) . '" async></script>';
	}

	public function admin_form(&$qa_content)
	{
		$ok = null;
		$error = null;

		if (qa_clicked('asyntai_ai_chatbot_save')) {
			$raw = (string) qa_post_text('asyntai_ai_chatbot_widget_id');
			$widget_id = self::clean_widget_id($raw);

			qa_opt('asyntai_ai_chatbot_enabled', (int) qa_post_text('asyntai_ai_chatbot_enabled') ? 1 : 0);
			qa_opt('asyntai_ai_chatbot_excluded_pages', trim((string) qa_post_text('asyntai_ai_chatbot_excluded_pages')));

			if (trim($raw) !== '' && $widget_id === '') {
				$error = 'This Widget ID is not valid. Copy it from the Install page of your Asyntai dashboard, for example asyntai_1a2b3c4d5e6f.';
			} else {
				qa_opt('asyntai_ai_chatbot_widget_id', $widget_id);
				$ok = 'Asyntai settings saved.';
			}
		}

		$widget_id = (string) qa_opt('asyntai_ai_chatbot_widget_id');

		if ($error === null && $widget_id === '') {
			$status = 'The chatbot does not show yet. Add your Widget ID to switch it on.';
		} elseif ((int) qa_opt('asyntai_ai_chatbot_enabled') !== 1) {
			$status = 'The chatbot is switched off.';
		} else {
			$status = 'The chatbot shows on your site.';
		}

		return array(
			'ok' => $ok === null ? null : qa_html($ok),
			'fields' => array(
				array(
					'type' => 'static',
					'label' => 'Status:',
					'value' => qa_html($status),
				),
				array(
					'label' => 'Widget ID:',
					'value' => qa_html($widget_id),
					'tags' => 'name="asyntai_ai_chatbot_widget_id" id="asyntai_ai_chatbot_widget_id"',
					'error' => $error === null ? null : qa_html($error),
					'note' => 'No account yet? Create one free at <a href="https://asyntai.com" target="_blank" rel="noopener">asyntai.com</a>. Then open the Install page of your <a href="https://asyntai.com/dashboard" target="_blank" rel="noopener">Asyntai dashboard</a> and copy your Widget ID.',
				),
				array(
					'type' => 'checkbox',
					'label' => 'Show the chatbot on the site',
					'value' => (int) qa_opt('asyntai_ai_chatbot_enabled') === 1,
					'tags' => 'name="asyntai_ai_chatbot_enabled" id="asyntai_ai_chatbot_enabled" value="1"',
				),
				array(
					'label' => 'Pages without the chatbot:',
					'value' => qa_html(qa_opt('asyntai_ai_chatbot_excluded_pages')),
					'tags' => 'name="asyntai_ai_chatbot_excluded_pages" id="asyntai_ai_chatbot_excluded_pages"',
					'note' => 'Optional. Page types or paths, separated by commas, for example: ask, login, register. Leave empty to show the chatbot everywhere. Admin pages never show it.',
				),
			),
			'buttons' => array(
				array(
					'label' => 'Save Changes',
					'tags' => 'name="asyntai_ai_chatbot_save"',
				),
			),
		);
	}
}
