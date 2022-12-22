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
 * Unsubscribe Controller
 *
 * This controller carries a single action responsible for processing POST requests
 * that wish to unsubscribe an existing new user that is subscribed to the 
 * delasign newsletter.
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
// Import Entry to allow for entries to work
use craft\elements\Entry;

class UnSubscribeController extends Controller
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
            * If you wish to use a conventional HTTPException use the line below.
            * throw new HttpException(403, 'Unauthorised API key.');
            *
            * We prefer to use a standardized response.
            */
            $this->sendResponse(403, 'Unauthorised API key.', null);
            return false;
        }
        

        return parent::beforeAction($action);
    }

    /**
     * Handle a request going to our module's actionUnSubscribe URL,
     * e.g.: subscription/un-subscribe/resolve-request
     * The actions must be in 'kebab-case'
     *
     * @return mixed
     */
    public function actionResolveRequest(): Response
    { 
        // Get the body of the API call
        $body = Craft::$app->request->getBodyParams();
        // Verify that all the neccesary body parameters exist
        // In this case, we need to check that the name and email exist.

        if (isset($body['email'])) {
            $email = $body['email'];
        } else {
            return $this->sendResponse(400,'Body is missing email variable.', null);
        }

        // Get the subscriber section
        $subscribers = Entry::find()->section(["subscribers"]);
        //Check that the email does not exist
        $emailExists = $subscribers->email($email)->count() == 1;
        // If it exist, if it doesn't do not continue
        if ($emailExists) {
            // Get the subscriber
            $subscriber = $subscribers->email($email)->one();
            // Modify the subscriber by setting subscribed to false;
            $subscriber->subscribed = false;
            // Save the entry and set whether it succeeded to a $success parameter.
            $success = Craft::$app->elements->saveElement($subscriber);
            // Check if it succesful
            if ($success) {
                // If it succeeds send a successful response
                return $this->sendResponse(200, null, "Subscriber with email " . $email . " has been unsubscribed.");
            } else {
                // Else send a 400
                return $this->sendResponse(400,"Failed to unsubscribe subscriber with email: ". $email .".", null);
            }
        }
        else {
            return $this->sendResponse(400,'A subscriber does not exist with email:'. $email . '.', null);   
        }
    }

    /**
     * Send a response based on a status code ($code), an error ($error) & a response ($response).
     *
     * @return array
     */

    public function sendResponse(int $code, mixed $error, mixed $response) {
        return $this->asJSON([
            'statusCode' => $code,
            'headers' => [
                "Access-Control-Allow-Origin"  => "*", // Required for CORS support to work
                "Access-Control-Allow-Credentials" => true, // Required for cookies, authorization headers with HTTPS
                "Content-Type" => "application/json"
            ],
            'body' => [
                'error' => $error,
                'response' => $response
            ]
        ]);
    }
}