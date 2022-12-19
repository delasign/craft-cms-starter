<?php
namespace subscription;

use Craft;

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

        // Custom initialization code goes here...
    }
}