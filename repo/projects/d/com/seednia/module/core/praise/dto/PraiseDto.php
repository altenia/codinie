<?php/**
 * This file is part of Quantilize.
 *
 * @author    Young Suk <yourmail@mail.com>
 * @version   0.1
 */
/**
 * The model class that access the table PraiseDto 
 */
class PraiseDto {

	/** The field of type long **/
	public $sid;
	/** The field of type string **/
	public $domain_id;
	/** The field of type class **/
	public $creator_dto;
	/** The field of type class **/
	public $support_dto;

	/**
	 * Validates 
	 */
	public static validate() {
	}

	/**
	* Load a record from persistent store
	*/
	public static load(sid) {
	}
}
