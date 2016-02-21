<?php

namespace Language;
header('Content-Type: text/plain');

/**
 * Business logic related to generating language files.
 */
class LanguageBatchBo {

	/**
	 * Starts the language file generation.
	 * @return void
	 */
	public static function generateLanguageFiles() {
		Language::generateFile(Config::get('system.translated_applications'));
	}

	/**
	 * Gets the language files for the applet and puts them into the cache.
	 * @throws Exception   If there was an error.
	 * @return void
	 */
	public static function generateAppletLanguageXmlFiles()	{
		AppletLanguage::generateFile(array('memberapplet'=>'JSM2_MemberApplet'));
	}

}
