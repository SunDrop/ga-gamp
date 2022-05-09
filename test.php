<?php

use GA\Gamp;

require_once __DIR__ . '/src/GA/Gamp.php';

$gamp = new Gamp();

$gamp
    ->setTrackingID('UA-228278710-1')
    ->setHitType(Gamp::HIT_TYPES['event'])
    ->setClientID('cid-007')
    ->setEventCategory('HW')
    ->setEventAction('Backend')
    ->setEventLabel('Info')
    ->setEventValue(random_int(1, 100));

$result = $gamp->sendEvent();
var_dump($result);
