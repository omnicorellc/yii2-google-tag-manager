<?php

namespace omnicorellc\googleTagManager;

use Yii;
use yii\base\BaseObject;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\helpers\Json;
use yii\web\View;

/**
 * Class GoogleTagManager
 * @package omnicorellc\googleTagManager
 */
class GoogleTagManager extends BaseObject implements BootstrapInterface
{
    /** @const string event upon occurrence of which the data layer variable rendering process will be launched */
    const EVENT_RENDER_DATA_LAYER = 'renderDataLayerEvent';
    /** @const string event upon occurrence of which the GTM js script rendering process will be launched */
    const EVENT_RENDER_CONTAINER_JS = 'renderContainerJsEvent';
    /** @const string event upon occurrence of which the GTM frame rendering process will be launched */
    const EVENT_RENDER_CONTAINER_FRAME = 'renderContainerFrameEvent';

    /** @var string|null */
    public $tagManagerId;
    /** @var string */
    public $tagManagerPrefix = 'GTM-';
    /** @var string */
    public $sessionKey = 'google-tag-manager-data-layer';
    /** @var string */
    public $frameSrc = '//www.googletagmanager.com/ns.html';
    /** @var string */
    public $jsScriptSrc = '//www.googletagmanager.com/gtm.js';
    /** @var string */
    public $dataLayerViewFilePath;
    /** @var string */
    public $containerJsViewFilePath;
    /** @var string */
    public $containerFrameViewFilePath;
    /** @var array */
    protected $_dataLayerForCurrentRequest = [];

    /**
     * GoogleTagManager constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        if (empty($this->dataLayerViewFilePath)) {
            $this->dataLayerViewFilePath = __DIR__.'/views/dataLayer.php';
        }
        if (empty($this->containerJsViewFilePath)) {
            $this->containerJsViewFilePath = __DIR__.'/views/containerJs.php';
        }
        if (empty($this->containerFrameViewFilePath)) {
            $this->containerFrameViewFilePath = __DIR__.'/views/containerFrame.php';
        }
    }

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        if (!$app->getRequest()->getIsGet() || $app->getRequest()->getIsAjax()) {
            return;
        }
        $app->getView()->on(self::EVENT_RENDER_DATA_LAYER,[$this,'renderDataLayer']);
        $app->getView()->on(self::EVENT_RENDER_CONTAINER_JS,[$this,'renderContainerJs']);
        $app->getView()->on(self::EVENT_RENDER_CONTAINER_FRAME,[$this,'renderContainerFrame']);
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function dataLayerPushItemDelay($key, $value)
    {
        $session = Yii::$app->getSession();

        $dataLayerItems = $session->get($this->sessionKey, []);
        $dataLayerItems[] = [$key => $value];

        $session->set($this->sessionKey, $dataLayerItems);
    }

    /**
     * @param string $value
     */
    public function dataLayerPushItemDelayNoKey($value)
    {
        $session = Yii::$app->getSession();

        $dataLayerItems = $session->get($this->sessionKey, []);
        $dataLayerItems[] = $value;

        $session->set($this->sessionKey, $dataLayerItems);
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function dataLayerPushItem($key, $value)
    {
        $this->_dataLayerForCurrentRequest[] = [$key => $value];
    }

    /**
     * Rendering DataLayer data
     *
     * @param Event $event
     */
    public function renderDataLayer(Event $event)
    {
        /* @var $view View */
        $view = $event->sender;
        $dataLayerItems = [];
        //If the session has data for dataLayer, then displays them and remove from the session
        $session = Yii::$app->getSession();
        if ($session->has($this->sessionKey)) {
            $dataLayerItems = $session->get($this->sessionKey, []);
            //Remove data from a session
            $session->remove($this->sessionKey);
        }
        $dataLayerItems = array_merge($dataLayerItems, $this->_dataLayerForCurrentRequest);
        echo $view->renderFile($this->dataLayerViewFilePath, ['dataLayerItems' => $dataLayerItems,]);
    }

    /**
     * Rendering container js script
     *
     * @param Event $event
     */
    public function renderContainerJs(Event $event)
    {
        /* @var $view View */
        $view = $event->sender;
        echo $view->renderFile(
            $this->containerJsViewFilePath,
            [
                'scriptSrc' => $this->jsScriptSrc,
                'tagManagerId' => $this->tagManagerId,
                'tagManagerPrefix' => $this->tagManagerPrefix
            ]
        );
    }

    /**
     * Rendering container frame
     *
     * @param Event $event
     */
    public function renderContainerFrame(Event $event)
    {
        /* @var $view View */
        $view = $event->sender;
        echo $view->renderFile(
            $this->containerFrameViewFilePath,
            [
                'frameSrc' => $this->frameSrc,
                'tagManagerId' => $this->tagManagerId,
                'tagManagerPrefix' => $this->tagManagerPrefix
            ]
        );
    }

    /**
     * Returns a code for triggering on a client side.
     * For example: "dataLayer.push(....);"
     *
     * @param array $variables
     *
     * @return string
     */
    public static function getClientDataLayerPush(array $variables)
    {
        if (count($variables) === 0) {
            return '';
        }
        return 'dataLayer.push(' . Json::encode($variables) . ');';
    }
}
