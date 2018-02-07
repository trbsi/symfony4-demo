<?php
namespace App;

use Symfony\Component\EventDispatcher\GenericEvent;

class CommentListener
{
    public function onCommentCreated(GenericEvent $event)
    {
        dump($event); die;
    }
}