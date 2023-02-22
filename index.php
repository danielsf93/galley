<?php

/**
 * @defgroup plugins_importexport_galley galley Plugin
 */
 
/**
 * @file plugins/importexport/galley/index.php
 *
 * Copyright (c) 2014-2020 Simon Fraser University
 * Copyright (c) 2003-2020 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @ingroup plugins_importexport_galley
 * @brief Wrapper for galley export plugin.
 *
 */

require_once('galleyExportPlugin.inc.php');

return new galleyExportPlugin();


