<?php
/**
 * @file
 * BeGreaterMatcher.php
 */

namespace spec\Matcher;

use PhpSpec\Exception\Example\FailureException;
use PhpSpec\Matcher\BasicMatcher;
use PhpSpec\Matcher\Matcher;

/**
 * Class BeGreaterMatcher
 *
 * @package spec\App\Matcher
 */
final class BeGreaterMatcher extends BasicMatcher
{
    /**
     * @inheritDoc
     */
    public function supports(string $name, $subject, array $arguments): bool
    {
        return in_array($name, ['beGreater', 'beGreaterThan'])
            && is_numeric($subject)
            && count($arguments) === 1
            && is_numeric($arguments[0]);
    }


    /**
     * @inheritDoc
     */
    protected function matches($subject, array $arguments): bool
    {
        return ($subject > $arguments[0]);
    }

    /**
     * @inheritDoc
     */
    protected function getFailureException(string $name, $subject, array $arguments): FailureException
    {
        throw new FailureException(sprintf(
            'Expected %d to be greater than %d',
            $subject,
            $arguments[0]
        ));
    }

    /**
     * @inheritDoc
     */
    protected function getNegativeFailureException(string $name, $subject, array $arguments): FailureException
    {
        throw new FailureException(sprintf(
            'Expected %d not be greater than %d',
            $subject,
            $arguments[0]
        ));
    }
}
