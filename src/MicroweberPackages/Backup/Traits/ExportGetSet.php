<?php
namespace MicroweberPackages\Backup\Traits;

trait ExportGetSet
{
    /**
     * Export media
     * @var string
     */
    public $exportMedia = false;
    public function setExportMedia($bool)
    {
        $this->exportMedia = $bool;
    }

    /**
     * Export modules
     * @var bool
     */
    public $exportModules = false;
    public function setExportModules($modules)
    {
        $this->exportModules = $modules;
    }

    /**
     * Export templates
     * @var bool
     */
    public $exportTemplates = false;
    public function setExportTemplates($templates)
    {
        $this->exportTemplates = $templates;
    }

    /**
     * Export only current template
     */
    public $exportOnlyTemplate = false;

    public function setExportOnlyTemplate($template)
    {
        $this->exportOnlyTemplate = $template;
    }

    /**
     * Add skip tables
     */

    public $skipTables;

    public function setSkipTables($tables) {
        $this->skipTables($tables);
    }

    public function addSkipTable($tableOrTables)
    {
        if (is_array($tableOrTables)) {
            foreach($tableOrTables as $table) {
                $this->skipTables[] = $table;
            }
        } else {
            $this->skipTables[] = $tableOrTables;
        }
    }
}
