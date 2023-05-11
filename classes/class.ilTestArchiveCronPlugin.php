<?php
// Copyright (c) 2018 Institut fuer Lern-Innovation, Friedrich-Alexander-Universitaet Erlangen-Nuernberg, GPLv3, see LICENSE

include_once("./Services/Cron/classes/class.ilCronHookPlugin.php");

class ilTestArchiveCronPlugin extends ilCronHookPlugin
{
	function getPluginName() : string
	{
		return "TestArchiveCron";
	}

	function getCronJobInstances() : array
	{
		return array($this->getCronJobInstance('test_archive_cron'));
	}

	function getCronJobInstance($a_job_id) : ilCronJob
	{
		return new ilTestArchiveCronJob($this);
	}

	/**
	 * Do checks bofore activating the plugin
	 * @return bool
	 * @throws ilPluginException
	 */
	function beforeActivation() : bool
	{
        global $DIC;

		if (!$this->checkCreatorPluginActive()) {
			throw new ilPluginException($this->txt("message_creator_plugin_missing"));
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
        if (!empty($plugin = $this->getCreatorPlugin())) {
            return $plugin->isActive();
        }
        return false;
	}

	/**
	 * Get the creator plugin object
	 * @return ilPlugin|null
	 */
	public function getCreatorPlugin() : ?ilPlugin
	{
        global $DIC;

        /** @var ilComponentFactory $factory */
        $factory = $DIC["component.factory"];

        /** @var ilPlugin $plugin */
        Foreach ($factory->getActivePluginsInSlot('uihk') as $plugin) {
            if ($plugin->getPluginName() == 'TestArchiveCreator') {
                return $plugin;
            }
        }

        return null;
	}
}