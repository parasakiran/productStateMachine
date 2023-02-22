<?PHP

require_once $_SESSION['system']['g_root_path']."/classes/productStateMgmt/__abstractProductBaseState.php";
class ProductStateWaitingForAdminApproval extends __abstractProductBaseState{

        function __construct($productActionObject)
        {
                parent::__construct($productActionObject);
                $this->state = ProductStatuses::WaitingForAdminApproval;
                $this->setVerbs(Array("admin"=>Array("Approve","Reject")));
                $this->setView(Array('admin'));
        }

        function onAction($action)
        {
                switch($action)
                {
                        case "Approve":
                        {
                                return $this->onApproved();
                                break;
                        }
                        case "Reject":
                        {
                                return $this->onReject();
                                break;
                        }
                        default:
                        {
                                //log Error - invalid action
                                $this->msg(" Invalid action ".$action.". Pls get in touch with Admin");
                                return ProductStatuses::WaitingForAdminApproval;
                        }
                }
        }

        function onApproved()
        {
			require_once $this->getParentObject()->getConfig();
			$filter = json_decode($this->getParentObject()->getParams('filter'), TRUE);

			$this->getParentObject()->db_query("update product set status=".ProductStatuses::Published." where oid_index = ".$this->getParentObject()->getField("oid_index"));


			return ProductStatuses::Published;
        }
		

        function onReject()
        {
			//send necessary emails
			// OrderStatuses::WaitingForPaymentGatewayConfirmation;
			require_once $this->getParentObject()->getConfig();

			$this->getParentObject()->db_query("update product set status=".ProductStatuses::Rejected." where oid_index = ".$this->getParentObject()->getField("oid_index"));


			return ProductStatuses::Rejected;
        }		
		
		
}


?>