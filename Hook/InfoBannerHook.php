<?php
/*************************************************************************************/
/*      This file is part of the module FeatureType                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace InfoBanner\Hook;

use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

/**
 * Class InfoBannerHook
 * @author Loïc MO <lmot@openstudio.com>
 */
class InfoBannerHook extends BaseHook
{
    public function onModuleConfig(HookRenderEvent $event): void
    {
        $event->add($this->render('infobanner-configuration.html'));
    }
}
