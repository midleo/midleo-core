CREATE TABLE IF NOT EXISTS mqenv_imported_files (
  id SERIAL PRIMARY KEY,
   proj varchar(20) DEFAULT NULL,
  impfile varchar(256) NOT NULL,
  impobjects NUMERIC(20) NOT NULL DEFAULT '0',
  impdate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  impby varchar(50)  DEFAULT ''
);

CREATE TABLE IF NOT EXISTS env_deployments (
  id SERIAL PRIMARY KEY,
  proj varchar(20) DEFAULT NULL,
  packuid varchar(16) DEFAULT NULL,
  deplobjects NUMERIC(20) NOT NULL DEFAULT '0',
  depldate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  deplby varchar(50)  DEFAULT '',
  depltype NUMERIC(1) DEFAULT NULL,
  deplenv NUMERIC(80) DEFAULT NULL,
  deplin NUMERIC(50) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS mqenv_mq_authrec (
  id SERIAL PRIMARY KEY,
  proj varchar(20) DEFAULT NULL,
  qmgr varchar(100) NOT NULL,
  objname varchar(200) NOT NULL,
  objtype varchar(50) NOT NULL,
  objdata text NULL,
  changed timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  lockedby varchar(50)  DEFAULT '',
  projinfo text NULL,
  jobrun INT DEFAULT NULL,
  jobid varchar(20) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS mqenv_mq_cert (
  id SERIAL PRIMARY KEY,
  proj varchar(20) DEFAULT NULL,
  qmgr varchar(100) NOT NULL,
  objname varchar(200) NOT NULL,
  objtype varchar(50) NOT NULL,
  objdata text NULL,
  changed timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  lockedby varchar(50)  DEFAULT '',
  projinfo text NULL,
  jobrun INT DEFAULT NULL,
  jobid varchar(50) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS mqenv_mq_channels (
  id SERIAL PRIMARY KEY,
  proj varchar(20) DEFAULT NULL,
  qmgr varchar(100) NOT NULL,
  objname varchar(200) NOT NULL,
  objtype varchar(50) NOT NULL,
  objdata text NULL,
  changed timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  lockedby varchar(50)  DEFAULT '',
  projinfo text NULL,
  jobrun INT DEFAULT NULL,
  jobid varchar(10) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS mqenv_mq_clusters (
  id SERIAL PRIMARY KEY,
  proj varchar(20) DEFAULT NULL,
  qmgr varchar(100) NOT NULL,
  objname varchar(200) NOT NULL,
  objtype varchar(50) NOT NULL,
  objdata text NULL,
  changed timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  lockedby varchar(50)  DEFAULT '',
  projinfo text NULL,
  jobrun INT DEFAULT NULL,
  jobid varchar(50) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS mqenv_mq_dlqh (
  id SERIAL PRIMARY KEY,
  proj varchar(20) DEFAULT NULL,
  qmgr varchar(100) NOT NULL,
  objname varchar(200) NOT NULL,
  objtype varchar(50) NOT NULL,
  objdata text NULL,
  changed timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  lockedby varchar(50)  DEFAULT '',
  projinfo text NULL,
  jobrun INT DEFAULT NULL,
  jobid varchar(50) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS mqenv_mq_nl (
  id SERIAL PRIMARY KEY,
  proj varchar(20) DEFAULT NULL,
  qmgr varchar(100) NOT NULL,
  objname varchar(200) NOT NULL,
  objtype varchar(50) NOT NULL,
  objdata text NULL,
  changed timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  lockedby varchar(50)  DEFAULT '',
  projinfo text NULL,
  jobrun INT DEFAULT NULL,
  jobid varchar(50) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS mqenv_mq_prepost (
  id SERIAL PRIMARY KEY,
  proj varchar(20) DEFAULT NULL,
  qmgr varchar(100) NOT NULL,
  objname varchar(200) NOT NULL,
  objtype varchar(50) NOT NULL,
  objdata text NULL,
  changed timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  lockedby varchar(50)  DEFAULT '',
  projinfo text NULL,
  jobrun INT DEFAULT NULL,
  jobid varchar(50) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS mqenv_mq_process (
  id SERIAL PRIMARY KEY,
  proj varchar(20) DEFAULT NULL,
  qmgr varchar(100) NOT NULL,
  objname varchar(200) NOT NULL,
  objtype varchar(50) NOT NULL,
  objdata text NULL,
  changed timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  lockedby varchar(50)  DEFAULT '',
  projinfo text NULL,
  jobrun INT DEFAULT NULL,
  jobid varchar(10) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS mqenv_mq_qm (
  id SERIAL PRIMARY KEY,
  proj varchar(20) DEFAULT NULL,
  qmgr varchar(100) NOT NULL,
  objname varchar(200) NOT NULL,
  objtype varchar(50) NOT NULL,
  objdata text NULL,
  changed timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  lockedby varchar(50)  DEFAULT '',
  projinfo text NULL,
  jobrun INT DEFAULT NULL,
  jobid varchar(10) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS mqenv_mq_queues (
  id SERIAL PRIMARY KEY,
  proj varchar(20) DEFAULT NULL,
  qmgr varchar(100) NOT NULL,
  objname varchar(200) NOT NULL,
  objtype varchar(50) NOT NULL,
  objdata text NULL,
  changed timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  lockedby varchar(50)  DEFAULT '',
  projinfo text NULL,
  jobrun INT DEFAULT NULL,
  jobid varchar(10) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS mqenv_mq_service (
  id SERIAL PRIMARY KEY,
  proj varchar(20) DEFAULT NULL,
  qmgr varchar(100) NOT NULL,
  objname varchar(200) NOT NULL,
  objtype varchar(50) NOT NULL,
  objdata text NULL,
  changed timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  lockedby varchar(50)  DEFAULT '',
  projinfo text NULL,
  jobrun INT DEFAULT NULL,
  jobid varchar(10) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS mqenv_mq_subs (
  id SERIAL PRIMARY KEY,
  proj varchar(20) DEFAULT NULL,
  qmgr varchar(100) NOT NULL,
  objname varchar(200) NOT NULL,
  objtype varchar(50) NOT NULL,
  objdata text NULL,
  changed timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  lockedby varchar(50)  DEFAULT '',
  projinfo text NULL,
  jobrun INT DEFAULT NULL,
  jobid varchar(10) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS mqenv_mq_topics (
  id SERIAL PRIMARY KEY,
  proj varchar(20) DEFAULT NULL,
  qmgr varchar(100) NOT NULL,
  objname varchar(200) NOT NULL,
  objtype varchar(50) NOT NULL,
  objdata text NULL,
  changed timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  lockedby varchar(50)  DEFAULT '',
  projinfo text NULL,
  jobrun INT DEFAULT NULL,
  jobid varchar(10) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS mqenv_vars (
  id SERIAL PRIMARY KEY,
  proj varchar(20) DEFAULT NULL,
  varname varchar(100) NOT NULL,
  varvalue varchar(255) NOT NULL,
  isarray NUMERIC(1) NOT NULL DEFAULT '0',
  tags varchar(150) NULL DEFAULT NULL,
  UNIQUE(varname)
);

CREATE TABLE IF NOT EXISTS mqenv_mqfte (
  id SERIAL PRIMARY KEY,
  proj varchar(20) DEFAULT NULL,
  fteid varchar(50) NOT NULL,
  mqftetype varchar(10) NOT NULL,
  regex NUMERIC(1) NOT NULL DEFAULT '0',
  tags varchar(255)  DEFAULT '',
  mqftename varchar(255)  DEFAULT '',
  batchsize NUMERIC(11) NOT NULL DEFAULT '1',
  sourceagt varchar(255)  DEFAULT '',
  sourceagtqmgr varchar(255)  DEFAULT '',
  destagt varchar(255)  DEFAULT '',
  destagtqmgr varchar(255)  DEFAULT '',
  sourcedisp varchar(10) NOT NULL DEFAULT 'leave',
  textorbinary varchar(10)  DEFAULT '',
  sourceccsid varchar(20)  DEFAULT '',
  destccsid varchar(20)  DEFAULT '',
  sourcedir varchar(255)  DEFAULT '',
  sourcefile varchar(255)  DEFAULT '',
  sourcequeue varchar(255)  DEFAULT '',
  destdir varchar(255)  DEFAULT '',
  destfile varchar(255) NOT NULL DEFAULT '${FileName}',
  destqueue varchar(255)  DEFAULT '',
  postsourcecmd varchar(255)  DEFAULT '',
  postsourcecmdarg varchar(255)  DEFAULT '',
  postdestcmd varchar(255)  DEFAULT '',
  postdestcmdarg varchar(255)  DEFAULT '',
  info varchar(255) DEFAULT NULL,
  changed timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  lockedby varchar(50)  DEFAULT '',
  jobrun INT DEFAULT NULL,
  jobid varchar(10) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS mqjms (
  id SERIAL PRIMARY KEY,
  proj varchar(20) DEFAULT NULL,
  bindings text NULL,
  connfactory text NULL,
  objects text NULL,
  changed timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  lockedby varchar(50)  DEFAULT ''
)  ;

CREATE TABLE IF NOT EXISTS requests (
  id SERIAL PRIMARY KEY,
  tags varchar(255) DEFAULT NULL,
  sname varchar(50) NOT NULL DEFAULT '0',
  projnum varchar(20) DEFAULT NULL,
  reqapp varchar(10) DEFAULT NULL,
  projapproved NUMERIC(1) NOT NULL DEFAULT '0',
  projconfirmed NUMERIC(1) NOT NULL DEFAULT '0',
  reqname varchar(100) NOT NULL,
  reqlatname varchar(100) DEFAULT NULL,
  efforts varchar(20) DEFAULT NULL,
  info varchar(150)  DEFAULT '',
  reqtype varchar(10) DEFAULT NULL,
  reqfile varchar(256)  DEFAULT '',
  created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  deadline date NOT NULL,
  deadlinedeployed date NOT NULL,
  modified timestamp NOT NULL DEFAULT '2001-01-01 00:00:00',
  assigned varchar(100)  DEFAULT '',
  wfstep varchar(100)  DEFAULT '',
  wfutype varchar(100)  DEFAULT '',
  wfunit varchar(100) DEFAULT '',
  wfbstep varchar(10) DEFAULT NULL,
  requser varchar(100) NOT NULL,
  deployed NUMERIC(1) NOT NULL DEFAULT '0',
  deployed_time timestamp NOT NULL DEFAULT '2001-01-01 00:00:00',
  deployed_by varchar(100)  DEFAULT '',
  priority NUMERIC(2) DEFAULT '0',
  wid varchar(8) DEFAULT NULL,
  UNIQUE(sname)
);

CREATE TABLE IF NOT EXISTS tracking (
  id SERIAL PRIMARY KEY,
  projid varchar(100) DEFAULT NULL,
  reqid varchar(100) DEFAULT NULL,
  appid varchar(100) DEFAULT NULL,
  srvid varchar(100) DEFAULT NULL,
  appsrvid varchar(100) DEFAULT NULL,
  wfid varchar(50) DEFAULT NULL,
  who varchar(100) DEFAULT NULL,
  whoid varchar(50) NOT NULL,
  what varchar(255) NOT NULL,
  trackdate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS users (
   id SERIAL PRIMARY KEY,
  uuid varchar(200)  DEFAULT '',
  ldap NUMERIC(1) NOT NULL DEFAULT '0',
  ldapserver varchar(255) DEFAULT NULL,
  mainuser varchar(50)  DEFAULT '',
  email varchar(255) NOT NULL,
  pwd varchar(220)  DEFAULT '',
  users_ip varchar(200)  DEFAULT '',
  fullname varchar(100)  DEFAULT '',
  active NUMERIC(1) NOT NULL DEFAULT '0',
  user_level NUMERIC(2) NOT NULL DEFAULT '1',
  wsteps varchar(200) DEFAULT NULL,
  ugroups varchar(250)  DEFAULT '',
  effgroup varchar(50)  DEFAULT '',
  ckey varchar(220)  DEFAULT '',
  ctime varchar(220)  DEFAULT '',
  user_online NUMERIC(1) NOT NULL DEFAULT '0',
  user_online_show NUMERIC(1) NOT NULL DEFAULT '1',
  user_activity_show NUMERIC(1) NOT NULL DEFAULT '0',
  online_time timestamp NOT NULL DEFAULT '2001-01-01 00:00:00',
  avatar varchar(150)  DEFAULT '',
  phone varchar(40) DEFAULT NULL,
  utitle varchar(100) DEFAULT NULL,
  uaddress varchar(100) DEFAULT NULL,
  modules varchar(255) DEFAULT NULL,
  wid varchar(250) DEFAULT NULL,
  appid varchar(255) DEFAULT NULL,
  pjid varchar(255) DEFAULT NULL,
  navfav varchar(200) NOT NULL DEFAULT '[]',
  UNIQUE(uuid)
)  ;

CREATE TABLE IF NOT EXISTS user_visits (
  id SERIAL PRIMARY KEY,
  month date NOT NULL DEFAULT '2001-01-01',
  views NUMERIC(200) NOT NULL DEFAULT '0',
  mainuser varchar(255) NOT NULL
) ;

CREATE TABLE IF NOT EXISTS search (
  id SERIAL PRIMARY KEY,
  what varchar(200)  DEFAULT '',
  swhere varchar(250)  DEFAULT '',
  tags varchar(250)  DEFAULT ''
)  ;

CREATE TABLE IF NOT EXISTS iibenv_flows (
  id SERIAL PRIMARY KEY,
  projid varchar(50) NOT NULL,
  flowid varchar(32) NOT NULL,
  flowname varchar(256) NOT NULL,
  info text NULL,
  insvn NUMERIC(1) NOT NULL DEFAULT '0',
  reqinfo text NULL,
  modified timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  lockedby varchar(50)  DEFAULT '',
  UNIQUE(flowid)
)  ;

CREATE TABLE IF NOT EXISTS user_failure (
  id SERIAL PRIMARY KEY,
  fail_type varchar(10) NOT NULL,
  mainuser varchar(100) NOT NULL,
  what varchar(100)  DEFAULT '',
  fail_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  ip varchar(20) NOT NULL
);

CREATE TABLE IF NOT EXISTS env_appservers (
  id SERIAL PRIMARY KEY,
  proj varchar(20) DEFAULT NULL,
  serv_type varchar(10) NOT NULL,
  tags varchar(255)  DEFAULT '',
  appsrvname varchar(100) DEFAULT NULL,
  serverdns varchar(255) NOT NULL,
  serverip varchar(100) DEFAULT NULL,
  serverid varchar(64) DEFAULT NULL,
  port NUMERIC(11) NOT NULL DEFAULT '0',
  qmname varchar(255)  DEFAULT '',
  qmchannel varchar(255)  DEFAULT '',
  agentname varchar(255)  DEFAULT '',
  brokername varchar(255)  DEFAULT '',
  execgname varchar(255)  DEFAULT '',
  info varchar(255) DEFAULT NULL,
  sslenabled NUMERIC(1) NOT NULL DEFAULT '0',
  sslkey varchar(200) DEFAULT NULL,
  sslpass varchar(100) DEFAULT NULL,
  sslcipher varchar(100) DEFAULT NULL,
  changed timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  srvuser varchar(200) DEFAULT NULL,
  srvpass varchar(255) DEFAULT NULL,
  lockedby varchar(50)  DEFAULT ''
);

CREATE TABLE IF NOT EXISTS knowledge_categories (
 id SERIAL PRIMARY KEY,
  public NUMERIC(2) NOT NULL DEFAULT '0',
  category varchar(255) NOT NULL,
  catname varchar(255) NOT NULL,
  accgroups varchar(255) DEFAULT NULL
)  ;

CREATE TABLE IF NOT EXISTS knowledge_info (
   id SERIAL PRIMARY KEY,
  cat_latname varchar(255) NOT NULL,
  cat_name varchar(255) NOT NULL,
  category varchar(255) NOT NULL,
  cattext text NOT NULL,
  catdate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  views NUMERIC(50) NOT NULL DEFAULT '0',
  public NUMERIC(1) NOT NULL DEFAULT '1',
  accgroups varchar(255) DEFAULT NULL,
  author varchar(100) DEFAULT NULL,
  tags varchar(150) NOT NULL,
  gitprepared int NOT NULL DEFAULT '0'
)  ;

CREATE TABLE IF NOT EXISTS ldap_config (
  id SERIAL PRIMARY KEY,
 ldapserver varchar(250) NOT NULL,
  ldapport NUMERIC(5) NOT NULL DEFAULT '389',
  ldaptree varchar(250) NOT NULL,
  ldapgtree varchar(250) NOT NULL,
  ldapinfo varchar(250) NOT NULL
)  ;

CREATE TABLE IF NOT EXISTS requests_approval (
   id SERIAL PRIMARY KEY,
   reqid varchar(15) NOT NULL,
  reqapp varchar(10) DEFAULT NULL,
  projid varchar(50) NOT NULL,
  appruser varchar(50)  DEFAULT '',
  apprfullname varchar(200)  DEFAULT '',
  reqdate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  apprdate date NOT NULL DEFAULT '2001-01-01'
) ;

CREATE TABLE IF NOT EXISTS requests_comments (
   id SERIAL PRIMARY KEY,
   reqid varchar(15) NOT NULL,
  commuser varchar(50)  DEFAULT '',
  commfullname varchar(200)  DEFAULT '',
  commdate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  commtext text DEFAULT NULL
) ;


CREATE TABLE IF NOT EXISTS requests_data (
   id SERIAL PRIMARY KEY,
  reqid varchar(15) NOT NULL,
  reqtype varchar(10) NOT NULL,
  reqdata text NOT NULL
);

CREATE TABLE IF NOT EXISTS env_firewall (
  id SERIAL PRIMARY KEY,
  tags varchar(255)  DEFAULT '',
  proj varchar(80) NOT NULL,
  port NUMERIC(10) NOT NULL,
  srcip varchar(80) NOT NULL,
  destip varchar(80) NOT NULL,
   srcdns varchar(120) NOT NULL,
  destdns varchar(120) NOT NULL,
  info varchar(255)  DEFAULT '',
  changed timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  lockedby varchar(50)  DEFAULT ''
);

CREATE TABLE IF NOT EXISTS requests_deployments (
   id SERIAL PRIMARY KEY,
  reqid varchar(15) NOT NULL,
  reqapp varchar(10) DEFAULT NULL,
  projid varchar(50) DEFAULT NULL,
  consuser varchar(50) NOT NULL,
  depluser varchar(50) DEFAULT NULL,
  deployedin varchar(255) DEFAULT NULL,
  prodnum varchar(50) DEFAULT NULL,
  prodinfo varchar(255) DEFAULT NULL,
  consdate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  depldate date NOT NULL DEFAULT '2001-01-01'
) ;

CREATE TABLE IF NOT EXISTS requests_efforts (
   id SERIAL PRIMARY KEY,
  reqid varchar(15) NOT NULL,
  reqapp varchar(10) DEFAULT NULL,
  effuser varchar(50) NOT NULL,
  efffullname varchar(200) DEFAULT NULL,
  effdays varchar(8) DEFAULT NULL,
  effdate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ;

CREATE TABLE IF NOT EXISTS requests_confirmation (
   id SERIAL PRIMARY KEY,
  reqid varchar(15) NOT NULL,
  projid varchar(50) NOT NULL,
  confuser varchar(50)  DEFAULT '',
  conffullname varchar(200)  DEFAULT '',
  reqdate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  confdate date NOT NULL DEFAULT '2001-01-01'
) ;

CREATE TABLE IF NOT EXISTS config_app_codes (
   id SERIAL PRIMARY KEY,
  tags varchar(255) DEFAULT NULL,
  appcode varchar(20) DEFAULT NULL,
  appname varchar(200) DEFAULT NULL,
  appinfo text DEFAULT NULL,
  appusers text DEFAULT NULL,
  owner varchar(100) DEFAULT NULL,
  appcreated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ;

CREATE TABLE IF NOT EXISTS config_projects (
   id SERIAL PRIMARY KEY,
  tags varchar(255) DEFAULT NULL,
  projcode varchar(20) DEFAULT NULL,
  projyear NUMERIC(4) NOT NULL DEFAULT '0000',
  projname varchar(200) DEFAULT NULL,
  projinfo text DEFAULT NULL,
  projstatus int DEFAULT 0,
  projstartdate date DEFAULT NULL,
  projduedate date DEFAULT NULL,
  projusers text DEFAULT NULL,
  budget double precision  DEFAULT 0.00,
  budgetspent double precision  DEFAULT 0.00,
  owner varchar(100) DEFAULT NULL
) ;

CREATE TABLE IF NOT EXISTS requests_efforts_all (
 id SERIAL PRIMARY KEY,
 reqid varchar(15) NOT NULL,
  effreq varchar(8) DEFAULT NULL,
  effappr varchar(8) DEFAULT NULL,
  effdata text DEFAULT NULL
) ;

CREATE TABLE IF NOT EXISTS config_diagrams (
   id SERIAL PRIMARY KEY,
  tags varchar(255) DEFAULT NULL,
  reqid varchar(50) NOT NULL,
  appcode varchar(20) DEFAULT NULL,
  srvlist varchar(250) DEFAULT NULL,
  appsrvlist varchar(250) DEFAULT NULL,
  desid varchar(50) DEFAULT NULL,
  desname varchar(150) NOT NULL,
  desdate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  desuser varchar(100) NOT NULL,
  imgdata text DEFAULT NULL,
  xmldata text DEFAULT NULL,
  public NUMERIC(1) NOT NULL DEFAULT 1,
  accgroups varchar(255) DEFAULT NULL,
  author varchar(100) DEFAULT NULL,
  category varchar(100) DEFAULT NULL,
  gitprepared int DEFAULT 0,
   UNIQUE(desid)
) ;

CREATE TABLE IF NOT EXISTS calendar (
   id SERIAL PRIMARY KEY,
  mainuser varchar(100)  DEFAULT '',
  subject varchar(200) NOT NULL,
  subj_id varchar(200) DEFAULT NULL,
  date_start timestamp NOT NULL DEFAULT '2001-01-01 00:00:00',
  date_end timestamp NOT NULL DEFAULT '2001-01-01 00:00:00',
  allDay NUMERIC(1) NOT NULL DEFAULT '0',
  time_period NUMERIC(10) NOT NULL DEFAULT '0',
  color varchar(20) NOT NULL DEFAULT '#0D90B9'
) ;

CREATE TABLE IF NOT EXISTS tasks (
   id SERIAL PRIMARY KEY,
  mainuser varchar(100)  DEFAULT '',
  taskinfo varchar(200) NOT NULL,
  taskstate varchar(1) NOT NULL DEFAULT '0',
  date_start timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  date_end timestamp NOT NULL DEFAULT '2001-01-01 00:00:00'
) ;

CREATE TABLE IF NOT EXISTS user_groups (
  id SERIAL PRIMARY KEY,
  group_latname varchar(150) NOT NULL,
  group_name varchar(150) NOT NULL,
  group_email varchar(150) DEFAULT NULL,
  users varchar  NOT NULL DEFAULT '[]',
  wid varchar DEFAULT NULL,
  appid varchar DEFAULT NULL,
  pjid varchar DEFAULT NULL,
  wsteps varchar DEFAULT NULL,
  acclist VARCHAR DEFAULT NULL
) ;

CREATE TABLE IF NOT EXISTS requests_tasks (
  id SERIAL PRIMARY KEY,
  reqid varchar(50) NOT NULL DEFAULT '0',
  taskname varchar(100) NOT NULL,
  info varchar(200)  DEFAULT '',
  created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  deadline date NOT NULL,
  modified timestamp NOT NULL DEFAULT '2001-01-01 00:00:00',
  taskby varchar(100)  DEFAULT '',
  taskto varchar(100)  DEFAULT '',
  assigned varchar(100)  DEFAULT '',
  taskstatus varchar(20) NOT NULL DEFAULT 'new'
);

CREATE TABLE IF NOT EXISTS config_workflows (
  id SERIAL PRIMARY KEY,
  wid varchar(8) NOT NULL,
  wname varchar(250) DEFAULT NULL,
  wcreated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  modified timestamp NOT NULL DEFAULT '2001-01-01 00:00:00',
  wuser_updated varchar(100) NOT NULL,
  wowner varchar(100) NOT NULL,
  winfo varchar(250) DEFAULT NULL,
  wdata text,
  wgroups text,
  formid varchar(8) NOT NULL,
  haveappr int DEFAULT 0,
  haveconf int DEFAULT 0,
  wtype varchar(8) NOT NULL,
  wfcost double precision DEFAULT 0.00,
  wfcurcost double precision DEFAULT 0.00
);

CREATE TABLE IF NOT EXISTS config_agents (
 id SERIAL PRIMARY KEY,
  uniqid varchar(60) NOT NULL,
  agent_name varchar(150) NOT NULL,
  agent_dns varchar(150) NOT NULL,
  agent_ip varchar(20) NOT NULL,
  agent_platform varchar(20) NOT NULL,
  agent_resources varchar(250) NOT NULL DEFAULT '[]',
  agent_disks varchar(250) NOT NULL DEFAULT '[]',
  date_updated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  processes text,
  uptime varchar(60) NOT NULL DEFAULT '0'
);

CREATE TABLE IF NOT EXISTS tibco_obj (
  id SERIAL PRIMARY KEY,
  proj varchar(20) DEFAULT NULL,
  srv varchar(100) NOT NULL,
  objname varchar(200) NOT NULL,
  objtype varchar(50) NOT NULL,
  objdata text NULL,
  changed timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  projinfo text NULL,
  jobrun INT DEFAULT NULL,
  jobid varchar(10) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS tibco_acl (
  id SERIAL PRIMARY KEY,
  proj varchar(20) DEFAULT NULL,
  srv varchar(100) NOT NULL,
  objtype varchar(10) NOT NULL,
  objname varchar(200) NOT NULL,
  acltype varchar(10) NOT NULL,
  aclname varchar(200) NOT NULL,
  perm varchar(255) NOT NULL DEFAULT '[]',
  changed timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  projinfo text NULL
);

CREATE TABLE IF NOT EXISTS env_jobs (
  id SERIAL PRIMARY KEY,
  jobid varchar(50) DEFAULT NULL,
  proj varchar(20) DEFAULT NULL,
  reqid varchar(20) DEFAULT NULL,
  srv varchar(100) NOT NULL,
  env varchar(100) NOT NULL,
  jobname varchar(255) NOT NULL,
  deplenv varchar(80) DEFAULT NULL,
  created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  lrun timestamp NOT NULL DEFAULT '2001-01-01 00:00:00',
  nrun timestamp NOT NULL DEFAULT '2001-01-01 00:00:00',
  runby varchar(50)  DEFAULT '',
  jobstatus INT DEFAULT NULL,
  jenabled int DEFAULT 1,
  jobdata text NULL,
  jobtype int DEFAULT 1,
  objtype varchar(100) DEFAULT NULL,
  objid int DEFAULT NULL,
  objname varchar(200) DEFAULT NULL,
  connstr varchar(255) DEFAULT NULL
) ;

CREATE TABLE IF NOT EXISTS env_dns (
  id SERIAL PRIMARY KEY,
  tags varchar(255)  DEFAULT '',
  proj varchar(80) NOT NULL,
  dnsname varchar(255) NOT NULL,
  dnsserv varchar(255) DEFAULT NULL,
  dnsservid varchar(64) DEFAULT NULL,
  ttl NUMERIC(10) NOT NULL,
  dnsclass varchar(10) DEFAULT NULL,
  dnstype varchar(10) NOT NULL,
  dnsrecord text NULL,
  changed timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS env_servers (
  id SERIAL PRIMARY KEY,
  tags varchar(255)  DEFAULT '',
  serverid varchar(64) DEFAULT NULL,
  serverdns varchar(255) NOT NULL,
  servertype varchar(80) NOT NULL,
  serverip varchar(100) DEFAULT NULL,
  serverhw text NULL,
  serverdisc text NULL,
  servernet text NULL,
  serverprog text NULL,
  servupdated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updperiod NUMERIC(10) NOT NULL DEFAULT '0',
  groupid varchar(150) DEFAULT NULL,
  pluid varchar(20) DEFAULT NULL,
  srvpublic NUMERIC(1) NOT NULL DEFAULT '0'
);

CREATE TABLE IF NOT EXISTS external_files (
  id SERIAL PRIMARY KEY,
  tags varchar(255) DEFAULT NULL,
  reqid varchar(50) NOT NULL,
  appcode varchar(20) DEFAULT NULL,
  srvlist varchar(250) DEFAULT NULL,
  appsrvlist varchar(250) DEFAULT NULL,
  filetype varchar(20) DEFAULT NULL,
  fileid varchar(100) DEFAULT NULL,
  file_size NUMERIC(40) DEFAULT NULL,
  file_name varchar(200) NOT NULL,
  filedate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  impuser varchar(100) NOT NULL,
  filelink varchar(500) DEFAULT NULL,
   UNIQUE(fileid)
);

CREATE TABLE IF NOT EXISTS env_packages (
  id SERIAL PRIMARY KEY,
  tags varchar(255) DEFAULT NULL,
  proj varchar(20) DEFAULT NULL,
  packname varchar(80) NOT NULL,
  srvtype varchar(20) DEFAULT NULL,
  packuid varchar(16) DEFAULT NULL,
  deployedin varchar(255) DEFAULT NULL,
  pkgobjects text NULL,
  created_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  modified_time timestamp NOT NULL DEFAULT '2001-01-01 00:00:00',
  modified int DEFAULT 0,
  gitprepared int DEFAULT 0,
  created_by varchar(100)  DEFAULT ''
) ;

CREATE TABLE IF NOT EXISTS env_gituploads (
  id SERIAL PRIMARY KEY,
  gittype varchar(20) DEFAULT NULL,
  commitid varchar(150) DEFAULT NULL,
  packuid varchar(150) DEFAULT NULL,
  fileplace varchar(255) DEFAULT NULL,
  steptime timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  steptype varchar(150) DEFAULT NULL,
  stepuser varchar(100)  DEFAULT NULL
) ;

CREATE TABLE IF NOT EXISTS env_places (
  id SERIAL PRIMARY KEY,
  tags varchar(255) DEFAULT NULL,
  placename varchar(80) NOT NULL,
  plregion varchar(5) DEFAULT NULL,
  plcity varchar(100) DEFAULT NULL,
  pltype varchar(30) DEFAULT NULL,
  pluid varchar(20) DEFAULT NULL,
  plused int DEFAULT 0,
  plcontact varchar(255) DEFAULT NULL,
  created_by varchar(100)  DEFAULT ''
) ;

CREATE TABLE IF NOT EXISTS users_recent (
  id SERIAL PRIMARY KEY,
  uuid int NOT NULL,
  recentdata text DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS env_jobs_mq (
  id SERIAL PRIMARY KEY,
  jobid varchar(50) DEFAULT NULL,
  proj varchar(20) DEFAULT NULL,
  srv varchar(100) NOT NULL,
  qmgr varchar(100) NOT NULL,
  env varchar(100) NOT NULL,
  created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  lrun timestamp NOT NULL DEFAULT '2001-01-01 00:00:00',
  nrun timestamp NOT NULL DEFAULT '2001-01-01 00:00:00',
  owner varchar(50)  DEFAULT '',
  jobstatus INT DEFAULT NULL,
  jobrepeat INT DEFAULT NULL,
  connstr text NULL,
  qminv text NULL
) ;

CREATE TABLE IF NOT EXISTS mon_jobs (
  id SERIAL PRIMARY KEY,
  monid varchar(20) DEFAULT NULL,
  monname varchar(50) NOT NULL,
  appid varchar(20) DEFAULT NULL,
  srv varchar(100) DEFAULT NULL,
  monprovider varchar(10) NOT NULL,
  montype int NOT NULL,
  monaltype varchar(20) NOT NULL,
  env varchar(20) NOT NULL,
  created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  owner varchar(50)  DEFAULT '',
  monsoft varchar(20) NOT NULL,
  jobstatus int DEFAULT 0,
  monaemail varchar(150) DEFAULT NULL
) ;

CREATE TABLE IF NOT EXISTS mon_alerts (
  id SERIAL PRIMARY KEY,
  srv varchar(100) DEFAULT NULL,
  appsrvid varchar(150) DEFAULT NULL,
  alerttype varchar(20) DEFAULT NULL,
  loglevel varchar(20) DEFAULT NULL,
  errorcode varchar(50) DEFAULT NULL,
  errorplace varchar(255) DEFAULT NULL,
  errormessage varchar(255) DEFAULT NULL,
  appsrv varchar(255) DEFAULT NULL,
  appobject varchar(255) DEFAULT NULL,
  alerttime timestamp NOT NULL DEFAULT '2001-01-01 00:00:00',
  reported timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ;

CREATE TABLE IF NOT EXISTS config_projtempl (
  id SERIAL PRIMARY KEY,
  tags varchar(255) DEFAULT NULL,
  appcode varchar(20) DEFAULT NULL,
  templcode varchar(20) DEFAULT NULL,
  templname varchar(255) DEFAULT NULL,
  templinfo text DEFAULT NULL,
  totalcost double precision  DEFAULT 0.00,
  servinfo text DEFAULT NULL,
  formid varchar(255) NOT NULL,
  serviceid varchar(255) DEFAULT NULL,
  owner varchar(100) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS config_projrequest (
  id SERIAL PRIMARY KEY,
  tags varchar(255) DEFAULT NULL,
  appcode varchar(20) DEFAULT NULL,
  projcode varchar(20) DEFAULT NULL,
  projname varchar(255) DEFAULT NULL,
  projinfo text DEFAULT NULL,
  totalcost double precision  DEFAULT 0.00,
  servinfo varchar(255) DEFAULT NULL,
  reqinfo varchar(255) DEFAULT NULL,
  serviceid varchar(255) DEFAULT NULL,
  formid varchar(255) NOT NULL,
  projstatus int DEFAULT 0,
  projstartdate date DEFAULT NULL,
  projduedate date DEFAULT NULL,
  owner varchar(100) DEFAULT NULL,
  created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  requser varchar(100) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS env_releases (
  id SERIAL PRIMARY KEY,
  relid varchar(10) DEFAULT NULL,
  versionmatch varchar(50) DEFAULT NULL,
  releasename varchar(80) NOT NULL,
  relperiod varchar(20) DEFAULT NULL,
  reltype varchar(20) DEFAULT NULL,
  relcontact varchar(255) DEFAULT NULL,
  relversion varchar(25) DEFAULT NULL,
  created_by varchar(100) NOT NULL DEFAULT '',
  lastcheck timestamp DEFAULT NULL,
  latestver text DEFAULT NULL
) ;

CREATE TABLE IF NOT EXISTS changes (
  id SERIAL PRIMARY KEY,
  tags varchar(255) DEFAULT NULL,
  chgname varchar(50) DEFAULT NULL,
  chgnum varchar(20) DEFAULT NULL,
  info varchar(155) DEFAULT NULL,
  created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  deadline date DEFAULT NULL,
  owner varchar(100) DEFAULT NULL,
  chgstatus int DEFAULT 0,
  taskcurr int DEFAULT 0,
  taskall int DEFAULT 0,
  priority int DEFAULT 0,
  started timestamp NOT NULL DEFAULT '2001-01-01 00:00:00',
  finished timestamp NOT NULL DEFAULT '2001-01-01 00:00:00',
  UNIQUE(chgnum)
);

CREATE TABLE IF NOT EXISTS env_docimport (
  id SERIAL PRIMARY KEY,
  fileid varchar(255) DEFAULT NULL,
  importedon timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  tags varchar(255) NOT NULL DEFAULT '',
  author varchar(50) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS changes_tasks (
  id SERIAL PRIMARY KEY,
  nestid int DEFAULT 0,
  uid varchar(6) DEFAULT NULL,
  chgnum varchar(20) DEFAULT NULL,
  owner varchar(100) DEFAULT NULL,
  appid varchar(10) DEFAULT NULL,
  groupid varchar(150) DEFAULT NULL,
  taskstatus int DEFAULT 0,
  taskname text DEFAULT NULL,
  taskinfo text DEFAULT NULL,
  emailsend int DEFAULT 0,
  email varchar(150) DEFAULT NULL,
  started timestamp NOT NULL DEFAULT '2001-01-01 00:00:00',
  finished timestamp NOT NULL DEFAULT '2001-01-01 00:00:00'
);