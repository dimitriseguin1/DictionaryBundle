<?php

declare(strict_types=1);

namespace Knp\DictionaryBundle\Dictionary;

use Knp\DictionaryBundle\Dictionary;

final class Combined implements Dictionary
{
    /**
     * @var CallableDictionary
     */
    private $dictionary;

    public function __construct(string $name, array $dictionaries)
    {
        $this->dictionary = new CallableDictionary($name, function () use ($dictionaries) {
            $data = [];

            foreach ($dictionaries as $dictionary) {
                $data = $this->merge($data, iterator_to_array($dictionary));
            }

            return $data;
        });
    }

    public function getName(): string
    {
        return $this->dictionary->getName();
    }

    public function getValues(): array
    {
        return $this->dictionary->getValues();
    }

    public function getKeys(): array
    {
        return $this->dictionary->getKeys();
    }

    public function offsetExists($offset)
    {
        return $this->dictionary->offsetExists($offset);
    }

    public function offsetGet($offset)
    {
        return $this->dictionary->offsetGet($offset);
    }

    public function offsetSet($offset, $value): void
    {
        $this->dictionary->offsetSet($offset, $value);
    }

    public function offsetUnset($offset): void
    {
        $this->dictionary->offsetUnset($offset);
    }

    public function count(): int
    {
        return \count($this->dictionary->getValues());
    }

    public function getIterator()
    {
        return $this->dictionary->getIterator();
    }

    private function merge(array $array1, array $array2): array
    {
        if ($array1 === array_values($array1) && $array2 === array_values($array2)) {
            return array_merge($array1, $array2);
        }

        $data = [];

        foreach ([$array1, $array2] as $array) {
            foreach ($array as $key => $value) {
                $data[$key] = $value;
            }
        }

        return $data;
    }
}
