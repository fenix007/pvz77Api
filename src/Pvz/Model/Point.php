<?php

namespace SpExt\Pvz\Model;

use SpExt\ApiWrapper\Model\AbstractModel;

class Point extends AbstractModel
{
    /** @var  int */
    public $id;

    /** @var  string */
    public $title;

    /** @var  string */
    public $address;

    /** @var  string */
    public $phone;

    /** @var  string */
    public $hours;

    /** @var  string */
    public $description;

    /** @var  string */
    public $longitude;

    /** @var  string */
    public $latitude;
}
