<?php
// Copyright (c) 2018 Institut fuer Lern-Innovation, Friedrich-Alexander-Universitaet Erlangen-Nuernberg, GPLv3, see LICENSE

include_once("./Services/Cron/classes/class.ilCronHookPlugin.php");

class ilTestArchiveCronPlugin extends ilCronHookPlugin
{
	function getPluginName()
	{
		return "TestArchiveCron";
	}

	function getCronJobInstances()
	{
		return array($this->getCronJobInstance('test_archive_cron'));
	}

	function getCronJobInstance($a_job_id)
	{
		$this->includeClass('class.ilTestArchiveCronJob.php');
		return new ilTestArchiveCronJob($this);
	}

	/**
	 * Do checks bofore activating the plugin
	 * @return bool
	 * @throws ilPluginException
	 */
	function beforeActivation()
	{
		if (!$this->checkCreatorPluginActive()) {
			ilUtil::sendFailure($this->txt("message_creator_plugin_missing"), true);
			// this does not show the message
			// throw new ilPluginException($this->txt("message_creator_plugin_missing"));
			return false;
		}

		return parent::beforeActivation();
	}

	/**
	 * Check if the player plugin is active
	 * @return bool
	 */
	public function checkCreatorPluginActive()
	{
		global $DIC;
		/** @var ilPluginAdmin $ilPluginAdmin */
		$ilPluginAdmin = $DIC['ilPluginAdmin'];

		return $ilPluginAdmin->isActive('Services', 'UIComponent', 'uihk', 'TestArchiveCreator');
	}

	/**
	 * Get the creator plugin object
	 * @return ilPlugin
	 */
	public function getCreatorPlugin()
	{
		return ilPluginAdmin::getPluginObject('Services', 'UIComponent', 'uihk', 'TestArchiveCreator');
	}
}