/**
 * Create a generic XMLHTTP object for AJAX invocations.
 * I maintain it for didactic purposes, as in the rest of the app
 * I'm using JQuery's  .ajax
 * 
 * @returns {ActiveXObject|XMLHttpRequest|xmlhttp}
 */
function createXMLHttpObject() {
    var xmlthhp;
    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    } else {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    return xmlhttp;
}


/** This function is activated when the user clicks the Update button 
 *  on any ADDEDIT type form.
 * 
 * @param {type} id       the id of the register to update, or 0 if it's an add
 * @param {type} f        the file that performs the update
 * @param {type} fields   Fields to be added or updated to a regist
 * @param {type} usuari   Need to know the user in case it's an issue
 * @returns {null}
 */
function loadXMLgenericAddedit(id,f,fields,usuari)
{    
    usuari =  typeof usuari !== 'undefined' ? usuari : '';

    var xmlhttp = createXMLHttpObject();
    //No ha de fer res en acabar.
/*    xmlhttp.onreadystatechange = function() { 
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            document.getElementById(id).innerHTML=xmlhttp.responseText;
        }
    }
*/                
    // Add the values of the fields for the Update of the Draft
    arr = fields.split('-');
    for(i in arr) 
    {
        field = document.getElementById(arr[i]);
        if (field) {  //Have to check if <input> fields exists or not (in short form)
            field_name = arr[i];
            // Boolean Field ?
            if (field_name.substring(0,5)=="bool_") 
            {
                if (field.checked)
                    f += "&"+field_name+"=1";
                else
                    f += "&"+field_name+"=0";
            }
            // Non boolean field
            else {
                if (field_name=="fkey_state") {
                    if (field.checked) {
                        if (usuari=="admin")
                            f += "&"+field_name+"=2"; //Tancada per admin
                        else
                           f += "&"+field_name+"=3";//Tancada per user
                    } else
                         f += "&"+field_name+"=1";  //Oberta
                } else {
                    if (document.getElementById(arr[i]).value)
                        f += "&"+field_name+"="+document.getElementById(field_name).value;              
                }
            }
        }
    }
    
    f += "&fields="+fields;
    
    //Show Loading Message
    $('#updating').show();
    var async=false;
    
    //Send the AJAX request, with async= false to allow showing and hiding the message
    xmlhttp.open("GET",f,async);
    xmlhttp.send();
    
    //Hide Loading Message
    $('#updating').hide();
}



//Load images JavaScript code
$('form#upload').submit( function()
{
    //Hide Buttons
    $('#upload_wrapper').hide();
    //Show Loading Message
    $('#loading').show();

    //If the <iframe> has been loaded with data (the <div> 'image')
    $('#upload_target').unbind().load( function()
    {
        //Get the image file name.
        var img = $('#upload_target').contents().find('#image').html();
        //Hide Loading Message
        $('#loading').hide();
        // Load Image Preview
        if (img) {
            $('#preview').show();
            //I should pass the Directory where I want to store the images to.
            $('#preview').attr('src', 'upload/'+img);
            $('#image_wrapper').show();

            //Show the name of the value in the Input Field 'image'
            document.getElementById("image").value = img;
        }
    });
}); 