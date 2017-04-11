function Ischar(elem)
{
    var alphaExp = /^[a-zA-Z. ]+$/;
    if(elem.match(alphaExp))
        return true;
    else
        return false;
} 
function firstLetter($a) {
    $inta=$a.charCodeAt('0');
    if(($inta>=97&&$inta<=122) || ($inta>=65&&$inta<=90))
        return 1;
    else
        return 0;
}
function IsAlphaNum(elem)
{
    var alphaExp = /^[a-zA-Z.0-9]+$/;
    if(elem.match(alphaExp))
        return true;
    else
        return false;
}
function firstfocus(){
    var bFound = false;
    // for each form
    for (f=0; f < document.forms.length; f++)
    {
        // for each element in each form
        for(i=0; i < document.forms[f].length; i++)
        {
            // if it's not a hidden element
            if (document.forms[f][i].type != "hidden")
            {
                // and it's not disabled
                if (document.forms[f][i].disabled != true)
                {
                    // set the focus to it
                    document.forms[f][i].focus();
                    bFound = true;
                }
            }
            // if found in this element, stop looking
            if (bFound == true)
                break;
        }
        // if found in this form, stop looking
        if (bFound == true)
            break;
    }
}

function ovalidate(frm,itemname,valdfor,strError)
{
    this.formobj = frm;
    var command = valdfor;
    var epos = valdfor.search("=");
    var cmdvalue = "";
    if (epos >= 0)
    {
        command = valdfor.substring(0, epos);
        cmdvalue = valdfor.substr(epos + 1);
    }
    else
    {
        command = valdfor;
    }
    var itemobj = this.formobj[itemname];
    var ret = true;
    switch (command)
    {
        case "req":
        case "required":{
            ret = TestRequiredInput(itemobj,strError);
            break;
        }
        case "num":
        case "numeric":{
            ret = TestRequieredNum(itemobj,strError);
            break;
        }
        case "dec":
        case "decimal":{
            ret = TestRequieredDec(itemobj,strError);
            break;
        }
        case "declt":{
            ret = TestDecLimitChk(itemobj,cmdvalue,strError);
            break;
        }
        case "alpha":{
            ret = TestAlpha(itemobj,strError);
            break;
        }
        case "alphaext":{
            ret = TestAlphaExt(itemobj,strError);
            break;
        }
        case "alphanum":{
            ret = TestAlphaNum(itemobj,strError);
            break;
        }
        case "alphanumext":{
            ret = TestAlphaNumExt(itemobj,strError);
            break;
        }
        case "uname":{
            ret = TestUsername(itemobj,strError);
            break;
        }
        case "passchk":{
            ret = TestPassChk(itemobj,strError);
            break;
        }
        case "maxlength":
        case "maxlen":{
            ret = TestMaxLen(itemobj, cmdvalue,strError)
            break;
        }
        case "minlength":
        case "minlen":{
            ret = TestMinLen(itemobj, cmdvalue,strError)
            break;
        }
        case "email":
        {
            ret = validateEmail(itemobj,strError)
            break;
        }
        case "url":
        {
            ret = validateUrl(itemobj,strError)
            break;
        }
        case "shouldselchk":
        {
            ret = TestShouldSelectChk(itemobj,strError)
            break;
        }
        case "selectradio":
        {
            ret = TestSelectRadio(itemobj,strError);
            break;
        }
        case "selectchk":
        {
            ret = TestSelectChk(itemname,strError);
            break;
        }
        case "getradioval":
        {
            ret = getSelectRadio(itemobj);
            break;
        }
        case "reqfile":
        {
            ret = TestRequiredFile(itemobj,strError);
            break;
        }
    }
    return ret;
}
function TestRequiredFile(itemobj,strError){
    var objValue = itemobj.value;
    if(objValue == ''){
        alert(strError);
        itemobj.focus();
        return false;
    }
    return true;
}
function TestRequiredInput(itemobj,strError){
    itemobj.value = LTrim(itemobj.value);
    var objValue = itemobj.value;
    if(objValue == ''){
        alert(strError);
        itemobj.focus();
        
        return false;
    }
    return true;
}
function TestRequieredNum(itemobj,strError){
    itemobj.value = LTrim(itemobj.value);
    var objValue = itemobj.value;
    if(!ValidateNo(objValue,'1234567890')){
        alert(strError);
        itemobj.focus();
        
        return false;
    }
    return true;
}
function TestRequieredDec(itemobj,strError){
    itemobj.value = LTrim(itemobj.value);
    var objValue = itemobj.value;
    if(!ValidateNo(objValue,'1234567890.')){
        alert(strError);
        itemobj.focus();
        
        return false;
    }
    return true;
}
function TestDecLimitChk(itemobj,decLen,strError){
    var decarr = decLen.split("-");
    var bf = decarr[0];
    var af = decarr[1];
    var alphaExp = new RegExp('^\\d{1,'+bf+'}(\\.\\d{1,'+af+'})?$','g');
    itemobj.value = LTrim(itemobj.value);
    var objValue = itemobj.value;
    if(!objValue.match(alphaExp)){
        alert(strError);
        itemobj.focus();
        
        return false;
    }
    return true;
}
function TestAlpha(itemobj,strError){
    var alphaExp = /^[a-zA-Z]+$/;
    itemobj.value = LTrim(itemobj.value);
    var objValue = itemobj.value;
    if(!objValue.match(alphaExp)){
        alert(strError);
        itemobj.focus();
        
        return false;
    }
    return true;
}
function TestAlphaExt(itemobj,strError){
    var alphaExp = /^[a-zA-Z. ]+$/;
    itemobj.value = LTrim(itemobj.value);
    var objValue = itemobj.value;
    if(!objValue.match(alphaExp)){
        alert(strError);
        itemobj.focus();
        
        return false;
    }
    return true;
}
function TestAlphaNum(itemobj,strError){
    var alphaExp = /^[a-zA-Z0-9]+$/;
    itemobj.value = LTrim(itemobj.value);
    var objValue = itemobj.value;
    if(!objValue.match(alphaExp)){
        alert(strError);
        itemobj.focus();
        
        return false;
    }
    return true;
}
function TestAlphaNumExt(itemobj,strError){
    var alphaExp = /^[a-zA-Z0-9.& ]+$/;
    itemobj.value = LTrim(itemobj.value);
    var objValue = itemobj.value;
    if(!objValue.match(alphaExp)){
        alert(strError);
        itemobj.focus();
        
        return false;
    }
    return true;
}
function TestUsername(itemobj,strError){
    var alphaExp = /^[a-zA-Z0-9._$]+$/;
    itemobj.value = LTrim(itemobj.value);
    var objValue = itemobj.value;
    if(!objValue.match(alphaExp)){
        alert(strError);
        itemobj.focus();
        
        return false;
    }
    return true;
}
function TestPassChk(itemobj,strError){
    var alphaExp = /^[a-zA-Z0-9._$]+$/;
    itemobj.value = LTrim(itemobj.value);
    var objValue = itemobj.value;
    if(!objValue.match(alphaExp)){
        alert(strError);
        itemobj.focus();
        
        return false;
    }
    return true;
}
function TestMaxLen(itemobj, strMaxLen, strError){
    itemobj.value = LTrim(itemobj.value);
    var objValue = itemobj.value;
    if (eval(objValue.length) > eval(strMaxLen))
    {
        alert(strError);
        itemobj.focus();
        
        return false;
    }
    return true;
}
function TestMinLen(itemobj, strMaxLen,strError){
    itemobj.value = LTrim(itemobj.value);
    var objValue = itemobj.value;
    if (eval(objValue.length) < eval(strMaxLen))
    {
        alert(strError);
        itemobj.focus();
        
        return false;
    }
    return true;
}
function validateEmail(itemobj,strError)
{
    itemobj.value = LTrim(itemobj.value);
    var objValue = itemobj.value;
    var reg1 = /(@.*@)|(\.\.)|(@\.)|(\.@)|(^\.)/; // not valid
    var reg2 = /^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,3}|[0-9]{1,3})(\]?)$/; // valid
    if (!reg1.test(objValue) && reg2.test(objValue)){ // if syntax is valid
        
        return true;
    }
    else
    {
        alert(strError);
        itemobj.focus();
        
        return false;
    }
}
function validateUrl(itemobj,strError)
{
    itemobj.value = LTrim(itemobj.value);
    var objValue = itemobj.value;
    var urlregex = new RegExp("^(http:\/\/www.|https:\/\/www.|ftp:\/\/www.|www.){1}([0-9A-Za-z]+\.)");

    if (urlregex.test(objValue)){ // if syntax is valid
        
        return true;
    }
    else
    {
        alert(strError);
        itemobj.focus();
        
        return false;
    }
}
function TestShouldSelectChk(itemobj,strError){
    var objValue = itemobj.value;
    if(objValue == 0 || objValue == ''){
        alert(strError);
        itemobj.focus();
        
        return false;
    }
    return true;
}
function TestSelectRadio(itemobj,strError){
    var objradio = itemobj;
    var selected = false;
    if(typeof objradio.length === 'number') {
        for (var i=objradio.length; i--; ) {
            if (objradio[i].checked) {
                selected = true;
                break;
            }
        }
    }
    else if(objradio.checked){
        selected = true;
    }

    /*for (var r = 0; r < objradio.length; r++)
    {
        if (objradio[r].checked)
        {
            selected = true;
            break;
        }
    }*/
    
    if (selected == false){
        alert(strError);
        
    }
        

    return selected;
}

function TestSelectChk(itemname,strError){
    var chks = document.getElementsByName(itemname);
    var hasChecked = false;
    for (var i = 0; i < chks.length; i++)
    {
        if (chks[i].checked)
        {
            hasChecked = true;
            break;
        }
    }
    if (hasChecked == false){
        alert(strError);
    }

    
    return hasChecked;
}
function getSelectRadio(itemobj){
    var objradio = itemobj;
    var selected = 0;
    for (var r = 0; r < objradio.length; r++)
    {
        if (objradio[r].checked)
        {
            selected = objradio[r].value;
            break;
        }
    }
    return selected;
}

function ValidateNo(NumStr,String){
    for( var Idx = 0; Idx < NumStr.length; Idx ++ )
    {
        var Char = NumStr.charAt( Idx );
        var Match = false;
        for( var Idx1 = 0; Idx1 < String.length; Idx1++)
        {
            if( Char == String.charAt( Idx1 ) )
                Match = true;
        }
        if ( !Match )
            return false;
    }
    return true;
}

function Trim(STRING){
    STRING = LTrim(STRING);
    return RTrim(STRING);
}
function RTrim(STRING){
    while(STRING.charAt((STRING.length -1))==" "){
        STRING = STRING.substring(0,STRING.length-1);
    }
    return STRING;
}
function LTrim(STRING){
    while(STRING.charAt(0)==" "){
        STRING = STRING.replace(STRING.charAt(0),"");
    }
    return STRING;
}

var change_color = "#FFFD9E"
var bgcolor;
function mover(aa) {
    aa.style.backgroundColor = change_color;
}
function mout(aa,getrow) {
    var row = getrow;
    if(row == 'oddrow')
        bgcolor = "#FFFFFF";
    else if(row == 'evenrow')
        bgcolor = "#E0FFE0";

    aa.style.backgroundColor = bgcolor;
}

function InpOnlyNumbers(evt) {
    var e = evt
    var charCode;
    if(window.event){ // IE
        charCode = e.keyCode;
    } else if (e.which) { // Safari 4, Firefox 3.0.4
        charCode = e.which;
    }
    //var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;

    return true;
}
function InpOnlyNumDec(evt) {
    var e = evt
    var charCode;
    if(window.event){ // IE
        charCode = e.keyCode;
    } else if (e.which) { // Safari 4, Firefox 3.0.4
        charCode = e.which;
    }
    //var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57) && (charCode != 46))
        return false;

    return true;
}