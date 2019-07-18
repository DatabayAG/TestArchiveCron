<?php
// Copyright (c) 2018 Institut fuer Lern-Innovation, Friedrich-Alexander-Universitaet Erlangen-Nuernberg, GPLv3, see LICENSE

include_once "Services/Cron/classes/class.ilCronJob.php";

class ilTestArchiveCronJob  extends ilCronJob
{
	/** @var  ilTestArchiveCronPlugin */
	protected $plugin;

	public function __construct($plugin)
	{
		$this->plugin = $plugin;
	}

	public function getId()
	{
		return "test_archive_cron";
	}

	public function getTitle()
	{
		return $this->plugin->txt('job_title');
	}

	public function getDescription()
	{
		if (!$this->plugin->checkCreatorPluginActive()) {
			return $this->plugin->txt('message_creator_plugin_missing');
		}
		return $this->plugin->txt('job_description');
	}

	public function getDefaultScheduleType()
	{
		return self::SCHEDULE_TYPE_IN_HOURS;
	}

	public function getDefaultScheduleValue()
	{
		return 1;
	}

	public function hasAutoActivation()
	{
		return true;
	}

	public function hasFlexibleSchedule()
	{
		return true;
	}

	public function hasCustomSettings()
	{
		return true;
	}

	/**
	 * Defines whether or not a cron job can be started manually
	 * @return bool
	 */
	public function isManuallyExecutable()
	{
		if (!$this->plugin->checkCreatorPluginActive()) {
			return false;
		}
		return parent::isManuallyExecutable();
	}

	/**
	 * Run the cron job
	 * @return ilCronJobResult
	 */
	public function run()
	{
		$result = new ilCronJobResult();

		if (!$this->plugin->checkCreatorPluginActive())
		{
			$result->setStatus(ilCronJobResult::STATUS_INVALID_CONFIGURATION);
			$result->setMessage($this->plugin->txt('message_creator_plugin_missing'));
			return $result;
		}
		else
		{
			try
			{
				/** @var ilTestArchiveCreatorPlugin $creatorPlugin */
				$creatorPlugin = $this->plugin->getCreatorPlugin();
				$number = $creatorPlugin->handleCronJob();
				if ($number == 0)
				{
					$result->setStatus(ilCronJobResult::STATUS_NO_ACTION);
					$result->setMessage($this->plugin->txt('no_archive_created'));
				}
				elseif ($number == 1)
				{
					$result->setStatus(ilCronJobResult::STATUS_OK);
					$result->setMessage($this->plugin->txt('one_archive_created'));

				}
				else {
					$result->setStatus(ilCronJobResult::STATUS_OK);
					$result->setMessage(sprintf($this->plugin->txt('x_archives_created'), $number));
				}
				return $result;
			}
			catch (Exception $e) {
				$result->setStatus(ilCronJobResult::STATUS_FAIL);
				$result->setMessage($e->getMessage());
				return $result;
			}
		}
	}


	/**
	 * Add custom settings to form
	 *
	 * @param ilPropertyFormGUI $a_form
	 * @throws ilDateTimeException
	 */
	public function addCustomSettingsToForm(ilPropertyFormGUI $a_form)
	{
		$setrun = new ilCheckboxInputGUI($this->plugin->txt('set_last_run'), 'set_last_run');
		$setrun->setInfo($this->plugin->txt('set_last_run_info'));
		$a_form->addItem($setrun);

		$lastrun = new ilDateTimeInputGUI($this->plugin->txt('last_run'), 'last_run');
		$lastrun->setShowTime(true);
		$lastrun->setShowSeconds(false);
		$lastrun->setMinuteStepSize(10);
		$lastrun->setDate($this->getLastRun());
		$setrun->addSubItem($lastrun);
	}

	/**
	 * Save custom settings
	 *
	 * @param ilPropertyFormGUI $a_form
	 * @return boolean
	 */
	public function saveCustomSettings(ilPropertyFormGUI $a_form)
	{
		global $DIC;
		$ilDB = $DIC->database();
		$ilUser = $DIC->user();

		if ($a_form->getInput('set_last_run')) {
			/** @var ilDateTimeInputGUI $lastrun */
			$lastrun = $a_form->getItemByPostVar('last_run');

			/** @var ilDateTime $date */
			$date = $lastrun->getDate();

			if (isset($date)) {

				$sql = "UPDATE cron_job SET ".
					" job_result_status = ".$ilDB->quote(null, "integer").
					" , job_result_user_id = ".$ilDB->quote($ilUser->getId(), "integer").
					" , job_result_code = ".$ilDB->quote(ilCronJobResult::CODE_MANUAL_RESET, "text").
					" , job_result_message = ".$ilDB->quote('', "text").
					" , job_result_type = ".$ilDB->quote(1, "integer").
					" , job_result_ts = ".$ilDB->quote($date->getUnixTime(), "integer").
					" , job_result_dur = ".$ilDB->quote(0, "integer").
					" WHERE job_id = ".$ilDB->quote($this->getId(), "text");
				$ilDB->manipulate($sql);
			}
		}

		return true;
	}

	/**
	 * get the date of the last run
	 * @return ilDateTime|null
	 * @throws ilDateTimeException
	 */
	public function getLastRun()
	{
		$rows = ilCronManager::getCronJobData($this->getId());
		$ts = $rows[0]['job_result_ts'];

		if ($ts > 0) {
			return new ilDateTime($ts, IL_CAL_UNIX);
		}
		return null;
	}
}