Notification HTML Handler for Elgg
==================================
![Elgg 2.0](https://img.shields.io/badge/Elgg-2.0.x-orange.svg?style=flat-square)

## Features

 * Leverages `Zend_Mail` (email library used in core) to send out HTML emails
 * Allows filtering/formatting instant notifications
 * Allows to configure email transports (Sendmail, SMTP, File Transport)
 * Allows to send file attachments
 * Inlines CSS styles for improved email client experience
 * Microdata support

## Notes

 * You can disable outgoing email by switching to File Transport in plugin settings,
this will instead write email as txt files to the filestore under `/notifications_log/zend/`

 * Sample SMTP config for GMail
	To use GMail as your SMTP relay, you will likely need to Allow less secure apps:
	https://support.google.com/accounts/answer/6010255?hl=en

	Host: smtp.gmail.com
	Port: 587
	Secure Connection: TLS
	Auth: SMTP with AUTH LOGIN
	Username: xxxx@gmail.com
	Password: xxxx


## Conflicts

This plugin will conflict with any other plugin that subscribes to `"send","notification:email"` hook

## Developer Notes

 * `'format','notification'` hook can be used to format an instance of \Elgg\Notifications\Notification
before it is passed on to the email transport. That also allows wrapping the message into an HTML shell.

 * To add attachments to your email, add an array of `ElggFile` objects to notification parameters:

```php
notify_user($to, $from, $subject, $body, array(
	'attachments' => array(
		$file1, $file2,
	)
));
```
