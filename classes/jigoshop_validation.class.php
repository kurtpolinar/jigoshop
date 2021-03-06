<?php
/**
 * Validation Class
 *
 * DISCLAIMER
 *
 * Do not edit or add directly to this file if you wish to upgrade Jigoshop to newer
 * versions in the future. If you wish to customise Jigoshop core for your needs,
 * please use our GitHub repository to publish essential changes for consideration.
 *
 * @package             Jigoshop
 * @category            Core
 * @author              Jigowatt
 * @copyright           Copyright © 2011-2012 Jigowatt Ltd.
 * @license             http://jigoshop.com/license/commercial-edition
 */
class jigoshop_validation {

	/**
	 * Validates a numeric integer value, positive or negative
	 * Must contain valid numeric characters [0-9]
	 *
	 * @param   string	value to determine if numeric integer value
	 * @return  boolean
	 */
	public static function is_integer( $value ) {
		if ( $value[0] == '-' || $value[0] == '+' ) {
			return ctype_digit( substr( $value, 1 ) );
		}
  		return ctype_digit( $value );
	}

	/**
	 * Validates a numeric natural number, positive only
	 * Must contain valid numeric characters [0-9]
	 *
	 * @param   string	value to determine if natural number
	 * @return  boolean
	 */
	public static function is_natural( $value ) {
		return ctype_digit( $value );
	}

	/**
	 * Validates a decimal value, positive or negative
	 * Must contain valid numeric characters [0-9]
	 * May contain optional decimal point followed by numeric characters [0-9]
	 *
	 * @param   string	value to determine if decimal value
	 * @return  boolean
	 */
	public static function is_decimal( $value ) {
  		return is_numeric( $value );
	}

	/**
	 * Validates an email using wordpress native is_email function
	 *
	 * @param   string	email address
	 * @return  boolean
	 */
	public static function is_email( $email ) {
		return is_email( $email );
	}

	/**
	 * Validates a phone number using a regular expression
	 *
	 * @param   string	phone number
	 * @return  boolean
	 */
	public static function is_phone( $phone ) {
		if (strlen(trim(preg_replace('/[\s\#0-9_\-\+\(\)]/', '', $phone)))>0) return false;
		return true;
	}

	/**
	 * Checks for a valid postcode (UK)
	 *
	 * @param   string	postcode
	 * @param	string	country
	 * @return  boolean
	 */
	public static function is_postcode( $postcode, $country ) {
		if (strlen(trim(preg_replace('/[\s\-A-Za-z0-9]/', '', $postcode)))>0) return false;
		if ($country=='GB') :
			return self::is_GB_postcode( $postcode );
		endif;
		return true;
	}

	/* Author: John Gardner */
	public static function is_GB_postcode( $toCheck ) {

		// Permitted letters depend upon their position in the postcode.
		$alpha1 = "[abcdefghijklmnoprstuwyz]";                          // Character 1
		$alpha2 = "[abcdefghklmnopqrstuvwxy]";                          // Character 2
		$alpha3 = "[abcdefghjkstuw]";                                   // Character 3
		$alpha4 = "[abehmnprvwxy]";                                     // Character 4
		$alpha5 = "[abdefghjlnpqrstuwxyz]";                             // Character 5

		// Expression for postcodes: AN NAA, ANN NAA, AAN NAA, and AANN NAA
		$pcexp[0] = '^('.$alpha1.'{1}'.$alpha2.'{0,1}[0-9]{1,2})([0-9]{1}'.$alpha5.'{2})$';

		// Expression for postcodes: ANA NAA
		$pcexp[1] =  '^('.$alpha1.'{1}[0-9]{1}'.$alpha3.'{1})([0-9]{1}'.$alpha5.'{2})$';

		// Expression for postcodes: AANA NAA
		$pcexp[2] =  '^('.$alpha1.'{1}'.$alpha2.'[0-9]{1}'.$alpha4.')([0-9]{1}'.$alpha5.'{2})$';

		// Exception for the special postcode GIR 0AA
		$pcexp[3] =  '^(gir)(0aa)$';

		// Standard BFPO numbers
		$pcexp[4] = '^(bfpo)([0-9]{1,4})$';

		// c/o BFPO numbers
		$pcexp[5] = '^(bfpo)(c\/o[0-9]{1,3})$';

		// Load up the string to check, converting into lowercase and removing spaces
		$postcode = strtolower($toCheck);
		$postcode = str_replace (' ', '', $postcode);

		// Assume we are not going to find a valid postcode
		$valid = false;

		// Check the string against the six types of postcodes
		foreach ($pcexp as $regexp) {

			if (ereg($regexp,$postcode, $matches)) {

				// Load new postcode back into the form element
				$toCheck = strtoupper ($matches[1] . ' ' . $matches [2]);

				// Take account of the special BFPO c/o format
				$toCheck = ereg_replace ('C\/O', 'c/o ', $toCheck);

				// Remember that we have found that the code is valid and break from loop
				$valid = true;
				break;
			}
		}

		if ($valid){return true;} else {return false;};
	}

	/**
	 * Format the postcode according to the country and length of the postcode
	 *
	 * @param   string	postcode
	 * @param	string	country
	 * @return  string	formatted postcode
	 */
	public static function format_postcode( $postcode, $country ) {
		$postcode = strtoupper(trim($postcode));
		$postcode = trim(preg_replace('/[\s]/', '', $postcode));

		if ($country=='GB') :
			if (strlen($postcode)==7)
				$postcode = substr_replace($postcode, ' ', 4, 0);
			else
				$postcode = substr_replace($postcode, ' ', 3, 0);
		endif;
		return $postcode;
	}

}