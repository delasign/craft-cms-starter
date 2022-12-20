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
 * Get All Subscribers Controller
 *
 * This controller carries a single action responsible for processing GET requests
 * that wish to get the details for subscribers.
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

class GetAllSubscribersController extends Controller
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
        // DISABLE CSRF PROTECTION TO ALLOW FOR RESTFUL REQUESTS THAT ARE NOT GET REQUESTS
        // =========================================================================
        // Disable protection for the action 'resolve-request'.
        // The actions must be in 'kebab-case'.
        if ($action->id === 'resolve-request') {
            $this->enableCsrfValidation = false;
        }
        // API KEY VALIDATION
        // =========================================================================
        // Get the API Key
        $apiKey = App::env('CRAFT_ENDPOINT_API_KEY');
        // Get the API Key through the x-api-key parameter
        $key = Craft::$app->getRequest()->getParam('x-api-key', '');

         // Verify provided key against API key
        if (empty($key) || empty($apiKey) || $key != $apiKey) {
            /* Throw a 403 - FORBIDDEN, if there 
            * - is no API Key in Craft
            * - there is no API Key in the request
            * - there API key does not match that of the request
            */
            throw new HttpException(403, 'Unauthorised API key.');
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
        return $this->asJSON("Here are all the details for our subscribers.");
    }
}