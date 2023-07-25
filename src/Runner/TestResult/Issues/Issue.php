<?php declare(strict_types=1);
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\TestRunner\TestResult\Issues;

use PHPUnit\Event\Code\Test;

/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 */
abstract class Issue
{
    /**
     * @psalm-var non-empty-string
     */
    private readonly string $file;

    /**
     * @psalm-var positive-int
     */
    private readonly int $line;

    /**
     * @psalm-var non-empty-string
     */
    private readonly string $description;

    /**
     * @psalm-var non-empty-array<non-empty-string, array{test: Test, count: int}>
     */
    private array $triggeringTests;

    /**
     * @psalm-param non-empty-string $file
     * @psalm-param positive-int $line
     * @psalm-param non-empty-string $description
     */
    final public static function error(string $file, int $line, string $description, Test $triggeringTest): Error
    {
        return new Error($file, $line, $description, $triggeringTest);
    }

    /**
     * @psalm-param non-empty-string $file
     * @psalm-param positive-int $line
     * @psalm-param non-empty-string $description
     */
    final public static function deprecation(string $file, int $line, string $description, Test $triggeringTest): Deprecation
    {
        return new Deprecation($file, $line, $description, $triggeringTest);
    }

    /**
     * @psalm-param non-empty-string $file
     * @psalm-param positive-int $line
     * @psalm-param non-empty-string $description
     */
    final public static function notice(string $file, int $line, string $description, Test $triggeringTest): Notice
    {
        return new Notice($file, $line, $description, $triggeringTest);
    }

    /**
     * @psalm-param non-empty-string $file
     * @psalm-param positive-int $line
     * @psalm-param non-empty-string $description
     */
    final public static function warning(string $file, int $line, string $description, Test $triggeringTest): Warning
    {
        return new Warning($file, $line, $description, $triggeringTest);
    }

    /**
     * @psalm-param non-empty-string $file
     * @psalm-param positive-int $line
     * @psalm-param non-empty-string $description
     */
    final public static function phpDeprecation(string $file, int $line, string $description, Test $triggeringTest): PhpDeprecation
    {
        return new PhpDeprecation($file, $line, $description, $triggeringTest);
    }

    /**
     * @psalm-param non-empty-string $file
     * @psalm-param positive-int $line
     * @psalm-param non-empty-string $description
     */
    final public static function phpNotice(string $file, int $line, string $description, Test $triggeringTest): PhpNotice
    {
        return new PhpNotice($file, $line, $description, $triggeringTest);
    }

    /**
     * @psalm-param non-empty-string $file
     * @psalm-param positive-int $line
     * @psalm-param non-empty-string $description
     */
    final public static function phpWarning(string $file, int $line, string $description, Test $triggeringTest): PhpWarning
    {
        return new PhpWarning($file, $line, $description, $triggeringTest);
    }

    /**
     * @psalm-param non-empty-string $file
     * @psalm-param positive-int $line
     * @psalm-param non-empty-string $description
     */
    public function __construct(string $file, int $line, string $description, Test $triggeringTest)
    {
        $this->file        = $file;
        $this->line        = $line;
        $this->description = $description;

        $this->triggeringTests = [
            $triggeringTest->id() => [
                'test'  => $triggeringTest,
                'count' => 1,
            ],
        ];
    }

    public function triggeredBy(Test $test): void
    {
        if (isset($this->triggeringTests[$test->id()])) {
            $this->triggeringTests[$test->id()]['count']++;

            return;
        }

        $this->triggeringTests[$test->id()] = [
            'test'  => $test,
            'count' => 1,
        ];
    }

    /**
     * @psalm-return non-empty-string
     */
    public function file(): string
    {
        return $this->file;
    }

    /**
     * @psalm-return positive-int
     */
    public function line(): int
    {
        return $this->line;
    }

    /**
     * @psalm-return non-empty-string
     */
    public function description(): string
    {
        return $this->description;
    }

    /**
     * @psalm-return non-empty-array<non-empty-string, array{test: Test, count: int}>
     */
    public function triggeringTests(): array
    {
        return $this->triggeringTests;
    }

    /**
     * @psalm-assert-if-true Error $this
     */
    public function isError(): bool
    {
        return false;
    }

    /**
     * @psalm-assert-if-true Deprecation $this
     */
    public function isDeprecation(): bool
    {
        return false;
    }

    /**
     * @psalm-assert-if-true Notice $this
     */
    public function isNotice(): bool
    {
        return false;
    }

    /**
     * @psalm-assert-if-true Warning $this
     */
    public function isWarning(): bool
    {
        return false;
    }

    /**
     * @psalm-assert-if-true PhpDeprecation $this
     */
    public function isPhpDeprecation(): bool
    {
        return false;
    }

    /**
     * @psalm-assert-if-true PhpNotice $this
     */
    public function isPhpNotice(): bool
    {
        return false;
    }

    /**
     * @psalm-assert-if-true PhpWarning $this
     */
    public function isPhpWarning(): bool
    {
        return false;
    }
}
