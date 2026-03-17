<?php

$_LANG['admin.menu.queue']        = 'Queue';
$_LANG['admin.breadcrumbs.Queue'] = 'Queue';

//table
$_LANG['packages.queue.data_table.queue.title']         = 'Tasks';
$_LANG['packages.queue.data_table.queue.titleError']    = 'Tasks - Error';
$_LANG['packages.queue.data_table.queue.titleRunning']  = 'Tasks - In Progress';
$_LANG['packages.queue.data_table.queue.titlePending']  = 'Tasks - Pending';
$_LANG['packages.queue.data_table.queue.titleFinished'] = 'Tasks - Finished';

$_LANG['packages.queue.data_table.queue.id']           = 'ID';
$_LANG['packages.queue.data_table.queue.related_item'] = 'Related Item';
$_LANG['packages.queue.data_table.queue.status']       = 'Status';
$_LANG['packages.queue.data_table.queue.job']          = 'Task';
$_LANG['packages.queue.data_table.queue.retry_count']  = 'Attempts';
$_LANG['packages.queue.data_table.queue.created_at']   = 'Created At';
$_LANG['packages.queue.data_table.queue.updated_at']   = 'Updated At';
$_LANG['packages.queue.data_table.queue.retry_after']  = 'Scheduled For';

$_LANG['packages.queue.buttons.edit_configuration.title'] = 'Settings';
$_LANG['packages.queue.data_table.queue.next_cron_run']   = 'Next Cron Run';

//buttons
$_LANG['packages.queue.buttons.run_button.title']       = 'Run';
$_LANG['packages.queue.buttons.show_data_button.title'] = 'Details';

//modals
//Run task modal
$_LANG['packages.queue.modals.run_modal.title']              = 'Run Task';
$_LANG['packages.queue.modals.run_modal.description']        = 'Are you sure that you want to run the selected task?';
$_LANG['packages.queue.forms.run_form.run_success']          = 'The task has been run successfully';
$_LANG['packages.queue.forms.run_form.runTaskFailedSeeLogs'] = 'Task ran failed. See logs';

//Show details modal
$_LANG['packages.queue.modals.show_data_modal.title']   = 'Task Details';
$_LANG['packages.queue.modals.show_data_modal.details'] = 'Details';
$_LANG['packages.queue.modals.show_data_modal.logs']    = 'Logs';

//Delete
$_LANG['packages.queue.modals.delete_job_modal.title']                = 'Delete Task';
$_LANG['packages.queue.modals.delete_job_modal.description']          = 'Are you sure that you want to delete the selected task?';
$_LANG['packages.queue.providers.delete_job_provider.delete_success'] = 'The task :name has been deleted successfully';

//Mass Delete
$_LANG['packages.queue.buttons.mass_delete_job_button.DeleteMass'] = 'Delete';
$_LANG['packages.queue.modals.mass_delete_job_modal.title']        = 'Delete Tasks';
$_LANG['packages.queue.modals.mass_delete_job_modal.description']  = 'Are you sure that you want to delete the selected tasks?';
$_LANG['packages.queue.forms.mass_delete_job_form.delete_success'] = 'The selected tasks have been deleted successfully';

//Edit modal
$_LANG['components.dropdown_menu.dropdown_menu.more_actions']                            = 'Actions';
$_LANG['packages.queue.modals.edit_configuration_modal.title']                           = 'Edit Settings';
$_LANG['packages.queue.forms.edit_configuration_form.auto_prune']                        = 'Auto Prune';
$_LANG['packages.queue.forms.edit_configuration_form.auto_prune_description']            = 'Automatically remove tasks after a specified period.';
$_LANG['packages.queue.forms.edit_configuration_form.auto_prune_older_than']             = 'Auto Prune Older Than';
$_LANG['packages.queue.forms.edit_configuration_form.auto_prune_older_than_description'] = 'Enter the number of days after which tasks will be automatically pruned.';
$_LANG['packages.queue.forms.edit_configuration_form.show_cron_info']                    = "Display 'Cron Job Configuration' Hint";
$_LANG['packages.queue.forms.edit_configuration_form.show_cron_info_description']        = 'When enabled, a hint with Cron Job information will be displayed.';
$_LANG['packages.queue.forms.edit_configuration_form.formValidationError']               = 'Please enter correct values';
$_LANG['packages.queue.forms.edit_configuration_form.update_success']                    = 'Settings have been changed successfully';

$_LANG['packages.queue.providers.configuration_provider.attributes.auto_prune'] = 'Auto Prune';
$_LANG['packages.queue.providers.configuration_provider.values.auto_prune.1']   = 'enabled';

//Task info
$_LANG['packages.queue.widgets.task_details_tab.details'] = "Details";

$_LANG['packages.queue.widgets.task_details_tab.id']          = "ID";
$_LANG['packages.queue.widgets.task_details_tab.job']         = "Job";
$_LANG['packages.queue.widgets.task_details_tab.queue']       = "Queue";
$_LANG['packages.queue.widgets.task_details_tab.retryCount']  = "Attempts";
$_LANG['packages.queue.widgets.task_details_tab.parentId']    = "Parent ID";
$_LANG['packages.queue.widgets.task_details_tab.relType']     = "Type";
$_LANG['packages.queue.widgets.task_details_tab.relatedItem'] = "Related Item";
$_LANG['packages.queue.widgets.task_details_tab.relCustom']   = "Custom Relation";
$_LANG['packages.queue.widgets.task_details_tab.status']      = "Status";
$_LANG['packages.queue.widgets.task_details_tab.retryAfter']  = "Retry After";
$_LANG['packages.queue.widgets.task_details_tab.createdAt']   = "Created";
$_LANG['packages.queue.widgets.task_details_tab.updatedAt']   = "Updated";

//Task Logs
$_LANG['packages.queue.widgets.task_logs_tab.logs']       = "Logs";
$_LANG['packages.queue.widgets.task_logs_tab.noLogsInfo'] = "There are no logs available for this task";

//Task Additional Info
$_LANG['packages.queue.widgets.task_additional_info_tab.additionalInfo'] = "Additional Information";

//Hint Box
$_LANG['packages.queue.widgets.hints_box.cronJobInfoHintTitle']          = "Cron Job Configuration";
$_LANG['packages.queue.widgets.hints_box.cronJobInfoHintExecuteCycle']   = "The cron job for the :moduleName (recommended every :minutesCount minutes):";
$_LANG['packages.queue.widgets.hints_box.cronJobInfoHintAdditionalInfo'] = "It ensures that queued tasks are executed automatically. <br> It can also run as frequently as every minute, though this may increase the load on the WHMCS server.";

//Tasks Summary
$_LANG['packages.queue.widgets.queue_summary_widget.errorTitle']                 = "Error";
$_LANG['packages.queue.widgets.queue_summary_widget.errorTitle_description']     = "The task encountered an error and will retry.";
$_LANG['packages.queue.widgets.queue_summary_widget.failedTitle']                = "Failed";
$_LANG['packages.queue.widgets.queue_summary_widget.failedTitle_description']    = "The task failed after reaching the maximum number of retry attempts.";
$_LANG['packages.queue.widgets.queue_summary_widget.runningTitle']               = "Running";
$_LANG['packages.queue.widgets.queue_summary_widget.runningTitle_description']   = "The task is currently in progress.";
$_LANG['packages.queue.widgets.queue_summary_widget.pendingTitle']               = "Pending";
$_LANG['packages.queue.widgets.queue_summary_widget.pendingTitle_description']   = "The task is scheduled and will start shortly.";
$_LANG['packages.queue.widgets.queue_summary_widget.finishedTitle']              = 'Finished';
$_LANG['packages.queue.widgets.queue_summary_widget.finishedTitle_description']  = 'The task completed successfully.';
$_LANG['packages.queue.widgets.queue_summary_widget.waitingTitle']               = 'Waiting';
$_LANG['packages.queue.widgets.queue_summary_widget.waitingTitle_description']   = 'The task has started but is waiting for execution.';
$_LANG['packages.queue.widgets.queue_summary_widget.cancelledTitle']             = 'Cancelled';
$_LANG['packages.queue.widgets.queue_summary_widget.cancelledTitle_description'] = 'The task was manually stopped before completion.';
$_LANG['packages.queue.widgets.queue_summary_widget.totalTitle']                 = "Total";
$_LANG['packages.queue.widgets.queue_summary_widget.totalTitle_description']     = "Total number of tasks across all statuses.";

$_LANG['packages.queue.widgets.queue_summary_widget.show'] = "Show";

//Jobs Priority
$_LANG['packages.queue.forms.edit_configuration_form.queue_priority']             = "Task Priority";
$_LANG['packages.queue.forms.edit_configuration_form.queue_priority_description'] = "Select tasks in order of priority, starting with the highest priority first. Unselected tasks will automatically be assigned the lowest priority.";

// Job STATUSES

$_LANG['packages.queue.support.translations.job_status_translator.error']     = "Error";
$_LANG['packages.queue.support.translations.job_status_translator.failed']    = "Failed";
$_LANG['packages.queue.support.translations.job_status_translator.running']   = "Running";
$_LANG['packages.queue.support.translations.job_status_translator.pending']   = "Pending";
$_LANG['packages.queue.support.translations.job_status_translator.finished']  = "Finished";
$_LANG['packages.queue.support.translations.job_status_translator.waiting']   = "Waiting";
$_LANG['packages.queue.support.translations.job_status_translator.cancelled'] = "Cancelled";

// Job lOGS STATUSES

$_LANG['packages.queue.support.translations.log_status_translator.error']   = "Error";
$_LANG['packages.queue.support.translations.log_status_translator.info']    = "Info";
$_LANG['packages.queue.support.translations.log_status_translator.success'] = "Success";


//**************** FRAGMENTS ***************

$_LANG['packages.queue.fragments.scheduled_tasks.data_tables.service_scheduled_tasks.title'] = 'Scheduled Tasks';
$_LANG['packages.queue.fragments.scheduled_tasks.data_tables.service_scheduled_tasks.job'] = 'Job';
$_LANG['packages.queue.fragments.scheduled_tasks.data_tables.service_scheduled_tasks.status'] = 'Status';
$_LANG['packages.queue.fragments.scheduled_tasks.data_tables.service_scheduled_tasks.attempts'] = 'Attempts';
$_LANG['packages.queue.fragments.scheduled_tasks.data_tables.service_scheduled_tasks.message'] = 'Message';
$_LANG['packages.queue.fragments.scheduled_tasks.data_tables.service_scheduled_tasks.created_at'] = 'Created At';
$_LANG['packages.queue.fragments.scheduled_tasks.data_tables.service_scheduled_tasks.updated_at'] = 'Updated At';

//**************** CHECKERS ***************
$_LANG['packages.queue.checkers.mismatchedTimeZone'] = 'The timezone does not match the one detected from the CLI. Make sure you enforce the timezone in configuration.';