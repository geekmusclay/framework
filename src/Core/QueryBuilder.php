<?php 

declare(strict_types=1);

namespace Geekmusclay\Framework\Core;

use Exception;

class QueryBuilder
{
    /** @var string[] $selects The properties to be selected in the query */
    private array $selects = [];

    /** @var string|null $from Table targted by the query */
    private ?string $from = null;

    /** @var string[] $joins Joints to be made */
    private array $joins = [];

    /** @var string[] $wheres Conditions to get results from the query */
    private array $wheres = [];

    /** @var string|null $order The property or properties that will order the result of the query */
    private ?string $order = null;

    /** @var string $direction The direction of the order */
    private string $direction = 'ASC';

    /** @var int|null $limit Result limit for the query */
    private ?int $limit = null;

    /**
     * Defines the properties to be selected in the query.
     *
     * @param string|string[] $target Properties to select
     */
    public function select($target): self
    {
        if (is_string($target)) {
            $this->selects[] = $target;
        } elseif (is_array($target)) {
            $this->selects = array_merge($this->selects, $target);
        } else {
            throw new Exception('Wrong type');
        }

        return $this;
    }

    /**
     * Defines the table targeted by the query.
     *
     * @param string $target Targeted table
     */
    public function from(string $target, ?string $name = null): self
    {
        $this->from = $target . ($name !== null ? ' AS ' . $name : null);

        return $this;
    }

    public function join(string $table, array $conditions, ?string $name = null, string $type = 'INNER'): self
    {
        if (0 === count($conditions)) {
            throw new Exception('No conditions');
        }

        $first = true;
        $join = strtoupper($type) . ' JOIN ' . $table . ($name !== null ? ' AS ' . $name : null);
        foreach ($conditions as $condition) {
            if (3 !== count($condition)) {
                continue;
            }

            if (true === $first) {
                $join .= ' ON ';
            } else {
                $join .= ' AND ';
            }
            $join .= $condition[0] . ' ' . $condition[1] . ' ' . $condition[2];
            $first = false;
        }
        $this->joins[] = $join;

        return $this;
    }

    /**
     * Define conditions to get results from the query.
     *
     * @param array $condtions Conditions to get results
     */
    public function where(array $condtions): self
    {
        $this->wheres = array_merge($this->wheres, $condtions);

        return $this;
    }

    /**
     * Defines the property or properties that will order the result of the query.
     *
     * @param string $property  The property(ies) for the order
     * @param string $direction The direction of the order
     */
    public function orderBy(string $property, string $direction = 'ASC'): self
    {
        $this->order = $property;
        $this->direction = $direction;

        return $this;
    }

    /**
     * Define the result limit for the query.
     *
     * @param integer $limit The result limit
     */
    public function limit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Get the final result.
     *
     * @return string Properly formatted query
     */
    public function getQuery(): string
    {
        if (0 === count($this->selects)) {
            throw new Exception('No properties selected');
        }
        $query = 'SELECT ';

        $count = count($this->selects);
        for ($i = 0; $i  < $count; $i++) {
            if ($i === $count - 1) {
                $query .= $this->selects[$i] . ' ';
            } else {
                $query .= $this->selects[$i] . ', ';
            }
        }

        if (null === $this->from) {
            throw new Exception('No target table set');
        }
        $query .= 'FROM ' . $this->from . ' ';

        if (0 !== count($this->joins)) {
            foreach ($this->joins as $join) {
                $query .= $join .  ' ';
            }
        }

        if (0 !== count($this->wheres)) {
            $where = 'WHERE ';
            foreach ($this->wheres as $property => $value) {
                if ($where === 'WHERE ') {
                    $where .= $property . ' = ' . $value . ' ';
                } else {
                    $where .= 'AND ' .  $property . ' = ' . $value . ' ';
                }
            }
            $query .= $where;
        }

        if (null !== $this->order) {
            $query .= 'ORDER BY ' . $this->order . ' ' . $this->direction . ' ';
        }

        if (null !== $this->limit) {
            $query .= 'LIMIT ' . $this->limit;
        }

        return trim($query);
    }
}