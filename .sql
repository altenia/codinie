/**
 * This file is DDL for Quantilize.
 *
 * @author    Young Suk <yourmail@email.com>
 * @version   0.1
 */
 
CREATE TABLE ProjectDto (
	sid LONG NOT NULL,
	domain_id VARCHAR(64),
	create_dt DATETIME,
	modify_counter INT,
	last_modified_dt DATETIME,
	uuid VARCHAR(128),
	id VARCHAR(64),
	name VARCHAR(255),
	goal TEXT,
	description TEXT,
	success_criteria TEXT,
	challenges TEXT,
	plan_start_date DATETIME,
	actual_start_date DATETIME,
	plan_end_date DATETIME,
	actual_end_date DATETIME,
	looking_for INT,
	status INT,
	privacy_level INT,
	num_cheers INT,
	sys_status INT,
	required_skill_tag_names_csv TEXT,
	success_level INT,
	evaluation_positive TEXT,
	evaluation_negative TEXT,
	improvements TEXT,
	params_text TEXT,
	creator_dto ,
	owner_dto ,
	category_dto ,
	profile_image 
 
)
 
CREATE TABLE ProjectTaggingDto (
	sid LONG NOT NULL,
	create_dt DATETIME,
	modify_counter INT,
	last_modified_dt DATETIME,
	weight INT DEFAULT '1',
	creator_dto 
 
)
 
CREATE TABLE RequiredSkillTaggingDto (
	sid LONG NOT NULL,
	create_dt DATETIME,
	modify_counter INT,
	last_modified_dt DATETIME,
	weight INT DEFAULT '1',
	creator_dto 
 
)
 
CREATE TABLE MemberDto (
	sid LONG NOT NULL,
	create_dt DATETIME,
	modify_counter INT,
	last_modified_dt DATETIME,
	status INT,
	type VARCHAR(24),
	role VARCHAR(127),
	time_commitment INT,
	description VARCHAR(255),
	last_activity_dt DATETIME,
	params_text VARCHAR(255),
	creator_dto ,
	project_dto ,
	user_dto 
 
)
 
CREATE TABLE MemberRequestDto (
	sid LONG NOT NULL,
	create_dt DATETIME,
	modify_counter INT,
	last_modified_dt DATETIME,
	uuid VARCHAR(128),
	status INT,
	type VARCHAR(24),
	role VARCHAR(127),
	time_commitment INT,
	description VARCHAR(255),
	decline_reason TEXT,
	params_text VARCHAR(255),
	creator_dto ,
	project_dto ,
	user_dto 
 
)

