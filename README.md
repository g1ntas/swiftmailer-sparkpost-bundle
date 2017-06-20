# Swiftmailer Sparkpost bundle

This bundle adds an extra transport to the Swiftmailer service that uses Sparkpost API.
Internally this bundle integrates 
[f500/swiftmailer-sparkpost](https://github.com/f500/swiftmailer-sparkpost) to
your Symfony Project.

Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require g1ntas/swiftmailer-sparkpost-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new Gintko\Swiftmailer\SparkpostBundle\GintkoSwiftmailerSparkpostBundle(),
        );

        // ...
    }

    // ...
}
```

Step 3: Configure the Bundle
----------------------------
Configure Sparkpost by adding API key in the `app/config/config.yml` configuration file:

```yaml
# app/config/config.yml
# ...
 
gintko_swiftmailer_sparkpost:
    api_key: 'SparkPostApiKey'
```

Also configure Swiftmailer to use Sparkpost transport:
```yaml
# app/config/config.yml
# ...
 
swiftmailer:
    transport: sparkpost
    # ...
```

Usage
=====
```php
<?php
// ...
 
public function sendEmailAction() 
{
    $mailer = $this->get('mailer');
    
    $message = $mailer->createMessage()
        ->setSubject('test')
        ->setFrom('me@domain.com', 'Me')
        ->setTo(['john@doe.com' => 'John Doe', 'jane@doe.com'])
        ->setSubject('...')
        ->setBody('...');
    
    $mailer->send($message);
}
```

Specialized messages
--------------------
```php
<?php
// ...
 
public function sendEmailAction() 
{
    
    $mailer = $this->get('mailer');
    
    $message = SwiftSparkPost\Message::newInstance()
        ->setFrom('me@domain.com', 'Me')
        ->setTo(['john@doe.com' => 'John Doe', 'jane@doe.com'])
        ->setSubject('...')
        ->setBody('...')
        
        ->setCampaignId('...')
        ->setPerRecipientTags('john@doe.com', ['...'])
        ->setMetadata(['...' => '...'])
        ->setPerRecipientMetadata('john@doe.com', ['...' => '...'])
        ->setSubstitutionData(['...' => '...'])
        ->setPerRecipientSubstitutionData('john@doe.com', ['...' => '...'])
        ->setOptions(['...']);
    
    $mailer->send($message);
}
```

Configuration
=============

All below specified values are default.
```yaml
gintko_swiftmailer_sparkpost:
    api_key: 'SparkPostApiKey' # required
    ip_pool_probability: 1.0
    recipient_override:
        email: null
        gmail_style: false
    message_options:
        transactional: true
        open_tracking: false
        click_tracking: false
        sandbox: false
        skip_suppression: false
        inline_css: false
        ip_pool: null
```

These options will be applied to all messages, but if you need you can provide custom configuration for each message. More about that read in [original package page](https://github.com/f500/swiftmailer-sparkpost).