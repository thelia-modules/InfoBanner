# Info Banner

Add a visible and editable banner to the site

## Installation

### Manually

* Copy the module into ```<thelia_root>/local/modules/``` directory and be sure that the name of the module is InfoBanner.
* Activate it in your thelia administration panel

### Composer

Add it in your main thelia composer.json file

```
composer require thelia/info-banner-module:~1.0
```

## Usage

Configure the catchphrase in the module configuration page.
Add the loop `infobanner` in your template html file

Example :
```html
{include file="../../../local/modules/InfoBanner/templates/frontOffice/default/infobanner.html"}

{ifloop rel="infobanner"}
    <header class="fixed flex w-full uppercase md:sticky infoBannerActive"></header>
{/ifloop}
```
