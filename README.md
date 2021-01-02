nette-visual-paginator
======================
Vizualni paginator pro Nette 2+

Priklad pouziti na strankach puvodniho projektu:
- http://addons.nette.org/cs/visualpaginator

Zde je navic moznost pridat do tridy VisualPaginator callback:
- Bez nej je zachovana puvodni funkcionalita.
- S nim sablona jako odkaz na stranky pouziva signal (+class="ajax") a v jeho handleru vyvola callback.

Ukazka obsluhy ajaxu v komponente nebo presenteru:
```php
class ExampleControl extends \Nette\Application\UI\Control {
  
	// ...

	public function render() {
		// priklad strankovani kolekci je na webu puvodniho projektu:
		// http://addons.nette.org/cs/visualpaginator
	}
	
	/**
	* trigger pro udalost strankovani
	* @param \Nette\Application\UI\Control $component
	* @param int $page
	*/
	public function triggerShowPage($component, $page) {
		//jen invalidovat, paginator si stranky ohlida sam
		if ($this->presenter->isAjax()) {
			$this->invalidateControl();
		} else {
			$this->presenter->redirect('this');
		}
	}

	/**
	* tovarnicka na paginator
	* @param strint $name
	*/
	public function createComponentPaginator($name) {
  		$vp = new VisualPaginator($this, $name);
		$vp->onShowPage[] = callback($this, 'triggerShowPage');
		return $vp;
	}
}
```
