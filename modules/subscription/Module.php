<?php
namespace subscription;

use Craft;
// The following imports are required to listen for the the RESTful requests
use craft\events\RegisterUrlRulesEvent;
use craft\web\UrlManager;
use yii\base\Event;

class Module extends \yii\base\Module
{
    public function init()
    {
        // Define a custom alias named after the namespace
        Craft::setAlias('@subscription', __DIR__);

        // Set the controllerNamespace based on whether this is a console or web request
        if (Craft::$app->getRequest()->getIsConsoleRequest()) {
            $this->controllerNamespace = 'subscription\\console\\controllers';
        } else {
            $this->controllerNamespace = 'subscription\\controllers';
        }

        parent::init();

        // Add Events for the URL Rules
        Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function(RegisterUrlRulesEvent $event) {
                $event->rules = array_merge($event->rules, [
                    'GET api/getall' => 'subscription/get-all-subscribers/resolve-request',
                    'POST api/subscribe' => 'subscription/new-subscriber/resolve-request',
                    'PUT api/unsubscribe' => 'subscription/un-subscribe/resolve-request',
                    'DELETE api/delete' => 'subscription/delete/resolve-request',
               ]);
            }
        );
    }
}