<?php

namespace MicroweberPackages\Livewire\Views\Columns;

use Illuminate\Database\Eloquent\Model;
use Rappasoft\LaravelLivewireTables\Views\Column;

class MwCardColumn extends Column
{
    protected string $view = 'livewire::livewire.livewire-tables.includes.columns.mw-card';
    protected $buttonsCallback;
    protected $attributesCallback;

    protected $icon = '';
    protected $noThumbnailIcon = '';

    public function noThumbnailIcon($icon) {
        $this->noThumbnailIcon = $icon;

        return $this;
    }

    public function icon($icon) {
        $this->icon = $icon;

        return $this;
    }

    public function buttons(callable $callback): self
    {
        $this->buttonsCallback = $callback;

        return $this;
    }

    public function attributes(callable $callback): self
    {
        $this->attributesCallback = $callback;

        return $this;
    }

    public function getAttributesCallback()
    {
        return $this->attributesCallback;
    }

    public function hasAttributesCallback()
    {
        return $this->attributesCallback !== null;
    }

    public function getView(): string
    {
        return $this->view;
    }

    public function getContents(Model $row)
    {
        return view($this->getView())
            ->withColumn($this)
            ->withButtons(app()->call($this->buttonsCallback, ['row'=>$row]))
            ->withRow($row)
            ->withAttributes($this->hasAttributesCallback() ? app()->call($this->getAttributesCallback(), ['row' => $row]) : []);
    }
}
