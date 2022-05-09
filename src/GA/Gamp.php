<?php

namespace GA;

/**
 * @link https://ga-dev-tools.web.app/hit-builder/
 */
class Gamp
{
    private const GA_URL = 'https://www.google-analytics.com/collect';

    private const GA_DEBUG_URL = 'https://www.google-analytics.com/debug/collect';

    private const PROTOCOL_VERSION = 1;

    public const HIT_TYPES = [
        'pageview' => 'pageview',
        'screenview' => 'screenview',
        'event' => 'event',
        'transaction' => 'transaction',
        'item' => 'item',
        'social' => 'social',
        'exception' => 'exception',
        'timing' => 'timing',
    ];

    private $debugMode = false;

    /**
     * Hit Type
     * The type of interaction collected for a particular user.
     * Possible values: pageview, screenview, event, transaction, item, social, exception, timing
     * @usage t=event
     * @var string
     */
    private $hitType;

    /**
     * Tracking ID/ Web Property ID
     * Required for all hit types.
     * The tracking ID / web property ID. The format is UA-XXXX-Y.
     * All collected data is associated by this ID.
     * @usage Example usage: tid=UA-XXXX-Y
     * @var string
     */
    private $trackingID;

    /**
     * Client ID
     * Optional.
     * This field is required if User ID (uid) is not specified in the request.
     * This pseudonymously identifies a particular user, device, or browser instance.
     * For the web, this is generally stored as a first-party cookie with a two-year expiration.
     * For mobile apps, this is randomly generated for each particular instance of an application install.
     * The value of this field should be a random UUID (version 4) as described in http://www.ietf.org/rfc/rfc4122.txt.
     * @usage Example usage: cid=35009a79-1a05-49d7-b876-2b884d0f825b
     * @var string
     */
    private $clientID;

    /**
     * Required for event hit type.
     * Specifies the event category. Must not be empty.
     *
     * Parameter: ec
     * Value Type: text
     * Default Value: None
     * Max Length: 150 Bytes
     * Supported Hit Types: event
     * @usage Example usage: ec=Category
     * @var string
     */
    private $eventCategory;

    /**
     * Required for event hit type.
     * Specifies the event action. Must not be empty.
     *
     * Parameter: ea
     * Value Type: text
     * Default Value: None
     * Max Length: 500 Bytes
     * Supported Hit Types: event
     * @usage Example usage: ea=Action
     * @var string
     */
    private $eventAction;

    /**
     * Optional.
     * Specifies the event label.
     *
     * Parameter: el
     * Value Type: text
     * Default Value: None
     * Max Length: 500 Bytes
     * Supported Hit Types: event
     * @usage Example usage: el=Label
     * @var string
     */
    private $eventLabel;

    /**
     * Optional.
     * Specifies the event value. Values must be non-negative.
     *
     * Parameter: ev
     * Value Type: integer
     * Default Value: None
     * Max Length: None
     * Supported Hit Types: event
     * @usage Example usage: ev=55
     * @var integer
     */
    private $eventValue;

    /**
     * @param bool $debugMode
     *
     * @return Gamp
     */
    public function setDebugMode(bool $debugMode): Gamp
    {
        $this->debugMode = $debugMode;

        return $this;
    }

    /**
     * @param string $hitType
     *
     * @return Gamp
     */
    public function setHitType(string $hitType): Gamp
    {
        $this->hitType = $hitType;

        return $this;
    }

    /**
     * @param string $trackingID
     *
     * @return Gamp
     */
    public function setTrackingID(string $trackingID): Gamp
    {
        $this->trackingID = $trackingID;

        return $this;
    }

    /**
     * @param string $clientID
     *
     * @return Gamp
     */
    public function setClientID(string $clientID): Gamp
    {
        $this->clientID = $clientID;

        return $this;
    }

    /**
     * @param string $eventCategory
     *
     * @return Gamp
     */
    public function setEventCategory(string $eventCategory): Gamp
    {
        $this->eventCategory = $eventCategory;

        return $this;
    }

    /**
     * @param string $eventAction
     *
     * @return Gamp
     */
    public function setEventAction(string $eventAction): Gamp
    {
        $this->eventAction = $eventAction;

        return $this;
    }

    /**
     * @param string $eventLabel
     *
     * @return Gamp
     */
    public function setEventLabel(string $eventLabel): Gamp
    {
        $this->eventLabel = $eventLabel;

        return $this;
    }

    /**
     * @param int $eventValue
     *
     * @return Gamp
     */
    public function setEventValue(int $eventValue): Gamp
    {
        $this->eventValue = $eventValue;

        return $this;
    }

    public function sendEvent()
    {
        $ch = $this->prepareCurl();
        curl_setopt($ch,CURLOPT_POSTFIELDS, $this->prepareEvent());
        return curl_exec($ch);
    }

    private function prepareCurl()
    {
        $ch = curl_init();
        $url = $this->debugMode ? self::GA_DEBUG_URL : self::GA_URL;
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

        return $ch;
    }

    private function prepareEvent()
    {
        return http_build_query([
            'v' => self::PROTOCOL_VERSION,
            't' => self::HIT_TYPES['event'],
            'tid' => $this->trackingID,
            'cid' => $this->clientID ?? uniqid(),
            'ec' => $this->eventCategory ?? null,
            'ea' => $this->eventAction ?? null,
            'el' => $this->eventLabel ?? null,
            'ev' => $this->eventValue ?? null,
        ]);
    }
}
