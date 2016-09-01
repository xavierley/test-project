#
# Table structure for table 'tx_eannuaires_domain_model_champbdd'
#

DROP TABLE IF EXISTS tx_eannuaires_domain_model_champbdd;
CREATE TABLE tx_eannuaires_domain_model_champbdd (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	title varchar(255) DEFAULT '' NOT NULL,
	sorting int(10) DEFAULT '0' NOT NULL,
	label varchar(255) DEFAULT '' NOT NULL,
	tablabel varchar(255) DEFAULT '' NOT NULL,
	typeannuaire varchar(255) DEFAULT '' NOT NULL,
	                
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,
	
	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(255) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,

	t3_origuid int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid),
	KEY language (l10n_parent,sys_language_uid)
);

--
-- Contenu de la table `tx_eannuaires_domain_model_champbdd`
--

INSERT INTO  tx_eannuaires_domain_model_champbdd  VALUES (1, 1, 'title', -1, 'video.title', '', '1', 1458200956, 1458200867, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, '');
INSERT INTO  tx_eannuaires_domain_model_champbdd  VALUES (2, 1, 'description', 2, 'video.description', '', '1', 1458200956, 1458200887, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, '');
INSERT INTO  tx_eannuaires_domain_model_champbdd  VALUES (3, 1, 'categories', 3, 'video.theme', '', '1', 1458200956, 1458200899, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, '');
INSERT INTO  tx_eannuaires_domain_model_champbdd  VALUES (4, 1, 'cell', 4, 'video.duree', '', '1', 1458200956, 1458200911, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, '');
INSERT INTO  tx_eannuaires_domain_model_champbdd  VALUES (5, 1, 'beneficiaire', 5, 'video.iframe', '', '1', 1458200956, 1458200924, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, '');
INSERT INTO  tx_eannuaires_domain_model_champbdd  VALUES (6, 1, 'image', 6, 'video.image', '', '1', 1458200956, 1458200952, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, '');
INSERT INTO  tx_eannuaires_domain_model_champbdd  VALUES (7, 1, 'documents', 7, 'video.file', '', '1', 1458200956, 1458200952, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, '');

-- --------------------------------------------------------

#
# Table structure for table 'tx_eannuaires_domain_model_typeannuaire'
#

DROP TABLE IF EXISTS tx_eannuaires_domain_model_typeannuaire;
CREATE TABLE tx_eannuaires_domain_model_typeannuaire (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	title varchar(255) DEFAULT '' NOT NULL,
	                
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(10) DEFAULT '0' NOT NULL,
	
	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(255) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,

	t3_origuid int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid),
	KEY language (l10n_parent,sys_language_uid)
);

--
-- Contenu de la table `tx_eannuaires_domain_model_typeannuaire`
--

INSERT INTO tx_eannuaires_domain_model_typeannuaire VALUES (1, 1, 'videos', 1458200867, 1458200867, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, '');

