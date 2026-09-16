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

namespace InfoBanner\Hook\Back;

use InfoBanner\Form\Configuration;
use InfoBanner\InfoBanner;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Form\FormServiceInterface;
use Thelia\Core\Hook\BaseHook;
use Thelia\Core\Template\Parser\ParserResolver;

/**
 * Draws the banner text field on the module's own configuration screen.
 */
final class ConfigHook extends BaseHook
{
    public function __construct(
        private readonly FormServiceInterface $formService,
        ?EventDispatcherInterface $dispatcher = null,
        ?ParserResolver $parserResolver = null,
    ) {
        parent::__construct($dispatcher, $parserResolver);
    }

    /**
     * @return array<string, list<array{type: string, method: string}>>
     */
    public static function getSubscribedHooks(): array
    {
        return [
            'module.configuration' => [
                ['type' => 'back', 'method' => 'onModuleConfiguration'],
            ],
        ];
    }

    public function onModuleConfiguration(HookRenderEvent $event): void
    {
        $form = $this->formService->getFormByName(Configuration::getName());

        $event->add($this->render('InfoBanner/module-config.html.twig', [
            'form' => $form->createView(),
            // Where the form posts back to. The screen is drawn by whoever renders
            // module.configuration, so the page it lives on is not the template's to guess.
            'configuration_url' => '/admin/module/'.InfoBanner::getModuleCode(),
        ]));
    }
}
