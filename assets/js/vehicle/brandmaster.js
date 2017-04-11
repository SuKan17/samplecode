function brandCheck(){
    frm = document.brandmaster ;
    resultCheck = ovalidate(frm,"brandName","req","Please Enter Brand Name");
    if(resultCheck == false){
        return resultCheck;
    }
    return true;
}
function cancel(){
    location.href='brandmaster.php';
}