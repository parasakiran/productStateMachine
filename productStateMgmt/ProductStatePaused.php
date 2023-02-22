<?PHP
require_once $_SESSION['system']['g_root_path']."/classes/productStateMgmt/__abstractProductBaseState.php";


class ProductStateUnPublished extends __abstractProductBaseState{
	function __construct($orderActionObject)
	{
			parent::__construct($orderActionObject);
			$this->state = ProductStatuses::UnPublished;
			$this->setVerbs(Array("RequestForApproval"));
			$this->setView('lender');
	}

	
	function onAction($action)
	{
		switch($action)
		{
			case "RequestForApproval":
			{
				$this->requestForApproval();
				return ProductStatuses::WaitingForAdminApproval;
			}
			default:
			{
				//Log Error
				return ProductStatuses::UnPublished;
			}
		}
	}
	
	function requestForApproval()
	{
		//send necessary emails
		// OrderStatuses::WaitingForPaymentGatewayConfirmation;
		require_once $this->getParentObject()->getConfig();
		global $g_order_serviceFeeLender;
		global $g_taxPercentage;
		$filter = json_decode($this->getParentObject()->getParams('filter'), TRUE);

		$this->getParentObject()->db_query("update product set status=".ProductStatuses::WaitingForAdminApproval." where oid_index = ".$this->getParentObject()->getField("oid_index"));

		//TODO
		$this->msg("Item Ready to be dispatched from Borrower");

		return ProductStatuses::Completed;		
	}
}


?>