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

namespace InfoBanner\Service;

use InfoBanner\Model\InfobannerQuery;

/**
 * The banner text, read once per request.
 *
 * The table holds at most one row — the configuration screen empties it before writing — so
 * "no banner" and "an empty banner" are the same thing here, and both mean nothing is drawn.
 */
final class BannerReader
{
    private ?string $text = null;

    private bool $read = false;

    public function text(): ?string
    {
        if (!$this->read) {
            $this->text = trim((string) InfobannerQuery::create()->findOne()?->getTitle()) ?: null;
            $this->read = true;
        }

        return $this->text;
    }
}
