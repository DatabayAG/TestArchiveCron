# TestArchiveCron

Copyright (c) 2017 Institut fuer Lern-Innovation, Friedrich-Alexander-Universitaet Erlangen-Nuernberg
GPLv3, see LICENSE

Author: Fred Neumann <fred.neumann@ili.fau.de>


This plugin for the LMS ILIAS open source allows the scheduled creation of test archives.

It requires an installation of the TestArchiveCreator plugin:
https://github.com/ilifau/TestArchiveCreator


INSTALLATION
------------
1. Put the content of the plugin directory in a subdirectory under your ILIAS main directory:
Customizing/global/plugins/Services/Cron/CronHook/TestArchiveCron

2. Open ILIAS > Administration > Plugins

3. Update/Activate the plugin


CONFIGURATION
-------------

You need to set up a call of the ILIAS cron jobs on your web server, see the ILIAS installation guide:
https://www.ilias.de/docu/goto_docu_pg_8240_367.html

1. Go to Administration > General Settings > Cron Jobs

2. Activate the 'Test Archive Creation' job

3. Set a reasonable schedule for the job, e.h. hourly.


USAGE
-----

See the documentation of the TestArchiveCreator plugin.


VERSIONS
--------

1.5.0 for ILIAS 8 (2023-05-11)
- new version for ILIAS 8
- works with TestArchiveCreator >= 1.5.1

1.3.0 for ILIAS 7 (2021-12-09)
- new version for ILIAS 7
- removed unneccessay catching of exceptions

1.1.1 for ILIAS 5.2 and 5.3 (2018-07-18)
- set the last run date for schedule initialisation

1.1.0 for ILIAS 5.2 and 5.3 (2018-05-07)
- compatibility for ILIAS 5.3

1.0.0 for ILIAS 5.2 (2018-01-18)
- initial version
