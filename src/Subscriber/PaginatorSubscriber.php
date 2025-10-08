<?php

// file: src/Subscriber/PaginateDirectorySubscriber.php
// requires Symfony\Component\Finder\Finder

namespace App\Subscriber;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Event\ItemsEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class PaginatorSubscriber implements EventSubscriberInterface
{
    public function __construct(
        protected EntityManagerInterface $em,
    ) {
    }

    public function items(ItemsEvent $event): void
    {
        if (false === \is_string($event->target)) {
            return;
        }
        $sql = $event->target.' LIMIT '.$event->getLimit().' OFFSET '.$event->getOffset();
        $stmt = $this->em->getConnection()->prepare($sql);
        $list = $stmt->executeQuery()->fetchAllAssociative();

        $event->items = $list;
        $event->count = $this->getCount();
        $event->stopPropagation();
    }

    protected function getCount()
    {
        return $this
        ->em->getConnection()
        ->executeQuery('SELECT FOUND_ROWS()')
        ->fetchOne();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'knp_pager.items' => ['items', 1/* increased priority to override any internal */],
        ];
    }
}
