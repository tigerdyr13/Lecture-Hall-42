#
# Table structure for table 'tx_aud42cmtscr_comments'
#
CREATE TABLE tx_aud42cmtscr_comments (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	comment text,
	fe_user_uid text,
	fe_user_name text,
	status int(11) DEFAULT '0' NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);
#
# Table structure for table 'tt_content'
#
CREATE TABLE tt_content (
    tx_aud42cmtscr_mode int(11) DEFAULT '0' NOT NULL
);