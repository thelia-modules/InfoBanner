<?php
/*************************************************************************************/
/*      This file is part of the GoogleTagManager package.                           */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace InfoBanner\Form;


use InfoBanner\InfoBanner;
use InfoBanner\Model\InfobannerQuery;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;

/**
 * Class Configuration
 * @package InfoBanner\Form
 * @author Loïc MO <lmo@openstudio.fr>
 */
class Configuration extends BaseForm
{
    protected function buildForm(): void
    {
        $form = $this->formBuilder;

        $lang = $this->getRequest()->getSession()?->get('thelia.admin.edition.lang');
        $data = InfobannerQuery::create()->findOne();

        $form->add(
            "infoBannerId",
            TextType::class,
            array(
                'data'  => $data?->getTitle(),
                'label' => Translator::getInstance()?->trans("Title",[] ,InfoBanner::DOMAIN_NAME),
                'label_attr' => array(
                    'for' => "infoBannerId"
                ),
            )
        );
    }

    public static function getName(): string
    {
        return 'infobanner';
    }
}
