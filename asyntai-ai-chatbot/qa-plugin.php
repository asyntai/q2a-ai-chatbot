<?php
/*
	Plugin Name: Asyntai AI Chatbot
	Plugin URI: https://asyntai.com/documentation/installation/question2answer/
	Plugin Description: Adds the Asyntai AI chatbot to your Q2A site. It answers visitor questions from your own pages, 24/7.
	Plugin Version: 1.0.0
	Plugin Date: 2026-10-26
	Plugin Author: Asyntai
	Plugin Author URI: https://asyntai.com
	Plugin License: MIT
	Plugin Minimum Question2Answer Version: 1.8
	Plugin Minimum PHP Version: 5.4
	Plugin Update Check URI:
*/

if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
	header('Location: ../../');
	exit;
}

// The settings form on Admin > Plugins > Asyntai AI Chatbot > options.
qa_register_plugin_module('module', 'qa-asyntai-admin.php', 'qa_asyntai_ai_chatbot_admin', 'Asyntai AI Chatbot');

// Adds the widget script before </body> on every public page.
qa_register_plugin_layer('qa-asyntai-layer.php', 'Asyntai AI Chatbot');
