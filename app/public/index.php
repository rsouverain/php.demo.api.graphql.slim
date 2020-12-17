<?php

$now = new \DateTime('now', new \DateTimeZone('UTC'));
$now->setTimezone(new \DateTimeZone('Europe/Paris'));
echo "Hello World !! ".$now->format(\DateTimeInterface::ISO8601);
