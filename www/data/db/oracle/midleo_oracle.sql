BEGIN EXECUTE IMMEDIATE 'DROP TABLE mqenv_vars'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE mqenv_vars (
  id number(10) NOT NULL,
  proj varchar2(20) DEFAULT '' NOT NULL,
  varname varchar2(100) NOT NULL,
  varvalue varchar2(255) NOT NULL,
  isarray number(10) DEFAULT '0' NOT NULL,
  tags varchar2(150) NULL DEFAULT NULL,
   PRIMARY KEY (id),
   CONSTRAINT varname UNIQUE  (varname)
)    ;
BEGIN EXECUTE IMMEDIATE 'drop sequence mqenv_vars_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE mqenv_vars_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger mqenv_vars_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER mqenv_vars_seq_tr
 BEFORE INSERT ON mqenv_vars FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT mqenv_vars_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/
BEGIN EXECUTE IMMEDIATE 'DROP TABLE env_deployments'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE env_deployments (
  id number(10) NOT NULL,
  proj varchar2(20) DEFAULT '' NOT NULL,
  packuid varchar2(16) DEFAULT NULL,
  deplobjects number(20) DEFAULT '0',
  depldate timestamp(0) DEFAULT SYSTIMESTAMP NOT NULL,
  deplby varchar2(50) DEFAULT '',
  depltype number(1) DEFAULT NULL,
  deplenv number(80) DEFAULT NULL,
  deplin number(50) DEFAULT NULL,
  PRIMARY KEY (id)
)   ;
BEGIN EXECUTE IMMEDIATE 'drop sequence env_deployments_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE env_deployments_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger env_deployments_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER env_deployments_seq_tr
 BEFORE INSERT ON env_deployments FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT env_deployments_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/  
BEGIN EXECUTE IMMEDIATE 'DROP TABLE mqenv_imported_files'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE mqenv_imported_files (
  id number(10) NOT NULL,
  proj varchar2(20) DEFAULT '' NOT NULL,
  impfile varchar2(256) NOT NULL,
  impobjects number(20) DEFAULT '0',
  impdate timestamp(0) DEFAULT SYSTIMESTAMP NOT NULL,
  impby varchar2(50) DEFAULT '',
  PRIMARY KEY (id)
)   ;
BEGIN EXECUTE IMMEDIATE 'drop sequence mqenv_imported_files_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE mqenv_imported_files_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger mqenv_imported_files_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER mqenv_imported_files_seq_tr
 BEFORE INSERT ON mqenv_imported_files FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT mqenv_imported_files_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/
BEGIN EXECUTE IMMEDIATE 'DROP TABLE mqenv_mqfte'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE mqenv_mqfte (
  id number(10) NOT NULL,
  proj varchar2(20) DEFAULT '' NOT NULL,
  fteid varchar2(50) NOT NULL,
  mqftetype varchar2(10) NOT NULL,
  regex number(10) DEFAULT '0',
  tags varchar2(255) DEFAULT '',
  mqftename varchar2(255) NOT NULL,
  batchsize number(10) DEFAULT '1',
  sourceagt varchar2(255) DEFAULT '',
  sourceagtqmgr varchar2(255) DEFAULT '',
  destagt varchar2(255) DEFAULT '',
  destagtqmgr varchar2(255) DEFAULT '',
  sourcedisp varchar2(10) DEFAULT 'leave',
  textorbinary varchar2(10) DEFAULT '',
  sourceccsid varchar2(20) DEFAULT '',
  destccsid varchar2(20) DEFAULT '',
  sourcedir varchar2(255) DEFAULT '',
  sourcefile varchar2(255) DEFAULT '',
  sourcequeue varchar2(255) DEFAULT '',
  destdir varchar2(255) DEFAULT '',
  destfile varchar2(255) DEFAULT '${FileName}',
  destqueue varchar2(255) DEFAULT '',
  postsourcecmd varchar2(255) DEFAULT '',
  postsourcecmdarg varchar2(255) DEFAULT '',
  postdestcmd varchar2(255) DEFAULT '',
  postdestcmdarg varchar2(255) DEFAULT '',
  info varchar2(255) DEFAULT '',
  changed timestamp(0) DEFAULT SYSTIMESTAMP NOT NULL,
  lockedby varchar2(50) DEFAULT '',
  jobrun number(1) DEFAULT NULL,
  jobid varchar2(20) DEFAULT NULL,
  PRIMARY KEY (id)
)    ;
BEGIN EXECUTE IMMEDIATE 'drop sequence mqenv_mqfte_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE mqenv_mqfte_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger mqenv_mqfte_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER mqenv_mqfte_seq_tr
 BEFORE INSERT ON mqenv_mqfte FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT mqenv_mqfte_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/
BEGIN EXECUTE IMMEDIATE 'DROP TABLE mqjms'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE mqjms (
  id number(10) NOT NULL,
  proj varchar2(20) DEFAULT '' NOT NULL,
  bindings clob DEFAULT '',
  connfactory clob DEFAULT '',
  objects clob DEFAULT '',
  changed timestamp(0) DEFAULT SYSTIMESTAMP NOT NULL,
  lockedby varchar2(50) DEFAULT '',
  PRIMARY KEY (id)
)   ;
BEGIN EXECUTE IMMEDIATE 'drop sequence mqjms_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE mqjms_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger mqjms_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER mqjms_seq_tr
 BEFORE INSERT ON mqjms FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT mqjms_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/  
BEGIN EXECUTE IMMEDIATE 'DROP TABLE requests'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE requests (
  id number(10) NOT NULL,
  tags varchar2(255) DEFAULT NULL,
  sname varchar2(50) NOT NULL,
  projnum varchar2(20) DEFAULT NULL,
  reqapp varchar2(10) DEFAULT NULL,
  projapproved number(1) DEFAULT '0',
  projconfirmed number(1) DEFAULT '0',
  efforts varchar2(20) DEFAULT NULL,
  reqname varchar2(100) NOT NULL,
  reqlatname varchar2(100) NOT NULL,
  info varchar2(150) DEFAULT '',
  reqtype varchar2(10) DEFAULT NULL,
  reqfile varchar2(256) DEFAULT '',
  created timestamp(0) DEFAULT SYSTIMESTAMP,
  deadline char(10) DEFAULT DATE '2000-01-01',
  deadlinedeployed char(10) DEFAULT DATE '2000-01-01',
  modified timestamp(0) DEFAULT TO_TIMESTAMP('2001-01-01 00:00:00', 'YYYY-MM-DD HH24:MI:SS.FF'),
  assigned varchar2(100) DEFAULT '',
  wfstep varchar2(100) DEFAULT '',
  wfutype varchar2(100) DEFAULT '',
  wfunit varchar2(100) DEFAULT '',
  wfbstep varchar2(10) DEFAULT NULL,
  requser varchar2(100) NOT NULL,
  priority number(2) DEFAULT '0',
  deployed number(10) DEFAULT '0',
  deployed_time timestamp(0) DEFAULT TO_TIMESTAMP('2001-01-01 00:00:00', 'YYYY-MM-DD HH24:MI:SS.FF'),
  deployed_by varchar2(100) DEFAULT '',
  wid varchar2(8) NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT sname UNIQUE  (sname)
)    ;
BEGIN EXECUTE IMMEDIATE 'drop sequence requests_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE requests_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger requests_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER requests_seq_tr
 BEFORE INSERT ON requests FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT requests_seq.NEXTVAL INTO :NEW.id FROM DUAL;
 SELECT CONCAT('REQ', LPAD(:NEW.id, 8, '0')) INTO :NEW.sname FROM DUAL;
END;
/
BEGIN EXECUTE IMMEDIATE 'DROP TABLE tracking'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE tracking (
  id number(10) NOT NULL,
  projid varchar2(100) DEFAULT NULL,
  appid varchar2(100) DEFAULT NULL,
  reqid varchar2(100) DEFAULT NULL,
  srvid varchar2(100) DEFAULT NULL,
  appsrvid varchar2(50) DEFAULT NULL,
  wfid varchar2(100) DEFAULT NULL,
  who varchar2(100) NOT NULL,
  whoid varchar2(50) NOT NULL,
  what varchar2(255) NOT NULL,
  trackdate timestamp(0) DEFAULT SYSTIMESTAMP NOT NULL,
  PRIMARY KEY (id)
)    ;
BEGIN EXECUTE IMMEDIATE 'drop sequence tracking_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE tracking_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger tracking_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER tracking_seq_tr
 BEFORE INSERT ON tracking FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT tracking_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/
BEGIN EXECUTE IMMEDIATE 'DROP TABLE users'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE users (
  id number(19) NOT NULL,
  uuid varchar2(200) NOT NULL,
  ldap number(10) DEFAULT '0',
  ldapserver varchar2(255) DEFAULT NULL,
  mainuser varchar2(50) DEFAULT '' NOT NULL,
  email varchar2(255) DEFAULT '',
  pwd varchar2(220) DEFAULT '' NOT NULL,
  users_ip varchar2(200) DEFAULT '',
  fullname varchar2(100) DEFAULT '',
  active number(10) DEFAULT '0',
  user_level number(10) DEFAULT '1',
  wsteps varchar2(200) DEFAULT NULL,
  ugroups varchar2(250) DEFAULT '',
  effgroup varchar2(50) DEFAULT '',
  ckey varchar2(220) DEFAULT '',
  ctime varchar2(220) DEFAULT '',
  user_online number(10) DEFAULT '0',
  user_online_show number(10) DEFAULT '1',
  user_activity_show number(10) DEFAULT '0',
  online_time timestamp(0) DEFAULT TO_TIMESTAMP('2001-01-01 00:00:00', 'YYYY-MM-DD HH24:MI:SS.FF'),
  avatar varchar2(150) DEFAULT '',
  phone varchar2(100) DEFAULT '',
  utitle varchar2(100) DEFAULT NULL,
  uaddress varchar2(100) DEFAULT NULL,
  modules varchar2(255) DEFAULT NULL,
  wid varchar2(250) DEFAULT NULL,
  appid varchar2(255) DEFAULT NULL,
  pjid varchar2(255) DEFAULT NULL,
  navfav varchar2(200) NOT NULL DEFAULT '[]',
  PRIMARY KEY (id),
  CONSTRAINT useruuid UNIQUE  (uuid)
)    ;
BEGIN EXECUTE IMMEDIATE 'drop sequence users_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE users_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger users_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER users_seq_tr
 BEFORE INSERT ON users FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT users_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/
BEGIN EXECUTE IMMEDIATE 'DROP TABLE user_visits'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE user_visits (
  id number(10) NOT NULL,
  thismonth char(10) DEFAULT DATE '2000-01-01',
  views number(10) DEFAULT '0',
  mainuser varchar2(255) NOT NULL,
  PRIMARY KEY (id)
)   ;
BEGIN EXECUTE IMMEDIATE 'drop sequence user_visits_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE user_visits_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger user_visits_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER user_visits_seq_tr
 BEFORE INSERT ON user_visits FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT user_visits_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/
BEGIN EXECUTE IMMEDIATE 'DROP TABLE search'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE search (
  id number(10) NOT NULL,
  what varchar2(200) DEFAULT '',
  swhere varchar2(250) DEFAULT '',
  tags varchar2(250) DEFAULT '',
  PRIMARY KEY (id)
)   ;
BEGIN EXECUTE IMMEDIATE 'drop sequence search_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE search_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger search_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER search_seq_tr
 BEFORE INSERT ON search FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT search_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/
BEGIN EXECUTE IMMEDIATE 'DROP TABLE iibenv_flows'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE iibenv_flows (
  id number(10) NOT NULL,
  projid varchar2(50) NOT NULL,
  flowid varchar2(32) NOT NULL,
  flowname varchar2(256) NOT NULL,
  info varchar2(500) DEFAULT '',
  insvn number(1) DEFAULT '0',
  reqinfo clob DEFAULT '',
  modified timestamp(0) DEFAULT SYSTIMESTAMP,
  lockedby varchar2(50) DEFAULT '',
  PRIMARY KEY (id),
  CONSTRAINT mqenvflowid UNIQUE  (flowid)
)   ;
BEGIN EXECUTE IMMEDIATE 'drop sequence iibenv_flows_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE iibenv_flows_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger iibenv_flows_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER iibenv_flows_seq_tr
 BEFORE INSERT ON iibenv_flows FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT iibenv_flows_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/
BEGIN EXECUTE IMMEDIATE 'DROP TABLE user_failure'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE user_failure (
  id number(10) NOT NULL,
  fail_type varchar2(10) NOT NULL,
  mainuser varchar2(100) NOT NULL,
  what varchar2(100) DEFAULT '',
  fail_date timestamp(0) DEFAULT SYSTIMESTAMP,
  ip varchar2(20) DEFAULT '',
  PRIMARY KEY (id)
)    ;
BEGIN EXECUTE IMMEDIATE 'drop sequence user_failure_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE user_failure_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger user_failure_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER user_failure_seq_tr
 BEFORE INSERT ON user_failure FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT user_failure_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/
BEGIN EXECUTE IMMEDIATE 'DROP TABLE env_appservers'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE env_appservers (
  id number(10) NOT NULL,
  proj varchar2(20) DEFAULT '' NOT NULL,
  serv_type varchar2(10) NOT NULL,
  tags varchar2(255) DEFAULT '',
  appsrvname varchar2(100) DEFAULT NULL,
  serverdns varchar2(255) NOT NULL,
  serverip varchar2(100) DEFAULT NULL,
  serverid varchar2(32) DEFAULT NULL,
  port number(10) DEFAULT '0',
  qmname varchar2(255) DEFAULT '',
  qmchannel varchar2(255) DEFAULT '',
  agentname varchar2(255) DEFAULT '',
  brokername varchar2(255) DEFAULT '',
  execgname varchar2(255) DEFAULT '',
  info varchar2(255) DEFAULT NULL,
  sslenabled number(1) DEFAULT '0',
  sslkey varchar2(200) DEFAULT '',
  sslpass varchar2(100) DEFAULT '',
  sslcipher varchar2(100) DEFAULT '',
  changed timestamp(0) DEFAULT SYSTIMESTAMP NOT NULL,
  srvuser varchar2(200) DEFAULT '',
  srvpass varchar2(255) DEFAULT '',
  lockedby varchar2(50) DEFAULT '',
  PRIMARY KEY (id)
)    ;
BEGIN EXECUTE IMMEDIATE 'drop sequence env_appservers_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE env_appservers_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger env_appservers_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER env_appservers_seq_tr
 BEFORE INSERT ON env_appservers FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT env_appservers_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/
BEGIN EXECUTE IMMEDIATE 'DROP TABLE knowledge_categories'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE knowledge_categories (
  id number(10) NOT NULL,
  public number(10) DEFAULT '0' NOT NULL,
  category varchar2(255) NOT NULL,
  catname varchar2(255) NOT NULL,
  accgroups varchar2(255) DEFAULT NULL,
  PRIMARY KEY (id)
)    ;
BEGIN EXECUTE IMMEDIATE 'drop sequence knowledge_categories_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE knowledge_categories_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger knowledge_categories_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER knowledge_categories_seq_tr
 BEFORE INSERT ON knowledge_categories FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT knowledge_categories_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/
BEGIN EXECUTE IMMEDIATE 'DROP TABLE knowledge_info'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE knowledge_info (
  id number(10) NOT NULL,
  cat_latname varchar2(255) NOT NULL,
  cat_name varchar2(255) NOT NULL,
  category varchar2(255) NOT NULL,
  cattext clob DEFAULT '',
  catdate timestamp(0) DEFAULT SYSTIMESTAMP NOT NULL,
  views number(10) DEFAULT '0' NOT NULL,
  public number(10) DEFAULT '1' NOT NULL,
  accgroups varchar2(255) DEFAULT NULL,
  author varchar2(100) DEFAULT NULL,
  tags varchar2(150) NOT NULL,
  gitprepared number(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
)    ;
BEGIN EXECUTE IMMEDIATE 'drop sequence knowledge_info_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE knowledge_info_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger knowledge_info_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER knowledge_info_seq_tr
 BEFORE INSERT ON knowledge_info FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT knowledge_info_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/
BEGIN EXECUTE IMMEDIATE 'DROP TABLE ldap_config'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE ldap_config (
  id number(10) NOT NULL,
  ldapserver varchar2(255) NOT NULL,
  ldapport number(5) DEFAULT '389' NOT NULL,
  ldaptree varchar2(255) NOT NULL,
  ldapgtree varchar2(255) NOT NULL,
  ldapinfo varchar2(255) DEFAULT '' NOT NULL,
  PRIMARY KEY (id)
)    ;
BEGIN EXECUTE IMMEDIATE 'drop sequence ldap_config_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE ldap_config_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger ldap_config_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER ldap_config_seq_tr
 BEFORE INSERT ON ldap_config FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT ldap_config_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/
BEGIN EXECUTE IMMEDIATE 'DROP TABLE requests_data'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE requests_data (
  id number(10) NOT NULL,
  reqid varchar2(15) NOT NULL,
  reqtype varchar2(10) NOT NULL,
  reqdata clob DEFAULT '',
  PRIMARY KEY (id)
);
BEGIN EXECUTE IMMEDIATE 'drop sequence requests_data_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE requests_data_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger requests_data_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER requests_data_seq_tr
 BEFORE INSERT ON requests_data FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT requests_data_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/
BEGIN EXECUTE IMMEDIATE 'DROP TABLE env_firewall'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE env_firewall (
  id number(10) NOT NULL,
  proj varchar2(20) DEFAULT '' NOT NULL,
  tags varchar2(255) DEFAULT '',
  port number(10) DEFAULT '0',
  srcip varchar2(80) DEFAULT '',
  destip varchar2(80) DEFAULT '',
  srcdns varchar2(120) DEFAULT '',
  destdns varchar2(120) DEFAULT '',
  info varchar2(255) DEFAULT '',
  changed timestamp(0) DEFAULT SYSTIMESTAMP NOT NULL,
  lockedby varchar2(50) DEFAULT '',
  PRIMARY KEY (id)
)    ;
BEGIN EXECUTE IMMEDIATE 'drop sequence env_firewall_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE env_firewall_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger env_firewall_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER env_firewall_seq_tr
 BEFORE INSERT ON env_firewall FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT env_firewall_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/
BEGIN EXECUTE IMMEDIATE 'DROP TABLE requests_approval'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE requests_approval (
  id number(10) NOT NULL,
  reqid varchar2(15) NOT NULL,
  reqapp varchar2(10) DEFAULT NULL,
  projid varchar2(50) NOT NULL,
  appruser varchar2(50) DEFAULT '',
  apprfullname varchar2(200) DEFAULT '',
  reqdate timestamp(0) DEFAULT SYSTIMESTAMP NOT NULL,
  apprdate DATE DEFAULT DATE '2000-01-01',
  PRIMARY KEY (id)
)  ;
BEGIN EXECUTE IMMEDIATE 'drop sequence requests_approval_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE requests_approval_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger requests_approval_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER requests_approval_seq_tr
 BEFORE INSERT ON requests_approval FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT requests_approval_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/
BEGIN EXECUTE IMMEDIATE 'DROP TABLE requests_comments'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE requests_comments (
  id number(10) NOT NULL,
  reqid varchar2(15) NOT NULL,
  commuser varchar2(50) DEFAULT '',
  commfullname varchar2(200) DEFAULT '',
  commdate timestamp(0) DEFAULT SYSTIMESTAMP NOT NULL,
  commtext clob DEFAULT '',
  PRIMARY KEY (id)
)  ;
BEGIN EXECUTE IMMEDIATE 'drop sequence requests_comments_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE requests_comments_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger requests_comments_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER requests_comments_seq_tr
 BEFORE INSERT ON requests_comments FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT requests_comments_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/
BEGIN EXECUTE IMMEDIATE 'DROP TABLE requests_deployments'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE requests_deployments (
  id number(10) NOT NULL,
  reqid varchar2(15) NOT NULL,
  reqapp varchar2(10) DEFAULT NULL,
  projid varchar2(50) DEFAULT '',
  consuser varchar2(50) NOT NULL,
  depluser varchar2(50) DEFAULT '',
  deployedin varchar2(255) DEFAULT '',
  prodnum varchar2(50) DEFAULT '',
  prodinfo varchar2(255) DEFAULT '',
  consdate timestamp(0) DEFAULT SYSTIMESTAMP NOT NULL,
  depldate DATE DEFAULT DATE '2000-01-01',
  PRIMARY KEY (id)
)  ;
BEGIN EXECUTE IMMEDIATE 'drop sequence requests_deployments_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE requests_deployments_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger requests_deployments_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER requests_deployments_seq_tr
 BEFORE INSERT ON requests_deployments FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT requests_deployments_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/
BEGIN EXECUTE IMMEDIATE 'DROP TABLE requests_efforts'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE requests_efforts (
  id number(10) NOT NULL,
  reqid varchar2(15) NOT NULL,
  reqapp varchar2(10) DEFAULT NULL,
  effuser varchar2(50) NOT NULL,
  efffullname varchar2(200) DEFAULT '',
  effdays varchar2(8) DEFAULT '',
  effdate timestamp(0) DEFAULT SYSTIMESTAMP NOT NULL,
  PRIMARY KEY (id)
)  ;
BEGIN EXECUTE IMMEDIATE 'drop sequence requests_efforts_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE requests_efforts_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger requests_efforts_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER requests_efforts_seq_tr
 BEFORE INSERT ON requests_efforts FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT requests_efforts_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/
BEGIN EXECUTE IMMEDIATE 'DROP TABLE requests_confirmation'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE requests_confirmation (
  id number(10) NOT NULL,
  reqid varchar2(15) NOT NULL,
  projid varchar2(50) NOT NULL,
  confuser varchar2(50) DEFAULT '',
  conffullname varchar2(200) DEFAULT '',
  reqdate timestamp(0) DEFAULT SYSTIMESTAMP NOT NULL,
  confdate DATE DEFAULT DATE '2000-01-01',
  PRIMARY KEY (id)
)  ;
BEGIN EXECUTE IMMEDIATE 'drop sequence requests_confirmation_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE requests_confirmation_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger requests_confirmation_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER requests_confirmation_seq_tr
 BEFORE INSERT ON requests_confirmation FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT requests_confirmation_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/
BEGIN EXECUTE IMMEDIATE 'DROP TABLE config_app_codes'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE config_app_codes (
  id number(20) NOT NULL,
  tags varchar2(255) DEFAULT NULL,
  appcode varchar2(20) NOT NULL,
  appname varchar2(200) DEFAULT NULL,
  appinfo clob DEFAULT '',
  appusers clob DEFAULT '',
  owner varchar2(100) DEFAULT NULL,
  appcreated timestamp(0) DEFAULT SYSTIMESTAMP NOT NULL,
   PRIMARY KEY (id)
   )    ;
BEGIN EXECUTE IMMEDIATE 'drop sequence config_app_codes_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE config_app_codes_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger config_app_codes_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER config_app_codes_seq_tr
 BEFORE INSERT ON config_app_codes FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT config_app_codes_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/
BEGIN EXECUTE IMMEDIATE 'DROP TABLE config_projects'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE config_projects (
  id number(20) NOT NULL,
  tags varchar2(255) DEFAULT NULL,
  projcode varchar2(20) DEFAULT NULL,
  projyear number(4) DEFAULT '0000' NOT NULL,
  projname varchar2(200) DEFAULT NULL,
  projinfo clob DEFAULT '',
  projstatus int(1) DEFAULT 0,
  projstartdate char(10) DEFAULT DATE '2000-01-01',
  projduedate char(10) DEFAULT DATE '2000-01-01',
  projusers clob DEFAULT '',
  budget TO_NUMBER(20,2) DEFAULT NULL,
  budgetspent TO_NUMBER(20,2) DEFAULT NULL,
  owner varchar2(100) DEFAULT NULL,
  PRIMARY KEY (id)
   )    ;
BEGIN EXECUTE IMMEDIATE 'drop sequence config_projects_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE config_projects_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger config_projects_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER config_projects_seq_tr
 BEFORE INSERT ON config_projects FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT config_projects_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/
BEGIN EXECUTE IMMEDIATE 'DROP TABLE requests_efforts_all'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE requests_efforts_all (
  id number(10) NOT NULL,
  reqid varchar2(15) NOT NULL,
  effreq varchar2(8) DEFAULT '',
  effappr varchar2(8) DEFAULT '',
  effdata clob DEFAULT '',
  PRIMARY KEY (id)
)  ;
BEGIN EXECUTE IMMEDIATE 'drop sequence requests_efforts_all_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE requests_efforts_all_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger requests_efforts_all_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER requests_efforts_all_seq_tr
 BEFORE INSERT ON requests_efforts_all FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT requests_efforts_all_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/
BEGIN EXECUTE IMMEDIATE 'DROP TABLE config_diagrams'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE config_diagrams (
  id number(10) NOT NULL,
  tags varchar2(255) DEFAULT NULL,
  reqid varchar2(50) NOT NULL,
  appcode varchar2(20) NOT NULL,
  srvlist varchar2(250) DEFAULT NULL,
  appsrvlist varchar2(250) DEFAULT NULL,
  desid varchar2(50) DEFAULT NULL,
  desname varchar2(150) NOT NULL,
  desdate timestamp(0) DEFAULT SYSTIMESTAMP NOT NULL,
  desuser varchar2(100) DEFAULT NULL,
  imgdata clob DEFAULT '',
  xmldata clob DEFAULT '',
  public number(1) NOT NULL DEFAULT 1,
  accgroups varchar2(255) DEFAULT NULL,
  author varchar2(100) DEFAULT NULL,
  category varchar2(100) DEFAULT NULL,
  gitprepared number(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  CONSTRAINT desid UNIQUE  (desid)
);
BEGIN EXECUTE IMMEDIATE 'drop sequence config_diagrams_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE config_diagrams_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger config_diagrams_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER config_diagrams_seq_tr
 BEFORE INSERT ON config_diagrams FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT config_diagrams_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/
BEGIN EXECUTE IMMEDIATE 'DROP TABLE calendar'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE calendar (
  id number(10) NOT NULL,
  mainuser varchar2(100) NOT NULL,
  subject varchar2(200) DEFAULT NULL,
  subj_id varchar2(150) DEFAULT NULL,
  date_start timestamp(0) DEFAULT TO_TIMESTAMP('2001-01-01 00:00:00', 'YYYY-MM-DD HH24:MI:SS.FF'),
  date_end timestamp(0) DEFAULT TO_TIMESTAMP('2001-01-01 00:00:00', 'YYYY-MM-DD HH24:MI:SS.FF'),
  allDay number(1) DEFAULT '0',
  color varchar2(10) DEFAULT '#0D90B9',
  PRIMARY KEY (id)
);
BEGIN EXECUTE IMMEDIATE 'drop sequence calendar_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE calendar_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger calendar_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER calendar_seq_tr
 BEFORE INSERT ON calendar FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT calendar_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/
BEGIN EXECUTE IMMEDIATE 'DROP TABLE tasks'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE tasks (
  id number(10) NOT NULL,
  mainuser varchar2(100) NOT NULL,
  taskinfo varchar2(200) DEFAULT NULL,
  taskstate number(1) DEFAULT '0',
  date_start timestamp(0) DEFAULT TO_TIMESTAMP('2001-01-01 00:00:00', 'YYYY-MM-DD HH24:MI:SS.FF'),
  date_end timestamp(0) DEFAULT TO_TIMESTAMP('2001-01-01 00:00:00', 'YYYY-MM-DD HH24:MI:SS.FF'),
  PRIMARY KEY (id)
);
BEGIN EXECUTE IMMEDIATE 'drop sequence tasks_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE tasks_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger tasks_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER tasks_seq_tr
 BEFORE INSERT ON tasks FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT tasks_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/
BEGIN EXECUTE IMMEDIATE 'DROP TABLE user_groups'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE user_groups (
  id number(10) NOT NULL,
  group_latname varchar2(150) NOT NULL,
  group_name varchar2(150) NOT NULL,
  group_email varchar2(150) DEFAULT NULL,
  users varchar2(255) DEFAULT '[]',
  wid varchar2(255) DEFAULT NULL,
  appid varchar2(255) DEFAULT NULL,
  pjid varchar2(255) DEFAULT NULL,
  wsteps varchar2(255) DEFAULT NULL,
  acclist varchar2(255) DEFAULT NULL,
  PRIMARY KEY (id)
);
BEGIN EXECUTE IMMEDIATE 'drop sequence user_groups_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE user_groups_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger user_groups_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER user_groups_seq_tr
 BEFORE INSERT ON user_groups FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT user_groups_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/
BEGIN EXECUTE IMMEDIATE 'DROP TABLE requests_tasks'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE requests_tasks (
  id number(10) NOT NULL,
  reqid varchar2(50) NOT NULL,
  taskname varchar2(100) NOT NULL,
  info varchar2(200) DEFAULT '',
  created timestamp(0) DEFAULT SYSTIMESTAMP NOT NULL,
  deadline char(10) DEFAULT DATE '2000-01-01',
  modified timestamp(0) DEFAULT TO_TIMESTAMP('2001-01-01 00:00:00', 'YYYY-MM-DD HH24:MI:SS.FF'),
  taskby varchar2(100) DEFAULT '',
  taskto varchar2(100) DEFAULT '',
  assigned varchar2(100) DEFAULT '',
  taskstatus varchar2(20) DEFAULT 'new',
  PRIMARY KEY (id)
);
BEGIN EXECUTE IMMEDIATE 'drop sequence requests_tasks_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE requests_tasks_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger requests_tasks_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER requests_tasks_seq_tr
 BEFORE INSERT ON requests_tasks FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT requests_tasks_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/
BEGIN EXECUTE IMMEDIATE 'DROP TABLE config_workflows'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE config_workflows (
  id number(10) NOT NULL,
  wid varchar2(8) NOT NULL,
  wname varchar2(250) DEFAULT '',
  wcreated timestamp(0) DEFAULT SYSTIMESTAMP NOT NULL,
  modified timestamp(0) DEFAULT TO_TIMESTAMP('2001-01-01 00:00:00', 'YYYY-MM-DD HH24:MI:SS.FF'),
  wuser_updated varchar2(100) NOT NULL,
  wowner varchar2(100) NOT NULL,
  winfo varchar2(250) DEFAULT '',
  wdata clob DEFAULT '',
  wgroups clob DEFAULT '',
  formid varchar2(8) DEFAULT NULL,
  haveappr number(1) DEFAULT '0',
  haveconf number(1) DEFAULT '0',
  wtype varchar2(8) DEFAULT NULL,
  wfcost TO_NUMBER(10,1) DEFAULT NULL,
  wfcurcost TO_NUMBER(20,2) DEFAULT NULL,
  PRIMARY KEY (id)
);
BEGIN EXECUTE IMMEDIATE 'drop sequence config_workflows_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE config_workflows_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger config_workflows_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER config_workflows_seq_tr
 BEFORE INSERT ON config_workflows FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT config_workflows_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/
BEGIN EXECUTE IMMEDIATE 'DROP TABLE config_agents'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE config_agents (
  id number(10) NOT NULL,
  uniqid varchar2(60) NOT NULL,
  agent_name varchar2(150) DEFAULT '',
  agent_dns varchar2(150) DEFAULT '',
  agent_ip varchar2(20) DEFAULT '',
  agent_resources varchar2(250) DEFAULT '[]',
  agent_disks varchar2(250) DEFAULT '[]',
  date_updated timestamp(0) DEFAULT SYSTIMESTAMP NOT NULL,
  processes clob DEFAULT '',
  uptime varchar2(60) DEFAULT '',
  PRIMARY KEY (id)
);
BEGIN EXECUTE IMMEDIATE 'drop sequence config_agents_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE config_agents_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger config_agents_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER config_agents_seq_tr
 BEFORE INSERT ON config_agents FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT config_agents_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/
BEGIN EXECUTE IMMEDIATE 'DROP TABLE tibco_obj'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE tibco_obj (
 id number(10) NOT NULL,
  proj varchar2(20) DEFAULT '' NOT NULL,
  srv varchar2(100) NOT NULL,
  objname varchar2(200) NOT NULL,
  objtype varchar2(50) NOT NULL,
  objdata clob DEFAULT '',
  changed timestamp(0) DEFAULT SYSTIMESTAMP NOT NULL,
  projinfo clob DEFAULT '',
  jobrun number(1) DEFAULT NULL,
  jobid varchar2(10) DEFAULT NULL,
  PRIMARY KEY (id)
)    ;
BEGIN EXECUTE IMMEDIATE 'drop sequence tibco_obj_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE tibco_obj_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger tibco_obj_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER tibco_obj_seq_tr
 BEFORE INSERT ON tibco_obj FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT tibco_obj_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/ 
BEGIN EXECUTE IMMEDIATE 'DROP TABLE tibco_acl'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE tibco_acl (
 id number(10) NOT NULL,
  proj varchar2(20) DEFAULT '' NOT NULL,
  srv varchar2(100) NOT NULL,
  objtype varchar2(10) NOT NULL,
  objname varchar2(200) NOT NULL,
  acltype varchar2(10) NOT NULL,
  aclname varchar2(200) NOT NULL,
  perm varchar2(255) DEFAULT '[]' NOT NULL,
  changed timestamp(0) DEFAULT SYSTIMESTAMP NOT NULL,
  projinfo clob DEFAULT '',
  PRIMARY KEY (id)
)    ;
BEGIN EXECUTE IMMEDIATE 'drop sequence tibco_acl_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE tibco_acl_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger tibco_acl_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER tibco_acl_seq_tr
 BEFORE INSERT ON tibco_acl FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT tibco_acl_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/ 
BEGIN EXECUTE IMMEDIATE 'DROP TABLE env_jobs'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE env_jobs (
 id number(10) NOT NULL,
  jobid varchar2(50) NOT NULL,
  proj varchar2(20) DEFAULT NULL,
  reqid varchar2(100) DEFAULT NULL,
  srv varchar2(100) NOT NULL,
  env VARCHAR2(100) NOT NULL,
  jobname varchar2(255) NOT NULL,
  deplenv varchar2(80) DEFAULT NULL,
  created timestamp(0) DEFAULT SYSTIMESTAMP NOT NULL,
  lrun timestamp(0) DEFAULT TO_TIMESTAMP('2001-01-01 00:00:00', 'YYYY-MM-DD HH24:MI:SS.FF'),
  nrun timestamp(0) DEFAULT TO_TIMESTAMP('2001-01-01 00:00:00', 'YYYY-MM-DD HH24:MI:SS.FF'),
  runby varchar2(50) NOT NULL DEFAULT '',
  jobstatus number(1) DEFAULT NULL,
  jenabled number(1) NOT NULL DEFAULT '1',
  jobdata clob DEFAULT '',
  jobtype number(1) NOT NULL DEFAULT '1',
  objtype varchar2(100) DEFAULT NULL,
  objid number(50) DEFAULT NULL,
  objname varchar2(200) DEFAULT NULL,
  connstr varchar2(255) DEFAULT NULL,
  PRIMARY KEY (id)
)    ;
BEGIN EXECUTE IMMEDIATE 'drop sequence env_jobs_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE env_jobs_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger env_jobs_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER env_jobs_seq_tr
 BEFORE INSERT ON env_jobs FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT env_jobs_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/ 
BEGIN EXECUTE IMMEDIATE 'DROP TABLE env_dns'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE env_dns (
  id number(10) NOT NULL,
  proj varchar2(20) DEFAULT '' NOT NULL,
  tags varchar2(255) DEFAULT '',
  dnsname varchar2(255) DEFAULT '',
  dnsserv varchar2(255) DEFAULT NULL,
  dnsservid varchar2(64) DEFAULT NULL,
  ttl number(10) DEFAULT '0',
  dnsclass varchar2(10) DEFAULT '',
  dnstype varchar2(10) DEFAULT '',
  dnsrecord clob DEFAULT '',
  changed timestamp(0) DEFAULT SYSTIMESTAMP NOT NULL,
  PRIMARY KEY (id)
)    ;
BEGIN EXECUTE IMMEDIATE 'drop sequence env_dns_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE env_dns_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger env_dns_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER env_dns_seq_tr
 BEFORE INSERT ON env_dns FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT env_dns_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/
BEGIN EXECUTE IMMEDIATE 'DROP TABLE env_servers'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE env_servers (
  id number(10) NOT NULL,
  tags varchar2(255) DEFAULT '',
  serverid varchar2(64) DEFAULT NULL,
  serverdns varchar2(255) DEFAULT NULL,
  servertype varchar2(80) DEFAULT NULL,
  serverip varchar2(100) DEFAULT NULL,
  serverhw clob DEFAULT '',
  serverdisc clob DEFAULT '',
  servernet clob DEFAULT '',
  serverprog  MEDIUMTEXT DEFAULT '',
  servupdated timestamp(0) DEFAULT SYSTIMESTAMP NOT NULL,
  updperiod number(10) NOT NULL DEFAULT '0',
  groupid varchar2(150) DEFAULT NULL,
  pluid varchar2(20) DEFAULT NULL,
  srvpublic number(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
)    ;
BEGIN EXECUTE IMMEDIATE 'drop sequence env_servers_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE env_servers_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger env_servers_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER env_servers_seq_tr
 BEFORE INSERT ON env_servers FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT env_servers_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/
BEGIN EXECUTE IMMEDIATE 'DROP TABLE external_files'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE external_files (
  id number(10) NOT NULL,
  tags varchar2(255) DEFAULT NULL,
  reqid varchar2(50) NOT NULL,
  appcode varchar2(20) NOT NULL,
  srvlist varchar2(250) DEFAULT NULL,
  appsrvlist varchar2(250) DEFAULT NULL,
  filetype varchar2(20) DEFAULT NULL,
  fileid varchar2(100) DEFAULT NULL,
  file_size number(40) DEFAULT NULL,
  file_name varchar2(200) NOT NULL,
  filedate timestamp(0) DEFAULT SYSTIMESTAMP NOT NULL,
  impuser varchar2(100) DEFAULT NULL,
  filelink varchar2(500) DEFAULT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fileid UNIQUE  (fileid)
);
BEGIN EXECUTE IMMEDIATE 'drop sequence external_files_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE external_files_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger external_files_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER external_files_seq_tr
 BEFORE INSERT ON external_files FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT external_files_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/
BEGIN EXECUTE IMMEDIATE 'DROP TABLE env_packages'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE env_packages (
  id number(10) NOT NULL,
  tags varchar2(255) DEFAULT NULL,
  proj varchar2(20) DEFAULT '' NOT NULL,
  packname varchar2(80) NOT NULL,
  srvtype varchar2(20) DEFAULT NULL,
  packuid varchar2(16) DEFAULT NULL,
  deployedin varchar2(255) DEFAULT NULL,
  pkgobjects clob DEFAULT '',
  created_time timestamp(0) DEFAULT SYSTIMESTAMP NOT NULL,
  modified_time timestamp(0) DEFAULT TO_TIMESTAMP('2001-01-01 00:00:00', 'YYYY-MM-DD HH24:MI:SS.FF'),
  modified number(1) NOT NULL DEFAULT '0',
  gitprepared number(1) NOT NULL DEFAULT '0',
  created_by varchar2(100) NOT NULL DEFAULT '',
  PRIMARY KEY (id)
)  ;
BEGIN EXECUTE IMMEDIATE 'drop sequence env_packages_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE env_packages_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger env_packages_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER env_packages_seq_tr
 BEFORE INSERT ON env_packages FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT env_packages_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/
BEGIN EXECUTE IMMEDIATE 'DROP TABLE env_gituploads'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE env_gituploads (
  id number(10) NOT NULL,
  gittype varchar2(20) DEFAULT NULL,
  commitid varchar2(150) DEFAULT NULL,
  packuid varchar2(150) DEFAULT NULL,
  fileplace varchar2(255) DEFAULT NULL,
  steptime timestamp(0) DEFAULT SYSTIMESTAMP NOT NULL,
  steptype varchar2(100) DEFAULT NULL,
  stepuser varchar2(100) DEFAULT NULL,
  PRIMARY KEY (id)
)  ;
BEGIN EXECUTE IMMEDIATE 'drop sequence env_gituploads_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE env_gituploads_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger env_gituploads_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER env_gituploads_seq_tr
 BEFORE INSERT ON env_gituploads FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT env_gituploads_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/
BEGIN EXECUTE IMMEDIATE 'DROP TABLE env_places'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE env_places (
  id number(10) NOT NULL,
  tags varchar2(255) DEFAULT NULL,
  placename varchar2(80) NOT NULL,
  plregion varchar2(5) DEFAULT NULL,
  plcity varchar2(100) DEFAULT NULL,
  pltype varchar2(30) DEFAULT NULL,
  pluid varchar2(20) DEFAULT NULL,
  plused number(10) NOT NULL DEFAULT '0',
  plcontact varchar2(255) DEFAULT NULL,
  created_by varchar2(100) NOT NULL DEFAULT '',
  PRIMARY KEY (id)
)  ;
BEGIN EXECUTE IMMEDIATE 'drop sequence env_places_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE env_places_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger env_places_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER env_places_seq_tr
 BEFORE INSERT ON env_places FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT env_places_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/

BEGIN EXECUTE IMMEDIATE 'DROP TABLE users_recent'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE users_recent (
  id number(10) NOT NULL,
  uuid number(20) NOT NULL,
  recentdata clob DEFAULT '',
  PRIMARY KEY (id)
)  ;
BEGIN EXECUTE IMMEDIATE 'drop sequence users_recent_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE users_recent_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger users_recent_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER users_recent_seq_tr
 BEFORE INSERT ON users_recent FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT users_recent_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/

BEGIN EXECUTE IMMEDIATE 'DROP TABLE env_jobs_mq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE env_jobs_mq (
 id number(10) NOT NULL,
  jobid varchar2(50) NOT NULL,
  proj varchar2(20) DEFAULT NULL,
  srv varchar2(100) NOT NULL,
  env VARCHAR2(100) NOT NULL,
  qmgr varchar2(100) NOT NULL,
  created timestamp(0) DEFAULT SYSTIMESTAMP NOT NULL,
  lrun timestamp(0) DEFAULT TO_TIMESTAMP('2001-01-01 00:00:00', 'YYYY-MM-DD HH24:MI:SS.FF'),
  nrun timestamp(0) DEFAULT TO_TIMESTAMP('2001-01-01 00:00:00', 'YYYY-MM-DD HH24:MI:SS.FF'),
  owner varchar2(50) NOT NULL DEFAULT '',
  jobstatus number(1) DEFAULT NULL,
  jobrepeat number(1) DEFAULT NULL,
  connstr clob DEFAULT '',
  qminv clob DEFAULT '',
  PRIMARY KEY (id)
)    ;
BEGIN EXECUTE IMMEDIATE 'drop sequence env_jobs_mq_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE env_jobs_mq_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger env_jobs_mq_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER env_jobs_mq_seq_tr
 BEFORE INSERT ON env_jobs_mq FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT env_jobs_mq_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/ 
BEGIN EXECUTE IMMEDIATE 'DROP TABLE mon_jobs'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE mon_jobs (
 id number(10) NOT NULL,
 monid varchar2(20) DEFAULT NULL,
  monname varchar2(50) NOT NULL,
  appid varchar2(20) DEFAULT NULL,
  srv varchar2(100) DEFAULT NULL,
  env VARCHAR2(100) NOT NULL,
  monprovider varchar2(10) NOT NULL,
  montype number(2) DEFAULT NULL,
  monaltype varchar2(20) NOT NULL,
  created timestamp(0) DEFAULT SYSTIMESTAMP NOT NULL,
  owner varchar2(50) NOT NULL DEFAULT '',
  jobstatus number(1) DEFAULT NULL,
  monsoft varchar2(20) DEFAULT NULL,
  monaemail varchar2(150) DEFAULT NULL,
  PRIMARY KEY (id)
)    ;
BEGIN EXECUTE IMMEDIATE 'drop sequence mon_jobs_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE mon_jobs_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger mon_jobs_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER mon_jobs_seq_tr
 BEFORE INSERT ON mon_jobs FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT mon_jobs_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/ 
BEGIN EXECUTE IMMEDIATE 'DROP TABLE mon_alerts'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE mon_alerts (
 id number(10) NOT NULL,
  srv varchar2(100) DEFAULT NULL,
  appsrvid varchar2(150) DEFAULT NULL,
  alerttype varchar2(20) DEFAULT NULL,
  loglevel varchar2(20) DEFAULT NULL,
  errorcode varchar2(50) DEFAULT NULL,
  errorplace varchar2(255) DEFAULT NULL,
  errormessage varchar2(255) DEFAULT NULL,
  appsrv varchar2(255) DEFAULT NULL,
  appobject varchar2(255) DEFAULT NULL,
  alerttime timestamp(0) DEFAULT TO_TIMESTAMP('2001-01-01 00:00:00', 'YYYY-MM-DD HH24:MI:SS.FF'),
  reported timestamp(0) DEFAULT SYSTIMESTAMP NOT NULL,
  PRIMARY KEY (id)
)    ;
BEGIN EXECUTE IMMEDIATE 'drop sequence mon_alerts_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE mon_alerts_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger mon_alerts_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER mon_alerts_seq_tr
 BEFORE INSERT ON mon_alerts FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT mon_alerts_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/ 
BEGIN EXECUTE IMMEDIATE 'DROP TABLE config_projtempl'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE config_projtempl (
  id number(20) NOT NULL,
  tags varchar2(255) DEFAULT NULL,
  appcode varchar2(20) NOT NULL,
  templcode varchar2(20) DEFAULT NULL,
  templname varchar2(255) DEFAULT NULL,
  templinfo clob DEFAULT '',
  servinfo clob DEFAULT '',
  formid varchar2(255) DEFAULT NULL,
  serviceid varchar2(255) DEFAULT NULL,
  totalcost TO_NUMBER(20,2) DEFAULT NULL,
  owner varchar2(100) DEFAULT NULL,
  PRIMARY KEY (id)
   )    ;
BEGIN EXECUTE IMMEDIATE 'drop sequence config_projtempl_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE config_projtempl_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger config_projtempl_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER config_projtempl_seq_tr
 BEFORE INSERT ON config_projtempl FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT config_projtempl_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/
BEGIN EXECUTE IMMEDIATE 'DROP TABLE config_projrequest'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE config_projrequest (
  id number(20) NOT NULL,
  tags varchar2(255) DEFAULT NULL,
  appcode varchar2(20) NOT NULL,
  projcode varchar2(20) DEFAULT NULL,
  projname varchar2(255) DEFAULT NULL,
  projinfo clob DEFAULT '',
  totalcost TO_NUMBER(20,2) DEFAULT NULL,
  servinfo varchar2(255) DEFAULT NULL,
  reqinfo varchar2(255) DEFAULT NULL,
  serviceid varchar2(255) DEFAULT NULL,
  formid varchar2(255) DEFAULT NULL,
  projstatus number(1) DEFAULT NULL,
  projstartdate char(10) DEFAULT DATE '2000-01-01',
  projduedate char(10) DEFAULT DATE '2000-01-01',
  owner varchar2(100) DEFAULT NULL,
  created timestamp(0) DEFAULT SYSTIMESTAMP,
  requser varchar2(100) DEFAULT NULL,
  PRIMARY KEY (id)
   )    ;
BEGIN EXECUTE IMMEDIATE 'drop sequence config_projrequest_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE config_projrequest_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger config_projrequest_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER config_projrequest_seq_tr
 BEFORE INSERT ON config_projrequest FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT config_projrequest_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/

BEGIN EXECUTE IMMEDIATE 'DROP TABLE env_releases'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE env_releases (
  id number(10) NOT NULL,
  versionmatch varchar2(50) DEFAULT NULL,
  relid varchar2(10) NOT NULL,
  releasename varchar2(80) DEFAULT NULL,
  relperiod varchar2(20) DEFAULT NULL,
  inpmethod number(1)  DEFAULT NULL,
  reltype varchar2(20) DEFAULT NULL,
  relcontact varchar2(255) DEFAULT NULL,
  relversion varchar2(25) DEFAULT NULL,
  created_by varchar2(100) NOT NULL DEFAULT '',
  lastcheck timestamp(0) DEFAULT NULL,
  latestver clob DEFAULT '',
  PRIMARY KEY (id)
)  ;
BEGIN EXECUTE IMMEDIATE 'drop sequence env_releases_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE env_releases_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger env_releases_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER env_releases_seq_tr
 BEFORE INSERT ON env_releases FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT env_releases_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/
BEGIN EXECUTE IMMEDIATE 'DROP TABLE env_docimport'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE env_docimport (
  id number(10) NOT NULL,
  fileid varchar2(255) DEFAULT NULL,
  importedon timestamp(0) DEFAULT SYSTIMESTAMP NOT NULL,
  tags varchar2(255) NOT NULL DEFAULT '',
  author varchar2(50) DEFAULT NULL,
  PRIMARY KEY (id)
)   ;
BEGIN EXECUTE IMMEDIATE 'drop sequence env_docimport_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE env_docimport_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger env_docimport_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER env_docimport_seq_tr
 BEFORE INSERT ON env_docimport FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT env_docimport_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END
/
BEGIN EXECUTE IMMEDIATE 'DROP TABLE changes'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE changes (
  id number(10) NOT NULL,
  proj varchar2(25) DEFAULT NULL,
  chgname varchar2(50) DEFAULT NULL,
  chgnum varchar2(20) DEFAULT NULL,
  info varchar2(155) DEFAULT NULL,
  created timestamp(0) DEFAULT SYSTIMESTAMP NOT NULL,
  deadline char(10) DEFAULT DATE '2000-01-01',
  owner varchar2(100) DEFAULT NULL,
  chgstatus number(1) DEFAULT 0,
  taskcurr number(1) DEFAULT 0,
  taskall number(5) DEFAULT 0,
  priority number(1) DEFAULT 0,
  started timestamp(0) DEFAULT TO_TIMESTAMP('2001-01-01 00:00:00', 'YYYY-MM-DD HH24:MI:SS.FF'),
  finished timestamp(0) DEFAULT TO_TIMESTAMP('2001-01-01 00:00:00', 'YYYY-MM-DD HH24:MI:SS.FF'),
  PRIMARY KEY (id),
  CONSTRAINT chgnum UNIQUE  (chgnum)
)   ;
BEGIN EXECUTE IMMEDIATE 'drop sequence changes_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE changes_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger changes_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER changes_seq_tr
 BEFORE INSERT ON changes FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT changes_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/ 
BEGIN EXECUTE IMMEDIATE 'DROP TABLE changes_tasks'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE TABLE changes_tasks (
  id number(10) NOT NULL,
  nestid number(3) DEFAULT 0,
  uid varchar2(6) DEFAULT NULL,
  chgnum varchar2(20) DEFAULT NULL,
  owner varchar2(100) DEFAULT NULL,
  appid varchar2(10) DEFAULT NULL,
  groupid varchar2(150) DEFAULT NULL,
  taskstatus number(1) DEFAULT 0,
  taskname clob DEFAULT '',
  taskinfo clob DEFAULT '',
  emailsend number(1) DEFAULT 0,
  email varchar2(150) DEFAULT NULL,
  started timestamp(0) DEFAULT TO_TIMESTAMP('2001-01-01 00:00:00', 'YYYY-MM-DD HH24:MI:SS.FF'),
  finished timestamp(0) DEFAULT TO_TIMESTAMP('2001-01-01 00:00:00', 'YYYY-MM-DD HH24:MI:SS.FF'),
  PRIMARY KEY (id)
)   ;
BEGIN EXECUTE IMMEDIATE 'drop sequence changes_tasks_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE SEQUENCE changes_tasks_seq START WITH 1 INCREMENT BY 1;
BEGIN EXECUTE IMMEDIATE 'drop trigger changes_tasks_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
CREATE OR REPLACE TRIGGER changes_tasks_seq_tr
 BEFORE INSERT ON changes_tasks FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT changes_tasks_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/ 