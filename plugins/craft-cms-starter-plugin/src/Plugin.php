<?php
namespace delasign\craftcmsstarterplugin;


use Craft;
use craft\services\Plugins;

class Plugin extends \craft\base\Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var Plugin
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public string $schemaVersion = "1.0.0";

    /**
     * @var bool
     */
    public bool $hasCpSettings = false;

    /**
     * @var bool
     */
    public bool $hasCpSection = false;

    public function init()
    {
        parent::init();
        // Define a custom alias named after the namespace
        Craft::setAlias('@craftcmsstarterplugin', __DIR__);
        // Custom initialization code goes here...
        self::$plugin = $this;
    }
}