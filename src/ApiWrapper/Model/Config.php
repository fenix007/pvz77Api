<?php

namespace SpExt\ApiWrapper\Model;

use SpExt\ApiWrapper\Http\IRequestObject;
use SpExt\ApiWrapper\Http\Rules;
use Symfony\Component\Validator\Constraints\NotBlank;

class Config extends AbstractModel implements IRequestObject
{
    /** @var  string */
    public $login;

    /** @var  string */
    public $password;

    /** @var  string */
    public $url;

    /** @var bool */
    public $cacheEnabled = false;

    /** @var int */
    public $cacheTime = 1800;

    /** @var string  */
    public $cachePath = '/tmp';

    public $cachedMethods = [
        'GET' => true,
        'POST' => true
    ];

    /** @var bool  */
    public $logEnabled = false;

    /** @var string  */
    public $logPath = '/tmp/apiwrapper.log';

    /**
     * Config for init guzzle client
     *
     * @var array
     */
    public $guzzleConfig = [];

    public static function rules()
    {
        return Rules::create([
            'login'    => [new NotBlank()],
            'password' => [new NotBlank()],
            'url'      => [new NotBlank()]
        ]);
    }

    public function enableCache()
    {
        $this->cacheEnabled = true;

        return $this;
    }

    public function disableCache()
    {
        $this->cacheEnabled = false;

        return $this;
    }
}
