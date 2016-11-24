<?php

namespace Motor\Backend\Grid;

use Illuminate\Support\Arr;

class Base {

    protected $attributes = [ ];

    public function attributes($attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }


    public function style($style)
    {
        $this->attributes['style'] = $style;

        return $this;
    }

    public function getStyle()
    {
        return Arr::get($this->attributes, 'style');
    }

    public function buildAttributes()
    {
        if (empty( $this->attributes )) {
            return '';
        }

        $compiled = '';
        foreach ($this->attributes as $key => $val) {
            $compiled .= ' ' . $key . '="' . htmlspecialchars((string) $val, ENT_QUOTES, "UTF-8", true) . '"';
        }

        return $compiled;
    }


    protected function sanitize($string)
    {
        if (!is_array($string)) {
            return nl2br(htmlspecialchars($string));
        }
        return $string;
    }
}