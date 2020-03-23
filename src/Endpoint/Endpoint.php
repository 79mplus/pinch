<?php
/**
 * Created by PhpStorm.
 * User: jafor
 * Date: 2020-03-19
 * Time: 11:42 AM
 */

namespace mplus\Pinch\Endpoint;


/**
 * Class Endpoint
 *
 * @package Paylike\Endpoint
 */
abstract class Endpoint
{
    /**
     * @var \Pinch\Pinch
     */
    protected $pinch;

    /**
     * Endpoint constructor.
     *
     * @param $pinch
     */
    function __construct($pinch)
    {
        $this->pinch = $pinch;
    }
}