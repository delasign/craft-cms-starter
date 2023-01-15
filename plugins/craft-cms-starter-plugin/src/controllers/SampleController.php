<?php
/**
 * SampleController for Craft CMS 4.x
 *
 * A Controller to manage the example of calling a controller action through js.
 *
 * @link      https://www.delasign.com
 * @copyright Copyright (c) 2022 Oscar de la Hera Gomez
 */

namespace delasign\craftcmsstarterplugin\controllers;

/**
 * Sample Controller
 *
 * This controller carries the actions that help you understand how controller
 * actions can be invoked through JS.
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
 * @package   Craft CMS Starter Plugin
 * @since     1.0.0
 */
use Craft;
use craft\helpers\App;
use craft\web\Controller;
use yii\web\HttpException;
use yii\web\Response;

class SampleController extends Controller
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
        return parent::beforeAction($action);
    }


    /**
     * Handle a sample controller action
     * e.g.: craftcmsstarterplugin/sample/sample-invocation
     *
     * @return redirect
     */

    public function actionSampleInvocation(Int $id): Response
    { 
        // All actions must end in a redirect
        return $this->redirect('craftcmsstarterplugin/?id=' . $id);
    }
}