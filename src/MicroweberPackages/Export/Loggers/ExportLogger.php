<?php
namespace MicroweberPackages\Backup\Loggers;

use MicroweberPackages\Backup\Loggers\DefaultLogger;

class ExportLogger extends DefaultLogger
{
	public static $logName = 'Exporting';
	public static $logFileName = 'backup-export-session.log';

}
