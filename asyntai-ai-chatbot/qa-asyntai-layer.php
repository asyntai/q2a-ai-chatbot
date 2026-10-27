<?php
/*
	Asyntai AI Chatbot for Question2Answer
	Theme layer: adds the widget script before </body> on public pages.
*/

if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
	header('Location: ../../');
	exit;
}

class qa_html_theme_layer extends qa_html_theme_base
{
	public function body_hidden()
	{
		parent::body_hidden();

		// Q2A evaluates layer code from qa-include, so __FILE__ is not this
		// folder. Load the settings module through Q2A instead.
		if (!class_exists('qa_asyntai_ai_chatbot_admin')) {
			qa_load_module('module', 'Asyntai AI Chatbot');
		}

		if (!class_exists('qa_asyntai_ai_chatbot_admin')) {
			return;
		}

		$tag = qa_asyntai_ai_chatbot_admin::script_tag($this->template, $this->request);

		if ($tag !== '') {
			$this->output($tag);
		}
	}
}
