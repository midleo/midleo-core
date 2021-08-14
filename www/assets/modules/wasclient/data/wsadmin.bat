@REM wsadmin thin client
@REM powered by Midleo ltd. 

@echo off

@REM Set the location of wsadmin.bat
set WAS_HOME="%cd%"

@REM C_PATH is the class path.  Add to it as needed. 
set SOAP_CONFIG=-Dcom.ibm.SOAP.ConfigURL=file:/%WAS_HOME%\properties\soap.client.props
set SSL_CONFIG=-Dcom.ibm.SSL.ConfigURL=file:/%WAS_HOME%\properties\ssl.client.props
set AUTH_CONFIG=-Djava.security.auth.login.config=file:/%WAS_HOME%\properties\wsjaas_client.conf
set USER_INSTALL_ROOT=-Duser.install.root=%WAS_HOME%
set WAS_INSTALL_ROOT=-Dwas.install.root=%WAS_HOME%

set WAS_LOGGING=-Djava.util.logging.manager=com.ibm.ws.bootstrap.WsLogManager -Djava.util.logging.configureByServer=true 
set WAS_LOGGING=%WAS_LOGGING% -Dcom.ibm.ws.scripting.traceString=com.ibm.*=all=disabled 
set WAS_LOGGING=%WAS_LOGGING% -Dcom.ibm.ws.scripting.traceFile=%WAS_HOME%\logs\wsadmin.traceout
set WAS_LOGGING=%WAS_LOGGING% -Dcom.ibm.ws.scripting.validationOutput=%WAS_HOME%\logs\wsadmin.valout
set JAVA_OPTS=-Xms256m -Xmx256m 

set TC=-Dcom.ibm.websphere.thinclient=true

set C_PATH=%WAS_HOME%\properties;%WAS_HOME%\v9\com.ibm.ws.admin.client.jar;%WAS_HOME%\v9\com.ibm.ws.security.crypto.jar;%WAS_HOME%\v9\ibmkeycert.jar;%WAS_HOME%\v9\ibmpkcs.jar

java %JAVA_OPTS% -classpath "%C_PATH%" %TC% %SOAP_CONFIG% %AUTH_CONFIG% %SSL_CONFIG% %USER_INSTALL_ROOT% %WAS_INSTALL_ROOT% %WAS_LOGGING% com.ibm.ws.scripting.WasxShell %*   
