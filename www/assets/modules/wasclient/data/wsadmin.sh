#!/bin/bash
#wsadmin thin client
#powered by Midleo Enterprise Middleware Services ltd. 


# Set the location of wsadmin.bat
WAS_HOME=$(pwd)

# C_PATH is the class path. Add to it as needed.
SOAP_CONFIG=-Dcom.ibm.SOAP.ConfigURL="file:$WAS_HOME/properties/soap.client.props"
SSL_CONFIG=-Dcom.ibm.SSL.ConfigURL="file:$WAS_HOME/properties/ssl.client.props"
AUTH_CONFIG=-Djava.security.auth.login.config="file:$WAS_HOME/properties/wsjaas_client.conf"
USER_INSTALL_ROOT=-Duser.install.root="$WAS_HOME"
WAS_INSTALL_ROOT=-Dwas.install.root="$WAS_HOME"

WAS_LOGGING="-Djava.util.logging.manager=com.ibm.ws.bootstrap.WsLogManager -Djava.util.logging.configureByServer=true"
WAS_LOGGING="$WAS_LOGGING -Dcom.ibm.ws.scripting.traceString=com.ibm.*=all=disabled "
WAS_LOGGING="$WAS_LOGGING -Dcom.ibm.ws.scripting.traceFile=$WAS_HOME/logs/wsadmin.traceout"
WAS_LOGGING="$WAS_LOGGING -Dcom.ibm.ws.scripting.validationOutput=$WAS_HOME/logs/wsadmin.valout"
JAVA_OPTS="-Xms256m -Xmx256m"

TC="-Dcom.ibm.websphere.thinclient=true"

C_PATH="$WAS_HOME/properties:$WAS_HOME/v9/com.ibm.ws.admin.client.jar:$WAS_HOME/v9/com.ibm.ws.security.crypto.jar:$WAS_HOME/v9/ibmkeycert.jar:$WAS_HOME/v9/ibmpkcs.jar"

echo java $JAVA_OPTS -classpath "$C_PATH" $TC $SOAP_CONFIG $AUTH_CONFIG $SSL_CONFIG $USER_INSTALL_ROOT $WAS_INSTALL_ROOT $WAS_LOGGING com.ibm.ws.scripting.WasxShell $@

java $JAVA_OPTS -classpath "$C_PATH" $TC $SOAP_CONFIG $AUTH_CONFIG $SSL_CONFIG $USER_INSTALL_ROOT $WAS_INSTALL_ROOT $WAS_LOGGING com.ibm.ws.scripting.WasxShell $@