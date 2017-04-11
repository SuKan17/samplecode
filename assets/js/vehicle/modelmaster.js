function modelCheck(){
    frm = document.modelmaster;
    var resultCheck = ovalidate(frm,"brandId","shouldselchk","Please Select Brand Name");
     if( resultCheck == false){
         return resultCheck;
     }
    resultCheck = ovalidate(frm,"modelName","req","Please Enter Model Name");
    if(resultCheck == false){
        return resultCheck;
    }
    return true;
}
function cancel(){
    var pass = "modelmaster.php?bd=" + document.getElementById("bd").value;
    location.href=pass;
}