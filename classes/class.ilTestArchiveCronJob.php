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
}