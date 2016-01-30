<?php

$entity = elgg_extract('entity', $vars);

echo elgg_view_title(elgg_echo('notifications:html:test'));
$button = elgg_view('output/url', array(
	'href' => elgg_echo('action/notifications/html/test'),
	'is_action' => true,
	'text' => elgg_echo('notifications:html:send'),
	'class' => 'elgg-button elgg-button-action mtl mbl',
));
echo elgg_format_element('div', ['class' => 'clearfix'], $button);

echo elgg_view_title(elgg_echo('notifications:html:transport_settings'));
echo elgg_view_input('text', array(
	'name' => 'params[from_email]',
	'value' => $entity->from_email,
	'label' => elgg_echo('notifications:html:from_email'),
	'help' => elgg_echo('notifications:html:from_email:help'),
));

echo elgg_view_input('text', array(
	'name' => 'params[from_name]',
	'value' => $entity->from_name,
	'label' => elgg_echo('notifications:html:from_name'),
	'help' => elgg_echo('notifications:html:from_name:help'),
));

echo elgg_view_input('select', array(
	'name' => 'params[transport]',
	'value' => $entity->transport,
	'options_values' => array(
		'sendmail' => elgg_echo('notifications:html:transport:sendmail'),
		'file' => elgg_echo('notifications:html:transport:file'),
		'smtp' => elgg_echo('notifications:html:transport:smtp'),
	),
	'label' => elgg_echo('notifications:html:transport'),
	'help' => elgg_echo('notifications:html:transport:help'),
));

echo elgg_view_title(elgg_echo('notifications:html:smtp_settings'));
//echo elgg_view_input('text', array(
//	'name' => 'params[smtp_host_name]',
//	'value' => $entity->smtp_host_name,
//	'label' => elgg_echo('notifications:html:smtp_host_name'),
//	'help' => elgg_echo('notifications:html:smtp_host_name:help'),
//));
echo elgg_view_input('text', array(
	'name' => 'params[smtp_host]',
	'value' => $entity->smtp_host,
	'label' => elgg_echo('notifications:html:smtp_host'),
	'help' => elgg_echo('notifications:html:smtp_host:help'),
));
echo elgg_view_input('text', array(
	'name' => 'params[smtp_port]',
	'value' => $entity->smtp_port,
	'label' => elgg_echo('notifications:html:smtp_port'),
	'help' => elgg_echo('notifications:html:smtp_port:help'),
));
echo elgg_view_input('select', array(
	'name' => 'params[smtp_ssl]',
	'value' => $entity->smtp_ssl,
	'options_values' => array(
		'' => elgg_echo('option:no'),
		'ssl' => 'SSL',
		'tls' => 'TLS',
	),
	'label' => elgg_echo('notifications:html:smtp_ssl'),
	'help' => elgg_echo('notifications:html:smtp_ssl:help'),
));

echo elgg_view_input('select', array(
	'name' => 'params[smtp_connection]',
	'value' => $entity->smtp_connection,
	'options_values' => array(
		'smtp' => elgg_echo('notifications:html:smtp_connection:smtp'),
		'plain' => elgg_echo('notifications:html:smtp_connection:plain'),
		'login' => elgg_echo('notifications:html:smtp_connection:login'),
		'crammd5' => elgg_echo('notifications:html:smtp_connection:crammd5'),
	),
	'label' => elgg_echo('notifications:html:smtp_connection'),
	'help' => elgg_echo('notifications:html:smtp_connection:help'),
));

echo elgg_view_input('text', array(
	'name' => 'params[smtp_username]',
	'value' => $entity->smtp_username,
	'label' => elgg_echo('notifications:html:smtp_username'),
));

echo elgg_view_input('password', array(
	'name' => 'params[smtp_password]',
	'value' => $entity->smtp_password,
	'label' => elgg_echo('notifications:html:smtp_password'),
));
