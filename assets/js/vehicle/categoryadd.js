function categoryCheck(){
    frm = document.category ;
    var cname=frm.catName.value;
    resultCheck = ovalidate(frm,"catName","req","Please enter category name.");
    if(resultCheck == false){
        return resultCheck;
    }
    else{
        if(cname > 255){
            alert("maximum 255 characters only allowed");
            frm.catName.focus();
            return false;
        }
    }
    resultCheck = ovalidate(frm,"catDescription","req","Please enter category description");
    if(resultCheck == false){
        return resultCheck;
    }
     return true;
}
function cancel(){
    var pass = "categorymgmt.php?page=" + document.getElementById("page").value;
    location.href=pass;
}