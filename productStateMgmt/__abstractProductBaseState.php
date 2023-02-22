<?PHP
require_once $_SESSION['system']['g_root_path']."/classes/process/__product.php";


require_once $_SESSION['system']['g_root_path']."/classes/productstatus.php";

abstract class __abstractProductBaseState
{
	private $view;
	private $verbs;
	private $config;
	private $link;
	private $parentObject;
	function __construct($parentObject)
	{
		$this->verbs = Array();
		$this->view = "";
		$this->parentObject = $parentObject;
		
		$ex = new Exception();
		$trace=$ex->getTrace();
		if (count($trace) > 10)
			throw new Exception("StateMgmt loop detected");
	}
	
	
	abstract function onAction($action);
	function onAccept(){ echo "Invalid State"; return ProductStatuses::NEWW;}
	function onReject(){ echo "Invalid State"; return ProductStatuses::NEWW;}
	function onFailure(){ echo "Invalid State"; return ProductStatuses::NEWW;}
	function onSuccess(){ echo "Invalid State"; return ProductStatuses::NEWW;}
	function getView(){return $this->view;}
	function setView($view) {$this->view = $view;}
	function setVerbs($list){$this->verbs=$list;}
	function getVerbs() {

		if (!isset($_SESSION["user_logged"]))
			return [];

		if (isset($_SESSION["user_details"]) && ($_SESSION["user_details"]["type"]!=$this->view))
			return [];
	
		return $this->verbs; 
	}
	
	function getParentObject()
	{
		return $this->parentObject;
	}
	
	function msg($msg)
	{
		$this->parentObject->systemMsg($this->parentObject->getField("buyerId"),$this->parentObject->user_cache_get("id"),$this->parentObject->getField("productId"),$this->parentObject->getField("oid_index"),$msg);
	}
	
	function isActionAllowed($action){

		if (!isset($_SESSION["user_logged"]))
			return 0;

		if (isset($_SESSION["user_details"]) && ($_SESSION["user_details"]["type"]!=$this->view))
			return 0;

	
		return in_array($action,$this->verbs);
	}
}
?>