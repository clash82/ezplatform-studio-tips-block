# eZ Studio Tips block

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/aea422a4-794b-4bf5-8aa5-0b96c05abe57/big.png)](https://insight.sensiolabs.com/projects/aea422a4-794b-4bf5-8aa5-0b96c05abe57)

This bundle adds an additional Tips block (aka Tip of the Day block) into eZ Systems [eZ Studio](//ezstudio.com). This bundle can be used for a demonstration purpose on how to build new blocks for eZ Studio.

![screenshot](https://cloud.githubusercontent.com/assets/3033038/18852413/9220ed76-8440-11e6-8afe-9fed26f9909e.png)

## Requirements

- eZ Platform 1.5 or later
- eZ Systems LandingPageFieldType 1.5 or later

### Installation

This package is available via Composer, so the instructions below are similar to how you install any other open source Symfony Bundle.

Run the following command in a terminal, from your Symfony installation root (pick the most recent release):
```bash
composer require omniproject/ezstudio-tips-block
```

Enable the bundle in `app/AppKernel.php` file:

```php
$bundles = array(
    // IMPORTANT: OmniProjectEzStudioTipsBlockBundle must be placed above LandingPageFieldTypeBundle to work properly
    new OmniProject\EzStudioTipsBlockBundle\OmniProjectEzStudioTipsBlockBundle(),
  
    // existing bundles   
    new EzSystems\LandingPageFieldTypeBundle\EzSystemsLandingPageFieldTypeBundle(),
    ...
);
```

Install additional assets (CSS) for default template (omit this step if you are planning to use custom stylesheets):
 
```twig
{% stylesheets
    'bundles/omniprojectezstudiotipsblock/css/style.css'
%}
    <link rel="stylesheet" type="text/css" href="{{ asset_url }}">
{% endstylesheets %}
```
 
If you are installing the bundle via `composer require` you must also copy assets to your project's `web` directory. You can do this by calling Symfony's built-in command from the project root directory:

```bash
php app/console assets:install --symlink
```

In production environment you have to dump assets using the `Assetic` built-in command:

```bash
php app/console assetic:dump -e=prod
```

Install new `Tip` ContentType using a built-in installer:

```bash
php app/console ezstudio:tips-block:install
```

### Usage

First you have to create a new `Folder` and add some tips using the new `Tip` ContentType. After that, go to the eZ Studio dashboard and drag a new `Tips` block into a selected zone in your landing page. Click on the block settings and choose a folder as a Parent container. After publishing you should see a new block with a randomly selected tips.
