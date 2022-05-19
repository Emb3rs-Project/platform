<?php
// GENERATED CODE -- DO NOT EDIT!

namespace Cf;

/**
 */
class CFModuleClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * Simulation Convert Sink
     * @param \Cf\PlatformOnlyInput $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function convert_sink(\Cf\PlatformOnlyInput $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/cf.CFModule/convert_sink',
        $argument,
        ['\Cf\ConvertSinkOutput', 'decode'],
        $metadata, $options);
    }

    /**
     * Simulation Convert Sources
     * @param \Cf\ConvertSourceInput $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function convert_source(\Cf\ConvertSourceInput $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/cf.CFModule/convert_source',
        $argument,
        ['\Cf\ConvertSourceOutput', 'decode'],
        $metadata, $options);
    }

    /**
     * Simulation Convert Pinch
     * @param \Cf\PlatformOnlyInput $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function convert_pinch(\Cf\PlatformOnlyInput $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/cf.CFModule/convert_pinch',
        $argument,
        ['\Cf\ConvertPinchOutput', 'decode'],
        $metadata, $options);
    }

    /**
     * Simulation Convert ORC
     * @param \Cf\PlatformOnlyInput $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function convert_orc(\Cf\PlatformOnlyInput $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/cf.CFModule/convert_orc',
        $argument,
        ['\Cf\ConvertOrcOutput', 'decode'],
        $metadata, $options);
    }

    /**
     * Characterization Detailed
     *  rpc char_detailed(CharacterizationInput) returns
     *  (CharacterizationSourceOutput);
     *
     * Characterization Simple
     * @param \Cf\CharacterizationInput $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function char_simple(\Cf\CharacterizationInput $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/cf.CFModule/char_simple',
        $argument,
        ['\Cf\CharacterizationSourceOutput', 'decode'],
        $metadata, $options);
    }

    /**
     * Characterization Building
     * @param \Cf\CharacterizationInput $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function char_building(\Cf\CharacterizationInput $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/cf.CFModule/char_building',
        $argument,
        ['\Cf\CharacterizationSinkOutput', 'decode'],
        $metadata, $options);
    }

    /**
     * Characterization Greenhouse
     * @param \Cf\CharacterizationInput $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function char_greenhouse(\Cf\CharacterizationInput $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/cf.CFModule/char_greenhouse',
        $argument,
        ['\Cf\CharacterizationSinkOutput', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Cf\CharacterizationInput $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function char_adjust_capacity(\Cf\CharacterizationInput $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/cf.CFModule/char_adjust_capacity',
        $argument,
        ['\Cf\CharacterizationOutput', 'decode'],
        $metadata, $options);
    }

}
