{**
 * plugins/importexport/galley/templates/settingsForm.tpl
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
	
	
	<p><h1>Plugin Galley DOI</h1></p>
	<p><h3>Este plugin é baseado no plugin "Exportação CrossRef em XML". Configure suas informações da mesma forma
	que o plugin "Exportação CrossRef em XML". Na aba Artigos é possível exportar o xml de arquivos. Por enquanto
	selecione somente artigos que possuem dois PDF's para baixar o xml. Atenção, baixe um xml por vez!!!</h3></p>
	
		<p class="pkp_help">{translate key="plugins.importexport.galley.settings.depositorIntro"}</p>
			{fbvFormSection}
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
		{/fbvFormSection}
	{/fbvFormArea}
	{fbvFormButtons submitText="common.save"}
	<p><span class="formRequired">{translate key="common.requiredField"}</span></p>
</form>
