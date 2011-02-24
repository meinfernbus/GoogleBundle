<?php

namespace AntiMattr\GoogleBundle\Maps;

interface MapInterface
{
	public function setId($id);
	public function getId();
	public function hasMarkers();
	public function hasMarker(Marker $marker);
	public function setMarkers($markers);
	public function getMarkers();
	public function addMarker(Marker $marker);
	public function removeMarker(Marker $marker);
	public function hasMeta();
	public function setMeta(array $meta);
	public function getMeta();
	public function render();
}
