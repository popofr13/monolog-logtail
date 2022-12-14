<?php

declare(strict_types=1);

namespace Logtail\Monolog\Formatter;

use Monolog\Logger;
use Monolog\LogRecord;

if (Logger::API >= 3) {
    trait CompatibilityFormatterTrait
    {
        public function format(LogRecord $record): string
        {
            return $this->toJson($this->formatRecord($record));
        }

        /**
         * @param array<LogRecord> $records
         * @return string
         */
        public function formatBatch(array $records): string
        {
            $normalized = array_values($this->normalize(
                array_map(
                    fn(LogRecord $record): array => $this->formatRecord($record),
                    $records
                )
            ));

            return $this->toJson($normalized, true);
        }
        
        protected function formatRecord(LogRecord $record): array
        {
            return [
                'dt' => '"'.$record->datetime->format('Y-m-d\TH:i:s.uP').'"',
                'message' => $record->message,
                'level' => $record->level->getName(),
                'monolog' => [
                    'channel' => $record->channel,
                    'context' => $record->context,
                    'extra' => $record->extra,
                ],
            ];
        }
    }
} else {
    trait CompatibilityFormatterTrait
    {
        /**
         * @param array $record
         *
         * @return mixed
         */
        public function format(array $record): string {
            return parent::format(self::formatRecord($record));
        }


        /**
         * @param array $records
         *
         * @return mixed
         */
        public function formatBatch(array $records): string
        {
            $normalized = array_values($this->normalize(array_map('self::formatRecord', $records)));
            return $this->toJson($normalized, true);
        }

        protected static function formatRecord(array $record): array
        {
            return [
                'dt' => $record['datetime'],
                'message' => $record['message'],
                'level' => $record['level_name'],
                'monolog' => [
                    'channel' => $record['channel'],
                    'context' => $record['context'],
                    'extra' => $record['extra'],
                ],
            ];
        }
    }
}