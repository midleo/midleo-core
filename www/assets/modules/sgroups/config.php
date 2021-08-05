<?php
$modulelist["sgroups"]["name"]="Support groups and tasks";
class Gr{
public static function supportGroups(){ 
  return array(
   array(
     "nid"=>"midgroup",
     "name"=>"Middleware Group",
     "type"=>"subgroup",
     "children"=>array(
       array(
    "nid"=>"middleweware",
    "name"=>"Middleware Team",
    "type"=>"group",
      ),
       array(
    "nid"=>"windows",
    "name"=>"Windows Team",
      "type"=>"group",
      ),
       array(
    "nid"=>"unix",
    "name"=>"Unix Team",
       "type"=>"group",
      ),
       array(
    "nid"=>"network",
    "name"=>"Network Team",
      "type"=>"group",
      ),
       )
   ),
  array(
    "nid"=>"project",
    "name"=>"Project Team",
     "type"=>"group",
    ),
   array(
    "nid"=>"tmanager",
    "name"=>"Team Manager",
      "type"=>"group",
    ),
   array(
    "nid"=>"smanager",
    "name"=>"Senior Manager",
      "type"=>"group",
    ),
   array(
    "nid"=>"operators",
    "name"=>"Operators",
     "type"=>"group",
    ),
   array(
    "nid"=>"configurations",
    "name"=>"Configuration Team",
     "type"=>"group",
    ),
   array(
    "nid"=>"deployer",
    "name"=>"Deployment Team",
     "type"=>"group",
    ),
    );
} 
public static function supportTasks(){ 
  return array(
     array(
    "nid"=>"approven",
    "name"=>"Approve and send to next level",
     "type"=>"task",
      ),
    array(
    "nid"=>"approvef",
    "name"=>"Approve and send to first level",
     "type"=>"task",
      ),
     array(
    "nid"=>"rejectc",
    "name"=>"Reject and send to customer",
     "type"=>"task",
      ),
    array(
    "nid"=>"rejectf",
    "name"=>"Reject and send to first level",
     "type"=>"task",
      ),
    array(
    "nid"=>"confirmn",
    "name"=>"Confirm and send to next level",
    "type"=>"task",
      ),
    array(
    "nid"=>"confirmf",
    "name"=>"Confirm and send to first level",
    "type"=>"task",
      ),
    array(
    "nid"=>"confirmc",
    "name"=>"Confirm and send to Customer",
    "type"=>"task",
      ),
    array(
    "nid"=>"accept",
    "name"=>"Accept",
    "type"=>"task",
      ),
    array(
    "nid"=>"proceed",
    "name"=>"Proceed to next level",
    "type"=>"task",
      ),
    array(
    "nid"=>"efforts",
    "name"=>"Give Efforts",
    "type"=>"task",
      ),
    array(
    "nid"=>"sendl",
    "name"=>"Send for deployment",
    "type"=>"task",
      ),
    array(
    "nid"=>"deploy",
    "name"=>"Deploy",
    "type"=>"task",
      ),
    array(
    "nid"=>"finish",
    "name"=>"Finish",
    "type"=>"task",
    "color"=>"#00c853",  
      ),
    );
}
}