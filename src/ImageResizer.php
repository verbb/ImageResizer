<?php
namespace verbb\imageresizer;

use verbb\imageresizer\base\PluginTrait;
use verbb\imageresizer\models\Settings;

use Craft;
use craft\base\Model;
use craft\base\Plugin;
use craft\elements\Asset;
use craft\events\RegisterUrlRulesEvent;
use craft\events\RegisterUserPermissionsEvent;
use craft\helpers\UrlHelper;
use craft\services\UserPermissions;
use craft\web\UrlManager;

use yii\base\Event;
use craft\base\Element;

class ImageResizer extends Plugin
{
    // Properties
    // =========================================================================

    public bool $hasCpSettings = true;
    public string $schemaVersion = '2.0.0';


    // Traits
    // =========================================================================

    use PluginTrait;


    // Public Methods
    // =========================================================================

    public function init(): void
    {
        parent::init();

        self::$plugin = $this;

        $this->_registerEventHandlers();

        if (Craft::$app->getRequest()->getIsCpRequest()) {
            $this->_registerCpRoutes();
        }
        
        if (Craft::$app->getEdition() !== Craft::Solo) {
            $this->_registerPermissions();
        }
    }

    public function getSettingsResponse(): mixed
    {
        return Craft::$app->getResponse()->redirect(UrlHelper::cpUrl('image-resizer/settings'));
    }

    // Protected Methods
    // =========================================================================

    protected function createSettingsModel(): Settings
    {
        return new Settings();
    }


    // Private Methods
    // =========================================================================

    private function _registerCpRoutes(): void
    {
        Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_CP_URL_RULES, function(RegisterUrlRulesEvent $event) {
            $event->rules = array_merge($event->rules, [
                'image-resizer' => 'image-resizer/logs/logs',
                'image-resizer/logs' => 'image-resizer/logs/logs',
                'image-resizer/logs/clear' => 'image-resizer/logs/clear',
                'image-resizer/settings' => 'image-resizer/base/settings',
                'image-resizer/clear-tasks' => 'image-resizer/base/clear-tasks',
            ]);
        });
    }

    private function _registerPermissions(): void
    {
        Event::on(UserPermissions::class, UserPermissions::EVENT_REGISTER_PERMISSIONS, function(RegisterUserPermissionsEvent $event) {
            $event->permissions[] = [
                'heading' => Craft::t('image-resizer', 'Image Resizer'),
                'permissions' => [
                    'imageResizer-resizeImage' => ['label' => Craft::t('image-resizer', 'Resize images')],
                ],
            ];
        });
    }

    private function _registerEventHandlers(): void
    {
        Event::on(Asset::class, Asset::EVENT_BEFORE_HANDLE_FILE, [$this->getService(), 'beforeHandleAssetFile']);
        Event::on(Asset::class, Element::EVENT_REGISTER_ACTIONS, [$this->getService(), 'registerAssetActions']);
    }

}
