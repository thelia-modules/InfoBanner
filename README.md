# InfoBanner

A single line of text drawn across the top of every front-office page — a delivery notice, a
holiday closing, an operation and its coupon code. It is edited from the module's own
configuration screen, and an empty field draws nothing at all.

## Requirements

Thelia 3.0 or newer. Version 1.0.0 is the Thelia 2 module and is not compatible with Thelia 3;
the `thelia2` branch keeps it.

## Install

```bash
composer require thelia/info-banner-module
php Thelia module:activate InfoBanner
```

## Use

*Modules > Information banner > Configure*: one field, the banner text. Saving an empty field
removes the banner.

Saving always comes back to that same configuration page — the form carries it in `success_url`
and `error_url` — and says what happened there, as a flash message: the banner was saved, the
banner was removed, or it could not be saved. An editor never lands on the front office to find
out whether the save went through.

A rejected save leaves the banner alone: the row is replaced only once the submitted text has
passed validation, so a line over the column's 255 characters is refused without taking down the
banner that was up.

## Rendering

The module answers the `layout.body.top` theme hook — the first point a Flexy layout opens inside
`<body>`, above the header. Nothing is rendered when the text is empty: the hook returns before the
template.

The markup is deliberately bare, and carries no stylesheet:

```html
<div class="InfoBannerModule">
    <p class="InfoBannerModule-text">…</p>
</div>
```

`.InfoBannerModule` is the surface, `.InfoBannerModule-text` the line. A theme styles those two
classes, or replaces the markup entirely by shadowing
`@InfoBannerModule/theme-hook/info-banner.html.twig` — a Twig namespace is a list of paths and the
first one holding the file wins, so a theme declaring its own path before the module's serves it
instead.

To draw the banner somewhere other than `layout.body.top`, decorate
`InfoBanner\Hook\Theme\InfoBannerThemeHook` and answer another hook point: `DecoratorServicePass`
moves the `thelia.theme_hook` tag to the decorator, so the module stops answering the original
point and no useless read is done on the rest of the site.

## Translations

The strings live in the module's own domain (`I18n/en_US.php`, `I18n/fr_FR.php`) rather than in a
`infobanner.bo.<template>` one, so renaming or moving the back-office template directory does not
silently drop them. Rendering them in the Twig back office relies on the `ModuleAwareTranslator`
decorator shipped with `thelia/backoffice-default-twig-template`, which routes module domains to
the Thelia translator; without it `|trans` falls back to the English source.

## Upgrading from 1.0.0

The `infobanner` table is unchanged — a single row, a single `title` column — so the configured
text survives the upgrade. What changed:

- the configuration form field is named `title` instead of `infoBannerId`, and the form no longer
  carries an id-shaped name for a line of text
- `Loop\IbLoop` and the Smarty templates are gone: Thelia 3 has no Smarty parser, and the front
  template they served was never rendered by anything — the module declared no front hook
- `Config/routing.xml` is gone, the controller route is a `#[Route]` attribute
- saving an empty field now clears the banner instead of storing an empty row
- saving returns to the module configuration page with a flash message instead of falling through
  to whatever `success_url` happened to hold
- the screen opts into the back-office form theme (`{% form_theme form with bo_form_themes only %}`),
  so the field is a Bootstrap `form-control` with its label, its optional marker, its inline help and
  its invalid state, instead of a bare input in a Bootstrap page
