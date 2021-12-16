<?php pdodb::disconnect();
  $pdosql = null;
  if(get_defined_vars()){
    $vars = array_keys(get_defined_vars());
    foreach($vars as $var) { unset(${"$var"}); }
  }
  if($GLOBALS){
    foreach (array_keys($GLOBALS) as $k) {
      if(isset($k)){
        unset($$k);
        unset($k);
        if(isset($GLOBALS[$k])){
        unset($GLOBALS[$k]); 
        }
      }
     }
  }  
?>