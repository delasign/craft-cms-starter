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
// Import Entry to allow for entries to work
use craft\elements\Entry;

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
        // Get the body of the API call
        $body = Craft::$app->request->getBodyParams();
        // Verify that all the neccesary body parameters exist
        // In this case, we need to check that the name and email exist.

        if (isset($body['email'])) {
            $email = $body['email'];
        } else {
            return $this->sendResponse(400,'Body is missing email variable.', null);
        }

        if (isset($body['name'])) {
            $name = $body['name'];
        } else {
            return $this->sendResponse(400,'Body is missing name variable.', null);
        }

        // Get the subscriber section
        $subscribers = Entry::find()->section(["subscribers"]);
        //Check that the email does not exist
        $emailExists = $subscribers->email($email)->count() > 0;
        // If it exist, do not continue
        if ($emailExists) {
            // return $this->asJSON("Email Already Exists.");
            return $this->sendResponse(400,"Email Already Exists.", null);
        } else {
            // If it doesn't exist, create new subscriber entry
            $newSubscriber = new Entry();
            // Set the section Id to match that of the subscribers
            $newSubscriber->sectionId = $subscribers->sectionId[0];
            // Set the field values
            $newSubscriber->setFieldValues([
                "email" => $email,
                "subscriberName" => $name,
                "subscribed" => true
            ]);
            // Save the entry and set wheter it succeeded to a $success parameter.
            $success = Craft::$app->elements->saveElement($newSubscriber);
            // Check if it s
            if ($success) {
                // If it succeeds send a successful response
                return $this->sendResponse(200, null, "Succesfully created a subscriber with email: ". $email);
            } else {
                // Else send a 400
                return $this->sendResponse(400,"Failed to create a subscriber.", null);
                // return throw new HttpException(400, "Couldn't save the entry");
            }
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
