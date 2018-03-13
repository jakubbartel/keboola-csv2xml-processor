<?php declare(strict_types = 1);

namespace Keboola\Csv2XmlProcessor;

use Keboola\Component\Config\BaseConfigDefinition;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;

class ConfigDefinition extends BaseConfigDefinition
{

    /**
     * @return ArrayNodeDefinition|NodeDefinition
     */
    protected function getParametersDefinition()
    {
        $parametersNode = parent::getParametersDefinition();

        $parametersNode
            ->isRequired()
            ->children()
                ->scalarNode('root_node')
                    ->isRequired()
                ->end()
                ->scalarNode('item_node')
                    ->isRequired()
                ->end()
            ->end();

        return $parametersNode;
    }

}
