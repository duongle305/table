<?php


namespace DuoLee\Table\Supports;


use Collective\Html\HtmlBuilder;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\HtmlString;
use Yajra\DataTables\Html\Builder as TableBuilder;

class Builder extends TableBuilder
{
    public function __construct(Repository $config, Factory $view, HtmlBuilder $html)
    {
        parent::__construct($config, $view, $html);
    }

    /**
     * @return bool
     */
    protected function hasButton()
    {
        return !empty($this->getButtons());
    }


    /**
     * @return array|mixed
     */
    protected function getButtons()
    {
        return $this->attributes['buttons'] ?? [];
    }


    protected function generateLibraryScript($src)
    {
        $attributes = $this->html->attributes(['src' => asset($src), 'type' => 'text/javascript']);
        return new HtmlString("<script{$attributes}></script>");
    }

    protected function libraries()
    {
        $extensions = $this->config->get('table-exts.extensions') ?? $this->config->get('table.extensions') ?? [];
        $libraries = [
            $this->generateLibraryScript($extensions['datatable'])
        ];
        if ($this->hasButton()) {
            $libraries[] = $this->generateLibraryScript($extensions['button']);
            foreach ($extensions as $name => $extension) {
                if (collect(array_values($this->attributes['buttons']))->contains('extend', $name)) {
                    if (is_array($extension)) {
                        foreach ($extension as $item) {
                            $libraries[] = $this->generateLibraryScript($item);
                        }
                    } else {
                        $libraries[] = $this->generateLibraryScript($extension);
                    }
                }
            }
        }
        return $libraries;
    }

    public function scripts($script = null, array $attributes = ['type' => 'text/javascript'])
    {
        return implode("", array_merge($this->libraries(), [parent::scripts($script, $attributes)]));
    }

    protected function template()
    {
        $template = $this->template ?: $this->config->get('table-exts.script', 'table::script');

        return $this->view->make($template, ['editors' => $this->editors])->render();
    }

}
