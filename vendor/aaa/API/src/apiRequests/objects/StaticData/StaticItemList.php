<?php

namespace LeagueAPI\Objects\StaticData;
use LeagueAPI\Objects\objectInit;

class StaticItemList extends objectInit
{
	/** @var StaticItem[] $data */
	public $data;
	/** @var string $version */
	public $version;
	/** @var StaticItemTree[] $tree */
	public $tree;
	/** @var StaticItemGroup[] $groups */
	public $groups;
	/** @var string $type */
	public $type;
}