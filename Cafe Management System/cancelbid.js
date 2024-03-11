function cancelBid(bidId) {
    var confirmation = confirm("Are you sure you want to cancel this bid?");
    if (confirmation) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../controller/cancel_bid.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    // Handle the response from the server, for example, reload the page
                    window.location.reload();
                } else {
                    // Handle errors, display a message to the user
                    console.error("Error cancelling bid");
                }
            }
        };
        xhr.send("bidId=" + bidId);
    }
}