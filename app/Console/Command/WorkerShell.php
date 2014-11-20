<?php

/**
 * Worker Shell – HC API
 */

class WorkerShell extends AppShell {

	public $uses = array('TwitterList');

	/*Main worker*/
	public function main() {
		$this->TwitterList->updateDefaultImages();
	}

}