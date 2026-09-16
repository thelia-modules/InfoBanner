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
use InfoBanner\Model\Infobanner;
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

        try {
            $title = trim((string) $this->validateForm($form)->get('title')->getData());

            // One row at most: the screen edits a single banner, so the write replaces it rather
            // than piling up rows the front would have to choose between.
            InfobannerQuery::create()->deleteAll();

            if ('' !== $title) {
                (new Infobanner())->setTitle($title)->save();
            }
        } catch (\Exception $exception) {
            $this->setupFormErrorContext(
                Translator::getInstance()?->trans('Banner configuration'),
                $exception->getMessage(),
                $form,
                $exception,
            );

            return $this->generateErrorRedirect($form) ?? $this->redirectToConfiguration();
        }

        return $this->generateSuccessRedirect($form) ?? $this->redirectToConfiguration();
    }

    /**
     * Where the screen goes when the form carries no success_url or error_url of its own —
     * back to the module configuration, rather than a null response the kernel cannot serve.
     */
    private function redirectToConfiguration(): Response
    {
        return $this->generateRedirect('/admin/module/InfoBanner');
    }
}
