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
 * The banner text.
 *
 * The table holds at most one row — the configuration screen empties it before writing — so
 * "no banner" and "an empty banner" are the same thing here, and both mean nothing is drawn.
 *
 * Nothing is kept between calls on purpose: the service is shared, and on a worker runtime
 * (FrankenPHP, RoadRunner) a value cached in a property would outlive the request that read it,
 * and the banner would go on showing the line an editor has already taken down. One hook point
 * asks once per page, so there is nothing to spare anyway.
 */
final readonly class BannerReader
{
    public function text(): ?string
    {
        return trim((string) InfobannerQuery::create()->findOne()?->getTitle()) ?: null;
    }
}
