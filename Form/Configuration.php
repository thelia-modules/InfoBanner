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
    protected function buildForm(): void
    {
        $this->formBuilder->add(
            'title',
            TextType::class,
            [
                'data' => InfobannerQuery::create()->findOne()?->getTitle(),
                'required' => false,
                'label' => Translator::getInstance()?->trans('Banner text', [], InfoBanner::DOMAIN_NAME),
                'label_attr' => ['for' => 'title'],
                // The column is a VARCHAR(255): a longer line would be cut on save, silently.
                'constraints' => [new Length(max: 255)],
            ],
        );
    }

    public static function getName(): string
    {
        return 'infobanner';
    }
}
