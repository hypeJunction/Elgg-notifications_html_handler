<?php

$user = elgg_get_logged_in_user_entity();
$site = elgg_get_site_entity();

$subject = "Test email from $site->name";
$body = elgg_view('plugins/notifications_html_handler/kitchen-sink.html');

$file = new ElggFile();
$file->owner_guid = $user->guid;
$file->setFilename('tmp/notify.txt');
$file->open('write');
$file->write('Hello world!');
$file->close();

$result = notify_user($user->guid, $site->guid, $subject, $body, array(
	'attachments' => array($file)
), 'email');

if ($result[$user->guid]['email']) {
	system_message(elgg_echo('notifications:html:send:success'));
} else {
	register_error(elgg_echo('notifications:html:send:error'));
}

$file->delete();