# TestArchiveCron

Copyright (c) 2017 Institut fuer Lern-Innovation, Friedrich-Alexander-Universitaet Erlangen-Nuernberg, GPLv3, see LICENSE

**Further maintenance can be offered by [Databay AG](https://www.databay.de).**

Versions: see [Changelog](CHANGELOG.md). Plugin versions for different ILIAS releases are provided in separate branches of this repository.

This plugin for the LMS ILIAS open source allows the scheduled creation of test archives.

It requires an installation of the TestArchiveCreator plugin:
https://github.com/DatabayAG/TestArchiveCreator


## Installation
1. Put the content of the plugin directory in a subdirectory under your ILIAS main directory:
public/Customizing/global/plugins/Services/Cron/CronHook/TestArchiveCron
2. Run `composer du` in the main directory of your ILIAS installation
3. Go to Administration > Extending ILIAS > Plugins
4. Install and activate the plugin


## Configuration

You need to set up a call of the ILIAS cron jobs on your web server, see the ILIAS installation guide:
https://www.ilias.de/docu/goto_docu_pg_8240_367.html

1. Go to Administration > General Settings > Cron Jobs
2. Activate the 'Test Archive Creation' job
3. Set a reasonable schedule for the job, e.h. hourly.


## Usage

See the documentation of the TestArchiveCreator plugin.
