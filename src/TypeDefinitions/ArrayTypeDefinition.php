<?php


namespace NanoSector\Models\TypeDefinitions;


class ArrayTypeDefinition implements TypeDefinitionInterface
{

    /**
     * @var \NanoSector\Models\TypeDefinitions\TypeDefinitionInterface
     */
    private $contentDefinition;

    public function __construct(TypeDefinitionInterface $contentDefinition)
    {
        $this->contentDefinition = $contentDefinition;
    }

    /**
     * @inheritDoc
     */
    public function validate($value): bool
    {
        if (!is_array($value)) {
            return false;
        }

        foreach ($value as $item) {
            if (!$this->contentDefinition->validate($item)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function default()
    {
        return [];
    }

}
