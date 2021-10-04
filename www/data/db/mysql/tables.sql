CREATE TABLE IF NOT EXISTS requests_seq
(
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY
);

CREATE TABLE IF NOT EXISTS `mqenv_imported_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
    `proj` varchar(20) DEFAULT NULL,
  `impfile` varchar(256) NOT NULL,
  `impobjects` int(20) NOT NULL DEFAULT '0',
  `impdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `impby` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `env_deployments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `proj` varchar(20) DEFAULT NULL,
  `packuid` varchar(16) DEFAULT NULL,
  `deplobjects` int(20) NOT NULL DEFAULT '0',
  `depldate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deplby` varchar(50) NOT NULL DEFAULT '',
  `depltype` INT(1) DEFAULT NULL,
  `deplenv` VARCHAR(80) DEFAULT NULL,
  `deplin` VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mqenv_mq_authrec` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
   `proj` varchar(20) DEFAULT NULL,
  `qmgr` varchar(100) NOT NULL,
  `objname` varchar(200) NOT NULL,
  `objtype` varchar(50) NOT NULL,
  `objdata` longtext NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lockedby` varchar(50) NOT NULL DEFAULT '',
  `projinfo` text NULL,
  `jobrun` INT(1) DEFAULT NULL,
  `jobid` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mqenv_mq_cert` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
   `proj` varchar(20) DEFAULT NULL,
  `qmgr` varchar(100) NOT NULL,
  `objname` varchar(200) NOT NULL,
  `objtype` varchar(50) NOT NULL,
  `objdata` longtext NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lockedby` varchar(50) NOT NULL DEFAULT '',
  `projinfo` text NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mqenv_mq_channels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
   `proj` varchar(20) DEFAULT NULL,
  `qmgr` varchar(100) NOT NULL,
  `objname` varchar(200) NOT NULL,
  `objtype` varchar(50) NOT NULL,
  `objdata` longtext NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lockedby` varchar(50) NOT NULL DEFAULT '',
  `projinfo` text NULL,
  `jobrun` INT(1) DEFAULT NULL,
  `jobid` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mqenv_mq_clusters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
   `proj` varchar(20) DEFAULT NULL,
  `qmgr` varchar(100) NOT NULL,
  `objname` varchar(200) NOT NULL,
  `objtype` varchar(50) NOT NULL,
  `objdata` longtext NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lockedby` varchar(50) NOT NULL DEFAULT '',
  `projinfo` text NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mqenv_mq_dlqh` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
   `proj` varchar(20) DEFAULT NULL,
  `qmgr` varchar(100) NOT NULL,
  `objname` varchar(200) NOT NULL,
  `objtype` varchar(50) NOT NULL,
  `objdata` longtext NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lockedby` varchar(50) NOT NULL DEFAULT '',
  `projinfo` text NULL,
  `jobrun` INT(1) DEFAULT NULL,
  `jobid` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mqenv_mq_nl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
   `proj` varchar(20) DEFAULT NULL,
  `qmgr` varchar(100) NOT NULL,
  `objname` varchar(200) NOT NULL,
  `objtype` varchar(50) NOT NULL,
  `objdata` longtext NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lockedby` varchar(50) NOT NULL DEFAULT '',
  `projinfo` text NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mqenv_mq_prepost` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
   `proj` varchar(20) DEFAULT NULL,
  `qmgr` varchar(100) NOT NULL,
  `objname` varchar(200) NOT NULL,
  `objtype` varchar(50) NOT NULL,
  `objdata` longtext NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lockedby` varchar(50) NOT NULL DEFAULT '',
  `projinfo` text NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mqenv_mq_process` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
   `proj` varchar(20) DEFAULT NULL,
  `qmgr` varchar(100) NOT NULL,
  `objname` varchar(200) NOT NULL,
  `objtype` varchar(50) NOT NULL,
  `objdata` longtext NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lockedby` varchar(50) NOT NULL DEFAULT '',
  `projinfo` text NULL,
  `jobrun` INT(1) DEFAULT NULL,
  `jobid` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mqenv_mq_qm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
   `proj` varchar(20) DEFAULT NULL,
  `qmgr` varchar(100) NOT NULL,
  `objname` varchar(200) NOT NULL,
  `objtype` varchar(50) NOT NULL,
  `objdata` longtext NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lockedby` varchar(50) NOT NULL DEFAULT '',
  `projinfo` text NULL,
  `jobrun` INT(1) DEFAULT NULL,
  `jobid` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mqenv_mq_queues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
   `proj` varchar(20) DEFAULT NULL,
  `qmgr` varchar(100) NOT NULL,
  `objname` varchar(200) NOT NULL,
  `objtype` varchar(50) NOT NULL,
  `objdata` longtext NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lockedby` varchar(50) NOT NULL DEFAULT '',
  `projinfo` text NULL,
  `jobrun` INT(1) DEFAULT NULL,
  `jobid` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `tibco_obj` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
   `proj` varchar(20) DEFAULT NULL,
  `srv` varchar(100) NOT NULL,
  `objname` varchar(200) NOT NULL,
  `objtype` varchar(50) NOT NULL,
  `objdata` text NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `projinfo` text NULL,
  `jobrun` INT(1) DEFAULT NULL,
  `jobid` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `tibco_acl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
   `proj` varchar(20) DEFAULT NULL,
  `srv` varchar(100) NOT NULL,
  `objtype` varchar(10) NOT NULL,
  `objname` varchar(200) NOT NULL,
  `acltype` varchar(10) NOT NULL,
  `aclname` varchar(200) NOT NULL,
  `perm` varchar(255)  NOT NULL DEFAULT '[]',
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `projinfo` text NULL,
  `jobrun` INT(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mqenv_mq_service` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
   `proj` varchar(20) DEFAULT NULL,
  `qmgr` varchar(100) NOT NULL,
  `objname` varchar(200) NOT NULL,
  `objtype` varchar(50) NOT NULL,
  `objdata` longtext NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lockedby` varchar(50) NOT NULL DEFAULT '',
  `projinfo` text NULL,
  `jobrun` INT(1) DEFAULT NULL,
  `jobid` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mqenv_mq_subs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
   `proj` varchar(20) DEFAULT NULL,
  `qmgr` varchar(100) NOT NULL,
  `objname` varchar(200) NOT NULL,
  `objtype` varchar(50) NOT NULL,
  `objdata` longtext NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lockedby` varchar(50) NOT NULL DEFAULT '',
  `projinfo` text NULL,
  `jobrun` INT(1) DEFAULT NULL,
  `jobid` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mqenv_mq_topics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
   `proj` varchar(20) DEFAULT NULL,
  `qmgr` varchar(100) NOT NULL,
  `objname` varchar(200) NOT NULL,
  `objtype` varchar(50) NOT NULL,
  `objdata` longtext NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lockedby` varchar(50) NOT NULL DEFAULT '',
  `projinfo` text NULL,
  `jobrun` INT(1) DEFAULT NULL,
  `jobid` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mqenv_vars` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
   `proj` varchar(20) DEFAULT NULL,
  `varname` varchar(100) NOT NULL UNIQUE,
  `varvalue` varchar(255) NOT NULL,
  `isarray` int(1) NOT NULL DEFAULT '0',
  `tags` VARCHAR(150) NULL DEFAULT NULL,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mqenv_mqfte` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
   `proj` varchar(20) DEFAULT NULL,
  `fteid` varchar(50) NOT NULL,
  `mqftetype` varchar(10) NOT NULL,
  `regex` int(1) NOT NULL DEFAULT '0',
  `tags` varchar(255) NOT NULL DEFAULT '',
  `mqftename` varchar(255) NOT NULL DEFAULT '',
  `batchsize` int(11) NOT NULL DEFAULT '1',
  `sourceagt` varchar(255) NOT NULL DEFAULT '',
  `sourceagtqmgr` varchar(255) NOT NULL DEFAULT '',
  `destagt` varchar(255) NOT NULL DEFAULT '',
  `destagtqmgr` varchar(255) NOT NULL DEFAULT '',
  `sourcedisp` varchar(10) NOT NULL DEFAULT 'leave',
  `textorbinary` varchar(10) NOT NULL DEFAULT '',
  `sourceccsid` varchar(20) NOT NULL DEFAULT '',
  `destccsid` varchar(20) NOT NULL DEFAULT '',
  `sourcedir` varchar(255) NOT NULL DEFAULT '',
  `sourcefile` varchar(255) NOT NULL DEFAULT '',
  `sourcequeue` varchar(255) NOT NULL DEFAULT '',
  `destdir` varchar(255) NOT NULL DEFAULT '',
  `destfile` varchar(255) NOT NULL DEFAULT '${FileName}',
  `destqueue` varchar(255) NOT NULL DEFAULT '',
  `postsourcecmd` varchar(255) NOT NULL DEFAULT '',
  `postsourcecmdarg` varchar(255) NOT NULL DEFAULT '',
  `postdestcmd` varchar(255) NOT NULL DEFAULT '',
  `postdestcmdarg` varchar(255) NOT NULL DEFAULT '',
  `info` varchar(255) DEFAULT NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lockedby` varchar(50) NOT NULL DEFAULT '',
  `jobrun` INT(1) DEFAULT NULL,
  `jobid` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mqjms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
   `proj` varchar(20) DEFAULT NULL,
  `bindings` text NULL,
  `connfactory` text NULL,
  `objects` longtext NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lockedby` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
 `tags` varchar(255) DEFAULT NULL,
  `sname` varchar(50) NOT NULL DEFAULT '0',
  `projnum` varchar(20) DEFAULT NULL,
  `reqapp` varchar(10) DEFAULT NULL,
  `projapproved` int(1) NOT NULL DEFAULT '0',
  `projconfirmed` int(1) NOT NULL DEFAULT '0',
  `reqname` varchar(100) NOT NULL,
  `reqlatname` varchar(100) DEFAULT NULL,
  `efforts` varchar(20) DEFAULT NULL,
  `info` varchar(150) NOT NULL DEFAULT '',
  `reqtype` varchar(10) DEFAULT NULL,
  `reqfile` varchar(256) NOT NULL DEFAULT '',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deadline` date NOT NULL,
  `deadlinedeployed` date NOT NULL,
  `modified` datetime NOT NULL DEFAULT '2001-01-01 00:00:00',
  `assigned` varchar(100) DEFAULT NULL,
  `wfstep` varchar(100) DEFAULT NULL,
  `wfutype` varchar(100) DEFAULT NULL,
  `wfunit` varchar(100) DEFAULT NULL,
  `wfbstep` varchar(10) DEFAULT NULL,
  `requser` varchar(100) NOT NULL,
  `deployed` int(1) NOT NULL DEFAULT '0',
  `deployed_time` datetime NOT NULL DEFAULT '2001-01-01 00:00:00',
  `deployed_by` varchar(100) NOT NULL DEFAULT '',
  `priority` int(2) DEFAULT '0',
  `wid` varchar(8) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sname` (`sname`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `tracking` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `appid` varchar(100) DEFAULT NULL,
  `reqid` varchar(100) DEFAULT NULL,
  `srvid` varchar(100) DEFAULT NULL,
  `appsrvid` varchar(100) DEFAULT NULL,
  `wfid` varchar(50) DEFAULT NULL,
  `projid` varchar(100) DEFAULT NULL,
  `who` varchar(100) DEFAULT NULL,
  `whoid` varchar(50) NOT NULL,
  `what` varchar(255) NOT NULL,
  `trackdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(200) NOT NULL DEFAULT '',
  `ldap` int(1) NOT NULL DEFAULT 0,
  `ldapserver` varchar(100) DEFAULT NULL,
  `mainuser` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL,
  `pwd` varchar(220) NOT NULL DEFAULT '',
  `users_ip` varchar(200) NOT NULL DEFAULT '',
  `fullname` varchar(100) DEFAULT NULL,
  `active` int(1) NOT NULL DEFAULT 0,
  `user_level` int(2) NOT NULL DEFAULT 1,
  `wsteps` varchar(200) DEFAULT NULL,
  `ugroups` varchar(255) DEFAULT NULL,
  `effgroup` varchar(50) DEFAULT NULL,
  `ckey` varchar(220) NOT NULL DEFAULT '',
  `ctime` varchar(220) NOT NULL DEFAULT '',
  `user_online` int(1) NOT NULL DEFAULT 0,
  `user_online_show` int(1) NOT NULL DEFAULT 1,
  `user_activity_show` int(1) NOT NULL DEFAULT 0,
  `online_time` datetime NOT NULL DEFAULT '2001-01-01 00:00:00',
  `avatar` varchar(150) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `utitle` VARCHAR(100) DEFAULT NULL,
  `uaddress` varchar(100) DEFAULT NULL,
  `modules` varchar(255) DEFAULT NULL,
  `wid` varchar(250) DEFAULT NULL,
  `appid` varchar(255) DEFAULT NULL,
  `pjid` varchar(255) DEFAULT NULL,
  `navfav` varchar(200) NOT NULL DEFAULT '[]',
  PRIMARY KEY (`id`),
  UNIQUE KEY `useruuid` (`uuid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf16 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `user_visits` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `month` date NOT NULL DEFAULT '2001-01-01',
  `views` int(200) NOT NULL DEFAULT '0',
  `mainuser` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `search` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `what` varchar(200) NOT NULL DEFAULT '',
  `swhere` varchar(250) DEFAULT NULL,
  `tags` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `iibenv_flows` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `projid` varchar(50) NOT NULL,
  `flowid` varchar(32) NOT NULL,
  `flowname` varchar(256) NOT NULL,
  `info` text NULL,
  `insvn` int(1) NOT NULL DEFAULT '0',
  `reqinfo` text NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lockedby` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `flowid` (`flowid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `user_failure` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fail_type` varchar(10) NOT NULL,
  `mainuser` varchar(100) NOT NULL,
  `what` varchar(100) NOT NULL DEFAULT '',
  `fail_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `env_appservers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
   `proj` varchar(20) DEFAULT NULL,
  `serv_type` varchar(10) NOT NULL,
  `tags` varchar(255) NOT NULL DEFAULT '',
  `appsrvname` varchar(100) DEFAULT NULL,
  `serverdns` varchar(255) NOT NULL,
  `serverip` varchar(100) DEFAULT NULL,
  `serverid` varchar(64) DEFAULT NULL,
  `port` int(11) NOT NULL DEFAULT '0',
  `qmname` varchar(255) NOT NULL DEFAULT '',
  `qmchannel` varchar(255) NOT NULL DEFAULT '',
  `agentname` varchar(255) NOT NULL DEFAULT '',
  `brokername` varchar(255) NOT NULL DEFAULT '',
  `execgname` varchar(255) NOT NULL DEFAULT '',
  `info` varchar(255) DEFAULT NULL,
  `sslenabled` INT(1) NOT NULL DEFAULT '0',
  `sslkey` VARCHAR(200) DEFAULT NULL,
  `sslpass` VARCHAR(100) DEFAULT NULL,
  `sslcipher` VARCHAR(100) DEFAULT NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `srvuser` VARCHAR(200) DEFAULT NULL,
  `srvpass` VARCHAR(255) DEFAULT NULL,
  `lockedby` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `knowledge_categories` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
  `public` int(2) NOT NULL DEFAULT '0',
  `category` varchar(255) NOT NULL,
  `catname` varchar(255) NOT NULL,
  `accgroups` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `knowledge_info` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_latname` varchar(255) NOT NULL,
  `cat_name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `cattext` mediumtext NOT NULL,
  `catdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `views` int(50) NOT NULL DEFAULT '0',
  `catlikes` int(50) NOT NULL DEFAULT '0',
  `public` int(1) NOT NULL DEFAULT '1',
  `accgroups` varchar(255) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `tags` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `ldap_config` (
 `id` int(20) NOT NULL AUTO_INCREMENT,
  `ldapserver` varchar(250) NOT NULL,
  `ldapport` int(5) NOT NULL DEFAULT '389',
  `ldaptree` varchar(250) NOT NULL,
  `ldapgtree` varchar(250) NOT NULL,
  `ldapinfo` varchar(250) NOT NULL,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `requests_approval` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
    `reqid` varchar(15) NOT NULL,
  `reqapp` varchar(10) DEFAULT NULL,
  `projid` varchar(50) NOT NULL,
  `appruser` varchar(50) NOT NULL DEFAULT '',
  `apprfullname` varchar(200) NOT NULL DEFAULT '',
  `reqdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `apprdate` date NOT NULL DEFAULT '2001-01-01',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `requests_comments` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
   `reqid` varchar(15) NOT NULL,
  `commuser` varchar(50) NOT NULL DEFAULT '',
  `commfullname` varchar(200) NOT NULL DEFAULT '',
  `commdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `commtext` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `requests_data` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `reqid` varchar(15) NOT NULL,
  `reqtype` varchar(10) NOT NULL,
  `reqdata` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `env_firewall` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `proj` varchar(20) DEFAULT NULL,
  `tags` varchar(255) NOT NULL DEFAULT '',
  `port` int(10) NOT NULL,
  `srcip` varchar(80) NOT NULL,
  `destip` varchar(80) NOT NULL,
  `srcdns` varchar(120) NOT NULL,
  `destdns` varchar(120) NOT NULL,
  `info` varchar(255) DEFAULT NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lockedby` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `requests_deployments` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
   `reqid` varchar(15) NOT NULL,
  `reqapp` varchar(10) DEFAULT NULL,
  `projid` varchar(50) DEFAULT NULL,
  `consuser` varchar(50) NOT NULL,
  `depluser` varchar(50) DEFAULT NULL,
  `deployedin` varchar(255) DEFAULT NULL,
  `prodnum` varchar(50) DEFAULT NULL,
  `prodinfo` varchar(255) DEFAULT NULL,
  `consdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `depldate` date NOT NULL DEFAULT '2001-01-01',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `requests_efforts` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `reqid` varchar(15) NOT NULL,
  `reqapp` varchar(10) DEFAULT NULL,
  `effuser` varchar(50) NOT NULL,
  `efffullname` varchar(200) DEFAULT NULL,
  `effdays` varchar(8) DEFAULT NULL,
  `effdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `requests_confirmation` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
   `reqid` varchar(15) NOT NULL,
  `projid` varchar(50) NOT NULL,
  `confuser` varchar(50) NOT NULL DEFAULT '',
  `conffullname` varchar(200) NOT NULL DEFAULT '',
  `reqdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `confdate` date NOT NULL DEFAULT '2001-01-01',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `config_app_codes` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `tags` varchar(255) DEFAULT NULL,
  `appcode` varchar(20) DEFAULT NULL,
  `appname` varchar(200) DEFAULT NULL,
  `appinfo` text DEFAULT NULL,
  `appusers` text DEFAULT NULL,
  `owner` varchar(100) DEFAULT NULL,
  `appcreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `config_projects` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `tags` varchar(255) DEFAULT NULL,
  `projcode` varchar(20) DEFAULT NULL,
  `projyear` year(4) NOT NULL DEFAULT 0000,
  `projname` varchar(200) DEFAULT NULL,
  `projinfo` text DEFAULT NULL,
  `projstatus` int(1) DEFAULT 0,
  `projstartdate` date DEFAULT NULL,
  `projduedate` date DEFAULT NULL,
  `projusers` text DEFAULT NULL,
  `budget` float(20,2) NOT NULL DEFAULT 0.00,
  `budgetspent` float(20,2) NOT NULL DEFAULT 0.00,
  `owner` varchar(100) DEFAULT NULL,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `requests_efforts_all` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `reqid` varchar(15) NOT NULL,
  `effreq` varchar(8) DEFAULT NULL,
  `effappr` varchar(8) DEFAULT NULL,
  `effdata` TEXT,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `config_diagrams` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `tags` varchar(255) DEFAULT NULL,
  `reqid` varchar(20) NOT NULL,
  `appcode` varchar(20) DEFAULT NULL,
  `srvlist` varchar(250) DEFAULT NULL,
  `appsrvlist` varchar(250) DEFAULT NULL,
  `desid` varchar(50) DEFAULT NULL,
  `desname` varchar(150) CHARACTER SET utf16 DEFAULT NULL,
  `desdate` timestamp NOT NULL DEFAULT current_timestamp(),
  `desuser` varchar(100) NOT NULL,
  `imgdata` longtext CHARACTER SET utf16 DEFAULT NULL,
  `xmldata` longtext NOT NULL,
  `public` int(1) NOT NULL DEFAULT 1,
  `accgroups` varchar(255) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
   PRIMARY KEY (`id`),
   UNIQUE KEY `desid` (`desid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `calendar` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `mainuser` varchar(100) NOT NULL DEFAULT '',
  `subject` varchar(255) DEFAULT NULL,
  `subj_id` VARCHAR(150) DEFAULT NULL,
  `date_start` datetime NOT NULL DEFAULT '2001-01-01 00:00:00',
  `date_end` datetime NOT NULL DEFAULT '2001-01-01 00:00:00',
  `allDay` tinyint(1) NOT NULL DEFAULT '0',
  `time_period` int(10) NOT NULL DEFAULT '0',
  `color` varchar(20) NOT NULL DEFAULT '#0D90B9',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `mainuser` varchar(100) NOT NULL DEFAULT '',
  `taskinfo` varchar(200) NOT NULL,
  `taskstate` varchar(1) NOT NULL DEFAULT '0',
  `date_start` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_end` datetime NOT NULL DEFAULT '2001-01-01 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `user_groups` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `group_latname` varchar(150) NOT NULL,
  `group_name` varchar(150) NOT NULL,
  `group_email` varchar(150) DEFAULT NULL,
  `group_avatar` varchar(150) DEFAULT NULL,
  `users` varchar(255)  NOT NULL DEFAULT '[]',
  `wid` varchar(255) DEFAULT NULL,
  `appid` varchar(255) DEFAULT NULL,
  `pjid` varchar(255) DEFAULT NULL,
  `wsteps` varchar(255) DEFAULT NULL,
  `acclist` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `requests_tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
   `reqid` varchar(50) NOT NULL DEFAULT '0',
  `taskname` varchar(100) NOT NULL,
  `info` varchar(200) NOT NULL DEFAULT '',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deadline` date NOT NULL,
  `modified` timestamp NOT NULL DEFAULT '2001-01-01 00:00:00',
  `taskby` varchar(100) NOT NULL DEFAULT '',
  `taskto` varchar(100) NOT NULL DEFAULT '',
  `assigned` varchar(100) NOT NULL DEFAULT '',
  `taskstatus` varchar(20) NOT NULL DEFAULT 'new',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `config_workflows` (
 `id` int(20) NOT NULL AUTO_INCREMENT,
  `wid` varchar(8) NOT NULL,
  `wname` varchar(250) DEFAULT NULL,
  `wcreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` DATETIME NOT NULL DEFAULT '2001-01-01 00:00:00',
  `wuser_updated` varchar(100) NOT NULL,
  `wowner` varchar(100) NOT NULL,
  `winfo` varchar(250) DEFAULT NULL,
  `wdata` text DEFAULT NULL,
  `wgroups` text,
  `formid` varchar(8) DEFAULT NULL,
  `haveappr` INT(1) DEFAULT '0',
  `haveconf` INT(1) DEFAULT '0',
  `wtype` varchar(8) DEFAULT NULL,
  `wfcost` FLOAT(10,1) DEFAULT NULL,
  `wfcurcost` FLOAT(20,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `config_agents` (
 `id` int(20) NOT NULL AUTO_INCREMENT,
   `uniqid` varchar(60) NOT NULL,
  `agent_name` varchar(150) NOT NULL,
  `agent_dns` varchar(150) NOT NULL,
  `agent_ip` varchar(20) NOT NULL,
  `agent_platform` varchar(20) NOT NULL,
  `agent_resources` varchar(250) NOT NULL DEFAULT '[]',
  `agent_disks` varchar(250) NOT NULL DEFAULT '[]',
  `date_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `processes` text,
  `uptime` varchar(60) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `env_jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jobid` varchar(50) NOT NULL,
  `proj` varchar(20) DEFAULT NULL,
  `reqid` varchar(100) DEFAULT NULL,
  `srv` varchar(100) NOT NULL,
  `env` VARCHAR(100) NOT NULL,
  `jobname` varchar(255) NOT NULL,
  `deplenv` VARCHAR(80) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lrun` TIMESTAMP NOT NULL DEFAULT '2001-01-01 00:00:00',
  `nrun` TIMESTAMP NOT NULL DEFAULT '2001-01-01 00:00:00',
  `runby` varchar(50) NOT NULL DEFAULT '',
  `jobstatus` INT(1) DEFAULT NULL,
  `jenabled` INT(1) NOT NULL DEFAULT '1',
  `jobdata` text DEFAULT NULL,
  `jobtype` INT(1) DEFAULT '1',
  `objtype` VARCHAR(100) DEFAULT NULL,
  `objid` INT(50) DEFAULT NULL,
  `objname` varchar(200) DEFAULT NULL,
  `connstr` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `env_dns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `proj` varchar(20) DEFAULT NULL,
  `tags` varchar(255) NOT NULL DEFAULT '',
  `dnsname` varchar(255) NOT NULL,
  `dnsserv` varchar(255) DEFAULT NULL,
  `dnsservid` varchar(64) DEFAULT NULL,
  `ttl` int(10) NOT NULL,
  `dnsclass` varchar(10) DEFAULT NULL,
  `dnstype` varchar(10) NOT NULL,
  `dnsrecord` text,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `env_servers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tags` varchar(255) NOT NULL DEFAULT '',
  `serverid` varchar(64) NOT NULL,
  `serverdns` varchar(255) NOT NULL,
  `servertype` varchar(80) NOT NULL,
  `serverip` varchar(100) DEFAULT NULL,
  `serverdisc` text DEFAULT NULL,
  `servernet` text DEFAULT NULL,
  `serverprog` MEDIUMTEXT DEFAULT NULL,
  `servupdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updperiod` int(10) NOT NULL DEFAULT '0',
  `groupid` varchar(150) DEFAULT NULL,
  `pluid` varchar(20) DEFAULT NULL,
  `srvpublic` INT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `external_files` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `tags` varchar(255) DEFAULT NULL,
  `reqid` varchar(50) NOT NULL,
  `appcode` varchar(20) DEFAULT NULL,
  `srvlist` varchar(250) DEFAULT NULL,
  `appsrvlist` varchar(250) DEFAULT NULL,
  `filetype` varchar(20) DEFAULT NULL,
  `fileid` varchar(100) DEFAULT NULL,
  `file_size` int(40) DEFAULT NULL,
  `file_name` varchar(200) NOT NULL,
  `filedate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `impuser` varchar(100) NOT NULL,
  `filelink` varchar(500) DEFAULT NULL,
   PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf16;

  CREATE TABLE IF NOT EXISTS `env_packages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tags` varchar(255) DEFAULT NULL,
  `proj` varchar(20) DEFAULT NULL,
  `packname` varchar(80) NOT NULL,
  `srvtype` varchar(20) DEFAULT NULL,
  `packuid` varchar(16) DEFAULT NULL,
  `deployedin` varchar(255) DEFAULT NULL,
  `pkgobjects` mediumtext NULL,
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` datetime NOT NULL DEFAULT '2001-01-01 00:00:00',
  `modified` int(1) NOT NULL DEFAULT '0',
  `gitprepared` int(1) NOT NULL DEFAULT '0',
  `created_by` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

  CREATE TABLE IF NOT EXISTS `env_gituploads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gittype` varchar(20) DEFAULT NULL,
  `commitid` varchar(150) DEFAULT NULL,
  `packuid` varchar(150) DEFAULT NULL,
  `fileplace` varchar(255) DEFAULT NULL,
  `steptime` timestamp NOT NULL DEFAULT current_timestamp(),
  `steptype` varchar(100) DEFAULT NULL,
  `stepuser` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `env_places` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tags` varchar(255) DEFAULT NULL,
  `proj` varchar(20) DEFAULT NULL,
  `placename` varchar(80) NOT NULL,
  `plregion` varchar(5) DEFAULT NULL,
  `plcity` varchar(100) DEFAULT NULL,
  `pltype` varchar(30) DEFAULT NULL,
  `pluid` varchar(20) DEFAULT NULL,
  `plcontact` varchar(255) DEFAULT NULL,
  `plused` int(10)  NOT NULL DEFAULT '0',
  `created_by` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `users_recent` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `uuid` int(20) NOT NULL,
  `recentdata` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `env_jobs_mq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jobid` varchar(50) NOT NULL,
  `proj` varchar(20) DEFAULT NULL,
  `srv` varchar(100) NOT NULL,
  `qmgr` varchar(100) NOT NULL,
  `env` VARCHAR(100) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lrun` TIMESTAMP NOT NULL DEFAULT '2001-01-01 00:00:00',
  `nrun` TIMESTAMP NOT NULL DEFAULT '2001-01-01 00:00:00',
  `owner` varchar(50) NOT NULL DEFAULT '',
  `jobstatus` INT(1) NOT NULL DEFAULT '0',
  `jobrepeat` INT(1) NOT NULL,
  `connstr` text DEFAULT NULL,
  `qminv` mediumtext DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mon_jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `monid` varchar(50) DEFAULT NULL,
  `monname` varchar(50) NOT NULL,
  `appid` varchar(20) DEFAULT NULL,
  `srv` varchar(100) DEFAULT NULL,
  `monprovider` varchar(10) NOT NULL,
  `montype` int(2) NOT NULL,
  `monaltype` varchar(20) NOT NULL,
  `env` VARCHAR(20) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `owner` varchar(50) NOT NULL DEFAULT '',
  `monsoft` varchar(20) NOT NULL,
  `jobstatus` INT(1) NOT NULL DEFAULT '0',
  `monaemail` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mon_alerts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `srv` varchar(100) DEFAULT NULL,
  `appsrvid` varchar(150) DEFAULT NULL,
  `alerttype` varchar(20) DEFAULT NULL,
  `loglevel` varchar(20) DEFAULT NULL,
  `errorcode` varchar(50) DEFAULT NULL,
  `errorplace` varchar(255) DEFAULT NULL,
  `errormessage` varchar(255) DEFAULT NULL,
  `appsrv` varchar(255) DEFAULT NULL,
  `appobject` varchar(255) DEFAULT NULL,
  `alerttime` datetime NOT NULL DEFAULT '2001-01-01 00:00:00',
  `reported` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `config_projtempl` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `tags` varchar(255) DEFAULT NULL,
  `appcode` varchar(20) DEFAULT NULL,
  `templcode` varchar(20) DEFAULT NULL,
  `templname` varchar(255) DEFAULT NULL,
  `templinfo` text DEFAULT NULL,
  `totalcost` float(20,2) NOT NULL DEFAULT 0.00,
  `servinfo` text DEFAULT NULL,
  `formid` varchar(255) DEFAULT NULL,
  `serviceid` varchar(255) DEFAULT NULL,
  `owner` varchar(100) DEFAULT NULL,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `config_projrequest` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `tags` varchar(255) DEFAULT NULL,
  `appcode` varchar(20) DEFAULT NULL,
  `projcode` varchar(20) DEFAULT NULL,
  `projname` varchar(255) DEFAULT NULL,
  `projinfo` text DEFAULT NULL,
  `totalcost` float(20,2) NOT NULL DEFAULT 0.00,
  `servinfo` varchar(255) DEFAULT NULL,
  `reqinfo` varchar(255) DEFAULT NULL,
  `serviceid` varchar(255) DEFAULT NULL,
  `formid` varchar(255) DEFAULT NULL,
  `projstartdate` date DEFAULT NULL,
  `projduedate` date DEFAULT NULL,
  `projstatus` int(1) DEFAULT 0,
  `owner` varchar(100) DEFAULT NULL,
  `requser` varchar(100) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `env_docimport` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `fileid` varchar(255) DEFAULT NULL,
  `importedon` timestamp NOT NULL DEFAULT current_timestamp(),
  `tags` varchar(255) NOT NULL DEFAULT '',
  `author` varchar(50) DEFAULT NULL,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Create default roles
-- madmin/admin
--

INSERT INTO `user_groups` (`id`, `group_latname`, `group_name`, `group_email`, `group_avatar`, `users`, `wid`, `appid`, `pjid`, `wsteps`, `acclist`) VALUES
(1, 'defadm', 'Default Admin Group', 'admin@local.lan', NULL, '{\"madmin\":\"Default Admin\"}', NULL, NULL, NULL, NULL, '[\"tibcoview\",\"tibcoadm\",\"pjm\",\"pja\",\"pjv\",\"unixadm\",\"unixview\",\"appadm\",\"appview\",\"ibmadm\",\"ibmview\",\"appconfig\",\"environment\",\"monview\",\"monadm\",\"designer\",\"automation\",\"smanagement\",\"smanagementadm\"]');

INSERT INTO `users` (`id`, `uuid`, `ldap`, `ldapserver`, `mainuser`, `email`, `pwd`, `users_ip`, `fullname`, `active`, `user_level`, `wsteps`, `ugroups`, `effgroup`, `ckey`, `ctime`, `user_online`, `user_online_show`, `user_activity_show`, `online_time`, `avatar`, `phone`, `utitle`, `uaddress`, `modules`, `wid`, `appid`, `pjid`, `navfav`) VALUES
(1, '365cd45fa661b5795e9747c6b416bbca', 0, NULL, 'madmin', 'admin@local.lan', '$2y$11$h4EXDS5YAx8DeQiTlO6ouOaGGBIIlTKtASzz/V7KuS37SHwc2tGfe', '', 'Default Admin', 0, 5, NULL, '{\"defadm\":\"Default admin group\"}', NULL, '', '', 0, 1, 0, '', '', '', '', NULL, NULL, NULL, '{\"test\":\"1\"}', NULL, '[\"1\"]');

INSERT INTO `knowledge_categories` (`id`, `public`, `category`, `catname`) VALUES (NULL, '1', 'documentation', 'Documentation'); 