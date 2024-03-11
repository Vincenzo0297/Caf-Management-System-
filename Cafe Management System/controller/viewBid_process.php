<?php
require_once('../classes/Bid.php');

class viewBid_process
{
    private $bids;

    public function __construct($conn)
    {
        $this->bids = new Bid($conn);
    }

    public function getBidsForToManage()
    {
        return $this->bids->getBidsToManage();
    }
	
	public function getAllBids($staffId) {
        return $this->bids->getBids($staffId);
    }


    // Add other methods for managing bids here
}
?>
