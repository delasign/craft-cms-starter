<?php
/**
 * Subscription module for Craft CMS 4.x
 *
 * A module to manage the subscribers of delasign.com.
 *
 * @link      https://www.delasign.com
 * @copyright Copyright (c) 2022 Oscar de la Hera Gomez
 */

namespace subscription\controllers;

/**
 * New Subscriber Controller
 *
 * This controller carries a single action responsible for processing POST requests
 * that wish to create a new user that is subscribed to the delasign newsletter.
 * 
 * Generally speaking, controllers are the middlemen between the front end of
 * the CP/website and your module’s services. They contain action methods which
 * handle individual tasks.
 *
 * A common pattern used throughout Craft involves a controller action gathering
 * post data, saving it on a model, passing the model off to a service, and then
 * responding to the request appropriately depending on the service method’s response.
 *
 * Action methods begin with the prefix “action”, followed by a description of what
 * the method does (for example, actionSaveIngredient()).
 *
 * https://craftcms.com/docs/plugins/controllers
 *
 * @author    Oscar de la Hera Gomez
 * @package   Subscription Module
 * @since     1.0.0
 */
use Craft;
use craft\helpers\App;
use craft\web\Controller;
use yii\web\HttpException;
use yii\web\Response;

class NewSubscriberController extends Controller
{
    // Protected Properties
    // =========================================================================

     /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected int|bool|array $allowAnonymous = true;

    
    
    // Public Methods
    // =========================================================================

    public function beforeAction($action): bool
    {
        // Disable protection for the action 'resolve-request'.
        // The actions must be in 'kebab-case'.
        if ($action->id === 'resolve-request') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }
    
    /**
     * Handle a request going to our module's actionNewSubscriber URL,
     * e.g.: subscription/new-subscriber/resolve-request
     *
     * @return mixed
     */
    public function actionResolveRequest(): Response
    { 
        return $this->asJSON("Recieved New Subscriber Request");
    }
}