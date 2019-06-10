<?php
namespace Microweber\Utils\Backup\Exporters;

use Microweber\Utils\Backup\Loggers\BackupExportLogger;

class ZipExport extends DefaultExport
{
	/**
	 * The current batch step.
	 * @var integer
	 */
	public $currentStep = 0;
	
	/**
	 * The total steps for batch.
	 * @var integer
	 */
	public $totalSteps = 3;
	
	
	/**
	 * The name of cache group for backup file.
	 * @var string
	 */
	private $_cacheGroupName = 'BackupExporting';

	public function getCurrentStep() {
		
		$this->currentStep = (int) cache_get('ExportCurrentStep', $this->_cacheGroupName);
		
		return $this->currentStep;
	}
	
	private function _getZipFileName() {
		
		$zipFileName = cache_get('ExportZipFileName', $this->_cacheGroupName);
		
		if (empty($zipFileName)) {
			$generateFileName = $this->_generateFilename();
			cache_save($generateFileName, 'ExportZipFileName', $this->_cacheGroupName, 60 * 10);
			return $generateFileName;
		}
		
		return $zipFileName;
	}
	
	public function start() {
		
		if ($this->getCurrentStep() == 0) {
			// Clear old log file
			BackupExportLogger::clearLog();
		}
		
		// Get zip filename
		$zipFileName = $this->_getZipFileName();
		
		// var_dump($zipFileName);
		
		BackupExportLogger::setLogInfo('Archiving files batch: ' . $this->getCurrentStep() . '/' . $this->totalSteps);
		
		// Generate zip file
		$zip = new \ZipArchive();
		$zip->open($zipFileName['filepath'], \ZipArchive::CREATE);
		$zip->setArchiveComment("Microweber backup of the userfiles folder and db.
                \nThe Microweber version at the time of backup was ".MW_VERSION."
                \nCreated on " . date('l jS \of F Y h:i:s A'));
		
		if ($this->getCurrentStep() == 0) {
			// Encode db json
			$json = new JsonExport($this->data);
			$getJson = $json->start();
			
			// Add json file
			if ($getJson['filepath']) {
				$zip->addFile($getJson['filepath'], 'mw_content.json');
			}
		}
		
		$userFiles = $this->_getUserFilesPaths();
		if (!empty($userFiles)) {
			
			$totalUserFilesForZip = sizeof($userFiles);
			$totalUserFilesForBatch = round($totalUserFilesForZip / $this->totalSteps, 0);
			
			$userFilesBatch = array_chunk($userFiles, $totalUserFilesForBatch);
			
			if (!isset($userFilesBatch[$this->getCurrentStep()])) {
				
				BackupExportLogger::setLogInfo('No files in batch for current step.');
				$this->_finishUp();
				
				return $zipFileName;
			}
			
			foreach($userFilesBatch[$this->getCurrentStep()] as $file) {
				BackupExportLogger::setLogInfo('Archiving file <b>'. $file['dataFile'] . '</b>');
				$zip->addFile($file['filePath'], $file['dataFile']);
			}
			
			$zip->close();
			
			cache_save($this->getCurrentStep() + 1, 'ExportCurrentStep', $this->_cacheGroupName, 60 * 10);
		}
		
		return $this->getExportLog();
	}
	
	public function getExportLog() {
		
		$log = array();
		$log['current_step'] = $this->getCurrentStep();
		$log['total_steps'] = $this->totalSteps;
		$log['precentage'] = ($this->getCurrentStep() * 100) / $this->totalSteps;
		$log['data'] = false;
		
		if ($this->getCurrentStep() >= $this->totalSteps) {
			$log['done'] = true;
		}
		
		return $log;
	}
	
	public function clearSteps() {
		cache_delete($this->_cacheGroupName);
	}
	
	private function _getUserFilesPaths() {
		
		$userFiles = array();
		$userFilesReady = array();
		
		$css = $this->_getDirContents(userfiles_path() . DIRECTORY_SEPARATOR . 'css');
		$media = $this->_getDirContents(userfiles_path() . DIRECTORY_SEPARATOR . 'media');
		
		$userFiles = array_merge($css, $media);
		
		foreach($userFiles as $filePath) {
			
			$dataFile = str_replace(userfiles_path() . DIRECTORY_SEPARATOR, false, $filePath);
			
			$dataFile = normalize_path($dataFile, false);
			$filePath =  normalize_path($filePath, false);
			
			$userFilesReady[] = array(
				'dataFile'=>$dataFile,
				'filePath'=>$filePath
			);
			
		}
		
		return $userFilesReady;
		
	}
	
	private function _getDirContents($path) {
		
		$rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));

		$files = array();
		foreach ($rii as $file) {
			if (! $file->isDir()) {
				$files[] = $file->getPathname();
			}
		}
		return $files;
	}
	
	/**
	 * Clear all cache 
	 */
	private function _finishUp() {
		$this->clearSteps();
		BackupExportLogger::setLogInfo('Done!');
		
	}
	
}