<?PHP
require_once $_SESSION['system']['g_root_path']."/classes/productStateMgmt/__abstractProductBaseState.php";


class ProductStateRejected extends __abstractProductBaseState{
	function __construct($orderActionObject)
	{
			parent::__construct($orderActionObject);
			$this->state = ProductStatuses::Rejected;
			$this->setVerbs(Array("lender"=>Array("Delete")));
			$this->setView(Array("lender"));
	}

	
	function onAction($action)
	{
		switch($action)
		{
			case "Delete":
			{
				$this->delete();
				return ProductStatuses::Deleted;
			}
			default:
			{
				//Log Error
				return ProductStatuses::Rejected;
			}
		}
	}
	
	function requestForApproval()
	{
		//send necessary emails
		// OrderStatuses::WaitingForPaymentGatewayConfirmation;
		require_once $this->getParentObject()->getConfig();

		$this->getParentObject()->db_query("update product set status=".ProductStatuses::Deleted." where oid_index = ".$this->getParentObject()->getField("oid_index"));


		return ProductStatuses::Deleted;		
	}
}


?>