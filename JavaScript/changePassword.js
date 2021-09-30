function showPw() {
    var pwd = document.getElementById("currentPwd");
    if (pwd.type === "password") {
        pwd.type = "text";
    } else {
        pwd.type = "password";
    }
  }


