<?php
$modulelist["iot"]["name"]="AMQP/MQTT connector for IoT";
$modulelist["css"][]="";
#https://www.ibm.com/support/knowledgecenter/SSFKSJ_8.0.0/com.ibm.mq.con.doc/tamqp_creating.htm
#https://stackoverflow.com/questions/34513324/rabbitmq-php-pecl-php-amqp-reading-takes-more-then-1-5-second-during-a-small

//function __autoload($class)
 // {
  //  require str_replace("\\","/",$class) . '.php';
 // }
//APACHEMQ delete queue: http://localhost:8161/api/jolokia/exec/org.apache.activemq:type=Broker,brokerName=localhost/removeQueue(java.lang.String)/TESTV
//APACHEMQ define queue: http://localhost:8161/api/jolokia/exec/org.apache.activemq:type=Broker,brokerName=localhost/addQueue(java.lang.String)/TESTV
/*
http://localhost:8161/hawtio/jolokia/
--add queue
{
 "type":"exec",
 "mbean":"org.apache.activemq:type=Broker,brokerName=localhost",
 "operation":"addQueue(java.lang.String)",
 "arguments":["q_OMG"]
}
--remove queue
{
 "type":"exec",
 "mbean":"org.apache.activemq:type=Broker,brokerName=localhost",
 "operation":"removeQueue(java.lang.String)",
 "arguments":["q_OMG"]
}
--add queue
{
 "type":"exec",
 "mbean":"org.apache.activemq:type=Broker,brokerName=localhost",
 "operation":"addTopic(java.lang.String)",
 "arguments":["t_OMG"]
}
--remove queue
{
 "type":"exec",
 "mbean":"org.apache.activemq:type=Broker,brokerName=localhost",
 "operation":"removeTopic(java.lang.String)",
 "arguments":["t_OMG"]
}
*/
class mqttClass{
  public static function getvars(){
  return array("mq_server"=>"vps.vasilev.link",
               "mq_user"=>"test",
               "mq_pass"=>"test",
               "mq_vhost"=>"MIDLEO.AMQP");
  }
  public static function PublishMessage($messageBody){
    require_once 'controller/vendor/autoload.php'; 
    $vars=mqttClass::getvars();
    $exchange="MIDLEO.TOPIC";
    $queue = "SYSTEM.MIDLEO.IOT.QUEUE";
    $ssl_options = array(
      'capath' => __DIR__.'ssl/',
      'cafile' => __DIR__.'ssl/midleo.pem',
      'verify_peer' => true,
    );
    $connection = new PhpAmqpLib\Connection\AMQPSSLConnection($vars['mq_server'], 5672, $vars['mq_user'], $vars['mq_pass'],$vars['mq_vhost'], $ssl_options);
    $channel = $connection->channel();
    $channel->queue_declare($queue,false,true,true,true);
    $channel->exchange_declare($exchange, 'topic', true, true, false);
    $channel->queue_bind($queue, $exchange);
    $message = new PhpAmqpLib\Message\AMQPMessage($messageBody, array('content_type' => 'text/plain','delivery_mode' => 2));
    $channel->basic_publish($message, $exchange, "midleo");
    $channel->close();
    $connection->close();
  }
}