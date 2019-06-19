<?php
namespace Microweber\Utils\Backup;

use Microweber\Utils\Backup\Readers\ZipReader;
use Microweber\Utils\Backup\Readers\JsonReader;
use Microweber\Utils\Backup\Readers\CsvReader;
use Microweber\Utils\Backup\Readers\XmlReader;
use Microweber\App\Providers\Illuminate\Support\Facades\Cache;
use Microweber\Utils\Backup\Loggers\BackupImportLogger;

class Import
{
	/**
	 * The import file type
	 *
	 * @var string
	 */
	public $type;

	/**
	 * The import file path
	 *
	 * @var string
	 */
	public $file;

	/**
	 * Set file type
	 *
	 * @param string $file
	 */
	public function setType($type)
	{
		$this->type = $type;
	}

	/**
	 * Set file path
	 *
	 * @param string $file
	 */
	public function setFile($file)
	{
		$this->file = $file;
	}

	/**
	 * Import data as type
	 *
	 * @param array $data
	 * @return array
	 */
	public function importAsType($file)
	{
		$reader = $this->_getReader($file);
		if ($reader) {
			
			BackupImportLogger::setLogInfo('Reading data from file ' . basename($this->file));

			try {
				$readedData = $reader->readData();
			} catch (\Exception $e) {
				$readedData = array();
				// $fileIsBroken = 'Can\'t read data. The file is corrupt.';
				$fileIsBroken = $e->getMessage();
				BackupImportLogger::setLogInfo($fileIsBroken);
				
				throw new \Exception($fileIsBroken);
			}
			
			if (! empty($readedData)) {
				$successMessages = count($readedData, COUNT_RECURSIVE) . ' items are readed.';
				BackupImportLogger::setLogInfo($successMessages);
				return array(
					'success' => $successMessages,
					'imoport_type' => $this->type,
					'data' => $this->_fixContentEncoding($readedData)
				);
			}
		}

		$formatNotSupported = 'Import format not supported';
		BackupImportLogger::setLogInfo($formatNotSupported);
		
		throw new \Exception($formatNotSupported);
		
	}

	/**
	 * Get readed content from import file.
	 *
	 * @return array
	 */
	public function readContentWithCache()
	{
		$databaseWriter = new DatabaseWriter();
		$currentStep = $databaseWriter->getCurrentStep();
		
		if ($currentStep == 0) {
			// This is frist step
			Cache::forget(md5($this->file));
			return Cache::rememberForever(md5($this->file), function () {
				BackupImportLogger::setLogInfo('Start importing session..');
				
				return $this->importAsType($this->file);
			});
		} else {
			BackupImportLogger::setLogInfo('Read content from cache..');
			
			// This is for the next steps from wizard
			return Cache::get(md5($this->file));
		}
	}
	
	public function readContent() {		
		
		BackupImportLogger::setLogInfo('Start importing session..');
		
		return $this->importAsType($this->file);
	}
	
	/**
	 * Fix wrong encoding on database
	 * @param array $item
	 * @return array
	 */
	private function _fixContentEncoding($content) {
		
		// Fix content encoding
		array_walk_recursive($content, function (&$element) {
			if (is_string($element)) {
				$utf8Chars = explode(' ', 'À Á Â Ã Ä Å Æ Ç È É Ê Ë Ì Í Î Ï Ð Ñ Ò Ó Ô Õ Ö × Ø Ù Ú Û Ü Ý Þ ß à á â ã ä å æ ç è é ê ë ì í î ï ð ñ ò ó ô õ ö');
				foreach ($utf8Chars as $char) {
					$element = str_replace($char, '', $element);
				}
			}
		});
			
		return $content;
	}

	/**
	 * Get file reader by type
	 *
	 * @param array $data
	 * @return boolean|\Microweber\Utils\Backup\Readers\DefaultReader
	 */
	private function _getReader($data = array())
	{
		$reader = false;

		switch ($this->type) {
			case 'json':
				$reader = new JsonReader($data);
				break;
				
			case 'csv':
				$reader = new CsvReader($data);
				break;
				
			case 'xml':
				$reader = new XmlReader($data);
				break;
				
			case 'zip':
				$reader = new ZipReader($data);
				break;
				
			default:
				throw new \Exception('Format not supported for importing.');
				break;
		}

		return $reader;
	}
}
