<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: cf/cf.proto

namespace Cf;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>cf.CharacterizationInput</code>
 */
class CharacterizationInput extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>string platform = 1;</code>
     */
    private $platform = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $platform
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Cf\Cf::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string platform = 1;</code>
     * @return string
     */
    public function getPlatform()
    {
        return $this->platform;
    }

    /**
     * Generated from protobuf field <code>string platform = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setPlatform($var)
    {
        GPBUtil::checkString($var, True);
        $this->platform = $var;

        return $this;
    }

}

