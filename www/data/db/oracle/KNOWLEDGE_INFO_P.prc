create or replace procedure KNOWLEDGE_INFO_P(P_ID       in number,
                                             P_LATNAME  in varchar2,
                                             P_NAME     in varchar2,
                                             P_CATEGORY in varchar2,
                                             P_CATTXT   in varchar2,
                                             P_TAGS     in varchar2,
                                             P_ACTIVE   in number,
                                             P_FLAG     in number) as
  g_clob clob;
begin
  if P_FLAG = 0 then
    insert into KNOWLEDGE_INFO
      (CAT_LATNAME, CAT_NAME, CATEGORY, TAGS, CATTEXT, ACTIVE)
    values
      (P_LATNAME, P_NAME, P_CATEGORY, P_TAGS, empty_clob(), P_ACTIVE)
    returning CATTEXT into g_clob;
    dbms_lob.write(g_clob, length(P_CATTXT), 1, P_CATTXT);
    commit;
  else
    update KNOWLEDGE_INFO set CATTEXT = empty_clob() where ID = P_ID;
    select CATTEXT
      into g_clob
      from KNOWLEDGE_INFO
     where ID = P_ID
       for update;
    dbms_lob.write(g_clob, length(P_CATTXT), 1, P_CATTXT);
    commit;
  end if;
end;
/
