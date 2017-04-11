function loginCheck(){
    frm = document.login ;
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
    resultCheck = ovalidate(frm,"userPass","req","Please enter password");
    if(resultCheck == false){
        return resultCheck;
    }
    return true;
}