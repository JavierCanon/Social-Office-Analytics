## Changelog

# 1.3.3

Changes:
* Use quotaUser to support multi-instance setups.

# 1.3.2

Bug fixes:
* Fix bug in ongoing import that could result in incomplete metrics being imported. Bug is more visible since changes in 1.3.0.

# 1.3.1

Changes:
* Allow re-importing ranges to work when a job is finished or has no more to import.
* Merge Time Started/Time Finished columns to provide more space in the UI.

Bug fix:
* Do not show resume button if status is 'started'.

# 1.3.0

Features:
* Improved support for shared hosting users with hosts that may kill long running processes. The import job is not attempted every hour
  if a system kills a job, it will restart promptly.
* Detect killed jobs and report to the user so they are not left in suspense.
* Allow re-importing ranges in the past.
* Add a protection for users of Matomo 3.13.2 that will disallow re-archiving of imported days (this can wipe the data that was imported).

# 1.2.2

Bug fixes:
* Fixing typo.
* Small style tweak.

# 1.2.1

Bug fixes:
* Handle old statuses without new property.

# 1.2.0

Features:
* Resume button to make it clearer that on an errored import the import doesn't have to be cancelled and restarted.
* Add feature to change import end date dynamically so users don't have to restart if they enter the wrong end date (or don't enter one).
* Support new VisitFrequency metrics in core if available.

Bug fixes:
* Tweaks to messages for clarity.
* Goals record importer was not applying new/returning segments.
* GA does not trim page titles, so ignore on error and hope users report issues.

# 1.1.2

Bug fixes:
* Fix variable not defined error.
* Make sure version is compatible w/ older versions of Matomo.

# 1.1.1

Bug fixes:
* Compatibility with Matomo for wordpress.
* Do not fail if an unmappable goal is found (in case user creates their own goal or edits a goal).

# 1.1.0

Features:
* Add new diagnostics to check for required functions and executables.
* Add troubleshooting option to enable debug logging so users can provide useful info in a bug report.
* Allow importing GA dimensions not natively supported in Matomo by creating new custom dimensions.
* Support importing mobile app properties (including screen views metrics as pageviews and screen reports as page title reports).

Bug fixes:
* Remove extra params when redirecting from processAuthCode action.
* Change include paths to better support wordpress installs.
* Do not try to import ecommerce items report if property does not support ecommerce.
* Ordering in GA API requests was not applied.
* Entry/exit page titles should not import unique visitors since we can't get that information reliably.
* URLs that end in the action default name cause a conflict w/ directory paths. This is not an issue anymore.
* Better process strange referrer URLs from GA.
* Allow specifying timezone manually in case GA timezone is not a valid PHP timezone.

# 1.0.6

* Better and configurable mysql ping for shared hosting.
* If invalid or missing config is found delete existing client configuration.

# 1.0.5

* Display query count even on rate limit in command output.
* Issue pointless mysql query to keep connection alive on systems that have a small wait_timeout.

# 1.0.4

* Add --skip-archiving option to allow avoiding launching of archiving command when importing.
* Default empty keyword value when importing campaign keyword report.
* Use CliPhp to determine php binary and default to just php if not found.

# 1.0.3

* Allow account ID to be specified explicitly since it can differ from the number in the UA-... property ID.
* Print debug message when account ID is deduced from property ID in CLI command.
* Use exponentially increasing wait time between rate limited requests when querying GA API.

# 1.0.2

* Add import date to error message when import fails.
* Fix bug in Actions record importer where it did not handle summary rows correctly.
* Fix untranslated text.

# 1.0.1

* Fix typo in actions record importer.

# 1.0.0-b1

* Initial release (beta).
