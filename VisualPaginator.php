<?php
declare(strict_types=1);

namespace Unio;

use Nette\Application\UI\Control;
use Nette\Utils\Paginator;

/**
 * Visual paginator control.
 *
 * @author     David Grudl
 * @copyright  Copyright (c) 2009 David Grudl
 * @package    Nette Extras
 * @property array onShowPage Pokud je nastaven nejaky handler, pouzije se ajax. Jinak natvrdo link na 'this'.
 */
class VisualPaginator extends Control
{

	/** @persistent */
	public $page = 1;
	/** @var array */
	public $onShowPage;
	/** @var Paginator */
	private $paginator;

	public function handleShowPage($page): void
	{
		// vyvolat události
		$this->onShowPage($this, $page);
	}

	/**
	 * Renders paginator.
	 */
	public function render(): void
	{
		$paginator = $this->getPaginator();
		$page = $paginator->page;
		if ($paginator->pageCount < 2) {
			$steps = array($page);
		} else {
			$arr = range(max($paginator->firstPage, $page - 3), min($paginator->lastPage, $page + 3));
			$count = 4;
			$quotient = ($paginator->pageCount - 1) / $count;
			for ($i = 0; $i <= $count; $i++) {
				$arr[] = round($quotient * $i) + $paginator->firstPage;
			}
			sort($arr);
			$steps = array_values(array_unique($arr));
		}

		$this->template->steps = $steps;
		$this->template->paginator = $paginator;
		$this->template->setFile(dirname(__FILE__) . '/template.latte');
		$this->template->ajax = !empty($this->onShowPage);
		$this->template->render();
	}

	public function getPaginator(): Paginator
	{
		if (!$this->paginator) {
			$this->paginator = new Paginator;
		}
		return $this->paginator;
	}

	/**
	 * Loads state informations.
	 */
	public function loadState(array $params): void
	{
		parent::loadState($params);
		$this->getPaginator()->page = $this->page;
	}

}
