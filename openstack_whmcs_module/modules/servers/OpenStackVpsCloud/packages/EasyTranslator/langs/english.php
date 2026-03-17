<?php

$_LANG['admin.menu.easyTranslator']        = 'Translations';
$_LANG['admin.breadcrumbs.EasyTranslator'] = 'Translations';

//**********  TABS  **********
$_LANG['packages.easy_translator.translations.tabs.languages_tab.languagesTabTitle']                      = 'Languages';
$_LANG['packages.easy_translator.translations.tabs.missing_langs_tab.missingLangsTabTitle']               = 'Missing Elements';
$_LANG['packages.easy_translator.translations.tabs.dynamic_translations_tab.dynamicTranslationsTabTitle'] = 'Dynamic Translations';

//**********  LANGUAGES TABLE  **********
$_LANG['packages.easy_translator.languages.pages.languages_table.language']        = 'Language';
$_LANG['packages.easy_translator.languages.pages.languages_table.created_at']      = 'Created';
$_LANG['packages.easy_translator.languages.buttons.create_language_button.title']  = 'Add Translation';
$_LANG['packages.easy_translator.languages.buttons.clone_language_button.title']   = 'Clone Translation';
$_LANG['packages.easy_translator.languages.buttons.import_language_button.title']  = 'Import Translation';
$_LANG['packages.easy_translator.languages.buttons.export_language_button.title']  = 'Export Translation';
$_LANG['packages.easy_translator.languages.buttons.refresh_language_button.title'] = 'Update';
$_LANG['packages.easy_translator.languages.buttons.mass_delete_button.delete']     = 'Delete';
$_LANG['packages.easy_translator.languages.buttons.settings_button.title']         = 'Settings';

//Modal create language
$_LANG['packages.easy_translator.languages.modals.create_language_modal.title']             = 'Add Translation';
$_LANG['packages.easy_translator.languages.modals.create_language_modal.submit']            = 'Confirm';
$_LANG['packages.easy_translator.languages.forms.create_language_form.language']            = 'Select Language';
$_LANG['packages.easy_translator.languages.forms.create_language_form.create_success']      = 'New translation has been created successfully';
$_LANG['packages.easy_translator.languages.forms.create_language_form.formValidationError'] = 'Incorrect values have been detected. Please check.';

//Modal delete language
$_LANG['packages.easy_translator.languages.modals.delete_language_modal.title']        = 'Delete Translation';
$_LANG['packages.easy_translator.languages.modals.delete_language_modal.submit']       = 'Confirm';
$_LANG['packages.easy_translator.languages.modals.delete_language_modal.description']  = 'Are you sure you want to delete this translation?';
$_LANG['packages.easy_translator.languages.forms.delete_language_form.delete_success'] = 'The translation has been deleted successfully';

//Modal clone language
$_LANG['packages.easy_translator.languages.modals.clone_language_modal.title']                         = 'Clone Translation';
$_LANG['packages.easy_translator.languages.modals.clone_language_modal.submit']                        = 'Confirm';
$_LANG['packages.easy_translator.languages.forms.clone_language_form.fromLanguage']                    = 'Source Language';
$_LANG['packages.easy_translator.languages.forms.clone_language_form.toLanguage']                      = 'Target Language';
$_LANG['packages.easy_translator.languages.forms.clone_language_form.create_success']                  = 'The translation has been cloned successfully';
$_LANG['packages.easy_translator.languages.forms.clone_language_form.sourceLanguageNotFound']          = 'Source language not found.';
$_LANG['packages.easy_translator.languages.forms.clone_language_form.invalidNewLanguageName']          = 'Invalid target language name.';
$_LANG['packages.easy_translator.languages.forms.clone_language_form.formValidationError']             = 'Incorrect values have been detected. Please check.';

//Modal refresh language
$_LANG['packages.easy_translator.languages.modals.refresh_language_modal.title']                 = 'Update Translation';
$_LANG['packages.easy_translator.languages.modals.refresh_language_modal.submit']                = 'Confirm';
$_LANG['packages.easy_translator.languages.forms.refresh_language_form.missingLangs']            = 'Missing elements';
$_LANG['packages.easy_translator.languages.modals.refresh_language_modal.noMissingLangsFound']   = 'No missing elements found.';
$_LANG['packages.easy_translator.languages.modals.refresh_language_modal.missingLangsFoundInfo'] = ':missingLangsCount missing elements found for the selected language. Are you sure that you want to update the translation now?';
$_LANG['packages.easy_translator.languages.providers.refresh_language_provider.create_success']  = 'Missing entries have been added successfully';
$_LANG['packages.easy_translator.languages.modals.refresh_language_modal.close']                 = 'Close';
$_LANG['packages.easy_translator.languages.modals.refresh_language_modal.add']                   = 'Confirm';
$_LANG['packages.easy_translator.languages.forms.refresh_language_form.invalidTargetLanguage']   = 'Invalid target language name.';

//Mass Delete
$_LANG['packages.easy_translator.languages.modals.mass_delete_modal.title']                   = 'Delete Translations';
$_LANG['packages.easy_translator.languages.modals.mass_delete_modal.submit']                  = 'Confirm';
$_LANG['packages.easy_translator.languages.modals.mass_delete_modal.description']             = 'Are you sure that you want to delete the selected translations?';
$_LANG['packages.easy_translator.languages.forms.mass_delete_form.delete_success']            = 'The selected translations have been deleted successfully';

//Modal import
$_LANG['packages.easy_translator.languages.modals.import_language_modal.title']                        = 'Import Translation';
$_LANG['packages.easy_translator.languages.modals.import_language_modal.submit']                       = 'Confirm';
$_LANG['packages.easy_translator.languages.forms.import_language_form.importedLanguage']               = 'Language File';
$_LANG['packages.easy_translator.languages.forms.import_language_form.toLanguage']                     = 'Target Language';
$_LANG['packages.easy_translator.languages.forms.import_language_form.import_success']                 = 'The language file has been imported successfully';
$_LANG['packages.easy_translator.languages.forms.import_language_form.extensionParserNotFound']        = 'The extension parser not found.';
$_LANG['packages.easy_translator.languages.forms.import_language_form.invalidParserExtensionFound']    = 'Invalid parser.';
$_LANG['packages.easy_translator.languages.forms.import_language_form.invalidFileExtension']           = 'Invalid file extension uploaded.';
$_LANG['packages.easy_translator.languages.forms.import_language_form.incorrectFileUploaded']          = 'Incorrect file uploaded.';
$_LANG['packages.easy_translator.languages.forms.import_language_form.invalidTargetLanguage']          = 'Invalid target language name.';
$_LANG['packages.easy_translator.languages.forms.import_language_form.formValidationError']            = 'Incorrect values have been detected. Please check.';

//Modal export
$_LANG['packages.easy_translator.languages.modals.export_language_modal.title']                         = 'Export Translation';
$_LANG['packages.easy_translator.languages.modals.export_language_modal.submit']                        = 'Confirm';
$_LANG['packages.easy_translator.languages.forms.export_language_form.type']                            = 'Type';
$_LANG['packages.easy_translator.languages.forms.export_language_form.fromLanguage']                    = 'Source Language';
$_LANG['packages.easy_translator.languages.forms.export_language_form.outputFileName']                  = 'Output File Name';
$_LANG['packages.easy_translator.languages.forms.export_language_form.invalidSourceLanguageName']       = 'Invalid source language name';
$_LANG['packages.easy_translator.languages.forms.export_language_form.formValidationError']             = 'Incorrect values have been detected. Please check.';
$_LANG['packages.easy_translator.languages.providers.import_export_provider.langsExportedSuccessfully'] = 'The language file has been exported successfully';

//Modal Settings
$_LANG['packages.easy_translator.languages.modals.settings_modal.title'] = 'Edit Settings';
$_LANG['packages.easy_translator.languages.forms.settings_form.enableMissingTranslationsSupport'] = 'Enable Missing Translations Support';
$_LANG['packages.easy_translator.languages.forms.settings_form.enableMissingTranslationsSupport_description'] = 'If enabled, the Missing Translations Support is visible';
$_LANG['packages.easy_translator.languages.providers.settings_provider.update_success'] = 'The settings have been updated successfully';

//**********  LANGS TABLE  **********
$_LANG['admin.breadcrumbs.EasyTranslator_editLanguage']                                                  = 'Translation';
$_LANG['packages.easy_translator.static_translations.pages.langs_table.lang']                            = 'Key';
$_LANG['packages.easy_translator.static_translations.pages.langs_table.value']                           = 'Content';
$_LANG['packages.easy_translator.static_translations.pages.langs_table.updated_at']                      = 'Updated';
$_LANG['packages.easy_translator.static_translations.buttons.refresh_langs_button.title']                = 'Update';
$_LANG['packages.easy_translator.static_translations.buttons.mass_delete_button.delete']                 = 'Delete';
$_LANG['packages.easy_translator.static_translations.containers.alert_container.incompleteLanguageInfo'] = ':languageName translation is incomplete. Please update.';

//Modal edit
$_LANG['packages.easy_translator.static_translations.modals.edit_lang_modal.title']        = 'Edit Translation Element';
$_LANG['packages.easy_translator.static_translations.modals.edit_lang_modal.submit']       = 'Confirm';
$_LANG['packages.easy_translator.static_translations.forms.edit_lang_form.value']          = 'New Content';
$_LANG['packages.easy_translator.static_translations.forms.edit_lang_form.originalLang']   = 'Original Content';
$_LANG['packages.easy_translator.static_translations.forms.edit_lang_form.update_success'] = 'The translation element has been changed successfully';

//Modal delete
$_LANG['packages.easy_translator.static_translations.modals.delete_lang_modal.title']        = 'Delete Translation Element';
$_LANG['packages.easy_translator.static_translations.modals.delete_lang_modal.submit']       = 'Confirm';
$_LANG['packages.easy_translator.static_translations.modals.delete_lang_modal.description']  = 'Are you sure that you want to delete the selected element?';
$_LANG['packages.easy_translator.static_translations.forms.delete_lang_form.delete_success'] = 'Translation element has been deleted successfully';

//Modal mass delete
$_LANG['packages.easy_translator.static_translations.modals.mass_delete_modal.title']                = 'Delete Translation Elements';
$_LANG['packages.easy_translator.static_translations.modals.mass_delete_modal.submit']               = 'Confirm';
$_LANG['packages.easy_translator.static_translations.modals.mass_delete_modal.description']          = 'Are you sure that you want to delete the selected elements?';
$_LANG['packages.easy_translator.static_translations.forms.mass_delete_form.delete_success']         = 'The selected elements have been deleted successfully';
$_LANG['packages.easy_translator.static_translations.providers.delete_lang_provider.delete_success'] = 'Translation element has been deleted successfully';

//Modal refresh
$_LANG['packages.easy_translator.static_translations.modals.refresh_langs_modal.title']                 = 'Update Element';
$_LANG['packages.easy_translator.static_translations.forms.refresh_langs_form.missingLangs']            = 'Missing elements';
$_LANG['packages.easy_translator.static_translations.modals.refresh_langs_modal.missingLangsFoundInfo'] = ':missingLangsCount missing elements found. Are you sure that you want to update the translation now?';
$_LANG['packages.easy_translator.static_translations.modals.refresh_langs_modal.noMissingLangsFound']   = 'No missing elements found';
$_LANG['packages.easy_translator.static_translations.modals.refresh_langs_modal.add']                   = 'Confirm';
$_LANG['packages.easy_translator.static_translations.modals.refresh_langs_modal.close']                 = 'Close';
$_LANG['packages.easy_translator.static_translations.providers.refresh_langs_provider.create_success']  = 'Missing elements have been added successfully';

//**********  TRANSLATIONS  *******
$_LANG['packages.easy_translator.translations.tabs.static_translations_tab.staticTranslationsTabTitle']   = 'Static Translations';
$_LANG['packages.easy_translator.translations.tabs.dynamic_translations_tab.dynamicTranslationsTabTitle'] = 'Dynamic Translations';


//**********  DYNAMIC TRANSLATIONS TABLE  **********
$_LANG['packages.easy_translator.dynamic_translations.pages.dynamic_translations_page.lang']       = 'Key';
$_LANG['packages.easy_translator.dynamic_translations.pages.dynamic_translations_page.regex']      = 'Regular Expression';
$_LANG['packages.easy_translator.dynamic_translations.pages.dynamic_translations_page.updated_at'] = 'Updated';

$_LANG['packages.easy_translator.dynamic_translations.buttons.create_lang_button.title'] = 'Add Dynamic Translation';

//Modal create
$_LANG['packages.easy_translator.dynamic_translations.modals.create_lang_modal.title']           = 'Add Dynamic Translation';
$_LANG['packages.easy_translator.dynamic_translations.forms.create_lang_form.regex']             = 'Regular Expression';
$_LANG['packages.easy_translator.dynamic_translations.forms.create_lang_form.regex_description'] = '
<span>Use regular expressions (RegEx) to dynamically match system messages for translation.</span> <br> 
<h6>Examples:</h6> 
<ul style="padding-left: 15px;">
  <li><b>^Error:\s+(.*)$</b> - matches any error starting with "Error:" and captures the rest</li>
  <li><b>(container|image) .* not found</b> - matches "container nginx not found" or "image apache not found"</li>
  <li><b>deployment .* (failed|timed out)</b> - matches deployment failure messages</li>
  <li><b>Volume "(.+)" (exists|in use)</b> - matches volume-related errors</li>
  <li><b>network .* is (already|still) in use</b> - matches network usage conflicts</li>
</ul>
<h6>Tips:</h6> 
<ul style="padding-left: 15px;">
<li>Use () for grouping, | for alternation, .* for wildcards</li>
<li>Use :$group_number in translation as replacement</li>
</ul>
';
$_LANG['packages.easy_translator.dynamic_translations.forms.create_lang_form.translation']       = 'Translation (:language)';
$_LANG['packages.easy_translator.dynamic_translations.forms.create_lang_form.create_success']    = 'Translation element has been created successfully';

//Modal edit
$_LANG['packages.easy_translator.dynamic_translations.modals.edit_lang_modal.title']           = 'Edit Dynamic Translation';
$_LANG['packages.easy_translator.dynamic_translations.forms.edit_lang_form.regex']             = 'Regular Expression';
$_LANG['packages.easy_translator.dynamic_translations.forms.edit_lang_form.regex_description'] = '
<span>Use regular expressions (RegEx) to dynamically match system messages for translation.</span> <br> 
<h6>Examples:</h6> 
<ul style="padding-left: 15px;">
  <li><b>^Error:\s+(.*)$</b> - matches any error starting with "Error:" and captures the rest</li>
  <li><b>(container|image) .* not found</b> - matches "container nginx not found" or "image apache not found"</li>
  <li><b>deployment .* (failed|timed out)</b> - matches deployment failure messages</li>
  <li><b>Volume "(.+)" (exists|in use)</b> - matches volume-related errors</li>
  <li><b>network .* is (already|still) in use</b> - matches network usage conflicts</li>
</ul>
<h6>Tips:</h6> 
<ul style="padding-left: 15px;">
<li>Use () for grouping, | for alternation, .* for wildcards</li>
<li>Use :$group_number in translation as replacement</li>
</ul>
';
$_LANG['packages.easy_translator.dynamic_translations.forms.edit_lang_form.lang']              = 'Lang Key';
$_LANG['packages.easy_translator.dynamic_translations.forms.edit_lang_form.update_success']    = 'Translation element has been updated successfully';

//Modal delete
$_LANG['packages.easy_translator.dynamic_translations.modals.delete_lang_modal.title']        = 'Delete Dynamic Translation';
$_LANG['packages.easy_translator.dynamic_translations.modals.delete_lang_modal.description']  = 'Are you sure that you want to delete the selected element?';
$_LANG['packages.easy_translator.dynamic_translations.forms.delete_lang_form.delete_success'] = 'Translation element has been deleted successfully';

//Mass delete
$_LANG['packages.easy_translator.dynamic_translations.modals.mass_delete_modal.title']        = 'Delete Dynamic Translations';
$_LANG['packages.easy_translator.dynamic_translations.modals.mass_delete_modal.description']  = 'Are you sure that you want to delete the selected elements?';
$_LANG['packages.easy_translator.dynamic_translations.buttons.mass_delete_button.delete']     = 'Delete';
$_LANG['packages.easy_translator.dynamic_translations.forms.mass_delete_form.delete_success'] = 'The selected elements have been deleted successfully';

//**********  MISSING LANGS TABLE  **********
$_LANG['packages.easy_translator.missing_langs.pages.missing_langs_page.lang']        = 'Key';
$_LANG['packages.easy_translator.missing_langs.pages.missing_langs_page.language']    = 'Language';
$_LANG['packages.easy_translator.missing_langs.pages.missing_langs_page.updated_at']  = 'Updated';
$_LANG['packages.easy_translator.missing_langs.buttons.set_translation_button.title'] = 'Add Missing Element';

//Modal set translation
$_LANG['packages.easy_translator.missing_langs.modals.set_translation_modal.title']     = 'Add Missing Element';

$_LANG['packages.easy_translator.missing_langs.forms.set_translation_form.sourceText']        = 'Source Text';
$_LANG['packages.easy_translator.missing_langs.forms.set_translation_form.sourceTranslation'] = 'Source Translation (English)';
$_LANG['packages.easy_translator.missing_langs.forms.set_translation_form.lang']              = 'Key';
$_LANG['packages.easy_translator.missing_langs.forms.set_translation_form.translation']       = 'Translated Text';
$_LANG['packages.easy_translator.missing_langs.forms.set_translation_form.update_success']    = 'Translation element has been added successfully';
$_LANG['packages.easy_translator.missing_langs.forms.set_translation_form.langAlreadyExists'] = 'The selected language is already defined and will be overwritten.';