<?php
namespace delasign\craftcmsstarterplugin;


use Craft;
use craft\services\Plugins;
use yii\base\Event;

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
    public bool $hasCpSection = true;

    public function init()
    {
        parent::init();
        // Custom initialization code goes here...
        self::$plugin = $this;
    }
}