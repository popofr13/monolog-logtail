<?php declare(strict_types=1);

/*
 * This file is part of the logtail/monolog-logtail package.
 *
 * (c) Better Stack
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Logtail\Monolog\Handler;

use Logtail\Monolog\Formatter\LogtailFormatter;
use Logtail\Monolog\LogtailClient;
use Monolog\Formatter\FormatterInterface;

/**
 * Sends log to Logtail.
 */
class SynchronousLogtailHandler extends \Monolog\Handler\AbstractProcessingHandler {

    use CompatibilityProcessingHandlerTrait;

    /**
     * @var LogtailClient $client
     */
    private $client;

    /**
     * @param string $sourceToken
     * @param int $level
     * @param bool $bubble
     * @param string $endpoint
     */
    public function __construct(
        $sourceToken,
        $level = \Monolog\Logger::DEBUG,
        $bubble = true,
        $endpoint = LogtailClient::URL
    ) {
        parent::__construct($level, $bubble);

        $this->client = new LogtailClient($sourceToken, $endpoint);

        $this->pushProcessor(new \Monolog\Processor\IntrospectionProcessor($level, ['Logtail\\']));
        $this->pushProcessor(new \Monolog\Processor\WebProcessor);
        $this->pushProcessor(new \Monolog\Processor\ProcessIdProcessor);
        $this->pushProcessor(new \Monolog\Processor\HostnameProcessor);
    }

    /**
     * @return LogtailFormatter
     */
    protected function getDefaultFormatter(): \Monolog\Formatter\FormatterInterface {
        return new LogtailFormatter();
    }

    public function getFormatter(): FormatterInterface
    {
        return $this->getDefaultFormatter();
    }
}
