<?php

$notification = elgg_extract('notification', $vars);
$recipient = $notification->getRecipient();

$site = elgg_get_site_entity();
$site_link = elgg_view('output/url', array(
	'href' => $site->getURL(),
	'text' => $site->name,
));

$settings_link = elgg_view('output/url', array(
	'href' => "notifications/$recipient->username/settings",
	'text' => elgg_echo('notifications:footer:link'),
));

echo elgg_echo('notifications:footer', array(
	$site_link,
	$settings_link,
));