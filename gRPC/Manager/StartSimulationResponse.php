<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: manager/manager.proto

namespace Manager;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>manager.StartSimulationResponse</code>
 */
class StartSimulationResponse extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>string simulation_uuid = 1;</code>
     */
    private $simulation_uuid = '';
    /**
     * Generated from protobuf field <code>string result = 2;</code>
     */
    private $result = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $simulation_uuid
     *     @type string $result
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Manager\Manager::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string simulation_uuid = 1;</code>
     * @return string
     */
    public function getSimulationUuid()
    {
        return $this->simulation_uuid;
    }

    /**
     * Generated from protobuf field <code>string simulation_uuid = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setSimulationUuid($var)
    {
        GPBUtil::checkString($var, True);
        $this->simulation_uuid = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string result = 2;</code>
     * @return string
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Generated from protobuf field <code>string result = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setResult($var)
    {
        GPBUtil::checkString($var, True);
        $this->result = $var;

        return $this;
    }

}
