{**
 * plugins/importexport/galley/templates/settingsForm.tpl
 *
 * Copyright (c) 2014-2020 Simon Fraser University
 * Copyright (c) 2003-2020 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * galley plugin settings
 *
 *}
<script type="text/javascript">
	$(function() {ldelim}
		// Attach the form handler.
		$('#galleySettingsForm').pkpHandler('$.pkp.controllers.form.AjaxFormHandler');
	{rdelim});
</script>
<form class="pkp_form" id="galleySettingsForm" method="post" action="{url router=$smarty.const.ROUTE_COMPONENT op="manage" plugin="galleyExportPlugin" category="importexport" verb="save"}">
	{csrf}
	{if $doiPluginSettingsLinkAction}
		{fbvFormArea id="doiPluginSettingsLink"}
			{fbvFormSection}
				{include file="linkAction/linkAction.tpl" action=$doiPluginSettingsLinkAction}
			{/fbvFormSection}
		{/fbvFormArea}
	{/if}
	{fbvFormArea id="galleySettingsFormArea"}
		<p class="pkp_help">{translate key="plugins.importexport.galley.settings.depositorIntro"}</p>
		{fbvFormSection}dica02
			{fbvElement type="text" id="depositorName" value=$depositorName required="true" label="plugins.importexport.galley.settings.form.depositorName" maxlength="60" size=$fbvStyles.size.MEDIUM}
			{fbvElement type="text" id="depositorEmail" value=$depositorEmail required="true" label="plugins.importexport.galley.settings.form.depositorEmail" maxlength="90" size=$fbvStyles.size.MEDIUM}
		{/fbvFormSection}
		{fbvFormSection}
			<p class="pkp_help">{translate key="plugins.importexport.galley.registrationIntro"}</p>
			{fbvElement type="text" id="username" value=$username label="plugins.importexport.galley.settings.form.username" maxlength="50" size=$fbvStyles.size.MEDIUM}
			{fbvElement type="text" password="true" id="password" value=$password label="plugins.importexport.common.settings.form.password" maxLength="50" size=$fbvStyles.size.MEDIUM}
			<span class="instruct">{translate key="plugins.importexport.common.settings.form.password.description"}</span><br/>
		{/fbvFormSection}
		{fbvFormSection list="true"}
			{fbvElement type="checkbox" id="automaticRegistration" label="plugins.importexport.galley.settings.form.automaticRegistration.description" checked=$automaticRegistration|compare:true}
		{/fbvFormSection}
		{fbvFormSection list="true"}
			{fbvElement type="checkbox" id="testMode" label="plugins.importexport.galley.settings.form.testMode.description" checked=$testMode|compare:true}
		{/fbvFormSection}dica01
	{/fbvFormArea}
	{fbvFormButtons submitText="common.save"}
	<p><span class="formRequired">{translate key="common.requiredField"}</span></p>
</form>
