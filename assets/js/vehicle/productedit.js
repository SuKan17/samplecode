function productCheck(){
    frm = document.product;
    var cname = frm.productName.value;
    resultCheck = ovalidate(frm,"productName","req","Please enter product name.");
    if(resultCheck == false){
        return resultCheck;
    }
    else{
        if(cname > 255){
            alert("maximum 255 characters only allowed");
            frm.productName.focus();
            return false;
        }
    }
    resultCheck = ovalidate(frm,"productPrice","req","Please enter product price");
    if(resultCheck == false){
        return resultCheck;
    }
    else{
        resultCheck = ovalidate(frm,"productPrice","decimal","Please enter valid price");
        if(resultCheck == false){
            return resultCheck;
        }
    }
    resultCheck = ovalidate(frm,"availableStockQty","req","Please enter available stock quantity");
    if(resultCheck == false){
        return resultCheck;
    }
    else{
        resultCheck = ovalidate(frm,"availableStockQty","num","Please enter numbers");
        if(resultCheck == false){
            return resultCheck;
        }
    }
    resultCheck = ovalidate(frm,"productDescription","req","Please enter product description");
    if(resultCheck == false){
        return resultCheck;
    }
     return true;
}
function cancel(){
    var pass = "productmgmt.php?page=" + document.getElementById("page").value;
    location.href=pass;
}
$(document).ready(function(){
     $("#brandId").change(function() {
        var loading="<img src='./assets/img/loading.gif'>";
        var headval = $("#brandId").val();
        $("#modelLoad").html(loading);
        $.ajax({
            type: "GET",
            url: "./ajax/modellist.php",
            data: "target="+headval+"&rnd="+String((new Date()).getTime()).replace(/\D/gi,''),
            contentType: "application/json;",
            global: false,
            async: false,
            dataType:"json",
            success: function(getResult) {
                loading="";
                $("#modelLoad").html(loading);
                $("#modelId option").remove(); // Remove all <option> child tags.
                $("#modelId").append("<option value=0> - - Model Name Not Specific - -");
                $.each(getResult, function(index, item) { // Iterates through a collection
                    $("#modelId").append( // Append an object to the inside of the select box
                        $("<option></option>") // Yes you can do this.
                            .text(item.modelName)
                            .val(item.modelId)
                    );
                });
            }
        });
    });
});