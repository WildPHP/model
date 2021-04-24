<?php


namespace NanoSector\Models\TypeDefinitions;


class GetTypeTypeDefinition implements TypeDefinitionInterface
{

    public const BOOLEAN = 'boolean';

    public const INTEGER = 'integer';

    public const FLOAT = 'double';

    public const STRING = 'string';

    public const RESOURCE = 'resource';

    public const RESOURCE_CLOSED = 'resource (closed)';

    public const NULL = 'NULL';

    /**
     * Possible values for gettype()
     *
     * @var string[]
     * @see \gettype()
     */
    public const GETTYPE_VALUES = [
      self::BOOLEAN,
      self::INTEGER,
      self::FLOAT,
      self::STRING,
      self::RESOURCE,
      self::RESOURCE_CLOSED,
      self::NULL,
    ];

    /**
     * @var string
     */
    private $wantedType;

    /**
     * GetTypeTypeDefinition constructor.
     *
     * @param  string  $wantedType
     *
     * @throws \NanoSector\Models\TypeDefinitions\TypeDefinitionException
     */
    public function __construct(string $wantedType)
    {
        if (!in_array($wantedType, self::GETTYPE_VALUES)) {
            throw new TypeDefinitionException(
              'Unknown gettype value passed to GetTypeTypeDefinition'
            );
        }

        $this->wantedType = $wantedType;
    }

    /**
     * @inheritDoc
     */
    public function validate($value): bool
    {
        return gettype($value) === $this->wantedType;
    }

    /**
     * @inheritDoc
     */
    public function default()
    {
        switch ($this->wantedType) {
            case self::BOOLEAN:
                return false;

            case self::INTEGER:
                return 0;

            case self::FLOAT:
                return 0.0;

            case self::STRING:
                return '';

            default:
                return null;
        }
    }

    /**
     * @inheritDoc
     * @return string
     */
    public function toDefinition(): string
    {
        return $this->wantedType;
    }

}
