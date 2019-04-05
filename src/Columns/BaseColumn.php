<?php

namespace Woo\GridView\Columns;

use Woo\GridView\Exceptions\GridViewConfigException;
use Woo\GridView\GridViewHelper;
use Woo\GridView\Traits\Configurable;

abstract class BaseColumn
{
    use Configurable;

    /**
     * Column title
     * @var string
     */
    public $title = '';

    /**
     * Column value. Could be an attribute,
     * @var string|mixed
     */
    public $value = '';

    /**
     * @var array
     */
    public $headerHtmlOptions = [];

    /**
     * @var array
     */
    public $contentHtmlOptions = [];

    /**
     * @var array - allowed: raw, url, email, text, image
     */
    public $formatters = ['text'];

    /**
     * Value when column is empty
     * @var string
     */
    public $emptyValue = '-';

    /**
     * BaseColumn constructor.
     * @param array $config
     * @throws \Woo\GridView\Exceptions\GridViewConfigException
     */
    public function __construct(array $config)
    {
        $this->loadConfig($config);
    }

    /**
     * @return array
     */
    protected function configTests(): array
    {
        return [
            'title' => 'string',
            'value' => 'any',
            'headerHtmlOptions' => 'array',
            'contentHtmlOptions' => 'array',
            'formatters' => 'array',
            'emptyValue' => 'string',
        ];
    }

    /**
     * Formatted header html options
     * @return string
     */
    public function headerHtmlOptions() : string
    {
        return GridViewHelper::htmlOptionsToString($this->headerHtmlOptions);
    }

    /**
     * Formatted content html options
     * @param array $context
     * @return string
     */
    public function contentHtmlOptions(array $context) : string
    {
        return GridViewHelper::htmlOptionsToString($this->contentHtmlOptions, $context);
    }

    /**
     * Render column value for row
     * @param array|object $row
     * @return string|mixed
     */
    protected abstract function _renderValue($row);

    /**
     * Renders column content
     * @param $row
     * @return string
     * @throws GridViewConfigException
     */
    public function renderValue($row)
    {
        $value = $this->_renderValue($row);

        foreach ($this->formatters as $formatter) {
            $className = GridViewHelper::resolveAlias($formatter);
            $value = (new $className)->format($value);
        }

        return $value;
    }

}