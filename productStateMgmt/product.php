<?PHP
session_start();

require_once $_SESSION['system']['g_root_path']."/classes/process/__product.php";
require_once $_SESSION['system']['g_root_path']."/classes/productstatus.php";

class productState
{
	private $currentState;
	private $parentObject;
	
	function __construct($state=ProductStatuses::UnPublished, $po=NULL)
	{
		$this->currentState = $state;
		require_once $_SESSION['system']['g_root_path']."/classes/productStateMgmt/".ProductStatuses::getString($this->currentState).".php";
		$i="Obj".ProductStatuses::getString($this->currentState);
		$c=ProductStatuses::getString($this->currentState);
		$this->parentObject = $po;
		$this->$i=new $c($this->parentObject);
		
	}
	
	function getCurrentState() { return $this->currentState;} 
	
	
	function onAction($action)
	{
		$i="Obj".ProductStatuses::getString($this->currentState);
		if (!$this->$i)
		{
			require_once $_SESSION['system']['g_root_path']."/classes/productStateMgmt/".ProductStatuses::getString($this->currentState).".php";
			$i="Obj".ProductStatuses::getString($this->currentState);
			$c=ProductStatuses::getString($this->currentState);
			$this->$i=new $c($this->parentObject);
		}
		$o=$this->$i;
		$this->currentState =$o->onAction($action);
		//echo "<br>On Action ".$action." => ".ProductStatuses::getString($this->currentState);
	}
	
	function getAvailableActions()
	{
		$i="Obj".ProductStatuses::getString($this->currentState);
		//echo "In get availableAction ".$i;
		
		if (!$this->$i)
		{
			require_once $_SESSION['system']['g_root_path']."/classes/productStateMgmt/".ProductStatuses::getString($this->currentState).".php";
			$i="Obj".ProductStatuses::getString($this->currentState);
			$c=ProductStatuses::getString($this->currentState);
			$this->$i=new $c($this->parentObject);
		}
		
		
		$ar= $this->$i->getVerbs();
		return $ar;
		
	}
	
	function isActionAllowed($action)
	{
		$i="Obj".ProductStatuses::getString($this->currentState);
		if (!$this->$i)
		{
			require_once $_SESSION['system']['g_root_path']."/classes/productStateMgmt/".ProductStatuses::getString($this->currentState).".php";
			$i="Obj".ProductStatuses::getString($this->currentState);
			$c=ProductStatuses::getString($this->currentState);
			$this->$i=new $c($this->getConfig(),$this->getDbHandle());
		}
		return($this->$i->isActionAllowed($action));
	}
}

/*
$o = new orderState();
$o->onAction("created");
$o->onAction("rejected");
*/
?>