<?php

declare(strict_types=1);

/*
 * This file is part of the Thelia package.
 * http://www.thelia.net
 *
 * (c) OpenStudio <info@thelia.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace InfoBanner\Hook\Theme;

use InfoBanner\Service\BannerReader;
use Thelia\Core\Hook\Theme\ThemeHookInterface;
use Twig\Environment;

/**
 * Draws the banner at the top of the front office.
 *
 * `layout.body.top` is the first point a Flexy layout opens inside <body>, above the header,
 * which is where the banner belongs. A theme that wants it elsewhere decorates this service
 * and answers another hook point; a theme that wants another rendering shadows the template.
 */
final readonly class InfoBannerThemeHook implements ThemeHookInterface
{
    private const string HOOK = 'layout.body.top';

    public function __construct(
        private Environment $twig,
        private BannerReader $bannerReader,
    ) {
    }

    public function supports(string $hookName): bool
    {
        return self::HOOK === $hookName;
    }

    public function render(string $hookName, array $parameters): string
    {
        $text = $this->bannerReader->text();

        if (!$this->supports($hookName) || null === $text) {
            return '';
        }

        return $this->twig->render('@InfoBannerModule/theme-hook/info-banner.html.twig', [
            'text' => $text,
        ]);
    }
}
