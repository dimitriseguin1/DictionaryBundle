<?php

namespace Knp\DictionaryBundle\Dictionary\Factory;

use Knp\DictionaryBundle\Dictionary\Factory;
use Knp\DictionaryBundle\Dictionary\SimpleTaggedDictionary;
use Knp\DictionaryBundle\Dictionary\SimpleDictionary;
use Knp\DictionaryBundle\Dictionary\ValueTransformer;

class Value extends AbstractSimpleFactory
{
    /**
     * @var ValueTransformer
     */
    protected $transformer;

    /**
     * @param ValueTransformer $transformer
     */
    public function __construct(ValueTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * {@inheritdoc}
     */
    public function create($name, array $config)
    {
        if (!isset($config['content'])) {
            throw new \InvalidArgumentException(\sprintf(
                'The key content for dictionary %s must be set',
                $name
            ));
        }

        $content = $config['content'];
        $values = [];

        foreach ($content as $value) {
            $values[] = $this->transformer->transform($value);
        }

        return $this->newInstance($name, $values, $config['tags'] ?? null);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(array $config)
    {
        return (isset($config['type'])) ? 'value' === $config['type'] : false;
    }
}
