//Script called after form has been processed eg ecwexford.ie
<?php
if($_POST['form']['prevreg'] == 'no')
{
unset($mappings[0]);
}
// Load the AcyMailing library
$postData = $_REQUEST['form'];
$ds = DIRECTORY_SEPARATOR;
include_once rtrim(JPATH_ADMINISTRATOR, $ds).$ds.'components'.$ds.'com_acym'.$ds.'helpers'.$ds.'helper.php';
$userClass = new AcyMailing\Classes\UserClass();

// Build the user based on your form's fields
$myUser = new stdClass(); 
$myUser->email = strip_tags($postData['Email']);
$myUser->name = strip_tags($_POST['form']['firstname'].' '.$_POST['form']['lastname']);

// If the user already exists update it
$existingUser = $userClass->getOneByEmail($postData['Email']);
if (!empty($existingUser)) $myUser->id = $existingUser->id;

// You can add as many extra fields as you want if you already created them in AcyMailing
//$customFields = [];
//$customFields[CUSTOM_FIELD_ID] = $postData['MY_FIELD']; // the custom field id can be found in the Custom fields list of the AcyMailing admin page


$userClass->sendConf = true; // Or false if you don't want a confirmation email to be sent
//$userId = $userClass->save($myUser, $customFields);
$userId = $userClass->save($myUser);

// The user now exists in AcyMailing, let's add some list subscriptions
$subscribe = [10]; // Ids of the lists you want the user to be subscribed to need to be added in this array
$userClass->subscribe($userId, $subscribe);
