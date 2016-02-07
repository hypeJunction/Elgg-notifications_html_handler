<?php

/**
 * Notification HTML Handler
 *
 * @author Ismayil Khayredinov <info@hypejunction.com>
 * @copyright Copyright (c) 2015, Ismayil Khayredinov
 */
require_once __DIR__ . '/autoloader.php';

elgg_register_event_handler('init', 'system', 'notifications_html_handler_init');

/**
 * Initialize the plugin
 * @return void
 */
function notifications_html_handler_init() {

	_elgg_services()->hooks->clearHandlers('send', 'notification:email');
	elgg_register_plugin_hook_handler('send', 'notification:email', 'notifications_html_handler_send_email_notification');

	elgg_register_plugin_hook_handler('format', 'notification', 'notifications_html_handler_format', 9999);

	elgg_register_action('notifications/html/test', __DIR__ . '/actions/notifications/html/test.php', 'admin');

	elgg_extend_view('page/notification.css', 'elements/components.css');
}

/**
 * Send an email notification
 *
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param bool   $result Was the email already sent
 * @param array  $params Hook parameters
 * @return bool
 */
function notifications_html_handler_send_email_notification($hook, $type, $result, $params) {

	if ($result === true) {
		// email was already sent by some other handler
		return;
	}

	$notification = elgg_extract('notification', $params);
	$notification = elgg_trigger_plugin_hook('format', 'notification', $params, $notification);

	if (!$notification instanceof \Elgg\Notifications\Notification) {
		return false;
	}

	$sender = $notification->getSender();
	$recipient = $notification->getRecipient();

	if (!$sender instanceof \ElggEntity) {
		return false;
	}

	if (!$recipient instanceof \ElggEntity || !$recipient->email) {
		return false;
	}

	$to = new Zend\Mail\Address($recipient->email, $recipient->getDisplayName());

	if (!$sender instanceof ElggUser && $sender->email) {
		// If there's an email address, use it - but only if it's not from a user.
		$from_email = $sender->email;
		$from_name = $sender->getDisplayName();
	} else {
		$site = elgg_get_site_entity();
		$from_email = elgg_get_plugin_setting('from_email', 'notifications_html_handler', $site->email);
		if (!$from_email) {
			$from_email = "noreply@{$site->getDomain()}";
		}
		$from_name = elgg_get_plugin_setting('from_name', 'notifications_html_handler', $site->name);
	}

	$from = new Zend\Mail\Address($from_email, $from_name);

	$email_params = array_merge((array) $notification->params, $params);
	$email_params['notification'] = $notification;

	return notifications_html_handler_send_email($from->toString(), $to->toString(), $notification->subject, $notification->body, $email_params);
}

/**
 * Send an email to any email address
 *
 * @param mixed $from     Email address or string: "name <email>" or \Zend\Mail\Address
 * @param mixed $to       Email address or string: "name <email>" or \Zend\Mail\Address
 * @param string $subject The subject of the message
 * @param string $body    The message body
 * @param array  $params  Optional parameters
 * @return bool
 * @throws NotificationException
 */
function notifications_html_handler_send_email($from, $to, $subject, $body, array $params = null) {

	$options = array(
		'to' => $to,
		'from' => $from,
		'subject' => $subject,
		'body' => $body,
		'params' => $params,
		'headers' => array(
			"Content-Type" => "text/html; charset=UTF-8; format=flowed",
			"MIME-Version" => "1.0",
			"Content-Transfer-Encoding" => "8bit",
		),
	);

	// $mail_params is passed as both params and return value. The former is for backwards
	// compatibility. The latter is so handlers can now alter the contents/headers of
	// the email by returning the array
	$options = _elgg_services()->hooks->trigger('email', 'system', $options, $options);
	if (!is_array($options)) {
		// don't need null check: Handlers can't set a hook value to null!
		return (bool) $options;
	}

	try {
		if (empty($options['from'])) {
			$msg = "Missing a required parameter, '" . 'from' . "'";
			throw new \NotificationException($msg);
		}

		if (empty($options['to'])) {
			$msg = "Missing a required parameter, '" . 'to' . "'";
			throw new \NotificationException($msg);
		}

		$options['to'] = \Elgg\Mail\Address::fromString($options['to']);
		$options['from'] = \Elgg\Mail\Address::fromString($options['from']);

		$options['subject'] = elgg_strip_tags($options['subject']);
		$options['subject'] = html_entity_decode($options['subject'], ENT_QUOTES, 'UTF-8');
		// Sanitise subject by stripping line endings
		$options['subject'] = preg_replace("/(\r\n|\r|\n)/", " ", $options['subject']);
		$options['subject'] = elgg_get_excerpt(trim($options['subject'], 80));

		$message = new \Zend\Mail\Message();
		foreach ($options['headers'] as $headerName => $headerValue) {
			$message->getHeaders()->addHeaderLine($headerName, $headerValue);
		}

		$message->setEncoding('UTF-8');
		$message->addFrom($options['from']);
		$message->addTo($options['to']);
		$message->setSubject($options['subject']);

		$body = new Zend\Mime\Message();

		$html = new \Zend\Mime\Part($options['body']);
		$html->type = "text/html";
		$body->addPart($html);

		$files = elgg_extract('attachments', $options['params']);
		if (!empty($files) && is_array($files)) {

			foreach ($files as $file) {
				if (!$file instanceof \ElggFile) {
					continue;
				}

				$attachment = new \Zend\Mime\Part(fopen($file->getFilenameOnFilestore(), 'r'));
				$attachment->type = $file->getMimeType() ? : $file->detectMimeType();
				$attachment->filename = $file->originalfilename ? : basename($file->getFilename());
				$attachment->disposition = Zend\Mime\Mime::DISPOSITION_ATTACHMENT;
				$attachment->encoding = Zend\Mime\Mime::ENCODING_BASE64;
				$body->addPart($attachment);
			}
		}

		$message->setBody($body);

		$transport = notifications_html_handler_get_transport();
		if (!$transport instanceof Zend\Mail\Transport\TransportInterface) {
			throw new \NotificationException("Invalid Email transport");
		}
		$transport->send($message);
	} catch (\Exception $e) {
		elgg_log($e->getMessage(), 'ERROR');
		return false;
	}

	return true;
}

/**
 * Returns email transport
 * @return \Zend\Mail\Transport\TransportInterface
 */
function notifications_html_handler_get_transport() {
	$setting = elgg_get_plugin_setting('transport', 'notifications_html_handler', 'sendmail');

	switch ($setting) {
		default :
			$transport = new \Zend\Mail\Transport\Sendmail();
			break;

		case 'file' :
			$dirname = elgg_get_config('dataroot') . 'notifications_log/zend/';
			if (!is_dir($dirname)) {
				mkdir($dirname, 0700, true);
			}
			$options = array(
				'path' => $dirname,
				'callback' => function () {
					return 'Message_' . microtime(true) . '_' . mt_rand() . '.txt';
				},
			);
			$transport = new Zend\Mail\Transport\File(new Zend\Mail\Transport\FileOptions($options));
			break;

		case 'smtp' :
			$options = array_filter(array(
				'name' => elgg_get_plugin_setting('smtp_host_name', 'notifications_html_handler'),
				'host' => elgg_get_plugin_setting('smtp_host', 'notifications_html_handler'),
				'port' => elgg_get_plugin_setting('smtp_port', 'notifications_html_handler'),
				'connection_class' => elgg_get_plugin_setting('smtp_connection', 'notifications_html_handler'),
				'connection_config' => array_filter(array(
					'username' => elgg_get_plugin_setting('smtp_username', 'notifications_html_handler'),
					'password' => elgg_get_plugin_setting('smtp_password', 'notifications_html_handler'),
					'ssl' => elgg_get_plugin_setting('smtp_ssl', 'notifications_html_handler'),
				)),
			));
			$transport = new \Zend\Mail\Transport\Smtp(new \Zend\Mail\Transport\SmtpOptions($options));
			break;
	}

	return elgg_trigger_plugin_hook('email:transport', 'system', null, $transport);
}

/**
 * Wraps HTML notification into a proper markup
 * 
 * @param string $hook         "format"
 * @param string $type         "notification"
 * @param string $notification Notificaiton
 * @param array  $params       Hook params
 * @return array
 */
function notifications_html_handler_format($hook, $type, $notification, $params) {

	if (!$notification instanceof \Elgg\Notifications\Notification) {
		return;
	}

	$view = elgg_view('page/notification', array(
		'notification' => $notification,
	));

	$css = elgg_view('page/notification.css');

	$emogrifier = new \Pelago\Emogrifier($view, $css);
	$notification->body = $emogrifier->emogrify();

	return $notification;
}