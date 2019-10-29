<?php

declare(strict_types=1);

namespace Knp\DictionaryBundle\Dictionary\Factory;

use InvalidArgumentException;
use Knp\DictionaryBundle\Dictionary;
use Knp\DictionaryBundle\Dictionary\DictionaryRegistry;

class Combined implements Dictionary\Factory
{
    const TYPE = 'combined';

    /**
     * @var Dictionary\DictionaryRegistry
     */
    private $dictionaries;

    public function __construct(DictionaryRegistry $dictionaries)
    {
        $this->dictionaries = $dictionaries;
    }

    /**
     * {@inheritdoc}
     */
    public function create($name, array $config): Dictionary
    {
        if (!isset($config['dictionaries'])) {
            throw new InvalidArgumentException(sprintf(
                'Dictionary of type %s must contains a key "dictionaries".',
                self::TYPE
            ));
        }

        $dictionaries = array_map(function ($name) {
            return $this->dictionaries[$name];
        }, $config['dictionaries']);

        return new Dictionary\Combined($name, $dictionaries);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(array $config): bool
    {
        return isset($config['type']) ? self::TYPE === $config['type'] : false;
    }
}