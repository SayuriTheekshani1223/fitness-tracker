function sendMessage(){

    let name = document.getElementById("name").value
    let email = document.getElementById("email").value
    let message = document.getElementById("message").value
    
    if(name === "" || email === "" || message === "")
    {
        alert("Please fill all fields")
        return
    }
    
    document.getElementById("successMsg").innerText =
    "Message sent successfully!"
    
    document.getElementById("name").value = ""
    document.getElementById("email").value = ""
    document.getElementById("message").value = ""
    
}