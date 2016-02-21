<?php

namespace Language;

/**
* Logic to generate applet language XML files 
*/
class AppletLanguage {

	/**
	*generate applet xml file
	*@param array of applets for which langauge xml's are generated
	*@return void 
	*@throws exception on failue
	*/
	public static function generateFile($applets) {

		echo "\nGENERATING APPLET LANGUAGE XML FILE\n";
    foreach ($applets as $appletDirectory => $appletLanguageId) {

      echo "\nAPPLET: $appletLanguageId\n";
      $languages = self::getLanguages($appletLanguageId);

      if (empty($languages)) {
				echo "\nLANGUAGE: NO LANGUAGE AVAIL\n";
        throw new \Exception("LANGUAGE: NO LANGUAGE AVAIL ($appletLanguageId)");
      }

      $path = self::getFilePath();

      foreach ($languages as $language) {
				echo "LANGUAGE: $language\n";
        $xmlContent = self::getFile($appletLanguageId, $language);
        $xmlFile    = $path.'/lang_'.$language.'.xml';
				echo "XMLFILE: $xmlFile\n";
        if (strlen($xmlContent) == file_put_contents($xmlFile, $xmlContent)) echo "RESPONSE: OK\n";
				else {
          echo "RESPONSE: FAILED\n";
          throw new \Exception("SAVE APPLET FAILED: $appletLanguageId LANG: $language XML: $xmlFile !");
        }
      }
    }
    echo "\nAPPLET LANGUAGE XML GENERATED SUCCESSFULLY\n";

	}

	/**
	*get applet xml languages
	*@param String containing applet identifier
	*@return array
	*@throws execption on failure
	*/
	public static function getLanguages($applet) {

		$result = ApiCall::call(
			'system_api',
			'language_api',
			array('system'=>'LanguageFiles','action'=>'getAppletLanguages'),
			array('applet'=>$applet)
		);

		try { 
			ErrorHandler::checkError($result); 
		} catch (\Exception $e) {
			echo "RESPONSE: GET LANGUAGE FAILED\n";
			throw new \Exception("GET LANGUAGE FAILED APPLET: $applet MSG: {$e->getMessage()}");
		}

		return $result['data'];

	}

	/**
	*get applet xml file
	*@param String containing applet identifier, String containing Language identifier
	*@return array
	*@throws exception on failure
	*/
	public static function getFile($applet,$language) {

		$result = ApiCall::call(
			'system_api',
			'language_api',
			array('system'=>'LanguageFiles','action'=>'getAppletLanguageFile'),
			array('applet'=>$applet,'language'=>$language)
		);

		try {
			ErrorHandler::checkError($result);
		} catch (\Exception $e) {
			echo "RESPONSE: GET LANGUAGE FILE FAILED\n";
			throw new \Exception("GET LANGUAGE XML FAILED APPLET: $applet LANGUAGE: $language MSG: {$e->getMessage()}");
		}
		
		return $result['data'];

	}

	/**
	*get applet xml file path
	*@param
	*@return String containing file path
	*@throws
	*/
	public static function getFilePath() {
      return Config::get('system.paths.root').'/cache/flash';
	}

}


?>
