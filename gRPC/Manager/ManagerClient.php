<?php
// GENERATED CODE -- DO NOT EDIT!

namespace Manager;

/**
 */
class ManagerClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \Manager\StartSimulationRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function StartSimulation(\Manager\StartSimulationRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/manager.Manager/StartSimulation',
        $argument,
        ['\Manager\StartSimulationResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Manager\StartCharacterizationRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function StartCharacterization(\Manager\StartCharacterizationRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/manager.Manager/StartCharacterization',
        $argument,
        ['\Manager\StartCharacterizationResponse', 'decode'],
        $metadata, $options);
    }

}
