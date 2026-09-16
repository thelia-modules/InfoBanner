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

namespace InfoBanner\Controller;

use InfoBanner\Form\Configuration;
use InfoBanner\InfoBanner;
use InfoBanner\Model\Infobanner as BannerRow;
use InfoBanner\Model\InfobannerQuery;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Core\Translation\Translator;

class InfoBannerConfigController extends BaseAdminController
{
    #[Route('/admin/module/InfoBanner/save', name: 'infobanner.configuration.save', methods: ['POST'])]
    public function saveAction(): Response
    {
        if (null !== $response = $this->checkAuth([AdminResources::MODULE], ['infobanner'], AccessManager::UPDATE)) {
            return $response;
        }

        $form = $this->createForm(Configuration::getName());
        $translator = Translator::getInstance();

        try {
            $title = trim((string) $this->validateForm($form)->get('title')->getData());

            // One row at most: the screen edits a single banner, so the write replaces it rather
            // than piling up rows the front would have to choose between. An empty field is not an
            // empty row either — it is the banner being taken down.
            InfobannerQuery::create()->deleteAll();

            if ('' !== $title) {
                (new BannerRow())->setTitle($title)->save();
            }

            $this->addFlash('success', '' !== $title
                ? $translator?->trans('Banner saved.', [], InfoBanner::DOMAIN_NAME)
                : $translator?->trans('Banner removed: nothing is shown any more.', [], InfoBanner::DOMAIN_NAME));
        } catch (\Exception $exception) {
            $this->setupFormErrorContext(
                $translator?->trans('Banner configuration', [], InfoBanner::DOMAIN_NAME),
                $exception->getMessage(),
                $form,
                $exception,
            );

            $this->addFlash('danger', $translator?->trans('The banner could not be saved.', [], InfoBanner::DOMAIN_NAME));

            return $this->generateErrorRedirect($form) ?? $this->generateRedirect($this->configurationPath());
        }

        return $this->generateSuccessRedirect($form) ?? $this->generateRedirect($this->configurationPath());
    }

    /**
     * Where the screen goes once it has saved, whatever the outcome: the module's own
     * configuration page, the one the form was drawn on. Built from the module code rather
     * than written out, so renaming the module moves it too.
     */
    private function configurationPath(): string
    {
        return '/admin/module/'.InfoBanner::getModuleCode();
    }
}
