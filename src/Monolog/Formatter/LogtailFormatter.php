<?php

/*
 * This file is part of the logtail/monolog-logtail package.
 *
 * (c) Better Stack
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Logtail\Monolog\Formatter;

/**
 * Format JSON records for Logtail
 */
class LogtailFormatter extends \Monolog\Formatter\JsonFormatter {

    use CompatibilityFormatterTrait;

    public function __construct() {
        parent::__construct(self::BATCH_MODE_JSON, false);
        $this->setMaxNormalizeItemCount(PHP_INT_MAX);
    }
}
