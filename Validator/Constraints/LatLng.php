<?php

namespace Oh\GoogleMapFormTypeBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD)]
class LatLng extends Constraint
{
    public $message = 'The values for latitude and longitude ("%latitude%" and "%longitude%") are not valid.';
}
