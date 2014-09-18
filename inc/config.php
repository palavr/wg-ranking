<?php

    // these two constants are used to create root-relative web addresses
    // and absolute server paths throughout all the code

	define("BASE_URL","/");
	define("ROOT_PATH",$_SERVER["DOCUMENT_ROOT"] . "/");

	// database related constants
	define("DB_HOST","localhost");
	define("DB_NAME","wg_ranking");
	define("DB_PORT","8889"); 
	define("DB_USER","root");
	define("DB_PASS","root");