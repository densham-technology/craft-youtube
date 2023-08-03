<?php

namespace denshamtechnology\youtube;

use Craft;
use craft\base\Plugin;
use craft\events\RegisterComponentTypesEvent;
use craft\services\Fields;
use craft\web\twig\variables\CraftVariable;
use denshamtechnology\youtube\fields\PlaylistsField;
use denshamtechnology\youtube\models\Settings;
use denshamtechnology\youtube\services\Videos;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use yii\base\Event;
use yii\base\Exception;
use yii\base\InvalidConfigException;

class YouTube extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * Static property that is an instance of this plugin class so that it can be accessed via
     * YouTube::$plugin
     *
     * @var YouTube
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * To execute your plugin’s migrations, you’ll need to increase its schema version.
     *
     * @var string
     */
    public $schemaVersion = '0.1.0';

    /**
     * Set to `true` if the plugin should have its own settings page in the control panel.
     *
     * @var bool
     */
    public $hasCpSettings = true;

    // Public Methods
    // =========================================================================

    /**
     * Set our $plugin static property to this class so that it can be accessed via
     * Craftbackinstock::$plugin
     *
     * Called after the plugin class is instantiated; do any one-time initialization
     * here such as hooks and events.
     *
     * If you have a '/vendor/autoload.php' file, it will be loaded for you automatically;
     * you do not need to load it in your init() method.
     *
     */
    public function init()
    {
        Event::on(
            Fields::class,
            Fields::EVENT_REGISTER_FIELD_TYPES,
            function(RegisterComponentTypesEvent $event) {
                $event->types[] = PlaylistsField::class;
            }
        );

        parent::init();

        self::$plugin = $this;

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function(Event $e) {
                /** @var CraftVariable $variable */
                $variable = $e->sender;

                // Attach a service:
                $variable->set('youtube', Videos::class);
            }
        );

        /**
         * Logging in Craft involves using one of the following methods:
         *
         * Craft::trace(): record a message to trace how a piece of code runs. This is mainly for development use.
         * Craft::info(): record a message that conveys some useful information.
         * Craft::warning(): record a warning message that indicates something unexpected has happened.
         * Craft::error(): record a fatal error that should be investigated as soon as possible.
         *
         * Unless `devMode` is on, only Craft::warning() & Craft::error() will log to `craft/storage/logs/web.log`
         *
         * It's recommended that you pass in the magic constant `__METHOD__` as the second parameter, which sets
         * the category to the method (prefixed with the fully qualified class name) where the constant appears.
         *
         * To enable the Yii debug toolbar, go to your user account in the AdminCP and check the
         * [] Show the debug toolbar on the front end & [] Show the debug toolbar on the Control Panel
         *
         * http://www.yiiframework.com/doc-2.0/guide-runtime-logging.html
         */
        Craft::info(
            Craft::t(
                'youtube',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    /**
     * @throws InvalidConfigException
     */
    public function getVideos(): Videos
    {
        return $this->get('videos');
    }

    // Protected Methods
    // =========================================================================
    protected function createSettingsModel(): Settings
    {
        return new Settings();
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws Exception
     * @throws LoaderError
     */
    protected function settingsHtml(): ?string
    {
        return Craft::$app->getView()->renderTemplate(
            'youtube/settings',
            ['settings' => $this->getSettings()]
        );
    }
}