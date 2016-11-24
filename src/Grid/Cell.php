<?php

namespace Motor\Backend\Grid;

class Cell extends Base
{

    protected $name = '';

    protected $value = '';

    protected $record;

    protected $renderer;

    protected $renderOptions = [ ];


    /**
     * Cell constructor.
     *
     * @param $name
     */
    public function __construct($name, $renderer, $renderOptions = [ ])
    {
        $this->name          = $name;
        $this->renderer      = $renderer;
        $this->renderOptions = $renderOptions;
    }


    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * @param $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    public function setRecord($record)
    {
        $this->record = $record;
    }


    /**
     * @return string
     */
    public function getValue()
    {
        // Get renderer
        $renderer = new $this->renderer($this->value, $this->renderOptions, $this->record);

        return $renderer->render();
    }


    /**
     * Parse filters assigned by column
     *
     * @param $filters
     *
     * @return bool
     */
    public function parseFilters($filters)
    {
        foreach ($filters as $filter) {
            $params = [ ];
            if (preg_match('/([^\[]*+)\[(.+)\]/', $filter, $match)) {
                $filter = $match[1];
                $params = explode(',', $match[2]);
            }

            if (function_exists($filter)) {
                if ($filter == "date") {
                    array_push($params, $this->value);
                } else {
                    array_unshift($params, $this->value);
                }

                try {
                    $this->value = call_user_func_array($filter, $params);
                } catch (\Exception $e) {
                    return false;
                }
            }
        }
    }
}