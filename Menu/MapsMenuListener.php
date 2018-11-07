<?php

namespace Disjfa\MapsBundle\Menu;

use Disjfa\MenuBundle\Menu\ConfigureMenuEvent;
use Symfony\Component\Translation\TranslatorInterface;

class MapsMenuListener
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * HomeMenuListener constructor.
     *
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param ConfigureMenuEvent $event
     */
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();

        $menu->addChild('maps', [
            'route' => 'disjfa_map_map_index',
            'label' => $this->translator->trans('menu.maps', [], 'disjfa-maps'),
        ])->setExtra('icon', 'fa-map');
    }
}
