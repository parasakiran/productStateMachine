<?PHP
require_once $_SESSION['system']['g_root_path']."/classes/process/__product.php";


require_once $_SESSION['system']['g_root_path']."/classes/productstatus.php";

class productAction extends __product
{
	private $pState;
	function __construct($config,$link)
	{
		parent::__construct($config, $link);
		$this->pState=ProductStatuses::UnPublished;
	}
	
/* DO NOT CHANGE THIS FILE - STRICT */
	function action($action)
	{
		require_once $_SESSION['system']['g_root_path']."/classes/productStateMgmt/product.php";
		


		$this->pState = new productState($this->getField("status"), $this);
		if (!$this->pState->isActionAllowed($action))
		{
			return;
		}
		
		$this->pState->onAction($action);
		//return $this->$action();
	}
	
	function withdraw()
	{
		
	}
/* DO NOT CHANGE THIS FILE - STRICT */
	
	
}

?>