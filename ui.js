window.onload = () => {

    // Initialize dashboard controls
    if (document.title == "Dashboard") {        
        let addUserButton = document.getElementById("add-user");
        let addIssueButton = document.getElementById("issue");
        let homeButton = document.getElementById("home");
    
        homeButton.addEventListener("click", (event)=>{
            event.preventDefault();
            let url = "control.php";
            let xhr = new XMLHttpRequest();
            xhr.onload = function() {
                if (xhr.status == 200) {
                    document.getElementById("content").innerHTML = xhr.responseText;
                } else {
                    document.getElementById("content").innerHTML = "could not load page";
                }
              };
            xhr.open("POST", url);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.send("request=gohome");
        });
    
        addIssueButton.addEventListener("click", (event)=>{
            event.preventDefault();
            let url = "control.php";
            let xhr = new XMLHttpRequest();
            xhr.onload = function() {
                if (xhr.status == 200) {
                    document.getElementById("content").innerHTML = xhr.responseText;
                } else {
                    document.getElementById("content").innerHTML = "could not load page";
                }
              };
            xhr.open("POST", url);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.send("request=newissueform");
        });
    
        addUserButton.addEventListener("click", (event)=>{
            event.preventDefault();
            let url = "control.php";
            let xhr = new XMLHttpRequest();
            xhr.onload = function() {
                if (xhr.status == 200) {
                    document.getElementById("content").innerHTML = xhr.responseText;
                } else {
                    document.getElementById("content").innerHTML = "could not load page";
                }
              };
            xhr.open("POST", url);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.send("request=newuserform");
        });
    }

};

// Shows all tickets
function filterAll(event) {
    event.preventDefault();
    let url = "control.php";
    let xhr = new XMLHttpRequest();
    xhr.onload = function() {
        if (xhr.status == 200) {
            document.getElementById("data").innerHTML = xhr.responseText;
        } else {
            document.getElementById("data").innerHTML = "could not load page";
        }
    };
    xhr.open("POST", url);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.send('send_all=true');    
}

// Shows all open tickets
function filterOpen(event) {
    event.preventDefault();
    var allFilterButton = document.querySelector("#all-filter");
    allFilterButton.classList.remove("default-filter");
    let url = "control.php";
    let xhr = new XMLHttpRequest();
    xhr.onload = function() {
        if (xhr.status == 200) {
            document.getElementById("data").innerHTML = xhr.responseText;
        } else {
            document.getElementById("data").innerHTML = "could not load page";
        }
    };
    xhr.open("POST", url);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.send('send_open=true');    
}

// Shows all tickets assigned to the logged in user
function filterMine(event) {
    event.preventDefault();
    var allFilterButton = document.querySelector("#all-filter");
    allFilterButton.classList.remove("default-filter");
    let url = "control.php";
    let xhr = new XMLHttpRequest();
    xhr.onload = function() {
        if (xhr.status == 200) {
            document.getElementById("data").innerHTML = xhr.responseText;
        } else {
            document.getElementById("data").innerHTML = "could not load page";
        }
    };
    xhr.open("POST", url);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.send('send_myisssues=true');    
}

// Loads new issue creation form
function createIssue(event) {
    event.preventDefault();
    let url = "control.php";
    let xhr = new XMLHttpRequest();
    xhr.onload = function() {
        if (xhr.status == 200) {
            document.getElementById("content").innerHTML = xhr.responseText;
        } else {
            document.getElementById("content").innerHTML = "could not load page";
        }
        };
    xhr.open("POST", url);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.send("request=newissueform");    
}

// Shows details of selected issue
function details(e) {
    let target = e.target;
    e.preventDefault();
    let url = "control.php";
    let xhr = new XMLHttpRequest();
    xhr.onload = function() {
        if (xhr.status == 200) {
            document.getElementById("content").innerHTML = xhr.responseText;
        } else {
            document.getElementById("content").innerHTML = "could not load page";
        }
    };
    xhr.open("POST", url);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.send(`details=${target.id}`);
        
}

function isValidPassword(pwd) {
    let pattern = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])[a-zA-Z0-9]{8,}$/;
    if (!pwd.match(pattern)) {
        return false;
    }
    return true;
}

function validName(n) {
    var nameval = /^[a-zA-Z]+$/;  
    return nameval.test(n);
}

function validEmail(e){
    var emailval = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    return emailval.test(e);
}

// Validate and submit dynamically loaded forms
function fsubmit(e){
    e.preventDefault();
    var target = e.target;
    var form = document.getElementsByTagName("form")[0];
    var formData = new FormData(form); 
    var passedValidation = false;
    if (form.name == "new-user"){
        // Validate Add User form data here
        document.querySelector('input[name="firstname"]').value = document.querySelector('input[name="firstname"]').value.charAt(0).toUpperCase() + document.querySelector('input[name="firstname"]').value.slice(1);
        document.querySelector('input[name="lastname"]').value = document.querySelector('input[name="lastname"]').value.charAt(0).toUpperCase() + document.querySelector('input[name="lastname"]').value.slice(1);
         
        let pwd = document.querySelector('input[type="password"]').value;
        let fname = document.querySelector('input[name="firstname"]').value;
        let lname = document.querySelector('input[name="lastname"]').value;
        let email = document.querySelector('input[type="email"]').value;
             
        if (!validName(fname)){
            if (fname == ""){
                alert("First Name invalid! Field Empty");                
            }
            else {
                alert("First Name invalid! Must not contain special characters");
            }          
            return false;
        }     
             
           
        if (!validName(lname)){
            if (lname == ""){
                alert("Last Name invalid! Field Empty");                
            }
            else{
                alert("Last Name invalid! Must not contain special characters");
            }            
            return false;
        }

        if (!isValidPassword(pwd)){
            alert("Password needs to be more than 8 characters");            
            return false;
        }

        if (!validEmail(email)){
            alert("Enter the correct email format");
            return false;
        }

        passedValidation = true;
        if(passedValidation) {
            
            formData.append('formname', 'new-user');
        } else {
            return false; // don't submit if failed any validation test
        }
    }
    
    if (form.name == "user-login"){
        // validate user input
        passedValidation = true;
        if(passedValidation) {
            target.submit();
            return; 
        } else {
            return false; // don't submit if failed any validation test
        }
    }
    
    if (form.name == "new-issue"){
        // Validate New Issue form data here
        passedValidation = true;
        if(passedValidation) {
            formData.append('formname', 'new-issue');
        } else {
            return false; // don't submit if failed any validation test
        }
    }

    // Send off validated form data using AJAX
    var url = "control.php";
    var xhr = new XMLHttpRequest();
    xhr.onload = function() {
        if (xhr.status == 200) {
            document.getElementById("content").innerHTML = xhr.responseText;
        } else {
            document.getElementById("content").innerHTML = "could not load page";
        }
    };
    xhr.open("POST", url);
    xhr.send(formData);
        
}


function closeTicket(e) {
    e.preventDefault();
    var url = "control.php";
    var xhr = new XMLHttpRequest();
    xhr.onload = function() {
        if (xhr.status == 200) {
            const response = xhr.responseText.split("&");
            document.getElementById("ticket-status").innerHTML = response[0];
            document.getElementById("update-time").innerHTML = response[1];
        } else {
            document.getElementById("content").innerHTML = "could not load page";
        }
    };
    xhr.open("POST", url);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.send(`request=mark-closed&ticketId=${document.getElementsByTagName("h4")[0].id}`);
}

function markInProgress(e) {
    e.preventDefault();
    var url = "control.php";
    var xhr = new XMLHttpRequest();
    xhr.onload = function() {
        if (xhr.status == 200) {
            const response = xhr.responseText.split("&");
            document.getElementById("ticket-status").innerHTML = response[0];
            document.getElementById("update-time").innerHTML = response[1];
        } else {
            document.getElementById("content").innerHTML = "could not load page";
        }
    };
    xhr.open("POST", url);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.send(`request=mark-in-progress&ticketId=${document.getElementsByTagName("h4")[0].id}`);
    
}
