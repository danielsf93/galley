<?php

/**
 * @file plugins/importexport/crossref/filter/IssueCrossrefXmlFilter.inc.php
 *
 * Copyright (c) 2014-2020 Simon Fraser University
 * Copyright (c) 2000-2020 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class IssueCrossrefXmlFilter
 * @ingroup plugins_importexport_crossref
 *
 * @brief Class that converts an Issue to a Crossref XML document.
 */

import('lib.pkp.plugins.importexport.native.filter.NativeExportFilter');

class IssuegalleyXmlFilter extends NativeExportFilter {
	/**
	 * Constructor
	 * @param $filterGroup FilterGroup
	 */
	function __construct($filterGroup) {
		$this->setDisplayName('galley XML issue export');
		parent::__construct($filterGroup);
	}

	//
	// Implement template methods from PersistableFilter
	//
	/**
	 * @copydoc PersistableFilter::getClassName()
	 */
	function getClassName() {
		return 'plugins.importexport.galley.filter.IssuegalleyXmlFilter';
	}

	//
	// Implement template methods from Filter
	//
	/**
	 * @see Filter::process()
	 * @param $pubObjects array Array of Issues or Submissions
	 * @return DOMDocument
	 */
	function &process(&$pubObjects) {
		// Create the XML document
		$doc = new DOMDocument('1.0', 'utf-8');
		$doc->preserveWhiteSpace = false;
		$doc->formatOutput = true;
		$deployment = $this->getDeployment();
		$context = $deployment->getContext();

		// Create the root node
		$rootNode = $this->createRootNode($doc);
		$doc->appendChild($rootNode);

		// Create and appet the 'head' node and all parts inside it
		$rootNode->appendChild($this->createHeadNode($doc));

		// Create and append the 'body' node, that contains everything
		$bodyNode = $doc->createElementNS($deployment->getNamespace(), 'body');
		$rootNode->appendChild($bodyNode);

		foreach($pubObjects as $pubObject) {
			// pubObject is either Issue or Submission
			$journalNode = $this->createJournalNode($doc, $pubObject);
			$bodyNode->appendChild($journalNode);
			$journalNode55 = $this->createJournalNode55($doc, $pubObject);
			$bodyNode->appendChild($journalNode55);
		}
		return $doc;
	}


	function &process55(&$pubObjects) {
		// Create the XML document
		$doc = new DOMDocument('1.0', 'utf-8');
		$doc->preserveWhiteSpace = false;
		$doc->formatOutput = true;
		$deployment = $this->getDeployment();
		$context = $deployment->getContext();

		// Create the root node
		$rootNode = $this->createRootNode($doc);
		$doc->appendChild($rootNode);

		// Create and appet the 'head' node and all parts inside it
		$rootNode->appendChild($this->createHeadNode($doc));

		// Create and append the 'body' node, that contains everything
		$bodyNode = $doc->createElementNS($deployment->getNamespace(), 'body');
		$rootNode->appendChild($bodyNode);

		foreach($pubObjects as $pubObject) {
			// pubObject is either Issue or Submission
			$journalNode55 = $this->createJournalNode55($doc, $pubObject);
			$bodyNode->appendChild($journalNode);
		}
		return $doc;
	}


	//
	// Issue conversion functions
	//
	/**
	 * Create and return the root node 'doi_batch'.
	 * @param $doc DOMDocument
	 * @return DOMElement
	 */
	function createRootNode($doc) {
		$deployment = $this->getDeployment();
		$rootNode = $doc->createElementNS($deployment->getNamespace(), $deployment->getRootElementName());
		$rootNode->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xsi', $deployment->getXmlSchemaInstance());
		$rootNode->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:jats', $deployment->getJATSNamespace());
		$rootNode->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ai', $deployment->getAINamespace());
		$rootNode->setAttribute('version', $deployment->getXmlSchemaVersion());
		$rootNode->setAttribute('xsi:schemaLocation', $deployment->getNamespace() . ' ' . $deployment->getSchemaFilename());
		return $rootNode;
	}

	/**
	 * Create and return the head node 'head'.
	 * @param $doc DOMDocument
	 * @return DOMElement
	 */
	function createHeadNode($doc) {
		$deployment = $this->getDeployment();
		$context = $deployment->getContext();
		$plugin = $deployment->getPlugin();
		$headNode = $doc->createElementNS($deployment->getNamespace(), 'head');
		$headNode->appendChild($node = $doc->createElementNS($deployment->getNamespace(), 'doi_batch_id', htmlspecialchars($context->getData('initials', $context->getPrimaryLocale()) . '_' . time(), ENT_COMPAT, 'UTF-8')));
		$headNode->appendChild($node = $doc->createElementNS($deployment->getNamespace(), 'timestamp', time()));
		$depositorNode = $doc->createElementNS($deployment->getNamespace(), 'depositor');
		$depositorName = $plugin->getSetting($context->getId(), 'depositorName');
		if (empty($depositorName)) {
			$depositorName = $context->getData('supportName');
		}
		$depositorEmail = $plugin->getSetting($context->getId(), 'depositorEmail');
		if (empty($depositorEmail)) {
			$depositorEmail = $context->getData('supportEmail');
		}
		$depositorNode->appendChild($node = $doc->createElementNS($deployment->getNamespace(), 'depositor_name', htmlspecialchars($depositorName, ENT_COMPAT, 'UTF-8')));
		$depositorNode->appendChild($node = $doc->createElementNS($deployment->getNamespace(), 'email_address', htmlspecialchars($depositorEmail, ENT_COMPAT, 'UTF-8')));
		$headNode->appendChild($depositorNode);
		$publisherInstitution = $context->getData('publisherInstitution');
		$headNode->appendChild($node = $doc->createElementNS($deployment->getNamespace(), 'registrant', htmlspecialchars($publisherInstitution, ENT_COMPAT, 'UTF-8')));
		return $headNode;
	}

	/**
	 * Create and return the journal node 'journal'.
	 * @param $doc DOMDocument
	 * @param $pubObject object Issue or Submission
	 * @return DOMElement
	 */
	function createJournalNode($doc, $pubObject) {
		$deployment = $this->getDeployment();
		$journalNode = $doc->createElementNS($deployment->getNamespace(), 'journal');
		$journalNode->appendChild($this->createJournalMetadataNode($doc));
		$journalNode->appendChild($this->createJournalIssueNode($doc, $pubObject));
		return $journalNode;
	}

	/**
	 * Create and return the journal metadata node 'journal_metadata'.
	 * @param $doc DOMDocument
	 * @return DOMElement
	 */
	function createJournalMetadataNode($doc) {
		$deployment = $this->getDeployment();
		$context = $deployment->getContext();

		$journalMetadataNode = $doc->createElementNS($deployment->getNamespace(), 'journal_metadata');
		// Full title
		$journalTitle = $context->getName($context->getPrimaryLocale());
		// Attempt a fall back, in case the localized name is not set.
		if ($journalTitle == '') {
			$journalTitle = $context->getData('abbreviation', $context->getPrimaryLocale());
		}
		$journalMetadataNode->appendChild($node = $doc->createElementNS($deployment->getNamespace(), 'full_title', htmlspecialchars($journalTitle, ENT_COMPAT, 'UTF-8')));
		/* Abbreviated title - defaulting to initials if no abbreviation found */
		$journalAbbrev = $context->getData('abbreviation', $context->getPrimaryLocale());
		if ( $journalAbbrev == '' ) {
			$journalAbbrev = $context->getData('acronym', $context->getPrimaryLocale());
		}
		$journalMetadataNode->appendChild($node = $doc->createElementNS($deployment->getNamespace(), 'abbrev_title', htmlspecialchars($journalAbbrev, ENT_COMPAT, 'UTF-8')));
		/* Both ISSNs are permitted for CrossRef, so sending whichever one (or both) */
		if ($ISSN = $context->getData('onlineIssn') ) {
			$journalMetadataNode->appendChild($node = $doc->createElementNS($deployment->getNamespace(), 'issn', $ISSN));
			$node->setAttribute('media_type', 'electronic');
		}
		/* Both ISSNs are permitted for CrossRef so sending whichever one (or both) */
		if ($ISSN = $context->getData('printIssn') ) {
			$journalMetadataNode->appendChild($node = $doc->createElementNS($deployment->getNamespace(), 'issn', $ISSN));
			$node->setAttribute('media_type', 'print');
		}
		return $journalMetadataNode;
	}










	/**
	 * Create and return the journal issue node 'journal_issue'.
	 * @param $doc DOMDocument
	 * @param $issue Issue
	 * @return DOMElement
	 */
	function createJournalIssueNode($doc, $issue) {
		$deployment = $this->getDeployment();
		$context = $deployment->getContext();
		$deployment->setIssue($issue);

		$journalIssueNode = $doc->createElementNS($deployment->getNamespace(), 'journal_issue');
		if ($issue->getDatePublished()) {
			$journalIssueNode->appendChild($this->createPublicationDateNode($doc, $issue->getDatePublished()));
		}
		if ($issue->getVolume() && $issue->getShowVolume()){
			$journalVolumeNode = $doc->createElementNS($deployment->getNamespace(), 'journal_volume');
			$journalVolumeNode->appendChild($node = $doc->createElementNS($deployment->getNamespace(), 'volume', htmlspecialchars($issue->getVolume(), ENT_COMPAT, 'UTF-8')));
			$journalIssueNode->appendChild($journalVolumeNode);
		}
		if ($issue->getNumber() && $issue->getShowNumber()) {
			$journalIssueNode->appendChild($node = $doc->createElementNS($deployment->getNamespace(), 'issue', htmlspecialchars($issue->getNumber(), ENT_COMPAT, 'UTF-8')));
		}
		if ($issue->getDatePublished() && $issue->getStoredPubId('doi')) {
			$request = Application::get()->getRequest();
			$journalIssueNode->appendChild($this->createDOIDataNode($doc, $issue->getStoredPubId('doi'), $request->url($context->getPath(), 'issue', 'view', $issue->getBestIssueId($context), null, null, true)));
		}
		return $journalIssueNode;
	}
	
	/*
	 * 
	 * 
	 * 
	 * minhas mudanças
	 * 
	 * 
	 * 
	 * /
		/**
	 * Create and return the journal node 'journal'.
	 * @param $doc DOMDocument
	 * @param $pubObject object Issue or Submission
	 * @return DOMElement
	 */
	function createJournalNode55($doc, $pubObject) {
		$deployment = $this->getDeployment();
		$journalNode55 = $doc->createElementNS($deployment->getNamespace(), 'journal55');
		$journalNode55->appendChild($this->createJournalMetadataNode22($doc));
		$journalNode55->appendChild($this->createJournalIssueNode33($doc, $pubObject));
		return $journalNode55;
	}

	
	
	/**
	 * Create and return the journal metadata node 'journal_metadata'.
	 * @param $doc DOMDocument
	 * @return DOMElement
	 */
	function createJournalMetadataNode22($doc) {
		$deployment = $this->getDeployment();
		$context = $deployment->getContext();

		$journalMetadataNode22 = $doc->createElementNS($deployment->getNamespace(), 'journal_metadata22');
		// Full title
		$journalTitle = $context->getName($context->getPrimaryLocale());
		// Attempt a fall back, in case the localized name is not set.
		if ($journalTitle == '') {
			$journalTitle = $context->getData('abbreviation', $context->getPrimaryLocale());
		}
		$journalMetadataNode22->appendChild($node = $doc->createElementNS($deployment->getNamespace(), 'full_title', htmlspecialchars($journalTitle, ENT_COMPAT, 'UTF-8')));
		/* Abbreviated title - defaulting to initials if no abbreviation found */
		$journalAbbrev = $context->getData('abbreviation', $context->getPrimaryLocale());
		if ( $journalAbbrev == '' ) {
			$journalAbbrev = $context->getData('acronym', $context->getPrimaryLocale());
		}
		$journalMetadataNode22->appendChild($node = $doc->createElementNS($deployment->getNamespace(), 'abbrev_title', htmlspecialchars($journalAbbrev, ENT_COMPAT, 'UTF-8')));
		/* Both ISSNs are permitted for CrossRef, so sending whichever one (or both) */
		if ($ISSN = $context->getData('onlineIssn') ) {
			$journalMetadataNode22->appendChild($node = $doc->createElementNS($deployment->getNamespace(), 'issn', $ISSN));
			$node->setAttribute('media_type', 'electronic');
		}
		/* Both ISSNs are permitted for CrossRef so sending whichever one (or both) */
		if ($ISSN = $context->getData('printIssn') ) {
			$journalMetadataNode22->appendChild($node = $doc->createElementNS($deployment->getNamespace(), 'issn', $ISSN));
			$node->setAttribute('media_type', 'print');
		}
		return $journalMetadataNode22;
	}


	
	
	
	/**
	 * Create and return the journal issue node 'journal_issue'.
	 * @param $doc DOMDocument
	 * @param $issue Issue
	 * @return DOMElement
	 */
	function createJournalIssueNode33($doc, $issue) {
		$deployment = $this->getDeployment();
		$context = $deployment->getContext();
		$deployment->setIssue($issue);

		$journalIssueNode33 = $doc->createElementNS($deployment->getNamespace(), 'journal_issue33');
		if ($issue->getDatePublished()) {
			$journalIssueNode33->appendChild($this->createPublicationDateNode($doc, $issue->getDatePublished()));
		}
		if ($issue->getVolume() && $issue->getShowVolume()){
			$journalVolumeNode = $doc->createElementNS($deployment->getNamespace(), 'journal_volume33');
			$journalVolumeNode->appendChild($node = $doc->createElementNS($deployment->getNamespace(), 'volume33', htmlspecialchars($issue->getVolume(), ENT_COMPAT, 'UTF-8')));
			$journalIssueNode33->appendChild($journalVolumeNode);
		}
		if ($issue->getNumber() && $issue->getShowNumber()) {
			$journalIssueNode33->appendChild($node = $doc->createElementNS($deployment->getNamespace(), 'issue33', htmlspecialchars($issue->getNumber(), ENT_COMPAT, 'UTF-8')));
		}
		if ($issue->getDatePublished() && $issue->getStoredPubId('doi')) {
			$request = Application::get()->getRequest();
			$journalIssueNode33->appendChild($this->createDOIDataNode($doc, $issue->getStoredPubId('doi'), $request->url($context->getPath(), 'issue33', 'view33', $issue->getBestIssueId($context), null, null, true)));
		}
		return $journalIssueNode33;
	}

	

	/**
	 * Create and return the publication date node 'publication_date'.
	 * @param $doc DOMDocument
	 * @param $objectPublicationDate string
	 * @return DOMElement
	 */
	function createPublicationDateNode($doc, $objectPublicationDate) {
		$deployment = $this->getDeployment();
		$publicationDate = strtotime($objectPublicationDate);
		$publicationDateNode = $doc->createElementNS($deployment->getNamespace(), 'publication_date');
		$publicationDateNode->setAttribute('media_type', 'online');
		if (date('m', $publicationDate)) {
			$publicationDateNode->appendChild($node = $doc->createElementNS($deployment->getNamespace(), 'month', date('m', $publicationDate)));
		}
		if (date('d', $publicationDate)) {
			$publicationDateNode->appendChild($node = $doc->createElementNS($deployment->getNamespace(), 'day', date('d', $publicationDate)));
		}
		$publicationDateNode->appendChild($node = $doc->createElementNS($deployment->getNamespace(), 'year', date('Y', $publicationDate)));
		return $publicationDateNode;
	}

	/**
	 * Create and return the DOI date node 'doi_data'.
	 * @param $doc DOMDocument
	 * @param $doi string
	 * @param $url string
	 * @return DOMElement
	 */
	function createDOIDataNode($doc, $doi, $url) {
		$deployment = $this->getDeployment();
		$doiDataNode = $doc->createElementNS($deployment->getNamespace(), 'doi_data');
		$doiDataNode->appendChild($node = $doc->createElementNS($deployment->getNamespace(), 'doi', htmlspecialchars($doi, ENT_COMPAT, 'UTF-8')));
		$doiDataNode->appendChild($node = $doc->createElementNS($deployment->getNamespace(), 'resource', $url));
		return $doiDataNode;
	}

}


