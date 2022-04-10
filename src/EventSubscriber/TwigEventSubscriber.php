<?php

namespace App\EventSubscriber;

use App\Repository\EtablissementRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Twig\Environment;

class TwigEventSubscriber implements EventSubscriberInterface
{

    private $twig;
    private $etablissementRepository;

    public function __construct(Environment $twig, EtablissementRepository $etablissementRepository)
    {
        $this->twig = $twig;
        $this->etablissementRepository = $etablissementRepository;

    }

    public function onControllerEvent(ControllerEvent $event)
    {
        $etablissements = $this->etablissementRepository->findAll();

        $this->twig->addGlobal('etablissements', $etablissements);
    }

    public static function getSubscribedEvents()
    {
        return [
            ControllerEvent::class => 'onControllerEvent',
        ];
    }
}

