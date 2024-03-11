function placeBid(workSlotId) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../controller/place_bid.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                // Bid placed successfully, show a success message to the user
                alert(xhr.responseText); 
                window.location.reload(); 
            } else if (xhr.status == 400) {
                // User already placed a bid for this work slot, show an error message to the user
                alert(xhr.responseText); // You can customize this part to display notifications in a different way
            } else {
                // Error occurred while placing the bid, show an error message to the user
                alert("Error placing bid. Please try again later.");
            }
        }
    };
    xhr.send("workSlotId=" + workSlotId);
}
