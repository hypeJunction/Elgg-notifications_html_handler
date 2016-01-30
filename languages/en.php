<?php

return [
	'notifications:html:transport_settings' => 'Transport Settings',
	'notifications:html:from_email' => 'From Email',
	'notifications:html:from_email:help' => 'An email address to use for outgoing email notifications, if different from site email',
	'notifications:html:from_name' => 'From Name',
	'notifications:html:from_name:help' => 'Name to use for outgoing email notifications, if different from site name',
	'notifications:html:transport' => 'Email Transport',
	'notifications:html:transport:sendmail' => 'Sendmail',
	'notifications:html:transport:file' => 'File Transport',
	'notifications:html:transport:smtp' => 'SMTP',
	'notifications:html:transport:help' => 'Select, which transport should be used to deliver outgoing emails. Use File Transport to disable outgoing emails and'
	. ' write them to filestore instead. Sendmail is the default transport. Configure your SMTP server details below, if choosing SMTP option',
	'notifications:html:smtp_settings' => 'SMTP Settings',
	'notifications:html:smtp_host_name' => 'SMTP Host Name',
	'notifications:html:smtp_host_name:help' => 'Name of the SMTP host; defaults to "localhost".',
	'notifications:html:smtp_host' => 'SMTP Host Address',
	'notifications:html:smtp_host:help' => 'Remote hostname or IP address; defaults to "127.0.0.1".',
	'notifications:html:smtp_port' => 'SMTP Port',
	'notifications:html:smtp_port:help' => 'Port on which the remote host is listening; defaults to "25".',
	'notifications:html:smtp_ssl' => 'SMTP Secure Connection',
	'notifications:html:smtp_ssl:help' => 'For authentication types other than SMTP, you will typically need to define the "username" and "password" options. For secure connections you will use port 587 for TLS or port 465 for SSL.',
	'notifications:html:smtp_connection' => 'SMTP Authentication Type',
	'notifications:html:smtp_connection:help' => 'Authentication protocol to use',
	'notifications:html:smtp_connection:smtp' => 'SMTP',
	'notifications:html:smtp_connection:plain' => 'SMTP with AUTH PLAIN',
	'notifications:html:smtp_connection:login' => 'SMTP with AUTH LOGIN',
	'notifications:html:smtp_connection:crammd5' => 'SMTP with AUTH CRAM-MD5',
	'notifications:html:smtp_username' => 'SMTP Username',
	'notifications:html:smtp_password' => 'SMTP Passoword',

	'notifications:html:test' => 'Send Test Email',
	'notifications:html:send' => 'Send Email',
	'notifications:html:send:success' => 'Email successfully sent',
	'notifications:html:send:error' => 'Email was not sent',

	'notifications:footer:link' => 'here',
	'notifications:footer' => 'This email has been sent by %s.<br />You can modify your notification preferences %s.<br />Please do not reply to this email.',
];

