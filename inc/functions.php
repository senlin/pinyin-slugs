<?php
 /**
  * Main function of the plugin
  * 
  * Checks whether title contains Chinese characters
  * and return the slug in Pinyin when true
  *
  * since version 2.0.0
  */

function getPinyinSlug( $strTitle ) {
	// Load Chinese character dictionary
	global $dictPinyin;
		
	$strRet = '';
	
/*
	$options = get_option( 'sosp_options' );
	$PSL = $options['slug_length'];
*/
	
	$PSL = get_option( 'slug_length', 100 );
	
	$origStrTitle = $strTitle; // Save the original title<-------------------------------------------
	$containsChinese = false; // Setting a flag variable, the default is false, if the title contains Chinese characters it echoes true<-------------------

	if ( get_bloginfo( 'charset' ) !="UTF-8" ) {
		$strTitle = iconv( get_bloginfo( "charset" ), "UTF-8", $strTitle );
	}
	if ( $PSL>0 ) { 
		$strTitle=substr( $strTitle, 0, $PSL );
	}
	for ( $i = 0; $i < strlen( $strTitle ); $i++ ) {
		//Take 1 byte???
		$byte1st = ord( substr( $strTitle, $i, 1 ) );
		//If in range between 11100000 and 11101111 then it is a Chinese character in UTF-8
		if ( $byte1st >= 224 && $byte1st <= 239 ) {
			$containsChinese = true; // If the title contains Chinese characters will flag variable is set to true<-------------------------------------------
			
			//Grab the whole character, UTF-8 is a 3-byte Chinese character
			$fullChar = substr( $strTitle, $i, 3 ); $i+=2;
			//Find spelling in the dictionary; if it cannotfind the character, it will be ignored
			foreach ( $dictPinyin as $pinyin=>$val ) {
				if ( strpos( $val, $fullChar ) !== false ) {
						$strRet .= $pinyin;
					break;
				}
			}
		} else {
			/**
			 * fix to not ignore alphanumerical characters
			 * by [vanabel](https://github.com/vanabel)
			 * 
			 * @source: //github.com/senlin/so-pinyin-slugs/issues/4
			 */
			$strRet .= preg_replace( '/[^A-Za-z0-9\-]/', '$0', chr( $byte1st ) );
		}
	}

	if (! $containsChinese ) { // If the title does not contain Chinese characters, return the previously saved original title<-----
		$strRet = $origStrTitle;
	} 
	
	return $strRet;
}

