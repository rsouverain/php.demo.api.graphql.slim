<?php declare(strict_types = 1);

namespace App\GraphQL\Schema\_common\Type\Scalar;

use DateTimeImmutable;
use GraphQL\Error\InvariantViolation;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Utils\Utils;

class DateTime extends ScalarType
{
    /**
     * @var string
     */
    public $name = 'DateTime';

    /**
     * @var string
     */
    public $description = 'The `DateTime` scalar type represents time data, represented as an ISO-8601 encoded UTC date string.';

    /**
     * @param mixed $value
     * @return string
     */
    public function serialize($value): string
    {
        if (!($value instanceof DateTimeImmutable) && !($value instanceof \DateTime)) {
            throw new InvariantViolation('DateTime is not an instance of DateTimeImmutable nor \\DateTime: ' . Utils::printSafe($value));
        }

        return $value->format(\DateTime::ISO8601);
    }

    /**
     * @param mixed $value
     * @return DateTimeImmutable|null
     */
    public function parseValue($value): ?DateTimeImmutable
    {
        die('<pre>' . print_r(222, true) . '</pre>');
        return DateTimeImmutable::createFromFormat(\DateTime::ISO8601, $value) ?: null;
    }

    /**
     * Parses an externally provided literal value to use as an input (e.g. in Query AST)
     *
     * @param Node $valueNode
     * @param array|null $variables
     * @return null|string
     */
    public function parseLiteral(Node $valueNode, ?array $variables = null)
    {
        if ($valueNode instanceof StringValueNode) {
            return $valueNode->value;
        }

        return null;
    }
}
