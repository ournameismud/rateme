<?php
/**
 * Rate Me plugin for Craft CMS 3.x
 *
 * Simple Rating plugin
 *
 * @link      http://ournameismud.co.uk/
 * @copyright Copyright (c) 2019 @cole007
 */

namespace ournameismud\rateme\controllers;

use ournameismud\rateme\RateMe;

use Craft;
use craft\web\Controller;

/**
 * @author    @cole007
 * @package   RateMe
 * @since     1
 */
class DefaultController extends Controller
{

    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected $allowAnonymous = ['index', 'rate'];

    // Public Methods
    // =========================================================================

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $result = 'Welcome to the DefaultController actionIndex() method';

        return $result;
    }

    /**
     * @return mixed
     */
    public function actionRate()
    {
        $this->requirePostRequest();
        $request = Craft::$app->getRequest();
        $rating = $request->getBodyParam('rating');
        $elementId = $request->getBodyParam('elementId');

        $settings = RateMe::$plugin->getSettings();
        $loggedIn = $settings->loggedIn;
        $user = Craft::$app->getUser();  
        if($loggedIn && $user->id == null) { 
            $message = 'Login required to post rating';
            if ($request->getAcceptsJson()) {
                return $this->asJson(['response' => $message]);
            } else {
                Craft::$app->getSession()->setNotice( $message );
                return $this->redirectToPostedUrl();
            }   
        }

        $record = RateMe::getInstance()->rateMeService->addRating($rating,$elementId);
        $message = 'Rating added';
        if ($request->getAcceptsJson()) {
            return $this->asJson(['response' => $message, 'rating' => $record]);
        } else {
            Craft::$app->getSession()->setNotice($message);
            return $this->redirectToPostedUrl();
        }   
    }
}
