<?php
/**
 * Rate Me plugin for Craft CMS 3.x
 *
 * Simple Rating plugin
 *
 * @link      http://ournameismud.co.uk/
 * @copyright Copyright (c) 2019 @cole007
 */

namespace ournameismud\rateme;

use ournameismud\rateme\services\RateMeService as RateMeServiceService;
use ournameismud\rateme\variables\RateMeVariable;
use ournameismud\rateme\models\Settings;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\web\UrlManager;
use craft\web\twig\variables\CraftVariable;
use craft\events\RegisterUrlRulesEvent;

use yii\base\Event;
use yii\web\User;
use yii\web\UserEvent;

/**
 * Class RateMe
 *
 * @author    @cole007
 * @package   RateMe
 * @since     1
 *
 * @property  RateMeServiceService $rateMeService
 */
class RateMe extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var RateMe
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    protected $sessionName = 'ournameismud_rateme';

    /**
     * @var string
     */
    public $schemaVersion = '1';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['siteActionTrigger1'] = 'rate-me/default';
            }
        );

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['cpActionTrigger1'] = 'rate-me/default/do-something';
            }
        );

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('rateMe', RateMeVariable::class);
            }
        );


        Event::on(
            User::class,
            User::EVENT_AFTER_LOGIN,
            function (UserEvent $userEvent) {
                
                $session = Craft::$app->getSession();
                $sessionName = $session[$this->sessionName];
                $this->rateMeService->convertRatings($sessionName);
            }
        );

        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                }
            }
        );

        Craft::info(
            Craft::t(
                'rate-me',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

    public $hasCpSettings = true;
    /**
     * @inheritdoc
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * @inheritdoc
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'rate-me/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }
}
