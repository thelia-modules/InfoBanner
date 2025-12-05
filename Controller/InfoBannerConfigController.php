<?php

namespace InfoBanner\Controller;

use InfoBanner\Form\Configuration;
use InfoBanner\Model\InfobannerQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Routing\Annotation\Route;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\Session\Session;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Core\Translation\Translator;

class InfoBannerConfigController extends BaseAdminController
{
    #[Route("/admin/module/InfoBanner/save", name:"infobanner.configuration.form", methods: ["POST"])]
    public function saveAction(Session $session)
    {
        if (null !== $response = $this->checkAuth([AdminResources::MODULE], ["infobanner"], AccessManager::VIEW)) {
            return $response;
        }

        $form = $this->createForm(Configuration::class);
        $response = null;

        try {
            InfobannerQuery::create()->deleteAll();
        } catch (PropelException $e) {}

        try {
            $vform = $this->validateForm($form);
            $data = $vform->getData();
            $lang = $session->get('thelia.admin.edition.lang');

            $infoBanner = new \InfoBanner\Model\Infobanner();
            $infoBanner
                ->setTitle($data['infoBannerId'])
                ->save();

        } catch (\Exception $e) {
            $this->setupFormErrorContext(
                Translator::getInstance()?->trans("Syntax error"),
                $e->getMessage(),
                $form,
                $e
            );
        }

        return $this->generateSuccessRedirect($form);
    }
}