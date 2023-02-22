<?php
/**
 * @defgroup plugins_importexport_galley galley export plugin
 */

/**
 * @file plugins/importexport/galley/galleyExportDeployment.inc.php
 *
 * Copyright (c) 2014-2020 Simon Fraser University
 * Copyright (c) 2000-2020 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class galleyExportDeployment
 * @ingroup plugins_importexport_galley
 *
 * @brief Base class configuring the galley export process to an
 * application's specifics.
 */

// XML attributes
define('galley_XMLNS' , 'http://www.crossref.org/schema/4.3.6');
define('galley_XMLNS_XSI' , 'http://www.w3.org/2001/XMLSchema-instance');
define('galley_XSI_SCHEMAVERSION' , '4.3.6');
define('galley_XSI_SCHEMALOCATION' , 'https://www.crossref.org/schemas/crossref4.3.6.xsd');
define('galley_XMLNS_JATS' , 'http://www.ncbi.nlm.nih.gov/JATS1');
define('galley_XMLNS_AI' , 'http://www.galley.org/AccessIndicators.xsd');

class galleyExportDeployment {
	/** @var Context The current import/export context */
	var $_context;

	/** @var Plugin The current import/export plugin */
	var $_plugin;

	/** @var Issue */
	var $_issue;

	function getCache() {
		return $this->_plugin->getCache();
	}

	/**
	 * Constructor
	 * @param $context Context
	 * @param $plugin DOIPubIdExportPlugin
	 */
	function __construct($context, $plugin) {
		$this->setContext($context);
		$this->setPlugin($plugin);
	}

	//
	// Deployment items for subclasses to override
	//
	/**
	 * Get the root lement name
	 * @return string
	 */
	function getRootElementName() {
		return 'doi_batch';
	}

	/**
	 * Get the namespace URN
	 * @return string
	 */
	function getNamespace() {
		return galley_XMLNS;
	}

	/**
	 * Get the schema instance URN
	 * @return string
	 */
	function getXmlSchemaInstance() {
		return galley_XMLNS_XSI;
	}

	/**
	 * Get the schema version
	 * @return string
	 */
	function getXmlSchemaVersion() {
		return galley_XSI_SCHEMAVERSION;
	}

	/**
	 * Get the schema location URL
	 * @return string
	 */
	function getXmlSchemaLocation() {
		return galley_XSI_SCHEMALOCATION;
	}

	/**
	 * Get the JATS namespace URN
	 * @return string
	 */
	function getJATSNamespace() {
		return galley_XMLNS_JATS;
	}

	/**
	 * Get the access indicators namespace URN
	 * @return string
	 */
	function getAINamespace() {
		return galley_XMLNS_AI;
	}

	/**
	 * Get the schema filename.
	 * @return string
	 */
	function getSchemaFilename() {
		return $this->getXmlSchemaLocation();
	}

	//
	// Getter/setters
	//
	/**
	 * Set the import/export context.
	 * @param $context Context
	 */
	function setContext($context) {
		$this->_context = $context;
	}

	/**
	 * Get the import/export context.
	 * @return Context
	 */
	function getContext() {
		return $this->_context;
	}

	/**
	 * Set the import/export plugin.
	 * @param $plugin ImportExportPlugin
	 */
	function setPlugin($plugin) {
		$this->_plugin = $plugin;
	}

	/**
	 * Get the import/export plugin.
	 * @return ImportExportPlugin
	 */
	function getPlugin() {
		return $this->_plugin;
	}

	/**
	 * Set the import/export issue.
	 * @param $issue Issue
	 */
	function setIssue($issue) {
		$this->_issue = $issue;
	}

	/**
	 * Get the import/export issue.
	 * @return Issue
	 */
	function getIssue() {
		return $this->_issue;
	}

}


