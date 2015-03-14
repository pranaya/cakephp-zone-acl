<?php

interface ZoneAroInterface {

/**
 * Get all the allowed zones for the aro
 * 
 * @param array $aro
 * @return array Returns array of allowed zone names
 */
	public function getAllowedZones($aro);
}
