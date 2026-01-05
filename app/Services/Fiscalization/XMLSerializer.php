<?php 

namespace App\Services\Fiscalization;

use GoetasWebservices\Xsd\XsdToPhpRuntime\Jms\Handler\BaseTypesHandler;
use GoetasWebservices\Xsd\XsdToPhpRuntime\Jms\Handler\XmlSchemaDateHandler;
use JMS\Serializer\Handler\HandlerRegistryInterface;
use JMS\Serializer\SerializerBuilder;

class XMLSerializer
{
    protected $object;

    public function __construct($object)
    {
        $this->object = $object;
    }

    public function toXml()
    {
        $serializerBuilder = SerializerBuilder::create();
        $metaDataDir = __DIR__ . '/Metadata';
        $serializerBuilder->addMetadataDir($metaDataDir, 'App\Services\Fiscalization');

        $serializerBuilder->configureHandlers(function (HandlerRegistryInterface $handler) use ($serializerBuilder) {
            $serializerBuilder->addDefaultHandlers();
            $handler->registerSubscribingHandler(new BaseTypesHandler());
            $handler->registerSubscribingHandler(new XmlSchemaDateHandler());
        });

        $serializer = $serializerBuilder->build();
        return $serializer->serialize($this->object, 'xml');
    }
}