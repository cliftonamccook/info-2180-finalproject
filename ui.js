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

function isValidName(n) {
    var nameval = /^[a-zA-Z- ']+$/;  
    return nameval.test(n);
}

function isValidEmail(e){
    var emailval = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    return emailval.test(e);
}

function isValidDescription(d) {
    let desc_patt = /^[a-zA-Z0-9_ $%"'?.,+-]+$/;
    return desc_patt.test(d);
}

function isValidTitle(t){
    let title_patt = /^[a-zA-Z- _']+$/;
    return title_patt.test(t);
}

function isValidType(tp){
    if(!(tp === "Bug" || tp === "Task" || tp === "Proposal")){
        return false;
    }
    return true;
}

function isValidPriority(p){
    if(!(p === "Minor" || p === "Major" || p === "Critical")){
        return false;
    }
    return true;
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

        if(pwd=="" && fname=="" && lname=="" && email==""){
            alert("Cannot submit empty form!");
            return false;
        }
             
        if (!isValidName(fname)){
            document.querySelector('input[name="firstname"]').style.backgroundColor = "red";
            document.querySelector('span#fname-error').innerHTML = " *Invalid Firstname";         
            return false;
        }     
        document.querySelector('input[name="firstname"]').style.backgroundColor = "white";
        document.querySelector('span#fname-error').innerHTML = ""; 
           
        if (!isValidName(lname)){
            document.querySelector('input[name="lastname"]').style.backgroundColor = "red";
            document.querySelector('span#lname-error').innerHTML = " *Invalid Lastname";   
            return false;
        }
        document.querySelector('input[name="lastname"]').style.backgroundColor = "white";
        document.querySelector('span#lname-error').innerHTML = ""; 

        if (!isValidPassword(pwd)){
            document.querySelector('input[name="password"]').style.backgroundColor = "red";
            document.querySelector('span#password-error').innerHTML = " *Must be at least 8 characters, at least one lowercase, one uppercase and at least one digit.";           
            return false;
        }
        document.querySelector('input[name="password"]').style.backgroundColor = "white";
        document.querySelector('span#password-error').innerHTML = "";     

        if (!isValidEmail(email)){
            document.querySelector('input[name="email"]').style.backgroundColor = "red";
            document.querySelector('span#email-error').innerHTML = " *Invalid email address";     
            return false;
        }
        document.querySelector('input[name="email"]').style.backgroundColor = "white";
        document.querySelector('span#email-error').innerHTML = "";   

        passedValidation = true;
        if(passedValidation) {
            
            formData.append('formname', 'new-user');
        } else {
            return false; // don't submit if failed any validation test
        }
    }
    
    if (form.name == "user-login"){
        // validate user input
        let patt = /^[a-zA-Z0-9]{8,}$/;
        let email = document.querySelector('input[name="email"]').value;
        let password = document.querySelector('input[name="password"]').value;
        if((email == "") && (password == "")){
            alert("Both fields must be filled out!");
            return false;
        }
        if(!isValidEmail(email)){
            document.querySelector('input[name="email"]').style.backgroundColor = "red";
            document.querySelector('span#email-error').innerHTML = " *Invalid email";
            return false;
        }
        document.querySelector('input[name="email"]').style.backgroundColor = "white";
        document.querySelector('span#email-error').innerHTML = "";

        if (!patt.test(password)){
            document.querySelector('input[name="password"]').style.backgroundColor = "red";
            document.querySelector('span#password-error').innerHTML = " *Invalid password";
            return false;
        }
        document.querySelector('input[name="password"]').style.backgroundColor = "white";
        document.querySelector('span#password-error').innerHTML = "";

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
        let title = document.querySelector('input[name="title"]').value;
        let desc = document.querySelector('textarea[name="description"]').value;
        let at = document.querySelector('input[name="assigned-to"]').value;
        let type = document.querySelector('input[name="type"]').value;
        let priority = document.querySelector('input[name="priority"]').value;

        if(title=="" && desc=="" && at=="" && type=="" && priority==""){
            alert("Cannot submit empty form!");
            return false;
        }

        if(!isValidTitle(title)){
            document.querySelector('input[name="title"]').style.backgroundColor ="red";
            document.querySelector('span#title-error').innerHTML = " *Title can only include characters: a-zA-Z- _'";
            return false;
        }
        document.querySelector('input[name="title"]').style.backgroundColor ="white";
        document.querySelector('span#title-error').innerHTML = "";

        if(!isValidDescription(desc)){
            document.querySelector('textarea[name="description"]').style.backgroundColor ="red";
            document.querySelector('span#description-error').innerHTML = " *Description can only include characters: a-zA-Z0-9_ $%\"'?.,+-";
            return false;
        }
        document.querySelector('textarea[name="description"]').style.backgroundColor ="white";
        document.querySelector('span#description-error').innerHTML = "";

        if(!isValidName(at)){
            document.querySelector('input[name="assigned-to"]').style.backgroundColor ="red";
            document.querySelector('span#assigned-error').innerHTML = " *Please assign a valid user";
            return false;
        }
        document.querySelector('input[name="assigned-to"]').style.backgroundColor ="white";
        document.querySelector('span#assigned-error').innerHTML = "";

        if(!isValidType(type)){
            document.querySelector('input[name="type"]').style.backgroundColor ="red";
            document.querySelector('span#type-error').innerHTML = " *Please select a valid type";
            return false;
        }
        document.querySelector('input[name="type"]').style.backgroundColor ="white";
        document.querySelector('span#type-error').innerHTML = "";

        if(!isValidPriority(priority)){
            document.querySelector('input[name="priority"]').style.backgroundColor ="red";
            document.querySelector('span#priority-error').innerHTML = " *Please select a valid priority";
            return false;
        }
        document.querySelector('input[name="priority"]').style.backgroundColor ="white";
        document.querySelector('span#priority-error').innerHTML = "";

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
