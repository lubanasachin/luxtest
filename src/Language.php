<?php

namespace Language;

/**
* Logic to generate language files
*/
class Language {

  /**
  * generate language file
  * @param array of applications
  * @return 
  * @throws exception on failure
  */
  public static function generateFile($applications) {
    echo "\nGENERATING LANGUAGE FILE\n";
    foreach ($applications as $application => $languages) {
      echo "\nAPPLICATION: $application\n";
      foreach ($languages as $language) {
        echo "LANGUAGE: $language\n";
        if (self::getFile($application, $language)) {
          echo "RESPONSE: OK\n";
        } else {
					echo "RESP: GENERATE FILE FAILED\n";
          throw new \Exception('GENERATE LANGUAGE FILE FAILED');
        }
      }
    }
    echo "\nLANGUAGE FILE GENERATED SUCCESSFULLY\n";
  }

	/**
	* get language file
	* @param String containing application identifier, String containing language identifier
	* @return boolean
	* @throws execption on failure
	*/
	public static function getFile($application,$language) {

		$result = false;
		$languageResponse = ApiCall::call(
			'system_api',
			'language_api',
			array('system'=>'LanguageFiles','action'=>'getLanguageFile'),
			array('language'=>$language)
		);
		
		try {
			ErrorHandler::checkError($languageResponse);
		} catch (\Exception $e) {
			echo "RESP: GET FILE FAILED\n";
			throw new \Exception("Error during getting language file: ($application/$language)");
		}

		$destination = self::getFilePath($application).$language.'.php';
		if(!is_dir(dirname($destination))) {
			mkdir(dirname($destination), 0755, true);
		}

		$result = file_put_contents($destination, $languageResponse['data']);
		return (bool)$result;

	}

	/**
	* get language file path
	* @param String containing application name
	* @return String containing file name
	* @throws
	*/
	public static function getFilePath($application) {
		return Config::get('system.paths.root').'/cache/'.$application.'/';
	}

}

?>
