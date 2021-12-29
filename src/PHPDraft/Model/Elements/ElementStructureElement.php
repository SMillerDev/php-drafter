<?php

namespace PHPDraft\Model\Elements;

class ElementStructureElement implements StructureElement
{
    /**
     * Object JSON type.
     *
     * @var string|null
     */
    public $type;

    /**
     * Object description.
     *
     * @var string|null
     */
    public $description;

    /**
     * Object value.
     *
     * @var mixed
     */
    public $value = null;

    /**
     * @param object|null $object
     * @param array $dependencies
     * @return StructureElement
     */
    public function parse(?object $object, array &$dependencies): StructureElement
    {
        if (!in_array($object->element, self::DEFAULTS)) {
            $dependencies[] = $object->element;
        }

        $this->type  = $object->element;
        $this->value = $object->content ?? null;
        $this->description = $object->meta->description->content ?? null;

        return $this;
    }

    public function __toString(): string
    {
        $type = $this->get_element_as_html($this->type);

        $desc  = is_null($this->description) ? '' : " - <span class=\"description\">{$this->description}</span>";
        $value = is_null($this->value) ? '' : " - <span class=\"example-value pull-right\">{$this->value}</span>";
        return '<li class="list-group-item mdl-list__item">' . $type . $desc . $value . '</li>';
    }

    /**
     * Get a string representation of the value.
     *
     * @param bool $flat get a flat representation of the item.
     *
     * @return string
     */
    public function string_value(bool $flat = false)
    {
        if ($flat === true) {
            return $this->value;
        }

        return $this->__toString();
    }

    /**
     * Represent the element in HTML.
     *
     * @param string|null $element Element name
     *
     * @return string HTML string
     */
    protected function get_element_as_html(?string $element): string
    {
        if ($element === null)
        {
            return '<code>null</code>';
        }

        if (in_array($element, self::DEFAULTS)) {
            return '<code>' . $element . '</code>';
        }

        $link_name = str_replace(' ', '-', strtolower($element));
        return '<a class="code" title="' . $element . '" href="#object-' . $link_name . '">' . $element . '</a>';
    }
}
