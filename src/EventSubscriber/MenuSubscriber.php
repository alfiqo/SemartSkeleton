<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\EventSubscriber;

use KejawenLab\Semart\Skeleton\Application;
use KejawenLab\Semart\Skeleton\Entity\Menu;
use KejawenLab\Semart\Skeleton\Repository\MenuRepository;
use KejawenLab\Semart\Skeleton\Request\FilterRequest;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class MenuSubscriber implements EventSubscriberInterface
{
    private $menuRepository;

    public function __construct(MenuRepository $menuRepository)
    {
        $this->menuRepository = $menuRepository;
    }

    public function filterRequest(FilterRequest $event)
    {
        $request = $event->getRequest();
        $object = $event->getObject();
        if (!$object instanceof Menu) {
            return;
        }

        $parentId = $request->request->get('parent');
        $request->request->remove('parent');

        if (!$parentId) {
            return;
        }

        $object->setParent($this->menuRepository->find($parentId));
    }

    public static function getSubscribedEvents()
    {
        return [
            Application::REQUEST_EVENT => [['filterRequest']],
        ];
    }
}
