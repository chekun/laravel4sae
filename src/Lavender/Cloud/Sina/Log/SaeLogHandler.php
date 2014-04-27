<?php namespace Lavender\Cloud\Sina\Log;

use Monolog\Handler\AbstractProcessingHandler;

class SaeLogHandler extends AbstractProcessingHandler {

    /**
     * {@inheritdoc}
     */
    protected function write(array $record)
    {
        sae_debug((string) $record['formatted']);
    }

}