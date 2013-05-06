/**
 * This file is DDL for Quantilize.
 *
 * @author    Young Suk <yourmail@email.com>
 * @version   0.1
 */
 
CREATE TABLE PraiseDto (
	sid LONG NOT NULL,
	domain_id VARCHAR(64),
	creator_dto class,
	support_dto class
 
)
 
CREATE TABLE PraiseSupportDto (
	sid LONG NOT NULL,
	domain_id VARCHAR(64),
	praise_dto class
 
)

