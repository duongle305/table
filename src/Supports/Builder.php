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
    /**
     * @var mixed|string
     */
    protected $resource;

    public function __construct(Repository $config, Factory $view, HtmlBuilder $html)
    {
        parent::__construct($config, $view, $html);
        $this->resource = $this->config->get('table.resource') ?? 'cdn';
    }

    protected function getExtension($key, $resource = null)
    {
        return $this->config->get("table.extensions.$key." . ($resource ?? $this->resource));
    }

    protected function isDisabled($key)
    {
        return collect($this->config->get('table.disabled') ?? [])->contains($key);
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

    /**
     * @param $url
     * @return string
     */
    protected final function resolveLibraryUrl($url)
    {
        return !preg_match('/^(http|https):\/\//', $url) ? asset($url) : $url;
    }

    /**
     * @param $href
     * @return HtmlString
     */
    protected function generateLibraryStyle($href)
    {
        $attributes = $this->html->attributes([
            'href' => $this->resolveLibraryUrl($href),
            'rel' => 'stylesheet',
            'type' => 'text/css'
        ]);
        return new HtmlString("<link $attributes>");
    }

    /**
     * @param $src
     * @return HtmlString
     */
    protected function generateLibraryScript($src)
    {
        $attributes = $this->html->attributes([
            'src' => $this->resolveLibraryUrl($src),
            'type' => 'text/javascript'
        ]);
        return new HtmlString("<script{$attributes}></script>");
    }

    /**
     * @param $src
     * @return HtmlString
     */
    public function resolveGenerateLibrary($src)
    {
        return preg_match('/(.js)$/', $src) ? $this->generateLibraryScript($src) : $this->generateLibraryStyle($src);
    }

    /**
     * @return array
     */
    protected function libraries()
    {
        $extensions = [];
        if (!$this->isDisabled('jquery')) {
            $jquery = $this->getExtension('jquery');
            $jquery = is_array($jquery)
                ? array_map(fn($src) => $this->resolveGenerateLibrary($src), $jquery)
                : [$this->resolveGenerateLibrary($jquery)];
            $extensions = [
                ...$jquery,
            ];
        }
        if (!$this->isDisabled('datatable')) {
            $datatable = $this->getExtension('datatable');
            $datatable = is_array($datatable)
                ? array_map(fn($src) => $this->resolveGenerateLibrary($src), $datatable)
                : [$this->resolveGenerateLibrary($datatable)];
            $extensions = [
                ...$extensions,
                ...$datatable,
            ];
        }
        return [
            ...$extensions,
            ...$this->buttonLibraries(),
        ];
    }

    /**
     * @return array|HtmlString[]
     */
    protected function buttonLibraries()
    {
        $extensions = $this->config->get('table.extensions.buttons') ?? [];
        $libraries = [];
        if ($this->hasButton()) {
            $libraries = is_array($extensions['button'][$this->resource]) ?
                array_map(fn($src) => $this->resolveGenerateLibrary($src),
                    $extensions['button'][$this->resource]) :
                [$this->resolveGenerateLibrary($extensions['button'][$this->resource])];
            foreach ($extensions as $name => $extension) {
                if (collect(array_values($this->attributes['buttons']))->contains($name)) {
                    if (is_array($extension[$this->resource])) {
                        $libraries = array_merge($libraries, array_map(fn($src) => $this->resolveGenerateLibrary($src), $extension[$this->resource]));
                    } else {
                        $libraries[] = $this->resolveGenerateLibrary($extension[$this->resource]);
                    }
                }
            }
        }
        return $libraries;
    }

    /**
     * @param null $script
     * @param array|string[] $attributes
     * @return HtmlString|string
     * @throws \Exception
     */
    public function scripts($script = null, array $attributes = ['type' => 'text/javascript'])
    {
        return implode("\n", [...$this->libraries(), parent::scripts($script, $attributes)]);
    }


    /**
     * @return string
     */
    protected function template()
    {
        $template = $this->template ?: $this->config->get('table.script', 'table::script');

        return $this->view->make($template, ['editors' => $this->editors])->render();
    }

}
