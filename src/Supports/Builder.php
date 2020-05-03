<?php


namespace DuoLee\Table\Supports;


use Arr;
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
        if (!preg_match('/^(http|https):\/\//', $src))
            $src = asset($src);
        if (!preg_match('/(.css)$/', $src)) {
            $attributes = $this->html->attributes(['src' => $src, 'type' => 'text/javascript']);
            return new HtmlString("<script{$attributes}></script>");
        } else {
            $attributes = $this->html->attributes(['href' => $src, 'rel' => 'stylesheet', 'type' => 'text/css']);
            return new HtmlString("<link $attributes>");
        }
    }

    protected function libraries()
    {
        $extensions = $this->config->get('table.extensions') ?? [];
        $resource = $this->config->get('table.resource') ?? 'local';
        $datatable = is_array($extensions['datatable'][$resource]) ?
            array_map(fn($src) => $this->generateLibraryScript($src),
                $extensions['datatable'][$resource]) :
            [$this->generateLibraryScript($extensions['datatable'][$resource])];
        $jquery = is_array($extensions['jquery'][$resource]) ?
            array_map(fn($src) => $this->generateLibraryScript($src),
                $extensions['jquery'][$resource]) :
            [$this->generateLibraryScript($extensions['jquery'][$resource])];
        $libraries = [
            ...$jquery,
            ...$datatable
        ];
        if ($this->hasButton()) {
            $libraries[] = $this->generateLibraryScript($extensions['button'][$resource]);
            foreach ($extensions as $name => $extension) {
                if (collect(array_values($this->attributes['buttons']))->contains('extend', $name)) {
                    $libraries = array_merge($libraries,
                        (is_array($extension[$resource])
                            ? array_map(fn($src) => $this->generateLibraryScript($src), $extension[$resource])
                            : [$this->generateLibraryScript($extension[$resource])]
                        ));
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
