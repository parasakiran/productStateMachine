<?PHP
require_once $_SESSION['system']['g_root_path']."/classes/productStateMgmt/__abstractProductBaseState.php";


class ProductStatePublished extends __abstractProductBaseState{
	function __construct($orderActionObject)
	{
			parent::__construct($orderActionObject);
			$this->state = ProductStatuses::Published;
			$this->setVerbs(Array("admin"=>Array("Revoke"),"lender"=>Array("Withdraw")));
			$this->setView(Array('admin','lender'));
	}

	
	function onAction($action)
	{
		switch ($this->getTyp())
		{
			case 'admin':		
				switch($action)
				{
						case "Revoke":
						{
								return $this->onRevoke();
								break;
						}
						default:
						{
								//log Error - invalid action
								$this->msg(" Invalid action ".$action.". Pls get in touch with Admin");
								return ProductStatuses::Published;
						}
				}
			case 'lender':
				switch($action)
				{
					case 'Withdraw':
					{
							return $this->onWithdraw();
							break;						
					}
					default:
					{
						//log Error - invalid action
						$this->msg(" Invalid action ".$action.". Pls get in touch with Admin");
						return ProductStatuses::Published;						
					}
				}
			default:
			{
				//log Error - invalid action
				$this->msg(" Invalid user action ".$action.". Pls get in touch with Admin");
				return ProductStatuses::Published;			
			}
		}
	}
	
	function onRevoke()
	{
		require_once $this->getParentObject()->getConfig();
		$filter = json_decode($this->getParentObject()->getParams('filter'), TRUE);

		$this->getParentObject()->db_query("update product set status=".ProductStatuses::WaitingForAdminApproval." where oid_index = ".$this->getParentObject()->getField("oid_index"));

		return ProductStatuses::WaitingForAdminApproval;
	}
	
	
	function onWithdraw()
	{
		require_once $this->getParentObject()->getConfig();
		$filter = json_decode($this->getParentObject()->getParams('filter'), TRUE);

		$this->getParentObject()->db_query("update product set status=".ProductStatuses::UnPublished." where oid_index = ".$this->getParentObject()->getField("oid_index"));

		return ProductStatuses::UnPublished;
	}	

}


?>