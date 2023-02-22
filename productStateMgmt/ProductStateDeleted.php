<?PHP
require_once $_SESSION['system']['g_root_path']."/classes/productStateMgmt/__abstractProductBaseState.php";


class ProductStateDeleted extends __abstractProductBaseState{
	function __construct($orderActionObject)
	{
			parent::__construct($orderActionObject);
			$this->state = ProductStatuses::Deleted;
			$this->setVerbs(Array());
	}

	
	function onAction($action)
	{
		switch($action)
		{
			default:
			{
				//Log Error
				return ProductStatuses::Deleted;
			}
		}
	}
	
}


?>