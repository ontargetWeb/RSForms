//Script called after form has been processed eg ecwexford.ie
<?php
if($_POST['form']['prevreg'] == 'no')
{
unset($mappings[0]);
}
//Acymailing Subscription to Substitution list
$postData = JRequest::getVar('form');
 
 include_once(rtrim(JPATH_ADMINISTRATOR,DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_acymailing'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php');
 
 $myUser = new stdClass();
 $myUser->email = strip_tags($postData['Email']); //Please replace email_field by your own field name (the name of the field "email").
 $myUser->name = strip_tags($_POST['form']['firstname'].' '.$_POST['form']['lastname']); //Please replace name_field by your own field name (the name of the field "name").
 $subscriberClass = acymailing_get('class.subscriber');
 
 $subscribe = array(10); //Specify here the ID of your lists separated by a comma, in this example the user will be subscribed to lists IDs 3,4 and 5.
$_REQUEST['subscription'] = $subscribe; //Only useful when using a subscription condition in the "Create Joomla user" plugin, you can delete this line if you don't use this plugin
 
 $subid = $subscriberClass->save($myUser);
 
 
$subscriberClass->sendConf($subid); //we send the confirmation email... only if needed based on the current user status and the option from the Acy configuration page.
 
 $newSubscription = array();
 if(!empty($subscribe)){
 foreach($subscribe as $listId){
 $newList = array();
 $newList['status'] = 1;
 $newSubscription[$listId] = $newList;
 }
 }
 $subscriberClass->saveSubscription($subid,$newSubscription);
