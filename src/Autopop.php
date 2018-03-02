<?php
/**
 * Autopop plugin for Craft CMS 3.x
 *
 * Helps automatically populate entry content.
 *
 * @link      https://onedesigncompany.com
 * @copyright Copyright (c) 2018 One Design Company
 */

namespace onedesign\autopop;

use onedesign\autopop\variables\AutopopVariable;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\web\twig\variables\CraftVariable;

use yii\base\Event;

/**
 * Class Autopop
 *
 * @author    One Design Company
 * @package   Autopop
 * @since     1.0.0
 *
 */
class Autopop extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var Autopop
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '1.0.0';

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
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('autopop', AutopopVariable::class);
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
                'autopop',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

}
