<?php

declare(strict_types=1);

namespace Logtail\Monolog\Handler;

use Monolog\Logger;
use Monolog\LogRecord;

if (Logger::API >= 3) {
    /**
     * Logic which is used if monolog >= 3 is installed.
     *
     * @internal
     */
    trait CompatibilityProcessingHandlerTrait
    {
        /**
         * @param array<LogRecord> $records The records to handle
         */
        public function handleBatch(array $records): void
        {
            $formattedRecords = $this->getFormatter()->formatBatch($records);
            $this->client->send($formattedRecords);
        }

        protected function write(LogRecord $record): void
        {
            $this->client->send($record->formatted);
        }
    }
} else {
    /**
     * Logic which is used if monolog < 3 is installed.
     *
     * @internal
     */
    trait CompatibilityProcessingHandlerTrait
    {
        /**
         * @param array $record
         */
        protected function write(array $record): void {
            $this->client->send($record["formatted"]);
        }

        /**
         * @param array $records
         * @return void
         */
        public function handleBatch(array $records): void
        {
            $formattedRecords = $this->getFormatter()->formatBatch($records);
            $this->client->send($formattedRecords);
        }
    }
}