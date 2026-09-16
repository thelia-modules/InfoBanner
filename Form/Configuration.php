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

namespace InfoBanner\Form;

use InfoBanner\InfoBanner;
use InfoBanner\Model\InfobannerQuery;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;

class Configuration extends BaseForm
{
    /** What the column holds, and what the field stops the editor at. */
    private const int MAX_LENGTH = 255;

    protected function buildForm(): void
    {
        $translator = Translator::getInstance();

        $this->formBuilder->add(
            'title',
            TextType::class,
            [
                'data' => InfobannerQuery::create()->findOne()?->getTitle(),
                'required' => false,
                // Already translated here, through the module's own catalogue: the back-office
                // form theme would otherwise run them through the theme's domain, which knows
                // nothing of this module.
                'translation_domain' => false,
                'label' => $translator?->trans('Banner text', [], InfoBanner::DOMAIN_NAME),
                'help' => $translator?->trans('Shown across the top of every front-office page. Leave it empty to draw no banner at all.', [], InfoBanner::DOMAIN_NAME),
                'attr' => [
                    'maxlength' => self::MAX_LENGTH,
                    'placeholder' => $translator?->trans('Free delivery from €50 with the code LIVRAISON', [], InfoBanner::DOMAIN_NAME),
                ],
                // The field stops the typing, this stops everything else: an import, a script,
                // a browser that ignores maxlength. Without it the column cuts the line in silence.
                'constraints' => [new Length(max: self::MAX_LENGTH)],
            ],
        );
    }

    public static function getName(): string
    {
        return 'infobanner';
    }
}
