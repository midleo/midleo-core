PL/SQL Developer Test script 3.0
29
declare 
cursor tablelist is select column_value as thistable from table(sys.dbms_debug_vc2coll('authrec','cert','channels','clusters','dlqh','nl','prepost','process','qm','queues','service','subs','topics'));
begin 
  for tables in tablelist loop
    BEGIN EXECUTE IMMEDIATE 'DROP TABLE mqenv_mq_'||tables.thistable; EXCEPTION WHEN OTHERS THEN NULL; END;
    begin execute immediate 'CREATE TABLE mqenv_mq_'||tables.thistable||' (
  id number(10) NOT NULL,
  qmgr varchar(100) NOT NULL,
  objname varchar(200) NOT NULL,
  objtype varchar(50) NOT NULL,
  objdata clob DEFAULT '''',
  changed timestamp(0) DEFAULT SYSTIMESTAMP NOT NULL,
  projinfo clob DEFAULT '''',
  appid  clob DEFAULT '''',
  jobrun number(1) DEFAULT NULL,
  jobid varchar2(10) DEFAULT NULL,
  PRIMARY KEY (id)
)'; end;
BEGIN EXECUTE IMMEDIATE 'drop sequence mqenv_mq_'||tables.thistable||'_seq'; EXCEPTION WHEN OTHERS THEN NULL; END;
BEGIN EXECUTE IMMEDIATE 'CREATE SEQUENCE mqenv_mq_'||tables.thistable||'_seq START WITH 1 INCREMENT BY 1'; end;
BEGIN EXECUTE IMMEDIATE 'drop trigger mqenv_mq_'||tables.thistable||'_seq_tr'; EXCEPTION WHEN OTHERS THEN NULL; END;
BEGIN EXECUTE IMMEDIATE 'CREATE OR REPLACE TRIGGER mqenv_mq_'||tables.thistable||'_seq_tr
 BEFORE INSERT ON mqenv_mq_'||tables.thistable||' FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT mqenv_mq_'||tables.thistable||'_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END
;'; EXCEPTION WHEN OTHERS THEN NULL; END;
dbms_output.put_line('Created table: mqenv_mq_'||tables.thistable);   
  end loop; 
end;
0
0
