<?php
namespace Microweber\Utils\Backup;

use Microweber\Utils\Backup\Exporters\JsonExport;
use Microweber\Utils\Backup\Exporters\CsvExport;
use Microweber\Utils\Backup\Exporters\XmlExport;
use Microweber\Utils\Backup\Exporters\ZipExport;
use Microweber\Utils\Backup\Loggers\BackupExportLogger;
use Microweber\App\Providers\Illuminate\Support\Facades\Cache;

class Export
{
	public $skipTables;
	public $exportData;
	public $type = 'json';
	public $exportAllData = false;
	
	public function setType($type)
	{
		$this->type = $type;
	}
	
	public function setExportData($data) {
		$this->exportData = $data;
	}
	
	public function setExportAllData($exportAllData) {
		$this->exportAllData = $exportAllData;
	}
	
	public function exportAsType($data)
	{
		$export = $this->_getExporter($data);
		
		$export->setType($this->type);
		$exportStatus = $export->start();
		
		if (isset($exportStatus['download']) && !empty($exportStatus['download'])) {
			return array(
				'success' => 'Items are exported',
				'export_type' => $this->type,
				'data' => $exportStatus
			);
		} else {
			/* return array(
				'error' => 'Export format not supported.'
			); */
			return $exportStatus;
		}
	}

	public function start() {
		
		$readyData = $this->_getReadyDataCached();
		
		return $this->exportAsType($readyData);
	}
	
	private function _getExportDataHash() {
		return md5(json_encode($this->exportData));
	}
	
	private function _getReadyDataCached() {
		
		$exportDataHash = $this->_getExportDataHash();
		
		$zipExport = new ZipExport();
		$currentStep = $zipExport->getCurrentStep();
		
		if ($currentStep == 0) {
			// This is frist step
			Cache::forget($exportDataHash);
			return Cache::rememberForever($exportDataHash, function () {
				return $this->_getReadyData();
			});
		} else {
			BackupExportLogger::setLogInfo('Start exporting selected data...');
			// This is for the next steps from wizard
			return Cache::get($exportDataHash);
		}
	}
	
	private function _getReadyData() {
		
		$exportTables = new ExportTables();
		
		$readyContent = array();
		$tables = $this->_getTablesForExport();
		
		foreach($tables as $table) {
			
			BackupExportLogger::setLogInfo('Exporting table: <b>' . $table. '</b>');
			
			$exportTables->addTable($table);
			
			$ids = array();
			
			if ($table == 'categories') {
				if (!empty($this->exportData['categoryIds'])) {
					$ids = $this->exportData['categoryIds'];
				}
			}
			
			if ($table == 'content') {
				if (!empty($this->exportData['contentIds'])) {
					$ids = $this->exportData['contentIds'];
				}
			}
			
			$tableContent = $this->_getTableContent($table, $ids);
			
			if (!empty($tableContent)) {
				
				$exportTables->addItemsToTable($table, $tableContent);
				
				$relations = array();
				foreach($tableContent as $content) {
					if (isset($content['rel_type']) && isset($content['rel_id'])) {
						$relations[$content['rel_type']][$content['rel_id']] = $content['rel_id'];
					}
				}
				
				if (!empty($relations)) {
					
					BackupExportLogger::setLogInfo('Get relations from table: <b>' . $table . '</b>');
					
					foreach($relations as $relationTable=>$relationIds) {
						
						BackupExportLogger::setLogInfo('Get data from relations table: <b>' . $relationTable. '</b>');
						
						$relationTableContent = $this->_getTableContent($relationTable, $relationIds);
						
						$exportTables->addItemsToTable($relationTable, $relationTableContent);
					}
				}
				
			}
		}
		
		return $exportTables->getAllTableItems();
	}
	
	private function _getTableContent($table, $ids = array()) {
		
		$exportFilter = array();
		$exportFilter['no_limit'] = 1;
		$exportFilter['do_not_replace_site_url'] = 1;
		
		if (!empty($ids)) {
			$exportFilter['ids'] = implode(',', $ids);
		}
		
		$table_exists = mw()->database_manager->table_exists($table);
		if (!$table_exists) {
			return;
		}
		
		return db_get($table, $exportFilter);
	}
	
	private function _skipTables() {
		
		$this->skipTables[] = 'modules';
		$this->skipTables[] = 'elements';
		$this->skipTables[] = 'users';
		$this->skipTables[] = 'log';
		$this->skipTables[] = 'notifications';
		$this->skipTables[] = 'content_revisions_history';
		$this->skipTables[] = 'module_templates';
		$this->skipTables[] = 'stats_users_online';
		$this->skipTables[] = 'stats_browser_agents';
		$this->skipTables[] = 'stats_referrers_paths';
		$this->skipTables[] = 'stats_referrers_domains';
		$this->skipTables[] = 'stats_referrers';
		$this->skipTables[] = 'stats_visits_log';
		$this->skipTables[] = 'stats_urls';
		$this->skipTables[] = 'system_licenses';
		$this->skipTables[] = 'users_oauth';
		$this->skipTables[] = 'sessions';
		$this->skipTables[] = 'global';
		
		return $this->skipTables;
	}
	
	private function _prepareSkipTables() {
		
		$skipTables = $this->_skipTables();
		
		// Add table categories if we have category ids
		if (!empty($this->exportData['categoryIds'])) {
			if (!in_array('categories',$this->exportData['tables'])) {
				$this->exportData['tables'][] = 'categories';
			}
		}
		
		// Add table categories if we have content ids
		if (!empty($this->exportData['contentIds'])) {
			if (!in_array('content', $this->exportData['tables'])) {
				$this->exportData['tables'][] = 'content';
			}
		}
		
		if (!empty($this->exportData['tables'])) {
			if (in_array('users', $this->exportData['tables'])) {
				$keyOfSkipTable = array_search('users', $skipTables);
				if ($keyOfSkipTable) {
					unset($skipTables[$keyOfSkipTable]);
				}
			}
		}
		
		return $skipTables;
	}
	
	private function _getTablesForExport() {
		
		$skipTables = $this->_prepareSkipTables();
		
		$tablesList = mw()->database_manager->get_tables_list();
		$tablePrefix = mw()->database_manager->get_prefix();
		
		$readyTableList = array();
		foreach ($tablesList as $tableName) {
			
			if ($tablePrefix) {
				$tableName = str_replace_first($tablePrefix, '', $tableName);
			}
			
			if (in_array($tableName, $skipTables)) {
				continue;
			}
			
			if (!empty($this->exportData) && $this->exportAllData == false) {
				if (isset($this->exportData['tables'])) {
					if (!in_array($tableName, $this->exportData['tables'])) {
						continue;
					}
				}
			}
			
			$readyTableList[] = $tableName;
			
		}
		
		return $readyTableList;
	}
	
	private function _getExporter($data) {
		
		$export = false;
		
		switch ($this->type) {
			case 'json':
				$export = new JsonExport($data);
				break;
				
			case 'csv':
				$export = new CsvExport($data);
				break;
				
			case 'xml':
				$export = new XmlExport($data);
				break;
				
			case 'zip':
				$export = new ZipExport($data);
				break;
				
			default:
				throw new \Exception('Format not supported for exporting.');
		}
		
		return $export;
	}
	
}