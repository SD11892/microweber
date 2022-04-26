<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use LivewireUI\Modal\ModalComponent;
use MicroweberPackages\Export\SessionStepper;
use MicroweberPackages\Import\DatabaseSave;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\FeedMapToArray;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\Readers\ItemMapReader;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\Readers\XmlToArray;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;

class StartImportingModal extends ModalComponent
{
    public $done = false;
    public $import_log = [
        'current_step'=>0,
        'total_steps'=>10,
        'percentage'=>0,
    ];
    public $import_feed_session_id = false;
    public $import_feed;

    public function nextStep()
    {
        $xmlFile = base_path() . DS . $this->import_feed->source_file_realpath;
        if (!is_file($xmlFile)) {
            return redirect(route('admin.import-export-tool.index'));
        }

        if (empty($this->import_feed->mapped_content)) {

            $feedMapToArray = new FeedMapToArray();
            $feedMapToArray->setImportFeedId($this->import_feed->id);
            $preparedData = $feedMapToArray->toArray();

            $this->import_feed->mapped_content = $preparedData;
            $this->import_feed->save();
            return; // next step
        }

        // Start importing cached data
        SessionStepper::setSessionId($this->import_feed_session_id);
        SessionStepper::nextStep();

        $this->import_log['current_step'] = SessionStepper::currentStep();
        $this->import_log['percentage'] = SessionStepper::percentage();

        $totalItemsForSave = sizeof($this->import_feed->mapped_content);
        $totalItemsForBatch = (int) ceil($totalItemsForSave / $this->import_log['total_steps']);
        $itemsBatch = array_chunk($this->import_feed->mapped_content, $totalItemsForBatch);

        $selectBatch = ($this->import_log['current_step'] - 1);
        if (!isset($itemsBatch[$selectBatch])) {
            SessionStepper::finish();
        }

        if (SessionStepper::isFinished()) {
            $this->import_feed->total_running = 0;
            $this->import_feed->last_import_end = Carbon::now();
            $this->import_feed->save();
            $this->done = true;
            return array("success"=>"Done! All steps are finished.");
        }

        $savedIds = array();
        foreach($itemsBatch[$selectBatch] as $item) {
            if (isset($item['price'])) {
                $savedIds[] = DatabaseSave::saveProduct($item);
            } else{
                $savedIds[] = DatabaseSave::savePost($item);
            }
        }

        if (empty($this->import_feed->imported_content_ids)) {
            $this->import_feed->imported_content_ids = [];
        }

        $importedContentIds = [];
        $importedContentIds = array_merge($importedContentIds,$this->import_feed->imported_content_ids);
        $importedContentIds = array_merge($importedContentIds,$savedIds);
        $importedContentIds = array_unique($importedContentIds);

        $this->import_feed->total_running = $this->import_log['current_step'];
        $this->import_feed->imported_content_ids = $importedContentIds;
        $this->import_feed->save();

        return $savedIds;
    }

    public function clearLog()
    {

    }

    public function setLogInfo($log)
    {
        $this->logDetails = $log;
    }

    public function mount($importFeedId)
    {
        $this->import_feed = ImportFeed::where('id', $importFeedId)->first();
        if ($this->import_feed == null) {
            return redirect(route('admin.import-export-tool.index'));
        }

        $this->import_feed->total_running = 1;
        $this->import_feed->last_import_start = Carbon::now();
        $this->import_feed->save();

        $this->import_log['total_steps'] = $this->import_feed->split_to_parts;
        $this->import_feed_session_id = SessionStepper::generateSessionId($this->import_feed->split_to_parts);
    }

    public function render()
    {
        return view('import_export_tool::admin.livewire-start-importing-modal');
    }
}
