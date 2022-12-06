<?php
/**
 * This file is part of PHP DotEnv
 * @author Vitor Reis <vitor@d5w.com.br>
 */

namespace DotEnv\Common;

use DotEnv\Exception\Runtime;
use DotEnv\Utils\IAdaptor;

/**
 * Trait Adaptors
 * @package DotEnv\Common
 */
trait Adaptors
{
    /**
     * @var IAdaptor[] Adaptors instance
     */
    private $adaptors = [];

    /**
     * Method for add adaptor instance
     * @param  IAdaptor    $adaptor Adaptor instance
     * @param  string|null $key     Key for adaptor, if "null" is adaptor class
     * @return void
     * @throws Runtime
     */
    public function adaptor($adaptor, $key = null)
    {
        if (is_null($key)) {
            $key = get_class($adaptor);
        }

        if (!is_subclass_of($adaptor, IAdaptor::class)) {
            throw new Runtime("Adaptor \"$key\" is not implemented IAdaptor interface");
        }

        $this->adaptors[$key] = $adaptor;
    }

    /**
     * Method for remove adaptor instance
     * @param  string $key Key for remove
     * @return void
     */
    public function removeAdaptor($key)
    {
        unset($this->adaptors[$key]);
    }

    /**
     * Method for get all adaptors instance
     * @return IAdaptor[]
     */
    public function getAdaptors()
    {
        return $this->adaptors;
    }
}
