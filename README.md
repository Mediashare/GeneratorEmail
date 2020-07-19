# GeneratorEmail
## Installation
```bash
composer require mediashare/generator-email
```
## Usage
Generate new email address:
```php
<?php
require 'vendor/autoload.php';
$mail_generator = new \Mediashare\Mail\Generator();
$mail = $mail_generator->run();
echo 'Email: '.$mail['email']."\n";
echo 'Url: '.$mail['inbox']."\n";
```
Check inbox from email generated:
```php
<?php
require 'vendor/autoload.php';
echo "Mails:\n";
$inbox = new \Mediashare\Mail\InBox($mail['email']);
$mails = $inbox->run();
if (empty($mails)):
    echo "0 mail from inbox.\n";
else:
    var_dump($mails);
endif;
```