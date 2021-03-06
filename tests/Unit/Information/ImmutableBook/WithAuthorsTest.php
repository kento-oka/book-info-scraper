<?php

namespace Tests\Unit\Information\ImmutableBook;

use BookBok\BookInfoScraper\Information\AuthorInterface;
use BookBok\BookInfoScraper\Information\ImmutableAuthor;
use BookBok\BookInfoScraper\Information\ImmutableBook;
use PHPUnit\Framework\TestCase;

class WithAuthorsTest extends TestCase
{
    /**
     * @dataProvider dataProviderPossibleToSetValidValueAndReturnCloneInstance
     * @param ImmutableAuthor[]|null $value
     * @return void
     */
    public function testPossibleToSetValidValueAndReturnCloneInstance(?array $value): void
    {
        $author = $this->createStub(ImmutableAuthor::class);

        $book = (new ImmutableBook())->withAuthors([$author]);
        $cloneBook = $book->withAuthors($value);

        $this->assertSame([$author], $book->getAuthors());
        $this->assertSame($value, $cloneBook->getAuthors());
        $this->assertNotSame($cloneBook, $book);
    }

    /**
     * @return array<array{ImmutableAuthor[]|null}>
     */
    public function dataProviderPossibleToSetValidValueAndReturnCloneInstance(): array
    {
        return [
            [[$this->createStub(ImmutableAuthor::class)]],
            [null],
        ];
    }

    /**
     * @dataProvider dataProviderThrowExceptionWhenSetInvalidValue
     * @param mixed[] $value
     * @param string $message
     * @return void
     */
    public function testThrowExceptionWhenSetInvalidValue(array $value, string $message): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($message);

        (new ImmutableBook())->withAuthors($value);
    }

    /**
     * @return array<array{mixed[],string}>
     */
    public function dataProviderThrowExceptionWhenSetInvalidValue(): array
    {
        return [
            "Empty array" => [
                [],
                "Cannot set empty array."
            ],
            "Associative array with number index" => [
                [
                    1 => $this->createStub(ImmutableAuthor::class),
                    2 => $this->createStub(ImmutableAuthor::class),
                ],
                "Cannot set associative array."
            ],
            "Associative array with string index" => [
                [
                    "a" => $this->createStub(ImmutableAuthor::class),
                    "b" => $this->createStub(ImmutableAuthor::class),
                ],
                "Cannot set associative array."
            ],
            "Contain not object" => [
                ["", $this->createStub(ImmutableAuthor::class)],
                "Cannot set the value other than the object that inherits ImmutableAuthor."
            ],
            "Contain not ImmutableAuthor object" => [
                [$this->createStub(ImmutableAuthor::class), $this->createStub(AuthorInterface::class)],
                "Cannot set the value other than the object that inherits ImmutableAuthor."
            ],
        ];
    }
}
