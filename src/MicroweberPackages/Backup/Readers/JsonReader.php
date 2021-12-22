<?php
namespace MicroweberPackages\Backup\Readers;

$dir = __DIR__;
$dir = str_replace('Backup\Readers', '', $dir);
$dir = str_replace('Backup/Readers', '', $dir);

use \JsonMachine\Items;
use JsonMachine\JsonDecoder\ExtJsonDecoder;
use MicroweberPackages\Backup\EncodingFix;

class JsonReader extends DefaultReader
{

	public function readData()
	{
		$readyJson = array();
        $json = Items::fromFile($this->file,'', new ExtJsonDecoder(true));


		foreach ($json as $jsonKey => $jsonValue) {
			$readyJson[$jsonKey] = $jsonValue;
		}

		if (isset($readyJson[0]['id'])) {
		   return EncodingFix::decode(array("content"=>$readyJson));
        }

		return EncodingFix::decode($readyJson);
	}
}
