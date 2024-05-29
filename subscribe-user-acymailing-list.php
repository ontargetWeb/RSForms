//Script called after form has been processed eg ecwexford.ie
<?php
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;

// Check if the form data indicates 'no' for previous registration and unset the first mapping if true
if (Factory::getApplication()->input->get('form', '', 'array')['prevreg'] == 'no') {
    unset($mappings[0]);
}

// Acymailing Subscription to Substitution list
$postData = Factory::getApplication()->input->get('form', '', 'array');

include_once rtrim(JPATH_ADMINISTRATOR, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_acymailing' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'helper.php';

$myUser = new stdClass();
$myUser->email = strip_tags($postData['Email']); // Replace 'Email' with your own field name
$myUser->name = strip_tags($postData['firstname'] . ' ' . $postData['lastname']); // Replace 'firstname' and 'lastname' with your own field names

$subscriberClass = acymailing_get('class.subscriber');

$subscribe = array(10); // Specify here the ID of your lists separated by a comma
Factory::getApplication()->input->set('subscription', $subscribe); // Only useful when using a subscription condition in the "Create Joomla user" plugin

$subid = $subscriberClass->save($myUser);

$subscriberClass->sendConf($subid); // Send the confirmation email if needed based on the current user status and the options from the Acy configuration page

$newSubscription = array();
if (!empty($subscribe)) {
    foreach ($subscribe as $listId) {
        $newList = array();
        $newList['status'] = 1;
        $newSubscription[$listId] = $newList;
    }
}
$subscriberClass->saveSubscription($subid, $newSubscription);
