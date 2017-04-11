function registerCheck(){
    frm = document.register ;
    resultCheck = ovalidate(frm,"userEmail","req","Please enter email id");
    if(resultCheck == false){
        return resultCheck;
    }
    else{
        resultCheck = ovalidate(frm,"userEmail","email","Please enter proper email id");
        if(resultCheck == false){
            return resultCheck;
        }
    }
    resultCheck = ovalidate(frm,"userPass1","req","Please enter password");
    if(resultCheck == false){
        return resultCheck;
    }
    resultCheck = ovalidate(frm,"userPass2","req","Please enter password");
    if(resultCheck == false){
        return resultCheck;
    }
    var passlength=frm.userPass1.value;
    if(passlength.length<6)
    {
        alert("Please enter password minimum 6 characters");
        frm.userPass1.focus();
        return false;
    }
    if(frm.userPass1.value != frm.userPass2.value)
    {
        alert("Password and Confirm Password didn't Match");
        frm.userPass2.focus();
        return false;
    }
    return true;
}
$(document).ready(function(){
     $("input[name='userEmail']").blur(function() {
        var headval = $("#userEmail").val();
        if(headval != ""){
        var loading="<img src='./assets/img/loading.gif'>";
        $("#emailLoad").html(loading);
        $.ajax({
            type: "GET",
            url: "./ajax/emailcheck.php",
            data: "target="+headval+"&rnd="+String((new Date()).getTime()).replace(/\D/gi,''),
            contentType: "application/json;",
            global: false,
            async: false,
            dataType:"json",
            success: function(getResult) {
                loading="";
                $("#emailLoad").html(loading);
                var resultData = getResult.dataExist;
                if(resultData != 0){
                    errorMsg = headval + " is already registered";
                    $("#emailResult").html(errorMsg);
                    $("#userEmail").val("");
                    $("#userEmail").focus();
                }
            }
        });
    }
    });
});